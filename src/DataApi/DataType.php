<?php

namespace DataBreakers\DataApi;


abstract class DataType
{

	const TEXT = 'TEXT';
	const INTEGER = 'INTEGER';
	const FLOAT = 'FLOAT';
	const BOOLEAN = 'BOOLEAN';
	const JSON = 'JSON';


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
