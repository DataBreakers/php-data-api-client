<?php

namespace DataBreakers\DataApi\Utils;


class HmacSignature
{

	const HMAC_TIMESTAMP_QUERY = 'hmac_timestamp';
	const HMAC_SIGN_QUERY = 'hmac_sign';

	/**
	 * Secret key used for authentication
	 * @var string
	 */
	private $secretKey;


	/**
	 * @param string $secretKey
	 */
	public function __construct($secretKey)
	{
		$this->secretKey = $secretKey;
	}

	/**
	 * Signs given path with hmac hashing
	 *
	 * @param string $path
	 * @return string
	 */
	public function sign($path)
	{
		$delimiter = strpos($path, '?') === false ? '?' : '&';
		$path .= $delimiter . self::HMAC_TIMESTAMP_QUERY . '=' . time();
		$sign = hash_hmac('sha1', $path, $this->secretKey);
		$path .= '&' . self::HMAC_SIGN_QUERY . '=' . $sign;
		return $path;
	}

}
