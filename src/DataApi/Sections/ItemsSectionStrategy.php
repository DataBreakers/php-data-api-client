<?php

namespace DataBreakers\DataApi\Sections;


class ItemsSectionStrategy implements IEntitySectionStrategy
{

	const INSERT_OR_UPDATE_ITEM_URL = '/{accountId}/item/';
	const GET_ITEMS_URL = '/{accountId}/items/{?limit,offset,attributes,orderBy,order,searchQuery,searchAttributes}';
	const GET_ITEM_URL = '/{accountId}/items/{itemId}{?withInteractions,interactionsLimit,interactionsOffset}';
	const GET_SELECTED_ITEMS_URL = '/{accountId}/items/{?withInteractions,interactionsLimit,interactionsOffset}';
	const DELETE_ITEM_URL = '/{accountId}/items/{itemId}{?permanently}';
	const DELETE_ITEMS_URL = '/{accountId}/items';

	const ITEM_ID_PARAMETER = 'itemId';


	/**
	 * @inheritdoc
	 */
	public function getInsertOrUpdateEntityUrl()
	{
		return self::INSERT_OR_UPDATE_ITEM_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getGetEntitiesUrl()
	{
		return self::GET_ITEMS_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getEntityIdParameter()
	{
		return self::ITEM_ID_PARAMETER;
	}

	/**
	 * @inheritdoc
	 */
	public function getGetEntityUrl()
	{
		return self::GET_ITEM_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getGetSelectedEntitiesUrl()
	{
		return self::GET_SELECTED_ITEMS_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getDeleteEntityUrl()
	{
		return self::DELETE_ITEM_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getDeleteEntitiesUrl()
	{
		return self::DELETE_ITEMS_URL;
	}

}
