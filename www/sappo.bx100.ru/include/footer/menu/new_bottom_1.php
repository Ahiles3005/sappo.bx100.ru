<? $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "bottom_tree_new", [
    "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
    "VIEW_MODE" => "TEXT",
    "SHOW_PARENT_NAME" => "Y",
    "IBLOCK_TYPE" => "aspro_max_catalog",
    "IBLOCK_ID" => "42",
    "SECTION_ID" => $_REQUEST["SECTION_ID"],
    "SECTION_CODE" => "",
    "SECTION_URL" => "",
    "COUNT_ELEMENTS" => "Y",
    "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
    "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
    "TOP_DEPTH" => "2",
    "SECTION_FIELDS" => "",
    "SECTION_USER_FIELDS" => "",
    "ADD_SECTIONS_CHAIN" => "N",
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000000",
    "CACHE_NOTES" => "",
    "CACHE_GROUPS" => "Y"
],
    false
); ?>