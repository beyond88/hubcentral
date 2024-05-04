<?php

require_once __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    'http://woocommerce.test/',
    'ck_6e4d9b71a9df2e4a637103be81be3081b3c7328e',
    'cs_b5b65368f45aad382debb4225028cda3e7e679e1',
    [
        'wp_api' => true,
        'version' => 'wc/v3',
        // 'query_string_auth' => true
    ]
);
print_r($woocommerce->get('orders'));
