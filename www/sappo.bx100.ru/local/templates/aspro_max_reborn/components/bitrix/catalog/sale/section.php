<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (strpos($APPLICATION->GetCurPage(), $arParams['SEF_FOLDER']) === 0) {
    $newUrl = str_replace($arParams['SEF_FOLDER'], '/catalog/', $APPLICATION->GetCurPage());

   if ($newUrl != $APPLICATION->GetCurPage()) {
        LocalRedirect($newUrl, true, '301 Moved Permanently');
        exit;
    }
}
?>
