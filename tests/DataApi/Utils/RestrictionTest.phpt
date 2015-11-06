<?php

namespace DataBreakers\DataApi\Utils;

use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';


class RestrictionTest extends TestCase
{

	/** @var Restriction */
	private $restriction;


	protected function setUp()
	{
		parent::setUp();
		$this->restriction = new Restriction();
	}

	public function testAddingParameter()
	{
		$this->restriction->addParameter('foo', 'bar');
		Assert::same('bar', $this->restriction->getParameter('foo'));
	}

	public function testSettingParameters()
	{
		$parameters = [
			'foo' => 'bar',
			'baz' => 120
		];
		$this->restriction->setParameters($parameters);
		Assert::same($parameters, $this->restriction->getParameters());
	}

	public function testAddingContent()
	{
		$this->restriction->addContent('foo', 'bar');
		Assert::same(['foo' => 'bar'], $this->restriction->getContents());
	}

	public function testSettingContents()
	{
		$contents = [
			'foo' => 'bar',
			'baz' => 120
		];
		$this->restriction->setContents($contents);
		Assert::same($contents, $this->restriction->getContents());
	}

}

(new RestrictionTest())->run();
