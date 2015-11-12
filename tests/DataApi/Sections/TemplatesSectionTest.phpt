<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\TemplateConfiguration;


require_once __DIR__ . '/../../bootstrap.php';


class TemplatesSectionTest extends SectionTest
{

	const TEMPLATE_ID = 'template1';
	const FILTER = 'filter > 1';
	const BOOSTER = 'booster < 2';
	const USER_WEIGHT = 0.4;
	const ITEM_WEIGHT = 0.6;
	const DIVERSITY = 0.8;

	/** @var TemplatesSection */
	private $templateSection;


	protected function setUp()
	{
		parent::setUp();
		$this->templateSection = new TemplatesSection($this->api);
	}

	public function testInsertingOrUpdatingTemplate()
	{
		$content = [
			TemplatesSection::TEMPLATE_ID_PARAMETER => self::TEMPLATE_ID,
			TemplatesSection::FILTER_PARAMETER => self::FILTER,
			TemplatesSection::BOOSTER_PARAMETER => self::BOOSTER,
			TemplatesSection::USER_WEIGHT_PARAMETER => self::USER_WEIGHT,
			TemplatesSection::ITEM_WEIGHT_PARAMETER => self::ITEM_WEIGHT,
			TemplatesSection::DIVERSITY_PARAMETER => self::DIVERSITY,
		];
		$this->mockPerformPost(TemplatesSection::INSERT_OR_UPDATE_TEMPLATE_URL, [], $content);
		$configuration = new TemplateConfiguration(self::FILTER, self::BOOSTER, self::USER_WEIGHT, self::ITEM_WEIGHT,
				self::DIVERSITY);
		$this->templateSection->insertOrUpdateTemplate(self::TEMPLATE_ID, $configuration);
	}

	public function testInsertingOrUpdatingTemplateWhenAllConfigurationIsNull()
	{
		$content = [TemplatesSection::TEMPLATE_ID_PARAMETER => self::TEMPLATE_ID];
		$this->mockPerformPost(TemplatesSection::INSERT_OR_UPDATE_TEMPLATE_URL, [], $content);
		$this->templateSection->insertOrUpdateTemplate(self::TEMPLATE_ID, new TemplateConfiguration());
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTemplateIsEmptyDuringInsertingOrUpdatingTemplate()
	{
		$this->templateSection->insertOrUpdateTemplate('', new TemplateConfiguration());
	}

	public function testGettingTemplates()
	{
		$this->mockPerformGet(TemplatesSection::GET_TEMPLATES_URL);
		$this->templateSection->getTemplates();
	}

	public function testGettingTemplate()
	{
		$parameters = [TemplatesSection::TEMPLATE_ID_PARAMETER => self::TEMPLATE_ID];
		$this->mockPerformGet(TemplatesSection::GET_TEMPLATE_URL, $parameters);
		$this->templateSection->getTemplate(self::TEMPLATE_ID);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTemplateIsEmptyDuringGettingTemplate()
	{
		$this->templateSection->getTemplate('');
	}

	public function testDeletingTemplate()
	{
		$parameters = [TemplatesSection::TEMPLATE_ID_PARAMETER => self::TEMPLATE_ID];
		$this->mockPerformDelete(TemplatesSection::DELETE_TEMPLATE_URL, $parameters);
		$this->templateSection->deleteTemplate(self::TEMPLATE_ID);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTemplateIsEmptyDuringDeletingTemplate()
	{
		$this->templateSection->deleteTemplate('');
	}

}

(new TemplatesSectionTest())->run();
