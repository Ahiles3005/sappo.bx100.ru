<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>


<div class="c-footerMob--div__MENU_ITEM">
    <p class="c-footerMob--p__TITLE">
                                <span>
                                    Каталог
                                </span>
        <svg class="c-footerMob--svg__TITLE" width="24" height="24" viewBox="0 0 24 24" fill="none"
             xmlns="http://www.w3.org/2000/svg">
            <path d="M12 15.4L6 9.4L7.4 8L12 12.6L16.6 8L18 9.4L12 15.4Z" fill="white"/>
        </svg>
    </p>
    <ul class="c-footerMob--ul">

        <? foreach ($arResult["SECTIONS"] as $arSection): ?>
            <? if ($arSection["DEPTH_LEVEL"] == 1) : ?>
                <li class="c-footerMob--li">
                    <a class="c-footerMob--a__MENU" href="<?= $arSection['SECTION_PAGE_URL'] ?>">
                        <span><?= $arSection['NAME'] ?></span>
                        <span><?= $arSection['ELEMENT_CNT'] ?></span>
                    </a>
                </li>
            <? endif; ?>

        <? endforeach ?>
    </ul>
</div>