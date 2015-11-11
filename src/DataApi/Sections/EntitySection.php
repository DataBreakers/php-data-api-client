<?php

namespace DataBreakers\DataApi\Sections;


abstract class EntitySection extends Section
{

	const PERMANENTLY_PARAMETER = 'permanently';
	const WITH_INTERACTIONS_PARAMETER = 'withInteractions';
	const INTERACTIONS_LIMIT_PARAMETER = 'interactionsLimit';
	const INTERACTIONS_OFFSET_PARAMETER = 'interactionsOffset';
	const IDS_PARAMETER = 'ids';
	const LIMIT_PARAMETER = 'limit';
	const OFFSET_PARAMETER = 'offset';
	const ATTRIBUTES_PARAMETER = 'attributes';
	const ORDER_BY_PARAMETER = 'orderBy';
	const ORDER_PARAMETER = 'order';
	const SEARCH_QUERY_PARAMETER = 'searchQuery';
	const SEARCH_ATTRIBUTES_PARAMETER = 'searchAttributes';
	const ENTITIES_PARAMETER = 'entities';
	const DISABLE_CHECKS_PARAMETER = 'disableChecks';
	const ID_PARAMETER = 'id';

}
