<?php

use DataBreakers\DataApi\Batch\InteractionsBatch;
use DataBreakers\DataApi\Client;
use DataBreakers\DataApi\DataType;
use DataBreakers\DataApi\Batch\EntitiesBatch;
use DataBreakers\DataApi\MetaType;
use DataBreakers\DataApi\Order;
use DataBreakers\DataApi\TemplateConfiguration;
use Tester\Assert;


define('NAME_ATTRIBUTE', 'name');
define('AGE_ATTRIBUTE', 'age');

define('EN_LANGUAGE', 'en');

define('ITEM_ID_1', 'item1');
define('ITEM_ID_2', 'item2');
define('ITEM_ID_3', 'item3');

define('USER_ID_1', 'user1');
define('USER_ID_2', 'user2');
define('USER_ID_3', 'user3');

define('NUMBER_OF_INTERACTION_DEFINITIONS', 6);
define('INTERACTION_LIKE', 'Like');
define('INTERACTION_DISLIKE', 'Dislike');
define('INTERACTION_PURCHASE', 'Purchase');
define('INTERACTION_RECOMMENDATION', 'Recommendation');
define('INTERACTION_DETAIL_VIEW', 'Detail view');
define('INTERACTION_BOOKMARK', 'Bookmark');

define('TEMPLATE_ID_1', 'template1');
define('TEMPLATE_ID_2', 'template2');
define('FILTER', 'filter > 1');
define('BOOSTER', 'booster < 2');
define('USER_WEIGHT', 0.4);
define('ITEM_WEIGHT', 0.6);
define('DIVERSITY', 0.8);


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

function testInteractions(Client $client, $userId, array $itemIds, array $interactionIds, DateTime $time)
{
	$user = $client->getUser($userId, true);
	Assert::same(count($itemIds), $user['totalInteractions']);
	$interactions = $user['entityInteractions'];
	Assert::same(count($itemIds), count($interactions));
	for ($i = 0; $i < count($itemIds); $i++) {
		$expectedInteraction = [
			'entityId' => $itemIds[$i],
			'interactionId' => $interactionIds[$i],
			'timestamp' => $time->getTimestamp()
		];
		Assert::contains($expectedInteraction, $interactions);
	}
}

function testTemplate(array $template, $id, TemplateConfiguration $configuration)
{
	Assert::same($id, $template['templateId']);
	if ($configuration->getFilter() !== NULL) {
		Assert::same($configuration->getFilter(), $template['filter']);
	}
	if ($configuration->getBooster() !== NULL) {
		Assert::same($configuration->getBooster(), $template['booster']);
	}
	if ($configuration->getUserWeight() !== NULL) {
		Assert::same($configuration->getUserWeight(), $template['userWeight']);
	}
	if ($configuration->getItemWeight() !== NULL) {
		Assert::same($configuration->getItemWeight(), $template['itemWeight']);
	}
	if ($configuration->getDiversity() !== NULL) {
		Assert::same($configuration->getDiversity(), $template['diversity']);
	}
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

function addAndTestUsers(Client $client)
{
	$attributes1 = [NAME_ATTRIBUTE => 'Foo'];
	$attributes2 = [NAME_ATTRIBUTE => 'Bar', AGE_ATTRIBUTE => 25];
	$attributes3 = [];

	$client->insertOrUpdateUser(USER_ID_1, $attributes1);
	$users = $client->getUsers(100, 0, [NAME_ATTRIBUTE, AGE_ATTRIBUTE]);
	testEntitiesCount($users, 1, 1);
	testEntity($users['entities'][0], USER_ID_1, $attributes1);

	$batch = (new EntitiesBatch())
			->addEntity(USER_ID_2, $attributes2)
			->addEntity(USER_ID_3, $attributes3);
	$client->insertOrUpdateUsers($batch);
	$users = $client->getUsers(2, 0, [NAME_ATTRIBUTE, AGE_ATTRIBUTE], NULL, Order::DESC);
	testEntitiesCount($users, 2, 3);
	testEntity($users['entities'][0], USER_ID_3, $attributes3);
	testEntity($users['entities'][1], USER_ID_2, $attributes2);

	$users = $client->getUsers(100, 0, [NAME_ATTRIBUTE, AGE_ATTRIBUTE], NULL, NULL, $attributes1['name'], [NAME_ATTRIBUTE]);
	testEntitiesCount($users, 1, 1);
	testEntity($users['entities'][0], USER_ID_1, $attributes1);

	$user = $client->getUser(USER_ID_2, true);
	testEntity($user, USER_ID_2, $attributes2);
	Assert::same(0, count($user['entityInteractions']));

	$users = $client->getSelectedUsers([USER_ID_1, USER_ID_3]);
	testEntitiesCount($users, 2, 3);
	foreach ($users['entities'] as $user) {
		if ($user['id'] === USER_ID_1) {
			testEntity($user, USER_ID_1, $attributes1);
		}
		if ($user['id'] === USER_ID_3) {
			testEntity($user, USER_ID_3, $attributes3);
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

function clearUsers(Client $client)
{
	$client->deleteUser(USER_ID_2);
	$users = $client->getSelectedUsers([USER_ID_2]);
	testEntitiesCount($users, 1, 3);
	Assert::true($users['entities'][0]['deleted']);

	$client->deleteUser(USER_ID_2, true);
	$users = $client->getUsers();
	testEntitiesCount($users, 2, 2);

	$client->deleteUsers();
	$users = $client->getUsers();
	testEntitiesCount($users, 0, 0);
}

function addAndTestInteractions(Client $client, DateTime $interactionTime)
{
	$client->insertInteraction(USER_ID_1, ITEM_ID_1, INTERACTION_LIKE, $interactionTime);
	$batch = (new InteractionsBatch())
		->addInteraction(USER_ID_1, ITEM_ID_2, INTERACTION_DISLIKE, $interactionTime)
		->addInteraction(USER_ID_2, ITEM_ID_1, INTERACTION_LIKE, $interactionTime)
		->addInteraction(USER_ID_2, ITEM_ID_1, INTERACTION_PURCHASE, $interactionTime)
		->addInteraction(USER_ID_3, ITEM_ID_3, INTERACTION_DISLIKE, $interactionTime);
	$client->insertInteractions($batch);

	testInteractions($client, USER_ID_1, [ITEM_ID_1, ITEM_ID_2], [INTERACTION_LIKE, INTERACTION_DISLIKE], $interactionTime);
	testInteractions($client, USER_ID_2, [ITEM_ID_1, ITEM_ID_1], [INTERACTION_LIKE, INTERACTION_PURCHASE], $interactionTime);
	testInteractions($client, USER_ID_3, [ITEM_ID_3], [INTERACTION_DISLIKE], $interactionTime);
}

function clearInteractions(Client $client, DateTime $interactionTime)
{
	$client->deleteInteraction(USER_ID_1, ITEM_ID_1, $interactionTime);
	testInteractions($client, USER_ID_1, [ITEM_ID_2], [INTERACTION_DISLIKE], $interactionTime);

	$client->deleteUserInteractions(USER_ID_1);
	testInteractions($client, USER_ID_1, [], [], $interactionTime);

	$client->deleteItemInteractions(ITEM_ID_1);
	testInteractions($client, USER_ID_2, [], [], $interactionTime);

	testInteractions($client, USER_ID_3, [ITEM_ID_3], [INTERACTION_DISLIKE], $interactionTime);
	$client->deleteInteractions();
	testInteractions($client, USER_ID_3, [], [], $interactionTime);
}

function testInteractionDefinitions(Client $client)
{
	$definitions = $client->getInteractionDefinitions();
	Assert::same(NUMBER_OF_INTERACTION_DEFINITIONS, $definitions['totalCount']);
	$expectedDefinitions = [
		INTERACTION_LIKE, INTERACTION_DISLIKE,
		INTERACTION_PURCHASE, INTERACTION_BOOKMARK,
		INTERACTION_DETAIL_VIEW, INTERACTION_RECOMMENDATION
	];
	foreach ($definitions['interactions'] as $definition) {
		Assert::contains($definition['id'], $expectedDefinitions);
	}
}

function addAndTestTemplates(Client $client)
{
	$configuration1 = (new TemplateConfiguration())
		->setFilter(FILTER)
		->setBooster(BOOSTER)
		->setDiversity(DIVERSITY);
	$configuration2 = (new TemplateConfiguration())
		->setUserWeight(USER_WEIGHT)
		->setItemWeight(ITEM_WEIGHT)
		->setDiversity(DIVERSITY);

	$client->insertOrUpdateTemplate(TEMPLATE_ID_1, $configuration1);
	$templates = $client->getTemplates();
	Assert::same(2, count($templates['templates']));
	testTemplate($client->getTemplate(TEMPLATE_ID_1), TEMPLATE_ID_1, $configuration1);

	$client->insertOrUpdateTemplate(TEMPLATE_ID_2, $configuration2);
	$templates = $client->getTemplates();
	Assert::same(3, count($templates['templates']));
	testTemplate($client->getTemplate(TEMPLATE_ID_2), TEMPLATE_ID_2, $configuration2);
}

function clearTemplates(Client $client)
{
	$client->deleteTemplate(TEMPLATE_ID_1);
	$templates = $client->getTemplates();
	Assert::same(2, count($templates['templates']));

	$client->deleteTemplate(TEMPLATE_ID_2);
	$templates = $client->getTemplates();
	Assert::same(1, count($templates['templates']));
}


require_once __DIR__ . '/bootstrap.php';

$credentials = require __DIR__ . '/credentials.php';
$client = new Client($credentials['accountId'], $credentials['secretKey']);

// Add attributes
addAndTestUsersAttributes($client);
addAndTestItemsAttributes($client);

// Add entities
addAndTestItems($client);
addAndTestUsers($client);

// Add interactions
$interactionTime = new DateTime();
addAndTestInteractions($client, $interactionTime);

// Add templates
addAndTestTemplates($client);

// Test interaction definitions
testInteractionDefinitions($client);

// Clear templates
clearTemplates($client);

// Clear interactions
clearInteractions($client, $interactionTime);

// Clear entities
clearItems($client);
clearUsers($client);

// Clear attributes
clearUsersAttributes($client);
clearItemsAttributes($client);
