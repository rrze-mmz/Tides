<?php

return [
    'url' => env('ELASTICSEARCH_URL', 'localhost'),
    'port' => env('ELASTICSEARCH_PORT', '9200'),
    'username' => env('ELASTICSEARCH_USER', 'elastic'),
    'password' => env('ELASTICSEARCH_PASSWORD', 'changeme'),
];
