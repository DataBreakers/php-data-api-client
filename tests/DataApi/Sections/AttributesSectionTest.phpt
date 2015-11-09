<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\DataType;
use DataBreakers\DataApi\MetaType;

require_once __DIR__ . '/../../bootstrap.php';


class AttributesSectionTest extends SectionTest
{

	const ATTRIBUTE_NAME = 'Foo_bar4';
	const LANGUAGE = 'en';

	/** @var AttributesSection */
	private $attributesSection;


	protected function setUp()
	{
		parent::setUp();
		$this->attributesSection = new AttributesSection($this->api);
	}

	public function testAddingUsersAttribute()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT);
		$this->mockPerformPost(AttributesSection::ADD_USERS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addUsersAttribute(self::ATTRIBUTE_NAME, DataType::TEXT);
	}

	public function testAddingItemsAttribute()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT);
		$this->mockPerformPost(AttributesSection::ADD_ITEMS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addItemsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT);
	}

	public function testAddingUsersAttributeWhenLanguageIsSet()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT, self::LANGUAGE);
		$this->mockPerformPost(AttributesSection::ADD_USERS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addUsersAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, self::LANGUAGE);
	}

	public function testAddingItemsAttributeWhenLanguageIsSet()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT, self::LANGUAGE);
		$this->mockPerformPost(AttributesSection::ADD_ITEMS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addItemsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, self::LANGUAGE);
	}

	public function testAddingUsersAttributeWhenMetaTypeIsSet()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, MetaType::TITLE);
		$this->mockPerformPost(AttributesSection::ADD_USERS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addUsersAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, MetaType::TITLE);
	}

	public function testAddingItemsAttributeWhenMetaTypeIsSet()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, MetaType::TITLE);
		$this->mockPerformPost(AttributesSection::ADD_ITEMS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addItemsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, MetaType::TITLE);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameIsEmptyDuringAddingUsersAttribute()
	{
		$this->attributesSection->addUsersAttribute('', DataType::TEXT);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameIsEmptyDuringAddingItemsAttribute()
	{
		$this->attributesSection->addItemsAttribute('', DataType::TEXT);
	}

	/**
	 * @param string $name
	 *
	 * @dataProvider getInvalidAttributeNames
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameDoNotMatchPatternDuringAddingUsersAttribute($name)
	{
		$this->attributesSection->addUsersAttribute($name, DataType::TEXT);
	}

	/**
	 * @param string $name
	 *
	 * @dataProvider getInvalidAttributeNames
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameDoNotMatchPatternDuringAddingItemsAttribute($name)
	{
		$this->attributesSection->addItemsAttribute($name, DataType::TEXT);
	}

	/**
	 * @return array
	 */
	public function getInvalidAttributeNames()
	{
		return [
			['foo%bar'],
			['1foo'],
			['_foo']
		];
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenDataTypeIsEmptyDuringAddingUsersAttribute()
	{
		$this->attributesSection->addUsersAttribute(self::ATTRIBUTE_NAME, '');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenDataTypeIsEmptyDuringAddingItemsAttribute()
	{
		$this->attributesSection->addItemsAttribute(self::ATTRIBUTE_NAME, '');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenDataTypeIsNotValidDuringAddingUsersAttribute()
	{
		$this->attributesSection->addUsersAttribute(self::ATTRIBUTE_NAME, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenDataTypeIsNotValidDuringAddingItemsAttribute()
	{
		$this->attributesSection->addItemsAttribute(self::ATTRIBUTE_NAME, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenMetaTypeIsNotValidDuringAddingUsersAttribute()
	{
		$this->attributesSection->addUsersAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenMetaTypeIsNotValidDuringAddingItemsAttribute()
	{
		$this->attributesSection->addItemsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, 'foo');
	}

	public function testGettingUsersAttributes()
	{
		$this->mockPerformGet(AttributesSection::GET_USERS_ATTRIBUTES_URL);
		$this->attributesSection->getUsersAttributes();
	}

	public function testGettingItemsAttributes()
	{
		$this->mockPerformGet(AttributesSection::GET_ITEMS_ATTRIBUTES_URL);
		$this->attributesSection->getItemsAttributes();
	}

	public function testDeletingUsersAttribute()
	{
		$expectedParameters = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$this->mockPerformDelete(AttributesSection::DELETE_USERS_ATTRIBUTE_URL, $expectedParameters);
		$this->attributesSection->deleteUsersAttribute(self::ATTRIBUTE_NAME);
	}

	public function testDeletingItemsAttribute()
	{
		$expectedParameters = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$this->mockPerformDelete(AttributesSection::DELETE_ITEMS_ATTRIBUTE_URL, $expectedParameters);
		$this->attributesSection->deleteItemsAttribute(self::ATTRIBUTE_NAME);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAttributeNameIsEmptyDuringDeletingUsersAttribute()
	{
		$this->attributesSection->deleteUsersAttribute('');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAttributeNameIsEmptyDuringDeletingItemsAttribute()
	{
		$this->attributesSection->deleteItemsAttribute('');
	}

	/**
	 * @param string $name
	 * @param string $dataType
	 * @param string|NULL $language
	 * @param string|NULL $metaType
	 * @return array
	 */
	private function getContentForAdding($name, $dataType, $language = NULL, $metaType = NULL)
	{
		$content = [
			AttributesSection::NAME_PARAMETER => $name,
			AttributesSection::DATA_TYPE_PARAMETER => $dataType
		];
		$description = [];
		if ($language !== NULL) {
			$description[AttributesSection::LANGUAGE_PARAMETER] = $language;
		}
		if ($metaType !== NULL) {
			$description[AttributesSection::META_TYPE_PARAMETER] = $metaType;
		}
		if ($description !== []) {
			$content[AttributesSection::DESCRIPTION_PARAMETER] = $description;
		}
		return $content;
	}

}

(new AttributesSectionTest())->run();
