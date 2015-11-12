<?php

namespace DataBreakers\DataApi\Sections;


use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\RecommendationTemplateConfiguration;
use DataBreakers\DataApi\Utils\Restriction;


class RecommendationSection extends Section
{

	const GET_RECOMMENDATION_URL = '/{accountId}/model/{modelId}/recommend';

	const DEFAULT_MODEL_ID = 'default';
	const MODEL_ID_PARAMETER = 'modelId';
	const USER_ID_PARAMETER = 'userId';
	const ITEM_ID_PARAMETER = 'itemId';
	const COUNT_PARAMETER = 'count';
	const TEMPLATE_PARAMETER = 'template';
	const TEMPLATE_ID_PARAMETER = 'templateId';
	const FILTER_PARAMETER = 'filter';
	const BOOSTER_PARAMETER = 'booster';
	const USER_WEIGHT_PARAMETER = 'userWeight';
	const ITEM_WEIGHT_PARAMETER = 'itemWeight';
	const DIVERSITY_PARAMETER = 'diversity';
	const DISTINCT_PARAMETER = 'distinct';
	const DETAILS_PARAMETER = 'details';


	/**
	 * @param string $userId
	 * @param string $itemId
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
		$content = [
			self::COUNT_PARAMETER => $count,
			self::TEMPLATE_PARAMETER => $this->getTemplateConfiguration($templateId, $configuration)
		];
		$content = $this->setContentIfNotNull($content, self::USER_ID_PARAMETER, $userId);
		$content = $this->setContentIfNotNull($content, self::ITEM_ID_PARAMETER, $itemId);
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
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 */
	private function getTemplateConfiguration($templateId, RecommendationTemplateConfiguration $configuration = NULL)
	{
		if ($templateId === NULL && $configuration === NULL) {
			return [];
		}
		if ($configuration === NULL) {
			return [self::TEMPLATE_ID_PARAMETER => $templateId];
		}
		$data = [
			self::DISTINCT_PARAMETER => $configuration->getAttributesLimits(),
			self::DETAILS_PARAMETER => $configuration->areDetailsEnabled()
		];
		$data = $this->setContentIfNotNull($data, self::TEMPLATE_ID_PARAMETER, $templateId);
		$data = $this->setContentIfNotNull($data, self::FILTER_PARAMETER, $configuration->getFilter());
		$data = $this->setContentIfNotNull($data, self::BOOSTER_PARAMETER, $configuration->getBooster());
		$data = $this->setContentIfNotNull($data, self::USER_WEIGHT_PARAMETER, $configuration->getUserWeight());
		$data = $this->setContentIfNotNull($data, self::ITEM_WEIGHT_PARAMETER, $configuration->getItemWeight());
		$data = $this->setContentIfNotNull($data, self::DIVERSITY_PARAMETER, $configuration->getDiversity());
		return $data;
	}

}
