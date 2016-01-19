<?php

namespace DataBreakers\DataApi;

use DataBreakers\IntegrationTestCase;
use DataBreakers\Seeder;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class InteractionDefinitionsTest extends IntegrationTestCase
{

	public function testGettingInteractionDefinitions()
	{
		$expectedDefinitions = [
			Seeder::INTERACTION_LIKE, Seeder::INTERACTION_DISLIKE,
			Seeder::INTERACTION_PURCHASE, Seeder::INTERACTION_BOOKMARK,
			Seeder::INTERACTION_DETAIL_VIEW, Seeder::INTERACTION_RECOMMENDATION
		];
		$definitions = $this->client->getInteractionDefinitions();
		Assert::same(count($expectedDefinitions), count($definitions['interactions']));
		foreach ($definitions['interactions'] as $definition) {
			Assert::contains($definition['id'], $expectedDefinitions);
		}
	}

}

(new InteractionDefinitionsTest())->run();
