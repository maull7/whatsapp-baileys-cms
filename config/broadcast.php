<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Jeda antar penerima (detik)
    |--------------------------------------------------------------------------
    |
    | Delay dalam detik antara pengiriman ke satu nomor dan nomor berikutnya
    | untuk mengurangi risiko rate limit / blokir.
    |
    */

    'delay_between_recipients_seconds' => (int) env('BROADCAST_DELAY_SECONDS', 2),

    /*
    |--------------------------------------------------------------------------
    | Maksimal baris per file
    |--------------------------------------------------------------------------
    */

    'max_recipients_per_file' => 20,

];
