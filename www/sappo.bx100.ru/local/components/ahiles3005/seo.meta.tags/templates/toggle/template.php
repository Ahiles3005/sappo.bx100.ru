<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if (!$arResult['ITEMS']) {
    return '';
}

?>

<div class="viewed_product_block <?= ($arTheme["SHOW_BG_BLOCK"]["VALUE"] == "Y" ? "fill" : "no_fill"); ?>">
    <div class="wrapper_inner">
        <div class="viewed-wrapper swipeignore main_horizontal">
            <!--            <h2 class="font_lg">Популярные производители</h2>-->
            <div class="brand-links-container">
                <div class="brand-links">
                    <?php
                    foreach ($arResult['ITEMS'] as $Item) {
                        echo "<a href='{$Item['URL']}' title='{$Item['TITLE']}'>{$Item['TITLE']}</a>";

                    }
                    ?>
                </div>

                <button class="toggle-button">
                    <svg viewBox="0 0 24 24" width="24" height="24" class="vverh">
                        <path d="M12 4l-8 8h16l-8-8z" fill="#42afe1" class="arrow-icon"/>
                    </svg>

                    <svg viewBox="0 0 24 24" width="24" height="24" class="vniz" style="display: none">
                        <path d="M12 20l8-8H4l8 8z" fill="#42afe1" class="arrow-icon"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>


<style>
    .brand-links-container {
        position: relative;
        overflow: hidden;
        max-height: 50px; /* Высота на одну линию */
        transition: max-height 0.3s ease;
    }

    .brand-links {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 10px;
    }

    .brand-links a {
        background-color: #eee;
        border-radius: 10px;
        padding: 10px;
        text-decoration: none;
        color: #000;
        transition: background-color 0.3s;
        line-height: 1.5;
        white-space: nowrap;
    }

    .toggle-button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        font-size: 16px;
        color: #000;
        position: absolute;
        right: 10px;
        top: 10px;
    }

    /*.toggle-btn {*/
    /*    background: none;*/
    /*    border: none;*/
    /*    cursor: pointer;*/
    /*    margin-left: auto;*/
    /*    display: flex;*/
    /*    align-items: center;*/
    /*    padding: 0 10px;*/
    /*    height: 100%; !* Matches the height of links *!*/
    /*}*/

    .arrow-icon {
        transition: transform 0.3s;
    }



</style>


<script>
    $('.toggle-button').click(function(){
        let container = $('.brand-links-container');

        if (container.css('max-height') === '50px') {
            container.css('max-height', '1000px'); // Полная высота, чтобы показать все элементы
            $(this).find('.vverh').hide();
            $(this).find('.vniz').show();
        } else {
            container.css('max-height', '50px'); // Возвращаем на высоту одной линии
            $(this).find('.vverh').show();
            $(this).find('.vniz').hide();
        }
    });


</script>