<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use Iterator;


class EntitiesBatch implements Iterator
{

	const ID_KEY = 'id';
	const ATTRIBUTES_KEY = 'attributes';

	/** @var int */
	private $iteratorPosition = 0;

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
	 * @return array with information about entity in format:
	 * 	 array(
     *       id => 'id of entity',
     *       attributes => array(... array of attributes ...)
     *   )
	 */
	public function current()
	{
		return $this->entities[$this->iteratorPosition];
	}

	/**
	 * @inheritdoc
	 */
	public function key()
	{
		return $this->iteratorPosition;
	}

	/**
	 * @inheritdoc
	 */
	public function next()
	{
		$this->iteratorPosition++;
	}

	/**
	 * @inheritdoc
	 */
	public function rewind()
	{
		$this->iteratorPosition = 0;
	}

	/**
	 * @inheritdoc
	 */
	public function valid()
	{
		return isset($this->entities[$this->iteratorPosition]);
	}

}
