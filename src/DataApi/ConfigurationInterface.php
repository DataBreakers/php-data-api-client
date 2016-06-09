<?php

namespace DataBreakers\DataApi;

/**
 * Defines client configuration
 *
 * @package DataBreakers\DataApi
 */
interface ConfigurationInterface
{
	/**
	 * @return string
	 */
	public function getHost();

	/**
	 * @param string $host
	 * @return $this
	 */
	public function setHost($host);

	/**
	 * @return string
	 */
	public function getSlug();

	/**
	 * @return string
	 */
	public function getAccountId();

	/**
	 * @return string
	 */
	public function getSecretKey();
}
