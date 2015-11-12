<?php

namespace DataBreakers\DataApi\Batch;

use Iterator;


abstract class Batch implements Iterator
{

	/** @var int */
	private $iteratorPosition = 0;


	/**
	 * @inheritdoc
	 */
	public function current()
	{
		return $this->getBatchArray()[$this->iteratorPosition];
	}

	/**
	 * @inheritdoc
	 */
	public function key()
	{
		return $this->iteratorPosition;
	}

	/**
	 * @inheritdoc
	 */
	public function next()
	{
		$this->iteratorPosition++;
	}

	/**
	 * @inheritdoc
	 */
	public function rewind()
	{
		$this->iteratorPosition = 0;
	}

	/**
	 * @inheritdoc
	 */
	public function valid()
	{
		return isset($this->getBatchArray()[$this->iteratorPosition]);
	}

	/**
	 * @return array
	 */
	abstract protected function getBatchArray();

}
