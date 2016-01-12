<?php

namespace DataBreakers\DataApi\Utils;

use DataBreakers\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


class PathBuilderTest extends TestCase
{

	const DUMMY_DOMAIN = 'http://www.foo.com';
	const PATH_TEMPLATE = '/foo/{bar}/baz/{id}{?offset,limit}';

	/** @var PathBuilder */
	private $pathBuilder;

	/** @var Restriction */
	private $restriction;

	/** @var array */
	private $testParameters = [
		'bar' => 'data',
		'id' => 101,
		'offset' => 10,
		'limit' => 25
	];


	protected function setUp()
	{
		$this->pathBuilder = new PathBuilder();
		$this->restriction = new Restriction($this->testParameters);
	}

	public function testBuildingValidUrl()
	{
		$url = $this->pathBuilder->build(self::PATH_TEMPLATE, $this->restriction);
		$url = self::DUMMY_DOMAIN . $url;
		Assert::notSame(false, filter_var($url, FILTER_VALIDATE_URL), "Invalid url from url builder");
	}

	public function testReplacingParameters()
	{
		$url = $this->pathBuilder->build(self::PATH_TEMPLATE, $this->restriction);
		Assert::same('/foo/data/baz/101?offset=10&limit=25', $url);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenSomeMandatoryParameterIsMissing()
	{
		$parameters = $this->testParameters;
		unset($parameters['id']);
		$this->restriction->setParameters($parameters);
		$this->pathBuilder->build(self::PATH_TEMPLATE, $this->restriction);
	}

	public function testItEncodesQueryParameter()
	{
		$value = 'foo bar&baz';
		$this->restriction->addParameter('offset', $value);
		$url = $this->pathBuilder->build(self::PATH_TEMPLATE, $this->restriction);
		Assert::contains(rawurlencode($value), $url);
	}

}

(new PathBuilderTest())->run();
