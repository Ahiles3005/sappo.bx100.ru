<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>




<?
$commonComponentParams = [
    'IBLOCK_TYPE' => 'aspro_max_catalog',
    'IBLOCK_ID' => 42,
    'SECTION_ID' => '',
    'SECTION_CODE' => '',
    'TABS_CODE' => 'HIT',
    'SECTION_USER_FIELDS' => ['', ''],
    'ELEMENT_SORT_FIELD2' => 'id',
    'ELEMENT_SORT_ORDER2' => 'desc',
    'INCLUDE_SUBSECTIONS' => 'Y',
    'SHOW_ALL_WO_SECTION' => 'Y',
    'HIDE_NOT_AVAILABLE' => 'Y',
    'PAGE_ELEMENT_COUNT' => 4,
    'LINE_ELEMENT_COUNT' => 4,
    'PROPERTY_CODE' => [
        'CML2_ARTICLE',
        'PROP_2089',
        'PROP_2085',
        'PROP_2084',
        'PROP_2091',
        'PROP_2086',
        'PROP_2090',
        'PROP_2092',
        'PROP_2093',
        'PROP_2094',
        ''
    ],
    'OFFERS_LIMIT' => 0,
    'SECTION_URL' => '',
    'DETAIL_URL' => '',
    'BASKET_URL' => '/basket/',
    'ACTION_VARIABLE' => 'action',
    'PRODUCT_ID_VARIABLE' => 'id',
    'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
    'PRODUCT_PROPS_VARIABLE' => 'prop',
    'SECTION_ID_VARIABLE' => 'SECTION_ID',
    'AJAX_MODE' => 'N',
    'AJAX_OPTION_JUMP' => 'N',
    'AJAX_OPTION_STYLE' => 'Y',
    'AJAX_OPTION_HISTORY' => 'N',
    'ADD_DETAIL_TO_SLIDER' => 'Y',
    'CACHE_TYPE' => 'N',
    'CACHE_TIME' => 36000000,
    'CACHE_GROUPS' => 'N',
    'CACHE_FILTER' => 'Y',
    'META_KEYWORDS' => '-',
    'META_DESCRIPTION' => '-',
    'BROWSER_TITLE' => '-',
    'ADD_SECTIONS_CHAIN' => 'N',
    'DISPLAY_COMPARE' => 'Y',
    'SET_TITLE' => 'N',
    'SET_STATUS_404' => 'N',
    'PRICE_CODE' => ['Цена без скидки', 'Цена'],
    'USE_PRICE_COUNT' => 'Y',
    'SHOW_ONE_CLICK_BUY' => 'Y',
    'SHOW_PRICE_COUNT' => 1,
    'PRICE_VAT_INCLUDE' => 'Y',
    'PRODUCT_PROPERTIES' => [],
    'USE_PRODUCT_QUANTITY' => 'N',
    'CONVERT_CURRENCY' => 'N',
    'DISPLAY_TOP_PAGER' => 'N',
    'DISPLAY_BOTTOM_PAGER' => 'Y',
    'PAGER_TITLE' => 'Товары',
    'PAGER_SHOW_ALWAYS' => 'N',
    'PAGER_TEMPLATE' => 'ajax',
    'PAGER_DESC_NUMBERING' => 'N',
    'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
    'PAGER_SHOW_ALL' => 'N',
    'DISCOUNT_PRICE_CODE' => '',
    'AJAX_OPTION_ADDITIONAL' => '',
    'SHOW_ADD_FAVORITES' => 'Y',
    'SHOW_ARTICLE_SKU' => 'Y',
    'SECTION_NAME_FILTER' => '',
    'SECTION_SLIDER_FILTER' => 21,
    'COMPONENT_TEMPLATE' => 'main',
    'OFFERS_FIELD_CODE' => ['ID', '', 'DETAIL_PAGE_URL', 'NAME'],
    'OFFERS_PROPERTY_CODE' => ['ARTICLE', 'SIZES', 'AGE', ''],
    'OFFER_TREE_PROPS' => ['', 'SIZES', ''],
    'OFFERS_SORT_FIELD' => 'sort',
    'OFFERS_SORT_ORDER' => 'asc',
    'OFFERS_SORT_FIELD2' => 'id',
    'OFFERS_SORT_ORDER2' => 'desc',
    'SHOW_MEASURE' => 'Y',
    'OFFERS_CART_PROPERTIES' => [],
    'DISPLAY_WISH_BUTTONS' => 'Y',
    'SHOW_DISCOUNT_PERCENT' => 'Y',
    'SHOW_OLD_PRICE' => 'Y',
    'SHOW_RATING' => 'Y',
    'MAX_GALLERY_ITEMS' => 5,
    'SHOW_GALLERY' => 'Y',
    'ADD_PICT_PROP' => 'MORE_PHOTO',
    'OFFER_ADD_PICT_PROP' => 'MORE_PHOTO',
    'SALE_STIKER' => 'SALE_TEXT',
    'FAV_ITEM' => 'FAVORIT_ITEM',
    'SHOW_DISCOUNT_TIME' => 'Y',
    'STORES' => [5],
    'STIKERS_PROP' => 'HIT',
    'SHOW_DISCOUNT_PERCENT_NUMBER' => 'Y',
    'SHOW_MEASURE_WITH_RATIO' => 'Y',
    'SHOW_DISCOUNT_TIME_EACH_SKU' => 'Y',
    'TITLE_BLOCK' => 'Лучшие предложения',
    'TITLE_BLOCK_ALL' => 'Весь каталог',
    'ALL_URL' => 'catalog/',
    'COMPOSITE_FRAME_MODE' => 'A',
    'COMPOSITE_FRAME_TYPE' => 'AUTO',
    'ADD_PROPERTIES_TO_BASKET' => 'Y',
    'PARTIAL_PRODUCT_PROPERTIES' => 'N',
    'ADD_PICT_PROP_OFFER' => 'MORE_PHOTO',
    'ID_FOR_TABS' => 'Y',
    'USER_FIELDS' => ['', ''],
    'FIELDS' => ['', ''],
    'QUANTITY_FLOAT' => 'N',
    'SET_SKU_TITLE' => 'Y',
    'SHOW_PROPS' => 'N',
    'DISPLAY_TYPE' => 'block',
    'TYPE_SKU' => 'TYPE_1',
    'MAX_SCU_COUNT_VIEW' => 20,
    'USE_CUSTOM_RESIZE_LIST' => 'N',
    'IS_COMPACT_SLIDER' => '',
    'CHECK_REQUEST_BLOCK' => '',
    'USE_FAST_VIEW' => 'fast_view_1',
    'USE_PERMISSIONS' => '',
    'GROUP_PERMISSIONS' => [1],
    'TYPE_VIEW_BASKET_BTN' => 'TYPE_2',
    'REVIEWS_VIEW' => '',
    'IS_AJAX' => 1,
    'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
    'USE_REGION' => 'Y',
    'PROP_CODE' => 'HIT',
    'FILL_ITEM_ALL_PRICES' => 'Y'
];

// Массив с параметрами для каждой вкладки
$tabs = [
    'SALE' => [
        'filter_name' => 'arrFilterPropSale',
        'filter_hit_prop' => 'SALE',
        'button_url' => '/sale-new/',
        'class' => 'hm-sales',
        'order'=>2,
        'section_name'=>'Акции',
    ],
    'NEW' => [
        'filter_name' => 'arrFilterPropNew',
        'filter_hit_prop' => 'NEW',
        'button_url' => '/new/',
        'class' => 'hm-newprod',
        'order'=>3,
        'section_name'=>'Новинки',
        'sort_field'=>'created',
        'sort_order'=>'desc',
    ],
    'HIT' => [
        'filter_name' => 'arrFilterPropHit',
        'filter_hit_prop' => 'HIT',
        'button_url' => '/hit/',
        'class' => 'hm-hits',
        'order'=>4,
        'section_name'=>'Хиты продаж',
        'sort_field'=>'property_QUANTITY_SOLD',
        'sort_order'=>'desc',
    ],

    'RECOMMEND' => [
        'filter_name' => 'arrFilterPropRec',
        'filter_hit_prop' => 'RECOMMEND',
        'button_url' => '/recommend/',
        'class' => 'hm-recom',
        'order'=>5,
        'section_name'=>'Советуем',
    ],
];

foreach ($tabs as $tabCode => $tabParams) {
    global ${$tabParams['filter_name']};
    ${$tabParams['filter_name']} = [
        'PROPERTY_HIT_VALUE' => [$tabCode === 'NEW' ? 'Новинка' : ($tabCode === 'HIT' ? 'Хит' : ($tabCode === 'RECOMMEND' ? 'Советуем' : 'Акция'))],
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => 42,
        [
            'LOGIC' => 'OR',
            ['TYPE' => [2, 3]],
            ['STORE_NUMBER' => [5], '>STORE_AMOUNT' => 0]
        ]
    ];
} ?>
<?
foreach ($tabs as $tabCode => $tabParams) :?>

    <div class="drag-block container grey CATALOG_TAB  <?= $tabParams['class'] ?>" data-class="catalog_tab_drag" data-order="<?= $tabParams['order'] ?>">

        <?
        $componentParams = array_merge($commonComponentParams, [
            'FILTER_NAME' => $tabParams['filter_name'],
            'FILTER_HIT_PROP' => $tabParams['filter_hit_prop'],
            'BUTTON_URL' => $tabParams['button_url'],
            'SECTION_NAME' => $tabParams['section_name'],
            'ELEMENT_SORT_FIELD' => $tabParams['sort_field'] ?? 'sort',
            'ELEMENT_SORT_ORDER' => $tabParams['sort_order'] ?? 'asc',
        ]);

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "catalog_block_main_new",
            $componentParams,
            false,
            ["HIDE_ICONS" => "Y"]
        );
        ?>
    </div>

<? endforeach; ?>



