<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Api;
use DataBreakers\DataApi\Utils\Restriction;
use DataBreakers\UnitTestCase;
use Mockery\MockInterface;


abstract class SectionTest extends UnitTestCase
{

	/** @var Api|MockInterface */
	protected $api;


	protected function setUp()
	{
		parent::setUp();
		$this->api = \Mockery::mock('DataBreakers\DataApi\Api');
	}

	/**
	 * @param string $path
	 * @param array $parameters
	 * @return void
	 */
	protected function mockPerformGet($path, array $parameters = [])
	{
		$this->mockPerformCall('performGet', $path, $parameters);
	}

	/**
	 * @param string $path
	 * @param array $parameters
	 * @param array $content
	 * @return void
	 */
	protected function mockPerformPost($path, array $parameters = [], array $content = [])
	{
		$this->mockPerformCall('performPost', $path, $parameters, $content);
	}

	/**
	 * @param string $path
	 * @param array $parameters
	 * @return void
	 */
	protected function mockPerformDelete($path, array $parameters = [])
	{
		$this->mockPerformCall('performDelete', $path, $parameters);
	}

	/**
	 * @param string $methodName
	 * @param string $path
	 * @param array $parameters
	 * @param array $content
	 * @return void
	 */
	private function mockPerformCall($methodName, $path, array $parameters = [], array $content = [])
	{
		$expectedRestrictions = $parameters === [] && $content === []
			? NULL
			: new Restriction($parameters, $content);
		$this->api->shouldReceive($methodName)
			->with($path, equalTo($expectedRestrictions))
			->once();
	}

}
