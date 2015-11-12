<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\TestCase;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class EntitiesBatchTest extends TestCase
{

	const ID1 = 'entity1';
	const ID2 = 'entity1';

	const NAME_ATTRIBUTE = 'name';
	const AGE_ATTRIBUTE = 'age';

	private $attributes1 = [
		self::NAME_ATTRIBUTE => 'Foo'
	];

	private $attributes2 = [
		self::NAME_ATTRIBUTE => 'Bar',
		self::AGE_ATTRIBUTE => 25
	];

	/** @var EntitiesBatch */
	private $batch;


	protected function setUp()
	{
		$this->batch = (new EntitiesBatch())
			->addEntity(self::ID1, $this->attributes1)
			->addEntity(self::ID2, $this->attributes2);
	}

	public function testGettingEntities()
	{
		$entities = $this->batch->getEntities();
		Assert::true(is_array($entities));
		Assert::same(2, count($entities));
		$this->checkEntity($entities[0], self::ID1, $this->attributes1);
		$this->checkEntity($entities[1], self::ID2, $this->attributes2);
	}

	public function testItCanBeTraversedByForeach()
	{
		$counter = 0;
		foreach ($this->batch as $entity) {
			if ($counter === 0) {
				$this->checkEntity($entity, self::ID1, $this->attributes1);
			}
			if ($counter === 1) {
				$this->checkEntity($entity, self::ID2, $this->attributes2);
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
	 * @param string $id
	 * @param array $attributes
	 */
	private function checkEntity(array $entity, $id, array $attributes)
	{
		Assert::same($entity['id'], $id);
		Assert::same($entity['attributes'], $attributes);
	}

}

(new EntitiesBatchTest())->run();
