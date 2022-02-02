<?php

return [
	'api_url' => env('SPACEX_API_URL', 'https://api.spacexdata.com/v3/'),
	'page_size' => env('API_PAGE_SIZE', 20),
	'google_drive_api_key' => env('GOOGLE_DRIVE_API_KEY', null),
	'google_drive_client_id' => env('GOOGLE_DRIVE_CLIENT_ID', null),
	'google_drive_client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET', null),
	'google_drive_access_token' => env('GOOGLE_DRIVE_ACCESS_TOKEN', null),
	'google_oauth_file' => storage_path('app/google/auth.json'),
	'google_token_file' => storage_path('app/google/token.json'),
];