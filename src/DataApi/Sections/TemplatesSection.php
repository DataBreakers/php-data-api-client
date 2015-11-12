<?php

namespace DataBreakers\DataApi\Sections;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;
use DataBreakers\DataApi\Exceptions\RequestFailedException;
use DataBreakers\DataApi\TemplateConfiguration;
use DataBreakers\DataApi\Utils\Restriction;


class TemplatesSection extends Section
{

	const INSERT_OR_UPDATE_TEMPLATE_URL = '/{accountId}/template';
	const GET_TEMPLATES_URL = '/{accountId}/templates';
	const GET_TEMPLATE_URL = '/{accountId}/templates/{templateId}';
	const DELETE_TEMPLATE_URL = '/{accountId}/templates/{templateId}';

	const TEMPLATE_ID_PARAMETER = 'templateId';
	const FILTER_PARAMETER = 'filter';
	const BOOSTER_PARAMETER = 'booster';
	const USER_WEIGHT_PARAMETER = 'userWeight';
	const ITEM_WEIGHT_PARAMETER = 'itemWeight';
	const DIVERSITY_PARAMETER = 'diversity';


	/**
	 * @param string $templateId
	 * @param TemplateConfiguration $configuration
	 * @return NULL
	 * @throws InvalidArgumentException when given template id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function insertOrUpdateTemplate($templateId, TemplateConfiguration $configuration)
	{
		if ($templateId == '') {
			throw new InvalidArgumentException("Template id can't be empty string.");
		}
		$content = [self::TEMPLATE_ID_PARAMETER => $templateId];
		$content = $this->setContentIfNotNull($content, self::FILTER_PARAMETER, $configuration->getFilter());
		$content = $this->setContentIfNotNull($content, self::BOOSTER_PARAMETER, $configuration->getBooster());
		$content = $this->setContentIfNotNull($content, self::USER_WEIGHT_PARAMETER, $configuration->getUserWeight());
		$content = $this->setContentIfNotNull($content, self::ITEM_WEIGHT_PARAMETER, $configuration->getItemWeight());
		$content = $this->setContentIfNotNull($content, self::DIVERSITY_PARAMETER, $configuration->getDiversity());
		$restriction = new Restriction([], $content);
		return $this->performPost(self::INSERT_OR_UPDATE_TEMPLATE_URL, $restriction);
	}

	/**
	 * @return array
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getTemplates()
	{
		return $this->performGet(self::GET_TEMPLATES_URL);
	}

	/**
	 * @param string $templateId
	 * @return array
	 * @throws InvalidArgumentException when given template id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function getTemplate($templateId)
	{
		if ($templateId == '') {
			throw new InvalidArgumentException("Template id can't be empty string.");
		}
		$restriction = new Restriction([self::TEMPLATE_ID_PARAMETER => $templateId]);
		return $this->performGet(self::GET_TEMPLATE_URL, $restriction);
	}

	/**
	 * @param string $templateId
	 * @return NULL
	 * @throws InvalidArgumentException when given template id is empty string value
	 * @throws RequestFailedException when request failed for some reason
	 */
	public function deleteTemplate($templateId)
	{
		if ($templateId == '') {
			throw new InvalidArgumentException("Template id can't be empty string.");
		}
		$restriction = new Restriction([self::TEMPLATE_ID_PARAMETER => $templateId]);
		return $this->performDelete(self::DELETE_TEMPLATE_URL, $restriction);
	}

}
