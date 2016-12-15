<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\UnitTestCase;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class RecommendationEntitiesBatchTest extends UnitTestCase
{

	const ID1 = 'entity1';
	const ID2 = 'entity2';
	const ID3 = 'entity3';
	const ID_PRIMARY = 'primaryEntity';

	const INTERACTION1 = 'interaction1';
	const WEIGHT1 = 0.5;
	const WEIGHT2 = 2.3;

	/** @var RecommendationEntitiesBatch */
	private $batch;


	protected function setUp()
	{
		$this->batch = (new RecommendationEntitiesBatch())
			->addEntity(self::ID1, [self::INTERACTION1 => self::WEIGHT1])
			->addEntity(self::ID2)
			->addWeightedEntity(self::ID3, self::WEIGHT2);
	}

	public function testGettingEntities()
	{
		$entities = $this->batch->getEntities();
		Assert::true(is_array($entities));
		Assert::same(3, count($entities));
		$this->checkEntity($entities[0], self::ID1, [self::INTERACTION1 => self::WEIGHT1]);
		$this->checkEntity($entities[1], self::ID2);
		$this->checkEntity($entities[2], self::ID3, NULL, self::WEIGHT2);
	}

	public function testGettingPrimaryEntityId()
	{
		Assert::same(NULL, $this->batch->getPrimaryEntityId());
		$this->batch->setPrimaryEntityId(self::ID_PRIMARY);
		Assert::same(self::ID_PRIMARY, $this->batch->getPrimaryEntityId());
	}

	public function testItCanBeTraversedByForeach()
	{
		$counter = 0;
		foreach ($this->batch as $entity) {
			if ($counter === 0) {
				$this->checkEntity($entity, self::ID1, [self::INTERACTION1 => self::WEIGHT1]);
			}
			if ($counter === 1) {
				$this->checkEntity($entity, self::ID2);
			}
			if ($counter === 2) {
				$this->checkEntity($entity, self::ID3, NULL, self::WEIGHT2);
			}
			$counter++;
		}
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingEntityWithEmptyId()
	{
		$this->batch->addEntity('');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingWeightedEntityWithEmptyId()
	{
		$this->batch->addWeightedEntity('', self::WEIGHT2);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAddingWeightedEntityWithNonNumericWeight()
	{
		$this->batch->addWeightedEntity(self::ID3, 'foo');
	}

	/**
	 * @param array $entity
	 * @param string $entityId
	 * @param array|NULL $interactions
	 * @param float|NULL $weight
	 * @return void
	 */
	private function checkEntity(array $entity, $entityId, array $interactions = NULL, $weight = NULL)
	{
		Assert::same($entityId, $entity[RecommendationEntitiesBatch::ENTITY_ID_KEY]);
		if ($interactions === NULL) {
			Assert::false(isset($entity[RecommendationEntitiesBatch::INTERACTIONS_KEY]));
		}
		else {
			Assert::same($interactions, $entity[RecommendationEntitiesBatch::INTERACTIONS_KEY]);
		}
		if ($weight === NULL) {
			Assert::false(isset($entity[RecommendationEntitiesBatch::ENTITY_WEIGHT_KEY]));
		}
		else {
			Assert::same($weight, $entity[RecommendationEntitiesBatch::ENTITY_WEIGHT_KEY]);
		}
	}

}

(new RecommendationEntitiesBatchTest())->run();
