<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$this->setFrameMode(true);
if (is_array($arResult)) {
    $firstElement = array_shift($arResult);
}
?>
<? if ($arResult): ?>
    <div class="c-footerDesc--div__BLOCK">
        <a class="c-footerDesc--a__BLOCK_TITLE dis">
            <span> <?= $firstElement['TEXT'] ?></span>
            <span></span>
        </a>
        <ul class="c-footerDesc--ul">
            <? foreach ($arResult as $i => $arItem): ?>
                <li class="c-footerDesc--li">
                    <a class="c-footerDesc--a__MENU" href="<?= $arItem['LINK'] ?>">
                        <span> <?= $arItem['TEXT'] ?> </span>
                        <span></span>
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endif; ?>

