<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAAOjqzTDw:APA91bFuNMgCFWlTAA4j9nCPzEFXwyUE5H1i4zeh-UY3rUaIxi4_9EX8fswnWNuE44XFbPjM-FcRuH4Wy4xTcBxLfpjMF5uBfPOyPq-bw1J2_KfIXDWAYmWRocZQm1zq_R_iJbEg4RSs'),
        'sender_id' => env('FCM_SENDER_ID', '250092932156'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];
