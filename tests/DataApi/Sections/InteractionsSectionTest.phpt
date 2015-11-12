<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Batch\InteractionsBatch;
use DateTime;


require_once __DIR__ . '/../../bootstrap.php';


class InteractionsSectionTest extends SectionTest
{

	const USER_ID1 = 'user1';
	const USER_ID2 = 'user2';
	const ITEM_ID1 = 'item1';
	const ITEM_ID2 = 'item2';
	const INTERACTION_ID1 = 'interaction1';
	const INTERACTION_ID2 = 'interaction2';

	const LIMIT = 250;
	const OFFSET = 500;

	/** @var InteractionsSection */
	private $interactionsSection;


	protected function setUp()
	{
		parent::setUp();
		$this->interactionsSection = new InteractionsSection($this->api);
	}

	public function testInsertingInteraction()
	{
		$time = new DateTime();
		$content = [
			InteractionsSection::INTERACTIONS_PARAMETER => [
				[
					InteractionsSection::USER_ID_PARAMETER => self::USER_ID1,
					InteractionsSection::ITEM_ID_PARAMETER => self::ITEM_ID1,
					InteractionsSection::INTERACTION_PARAMETER => [
						InteractionsSection::INTERACTION_ID_PARAMETER => self::INTERACTION_ID1
					],
					InteractionsSection::TIMESTAMP_PARAMETER => $time->getTimestamp()
				]
			]
		];
		$this->mockPerformPost(InteractionsSection::INSERT_INTERACTION_URL, [], $content);
		$this->interactionsSection->insertInteraction(self::USER_ID1, self::ITEM_ID1, self::INTERACTION_ID1, $time);
	}

	public function testInsertingInteractionWithoutTime()
	{
		$content = [
			InteractionsSection::INTERACTIONS_PARAMETER => [
				[
					InteractionsSection::USER_ID_PARAMETER => self::USER_ID1,
					InteractionsSection::ITEM_ID_PARAMETER => self::ITEM_ID1,
					InteractionsSection::INTERACTION_PARAMETER => [
						InteractionsSection::INTERACTION_ID_PARAMETER => self::INTERACTION_ID1
					]
				]
			]
		];
		$this->mockPerformPost(InteractionsSection::INSERT_INTERACTION_URL, [], $content);
		$this->interactionsSection->insertInteraction(self::USER_ID1, self::ITEM_ID1, self::INTERACTION_ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyDuringInsertingInteraction()
	{
		$this->interactionsSection->insertInteraction('', self::ITEM_ID1, self::INTERACTION_ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyDuringInsertingInteraction()
	{
		$this->interactionsSection->insertInteraction(self::USER_ID1, '', self::INTERACTION_ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionIdIsEmptyDuringInsertingInteraction()
	{
		$this->interactionsSection->insertInteraction(self::USER_ID1, self::ITEM_ID1, '');
	}

	public function testInsertingInteractions()
	{
		$time = new DateTime();
		$batch = (new InteractionsBatch())
			->addInteraction(self::USER_ID1, self::ITEM_ID1, self::INTERACTION_ID1, $time)
			->addInteraction(self::USER_ID2, self::ITEM_ID2, self::INTERACTION_ID2);
		$content = [
			InteractionsSection::INTERACTIONS_PARAMETER => $batch->getInteractions()
		];
		$this->mockPerformPost(InteractionsSection::INSERT_INTERACTION_URL, [], $content);
		$this->interactionsSection->insertInteractions($batch);
	}

	public function testDeletingInteraction()
	{
		$time = new DateTime();
		$parameters = [
			InteractionsSection::USER_ID_PARAMETER => self::USER_ID1,
			InteractionsSection::ITEM_ID_PARAMETER => self::ITEM_ID1,
			InteractionsSection::TIMESTAMP_PARAMETER => $time->getTimestamp()
		];
		$this->mockPerformDelete(InteractionsSection::DELETE_INTERACTION_URL, $parameters);
		$this->interactionsSection->deleteInteraction(self::USER_ID1, self::ITEM_ID1, $time);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyDuringDeletingInteraction()
	{
		$this->interactionsSection->deleteInteraction('', self::ITEM_ID1, new DateTime());
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyDuringDeletingInteraction()
	{
		$this->interactionsSection->deleteInteraction(self::USER_ID1, '', new DateTime());
	}

	public function testDeletingUserInteractions()
	{
		$parameters = [InteractionsSection::USER_ID_PARAMETER => self::USER_ID1];
		$this->mockPerformDelete(InteractionsSection::DELETE_INTERACTION_URL, $parameters);
		$this->interactionsSection->deleteUserInteractions(self::USER_ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyDuringDeletingUserInteractions()
	{
		$this->interactionsSection->deleteUserInteractions('');
	}

	public function testDeletingItemInteractions()
	{
		$parameters = [InteractionsSection::ITEM_ID_PARAMETER => self::ITEM_ID1];
		$this->mockPerformDelete(InteractionsSection::DELETE_INTERACTION_URL, $parameters);
		$this->interactionsSection->deleteItemInteractions(self::ITEM_ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyDuringDeletingItemInteractions()
	{
		$this->interactionsSection->deleteItemInteractions('');
	}

	public function testDeletingInteractions()
	{
		$this->mockPerformDelete(InteractionsSection::DELETE_INTERACTIONS_URL);
		$this->interactionsSection->deleteInteractions();
	}

	public function testGettingInteractionDefinitionsWithBasicParameters()
	{
		$parameters = [
			InteractionsSection::LIMIT_PARAMETER => InteractionsSection::DEFAULT_LIMIT,
			InteractionsSection::OFFSET_PARAMETER => InteractionsSection::DEFAULT_OFFSET,
		];
		$this->mockPerformGet(InteractionsSection::GET_INTERACTIONS_DEFINITIONS_URL, $parameters);
		$this->interactionsSection->getInteractionDefinitions();
	}

	public function testGettingInteractionDefinitionsWithAllParameters()
	{
		$attributes = ['foo', 'bar'];
		$searchQuery = 'baz';
		$parameters = [
			InteractionsSection::LIMIT_PARAMETER => self::LIMIT,
			InteractionsSection::OFFSET_PARAMETER => self::OFFSET,
			InteractionsSection::ATTRIBUTES_PARAMETER => implode(',', $attributes),
			InteractionsSection::SEARCH_QUERY_PARAMETER => $searchQuery,
			InteractionsSection::SEARCH_ATTRIBUTES_PARAMETER => implode(',', $attributes)
		];
		$this->mockPerformGet(InteractionsSection::GET_INTERACTIONS_DEFINITIONS_URL, $parameters);
		$this->interactionsSection->getInteractionDefinitions(self::LIMIT, self::OFFSET, $attributes, $searchQuery, $attributes);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitIsNotANumberDuringGettingInteractionDefinitions()
	{
		$this->interactionsSection->getInteractionDefinitions('foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitIsNegativeDuringGettingInteractionDefinitions()
	{
		$this->interactionsSection->getInteractionDefinitions(-10);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNotANumberDuringGettingInteractionDefinitions()
	{
		$this->interactionsSection->getInteractionDefinitions(100, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNegativeDuringGettingInteractionDefinitions()
	{
		$this->interactionsSection->getInteractionDefinitions(100, -10);
	}

}

(new InteractionsSectionTest())->run();
