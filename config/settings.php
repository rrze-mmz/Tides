<?php

return [
    'portal' => [
        'maintenance_mode' => false,
        'allow_user_registration' => false,
        'feeds_default_owner_name' => 'Tides',
        'feeds_default_owner_email' => 'itunes@tides.com',
        'default_image_id' => env('DEFAULT_IMAGE_ID', 1),
        'support_email_address' => env('SUPPORT_MAIL_ADDRESS', 'support@tides.com'),
        'saml_tenant_uuid' => env('SAML2_TENANT_UUID', null),
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
        'default_workflow' => 'fast',
        'upload_workflow_id' => 'fast',
        'theme_id_top_right' => '500',
        'theme_id_top_left' => '501',
        'theme_id_bottom_left' => '502',
        'theme_id_bottom_right' => '503',
        'assistants_group_name' => 'ROLE_GROUP_TIDES_ASSISTANTS',
    ],
    'streaming' => [
        'engine_url' => 'localhost:1935',
        'api_url' => 'localhost:8087',
        'username' => 'digest_user',
        'password' => 'digest_password',
        'content_path' => '/content/videoportal',
        'secure_token' => 'awsTides12tvv10',
        'token_prefix' => 'tides',
    ],
    'openSearch' => [
        'url' => 'localhost',
        'port' => 9200,
        'username' => 'admin',
        'password' => 'admin',
        'prefix' => 'tides_',
    ],
];
