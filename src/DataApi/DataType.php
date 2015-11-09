<?php

namespace DataBreakers\DataApi;


abstract class DataType
{

	const TEXT = 'text';
	const INTEGER = 'integer';
	const FLOAT = 'float';
	const BOOLEAN = 'boolean';
	const JSON = 'json';


	/**
	 * @param string $dataType
	 * @return bool
	 */
	public static function isValidDataType($dataType)
	{
		$validDataTypes = [
			self::TEXT,
			self::INTEGER,
			self::FLOAT,
			self::BOOLEAN,
			self::JSON
		];
		return in_array($dataType, $validDataTypes);
	}
}
