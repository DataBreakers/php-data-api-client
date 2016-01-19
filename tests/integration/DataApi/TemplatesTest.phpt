<?php

namespace DataBreakers\DataApi;

use DataBreakers\IntegrationTestCase;
use DataBreakers\Seeder;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class TemplatesTest extends IntegrationTestCase
{

	const TEMPLATE_ID_3 = 'template3';
	const DEFAULT_TEMPLATE = 'default';


	public function testInsertingTemplate()
	{
		$this->client->insertOrUpdateTemplate(self::TEMPLATE_ID_3, (new TemplateConfiguration())
			->setFilter(Seeder::TEMPLATE_FILTER)
		);
		$this->validateExpectedTemplates([self::DEFAULT_TEMPLATE, Seeder::TEMPLATE_ID_1, Seeder::TEMPLATE_ID_2, self::TEMPLATE_ID_3]);
	}

	public function testGettingOneTemplate()
	{
		$template = $this->client->getTemplate(Seeder::TEMPLATE_ID_1);
		Assert::same(Seeder::TEMPLATE_FILTER, $template['filter']);
		Assert::same(Seeder::TEMPLATE_BOOSTER, $template['booster']);
		Assert::same(Seeder::TEMPLATE_DIVERSITY, $template['diversity']);
	}

	public function testGettingAllTemplates()
	{
		$this->validateExpectedTemplates([self::DEFAULT_TEMPLATE, Seeder::TEMPLATE_ID_1, Seeder::TEMPLATE_ID_2]);
	}

	public function testDeletingTemplate()
	{
		$this->client->deleteTemplate(Seeder::TEMPLATE_ID_1);
		$this->validateExpectedTemplates([self::DEFAULT_TEMPLATE, Seeder::TEMPLATE_ID_2]);
	}

	/**
	 * @param array $expectedTemplates
	 * @return void
	 */
	private function validateExpectedTemplates(array $expectedTemplates)
	{
		$actualTemplates = $this->client->getTemplates()['templates'];
		Assert::same(count($expectedTemplates), count($actualTemplates));
		foreach ($actualTemplates as $template) {
			Assert::true(in_array($template['templateId'], $expectedTemplates));
		}
	}

}

(new TemplatesTest())->run();
