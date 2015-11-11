<?php

namespace DataBreakers\DataApi;


abstract class Order
{

	const ASC = 'asc';
	const DESC = 'desc';


	/**
	 * @param string $order
	 * @return bool
	 */
	public static function isValidOrderValue($order)
	{
		$validValues = [self::ASC, self::DESC];
		return in_array($order, $validValues);
	}

}
