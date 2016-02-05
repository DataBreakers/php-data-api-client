<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Batch\InteractionTypesBatch;
use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\Restriction;
use DateTime;


class InteractionTypesSection extends Section
{

	const INSERT_OR_UPDATE_INTERACTION_TYPE_URL = '/{accountId}/interaction/type';
	const GET_INTERACTION_TYPE_URL = '/{accountId}/interactions/type/{interactionTypeId}';
	const GET_INTERACTION_TYPES_URL = '/{accountId}/interactions/type';
	const DELETE_INTERACTION_TYPE_URL = '/{accountId}/interactions/type/{interactionTypeId}';

	const ENTITIES_PARAMETER = 'entities';
	const INTERACTION_TYPE_ID_PARAMETER = 'interactionTypeId';
	const LIMIT_PARAMETER = 'limit';
	const OFFSET_PARAMETER = 'offset';
	const ATTRIBUTES_PARAMETER = 'attributes';
	const SEARCH_QUERY_PARAMETER = 'searchQuery';
	const SEARCH_ATTRIBUTES_PARAMETER = 'searchAttributes';


	/**
	 * @param string $interactionTypeId
	 * @param string|NULL $interactionMetaType
	 * @param array $attributes
	 * @param float|NULL $interactionWeight decimal number from interval <-1,1>
	 * @param float|NULL $interactionLearnWeight decimal number from interval <-1,1>
	 * @param DateTime|NULL $time
	 * @return NULL
	 * @throws InvalidArgumentException when given entity id is empty string
	 * @throws InvalidArgumentException when given interaction meta type isn't valid meta type
	 * @throws InvalidArgumentException when given interaction weight isn't number from interval <-1,1>
	 * @throws InvalidArgumentException when given interaction learn weight isn't number from interval <-1,1>
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateInteractionType($interactionTypeId, $interactionMetaType = NULL, array $attributes = [],
												  $interactionWeight = NULL, $interactionLearnWeight = NULL, DateTime $time = NULL)
	{
		return $this->insertOrUpdateInteractionTypes((new InteractionTypesBatch())
			->addInteractionType(
				$interactionTypeId, $interactionMetaType, $attributes,
				$interactionWeight, $interactionLearnWeight, $time
			)
		);
	}

	/**
	 * @param InteractionTypesBatch $interactionTypes
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateInteractionTypes(InteractionTypesBatch $interactionTypes)
	{
		$restriction = new Restriction([], [self::ENTITIES_PARAMETER => $interactionTypes->getInteractionTypes()]);
		return $this->performPost(self::INSERT_OR_UPDATE_INTERACTION_TYPE_URL, $restriction);
	}

	/**
	 * @param string $interactionTypeId
	 * @return NULL
	 * @throws InvalidArgumentException when given interaction type id is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getInteractionType($interactionTypeId)
	{
		$this->validateInteractionTypeId($interactionTypeId);
		$restriction = new Restriction([self::INTERACTION_TYPE_ID_PARAMETER => $interactionTypeId]);
		return $this->performGet(self::GET_INTERACTION_TYPE_URL, $restriction);
	}

	/**
	 * @param int|NULL $limit
	 * @param int|NULL $offset
	 * @param array|NULL $attributes
	 * @param string|NULL $searchQuery
	 * @param array|NULL $searchAttributes
	 * @returns array
	 * @throws InvalidArgumentException when given limit isn't number or is negative
	 * @throws InvalidArgumentException when given offset isn't number or is negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getInteractionTypes($limit = NULL, $offset = NULL, array $attributes = NULL,
										$searchQuery = NULL, array $searchAttributes = NULL)
	{
		$this->validateLimitAndOffset($limit, $offset);
		$restriction = new Restriction();
		$this->addParameterIfNotNull($restriction, self::LIMIT_PARAMETER, $limit);
		$this->addParameterIfNotNull($restriction, self::OFFSET_PARAMETER, $offset);
		$attributes = $attributes === NULL ? NULL : implode(',', $attributes);
		$this->addParameterIfNotNull($restriction, self::ATTRIBUTES_PARAMETER, $attributes);
		$this->addParameterIfNotNull($restriction, self::SEARCH_QUERY_PARAMETER, $searchQuery);
		$searchAttributes = $searchAttributes === NULL ? NULL : implode(',', $searchAttributes);
		$this->addParameterIfNotNull($restriction, self::SEARCH_ATTRIBUTES_PARAMETER, $searchAttributes);
		return $this->performGet(self::GET_INTERACTION_TYPES_URL, $restriction);
	}

	/**
	 * @param string $interactionTypeId
	 * @return NULL
	 * @throws InvalidArgumentException when given interaction type id is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteInteractionType($interactionTypeId)
	{
		$this->validateInteractionTypeId($interactionTypeId);
		$restriction = new Restriction([self::INTERACTION_TYPE_ID_PARAMETER => $interactionTypeId]);
		return $this->performDelete(self::DELETE_INTERACTION_TYPE_URL, $restriction);
	}

	/**
	 * @param string $interactionTypeId
	 * @return void
	 * @throws InvalidArgumentException when given interaction type id is empty
	 */
	private function validateInteractionTypeId($interactionTypeId)
	{
		if ($interactionTypeId == '') {
			throw new InvalidArgumentException("Interaction type id can't be empty string.");
		}
	}

}
