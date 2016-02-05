<?php

namespace DataBreakers\DataApi\Batch;


use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\InteractionMetaType;
use DateTime;


class InteractionTypesBatch extends Batch
{

	const ID_KEY = 'id';
	const INTERACTION_META_TYPE_KEY = 'interactionMetaType';
	const ATTRIBUTES_KEY = 'attributes';
	const INTERACTION_WEIGHT_KEY = 'interactionWeight';
	const INTERACTION_LEARN_WEIGHT_KEY = 'interactionLearnWeight';
	const TIMESTAMP_KEY = 'timestamp';

	/** @var array */
	private $interactionTypes = [];


	/**
	 * @param string $interactionTypeId
	 * @param string|NULL $interactionMetaType
	 * @param array $attributes
	 * @param float|NULL $interactionWeight decimal number from interval <-1,1>
	 * @param float|NULL $interactionLearnWeight decimal number from interval <-1,1>
	 * @param DateTime|NULL $time
	 * @return $this
	 * @throws InvalidArgumentException when given entity id is empty string
	 * @throws InvalidArgumentException when given interaction meta type isn't valid meta type
	 * @throws InvalidArgumentException when given interaction weight isn't number from interval <-1,1>
	 * @throws InvalidArgumentException when given interaction learn weight isn't number from interval <-1,1>
	 */
	public function addInteractionType($interactionTypeId, $interactionMetaType = NULL, array $attributes = [],
												  $interactionWeight = NULL, $interactionLearnWeight = NULL, DateTime $time = NULL)
	{
		if ($interactionTypeId == '') {
			throw new InvalidArgumentException("Interaction type id can't be empty value.");
		}
		if ($interactionMetaType !== NULL && !InteractionMetaType::isValidInteractionMetaType($interactionMetaType)) {
			throw new InvalidArgumentException("Interaction type id can't be empty value.");
		}
		$interactionWeight = $this->validateWeight($interactionWeight, 'Interaction weight');
		$interactionLearnWeight = $this->validateWeight($interactionLearnWeight, 'Interaction learn weight');
		$interactionType = [self::ID_KEY => $interactionTypeId, self::ATTRIBUTES_KEY => $attributes];
		$interactionType = $this->setIfNotNull($interactionType, self::INTERACTION_META_TYPE_KEY, $interactionMetaType);
		$interactionType = $this->setIfNotNull($interactionType, self::INTERACTION_WEIGHT_KEY, $interactionWeight);
		$interactionType = $this->setIfNotNull($interactionType, self::INTERACTION_LEARN_WEIGHT_KEY, $interactionLearnWeight);
		$interactionType = $this->setIfNotNull(
			$interactionType, self::TIMESTAMP_KEY,
			$time !== NULL ? $time->getTimestamp() : NULL
		);
		$this->interactionTypes[] = $interactionType;
		return $this;
	}

	/**
	 * @return array of interaction types
	 *    all interaction types have format:
	 *       array(
	 *           id => 'id of interaction type',
	 * 			 interactionMetaType => 'action', // if set
	 *           attributes => array(... array of attributes ...),
	 * 			 interactionWeight => 0.5, // if set
	 * 			 interactionLearnWeight => 0.25, // if set
	 *           timestamp => 123456 // if set
	 *       )
	 */
	public function getInteractionTypes()
	{
		return $this->interactionTypes;
	}

	/**
	 * @inheritdoc
	 */
	protected function getBatchArray()
	{
		return $this->interactionTypes;
	}

	/**
	 * @param array $interactionType
	 * @param string $name
	 * @param mixed|NULL $value
	 * @return array
	 */
	private function setIfNotNull(array $interactionType, $name, $value)
	{
		if ($value !== NULL) {
			$interactionType[$name] = $value;
		}
		return $interactionType;
	}

	/**
	 * @param float|NULL $weight
	 * @param string $weightName
	 * @return float
	 * @throws InvalidArgumentException when given weight isn't number from interval <-1,1>
	 */
	private function validateWeight($weight, $weightName)
	{
		if ($weight === NULL) {
			return NULL;
		}
		$weight = (float) $weight;
		if ($weight < -1 || $weight > 1) {
			throw new InvalidArgumentException("{$weightName} must be decimal number in interval <-1,1>.");
		}
		return $weight;
	}

}
