<?php

namespace DataBreakers\DataApi;

use DataBreakers\UnitTestCase;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';


class RecommendationTemplateConfigurationTest extends UnitTestCase
{

	const ATTRIBUTE_ID1 = 'attribute1';
	const ATTRIBUTE_ID2 = 'attribute2';

	/** @var RecommendationTemplateConfiguration */
	private $configuration;


	protected function setUp()
	{
		parent::setUp();
		$this->configuration = new RecommendationTemplateConfiguration();
	}

	public function testGettingAttributesLimits()
	{
		$this->configuration->addAttributeLimitInRequest(self::ATTRIBUTE_ID1, 10)
			->addAttributeLimitInTime(self::ATTRIBUTE_ID2, 20, 30);
		$expected1 = [
			RecommendationTemplateConfiguration::ATTRIBUTE_ID_KEY => self::ATTRIBUTE_ID1,
			RecommendationTemplateConfiguration::LIMIT_IN_REQUEST_KEY => 10
		];
		$expected2 = [
			RecommendationTemplateConfiguration::ATTRIBUTE_ID_KEY => self::ATTRIBUTE_ID2,
			RecommendationTemplateConfiguration::TIME_KEY => 20,
			RecommendationTemplateConfiguration::LIMIT_IN_TIME_KEY => 30
		];
		$limits = $this->configuration->getAttributesLimits();
		Assert::same(2, count($limits));
		Assert::same($expected1, $limits[0]);
		Assert::same($expected2, $limits[1]);
	}

	public function testAddingAttributeLimitInRequest()
	{
		$this->configuration->addAttributeLimitInRequest(self::ATTRIBUTE_ID1, 10);
		$expected = [
				RecommendationTemplateConfiguration::ATTRIBUTE_ID_KEY => self::ATTRIBUTE_ID1,
				RecommendationTemplateConfiguration::LIMIT_IN_REQUEST_KEY => 10
		];
		$limits = $this->configuration->getAttributesLimits();
		Assert::same(1, count($limits));
		Assert::same($expected, $limits[0]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAttributeIdIsEmptyDuringAddingAttributeLimitInRequest()
	{
		$this->configuration->addAttributeLimitInRequest('', 10);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitInRequestIsZeroDuringAddingAttributeLimitInRequest()
	{
		$this->configuration->addAttributeLimitInRequest(self::ATTRIBUTE_ID1, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitInRequestIsNegativeDuringAddingAttributeLimitInRequest()
	{
		$this->configuration->addAttributeLimitInRequest(self::ATTRIBUTE_ID1, -10);
	}

	public function testAddingAttributeLimitInTime()
	{
		$this->configuration->addAttributeLimitInTime(self::ATTRIBUTE_ID1, 10, 20);
		$expected = [
				RecommendationTemplateConfiguration::ATTRIBUTE_ID_KEY => self::ATTRIBUTE_ID1,
				RecommendationTemplateConfiguration::TIME_KEY => 10,
				RecommendationTemplateConfiguration::LIMIT_IN_TIME_KEY => 20
		];
		$limits = $this->configuration->getAttributesLimits();
		Assert::same(1, count($limits));
		Assert::same($expected, $limits[0]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAttributeIdIsEmptyDuringAddingAttributeLimitInTime()
	{
		$this->configuration->addAttributeLimitInTime('', 10, 10);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTimeIsZeroDuringAddingAttributeLimitInTime()
	{
		$this->configuration->addAttributeLimitInTime(self::ATTRIBUTE_ID1, 0, 20);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTimeIsNegativeDuringAddingAttributeLimitInTime()
	{
		$this->configuration->addAttributeLimitInTime(self::ATTRIBUTE_ID1, -10, 20);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitInTimeIsZeroDuringAddingAttributeLimitInTime()
	{
		$this->configuration->addAttributeLimitInTime(self::ATTRIBUTE_ID1, 10, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitInTimeIsNegativeDuringAddingAttributeLimitInTime()
	{
		$this->configuration->addAttributeLimitInTime(self::ATTRIBUTE_ID1, 10, -20);
	}

	public function testAddingAttributeLimitInRequestAndTIme()
	{
		$this->configuration->addAttributeLimitInRequestAndTime(self::ATTRIBUTE_ID1, 10, 20, 30);
		$expected = [
			RecommendationTemplateConfiguration::ATTRIBUTE_ID_KEY => self::ATTRIBUTE_ID1,
			RecommendationTemplateConfiguration::LIMIT_IN_REQUEST_KEY => 10,
			RecommendationTemplateConfiguration::TIME_KEY => 20,
			RecommendationTemplateConfiguration::LIMIT_IN_TIME_KEY => 30
		];
		$limits = $this->configuration->getAttributesLimits();
		Assert::same(1, count($limits));
		Assert::same($expected, $limits[0]);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAttributeIdIsEmptyDuringAddingAttributeLimitInRequestAndTime()
	{
		$this->configuration->addAttributeLimitInRequestAndTime('', 10, 20, 30);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitInRequestIsZeroDuringAddingAttributeLimitInRequestAndTime()
	{
		$this->configuration->addAttributeLimitInRequestAndTime(self::ATTRIBUTE_ID1, 0, 20, 30);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitInRequestIsNegativeDuringAddingAttributeLimitInRequestAndTime()
	{
		$this->configuration->addAttributeLimitInRequestAndTime(self::ATTRIBUTE_ID1, -10, 20, 30);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTimeIsZeroDuringAddingAttributeLimitInRequestAndTime()
	{
		$this->configuration->addAttributeLimitInRequestAndTime(self::ATTRIBUTE_ID1, 10, 0, 30);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTimeIsNegativeDuringAddingAttributeLimitInRequestAndTime()
	{
		$this->configuration->addAttributeLimitInRequestAndTime(self::ATTRIBUTE_ID1, 10, -20, 30);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitInTimeIsZeroDuringAddingAttributeLimitInRequestAndTime()
	{
		$this->configuration->addAttributeLimitInRequestAndTime(self::ATTRIBUTE_ID1, 10, 20, 0);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitInTimeIsNegativeDuringAddingAttributeLimitInRequestAndTime()
	{
		$this->configuration->addAttributeLimitInRequestAndTime(self::ATTRIBUTE_ID1, 10, 20, -30);
	}

	public function testGettingIfDetailsAreEnabled()
	{
		Assert::false($this->configuration->areDetailsEnabled());
		$this->configuration->enableDetails();
		Assert::true($this->configuration->areDetailsEnabled());
		$this->configuration->disableDetails();
		Assert::false($this->configuration->areDetailsEnabled());
	}

	public function testGettingRecommendationFeedback()
	{
		Assert::null($this->configuration->getRecommendationFeedback());
		$this->configuration->enableRecommendationFeedback();
		Assert::true($this->configuration->getRecommendationFeedback());
		$this->configuration->disableRecommendationFeedback();
		Assert::false($this->configuration->getRecommendationFeedback());
	}

	public function testGettingCategoryBlacklist()
	{
		Assert::null($this->configuration->getCategoryBlacklist());
		$this->configuration->enableCategoryBlacklist();
		Assert::true($this->configuration->getCategoryBlacklist());
		$this->configuration->disableCategoryBlacklist();
		Assert::false($this->configuration->getCategoryBlacklist());
	}

	/**
	 * @param float $diversityDecay
	 * @dataProvider getValidDiversityDecayValues
	 */
	public function testSettingsDiversityDecay($diversityDecay)
	{
		Assert::null($this->configuration->getDiversityDecay());
		$this->configuration->setDiversityDecay($diversityDecay);
		Assert::same($diversityDecay, $this->configuration->getDiversityDecay());
	}

	public function getValidDiversityDecayValues()
	{
		return [
			[0.0],
			[0.25],
			[1.0],
		];
	}

	/**
	 * @param float $diversityDecay
	 * @dataProvider getInvalidDiversityDecayValues
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testSettingDiversityDecayThrowsExceptionWhenGivenInvalidValue($diversityDecay)
	{
		$this->configuration->setDiversityDecay($diversityDecay);
	}

	public function getInvalidDiversityDecayValues()
	{
		return [
			[-0.1],
			[-1.25],
			[1.1],
			[2.25],
		];
	}

}

(new RecommendationTemplateConfigurationTest())->run();
