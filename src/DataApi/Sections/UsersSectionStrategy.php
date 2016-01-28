<?php

namespace DataBreakers\DataApi\Sections;


class UsersSectionStrategy implements IEntitySectionStrategy
{

	const INSERT_OR_UPDATE_USER_URL = '/{accountId}/user/';
	const GET_USERS_URL = '/{accountId}/users/{?limit,offset,attributes,orderBy,order,searchQuery,searchAttributes}';
	const GET_USER_URL = '/{accountId}/users/{userId}{?withInteractions,interactionsLimit,interactionsOffset}';
	const GET_SELECTED_USERS_URL = '/{accountId}/users/{?withInteractions,interactionsLimit,interactionsOffset}';
	const DELETE_USER_URL = '/{accountId}/users/{userId}{?permanently}';
	const DELETE_USERS_URL = '/{accountId}/users';
	const ACTIVATE_USERS_URL = '/{accountId}/users/activate';

	const USER_ID_PARAMETER = 'userId';


	/**
	 * @inheritdoc
	 */
	public function getInsertOrUpdateEntityUrl()
	{
		return self::INSERT_OR_UPDATE_USER_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getGetEntitiesUrl()
	{
		return self::GET_USERS_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getEntityIdParameter()
	{
		return self::USER_ID_PARAMETER;
	}

	/**
	 * @inheritdoc
	 */
	public function getGetEntityUrl()
	{
		return self::GET_USER_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getGetSelectedEntitiesUrl()
	{
		return self::GET_SELECTED_USERS_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getDeleteEntityUrl()
	{
		return self::DELETE_USER_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getDeleteEntitiesUrl()
	{
		return self::DELETE_USERS_URL;
	}

	/**
	 * @inheritdoc
	 */
	public function getActivateEntitiesUrl()
	{
		return self::ACTIVATE_USERS_URL;
	}

}
