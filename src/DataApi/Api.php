<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\HmacSignature;
use DataBreakers\DataApi\Utils\PathBuilder;
use DataBreakers\DataApi\Utils\Restriction;
use GuzzleHttp\Client as GuzzleClient;


class Api
{

	/** @var Configuration */
	private $configuration;

	/** @var GuzzleClient */
	private $client;

	/** @var PathBuilder */
	private $pathBuilder;

	/** @var HmacSignature */
	private $hmacSignature;


	/**
	 * @param Configuration $configuration
	 */
	public function __construct(Configuration $configuration)
	{
		$this->configuration = $configuration;
		$this->pathBuilder = new PathBuilder();
		$this->hmacSignature = new HmacSignature($this->configuration->getSecretKey());
		$this->client = new GuzzleClient(['verify' => false]);
	}

	/**
	 * @param string $pathTemplate
	 * @param Restriction|NULL $restriction
	 * @return array|NULL
	 * @throws RequestFailedException when request failed for some reason
	 * @throws InvalidArgumentException when some mandatory parameter is missing in restriction
	 */
	public function performGet($pathTemplate, Restriction $restriction = NULL)
	{
		$url = $this->constructUrl($pathTemplate, $restriction);
		return $this->createRequest()->performGet($url);
	}

	/**
	 * @param string $pathTemplate
	 * @param Restriction|NULL $restriction
	 * @return array|NULL
	 * @throws RequestFailedException when request failed for some reason
	 * @throws InvalidArgumentException when some mandatory parameter is missing in restriction
	 */
	public function performPost($pathTemplate, Restriction $restriction = NULL)
	{
		$url = $this->constructUrl($pathTemplate, $restriction);
		return $this->createRequest()->performPost($url, $restriction->getContents());
	}

	/**
	 * @param string $pathTemplate
	 * @param Restriction|NULL $restriction
	 * @return array|NULL
	 * @throws RequestFailedException when request failed for some reason
	 * @throws InvalidArgumentException when some mandatory parameter is missing in restriction
	 */
	public function performDelete($pathTemplate, Restriction $restriction = NULL)
	{
		$url = $this->constructUrl($pathTemplate, $restriction);
		return $this->createRequest()->performDelete($url);
	}

	/**
	 * @param string $host
	 * @return $this
	 */
	public function changeHost($host)
	{
		$this->configuration->setHost($host);
		return $this;
	}

	/**
	 * @param string $slug
	 * @return $this
	 */
	public function changeSlug($slug)
	{
		$this->configuration->setSlug($slug);
		return $this;
	}

	/**
	 * @return Request
	 */
	private function createRequest()
	{
		return new Request($this->client);
	}

	/**
	 * @param $pathTemplate
	 * @param Restriction|NULL $restriction
	 * @return string
	 */
	private function constructUrl($pathTemplate, Restriction $restriction = NULL)
	{
		$restriction = $restriction ?: new Restriction();
		$restriction->addParameter('accountId', $this->configuration->getAccountId());
		$path = $this->configuration->getSlug() . $this->pathBuilder->build($pathTemplate, $restriction);
		return $this->configuration->getHost() . $this->hmacSignature->sign($path);
	}

}
