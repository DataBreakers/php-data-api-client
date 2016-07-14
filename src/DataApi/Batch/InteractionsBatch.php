<?php

namespace DataBreakers\DataApi\Batch;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DateTime;


class InteractionsBatch extends Batch
{

	const USER_ID_KEY = 'userId';
	const ITEM_ID_KEY = 'itemId';
	const INTERACTION_KEY = 'interaction';
	const INTERACTION_ID_KEY = 'interactionId';
	const TIMESTAMP_KEY = 'timestamp';
	const ATTRIBUTES_KEY = 'attributes';

	/** @var array */
	private $interactions = [];


	/**
	 * @param $userId
	 * @param $itemId
	 * @param $interactionId
	 * @param DateTime|NULL $time
	 * @param array|NULL $attributes
	 * @return $this
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given item id is empty string value
	 * @throws InvalidArgumentException when given interaction id is empty string value
	 */
	public function addInteraction($userId, $itemId, $interactionId, DateTime $time = NULL, array $attributes = NULL)
	{
		if ($userId == '') {
			throw new InvalidArgumentException("User id can't be empty value.");
		}
		if ($itemId == '') {
			throw new InvalidArgumentException("Item id can't be empty value.");
		}
		if ($interactionId == '') {
			throw new InvalidArgumentException("Interaction id can't be empty value.");
		}
		$interactionArray[self::INTERACTION_ID_KEY] = $interactionId;
		if ($attributes != NULL) {
			$interactionArray[self::ATTRIBUTES_KEY] = $attributes;
		}
		$interaction = [
			self::USER_ID_KEY => $userId,
			self::ITEM_ID_KEY => $itemId,
			self::INTERACTION_KEY => $interactionArray,
		];
		if ($time !== NULL) {
			$interaction[self::TIMESTAMP_KEY] = $time->getTimestamp();
		}
		$this->interactions[] = $interaction;
		return $this;
	}

	/**
	 * @return array of interactions
	 *    all interactions have format:
	 *       array(
	 *           userId => 'id of user',
	 *           itemId => 'id of item',
	 * 			 interaction => array(
     *               interactionId => 'id of interaction'
	 *           ),
	 *           timestamp => 123456 // if set
	 *       )
	 */
	public function getInteractions()
	{
		return $this->interactions;
	}

	/**
	 * @inheritdoc
	 */
	protected function getBatchArray()
	{
		return $this->interactions;
	}

}
