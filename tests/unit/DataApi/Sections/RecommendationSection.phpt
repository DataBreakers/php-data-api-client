<?php

namespace DataBreakers\DataApi\Sections;


use DataBreakers\DataApi\RecommendationTemplateConfiguration;


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
		$content = [
			RecommendationSection::USER_ID_PARAMETER => self::USER_ID,
			RecommendationSection::ITEM_ID_PARAMETER => self::ITEM_ID,
			RecommendationSection::COUNT_PARAMETER => self::COUNT,
			RecommendationSection::TEMPLATE_PARAMETER => $this->getExpectedTemplateConfiguration(NULL, $configuration)
		];
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
		$content = [
			RecommendationSection::USER_ID_PARAMETER => self::USER_ID,
			RecommendationSection::COUNT_PARAMETER => self::COUNT,
			RecommendationSection::TEMPLATE_PARAMETER => $this->getExpectedTemplateConfiguration(self::TEMPLATE_ID, $configuration)
		];
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
		$content = [
			RecommendationSection::ITEM_ID_PARAMETER => self::ITEM_ID,
			RecommendationSection::COUNT_PARAMETER => self::COUNT,
			RecommendationSection::TEMPLATE_PARAMETER => $this->getExpectedTemplateConfiguration(self::TEMPLATE_ID)
		];
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
			->enableCategoryBlacklist();
		$content = [
			RecommendationSection::COUNT_PARAMETER => self::COUNT,
			RecommendationSection::TEMPLATE_PARAMETER => $this->getExpectedTemplateConfiguration(NULL, $configuration)
		];
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

	/**
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration $configuration
	 * @return array
	 */
	private function getExpectedTemplateConfiguration($templateId, RecommendationTemplateConfiguration $configuration = NULL)
	{
		if ($configuration === NULL) {
			return [RecommendationSection::TEMPLATE_ID_PARAMETER => $templateId];
		}
		$data = [
			RecommendationSection::DISTINCT_PARAMETER => $configuration->getAttributesLimits(),
			RecommendationSection::DETAILS_PARAMETER => $configuration->areDetailsEnabled()
		];
		$data = $this->setIfNotNull($data, RecommendationSection::TEMPLATE_ID_PARAMETER, $templateId);
		$data = $this->setIfNotNull($data, RecommendationSection::FILTER_PARAMETER, $configuration->getFilter());
		$data = $this->setIfNotNull($data, RecommendationSection::BOOSTER_PARAMETER, $configuration->getBooster());
		$data = $this->setIfNotNull($data, RecommendationSection::USER_WEIGHT_PARAMETER, $configuration->getUserWeight());
		$data = $this->setIfNotNull($data, RecommendationSection::ITEM_WEIGHT_PARAMETER, $configuration->getItemWeight());
		$data = $this->setIfNotNull($data, RecommendationSection::DIVERSITY_PARAMETER, $configuration->getDiversity());
		$data = $this->setIfNotNull($data, RecommendationSection::RECOMMENDATION_FEEDBACK_PARAMETER, $configuration->getRecommendationFeedback());
		$data = $this->setIfNotNull($data, RecommendationSection::CATEGORY_BLACKLIST_PARAMETER, $configuration->getCategoryBlacklist());
		return $data;
	}

	/**
	 * @param array $data
	 * @param string $name
	 * @param string|NULL $value
	 * @return array
	 */
	private function setIfNotNull(array $data, $name, $value)
	{
		if ($value !== NULL) {
			$data[$name] = $value;
		}
		return $data;
	}

}

(new RecommendationSectionTest())->run();
