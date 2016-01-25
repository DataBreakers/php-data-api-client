<?php

namespace DataBreakers\DataApi\Sections;

require_once __DIR__ . '/../../bootstrap.php';


class UsersSectionTest extends SectionTest
{

	const ID1 = 'user1';
	const ID2 = 'user2';
	const ID3 = 'user3';

	/** @var UsersSection */
	private $usersSection;


	protected function setUp()
	{
		parent::setUp();
		$this->usersSection = new UsersSection($this->api, new UsersSectionStrategy());
	}

	public function testMergingUser()
	{
		$sourceUsers = [self::ID1, self::ID2];
		$targetUser = self::ID3;
		$content = [
			UsersSection::SOURCE_USERS_PARAMETER => $sourceUsers,
			UsersSection::TARGET_USER_PARAMETER => $targetUser
		];
		$this->mockPerformPost(UsersSection::MERGE_USERS_URL, [], $content);
		$this->usersSection->mergeUser($sourceUsers, $targetUser);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenSourceUsersIdsIsEmptyArrayDuringMergingUser()
	{
		$this->usersSection->mergeUser([], self::ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTargetUserIdIsEmptyStringDuringMergingUser()
	{
		$this->usersSection->mergeUser([self::ID1], '');
	}

	public function testCopyingUser()
	{
		$sourceUsers = [self::ID1, self::ID2];
		$targetUser = self::ID3;
		$content = [
			UsersSection::SOURCE_USERS_PARAMETER => $sourceUsers,
			UsersSection::TARGET_USER_PARAMETER => $targetUser
		];
		$this->mockPerformPost(UsersSection::COPY_USER_URL, [], $content);
		$this->usersSection->copyUser($sourceUsers, $targetUser);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenSourceUsersIdsIsEmptyArrayDuringCopyingUser()
	{
		$this->usersSection->copyUser([], self::ID1);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenTargetUserIdIsEmptyStringDuringCopyingUser()
	{
		$this->usersSection->copyUser([self::ID1], '');
	}

}

(new UsersSectionTest())->run();
