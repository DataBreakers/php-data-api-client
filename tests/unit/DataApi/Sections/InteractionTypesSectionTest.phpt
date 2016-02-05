<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Batch\InteractionTypesBatch;
use DataBreakers\DataApi\InteractionMetaType;
use DateTime;
use Tester\Assert;


require_once __DIR__ . '/../../bootstrap.php';


class InteractionTypesSectionTest extends SectionTest
{

	const ID1 = 'interactionType1';
	const ID2 = 'interactionType2';
	const ID3 = 'interactionType3';

	const DESCRIPTION_ATTRIBUTE = 'description';
	const PRIORITY_ATTRIBUTE = 'priority';

	const LIMIT = 250;
	const OFFSET = 500;
	const SEARCH_QUERY = 'foo';

	const INTERACTION_WEIGHT = 0.5;
	const INTERACTION_LEARN_WEIGHT = 0.25;

	/** @var InteractionTypesSection */
	private $interactionTypesSection;


	protected function setUp()
	{
		parent::setUp();
		$this->interactionTypesSection = new InteractionTypesSection($this->api);
	}

	public function testInsertingOrUpdatingInteractionType()
	{
		$attributes = [
			self::DESCRIPTION_ATTRIBUTE => 'Bar',
			self::PRIORITY_ATTRIBUTE => 25
		];
		$content = [
			InteractionTypesSection::ENTITIES_PARAMETER => [
				[
					InteractionTypesBatch::ID_KEY => self::ID1,
					InteractionTypesBatch::INTERACTION_META_TYPE_KEY => InteractionMetaType::ACTION,
					InteractionTypesBatch::ATTRIBUTES_KEY => $attributes,
					InteractionTypesBatch::INTERACTION_WEIGHT_KEY => self::INTERACTION_WEIGHT,
					InteractionTypesBatch::INTERACTION_LEARN_WEIGHT_KEY => self::INTERACTION_LEARN_WEIGHT,
				]
			]
		];
		$this->mockPerformPost(InteractionTypesSection::INSERT_OR_UPDATE_INTERACTION_TYPE_URL, [], $content);
		$this->interactionTypesSection->insertOrUpdateInteractionType(
			self::ID1, InteractionMetaType::ACTION, $attributes,
			self::INTERACTION_WEIGHT, self::INTERACTION_LEARN_WEIGHT
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionTypeIdIsEmptyStringDuringInsertingOrUpdatingInteractionType()
	{
		$this->interactionTypesSection->insertOrUpdateInteractionType('');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionMetaTypeIsInvalidDuringInsertingOrUpdatingInteractionType()
	{
		$this->interactionTypesSection->insertOrUpdateInteractionType(self::ID1, 'foo');
	}

	/**
	 * @param float $interactionWeight
	 * @dataProvider getInvalidWeights
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionWeightIsInvalidDuringInsertingOrUpdatingInteractionType($interactionWeight)
	{
		$this->interactionTypesSection->insertOrUpdateInteractionType(self::ID1, NULL, [], $interactionWeight);
	}

	/**
	 * @param float $interactionLearnWeight
	 * @dataProvider getInvalidWeights
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionLearnWeightIsInvalidDuringInsertingOrUpdatingInteractionType($interactionLearnWeight)
	{
		$this->interactionTypesSection->insertOrUpdateInteractionType(self::ID1, NULL, [], NULL, $interactionLearnWeight);
	}

	public function testInsertingOrUpdatingInteractionTypes()
	{
		$time = new DateTime();
		$attributes1 = [self::DESCRIPTION_ATTRIBUTE => 'Foo'];
		$attributes2 = [
			self::DESCRIPTION_ATTRIBUTE => 'Bar',
			self::PRIORITY_ATTRIBUTE => 25
		];
		$batch = (new InteractionTypesBatch())
			->addInteractionType(self::ID1, InteractionMetaType::ACTION, $attributes1)
			->addInteractionType(
				self::ID2, NULL, $attributes2, self::INTERACTION_WEIGHT,
				self::INTERACTION_LEARN_WEIGHT, $time
			);
		$content = [InteractionTypesSection::ENTITIES_PARAMETER => $batch->getInteractionTypes()];
		$this->mockPerformPost(InteractionTypesSection::INSERT_OR_UPDATE_INTERACTION_TYPE_URL, [], $content);
		$this->interactionTypesSection->insertOrUpdateInteractionTypes($batch);
	}

	public function testGettingInteractionType()
	{
		$parameters = [InteractionTypesSection::INTERACTION_TYPE_ID_PARAMETER => self::ID1];
		$this->mockPerformGet(InteractionTypesSection::GET_INTERACTION_TYPE_URL, $parameters);
		$this->interactionTypesSection->getInteractionType(self::ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenIdsAreEmptyDuringGettingInteractionType()
	{
		$this->interactionTypesSection->getInteractionType('');
	}

	public function testGettingInteractionTypesWithoutParameters()
	{
		$this->mockPerformGet(InteractionTypesSection::GET_INTERACTION_TYPES_URL, []);
		$this->interactionTypesSection->getInteractionTypes();
	}

	public function testGettingInteractionTypesWithAllParameters()
	{
		$parameters = [
			InteractionTypesSection::LIMIT_PARAMETER => self::LIMIT,
			InteractionTypesSection::OFFSET_PARAMETER => self::OFFSET,
			InteractionTypesSection::ATTRIBUTES_PARAMETER => self::DESCRIPTION_ATTRIBUTE,
			InteractionTypesSection::SEARCH_QUERY_PARAMETER => self::SEARCH_QUERY,
			InteractionTypesSection::SEARCH_ATTRIBUTES_PARAMETER => self::DESCRIPTION_ATTRIBUTE . ',' . self::PRIORITY_ATTRIBUTE
		];
		$this->mockPerformGet(InteractionTypesSection::GET_INTERACTION_TYPES_URL, $parameters);
		$this->interactionTypesSection->getInteractionTypes(
			self::LIMIT, self::OFFSET, [self::DESCRIPTION_ATTRIBUTE],
			self::SEARCH_QUERY, [self::DESCRIPTION_ATTRIBUTE, self::PRIORITY_ATTRIBUTE]
		);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitIsNotANumberDuringGettingInteractionsTypes()
	{
		$this->interactionTypesSection->getInteractionTypes('foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenLimitIsNegativeNumberDuringGettingInteractionTypes()
	{
		$this->interactionTypesSection->getInteractionTypes(-10);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNotANumberDuringGettingInteractionTypes()
	{
		$this->interactionTypesSection->getInteractionTypes(NULL, 'foo');
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenOffsetIsNegativeNumberDuringGettingInteractionTypes()
	{
		$this->interactionTypesSection->getInteractionTypes(NULL, -10);
	}

	public function testDeletingInteractionType()
	{
		$parameters = [InteractionTypesSection::INTERACTION_TYPE_ID_PARAMETER => self::ID1];
		$this->mockPerformDelete(InteractionTypesSection::DELETE_INTERACTION_TYPE_URL, $parameters);
		$this->interactionTypesSection->deleteInteractionType(self::ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenInteractionTypeIdIsEmptyDuringDeletingInteractionType()
	{
		$this->interactionTypesSection->deleteInteractionType('');
	}

	/**
	 * @return array
	 */
	public function getInvalidWeights()
	{
		return [
			[-1.1],
			[-2],
			[1.1],
			[2],
		];
	}

}

(new InteractionTypesSectionTest())->run();
