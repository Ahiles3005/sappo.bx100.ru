<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
$APPLICATION->IncludeComponent(
    "aspro:catalog.section.list.max",
    "front_sections_only",
    [
        'MOBILE_TEMPLATE' => 'normal',
        'IBLOCK_TYPE' => 'aspro_max_catalog',
        'IBLOCK_ID' => '42',
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '36000000',
        'CACHE_GROUPS' => 'Y',
        'CACHE_FILTER' => 'Y',
        'COUNT_ELEMENTS' => '',
        'FILTER_NAME' => 'arrPopularSections',
        'TOP_DEPTH' => '2',
        'SECTION_URL' => '',
        'VIEW_MODE' => '',
        'SHOW_PARENT_NAME' => 'N',
        'HIDE_SECTION_NAME' => 'N',
        'ADD_SECTIONS_CHAIN' => '',
        'SHOW_SECTIONS_LIST_PREVIEW' => 'N',
        'SECTIONS_LIST_PREVIEW_PROPERTY' => 'N',
        'SECTIONS_LIST_PREVIEW_DESCRIPTION' => 'N',
        'SHOW_SECTION_LIST_PICTURES' => 'N',
        'DISPLAY_PANEL' => 'N',
        'COMPONENT_TEMPLATE' => 'front_sections_only',
        'SECTION_ID' => '0',
        'SECTION_CODE' => '',
        'VIEW_TYPE' => 'type1',
        'NO_MARGIN' => 'Y',
        'SHOW_ICONS' => 'Y',
        'FILLED' => 'N',
        'INCLUDE_FILE' => '',
        'SHOW_SUBSECTIONS' => '',
        'SCROLL_SUBSECTIONS' => '',
        'SECTION_FIELDS' => ['', ''],
        'SECTION_USER_FIELDS' => ['UF_CATALOG_ICON', ''],
        'TITLE_BLOCK' => 'Популярные категории',
        'TITLE_BLOCK_ALL' => 'Весь каталог',
        'ALL_URL' => 'catalog/',
        'SECTION_TYPE_TEXT' => 'NORMAL'
    ],
    false
); ?>