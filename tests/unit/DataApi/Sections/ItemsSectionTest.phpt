<?php

namespace DataBreakers\DataApi\Sections;

use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class ItemsSectionTest extends SectionTest
{

	/** @var ItemsSection */
	private $itemsSection;


	protected function setUp()
	{
		parent::setUp();
		$this->itemsSection = new ItemsSection($this->api, new ItemsSectionStrategy());
	}

	public function testClearingItems()
	{
		$this->mockPerformDelete(ItemsSection::CLEAR_ITEMS_URL);
		$this->itemsSection->clearItems();
		Assert::true(true); // prevent "This test forgets to execute an assertion"
	}

}

(new ItemsSectionTest())->run();
