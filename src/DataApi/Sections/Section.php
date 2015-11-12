<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Api;
use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\Restriction;
use DataBreakers\DataApi\Utils\Validator;


abstract class Section
{

	/** @var Api */
	private $api;


	/**
	 * @param Api $api
	 */
	public function __construct(Api $api)
	{
		$this->api = $api;
	}

	/**
	 * @param string $pathTemplate
	 * @param Restriction|NULL $restriction
	 * @return array|NULL
	 * @throws RequestFailedException when request failed for some reason
	 * @throws InvalidArgumentException when some mandatory parameter is missing in restriction
	 */
	protected function performGet($pathTemplate, Restriction $restriction = NULL)
	{
		return $this->api->performGet($pathTemplate, $restriction);
	}

	/**
	 * @param string $pathTemplate
	 * @param Restriction|NULL $restriction
	 * @return array|NULL
	 * @throws RequestFailedException when request failed for some reason
	 * @throws InvalidArgumentException when some mandatory parameter is missing in restriction
	 */
	protected function performPost($pathTemplate, Restriction $restriction = NULL)
	{
		return $this->api->performPost($pathTemplate, $restriction);
	}

	/**
	 * @param string $pathTemplate
	 * @param Restriction|NULL $restriction
	 * @return array|NULL
	 * @throws RequestFailedException when request failed for some reason
	 * @throws InvalidArgumentException when some mandatory parameter is missing in restriction
	 */
	protected function performDelete($pathTemplate, Restriction $restriction = NULL)
	{
		return $this->api->performDelete($pathTemplate, $restriction);
	}

	/**
	 * @param Restriction $restriction
	 * @param string $name
	 * @param mixed|NULL $value
	 * @return void
	 */
	protected function addParameterIfNotNull(Restriction $restriction, $name, $value)
	{
		if ($value !== NULL) {
			$restriction->addParameter($name, $value);
		}
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 * @throws InvalidArgumentException when given limit isn't number or is negative
	 * @throws InvalidArgumentException when given offset isn't number or is negative
	 */
	protected function validateLimitAndOffset($limit, $offset)
	{
		if (!Validator::isPositiveNumberOrZero($limit)) {
			throw new InvalidArgumentException("Limit must be positive numeric value or zero.");
		}
		if (!Validator::isPositiveNumberOrZero($offset)) {
			throw new InvalidArgumentException("Offset must be positive numeric value or zero.");
		}
	}

	/**
	 * @param array $content
	 * @param string $name
	 * @param mixed|NULL $value
	 * @return array
	 */
	protected function setContentIfNotNull(array $content, $name, $value)
	{
		if ($value !== NULL) {
			$content[$name] = $value;
		}
		return $content;
	}

}
