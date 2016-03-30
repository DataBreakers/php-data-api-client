<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\RecommendationTemplateConfiguration;
use DataBreakers\DataApi\Utils\RecommendationContentBuilder;


require_once __DIR__ . '/../../bootstrap.php';


class RecommendationSectionTest extends SectionTest
{

	const USER_ID = 'user1';
	const ITEM_ID = 'item1';
	const COUNT = 10;
	const TEMPLATE_ID = 'template1';
	const FILTER = 'filter > 1';
	const BOOSTER = 'booster < 2';
	const USER_WEIGHT = 0.4;
	const ITEM_WEIGHT = 0.6;
	const DIVERSITY = 0.8;
	const ATTRIBUTE_ID1 = 'attribute1';
	const ATTRIBUTE_ID2 = 'attribute2';

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
		$content = RecommendationContentBuilder::construct(self::USER_ID, self::ITEM_ID, self::COUNT, NULL, $configuration);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendations(self::USER_ID, self::ITEM_ID, self::COUNT, NULL, $configuration);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations('', self::ITEM_ID, self::COUNT);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations(self::USER_ID, '', self::COUNT);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations(self::USER_ID, self::ITEM_ID, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeDuringGettingRecommendations()
	{
		$this->recommendationSection->getRecommendations(self::USER_ID, self::ITEM_ID, -10);
	}

	public function testGettingRecommendationsForUser()
	{
		$configuration = (new RecommendationTemplateConfiguration())
			->setBooster(self::BOOSTER)
			->setDiversity(self::DIVERSITY);
		$content = RecommendationContentBuilder::construct(self::USER_ID, NULL, self::COUNT, self::TEMPLATE_ID, $configuration);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendationsForUser(self::USER_ID, self::COUNT, self::TEMPLATE_ID, $configuration);
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
		$this->recommendationSection->getRecommendationsForUser(self::USER_ID, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeDuringGettingRecommendationsForUser()
	{
		$this->recommendationSection->getRecommendationsForUser(self::USER_ID, -10);
	}

	public function testGettingRecommendationsForItem()
	{
		$content = RecommendationContentBuilder::construct(NULL, self::ITEM_ID, self::COUNT, self::TEMPLATE_ID);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getRecommendationsForItem(self::ITEM_ID, self::COUNT, self::TEMPLATE_ID);
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
		$this->recommendationSection->getRecommendationsForItem(self::ITEM_ID, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeDuringGettingRecommendationsForItem()
	{
		$this->recommendationSection->getRecommendationsForItem(self::ITEM_ID, -10);
	}

	public function testGettingGeneralRecommendations()
	{
		$configuration = (new RecommendationTemplateConfiguration())
			->setBooster(self::BOOSTER)
			->setDiversity(self::DIVERSITY)
			->enableRecommendationFeedback()
			->enableCategoryBlacklist()
			->setDiversityDecay(0.25);
		$content = RecommendationContentBuilder::construct(NULL, NULL, self::COUNT, NULL, $configuration);
		$parameters = [RecommendationSection::MODEL_ID_PARAMETER => RecommendationSection::DEFAULT_MODEL_ID];
		$this->mockPerformPost(RecommendationSection::GET_RECOMMENDATION_URL, $parameters, $content);
		$this->recommendationSection->getGeneralRecommendations(self::COUNT, NULL, $configuration);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringGettingGeneralRecommendations()
	{
		$this->recommendationSection->getGeneralRecommendations( 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeDuringGettingGeneralRecommendations()
	{
		$this->recommendationSection->getGeneralRecommendations(-10);
	}

}

(new RecommendationSectionTest())->run();
