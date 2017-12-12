<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\DataApi\RecommendationTemplateConfiguration;
use DataBreakers\DataApi\Utils\RecommendationContentBuilder;
use DataBreakers\UnitTestCase;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class RecommendationsBatchTest extends UnitTestCase
{

	const REQUEST_ID1 = 'request1';
	const REQUEST_ID2 = 'request1';
	const IMPORTANCE1 = 2.5;
	const IMPORTANCE2 = 5;
	const USER_ID1 = 'user1';
	const USER_ID2 = 'user2';
	const ITEM_ID1 = 'item1';
	const ITEM_ID2 = 'item2';
	const COUNT = 10;
	const TEMPLATE_ID = 'template1';
	const SEARCH_QUERY = 'search_query';
	const FILTER = 'filter > 1';
	const BOOSTER = 'booster < 2';
	const OFFSET = 10;

	/** @var RecommendationsBatch */
	private $batch;

	/** @var RecommendationTemplateConfiguration */
	private $configuration;


	/**
	 * @inheritdoc
	 */
	protected function setUp()
	{
		$this->batch = (new RecommendationsBatch())
			->requestRecommendations(
				self::REQUEST_ID1,
                self::IMPORTANCE1,
				self::USER_ID2,
                self::ITEM_ID2,
				self::COUNT,
                self::TEMPLATE_ID,
                self::SEARCH_QUERY,
				NULL,
                self::OFFSET
			);
		$this->configuration = (new RecommendationTemplateConfiguration())
			->setFilter(self::FILTER)
			->setBooster(self::BOOSTER);
	}

	public function testRequestingRecommendations()
	{
		$this->batch->requestRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1,
            self::ITEM_ID1,
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
		$this->validateRecommendations(self::REQUEST_ID2, self::IMPORTANCE2, RecommendationContentBuilder::construct(
			self::USER_ID1, self::ITEM_ID1, self::COUNT, self::TEMPLATE_ID, self::SEARCH_QUERY, $this->configuration
		));
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenRequestIdIsEmptyStringDuringRequestingRecommendations()
	{
		$this->batch->requestRecommendations(
			'',
            self::IMPORTANCE2,
			self::USER_ID1,
            self::ITEM_ID1,
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyStringDuringRequestingRecommendations()
	{
		$this->batch->requestRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			'',
            self::ITEM_ID1,
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyStringDuringRequestingRecommendations()
	{
		$this->batch->requestRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1, '',
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringRequestingRecommendations()
	{
		$this->batch->requestRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1,
            self::ITEM_ID1,
			0,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeNumberDuringRequestingRecommendations()
	{
		$this->batch->requestRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1,
            self::ITEM_ID1,
			-10,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNegativeDuringRequestingRecommendations()
	{
		$this->batch->requestRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1,
            self::ITEM_ID1,
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration, -10
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNotANumberNumberDuringRequestingRecommendations()
	{
		$this->batch->requestRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1,
            self::ITEM_ID1,
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration, 'foo'
		);
	}

	public function testRequestingRecommendationsForItem()
	{
		$this->batch->requestRecommendationsForItem(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::ITEM_ID1,
            self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
		$this->validateRecommendations(self::REQUEST_ID2, self::IMPORTANCE2, RecommendationContentBuilder::construct(
			NULL, self::ITEM_ID1, self::COUNT, self::TEMPLATE_ID, self::SEARCH_QUERY, $this->configuration
		));
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenRequestIdIsEmptyStringDuringRequestingRecommendationsForItem()
	{
		$this->batch->requestRecommendationsForItem(
			'',
            self::IMPORTANCE2,
			self::ITEM_ID1,
            self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenItemIdIsEmptyStringDuringRequestingRecommendationsForItem()
	{
		$this->batch->requestRecommendationsForItem(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			'',
            self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringRequestingRecommendationsForItem()
	{
		$this->batch->requestRecommendationsForItem(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::ITEM_ID1,
            0,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeNumberDuringRequestingRecommendationsForItem()
	{
		$this->batch->requestRecommendationsForItem(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::ITEM_ID1,
            -10,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	public function testRequestingRecommendationsForItems()
	{
		$items = (new RecommendationEntitiesBatch())
			->addEntity(self::ITEM_ID1)
			->addEntity(self::ITEM_ID2);
		$this->batch->requestRecommendationsForItems(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			$items,
            self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
		$this->validateRecommendations(self::REQUEST_ID2, self::IMPORTANCE2, RecommendationContentBuilder::construct(
			NULL, $items, self::COUNT, self::TEMPLATE_ID, self::SEARCH_QUERY, $this->configuration
		));
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenRequestIdIsEmptyStringDuringRequestingRecommendationsForItems()
	{
		$this->batch->requestRecommendationsForItems(
			'',
            self::IMPORTANCE2,
			new RecommendationEntitiesBatch(),
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringRequestingRecommendationsForItems()
	{
		$this->batch->requestRecommendationsForItems(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			new RecommendationEntitiesBatch(),
			0,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeNumberDuringRequestingRecommendationsForItems()
	{
		$this->batch->requestRecommendationsForItems(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			new RecommendationEntitiesBatch(),
			-10,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	public function testRequestingRecommendationsForUser()
	{
		$this->batch->requestRecommendationsForUser(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1,
            self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
		$this->validateRecommendations(self::REQUEST_ID2, self::IMPORTANCE2, RecommendationContentBuilder::construct(
			self::USER_ID1, NULL, self::COUNT, self::TEMPLATE_ID, self::SEARCH_QUERY, $this->configuration
		));
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenRequestIdIsEmptyStringDuringRequestingRecommendationsForUser()
	{
		$this->batch->requestRecommendationsForUser(
			'',
            self::IMPORTANCE2,
			self::USER_ID1,
            self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyStringDuringRequestingRecommendationsForUser()
	{
		$this->batch->requestRecommendationsForUser(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			'',
            self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringRequestingRecommendationsForUser()
	{
		$this->batch->requestRecommendationsForUser(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1,
            0,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeNumberDuringRequestingRecommendationsForUser()
	{
		$this->batch->requestRecommendationsForUser(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::USER_ID1,
            -10,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	public function testRequestingRecommendationsForUsers()
	{
		$users = (new RecommendationEntitiesBatch())
			->addEntity(self::USER_ID1)
			->addEntity(self::USER_ID2);
		$this->batch->requestRecommendationsForUsers(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			$users,
            self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
		$this->validateRecommendations(self::REQUEST_ID2, self::IMPORTANCE2, RecommendationContentBuilder::construct(
			$users, NULL, self::COUNT, self::TEMPLATE_ID, self::SEARCH_QUERY, $this->configuration
		));
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenRequestIdIsEmptyStringDuringRequestingRecommendationsForUsers()
	{
		$this->batch->requestRecommendationsForUsers(
			'',
            self::IMPORTANCE2,
			new RecommendationEntitiesBatch(),
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringRequestingRecommendationsForUsers()
	{
		$this->batch->requestRecommendationsForUsers(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			new RecommendationEntitiesBatch(),
			0,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeNumberDuringRequestingRecommendationsForUsers()
	{
		$this->batch->requestRecommendationsForUsers(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			new RecommendationEntitiesBatch(),
			-10,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	public function testRequestingGeneralRecommendations()
	{
		$this->batch->requestGeneralRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
		$this->validateRecommendations(self::REQUEST_ID2, self::IMPORTANCE2, RecommendationContentBuilder::construct(
			NULL, NULL, self::COUNT, self::TEMPLATE_ID, self::SEARCH_QUERY, $this->configuration
		));
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenRequestIdIsEmptyStringDuringRequestingGeneralRecommendations()
	{
		$this->batch->requestGeneralRecommendations(
			'',
            self::IMPORTANCE2,
			self::COUNT,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsZeroDuringRequestingGeneralRecommendations()
	{
		$this->batch->requestGeneralRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			0,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenCountIsNegativeNumberDuringRequestingGeneralRecommendations()
	{
		$this->batch->requestGeneralRecommendations(
			self::REQUEST_ID2,
            self::IMPORTANCE2,
			-10,
            self::TEMPLATE_ID,
            self::SEARCH_QUERY,
			$this->configuration
		);
	}

	/**
	 * @param string $addedRequestId
	 * @param float $addedImportance
	 * @param array $addedRequest
	 * @return void
	 */
	private function validateRecommendations($addedRequestId, $addedImportance, $addedRequest)
	{
		$recommendations = $this->batch->getRecommendations();
		Assert::same(2, count($recommendations));
		$expectedFirstRecommendations = [
			RecommendationsBatch::REQUEST_ID_KEY => self::REQUEST_ID1,
			RecommendationsBatch::IMPORTANCE_KEY => (float) self::IMPORTANCE1,
			RecommendationsBatch::REQUEST_KEY => RecommendationContentBuilder::construct(
				self::USER_ID2,
                self::ITEM_ID2,
				self::COUNT,
                self::TEMPLATE_ID,
                self::SEARCH_QUERY,
				NULL,
                self::OFFSET
			)
		];
		Assert::same($expectedFirstRecommendations, $recommendations[0]);
		$expectedAddedRecommendation = [
			RecommendationsBatch::REQUEST_ID_KEY => $addedRequestId,
			RecommendationsBatch::IMPORTANCE_KEY => (float) $addedImportance,
			RecommendationsBatch::REQUEST_KEY => $addedRequest
		];
		Assert::same($expectedAddedRecommendation, $recommendations[1]);
	}

}

(new RecommendationsBatchTest())->run();
