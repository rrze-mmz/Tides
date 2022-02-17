<?php

return [
    'base_uri'         => env('OPENCAST_ADMIN_URL', 'localhost:8080'),
    'digest_user'      => env('OPENCAST_DIGEST_USER', 'admin'),
    'digest_pass'      => env('OPENCAST_DIGEST_PASS', 'opencast'),
    'archive_path'     => env('OPENCAST_ARCHIVE_PATH', 'archive/mh_default_org'),
    'default_theme_id' => env('OPENCAST_DEFAULT_THEME_ID', 551),
];
