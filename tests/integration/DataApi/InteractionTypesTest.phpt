<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Batch\InteractionsBatch;
use DataBreakers\IntegrationTestCase;
use DataBreakers\Seeder;
use DateTime;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class InteractionTypesTest extends IntegrationTestCase
{

	const INTERACTION_BOOKMARK = 'bookmark';


	public function testInsertingInteractionType()
	{
		$this->client->insertOrUpdateInteractionType(self::INTERACTION_BOOKMARK);
		$this->verifyInteractionTypes([
			Seeder::INTERACTION_LIKE,
			Seeder::INTERACTION_DISLIKE,
			Seeder::INTERACTION_PURCHASE,
			Seeder::INTERACTION_RECOMMENDATION,
			self::INTERACTION_BOOKMARK
		]);
	}
	
	public function testGettingInteractionTypes()
	{
		$this->verifyInteractionTypes([
			Seeder::INTERACTION_LIKE,
			Seeder::INTERACTION_DISLIKE,
			Seeder::INTERACTION_PURCHASE,
			Seeder::INTERACTION_RECOMMENDATION
		]);
	}

	public function testGettingInteractionType()
	{
		$interactionType = $this->client->getInteractionType(Seeder::INTERACTION_DISLIKE);
		Assert::same(Seeder::INTERACTION_DISLIKE, $interactionType['id']);
		Assert::same(InteractionMetaType::ACTION, $interactionType['interactionMetaType']);
		Assert::same(Seeder::INTERACTION_WEIGHT2, $interactionType['weight']);
		Assert::same(Seeder::INTERACTION_WEIGHT1, $interactionType['learnWeight']);
		Assert::same('Foo', $interactionType['attributes'][Seeder::ATTRIBUTE_DESCRIPTION]);
		Assert::same(150, $interactionType['attributes'][Seeder::ATTRIBUTE_WEIGHT]);
	}
	
	public function testDeletingInteractionType()
	{
		$this->client->deleteInteractionType(Seeder::INTERACTION_PURCHASE);
		$this->verifyInteractionTypes([
			Seeder::INTERACTION_LIKE,
			Seeder::INTERACTION_DISLIKE,
			Seeder::INTERACTION_RECOMMENDATION
		]);
	}

	/**
	 * @param array $expectedInteractionTypes
	 * @return void
	 */
	private function verifyInteractionTypes(array $expectedInteractionTypes)
	{
		$interactionTypes = $this->client->getInteractionTypes();
		foreach ($interactionTypes['interactions'] as $interaction) {
			Assert::contains($interaction['id'], $expectedInteractionTypes);
		}
	}

}

(new InteractionTypesTest())->run();
