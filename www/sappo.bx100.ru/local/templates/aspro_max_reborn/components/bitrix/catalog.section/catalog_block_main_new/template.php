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
                                                <? /*
                                                <div class="catalog_item_wrapp catalog_item item_wrap main_item_wrapper  product_image "
                                                     id="bx_3966226736_346416_SALE">
                                                    <div class="inner_wrap TYPE_2">
                                                        <div class="image_wrapper_block js-notice-block__image ">

                                                            <div class="stickers custom-font">

                                                                <div>
                                                                    <div class="sticker_aktsiya font_sxs rounded2">Акция
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="like_icons block" data-size="3">
                                                                <div class="wish_item_button">
                                                                <span title="Отложить" data-quantity="1"
                                                                      class="wish_item to rounded3 colored_theme_hover_bg"
                                                                      data-item="346416" data-iblock="42"><i
                                                                            class="svg inline  svg-inline-wish ncolor colored"
                                                                            aria-hidden="true"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="13"
                                                                                viewBox="0 0 16 13"><defs><style>.clsw-1 {
                                                                                        fill: #fff;
                                                                                        fill-rule: evenodd;
                                                                                    }</style></defs><path class="clsw-1"
                                                                                                          d="M506.755,141.6l0,0.019s-4.185,3.734-5.556,4.973a0.376,0.376,0,0,1-.076.056,1.838,1.838,0,0,1-1.126.357,1.794,1.794,0,0,1-1.166-.4,0.473,0.473,0,0,1-.1-0.076c-1.427-1.287-5.459-4.878-5.459-4.878l0-.019A4.494,4.494,0,1,1,500,135.7,4.492,4.492,0,1,1,506.755,141.6Zm-3.251-5.61A2.565,2.565,0,0,0,501,138h0a1,1,0,1,1-2,0h0a2.565,2.565,0,0,0-2.506-2,2.5,2.5,0,0,0-1.777,4.264l-0.013.019L500,145.1l5.179-4.749c0.042-.039.086-0.075,0.126-0.117l0.052-.047-0.006-.008A2.494,2.494,0,0,0,503.5,135.993Z"
                                                                                                          transform="translate(-492 -134)"></path></svg></i></span>
                                                                    <span title="В отложенных" data-quantity="1"
                                                                          class="wish_item in added rounded3 colored_theme_bg"
                                                                          style="display: none;" data-item="346416"
                                                                          data-iblock="42"><i
                                                                                class="svg inline  svg-inline-wish ncolor colored"
                                                                                aria-hidden="true"><svg
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    width="16" height="13"
                                                                                    viewBox="0 0 16 13"><defs><style>.clsw-1 {
                                                                                            fill: #fff;
                                                                                            fill-rule: evenodd;
                                                                                        }</style></defs><path
                                                                                        class="clsw-1"
                                                                                        d="M506.755,141.6l0,0.019s-4.185,3.734-5.556,4.973a0.376,0.376,0,0,1-.076.056,1.838,1.838,0,0,1-1.126.357,1.794,1.794,0,0,1-1.166-.4,0.473,0.473,0,0,1-.1-0.076c-1.427-1.287-5.459-4.878-5.459-4.878l0-.019A4.494,4.494,0,1,1,500,135.7,4.492,4.492,0,1,1,506.755,141.6Zm-3.251-5.61A2.565,2.565,0,0,0,501,138h0a1,1,0,1,1-2,0h0a2.565,2.565,0,0,0-2.506-2,2.5,2.5,0,0,0-1.777,4.264l-0.013.019L500,145.1l5.179-4.749c0.042-.039.086-0.075,0.126-0.117l0.052-.047-0.006-.008A2.494,2.494,0,0,0,503.5,135.993Z"
                                                                                        transform="translate(-492 -134)"></path></svg></i></span>
                                                                </div>
                                                                <div class="compare_item_button">
                                                                <span title="Сравнить"
                                                                      class="compare_item to rounded3 colored_theme_hover_bg"
                                                                      data-iblock="42" data-item="346416"><i
                                                                            class="svg inline  svg-inline-compare ncolor colored"
                                                                            aria-hidden="true"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="14" height="13"
                                                                                viewBox="0 0 14 13"><path
                                                                                    data-name="Rounded Rectangle 913 copy"
                                                                                    class="cls-1"
                                                                                    d="M595,137a1,1,0,0,1,1,1v8a1,1,0,1,1-2,0v-8A1,1,0,0,1,595,137Zm-4,3a1,1,0,0,1,1,1v5a1,1,0,1,1-2,0v-5A1,1,0,0,1,591,140Zm8-6a1,1,0,0,1,1,1v11a1,1,0,1,1-2,0V135A1,1,0,0,1,599,134Zm4,6h0a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h0a1,1,0,0,1-1-1v-5A1,1,0,0,1,603,140Z"
                                                                                    transform="translate(-590 -134)"></path></svg></i></span>
                                                                    <span title="В сравнении"
                                                                          class="compare_item in added rounded3 colored_theme_bg"
                                                                          style="display: none;" data-iblock="42"
                                                                          data-item="346416"><i
                                                                                class="svg inline  svg-inline-compare ncolor colored"
                                                                                aria-hidden="true"><svg
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    width="14" height="13"
                                                                                    viewBox="0 0 14 13"><path
                                                                                        data-name="Rounded Rectangle 913 copy"
                                                                                        class="cls-1"
                                                                                        d="M595,137a1,1,0,0,1,1,1v8a1,1,0,1,1-2,0v-8A1,1,0,0,1,595,137Zm-4,3a1,1,0,0,1,1,1v5a1,1,0,1,1-2,0v-5A1,1,0,0,1,591,140Zm8-6a1,1,0,0,1,1,1v11a1,1,0,1,1-2,0V135A1,1,0,0,1,599,134Zm4,6h0a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h0a1,1,0,0,1-1-1v-5A1,1,0,0,1,603,140Z"
                                                                                        transform="translate(-590 -134)"></path></svg></i></span>
                                                                </div>
                                                                <div class="wrapp_one_click">
                                                                                            <span class="rounded3 colored_theme_hover_bg one_click"
                                                                                                  data-item="346416"
                                                                                                  data-iblockid="42"
                                                                                                  data-quantity="1"
                                                                                                  onclick="oneClickBuy(&#39;346416&#39;, &#39;42&#39;, this)"
                                                                                                  title="Купить в 1 клик">
                                                                                        <i class="svg inline  svg-inline-fw ncolor colored"
                                                                                           aria-hidden="true"><svg
                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                    width="18"
                                                                                                    height="16"
                                                                                                    viewBox="0 0 18 16"><path
                                                                                                        data-name="Rounded Rectangle 941 copy 2"
                                                                                                        class="cls-1"
                                                                                                        d="M653,148H643a2,2,0,0,1-2-2v-3h2v3h10v-7h-1v2a1,1,0,1,1-2,0v-2H638a1,1,0,1,1,0-2h6v-1a4,4,0,0,1,8,0v1h1a2,2,0,0,1,2,2v7A2,2,0,0,1,653,148Zm-3-12a2,2,0,0,0-4,0v1h4v-1Zm-10,4h5a1,1,0,0,1,0,2h-5A1,1,0,0,1,640,140Z"
                                                                                                        transform="translate(-637 -132)"></path></svg></i>										</span>
                                                                </div>
                                                                <div class="fast_view_button">
                                                                <span title="Быстрый просмотр"
                                                                      class="rounded3 colored_theme_hover_bg"
                                                                      data-event="jqm" data-param-form_id="fast_view"
                                                                      data-param-iblock_id="42" data-param-id="346416"
                                                                      data-param-item_href="%2Fproduct%2Fpolirovalnyy_krug_dlya_abrazivnykh_past_voskov_serii_p1_p2_150_kh_25_mm_koch_chemie%2F"
                                                                      data-name="fast_view"><i
                                                                            class="svg inline  svg-inline-fw ncolor colored"
                                                                            aria-hidden="true"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="16" height="12"
                                                                                viewBox="0 0 16 12"><path
                                                                                    data-name="Ellipse 302 copy 3"
                                                                                    class="cls-1"
                                                                                    d="M549,146a8.546,8.546,0,0,1-8.008-6,8.344,8.344,0,0,1,16.016,0A8.547,8.547,0,0,1,549,146Zm0-2a6.591,6.591,0,0,0,5.967-4,7.022,7.022,0,0,0-1.141-1.76,4.977,4.977,0,0,1-9.652,0,7.053,7.053,0,0,0-1.142,1.76A6.591,6.591,0,0,0,549,144Zm-2.958-7.246c-0.007.084-.042,0.159-0.042,0.246a3,3,0,1,0,6,0c0-.087-0.035-0.162-0.042-0.246A6.179,6.179,0,0,0,546.042,136.753Z"
                                                                                    transform="translate(-541 -134)"></path></svg></i></span>
                                                                </div>
                                                            </div>
                                                            <a href="https://sappo.ru/product/polirovalnyy_krug_dlya_abrazivnykh_past_voskov_serii_p1_p2_150_kh_25_mm_koch_chemie/"
                                                               class="thumb shine">
                                                                                        <span class="section-gallery-wrapper flexbox">
                                                                                                            <span class="section-gallery-wrapper__item _active">
                                            <span class="section-gallery-wrapper__item-nav section-gallery-wrapper__item_hidden "></span>
                                                                                        <img class="img-responsive lazyloaded"
                                                                                             src="./home_files/bkesc3hwhe456ya28u7q749my2hdjit1.jpg"
                                                                                             data-src="/upload/iblock/56b/bkesc3hwhe456ya28u7q749my2hdjit1.jpg"
                                                                                             alt="Полировальный круг для абразивных паст восков серии P1, P2 Ø 150 х 25 мм, Koch Chemie"
                                                                                             title="Полировальный круг для абразивных паст восков серии P1, P2 Ø 150 х 25 мм, Koch Chemie">
                                                                                        </span>
                                                                                        </span>
                                                            </a>
                                                        </div>

                                                        <div class="item_info">
                                                            <div class="rating">
                                                                <!--'start_frame_cache_dv_346416'-->
                                                                <div class="votes_block nstar">
                                                                    <div class="ratings">
                                                                        <div class="inner_rating">
                                                                            <div class="item-rating" title="1"><i
                                                                                        class="svg inline  svg-inline-star"
                                                                                        aria-hidden="true">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                         width="15" height="13"
                                                                                         viewBox="0 0 15 13">
                                                                                        <rect class="sscls-1" width="15"
                                                                                              height="13"></rect>
                                                                                        <path data-name="Shape 921 copy 15"
                                                                                              class="sscls-2"
                                                                                              d="M1333.37,457.5l-4.21,2.408,0.11,0.346,2.07,4.745h-0.72l-4.12-3-4.09,3h-0.75l2.04-4.707,0.12-.395-4.19-2.4V457h5.12l1.53-5h0.38l1.57,5h5.14v0.5Z"
                                                                                              transform="translate(-1319 -452)"></path>
                                                                                    </svg>
                                                                                </i></div>
                                                                            <div class="item-rating" title="2"><i
                                                                                        class="svg inline  svg-inline-star"
                                                                                        aria-hidden="true">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                         width="15" height="13"
                                                                                         viewBox="0 0 15 13">
                                                                                        <rect class="sscls-1" width="15"
                                                                                              height="13"></rect>
                                                                                        <path data-name="Shape 921 copy 15"
                                                                                              class="sscls-2"
                                                                                              d="M1333.37,457.5l-4.21,2.408,0.11,0.346,2.07,4.745h-0.72l-4.12-3-4.09,3h-0.75l2.04-4.707,0.12-.395-4.19-2.4V457h5.12l1.53-5h0.38l1.57,5h5.14v0.5Z"
                                                                                              transform="translate(-1319 -452)"></path>
                                                                                    </svg>
                                                                                </i></div>
                                                                            <div class="item-rating" title="3"><i
                                                                                        class="svg inline  svg-inline-star"
                                                                                        aria-hidden="true">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                         width="15" height="13"
                                                                                         viewBox="0 0 15 13">
                                                                                        <rect class="sscls-1" width="15"
                                                                                              height="13"></rect>
                                                                                        <path data-name="Shape 921 copy 15"
                                                                                              class="sscls-2"
                                                                                              d="M1333.37,457.5l-4.21,2.408,0.11,0.346,2.07,4.745h-0.72l-4.12-3-4.09,3h-0.75l2.04-4.707,0.12-.395-4.19-2.4V457h5.12l1.53-5h0.38l1.57,5h5.14v0.5Z"
                                                                                              transform="translate(-1319 -452)"></path>
                                                                                    </svg>
                                                                                </i></div>
                                                                            <div class="item-rating" title="4"><i
                                                                                        class="svg inline  svg-inline-star"
                                                                                        aria-hidden="true">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                         width="15" height="13"
                                                                                         viewBox="0 0 15 13">
                                                                                        <rect class="sscls-1" width="15"
                                                                                              height="13"></rect>
                                                                                        <path data-name="Shape 921 copy 15"
                                                                                              class="sscls-2"
                                                                                              d="M1333.37,457.5l-4.21,2.408,0.11,0.346,2.07,4.745h-0.72l-4.12-3-4.09,3h-0.75l2.04-4.707,0.12-.395-4.19-2.4V457h5.12l1.53-5h0.38l1.57,5h5.14v0.5Z"
                                                                                              transform="translate(-1319 -452)"></path>
                                                                                    </svg>
                                                                                </i></div>
                                                                            <div class="item-rating" title="5"><i
                                                                                        class="svg inline  svg-inline-star"
                                                                                        aria-hidden="true">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                         width="15" height="13"
                                                                                         viewBox="0 0 15 13">
                                                                                        <rect class="sscls-1" width="15"
                                                                                              height="13"></rect>
                                                                                        <path data-name="Shape 921 copy 15"
                                                                                              class="sscls-2"
                                                                                              d="M1333.37,457.5l-4.21,2.408,0.11,0.346,2.07,4.745h-0.72l-4.12-3-4.09,3h-0.75l2.04-4.707,0.12-.395-4.19-2.4V457h5.12l1.53-5h0.38l1.57,5h5.14v0.5Z"
                                                                                              transform="translate(-1319 -452)"></path>
                                                                                    </svg>
                                                                                </i></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--'end_frame_cache_dv_346416'-->
                                                            </div>
                                                            <div class="item-title" style="height: 100px;">
                                                                <a href="https://sappo.ru/product/polirovalnyy_krug_dlya_abrazivnykh_past_voskov_serii_p1_p2_150_kh_25_mm_koch_chemie/"
                                                                   class="dark_link js-notice-block__title option-font-bold font_sm"><span>Полировальный круг для абразивных паст восков серии P1, P2 Ø 150 х 25 мм, Koch Chemie</span></a>
                                                            </div>
                                                            <div class="sa_block"
                                                                 data-fields="[&quot;&quot;,&quot;&quot;]"
                                                                 data-stores="[&quot;5&quot;]"
                                                                 data-user-fields="[&quot;&quot;,&quot;&quot;]"
                                                                 style="height: 24px;">
                                                                <div class="item-stock js-show-stores js-show-info-block "
                                                                     data-id="346416"><span
                                                                            class="icon stock"></span><span
                                                                            class="value font_sxs">В наличии</span>
                                                                </div>
                                                                <div class="article_block" data-name="Арт."
                                                                     data-value="9998338">
                                                                    <div class="muted font_sxs">Арт. : 9998338</div>
                                                                </div>
                                                            </div>
                                                            <div class="cost prices clearfix 123qwe">
                                                                <div class="icons-basket-wrapper offer_buy_block ce_cmp_hidden">
                                                                    <div class="button_block">
                                                                        <!--noindex-->
                                                                        <span data-value="1156" data-currency="RUB"
                                                                              class="btn-exlg to-cart btn btn-default transition_bg animate-load"
                                                                              data-item="346416" data-float_ratio=""
                                                                              data-ratio="1"
                                                                              data-bakset_div="bx_basket_div_346416"
                                                                              data-props="" data-part_props="N"
                                                                              data-add_props="Y" data-empty_props="Y"
                                                                              data-offers="" data-iblockid="42"
                                                                              data-quantity="1"><i
                                                                                    class="svg inline  svg-inline-fw ncolor colored"
                                                                                    aria-hidden="true"
                                                                                    title="В корзину"><svg
                                                                                        class="" width="19" height="16"
                                                                                        viewBox="0 0 19 16"><path
                                                                                            data-name="Ellipse 2 copy 9"
                                                                                            class="cls-1"
                                                                                            d="M956.047,952.005l-0.939,1.009-11.394-.008-0.952-1-0.953-6h-2.857a0.862,0.862,0,0,1-.952-1,1.025,1.025,0,0,1,1.164-1h2.327c0.3,0,.6.006,0.6,0.006a1.208,1.208,0,0,1,1.336.918L943.817,947h12.23L957,948v1Zm-11.916-3,0.349,2h10.007l0.593-2Zm1.863,5a3,3,0,1,1-3,3A3,3,0,0,1,945.994,954.005ZM946,958a1,1,0,1,0-1-1A1,1,0,0,0,946,958Zm7.011-4a3,3,0,1,1-3,3A3,3,0,0,1,953.011,954.005ZM953,958a1,1,0,1,0-1-1A1,1,0,0,0,953,958Z"
                                                                                            transform="translate(-938 -944)"></path></svg></i><span>В корзину</span></span><a
                                                                                rel="nofollow"
                                                                                href="https://sappo.ru/basket/"
                                                                                class="btn-exlg in-cart btn btn-default transition_bg"
                                                                                data-item="346416"
                                                                                style="display:none;"><i
                                                                                    class="svg inline  svg-inline-fw ncolor colored"
                                                                                    aria-hidden="true"
                                                                                    title="В корзине">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="19" height="18"
                                                                                     viewBox="0 0 19 18">
                                                                                    <path data-name="Rounded Rectangle 906 copy 3"
                                                                                          class="cls-1"
                                                                                          d="M1005.97,4556.22l-1.01,4.02a0.031,0.031,0,0,0-.01.02,0.87,0.87,0,0,1-.14.29,0.423,0.423,0,0,1-.05.07,0.7,0.7,0,0,1-.2.18,0.359,0.359,0,0,1-.1.07,0.656,0.656,0,0,1-.21.08,1.127,1.127,0,0,1-.18.03,0.185,0.185,0,0,1-.07.02H993c-0.03,0-.056-0.02-0.086-0.02a1.137,1.137,0,0,1-.184-0.04,0.779,0.779,0,0,1-.207-0.08c-0.031-.02-0.059-0.04-0.088-0.06a0.879,0.879,0,0,1-.223-0.22s-0.007-.01-0.011-0.01a1,1,0,0,1-.172-0.43l-1.541-6.14H988a1,1,0,1,1,0-2h3.188a0.3,0.3,0,0,1,.092.02,0.964,0.964,0,0,1,.923.76l1.561,6.22h9.447l0.82-3.25a1,1,0,0,1,1.21-.73A0.982,0.982,0,0,1,1005.97,4556.22Zm-7.267.47c0,0.01,0,.01,0,0.01a1,1,0,0,1-1.414,0l-2.016-2.03a0.982,0.982,0,0,1,0-1.4,1,1,0,0,1,1.414,0l1.305,1.31,4.3-4.3a1,1,0,0,1,1.41,0,1.008,1.008,0,0,1,0,1.42ZM995,4562a3,3,0,1,1-3,3A3,3,0,0,1,995,4562Zm0,4a1,1,0,1,0-1-1A1,1,0,0,0,995,4566Zm7-4a3,3,0,1,1-3,3A3,3,0,0,1,1002,4562Zm0,4a1,1,0,1,0-1-1A1,1,0,0,0,1002,4566Z"
                                                                                          transform="translate(-987 -4550)"></path>
                                                                                </svg>
                                                                            </i><span>В корзине</span></a>
                                                                        <!--/noindex-->
                                                                    </div>
                                                                    <div class="counter_block_inner">
                                                                        <div class="counter_block" data-item="346416">
                                                                        <span class="minus dark-color"
                                                                              id="bx_3966226736_346416_SALE_quant_down"><i
                                                                                    class="svg inline  svg-inline-wish ncolor colored1"
                                                                                    aria-hidden="true"><svg x="0px"
                                                                                                            y="0px"
                                                                                                            width="14px"
                                                                                                            height="2px"
                                                                                                            viewBox="0 0 14 2"
                                                                                                            style="enable-background:new 0 0 14 2;"
                                                                                                            xml:space="preserve"><path
                                                                                            d="M1.9,0.1h10.2C12.6,0.1,13,0.5,13,1l0,0c0,0.5-0.4,0.9-0.9,0.9H1.9C1.4,1.9,1,1.5,1,1l0,0C1,0.5,1.4,0.1,1.9,0.1z"></path></svg></i></span>
                                                                            <input type="text" class="text"
                                                                                   id="bx_3966226736_346416_SALE_quantity"
                                                                                   name="quantity" value="1">
                                                                            <span class="plus dark-color"
                                                                                  id="bx_3966226736_346416_SALE_quant_up"
                                                                                  data-max="5"><i
                                                                                        class="svg inline  svg-inline-wish ncolor colored1"
                                                                                        aria-hidden="true"><svg x="0px"
                                                                                                                y="0px"
                                                                                                                width="14px"
                                                                                                                height="14px"
                                                                                                                viewBox="0 0 14 14"><path
                                                                                                d="M7.9,6.1V1.9C7.9,1.4,7.5,1,7,1C6.5,1,6.1,1.4,6.1,1.9v4.3H1.9C1.4,6.1,1,6.5,1,7c0,0.5,0.4,0.9,0.9,0.9c0,0,0,0,0,0h4.3
        v4.3C6.1,12.6,6.5,13,7,13c0.5,0,0.9-0.4,0.9-0.9V7.9h4.3C12.6,7.9,13,7.5,13,7s-0.4-0.9-0.9-0.9C12.1,6.1,7.9,6.1,7.9,6.1z"></path></svg></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="price_group min">
                                                                    <div class="price_matrix_wrapper">
                                                                        <div class="prices-wrapper">
                                                                            <div class="price font-bold font_mlg font_mxs"
                                                                                 data-currency="" data-value="">
                                                                                                        <span class="values_wrapper">
                                                        <span class="price_value">1156</span>
                                                                                                        <span class="price_currency"> ₽</span>
                                                                                                        </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="sale_block">
                                                                            <div class="sale_wrapper font_xxs">
                                                                                <div class="sale-number rounded2">
                                                                                    <div class="value">-<span>15</span>%
                                                                                    </div>
                                                                                    <div class="inner-sale rounded1">
                                                                                        <div class="text">Экономия <span
                                                                                                    class="values_wrapper"><span
                                                                                                        class="price_value">204</span><span
                                                                                                        class="price_currency"> ₽</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="price discount" data-currency=""
                                                                                 data-value="">
                                                                                <span class="values_wrapper font_xs muted">1360 ₽</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="footer_button  inner_content js_offers__346416_SALE n-btn">
                                                            <div class="counter_wrapp clearfix offer_buy_block ce_cmp_visible">
                                                                <div class="button_block">
                                                                    <!--noindex-->
                                                                    <span data-value="1156" data-currency="RUB"
                                                                          class="btn-exlg to-cart btn btn-default transition_bg animate-load"
                                                                          data-item="346416" data-float_ratio=""
                                                                          data-ratio="1"
                                                                          data-bakset_div="bx_basket_div_346416"
                                                                          data-props="" data-part_props="N"
                                                                          data-add_props="Y" data-empty_props="Y"
                                                                          data-offers="" data-iblockid="42"
                                                                          data-quantity="1"><i
                                                                                class="svg inline  svg-inline-fw ncolor colored"
                                                                                aria-hidden="true" title="В корзину"><svg
                                                                                    class="" width="19" height="16"
                                                                                    viewBox="0 0 19 16"><path
                                                                                        data-name="Ellipse 2 copy 9"
                                                                                        class="cls-1"
                                                                                        d="M956.047,952.005l-0.939,1.009-11.394-.008-0.952-1-0.953-6h-2.857a0.862,0.862,0,0,1-.952-1,1.025,1.025,0,0,1,1.164-1h2.327c0.3,0,.6.006,0.6,0.006a1.208,1.208,0,0,1,1.336.918L943.817,947h12.23L957,948v1Zm-11.916-3,0.349,2h10.007l0.593-2Zm1.863,5a3,3,0,1,1-3,3A3,3,0,0,1,945.994,954.005ZM946,958a1,1,0,1,0-1-1A1,1,0,0,0,946,958Zm7.011-4a3,3,0,1,1-3,3A3,3,0,0,1,953.011,954.005ZM953,958a1,1,0,1,0-1-1A1,1,0,0,0,953,958Z"
                                                                                        transform="translate(-938 -944)"></path></svg></i><span>В корзину</span></span><a
                                                                            rel="nofollow"
                                                                            href="https://sappo.ru/basket/"
                                                                            class="btn-exlg in-cart btn btn-default transition_bg"
                                                                            data-item="346416" style="display:none;"><i
                                                                                class="svg inline  svg-inline-fw ncolor colored"
                                                                                aria-hidden="true" title="В корзине">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="19" height="18"
                                                                                 viewBox="0 0 19 18">
                                                                                <path data-name="Rounded Rectangle 906 copy 3"
                                                                                      class="cls-1"
                                                                                      d="M1005.97,4556.22l-1.01,4.02a0.031,0.031,0,0,0-.01.02,0.87,0.87,0,0,1-.14.29,0.423,0.423,0,0,1-.05.07,0.7,0.7,0,0,1-.2.18,0.359,0.359,0,0,1-.1.07,0.656,0.656,0,0,1-.21.08,1.127,1.127,0,0,1-.18.03,0.185,0.185,0,0,1-.07.02H993c-0.03,0-.056-0.02-0.086-0.02a1.137,1.137,0,0,1-.184-0.04,0.779,0.779,0,0,1-.207-0.08c-0.031-.02-0.059-0.04-0.088-0.06a0.879,0.879,0,0,1-.223-0.22s-0.007-.01-0.011-0.01a1,1,0,0,1-.172-0.43l-1.541-6.14H988a1,1,0,1,1,0-2h3.188a0.3,0.3,0,0,1,.092.02,0.964,0.964,0,0,1,.923.76l1.561,6.22h9.447l0.82-3.25a1,1,0,0,1,1.21-.73A0.982,0.982,0,0,1,1005.97,4556.22Zm-7.267.47c0,0.01,0,.01,0,0.01a1,1,0,0,1-1.414,0l-2.016-2.03a0.982,0.982,0,0,1,0-1.4,1,1,0,0,1,1.414,0l1.305,1.31,4.3-4.3a1,1,0,0,1,1.41,0,1.008,1.008,0,0,1,0,1.42ZM995,4562a3,3,0,1,1-3,3A3,3,0,0,1,995,4562Zm0,4a1,1,0,1,0-1-1A1,1,0,0,0,995,4566Zm7-4a3,3,0,1,1-3,3A3,3,0,0,1,1002,4562Zm0,4a1,1,0,1,0-1-1A1,1,0,0,0,1002,4566Z"
                                                                                      transform="translate(-987 -4550)"></path>
                                                                            </svg>
                                                                        </i><span>В корзине</span></a>
                                                                    <!--/noindex-->
                                                                </div>
                                                                <div class="counter_block_inner">
                                                                    <div class="counter_block " data-item="346416">
                                                                    <span class="minus dark-color"
                                                                          id="bx_3966226736_346416_SALE_quant_down"><i
                                                                                class="svg inline  svg-inline-wish ncolor colored1"
                                                                                aria-hidden="true"><svg x="0px" y="0px"
                                                                                                        width="14px"
                                                                                                        height="2px"
                                                                                                        viewBox="0 0 14 2"
                                                                                                        style="enable-background:new 0 0 14 2;"
                                                                                                        xml:space="preserve"><path
                                                                                        d="M1.9,0.1h10.2C12.6,0.1,13,0.5,13,1l0,0c0,0.5-0.4,0.9-0.9,0.9H1.9C1.4,1.9,1,1.5,1,1l0,0C1,0.5,1.4,0.1,1.9,0.1z"></path></svg></i></span>
                                                                        <input type="text" class="text"
                                                                               id="bx_3966226736_346416_SALE_quantity"
                                                                               name="quantity" value="1">
                                                                        <span class="plus dark-color"
                                                                              id="bx_3966226736_346416_SALE_quant_up"
                                                                              data-max="5"><i
                                                                                    class="svg inline  svg-inline-wish ncolor colored1"
                                                                                    aria-hidden="true"><svg x="0px"
                                                                                                            y="0px"
                                                                                                            width="14px"
                                                                                                            height="14px"
                                                                                                            viewBox="0 0 14 14"><path
                                                                                            d="M7.9,6.1V1.9C7.9,1.4,7.5,1,7,1C6.5,1,6.1,1.4,6.1,1.9v4.3H1.9C1.4,6.1,1,6.5,1,7c0,0.5,0.4,0.9,0.9,0.9c0,0,0,0,0,0h4.3
        v4.3C6.1,12.6,6.5,13,7,13c0.5,0,0.9-0.4,0.9-0.9V7.9h4.3C12.6,7.9,13,7.5,13,7s-0.4-0.9-0.9-0.9C12.1,6.1,7.9,6.1,7.9,6.1z"></path></svg></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="sku_props ce_cmp_hidden">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            */ ?>

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