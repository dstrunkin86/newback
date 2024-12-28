<?php

return [
    'user' => env('ELASTICSEARCH_USER'),
    'password' => env('ELASTICSEARCH_PASSWORD'),
    'host' => env('ELASTICSEARCH_HOST'),
    'index' => env('ELASTICSEARCH_INDEX_NAME'),
    'errorIndex' => env('ELASTICSEARCH_ERROR_INDEX'),
];
