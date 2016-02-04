<?php

namespace DataBreakers;

use DataBreakers\DataApi\Batch\EntitiesBatch;
use DataBreakers\DataApi\Batch\InteractionsBatch;
use DataBreakers\DataApi\Client;
use DataBreakers\DataApi\DataType;
use DataBreakers\DataApi\MetaType;
use DataBreakers\DataApi\TemplateConfiguration;
use DateTime;


class Seeder
{

	const ATTRIBUTE_NAME = 'name';
	const ATTRIBUTE_DESCRIPTION = 'description';
	const ATTRIBUTE_AGE = 'age';
	const ATTRIBUTE_WEIGHT = 'weight';

	const ITEM_FOO = 'item_foo';
	const ITEM_BAR = 'item_bar';
	const ITEM_BAZ = 'item_baz';

	const USER_JOHN = 'user_john';
	const USER_PAUL = 'user_paul';
	const USER_SUZIE = 'user_suzie';

	const INTERACTION_LIKE = 'Like';
	const INTERACTION_DISLIKE = 'Dislike';
	const INTERACTION_PURCHASE = 'Purchase';
	const INTERACTION_RECOMMENDATION = 'Recommendation';
	const INTERACTION_DETAIL_VIEW = 'Detail view';
	const INTERACTION_BOOKMARK = 'Bookmark';

	const TEMPLATE_DEFAULT = 'default';
	const TEMPLATE_ID_1 = 'template1';
	const TEMPLATE_ID_2 = 'template2';
	const TEMPLATE_FILTER = 'filter > 1';
	const TEMPLATE_BOOSTER = 'booster < 2';
	const TEMPLATE_USER_WEIGHT = 0.4;
	const TEMPLATE_ITEM_WEIGHT = 0.6;
	const TEMPLATE_DIVERSITY = 0.8;

	/** @var Client */
	private $client;


	/**
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * @return void
	 */
	public function clear()
	{
		$this->clearTemplates();
		$this->clearItems();
		$this->clearUsers();
	}

	/**
	 * @return void
	 */
	public function seed()
	{
		$this->refreshUsersAttributes();
		$this->refreshItemsAttributes();
		$this->refreshInteractionsAttributes();
		$this->seedItems();
		$this->seedUsers();
		$this->seedInteractions();
		$this->seedTemplates();
	}

	/**
	 * @return void
	 */
	private function clearUsers()
	{
		$users = $this->client->getUsers();
		while (count($users['entities']) > 0) {
			foreach ($users['entities'] as $user) {
				$this->client->deleteUser($user['id'], true);
			}
			$users = $this->client->getUsers();
		}
	}

	/**
	 * @return void
	 */
	private function clearItems()
	{
		$items = $this->client->getItems();
		while (count($items['entities']) > 0) {
			foreach ($items['entities'] as $item) {
				$this->client->deleteItem($item['id'], true);
			}
			$items = $this->client->getItems();
		}
	}

	/**
	 * @return void
	 */
	private function clearTemplates()
	{
		$templates = $this->client->getTemplates();
		foreach ($templates['templates'] as $template) {
			$templateId = $template['templateId'];
			if ($templateId !== self::TEMPLATE_DEFAULT) {
				$this->client->deleteTemplate($templateId);
			}
		}
	}

	/**
	 * @return void
	 */
	private function refreshUsersAttributes()
	{
		$expectedAttributes = [self::ATTRIBUTE_NAME, self::ATTRIBUTE_AGE];
		$actualAttributes = $this->getAttributesNames($this->client->getUsersAttributes());
		foreach ($actualAttributes as $attribute) {
			if (!in_array($attribute, $expectedAttributes)) {
				$this->client->deleteUsersAttribute($attribute);
			}
		}
		if (!in_array(self::ATTRIBUTE_NAME, $actualAttributes)) {
			$this->client->addUsersAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, 'en', MetaType::TITLE);
		}
		if (!in_array(self::ATTRIBUTE_AGE, $actualAttributes)) {
			$this->client->addUsersAttribute(self::ATTRIBUTE_AGE, DataType::INTEGER);
		}
	}

	/**
	 * @return void
	 */
	private function refreshItemsAttributes()
	{
		$expectedAttributes = [self::ATTRIBUTE_NAME, self::ATTRIBUTE_DESCRIPTION, self::ATTRIBUTE_WEIGHT];
		$actualAttributes = $this->getAttributesNames($this->client->getItemsAttributes());
		foreach ($actualAttributes as $attribute) {
			if (!in_array($attribute, $expectedAttributes)) {
				$this->client->deleteItemsAttribute($attribute);
			}
		}
		if (!in_array(self::ATTRIBUTE_NAME, $actualAttributes)) {
			$this->client->addItemsAttribute(self::ATTRIBUTE_NAME, DataType::TEXT, 'en', MetaType::TITLE);
		}
		if (!in_array(self::ATTRIBUTE_DESCRIPTION, $actualAttributes)) {
			$this->client->addItemsAttribute(self::ATTRIBUTE_DESCRIPTION, DataType::TEXT, 'en');
		}
		if (!in_array(self::ATTRIBUTE_WEIGHT, $actualAttributes)) {
			$this->client->addItemsAttribute(self::ATTRIBUTE_WEIGHT, DataType::INTEGER);
		}
	}

	/**
	 * @return void
	 */
	private function refreshInteractionsAttributes()
	{
		$expectedAttributes = [self::ATTRIBUTE_DESCRIPTION, self::ATTRIBUTE_WEIGHT];
		$actualAttributes = $this->getAttributesNames($this->client->getInteractionsAttributes());
		foreach ($actualAttributes as $attribute) {
			if (!in_array($attribute, $expectedAttributes)) {
				$this->client->deleteInteractionsAttribute($attribute);
			}
		}
		if (!in_array(self::ATTRIBUTE_DESCRIPTION, $actualAttributes)) {
			$this->client->addInteractionsAttribute(self::ATTRIBUTE_DESCRIPTION, DataType::TEXT, 'en');
		}
		if (!in_array(self::ATTRIBUTE_WEIGHT, $actualAttributes)) {
			$this->client->addInteractionsAttribute(self::ATTRIBUTE_WEIGHT, DataType::INTEGER);
		}
	}

	/**
	 * @param array $attributes
	 * @return array
	 */
	private function getAttributesNames(array $attributes)
	{
		$names = [];
		foreach ($attributes['attributes'] as $attribute) {
			$names[] = $attribute['name'];
		}
		return $names;
	}

	/**
	 * @return void
	 */
	private function seedItems()
	{
		$this->client->insertOrUpdateItems((new EntitiesBatch())
			->addEntity(self::ITEM_FOO, [self::ATTRIBUTE_NAME => 'Foo'])
			->addEntity(self::ITEM_BAR, [
				self::ATTRIBUTE_NAME => 'Bar',
				self::ATTRIBUTE_DESCRIPTION => 'My favourite bar',
				self::ATTRIBUTE_WEIGHT => 150
			])
			->addEntity(self::ITEM_BAZ, [self::ATTRIBUTE_NAME => 'Baz'])
		);
	}

	/**
	 * @return void
	 */
	private function seedUsers()
	{
		$this->client->insertOrUpdateUsers((new EntitiesBatch())
			->addEntity(self::USER_JOHN, [self::ATTRIBUTE_NAME => 'John'])
			->addEntity(self::USER_PAUL, [
				self::ATTRIBUTE_NAME => 'Paul',
				self::ATTRIBUTE_AGE => 35
			])
			->addEntity(self::USER_SUZIE, [self::ATTRIBUTE_NAME => 'Suzie'])
		);
	}

	/**
	 * @return void
	 */
	private function seedInteractions()
	{
		$interactionTime = new DateTime();
		$this->client->insertInteractions((new InteractionsBatch())
			->addInteraction(self::USER_JOHN, self::ITEM_FOO, self::INTERACTION_LIKE, $interactionTime)
			->addInteraction(self::USER_JOHN, self::ITEM_BAR, self::INTERACTION_DISLIKE, $interactionTime)
			->addInteraction(self::USER_PAUL, self::ITEM_FOO, self::INTERACTION_LIKE, $interactionTime)
			->addInteraction(self::USER_PAUL, self::ITEM_FOO, self::INTERACTION_PURCHASE, $interactionTime)
			->addInteraction(self::USER_SUZIE, self::ITEM_BAZ, self::INTERACTION_DISLIKE, $interactionTime)
		);
	}

	/**
	 * @return void
	 */
	private function seedTemplates()
	{
		$this->client->insertOrUpdateTemplate(self::TEMPLATE_ID_1, (new TemplateConfiguration())
			->setFilter(self::TEMPLATE_FILTER)
			->setBooster(self::TEMPLATE_BOOSTER)
			->setDiversity(self::TEMPLATE_DIVERSITY)
		);
		$this->client->insertOrUpdateTemplate(self::TEMPLATE_ID_2, (new TemplateConfiguration())
			->setUserWeight(self::TEMPLATE_USER_WEIGHT)
			->setItemWeight(self::TEMPLATE_ITEM_WEIGHT)
			->setDiversity(self::TEMPLATE_DIVERSITY)
		);
	}

}
