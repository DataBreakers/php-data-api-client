<?php

namespace DataBreakers\DataApi\Sections;

use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class ItemsSectionTest extends SectionTest
{

	const ID1 = 'entity1';
	const ID2 = 'entity2';
	const ID3 = 'entity3';

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

	public function testDeletingSelectedItems()
	{
		$content = [
			EntitySection::IDS_PARAMETER => [self::ID1, self::ID3],
			EntitySection::PERMANENTLY_PARAMETER => true
		];
		$this->mockPerformPost(ItemsSection::DELETE_SELECTED_ITEMS_URL, [], $content);
		$this->itemsSection->deleteSelectedItems([self::ID1, self::ID3], true);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenIdsAreEmptyDuringDeletingSelectedItems()
	{
		$this->itemsSection->deleteSelectedItems([]);
	}

}

(new ItemsSectionTest())->run();
