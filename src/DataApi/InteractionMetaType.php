<?php

namespace DataBreakers\DataApi;


abstract class InteractionMetaType
{

	const CONVERSION = 'conversion';
	const ACTION = 'action';


	/**
	 * @param string $metaType
	 * @return bool
	 */
	public static function isValidInteractionMetaType($metaType)
	{
		$validMetaTypes = [self::CONVERSION, self::ACTION];
		return in_array($metaType, $validMetaTypes);
	}

}
