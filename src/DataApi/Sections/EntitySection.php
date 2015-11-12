<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Api;
use DataBreakers\DataApi\Batch\EntitiesBatch;
use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Order;
use DataBreakers\DataApi\Utils\Restriction;
use DataBreakers\DataApi\Utils\Validator;


class EntitySection extends Section
{

	const PERMANENTLY_PARAMETER = 'permanently';
	const WITH_INTERACTIONS_PARAMETER = 'withInteractions';
	const INTERACTIONS_LIMIT_PARAMETER = 'interactionsLimit';
	const INTERACTIONS_OFFSET_PARAMETER = 'interactionsOffset';
	const IDS_PARAMETER = 'ids';
	const LIMIT_PARAMETER = 'limit';
	const OFFSET_PARAMETER = 'offset';
	const ATTRIBUTES_PARAMETER = 'attributes';
	const ORDER_BY_PARAMETER = 'orderBy';
	const ORDER_PARAMETER = 'order';
	const SEARCH_QUERY_PARAMETER = 'searchQuery';
	const SEARCH_ATTRIBUTES_PARAMETER = 'searchAttributes';
	const ENTITIES_PARAMETER = 'entities';
	const ID_PARAMETER = 'id';

	const DEFAULT_LIMIT = 100;
	const DEFAULT_OFFSET = 0;
	const DEFAULT_INTERACTIONS_LIMIT = 100;
	const DEFAULT_INTERACTIONS_OFFSET = 0;

	/** @var  */
	private $strategy;


	/**
	 * @param Api $api
	 * @param IEntitySectionStrategy $strategy
	 */
	public function __construct(Api $api, IEntitySectionStrategy $strategy)
	{
		parent::__construct($api);
		$this->strategy = $strategy;
	}

	/**
	 * @param string $entityId
	 * @param array $attributes
	 * @return NULl
	 * @throws InvalidArgumentException when given entity id is empty string
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateEntity($entityId, array $attributes = [])
	{
		$batch = (new EntitiesBatch())->addEntity($entityId, $attributes);
		return $this->insertOrUpdateEntities($batch);
	}

	/**
	 * @param EntitiesBatch $entities
	 * @return NULl
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateEntities(EntitiesBatch $entities)
	{
		$restriction = new Restriction([], [self::ENTITIES_PARAMETER => $entities->getEntities()]);
		return $this->performPost($this->strategy->getInsertOrUpdateEntityUrl(), $restriction);
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 * @param array|NULL $attributes
	 * @param string|NULL $orderBy
	 * @param string|NULL $order
	 * @param string|NULL $searchQuery
	 * @param array|NULL $searchAttributes
	 * @return array
	 * @throws InvalidArgumentException when given limit isn't number or is negative
	 * @throws InvalidArgumentException when given offset isn't number or is negative
	 * @throws InvalidArgumentException when given order by is empty string value
	 * @throws InvalidArgumentException when given order isn't NULL, 'asc' or 'desc'
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getEntities($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET, array $attributes = NULl,
								$orderBy = NULL, $order = NULL, $searchQuery = NULL, array $searchAttributes = NULL)
	{
		$this->validateLimitAndOffset($limit, $offset);
		if ($orderBy !== NULL && $orderBy == '') {
			throw new InvalidArgumentException("Order by can't be empty string value.");
		}
		if ($order !== NULL && !Order::isValidOrderValue($order)) {
			throw new InvalidArgumentException("Order must be 'asc', 'desc' or NULL.");
		}
		$restriction = new Restriction([
				self::LIMIT_PARAMETER => (int) $limit,
				self::OFFSET_PARAMETER => (int) $offset
		]);
		$attributes = $attributes === NULL ? NULL : implode(',', $attributes);
		$this->addParameterIfNotNull($restriction, self::ATTRIBUTES_PARAMETER, $attributes);
		$this->addParameterIfNotNull($restriction, self::ORDER_BY_PARAMETER, $orderBy);
		$this->addParameterIfNotNull($restriction, self::ORDER_PARAMETER, $order);
		$this->addParameterIfNotNull($restriction, self::SEARCH_QUERY_PARAMETER, $searchQuery);
		$searchAttributes = $searchAttributes === NULL ? NULL : implode(',', $searchAttributes);
		$this->addParameterIfNotNull($restriction, self::SEARCH_ATTRIBUTES_PARAMETER, $searchAttributes);
		return $this->performGet($this->strategy->getGetEntitiesUrl(), $restriction);
	}

	/**
	 * @param string $entityId
	 * @param bool $withInteractions
	 * @param int $interactionsLimit
	 * @param int $interactionsOffset
	 * @returns array
	 * @throws InvalidArgumentException when given entity id is empty
	 * @throws InvalidArgumentException when given interactions limit isn't number or is negative
	 * @throws InvalidArgumentException when given interactions offset isn't number or is negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getEntity($entityId, $withInteractions = false, $interactionsLimit = self::DEFAULT_INTERACTIONS_LIMIT,
							  $interactionsOffset = self::DEFAULT_INTERACTIONS_OFFSET)
	{
		if ($entityId == '') {
			throw new InvalidArgumentException("Entity id can't be empty string.");
		}
		$this->validateLimitAndOffset($interactionsLimit, $interactionsOffset);
		$restriction = new Restriction([
				$this->strategy->getEntityIdParameter() => $entityId,
				self::WITH_INTERACTIONS_PARAMETER => (bool) $withInteractions,
				self::INTERACTIONS_LIMIT_PARAMETER => (int) $interactionsLimit,
				self::INTERACTIONS_OFFSET_PARAMETER => (int) $interactionsOffset,
		]);
		return $this->performGet($this->strategy->getGetEntityUrl(), $restriction);
	}

	/**
	 * @param string[] $ids
	 * @returns array
	 * @throws InvalidArgumentException when given array of ids is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getSelectedEntities(array $ids)
	{
		if (count($ids) === 0) {
			throw new InvalidArgumentException("Ids array can't be empty.");
		}
		$restriction = new Restriction([], [self::IDS_PARAMETER => $ids]);
		return $this->performPost($this->strategy->getGetSelectedEntitiesUrl(), $restriction);
	}

	/**
	 * @param string $entityId
	 * @param bool $permanently
	 * @return array|NULL
	 * @throws InvalidArgumentException when given entity id is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteEntity($entityId, $permanently = false)
	{
		if ($entityId == '') {
			throw new InvalidArgumentException("Entity id can't be empty.");
		}
		$restriction = new Restriction([
				$this->strategy->getEntityIdParameter() => $entityId,
				self::PERMANENTLY_PARAMETER => (bool) $permanently
		]);
		return $this->performDelete($this->strategy->getDeleteEntityUrl(), $restriction);
	}

	/**
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteEntities()
	{
		return $this->performDelete($this->strategy->getDeleteEntitiesUrl());
	}

}
