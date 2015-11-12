<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Batch\InteractionsBatch;
use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\Restriction;
use DateTime;


class InteractionsSection extends Section
{

	const INSERT_INTERACTION_URL = '/{accountId}/interaction';
	const DELETE_INTERACTION_URL = '/{accountId}/interaction{?userId,itemId,timestamp}';
	const DELETE_INTERACTIONS_URL = '/{accountId}/interactions';
	const GET_INTERACTIONS_DEFINITIONS_URL = '/{accountId}/interactions/definitions';

	const INTERACTIONS_PARAMETER = 'interactions';
	const USER_ID_PARAMETER = 'userId';
	const ITEM_ID_PARAMETER = 'itemId';
	const INTERACTION_PARAMETER = 'interaction';
	const INTERACTION_ID_PARAMETER = 'interactionId';
	const TIMESTAMP_PARAMETER = 'timestamp';
	const LIMIT_PARAMETER = 'limit';
	const OFFSET_PARAMETER = 'offset';
	const ATTRIBUTES_PARAMETER = 'attributes';
	const SEARCH_QUERY_PARAMETER = 'searchQuery';
	const SEARCH_ATTRIBUTES_PARAMETER = 'searchAttributes';


	/**
	 * @param string $userId
	 * @param string $itemId
	 * @param string $interactionId
	 * @param DateTime|NULL $time
	 * @return NULL
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given interaction id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertInteraction($userId, $itemId, $interactionId, DateTime $time = NULL)
	{
		$batch = (new InteractionsBatch())->addInteraction($userId, $itemId, $interactionId, $time);
		return $this->insertInteractions($batch);
	}

	/**
	 * @param InteractionsBatch $batch
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertInteractions(InteractionsBatch $batch)
	{
		$restriction = new Restriction([], [self::INTERACTIONS_PARAMETER => $batch->getInteractions()]);
		return $this->performPost(self::INSERT_INTERACTION_URL, $restriction);
	}

	/**
	 * @param string $userId
	 * @param string $itemId
	 * @param DateTime $time
	 * @return NULL
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteInteraction($userId, $itemId, DateTime $time)
	{
		if ($userId == '') {
			throw new InvalidArgumentException("User id can't be empty value.");
		}
		if ($itemId == '') {
			throw new InvalidArgumentException("Item id can't be empty value.");
		}
		$restriction = new Restriction([
			self::USER_ID_PARAMETER => $userId,
			self::ITEM_ID_PARAMETER => $itemId,
			self::TIMESTAMP_PARAMETER => $time->getTimestamp()
		]);
		return $this->performDelete(self::DELETE_INTERACTION_URL, $restriction);
	}

	/**
	 * @param string $userId
	 * @return NULL
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteUserInteractions($userId)
	{
		if ($userId == '') {
			throw new InvalidArgumentException("User id can't be empty value.");
		}
		$restriction = new Restriction([self::USER_ID_PARAMETER => $userId]);
		return $this->performDelete(self::DELETE_INTERACTION_URL, $restriction);
	}

	/**
	 * @param string $itemId
	 * @return NULL
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteItemInteractions($itemId)
	{
		if ($itemId == '') {
			throw new InvalidArgumentException("Item id can't be empty value.");
		}
		$restriction = new Restriction([self::ITEM_ID_PARAMETER => $itemId]);
		return $this->performDelete(self::DELETE_INTERACTION_URL, $restriction);
	}

	/**
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteInteractions()
	{
		return $this->performDelete(self::DELETE_INTERACTIONS_URL);
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 * @param array|NULL $attributes
	 * @param string|NULL $searchQuery
	 * @param array|NULL $searchAttributes
	 * @return array
	 * @throws InvalidArgumentException when given limit isn't number or is negative
	 * @throws InvalidArgumentException when given offset isn't number or is negative
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getInteractionDefinitions($limit = 100, $offset = 0, array $attributes = NULL, $searchQuery = NULL,
											  array $searchAttributes = NULL)
	{
		$this->validateLimitAndOffset($limit, $offset);
		$restriction = new Restriction([self::LIMIT_PARAMETER => $limit, self::OFFSET_PARAMETER => $offset]);
		$attributes = $attributes === NULL ? NULL : implode(',', $attributes);
		$this->addParameterIfNotNull($restriction, self::ATTRIBUTES_PARAMETER, $attributes);
		$this->addParameterIfNotNull($restriction, self::SEARCH_QUERY_PARAMETER, $searchQuery);
		$searchAttributes = $searchAttributes === NULL ? NULL : implode(',', $searchAttributes);
		$this->addParameterIfNotNull($restriction, self::SEARCH_ATTRIBUTES_PARAMETER, $searchAttributes);
		return $this->performGet(self::GET_INTERACTIONS_DEFINITIONS_URL, $restriction);
	}

}
