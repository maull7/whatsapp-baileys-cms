<?php

return [
    'api' => [
        'base_url' => env('WHATSAPP_API_BASE_URL', 'http://localhost:3000'),
        'timeout' => (int) env('WHATSAPP_API_TIMEOUT', 30),
    ],
];
