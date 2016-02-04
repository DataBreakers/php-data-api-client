<?php

namespace DataBreakers\DataApi;

use DataBreakers\IntegrationTestCase;
use DataBreakers\Seeder;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class InteractionsAttributesTest extends IntegrationTestCase
{

	const ATTRIBUTE_RATING = 'rating';


	public function testAddingAttribute()
	{
		$this->client->addInteractionsAttribute(self::ATTRIBUTE_RATING, DataType::FLOAT);
		$this->validateExpectedAttributes([
			Seeder::ATTRIBUTE_DESCRIPTION,
			Seeder::ATTRIBUTE_WEIGHT,
			self::ATTRIBUTE_RATING
		]);
	}

	public function testGettingAttributes()
	{
		$this->validateExpectedAttributes([
			Seeder::ATTRIBUTE_DESCRIPTION,
			Seeder::ATTRIBUTE_WEIGHT
		]);
	}

	public function testUpdatingAttributesDescription()
	{
		$this->client->updateInteractionsAttributeDescription(Seeder::ATTRIBUTE_DESCRIPTION, 'cz', MetaType::PRICE);
		$attributes = $this->client->getInteractionsAttributes();
		foreach ($attributes['attributes'] as $attribute) {
			if ($attribute['name'] === Seeder::ATTRIBUTE_DESCRIPTION) {
				Assert::same('cz', $attribute['description']['language']);
				Assert::same(MetaType::PRICE, $attribute['description']['metaType']);
			}
		}
	}

	public function testDeletingAttribute()
	{
		$this->client->deleteInteractionsAttribute(Seeder::ATTRIBUTE_DESCRIPTION);
		$this->validateExpectedAttributes([Seeder::ATTRIBUTE_WEIGHT]);
	}

	/**
	 * @param array $expectedAttributes
	 * @return void
	 */
	private function validateExpectedAttributes(array $expectedAttributes)
	{
		$attributes = $this->client->getInteractionsAttributes();
		Assert::same(count($expectedAttributes), count($attributes['attributes']));
		foreach ($attributes['attributes'] as $attribute) {
			Assert::true(in_array($attribute['name'], $expectedAttributes));
		}
	}

}

(new InteractionsAttributesTest())->run();
