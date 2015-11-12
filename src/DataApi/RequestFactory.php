<?php

namespace DataBreakers\DataApi;

use GuzzleHttp\Client as GuzzleClient;


class RequestFactory
{

	/** @var GuzzleClient */
	private $client;


	/**
	 * @param GuzzleClient $client
	 */
	public function __construct(GuzzleClient $client)
	{
		$this->client = $client;
	}

	/**
	 * @return Request
	 */
	public function create()
	{
		return new Request($this->client);
	}

}
