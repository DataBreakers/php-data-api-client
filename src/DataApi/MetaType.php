<?php

namespace DataBreakers\DataApi;


abstract class MetaType
{

	const CATEGORIES = 'categories';
	const PRICE = 'price';
	const TITLE = 'title';


	/**
	 * @param string $metaType
	 * @return bool
	 */
	public static function isValidMetaType($metaType)
	{
		$validMetaTypes = [
			self::CATEGORIES,
			self::PRICE,
			self::TITLE
		];
		return in_array($metaType, $validMetaTypes);
	}

}
