<?php

use PHPStan\Rules\TipRuleError;

return [
    'stream_url'        => env('WOWZA_ENGINE_URL', 'localhost:1935'),
    'vod_url'           => env('WOWZA_VOD_URL', 'localhost:1935/vod/_definst_/'),
    'base_uri'          => env('WOWZA_API_URL', 'localhost:8087'),
    'digest_user'       => env('WOWZA_DIGEST_USER', 'admin'),
    'digest_pass'       => env('WOWZA_DIGEST_PASS', 'wowza'),
    'content_path'      => env('WOWZA_CONTENT_PATH', '/content'),
    'secure_token'      => env('WOWZA_SECURE_TOKEN', 'yoursSecureToken20221'),
    'token_prefix'      => env('WOWZA_TOKEN_PREFIX', 'tides'),
    'check_fautv_links' => env('CHECK_FAUTV_VIDEOLINKS', true),
];
