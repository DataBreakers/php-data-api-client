<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Exceptions\RequestFailedException;


class ItemsSection extends EntitySection
{

	const CLEAR_ITEMS_URL = '/{accountId}/items/clear';


	/**
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function clearItems()
	{
		return $this->performDelete(self::CLEAR_ITEMS_URL);
	}

}
