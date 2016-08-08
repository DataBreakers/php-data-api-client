<?php

namespace DataBreakers\DataApi\Utils;

use DataBreakers\DataApi\Batch\RecommendationEntitiesBatch;
use DataBreakers\DataApi\RecommendationTemplateConfiguration;
use DataBreakers\UnitTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


class RecommendationContentBuilderTest extends UnitTestCase
{

	const USER_ID1 = 'user1';
	const USER_ID2 = 'user2';
	const ITEM_ID1 = 'item1';
	const ITEM_ID2 = 'item2';
	const COUNT = 10;
	const TEMPLATE_ID = 'template1';
	const FILTER = 'filter > 1';
	const BOOSTER = 'booster < 2';
	const USER_WEIGHT = 0.4;
	const ITEM_WEIGHT = 0.6;
	const DIVERSITY = 0.8;
	const DIVERSITY_DECAY = 0.5;
	const INTERACTION = 'interaction';
	const INTERACTION_WEIGHT = 0.25;
	const OFFSET = 10;

	/** @var RecommendationContentBuilder */
	private $builder;


	protected function setUp()
	{
		parent::setUp();
		$this->builder = new RecommendationContentBuilder();
	}

	public function testSettingMultipleSettings()
	{
		$expected = [
			RecommendationContentBuilder::USER_ID_PARAMETER => self::USER_ID1,
			RecommendationContentBuilder::ITEM_ID_PARAMETER => self::ITEM_ID1,
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::TEMPLATE_ID_PARAMETER => self::TEMPLATE_ID,
				RecommendationContentBuilder::FILTER_PARAMETER => self::FILTER,
				RecommendationContentBuilder::BOOSTER_PARAMETER => self::BOOSTER,
				RecommendationContentBuilder::USER_WEIGHT_PARAMETER => self::USER_WEIGHT,
				RecommendationContentBuilder::ITEM_WEIGHT_PARAMETER => self::ITEM_WEIGHT
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setFilter(self::FILTER)
			->setBooster(self::BOOSTER)
			->setUserWeight(self::USER_WEIGHT)
			->setItemWeight(self::ITEM_WEIGHT);
		$this->validateRecommendationContent($expected, self::USER_ID1, self::ITEM_ID1, self::COUNT, self::TEMPLATE_ID, $configuration);
	}
	
	public function testSettingOnlyCount()
	{
		$expected = [RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT];
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT);
	}

	public function testSettingUserId()
	{
		$expected = [
			RecommendationContentBuilder::USER_ID_PARAMETER => self::USER_ID1,
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT
		];
		$this->validateRecommendationContent($expected, self::USER_ID1, NULL, self::COUNT);
	}

	public function testSettingUsers()
	{
		$expected = [
			RecommendationContentBuilder::USERS_PARAMETER => [
				[
					RecommendationContentBuilder::USER_ID_PARAMETER => self::USER_ID1
				],
				[
					RecommendationContentBuilder::USER_ID_PARAMETER => self::USER_ID2,
					RecommendationEntitiesBatch::INTERACTIONS_KEY => [
						self::INTERACTION => self::INTERACTION_WEIGHT
					]
				],
			],
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT
		];
		$users = (new RecommendationEntitiesBatch())
			->addEntity(self::USER_ID1)
			->addEntity(self::USER_ID2, [self::INTERACTION => self::INTERACTION_WEIGHT]);
		$this->validateRecommendationContent($expected, $users, NULL, self::COUNT);
	}

	public function testSettingsUsersAndUserId()
	{
		$expected = [
			RecommendationContentBuilder::USER_ID_PARAMETER => self::USER_ID1,
			RecommendationContentBuilder::USERS_PARAMETER => [
				[
					RecommendationContentBuilder::USER_ID_PARAMETER => self::USER_ID2,
					RecommendationEntitiesBatch::INTERACTIONS_KEY => [
						self::INTERACTION => self::INTERACTION_WEIGHT
					]
				],
			],
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT
		];
		$users = (new RecommendationEntitiesBatch())
			->setPrimaryEntityId(self::USER_ID1)
			->addEntity(self::USER_ID2, [self::INTERACTION => self::INTERACTION_WEIGHT]);
		$this->validateRecommendationContent($expected, $users, NULL, self::COUNT);
	}

	public function testSettingItemId()
	{
		$expected = [
			RecommendationContentBuilder::ITEM_ID_PARAMETER => self::ITEM_ID1,
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT
		];
		$this->validateRecommendationContent($expected, NULL, self::ITEM_ID1, self::COUNT);
	}

	public function testSettingItems()
	{
		$expected = [
			RecommendationContentBuilder::ITEMS_PARAMETER => [
				[
					RecommendationContentBuilder::ITEM_ID_PARAMETER => self::ITEM_ID1
				],
				[
					RecommendationContentBuilder::ITEM_ID_PARAMETER => self::ITEM_ID2,
					RecommendationEntitiesBatch::INTERACTIONS_KEY => [
						self::INTERACTION => self::INTERACTION_WEIGHT
					]
				],
			],
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT
		];
		$items = (new RecommendationEntitiesBatch())
			->addEntity(self::ITEM_ID1)
			->addEntity(self::ITEM_ID2, [self::INTERACTION => self::INTERACTION_WEIGHT]);
		$this->validateRecommendationContent($expected, NULL, $items, self::COUNT);
	}

	public function testSettingItemsAndItemId()
	{
		$expected = [
			RecommendationContentBuilder::ITEM_ID_PARAMETER => self::ITEM_ID1,
			RecommendationContentBuilder::ITEMS_PARAMETER => [
				[
					RecommendationContentBuilder::ITEM_ID_PARAMETER => self::ITEM_ID2,
					RecommendationEntitiesBatch::INTERACTIONS_KEY => [
						self::INTERACTION => self::INTERACTION_WEIGHT
					]
				],
			],
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT
		];
		$items = (new RecommendationEntitiesBatch())
			->setPrimaryEntityId(self::ITEM_ID1)
			->addEntity(self::ITEM_ID2, [self::INTERACTION => self::INTERACTION_WEIGHT]);
		$this->validateRecommendationContent($expected, NULL, $items, self::COUNT);
	}

	public function testSettingTemplateId()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::TEMPLATE_ID_PARAMETER => self::TEMPLATE_ID
			]
		];
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, self::TEMPLATE_ID);
	}

	public function testSettingFilter()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::FILTER_PARAMETER => self::FILTER
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setFilter(self::FILTER);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingBooster()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::BOOSTER_PARAMETER => self::BOOSTER
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setBooster(self::BOOSTER);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingUserWeight()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::USER_WEIGHT_PARAMETER => self::USER_WEIGHT
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setUserWeight(self::USER_WEIGHT);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingItemWeight()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::ITEM_WEIGHT_PARAMETER => self::ITEM_WEIGHT
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setItemWeight(self::ITEM_WEIGHT);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingDiversity()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::DIVERSITY_PARAMETER => self::DIVERSITY
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setDiversity(self::DIVERSITY);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingDiversityDecay()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::DIVERSITY_DECAY_PARAMETER => self::DIVERSITY_DECAY
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setDiversityDecay(self::DIVERSITY_DECAY);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingRecommendationFeedback()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::RECOMMENDATION_FEEDBACK_PARAMETER => true
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->enableRecommendationFeedback();
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingCategoryBlacklist()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::CATEGORY_BLACKLIST_PARAMETER => true
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->enableCategoryBlacklist();
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingDiversityByCategoriesWithoutDiversityCategories()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::DIVERSITY_TYPES_PARAMETER => [
					[
						RecommendationContentBuilder::TYPE_PARAMETER => RecommendationContentBuilder::CATEGORIES_DIVERSITY_TYPE
					]
				]
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setDiversityByCategories();
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingDiversityByCategoriesWithDiversityCategories()
	{
		$categories = ['foo', 'bar'];
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::DIVERSITY_TYPES_PARAMETER => [
					[
						RecommendationContentBuilder::TYPE_PARAMETER => RecommendationContentBuilder::CATEGORIES_DIVERSITY_TYPE,
						RecommendationContentBuilder::DIVERSITY_CATEGORIES_PARAMETER => $categories
					]
				]
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setDiversityByCategories($categories);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingDiversityBySimilarityWithoutSimilarityTypes()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::DIVERSITY_TYPES_PARAMETER => [
					[
						RecommendationContentBuilder::TYPE_PARAMETER => RecommendationContentBuilder::SIMILARITY_DIVERSITY_TYPE
					]
				]
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setDiversityBySimilarity();
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingDiversityBySimilarityWithSimilarityTypes()
	{
		$similarityTypes = [4, 2, 3, 8];
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::DIVERSITY_TYPES_PARAMETER => [
					[
						RecommendationContentBuilder::TYPE_PARAMETER => RecommendationContentBuilder::SIMILARITY_DIVERSITY_TYPE,
						RecommendationContentBuilder::SIMILARITY_TYPES_PARAMETER => $similarityTypes
					]
				]
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setDiversityBySimilarity($similarityTypes);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingBothDiversityTypes()
	{
		$categories = ['foo', 'bar'];
		$similarityTypes = [4, 2, 3, 8];
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::DIVERSITY_TYPES_PARAMETER => [
					[
						RecommendationContentBuilder::TYPE_PARAMETER => RecommendationContentBuilder::CATEGORIES_DIVERSITY_TYPE,
						RecommendationContentBuilder::DIVERSITY_CATEGORIES_PARAMETER => $categories,
					],
					[
						RecommendationContentBuilder::TYPE_PARAMETER => RecommendationContentBuilder::SIMILARITY_DIVERSITY_TYPE,
						RecommendationContentBuilder::SIMILARITY_TYPES_PARAMETER => $similarityTypes,
					]
				]
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->setDiversityBySimilarity($similarityTypes)
			->setDiversityByCategories($categories);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingAttributesLimits()
	{
		$attribute1 = 'foo';
		$attribute2 = 'bar';
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::TEMPLATE_PARAMETER => [
				RecommendationContentBuilder::DISTINCT_PARAMETER => [
					[
						RecommendationTemplateConfiguration::ATTRIBUTE_ID_KEY => $attribute1,
						RecommendationTemplateConfiguration::LIMIT_IN_REQUEST_KEY => 100
					],
					[
						RecommendationTemplateConfiguration::ATTRIBUTE_ID_KEY => $attribute2,
						RecommendationTemplateConfiguration::TIME_KEY => 10,
						RecommendationTemplateConfiguration::LIMIT_IN_TIME_KEY => 20,
					]
				]
			]
		];
		$configuration = (new RecommendationTemplateConfiguration())
			->addAttributeLimitInRequest($attribute1, 100)
			->addAttributeLimitInTime($attribute2, 10, 20);
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, $configuration);
	}

	public function testSettingOffset()
	{
		$expected = [
			RecommendationContentBuilder::COUNT_PARAMETER => self::COUNT,
			RecommendationContentBuilder::OFFSET_PARAMETER => self::OFFSET
		];
		$this->validateRecommendationContent($expected, NULL, NULL, self::COUNT, NULL, NULL, self::OFFSET);
	}

	/**
	 * @param array|NULL $expectedContent
	 * @param string|NULL|RecommendationEntitiesBatch $users
	 * @param string|NULL|RecommendationEntitiesBatch $items
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param int|NULL $offset
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return void
	 */
	private function validateRecommendationContent(array $expectedContent, $users, $items, $count, $templateId = NULL,
												   RecommendationTemplateConfiguration $configuration = NULL, $offset = NULL)
	{
		if ($configuration !== NULL) {
			$expectedContent[RecommendationContentBuilder::TEMPLATE_PARAMETER] = array_merge(
				[
					RecommendationContentBuilder::DETAILS_PARAMETER => false,
					RecommendationContentBuilder::DISTINCT_PARAMETER => [],
				],
				$expectedContent[RecommendationContentBuilder::TEMPLATE_PARAMETER]
			);
		}
		$actualContent = RecommendationContentBuilder::construct($users, $items, $count, $templateId, $configuration, $offset);
		Assert::equal($expectedContent, $actualContent);
	}

}

(new RecommendationContentBuilderTest())->run();
