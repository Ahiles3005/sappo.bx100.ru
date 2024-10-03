<?$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"corp",
	Array(
		"CATEGORY_0" => array("iblock_aspro_max_catalog"),
		"CATEGORY_0_TITLE" => "ALL",
		"CATEGORY_0_iblock_1c_catalog" => array("63"),
		"CATEGORY_0_iblock_aspro_max_catalog" => array("42"),
		"CATEGORY_OTHERS_TITLE" => "OTHER",
		"CHECK_DATES" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTAINER_ID" => "title-search_fixed",
		"CONVERT_CURRENCY" => "N",
		"INPUT_ID" => "title-search-input_fixed",
		"NUM_CATEGORIES" => "1",
		"ORDER" => "date",
		"PAGE" => CMax::GetFrontParametrValue("CATALOG_PAGE_URL"),
		"PREVIEW_HEIGHT" => "38",
		"PREVIEW_TRUNCATE_LEN" => "50",
		"PREVIEW_WIDTH" => "38",
		"PRICE_CODE" => array(0=>"BASE",),
		"PRICE_VAT_INCLUDE" => "Y",
		"SEARCH_ICON" => $arParams["SEARCH_ICON"]=="Y"?"Y":"N",
		"SHOW_ANOUNCE" => "N",
		"SHOW_INPUT" => "Y",
		"SHOW_INPUT_FIXED" => "Y",
		"SHOW_OTHERS" => "N",
		"SHOW_PREVIEW" => "Y",
		"TOP_COUNT" => "10",
		"USE_LANGUAGE_GUESS" => "Y"
	),
false,
Array(
	'ACTIVE_COMPONENT' => 'Y'
)
);?>