<?php 

$api_dir = 'api/v1/';

/*------------------- merge -------------------*/
$r->addRoute('GET', '/', [
	'use' => $api_dir.'sampleController@test',
	// 'middleware' => 'setUserid'
]);
