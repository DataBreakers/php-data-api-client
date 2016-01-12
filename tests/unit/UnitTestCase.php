<?php

namespace DataBreakers;

use Tester\TestCase;


abstract class UnitTestCase extends TestCase
{
	
	/**
	 * @inheritdoc
	 */
	protected function tearDown()
	{
		\Mockery::close();
	}

}
