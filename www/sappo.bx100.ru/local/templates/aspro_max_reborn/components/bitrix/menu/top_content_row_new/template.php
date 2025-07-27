<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (!empty($arResult)) { ?>
    <ul class="menu topest initied">
        <? foreach ($arResult as $key => $arItem) { ?>
            <li <? if ($arItem["SELECTED"]): ?> class="current"<? endif ?>>
                <a class="c-header--a__MENU has-ripple" href="<?= $arItem["LINK"] ?>">
                    <? if (isset($arItem["PARAMS"]["ICON"]) && $arItem["PARAMS"]["ICON"]): ?>
                        <?= CMax::showIconSvg($arItem["PARAMS"]["ICON"], SITE_TEMPLATE_PATH . '/images/svg/' . $arItem["PARAMS"]["ICON"] . '.svg', '', ''); ?>
                    <? endif; ?>
                    <span><?= $arItem["TEXT"] ?></span>
                </a>
            </li>

        <? } ?>
        <li class="more hidden">
            <span>...</span>
            <ul class="dropdown"></ul>
        </li>
    </ul>
    <script data-skip-moving="true">
        InitTopestMenuGummi();
        CheckTopMenuPadding();
        CheckTopMenuOncePadding();
        CheckTopMenuDotted();
    </script>
<? } ?>