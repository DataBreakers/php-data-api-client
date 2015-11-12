<?php

namespace DataBreakers\DataApi;


use DataBreakers\DataApi\Exceptions\InvalidArgumentException;


class TemplateConfiguration
{

	/** @var string|NULL */
	private $filter;

	/** @var string|NULL */
	private $booster;

	/** @var float|NULL */
	private $userWeight;

	/** @var float|NULL */
	private $itemWeight;

	/** @var float|NULL */
	private $diversity;


	/**
	 * @param string|NULL $filter
	 * @param string|NULL $booster
	 * @param float|NULL $userWeight
	 * @param float|NULL $itemWeight
	 * @param float|NULL $diversity
	 */
	public function __construct($filter = NULL, $booster = NULL, $userWeight = NULL, $itemWeight = NULL, $diversity = NULL)
	{
		$this->setFilter($filter);
		$this->setBooster($booster);
		$this->setUserWeight($userWeight);
		$this->setItemWeight($itemWeight);
		$this->setDiversity($diversity);
	}

	/**
	 * @return NULL|string
	 */
	public function getFilter()
	{
		return $this->filter;
	}

	/**
	 * @param NULL|string $filter
	 * @return $this
	 */
	public function setFilter($filter)
	{
		$this->filter = $filter === NULL ? NULL : (string) $filter;
		return $this;
	}

	/**
	 * @return NULL|string
	 */
	public function getBooster()
	{
		return $this->booster;
	}

	/**
	 * @param NULL|string $booster
	 * @return $this
	 */
	public function setBooster($booster)
	{
		$this->booster = $booster === NULL ? NULL : (string) $booster;
		return $this;
	}

	/**
	 * @return float|NULL
	 */
	public function getUserWeight()
	{
		return $this->userWeight;
	}

	/**
	 * @param float|NULL $userWeight
	 * @return $this
	 * @throws InvalidArgumentException when given user weight isn't float value
	 */
	public function setUserWeight($userWeight)
	{
		if ($userWeight !== NULL && !is_numeric($userWeight)) {
			throw new InvalidArgumentException("User weight must be float value.");
		}
		$this->userWeight = $userWeight;
		return $this;
	}

	/**
	 * @return float|NULL
	 */
	public function getItemWeight()
	{
		return $this->itemWeight;
	}

	/**
	 * @param float|NULL $itemWeight
	 * @return $this
	 * @throws InvalidArgumentException when given item weight isn't float value
	 */
	public function setItemWeight($itemWeight)
	{
		if ($itemWeight !== NULL && !is_numeric($itemWeight)) {
			throw new InvalidArgumentException("Item weight must be float value.");
		}
		$this->itemWeight = $itemWeight;
		return $this;
	}

	/**
	 * @return float|NULL
	 */
	public function getDiversity()
	{
		return $this->diversity;
	}

	/**
	 * @param float|NULL $diversity
	 * @return $this
	 * @throws InvalidArgumentException when given diversity isn't float value
	 * @throws InvalidArgumentException when given diversity is negative value
	 */
	public function setDiversity($diversity)
	{
		if ($diversity !== NULL && !is_numeric($diversity)) {
			throw new InvalidArgumentException("Diversity must be float value.");
		}
		if ($diversity < 0) {
			throw new InvalidArgumentException("Diversity can't be negative value.");
		}
		$this->diversity = $diversity;
		return $this;
	}

}
