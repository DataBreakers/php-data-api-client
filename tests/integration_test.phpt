<?php

use DataBreakers\DataApi\Client;
use DataBreakers\DataApi\DataType;
use DataBreakers\DataApi\EntitiesBatch;
use DataBreakers\DataApi\MetaType;
use DataBreakers\DataApi\Order;
use Tester\Assert;


define('NAME_ATTRIBUTE', 'name');
define('AGE_ATTRIBUTE', 'age');

define('EN_LANGUAGE', 'en');

define('ITEM_ID_1', 'item1');
define('ITEM_ID_2', 'item2');
define('ITEM_ID_3', 'item3');


function testAttribute(array $attribute)
{
	Assert::true(in_array($attribute['name'], [NAME_ATTRIBUTE, AGE_ATTRIBUTE]));
	if ($attribute['name'] === NAME_ATTRIBUTE) {
		Assert::same(DataType::TEXT, $attribute['dataType']);
		Assert::same(EN_LANGUAGE, $attribute['description']['language']);
		Assert::same(MetaType::TITLE, $attribute['description']['metaType']);
	}
	if ($attribute['name'] === AGE_ATTRIBUTE) {
		Assert::same(DataType::INTEGER, $attribute['dataType']);
	}
}

function testAttributes(array $attributes)
{
	Assert::same(2, count($attributes['attributes']));
	testAttribute($attributes['attributes'][0]);
	testAttribute($attributes['attributes'][1]);
}

function testEntity(array $entity, $id, array $attributes)
{
	foreach ([NAME_ATTRIBUTE, AGE_ATTRIBUTE] as $attribute) {
		if (!isset($attributes[$attribute])) {
			$attributes[$attribute] = NULL;
		}
	}
	Assert::same($id, $entity['id']);
	Assert::same($attributes, $entity['attributes']);
}

function testEntitiesCount(array $entities, $count, $totalCount)
{
	Assert::same($totalCount, $entities['totalCount']);
	Assert::same($count, count($entities['entities']));
}

function addAndTestUsersAttributes(Client $client)
{
	$client->addUsersAttribute(NAME_ATTRIBUTE, DataType::TEXT, EN_LANGUAGE, MetaType::TITLE);
	$client->addUsersAttribute(AGE_ATTRIBUTE, DataType::INTEGER);
	testAttributes($client->getUsersAttributes());
}

function addAndTestItemsAttributes(Client $client)
{
	$client->addItemsAttribute(NAME_ATTRIBUTE, DataType::TEXT, EN_LANGUAGE, MetaType::TITLE);
	$client->addItemsAttribute(AGE_ATTRIBUTE, DataType::INTEGER);
	testAttributes($client->getItemsAttributes());
}

function clearUsersAttributes(Client $client)
{
	$client->deleteUsersAttribute(NAME_ATTRIBUTE);
	$client->deleteUsersAttribute(AGE_ATTRIBUTE);
	$attributes = $client->getUsersAttributes();
	Assert::same(0, count($attributes['attributes']));
}

function clearItemsAttributes(Client $client)
{
	$client->deleteItemsAttribute(NAME_ATTRIBUTE);
	$client->deleteItemsAttribute(AGE_ATTRIBUTE);
	$attributes = $client->getItemsAttributes();
	Assert::same(0, count($attributes['attributes']));
}

function addAndTestItems(Client $client)
{
	$attributes1 = [NAME_ATTRIBUTE => 'Foo'];
	$attributes2 = [NAME_ATTRIBUTE => 'Bar', AGE_ATTRIBUTE => 25];
	$attributes3 = [];

	$client->insertOrUpdateItem(ITEM_ID_1, $attributes1);
	$items = $client->getItems(100, 0, [NAME_ATTRIBUTE, AGE_ATTRIBUTE]);
	testEntitiesCount($items, 1, 1);
	testEntity($items['entities'][0], ITEM_ID_1, $attributes1);

	$batch = (new EntitiesBatch())
		->addEntity(ITEM_ID_2, $attributes2)
		->addEntity(ITEM_ID_3, $attributes3);
	$client->insertOrUpdateItems($batch);
	$items = $client->getItems(2, 0, [NAME_ATTRIBUTE, AGE_ATTRIBUTE], NULL, Order::DESC);
	testEntitiesCount($items, 2, 3);
	testEntity($items['entities'][0], ITEM_ID_3, $attributes3);
	testEntity($items['entities'][1], ITEM_ID_2, $attributes2);

	$items = $client->getItems(100, 0, [NAME_ATTRIBUTE, AGE_ATTRIBUTE], NULL, NULL, $attributes1['name'], [NAME_ATTRIBUTE]);
	testEntitiesCount($items, 1, 1);
	testEntity($items['entities'][0], ITEM_ID_1, $attributes1);

	$item = $client->getItem(ITEM_ID_2, true);
	testEntity($item, ITEM_ID_2, $attributes2);
	Assert::same(0, count($item['entityInteractions']));

	$items = $client->getSelectedItems([ITEM_ID_1, ITEM_ID_3]);
	testEntitiesCount($items, 2, 3);
	foreach ($items['entities'] as $item) {
		if ($item['id'] === ITEM_ID_1) {
			testEntity($item, ITEM_ID_1, $attributes1);
		}
		if ($item['id'] === ITEM_ID_3) {
			testEntity($item, ITEM_ID_3, $attributes3);
		}
	}
}

function clearItems(Client $client)
{
	$client->deleteItem(ITEM_ID_2);
	$items = $client->getSelectedItems([ITEM_ID_2]);
	testEntitiesCount($items, 1, 3);
	Assert::true($items['entities'][0]['deleted']);

	$client->deleteItem(ITEM_ID_1, true);
	$items = $client->getItems();
	testEntitiesCount($items, 2, 2);

	$client->deleteItems();
	$items = $client->getItems();
	testEntitiesCount($items, 0, 0);
}


require_once __DIR__ . '/bootstrap.php';

$credentials = require __DIR__ . '/credentials.php';
$client = new Client($credentials['accountId'], $credentials['secretKey']);

// Add attributes
addAndTestUsersAttributes($client);
addAndTestItemsAttributes($client);

// Add entities
addAndTestItems($client);

// Clear entities
clearItems($client);

// Clear attributes
clearUsersAttributes($client);
clearItemsAttributes($client);
