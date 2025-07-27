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

$arBackParametrs = CMax::GetBackParametrsValues(SITE_ID);
$phone = ($arRegion ? $arRegion['PHONES'][0]['PHONE'] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_0'])
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
                    <span> <?=$phone ?></span>
<!--                    <svg class="c-header--svg__PHONE2" xmlns="http://www.w3.org/2000/svg" width="5" height="3" viewBox="0 0 5 3">-->
<!--                        <path class="cls-1" d="M250,80h5l-2.5,3Z" transform="translate(-250 -80)"></path>-->
<!--                    </svg>-->
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
<!-- Центральная строка -->
<div class="header-wrapper header-v17">
    <div class="logo_and_menu-row longs fixed c-header--div__FIXED">
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

                        <div class="pull-right c-header--div__ENTER">
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
                                       href="https://sappo.ru/auth/">
                                        <i class="svg inline big svg-inline-cabinet" aria-hidden="true"
                                           title="Мой кабинет">
                                            <svg width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.5002 18H2.46924C1.99228 17.9968 1.52634 17.8565 1.12696 17.5957C0.727572 17.335 0.411611 16.9648 0.2168 16.5294C0.0219904 16.094 -0.04345 15.6118 0.0282629 15.1403C0.0999758 14.6687 0.305788 14.2278 0.621219 13.87C0.639219 13.844 0.64724 13.818 0.66724 13.793C2.04124 12.093 5.14325 11.003 7.50025 11.003H8.50025C10.8572 11.003 13.9593 12.092 15.3333 13.793C15.3533 13.818 15.3613 13.844 15.3793 13.87C15.7772 14.32 15.9979 14.8993 16.0002 15.5C16.0002 16.163 15.7368 16.7989 15.268 17.2678C14.7992 17.7366 14.1633 18 13.5002 18ZM14.0002 15.467C13.9135 15.1002 13.6927 14.7792 13.3812 14.567C11.9137 13.6386 10.2339 13.0993 8.50025 13H7.50025C5.76975 13.1006 4.09323 13.6394 2.62824 14.566C2.31612 14.7775 2.09511 15.0989 2.00922 15.466C2.00922 15.476 2.00922 15.49 2.00922 15.499C2.00922 15.6316 2.06194 15.7588 2.1557 15.8525C2.24947 15.9463 2.37661 15.999 2.50922 15.999H13.5092C13.6418 15.999 13.769 15.9463 13.8628 15.8525C13.9566 15.7588 14.0092 15.6316 14.0092 15.499C14.0002 15.491 14.0002 15.477 14.0002 15.467ZM8.00025 10C7.01134 10 6.04466 9.70676 5.22242 9.15735C4.40017 8.60794 3.7593 7.82702 3.38086 6.91339C3.00242 5.99976 2.90339 4.99444 3.09632 4.02454C3.28924 3.05463 3.76546 2.16374 4.46473 1.46448C5.16399 0.765217 6.05488 0.288998 7.02478 0.0960714C7.99469 -0.0968547 9.00001 0.00217915 9.91364 0.380617C10.8273 0.759055 11.6082 1.39992 12.1576 2.22217C12.707 3.04442 13.0002 4.0111 13.0002 5C13.0002 6.32608 12.4735 7.59784 11.5358 8.53552C10.5981 9.47321 9.32633 10 8.00025 10ZM8.00025 2C7.4069 2 6.82691 2.17597 6.33356 2.50562C5.84021 2.83526 5.45564 3.30375 5.22858 3.85193C5.00152 4.40011 4.94211 5.00332 5.05787 5.58527C5.17362 6.16721 5.45935 6.70178 5.87891 7.12134C6.29847 7.5409 6.83304 7.82663 7.41498 7.94238C7.99692 8.05814 8.60014 7.99873 9.14832 7.77167C9.6965 7.54461 10.165 7.16004 10.4946 6.66669C10.8243 6.17334 11.0002 5.59335 11.0002 5C11.0002 4.20435 10.6842 3.44127 10.1216 2.87866C9.55898 2.31606 8.7959 2 8.00025 2Z"
                                                      fill="white"/>
                                            </svg>
                                        </i>
                                        <span class="wrap"><span class="name">Войти</span></span>
                                    </a>
                                <?php } ?>
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


                    </div>
                </div>

            </div>
        </div><? // class=logo-row?>
    </div>
</div>
<!-- Нижняя строка -->
<div class="top-block top-block-v1 header-v16">
    <div class="maxwidth-theme">
        <div class="wrapp_block">
            <div class="row">
                <div class="items-wrapper flexbox flexbox--row justify-content-between c-header--div__BOTTOM_CONT">
                    <div class="menus initied">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                            [
                                "COMPONENT_TEMPLATE" => ".default",
                                "PATH" => SITE_DIR . "include/menu/menu.topest3.php",
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "include_area.php"
                            ],
                            false
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>