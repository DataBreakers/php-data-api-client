<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Batch\RecommendationEntitiesBatch;
use DataBreakers\DataApi\Batch\RecommendationsBatch;
use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\RecommendationTemplateConfiguration;
use DataBreakers\DataApi\Utils\RecommendationContentBuilder;
use DataBreakers\DataApi\Utils\Restriction;
use DateTime;


class RecommendationSection extends Section
{

	const GET_RECOMMENDATION_URL = '/{accountId}/model/{modelId}/recommend';
	const GET_RECOMMENDATIONS_BATCH_URL = '/{accountId}/model/{modelId}/recommend/batch';

	const DEFAULT_MODEL_ID = 'default';
	const MODEL_ID_PARAMETER = 'modelId';
	const REQUESTS_PARAMETER = 'requests';
	const EVALUATION_PARAMETER = 'evaluation';
	const IMPORTANCE_TYPE_PARAMETER = 'importanceType';
	const UNIQUE_RECOMMENDATIONS_PARAMETER = 'uniqueRecommendations';


	/**
	 * @param string|NULL|RecommendationEntitiesBatch $users
	 * @param string|NULL|RecommendationEntitiesBatch $items
	 * @param int $count
	 * @param int|NULL $offset
	 * @param string|NULL $templateId
	 * @param DateTime|NULL $time
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendations($users, $items, $count, $offset = NULL, $templateId = NULL,
									   DateTime $time = NULL,
									   RecommendationTemplateConfiguration $configuration = NULL)
	{
		if ($users !== NULL && is_string($users) && $users == '') {
			throw new InvalidArgumentException("User id can't be empty string value.");
		}
		if ($items !== NULL && is_string($items) && $items == '') {
			throw new InvalidArgumentException("Item id can't be empty string value.");
		}
		if (!is_int($count) || $count <= 0) {
			throw new InvalidArgumentException("Count must be integer value bigger than 0.");
		}
		$parameters = [self::MODEL_ID_PARAMETER => self::DEFAULT_MODEL_ID];
		$content = RecommendationContentBuilder::construct($users, $items, $count, $offset, $templateId, $time, $configuration);
		$restriction = new Restriction($parameters, $content);
		return $this->performPost(self::GET_RECOMMENDATION_URL, $restriction);
	}

	/**
	 * @param string $userId
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendationsForUser($userId, $count, $templateId = NULL,
											  RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->getRecommendations($userId, NULL, $count, $templateId, $configuration);
	}

	/**
	 * @param RecommendationEntitiesBatch $users
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendationsForUsers(RecommendationEntitiesBatch $users, $count, $templateId = NULL,
											   RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->getRecommendations($users, NULL, $count, $templateId, $configuration);
	}

	/**
	 * @param string $itemId
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendationsForItem($itemId, $count, $templateId = NULL,
											  RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->getRecommendations(NULL, $itemId, $count, $templateId, $configuration);
	}

	/**
	 * @param RecommendationEntitiesBatch $items
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendationsForItems(RecommendationEntitiesBatch $items, $count, $templateId = NULL,
											   RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->getRecommendations(NULL, $items, $count, $templateId, $configuration);
	}

	/**
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getGeneralRecommendations($count, $templateId = NULL,
											  RecommendationTemplateConfiguration $configuration = NULL)
	{
		return $this->getRecommendations(NULL, NULL, $count, $templateId, $configuration);
	}

	/**
	 * @param RecommendationsBatch $batch
	 * @param bool $uniqueRecommendations
	 * @param string $evaluation
	 * @param string $importanceType
	 * @return array
	 * @throws InvalidArgumentException when given evaluation isn't 'parallel' or 'sequential'
	 * @throws InvalidArgumentException when given evaluation isn't 'priority' or 'weight'
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendationsBatch(RecommendationsBatch $batch,
											$uniqueRecommendations = true,
											$evaluation = RecommendationsBatch::PARALLEL_EVALUATION,
											$importanceType = RecommendationsBatch::PRIORITY_IMPORTANCE_TYPE)
	{
		$validEvaluations = [
			RecommendationsBatch::PARALLEL_EVALUATION,
			RecommendationsBatch::SEQUENTIAL_EVALUATION
		];
		if (!in_array($evaluation, $validEvaluations)) {
			throw new InvalidArgumentException("Given evaluation '{$evaluation}' isn't valid.");
		}
		$validImportanceTypes = [
			RecommendationsBatch::PRIORITY_IMPORTANCE_TYPE,
			RecommendationsBatch::WEIGHT_IMPORTANCE_TYPE,
		];
		if (!in_array($importanceType, $validImportanceTypes)) {
			throw new InvalidArgumentException("Given importance type '{$importanceType}' isn't valid.");
		}
		$parameters = [self::MODEL_ID_PARAMETER => self::DEFAULT_MODEL_ID];
		$content = [
			self::REQUESTS_PARAMETER => $batch->getRecommendations(),
			self::EVALUATION_PARAMETER => $evaluation,
			self::IMPORTANCE_TYPE_PARAMETER => $importanceType,
			self::UNIQUE_RECOMMENDATIONS_PARAMETER => (bool) $uniqueRecommendations,
		];
		$restriction = new Restriction($parameters, $content);
		return $this->performPost(self::GET_RECOMMENDATIONS_BATCH_URL, $restriction);
	}

}
