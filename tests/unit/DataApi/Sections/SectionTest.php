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
	 * @param array|NULL $parameters
	 * @return void
	 */
	protected function mockPerformGet($path, array $parameters = NULL)
	{
		$this->mockPerformCall('performGet', $path, $parameters);
	}

	/**
	 * @param string $path
	 * @param array|NULL $parameters
	 * @param array|NULL $content
	 * @return void
	 */
	protected function mockPerformPost($path, array $parameters = NULL, array $content = NULL)
	{
		$this->mockPerformCall('performPost', $path, $parameters, $content);
	}

	/**
	 * @param string $path
	 * @param array|NULL $parameters
	 * @return void
	 */
	protected function mockPerformDelete($path, array $parameters = NULL)
	{
		$this->mockPerformCall('performDelete', $path, $parameters);
	}

	/**
	 * @param string $methodName
	 * @param string $path
	 * @param array|NULL $parameters
	 * @param array|NULL $content
	 * @return void
	 */
	private function mockPerformCall($methodName, $path, array $parameters = NULL, array $content = NULL)
	{
		$expectedRestrictions = $this->getExpectedRestrictions($parameters, $content);
		$this->api->shouldReceive($methodName)
			->with($path, equalTo($expectedRestrictions))
			->once();
	}

	/**
	 * @param array|NULL $parameters
	 * @param array|NULL $content
	 * @return array
	 */
	private function getExpectedRestrictions(array $parameters = NULL, array $content = NULL)
	{
		if ($parameters === NULL && $content === NULL) {
			return NULL;
		}
		return new Restriction((array) $parameters, (array) $content);
	}

}
