# FK Železničar Niš — sajt kluba

Zvanični sajt fudbalskog kluba sa admin panelom (Filament v4). Napravljen po
principu **site-core** šablona (kao d1centar): Laravel monolit, SQLite, sadržaj
se uređuje kroz admin, frontend je Blade + Tailwind.

**Fokus sajta je omladinska škola i upis novih igrača** — dugme „Upiši se“ je
vidljivo na svakoj strani (header, footer, mobilna traka, CTA sekcije), a
„Podrži klub“ je sekundarni poziv na akciju.

## Brzi start

```bash
./start.sh              # http://127.0.0.1:8000  (prvi put: install + build + migrate + seed)
PORT=8080 ./start.sh    # na drugom portu
```

Skripta sama odradi `composer install`, `npm run build`, migracije, seed i
`storage:link` ako nešto od toga nedostaje.

## Provera

```bash
./check-backend.sh          # preduslovi, baza, sve javne rute
./check-backend.sh --full   # + kompletan test suite
php artisan test            # feature testovi (SQLite u memoriji)
```

## Admin panel

| | |
|---|---|
| URL | `http://127.0.0.1:8000/admin` (menja se preko `ADMIN_PATH` u `.env`) |
| Email | iz `SEED_ADMIN_EMAIL` (podrazumevano `admin@example.com`) |
| Lozinka | iz `SEED_ADMIN_PASSWORD` (podrazumevano `change-me` u `.env.example`) |

> ⚠ **Odmah promeni lozinku** (Admin → Sistem → Korisnici).

**Uloge:** `admin` (sve) i `editor` (samo sadržaj — ne vidi Korisnike ni Podešavanja).

### Šta se uređuje u adminu

| Grupa | Resurs | Šta radi |
|---|---|---|
| Sadržaj | **Vesti** | rich-text editor, naslovna slika, YouTube/Vimeo link (auto-embed → i u sekciji Video), kategorija, nacrt/objavljeno + zakazivanje, SEO polja, „istakni na početnoj“ |
| | **Kategorije vesti** | Prvi tim, Omladinci, Klub, Utakmice… (kategorija `prvi-tim` puni vesti na strani prvog tima, `omladinci` na strani omladinaca) |
| | **Galerija** | fotografije po albumima (Prvi tim / Omladinci / Klub) |
| Prvi tim | **Igrači** | broj, pozicija, datum rođenja, visina, fotografija, kapiten, „iz omladinske škole“, redosled prevlačenjem |
| | **Stručni štab i treneri** | prvi tim / omladinci (vezano za selekciju) / uprava, licenca |
| | **Utakmice i rezultati** | protivnik, dom/gost, takmičenje, kolo, termin, rezultat, status, veza na vest-izveštaj |
| | **Tabela** | redovi tabele — brojevi se menjaju direktno u tabeli, naš klub istaknut |
| Omladinci | **Selekcije** | U-7 … U-19: godišta, opis, termini i mesto treninga, „prima nove igrače“ |
| Prijave | **Upis omladinaca** | prijave sa forme; status (nova / kontaktirani / upisan / odbijena), interna beleška, poziv / imejl jednim klikom, badge sa brojem novih |
| | **Poruke sa sajta** | kontakt forma |
| Sistem | **Korisnici**, **Podešavanja sajta** | identitet kluba, hero početne strane, tekstovi za omladince i upis, podaci za uplatu (Podrži klub), kontakt, društvene mreže, SEO |

Dashboard prikazuje: nove prijave, nepročitane poruke, sledeću utakmicu, broj
vesti, igrača i selekcija.

## Struktura sajta

| Ruta | Strana |
|---|---|
| `/{sr,en}` | Početna: hero + match centar (sledeća utakmica sa odbrojavanjem, poslednji rezultat, pozicija na tabeli), vesti, video, prvi tim, omladinci, Upis / Podrži klub, mreže |
| `/{sr,en}/prvi-tim` | Igrači po pozicijama, stručni štab, raspored / rezultati (tabovi), tabela, galerija, vesti prvog tima |
| `/{sr,en}/omladinci` | Kako izgleda upis, selekcije po uzrastima, treneri, treninzi, galerija/video, **forma za upis** (`#upis`) |
| `/{sr,en}/podrzi-klub` | Podaci za uplatu (kopiranje jednim klikom), gde ide novac, sponzorstvo |
| `/{sr,en}/kontakt` | Kontakt forma, podaci kluba, mapa, mreže |
| `/vesti`, `/vesti/{slug}` | Vesti (bez jezičkog prefiksa zbog stabilnih SEO adresa) |
| `/video` | Sve vesti koje nose YouTube/Vimeo link |
| `/sitemap.xml` | Sitemap |
| `/api/v1/*` | Read-only JSON (settings, posts, squad, matches, standings, youth) + `POST contact`, `POST enrol` — za mobilnu aplikaciju ili drugi frontend |

Forme (`POST /upis`, `POST /contact`) imaju honeypot i rate limit, čuvaju unos
u admin i šalju imejl klubu + potvrdu pošiljaocu (`MAIL_*` u `.env`).

## Dizajn

Tokeni su u `resources/css/app.css` (`@theme` blok): teget grba, crvena zvezda,
zlatna krila; fontovi Barlow Condensed (naslovi) + Barlow (tekst). Prepoznatljivi
elementi: „šine“ kao razdelnik sekcija, kose ivice tamnih sekcija, veliki
outline natpis kluba, kartice igrača sa brojem u pozadini, match kartice sa
velikim rezultatom i odbrojavanjem.

## Ručno pokretanje

```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed       # admin, kategorije, podešavanja + demo sadržaj (SEED_DEMO=true)
php artisan storage:link
npm install && npm run build
php artisan serve
```

Demo sadržaj (igrači, štab, selekcije, utakmice, tabela, vesti) je izmišljen
placeholder — zameni ga u adminu. Za prazan sajt postavi `SEED_DEMO=false` pre
seed-a.

## Produkcija

- `.env`: `APP_ENV=production`, `APP_DEBUG=false`, tačan `APP_URL`, nasumičan
  `ADMIN_PATH`, pravi `MAIL_*`, jaka `SEED_ADMIN_PASSWORD`.
- `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- Backup = kopija `database/database.sqlite` + `storage/app/public`.
