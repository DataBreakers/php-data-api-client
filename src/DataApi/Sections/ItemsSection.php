<?php

namespace DataBreakers\DataApi\Sections;


use DataBreakers\DataApi\EntitiesBatch;
use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Order;
use DataBreakers\DataApi\Utils\Restriction;
use DataBreakers\DataApi\Utils\Validator;


class ItemsSection extends EntitySection
{

	const INSERT_OR_UPDATE_ITEM_URL = '/{accountId}/item/';
	const GET_ITEMS_URL = '/{accountId}/items/{?limit,offset,attributes,orderBy,order,searchQuery,searchAttributes}';
	const GET_ITEM_URL = '/{accountId}/items/{itemId}{?withInteractions,interactionsLimit,interactionsOffset}';
	const GET_SELECTED_ITEMS_URL = '/{accountId}/items/{?withInteractions,interactionsLimit,interactionsOffset}';
	const DELETE_ITEM_URL = '/{accountId}/items/{itemId}{?permanently}';
	const DELETE_ITEMS_URL = '/{accountId}/items/';

	const ITEM_ID_PARAMETER = 'itemId';


	/**
	 * @param string $itemId
	 * @param array $attributes
	 * @return NULl
	 * @throws InvalidArgumentException when given item id is empty string
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateItem($itemId, array $attributes = [])
	{
		if ($itemId == '') {
			throw new InvalidArgumentException("Item id can't be empty string.");
		}
		$batch = (new EntitiesBatch())->addEntity($itemId, $attributes);
		return $this->insertOrUpdateItems($batch);
	}

	/**
	 * @param EntitiesBatch $items
	 * @return NULl
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateItems(EntitiesBatch $items)
	{
		$entities = [];
		foreach ($items as $item) {
			$entities[] = $item;
		}
		$restriction = new Restriction([], [
			self::ENTITIES_PARAMETER => $entities,
			self::DISABLE_CHECKS_PARAMETER => false
		]);
		return $this->performPost(self::INSERT_OR_UPDATE_ITEM_URL, $restriction);
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
	public function getItems($limit = 100, $offset = 0, array $attributes = NULl, $orderBy = NULL, $order = NULL,
							 $searchQuery = NULL, array $searchAttributes = NULL)
	{
		if (!Validator::isPositiveNumberOrZero($limit)) {
			throw new InvalidArgumentException("Limit must be positive numeric value or zero.");
		}
		if (!Validator::isPositiveNumberOrZero($offset)) {
			throw new InvalidArgumentException("Offset must be positive numeric value or zero.");
		}
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
		return $this->performGet(self::GET_ITEMS_URL, $restriction);
	}

	/**
	 * @param string $itemId
	 * @param bool $withInteractions
	 * @param int $interactionsLimit
	 * @param int $interactionsOffset
	 * @returns array
	 * @throws InvalidArgumentException when given item id is empty
	 * @throws InvalidArgumentException when given interactions limit isn't number or is negative
	 * @throws InvalidArgumentException when given interactions offset isn't number or is negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getItem($itemId, $withInteractions = false, $interactionsLimit = 100, $interactionsOffset = 0)
	{
		if ($itemId == '') {
			throw new InvalidArgumentException("Item id can't be empty string.");
		}
		if (!Validator::isPositiveNumberOrZero($interactionsLimit)) {
			throw new InvalidArgumentException("Interactions limit must be positive numeric value or zero.");
		}
		if (!Validator::isPositiveNumberOrZero($interactionsOffset)) {
			throw new InvalidArgumentException("Interactions offset must be positive numeric value or zero.");
		}
		$restriction = new Restriction([
			self::ITEM_ID_PARAMETER => $itemId,
			self::WITH_INTERACTIONS_PARAMETER => (bool) $withInteractions,
			self::INTERACTIONS_LIMIT_PARAMETER => (int) $interactionsLimit,
			self::INTERACTIONS_OFFSET_PARAMETER => (int) $interactionsOffset,
		]);
		return $this->performGet(self::GET_ITEM_URL, $restriction);
	}

	/**
	 * @param string[] $ids
	 * @returns array
	 * @throws InvalidArgumentException when given array of ids is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getSelectedItems(array $ids)
	{
		if (count($ids) === 0) {
			throw new InvalidArgumentException("Ids array can't be empty.");
		}
		$restriction = new Restriction([], [self::IDS_PARAMETER => $ids]);
		return $this->performPost(self::GET_SELECTED_ITEMS_URL, $restriction);
	}

	/**
	 * @param string $itemId
	 * @param bool $permanently
	 * @return array|NULL
	 * @throws InvalidArgumentException when given item id is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteItem($itemId, $permanently = false)
	{
		if ($itemId == '') {
			throw new InvalidArgumentException("Item id can't be empty.");
		}
		$restriction = new Restriction([
			self::ITEM_ID_PARAMETER => $itemId,
			self::PERMANENTLY_PARAMETER => (bool) $permanently
		]);
		return $this->performDelete(self::DELETE_ITEM_URL, $restriction);
	}

	/**
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteItems()
	{
		return $this->performDelete(self::DELETE_ITEMS_URL);
	}

}
