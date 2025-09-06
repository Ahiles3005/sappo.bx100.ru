<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? use \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Web\Json; ?>

<?
$currencyList = '';
if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = [
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
];
unset($currencyList, $templateLibrary);

$arParamsCE_CMP = $arParams;
$arParamsCE_CMP['TYPE_SKU'] = 'N';


?>


<? if ($arResult["ITEMS"]): ?>
    <div class="js_wrapper_items">
        <div class="content_wrapper_block main">
            <div class="maxwidth-theme c-common--div__MAXWIDTH">
                <div class="tab_slider_wrapp specials best_block clearfix" itemscope=""
                     itemtype="http://schema.org/WebPage">

                    <div class="top_block">
                        <h3><?= $arParams['SECTION_NAME'] ?></h3>
                    </div>
                    <ul class="tabs_content">
                        <li class="tab SALE_wrapp  cur opacity1" data-code="SALE">
                            <div class="tabs_slider SALE_slides wr">
                                <div class="top_wrapper items_wrapper catalog_block_template ">
                                    <div class="fast_view_params" data-params="N%3B"></div>
                                    <div class="catalog_block items row  margin0 has-bottom-nav js_append ajax_load block flexbox">
                                        <?
                                        $arOfferProps = '';
                                        ?>
                                        <? foreach ($arResult["ITEMS"] as $arItem) {
                                            $map = [];
                                            foreach ($arItem ['ITEM_ALL_PRICES'][0]['PRICES'] as $priceData) {
                                                if ($priceData['PRICE_TYPE_ID'] == 2) {
                                                    $map['Цена'] = $priceData;
                                                } elseif ($priceData['PRICE_TYPE_ID'] == 14) {
                                                    $map['Цена без скидки'] = $priceData;
                                                }
                                            }

                                            $arItem["PRICES"] = $map;

                                            $basePrice = $arItem["PRICES"]["Цена"]["PRICE"];
                                            $oldPrice = $arItem["PRICES"]["Цена без скидки"]["PRICE"];
                                            $ecoP = $oldPrice - $basePrice;

                                            $basePriceM = $arItem["PRICES"]["Цена"]["PRICE"];
                                            $oldPriceM = $arItem["PRICES"]["Цена без скидки"]["PRICE"];
                                            $ecoPrMatrix = $oldPriceM - $basePriceM;
                                            $sale = "N";
                                            if ($ecoP > 0) {
                                                $sale = "Y";
                                            } elseif ($ecoPrMatrix > 0) {
                                                $sale = "Y";
                                            };


                                            ?>

                                            <?
                                            \Bitrix\Main\Loader::includeModule('kilbil.bonus');
                                            $catalog = new \Kilbil\Bonus\Ecommerce\Catalog\Catalog();
                                            $bonusesSum = $catalog->calculateBonus([
                                                [
                                                    'ID' => $arItem['ID'],
                                                    'PRICE' => $basePrice
                                                ]
                                            ]);

                                            ?>


                                            <? $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                                            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')]);

                                            if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])) {
                                                foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                                                    if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
                                                        unset($arItem['PRODUCT_PROPERTIES'][$propID]);
                                                }
                                            }

                                            $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                                            $arItem["EMPTY_PROPS_JS"] = (!$emptyProductProperties ? "N" : "Y");

                                            $item_id = $arItem["ID"];
                                            $strMeasure = '';
                                            $arCurrentSKU = [];

                                            $currentSKUID = $currentSKUIBlock = '';

                                            $totalCount = CMax::GetTotalCount($arItem, $arParams);
                                            $arQuantityData = CMax::GetQuantityArray($totalCount, ['ID' => $item_id], 'N', (($arItem["OFFERS"] || $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || $bSlide || !$arResult['STORES_COUNT']) ? false : true));

                                            if (isset($arParams['ID_FOR_TABS']) && $arParams['ID_FOR_TABS'] == 'Y') {
                                                $arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']) . "_" . $arParams["FILTER_HIT_PROP"];
                                            } else {
                                                $arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
                                            }

                                            $arItemIDs = CMax::GetItemsIDs($arItem);

                                            if ($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]) {
                                                if (isset($arItem["ITEM_MEASURE"]) && (is_array($arItem["ITEM_MEASURE"]) && $arItem["ITEM_MEASURE"]["TITLE"])) {
                                                    $strMeasure = $arItem["ITEM_MEASURE"]["TITLE"];
                                                } else {
                                                    $arMeasure = CCatalogMeasure::getList([], ["ID" => $arItem["CATALOG_MEASURE"]], false, false, [])->GetNext();
                                                    $strMeasure = $arMeasure["SYMBOL_RUS"];
                                                }
                                            }

                                            $bUseSkuProps = ($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP']));

                                            $elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);

                                            if ($bUseSkuProps) {
                                                if (!$arItem["OFFERS"]) {
                                                    $arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);
                                                } elseif ($arItem["OFFERS"]) {

                                                    $currentSKUIBlock = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];
                                                    $currentSKUID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];

                                                    $strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
                                                    $totalCount = CMax::GetTotalCount($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $arParams);
                                                    $arQuantityData = CMax::GetQuantityArray($totalCount, ['ID' => $currentSKUID], 'N', (($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || $bSlide || !$arResult['STORES_COUNT']) ? false : true), 'ce_cmp_hidden');

                                                    if ($arItem["OFFERS"]) {
                                                        $totalCountCMP = CMax::GetTotalCount($arItem, $arParamsCE_CMP);
                                                        $arQuantityDataCMP = CMax::GetQuantityArray($totalCountCMP, ['ID' => $item_id], 'N', false, 'ce_cmp_visible');
                                                    }


                                                    $arItem["DETAIL_PAGE_URL"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PAGE_URL"];
                                                    if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
                                                        $arItem["PREVIEW_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"];
                                                    if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
                                                        $arItem["DETAIL_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PICTURE"];

                                                    if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']) {
                                                        $arItem['SELECTED_SKU_IPROPERTY_VALUES'] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES'];
                                                    }

                                                    if ($arParams["SET_SKU_TITLE"] == "Y")
                                                        $arItem["NAME"] = $elementName = ((isset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['NAME']);
                                                    $item_id = $currentSKUID;

                                                    // ARTICLE
                                                    if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) {
                                                        $arItem["ARTICLE"]["NAME"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["NAME"];
                                                        $arItem["ARTICLE"]["VALUE"] = (is_array($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) ? reset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]);
                                                        unset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]);
                                                    }

                                                    $arCurrentSKU = $arItem["JS_OFFERS"][$arItem["OFFERS_SELECTED"]];
                                                    $strMeasure = $arCurrentSKU["MEASURE"];

                                                    $arAddToBasketData = CMax::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);
                                                }
                                            } else {
                                                $arItem['OFFERS_PROP'] = '';
                                                if ($arItem["OFFERS"]) {
                                                    $arItem["OFFERS_MORE"] = "Y";
                                                }

                                                $arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, [], 'btn-exlg', $arParams);
                                            }


                                            ?>


                                            <? $bFonImg = false; ?>


                                            <? ob_start(); ?>
                                            <div class="rating">
                                                <? $frame = $this->createFrame('dv_' . $arItem["ID"])->begin(''); ?>
                                                <?
                                                global $arTheme;
                                                if ($arParams['REVIEWS_VIEW']):?>
                                                    <div class="blog-info__rating--top-info">
                                                        <div class="votes_block nstar with-text">
                                                            <div class="ratings">
                                                                <? $message = $arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? GetMessage('VOTES_RESULT', ['#VALUE#' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE']]) : GetMessage('VOTES_RESULT_NONE') ?>
                                                                <div class="inner_rating" title="<?= $message ?>">
                                                                    <? for ($i = 1; $i <= 5; $i++): ?>
                                                                        <div class="item-rating <?= $i <= $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ? 'filed' : '' ?>"><?= CMax::showIconSvg("star", SITE_TEMPLATE_PATH . "/images/svg/catalog/star_small.svg"); ?></div>
                                                                    <? endfor; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <? if ($arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']): ?>
                                                            <span class="font_sxs"><?= $arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ?></span>
                                                        <? endif; ?>
                                                    </div>
                                                <? else: ?>
                                                    <? $APPLICATION->IncludeComponent(
                                                        "bitrix:iblock.vote",
                                                        "element_rating_front",
                                                        [
                                                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                                            "IBLOCK_ID" => $arItem["IBLOCK_ID"],
                                                            "ELEMENT_ID" => $arItem["ID"],
                                                            "MAX_VOTE" => 5,
                                                            "VOTE_NAMES" => [],
                                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                            "DISPLAY_AS_RATING" => 'vote_avg'
                                                        ],
                                                        $component, ["HIDE_ICONS" => "Y"]
                                                    ); ?>
                                                <? endif; ?>
                                                <? $frame->end(); ?>
                                            </div>
                                            <? $itemRating = ob_get_clean(); ?>


                                            <? ob_start(); ?>
                                            <div class="sa_block"
                                                 data-fields='<?= Json::encode($arParams["FIELDS"]) ?>'
                                                 data-stores='<?= Json::encode($arParams["STORES"]) ?>'
                                                 data-user-fields='<?= Json::encode($arParams["USER_FIELDS"]) ?>'>
                                                <?= $arQuantityData["HTML"]; ?>
                                                <? if (isset($arQuantityDataCMP) && $arQuantityDataCMP && $arItem['OFFERS']): ?>
                                                    <?= $arQuantityDataCMP["HTML"]; ?>
                                                <? endif; ?>
                                                <? $bHasArticle = isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE']; ?>
                                                <div class="article_block"
                                                     <? if ($bHasArticle): ?>data-name="<?= Loc::getMessage('T_ARTICLE_COMPACT'); ?>"
                                                     data-value="<?= $arItem['ARTICLE']['VALUE']; ?>"<? endif; ?>><? if ($bHasArticle) { ?>
                                                        <div class="muted font_sxs"><?= Loc::getMessage('T_ARTICLE_COMPACT'); ?>
                                                        : <?= $arItem['ARTICLE']['VALUE']; ?></div><? } ?></div>
                                            </div>
                                            <? $itemSaBlock = ob_get_clean(); ?>

                                            <? ob_start(); ?>
                                            <div class="item-title">
                                                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
                                                   class="<?= $bFonImg ? '' : 'dark_link' ?> js-notice-block__title option-font-bold font_sm"><span><?= $elementName; ?></span></a>
                                            </div>
                                            <? $itemTitle = ob_get_clean(); ?>

                                            <? ob_start(); ?>
                                            <div class="cost prices clearfix 123qwe">

                                                <div class="icons-basket-wrapper offer_buy_block ce_cmp_hidden">
                                                    <div class="button_block">
                                                        <!--noindex-->
                                                        <?= $arAddToBasketData["HTML"] ?>
                                                        <!--/noindex-->
                                                    </div>
                                                    <? if ($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && !count($arItem["OFFERS"]) && $arAddToBasketData["ACTION"] == "ADD" && $arAddToBasketData["CAN_BUY"]): ?>
                                                        <? $bWrap = true ?>
                                                        <? if (is_array($arParams) && $arParams): ?>
                                                            <? ob_start(); ?>
                                                            <? if (($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]): ?>
                                                                <? if ($bWrap): ?>
                                                                    <div class="counter_block_inner">
                                                                <? endif; ?>
                                                                <div class="counter_block<?= $class; ?>"
                                                                     data-item="<?= $arItem["ID"]; ?>">
                                                                    <? $cntBasketItems = CSaleBasket::GetList(
                                                                        ["NAME" => "ASC", "ID" => "ASC"],
                                                                        [
                                                                            "PRODUCT_ID" => $arItem["ID"],
                                                                            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                                                                            "LID" => SITE_ID,
                                                                            "ORDER_ID" => "NULL"
                                                                        ],
                                                                        false,
                                                                        false,
                                                                        ["QUANTITY"]
                                                                    )->Fetch();
                                                                    ?>
                                                                    <span class="minus dark-color"
                                                                          id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>"><?= \CMax::showIconSvg("wish ncolor colored1", SITE_TEMPLATE_PATH . "/images/svg/minus" . $svgSize . ".svg"); ?></span>
                                                                    <input type="text" class="text"
                                                                           id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>"
                                                                           name="<?= $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
                                                                           value="<?= floatval($cntBasketItems['QUANTITY']) ? floatval($cntBasketItems['QUANTITY']) : 1 ?>"/>
                                                                    <span class="plus dark-color"
                                                                          id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?= ($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='" . $arAddToBasketData["MAX_QUANTITY_BUY"] . "'" : "") ?>><?= \CMax::showIconSvg("wish ncolor colored1", SITE_TEMPLATE_PATH . "/images/svg/plus" . $svgSize . ".svg"); ?></span>
                                                                </div>
                                                                <? if ($bWrap): ?>
                                                                    </div>
                                                                <? endif; ?>
                                                            <? endif; ?>
                                                            <? $html = ob_get_contents();
                                                            ob_end_clean();

                                                            foreach (GetModuleEvents(\CMax::moduleID, 'OnAsproShowItemCounter', true) as $arEvent) // event for manipulation item delay and compare buttons
                                                                ExecuteModuleEventEx($arEvent, [
                                                                    $arAddToBasketData,
                                                                    $arItem,
                                                                    $arItemIDs,
                                                                    $arParams,
                                                                    &$html
                                                                ]);

                                                            echo $html; ?>
                                                        <? endif; ?>
                                                    <? endif; ?>
                                                </div>

                                                <? if ($arItem["OFFERS"]): ?>
                                                    <? if ($arCurrentSKU): ?>
                                                        <div class="ce_cmp_hidden">
                                                    <? endif; ?>
                                                    <?= \Aspro\Functions\CAsproMaxItem::showItemPricesDefault($arParams); ?>
                                                    <div class="js_price_wrapper">
                                                        <? if ($arCurrentSKU): ?>
                                                            <? $item_id = $arCurrentSKU["ID"];
                                                            $arCurrentSKU['PRICE_MATRIX'] = $arCurrentSKU['PRICE_MATRIX_RAW'];
                                                            $arCurrentSKU['CATALOG_MEASURE_NAME'] = $arCurrentSKU['MEASURE'];
                                                            if (isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']): // USE_PRICE_COUNT
                                                                ?>
                                                                <? if ($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1): ?>
                                                                <?= CMax::showPriceRangeTop($arCurrentSKU, $arParams, Loc::getMessage("CATALOG_ECONOMY")); ?>
                                                            <? endif; ?>
                                                                <?= CMax::showPriceMatrix($arCurrentSKU, $arParams, $strMeasure, $arAddToBasketData); ?>
                                                            <? else: ?>
                                                                <? \Aspro\Functions\CAsproMaxItem::showItemPricesPatched($arParams, $arCurrentSKU["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
                                                            <? endif; ?>
                                                        <? else: ?>
                                                            <? \Aspro\Functions\CAsproMaxSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, [], ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
                                                        <? endif; ?>
                                                    </div>
                                                    <? if ($arCurrentSKU): ?>
                                                        </div>
                                                        <div class="ce_cmp_visible">
                                                            <? \Aspro\Functions\CAsproMaxSku::showItemPrices($arParamsCE_CMP, $arItem, $item_id, $min_price_id, $arItemIDs, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
                                                        </div>
                                                    <? endif; ?>
                                                <? else: ?>

                                                    <? if (isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']): // USE_PRICE_COUNT?>
                                                        <? if (\CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') == 'Y' || $arItem['ITEM_PRICE_MODE'] == 'Q' || (\CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') != 'Y' && $arItem['ITEM_PRICE_MODE'] != 'Q' && count($arItem['PRICE_MATRIX']['COLS']) <= 1)): ?>
                                                            <?= CMax::showPriceRangeTop($arItem, $arParams, Loc::getMessage("CATALOG_ECONOMY")); ?>
                                                        <? endif; ?>
                                                        <? if (count($arItem['PRICE_MATRIX']['ROWS']) > 1 || count($arItem['PRICE_MATRIX']['COLS']) > 1): ?>
                                                            <?

                                                            if ($sale === "Y") {
                                                                ?>
                                                                <div class="price_group min">
                                                                    <div class="price_matrix_wrapper">
                                                                        <div class="prices-wrapper">
                                                                            <div class="price font-bold font_mlg font_mxs"
                                                                                 data-currency=""
                                                                                 data-value="">
												<span class="values_wrapper">
													<span class="price_value"><?= $basePriceM ?></span>
													<span class="price_currency"> ₽</span>
												</span>
                                                                            </div>
                                                                            <div class="price discount"
                                                                                 data-currency="" data-value="">
                                                                                <span class="values_wrapper <?= ($arParams['MD_PRICE'] ? 'font_sm' : 'font_xs'); ?> muted"><?= $oldPriceM ?> ₽</span>
                                                                            </div>

                                                                        </div>

                                                                        <div class="sale_block">
                                                                            <div class="sale_wrapper font_xxs">

                                                                                <?
                                                                                $percent = round(($ecoPrMatrix / $oldPriceM) * 100, 0); ?>
                                                                                <div class="sale-number rounded2">
                                                                                    <? if ($percent && $percent < 100): ?>
                                                                                        <div class="value">
                                                                                            -<span><?= $percent; ?></span>%
                                                                                        </div>
                                                                                    <? endif; ?>
                                                                                    <div class="inner-sale rounded1">
                                                                                        <div class="text">Экономия
                                                                                            <span class="values_wrapper"><span
                                                                                                        class="price_value"><?= $ecoPrMatrix ?></span><span
                                                                                                        class="price_currency"> ₽</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            <? } else {
                                                                ?>
                                                                <?= CMax::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData); ?>
                                                            <? } ?>

                                                        <? endif; ?>
                                                    <? elseif ($arItem["PRICES"]): ?>
                                                        <? //доработка отображения старых цен в скидку
                                                        if ($sale === "Y") {
                                                            ?>
                                                            <div class="price_group min">
                                                                <div class="price_matrix_wrapper">
                                                                    <div class="prices-wrapper">
                                                                        <div class="price font-bold font_mlg font_mxs"
                                                                             data-currency=""
                                                                             data-value="">
												<span class="values_wrapper">
													<span class="price_value"><?= $basePrice ?></span>
													<span class="price_currency"> ₽</span>
												</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="sale_block">
                                                                        <div class="sale_wrapper font_xxs">
                                                                            <? $percent = round(($ecoP / $oldPrice) * 100, 0); ?>
                                                                            <div class="sale-number rounded2">
                                                                                <? if ($percent && $percent < 100): ?>
                                                                                    <div class="value">
                                                                                        -<span><?= $percent; ?></span>%
                                                                                    </div>
                                                                                <? endif; ?>
                                                                                <div class="inner-sale rounded1">
                                                                                    <div class="text">Экономия <span
                                                                                                class="values_wrapper"><span
                                                                                                    class="price_value"><?= $ecoP ?></span><span
                                                                                                    class="price_currency"> ₽</span></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="price discount" data-currency=""
                                                                             data-value="">
                                                                            <span class="values_wrapper <?= ($arParams['MD_PRICE'] ? 'font_sm' : 'font_xs'); ?> muted"><?= $oldPrice ?> ₽</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <?
                                                        } else {
                                                            if ($arItem["PRICES"]['Цена']) {
                                                                unset($arItem["PRICES"]['Цена без скидки']);
                                                            }
                                                            \Aspro\Functions\CAsproMaxItem::showItemPricesPatched($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));
                                                        }
                                                    endif;
                                                endif;

                                                ?>
                                            </div>
                                            <? $itemPrice = ob_get_clean(); ?>

                                            <? ob_start(); ?>
                                            <div class="footer_button <?= ($arItem["OFFERS"] && $arItem['OFFERS_PROP'] ? 'has_offer_prop' : ''); ?> inner_content js_offers__<?= $arItem['ID']; ?>_<?= $arParams["FILTER_HIT_PROP"] ?><?= ($arParams["TYPE_VIEW_BASKET_BTN"] == "TYPE_2" ? ' n-btn' : '') ?>">

                                                <div class="counter_wrapp clearfix offer_buy_block<?= (($arAddToBasketData["ACTION"] == "NOTHING") ? ' n-btn' : ''); ?> ce_cmp_visible">
                                                    <div class="button_block">
                                                        <? if ($totalCountCMP): ?>
                                                            <? if ($bUseSkuProps) {
                                                                if (!$arItem["OFFERS"]) {
                                                                    $arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCountCMP, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);
                                                                } elseif ($arItem["OFFERS"]) {
                                                                    $arAddToBasketData = CMax::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCountCMP, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);

                                                                }
                                                            }
                                                            ?>
                                                        <? endif; ?>
                                                        <!--noindex-->
                                                        <?= $arAddToBasketData["HTML"] ?>
                                                        <!--/noindex-->
                                                    </div>
                                                    <? if ($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && !count($arItem["OFFERS"]) && $arAddToBasketData["ACTION"] == "ADD" && $arAddToBasketData["CAN_BUY"]): ?>
                                                        <? $bWrap = true ?>
                                                        <? if (is_array($arParams) && $arParams): ?>
                                                            <? ob_start(); ?>
                                                            <? if (($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]): ?>
                                                                <? if ($bWrap): ?>
                                                                    <div class="counter_block_inner">
                                                                <? endif; ?>
                                                                <div class="counter_block <?= $class; ?>" data-item="<?= $arItem["ID"]; ?>">
                                                                    <? $cntBasketItems = CSaleBasket::GetList(
                                                                        array("NAME" => "ASC", "ID" => "ASC"),
                                                                        array("PRODUCT_ID" => $arItem["ID"], "FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"),
                                                                        false,
                                                                        false,
                                                                        array("QUANTITY")
                                                                    )->Fetch();
                                                                    ?>
                                                                    <span class="minus dark-color"
                                                                          id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>"><?= \CMax::showIconSvg("wish ncolor colored1", SITE_TEMPLATE_PATH . "/images/svg/minus" . $svgSize . ".svg"); ?></span>
                                                                    <input type="text" class="text"
                                                                           id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>"
                                                                           name="<?= $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
                                                                           value="<?= floatval($cntBasketItems['QUANTITY']) ? floatval($cntBasketItems['QUANTITY']) : $arAddToBasketData["MIN_QUANTITY_BUY"] ?>"/>
                                                                    <span class="plus dark-color"
                                                                          id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?= ($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='" . $arAddToBasketData["MAX_QUANTITY_BUY"] . "'" : "") ?>><?= \CMax::showIconSvg("wish ncolor colored1", SITE_TEMPLATE_PATH . "/images/svg/plus" . $svgSize . ".svg"); ?></span>
                                                                </div>
                                                                <? if ($bWrap): ?>
                                                                    </div>
                                                                <? endif; ?>
                                                            <? endif; ?>
                                                            <? $html = ob_get_contents();
                                                            ob_end_clean();

                                                            foreach (GetModuleEvents(\CMax::moduleID, 'OnAsproShowItemCounter', true) as $arEvent) // event for manipulation item delay and compare buttons
                                                                ExecuteModuleEventEx($arEvent, array($arAddToBasketData, $arItem, $arItemIDs, $arParams, &$html));

                                                            echo $html; ?>
                                                        <? endif; ?>
                                                    <? endif; ?>
                                                </div>



                                                <div class="sku_props ce_cmp_hidden">
                                                    <? if ($arItem["OFFERS"]) {
                                                        if (!empty($arItem['OFFERS_PROP'])) {
                                                            ?>
                                                            <div class="bx_catalog_item_scu wrapper_sku"
                                                                 id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>"
                                                                 data-site_id="<?= SITE_ID; ?>"
                                                                 data-id="<?= $arItem["ID"]; ?>"
                                                                 data-offer_id="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"]; ?>"
                                                                 data-propertyid="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PROPERTIES"]["CML2_LINK"]["ID"]; ?>"
                                                                 data-offer_iblockid="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"]; ?>">
                                                                <? $arSkuTemplate = []; ?>
                                                                <? $arSkuTemplate = CMax::GetSKUPropsArray($arItem['OFFERS_PROPS_JS'], $arResult["SKU_IBLOCK_ID"], $arParams["DISPLAY_TYPE"], $arParams["OFFER_HIDE_NAME_PROPS"], "N", $arItem, $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS'], $arParams['MAX_SCU_COUNT_VIEW']); ?>
                                                                <? foreach ($arSkuTemplate as $code => $strTemplate) {
                                                                    if (!isset($arItem['OFFERS_PROP'][$code]))
                                                                        continue;
                                                                    echo '<div class="item_wrapper">', str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate), '</div>';
                                                                } ?>
                                                            </div>
                                                            <? $arItemJSParams = CMax::GetSKUJSParams($arResult, $arParams, $arItem); ?>
                                                            <?
                                                        }
                                                    } ?>
                                                </div>
                                            </div>
                                            <? $itemFooterButton = ob_get_clean(); ?>

                                            <? ob_start(); ?>
                                            <? $min_price_id = 0;
                                            if ($arItem["OFFERS"]) {
                                                if ($arCurrentSKU && isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
                                                {
                                                    if (isset($arCurrentSKU['PRICE_MATRIX']['MATRIX']) && is_array($arCurrentSKU['PRICE_MATRIX']['MATRIX'])) {
                                                        $arMatrixKey = array_keys($arCurrentSKU['PRICE_MATRIX']['MATRIX']);
                                                        $min_price_id = current($arMatrixKey);
                                                    }
                                                }
                                            } else {
                                                if (isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
                                                {
                                                    $arMatrixKey = array_keys($arItem['PRICE_MATRIX']['MATRIX']);
                                                    $min_price_id = current($arMatrixKey);
                                                }
                                            } ?>
                                            <? $arDiscount = [] ?>
                                            <? \Aspro\Functions\CAsproMax::showDiscountCounter($totalCount, $arDiscount, $arQuantityData, $arItem, $strMeasure, 'v2 grey', $item_id, true); ?>
                                            <? $itemDiscountTime = ob_get_clean(); ?>

                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 col-xxs-12 item item-parent js-notice-block item_block ">

                                                <div class="sbonus-product v2">
                                                    <span>+<?= round($bonusesSum[$arItem['ID']]); ?></span></div>

                                                <div class="basket_props_block"
                                                     id="bx_basket_div_<?= $arItem["ID"]; ?>_<?= $arParams["FILTER_HIT_PROP"] ?>"
                                                     style="display: none;">
                                                    <? if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])) {
                                                        foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo):?>
                                                            <input type="hidden"
                                                                   name="<?= $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?= $propID; ?>]"
                                                                   value="<?= htmlspecialcharsbx($propInfo['ID']); ?>">
                                                        <?endforeach;
                                                    }
                                                    if (!$emptyProductProperties) {
                                                        ?>
                                                        <div class="wrapper">
                                                            <table>
                                                                <? foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
                                                                        <td>
                                                                            <? if ('L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']) {
                                                                                foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                                                    ?>
                                                                                    <label>
                                                                                        <input type="radio"
                                                                                               name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                                                                               value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
                                                                                    </label>
                                                                                    <?
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
                                                                                    foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                                                        ?>
                                                                                        <option value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
                                                                                    <? } ?>
                                                                                </select>
                                                                            <? } ?>
                                                                        </td>
                                                                    </tr>
                                                                <? } ?>
                                                            </table>
                                                        </div>
                                                    <? } ?>
                                                </div>

                                                <div class="catalog_item_wrapp catalog_item item_wrap main_item_wrapper <?= ($bFonImg ? '' : ' product_image') ?> <?= ($arItem["OFFERS"] ? 'has-sku' : '') ?>"
                                                     id="<?= $arItem["strMainID"] ?>">
                                                    <div class="inner_wrap <?= $arParams["TYPE_VIEW_BASKET_BTN"] ?>">
                                                        <? if ($arParams['SHOW_GALLERY'] == 'Y' && $arItem['OFFERS']): ?>
                                                            <div class="js-item-gallery hidden"><? \Aspro\Functions\CAsproMaxItem::showSectionGallery([
                                                                    'ITEM' => $arItem,
                                                                    'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']
                                                                ]); ?></div>
                                                        <? endif; ?>

                                                        <div id='<?= $totalCount <= 0 ? 'not_available' : '' ?>'
                                                             class="image_wrapper_block js-notice-block__image <?= ($arParams['SHOW_PROPS'] == 'Y' && $arItem['DISPLAY_PROPERTIES'] ? ' with-props' : ''); ?>">
                                                            <? \Aspro\Functions\CAsproMaxItem::showStickers($arParams, $arItem, true); ?>
                                                            <? if ($arParams['TYPE_VIEW_BASKET_BTN'] == 'TYPE_3'): ?>
                                                                <div class="like_icons block ce_cmp_hidden">
                                                                    <? if ($fast_view_text_tmp = \CMax::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
                                                                        $fast_view_text = $fast_view_text_tmp;
                                                                    else
                                                                        $fast_view_text = Loc::getMessage('FAST_VIEW'); ?>
                                                                    <div class="fast_view_button">
                                            <span title="<?= $fast_view_text ?>" class="rounded3 colored_theme_hover_bg"
                                                  data-event="jqm" data-param-form_id="fast_view"
                                                  data-param-iblock_id="<?= $arParams["IBLOCK_ID"]; ?>"
                                                  data-param-id="<?= $arItem["ID"]; ?>"
                                                  data-param-item_href="<?= urlencode($arItem["DETAIL_PAGE_URL"]); ?>"
                                                  data-name="fast_view"><?= \CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH . "/images/svg/quickview" . $typeSvg . ".svg"); ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="ce_cmp_visible">
                                                                    <? \Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'block', ($arParams['USE_FAST_VIEW'] != 'N'), ($arParams['SHOW_ONE_CLICK_BUY'] == 'Y'), '_small', $currentSKUID, $currentSKUIBlock); ?>
                                                                </div>
                                                            <? else: ?>
                                                                <? \Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'block', ($arParams['USE_FAST_VIEW'] != 'N'), ($arParams['SHOW_ONE_CLICK_BUY'] == 'Y'), '_small', $currentSKUID, $currentSKUIBlock); ?>
                                                            <? endif; ?>

                                                            <? if ($arParams['SHOW_PROPS'] == 'Y' && $arItem['DISPLAY_PROPERTIES']): ?>
                                                                <div class="properties properties_absolute scrollbar scroll-deferred">
                                                                    <div class="properties__container">
                                                                        <? foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp): ?>
                                                                            <div class="properties__item">
                                                                                <div class="properties__title font_sxs muted">
                                                                                    <?= $arProp['NAME'] ?>
                                                                                    <? if ($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"): ?>
                                                                                        <div class="hint"><span
                                                                                                    class="icon colored_theme_hover_bg"><i>?</i></span>
                                                                                            <div class="tooltip"><?= $arProp["HINT"] ?></div>
                                                                                        </div>
                                                                                    <? endif; ?>
                                                                                </div>

                                                                                <div class="properties__value font_sm darken">
                                                                                    <?
                                                                                    if (is_array($arProp["DISPLAY_VALUE"])) {
                                                                                        foreach ($arProp["DISPLAY_VALUE"] as $key => $value) {
                                                                                            if ($arProp["DISPLAY_VALUE"][$key + 1]) {
                                                                                                echo $value . ", ";
                                                                                            } else {
                                                                                                echo $value;
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        echo $arProp["DISPLAY_VALUE"];
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        <? endforeach; ?>
                                                                    </div>
                                                                    <div class="properties__container properties__container_js">
                                                                        <? if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['DISPLAY_PROPERTIES']): ?>
                                                                            <? foreach ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['DISPLAY_PROPERTIES'] as $arProp): ?>
                                                                                <div class="properties__item">
                                                                                    <div class="properties__title font_sxs muted"><?= $arProp['NAME'] ?></div>
                                                                                    <div class="properties__value font_sm darken">
                                                                                        <?
                                                                                        if (is_array($arProp["DISPLAY_VALUE"])) {
                                                                                            foreach ($arProp["DISPLAY_VALUE"] as $key => $value) {
                                                                                                if ($arProp["DISPLAY_VALUE"][$key + 1]) {
                                                                                                    echo $value . ", ";
                                                                                                } else {
                                                                                                    echo $value;
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $arProp["DISPLAY_VALUE"];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            <? endforeach; ?>
                                                                        <? endif; ?>
                                                                    </div>
                                                                </div>
                                                            <? endif; ?>
                                                            <?= $itemDiscountTime ?>
                                                            <? if ($arParams['SHOW_GALLERY'] == 'Y' && $arParams['SHOW_PROPS'] != 'Y'): ?>
                                                                <? if ($bUseSkuProps && $arItem["OFFERS"]): ?>
                                                                    <? \Aspro\Functions\CAsproMaxItem::showSectionGallery([
                                                                        'ITEM' => $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]],
                                                                        'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']
                                                                    ]); ?>
                                                                <? else: ?>
                                                                    <? \Aspro\Functions\CAsproMaxItem::showSectionGallery([
                                                                        'ITEM' => $arItem,
                                                                        'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']
                                                                    ]); ?>
                                                                <? endif; ?>
                                                            <? else: ?>
                                                                <? \Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, false); ?>
                                                            <? endif; ?>

                                                        </div>


                                                        <div class="item_info">
                                                            <?= $itemRating ?>
                                                            <?= $itemTitle ?>
                                                            <?= $itemSaBlock ?>
                                                            <?= $itemPrice ?>
                                                        </div>


                                                        <?= $itemFooterButton ?>

                                                    </div>


                                                </div>

                                            </div>


                                        <? } ?>


                                    </div>
                                </div>

                                <div class="bottom_nav animate-load-state block-type" data-parent=".tabs_slider"
                                     data-append=".items">
                                    <a class="hm-sales--a__SEEALL c-common--a__ALL has-ripple"
                                       href="<?= $arParams['BUTTON_URL'] ?>">
                                        Смотреть все
                                    </a>
                                </div>


                                <script>
                                    sliceItemBlock();
                                </script>

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<? endif; ?>