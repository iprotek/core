<?php

return [
    'system' => env('APP_SYSTEMS', ''),
    'system_id' => env('APP_SYSTEM_ID', '0'),
    'pay_client_id'=>env('PAY_IPROTEK_CLIENT_ID', '0'),
    'pay_url'=>env('PAY_IPROTEK_URL', ''),
    'pay_client_secret'=>env('PAY_IPROTEK_CLIENT_SECRET', ''),
    'api_version'=>env('APP_API_VERSION', '1.0.0.1'),
    'api_db_version'=>env('APP_API_DB_VERSION', '1.0.0.1'),
    'api_description'=>env('APP_DESCRIPTION', ''),
    'pay_message_url'=>env('PAY_MESSAGE_URL', '' ),
    "pay_sms_report_url"=>env('PAY_SMS_REPORT_URL','www.iprotek.net'),
    'app_type'=>env('PAY_IPROTEK_TYPE',''),
    "cart_url"=>env('PAY_CART_URL', ''),
    "google_map_key"=>env('PAY_GOOGLE_MAP_KEY', ''),
    "google_map_id"=>env('PAY_GOOGLE_MAP_ID', ''),
    'disable_multi_branch'=>env('PAY_DISABLE_MULTI_BRANCH', 0),
    'sa_app_account_id'=>env('PAY_SA_APP_ACCOUNT_ID', 0),
    'sa_user_admin_id'=>env('PAY_SA_USER_ADMIN_ID', 0),
    'is_demo'=>env('PAY_IPROTEK_IS_DEMO', 0),
    'timezone'=>env('TIMEZONE', 'UTC'),
    'sidebar_color'=>env('PAY_DEFAULT_THEME_SIDEBAR_COLOR', 'dark-primary'),
    'navbar_color'=>env('PAY_DEFAULT_THEME_NAVBAR_COLOR', 'light navbar-light'),
    'menu'=>env('PAY_MENU', 'admin')
];