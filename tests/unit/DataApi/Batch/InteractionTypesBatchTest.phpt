<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\DataApi\InteractionMetaType;
use DataBreakers\UnitTestCase;
use DateTime;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class InteractionTypesBatchTest extends UnitTestCase
{

	const ID1 = 'interactionType1';
	const ID2 = 'interactionType2';

	const INTERACTION_WEIGHT = 0.5;
	const INTERACTION_LEARN_WEIGHT = 0.25;

	const DESCRIPTION_ATTRIBUTE = 'description';
	const PRIORITY_ATTRIBUTE = 'priority';

	private $attributes1 = [
		self::DESCRIPTION_ATTRIBUTE => 'Foo'
	];

	private $attributes2 = [
		self::DESCRIPTION_ATTRIBUTE => 'Bar',
		self::PRIORITY_ATTRIBUTE => 25
	];

	/** @var InteractionTypesBatch */
	private $batch;

	/** @var DateTime */
	private $time;


	protected function setUp()
	{
		$this->time = new DateTime();
		$this->batch = (new InteractionTypesBatch())
			->addInteractionType(self::ID1, InteractionMetaType::CONVERSION, $this->attributes1)
			->addInteractionType(
				self::ID2, NULL, $this->attributes2, self::INTERACTION_WEIGHT,
				self::INTERACTION_LEARN_WEIGHT, $this->time
			);
	}

	public function testGettingInteractionTypes()
	{
		$interactionTypes = $this->batch->getInteractionTypes();
		Assert::true(is_array($interactionTypes));
		Assert::same(2, count($interactionTypes));
		$this->checkInteractionType($interactionTypes[0], self::ID1, InteractionMetaType::CONVERSION, $this->attributes1);
		$this->checkInteractionType(
			$interactionTypes[1], self::ID2, NULL, $this->attributes2, self::INTERACTION_WEIGHT,
			self::INTERACTION_LEARN_WEIGHT, $this->time
		);
	}

	public function testItCanBeTraversedByForeach()
	{
		$counter = 0;
		foreach ($this->batch as $interactionType) {
			if ($counter === 0) {
				$this->checkInteractionType($interactionType, self::ID1, InteractionMetaType::CONVERSION, $this->attributes1);
			}
			if ($counter === 1) {
				$this->checkInteractionType(
					$interactionType, self::ID2, NULL, $this->attributes2, self::INTERACTION_WEIGHT,
					self::INTERACTION_LEARN_WEIGHT, $this->time
				);
			}
			$counter++;
		}
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingInteractionTypeWithEmptyId()
	{
		$this->batch->addInteractionType('');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingInteractionTypeWithInvalidMetaType()
	{
		$this->batch->addInteractionType(self::ID1, 'foo');
	}

	/**
	 * @param float $interactionWeight
	 * @dataProvider getInvalidWeights
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingInteractionTypeWithInvalidInteractionWeight($interactionWeight)
	{
		$this->batch->addInteractionType(self::ID1, NULL, [], $interactionWeight);
	}

	/**
	 * @param float $interactionLearnWeight
	 * @dataProvider getInvalidWeights
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingInteractionTypeWithInvalidInteractionLearnWeight($interactionLearnWeight)
	{
		$this->batch->addInteractionType(self::ID1, NULL, [], NULL, $interactionLearnWeight);
	}

	/**
	 * @return array
	 */
	public function getInvalidWeights()
	{
		return [
			[-1.1],
			[-2],
			[1.1],
			[2],
		];
	}

	/**
	 * @param array $interactionType
	 * @param string $id
	 * @param string|NULL $interactionMetaType
	 * @param array $attributes
	 * @param float|NULL $interactionWeight
	 * @param float|NULL $interactionLearnWeight
	 * @param DateTime|NULL $time
	 * @return void
	 */
	private function checkInteractionType(array $interactionType, $id, $interactionMetaType = NULL, array $attributes = [],
										  $interactionWeight = NULL, $interactionLearnWeight = NULL, DateTime $time = NULL)
	{
		Assert::same($id, $interactionType[InteractionTypesBatch::ID_KEY]);
		Assert::same($attributes, $interactionType[InteractionTypesBatch::ATTRIBUTES_KEY]);
		$this->assertNullableAttribute($interactionType, InteractionTypesBatch::INTERACTION_META_TYPE_KEY, $interactionMetaType);
		$this->assertNullableAttribute($interactionType, InteractionTypesBatch::INTERACTION_WEIGHT_KEY, $interactionWeight);
		$this->assertNullableAttribute($interactionType, InteractionTypesBatch::INTERACTION_LEARN_WEIGHT_KEY, $interactionLearnWeight);
		if ($time !== NULL) {
			Assert::same($time->getTimestamp(), $interactionType[InteractionTypesBatch::TIMESTAMP_KEY]);
		}
		else {
			Assert::false(isset($interactionType[InteractionTypesBatch::TIMESTAMP_KEY]));
		}
	}

	/**
	 * @param array $interactionType
	 * @param string $attribute
	 * @param mixed $expectedValue
	 * @return void
	 */
	private function assertNullableAttribute(array $interactionType, $attribute, $expectedValue)
	{
		if ($expectedValue !== NULL) {
			Assert::same($expectedValue, $interactionType[$attribute]);
		}
		else {
			Assert::false(isset($interactionType[$attribute]));
		}
	}

}

(new InteractionTypesBatchTest())->run();
