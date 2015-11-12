<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Batch\EntitiesBatch;
use DataBreakers\DataApi\Batch\InteractionsBatch;
use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Sections\AttributesSection;
use DataBreakers\DataApi\Sections\EntitySection;
use DataBreakers\DataApi\Sections\InteractionsSection;
use DataBreakers\DataApi\Sections\ItemsSectionStrategy;
use DataBreakers\DataApi\Sections\RecommendationSection;
use DataBreakers\DataApi\Sections\TemplatesSection;
use DataBreakers\DataApi\Sections\UsersSectionStrategy;
use DataBreakers\DataApi\Utils\HmacSignature;
use DataBreakers\DataApi\Utils\PathBuilder;
use DateTime;
use GuzzleHttp\Client as GuzzleClient;


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

	/** @var InteractionsSection */
	private $interactionsSection;

	/** @var TemplatesSection */
	private $templatesSection;

	/** @var RecommendationSection */
	private $recommendationSection;


	/**
	 * @param string $accountId Unique identifier of account
	 * @param string $secretKey Key used for hmac signature
	 */
	public function __construct($accountId, $secretKey)
	{
		$configuration = new Configuration(Configuration::DEFAULT_HOST, Configuration::DEFAULT_SLUG, $accountId, $secretKey);
		$pathBuilder = new PathBuilder();
		$hmacSignature = new HmacSignature($configuration->getSecretKey());
		$requestFactory = new RequestFactory(new GuzzleClient(['verify' => false]));
		$this->api = new Api($configuration, $pathBuilder, $hmacSignature, $requestFactory);

		$this->attributesSection = new AttributesSection($this->api);
		$this->itemsSection = new EntitySection($this->api, new ItemsSectionStrategy());
		$this->usersSection = new EntitySection($this->api, new UsersSectionStrategy());
		$this->interactionsSection = new InteractionsSection($this->api);
		$this->templatesSection = new TemplatesSection($this->api);
		$this->recommendationSection = new RecommendationSection($this->api);
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
	 * @param DateTime|NULL $time
	 * @return NULl
	 * @throws InvalidArgumentException when given item id is empty string
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateItem($itemId, array $attributes = [], DateTime $time = NULL)
	{
		return $this->itemsSection->insertOrUpdateEntity($itemId, $attributes, $time);
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
	public function getItems($limit = EntitySection::DEFAULT_LIMIT, $offset = EntitySection::DEFAULT_OFFSET, array $attributes = NULL,
							 $orderBy = NULL, $order = NULL, $searchQuery = NULL, array $searchAttributes = NULL)
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
	public function getItem($itemId, $withInteractions = false, $interactionsLimit = EntitySection::DEFAULT_INTERACTIONS_LIMIT,
							$interactionsOffset = EntitySection::DEFAULT_INTERACTIONS_OFFSET)
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
	 * @param DateTime|NULL $time
	 * @return NULl
	 * @throws InvalidArgumentException when given user id is empty string
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateUser($userId, array $attributes = [], DateTime $time = NULL)
	{
		return $this->usersSection->insertOrUpdateEntity($userId, $attributes, $time);
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
	public function getUsers($limit = EntitySection::DEFAULT_LIMIT, $offset = EntitySection::DEFAULT_OFFSET, array $attributes = NULL,
							 $orderBy = NULL, $order = NULL, $searchQuery = NULL, array $searchAttributes = NULL)
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
	public function getUser($userId, $withInteractions = false, $interactionsLimit = EntitySection::DEFAULT_INTERACTIONS_LIMIT,
							$interactionsOffset = EntitySection::DEFAULT_INTERACTIONS_OFFSET)
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



	// ------------------------- INTERACTIONS ------------------------- //

	/**
	 * @param string $userId
	 * @param string $itemId
	 * @param string $interactionId
	 * @param DateTime|NULL $time
	 * @return NULL
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given interaction id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertInteraction($userId, $itemId, $interactionId, DateTime $time = NULL)
	{
		return $this->interactionsSection->insertInteraction($userId, $itemId, $interactionId, $time);
	}

	/**
	 * @param InteractionsBatch $batch
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertInteractions(InteractionsBatch $batch)
	{
		return $this->interactionsSection->insertInteractions($batch);
	}

	/**
	 * @param string $userId
	 * @param string $itemId
	 * @param DateTime $time
	 * @return NULL
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteInteraction($userId, $itemId, DateTime $time)
	{
		return $this->interactionsSection->deleteInteraction($userId, $itemId, $time);
	}

	/**
	 * @param string $userId
	 * @return NULL
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteUserInteractions($userId)
	{
		return $this->interactionsSection->deleteUserInteractions($userId);
	}

	/**
	 * @param string $itemId
	 * @return NULL
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteItemInteractions($itemId)
	{
		return $this->interactionsSection->deleteItemInteractions($itemId);
	}

	/**
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteInteractions()
	{
		return $this->interactionsSection->deleteInteractions();
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 * @param array|NULL $attributes
	 * @param string|NULL $searchQuery
	 * @param array|NULL $searchAttributes
	 * @return array
	 * @throws InvalidArgumentException when given limit isn't number or is negative
	 * @throws InvalidArgumentException when given offset isn't number or is negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getInteractionDefinitions($limit = InteractionsSection::DEFAULT_LIMIT, $offset = InteractionsSection::DEFAULT_OFFSET,
											  array $attributes = NULL, $searchQuery = NULL, array $searchAttributes = NULL)
	{
		return $this->interactionsSection->getInteractionDefinitions($limit, $offset, $attributes, $searchQuery, $searchAttributes);
	}



	// ------------------------- RECOMMENDATION ------------------------- //

	/**
	 * @param string $userId
	 * @param string $itemId
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendations($userId, $itemId, $count, $templateId = NULL,
									   RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->recommendationSection->getRecommendations($userId, $itemId, $count, $templateId, $configuration);
	}

	/**
	 * @param string $userId
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendationsForUser($userId, $count, $templateId = NULL,
											  RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->recommendationSection->getRecommendationsForUser($userId, $count, $templateId, $configuration);
	}

	/**
	 * @param string $itemId
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendationsForItem($itemId, $count, $templateId = NULL,
											  RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->recommendationSection->getRecommendationsForItem($itemId, $count, $templateId, $configuration);
	}

	/**
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getGeneralRecommendations($count, $templateId = NULL,
											  RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->recommendationSection->getGeneralRecommendations($count, $templateId, $configuration);
	}



	// ------------------------- TEMPLATES ------------------------- //

	/**
	 * @param string $templateId
	 * @param TemplateConfiguration $configuration
	 * @return NULL
	 * @throws InvalidArgumentException when given template id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateTemplate($templateId, TemplateConfiguration $configuration)
	{
		return $this->templatesSection->insertOrUpdateTemplate($templateId, $configuration);
	}

	/**
	 * @return array
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getTemplates()
	{
		return $this->templatesSection->getTemplates();
	}

	/**
	 * @param string $templateId
	 * @return array
	 * @throws InvalidArgumentException when given template id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getTemplate($templateId)
	{
		return $this->templatesSection->getTemplate($templateId);
	}

	/**
	 * @param string $templateId
	 * @return NULL
	 * @throws InvalidArgumentException when given template id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteTemplate($templateId)
	{
		return $this->templatesSection->deleteTemplate($templateId);
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
