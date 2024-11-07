<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(!$arResult['ITEMS']) {
    return '' ;
}

?>

<div class="viewed_product_block <?= ($arTheme["SHOW_BG_BLOCK"]["VALUE"] == "Y" ? "fill" : "no_fill"); ?>">
    <div class="wrapper_inner">
        <div class="viewed-wrapper swipeignore main_horizontal">
            <h2 class="font_lg">Популярные производители</h2>
            <div class="brand-links" >
                <?php
                foreach ($arResult['ITEMS'] as $Item) {
                    echo "<a href='{$Item['URL']}' title='{$Item['TITLE']}'>{$Item['TITLE']}</a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>


<style>
    .brand-links {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap; /* Позволяет элементам переноситься на новую строку, если они не помещаются в одну строку */
        gap: 10px; /* Небольшой отступ между элементами */
    }

    .brand-links a {
        background-color: #eee; /* Серый цвет фона */
        border-radius: 10px; /* Закругленные углы */
        padding: 10px; /* Внутренний отступ */
        text-decoration: none; /* Убираем подчеркивание */
        color: #000; /* Цвет текста */
        transition: background-color 0.3s; /* Плавный переход цвета фона */
        line-height: 1.5; /* Высота строки */
        white-space: nowrap; /* Предотвращает перенос текста на новую строку */
    }

    .brand-links a:hover {
        background-color: #27a4dd;; /* Цвет фона при наведении */
        color: white;
    }

</style>

