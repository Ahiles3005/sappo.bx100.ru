<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $arTheme, $arRegion, $bLongHeader, $dopClass;
$arRegions = CMaxRegionality::getRegions();
if ($arRegion) {

    $bPhone = ($arRegion['PHONES'] ? true : false);
} else {

    $bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
}
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader = true;
$dopClass .= ' high_one_row_header';
?>


<!-- Верхняя строка -->
<div class="c-header--div__TOP">
    <div class="maxwidth-theme">
        <div class="c-header--div__TOP_WRAP">
            <div class="c-header--div__TOP_ITEM">
                <!-- Выбор города -->
                <? if ($arRegions): ?>
                    <div class="top-block-item">
                        <div class="top-description no-title">
                            <? \Aspro\Functions\CAsproMax::showRegionList(); ?>
                        </div>
                    </div>
                <? endif; ?>


                <!-- Телефон для связи -->
                <button class="c-header--button__PHONE">
                    <svg class="c-header--svg__PHONE1" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.3 12C9.91111 12 8.53889 11.6972 7.18333 11.0917C5.82778 10.4861 4.59444 9.62778 3.48333 8.51667C2.37222 7.40556 1.51389 6.17222 0.908333 4.81667C0.302778 3.46111 0 2.08889 0 0.7C0 0.5 0.0666667 0.333333 0.2 0.2C0.333333 0.0666667 0.5 0 0.7 0H3.4C3.55556 0 3.69444 0.0527778 3.81667 0.158333C3.93889 0.263889 4.01111 0.388889 4.03333 0.533333L4.46667 2.86667C4.48889 3.04444 4.48333 3.19444 4.45 3.31667C4.41667 3.43889 4.35556 3.54444 4.26667 3.63333L2.65 5.26667C2.87222 5.67778 3.13611 6.075 3.44167 6.45833C3.74722 6.84167 4.08333 7.21111 4.45 7.56667C4.79444 7.91111 5.15556 8.23056 5.53333 8.525C5.91111 8.81944 6.31111 9.08889 6.73333 9.33333L8.3 7.76667C8.4 7.66667 8.53056 7.59167 8.69167 7.54167C8.85278 7.49167 9.01111 7.47778 9.16667 7.5L11.4667 7.96667C11.6222 8.01111 11.75 8.09167 11.85 8.20833C11.95 8.325 12 8.45556 12 8.6V11.3C12 11.5 11.9333 11.6667 11.8 11.8C11.6667 11.9333 11.5 12 11.3 12ZM2.01667 4L3.11667 2.9L2.83333 1.33333H1.35C1.40556 1.78889 1.48333 2.23889 1.58333 2.68333C1.68333 3.12778 1.82778 3.56667 2.01667 4ZM7.98333 9.96667C8.41667 10.1556 8.85833 10.3056 9.30833 10.4167C9.75833 10.5278 10.2111 10.6 10.6667 10.6333V9.16667L9.1 8.85L7.98333 9.96667Z" fill="#121212"/>
                    </svg>
                    <span>8 499 350-11-04</span>
                    <svg class="c-header--svg__PHONE2" xmlns="http://www.w3.org/2000/svg" width="5" height="3" viewBox="0 0 5 3">
                        <path class="cls-1" d="M250,80h5l-2.5,3Z" transform="translate(-250 -80)"></path>
                    </svg>
                </button>
            </div>

            <div class="c-header--div__TOP_ITEM">
                <a class="c-header--a__TOP" href="#">
                    Детейлинг-центр
                </a>
                <a class="c-header--a__TOP" href="#">
                    Детейлинг-школа
                </a>
            </div>
        </div>
    </div>
</div>

<div class="header-wrapper header-v17">
    <div class="logo_and_menu-row longs">
        <div class="logo-row paddings">
            <div class="maxwidth-theme">
                <div class="row">
                    <div class="col-md-12">
                        <div class="logo-block pull-left floated">
                            <div class="logo<?= $logoClass ?>">
                                <?= CMax::ShowLogo(); ?>
                            </div>
                        </div>

                        <div class="pull-left menu_fixed">
                            <div class="menu-row">
                                <div class="menu-only">
                                    <nav class="mega-menu">
                                        <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                            [
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "PATH" => SITE_DIR . "include/menu/menu.only_catalog.php",
                                                "AREA_FILE_SHOW" => "file",
                                                "AREA_FILE_SUFFIX" => "",
                                                "AREA_FILE_RECURSIVE" => "Y",
                                                "EDIT_TEMPLATE" => "include_area.php"
                                            ],
                                            false, ["HIDE_ICONS" => "Y"]
                                        ); ?>
                                    </nav>
                                </div>
                            </div>
                        </div>

                        <div class="right-icons1 pull-right wb">
                            <div class="pull-right longest">
                                <?= CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets'); ?>
                            </div>
                        </div>

                        <div class="search_wrap pull-left">
                            <div class="search-block inner-table-block">
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    [
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR . "include/top_page/search.title.catalog.php",
                                        "EDIT_TEMPLATE" => "include_area.php",
                                        'SEARCH_ICON' => 'Y',
                                    ]
                                ); ?>
                            </div>
                        </div>
                        <div class="top-block-item phone pull-right">
                            <div class="phone-block">
                                <? if ($bPhone): ?>
                                    <div class="inline-block">
                                        <? CMax::ShowHeaderPhones('no-icons'); ?>
                                    </div>
                                <? endif ?>
                                <? $callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
                                if (in_array('HEADER', $callbackExploded)):?>
                                    <div class="inline-block">
                                        <span class="callback-block animate-load font_upper_xs colored" data-event="jqm"
                                              data-param-form_id="CALLBACK"
                                              data-name="callback"><?= GetMessage("CALLBACK") ?></span>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div><? // class=logo-row?>
    </div>
</div>
<div class="top-block top-block-v1 header-v16">
    <div class="maxwidth-theme">
        <div class="wrapp_block">
            <div class="row">
                <div class="items-wrapper flexbox flexbox--row justify-content-between">



                    <div class="menus">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                            [
                                "COMPONENT_TEMPLATE" => ".default",
                                "PATH" => SITE_DIR . "include/menu/menu.topest2.php",
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "include_area.php"
                            ],
                            false
                        ); ?>
                    </div>
                    <div class="right-icons top-block-item logo_and_menu-row showed">
                        <div class="pull-right">
                            <div class="sappo-header-sbonus">

                                <?
                                \Bitrix\Main\Loader::includeModule('kilbil.bonus');
                                $APPLICATION->IncludeComponent(
                                    'kilbil.bonus:bonuses.client',
                                    '',
                                    [
                                        'USER_PHONE' => '' // номер телефона пользователя
                                    ],
                                );
                                if (\App\User::isAuth()) { ?>


                                    <div class="wrap_icon inner-table-block1 person">
                                        <?= CMax::showCabinetLink(true, true, 'big'); ?>
                                    </div>

                                <?php } else { ?>
                                    <a title="Мой кабинет" class="personal-link dark-color animate-load"
                                       href="/auth/"><i class="svg inline big svg-inline-cabinet" aria-hidden="true"
                                                        title="Мой кабинет">
                                            <svg class="" width="18" height="18" viewBox="0 0 18 18">
                                                <path data-name="Ellipse 206 copy 4" class="cls-1"
                                                      d="M909,961a9,9,0,1,1,9-9A9,9,0,0,1,909,961Zm2.571-2.5a6.825,6.825,0,0,0-5.126,0A6.825,6.825,0,0,0,911.571,958.5ZM909,945a6.973,6.973,0,0,0-4.556,12.275,8.787,8.787,0,0,1,9.114,0A6.973,6.973,0,0,0,909,945Zm0,10a4,4,0,1,1,4-4A4,4,0,0,1,909,955Zm0-6a2,2,0,1,0,2,2A2,2,0,0,0,909,949Z"
                                                      transform="translate(-900 -943)"></path>
                                            </svg>
                                        </i><span class="wrap"><span class="name">Войти</span></span></a>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>