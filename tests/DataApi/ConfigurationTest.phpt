<?php

namespace DataBreakers\DataApi;

use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';


class ConfigurationTest extends TestCase
{

	const HOST = 'http://foo.host.com';
	const SLUG = '/v1';
	const ACCOUNT_ID = 'FooAccount';
	const SECRET_KEY = '1234';

	/** @var Configuration */
	private $configuration;


	protected function setUp()
	{
		parent::setUp();
		$this->configuration = new Configuration(self::HOST, self::SLUG, self::ACCOUNT_ID, self::SECRET_KEY);
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

}

(new ConfigurationTest())->run();
