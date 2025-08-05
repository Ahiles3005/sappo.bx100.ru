<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? use \Bitrix\Main\Localization\Loc; ?>
<? global $arTheme; ?>

<?php

$class = '';
if ($arParams['IBLOCK_ID'] == 36) {
    $class = 'item-views news2 lg with-border normal hm-news--div__BODY __hm-news--div__BODY __opac';

}

if ($arParams['IBLOCK_ID'] == 33) {
    $class = 'item-views news2 articles lg with-border normal hm-news--div__BODY';
}
?>




<? if ($arResult['ITEMS']): ?>

    <div class="<?= $class ?>">
        <div class="items s_4">
            <div class="row flexbox normal swipeignore mobile-overflow mobile-margin-16 mobile-compact">
                <? foreach ($arResult['ITEMS'] as $i => $arItem): ?>

                    <?

                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), ['CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);


                    $imageSrc = ($arItem['FIELDS']['PREVIEW_PICTURE'] ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : '');
                    $bImage = ($imageSrc ? true : false);
                    $noImageSrc = SITE_TEMPLATE_PATH . '/images/svg/noimage_content.svg';


                    $bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
                    ?>

                    <div class="item-wrapper col-md-4 col-lg-3 col-sm-6 col-xs-6 col-xxs-12 clearfix item-width-261"
                         data-ref="mixitup-target">
                        <div class="item bg-white bordered box-shadow rounded3 clearfix"
                             id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                            <div class="image shine">
                                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                                                <span class="bg-fon-img set-position top left lazyloaded"
                                                                      data-src="<?= $imageSrc ?>"
                                                                      style="background-image:url(<?= \Aspro\Functions\CAsproMax::showBlankImg($imageSrc); ?>)"></span>

                                </a>

                            </div>

                            <div class="inner-text with-date">
                                <div class="title">
                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                        <?= $arItem['NAME']; ?>
                                    </a>
                                </div>
                                <? if ($arItem['IBLOCK_SECTION_ID'] && $arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]): ?>
                                    <a class="hm-news--a__HASH"
                                       href="<?= $arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['SECTION_PAGE_URL'] ?>">#<?= ltrim($arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME'],'#'); ?></a>
                                <? endif; ?>
                                <div class="period-block muted font_xs">
                                    <? if (strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])): ?>
                                        <span class="date"><?= $arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'] ?></span>
                                    <? else: ?>
                                        <span class="date"><?= $arItem['DISPLAY_ACTIVE_FROM'] ?></span>
                                    <? endif; ?>
                                </div>
                            </div>


                        </div>
                    </div>


                <? endforeach; ?>
            </div>
        </div>
        <div class="bottom_nav_wrapper hidden-slider-nav">
            <div class="bottom_nav animate-load-state" data-parent=".item-views"
                 data-scroll-class=".swipeignore.mobile-overflow"
                 data-append=".items &gt; .row"></div>
        </div>

        <a class="hm-news--a__ALL c-common--a__ALL has-ripple"
           href="<?= SITE_DIR . $arParams['ALL_URL'] ?>">
            <?= $arParams['TITLE_BLOCK_ALL'] ?>
        </a>
    </div>

<? endif; ?>




