<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\HmacSignature;
use DataBreakers\DataApi\Utils\PathBuilder;
use DataBreakers\DataApi\Utils\Restriction;


class Api
{

	/** @var ConfigurationInterface */
	private $configuration;

	/** @var PathBuilder */
	private $pathBuilder;

	/** @var HmacSignature */
	private $hmacSignature;

	/** @var RequestFactory */
	private $requestFactory;


	/**
	 * @param ConfigurationInterface $configuration
	 * @param PathBuilder $pathBuilder
	 * @param HmacSignature $hmacSignature
	 * @param RequestFactory $requestFactory
	 */
	public function __construct(ConfigurationInterface $configuration, PathBuilder $pathBuilder, HmacSignature $hmacSignature,
								RequestFactory $requestFactory)
	{
		$this->configuration = $configuration;
		$this->pathBuilder = $pathBuilder;
		$this->hmacSignature = $hmacSignature;
		$this->requestFactory = $requestFactory;
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
		return $this->requestFactory->create();
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
