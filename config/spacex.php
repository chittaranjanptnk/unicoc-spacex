<?php

return [
	'api_url' => env('SPACEX_API_URL', 'https://api.spacexdata.com/v3/'),
	'page_size' => env('API_PAGE_SIZE', 20),
	'google_drive_api_key' => env('GOOGLE_DRIVE_API_KEY', NULL),
	'google_drive_access_token' => env('GOOGLE_DRIVE_ACCESS_TOKEN', NULL),
];