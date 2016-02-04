<?php

namespace DataBreakers\DataApi;

use DataBreakers\IntegrationTestCase;
use DataBreakers\Seeder;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class UsersAttributesTest extends IntegrationTestCase
{

	const ATTRIBUTE_CITY = 'city';


	public function testAddingAttribute()
	{
		$this->client->addUsersAttribute(self::ATTRIBUTE_CITY, DataType::TEXT);
		$this->validateExpectedAttributes([Seeder::ATTRIBUTE_NAME, Seeder::ATTRIBUTE_AGE, self::ATTRIBUTE_CITY]);
	}

	public function testGettingAttributes()
	{
		$this->validateExpectedAttributes([Seeder::ATTRIBUTE_NAME, Seeder::ATTRIBUTE_AGE]);
	}

	public function testUpdatingAttributesDescription()
	{
		$this->client->updateUsersAttributeDescription(Seeder::ATTRIBUTE_NAME, 'cz', MetaType::PRICE);
		$attributes = $this->client->getUsersAttributes();
		foreach ($attributes['attributes'] as $attribute) {
			if ($attribute['name'] === Seeder::ATTRIBUTE_NAME) {
				Assert::same('cz', $attribute['description']['language']);
				Assert::same(MetaType::PRICE, $attribute['description']['metaType']);
			}
		}
	}

	public function testDeletingAttribute()
	{
		$this->client->deleteUsersAttribute(Seeder::ATTRIBUTE_AGE);
		$this->validateExpectedAttributes([Seeder::ATTRIBUTE_NAME]);
	}

	/**
	 * @param array $expectedAttributes
	 * @return void
	 */
	private function validateExpectedAttributes(array $expectedAttributes)
	{
		$attributes = $this->client->getUsersAttributes();
		Assert::same(count($expectedAttributes), count($attributes['attributes']));
		foreach ($attributes['attributes'] as $attribute) {
			Assert::true(in_array($attribute['name'], $expectedAttributes));
		}
	}

}

(new UsersAttributesTest())->run();
