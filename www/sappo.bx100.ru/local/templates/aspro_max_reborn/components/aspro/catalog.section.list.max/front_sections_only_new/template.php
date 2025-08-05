<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if ($arResult['SECTIONS']): ?>
    <?
    global $arTheme;

    $nResizeWH = 140;
    ?>


    <div class="content_wrapper_block front_sections_only">
        <div class="maxwidth-theme c-common--div__MAXWIDTH">
            <div class="sections_wrapper type1 icons normal hm-popular--div__CONT">

                <div class="top_block">
                    <h3><?= $arParams["TITLE_BLOCK"]; ?></h3>
                    <a href="" class="pull-right font_upper muted"><?= $arParams["TITLE_BLOCK_ALL"]; ?></a>
                </div>

                <div class="hm-popular--div__GRID">
                    <? foreach ($arResult['SECTIONS'] as $arSection):
                        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]); ?>

                        <div class="hm-popular--div__ITEM" id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
                            <a class="hm-popular--a__CIRCLE" href="<?= $arSection['SECTION_PAGE_URL']; ?>">
                                <? if ($arSection["PICTURE"]["SRC"]): ?>
                                    <? $img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], [
                                        "width" => $nResizeWH,
                                        "height" => $nResizeWH
                                    ], BX_RESIZE_IMAGE_EXACT, true); ?>
                                    <img class="hm-popular--img__ITEM"
                                         src="<?= $img["src"] ?>"
                                         alt="<?= ($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"]
                                             : $arSection["NAME"]) ?>" title="<?= ($arSection["PICTURE"]["TITLE"] ?
                                        $arSection["PICTURE"]["TITLE"] : $arSection["NAME"]) ?>"/>

                                <? elseif ($arSection["~PICTURE"]): ?>
                                    <? $img = CFile::ResizeImageGet($arSection["~PICTURE"], [
                                        "width" => $nResizeWH,
                                        "height" => $nResizeWH
                                    ], BX_RESIZE_IMAGE_EXACT, true); ?>

                                    <img class="hm-popular--img__ITEM"
                                         src="<?= $img["src"] ?>"
                                         alt="<?= ($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"]) ?>"
                                         title="<?= ($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"]) ?>"/>

                                <? else: ?>

                                    <img class="hm-popular--img__ITEM"

                                         src="<?= SITE_TEMPLATE_PATH . '/images/svg/noimage_product.svg'; ?>"
                                         alt="<?= $arSection["NAME"] ?>"
                                         title="<?= $arSection["NAME"] ?>"
                                         height="90"/>

                                <? endif; ?>
                            </a>
                            <a class="hm-popular--a__NAME" href="<?= $arSection['SECTION_PAGE_URL']; ?>">
                                <?= $arSection['NAME']; ?>
                            </a>

                        </div>

                    <? endforeach; ?>


                </div>


                <a class="hm-popular--a__ALL c-common--a__ALL has-ripple"
                   href="<?= SITE_DIR . $arParams["ALL_URL"]; ?>">
                    Смотреть все
                </a>


            </div>
        </div>
    </div>

<? endif; ?>