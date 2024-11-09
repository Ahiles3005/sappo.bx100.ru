<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if (!$arResult['ITEMS']) {
    return '';
}

/*<div class="viewed_product_block <?= ($arTheme["SHOW_BG_BLOCK"]["VALUE"] == "Y" ? "fill" : "no_fill"); ?>"*/
//     style="padding-bottom: 10px">
//    <div class="wrapper_inner">
?>

<div class=""
     style="padding-bottom: 10px">
    <div class="">
        <div class="viewed-wrapper swipeignore main_horizontal">
            <div class="brand-links-container">
                <div class="brand-links">
                    <?php
                    foreach ($arResult['ITEMS'] as $Item) {
                        echo "<a href='{$Item['URL']}' title='{$Item['TITLE']}'>{$Item['TITLE']}</a>";

                    }
                    ?>
                </div>
            </div>
            <div class="toggle-arrow-container">
                <div class="brand-toggle-arrow toggle-arrow arrow-down down" style="display: none;"></div>
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
    $(document).ready(function () {
        $height = $('.brand-links').height();
        if ($height > 50) {
            $('.brand-toggle-arrow').css('display', 'flex');
        }

        $('.toggle-arrow').click(function () {
            let container = $('.brand-links-container');

            if (container.css('max-height') === '50px') {
                container.css('max-height', '1000px'); // Полная высота, чтобы показать все элементы
                $(this).removeClass('down').addClass('up');
            } else {
                container.css('max-height', '50px'); // Возвращаем на высоту одной линии
                $(this).removeClass('up').addClass('down');
            }
        });
    })
</script>