<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'register', 'login', 'logout'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Vue.js の開発サーバーのURL
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // クッキーベースの認証を使用する場合は true
];
