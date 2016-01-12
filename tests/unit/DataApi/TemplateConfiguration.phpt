<?php

namespace DataBreakers\DataApi;

use DataBreakers\UnitTestCase;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';


class TemplateConfigurationTest extends UnitTestCase
{

	const FILTER = 'filter > 1';
	const BOOSTER = 'booster < 2';
	const USER_WEIGHT = 0.4;
	const ITEM_WEIGHT = 0.6;
	const DIVERSITY = 0.8;

	/** @var TemplateConfiguration */
	private $configuration;


	protected function setUp()
	{
		$this->configuration = new TemplateConfiguration(self::FILTER, self::BOOSTER, self::USER_WEIGHT, self::ITEM_WEIGHT,
			self::DIVERSITY);
	}

	public function testGettingFilter()
	{
		Assert::same(self::FILTER, $this->configuration->getFilter());
	}

	public function testSettingFilter()
	{
		$this->configuration->setFilter(NULL);
		Assert::null($this->configuration->getFilter());
		$this->configuration->setFilter(self::FILTER);
		Assert::same(self::FILTER, $this->configuration->getFilter());
	}

	public function testGettingBooster()
	{
		Assert::same(self::BOOSTER, $this->configuration->getBooster());
	}

	public function testSettingBooster()
	{
		$this->configuration->setBooster(NULL);
		Assert::null($this->configuration->getBooster());
		$this->configuration->setBooster(self::BOOSTER);
		Assert::same(self::BOOSTER, $this->configuration->getBooster());
	}

	public function testGettingUserWeight()
	{
		Assert::same(self::USER_WEIGHT, $this->configuration->getUserWeight());
	}

	public function testSettingUserWeight()
	{
		$this->configuration->setUserWeight(NULL);
		Assert::null($this->configuration->getUserWeight());
		$this->configuration->setUserWeight(self::USER_WEIGHT);
		Assert::same(self::USER_WEIGHT, $this->configuration->getUserWeight());
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserWeightIsNotAFloatNumber()
	{
		$this->configuration->setUserWeight('foo');
	}

	public function testGettingItemWeight()
	{
		Assert::same(self::ITEM_WEIGHT, $this->configuration->getItemWeight());
	}

	public function testSettingItemWeight()
	{
		$this->configuration->setItemWeight(NULL);
		Assert::null($this->configuration->getItemWeight());
		$this->configuration->setItemWeight(self::ITEM_WEIGHT);
		Assert::same(self::ITEM_WEIGHT, $this->configuration->getItemWeight());
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemWeightIsNotAFloatNumber()
	{
		$this->configuration->setItemWeight('foo');
	}

	public function testGettingDiversity()
	{
		Assert::same(self::DIVERSITY, $this->configuration->getDiversity());
	}

	public function testSettingDiversity()
	{
		$this->configuration->setDiversity(NULL);
		Assert::null($this->configuration->getDiversity());
		$this->configuration->setDiversity(self::DIVERSITY);
		Assert::same(self::DIVERSITY, $this->configuration->getDiversity());
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenDiversityIsNotAFloatNumber()
	{
		$this->configuration->setDiversity('foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenDiversityIsNegative()
	{
		$this->configuration->setDiversity(-0.5);
	}

}

(new TemplateConfigurationTest())->run();
