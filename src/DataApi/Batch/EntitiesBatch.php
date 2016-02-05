<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DateTime;


class EntitiesBatch extends Batch
{

	const ID_KEY = 'id';
	const ATTRIBUTES_KEY = 'attributes';
	const TIMESTAMP_KEY = 'timestamp';

	/** @var array */
	private $entities = [];


	/**
	 * @param string $entityId
	 * @param array $attributes
	 * @param DateTime|NULL $time
	 * @return $this
	 * @throws InvalidArgumentException when given entity id is empty string
	 */
	public function addEntity($entityId, array $attributes = [], DateTime $time = NULL)
	{
		if ($entityId == '') {
			throw new InvalidArgumentException("Entity id can't be empty value.");
		}
		$entity = [self::ID_KEY => $entityId, self::ATTRIBUTES_KEY => $attributes];
		if ($time !== NULL) {
			$entity[self::TIMESTAMP_KEY] = $time->getTimestamp();
		}
		$this->entities[] = $entity;
		return $this;
	}

	/**
	 * @return array of entities
	 *    all entities have format:
	 *       array(
	 *           id => 'id of entity',
	 *           attributes => array(... array of attributes ...),
	 *           timestamp => 123456 // if set
	 *       )
	 */
	public function getEntities()
	{
		return $this->entities;
	}

	/**
	 * @inheritdoc
	 */
	protected function getBatchArray()
	{
		return $this->entities;
	}

}
