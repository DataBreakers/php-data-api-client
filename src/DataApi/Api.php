<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\HmacSignature;
use DataBreakers\DataApi\Utils\PathBuilder;
use DataBreakers\DataApi\Utils\Restriction;
use GuzzleHttp\Client;


class Api
{

	/** @var Configuration */
	private $configuration;

	/** @var Client */
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
		$this->createClient();
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
		$path = $this->constructPath($pathTemplate, $restriction);
		return $this->createRequest()->performGet($path);
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
		$path = $this->constructPath($pathTemplate, $restriction);
		return $this->createRequest()->performPost($path);
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
		$path = $this->constructPath($pathTemplate, $restriction);
		return $this->createRequest()->performDelete($path);
	}

	/**
	 * @param string $host
	 * @return $this
	 */
	public function changeHost($host)
	{
		$this->configuration->setHost($host);
		$this->createClient();
		return $this;
	}

	/**
	 * @param string $slug
	 * @return $this
	 */
	public function changeSlug($slug)
	{
		$this->configuration->setSlug($slug);
		$this->createClient();
		return $this;
	}

	/**
	 * @return void
	 */
	private function createClient()
	{
		$this->client = new Client([
			'base_uri' => $this->configuration->getHost() . $this->configuration->getSlug()
		]);
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
	private function constructPath($pathTemplate, Restriction $restriction = NULL)
	{
		$restriction = $restriction ?: new Restriction();
		$restriction->addParameter('accountId', $this->configuration->getAccountId());
		$path = $this->pathBuilder->build($pathTemplate, $restriction);
		return $this->hmacSignature->sign($path);
	}

}
