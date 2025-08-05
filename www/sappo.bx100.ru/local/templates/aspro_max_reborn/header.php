<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if ($_GET["debug"] == "y")
    error_reporting(E_ERROR | E_PARSE);
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $arRegion, $arSite, $arTheme, $bIndexBot, $bIframeMode;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
$bIncludedModule = (\Bitrix\Main\Loader::includeModule("aspro.max")); ?>

    <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= LANGUAGE_ID ?>"
      lang="<?= LANGUAGE_ID ?>" <?= ($htmlClass ? 'class="' . $htmlClass . '"' : '') ?> <?= ($bIncludedModule ? CMax::getCurrentHtmlClass() : '') ?>>
    <head>
        <?
        if ($APPLICATION->GetPageProperty('canonical') == '') {
            $canon_url = 'https' . '://' . SITE_SERVER_NAME . $APPLICATION->GetCurPage();
            $APPLICATION->SetPageProperty('canonical', $canon_url);
        }
        ?>
        <title><? $APPLICATION->ShowTitle() ?></title>
        <? $APPLICATION->ShowMeta("viewport"); ?>
        <? $APPLICATION->ShowMeta("HandheldFriendly"); ?>
        <? $APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes"); ?>
        <? $APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style"); ?>
        <? $APPLICATION->ShowMeta("SKYPE_TOOLBAR"); ?>
        <? $APPLICATION->ShowHead(); ?>
        <? $APPLICATION->AddHeadString('<script>BX.message(' . CUtil::PhpToJSObject($MESS, false) . ')</script>', true); ?>
        <? Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_DIR . 'local/js/aspro_max_reborn/notice.js'); ?>
        <? Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_DIR . 'local/css/aspro_max_reborn/notice.css'); ?>
        <? Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_DIR . 'local/css/aspro_max_reborn/sbonus.css'); ?>

        <? if ($bIncludedModule)
            CMax::Start(SITE_ID); ?>
        <? include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . SITE_DIR . 'include/header_include/head.php')); ?>
        <!-- Top.Mail.Ru counter -->
        <script type="text/javascript">
            var _tmr = window._tmr || (window._tmr = []);
            _tmr.push({id: "3509615", type: "pageView", start: (new Date()).getTime(), pid: "USER_ID"});
            (function (d, w, id) {
                if (d.getElementById(id)) return;
                var ts = d.createElement("script");
                ts.type = "text/javascript";
                ts.async = true;
                ts.id = id;
                ts.src = "https://top-fwz1.mail.ru/js/code.js";
                var f = function () {
                    var s = d.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(ts, s);
                };
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(document, window, "tmr-code");
        </script>
        <noscript>
            <div><img src="https://top-fwz1.mail.ru/counter?id=3509615;js=na" style="position:absolute;left:-9999px;"
                      alt="Top.Mail.Ru"/></div>
        </noscript>
        <!-- /Top.Mail.Ru counter -->
        <!-- B24 analytics -->
        <script>
            (function (w, d, u) {
                var s = d.createElement('script');
                s.async = true;
                s.src = u + '?' + (Date.now() / 60000 | 0);
                var h = d.getElementsByTagName('script')[0];
                h.parentNode.insertBefore(s, h);
            })(window, document, 'https://crm.sappo.ru/upload/crm/366/0q9j4q0rdegur0qyqhrw4huynfvo1p7m.js');
        </script>
        <!-- /B24 analytics -->
        <!-- VK Pixel Code -->
        <script type="text/javascript">!function () {
                var t = document.createElement("script");
                t.type = "text/javascript", t.async = !0, t.src = 'https://vk.com/js/api/openapi.js?169', t.onload = function () {
                    VK.Retargeting.Init("VK-RTRG-1301299-1VpWR"), VK.Retargeting.Hit()
                }, document.head.appendChild(t)
            }();</script>
        <noscript><img src="https://vk.com/rtrg?p=VK-RTRG-1301299-1VpWR" style="position:fixed; left:-999px;" alt=""/>
        </noscript>
        <!-- End VK Pixel Code -->
        <meta name="facebook-domain-verification" content="p3z3vxoqa89q1kd4tf4odkqyakdbjp"/>
        <meta name="google-site-verification" content="T5hLmPGI2GO_-kpVmx_zYzGWUWCeB1A-3pKbEeDZx6Y"/>
        <meta name="yandex-verification" content="e40ebbdb4d12e3fd"/>
        <meta name="yandex-verification" content="9de739e4fe12100b"/>
        <meta name="yandex-verification" content="97eadc8ee9ceb486"/>


        <?
        $bodyBonusProgramClass = '';
        if (\Bitrix\Main\Loader::includeModule('kilbil.bonus')) {
            \Kilbil\Bonus\Tools\StyleManager::addBonusStyles();
            $bodyBonusProgramClass = \Kilbil\Bonus\Tools\StyleManager::getCurrentBonusClass();
        } ?>
    </head>
<? $bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false); ?>
<body class="<?= ($bIndexBot ? "wbot" : ""); ?> site_<?= SITE_ID ?> <?= ($bIncludedModule ? CMax::getCurrentBodyClass() : '') ?> <?= $bodyBonusProgramClass ?>"
      id="main" data-site="<?= SITE_DIR ?>">
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5CD8R6M" height="0" width="0"
                style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
<? if (!$bIncludedModule): ?>
    <? $APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_ASPRO_MAX_TITLE")); ?>
    <center><? $APPLICATION->IncludeFile(SITE_DIR . "include/error_include_module.php"); ?></center></body></html><? die(); ?>
<? endif; ?>

<? include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . SITE_DIR . 'include/header_include/body_top.php')); ?>

<? $arTheme = $APPLICATION->IncludeComponent("aspro:theme.max", ".default", ["COMPONENT_TEMPLATE" => ".default"], false, ["HIDE_ICONS" => "Y"]); ?>
<? include_once('defines.php'); ?>
<? CMax::SetJSOptions(); ?>

<? include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . SITE_DIR . 'include/header_include/under_wrapper1.php')); ?>
<div class="wrapper1 <?= ($isIndex && $isShowIndexLeftBlock ? "with_left_block" : ""); ?> <?= CMax::getCurrentPageClass(); ?> <? $APPLICATION->AddBufferContent([
    'CMax',
    'getCurrentThemeClasses'
]) ?>  ">
<? include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . SITE_DIR . 'include/header_include/top_wrapper1.php')); ?>

<div class="wraps hover_<?= $arTheme["HOVER_TYPE_IMG"]["VALUE"]; ?>" id="content">
<? include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . SITE_DIR . 'include/header_include/top_wraps.php')); ?>

<? if ($isIndexCustom): ?>
    <? Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/swiper-bundle.min.js', true) ?>
    <? Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/swiper-bundle.min.css'); ?>
    <? $APPLICATION->ShowViewContent('index_blocks'); ?>
    <div class="wrapper_inner front <?= ($isShowIndexLeftBlock ? "" : "wide_page"); ?> <?= $APPLICATION->ShowViewContent('wrapper_inner_class') ?>">

    <? elseif ($isIndex): ?>
    <? $APPLICATION->ShowViewContent('front_top_big_banner'); ?>
    <div class="wrapper_inner front <?= ($isShowIndexLeftBlock ? "" : "wide_page"); ?> <?= $APPLICATION->ShowViewContent('wrapper_inner_class') ?>">

    <? elseif (!$isWidePage): ?>
    <div class="wrapper_inner <?= ($isHideLeftBlock ? "wide_page" : ""); ?> <?= $APPLICATION->ShowViewContent('wrapper_inner_class') ?>">
<? endif; ?>

<div class="container_inner clearfix <?= $APPLICATION->ShowViewContent('container_inner_class') ?>">
<? if (($isIndex && ($isShowIndexLeftBlock || $bActiveTheme)) || (!$isIndex && !$isHideLeftBlock)): ?>
    <div class="right_block <?= (defined("ERROR_404") ? "error_page" : ""); ?> wide_<?= CMax::ShowPageProps("HIDE_LEFT_BLOCK"); ?> <?= $APPLICATION->ShowViewContent('right_block_class') ?>">
<? endif; ?>
<div class="middle <?= ($is404 ? 'error-page' : ''); ?> <?= $APPLICATION->ShowViewContent('middle_class') ?>">
<? CMax::get_banners_position('CONTENT_TOP'); ?>
<? if (!$isIndex): ?>
    <div class="container">
    <? //h1?>
    <? if ($isHideLeftBlock && !$isWidePage): ?>
    <div class="maxwidth-theme">
    <? endif; ?>
<? endif; ?>
    <div class="qweasdz"></div>
<? CMax::checkRestartBuffer(); ?>