<?php

namespace DataBreakers\DataApi\Utils;


class Validator
{

	/**
	 * @param mixed $number
	 * @return bool
	 */
	public static function isPositiveNumberOrZero($number)
	{
		return is_numeric($number) && $number >= 0;
	}

}
