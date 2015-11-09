<?php

use DataBreakers\DataApi\Client;
use DataBreakers\DataApi\DataType;
use DataBreakers\DataApi\MetaType;
use Tester\Assert;


define('NAME_ATTRIBUTE', 'name');
define('AGE_ATTRIBUTE', 'age');
define('EN_LANGUAGE', 'en');


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


require_once __DIR__ . '/bootstrap.php';

$credentials = require __DIR__ . '/credentials.php';
$client = new Client($credentials['accountId'], $credentials['secretKey']);

// Add attributes
addAndTestUsersAttributes($client);
addAndTestItemsAttributes($client);

// Clear attributes
clearUsersAttributes($client);
clearItemsAttributes($client);
