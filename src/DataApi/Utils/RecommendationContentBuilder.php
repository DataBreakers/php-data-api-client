<?php

namespace DataBreakers\DataApi\Utils;

use DataBreakers\DataApi\Batch\RecommendationEntitiesBatch;
use DataBreakers\DataApi\RecommendationTemplateConfiguration;


class RecommendationContentBuilder
{

	const USER_ID_PARAMETER = 'userId';
	const USERS_PARAMETER = 'users';
	const ITEM_ID_PARAMETER = 'itemId';
	const ITEMS_PARAMETER = 'items';
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
	const RECOMMENDATION_FEEDBACK_PARAMETER = 'recommendationFeedback';
	const CATEGORY_BLACKLIST_PARAMETER = 'categoryBlacklist';
	const DIVERSITY_DECAY_PARAMETER = 'diversityDecay';
	const DIVERSITY_TYPES_PARAMETER = 'diversityTypes';
	const TYPE_PARAMETER = 'type';
	const SIMILARITY_TYPES_PARAMETER = 'similarityTypes';
	const DIVERSITY_CATEGORIES_PARAMETER = 'diversityCategories';
	const SIMILARITY_DIVERSITY_TYPE = 'similarity';
	const CATEGORIES_DIVERSITY_TYPE = 'categories';


	/**
	 * @param string|NULL|RecommendationEntitiesBatch $users
	 * @param string|NULL|RecommendationEntitiesBatch $items
	 * @param int $count
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 */
	public static function construct($users, $items, $count, $templateId = NULL,
									 RecommendationTemplateConfiguration $configuration = NULL)
	{
		$data = [self::COUNT_PARAMETER => $count];
		if ($users !== NULL && is_string($users)) {
			$data[self::USER_ID_PARAMETER] = $users;
		}
		if ($items !== NULL && is_string($items)) {
			$data[self::ITEM_ID_PARAMETER] = $items;
		}
		if ($users !== NULL && $users instanceof RecommendationEntitiesBatch) {
			$data[self::USERS_PARAMETER] = self::convertEntitiesBatchToArray($users, self::USER_ID_PARAMETER);
		}
		if ($items !== NULL && $items instanceof RecommendationEntitiesBatch) {
			$data[self::ITEMS_PARAMETER] = self::convertEntitiesBatchToArray($items, self::ITEM_ID_PARAMETER);
		}
		$template = self::getTemplateConfiguration($templateId, $configuration);
		$data = self::setIfNotNull($data, self::TEMPLATE_PARAMETER, $template);
		return $data;
	}

	/**
	 * @param string|NULL $templateId
	 * @param RecommendationTemplateConfiguration|NULL $configuration
	 * @return array
	 */
	private static function getTemplateConfiguration($templateId, RecommendationTemplateConfiguration $configuration = NULL)
	{
		if ($templateId === NULL && $configuration === NULL) {
			return NULL;
		}
		if ($configuration === NULL) {
			return [self::TEMPLATE_ID_PARAMETER => $templateId];
		}
		$data = [
			self::DISTINCT_PARAMETER => $configuration->getAttributesLimits(),
			self::DETAILS_PARAMETER => $configuration->areDetailsEnabled()
		];
		$data = self::setIfNotNull($data, self::TEMPLATE_ID_PARAMETER, $templateId);
		$data = self::setIfNotNull($data, self::FILTER_PARAMETER, $configuration->getFilter());
		$data = self::setIfNotNull($data, self::BOOSTER_PARAMETER, $configuration->getBooster());
		$data = self::setIfNotNull($data, self::USER_WEIGHT_PARAMETER, $configuration->getUserWeight());
		$data = self::setIfNotNull($data, self::ITEM_WEIGHT_PARAMETER, $configuration->getItemWeight());
		$data = self::setIfNotNull($data, self::DIVERSITY_PARAMETER, $configuration->getDiversity());
		$data = self::setIfNotNull($data, self::RECOMMENDATION_FEEDBACK_PARAMETER, $configuration->getRecommendationFeedback());
		$data = self::setIfNotNull($data, self::CATEGORY_BLACKLIST_PARAMETER, $configuration->getCategoryBlacklist());
		$data = self::setIfNotNull($data, self::DIVERSITY_DECAY_PARAMETER, $configuration->getDiversityDecay());
		$data = self::setIfNotNull($data, self::DIVERSITY_TYPES_PARAMETER, self::getDiversityTypes($configuration));
		return $data;
	}

	/**
	 * @param RecommendationTemplateConfiguration $configuration
	 * @return array
	 */
	private static function getDiversityTypes(RecommendationTemplateConfiguration $configuration)
	{
		$diversityTypes = [];
		if ($configuration->isDiversityByCategoriesEnabled()) {
			$type = [self::TYPE_PARAMETER => self::CATEGORIES_DIVERSITY_TYPE];
			if ($configuration->getDiversityCategories() !== NULL) {
				$type[self::DIVERSITY_CATEGORIES_PARAMETER] = $configuration->getDiversityCategories();
			}
			$diversityTypes[] = $type;
		}
		if ($configuration->isDiversityBySimilarityEnabled()) {
			$type = [self::TYPE_PARAMETER => self::SIMILARITY_DIVERSITY_TYPE];
			if ($configuration->getSimilarityTypes() !== NULL) {
				$type[self::SIMILARITY_TYPES_PARAMETER] = $configuration->getSimilarityTypes();
			}
			$diversityTypes[] = $type;
		}
		return $diversityTypes === [] ? NULL : $diversityTypes;
	}

	/**
	 * @param array $data
	 * @param string $name
	 * @param mixed|NULL $value
	 * @return array
	 */
	private static function setIfNotNull(array $data, $name, $value)
	{
		if ($value !== NULL) {
			$data[$name] = $value;
		}
		return $data;
	}

	/**
	 * @param RecommendationEntitiesBatch $batch
	 * @param string $entityIdKey
	 * @return array
	 */
	private static function convertEntitiesBatchToArray(RecommendationEntitiesBatch $batch, $entityIdKey)
	{
		return array_map(function($entity) use($entityIdKey) {
			$entity[$entityIdKey] = $entity[RecommendationEntitiesBatch::ENTITY_ID_KEY];
			unset($entity[RecommendationEntitiesBatch::ENTITY_ID_KEY]);
			return $entity;
		}, $batch->getEntities());
	}

}
