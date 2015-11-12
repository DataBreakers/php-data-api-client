<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;


class EntitiesBatch extends Batch
{

	const ID_KEY = 'id';
	const ATTRIBUTES_KEY = 'attributes';

	/** @var array */
	private $entities = [];


	/**
	 * @param string $entityId
	 * @param array $attributes
	 * @return $this
	 * @throws InvalidArgumentException when given entity id is empty string
	 */
	public function addEntity($entityId, array $attributes = [])
	{
		if ($entityId == '') {
			throw new InvalidArgumentException("Entity id can't be empty value.");
		}
		$this->entities[] = [self::ID_KEY => $entityId, self::ATTRIBUTES_KEY => $attributes];
		return $this;
	}

	/**
	 * @return array of entities
	 *    all entities have format:
	 *       array(
	 *           id => 'id of entity',
	 *           attributes => array(... array of attributes ...)
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
