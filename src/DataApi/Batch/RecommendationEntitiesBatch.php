<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;


class RecommendationEntitiesBatch extends Batch
{

	const ENTITY_ID_KEY = 'entityId';
	const INTERACTIONS_KEY = 'interactions';

	/** @var array */
	private $entities = [];

	/** @var string */
	private $primaryEntityId;


	/**
	 * @param string $entityId
	 * @param array|NULL $interactions in format 'interaction name' => weight
	 * @return $this
	 * @throws InvalidArgumentException when given entity id is empty string
	 */
	public function addEntity($entityId, array $interactions = NULL)
	{
		if ($entityId == '') {
			throw new InvalidArgumentException("Entity id can't be empty string value.");
		}
		$data = [self::ENTITY_ID_KEY => $entityId];
		if ($interactions !== NULL) {
			$data[self::INTERACTIONS_KEY] = $interactions;
		}
		$this->entities[] = $data;
		return $this;
	}

	/**
	 * @param string $entityId
	 * @return $this
	 */
	public function setPrimaryEntityId($entityId)
	{
		$this->primaryEntityId = $entityId;
		return $this;
	}

	/**
	 * @return array of entities
	 *    all entities have format:
	 *       array(
	 *           entityId => 'id of entity',
	 *           interactions => array(... array of interaction and their weights ...) // if specified
	 *       )
	 */
	public function getEntities()
	{
		return $this->entities;
	}

	/**
	 * @return string
	 */
	public function getPrimaryEntityId()
	{
		return $this->primaryEntityId;
	}

	/**
	 * @inheritdoc
	 */
	protected function getBatchArray()
	{
		return $this->entities;
	}

}
