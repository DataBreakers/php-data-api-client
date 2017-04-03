<?php

namespace DataBreakers\DataApi\Sections;

require_once __DIR__ . '/../../bootstrap.php';


class AbTestsSectionTest extends SectionTest
{

	const USER_ID = 'userFoo';
	const AB_TEST_ID = 'mainAbTest';

	/** @var AbTestsSection */
	private $abTestsSection;


	protected function setUp()
	{
		parent::setUp();
		$this->abTestsSection = new AbTestsSection($this->api);
	}

	public function testGettingUserAbTestGroup()
	{
		$parameters = [
			AbTestsSection::USER_ID_PARAMETER => self::USER_ID,
			AbTestsSection::AB_TEST_ID_PARAMETER => self::AB_TEST_ID,
		];
		$this->mockPerformGet(AbTestsSection::GET_USER_AB_TEST_GROUP_URL, $parameters);
		$this->abTestsSection->getUserAbTestGroup(self::USER_ID, self::AB_TEST_ID);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenUserIdIsEmptyDuringGettingUsersAbTestGroup()
	{
		$this->abTestsSection->getUserAbTestGroup('', self::AB_TEST_ID);
	}

	/**
	 * @throws \DataBreakers\DataApi\Exceptions\InvalidArgumentException
	 */
	public function testThrowingExceptionWhenAbTestIdIsEmptyDuringGettingUsersAbTestGroup()
	{
		$this->abTestsSection->getUserAbTestGroup(self::USER_ID, '');
	}

}

(new AbTestsSectionTest())->run();
