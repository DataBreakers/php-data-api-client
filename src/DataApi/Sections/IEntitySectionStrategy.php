<?php

namespace DataBreakers\DataApi\Sections;


interface IEntitySectionStrategy
{

	/**
	 * @return string
	 */
	public function getInsertOrUpdateEntityUrl();

	/**
	 * @return string
	 */
	public function getGetEntitiesUrl();

	/**
	 * @return string
	 */
	public function getEntityIdParameter();

	/**
	 * @return string
	 */
	public function getGetEntityUrl();

	/**
	 * @return string
	 */
	public function getGetSelectedEntitiesUrl();

	/**
	 * @return string
	 */
	public function getDeleteEntityUrl();

	/**
	 * @return string
	 */
	public function getDeleteEntitiesUrl();

	/**
	 * @return string
	 */
	public function getActivateEntitiesUrl();

}
