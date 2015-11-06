<?php

namespace DataBreakers\DataApi\Utils;


class Restriction
{

	/** @var array */
	private $parameters = [];

	/** @var array */
	private $contents = [];


	/**
	 * @param array $parameters
	 * @param array $contents
	 */
	public function __construct(array $parameters = [], array $contents = [])
	{
		$this->parameters = $parameters;
		$this->contents = $contents;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function addParameter($name, $value)
	{
		$this->parameters[$name] = (string) $value;
		return $this;
	}

	/**
	 * @param array $parameters
	 * @return $this
	 */
	public function setParameters(array $parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * @param string $name
	 * @return string|NULL
	 */
	public function getParameter($name)
	{
		return isset($this->parameters[$name]) ? (string) $this->parameters[$name] : NULL;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function addContent($name, $value)
	{
		$this->contents[$name] = $value;
		return $this;
	}

	/**
	 * @param array $contents
	 * @return $this
	 */
	public function setContents(array $contents)
	{
		$this->contents = $contents;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getContents()
	{
		return $this->contents;
	}

}
