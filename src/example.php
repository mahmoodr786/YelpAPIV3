<?php

require "vendor/autoload.php";
mahmoodr786\YelpAPI\YelpAPI;

$api = new YelpAPI(
    'clientID',
    'secret'
);
$params = [
	'term' => 'food',
	'radius' => '500',
	'location' => '20124'
];
$data = $api->getData('businesses/search', $params);

print_r($data);
