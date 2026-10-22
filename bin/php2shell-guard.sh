#!/usr/bin/env bash
# PHP2Shell guard (CVE-2026-109618 / CVE-2026-109619, RCE bez autentifikacije u samom PHP-u).
# Pokreće ga deploy skripta kao root, pre svega ostalog; može i ručno:
#   sudo bash bin/php2shell-guard.sh            # nadogradi, zaštiti, proveri
#   sudo bash bin/php2shell-guard.sh --check    # samo proveri, ništa ne menja
#
# Šta radi:
#   1. nadogradi sve instalirane php* pakete (apt, samo --only-upgrade, konfiguracija ostaje),
#   2. upiše server-wide ublažavanje `session.upload_progress.enabled = Off` u conf.d
#      svake PHP verzije (zatvara 3 od 5 poznatih putanja napada; nije zamena za zakrpu),
#   3. restartuje php-fpm koji i dalje vrti stari (obrisani) binarni fajl,
#   4. proveri da je svaki php-fpm zakrpljen: upstream >= 8.2.35 / 8.3.36 / 8.4.27 / 8.5.12,
#      ili distro backport (Ubuntu/Debian ostaju na staroj verziji, npr. 8.3.6-0ubuntu0.24.04.13,
#      pa se gleda changelog paketa) - inače deploy PADA,
#   5. upozori na PHP fajlove u public/ i storage/ kojih tamo ne bi trebalo da bude (webshell).
#
# PHP2SHELL_ALLOW_VULNERABLE=1 pušta deploy dalje i kad PHP nije zakrpljen (samo u nuždi).
set -euo pipefail

APP="$(cd "$(dirname "$0")/.." && pwd)"
CHECK_ONLY=0
[ "${1:-}" = "--check" ] && CHECK_ONLY=1

# Prva zakrpljena upstream verzija po grani.
fixed_for() {
    case "$1" in
        8.2) echo 8.2.35 ;;
        8.3) echo 8.3.36 ;;
        8.4) echo 8.4.27 ;;
        8.5) echo 8.5.12 ;;
        *) echo "" ;;   # 8.1 i starije: nema upstream izdanja, samo distro backport
    esac
}

version_ge() { [ "$(printf '%s\n%s\n' "$2" "$1" | sort -V | head -1)" = "$2" ]; }

if ! command -v dpkg-query >/dev/null; then
    echo "php2shell-guard: nije Debian/Ubuntu server, proveri PHP ručno (treba >= 8.3.36 itd.)." >&2
    exit 0
fi

# ---------- 1. Nadogradnja ----------
if [ "$CHECK_ONLY" = 0 ]; then
    mapfile -t PKGS < <(dpkg-query -W -f='${db:Status-Abbrev} ${binary:Package}\n' 'php*' 2>/dev/null | awk '$1=="ii" {print $2}')
    if [ "${#PKGS[@]}" -gt 0 ]; then
        echo "==> php2shell: apt nadogradnja PHP paketa (${#PKGS[@]})"
        if timeout 300 apt-get -o DPkg::Lock::Timeout=120 update -qq; then
            DEBIAN_FRONTEND=noninteractive timeout 900 apt-get -o DPkg::Lock::Timeout=120 \
                -o Dpkg::Options::=--force-confold install -y -qq --only-upgrade "${PKGS[@]}" \
                || echo "   ⚠️  apt nadogradnja nije uspela - provera ispod će reći da li je PHP ipak zakrpljen."
        else
            echo "   ⚠️  apt-get update nije uspeo - preskačem nadogradnju."
        fi
    fi
fi

# ---------- 2. Ublažavanje (server-wide, ne zamenjuje zakrpu) ----------
INI_CHANGED=0
if [ "$CHECK_ONLY" = 0 ]; then
    for dir in /etc/php/*/fpm/conf.d /etc/php/*/cli/conf.d; do
        [ -d "$dir" ] || continue
        ver="$(echo "$dir" | cut -d/ -f4)"
        want="$(
            echo "; PHP2Shell (CVE-2026-109618 / CVE-2026-109619) - upisuje php2shell-guard.sh"
            echo "session.upload_progress.enabled = Off"
            # Na 8.5 je fatal_error_backtraces potvrđen izvor curenja memorije.
            if [ "$ver" = "8.5" ]; then echo "fatal_error_backtraces = Off"; fi
        )"
        if [ "$(cat "$dir/99-php2shell.ini" 2>/dev/null)" != "$want" ]; then
            printf '%s\n' "$want" > "$dir/99-php2shell.ini"
            INI_CHANGED=1
        fi
    done
fi

# ---------- 3. php-fpm mora da vrti novi binarni fajl ----------
VULNERABLE=0
mapfile -t UNITS < <(systemctl list-units --no-legend --type=service 'php*-fpm.service' 2>/dev/null | awk '{print $1}')
for unit in "${UNITS[@]}"; do
    pid="$(systemctl show -p MainPID --value "$unit" 2>/dev/null || echo 0)"
    [ "$pid" -gt 0 ] 2>/dev/null || continue
    # Posle nadogradnje stari proces radi iz obrisanog fajla; restart ga zamenjuje
    # (i učitava 99-php2shell.ini).
    stale=0
    readlink "/proc/$pid/exe" 2>/dev/null | grep -q '(deleted)' && stale=1
    if [ "$CHECK_ONLY" = 0 ] && { [ "$stale" = 1 ] || [ "$INI_CHANGED" = 1 ]; }; then
        echo "==> php2shell: restart $unit"
        systemctl restart "$unit"
    elif [ "$stale" = 1 ]; then
        echo "   ⚠️  $unit još vrti stari PHP iz memorije - treba: systemctl restart $unit" >&2
        VULNERABLE=1
    fi
done

# ---------- 4. Provera ----------
FOUND=0
for bin in /usr/sbin/php-fpm[0-9]*; do
    [ -x "$bin" ] || continue
    FOUND=1
    ver="${bin#/usr/sbin/php-fpm}"
    full="$("$bin" -v 2>/dev/null | head -1 | awk '{print $2}')"
    pkgver="$(dpkg-query -W -f='${Version}' "php$ver-fpm" 2>/dev/null || echo '?')"
    fixed="$(fixed_for "$ver")"
    changelog="/usr/share/doc/php$ver-fpm/changelog.Debian.gz"
    [ -f "$changelog" ] || changelog="/usr/share/doc/php$ver-common/changelog.Debian.gz"
    if [ -n "$fixed" ] && [ -n "$full" ] && version_ge "${full%%-*}" "$fixed"; then
        echo "   ✅ php-fpm$ver $full (paket $pkgver) - zakrpljen (>= $fixed)"
    elif [ -f "$changelog" ] && zgrep -q 'CVE-2026-109618' "$changelog" && zgrep -q 'CVE-2026-109619' "$changelog"; then
        echo "   ✅ php-fpm$ver $full (paket $pkgver) - distro backport, changelog navodi oba CVE-a"
    else
        echo "   ❌ php-fpm$ver $full (paket $pkgver) je RANJIV na PHP2Shell (treba ${fixed:-distro backport})" >&2
        VULNERABLE=1
    fi
done
[ "$FOUND" = 1 ] || echo "   ⚠️  php-fpm nije pronađen u /usr/sbin - proveri ručno koji PHP služi sajt."

# ---------- 5. Tragovi upada ----------
# Laravel u public/ ima samo index.php; u storage/ PHP fajlovi žive samo u framework/views.
SUSPECT="$(find "$APP/public" "$APP/storage" -type f \( -iname '*.php*' -o -iname '*.phtml' -o -iname '*.phar' \) \
    ! -path "$APP/public/index.php" ! -path "$APP/storage/framework/views/*" 2>/dev/null || true)"
if [ -n "$SUSPECT" ]; then
    echo "   ⚠️  PHP fajlovi kojima tu nije mesto (mogući webshell, pregledaj ih):" >&2
    echo "$SUSPECT" | sed 's/^/      /' >&2
fi

if [ "$VULNERABLE" = 1 ]; then
    if [ "${PHP2SHELL_ALLOW_VULNERABLE:-0}" = 1 ]; then
        echo "   ⚠️  PHP2SHELL_ALLOW_VULNERABLE=1 - deploy ide dalje na RANJIVOM PHP-u." >&2
    else
        echo "php2shell-guard: PHP nije zakrpljen. Ručno: apt update && apt install --only-upgrade 'php8.3*'" >&2
        echo "  (ili ugasi sajt dok se ne zakrpi). Deploy staje." >&2
        exit 1
    fi
fi
echo "==> php2shell: OK"
