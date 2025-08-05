<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $arMainPageOrder = [
    'BIG_BANNER_INDEX',
//      'STORIES',
    'CATALOG_TAB',
    'CATALOG_SECTIONS',
    'FLOAT_BANNERS',
    'NEWS',
    'INFO',
    'BRANDS',
]; ?>
<? global $arTheme, $dopBodyClass; ?>
<? if ($arMainPageOrder && is_array($arMainPageOrder)): ?>

    <? ob_start(); ?>
    <div class="middle">
        <? foreach ($arMainPageOrder as $key => $optionCode): ?>
            <? $strTemplateName = 'type_custom'; ?>
            <? $subtype = strtolower($optionCode); ?>

            <? $dopBodyClass .= ' ' . $optionCode . '_' . $strTemplateName; ?>

            <? //BIG_BANNER_INDEX?>
            <? if ($optionCode == "BIG_BANNER_INDEX"): ?>


                <div class="drag-block grey container BIG_BANNER_INDEX hm-banners--div__WRAPPER"
                     data-class="big_banner_index_drag" data-order="<?= ++$key; ?>">
                    <div class="maxwidth-theme hm-banners--div__MAXWIDTH">
                        <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName); ?>
                        <?= CMax::ShowPageType('mainpage', strtolower('STORIES'), $strTemplateName); ?>
                    </div>
                </div>


            <? endif; ?>

            <? //CATALOG_TAB?>
            <? if ($optionCode == "CATALOG_TAB"): ?>
                <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true); ?>
            <? endif; ?>


            <? //CATALOG_SECTIONS?>
            <? if ($optionCode == "CATALOG_SECTIONS"): ?>
                <div class="drag-block container CATALOG_SECTIONS hm-popular"
                     data-class="catalog_sections_drag" data-order="6">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName); ?>
                </div>

            <? endif; ?>


            <? //FLOAT_BANNERS?>
            <? if ($optionCode == "FLOAT_BANNERS"): ?>
                <div class="drag-block container FLOAT_BANNERS hm-resources" data-class="float_banners_drag"
                     data-order="7">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName); ?>
                </div>
            <? endif; ?>


            <? //NEWS?>
            <? if ($optionCode == "NEWS"): ?>
                <div class="drag-block container grey NEWS hm-news" data-class="news_drag" data-order="8">
                    <div class="content_wrapper_block front_news content_news2 without-border">
                        <div class="maxwidth-theme c-common--div__MAXWIDTH only-on-front">
                            <div class="top_block clearfix hm-news--div__TOP_CONT">
                                <div class="hm-news--div__TOP __hm-news--div__TOP">
                                    <h3 class="pull-left hm-news--h3__TOP">Новости</h3>
                                    <span class="hm-news--span__TOP"><?= CIBlockElement::GetList([], [
                                            "IBLOCK_ID" => 36,
                                            "ACTIVE" => "Y"
                                        ])->SelectedRowsCount() ?></span>
                                </div>
                                <div class="hm-news--div__TOP">
                                    <h3 class="pull-left hm-news--h3__TOP">Статьи</h3>

                                    <span class="hm-news--span__TOP"><?= CIBlockElement::GetList([], [
                                            "IBLOCK_ID" => 33,
                                            "ACTIVE" => "Y"
                                        ])->SelectedRowsCount() ?></span>
                                </div>
                            </div>

                            <div class="hm-news--div__BODY_CONT">

                                <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true); ?>

                            </div>
                        </div>
                    </div>
                </div>
            <? endif; ?>

            <? if ($optionCode == "INFO"): ?>

                <div class="hm-info drag-block container" data-class="info_drag" data-order="9">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName); ?>
                </div>

            <? endif; ?>

            <? //BRANDS?>
            <? if ($optionCode == "BRANDS"): ?>


                <div class="drag-block container BRANDS hm-brands" data-class="brands_drag" data-order="10">

                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true); ?>
                </div>

            <? endif; ?>
        <? endforeach; ?>
    </div>
    <? $html = ob_get_contents();
    ob_end_clean(); ?>
    <? $APPLICATION->AddViewContent('index_blocks', $html); ?>
<? endif; ?>