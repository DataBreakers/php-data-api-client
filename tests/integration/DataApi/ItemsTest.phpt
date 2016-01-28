<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Batch\EntitiesBatch;
use DataBreakers\IntegrationTestCase;
use DataBreakers\Seeder;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class ItemsTest extends IntegrationTestCase
{

	const ITEM_CAR = 'car';
	const ITEM_FRIDGE = 'fridge';


	public function testAddingOneItem()
	{
		$attributes = [
			Seeder::ATTRIBUTE_NAME => 'Blue car',
			Seeder::ATTRIBUTE_DESCRIPTION => 'This is my blue car.',
		];
		$this->client->insertOrUpdateItem(self::ITEM_CAR, $attributes);
		$this->validateItem($this->client->getItem(self::ITEM_CAR), self::ITEM_CAR, $attributes);
	}

	public function testAddingMultipleItems()
	{
		$carAttributes = [
			Seeder::ATTRIBUTE_NAME => 'Blue car',
			Seeder::ATTRIBUTE_DESCRIPTION => 'This is my blue car.',
		];
		$fridgeAttributes = [
			Seeder::ATTRIBUTE_NAME => 'White fridge',
			Seeder::ATTRIBUTE_WEIGHT => 100,
		];
		$this->client->insertOrUpdateItems((new EntitiesBatch())
			->addEntity(self::ITEM_FRIDGE, $fridgeAttributes)
			->addEntity(self::ITEM_CAR, $carAttributes)
		);
		$this->validateItem($this->client->getItem(self::ITEM_CAR), self::ITEM_CAR, $carAttributes);
		$this->validateItem($this->client->getItem(self::ITEM_FRIDGE), self::ITEM_FRIDGE, $fridgeAttributes);
	}

	public function testGettingItems()
	{
		$expectedIds = [Seeder::ITEM_FOO, Seeder::ITEM_BAR, Seeder::ITEM_BAZ];
		$items = $this->client->getItems();
		Assert::same(count($expectedIds), count($items['entities']));
		foreach ($items['entities'] as $item) {
			Assert::true(in_array($item['id'], $expectedIds));
		}
	}

	public function testGettingOneItem()
	{
		$item = $this->client->getItem(Seeder::ITEM_FOO);
		Assert::same(Seeder::ITEM_FOO, $item['id']);
	}

	public function testGettingSelectedItems()
	{
		$selectedItemsIds = [Seeder::ITEM_BAR, Seeder::ITEM_BAZ];
		$items = $this->client->getSelectedItems($selectedItemsIds);
		Assert::same(count($selectedItemsIds), count($items['entities']));
		foreach ($items['entities'] as $item) {
			Assert::true(in_array($item['id'], $selectedItemsIds));
		}
	}

	public function testSoftDeletingItem()
	{
		$this->client->deleteItem(Seeder::ITEM_FOO);
		$item = $this->client->getItem(Seeder::ITEM_FOO);
		Assert::true($item['deleted']);
	}

	public function testHardDeletingItem()
	{
		$this->client->deleteItem(Seeder::ITEM_FOO, true);
		$items = $this->client->getItems();
		Assert::same(2, count($items['entities']));
		foreach ($items['entities'] as $item) {
			Assert::notSame(Seeder::ITEM_FOO, $item['id']);
		}
	}

	public function testSoftDeletingSelectedItems()
	{
		$itemsToDelete = [Seeder::ITEM_FOO, Seeder::ITEM_BAZ];
		$this->client->deleteSelectedItems($itemsToDelete);
		foreach ($itemsToDelete as $itemToDelete) {
			$item = $this->client->getItem($itemToDelete);
			Assert::true($item['deleted']);
		}
	}

	public function testHardDeletingSelectedItems()
	{
		$itemsToDelete = [Seeder::ITEM_FOO, Seeder::ITEM_BAZ];
		$this->client->deleteSelectedItems($itemsToDelete, true);
		$items = $this->client->getItems();
		Assert::same(1, count($items['entities']));
		foreach ($items['entities'] as $item) {
			Assert::notContains($item['id'], $itemsToDelete);
		}
	}

	public function testClearingItems()
	{
		$this->client->deleteItem(Seeder::ITEM_FOO);
		$this->client->clearItems();
		Assert::same(3, count($this->client->getItems()['entities']));
		$item = $this->client->getItem(Seeder::ITEM_FOO);
		Assert::same(false, $item['deleted']);
		Assert::notContains(Seeder::ATTRIBUTE_NAME, $item['attributes']);
		Assert::notContains(Seeder::ATTRIBUTE_DESCRIPTION, $item['attributes']);
		Assert::notContains(Seeder::ATTRIBUTE_WEIGHT, $item['attributes']);
	}

	public function testActivateItems()
	{
		$itemsToActivate = [Seeder::ITEM_FOO, Seeder::ITEM_BAZ];
		$this->client->deleteSelectedItems($itemsToActivate);
		$this->client->activateItems($itemsToActivate);
		$items = $this->client->getSelectedItems($itemsToActivate);
		Assert::same(2, count($items['entities']));
		foreach ($items['entities'] as $item) {
			Assert::same(false, $item['deleted']);
		}
	}

	/**
	 * @param array $item
	 * @param string $id
	 * @param array $attributes
	 * @return void
	 */
	private function validateItem(array $item, $id, array $attributes)
	{
		Assert::same($id, $item['id']);
		foreach ($attributes as $name => $value) {
			Assert::same($value, $item['attributes'][$name]);
		}
	}

}

(new ItemsTest())->run();
