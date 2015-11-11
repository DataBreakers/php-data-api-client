<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Sections\AttributesSection;
use DataBreakers\DataApi\Sections\EntitySection;
use DataBreakers\DataApi\Sections\ItemsSection;
use DataBreakers\DataApi\Sections\ItemsSectionStrategy;
use DataBreakers\DataApi\Sections\UsersSectionStrategy;


class Client
{

	/** @var Api */
	private $api;

	/** @var AttributesSection */
	private $attributesSection;

	/** @var EntitySection */
	private $itemsSection;

	/** @var EntitySection */
	private $usersSection;


	/**
	 * @param string $accountId Unique identifier of account
	 * @param string $secretKey Key used for hmac signature
	 */
	public function __construct($accountId, $secretKey)
	{
		$configuration = new Configuration(Configuration::DEFAULT_HOST, Configuration::DEFAULT_SLUG, $accountId, $secretKey);
		$this->api = new Api($configuration);
		$this->attributesSection = new AttributesSection($this->api);
		$this->itemsSection = new EntitySection($this->api, new ItemsSectionStrategy());
		$this->usersSection = new EntitySection($this->api, new UsersSectionStrategy());
	}



	// ------------------------- ATTRIBUTES ------------------------- //

	/**
	 * @param string $name
	 * @param string $dataType
	 * @param string|NULL $language
	 * @param string|NULL $metaType
	 * @return NULL
	 * @throws InvalidArgumentException when given name is empty
	 * @throws InvalidArgumentException when given name doesn't match required pattern
	 * @throws InvalidArgumentException when given data type is empty
	 * @throws InvalidArgumentException when given data type isn't known data type
	 * @throws InvalidArgumentException when given meta type isn't known meta type
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function addUsersAttribute($name, $dataType, $language = NULL, $metaType = NULL)
	{
		return $this->attributesSection->addUsersAttribute($name, $dataType, $language, $metaType);
	}

	/**
	 * @param string $name
	 * @param string $dataType
	 * @param string|NULL $language
	 * @param string|NULL $metaType
	 * @return NULL
	 * @throws InvalidArgumentException when given name is empty
	 * @throws InvalidArgumentException when given name doesn't match required pattern
	 * @throws InvalidArgumentException when given data type is empty
	 * @throws InvalidArgumentException when given data type isn't known data type
	 * @throws InvalidArgumentException when given meta type isn't known meta type
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function addItemsAttribute($name, $dataType, $language = NULL, $metaType = NULL)
	{
		return $this->attributesSection->addItemsAttribute($name, $dataType, $language, $metaType);
	}

	/**
	 * @return array
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getUsersAttributes()
	{
		return $this->attributesSection->getUsersAttributes();
	}

	/**
	 * @return array
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getItemsAttributes()
	{
		return $this->attributesSection->getItemsAttributes();
	}

	/**
	 * @param string $attributeName
	 * @return NULL
	 * @throws InvalidArgumentException when given attribute name is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteUsersAttribute($attributeName)
	{
		return $this->attributesSection->deleteUsersAttribute($attributeName);
	}

	/**
	 * @param string $attributeName
	 * @return NULL
	 * @throws InvalidArgumentException when given attribute name is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteItemsAttribute($attributeName)
	{
		return $this->attributesSection->deleteItemsAttribute($attributeName);
	}



	// ------------------------- ITEMS ------------------------- //

	/**
	 * @param string $itemId
	 * @param array $attributes
	 * @return NULl
	 * @throws InvalidArgumentException when given item id is empty string
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateItem($itemId, array $attributes = [])
	{
		return $this->itemsSection->insertOrUpdateEntity($itemId, $attributes);
	}

	/**
	 * @param EntitiesBatch $items
	 * @return NULl
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateItems(EntitiesBatch $items)
	{
		return $this->itemsSection->insertOrUpdateEntities($items);
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
	public function getItems($limit = 100, $offset = 0, array $attributes = NULL, $orderBy = NULL, $order = NULL,
							 $searchQuery = NULL, array $searchAttributes = NULL)
	{
		return $this->itemsSection->getEntities($limit, $offset, $attributes, $orderBy, $order, $searchQuery, $searchAttributes);
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
		return $this->itemsSection->getEntity($itemId, $withInteractions, $interactionsLimit, $interactionsOffset);
	}

	/**
	 * @param string[] $ids
	 * @returns array
	 * @throws InvalidArgumentException when given array of ids is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getSelectedItems(array $ids)
	{
		return $this->itemsSection->getSelectedEntities($ids);
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
		return $this->itemsSection->deleteEntity($itemId, $permanently);
	}

	/**
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteItems()
	{
		return $this->itemsSection->deleteEntities();
	}



	// ------------------------- USERS ------------------------- //

	/**
	 * @param string $userId
	 * @param array $attributes
	 * @return NULl
	 * @throws InvalidArgumentException when given user id is empty string
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateUser($userId, array $attributes = [])
	{
		return $this->usersSection->insertOrUpdateEntity($userId, $attributes);
	}

	/**
	 * @param EntitiesBatch $users
	 * @return NULl
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateUsers(EntitiesBatch $users)
	{
		return $this->usersSection->insertOrUpdateEntities($users);
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
	public function getUsers($limit = 100, $offset = 0, array $attributes = NULL, $orderBy = NULL, $order = NULL,
							 $searchQuery = NULL, array $searchAttributes = NULL)
	{
		return $this->usersSection->getEntities($limit, $offset, $attributes, $orderBy, $order, $searchQuery, $searchAttributes);
	}

	/**
	 * @param string $userId
	 * @param bool $withInteractions
	 * @param int $interactionsLimit
	 * @param int $interactionsOffset
	 * @returns array
	 * @throws InvalidArgumentException when given user id is empty
	 * @throws InvalidArgumentException when given interactions limit isn't number or is negative
	 * @throws InvalidArgumentException when given interactions offset isn't number or is negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getUser($userId, $withInteractions = false, $interactionsLimit = 100, $interactionsOffset = 0)
	{
		return $this->usersSection->getEntity($userId, $withInteractions, $interactionsLimit, $interactionsOffset);
	}

	/**
	 * @param string[] $ids
	 * @returns array
	 * @throws InvalidArgumentException when given array of ids is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getSelectedUsers(array $ids)
	{
		return $this->usersSection->getSelectedEntities($ids);
	}

	/**
	 * @param string $userId
	 * @param bool $permanently
	 * @return array|NULL
	 * @throws InvalidArgumentException when given user id is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteUser($userId, $permanently = false)
	{
		return $this->usersSection->deleteEntity($userId, $permanently);
	}

	/**
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteUsers()
	{
		return $this->usersSection->deleteEntities();
	}



	// ------------------------- CONFIGURATION ------------------------- //

	/**
	 * @param string $host
	 * @return $this
	 */
	public function changeHost($host)
	{
		$this->api->changeHost($host);
		return $this;
	}

	/**
	 * @param string $slug
	 * @return $this
	 */
	public function changeSlug($slug)
	{
		$this->api->changeSlug($slug);
		return $this;
	}

}
