# PHP client for DataBreakers DataAPI

This library provides PHP implementation of client for DataBreakers DataAPI.


## Requirements

Library requires PHP version 5.4 (or higher) and [Guzzle](http://guzzlephp.org).


## Installation

The best way to install it is using the [Composer](http://getcomposer.org/):

```sh
$ composer require databreakers/php-data-api-client
```


## Quickstart

```php
use DataBreakers\DataApi;

// Create a new instance of Client and provide your credentials
$client = new DataApi\Client('yourAccountId', 'yourSecretKey');

// Define items attributes (do this only when items attributes aren't defined in recommender yet)
$client->addItemsAttribute('title', DataApi\DataType::TEXT, 'en', DataApi\MetaType::TITLE);
$client->addItemsAttribute('color', DataApi\DataType::TEXT, 'en');
$client->addItemsAttribute('weight', DataApi\DataType::INTEGER);

// Add some items (if you are adding multiple items, users or interactions it's much faster to use batches)
$itemsBatch = (new DataApi\Batch\EntitiesBatch())
	->addEntity('fridge', [
		'title' => 'Fridge',
		'color' => 'white',
		'weight' => 55
	])
	->addEntity('car', [
		'title' => 'Car',
		'color' => 'blue',
		'weight' => 1547
	]);
$client->insertOrUpdateItems($itemsBatch);

// Define users attributes (do this only when users attributes aren't defined in recommender yet)
$client->addUsersAttribute('name', DataApi\DataType::TEXT, 'en', DataApi\MetaType::TITLE);
$client->addUsersAttribute('age', DataApi\DataType::INTEGER);

// Add some users
$usersBatch = (new DataApi\Batch\EntitiesBatch())
	->addEntity('john', [
		'name' => 'John Smith',
		'age' => 35
	])
	->addEntity('sophia', [
		'name' => 'Sophia White',
		'age' => 27
	]);
$client->insertOrUpdateUsers($usersBatch);

// Add interactions between users and items
$interactionsBatch = (new DataApi\Batch\InteractionsBatch())
	->addInteraction('john', 'car', 'Like')
	->addInteraction('john', 'car', 'Purchase')
	->addInteraction('john', 'fridge', 'Dislike')
	->addInteraction('sophia', 'car', 'Detail view')
	->addInteraction('sophia', 'fridge', 'Purchase');
$client->insertInteractions($interactionsBatch);

// And finally obtain ten recommendations for Sophia and car item!
$recommendations = $client->getRecommendations('sophia', 'car', 10);
```


-----

[DataBreakers](https://databreakers.com) – we are your data sense
