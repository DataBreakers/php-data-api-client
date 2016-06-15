<?php

namespace DataBreakers\DataApi;


class Configuration implements ConfigurationInterface
{

	const DEFAULT_HOST = 'https://api.databreakers.com';
	const DEFAULT_SLUG = '/v1';
	const DEFAULT_REQUEST_TIMEOUT = NULL;

	/**
	 * Beginning part of API url
	 * @var string
	 */
	private $host;

	/**
	 * String determining version of API
	 * @var string
	 */
	private $slug;

	/**
	 * Unique identifier of account
	 * @var string
	 */
	private $accountId;

	/**
	 * Key used for hmac signature
	 * @var string
	 */
	private $secretKey;

	/**
	 * Timeout for http request [s]
	 * @var int
	 */
	protected $requestTimeout;


	/**
	 * @param string $host
	 * @param string $slug
	 * @param string $accountId
	 * @param string $secretKey
	 * @param int $requestTimeout
	 */
	public function __construct($host, $slug, $accountId, $secretKey, $requestTimeout = self::DEFAULT_REQUEST_TIMEOUT)
	{
		$this->host = $host;
		$this->slug = $slug;
		$this->accountId = $accountId;
		$this->secretKey = $secretKey;
		$this->requestTimeout = $requestTimeout;
	}

	/**
	 * @inheritdoc
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @inheritdoc
	 */
	public function setHost($host)
	{
		$this->host = $host;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @inheritdoc
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getAccountId()
	{
		return $this->accountId;
	}

	/**
	 * @param string $accountId
	 * @return $this
	 */
	public function setAccountId($accountId)
	{
		$this->accountId = $accountId;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getSecretKey()
	{
		return $this->secretKey;
	}

	/**
	 * @inheritdoc
	 */
	public function setSecretKey($secretKey)
	{
		$this->secretKey = $secretKey;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getRequestTimeout()
	{
		return $this->requestTimeout;
	}

	/**
	 * @param int $requestTimeout
	 * @return $this
	 */
	public function setRequestTimeout($requestTimeout)
	{
		$this->requestTimeout = $requestTimeout;
		return $this;
	}

}
