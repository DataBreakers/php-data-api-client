<?php

namespace DataBreakers;

use DataBreakers\DataApi\Client;
use Tester\TestCase;


abstract class IntegrationTestCase extends TestCase
{

	/** @var Client */
	protected $client;


	/**
	 * @inheritdoc
	 */
	protected function setUp()
	{
		parent::setUp();

		$credentials = require __DIR__ . '/credentials.php';
		$this->client = new Client($credentials['accountId'], $credentials['secretKey']);

		$seeder = new Seeder($this->client);
		$seeder->clear();
		$seeder->seed();
	}

}
