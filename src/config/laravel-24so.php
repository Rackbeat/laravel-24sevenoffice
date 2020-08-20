<?php

return [

	'username'   => env( 'LARAVEL_24SO_USERNAME' ),
	'password'   => env( 'LARAVEL_24SO_PASSWORD', null ),
	'api_key'    => env( 'LARAVEL_24SO_API_KEY' ),
	'shop_url'   => env( 'LARAVEL_24SO_SHOP_URL' ),
	'user_agent' => '4SevenOffice API Wrapper (https://github.com/kg-bot/laravel-24sevenoffice)',
	'api_limit'  => env( 'LARAVEL_24SO_RATE_LIMIT', 7200 ),
];