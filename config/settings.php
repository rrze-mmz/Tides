<?php

use Carbon\Carbon;

return [
    'portal' => [
        'maintenance_mode' => false,
        'allow_user_registration' => false,
        'show_dropbox_files_in_dashboard' => true,
        'protected_files_string' => '_Kein',
        'feeds_default_owner_name' => 'Tides',
        'feeds_default_owner_email' => 'itunes@tides.com',
        'default_image_id' => env('DEFAULT_IMAGE_ID', 1),
        'support_email_address' => env('SUPPORT_MAIL_ADDRESS', 'support@tides.com'),
        'admin_main_address' => env('ADMIN_MAIL_ADDRESS', 'admin@tides.org'),
        'player_show_article_link_in_player' => false,
        'player_article_link_url' => 1,
        'player_article_link_text' => '',
        'player_enable_adaptive_streaming' => true,
        'clip_generic_poster_image_name' => 'generic_clip_poster_image.png',
        'a_new_key' => 'default value',
    ],
    'user' => [
        'accept_use_terms' => false,
        'language' => 'de',
        'show_subscriptions_to_home_page' => false,
    ],
    'opencast' => [
        'url' => 'localhost:8080',
        'username' => 'admin',
        'password' => 'opencast',
        'archive_path' => 'archive/mh_default_org',
        'default_workflow_id' => 'fast',
        'upload_workflow_id' => 'fast',
        'theme_id_top_right' => '500',
        'theme_id_top_left' => '501',
        'theme_id_bottom_left' => '502',
        'theme_id_bottom_right' => '503',
        'assistants_group_name' => 'ROLE_GROUP_TIDES_ASSISTANTS',
        'opencast_purge_end_date' => Carbon::now(),
        'opencast_purge_events_per_minute' => '20',
    ],
    //    array:16 [▼ // app/Http/Controllers/Backend/StreamingSettingsController.php:21
    //  "wowza_vod_engine_url" => "https://stream.fau.tv/"
    //  "wowza_vod_api_url" => "http://localhost:8087"
    //  "wowza_vod_username" => "admin"
    //  "wowza_vod_password" => "wowza"
    //  "wowza_vod_content_path" => "videoportal_sec/_definst_/videoportal/"
    //  "wowza_vod_secure_token" => "emsJue5Rtv7"
    //  "wowza_vod_token_prefix" => "rrzevp"
    //  "wowza_livestream_engine_url" => "https://livestream.fau.tv/"
    //  "wowza_livestream_api_url" => "http://localhost:8087"
    //  "wowza_livestream_username" => "admin"
    //  "wowza_livestream_password" => "wowza"
    //  "wowza_livestream_content_path" => "/content/videoportal"
    //  "wowza_livestream_secure_token" => "awsTides12tvv10"
    //  "wowza_livestream_token_prefix" => "tides"
    //  "cdn_server_url" => "https://vp-cdn-balance.rrze.de/media_bu/"
    //  "cdn_server_secret" => "emsJue5Rtv7"
    //]
    'streaming' => [
        'wowza' => [
            'server1' => [
                'engine_url' => 'localhost:1935/',
                'api_url' => 'localhost:8087',
                'api_username' => 'digest_user',
                'api_password' => 'digest_password',
                'content_path' => '/content/videoportal',
                'secure_token' => 'awsTides12tvv10',
                'token_prefix' => 'tides',
            ],
            'server2' => [
                'engine_url' => 'localhost:1935/',
                'api_url' => 'localhost:8087',
                'api_username' => 'digest_user',
                'api_password' => 'digest_password',
                'content_path' => '/content/videoportal',
                'secure_token' => 'awsTides12tvv10',
                'token_prefix' => 'tides',
            ],
        ],
        'nginx' => [],
        'cdn' => [
            'server1' => [
                'url' => env('CDN_SERVER_URL', 'http://localhost/'),
                'secret' => env('CDN_SERVER_SECRET', 'dsnJ23fjeq!'),
            ],

        ],
    ],
    //    'streaming' => [
    //        'wowza_vod_engine_url' => 'localhost:1935',
    //        'wowza_vod_api_url' => 'localhost:8087',
    //        'wowza_vod_username' => 'digest_user',
    //        'wowza_vod_password' => 'digest_password',
    //        'wowza_vod_content_path' => '/content/videoportal',
    //        'wowza_vod_secure_token' => 'awsTides12tvv10',
    //        'wowza_vod_token_prefix' => 'tides',
    //        'wowza_livestream_engine_url' => 'localhost:1935',
    //        'wowza_livestream_api_url' => 'localhost:8087',
    //        'wowza_livestream_username' => 'digest_user',
    //        'wowza_livestream_password' => 'digest_password',
    //        'wowza_livestream_content_path' => '/content/videoportal',
    //        'wowza_livestream_secure_token' => 'awsTides12tvv10',
    //        'wowza_livestream_token_prefix' => 'tides',
    //        'cdn_server_url' => env('CDN_SERVER_URL', 'http://localhost/'),
    //        'cdn_server_secret' => env('CDN_SERVER_SECRET', 'dsnJ23fjeq!'),
    //    ],
    'openSearch' => [
        'search_frontend_enable_open_search' => false,
        'url' => 'localhost',
        'port' => 9200,
        'username' => 'admin',
        'password' => 'admin',
        'prefix' => 'tides_',
    ],
    'envoy' => [
        'deploy_server' => env('DEPLOY_SERVER', 'localhost'),
        'deploy_repo' => env('DEPLOY_REPO', 'git@github.com/mmz/tides'),
        'deploy_branch' => env('DEPLOY_BRANCH', 'main'),
    ],
];
