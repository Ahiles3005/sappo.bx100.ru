<?
use Bitrix\Main;
use Bitrix\Main\EventManager; ///D7 события
use Bitrix\Main\Diag\Debug;
use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Order;
use Bitrix\Sale;
use Yandex\Market;
use Bitrix\Main\Loader;
use Bitrix\Main\Event;

CModule::IncludeModule("iblock");

require_once(dirname(dirname(__DIR__)).'/vue/vendor/autoload.php');

require_once(__DIR__ . '/yandex_delivery_log.php');

EventManager::getInstance()->addEventHandler("main", "OnProlog", function (){
    $GLOBALS["VUE_ROUTER"] = new App\router();
}, 100);

// AddEventHandler( "iblock", "OnAfterIBlockElementAdd", array( "aspro_import", "FillTheBrands" ) );
// AddEventHandler( "iblock", "OnAfterIBlockElementUpdate", array( "aspro_import", "FillTheBrands" ) );
// class aspro_import {
//     function FillTheBrands( $arFields ){
//         $arCatalogID=array(42);
//         if( in_array($arFields['IBLOCK_ID'], $arCatalogID) ){
//             $arItem = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID'] ), false, false, array( 'ID', 'PROPERTY_BREND' ) )->fetch();
//             if( $arItem['PROPERTY_BREND_VALUE'] ){
//                 $arBrand = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => 46, 'NAME' => $arItem['PROPERTY_BREND_VALUE'] ) )->fetch();
//                 if( $arBrand ){
//                     CIBlockElement::SetPropertyValuesEx( $arFields['ID'], false, array( 'BRAND' => $arBrand['ID'] ) );
//                 }else{
//                     $el = new CIBlockElement;
//                     $arParams = array( "replace_space" => "-", "replace_other" => "-" );
//                     $id = $el->Add( array(
//                         'ACTIVE' => 'Y',
//                         'NAME' => $arItem['PROPERTY_BREND_VALUE'],
//                         'IBLOCK_ID' => 46,
//                         'CODE' => Cutil::translit( $arItem['PROPERTY_BREND_VALUE'], "ru", $arParams )
//                     ) );
//                     if( $id ){
//                         CIBlockElement::SetPropertyValuesEx( $arFields['ID'], false, array( 'BRAND' => $id ) );
//                     }else{
//                         echo $el->LAST_ERROR;
//                     }
//                 }
//             }
//         }
//     }
// }
?>
<?

AddEventHandler("iblock", "OnAfterIBlockElementAdd", array("aspro_import", "FillTheBrands"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("aspro_import", "FillTheBrands"));
AddEventHandler("catalog", "OnCompleteCatalogImport1C", array("aspro_import", "ric_OnCompleteCatalogImport1C"));

if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/events.php")) {

    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/events.php");
}

if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/unisender/unisender.php")) {

    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/unisender/unisender.php");
}


class aspro_import
{
    static function FillTheBrands($arFields)
    {
        $arCatalogID = array(42);
        if (in_array($arFields['IBLOCK_ID'], $arCatalogID)) {
            $arItem = CIBlockElement::GetList(false, array('IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID']), false, false, array('ID', 'PROPERTY_BREND'))->fetch();
            if ($arItem['PROPERTY_BREND_VALUE']) {
                $arBrand = CIBlockElement::GetList(false, array('IBLOCK_ID' => 46, 'NAME' => $arItem['PROPERTY_BREND_VALUE']))->fetch();
                if ($arBrand) {
                    CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('BRAND' => $arBrand['ID']));
                } else {
                    $el = new CIBlockElement;
                    $arParams = array("replace_space" => "-", "replace_other" => "-");
                    $id = $el->Add(array(
                        'ACTIVE' => 'Y',
                        'NAME' => $arItem['PROPERTY_BREND_VALUE'],
                        'IBLOCK_ID' => 46,
                        'CODE' => Cutil::translit($arItem['PROPERTY_BREND_VALUE'], "ru", $arParams)
                    ));
                    if ($id) {
                        CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('BRAND' => $id));
                    } else {
                        echo $el->LAST_ERROR;
                    }
                }
            }
        }
    }

    static function ric_OnCompleteCatalogImport1C()
    {
        $msg = "После загрузки всех XML пакетов. Старт";
        /*cl_print_w("-On- CompleteCatalogImport1C : ".$msg.
            "\n----------------------------------------------------------------------------------------------------------------------",
            "ImportFrom1C"
        );*/
     //   echo nl2br(date("H:i:s") . " " . $msg . "\n");                    // оставьте эту строчку если хотите увидеть это в ответе для 1С

        $startImportCatalogTimer = microtime(true);                // начало измерения импорта каталога
        $ret = CAEDucemUpdateAfterExchange();   //скрипт обработки каталога
        $stopImportCatalogTimer = microtime(true);                    // конец измерения импорта каталога

        $msg = "После загрузки всех XML пакетов. Стоп";
      //  echo nl2br(date("H:i:s") . " " . $msg . "\n");                    // оставьте эту строчку если хотите увидеть это в ответе для 1С
        /*cl_print_w("-On- CompleteCatalogImport1C : ".$msg.
            "\nЗатрачено время: ".($stopImportCatalogTimer - $startImportCatalogTimer)." сек.".
            "\n----------------------------------------------------------------------------------------------------------------------",
            "ImportFrom1C"
        );*/
        return "";
    }
}

/***********************************************************************************
 * записать в лог файл на диске
 * $var - переменная
 * $filename - имя файла
 * $path - путь относительно корня *по умолчанию
 * /_logs/bx/                    - каталог для системных ошибок
 * /_logs/log_debug/            - просто каталог для отладки скриптов
 * /_logs/log_import_from_1C/    - каталог для отладки скриптов мипорта
 ***********************************************************************************/
/*if(!function_exists("cl_print_w")){
    function cl_print_w ($var, $filename="", $path = "/_logs/log_import_from_1C/")
    {
//  $pr = date("Y-m-d H:i:s");
        $pr = date("H:i:s");

        if ($filename=="" or empty($filename)){
            \Bitrix\Main\Diag\Debug::writeToFile($pr.$var,
                "",
                $path.date("Y-m-d H_00")." cadmarket.log");
        }
        else {
            \Bitrix\Main\Diag\Debug::writeToFile($pr.$var,
                "",
                $path.date("Y-m-d H_00")." cadmarket ".$filename.".log");
        }
    }
}*/
if (!function_exists("CAEDucemUpdateAfterExchange")) {

    function CAEDucemUpdateAfterExchange()
    {
        CModule::IncludeModule('iblock');
        CModule::IncludeModule('sale');
        $el = new CIBlockElement;
        $IBLOCK_ID = 42;
        $idPropListValue = 7405;
        $listProduct = [];
        $arSelect = array("ID", "IBLOCK_ID", "NAME", "CATALOG_PRICE_2", "CATALOG_PRICE_14", "PROPERTY_*");
        $arFilter = array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arFields["PROPERTY"] = $ob->GetProperties();
            $listProduct[] = [
                "ID" => $arFields["ID"],
                "CATALOG_PRICE_2" => $arFields["CATALOG_PRICE_2"],
                "CATALOG_PRICE_14" => $arFields["CATALOG_PRICE_14"],
                "VALUE_ENUM_ID" => $arFields["PROPERTY"]["HIT"]["VALUE_ENUM_ID"]
            ];
        }
        $log = date('Y-m-d H:i:s') . ' Начало цикла товаров';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/log_sync.txt', $log . PHP_EOL, FILE_APPEND);
        foreach ($listProduct as $product) {
            $PROP = [];
            if (round($product["CATALOG_PRICE_2"]) < round($product["CATALOG_PRICE_14"])) {
                if (is_array($product["VALUE_ENUM_ID"])) $PROP["HIT"] = array_merge($product["VALUE_ENUM_ID"], [0 => $idPropListValue]);
                else $PROP["HIT"] = [0 => $idPropListValue];
            } else {
                if (is_array($product["VALUE_ENUM_ID"])) {
                    if (in_array($idPropListValue, $product["VALUE_ENUM_ID"]) && (round($product["CATALOG_PRICE_2"]) == round($product["CATALOG_PRICE_14"]))) {
                        $PROP["HIT"] = array_diff($product["VALUE_ENUM_ID"], [0 => $idPropListValue]);
                        if (count($PROP["HIT"]) == 0) $PROP["HIT"] = [0 => ''];
                    }
                }
            }

            $rsStore = CCatalogStoreProduct::GetList(array(), array(
                'PRODUCT_ID' => $product["ID"],
                'STORE_ID' => [3, 5]),
                false, false, array());
            while ($arStore = $rsStore->Fetch()) {
                switch ($arStore["STORE_ID"]) {
                    case "5":
                        $PROP["STORE_SPB"] = (int)$arStore["AMOUNT"];
                        $PROP["AVAILABLE_SPB"] = ((int)$arStore["AMOUNT"] > 0) ? 1 : 0;
                        break;
                    case "3":
                        $PROP["STORE_MSK"] = (int)$arStore["AMOUNT"];
                        $PROP["AVAILABLE_MSK"] = ((int)$arStore["AMOUNT"] > 0) ? 1 : 0;
                        break;
                }
            }
            if ($product["ID"] == 23448) {
                $log = date('Y-m-d H:i:s') . ' ' . print_r($PROP, true);
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/log_sync.txt', $log . PHP_EOL, FILE_APPEND);
            }
            $result = CIBlockElement::SetPropertyValuesEx($product["ID"], false, $PROP);
            $result2 = $el->Update($product["ID"], array('TIMESTAMP_X' => true));
        }

      return "CAEDucemUpdateAfterExchange();";
    }
}


$eventManager = EventManager::getInstance();

$eventManager->addEventHandler('sale', 'OnSaleComponentOrderResultPrepared', 'OnSaleComponentOrderResultPrepared');

function OnSaleComponentOrderResultPrepared(&$order, &$arUserResult, $request, &$arParams, &$arResult)
{
    $paymentCollection = $order->getPaymentCollection();
    $payment = current($paymentCollection);

    $isBonusActive = false;
    if($payment && isset($payment[0]) && $payment[0]){
        $paySystemId = $payment[0]->getPaymentSystemId();
        $isBonusActive =  in_array($paySystemId, [9, 10]);
    }


    if(!$isBonusActive) {
        $property = $order->getPropertyCollection()->getItemByOrderPropertyCode('KILBIL_BONUS');
        $property->setField('VALUE', 0);
    } else {

    }

    $bonusOut = (float)$order->getPropertyCollection()->getItemByOrderPropertyCode('KILBIL_BONUS')->getValue();
    $maxOut = 0;
    $sumBonus = 0;
    \Bitrix\Main\Loader::includeModule('kilbil.bonus');
    if(\Bitrix\Main\Engine\CurrentUser::get()->getId()) {
        $userBonuses = new \Kilbil\Bonus\Ecommerce\User\UserBonuses();
        $maxOut = (float)$userBonuses->getClientBonuses()->maxBonusOut;

        if($bonusOut > $maxOut) {
            $property = $order->getPropertyCollection()->getItemByOrderPropertyCode('KILBIL_BONUS');
            $property->setField('VALUE', $maxOut);
            $bonusOut = (float)$maxOut;
        }

        $basket = new \Kilbil\Bonus\Ecommerce\Basket\Basket();
        $sumBonus = (float)$basket->calculateAllBonuses();

    } else {
        $isBonusActive = false;
    }


    $arResult['JS_DATA']['ADD_BONUSES_ORIG'] = $sumBonus;
    $arResult['JS_DATA']['ADD_BONUSES'] = $sumBonus - $bonusOut;
    $arResult['JS_DATA']['BONUS_OUT_FORMAT'] = SaleFormatCurrency($bonusOut, $order->getCurrency());

    $arResult['JS_DATA']['BONUS_OUT'] = $bonusOut;
    $arResult['JS_DATA']['BONUS_MAX_OUT'] = $maxOut;
    $arResult['JS_DATA']['IS_BONUS_ACTIVE'] = $isBonusActive ? 'Y' : 'N';

    if($bonusOut > 0) {
        $totalPrice = $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE'];
        $productPrice = $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE'];

        $arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE_SALE_FORMATED'] = SaleFormatCurrency( ($totalPrice - $productPrice + ($productPrice - $bonusOut)) , $order->getCurrency());
    }
}

// /product/ element
//AddEventHandler("main", "OnEndBufferContent", "ChangeUrlProduct");

function ChangeUrlProduct(&$content)
{
    $IBLOCK_ID = 42;
    $arCurURL = [];
    $arNewURL = [];

    $cache = Bitrix\Main\Data\Cache::createInstance();

    if ($cache->initCache(7200, 'productUrlTemplates', '/')) {
        $arResult = $cache->getVars();
        $arCurURL = $arResult["CUR_URL"];
        $arNewURL = $arResult["NEW_URL"];
    } else if ($cache->startDataCache()) {
        //current URLs
        $rsElements = CIBlockElement::GetList(array("ID" => 'asc'), array("IBLOCK_ID" => $IBLOCK_ID), false, false, array("ID", "NAME", "DETAIL_PAGE_URL"));

        while ($arElement = $rsElements->GetNext()) {
            $arCurURL[] = $arElement["DETAIL_PAGE_URL"];
        }

        //new URLs
        $rsElements = CIBlockElement::GetList(array("ID" => 'asc'), array("IBLOCK_ID" => $IBLOCK_ID), false, false, array("ID", "NAME", "DETAIL_PAGE_URL"));
        $rsElements->SetUrlTemplates("/product/#ELEMENT_CODE#/");

        while ($arElement = $rsElements->GetNext()) {
            $arNewURL[] = $arElement["DETAIL_PAGE_URL"];
        }
        $arResult["CUR_URL"] = $arCurURL;
        $arResult["NEW_URL"] = $arNewURL;

        $cache->endDataCache($arResult);
    }

    if (!empty($arCurURL) && !empty($arNewURL)) {
        $content = str_replace($arCurURL, $arNewURL, $content);
    }
}


AddEventHandler("main", "OnEndBufferContent", "redirectToNewUri");

function redirectToNewUri(&$content)
{
    $IBLOCK_ID = 42;

    [$requestUri, $get] = explode('?', $_SERVER['REQUEST_URI']);
    $segments = explode('/', trim($requestUri,'/'));
    $lastSegment = end($segments);

    if (strpos($requestUri, '/catalog/') === false || !is_numeric($lastSegment)) {
        return;
    }

    $productId = (int)$lastSegment;

    $cacheId = 'productUrlTemplates_' . $productId;
    $cache = Bitrix\Main\Data\Cache::createInstance();

    if ($cache->initCache(7200, $cacheId, '/')) {
        $newUrl = $cache->getVars();
    } else if ($cache->startDataCache()) {
        $rsElement = CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $IBLOCK_ID, "ID" => $productId],
            false,
            false,
            ["ID", "NAME", "DETAIL_PAGE_URL"]
        );
        $rsElement->SetUrlTemplates("/product/#ELEMENT_CODE#/");

        if ($arElement = $rsElement->GetNext()) {
            $newUrl = $arElement["DETAIL_PAGE_URL"];
            $cache->endDataCache($newUrl);
        } else {
            $cache->abortDataCache();
            return;
        }
    }
    if ($get) {
        $newUrl .= '?' . $get;
    }

    if (!empty($newUrl)) {
        LocalRedirect($newUrl,true,'301 Moved Permanently');
        exit;
    }
}


AddEventHandler("main", "OnPageStart", "saveUtmToSession");

function saveUtmToSession() {
    $utmParams = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    foreach ($utmParams as $param) {
        if (isset($_GET[$param])) {
            $_SESSION[$param] = $_GET[$param];
            /*echo '<script>
                sessionStorage.setItem("' . $param . '", "' . $_GET[$param] . '");
                console.log(sessionStorage.getItem("' . $param . '"));
                console.log(sessionStorage);
            </script>';*/
        }
    }
}


AddEventHandler("main", "OnEndBufferContent", "ReplaceBreadcrumbLink");
function ReplaceBreadcrumbLink(&$content)
{
    global $APPLICATION;

    if (!defined("ADMIN_SECTION") || ADMIN_SECTION !== true) {
        $search = '<a class="breadcrumbs__link" href="/catalog/sale/" title="Распродажа" itemprop="item">';
        $replace = '<a class="breadcrumbs__link" href="/sale-new/" title="Распродажа" itemprop="item">';
        $content = str_replace($search, $replace, $content);
    }
}

// Change url for yandex market
$eventManager->addEventHandler("yandex.market", "onExportOfferWriteData", function (Main\Event $event) {
    $tagResultList = $event->getParameter("TAG_RESULT_LIST");
    $context = $event->getParameter('CONTEXT');

    foreach ($tagResultList as $elementId => $tagResult) {
        if ($tagResult->isSuccess()) {
            $tagNode = $tagResult->getXmlElement();
            $children = $tagNode->children();

                foreach ($children as $child_name => $child_val) {
                    if($child_name == 'url'){
                        if($context["SETUP_ID"] == 3) {
                            $child_val[0] = $child_val[0].'?city=msk';
                        }
                        if($context["SETUP_ID"] == 4) {
                            $child_val[0] = $child_val[0].'?city=spb';
                        }
                    }
                }

            $tagResult->invalidateXmlContents();
        }
    }
});

if(isset($_GET['city'])) {
    global $_COOKIE;

    if ( $_GET['city'] == 'msk' ) {
        $_COOKIE['current_region'] = 2243;
    }
    elseif ( $_GET['city'] == 'spb' ) {
        $_COOKIE['current_region'] = 2242;
    }
}



addEventHandler("main", "OnEndBufferContent", "SetHostForTurbo");

function SetHostForTurbo(&$content)
{
    $newHost = 'https://sappo.ru';

    $type = $_GET['type'] ?? '';
    $id = $_GET['id'] ?? '';

    if($type == 'aspro_max_content' && $id==33){

        $patterns = [
            '/<(img[^>]+)\b(src)="(\/[^"]+|\bhttps?:\/\/[^"]+)/i',
        ];

        foreach ($patterns as $pattern) {
            $content = preg_replace_callback($pattern, function($matches) use ($newHost) {
                $url = $matches[3];

                if (str_starts_with($url, '/')) {
                    return '<' . $matches[1] . ' ' . $matches[2] . '="' . $newHost . $url . '';
                }

                return $matches[0];
            }, $content);
        }
    }

}

$eventManager->addEventHandler(
    'sale',
    'onSalePaySystemRestrictionsClassNamesBuildList', function (Main\Event $event) {
        return new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::SUCCESS,
            array(
                '\MyPayRestriction' => '/local/php_interface/include/mypayrestriction.php',
            )
        );
    }
);


function updateNewsProducts()
{

    if (!\Bitrix\Main\Loader::includeModule('iblock')) {
        return false;
    }

    $iblockId = 42;
    $propValueId = 7404; //new
    $dateThreshold = new \Bitrix\Main\Type\DateTime();
    $dateThreshold->add("-30 days");

    $oldProducts = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $iblockId,
            'PROPERTY_HIT' => $propValueId,
            '<DATE_CREATE' => $dateThreshold
        ],
        false,
        false,
        ['*']
    );

    while ($productData = $oldProducts->GetNextElement()) {

        $product = $productData->GetFields();

        $productPrp = $productData->GetProperties();
        $hitValues = $productPrp['HIT']['VALUE_ENUM_ID'] ?? [];
        foreach ($hitValues as $k => $hitValue) {
            if ($hitValue == $propValueId) {
                unset($hitValues[$k]);
                break;
            }
        }

        if (empty($hitValues)) {
            $hitValues = false;
        }

        $result = CIBlockElement::SetPropertyValuesEx($product['ID'], $iblockId, ['HIT' => $hitValues]);
    }


    $newProducts = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $iblockId,
            '>DATE_CREATE' => $dateThreshold
        ],
        false,
        false,
        ['*']
    );


    while ($productData = $newProducts->GetNextElement()) {
        $product = $productData->GetFields();
        $productPrp = $productData->GetProperties();
        $hitValues = $productPrp['HIT']['VALUE_ENUM_ID'] ?? [];
        $hitValues[] = $propValueId;
        CIBlockElement::SetPropertyValuesEx($product['ID'], $iblockId, ['HIT' => $hitValues]);
    }


    return "updateNewsProducts();";
}

function updateHitProducts()
{

    if (!\Bitrix\Main\Loader::includeModule('iblock')) {
        return false;
    }

    $iblockId = 42;
    $propValueId = 7402; //hit

    $oldProducts = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $iblockId,
            'PROPERTY_HIT' => $propValueId
        ],
        false,
        false,
        ['*']
    );


    while ($productData = $oldProducts->GetNextElement()) {
        $product = $productData->GetFields();
        $productPrp = $productData->GetProperties();
        $hitValues = $productPrp['HIT']['VALUE_ENUM_ID'] ?? [];
        foreach ($hitValues as $k => $hitValue) {
            if ($hitValue == $propValueId) {
                unset($hitValues[$k]);
                break;
            }
        }
        if (empty($hitValues)) {
            $hitValues = false;
        }
        CIBlockElement::SetPropertyValuesEx($product['ID'], $iblockId, ['HIT' => $hitValues]);
    }


    $dateThreshold = new \Bitrix\Main\Type\DateTime();
    $dateThreshold->add("-30 days");

    $dbOrders = \Bitrix\Sale\Order::getList([
        'select' => ['ID'],
        'filter' => [
            '>=DATE_INSERT' => $dateThreshold,
            'STATUS_ID' => 'F'
        ]
    ]);

    $productIds = [];
    $quantitySoldByProductId = [];
    while ($order = $dbOrders->fetch()) {
        $dbBasket = \Bitrix\Sale\Basket::getList([
            'select' => ['PRODUCT_ID'],
            'filter' => ['ORDER_ID' => $order['ID']]
        ]);
        while ($item = $dbBasket->fetch()) {
            $productId = intval($item['PRODUCT_ID']);
            $productIds[] = $productId;
            if (isset($quantitySoldByProductId[$productId])) {
                $quantitySoldByProductId[$productId] += 1;
            } else {
                $quantitySoldByProductId[$productId] = 1;
            }
        }
    }

    $products = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $iblockId,
            'ID' => $productIds
        ],
        false,
        false,
        ['*']
    );

    while ($productData = $products->GetNextElement()) {
        $product = $productData->GetFields();
        $productPrp = $productData->GetProperties();
        $hitValues = $productPrp['HIT']['VALUE_ENUM_ID'] ?? [];
        $hitValues[] = $propValueId;

        $productId = intval($product['ID']);
        $quantitySold = $quantitySoldByProductId[$productId];
        CIBlockElement::SetPropertyValuesEx($product['ID'], $iblockId, ['HIT' => $hitValues,'QUANTITY_SOLD'=>$quantitySold]);
    }


    return "updateHitProducts();";
}

if (\Bitrix\Main\Loader::includeModule('iblock'))
{
    \Bitrix\Main\EventManager::getInstance()->addEventHandler(
        "iblock",
        "OnTemplateGetFunctionClass",
        array("FunctionMinPrice", "eventHandler")
    );
    include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/lib/template/functions/fabric.php");
    class FunctionMinPrice extends \Bitrix\Iblock\Template\Functions\FunctionBase
    {
        public static function eventHandler($event)
        {
            $parameters = $event->getParameters();
            $functionName = $parameters[0];
            if ($functionName === "minprice")
            {
                return new \Bitrix\Main\EventResult(
                    \Bitrix\Main\EventResult::SUCCESS,
                    "\\FunctionMinPrice"
                );
            }
        }
        public function calculate($parameters)
        {
            $arFilter = false;

            if(isset($parameters[0])){

                $rsSections = CIBlockSection::GetList([], ['CODE'=>$parameters[0],  'IBLOCK_ID' => 42], false, ["ID", "IBLOCK_ID", "CODE", "NAME"]);
                while($arSec = $rsSections->Fetch()){

                    $arSection = $arSec;
                }
                if($arSection){
                    $arFilter = ['IBLOCK_ID'=>$arSection['IBLOCK_ID'], 'SECTION_ID'=>$arSection['ID'], 'INCLUDE_SUBSECTIONS' => "Y"];
                }

                if(isset($parameters[1])){
                    $rsBrands = CIBlockElement::GetList([], ['IBLOCK_ID'=>46, 'NAME'=>$parameters[1]], false, false, ["ID"]);
                    while($arBrn = $rsBrands->Fetch()){
                        $arBrands = $arBrn;
                    }
                    if($arSection){
                        $arFilter['PROPERTY_BRAND.ID'] = $arBrands['ID'];
                    }
                }

                if(!$arFilter){
                    reset($parameters);
                    return '';
                }
                $rsElements = CIBlockElement::GetList(['SORT'=> 'asc'], $arFilter, false, false, ["ID"]);
                while($arElem = $rsElements->Fetch()){
                    $tmpPrice = '';
                    $tmpPrice = CPrice::GetBasePrice($arElem['ID']);
                    if(!empty($tmpPrice)){
                        $arPrice[] = $tmpPrice['PRICE'];
                    }
                }
                if($arPrice){
                    $minPriceSection = \CCurrencyLang::CurrencyFormat(min($arPrice), \Bitrix\Currency\CurrencyManager::getBaseCurrency());
                    return $minPriceSection;
                }
                reset($parameters);
                return '';
            }
            return '';
        }
    }
}

/* Сертификаты отправка */
Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleStatusOrderChange',
    'checkOrderForCertificatesOnPayment'
);


// Функция для записи логов SMS в файл
function writeSmsLog($message) {
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/upload/sms_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}

function checkOrderForCertificatesOnPayment(Main\Event $event)
{
    // Настройка переменных окружения для Green SMS
    if (!getenv('GREENSMS_USER')) {
        putenv('GREENSMS_USER=sappogreensms');
        $_ENV['GREENSMS_USER'] = 'sappogreensms';
    }
    if (!getenv('GREENSMS_PASS')) {
        putenv('GREENSMS_PASS=HdF189sD');
        $_ENV['GREENSMS_PASS'] = 'HdF189sD';
    }

    // Правильно получаем объект заказа из события
    $order = $event->getParameter("ENTITY");

    // Проверяем, что модули загружены
    if (!\Bitrix\Main\Loader::includeModule('sale') || !\Bitrix\Main\Loader::includeModule('iblock')) {
        return;
    }

    // Проверяем, что заказ перешёл в статус "Оплачен"
    if ($order->getField('STATUS_ID') !== 'P') {
        return;
    }

    // Телефон покупателя по умолчанию (fallback)
    $fallbackPhone = '';
    $propertyCollection = $order->getPropertyCollection();
    $phoneProp = $propertyCollection->getItemByOrderPropertyCode('PHONE');
    if ($phoneProp && $phoneProp->getValue()) {
        $fallbackPhone = preg_replace("/[^0-9]/", '', $phoneProp->getValue());
    }
    if (!$fallbackPhone && class_exists('\\Bitrix\\Main\\UserPhoneAuthTable')) {
        $userId = (int)$order->getUserId();
        if ($userId > 0) {
            $row = \Bitrix\Main\UserPhoneAuthTable::getRowById($userId);
            if ($row && !empty($row['PHONE_NUMBER'])) {
                $fallbackPhone = preg_replace("/[^0-9]/", '', $row['PHONE_NUMBER']);
            }
        }
    }

    // Проходимся по товарам заказа и ищем связанные предложения в ИБ 80
    $basket = $order->getBasket();

    if (!$basket) {
        return;
    }

    foreach ($basket as $basketItem) {
		$productId = (int)$basketItem->getProductId();
		if ($productId <= 0) {
			continue;
		}

		$name = (string)$basketItem->getField('NAME');
		if ($name === '' || mb_stripos($name, 'сертификат') === false) {
			continue;
		}

		$offers = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => 80,
                'ACTIVE' => 'Y',
                'ID' => $productId,
            ],
            false,
            ['nTopCount' => 1],
			['ID', 'NAME', 'PROPERTY_CML2_BAR_CODE']
        );

		while ($offer = $offers->Fetch()) {
			$targetPhone = '';
			// Пробуем взять телефон из свойства заказа GIFT_PHONE, затем fallback PHONE
			$giftPhoneProp = $propertyCollection->getItemByOrderPropertyCode('GIFT_PHONE');
			if ($giftPhoneProp && $giftPhoneProp->getValue()) {
				$targetPhone = preg_replace("/[^0-9]/", '', (string)$giftPhoneProp->getValue());
			}
			if (!$targetPhone) {
				$targetPhone = $fallbackPhone;
			}

			if (!$targetPhone) {
				continue;
			}

			$barcode = isset($offer['PROPERTY_CML2_BAR_CODE_VALUE']) ? trim((string)$offer['PROPERTY_CML2_BAR_CODE_VALUE']) : '';
			$hash = $barcode !== '' ? md5('sappo' . $barcode) : '';
			$link = $barcode !== '' ? ('https://sappo.ru/gift/?code=' . urlencode($barcode) . '&hash=' . $hash) : '';

			$fromName = '';
			$giftFromProp = $propertyCollection->getItemByOrderPropertyCode('GIFT_FROM');
			if ($giftFromProp && $giftFromProp->getValue()) {
				$fromName = trim((string)$giftFromProp->getValue());
			}
			$text = ($fromName ? ('Вам подарили сертификат в SAPPO.RU от ' . $fromName . '. ') : 'Ваш подарочный сертификат оформлен. ')
				. ($link ? ('Ссылка на сертификат: ' . $link) : '');

            try {
                if (class_exists('App\\Services\\SmsService')) {
                    $smsService = new \App\Services\SmsService();
                    $result = $smsService->sendSms($targetPhone, $text, 'Sappo.ru');

                    if ($result !== true) {
                        writeSmsLog("SMS отправка не удалась для номера {$targetPhone}: " . $result);
                    } else {
                        writeSmsLog("SMS успешно отправлена на номер {$targetPhone}");
                    }
                } else {
                    writeSmsLog("SmsService класс не найден");
                }
            } catch (Exception $e) {
                writeSmsLog("Ошибка при отправке SMS: " . $e->getMessage());
            }
        }
    }
}

AddEventHandler("main", "OnEndBufferContent", "AddGiftCardForm", 1000); // Приоритет 1000 для выполнения после других обработчиков

function AddGiftCardForm(&$content)
{
    global $APPLICATION;

    if (strpos($APPLICATION->GetCurPage(), '/order/') === false) {

        return;
    }

    $sessionGiftCardCode = isset($_SESSION['KILBIL_GIFTCARD']['code']) ? htmlspecialchars($_SESSION['KILBIL_GIFTCARD']['code']) : '';
    $sessionGiftCardBalance = isset($_SESSION['KILBIL_GIFTCARD']['balance']) ? floatval($_SESSION['KILBIL_GIFTCARD']['balance']) : 0;

    $giftcardJs = <<<EOD
<script>
console.log("Gift card script loaded on /order/ page");

function insertGiftCardForm() {
    var couponBlock = document.querySelector(".bx-soa-coupon");
    if (!couponBlock || document.querySelector(".kilbil-giftcard-container")) {
        console.log("Coupon block not found or gift card form already exists");
        return;
    }

    console.log("Coupon block found, inserting gift card form");
    var giftcardForm = document.createElement("div");
    giftcardForm.innerHTML = `
        <div class="bx-soa-coupon kilbil-giftcard-container" style="margin-top: 15px;">
            <div class="bx-soa-coupon-label">
                <label>Применить подарочный сертификат:</label>
            </div>
            <div class="bx-soa-coupon-block">
                <div class="bx-soa-coupon-input" id="kilbil-giftcard-input-container">
                    <input class="form-control bx-ios-fix" type="text" id="kilbil-giftcard-input" value="{$sessionGiftCardCode}">
                </div>
                <span class="bx-soa-coupon-item" id="kilbil-giftcard-item">
                    <span class="bx-soa-coupon-item-apply" id="kilbil-giftcard-apply"></span>
                </span>
            </div>
            <div id="kilbil-giftcard-result" style="margin-top: 10px; display: none;">
                <div id="kilbil-giftcard-balance" style="color: green;"></div>
            </div>
        </div>`;
    couponBlock.insertAdjacentElement("afterend", giftcardForm);

    var giftcardInput = document.getElementById("kilbil-giftcard-input");
    var giftcardResult = document.getElementById("kilbil-giftcard-result");
    var giftcardBalance = document.getElementById("kilbil-giftcard-balance");
    var giftcardContainer = document.querySelector(".kilbil-giftcard-container");
    var giftcardApply = document.getElementById("kilbil-giftcard-apply");

    if (!giftcardContainer) {
        console.error("Gift card container not found");
        return;
    }

    function extractPriceFromText(text) {
        if (!text) return 0;
        var matches = text.match(/([0-9\s]+[.,]?[0-9]*)/);
        if (!matches) return 0;
        return parseFloat(matches[1].replace(/\s/g, "").replace(",", "."));
    }

    function getDeliveryPrice() {
        try {
            var deliveryRows = document.querySelectorAll(".bx-soa-cart-total-line");
            for (var i = 0; i < deliveryRows.length; i++) {
                var titleElement = deliveryRows[i].querySelector(".bx-soa-cart-t");
                if (titleElement && titleElement.textContent.toLowerCase().includes("доставк")) {
                    var priceElement = deliveryRows[i].querySelector(".bx-soa-cart-d");
                    if (priceElement) {
                        var price = extractPriceFromText(priceElement.textContent);
                        console.log("Delivery price from DOM:", price);
                        return price;
                    }
                }
            }
            console.log("Delivery price not found, assuming 0 (self-pickup)");
            return 0;
        } catch (e) {
            console.error("Error getting delivery price:", e);
            return 0;
        }
    }

    function getProductsPrice() {
        try {
            var productRows = document.querySelectorAll(".bx-soa-cart-total-line");
            for (var i = 0; i < productRows.length; i++) {
                var titleElement = productRows[i].querySelector(".bx-soa-cart-t");
                if (titleElement && titleElement.textContent.toLowerCase().includes("товар")) {
                    var priceElement = productRows[i].querySelector(".bx-soa-cart-d");
                    if (priceElement) {
                        var price = extractPriceFromText(priceElement.textContent);
                        console.log("Products price from DOM:", price);
                        return price;
                    }
                }
            }
            if (typeof BX !== "undefined" && BX.Sale && BX.Sale.OrderAjaxComponent) {
                if (BX.Sale.OrderAjaxComponent.result && BX.Sale.OrderAjaxComponent.result.TOTAL) {
                    var productsPrice = parseFloat(BX.Sale.OrderAjaxComponent.result.TOTAL.PRICE_WITHOUT_DISCOUNT || 0);
                    console.log("Products price from API:", productsPrice);
                    if (productsPrice > 0) return productsPrice;
                }
            }
            return 0;
        } catch (e) {
            console.error("Error getting products price:", e);
            return 0;
        }
    }

    function addGiftCardLine(appliedAmount) {
        try {
            // Удаляем существующие строки с сертификатом, если они есть
            var existingGiftCardLines = document.querySelectorAll("#kilbil-giftcard-line");
            existingGiftCardLines.forEach(function(line) {
                line.remove();
            });

            // Форматируем сумму
            var formattedAmount = Math.round(appliedAmount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            console.log("Formatting amount:", appliedAmount, "to", formattedAmount);

            // Добавляем строку с сертификатом в основной блок
            var mainTotalBlock = document.querySelector(".bx-soa-cart-total:not(.bx-soa-cart-total-fixed)");
            if (mainTotalBlock) {
                var totalLine = mainTotalBlock.querySelector(".bx-soa-cart-total-line-total");
                if (totalLine) {
                    var giftCardLine = document.createElement("div");
                    giftCardLine.id = "kilbil-giftcard-line";
                    giftCardLine.className = "bx-soa-cart-total-line";
                    giftCardLine.innerHTML =
                        '<span class="bx-soa-cart-t" style="color: green;">Подарочный сертификат:</span>' +
                        '<span class="bx-soa-cart-d" style="color: green;">-' + formattedAmount + ' руб.</span>';

                    totalLine.parentNode.insertBefore(giftCardLine, totalLine);
                    console.log("Added gift card line to main total block showing:", formattedAmount, "rubles");
                }
            }

            // Добавляем строку с сертификатом в фиксированный боковой блок
            var fixedTotalBlock = document.querySelector(".bx-soa-cart-total-fixed");
            if (fixedTotalBlock) {
                var fixedTotalLine = fixedTotalBlock.querySelector(".bx-soa-cart-total-line-total");
                if (fixedTotalLine) {
                    var fixedGiftCardLine = document.createElement("div");
                    fixedGiftCardLine.id = "kilbil-giftcard-line";
                    fixedGiftCardLine.className = "bx-soa-cart-total-line";
                    fixedGiftCardLine.innerHTML =
                        '<span class="bx-soa-cart-t" style="color: green;">Подарочный сертификат:</span>' +
                        '<span class="bx-soa-cart-d" style="color: green;">-' + formattedAmount + ' руб.</span>';

                    fixedTotalLine.parentNode.insertBefore(fixedGiftCardLine, fixedTotalLine);
                    console.log("Added gift card line to fixed block showing:", formattedAmount, "rubles");
                }
            }

            return true;
        } catch (e) {
            console.error("Error adding gift card line:", e);
            console.error(e.stack); // Выводим стек ошибки для отладки
            return false;
        }
    }

    function updateTotalPrice() {
        try {
            var totalElements = document.querySelectorAll(".bx-soa-cart-total-line-total .bx-soa-cart-d");
            if (totalElements.length === 0) {
                console.error("Total price elements not found");
                return;
            }

            var deliveryPrice = getDeliveryPrice();
            var productsPrice = getProductsPrice();
            var giftcardBalance = window.KILBIL_GIFTCARD && window.KILBIL_GIFTCARD.balance ? parseFloat(window.KILBIL_GIFTCARD.balance) : 0;

            var appliedAmount = Math.min(giftcardBalance, productsPrice);
            var discountedProductsPrice = Math.max(0, productsPrice - appliedAmount);
            var newTotalPrice = discountedProductsPrice + deliveryPrice;

            console.log("Delivery price:", deliveryPrice);
            console.log("Products price:", productsPrice);
            console.log("Giftcard balance:", giftcardBalance);
            console.log("Applied discount:", appliedAmount);
            console.log("Discounted products price:", discountedProductsPrice);
            console.log("New total price:", newTotalPrice);

            if (appliedAmount > 0) {
                addGiftCardLine(appliedAmount);

                // Сохраняем значение скидки в глобальную переменную для восстановления
                window.KILBIL_APPLIED_AMOUNT = appliedAmount;
            } else {
                var existingGiftCardLines = document.querySelectorAll("#kilbil-giftcard-line");
                existingGiftCardLines.forEach(function(line) {
                    line.remove();
                });
                window.KILBIL_APPLIED_AMOUNT = 0;
            }

            totalElements.forEach(function(totalElement) {
                var currencySymbol = "руб.";
                var priceText = totalElement.textContent;
                var currencyMatch = priceText.match(/[^0-9\s.,]+/);
                if (currencyMatch) {
                    currencySymbol = currencyMatch[0];
                }

                var formattedPrice = Math.round(newTotalPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                totalElement.textContent = formattedPrice + " " + currencySymbol;
                console.log("Total price updated to:", formattedPrice, currencySymbol);
            });

            window.GIFTCARD_PRICE_UPDATED = {
                productsPrice: productsPrice,
                deliveryPrice: deliveryPrice,
                appliedAmount: appliedAmount,
                discountedProductsPrice: discountedProductsPrice,
                newTotalPrice: newTotalPrice
            };
        } catch (e) {
            console.error("Error updating total price:", e);
        }
    }

    // Функция для проверки и восстановления строки с сертификатом
    function checkAndRestoreGiftCardLine() {
        if (window.KILBIL_GIFTCARD && window.KILBIL_GIFTCARD.balance && window.KILBIL_APPLIED_AMOUNT > 0) {
            var existingLines = document.querySelectorAll("#kilbil-giftcard-line");
            if (existingLines.length === 0) {
                console.log("Restoring gift card line after DOM changes");
                addGiftCardLine(window.KILBIL_APPLIED_AMOUNT);

                // Также обновляем итоговую цену
                var totalElements = document.querySelectorAll(".bx-soa-cart-total-line-total .bx-soa-cart-d");
                if (totalElements.length > 0 && window.GIFTCARD_PRICE_UPDATED) {
                    totalElements.forEach(function(totalElement) {
                        var currencySymbol = "руб.";
                        var priceText = totalElement.textContent;
                        var currencyMatch = priceText.match(/[^0-9\s.,]+/);
                        if (currencyMatch) {
                            currencySymbol = currencyMatch[0];
                        }

                        var formattedPrice = Math.round(window.GIFTCARD_PRICE_UPDATED.newTotalPrice)
                            .toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                        totalElement.textContent = formattedPrice + " " + currencySymbol;
                    });
                }
            }
        }
    }

    function checkGiftCard() {
        var giftcardCode = giftcardInput.value.trim();
        console.log("Gift card code: " + giftcardCode);

        if (giftcardCode) {
            console.log("Starting AJAX request to", "/local/ajax/check_giftcard.php");
            BX.ajax({
                url: "/local/ajax/check_giftcard.php",
                method: "POST",
                dataType: "json",
                data: { giftcard_code: giftcardCode },
                timeout: 10000,
                onsuccess: function(data) {
                    console.log("AJAX response: ", data);
                    giftcardResult.style.display = "block";
                    if (data.success) {
                        giftcardBalance.style.color = "green";
                        giftcardBalance.innerHTML = "Сертификат применен. Баланс: " + data.balance + " руб.";
                        window.KILBIL_GIFTCARD = {
                            code: giftcardCode,
                            balance: data.balance
                        };
                        updateTotalPrice();
                    } else {
                        giftcardBalance.innerHTML = data.error || "Ошибка при проверке сертификата";
                        giftcardBalance.style.color = "red";
                        window.KILBIL_GIFTCARD = null;
                        window.KILBIL_APPLIED_AMOUNT = 0;
                        updateTotalPrice();
                    }
                },
                onfailure: function(xhr, status, error) {
                    console.log("AJAX request failed with status:", status, "Error:", error);
                    giftcardResult.style.display = "block";
                    giftcardBalance.innerHTML = "Ошибка соединения с сервером. Попробуйте позже.";
                    giftcardBalance.style.color = "red";
                    window.KILBIL_GIFTCARD = null;
                    window.KILBIL_APPLIED_AMOUNT = 0;
                    updateTotalPrice();
                }
            });
        } else {
            giftcardResult.style.display = "block";
            giftcardBalance.innerHTML = "Введите код сертификата";
            giftcardBalance.style.color = "red";
            window.KILBIL_GIFTCARD = null;
            window.KILBIL_APPLIED_AMOUNT = 0;
            updateTotalPrice();
        }
    }

    giftcardApply.addEventListener("click", checkGiftCard);
    giftcardInput.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            checkGiftCard();
        }
    });

    var inputContainer = giftcardContainer.querySelector(".bx-soa-coupon-input");
    if (inputContainer) {
        inputContainer.style.cursor = "pointer";
        inputContainer.addEventListener("click", function(e) {
            var rect = this.getBoundingClientRect();
            if (e.clientX > rect.right - 45) {
                console.log("Click detected on giftcard arrow area");
                checkGiftCard();
            }
        });
    }

    if (window.KILBIL_GIFTCARD && window.KILBIL_GIFTCARD.balance) {
        updateTotalPrice();
    }

    // Обработчики событий Битрикса для отслеживания изменений
    if (typeof BX !== "undefined" && BX.Sale && BX.Sale.OrderAjaxComponent) {
        BX.addCustomEvent("onAjaxSuccess", function() {
            setTimeout(checkAndRestoreGiftCardLine, 100);
        });

        // Также следим за изменениями блоков
        var totalBlocks = document.querySelectorAll(".bx-soa-cart-total");
        totalBlocks.forEach(function(block) {
            var blockObserver = new MutationObserver(function() {
                setTimeout(checkAndRestoreGiftCardLine, 100);
            });
            blockObserver.observe(block, { childList: true, subtree: true });
        });

        // Наблюдаем за блоком доставки
        var deliveryBlock = document.getElementById("bx-soa-delivery");
        if (deliveryBlock) {
            var deliveryObserver = new MutationObserver(function() {
                setTimeout(checkAndRestoreGiftCardLine, 100);
            });
            deliveryObserver.observe(deliveryBlock, { childList: true, subtree: true });
        }
    }

    // Периодически проверяем наличие строки сертификата
    setInterval(checkAndRestoreGiftCardLine, 2000);
}

document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM loaded, trying to insert gift card form");
    insertGiftCardForm();
});

var observer = new MutationObserver(function(mutations) {
    insertGiftCardForm();

    // Также проверяем, не нужно ли восстановить строку с сертификатом
    if (window.KILBIL_GIFTCARD && window.KILBIL_GIFTCARD.balance && window.KILBIL_APPLIED_AMOUNT > 0) {
        setTimeout(function() {
            var existingLines = document.querySelectorAll("#kilbil-giftcard-line");
            if (existingLines.length === 0) {
                console.log("Restoring gift card line after DOM mutation");

                // Получаем все блоки итогов
                var totalBlocks = document.querySelectorAll(".bx-soa-cart-total");
                if (totalBlocks.length > 0) {
                    var foundMutation = false;

                    // Проверяем, затронули ли мутации блоки итогов
                    mutations.forEach(function(mutation) {
                        if (totalBlocks.some(function(block) {
                            return block.contains(mutation.target) || mutation.target.contains(block);
                        })) {
                            foundMutation = true;
                        }
                    });

                    if (foundMutation) {
                        // Восстанавливаем строку с сертификатом и обновляем цену
                        var totalElements = document.querySelectorAll(".bx-soa-cart-total-line-total .bx-soa-cart-d");
                        if (totalElements.length > 0 && window.GIFTCARD_PRICE_UPDATED) {
                            // Добавляем строку
                            if (window.addGiftCardLine) {
                                window.addGiftCardLine(window.KILBIL_APPLIED_AMOUNT);
                            }
                        }
                    }
                }
            }
        }, 100);
    }
});

// Делаем addGiftCardLine глобальной для доступа из наблюдателя
window.addGiftCardLine = function(appliedAmount) {
    try {
        // Удаляем существующие строки с сертификатом, если они есть
        var existingGiftCardLines = document.querySelectorAll("#kilbil-giftcard-line");
        existingGiftCardLines.forEach(function(line) {
            line.remove();
        });

        // Форматируем сумму
        var formattedAmount = Math.round(appliedAmount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        console.log("Global addGiftCardLine formatting amount:", appliedAmount, "to", formattedAmount);

        // Добавляем строку с сертификатом в основной блок
        var mainTotalBlock = document.querySelector(".bx-soa-cart-total:not(.bx-soa-cart-total-fixed)");
        if (mainTotalBlock) {
            var totalLine = mainTotalBlock.querySelector(".bx-soa-cart-total-line-total");
            if (totalLine) {
                var giftCardLine = document.createElement("div");
                giftCardLine.id = "kilbil-giftcard-line";
                giftCardLine.className = "bx-soa-cart-total-line";
                giftCardLine.innerHTML =
                    '<span class="bx-soa-cart-t" style="color: green;">Подарочный сертификат:</span>' +
                    '<span class="bx-soa-cart-d" style="color: green;">-' + formattedAmount + ' руб.</span>';

                totalLine.parentNode.insertBefore(giftCardLine, totalLine);
                console.log("Global: Added gift card line to main total block showing:", formattedAmount, "rubles");
            }
        }

        // Добавляем строку с сертификатом в фиксированный боковой блок
        var fixedTotalBlock = document.querySelector(".bx-soa-cart-total-fixed");
        if (fixedTotalBlock) {
            var fixedTotalLine = fixedTotalBlock.querySelector(".bx-soa-cart-total-line-total");
            if (fixedTotalLine) {
                var fixedGiftCardLine = document.createElement("div");
                fixedGiftCardLine.id = "kilbil-giftcard-line";
                fixedGiftCardLine.className = "bx-soa-cart-total-line";
                fixedGiftCardLine.innerHTML =
                    '<span class="bx-soa-cart-t" style="color: green;">Подарочный сертификат:</span>' +
                    '<span class="bx-soa-cart-d" style="color: green;">-' + formattedAmount + ' руб.</span>';

                fixedTotalLine.parentNode.insertBefore(fixedGiftCardLine, fixedTotalLine);
                console.log("Global: Added gift card line to fixed block showing:", formattedAmount, "rubles");
            }
        }

        return true;
    } catch (e) {
        console.error("Error in global addGiftCardLine:", e);
        console.error(e.stack);
        return false;
    }
};

observer.observe(document.body, { childList: true, subtree: true });
</script>

EOD;

    $content .= $giftcardJs;
}

$eventManager->addEventHandler('main', 'OnBeforeProlog', ['initEvents', 'OnBeforeProlog']);

$eventManager->addEventHandler('sale', 'OnSaleOrderBeforeSaved', ['initEvents', 'saleOrderBeforeSaved']);


class initEvents
{

    const UTMS = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
    ];

    public static function OnBeforeProlog()
    {
        //$domain = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);
        $domain = $_SERVER['HTTP_HOST'];
        $context = \Bitrix\Main\Application::getInstance()->getContext();
        $request = $context->getRequest();
        $isAdminSection = $request->isAdminSection();

        if (!$isAdminSection)
        {
            $server = \Bitrix\Main\Context::getCurrent()->getServer();

            // Обработка UTM-параметров и HTTP_REFERER
            foreach (self::UTMS as $key)
            {
                $value = null;

                // Для http_referer берем значение из $_SERVER
                if ($key === 'http_referer')
                {
                    $value = $server->get('HTTP_REFERER');
                    $expire = time() + 60 * 60 * 24 * 365 * 10; // Долгий срок для HTTP_REFERER
                }
                // Для UTM-параметров берем значение из $_GET
                else if (isset($_GET[$key]) && strlen($_GET[$key]) > 0)
                {
                    $value = $_GET[$key];
                    $expire = time() + 60 * 60 * 24 * 30; // 30 дней для UTM
                }

                // Если значение есть, создаем cookie
                if ($value)
                {
                    $cookie = new \Bitrix\Main\Web\Cookie($key, $value);
                    $cookie->setDomain($domain);
                    \Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
                    setcookie($key, $value, $expire, '/', $cookie->getDomain());
                }
            }
        }
    }

    public static function saleOrderBeforeSaved(Event $event)
    {
        try {
            /** @var Sale\Order $order */
            $order = $event->getParameter("ENTITY");

            /** @var Sale\PropertyValueCollection $propertyCollection */
            $propertyCollection = $order->getPropertyCollection();

            /** @var Sale\PropertyValue $propertyItem */
            foreach ($propertyCollection as $propertyItem) {
                $propCode = $propertyItem->getField("CODE");

                if (in_array($propCode, self::UTMS) && !empty($_COOKIE[$propCode])) {
                    // Санитизация значения cookie
                    $cookieValue = filter_var($_COOKIE[$propCode], FILTER_SANITIZE_STRING);
                    if ($cookieValue !== false) {
                        $propertyItem->setValue($cookieValue);
                    }
                }
            }
        } catch (Exception $e) {
            // Логирование ошибки
            Main\Diag\Debug::writeToFile(
                "Ошибка в saleOrderBeforeSaved: " . $e->getMessage(),
                "error",
                "/local/logs/sale_order_errors.log"
            );
            throw new Main\SystemException("Ошибка при обработке свойств заказа");
        }
    }
}
?>
