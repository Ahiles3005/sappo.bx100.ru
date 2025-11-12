<?if('Y' == $arParams['USE_FILTER']):?>
	<?
	if($arTheme["FILTER_VIEW"]["VALUE"] == 'COMPACT'){
		if($arParams["AJAX_FILTER_CATALOG"]=="Y"){
			$template_filter = 'main_compact_ajax';
		}
		else{
			$template_filter = 'main_compact';
		}
	}
	elseif($arParams["AJAX_FILTER_CATALOG"]=="Y"){
		$template_filter = 'main_ajax';
	}
	else{
		$template_filter = 'main';
	}
	?>

	<?
	$GLOBALS['saleFilter'] = array("PROPERTY_HIT"=>$arParams['FILTER_HIT_ID']?:'7405');
	$TOP_VERTICAL_FILTER_PANEL = 'Y';
	//$TOP_VERTICAL_FILTER_PANEL = $arTheme["LEFT_BLOCK_CATALOG_SECTIONS"]['VALUE'] == 'Y' ? $arTheme["FILTER_VIEW"]['DEPENDENT_PARAMS']['TOP_VERTICAL_FILTER_PANEL']['VALUE'] : 'N';
	/*$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		$template_filter,
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"AJAX_FILTER_FLAG" => ( isset($isAjaxFilter) ? $isAjaxFilter : '' ),
			"SECTION_ID" => 0,
			"SHOW_ALL_WO_SECTION" => "Y",
			"PREFILTER_NAME" => 'saleFilter',
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"PRICE_CODE" => ($arParams["USE_FILTER_PRICE"] == 'Y' ? $arParams["FILTER_PRICE_CODE"] : $arParams["PRICE_CODE"]),
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_NOTES" => "",
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SAVE_IN_SESSION" => "N",
			"XML_EXPORT" => "Y",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			"SHOW_HINTS" => $arParams["SHOW_HINTS"],
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID' => $arParams['CURRENCY_ID'],
			'DISPLAY_ELEMENT_COUNT' => $arParams['DISPLAY_ELEMENT_COUNT'],
			"INSTANT_RELOAD" => "Y",
			"VIEW_MODE" => strtolower($arTheme["FILTER_VIEW"]["VALUE"]),
			"SEF_MODE" => "Y",
			"SEF_RULE" => '/sale-new/filter/#SMART_FILTER_PATH#/apply/',
			"SMART_FILTER_PATH" => $_REQUEST["SMART_FILTER_PATH"],
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"SEF_RULE_FILTER" => '/filter/#SMART_FILTER_PATH#/apply/',
			"SORT_BUTTONS" => $arParams["SORT_BUTTONS"],
			"SORT_PRICES" => $arParams["SORT_PRICES"],
			"AVAILABLE_SORT" => $arAvailableSort,
			"SORT" => $sort,
			"SORT_ORDER" => $sort_order,
			"TOP_VERTICAL_FILTER_PANEL" => $TOP_VERTICAL_FILTER_PANEL,
			"SHOW_SORT" => ($arParams['SHOW_SORT_IN_FILTER'] != 'N'),
		),
		$component);
	*/?>
	<?php
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		/*$template_filter*/'main',
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"AJAX_FILTER_FLAG" => /*( isset($isAjaxFilter) ? $isAjaxFilter : '' )*/$isAjaxFilter,
			"SECTION_ID" => 0,
			"PREFILTER_NAME" => 'saleFilter',
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"PRICE_CODE" => ($arParams["USE_FILTER_PRICE"] == 'Y' ? $arParams["FILTER_PRICE_CODE"] : $arParams["PRICE_CODE"]),
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_NOTES" => "",
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SAVE_IN_SESSION" => "N",
			"XML_EXPORT" => "Y",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			"SHOW_HINTS" => $arParams["SHOW_HINTS"],
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID' => $arParams['CURRENCY_ID'],
			'DISPLAY_ELEMENT_COUNT' => $arParams['DISPLAY_ELEMENT_COUNT'],
			"INSTANT_RELOAD" => "Y",
			"VIEW_MODE" => strtolower($arTheme["FILTER_VIEW"]["VALUE"]),
			"SEF_MODE" => "Y",
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"SEF_RULE_FILTER" => '/filter/#SMART_FILTER_PATH#/apply/',
			"SORT_BUTTONS" => $arParams["SORT_BUTTONS"],
			"SORT_PRICES" => $arParams["SORT_PRICES"],
			"AVAILABLE_SORT" => $arAvailableSort,
			"SORT" => $sort,
			"SORT_ORDER" => 'asc',
			"TOP_VERTICAL_FILTER_PANEL" => $TOP_VERTICAL_FILTER_PANEL,
			"SHOW_SORT" => ($arParams['SHOW_SORT_IN_FILTER'] != 'N'),

			//ключевые места 1
			"SEF_RULE" => $arParams["SEF_FOLDER"].'filter/#SMART_FILTER_PATH#/apply/',
			"SMART_FILTER_PATH" => $_REQUEST["SMART_FILTER_PATH"],
			'SHOW_ALL_WO_SECTION'=>'Y',
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
	?>
<?endif;?>