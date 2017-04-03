<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\Restriction;


class AbTestsSection extends Section
{

	const GET_USER_AB_TEST_GROUP_URL = '/{accountId}/users/{userId}/abtests/{abtestId}/group';

	const USER_ID_PARAMETER = 'userId';
	const AB_TEST_ID_PARAMETER = 'abtestId';
	
	
	/**
	 * @param string $userId
	 * @param string $abTestId
	 * @return array
	 * @throws InvalidArgumentException when given user id is empty string value
	 * @throws InvalidArgumentException when given ab test id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getUserAbTestGroup($userId, $abTestId)
	{
		if ($userId == '') {
			throw new InvalidArgumentException("User id can't be empty string.");
		}
		if ($abTestId == '') {
			throw new InvalidArgumentException("AB test id can't be empty string.");
		}
		$restrictions = new Restriction([
			self::USER_ID_PARAMETER => $userId,
			self::AB_TEST_ID_PARAMETER => $abTestId,
		]);
		return $this->performGet(self::GET_USER_AB_TEST_GROUP_URL, $restrictions);
	}

}
