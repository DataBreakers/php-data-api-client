<?php

namespace DataBreakers\DataApi;

use DataBreakers\UnitTestCase;
use GuzzleHttp\Client as GuzzleClient;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';


class RequestFactoryTest extends UnitTestCase
{

	/** @var RequestFactory */
	private $factory;


	protected function setUp()
	{
		parent::setUp();
		$client = \Mockery::mock('GuzzleHttp\Client'); /** @var GuzzleClient $client */
		$this->factory = new RequestFactory($client);
	}

	public function testItCreatesInstanceOfRequest()
	{
		$request = $this->factory->create();
		Assert::true($request instanceof Request);
	}

}

(new RequestFactoryTest())->run();
