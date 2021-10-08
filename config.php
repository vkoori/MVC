<?php 
return [
	'app' => [
		'TIMEZONE' 		=> 'Asia/Tehran',
		'APP_DEBUG' 	=> true,
		'APP_PREFIX' 	=> '/kargosha-new/dideshow',
		'HASH' 			=> 'argon2i',
		'LANG' 			=> 'fa'
	],
	'cors' => [
		'allowed_methods' => ['*'],
		'allowed_origins' => ['*'],
		'allowed_headers' => ['*'],
		'exposed_headers' => [],
		'max_age' => 0,
		'supports_credentials' => "false",
	]
];
