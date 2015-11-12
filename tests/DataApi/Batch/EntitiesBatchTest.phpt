<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\TestCase;
use DateTime;
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

	/** @var DateTime */
	private $time;


	protected function setUp()
	{
		$this->time = new DateTime();
		$this->batch = (new EntitiesBatch())
			->addEntity(self::ID1, $this->attributes1)
			->addEntity(self::ID2, $this->attributes2, $this->time);
	}

	public function testGettingEntities()
	{
		$entities = $this->batch->getEntities();
		Assert::true(is_array($entities));
		Assert::same(2, count($entities));
		$this->checkEntity($entities[0], self::ID1, $this->attributes1);
		$this->checkEntity($entities[1], self::ID2, $this->attributes2, $this->time);
	}

	public function testItCanBeTraversedByForeach()
	{
		$counter = 0;
		foreach ($this->batch as $entity) {
			if ($counter === 0) {
				$this->checkEntity($entity, self::ID1, $this->attributes1);
			}
			if ($counter === 1) {
				$this->checkEntity($entity, self::ID2, $this->attributes2, $this->time);
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
	 * @param DateTime|NULL $time
	 * @return void
	 */
	private function checkEntity(array $entity, $id, array $attributes, DateTime $time = NULL)
	{
		Assert::same($id, $entity[EntitiesBatch::ID_KEY]);
		Assert::same($attributes, $entity[EntitiesBatch::ATTRIBUTES_KEY]);
		if ($time !== NULL) {
			Assert::same($time->getTimestamp(), $entity[EntitiesBatch::TIMESTAMP_KEY]);
		}
		else {
			Assert::false(isset($entity[EntitiesBatch::TIMESTAMP_KEY]));
		}
	}

}

(new EntitiesBatchTest())->run();
