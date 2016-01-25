<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\Utils\Restriction;


class UsersSection extends EntitySection
{

	const MERGE_USERS_URL = '/{accountId}/user/merge';
	const COPY_USER_URL = '/{accountId}/user/copy';

	const SOURCE_USERS_PARAMETER = 'sourceUserIds';
	const TARGET_USER_PARAMETER = 'targetUserId';


	/**
	 * @param array $sourceUsersIds
	 * @param string $targetUserId
	 * @return NULL
	 * @throws InvalidArgumentException when given array of source users ids is empty
	 * @throws InvalidArgumentException when given target user id is empty string
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function copyUser(array $sourceUsersIds, $targetUserId)
	{
		$this->validateSourceAndTargetUserIds($sourceUsersIds, $targetUserId);
		$content = [
			self::SOURCE_USERS_PARAMETER => $sourceUsersIds,
			self::TARGET_USER_PARAMETER => $targetUserId,
		];
		$restriction = new Restriction([], $content);
		return $this->performPost(self::COPY_USER_URL, $restriction);
	}

	/**
	 * Merges interactions from one (or more) user(s) to another
	 *
	 * @param array $sourceUsersIds
	 * @param string $targetUserId
	 * @return NULL
	 * @throws InvalidArgumentException when given array of source users ids is empty
	 * @throws InvalidArgumentException when given target user id is empty string
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function mergeUser(array $sourceUsersIds, $targetUserId)
	{
		$this->validateSourceAndTargetUserIds($sourceUsersIds, $targetUserId);
		$content = [
			self::SOURCE_USERS_PARAMETER => $sourceUsersIds,
			self::TARGET_USER_PARAMETER => $targetUserId,
		];
		$restriction = new Restriction([], $content);
		return $this->performPost(self::MERGE_USERS_URL, $restriction);
	}

	/**
	 * @param array $sourceUsersIds
	 * @param string $targetUserId
	 * @return NULL
	 * @throws InvalidArgumentException when given array of source users ids is empty
	 * @throws InvalidArgumentException when given target user id is empty string
	 */
	private function validateSourceAndTargetUserIds(array $sourceUsersIds, $targetUserId)
	{
		if (count($sourceUsersIds) === 0) {
			throw new InvalidArgumentException("Source users ids array can't be empty.");
		}
		if ($targetUserId == '') {
			throw new InvalidArgumentException("Target user id can't be empty string.");
		}
	}

}
