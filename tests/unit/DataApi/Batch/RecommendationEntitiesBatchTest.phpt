<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\UnitTestCase;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class RecommendationEntitiesBatchTest extends UnitTestCase
{

	const ID1 = 'entity1';
	const ID2 = 'entity1';

	const INTERACTION1 = 'interaction1';
	const WEIGHT1 = 0.5;

	/** @var RecommendationEntitiesBatch */
	private $batch;


	protected function setUp()
	{
		$this->batch = (new RecommendationEntitiesBatch())
			->addEntity(self::ID1, [self::INTERACTION1 => self::WEIGHT1])
			->addEntity(self::ID2);
	}

	public function testGettingEntities()
	{
		$entities = $this->batch->getEntities();
		Assert::true(is_array($entities));
		Assert::same(2, count($entities));
		$this->checkEntity($entities[0], self::ID1, [self::INTERACTION1 => self::WEIGHT1]);
		$this->checkEntity($entities[1], self::ID2);
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
	 * @param array $entity
	 * @param string $entityId
	 * @param array|NULL $interactions
	 * @return void
	 */
	private function checkEntity(array $entity, $entityId, array $interactions = NULL)
	{
		Assert::same($entityId, $entity[RecommendationEntitiesBatch::ENTITY_ID_KEY]);
		if ($interactions === NULL) {
			Assert::false(isset($entity[RecommendationEntitiesBatch::INTERACTIONS_KEY]));
		}
		else {
			Assert::same($interactions, $entity[RecommendationEntitiesBatch::INTERACTIONS_KEY]);
		}
	}

}

(new RecommendationEntitiesBatchTest())->run();
