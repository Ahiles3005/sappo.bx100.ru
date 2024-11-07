<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$newsItems = [];

foreach ($arResult['ITEMS'] as $Item) {
    if(strstr($Item['REAL_URL'],'brand-is-') === false){
        $newsItems[] = $Item;
    }
}

$arResult['ITEMS'] = $newsItems;