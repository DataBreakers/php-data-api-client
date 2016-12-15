<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Batch\RecommendationEntitiesBatch;
use DataBreakers\DataApi\Batch\RecommendationsBatch;
use DataBreakers\DataApi\RecommendationTemplateConfiguration;
use DataBreakers\DataApi\Utils\RecommendationContentBuilder;


require_once __DIR__ . '/../../bootstrap.php';


class RecommendationSectionTest extends SectionTest
{

	const USER_ID1 = 'user1';
	const USER_ID2 = 'user2';
	const ITEM_ID1 = 'item1';
	const ITEM_ID2 = 'item2';
	const ITEM_ID3 = 'item3';
	const COUNT = 10;
	const TEMPLATE_ID = 'template1';
	const FILTER = 'filter > 1';
	const BOOSTER = 'booster < 2';
	const USER_WEIGHT = 0.4;
	const ITEM_WEIGHT = 0.6;
	const DIVERSITY = 0.8;
	const ATTRIBUTE_ID1 = 'attribute1';
	const ATTRIBUTE_ID2 = 'attribute2';
	const INTERACTION = 'interaction';
	const INTERACTION_WEIGHT = 0.25;
	const OFFSET = 10;

	/** @var RecommendationSection */
	private $recommendationSection;


	protected function setUp()
	{
		parent::setUp();
		$this->recommendationSection = new RecommendationSection($this->api);
	}

	public function testGettingRecommendations()
	{
		$configuration = (new RecommendationTemplateConfiguration())
			->setFilter(self::FILTER)
			->setUserWeight(self::USER_WEIGHT)
			->setItemWeight(self::ITEM_WEIGHT)
			->setDiversity(self::DIVERSITY)
			->disableDetails()
			->addAttributeLimitInRequest(self::ATTRIBUTE_ID1, 10);
		$content = RecommendationContentBuilder::construct(self::USER_ID1, self::ITEM_ID1, self::COUNT, NULL, $configuration, self::OFFSET);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendations(self::USER_ID1, self::ITEM_ID1, self::COUNT, NULL, $configuration, self::OFFSET);
	}

	public function testGettingRecommendationsWhenEntitiesBatchesAreGiven()
	{
		$users = (new RecommendationEntitiesBatch())
			->addEntity(self::USER_ID1, [self::INTERACTION => self::INTERACTION_WEIGHT]);
		$items = (new RecommendationEntitiesBatch())
			->addEntity(self::ITEM_ID1)
			->addEntity(self::ITEM_ID2, [self::INTERACTION => self::INTERACTION_WEIGHT])
			->addWeightedEntity(self::ITEM_ID3, self::ITEM_WEIGHT);
		$content = RecommendationContentBuilder::construct($users, $items, self::COUNT);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendations($users, $items, self::COUNT);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations('', self::ITEM_ID1, self::COUNT);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations(self::USER_ID1, '', self::COUNT);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations(self::USER_ID1, self::ITEM_ID1, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations(self::USER_ID1, self::ITEM_ID1, -10);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNegativeDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations(self::USER_ID1, self::ITEM_ID1, self::COUNT, NULL, NULL, -10);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNotANumberDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations(self::USER_ID1, self::ITEM_ID1, self::COUNT, NULL, NULL, 'foo');
	}

	public function testGettingRecommendationsForUser()
	{
		$configuration = (new RecommendationTemplateConfiguration())
			->setBooster(self::BOOSTER)
			->setDiversity(self::DIVERSITY);
		$content = RecommendationContentBuilder::construct(self::USER_ID1, NULL, self::COUNT, self::TEMPLATE_ID, $configuration, self::OFFSET);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendationsForUser(self::USER_ID1, self::COUNT, self::TEMPLATE_ID, $configuration, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyDuringGettingRecommendationsForUser()
	{
		$this->recommendationSection->getRecommendationsForUser('', self::COUNT);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringGettingRecommendationsForUser()
	{
		$this->recommendationSection->getRecommendationsForUser(self::USER_ID1, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeDuringGettingRecommendationsForUser()
	{
		$this->recommendationSection->getRecommendationsForUser(self::USER_ID1, -10);
	}

	public function testGettingRecommendationsForUsers()
	{
		$configuration = (new RecommendationTemplateConfiguration())
			->setBooster(self::BOOSTER)
			->setDiversity(self::DIVERSITY);
		$users = (new RecommendationEntitiesBatch())
			->addEntity(self::USER_ID1, [self::INTERACTION => self::INTERACTION_WEIGHT])
			->addEntity(self::USER_ID2);
		$content = RecommendationContentBuilder::construct($users, NULL, self::COUNT, self::TEMPLATE_ID, $configuration, self::OFFSET);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendationsForUsers($users, self::COUNT, self::TEMPLATE_ID, $configuration, self::OFFSET);
	}

	public function testGettingRecommendationsForItem()
	{
		$content = RecommendationContentBuilder::construct(NULL, self::ITEM_ID1, self::COUNT, self::TEMPLATE_ID, NULL, self::OFFSET);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendationsForItem(self::ITEM_ID1, self::COUNT, self::TEMPLATE_ID, NULL, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyDuringGettingRecommendationsForItem()
	{
		$this->recommendationSection->getRecommendationsForItem('', self::COUNT);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringGettingRecommendationsForItem()
	{
		$this->recommendationSection->getRecommendationsForItem(self::ITEM_ID1, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeDuringGettingRecommendationsForItem()
	{
		$this->recommendationSection->getRecommendationsForItem(self::ITEM_ID1, -10);
	}

	public function testGettingRecommendationsForItems()
	{
		$items = (new RecommendationEntitiesBatch())
			->addEntity(self::ITEM_ID1, [self::INTERACTION => self::INTERACTION_WEIGHT])
			->addEntity(self::ITEM_ID2)
			->addWeightedEntity(self::ITEM_ID3, self::ITEM_WEIGHT);
		$content = RecommendationContentBuilder::construct(NULL, $items, self::COUNT, self::TEMPLATE_ID);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendationsForItems($items, self::COUNT, self::TEMPLATE_ID);
	}

	public function testGettingGeneralRecommendations()
	{
		$configuration = (new RecommendationTemplateConfiguration())
			->setBooster(self::BOOSTER)
			->setDiversity(self::DIVERSITY)
			->enableRecommendationFeedback()
			->enableCategoryBlacklist()
			->setDiversityDecay(0.25);
		$content = RecommendationContentBuilder::construct(NULL, NULL, self::COUNT, NULL, $configuration, self::OFFSET);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getGeneralRecommendations(self::COUNT, NULL, $configuration, self::OFFSET);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringGettingGeneralRecommendations()
	{
		$this->recommendationSection->getGeneralRecommendations(0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeDuringGettingGeneralRecommendations()
	{
		$this->recommendationSection->getGeneralRecommendations(-10);
	}

	public function testGettingRecommendationsBatch()
	{
		$batch = (new RecommendationsBatch())
			->requestRecommendationsForItem('request1', 2.0, self::ITEM_ID1, 10, self::TEMPLATE_ID)
			->requestGeneralRecommendations('request2', 5.0, 50);
		$content = [
			RecommendationSection::REQUESTS_PARAMETER => $batch->getRecommendations(),
			RecommendationSection::EVALUATION_PARAMETER => RecommendationsBatch::SEQUENTIAL_EVALUATION,
			RecommendationSection::IMPORTANCE_TYPE_PARAMETER => RecommendationsBatch::WEIGHT_IMPORTANCE_TYPE,
			RecommendationSection::UNIQUE_RECOMMENDATIONS_PARAMETER => false
		];
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATIONS_BATCH_URL, $parameters, $content);
		$this->recommendationSection->getRecommendationsBatch(
			$batch,
			false,
			RecommendationsBatch::SEQUENTIAL_EVALUATION,
			RecommendationsBatch::WEIGHT_IMPORTANCE_TYPE
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInvalidEvaluationWhileGettingRecommendationsBatch()
	{
		$this->recommendationSection->getRecommendationsBatch(
			new RecommendationsBatch(),
			true,
			'bar',
			RecommendationsBatch::WEIGHT_IMPORTANCE_TYPE
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInvalidImportanceTypeWhileGettingRecommendationsBatch()
	{
		$this->recommendationSection->getRecommendationsBatch(
			new RecommendationsBatch(),
			true,
			RecommendationsBatch::PARALLEL_EVALUATION,
			'foo'
		);
	}

}

(new RecommendationSectionTest())->run();
