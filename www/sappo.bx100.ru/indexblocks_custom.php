<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $arMainPageOrder = [
    'BIG_BANNER_INDEX',
//      'STORIES',
    'CATALOG_TAB',
    'CATALOG_SECTIONS',
    'FLOAT_BANNERS',
    'NEWS',
    'BRANDS',
    'COMPANY_TEXT',
]; ?>
<? global $arTheme, $dopBodyClass; ?>
<? if ($arMainPageOrder && is_array($arMainPageOrder)): ?>


    <? foreach ($arMainPageOrder as $key => $optionCode): ?>
        <? $strTemplateName = 'type_custom'; ?>
        <? $subtype = strtolower($optionCode); ?>

        <? $dopBodyClass .= ' ' . $optionCode . '_' . $strTemplateName; ?>

        <? //BIG_BANNER_INDEX?>
        <? if ($optionCode == "BIG_BANNER_INDEX"): ?>
            <? global $bShowBigBanners, $bBigBannersIndexClass; ?>
            <? if ($bShowBigBanners): ?>
                <? $bIndexLongBigBanner = ($strTemplateName != "type_1" && $strTemplateName != "type_4") ?>
                <? if (!$bIndexLongBigBanner): ?>
                    <? $dopBodyClass .= ' right_mainpage_banner'; ?>
                <? endif; ?>

                <? if ($bIndexLongBigBanner): ?>
                    <? ob_start(); ?>
                    <div class="middle">
                <? endif; ?>

                <div class="hm-banners--div__WRAPPER drag-block grey container <?= $optionCode ?> <?= $bBigBannersIndexClass ?>"
                     data-class="<?= $subtype ?>_drag" data-order="<?= ++$key; ?>">
                    <div class="maxwidth-theme hm-banners--div__MAXWIDTH">

                        <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName); ?>
                    </div>
                </div>

                <? if ($bIndexLongBigBanner): ?>
                    </div>
                    <? $html = ob_get_contents();
                    ob_end_clean(); ?>
                    <? $APPLICATION->AddViewContent('front_top_big_banner', $html); ?>
                <? endif; ?>
            <? endif; ?>
        <? endif; ?>

        <? //STORIES?>
        <? if ($optionCode == "STORIES"): ?>
            <? global $bShowStories, $bStoriesIndexClass; ?>
            <? if ($bShowStories): ?>
                <div class="drag-block container <?= $optionCode ?> <?= $bStoriesIndexClass; ?>"
                     data-class="<?= $subtype ?>_drag" data-order="<?= ++$key; ?>">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true); ?>
                </div>
            <? endif; ?>
        <? endif; ?>

        <? //CATALOG_SECTIONS?>
        <? if ($optionCode == "CATALOG_SECTIONS"): ?>
            <? global $bShowCatalogSections, $bCatalogSectionsIndexClass; ?>
            <? if ($bShowCatalogSections): ?>
                <div class="drag-block container <?= $optionCode ?> <?= $bCatalogSectionsIndexClass; ?> js-load-block loader_circle"
                     data-class="<?= $subtype ?>_drag" data-order="<?= ++$key; ?>"
                     data-file="<?= SITE_DIR; ?>include/mainpage/components/<?= $subtype; ?>/<?= $strTemplateName; ?>.php">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName); ?>
                </div>
            <? endif; ?>
        <? endif; ?>

        <? //CATALOG_TAB?>
        <? if ($optionCode == "CATALOG_TAB"): ?>
            <? global $bShowCatalogTab, $bCatalogTabIndexClass; ?>
            <? if ($bShowCatalogTab): ?>
                <div class="drag-block container grey <?= $optionCode ?> <?= $bCatalogTabIndexClass; ?> js-load-block loader_circle"
                     data-class="<?= $subtype ?>_drag" data-order="<?= ++$key; ?>"
                     data-file="<?= SITE_DIR; ?>include/mainpage/components/<?= $subtype; ?>/<?= $strTemplateName; ?>.php">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true); ?>
                </div>
            <? endif; ?>
        <? endif; ?>

        <? //FLOAT_BANNERS?>
        <? if ($optionCode == "FLOAT_BANNERS"): ?>
            <? global $bShowFloatBanners, $bFloatBannersIndexClass; ?>
            <? if ($bShowFloatBanners): ?>
                <div class="drag-block container <?= $optionCode ?> <?= $bFloatBannersIndexClass; ?>"
                     data-class="<?= $subtype ?>_drag" data-order="<?= ++$key; ?>">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName); ?>
                </div>
            <? endif; ?>
        <? endif; ?>

        <? //NEWS?>
        <? if ($optionCode == "NEWS"): ?>
            <? global $bShowNews, $bNewsIndexClass; ?>
            <? if ($bShowNews): ?>
                <div class="drag-block container grey <?= $optionCode ?> <?= $bNewsIndexClass; ?>"
                     data-class="<?= $subtype ?>_drag" data-order="<?= ++$key; ?>">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true); ?>
                </div>
            <? endif; ?>
        <? endif; ?>

        <? //COMPANY_TEXT?>
        <? if ($optionCode == "COMPANY_TEXT"): ?>
            <? global $bShowCompany, $bCompanyTextIndexClass; ?>
            <? if ($bShowCompany): ?>
                <div class="drag-block container <?= $optionCode ?> <?= $bCompanyTextIndexClass; ?>"
                     data-class="<?= $subtype ?>_drag" data-order="<?= ++$key; ?>">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName); ?>
                </div>
            <? endif; ?>
        <? endif; ?>

        <? //BRANDS?>
        <? if ($optionCode == "BRANDS"): ?>
            <? global $bShowBrands, $bBrandsIndexClass; ?>
            <? if ($bShowBrands): ?>
                <div class="drag-block container <?= $optionCode ?> <?= $bBrandsIndexClass; ?>"
                     data-class="<?= $subtype ?>_drag" data-order="<?= ++$key; ?>">
                    <?= CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true); ?>
                </div>
            <? endif; ?>
        <? endif; ?>
    <? endforeach; ?>
<? endif; ?>