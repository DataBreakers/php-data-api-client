<?php

namespace DataBreakers;


abstract class TestCase extends \Tester\TestCase
{
	
	/**
	 * @inheritdoc
	 */
	protected function tearDown()
	{
		\Mockery::close();
	}

}
