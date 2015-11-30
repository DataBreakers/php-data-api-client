<?php
/**
 * Created by PhpStorm.
 * User: peta
 * Date: 27.11.15
 * Time: 11:35
 */

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Utils\Restriction;

class UsersSection extends EntitySection
{
	const MERGE_USERS_URL = '/{accountId}/user/merge';

	const SOURCE_USERS_PARAMETER = 'sourceUserIds';
	const TARGET_USER_PARAMETER = 'targetUserId';

	/**
	 * Merges interactions from one (or more) user(s) to another
	 * @param array $sourceUsers
	 * @param       $targetUser
	 *
	 * @return array|NULL
	 */
	public function mergeUser(array $sourceUsers, $targetUser)
	{
		$content = [
			self::SOURCE_USERS_PARAMETER => $sourceUsers,
			self::TARGET_USER_PARAMETER => $targetUser,
		];

		$restriction = new Restriction([], $content);

		return $this->performPost(self::MERGE_USERS_URL, $restriction);
	}
}
