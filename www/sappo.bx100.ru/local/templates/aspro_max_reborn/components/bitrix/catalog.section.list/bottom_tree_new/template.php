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

$strTitle = "";
$countColumn = 0;
foreach ($arResult["SECTIONS"] as $arSection) {
    if ($arSection["DEPTH_LEVEL"] == 1) {
        $countColumn++;
    }
}
$countColumn = $countColumn / 2;


foreach ($arResult["SECTIONS"] as $section) {
    $section['CHILDREN'] = [];
    $depth = intval($section['DEPTH_LEVEL']);
    if ($depth == 1) {
        $tree[] = $section;
        $parents[$depth] = &$tree[count($tree) - 1];
    } else {
        $parents[$depth - 1]['CHILDREN'][] = $section;
        $parents[$depth] = &$parents[$depth - 1]['CHILDREN'][count($parents[$depth - 1]['CHILDREN']) - 1];
    }
}
$count = 0;
foreach ($tree as $k => $section) {

    if ($count == 0) {
        echo '<div class="c-footerDesc--div__RIGHT_COL">';
    }
    $count++;

    echo '<div class="c-footerDesc--div__RIGHT_COL">';
    ?>
    <a class="c-footerDesc--a__BLOCK_TITLE" href="<?= $section['SECTION_PAGE_URL'] ?>">
                                    <span>
                                        <?= $section['NAME'] ?>
                                    </span>
        <span>
                                        <?= $section['ELEMENT_CNT'] ?>
                                    </span>
    </a>

    <?php
    if (!empty($section['CHILDREN'])) {
        echo '<ul class="c-footerDesc--ul">';

        foreach ($section['CHILDREN'] as $childer) {
            ?>

            <li class="c-footerDesc--li">
                <a class="c-footerDesc--a__MENU" href="<?= $childer['SECTION_PAGE_URL'] ?>">
                    <span>        <?= $childer['NAME'] ?></span>
                    <span>        <?= $childer['ELEMENT_CNT'] ?></span>
                </a>
            </li>

            <?php
        }
        echo '</ul>';
    }
    echo '</div>';

    if ($count >= $countColumn || $k == count($tree) - 1) {
        echo '</div>';
        $count = 0;
    }
}
