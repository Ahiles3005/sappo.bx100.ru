<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true);

$asset = \Bitrix\Main\Page\Asset::getInstance();


?>

<? if ($arResult['ITEMS']): ?>
    <div class="hm-banners--div__SWIPER1 swiper">
        <div class="hm-banners--div__SWIPER1_WRAPPER swiper-wrapper">
            <? foreach ($arResult["ITEMS"][$arParams["BANNER_TYPE_THEME"]]["ITEMS"] as $i => $arItem): ?>
                <?
                if ($arItem['PROPERTIES']['ONLY_SALE']['VALUE'] == 'Y') continue;
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);

                if (is_array($arItem["DETAIL_PICTURE"])) {
                    $pc = $arItem["DETAIL_PICTURE"]["SRC"];
                    $file = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], [
                        'width' => 343,
                        'height' => 208
                    ], BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    $mobile = $file['src'];
                } else {
                    $pc = $this->GetFolder() . "/images/background.jpg";
                    $mobile = $pc;
                }
                $target = $arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"];
                ?>
                <div class="hm-banners--div__SWIPER1_SLIDE swiper-slide"
                     id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <a class="hm-banners--a__MAIN"
                       href="<?= $arItem["PROPERTIES"]["URL_STRING"]["VALUE"] ?>" <?= (strlen($target) ? 'target="' . $target . '"' : '') ?>>
                        <img class="hm-banners--img__MAIN" data-typesrc="mobile" src="<?= $mobile ?>" alt="" >
                        <img class="hm-banners--img__MAIN" data-typesrc="pc" src="<?= $pc ?>" alt="">

                    </a>
                </div>
            <? endforeach; ?>
        </div>
        <div class="hm-banners--div__SWIPER1_PAG swiper-pagination"></div>

        <button class="hm-banners--button__SWIPER1_PREV">
            <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.65004 13.6666L8.83337 12.4833L3.35004 6.99992L8.83337 1.51659L7.65004 0.333252L0.983374 6.99992L7.65004 13.6666Z"
                      fill="#121212"/>
            </svg>
        </button>
        <button class="hm-banners--button__SWIPER1_NEXT">
            <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.34996 13.6663L0.166626 12.483L5.64996 6.99967L0.166626 1.51634L1.34996 0.333008L8.01663 6.99967L1.34996 13.6663Z"
                      fill="#121212"/>
            </svg>
        </button>
    </div>
<? endif; ?>


