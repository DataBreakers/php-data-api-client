<?php

namespace DataBreakers\DataApi;

use DataBreakers\DataApi\Batch\EntitiesBatch;
use DataBreakers\IntegrationTestCase;
use DataBreakers\Seeder;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class UsersTest extends IntegrationTestCase
{

	const USER_NOAH = 'user_noah';
	const USER_EMMA = 'user_emma';


	public function testAddingOneUser()
	{
		$attributes = [
			Seeder::ATTRIBUTE_NAME => 'Noah Noah',
			Seeder::ATTRIBUTE_AGE => 25,
		];
		$this->client->insertOrUpdateUser(self::USER_NOAH, $attributes);
		$this->validateUser($this->client->getUser(self::USER_NOAH), self::USER_NOAH, $attributes);
	}

	public function testAddingMultipleUsers()
	{
		$noahAttributes = [
			Seeder::ATTRIBUTE_NAME => 'Noah Noah',
			Seeder::ATTRIBUTE_AGE => 25,
		];
		$emmaAttributes = [
			Seeder::ATTRIBUTE_NAME => 'Emma Emma',
		];
		$this->client->insertOrUpdateUsers((new EntitiesBatch())
			->addEntity(self::USER_EMMA, $emmaAttributes)
			->addEntity(self::USER_NOAH, $noahAttributes)
		);
		$this->validateUser($this->client->getUser(self::USER_NOAH), self::USER_NOAH, $noahAttributes);
		$this->validateUser($this->client->getUser(self::USER_EMMA), self::USER_EMMA, $emmaAttributes);
	}

	public function testGettingUsers()
	{
		$expectedIds = [Seeder::USER_JOHN, Seeder::USER_PAUL, Seeder::USER_SUZIE];
		$users = $this->client->getUsers();
		Assert::same(count($expectedIds), count($users['entities']));
		foreach ($users['entities'] as $user) {
			Assert::true(in_array($user['id'], $expectedIds));
		}
	}

	public function testGettingOneUser()
	{
		$user = $this->client->getUser(Seeder::USER_JOHN);
		Assert::same(Seeder::USER_JOHN, $user['id']);
	}

	public function testGettingSelectedUsers()
	{
		$selectedUsersIds = [Seeder::USER_PAUL, Seeder::USER_SUZIE];
		$users = $this->client->getSelectedUsers($selectedUsersIds);
		Assert::same(count($selectedUsersIds), count($users['entities']));
		foreach ($users['entities'] as $user) {
			Assert::true(in_array($user['id'], $selectedUsersIds));
		}
	}

	public function testSoftDeletingUser()
	{
		$this->client->deleteUser(Seeder::USER_JOHN);
		$user = $this->client->getUser(Seeder::USER_JOHN);
		Assert::true($user['deleted']);
	}

	public function testHardDeletingUser()
	{
		$this->client->deleteUser(Seeder::USER_JOHN, true);
		$users = $this->client->getUsers();
		Assert::same(2, count($users));
		foreach ($users['entities'] as $user) {
			Assert::notSame(Seeder::USER_JOHN, $user['id']);
		}
	}

	public function testMergingUser()
	{
		$this->client->mergeUser([Seeder::USER_JOHN, Seeder::USER_PAUL], self::USER_EMMA);
		$this->validateExistingUsers([Seeder::USER_SUZIE, self::USER_EMMA]);
	}

	public function testCopyingUser()
	{
		$this->client->copyUser([Seeder::USER_JOHN, Seeder::USER_PAUL], self::USER_EMMA);
		$this->validateExistingUsers([Seeder::USER_JOHN, Seeder::USER_PAUL, Seeder::USER_SUZIE, self::USER_EMMA]);
	}

	/**
	 * @param array $user
	 * @param string $id
	 * @param array $attributes
	 * @return void
	 */
	private function validateUser(array $user, $id, array $attributes)
	{
		Assert::same($id, $user['id']);
		foreach ($attributes as $name => $value) {
			Assert::same($value, $user['attributes'][$name]);
		}
	}

	/**
	 * @param array $expectedIds
	 * @return void
	 */
	private function validateExistingUsers(array $expectedIds)
	{
		$users = $this->client->getUsers();
		Assert::same(count($expectedIds), count($users['entities']));
		foreach ($users['entities'] as $user) {
			Assert::true(in_array($user['id'], $expectedIds));
		}
	}

}

(new UsersTest())->run();
