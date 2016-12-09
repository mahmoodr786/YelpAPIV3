# Yelp API V3 PHP Class
A PHP Class for Yelp API V3
  - Manages access token
  - Adds the token to headers with every request.
### Installation

```sh
composer require mahmoodr786/yelp-apiv3
```

Load the class.

```php
require "vendor/autoload.php";
use mahmoodr786\YelpAPI\YelpAPI;
```

Create an instance
```php
$api = new YelpAPI(
    'ClientID',
    'Secret'
);
```

Query
```php
$params = [
	'term' => 'food',
	'radius' => '500',
	'location' => '840124'
];
$data = $api->getData('businesses/search', $params);
print_r($data);
```

