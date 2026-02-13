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
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'admin' => [
        'path' => './assets/admin.js',
        'entrypoint' => true,
    ],
    '@splidejs/splide' => [
        'version' => '4.1.4',
    ],
    '@splidejs/splide/dist/css/splide.min.css' => [
        'version' => '4.1.4',
        'type' => 'css',
    ],
    '@splidejs/splide-extension-auto-scroll' => [
        'version' => '0.5.3',
    ],
    'datatables.net-dt' => [
        'version' => '2.3.6',
    ],
    'jquery' => [
        'version' => '4.0.0',
    ],
    'datatables.net' => [
        'version' => '2.3.6',
    ],
    'datatables.net-dt/css/dataTables.dataTables.min.css' => [
        'version' => '2.3.6',
        'type' => 'css',
    ],
    'datatables.net-rowreorder-dt' => [
        'version' => '1.5.1',
    ],
    'datatables.net-rowreorder' => [
        'version' => '1.5.1',
    ],
    'datatables.net-rowreorder-dt/css/rowReorder.dataTables.min.css' => [
        'version' => '1.5.1',
        'type' => 'css',
    ],
];
