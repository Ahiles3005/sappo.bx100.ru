<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<?


?>
<? if ($arResult['ITEMS']): ?>


    <div class="content_wrapper_block front_brands_slider">
        <div class="maxwidth-theme only-on-front">
            <div class="hm-brands--div__SWIPER1 swiper">
                <div class="hm-brands--div__SWIPER1_WRAPPER swiper-wrapper">
                    <? foreach ($arResult["ITEMS"] as $arItem) { ?>
                        <?
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                        ?>
                        <? if (is_array($arItem["PREVIEW_PICTURE"])) { ?>

                            <div class="hm-brands--div__SWIPER1_SLIDE swiper-slide"
                                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                                <a class="hm-brands--a__SWIPER1" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                                    <img class="hm-brands--img__SWIPER1 lazy"
                                         data-src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                         src="<?= \Aspro\Functions\CAsproMax::showBlankImg($arItem["PREVIEW_PICTURE"]["SRC"]); ?>"
                                         alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>"/>
                                </a>
                            </div>

                        <? } ?>
                    <? } ?>
                </div>
                <button class="hm-brands--button__SWIPER1_PREV">
                    <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.65004 13.6666L8.83337 12.4833L3.35004 6.99992L8.83337 1.51659L7.65004 0.333252L0.983374 6.99992L7.65004 13.6666Z"
                              fill="#121212"/>
                    </svg>
                </button>
                <button class="hm-brands--button__SWIPER1_NEXT">
                    <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.34996 13.6663L0.166626 12.483L5.64996 6.99967L0.166626 1.51634L1.34996 0.333008L8.01663 6.99967L1.34996 13.6663Z"
                              fill="#121212"/>
                    </svg>
                </button>
            </div>
            <div class="hm-brands--div__SWIPER2 swiper">
                <div class="hm-brands--div__SWIPER2_WRAPPER swiper-wrapper">
                    <? foreach ($arResult["ITEMS"] as $arItem) { ?>
                        <?
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                        ?>
                        <? if (is_array($arItem["PREVIEW_PICTURE"])) { ?>

                            <div class="hm-brands--div__SWIPER2_SLIDE swiper-slide"
                                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                                <a class="hm-brands--a__SWIPER2" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                                    <img class="hm-brands--img__SWIPER2 lazy"
                                         data-src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                         src="<?= \Aspro\Functions\CAsproMax::showBlankImg($arItem["PREVIEW_PICTURE"]["SRC"]); ?>"
                                         alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>"/>
                                </a>
                            </div>

                        <? } ?>
                    <? } ?>
                </div>
                <button class="hm-brands--button__SWIPER2_PREV">
                    <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.65004 13.6666L8.83337 12.4833L3.35004 6.99992L8.83337 1.51659L7.65004 0.333252L0.983374 6.99992L7.65004 13.6666Z"
                              fill="#121212"/>
                    </svg>
                </button>
                <button class="hm-brands--button__SWIPER2_NEXT">
                    <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.34996 13.6663L0.166626 12.483L5.64996 6.99967L0.166626 1.51634L1.34996 0.333008L8.01663 6.99967L1.34996 13.6663Z"
                              fill="#121212"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
<? endif; ?>








