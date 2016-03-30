<?php

namespace DataBreakers\DataApi\Sections;


use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\RecommendationTemplateConfiguration;
use DataBreakers\DataApi\Utils\RecommendationContentBuilder;
use DataBreakers\DataApi\Utils\Restriction;


class RecommendationSection extends Section
{

	const GET_RECOMMENDATION_URL = '/{accountId}/model/{modelId}/recommend';

	const DEFAULT_MODEL_ID = 'default';
	const MODEL_ID_PARAMETER = 'modelId';


	/**
	 * @param string|NULL $userId
	 * @param string|NULL $itemId
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given count isn't integer value or is zero or negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getRecommendations($userId, $itemId, $count, $templateId = NULL,
									   RecommendationTemplateConfiguration $configuration = NULL)
	{
		if ($userId !== NULL && $userId == '') {
			throw new InvalidArgumentException("User id can't be empty string value.");
		}
		if ($itemId !== NULL && $itemId == '') {
			throw new InvalidArgumentException("Item id can't be empty string value.");
		}
		if (!is_int($count) || $count <= 0) {
			throw new InvalidArgumentException("Count must be integer value bigger than 0.");
		}
		$parameters = [self::MODEL_ID_PARAMETER => self::DEFAULT_MODEL_ID];
		$content = RecommendationContentBuilder::construct($userId, $itemId, $count, $templateId, $configuration);
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

}
