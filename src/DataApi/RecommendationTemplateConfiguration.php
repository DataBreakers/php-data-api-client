<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;


class RecommendationTemplateConfiguration extends TemplateConfiguration
{

	const ATTRIBUTE_ID_KEY = 'attributeId';
	const LIMIT_IN_REQUEST_KEY = 'limitInRequest';
	const TIME_KEY = 'time';
	const LIMIT_IN_TIME_KEY = 'limitInTime';

	/** @var array */
	private $attributesLimits = [];

	/** @var bool */
	private $details;

	/** @var bool|NULL */
	private $recommendationFeedback;

	/** @var bool|NULL */
	private $categoryBlacklist;


	/**
	 * @param string|NULL $filter
	 * @param string|NULL $booster
	 * @param float|NULL $userWeight
	 * @param float|NULL $itemWeight
	 * @param float|NULL $diversity
	 * @param bool $details
	 * @param bool|NULL $recommendationFeedback
	 * @param bool|NULL $categoryBlacklist
	 */
	public function __construct($filter = NULL, $booster = NULL, $userWeight = NULL, $itemWeight = NULL, $diversity = NULL,
								$details = false, $recommendationFeedback = NULL, $categoryBlacklist = NULL)
	{
		parent::__construct($filter, $booster, $userWeight, $itemWeight, $diversity);
		$this->details = $details;
		$this->recommendationFeedback = $recommendationFeedback;
		$this->categoryBlacklist = $categoryBlacklist;
	}

	/**
	 * @return array
	 */
	public function getAttributesLimits()
	{
		return $this->attributesLimits;
	}

	/**
	 * @param string $attributeId
	 * @param int $limitInRequest
	 * @return $this
	 * @throws InvalidArgumentException when given attribute id is empty string value
	 * @throws InvalidArgumentException when given limit in request isn't integer or is smaller or equal to zero
	 */
	public function addAttributeLimitInRequest($attributeId, $limitInRequest)
	{
		return $this->addAttributeLimit($attributeId, $limitInRequest);
	}

	/**
	 * @param string $attributeId
	 * @param int $time
	 * @param int $limitInTime
	 * @return $this
	 * @throws InvalidArgumentException when given attribute id is empty string value
	 * @throws InvalidArgumentException when given time isn't integer or is smaller or equal to zero
	 * @throws InvalidArgumentException when given limit in time isn't integer or is smaller or equal to zero
	 */
	public function addAttributeLimitInTime($attributeId, $time, $limitInTime)
	{
		return $this->addAttributeLimit($attributeId, NULL, $time, $limitInTime);
	}

	/**
	 * @param string $attributeId
	 * @param int $limitInRequest
	 * @param int $time
	 * @param int $limitInTime
	 * @return $this
	 * @throws InvalidArgumentException when given attribute id is empty string value
	 * @throws InvalidArgumentException when given limit in request isn't integer or is smaller or equal to zero
	 * @throws InvalidArgumentException when given time isn't integer or is smaller or equal to zero
	 * @throws InvalidArgumentException when given limit in time isn't integer or is smaller or equal to zero
	 */
	public function addAttributeLimitInRequestAndTime($attributeId, $limitInRequest, $time, $limitInTime)
	{
		return $this->addAttributeLimit($attributeId, $limitInRequest, $time, $limitInTime);
	}

	/**
	 * @return bool
	 */
	public function areDetailsEnabled()
	{
		return $this->details;
	}

	/**
	 * @return $this
	 */
	public function enableDetails()
	{
		$this->details = true;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function disableDetails()
	{
		$this->details = false;
		return $this;
	}

	/**
	 * @return bool|NULL
	 */
	public function getRecommendationFeedback()
	{
		return $this->recommendationFeedback;
	}

	/**
	 * @return $this
	 */
	public function enableRecommendationFeedback()
	{
		$this->recommendationFeedback = true;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function disableRecommendationFeedback()
	{
		$this->recommendationFeedback = false;
		return $this;
	}

	/**
	 * @return bool|NULL
	 */
	public function getCategoryBlacklist()
	{
		return $this->categoryBlacklist;
	}

	/**
	 * @return $this
	 */
	public function enableCategoryBlacklist()
	{
		$this->categoryBlacklist = true;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function disableCategoryBlacklist()
	{
		$this->categoryBlacklist = false;
		return $this;
	}

	/**
	 * @param string $attributeId
	 * @param int|NULL $limitInRequest
	 * @param int|NULL $time
	 * @param int|NULL $limitInTime
	 * @returns $this
	 * @throws InvalidArgumentException when given attribute id is empty string value
	 * @throws InvalidArgumentException when given limit in request is set and isn't integer or is smaller or equal to zero
	 * @throws InvalidArgumentException when given time is set and isn't integer or is smaller or equal to zero
	 * @throws InvalidArgumentException when given limit in time is set and isn't integer or is smaller or equal to zero
	 */
	private function addAttributeLimit($attributeId, $limitInRequest = NULL, $time = NULL, $limitInTime = NULL)
	{
		if ($attributeId == '') {
			throw new InvalidArgumentException("Attribute id can't be empty string value.");
		}
		$limit = [self::ATTRIBUTE_ID_KEY => $attributeId];
		if ($limitInRequest !== NULL) {
			if (!is_int($limitInRequest) || $limitInRequest <= 0) {
				throw new InvalidArgumentException("Limit in request must be integer value bigger than 0.");
			}
			$limit[self::LIMIT_IN_REQUEST_KEY] = $limitInRequest;
		}
		if ($time !== NULL && $limitInTime !== NULL) {
			if (!is_int($time) || $time <= 0) {
				throw new InvalidArgumentException("Time must be integer value bigger than 0.");
			}
			if (!is_int($limitInTime) || $limitInTime <= 0) {
				throw new InvalidArgumentException("Limit in time must be integer value bigger than 0.");
			}
			$limit[self::TIME_KEY] = $time;
			$limit[self::LIMIT_IN_TIME_KEY] = $limitInTime;
		}
		$this->attributesLimits[] = $limit;
		return $this;
	}

}
