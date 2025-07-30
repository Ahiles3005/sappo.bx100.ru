<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$this->setFrameMode(true);
if (is_array($arResult)) {
    $firstElement = array_shift($arResult);
}
?>
<? if ($arResult): ?>
    <div class="c-footerMob--div__MENU_ITEM">


        <p class="c-footerMob--p__TITLE">
            <span> <?= $firstElement['TEXT'] ?> </span>
            <svg class="c-footerMob--svg__TITLE" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15.4L6 9.4L7.4 8L12 12.6L16.6 8L18 9.4L12 15.4Z" fill="white"/>
            </svg>
        </p>

        <ul class="c-footerMob--ul">
            <? foreach ($arResult as $i => $arItem): ?>
                <li class="c-footerMob--li">
                    <a class="c-footerMob--a__MENU" href="<?= $arItem['LINK'] ?>">
                        <span><?= $arItem['TEXT'] ?></span>
                        <span></span>
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endif; ?>



