<?php

return [
    'system' => env('APP_SYSTEMS', ''),
    'system_id' => env('APP_SYSTEM_ID', '0'),
    'pay_client_id'=>env('PAY_IPROTEK_CLIENT_ID', '0'),
    'pay_url'=>env('PAY_IPROTEK_URL', ''),
    'pay_client_secret'=>env('PAY_IPROTEK_CLIENT_SECRET', ''),
    'api_version'=>env('APP_API_VERSION', '1.0.0.1'),
    'api_db_version'=>env('APP_API_DB_VERSION', '1.0.0.1'),
    'api_description'=>env('APP_DESCRIPTION', '')
];