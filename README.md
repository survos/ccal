# ccal — Community Calendar Aggregator

A multi-organization community calendar. Organizations link iCal feeds (or create
calendars natively); the events are aggregated, color-coded, and rendered with
FullCalendar. Individuals can sign in and subscribe to the calendars they care about.

Rendering is provided by [`survos/ux-calendar-bundle`](https://github.com/survos/ux-calendar-bundle)
(FullCalendar v7 + AssetMapper + Stimulus). ccal is the app around it: the org/feed
data model, moderation, accounts, and ingestion.

Symfony 8 · PHP 8.4 · AssetMapper (no Webpack/yarn).

## Quick start

```bash
git clone git@github.com:survos/ccal.git
cd ccal
composer install
php bin/console importmap:install          # download JS assets (AssetMapper)
php bin/console doctrine:schema:update --force --complete
php bin/console app:load-demo-feeds        # load the sample Rappahannock feeds
php bin/console doctrine:fixtures:load -n   # optional

# serve it
symfony server:start -d
# OR
php -S 127.0.0.1:8124 -t public
```

Then open the homepage — the aggregated, color-coded calendar with a per-calendar
toggle legend.

## Workflow

* Individuals sign in ("users").
* Create/Join an Organization.
* Organization admins can:
    * link an iCal feed,
    * import an iCal (`.ics`) file,
    * create/edit a calendar that lives natively on ccal.
* Individuals subscribe to the feeds they want; subscribed calendars are shown by default.

## Data model

```
Org ──┬── Cal ──── Event      (calendars created natively on ccal)
      └── Feed ─── Booking     (events imported from an external iCal feed)
User                           (+ a Symfony Workflow on Feed for moderation)
```

## How aggregation works

`App\EventSource\DatabaseEventSource` (implements the bundle's `EventSourceInterface`)
reads every `Feed`, fetches its ICS, and tags each event with the feed's slug + color.
It is auto-registered into the bundle's `EventSourceRegistry` — the bundle does a
`registerForAutoconfiguration(EventSourceInterface::class)`, so no `services.yaml`
wiring is needed. The `/ux-calendar/events` feed endpoint serves the merged JSON to
FullCalendar.

`bin/console app:load-demo-feeds` reads the demo calendar list straight from the
installed bundle (`vendor/survos/ux-calendar-bundle/demo/...`) and upserts `Feed` rows.

## Tools

* iCal parsing: [`johngrogg/ics-parser`](https://github.com/u01jmg3/ics-parser)
* iCal generation: [`spatie/icalendar-generator`](https://github.com/spatie/icalendar-generator)

## Deployment (Dokku)

Uses [`survos/deployment-bundle`](https://github.com/survos/deployment-bundle) and an
`app.json` (Postgres addon + AssetMapper compile + schema update on predeploy):

```bash
bin/console dokku bootstrap --force        # create app + remote + scaffold
ssh dokku@ssh.survos.com postgres:create ccal-db && ssh dokku@ssh.survos.com postgres:link ccal-db ccal
bin/console dokku config APP_ENV=prod APP_SECRET=$(openssl rand -hex 16) --force
bin/console dokku deploy
```
