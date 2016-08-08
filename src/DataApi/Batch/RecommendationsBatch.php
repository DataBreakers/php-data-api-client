<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\RecommendationTemplateConfiguration;
use DataBreakers\DataApi\Utils\RecommendationContentBuilder;


class RecommendationsBatch extends Batch
{

	const PRIORITY_IMPORTANCE_TYPE = 'priority';
	const WEIGHT_IMPORTANCE_TYPE = 'weight';

	const SEQUENTIAL_EVALUATION = 'sequential';
	const PARALLEL_EVALUATION = 'parallel';

	const REQUEST_ID_KEY = 'requestId';
	const IMPORTANCE_KEY = 'importance';
	const REQUEST_KEY = 'request';

	/** @var array */
	private $recommendations = [];


	/**
	 * @param string $requestId
	 * @param float $importance
	 * @param string|RecommendationEntitiesBatch|NULL $users user id or users batch
	 * @param string|RecommendationEntitiesBatch|NULL $items item id or items batch
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @param int|NULL $offset
	 * @return $this
	 * @throws InvalidArgumentException when given request id is empty string value
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 */
	public function requestRecommendations($requestId, $importance, $users, $items, $count, $templateId = NULL,
										   RecommendationTemplateConfiguration $configuration = NULL, $offset = NULL)
	{
		return $this->addRecommendationsRequest($requestId, $importance, $users, $items, $count, $templateId, $configuration, $offset);
	}

	/**
	 * @param string $requestId
	 * @param float $importance
	 * @param string $userId
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @param int|NULL $offset
	 * @return $this
	 * @throws InvalidArgumentException when given request id is empty string value
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 */
	public function requestRecommendationsForUser($requestId, $importance, $userId, $count, $templateId = NULL,
											      RecommendationTemplateConfiguration $configuration = NULL, $offset = NULL)
	{
		return $this->addRecommendationsRequest($requestId, $importance, $userId, NULL, $count, $templateId, $configuration, $offset);
	}

	/**
	 * @param string $requestId
	 * @param float $importance
	 * @param RecommendationEntitiesBatch $users
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @param int|NULL $offset
	 * @return $this
	 * @throws InvalidArgumentException when given request id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 */
	public function requestRecommendationsForUsers($requestId, $importance, RecommendationEntitiesBatch $users, $count, $templateId = NULL,
												   RecommendationTemplateConfiguration $configuration = NULL, $offset = NULL)
	{
		return $this->addRecommendationsRequest($requestId, $importance, $users, NULL, $count, $templateId, $configuration, $offset);
	}

	/**
	 * @param string $requestId
	 * @param float $importance
	 * @param string $itemId
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @param int|NULL $offset
	 * @return $this
	 * @throws InvalidArgumentException when given request id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 */
	public function requestRecommendationsForItem($requestId, $importance, $itemId, $count, $templateId = NULL,
											      RecommendationTemplateConfiguration $configuration = NULL, $offset = NULL)
	{
		return $this->addRecommendationsRequest($requestId, $importance, NULL, $itemId, $count, $templateId, $configuration, $offset);
	}

	/**
	 * @param string $requestId
	 * @param float $importance
	 * @param RecommendationEntitiesBatch $items
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @param int|NULL $offset
	 * @return $this
	 * @throws InvalidArgumentException when given request id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 */
	public function requestRecommendationsForItems($requestId, $importance, RecommendationEntitiesBatch $items, $count, $templateId = NULL,
												   RecommendationTemplateConfiguration $configuration = NULL, $offset = NULL)
	{
		return $this->addRecommendationsRequest($requestId, $importance, NULL, $items, $count, $templateId, $configuration, $offset);
	}

	/**
	 * @param string $requestId
	 * @param float $importance
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @param int|NULL $offset
	 * @return $this
	 * @throws InvalidArgumentException when given request id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 */
	public function requestGeneralRecommendations($requestId, $importance, $count, $templateId = NULL,
												  RecommendationTemplateConfiguration $configuration = NULL, $offset = NULL)
	{
		return $this->addRecommendationsRequest($requestId, $importance, NULL, NULL, $count, $templateId, $configuration, $offset);
	}

	/**
	 * @return array of recommendations
	 *    all recommendations have format:
	 *       array(
	 *           requestId => 'id of request',
	 * 			 importance => 1.0, // number representing request importance
	 *           request => array(... recommendation request ...)
	 *       )
	 */
	public function getRecommendations()
	{
		return $this->recommendations;
	}

	/**
	 * @inheritdoc
	 */
	protected function getBatchArray()
	{
		return $this->recommendations;
	}

	/**
	 * @param string $requestId
	 * @param float $importance
	 * @param string|NULL|RecommendationEntitiesBatch $users
	 * @param string|NULL|RecommendationEntitiesBatch $items
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @param int|NULL $offset
	 * @return $this
	 * @throws InvalidArgumentException when given request id is empty string value
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 */
	private function addRecommendationsRequest($requestId, $importance, $users, $items, $count, $templateId = NULL,
											   RecommendationTemplateConfiguration $configuration = NULL, $offset = NULL)
	{
		if ($requestId == '') {
			throw new InvalidArgumentException("Request id can't be empty string value.");
		}
		if ($users !== NULL && is_string($users) && $users == '') {
			throw new InvalidArgumentException("User id can't be empty string value.");
		}
		if ($items !== NULL && is_string($items) && $items == '') {
			throw new InvalidArgumentException("Item id can't be empty string value.");
		}
		if (!is_int($count) || $count <= 0) {
			throw new InvalidArgumentException("Count must be integer value bigger than 0.");
		}
		if ($offset !== NULL && (!is_int($offset) || $offset < 0)) {
			throw new InvalidArgumentException("Offset must be integer value bigger or equal to 0.");
		}
		$request = RecommendationContentBuilder::construct($users, $items, $count, $templateId, $configuration, $offset);
		$this->recommendations[] = [
			self::REQUEST_ID_KEY => $requestId,
			self::IMPORTANCE_KEY => (float) $importance,
			self::REQUEST_KEY => $request
		];
		return $this;
	}

}
