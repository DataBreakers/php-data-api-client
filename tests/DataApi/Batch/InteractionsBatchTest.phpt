<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\TestCase;
use DateTime;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class InteractionsBatchTest extends TestCase
{

	const USER_ID1 = 'user1';
	const USER_ID2 = 'user2';
	const ITEM_ID1 = 'item1';
	const ITEM_ID2 = 'item2';
	const INTERACTION_ID1 = 'interaction1';
	const INTERACTION_ID2 = 'interaction2';

	/** @var DateTime */
	private $time;

	/** @var InteractionsBatch */
	private $batch;


	protected function setUp()
	{
		$this->time = new DateTime();
		$this->batch = (new InteractionsBatch())
			->addInteraction(self::USER_ID1, self::ITEM_ID1, self::INTERACTION_ID1, $this->time)
			->addInteraction(self::USER_ID2, self::ITEM_ID2, self::INTERACTION_ID2);
	}

	public function testGettingInteractions()
	{
		$interactions = $this->batch->getInteractions();
		Assert::true(is_array($interactions));
		Assert::same(2, count($interactions));
		$this->checkInteraction($interactions[0], self::USER_ID1, self::ITEM_ID1, self::INTERACTION_ID1, $this->time);
		$this->checkInteraction($interactions[1], self::USER_ID2, self::ITEM_ID2, self::INTERACTION_ID2);
	}

	public function testItCanBeTraversedByForeach()
	{
		$counter = 0;
		foreach ($this->batch as $interaction) {
			if ($counter === 0) {
				$this->checkInteraction($interaction, self::USER_ID1, self::ITEM_ID1, self::INTERACTION_ID1, $this->time);
			}
			if ($counter === 1) {
				$this->checkInteraction($interaction, self::USER_ID2, self::ITEM_ID2, self::INTERACTION_ID2);
			}
			$counter++;
		}
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingInteractionWithEmptyUserId()
	{
		$this->batch->addInteraction('', self::ITEM_ID1, self::INTERACTION_ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingInteractionWithEmptyItemId()
	{
		$this->batch->addInteraction(self::USER_ID1, '', self::INTERACTION_ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingInteractionWithEmptyInteractionId()
	{
		$this->batch->addInteraction(self::USER_ID1, self::ITEM_ID1, '');
	}

	/**
	 * @param array $interaction
	 * @param string $userId
	 * @param string $itemId
	 * @param string $interactionId
	 * @param DateTime|NULL $time
	 * @return void
	 */
	private function checkInteraction(array $interaction, $userId, $itemId, $interactionId, DateTime $time = NULL)
	{
		Assert::same($userId, $interaction[InteractionsBatch::USER_ID_KEY]);
		Assert::same($itemId, $interaction[InteractionsBatch::ITEM_ID_KEY]);
		Assert::same(
			$interactionId,
			$interaction[InteractionsBatch::INTERACTION_KEY][InteractionsBatch::INTERACTION_ID_KEY]
		);
		if ($time !== NULL) {
			Assert::same($time->getTimestamp(), $interaction[InteractionsBatch::TIMESTAMP_KEY]);
		}
	}

}

(new InteractionsBatchTest())->run();
