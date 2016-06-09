<?php

namespace DataBreakers\DataApi;


class Configuration implements ConfigurationInterface
{

	const DEFAULT_HOST = 'https://api.databreakers.com';
	const DEFAULT_SLUG = '/v1';

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
	 * @param string $host
	 * @param string $slug
	 * @param string $accountId
	 * @param string $secretKey
	 */
	public function __construct($host, $slug, $accountId, $secretKey)
	{
		$this->host = $host;
		$this->slug = $slug;
		$this->accountId = $accountId;
		$this->secretKey = $secretKey;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param string $host
	 * @return $this
	 */
	public function setHost($host)
	{
		$this->host = $host;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param string $slug
	 * @return $this
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
		return $this;
	}

	/**
	 * @return string
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
	 * @return string
	 */
	public function getSecretKey()
	{
		return $this->secretKey;
	}

	/**
	 * @param string $secretKey
	 * @return $this
	 */
	public function setSecretKey($secretKey)
	{
		$this->secretKey = $secretKey;
		return $this;
	}

}
