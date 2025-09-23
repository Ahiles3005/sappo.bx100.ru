<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $arTheme;
global $arRegion;
$arBackParametrs = CMax::GetBackParametrsValues(SITE_ID);
$phone = ($arRegion ? $arRegion['PHONES'][0]['PHONE'] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_0']);
$phonePLus = str_replace(array(' ', ',', '-', '(', ')'), '', $phone);
?>

<div class="c-common--div__MAXWIDTH maxwidth-theme">
    <div class="c-footerMob">
        <img class="c-footer--img__LOGO" src="/include/footer/img/c-footer_logo.svg" alt="Логотип">

        <div class="c-footer--div__SOCIALS">


            <? $APPLICATION->IncludeComponent(
                "aspro:social.info.max",
                "sappo_footer_new",
                [
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600000",
                    "CACHE_GROUPS" => "N",
                    "COMPONENT_TEMPLATE" => "sappo_footer_new",
                    "TITLE_BLOCK" => ""
                ],
                false
            ); ?>

        </div>


        <div class="c-footer--div__CONTACTS">
            <!-- Москва и область -->
            <a class="c-footer--a__PHONE" href="tel:+<?= $phonePLus ?>">
                <svg class="c-footer--svg__PHONE1" width="24" height="24" viewBox="0 0 12 12" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.3 12C9.91111 12 8.53889 11.6972 7.18333 11.0917C5.82778 10.4861 4.59444 9.62778 3.48333 8.51667C2.37222 7.40556 1.51389 6.17222 0.908333 4.81667C0.302778 3.46111 0 2.08889 0 0.7C0 0.5 0.0666667 0.333333 0.2 0.2C0.333333 0.0666667 0.5 0 0.7 0H3.4C3.55556 0 3.69444 0.0527778 3.81667 0.158333C3.93889 0.263889 4.01111 0.388889 4.03333 0.533333L4.46667 2.86667C4.48889 3.04444 4.48333 3.19444 4.45 3.31667C4.41667 3.43889 4.35556 3.54444 4.26667 3.63333L2.65 5.26667C2.87222 5.67778 3.13611 6.075 3.44167 6.45833C3.74722 6.84167 4.08333 7.21111 4.45 7.56667C4.79444 7.91111 5.15556 8.23056 5.53333 8.525C5.91111 8.81944 6.31111 9.08889 6.73333 9.33333L8.3 7.76667C8.4 7.66667 8.53056 7.59167 8.69167 7.54167C8.85278 7.49167 9.01111 7.47778 9.16667 7.5L11.4667 7.96667C11.6222 8.01111 11.75 8.09167 11.85 8.20833C11.95 8.325 12 8.45556 12 8.6V11.3C12 11.5 11.9333 11.6667 11.8 11.8C11.6667 11.9333 11.5 12 11.3 12ZM2.01667 4L3.11667 2.9L2.83333 1.33333H1.35C1.40556 1.78889 1.48333 2.23889 1.58333 2.68333C1.68333 3.12778 1.82778 3.56667 2.01667 4ZM7.98333 9.96667C8.41667 10.1556 8.85833 10.3056 9.30833 10.4167C9.75833 10.5278 10.2111 10.6 10.6667 10.6333V9.16667L9.1 8.85L7.98333 9.96667Z"
                          fill="#fff"></path>
                </svg>
                <span><?= $phone ?></span>
            </a>

            <? if ($arRegion): ?>
                <? if ($arRegion['PROPERTY_EMAIL_VALUE']): ?>
                    <? foreach ($arRegion['PROPERTY_EMAIL_VALUE'] as $value): ?>
                        <a class="c-footer--a__MAIL" href="mailto:<?= $value ?>">
                            <svg class="c-footer--svg__MAIL1" width="17" height="14" viewBox="0 0 17 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.5 13.6663V0.333008L16.3333 6.99967L0.5 13.6663ZM2.16667 11.1663L12.0417 6.99967L2.16667 2.83301V5.74967L7.16667 6.99967L2.16667 8.24967V11.1663Z"
                                      fill="white"/>
                            </svg>
                            <span><?= $value ?></span>
                        </a>
                    <? endforeach; ?>
                <? endif; ?>
            <? endif; ?>


            <p class="c-footer--p__CONTACTS">
                <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/text_1.php", [], [
                        "MODE" => "php",
                        "NAME" => "text_1",
                        "TEMPLATE" => "include_area.php",
                    ]
                ); ?>
            </p>


            <div class="c-footer--div__GEO">
                <a class="c-footer--a__GEO SPB" href="#" data-region="2242">
                    <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.99984 6.99967C6.3665 6.99967 6.68039 6.86912 6.9415 6.60801C7.20261 6.3469 7.33317 6.03301 7.33317 5.66634C7.33317 5.29967 7.20261 4.98579 6.9415 4.72467C6.68039 4.46356 6.3665 4.33301 5.99984 4.33301C5.63317 4.33301 5.31928 4.46356 5.05817 4.72467C4.79706 4.98579 4.6665 5.29967 4.6665 5.66634C4.6665 6.03301 4.79706 6.3469 5.05817 6.60801C5.31928 6.86912 5.63317 6.99967 5.99984 6.99967ZM5.99984 11.8997C7.35539 10.6552 8.36095 9.52468 9.0165 8.50801C9.67206 7.49134 9.99984 6.58856 9.99984 5.79967C9.99984 4.58856 9.61373 3.5969 8.8415 2.82467C8.06928 2.05245 7.12206 1.66634 5.99984 1.66634C4.87761 1.66634 3.93039 2.05245 3.15817 2.82467C2.38595 3.5969 1.99984 4.58856 1.99984 5.79967C1.99984 6.58856 2.32761 7.49134 2.98317 8.50801C3.63873 9.52468 4.64428 10.6552 5.99984 11.8997ZM5.99984 13.6663C4.21095 12.1441 2.87484 10.7302 1.9915 9.42467C1.10817 8.11912 0.666504 6.91079 0.666504 5.79967C0.666504 4.13301 1.20261 2.80523 2.27484 1.81634C3.34706 0.827452 4.58873 0.333008 5.99984 0.333008C7.41095 0.333008 8.65261 0.827452 9.72484 1.81634C10.7971 2.80523 11.3332 4.13301 11.3332 5.79967C11.3332 6.91079 10.8915 8.11912 10.0082 9.42467C9.12484 10.7302 7.78873 12.1441 5.99984 13.6663Z"
                              fill="#121212"/>
                    </svg>
                    <span>Санкт-Петербург</span>
                </a>
                <a class="c-footer--a__GEO MSK" href="#" data-region="2243">
                    <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.99984 6.99967C6.3665 6.99967 6.68039 6.86912 6.9415 6.60801C7.20261 6.3469 7.33317 6.03301 7.33317 5.66634C7.33317 5.29967 7.20261 4.98579 6.9415 4.72467C6.68039 4.46356 6.3665 4.33301 5.99984 4.33301C5.63317 4.33301 5.31928 4.46356 5.05817 4.72467C4.79706 4.98579 4.6665 5.29967 4.6665 5.66634C4.6665 6.03301 4.79706 6.3469 5.05817 6.60801C5.31928 6.86912 5.63317 6.99967 5.99984 6.99967ZM5.99984 11.8997C7.35539 10.6552 8.36095 9.52468 9.0165 8.50801C9.67206 7.49134 9.99984 6.58856 9.99984 5.79967C9.99984 4.58856 9.61373 3.5969 8.8415 2.82467C8.06928 2.05245 7.12206 1.66634 5.99984 1.66634C4.87761 1.66634 3.93039 2.05245 3.15817 2.82467C2.38595 3.5969 1.99984 4.58856 1.99984 5.79967C1.99984 6.58856 2.32761 7.49134 2.98317 8.50801C3.63873 9.52468 4.64428 10.6552 5.99984 11.8997ZM5.99984 13.6663C4.21095 12.1441 2.87484 10.7302 1.9915 9.42467C1.10817 8.11912 0.666504 6.91079 0.666504 5.79967C0.666504 4.13301 1.20261 2.80523 2.27484 1.81634C3.34706 0.827452 4.58873 0.333008 5.99984 0.333008C7.41095 0.333008 8.65261 0.827452 9.72484 1.81634C10.7971 2.80523 11.3332 4.13301 11.3332 5.79967C11.3332 6.91079 10.8915 8.11912 10.0082 9.42467C9.12484 10.7302 7.78873 12.1441 5.99984 13.6663Z"
                              fill="#121212"/>
                    </svg>
                    <span>Москва</span>
                </a>
            </div>
        </div>


        <div class="c-footerMob--div__MENU">


            <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/menu/new_bottom_mobile_1.php", [], [
                    "MODE" => "php",
                    "NAME" => "new_bottom_mobile_3_1",
                    "TEMPLATE" => "include_area.php",
                ]
            ); ?>


            <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/menu/new_bottom_mobile_3_1.php", [], [
                    "MODE" => "php",
                    "NAME" => "new_bottom_mobile_3_1",
                    "TEMPLATE" => "include_area.php",
                ]
            ); ?>

            <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/menu/new_bottom_mobile_3_2.php", [], [
                    "MODE" => "php",
                    "NAME" => "new_bottom_mobile_3_2",
                    "TEMPLATE" => "include_area.php",
                ]
            ); ?>

            <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/menu/new_bottom_mobile_3_3.php", [], [
                    "MODE" => "php",
                    "NAME" => "new_bottom_mobile_3_3",
                    "TEMPLATE" => "include_area.php",
                ]
            ); ?>



        </div>


        <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/five_stars.php", [], [
                "MODE" => "php",
                "NAME" => "five_stars",
                "TEMPLATE" => "include_area.php",
            ]
        ); ?>


        <div class="c-footer--div__BOTTOM">
                        <span class="c-footer--span__BOTTOM">

                            <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/confidentiality.php", [], [
                                    "MODE" => "php",
                                    "NAME" => "onfidentiality",
                                    "TEMPLATE" => "include_area.php",
                                ]
                            ); ?>
                        </span>
            <span class="c-footer--span__BOTTOM">
                <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/copy/copyright.php", [], [
                        "MODE" => "php",
                        "NAME" => "Copyright",
                        "TEMPLATE" => "include_area.php",
                    ]
                ); ?>
                        </span>
        </div>
    </div>

    <div class="c-footerDesc">
        <div class="c-footerDesc--div__LEFT">
            <div class="c-footerDesc--div__LEFT_TOP">
                <img class="c-footer--img__LOGO" src="/include/footer/img/c-footer_logo.svg" alt="Логотип">
                <div class="c-footer--div__SOCIALS">


                    <? $APPLICATION->IncludeComponent(
                        "aspro:social.info.max",
                        "sappo_footer_new",
                        [
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600000",
                            "CACHE_GROUPS" => "N",
                            "COMPONENT_TEMPLATE" => "sappo_footer_new",
                            "TITLE_BLOCK" => ""
                        ],
                        false
                    ); ?>

                </div>
                <div class="c-footer--div__CONTACTS">
                    <a class="c-footer--a__PHONE" href="tel:+<?= $phonePLus ?>">
                        <svg class="c-footer--svg__PHONE1" width="24" height="24" viewBox="0 0 12 12" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.3 12C9.91111 12 8.53889 11.6972 7.18333 11.0917C5.82778 10.4861 4.59444 9.62778 3.48333 8.51667C2.37222 7.40556 1.51389 6.17222 0.908333 4.81667C0.302778 3.46111 0 2.08889 0 0.7C0 0.5 0.0666667 0.333333 0.2 0.2C0.333333 0.0666667 0.5 0 0.7 0H3.4C3.55556 0 3.69444 0.0527778 3.81667 0.158333C3.93889 0.263889 4.01111 0.388889 4.03333 0.533333L4.46667 2.86667C4.48889 3.04444 4.48333 3.19444 4.45 3.31667C4.41667 3.43889 4.35556 3.54444 4.26667 3.63333L2.65 5.26667C2.87222 5.67778 3.13611 6.075 3.44167 6.45833C3.74722 6.84167 4.08333 7.21111 4.45 7.56667C4.79444 7.91111 5.15556 8.23056 5.53333 8.525C5.91111 8.81944 6.31111 9.08889 6.73333 9.33333L8.3 7.76667C8.4 7.66667 8.53056 7.59167 8.69167 7.54167C8.85278 7.49167 9.01111 7.47778 9.16667 7.5L11.4667 7.96667C11.6222 8.01111 11.75 8.09167 11.85 8.20833C11.95 8.325 12 8.45556 12 8.6V11.3C12 11.5 11.9333 11.6667 11.8 11.8C11.6667 11.9333 11.5 12 11.3 12ZM2.01667 4L3.11667 2.9L2.83333 1.33333H1.35C1.40556 1.78889 1.48333 2.23889 1.58333 2.68333C1.68333 3.12778 1.82778 3.56667 2.01667 4ZM7.98333 9.96667C8.41667 10.1556 8.85833 10.3056 9.30833 10.4167C9.75833 10.5278 10.2111 10.6 10.6667 10.6333V9.16667L9.1 8.85L7.98333 9.96667Z"
                                  fill="#fff"></path>
                        </svg>
                        <span><?= $phone ?></span>
                    </a>


                    <? if ($arRegion): ?>
                        <? if ($arRegion['PROPERTY_EMAIL_VALUE']): ?>
                            <? foreach ($arRegion['PROPERTY_EMAIL_VALUE'] as $value): ?>
                                <a class="c-footer--a__MAIL" href="mailto:<?= $value ?>">
                                    <svg class="c-footer--svg__MAIL1" width="17" height="14" viewBox="0 0 17 14"
                                         fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.5 13.6663V0.333008L16.3333 6.99967L0.5 13.6663ZM2.16667 11.1663L12.0417 6.99967L2.16667 2.83301V5.74967L7.16667 6.99967L2.16667 8.24967V11.1663Z"
                                              fill="white"/>
                                    </svg>
                                    <span><?= $value ?></span>
                                </a>
                            <? endforeach; ?>
                        <? endif; ?>
                    <? endif; ?>

                    <p class="c-footer--p__CONTACTS">
                        <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/text_1.php", [], [
                                "MODE" => "php",
                                "NAME" => "text_1",
                                "TEMPLATE" => "include_area.php",
                            ]
                        ); ?>
                    </p>


                    <div class="c-footer--div__GEO">
                        <a class="c-footer--a__GEO SPB" href="#" data-region="2242">
                            <svg width="12" height="14" viewBox="0 0 12 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.99984 6.99967C6.3665 6.99967 6.68039 6.86912 6.9415 6.60801C7.20261 6.3469 7.33317 6.03301 7.33317 5.66634C7.33317 5.29967 7.20261 4.98579 6.9415 4.72467C6.68039 4.46356 6.3665 4.33301 5.99984 4.33301C5.63317 4.33301 5.31928 4.46356 5.05817 4.72467C4.79706 4.98579 4.6665 5.29967 4.6665 5.66634C4.6665 6.03301 4.79706 6.3469 5.05817 6.60801C5.31928 6.86912 5.63317 6.99967 5.99984 6.99967ZM5.99984 11.8997C7.35539 10.6552 8.36095 9.52468 9.0165 8.50801C9.67206 7.49134 9.99984 6.58856 9.99984 5.79967C9.99984 4.58856 9.61373 3.5969 8.8415 2.82467C8.06928 2.05245 7.12206 1.66634 5.99984 1.66634C4.87761 1.66634 3.93039 2.05245 3.15817 2.82467C2.38595 3.5969 1.99984 4.58856 1.99984 5.79967C1.99984 6.58856 2.32761 7.49134 2.98317 8.50801C3.63873 9.52468 4.64428 10.6552 5.99984 11.8997ZM5.99984 13.6663C4.21095 12.1441 2.87484 10.7302 1.9915 9.42467C1.10817 8.11912 0.666504 6.91079 0.666504 5.79967C0.666504 4.13301 1.20261 2.80523 2.27484 1.81634C3.34706 0.827452 4.58873 0.333008 5.99984 0.333008C7.41095 0.333008 8.65261 0.827452 9.72484 1.81634C10.7971 2.80523 11.3332 4.13301 11.3332 5.79967C11.3332 6.91079 10.8915 8.11912 10.0082 9.42467C9.12484 10.7302 7.78873 12.1441 5.99984 13.6663Z"
                                      fill="#121212"/>
                            </svg>
                            <span>Санкт-Петербург</span>
                        </a>
                        <a class="c-footer--a__GEO MSK" href="#" data-region="2243">
                            <svg width="12" height="14" viewBox="0 0 12 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.99984 6.99967C6.3665 6.99967 6.68039 6.86912 6.9415 6.60801C7.20261 6.3469 7.33317 6.03301 7.33317 5.66634C7.33317 5.29967 7.20261 4.98579 6.9415 4.72467C6.68039 4.46356 6.3665 4.33301 5.99984 4.33301C5.63317 4.33301 5.31928 4.46356 5.05817 4.72467C4.79706 4.98579 4.6665 5.29967 4.6665 5.66634C4.6665 6.03301 4.79706 6.3469 5.05817 6.60801C5.31928 6.86912 5.63317 6.99967 5.99984 6.99967ZM5.99984 11.8997C7.35539 10.6552 8.36095 9.52468 9.0165 8.50801C9.67206 7.49134 9.99984 6.58856 9.99984 5.79967C9.99984 4.58856 9.61373 3.5969 8.8415 2.82467C8.06928 2.05245 7.12206 1.66634 5.99984 1.66634C4.87761 1.66634 3.93039 2.05245 3.15817 2.82467C2.38595 3.5969 1.99984 4.58856 1.99984 5.79967C1.99984 6.58856 2.32761 7.49134 2.98317 8.50801C3.63873 9.52468 4.64428 10.6552 5.99984 11.8997ZM5.99984 13.6663C4.21095 12.1441 2.87484 10.7302 1.9915 9.42467C1.10817 8.11912 0.666504 6.91079 0.666504 5.79967C0.666504 4.13301 1.20261 2.80523 2.27484 1.81634C3.34706 0.827452 4.58873 0.333008 5.99984 0.333008C7.41095 0.333008 8.65261 0.827452 9.72484 1.81634C10.7971 2.80523 11.3332 4.13301 11.3332 5.79967C11.3332 6.91079 10.8915 8.11912 10.0082 9.42467C9.12484 10.7302 7.78873 12.1441 5.99984 13.6663Z"
                                      fill="#121212"/>
                            </svg>
                            <span>Москва</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="c-footerDesc--div__LEFT_BOTTOM">

                <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/five_stars.php", [], [
                        "MODE" => "php",
                        "NAME" => "five_stars",
                        "TEMPLATE" => "include_area.php",
                    ]
                ); ?>




                <div class="c-footer--div__BOTTOM">
                                <span class="c-footer--span__BOTTOM">

                                       <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/confidentiality.php", [], [
                                               "MODE" => "php",
                                               "NAME" => "onfidentiality",
                                               "TEMPLATE" => "include_area.php",
                                           ]
                                       ); ?>
                                </span>
                    <span class="c-footer--span__BOTTOM">
                                      <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/copy/copyright.php", [], [
                                              "MODE" => "php",
                                              "NAME" => "Copyright",
                                              "TEMPLATE" => "include_area.php",
                                          ]
                                      ); ?>
                                </span>
                </div>
            </div>
        </div>


        <div class="c-footerDesc--div__RIGHT">

                <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/menu/new_bottom_1.php", [], [
                        "MODE" => "php",
                        "NAME" => "new_bottom_1",
                        "TEMPLATE" => "include_area.php",
                    ]
                ); ?>

            <div class="c-footerDesc--div__RIGHT_COL">
                <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/menu/new_bottom_3_1.php", [], [
                        "MODE" => "php",
                        "NAME" => "new_bottom_3_1",
                        "TEMPLATE" => "include_area.php",
                    ]
                ); ?>


                <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/menu/new_bottom_3_2.php", [], [
                        "MODE" => "php",
                        "NAME" => "new_bottom_3_2",
                        "TEMPLATE" => "include_area.php",
                    ]
                ); ?>


                <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/menu/new_bottom_3_3.php", [], [
                        "MODE" => "php",
                        "NAME" => "new_bottom_3_3",
                        "TEMPLATE" => "include_area.php",
                    ]
                ); ?>

            </div>
        </div>
    </div>
</div>





