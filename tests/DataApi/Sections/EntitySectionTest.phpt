<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Batch\EntitiesBatch;
use DataBreakers\DataApi\Order;
use DateTime;


require_once __DIR__ . '/../../bootstrap.php';


class EntitySectionTest extends SectionTest
{

	const ID1 = 'entity1';
	const ID2 = 'entity2';
	const ID3 = 'entity3';

	const NAME_ATTRIBUTE = 'name';
	const AGE_ATTRIBUTE = 'age';

	const LIMIT = 250;
	const OFFSET = 500;

	/** @var EntitySection */
	private $entitySection;


	protected function setUp()
	{
		parent::setUp();
		$this->entitySection = new EntitySection($this->api, new ItemsSectionStrategy());
	}

	public function testInsertingOrUpdatingEntity()
	{
		$attributes = [
			self::NAME_ATTRIBUTE => 'Foo',
			self::AGE_ATTRIBUTE => 25
		];
		$content = [
			EntitySection::ENTITIES_PARAMETER => [
				[
					EntitySection::ID_PARAMETER => self::ID1,
					EntitySection::ATTRIBUTES_PARAMETER => $attributes
				]
			]
		];
		$this->mockPerformPost(ItemsSectionStrategy::INSERT_OR_UPDATE_ITEM_URL, [], $content);
		$this->entitySection->insertOrUpdateEntity(self::ID1, $attributes);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenEntityIdIsEmptyStringDuringInsertingOrUpdatingEntity()
	{
		$this->entitySection->insertOrUpdateEntity('');
	}

	public function testInsertingOrUpdatingEntities()
	{
		$time = new DateTime();
		$attributes1 = [
			self::NAME_ATTRIBUTE => 'Foo'
		];
		$attributes2 = [
			self::NAME_ATTRIBUTE => 'Bar',
			self::AGE_ATTRIBUTE => 25
		];
		$batch = (new EntitiesBatch())
			->addEntity(self::ID1, $attributes1)
			->addEntity(self::ID2, $attributes2, $time);
		$content = [EntitySection::ENTITIES_PARAMETER => $batch->getEntities()];
		$this->mockPerformPost(ItemsSectionStrategy::INSERT_OR_UPDATE_ITEM_URL, [], $content);
		$this->entitySection->insertOrUpdateEntities($batch);
	}

	public function testGettingEntitiesWithBasicParameters()
	{
		$parameters = [
			EntitySection::LIMIT_PARAMETER => self::LIMIT,
			EntitySection::OFFSET_PARAMETER => self::OFFSET,
			EntitySection::ATTRIBUTES_PARAMETER => self::NAME_ATTRIBUTE . ',' . self::AGE_ATTRIBUTE
		];
		$this->mockPerformGet(ItemsSectionStrategy::GET_ITEMS_URL, $parameters);
		$this->entitySection->getEntities(self::LIMIT, self::OFFSET, [self::NAME_ATTRIBUTE, self::AGE_ATTRIBUTE]);
	}

	public function testGettingEntitiesWithAllParameters()
	{
		$searchQuery = 'foo';
		$parameters = [
			EntitySection::LIMIT_PARAMETER => self::LIMIT,
			EntitySection::OFFSET_PARAMETER => self::OFFSET,
			EntitySection::ATTRIBUTES_PARAMETER => self::NAME_ATTRIBUTE,
			EntitySection::ORDER_BY_PARAMETER => self::AGE_ATTRIBUTE,
			EntitySection::ORDER_PARAMETER => Order::DESC,
			EntitySection::SEARCH_QUERY_PARAMETER => $searchQuery,
			EntitySection::SEARCH_ATTRIBUTES_PARAMETER => self::NAME_ATTRIBUTE . ',' . self::AGE_ATTRIBUTE
		];
		$this->mockPerformGet(ItemsSectionStrategy::GET_ITEMS_URL, $parameters);
		$this->entitySection->getEntities(self::LIMIT, self::OFFSET, [self::NAME_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::DESC,
			$searchQuery, [self::NAME_ATTRIBUTE, self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitIsNotANumberDuringGettingEntities()
	{
		$this->entitySection->getEntities('foo', self::OFFSET, [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::ASC,
			'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitIsNegativeNumberDuringGettingEntities()
	{
		$this->entitySection->getEntities(-10, self::OFFSET, [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::ASC,
				'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNotANumberDuringGettingEntities()
	{
		$this->entitySection->getEntities(self::LIMIT, 'foo', [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::ASC,
				'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNegativeNumberDuringGettingEntities()
	{
		$this->entitySection->getEntities(self::LIMIT, -10, [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, Order::ASC,
				'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOrderIsEmptyStringDuringGettingEntities()
	{
		$this->entitySection->getEntities(self::LIMIT, self::OFFSET, [self::AGE_ATTRIBUTE], '', Order::ASC,
				'foo', [self::AGE_ATTRIBUTE]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOrderByIsNotValidValueDuringGettingEntities()
	{
		$this->entitySection->getEntities(self::LIMIT, self::OFFSET, [self::AGE_ATTRIBUTE], self::AGE_ATTRIBUTE, 'foo',
				'foo', [self::AGE_ATTRIBUTE]);
	}

	public function testGettingEntity()
	{
		$parameters = [
			ItemsSectionStrategy::ITEM_ID_PARAMETER => self::ID1,
			EntitySection::WITH_INTERACTIONS_PARAMETER => true,
			EntitySection::INTERACTIONS_LIMIT_PARAMETER => self::LIMIT,
			EntitySection::INTERACTIONS_OFFSET_PARAMETER => self::OFFSET,
		];
		$this->mockPerformGet(ItemsSectionStrategy::GET_ITEM_URL, $parameters);
		$this->entitySection->getEntity(self::ID1, true, self::LIMIT, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenIdsAreEmptyDuringGettingEntity()
	{
		$this->entitySection->getEntity('', true, self::LIMIT, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionsLimitIsNotANumberDuringEntity()
	{
		$this->entitySection->getEntity(self::ID1, true, 'foo', self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionsLimitIsNotNegativeNumberDuringGettingEntity()
	{
		$this->entitySection->getEntity(self::ID1, true, -10, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionsOffsetIsNotANumberDuringGettingEntity()
	{
		$this->entitySection->getEntity(self::ID1, true, self::LIMIT, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionsOffsetIsNotNegativeNumberDuringGettingEntity()
	{
		$this->entitySection->getEntity(self::ID1, true, self::LIMIT, -10);
	}

	public function testGettingSelectedEntities()
	{
		$ids = [self::ID1, self::ID2, self::ID3];
		$content = [EntitySection::IDS_PARAMETER => $ids];
		$this->mockPerformPost(ItemsSectionStrategy::GET_SELECTED_ITEMS_URL, [], $content);
		$this->entitySection->getSelectedEntities($ids);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenIdsAreEmptyDuringGettingSelectedEntities()
	{
		$this->entitySection->getSelectedEntities([]);
	}
	
	public function testDeletingEntity()
	{
		$parameters = [
			ItemsSectionStrategy::ITEM_ID_PARAMETER => self::ID1,
			EntitySection::PERMANENTLY_PARAMETER => false
		];
		$this->mockPerformDelete(ItemsSectionStrategy::DELETE_ITEM_URL, $parameters);
		$this->entitySection->deleteEntity(self::ID1, false);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenEntityIdIsEmptyDuringDeletingEntity()
	{
		$this->entitySection->deleteEntity('');
	}

	public function testDeletingEntities()
	{
		$this->mockPerformDelete(ItemsSectionStrategy::DELETE_ITEMS_URL);
		$this->entitySection->deleteEntities();
	}

}

(new EntitySectionTest())->run();
