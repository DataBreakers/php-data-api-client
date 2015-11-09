<?php

namespace DataBreakers\DataApi;


use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Sections\AttributesSection;


class Client
{

	const DEFAULT_HOST = 'https://api.databreakers.com';
	const DEFAULT_SLUG = '/v1';

	/** @var Api */
	private $api;

	/** @var AttributesSection */
	private $attributesSection;


	/**
	 * @param string $accountId Unique identifier of account
	 * @param string $secretKey Key used for hmac signature
	 */
	public function __construct($accountId, $secretKey)
	{
		$configuration = new Configuration(self::DEFAULT_HOST, self::DEFAULT_SLUG, $accountId, $secretKey);
		$this->api = new Api($configuration);
		$this->attributesSection = new AttributesSection($this->api);
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
