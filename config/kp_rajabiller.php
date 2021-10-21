<?php

return [
    'env' => strtolower(env('KP_RB_ENV', 'dev')), // dev or prod

    'url' => strtolower(env('KP_RB_URL', 'https://rajabiller.fastpay.co.id/transaksi/json_devel.php')),

    'uid' => env('KP_RB_UID', ''),

    'pin' => env('KP_RB_PIN', ''),

    'table_names' => [
        'groups' => 'rb_groups',

        'items' => 'rb_items',

        'orders' => 'rb_orders',
    ],
];
