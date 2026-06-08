<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 *
 * @return array<string, array{    // Import name as key, description of the imported file as value
 *     path: string,               // Logical, relative or absolute path to the file
 *     type?: 'js'|'css'|'json',   // Type of the file, defaults to 'js'
 *     entrypoint?: bool,          // Whether the file is an entrypoint, for 'js' only
 * }|array{
 *     version: string,            // Version of the remote package
 *     package_specifier?: string, // Remote "package-name/path" specifier, defaults to the import name
 *     type?: 'js'|'css'|'json',
 *     entrypoint?: bool,
 * }>
 */
return [
    'app' => ['path' => './assets/app.js', 'entrypoint' => true],
    '@hotwired/stimulus' => ['version' => '3.2.2'],
    'bootstrap' => ['version' => '5.3.3'],
    '@popperjs/core' => ['version' => '2.11.8'],
    'bootstrap/dist/css/bootstrap.min.css' => ['version' => '5.3.3', 'type' => 'css'],
    '@symfony/stimulus-bundle' => ['path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js'],
    'twig' => ['version' => '1.17.1'],
    'locutus/php/strings/sprintf' => ['version' => '2.0.16'],
    'locutus/php/strings/vsprintf' => ['version' => '2.0.16'],
    'locutus/php/math/round' => ['version' => '2.0.16'],
    'locutus/php/math/max' => ['version' => '2.0.16'],
    'locutus/php/math/min' => ['version' => '2.0.16'],
    'locutus/php/strings/strip_tags' => ['version' => '2.0.16'],
    'locutus/php/datetime/strtotime' => ['version' => '2.0.16'],
    'locutus/php/datetime/date' => ['version' => '2.0.16'],
    'locutus/php/var/boolval' => ['version' => '2.0.16'],
    'axios' => ['version' => '1.6.7'],
    'fos-routing' => ['version' => '0.0.6'],
    'perfect-scrollbar' => ['version' => '1.5.5'],
    'perfect-scrollbar/css/perfect-scrollbar.min.css' => ['version' => '1.5.5', 'type' => 'css'],
    'datatables.net-plugins/i18n/en-GB.mjs' => ['version' => '2.0.8'],
    'datatables.net-bs5' => ['version' => '2.1.3'],
    'jquery' => ['version' => '3.7.1'],
    'datatables.net' => ['version' => '2.1.3'],
    'datatables.net-bs5/css/dataTables.bootstrap5.min.css' => ['version' => '2.1.3', 'type' => 'css'],
    'datatables.net-buttons-bs5' => ['version' => '3.1.1'],
    'datatables.net-buttons' => ['version' => '3.1.1'],
    'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css' => ['version' => '3.1.1', 'type' => 'css'],
    'datatables.net-responsive-bs5' => ['version' => '3.0.2'],
    'datatables.net-responsive' => ['version' => '3.0.2'],
    'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css' => ['version' => '3.0.2', 'type' => 'css'],
    'datatables.net-scroller-bs5' => ['version' => '2.4.3'],
    'datatables.net-scroller' => ['version' => '2.4.3'],
    'datatables.net-scroller-bs5/css/scroller.bootstrap5.min.css' => ['version' => '2.4.3', 'type' => 'css'],
    'datatables.net-searchpanes-bs5' => ['version' => '2.3.0'],
    'datatables.net-searchpanes' => ['version' => '2.3.0'],
    'datatables.net-searchpanes-bs5/css/searchPanes.bootstrap5.min.css' => ['version' => '2.3.0', 'type' => 'css'],
    'datatables.net-searchbuilder-bs5' => ['version' => '1.7.0'],
    'datatables.net-searchbuilder' => ['version' => '1.7.0'],
    'datatables.net-searchbuilder-bs5/css/searchBuilder.bootstrap5.min.css' => ['version' => '1.7.0', 'type' => 'css'],
    'datatables.net-select-bs5' => ['version' => '2.0.4'],
    'datatables.net-select' => ['version' => '2.0.4'],
    'datatables.net-select-bs5/css/select.bootstrap5.min.css' => ['version' => '2.0.4', 'type' => 'css'],
    'preact' => ['version' => '10.19.5'],
    'preact/hooks' => ['version' => '10.19.5'],
    'preact/jsx-runtime' => ['version' => '10.19.5'],
    'preact/compat' => ['version' => '10.19.5'],
    'preact/compat/client' => ['version' => '10.19.5'],
    'temporal-polyfill/fns/zoneddatetime' => ['version' => '0.3.2'],
    'temporal-polyfill/fns/plaindatetime' => ['version' => '0.3.2'],
    'temporal-polyfill/fns/instant' => ['version' => '0.3.2'],
    '@full-ui/headless-calendar' => ['version' => '7.0.0-beta.8'],
    'fullcalendar' => ['version' => '7.0.0-beta.8'],
    'fullcalendar/themes/classic' => ['version' => '7.0.0-beta.8'],
    'fullcalendar/interaction' => ['version' => '7.0.0-beta.8'],
    'fullcalendar/daygrid' => ['version' => '7.0.0-beta.8'],
    'fullcalendar/timegrid' => ['version' => '7.0.0-beta.8'],
    'fullcalendar/list' => ['version' => '7.0.0-beta.8'],
];
