# Community Calendar

A multi-organization calendar system for communities to share their calendars.

Individuals can also belong to calendars, including private ones if given permissions.

## Quick Start

```bash
git clone git@github.com:survos/calendar-bundle-demo.git
cd calendar-bundle-demo/
composer install
bin/console d:database:create
bin/console d:schema:update --force --complete
bin/console doctrine:fixtures:load -n

# 
symfony proxy:domain:attach calendar-demo
symfony server:start -d

# OR php -S localhost:8300 -t public/
```



## Workflow 

* Individuals sign in ("users")
* Create/Join an Organization
* Organization Admins can 
    * Link an iCal feed
    * Import an iCal (.ics) file, e.g. created from RunMyVillage
    * Create/Edit a Calendar that resides on ccal.
* Individuals can
  * Subscribe to iCal feeds, either in their ccal account or Google account

## Entities

* Org
   * Cal
     * Event
   * Feed
     * Booking (ICS Event)

https://github.com/tattali/CalendarBundle/blob/master/src/Resources/doc/multi-calendar.md

## Sample feeds

* http://www.mysportscal.com/download-major-league-schedules/mlb/
* https://www.officeholidays.com/subscribe/usa

## Tools

old: https://github.com/Graceas/php-ics-reader
in use: https://github.com/u01jmg3/ics-parser

to write iCS:

(seems to be more current)
https://github.com/spatie/icalendar-generator

(funky DI)
https://github.com/jasvrcek/ICS and https://github.com/jasvrcek/IcsBundle
"jsvrcek/ics-bundle": "dev-tac",
"ics_bundle": {
"type": "vcs",
"url": "git@github.com:tacman/IcsBundle.git"
},


