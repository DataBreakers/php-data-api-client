<?php

namespace DataBreakers\DataApi;

use DataBreakers\UnitTestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class ConfigurationTest extends UnitTestCase
{

	const HOST = 'http://foo.host.com';
	const SLUG = '/v1';
	const ACCOUNT_ID = 'FooAccount';
	const SECRET_KEY = '1234';
	const REQUEST_TIMEOUT = 10;

	/** @var Configuration */
	private $configuration;


	protected function setUp()
	{
		parent::setUp();
		$this->configuration = new Configuration(
			self::HOST,
			self::SLUG,
			self::ACCOUNT_ID,
			self::SECRET_KEY,
			self::REQUEST_TIMEOUT
		);
	}

	public function testGettingHost()
	{
		Assert::same(self::HOST, $this->configuration->getHost());
	}

	public function testSettingHost()
	{
		$host = 'http://bar.baz.com';
		$this->configuration->setHost($host);
		Assert::same($host, $this->configuration->getHost());
	}

	public function testGettingSlug()
	{
		Assert::same(self::SLUG, $this->configuration->getSlug());
	}

	public function testSettingSlug()
	{
		$slug = '/v2';
		$this->configuration->setSlug($slug);
		Assert::same($slug, $this->configuration->getSlug());
	}

	public function testGettingAccountId()
	{
		Assert::same(self::ACCOUNT_ID, $this->configuration->getAccountId());
	}

	public function testSettingAccountId()
	{
		$accountId = 'BarAccount';
		$this->configuration->setAccountId($accountId);
		Assert::same($accountId, $this->configuration->getAccountId());
	}

	public function testGettingSecretKey()
	{
		Assert::same(self::SECRET_KEY, $this->configuration->getSecretKey());
	}

	public function testSettingSecretKey()
	{
		$secretKey = '4321';
		$this->configuration->setSecretKey($secretKey);
		Assert::same($secretKey, $this->configuration->getSecretKey());
	}

	public function testGettingRequestTimeout()
	{
		Assert::same(self::REQUEST_TIMEOUT, $this->configuration->getRequestTimeout());
	}

	public function testSettingRequestTimeout()
	{
		$requestTimeout = 150;
		$this->configuration->setRequestTimeout($requestTimeout);
		Assert::same($requestTimeout, $this->configuration->getRequestTimeout());
	}

}

(new ConfigurationTest())->run();
