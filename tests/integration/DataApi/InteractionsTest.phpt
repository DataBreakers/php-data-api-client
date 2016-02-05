<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Batch\InteractionsBatch;
use DataBreakers\IntegrationTestCase;
use DataBreakers\Seeder;
use DateTime;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class InteractionsTest extends IntegrationTestCase
{

	public function testInsertingInteraction()
	{
		$this->client->insertInteraction(Seeder::USER_JOHN, Seeder::ITEM_BAZ, Seeder::INTERACTION_PURCHASE, new DateTime());
		$user = $this->client->getUser(Seeder::USER_JOHN, true);
		$this->validateExpectedInteractions($user, [
			Seeder::ITEM_FOO => Seeder::INTERACTION_LIKE,
			Seeder::ITEM_BAR => Seeder::INTERACTION_DISLIKE,
			Seeder::ITEM_BAZ => Seeder::INTERACTION_PURCHASE
		]);
	}

	public function testInsertingMultipleInteractions()
	{
		$this->client->insertInteractions((new InteractionsBatch())
			->addInteraction(Seeder::USER_SUZIE, Seeder::ITEM_FOO, Seeder::INTERACTION_PURCHASE, new DateTime())
			->addInteraction(Seeder::USER_SUZIE, Seeder::ITEM_BAR, Seeder::INTERACTION_DISLIKE, new DateTime())
		);
		$user = $this->client->getUser(Seeder::USER_SUZIE, true);
		$this->validateExpectedInteractions($user, [
			Seeder::ITEM_FOO => Seeder::INTERACTION_PURCHASE,
			Seeder::ITEM_BAR => Seeder::INTERACTION_DISLIKE,
			Seeder::ITEM_BAZ => Seeder::INTERACTION_DISLIKE
		]);
	}

	public function testGettingInteractionInUserEntity()
	{
		$user = $this->client->getUser(Seeder::USER_JOHN, true);
		$this->validateExpectedInteractions($user, [
			Seeder::ITEM_FOO => Seeder::INTERACTION_LIKE,
			Seeder::ITEM_BAR => Seeder::INTERACTION_DISLIKE
		]);
	}

	public function testGettingInteractionInItemEntity()
	{
		$item = $this->client->getItem(Seeder::ITEM_BAZ, true);
		$this->validateExpectedInteractions($item, [Seeder::USER_SUZIE => Seeder::INTERACTION_DISLIKE]);
	}

	public function testDeletingInteraction()
	{
		$interactionTime = new DateTime();
		$this->client->insertInteraction(Seeder::USER_JOHN, Seeder::ITEM_BAZ, Seeder::INTERACTION_PURCHASE, $interactionTime);
		$this->client->deleteInteraction(Seeder::USER_JOHN, Seeder::ITEM_BAZ, $interactionTime);
		$user = $this->client->getUser(Seeder::USER_JOHN, true);
		$this->validateExpectedInteractions($user, [
			Seeder::ITEM_FOO => Seeder::INTERACTION_LIKE,
			Seeder::ITEM_BAR => Seeder::INTERACTION_DISLIKE,
		]);
	}

	public function testDeletingUserInteractions()
	{
		var_dump($this->client->getUser(Seeder::USER_JOHN, true));
		$this->client->deleteUserInteractions(Seeder::USER_JOHN);
		$user = $this->client->getUser(Seeder::USER_JOHN, true);
		$this->validateExpectedInteractions($user, []);
	}

	public function testDeletingItemInteractions()
	{
		$this->client->deleteItemInteractions(Seeder::ITEM_FOO);
		$item = $this->client->getItem(Seeder::ITEM_FOO, true);
		$this->validateExpectedInteractions($item, []);
	}

	/**
	 * @param array $entityData
	 * @param array $expectedInteractions
	 * @return void
	 */
	private function validateExpectedInteractions(array $entityData, array $expectedInteractions)
	{
		$expectedInteractedEntities = array_keys($expectedInteractions);
		$actualInteractions = $entityData['entityInteractions'];
		Assert::same(count($expectedInteractedEntities), count($actualInteractions));
		foreach ($actualInteractions as $interaction) {
			$entityId = $interaction['entityId'];
			Assert::true(in_array($entityId, $expectedInteractedEntities));
			Assert::same($expectedInteractions[$entityId], $interaction['interactionId']);
		}
	}

}

(new InteractionsTest())->run();
