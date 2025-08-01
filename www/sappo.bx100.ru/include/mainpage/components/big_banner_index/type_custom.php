<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"aspro:com.banners.max", 
	"top_big_banner_3_2_new",
	array(
		"IBLOCK_TYPE" => "aspro_max_adv",
		"IBLOCK_ID" => "43",
		"TYPE_BANNERS_IBLOCK_ID" => "17",
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"NEWS_COUNT" => "10",
		"NEWS_COUNT2" => "3",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "TEXT_POSITION",
			1 => "TARGETS",
			2 => "TEXTCOLOR",
			3 => "URL_STRING",
			4 => "BUTTON1TEXT",
			5 => "BUTTON1LINK",
			6 => "BUTTON2TEXT",
			7 => "BUTTON2LINK",
			8 => "ONLY_SALE",
		),
		"CHECK_DATES" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_GROUPS" => "N",
		"WIDE_BANNER" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"BANNER_TYPE_THEME" => "TOP",
		"COMPONENT_TEMPLATE" => "top_big_banner_3_2_new",
		"FILTER_NAME" => "arRegionLink",
		"BANNER_TYPE_THEME_CHILD" => "",
		"SECTION_ID" => "",
		"PRICE_CODE" => array(
		),
		"STORES" => array(
			0 => "",
			1 => "",
		),
		"CONVERT_CURRENCY" => "N"
	),
	false
);?>