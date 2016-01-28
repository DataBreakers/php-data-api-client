<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\Restriction;


class ItemsSection extends EntitySection
{

	const CLEAR_ITEMS_URL = '/{accountId}/items/clear';
	const DELETE_SELECTED_ITEMS_URL = '/{accountId}/items/delete';


	/**
	 * @return NULL
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function clearItems()
	{
		return $this->performDelete(self::CLEAR_ITEMS_URL);
	}

	/**
	 * @param string[] $ids
	 * @param bool $permanently
	 * @return NULL
	 * @throws InvalidArgumentException when given array of ids is empty
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteSelectedItems(array $ids, $permanently = false)
	{
		if (count($ids) === 0) {
			throw new InvalidArgumentException("Ids array can't be empty.");
		}
		$restriction = new Restriction([], [
			self::IDS_PARAMETER => $ids,
			self::PERMANENTLY_PARAMETER => $permanently
		]);
		return $this->performPost(self::DELETE_SELECTED_ITEMS_URL, $restriction);
	}

}
