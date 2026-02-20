<?php

return [
    'base_url' => env('SATU_SEHAT_BASE_URL', 'https://api-satusehat-stg.dto.kemkes.go.id'),
    'auth_url' => env('SATU_SEHAT_AUTH_URL', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken'),
    'client_id' => env('SATU_SEHAT_CLIENT_ID'),
    'client_secret' => env('SATU_SEHAT_CLIENT_SECRET'),
    'organization_id' => env('SATU_SEHAT_ORGANIZATION_ID'),
];
