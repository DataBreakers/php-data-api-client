<?php

namespace DataBreakers\DataApi\Utils;

use DataBreakers\DataApi\Exceptions\InvalidArgumentException;


class PathBuilder
{

	/**
	 * Replaces parameters in path template with their values from restriction.
	 *
	 * @param string $pathTemplate
	 * @param Restriction $restriction
	 * @returns string
	 * @throws InvalidArgumentException when some mandatory parameter is missing
	 */
	public function build($pathTemplate, Restriction $restriction)
	{
		$matches = [];
		preg_match_all("/\\{(.*?)\\}/", $pathTemplate, $matches);
		$matchedParameters = end($matches);

		$required = [];
		$parameters = [];
		foreach ($matchedParameters as $parameter) {
			if (substr($parameter, 0, 1) == '?') {
				$parameters = explode(',', str_replace('?', '', $parameter));
			}
			else {
				if (($p = $restriction->getParameter($parameter)) === NULL) {
					$required[] = $parameter;
				} else {
					$pathTemplate = str_replace('{' . $parameter . '}', $p, $pathTemplate);
				}
			}
		}

		if (!empty($required)) {
			$message = 'Empty required parameters: ' . implode(', ', $required);
			throw new InvalidArgumentException($message);
		}

		if (($pos = strpos($pathTemplate, '{?')) !== FALSE) {
			$pathTemplate = substr($pathTemplate, 0, $pos);
			$first = TRUE;
			foreach ($parameters as $qParameter) {
				$p = $restriction->getParameter($qParameter);
				if ($p !== NULL) {
					$pathTemplate .= ($first ? '?' : '&') . $qParameter . '=' . rawurlencode($p);
					$first = FALSE;
				}
			}
		}

		return $pathTemplate;
	}

}
