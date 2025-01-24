<?
use Bitrix\Main;
use Bitrix\Main\EventManager; ///D7 события
use Bitrix\Main\Diag\Debug;
use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Order;
use Bitrix\Sale;
use Yandex\Market;

CModule::IncludeModule("iblock");

require_once(dirname(dirname(__DIR__)).'/vue/vendor/autoload.php');

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
        echo nl2br(date("H:i:s") . " " . $msg . "\n");                    // оставьте эту строчку если хотите увидеть это в ответе для 1С

        $startImportCatalogTimer = microtime(true);                // начало измерения импорта каталога
        $ret = CAEDucemUpdateAfterExchange();   //скрипт обработки каталога
        $stopImportCatalogTimer = microtime(true);                    // конец измерения импорта каталога

        $msg = "После загрузки всех XML пакетов. Стоп";
        echo nl2br(date("H:i:s") . " " . $msg . "\n");                    // оставьте эту строчку если хотите увидеть это в ответе для 1С
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

    $oldProducts = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $iblockId,
            'PROPERTY_HIT' => $propValueId
        ],
        false,
        false,
        ['ID']
    );

    while ($product = $oldProducts->Fetch()) {
        CIBlockElement::SetPropertyValuesEx($product['ID'], $iblockId, ['HIT' => false]);
    }


    $dateThreshold = new \Bitrix\Main\Type\DateTime();
    $dateThreshold->add("-30 days");

    $newProducts = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $iblockId,
            '>DATE_CREATE' => $dateThreshold
        ],
        false,
        false,
        ['ID']
    );

    while ($product = $newProducts->Fetch()) {
        CIBlockElement::SetPropertyValuesEx($product['ID'], $iblockId, ['HIT' => $propValueId]);
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
        ['ID']
    );

    while ($product = $oldProducts->Fetch()) {
        CIBlockElement::SetPropertyValuesEx($product['ID'], $iblockId, ['HIT' => false]);
    }

    $query = \Bitrix\Main\Application::getConnection()->query("
    SELECT
        B.PRODUCT_ID,
        SUM(B.QUANTITY) as TOTAL_SOLD
    FROM
        b_sale_basket B
    INNER JOIN
        b_iblock_element E ON E.ID = B.PRODUCT_ID
    WHERE
        E.IBLOCK_ID = {$iblockId} AND B.ORDER_ID > 0
    GROUP BY
        B.PRODUCT_ID
    ORDER BY
        TOTAL_SOLD DESC
    LIMIT 100
");

    while ($row = $query->fetch()) {
        CIBlockElement::SetPropertyValuesEx($row['PRODUCT_ID'], $iblockId, ['HIT' => $propValueId]);
    }

    return "updateHitProducts();";
}



?>
