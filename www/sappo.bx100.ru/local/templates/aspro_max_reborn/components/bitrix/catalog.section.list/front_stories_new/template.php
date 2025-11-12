<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true);

?>
<? if ($arResult['SECTIONS']): ?>

    <div class="hm-banners--div__SWIPER2 swiper">
        <div class="hm-banners--div__SWIPER2_WRAPPER swiper-wrapper">
            <? foreach ($arResult['SECTIONS'] as $arSection):
                if ($arParams["COUNT_ELEMENTS"] && !$arSection['ELEMENT_CNT']) {
                    continue;
                }

                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')]); ?>
                <div class="hm-banners--div__SWIPER2_SLIDE swiper-slide"
                     id="<?= $this->GetEditAreaId($arSection['ID']); ?>">

                    <a class="hm-banners--a__2" href="<?= $arSection['CHILDS'][0] ?>">
                        <img class="hm-banners--img__2" src="<?= $arSection["PICTURE"]["SRC"] ?>"
                             alt="<?= $arSection['NAME']; ?>">
                    </a>
                </div>
            <? endforeach; ?>
        </div>

        <button class="hm-banners--button__SWIPER2_PREV">
            <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.65004 13.6666L8.83337 12.4833L3.35004 6.99992L8.83337 1.51659L7.65004 0.333252L0.983374 6.99992L7.65004 13.6666Z"
                      fill="#121212"/>
            </svg>
        </button>
        <button class="hm-banners--button__SWIPER2_NEXT">
            <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.34996 13.6663L0.166626 12.483L5.64996 6.99967L0.166626 1.51634L1.34996 0.333008L8.01663 6.99967L1.34996 13.6663Z"
                      fill="#121212"/>
            </svg>
        </button>
    </div>

<? endif; ?>