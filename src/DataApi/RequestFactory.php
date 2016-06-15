<?php

namespace DataBreakers\DataApi;

use GuzzleHttp\Client as GuzzleClient;


class RequestFactory
{

	/** @var GuzzleClient */
	private $client;

	/** @var array */
	private $requestOptions = [];


	/**
	 * @param GuzzleClient $client
	 * @param array $requestOptions array of GuzzleClient request options
	 */
	public function __construct(GuzzleClient $client, array $requestOptions = [])
	{
		$this->client = $client;
		$this->requestOptions = $requestOptions;
	}

	/**
	 * @return Request
	 */
	public function create()
	{
		return new Request($this->client, $this->requestOptions);
	}

}
