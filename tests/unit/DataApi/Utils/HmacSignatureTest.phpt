<?php

namespace DataBreakers\DataApi\Utils;

use DataBreakers\UnitTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


class HmacSignatureTest extends UnitTestCase
{

	const SECRET_KEY = 'm8PxIXmhi2';
	const NO_QUERY_PATH = '/foo/bar/baz';
	const QUERY_PATH = '/foo/bar/baz?foo=bar';

	/** @var HmacSignature */
	private $hmacSignature;


	protected function setUp()
	{
		parent::setUp();
		$this->hmacSignature = new HmacSignature(self::SECRET_KEY);
	}

	public function testPathContainsHmacTimestampAfterSigning()
	{
		$path = $this->hmacSignature->sign(self::NO_QUERY_PATH);
		Assert::contains(HmacSignature::HMAC_TIMESTAMP_QUERY, $path);
	}

	public function testPathContainsHmacSignAfterSigning()
	{
		$path = $this->hmacSignature->sign(self::NO_QUERY_PATH);
		Assert::contains(HmacSignature::HMAC_SIGN_QUERY, $path);
	}

	public function testAppendingQuestionMarkWhenPathContainNoQueryYet()
	{
		$path = $this->hmacSignature->sign(self::NO_QUERY_PATH);
		Assert::contains('?' . HmacSignature::HMAC_TIMESTAMP_QUERY, $path);
	}

	public function testAppendingAndWhenPathContainsSomeQueryAlready()
	{
		$path = $this->hmacSignature->sign(self::QUERY_PATH);
		Assert::contains('&' . HmacSignature::HMAC_TIMESTAMP_QUERY, $path);
	}

}

(new HmacSignatureTest())->run();
