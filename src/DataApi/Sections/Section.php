<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Api;
use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\Restriction;


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

}
