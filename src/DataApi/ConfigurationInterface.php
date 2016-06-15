<?php

namespace DataBreakers\DataApi;

/**
 * Defines client configuration
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
	 * @param string $slug
	 * @return $this
	 */
	public function setSlug($slug);

	/**
	 * @return string
	 */
	public function getAccountId();

	/**
	 * @return string
	 */
	public function getSecretKey();

	/**
	 * @return int
	 */
	public function getRequestTimeout();

}
