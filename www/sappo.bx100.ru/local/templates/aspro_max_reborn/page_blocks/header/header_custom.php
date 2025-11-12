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

$currentRegion = $_COOKIE["current_region"];
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


                <!-- Месседжеры для связи -->
				<?if ($currentRegion == 2243): ?>
					<a class="c-header--button__PHONE" href="https://t.me/sappomsk">
						<svg class="c-header--svg__TG" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M7.75 0.25C12.0312 0.25 15.5 3.71875 15.5 8C15.5 12.2812 12.0312 15.75 7.75 15.75C3.46875 15.75 0 12.2812 0 8C0 3.71875 3.46875 0.25 7.75 0.25ZM11.5312 5.5625C11.6562 5.0625 11.3438 4.84375 11 4.96875L3.53125 7.84375C3.03125 8.03125 3.03125 8.34375 3.4375 8.46875L5.34375 9.0625L9.78125 6.25C10 6.125 10.1875 6.21875 10.0312 6.34375L6.4375 9.59375L6.3125 11.5625C6.5 11.5625 6.59375 11.4688 6.6875 11.375L7.625 10.4688L9.5625 11.9062C9.9375 12.0938 10.1875 12 10.2812 11.5625L11.5312 5.5625Z" fill="#999999"/>
						</svg>
					</a>
					<a class="c-header--button__PHONE" href="https://wa.me/79626853555">
						<svg class="c-header--svg__WA" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12.6568 11.2969C12.464 11.8426 11.6976 12.2941 11.0864 12.4262C10.668 12.515 10.1224 12.5853 8.284 11.8234C6.2192 10.968 3.352 7.92078 3.352 5.89297C3.352 4.86066 3.9472 3.65859 4.988 3.65859C5.4888 3.65859 5.5992 3.66836 5.764 4.06367C5.9568 4.52942 6.4272 5.6769 6.4832 5.79453C6.7144 6.27708 6.24799 6.55956 5.9096 6.97969C5.80159 7.10612 5.6792 7.24286 5.816 7.47813C5.952 7.7086 6.4224 8.47524 7.1136 9.09062C8.0064 9.88607 8.7304 10.14 8.9896 10.248C9.1824 10.3281 9.4128 10.3094 9.5536 10.159C9.732 9.96613 9.9536 9.64616 10.1792 9.33086C10.3384 9.10519 10.5408 9.07701 10.7528 9.15703C10.896 9.20665 12.716 10.0519 12.7928 10.1871C12.8496 10.2855 12.8496 10.7511 12.6568 11.2969ZM8.0016 0H7.9976C3.5872 0 0 3.58828 0 8C0 9.74933 0.564006 11.3723 1.52321 12.6887L0.526404 15.6613L3.60081 14.6789C4.86561 15.516 6.3752 16 8.0016 16C12.412 16 16 12.4117 16 8C16 3.58828 12.412 0 8.0016 0Z" fill="#999999"/>
						</svg>
					</a>
				<?else: ?>
					<a class="c-header--button__PHONE" href="https://t.me/SappoShop">
						<svg class="c-header--svg__TG" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M7.75 0.25C12.0312 0.25 15.5 3.71875 15.5 8C15.5 12.2812 12.0312 15.75 7.75 15.75C3.46875 15.75 0 12.2812 0 8C0 3.71875 3.46875 0.25 7.75 0.25ZM11.5312 5.5625C11.6562 5.0625 11.3438 4.84375 11 4.96875L3.53125 7.84375C3.03125 8.03125 3.03125 8.34375 3.4375 8.46875L5.34375 9.0625L9.78125 6.25C10 6.125 10.1875 6.21875 10.0312 6.34375L6.4375 9.59375L6.3125 11.5625C6.5 11.5625 6.59375 11.4688 6.6875 11.375L7.625 10.4688L9.5625 11.9062C9.9375 12.0938 10.1875 12 10.2812 11.5625L11.5312 5.5625Z" fill="#999999"/>
						</svg>
					</a>
					<a class="c-header--button__PHONE" href="https://wa.me/79626852595">
						<svg class="c-header--svg__WA" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12.6568 11.2969C12.464 11.8426 11.6976 12.2941 11.0864 12.4262C10.668 12.515 10.1224 12.5853 8.284 11.8234C6.2192 10.968 3.352 7.92078 3.352 5.89297C3.352 4.86066 3.9472 3.65859 4.988 3.65859C5.4888 3.65859 5.5992 3.66836 5.764 4.06367C5.9568 4.52942 6.4272 5.6769 6.4832 5.79453C6.7144 6.27708 6.24799 6.55956 5.9096 6.97969C5.80159 7.10612 5.6792 7.24286 5.816 7.47813C5.952 7.7086 6.4224 8.47524 7.1136 9.09062C8.0064 9.88607 8.7304 10.14 8.9896 10.248C9.1824 10.3281 9.4128 10.3094 9.5536 10.159C9.732 9.96613 9.9536 9.64616 10.1792 9.33086C10.3384 9.10519 10.5408 9.07701 10.7528 9.15703C10.896 9.20665 12.716 10.0519 12.7928 10.1871C12.8496 10.2855 12.8496 10.7511 12.6568 11.2969ZM8.0016 0H7.9976C3.5872 0 0 3.58828 0 8C0 9.74933 0.564006 11.3723 1.52321 12.6887L0.526404 15.6613L3.60081 14.6789C4.86561 15.516 6.3752 16 8.0016 16C12.412 16 16 12.4117 16 8C16 3.58828 12.412 0 8.0016 0Z" fill="#999999"/>
						</svg>
					</a>
				<?endif; ?>
            </div>

            <div class="c-header--div__TOP_ITEM">
                <? $APPLICATION->IncludeFile(SITE_DIR . "include/header_include/header_right_menu.php", [], [
                        "MODE" => "php",
                        "NAME" => "header_right_menu",
                        "TEMPLATE" => "include_area.php",
                    ]
                ); ?>
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