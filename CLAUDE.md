# CLAUDE.md

Guidance for Claude Code working in this repo.

## What this is

The website of **FK Železničar Niš** (football club) — a client site built on the
reusable **site-core** (Laravel backend + Filament admin, same base as the
`d1centar-media` repo). Priority of the site: **youth academy and enrolment**
("Upiši se" is the primary CTA on every page), then first team, news/video,
"Podrži klub" (bank-transfer support page), contact.

**Stack:** Laravel 12 · Filament v4 (admin) · **SQLite** · Blade + Tailwind v4 · PHP 8.2+.

## Core decisions (don't relitigate)

- **SQLite is the database, on purpose.** One file `database/database.sqlite`.
  Tests run on SQLite in memory (`phpunit.xml`).
- **Filament for the admin.** Never hand-roll admin UI. New admin work goes in
  `app/Filament/Resources/<Name>/{Resource,Schemas/Form,Tables/Table,Pages}`.
- **Admin path is secret & per-client:** `config('site.admin_path')` from
  `ADMIN_PATH`. Never hardcode `/admin` outside tests.
- **Two roles:** `admin` (everything) and `editor` (content only — no Users/Settings).
- **No online payments.** "Podrži klub" only shows bank details from Settings.
- **Matches are stored from the club's perspective** (`opponent`, `is_home`,
  `our_score`, `their_score`); `FootballMatch` accessors derive home/away display.
- **Admin UI is Serbian** (labels in resources; Filament core strings via `sr_Latn`).
  Frontend is bilingual: `resources/lang/{sr,en}/club.php`.

## Data model (`app/Models/`)

Site-core: `Post` (news; a YouTube/Vimeo `video_url` makes it a Video item),
`Category`, `ContactMessage`, `Setting` (key/value, exposed to all views as
`$site`), `User`.
Club: `Player`, `StaffMember` (department: first_team / youth / club, optional
`youth_selection_id`), `YouthSelection` (age groups), `FootballMatch` (table
`matches`), `StandingRow` (league table rows, `competition` key, default `first`),
`Photo` (gallery albums), `YouthApplication` (enrolment inbox with status).
`StandingRow` and first-team `FootballMatch` rows are fed by `fsn:sync`
(`app/Console/Commands/FsnSync.php` + `app/Services/FsnLeagueParser.php`, source in
`config('site.fsn')`), scheduled weekly (Sunday 23:00) in `routes/console.php`.

Migrations: `2026_07_20_…site_content_tables` (core) and `2026_09_05_…club_tables`.
Seeding: `DatabaseSeeder` (admin, categories, settings) + `ZeleznicarDemoSeeder`
(placeholder squad/fixtures/news, gated by `SEED_DEMO`).

## Commands

```bash
./start.sh                 # install + build + migrate + seed + serve → :8000
php artisan migrate --seed # admin user, categories, settings (+ demo when SEED_DEMO=true)
php artisan test           # feature tests (SQLite :memory:)
./check-backend.sh         # smoke-test every public route, exit 0 if healthy
php artisan fsn:sync       # league table + first-team fixtures from fsn.org.rs (--dry-run to preview)
npm run build              # Vite/Tailwind build (required before serving/testing views)
```

## Frontend

- Layout: `resources/views/components/site-layout.blade.php` (header with
  ticker, nav, CTA buttons, mobile menu, footer, sticky mobile CTA bar).
- Components: `post-card`, `player-card`, `staff-card`, `match-card`, `match-row`,
  `standings-table`, `section-heading`, `cta-band`, `flash`.
- Pages: `home`, `news/{index,show}`, `video/index`, `team/index`, `youth/index`
  (enrolment form at `#upis`), `support/index`, `contact/index`.
- Design tokens: `@theme` in `resources/css/app.css` (navy / red / gold / surface / ink),
  fonts Barlow Condensed + Barlow. Utility classes: `.display`, `.kicker`, `.btn*`,
  `.rails`, `.slant-top`, `.film`, `.score`, `.form-pill`, `.field`.
- JS (`resources/js/app.js`): mobile menu, reveal-on-scroll, match countdown,
  copy-to-clipboard, tabs, flash toast. No framework.

## Conventions

- News URLs stay unprefixed (`/vesti/{slug}`); other pages are locale-prefixed (`/{sr|en}/…`).
- Text the club edits per season goes in **Settings** (`ManageSettings`), not in code.
- Match existing code style; tests must pass (`php artisan test`) before a change is done.
