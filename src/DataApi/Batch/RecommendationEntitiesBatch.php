<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;


class RecommendationEntitiesBatch extends Batch
{

	const ENTITY_ID_KEY = 'entityId';
	const ENTITY_WEIGHT_KEY = 'entityWeight';
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
		$this->entities[] = $this->getEntityData($entityId, $interactions);
		return $this;
	}

	/**
	 * @param string $entityId
	 * @param float $weight
	 * @return $this
	 * @throws InvalidArgumentException when given entity id is empty string
	 */
	public function addWeightedEntity($entityId, $weight)
	{
		$this->entities[] = $this->getEntityData($entityId, NULL, $weight);
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
	 * 			 entityWeight => 0.5, // if specified
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

	/**
	 * @param string $entityId
	 * @param NULL|array $interactions
	 * @param NULL|float $weight
	 * @return array
	 * @throws InvalidArgumentException when given entity id is empty string or given weight isn't number
	 */
	private function getEntityData($entityId, array $interactions = NULL, $weight = NULL)
	{
		if ($entityId == '') {
			throw new InvalidArgumentException("Entity id can't be empty string value.");
		}
		$data = [self::ENTITY_ID_KEY => $entityId];
		if ($interactions !== NULL) {
			$data[self::INTERACTIONS_KEY] = $interactions;
		}
		if ($weight !== NULL) {
			if (!is_numeric($weight)) {
				throw new InvalidArgumentException("Entity weight must be numeric value.");
			}
			$data[self::ENTITY_WEIGHT_KEY] = $weight;
		}
		return $data;
	}

}
