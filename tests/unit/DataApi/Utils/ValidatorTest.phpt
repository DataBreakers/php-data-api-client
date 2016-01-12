<?php

namespace DataBreakers\DataApi\Utils;

use DataBreakers\UnitTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


class ValidatorTest extends UnitTestCase
{

	/**
	 * @param mixed $value
	 * @param boolean $result
	 * @dataProvider getDataForPositiveNumberOrZeroTest
	 */
	public function testGettingIfGivenValueIsPositiveNumberOrZero($value, $result)
	{
		Assert::same($result, Validator::isPositiveNumberOrZero($value));
	}

	public function getDataForPositiveNumberOrZeroTest()
	{
		return [
			[0, true],
			[1, true],
			[5, true],
			[10, true],
			[1000, true],
			['foo', false],
			[false, false],
			[-1, false],
			[-2, false],
			[-10, false],
			[-1000, false],
		];
	}

}

(new ValidatorTest())->run();
