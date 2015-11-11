<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Sections\EntitySectionTest;
use DataBreakers\DataApi\Sections\ItemsSection;
use DataBreakers\DataApi\Utils\Restriction;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class ItemsSectionTest extends EntitySectionTest
{

	const ITEM_ID_1 = 'item1';
	const ITEM_ID_2 = 'item2';
	const ITEM_ID_3 = 'item3';

	const NAME_ATTRIBUTE = 'name';
	const AGE_ATTRIBUTE = 'age';

	const LIMIT = 250;
	const OFFSET = 500;

	/** @var ItemsSection */
	private $itemsSection;


	protected function setUp()
	{
		parent::setUp();
		$this->itemsSection = new ItemsSection($this->api);
	}

	public function testInsertingOrUpdatingItem()
	{
		$attributes = [
			self::NAME_ATTRIBUTE => 'Foo',
			self::AGE_ATTRIBUTE => 25
		];
		$content = [
			ItemsSection::ENTITIES_PARAMETER => [
				[
					ItemsSection::ID_PARAMETER => self::ITEM_ID_1,
					ItemsSection::ATTRIBUTES_PARAMETER => $attributes
				]
			],
			ItemsSection::DISABLE_CHECKS_PARAMETER => false
		];
		$this->mockPerformPost(ItemsSection::INSERT_OR_UPDATE_ITEM_URL, [], $content);
		$this->itemsSection->insertOrUpdateItem(self::ITEM_ID_1, $attributes);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyStringDuringInsertingOrUpdatingItem()
	{
		$this->itemsSection->insertOrUpdateItem('');
	}

	public function testInsertingOrUpdatingItems()
	{
		$attributes1 = [
			self::NAME_ATTRIBUTE => 'Foo'
		];
		$attributes2 = [
			self::NAME_ATTRIBUTE => 'Bar',
			self::AGE_ATTRIBUTE => 25
		];
		$batch = (new EntitiesBatch())
			->addEntity(self::ITEM_ID_1, $attributes1)
			->addEntity(self::ITEM_ID_2, $attributes2);
		$content = [
				ItemsSection::ENTITIES_PARAMETER => [
					[
						ItemsSection::ID_PARAMETER => self::ITEM_ID_1,
						ItemsSection::ATTRIBUTES_PARAMETER => $attributes1
					],
					[
						ItemsSection::ID_PARAMETER => self::ITEM_ID_2,
						ItemsSection::ATTRIBUTES_PARAMETER => $attributes2
					]
				],
				ItemsSection::DISABLE_CHECKS_PARAMETER => false
		];
		$this->mockPerformPost(ItemsSection::INSERT_OR_UPDATE_ITEM_URL, [], $content);
		$this->itemsSection->insertOrUpdateItems($batch);
	}

	public function testGettingItemsWithBasicParameters()
	{
		$parameters = [
			ItemsSection::LIMIT_PARAMETER => self::LIMIT,
			ItemsSection::OFFSET_PARAMETER => self::OFFSET,
			ItemsSection::ATTRIBUTES_PARAMETER => self::NAME_ATTRIBUTE . ',' . self::AGE_ATTRIBUTE
		];
		$this->mockPerformGet(ItemsSection::GET_ITEMS_URL, $parameters);
		$this->itemsSection->getItems(self::LIMIT, self::OFFSET, [self::NAME_ATTRIBUTE, self::AGE_ATTRIBUTE]);
	}

	public function testGettingItemsWithAllParameters()
	{
		$searchQuery = 'foo';
		$parameters = [
			ItemsSection::LIMIT_PARAMETER => self::LIMIT,
			ItemsSection::OFFSET_PARAMETER => self::OFFSET,
			ItemsSection::ATTRIBUTES_PARAMETER => self::NAME_ATTRIBUTE,
			ItemsSection::ORDER_BY_PARAMETER => self::AGE_ATTRIBUTE,
			ItemsSection::ORDER_PARAMETER => Order::DESC,
			ItemsSection::SEARCH_QUERY_PARAMETER => $searchQuery,
			ItemsSection::SEARCH_ATTRIBUTES_PARAMETER => self::NAME_ATTRIBUTE . ',' . self::AGE_ATTRIBUTE
		];
		$this->mockPerformGet(ItemsSection::GET_ITEMS_URL, $parameters);
		$this->itemsSection->getItems(self::LIMIT, self::OFFSET, [self::NAME_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::DESC,
			$searchQuery, [self::NAME_ATTRIBUTE, self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitIsNotANumberDuringGettingItems()
	{
		$this->itemsSection->getItems('foo', self::OFFSET, [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::ASC,
			'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitIsNegativeNumberDuringGettingItems()
	{
		$this->itemsSection->getItems(-10, self::OFFSET, [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::ASC,
				'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNotANumberDuringGettingItems()
	{
		$this->itemsSection->getItems(self::LIMIT, 'foo', [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::ASC,
				'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNegativeNumberDuringGettingItems()
	{
		$this->itemsSection->getItems(self::LIMIT, -10, [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::ASC,
				'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOrderIsEmptyStringDuringGettingItems()
	{
		$this->itemsSection->getItems(self::LIMIT, self::OFFSET, [self::AGE_ATTRIBUTE], '', Order::ASC,
				'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOrderByIsNotValidValueDuringGettingItems()
	{
		$this->itemsSection->getItems(self::LIMIT, self::OFFSET, [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, 'foo',
				'foo', [self::AGE_ATTRIBUTE]);
	}

	public function testGettingItem()
	{
		$parameters = [
			ItemsSection::ITEM_ID_PARAMETER => self::ITEM_ID_1,
			ItemsSection::WITH_INTERACTIONS_PARAMETER => true,
			ItemsSection::INTERACTIONS_LIMIT_PARAMETER => self::LIMIT,
			ItemsSection::INTERACTIONS_OFFSET_PARAMETER => self::OFFSET,
		];
		$this->mockPerformGet(ItemsSection::GET_ITEM_URL, $parameters);
		$this->itemsSection->getItem(self::ITEM_ID_1, true, self::LIMIT, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenIdsAreEmptyDuringGettingItem()
	{
		$this->itemsSection->getItem('', true, self::LIMIT, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionsLimitIsNotANumberDuringItem()
	{
		$this->itemsSection->getItem(self::ITEM_ID_1, true, 'foo', self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionsLimitIsNotNegativeNumberDuringGettingItem()
	{
		$this->itemsSection->getItem(self::ITEM_ID_1, true, -10, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionsOffsetIsNotANumberDuringGettingItem()
	{
		$this->itemsSection->getItem(self::ITEM_ID_1, true, self::LIMIT, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionsOffsetIsNotNegativeNumberDuringGettingItem()
	{
		$this->itemsSection->getItem(self::ITEM_ID_1, true, self::LIMIT, -10);
	}

	public function testGettingSelectedItems()
	{
		$ids = [self::ITEM_ID_1, self::ITEM_ID_2, self::ITEM_ID_3];
		$content = [ItemsSection::IDS_PARAMETER => $ids];
		$this->mockPerformPost(ItemsSection::GET_SELECTED_ITEMS_URL, [], $content);
		$this->itemsSection->getSelectedItems($ids);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenIdsAreEmptyDuringGettingSelectedItems()
	{
		$this->itemsSection->getSelectedItems([]);
	}
	
	public function testDeletingItem()
	{
		$parameters = [
			ItemsSection::ITEM_ID_PARAMETER => self::ITEM_ID_1,
			ItemsSection::PERMANENTLY_PARAMETER => false
		];
		$this->mockPerformDelete(ItemsSection::DELETE_ITEM_URL, $parameters);
		$this->itemsSection->deleteItem(self::ITEM_ID_1, false);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyDuringDeletingItem()
	{
		$this->itemsSection->deleteItem('');
	}

	public function testDeletingItems()
	{
		$this->mockPerformDelete(ItemsSection::DELETE_ITEMS_URL);
		$this->itemsSection->deleteItems();
	}

}

(new ItemsSectionTest())->run();
