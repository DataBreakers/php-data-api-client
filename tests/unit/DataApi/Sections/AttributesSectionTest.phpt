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

	public function testAddingInteractionsAttribute()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT);
		$this->mockPerformPost(AttributesSection::ADD_INTERACTIONS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addInteractionsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT);
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

	public function testAddingInteractionsAttributeWhenLanguageIsSet()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT, self::LANGUAGE);
		$this->mockPerformPost(AttributesSection::ADD_INTERACTIONS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addInteractionsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, self::LANGUAGE);
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

	public function testAddingInteractionsAttributeWhenMetaTypeIsSet()
	{
		$content = $this->getContentForAdding(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, MetaType::TITLE);
		$this->mockPerformPost(AttributesSection::ADD_INTERACTIONS_ATTRIBUTE_URL, [], $content);
		$this->attributesSection->addInteractionsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, MetaType::TITLE);
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
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameIsEmptyDuringAddingInteractionsAttribute()
	{
		$this->attributesSection->addInteractionsAttribute('', DataType::TEXT);
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
	 * @param string $name
	 *
	 * @dataProvider getInvalidAttributeNames
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameDoNotMatchPatternDuringAddingInteractionsAttribute($name)
	{
		$this->attributesSection->addInteractionsAttribute($name, DataType::TEXT);
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
	public function testThrowingExceptionWhenDataTypeIsEmptyDuringAddingInteractionsAttribute()
	{
		$this->attributesSection->addInteractionsAttribute(self::ATTRIBUTE_NAME, '');
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
	public function testThrowingExceptionWhenDataTypeIsNotValidDuringAddingInteractionsAttribute()
	{
		$this->attributesSection->addInteractionsAttribute(self::ATTRIBUTE_NAME, 'foo');
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

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenMetaTypeIsNotValidDuringAddingInteractionsAttribute()
	{
		$this->attributesSection->addInteractionsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, NULL, 'foo');
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

	public function testGettingInteractionsAttributes()
	{
		$this->mockPerformGet(AttributesSection::GET_INTERACTIONS_ATTRIBUTES_URL);
		$this->attributesSection->getInteractionsAttributes();
	}

	public function testUpdatingUsersAttributeDescription()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription()];
		$this->mockPerformPost(AttributesSection::UPDATE_USERS_ATTRIBUTE_DESCRIPTION_URL, $params, $content);
		$this->attributesSection->updateUsersAttributeDescription(self::ATTRIBUTE_NAME);
	}

	public function testUpdatingItemsAttributeDescription()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription()];
		$this->mockPerformPost(AttributesSection::UPDATE_ITEMS_ATTRIBUTE_DESCRIPTION_URL, $params, $content);
		$this->attributesSection->updateItemsAttributeDescription(self::ATTRIBUTE_NAME);
	}

	public function testUpdatingInteractionsAttributeDescription()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription()];
		$this->mockPerformPost(AttributesSection::UPDATE_INTERACTIONS_DESCRIPTION_ATTRIBUTE_URL, $params, $content);
		$this->attributesSection->updateInteractionsAttributeDescription(self::ATTRIBUTE_NAME);
	}

	public function testUpdatingUsersAttributeDescriptionWhenLanguageIsSet()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription(self::LANGUAGE)];
		$this->mockPerformPost(AttributesSection::UPDATE_USERS_ATTRIBUTE_DESCRIPTION_URL, $params, $content);
		$this->attributesSection->updateUsersAttributeDescription(self::ATTRIBUTE_NAME, self::LANGUAGE);
	}

	public function testUpdatingItemsAttributeDescriptionWhenLanguageIsSet()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription(self::LANGUAGE)];
		$this->mockPerformPost(AttributesSection::UPDATE_ITEMS_ATTRIBUTE_DESCRIPTION_URL, $params, $content);
		$this->attributesSection->updateItemsAttributeDescription(self::ATTRIBUTE_NAME, self::LANGUAGE);
	}

	public function testUpdatingInteractionsAttributeDescriptionWhenLanguageIsSet()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription(self::LANGUAGE)];
		$this->mockPerformPost(AttributesSection::UPDATE_INTERACTIONS_DESCRIPTION_ATTRIBUTE_URL, $params, $content);
		$this->attributesSection->updateInteractionsAttributeDescription(self::ATTRIBUTE_NAME, self::LANGUAGE);
	}

	public function testUpdatingUsersAttributeDescriptionWhenMetaTypeIsSet()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription(NULL, MetaType::TITLE)];
		$this->mockPerformPost(AttributesSection::UPDATE_USERS_ATTRIBUTE_DESCRIPTION_URL, $params, $content);
		$this->attributesSection->updateUsersAttributeDescription(self::ATTRIBUTE_NAME, NULL, MetaType::TITLE);
	}

	public function testUpdatingItemsAttributeDescriptionWhenMetaTypeIsSet()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription(NULL, MetaType::TITLE)];
		$this->mockPerformPost(AttributesSection::UPDATE_ITEMS_ATTRIBUTE_DESCRIPTION_URL, $params, $content);
		$this->attributesSection->updateItemsAttributeDescription(self::ATTRIBUTE_NAME, NULL, MetaType::TITLE);
	}

	public function testUpdatingInteractionsAttributeDescriptionWhenMetaTypeIsSet()
	{
		$params = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$content = [AttributesSection::DESCRIPTION_PARAMETER => $this->getContentForDescription(NULL, MetaType::TITLE)];
		$this->mockPerformPost(AttributesSection::UPDATE_INTERACTIONS_DESCRIPTION_ATTRIBUTE_URL, $params, $content);
		$this->attributesSection->updateInteractionsAttributeDescription(self::ATTRIBUTE_NAME, NULL, MetaType::TITLE);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameIsEmptyDuringUpdatingUsersAttributeDescription()
	{
		$this->attributesSection->updateUsersAttributeDescription('');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameIsEmptyDuringUpdatingItemsAttributeDescription()
	{
		$this->attributesSection->updateItemsAttributeDescription('');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameIsEmptyDuringUpdatingInteractionsAttributeDescription()
	{
		$this->attributesSection->updateInteractionsAttributeDescription('');
	}

	/**
	 * @param string $name
	 *
	 * @dataProvider getInvalidAttributeNames
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameDoNotMatchPatternDuringUpdatingUsersAttributeDescription($name)
	{
		$this->attributesSection->updateUsersAttributeDescription($name);
	}

	/**
	 * @param string $name
	 *
	 * @dataProvider getInvalidAttributeNames
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameDoNotMatchPatternDuringUpdatingItemsAttributeDescription($name)
	{
		$this->attributesSection->updateItemsAttributeDescription($name);
	}

	/**
	 * @param string $name
	 *
	 * @dataProvider getInvalidAttributeNames
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenNameDoNotMatchPatternDuringUpdatingInteractionsAttributeDescription($name)
	{
		$this->attributesSection->updateInteractionsAttributeDescription($name);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenMetaTypeIsNotValidDuringUpdatingUsersAttributeDescription()
	{
		$this->attributesSection->updateUsersAttributeDescription(self::ATTRIBUTE_NAME, NULL, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenMetaTypeIsNotValidDuringUpdatingItemsAttributeDescription()
	{
		$this->attributesSection->updateItemsAttributeDescription(self::ATTRIBUTE_NAME, NULL, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenMetaTypeIsNotValidDuringUpdatingInteractionsAttributeDescription()
	{
		$this->attributesSection->updateInteractionsAttributeDescription(self::ATTRIBUTE_NAME, NULL, 'foo');
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

	public function testDeletingInteractionsAttribute()
	{
		$expectedParameters = [AttributesSection::ATTRIBUTE_NAME_PARAMETER => self::ATTRIBUTE_NAME];
		$this->mockPerformDelete(AttributesSection::DELETE_INTERACTIONS_ATTRIBUTE_URL, $expectedParameters);
		$this->attributesSection->deleteInteractionsAttribute(self::ATTRIBUTE_NAME);
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
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAttributeNameIsEmptyDuringDeletingInteractionsAttribute()
	{
		$this->attributesSection->deleteInteractionsAttribute('');
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
		$description = $this->getContentForDescription($language, $metaType);
		if ($description !== []) {
			$content[AttributesSection::DESCRIPTION_PARAMETER] = $description;
		}
		return $content;
	}

	/**
	 * @param string|NULL $language
	 * @param string|NULL $metaType
	 * @return array
	 */
	private function getContentForDescription($language = NULL, $metaType = NULL)
	{
		$description = [];
		if ($language !== NULL) {
			$description[AttributesSection::LANGUAGE_PARAMETER] = $language;
		}
		if ($metaType !== NULL) {
			$description[AttributesSection::META_TYPE_PARAMETER] = $metaType;
		}
		return $description;
	}

}

(new AttributesSectionTest())->run();
