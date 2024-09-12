<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\SectionPropertyTable;

/*DEMO CODE for component inheritance
CBitrixComponent::includeComponentClass("bitrix::news.base");
class CBitrixCatalogSmartFilter extends CBitrixNewsBase
*/

class CBitrixBrandList extends CBitrixComponent
{
    public $IBLOCK_ID = 0;
    public $SKU_IBLOCK_ID = 0;
    public $SKU_PROPERTY_ID = 0;
    public $SECTION_ID = 0;
    public $FILTER_NAME = "";
    public $SAFE_FILTER_NAME = "";

    protected $cache = array();
    protected static $catalogIncluded = null;
    protected static $iblockIncluded = null;
    /** @var \Bitrix\Iblock\PropertyIndex\Facet * */
    protected $facet = null;

    public function onPrepareComponentParams($arParams)
    {
        $arParams["CACHE_TIME"] = (int)($arParams["CACHE_TIME"] ?? 36000000);
        $arParams["IBLOCK_ID"] = (int)($arParams["IBLOCK_ID"] ?? 0);
        $arParams["SECTION_ID"] = (int)($arParams["SECTION_ID"] ?? 0);
        if ($arParams["SECTION_ID"] <= 0 && Loader::includeModule('iblock')) {
            $arParams["SECTION_ID"] = CIBlockFindTools::GetSectionID(
                $arParams["SECTION_ID"],
                $arParams["SECTION_CODE"],
                array(
                    "GLOBAL_ACTIVE" => "Y",
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                )
            );
            if (!$arParams["SECTION_ID"] && $arParams["SECTION_CODE_PATH"] <> '') {
                $arParams["SECTION_ID"] = CIBlockFindTools::GetSectionIDByCodePath(
                    $arParams["IBLOCK_ID"],
                    $arParams["SECTION_CODE_PATH"]
                );
            }
        }

        $arParams['SMART_FILTER_PATH'] = (string)($arParams['SMART_FILTER_PATH'] ?? '');
        $arParams["SAVE_IN_SESSION"] = ($arParams["SAVE_IN_SESSION"] ?? 'N') === "Y";
        $arParams["CACHE_GROUPS"] = ($arParams["CACHE_GROUPS"] ?? '') !== "N";
        $arParams["INSTANT_RELOAD"] = ($arParams["INSTANT_RELOAD"] ?? '') === "Y";

        $arParams["SECTION_TITLE"] = trim((string)($arParams["SECTION_TITLE"] ?? ''));
        $arParams["SECTION_DESCRIPTION"] = trim((string)($arParams["SECTION_DESCRIPTION"] ?? ''));

        $arParams["FILTER_NAME"] = (string)($arParams["FILTER_NAME"] ?? '');
        if (
            $arParams["FILTER_NAME"] === ''
            || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])
        ) {
            $arParams["FILTER_NAME"] = "arrFilter";
        }
        $arParams["PREFILTER_NAME"] = (string)($arParams["PREFILTER_NAME"] ?? '');
        if (
            $arParams["PREFILTER_NAME"] === ''
            || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PREFILTER_NAME"])
        ) {
            $arParams["PREFILTER_NAME"] = "smartPreFilter";
        }


        return $arParams;
    }

    public function executeComponent()
    {
        $this->IBLOCK_ID = $this->arParams["IBLOCK_ID"];
        $this->SECTION_ID = $this->arParams["SECTION_ID"];
        $this->FILTER_NAME = $this->arParams["FILTER_NAME"];
        $this->SAFE_FILTER_NAME = htmlspecialcharsbx($this->FILTER_NAME);
        $this->arResult['FILTER_NAME'] = $this->FILTER_NAME;


        if (self::$iblockIncluded === null)
            self::$iblockIncluded = Loader::includeModule('iblock');
        if (!self::$iblockIncluded)
            return '';

        if (self::$catalogIncluded === null)
            self::$catalogIncluded = Loader::includeModule('catalog');
        if (self::$catalogIncluded) {
            $arCatalog = CCatalogSKU::GetInfoByProductIBlock($this->IBLOCK_ID);
            if (!empty($arCatalog)) {
                $this->SKU_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
                $this->SKU_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
            }
        }

        $this->facet = new \Bitrix\Iblock\PropertyIndex\Facet($this->IBLOCK_ID);

        return parent::executeComponent();
    }

    public function getIBlockItems($IBLOCK_ID)
    {
        $items = array();

        foreach (CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $this->SECTION_ID) as $PID => $arLink) {
            if ($arLink["SMART_FILTER"] !== "Y")
                continue;

            if ($arLink["ACTIVE"] === "N")
                continue;

            if ($arLink['FILTER_HINT'] !== null && $arLink['FILTER_HINT'] !== '') {
                $arLink['FILTER_HINT'] = CTextParser::closeTags($arLink['FILTER_HINT']);
            }

            $rsProperty = CIBlockProperty::GetByID($PID);
            $arProperty = $rsProperty->Fetch();
            if ($arProperty && $arProperty["CODE"] == 'BRAND') {
                $items[$arProperty["ID"]] = array(
                    "ID" => $arProperty["ID"],
                    "IBLOCK_ID" => $arProperty["IBLOCK_ID"],
                    "CODE" => $arProperty["CODE"],
                    "~NAME" => $arProperty["NAME"],
                    "NAME" => htmlspecialcharsEx($arProperty["NAME"]),
                    "PROPERTY_TYPE" => $arProperty["PROPERTY_TYPE"],
                    "USER_TYPE" => $arProperty["USER_TYPE"],
                    "USER_TYPE_SETTINGS" => $arProperty["USER_TYPE_SETTINGS"],
                    "DISPLAY_TYPE" => $arLink["DISPLAY_TYPE"],
                    "DISPLAY_EXPANDED" => $arLink["DISPLAY_EXPANDED"],
                    "FILTER_HINT" => $arLink["FILTER_HINT"],
                    "VALUES" => array(),
                    "ENCODED_ID" => "", // hack for remove warning in custom templates
                    //PRICE - absent, don't add - check isset in templates
                    //URL_ID - absent
                );

            }
        }

        return $items;
    }


    public function getResultItems()
    {
        $items = $this->getIBlockItems($this->IBLOCK_ID);
        $this->arResult["PROPERTY_COUNT"] = count($items);
        $this->arResult["PROPERTY_ID_LIST"] = array_keys($items);

        if ($this->SKU_IBLOCK_ID) {
            $this->arResult['SKU_PROPERTY_COUNT'] = 0;
            $this->arResult["SKU_PROPERTY_ID_LIST"] = array($this->SKU_PROPERTY_ID);
            foreach ($this->getIBlockItems($this->SKU_IBLOCK_ID) as $PID => $arItem) {
                $items[$PID] = $arItem;
                $this->arResult["SKU_PROPERTY_COUNT"]++;
                $this->arResult["SKU_PROPERTY_ID_LIST"][] = $PID;
            }
        }

        return $items;
    }

    public function processProperties(array &$resultItem, array $elements, array $dictionaryID, array $directoryPredict = [])
    {
        $lookupDictionary = [];
        if (!empty($dictionaryID)) {
            $lookupDictionary = $this->facet->getDictionary()->getStringByIds($dictionaryID);
        }

        if (!empty($directoryPredict)) {
            foreach ($directoryPredict as $directory) {
                if (empty($directory['VALUE']) || !is_array($directory['VALUE']))
                    continue;
                $values = [];
                foreach ($directory['VALUE'] as $item) {
                    if (isset($lookupDictionary[$item]))
                        $values[] = $lookupDictionary[$item];
                }
                if (!empty($values))
                    $this->predictHlFetch($directory['PROPERTY'], $values);
                unset($values);
            }
            unset($directory);
        }

        foreach ($elements as $row) {
            $PID = $row['PID'];
            if ($resultItem["ITEMS"][$PID]["PROPERTY_TYPE"] === PropertyTable::TYPE_NUMBER) {
                $this->fillItemValues($resultItem["ITEMS"][$PID], $row["MIN_VALUE_NUM"]);
                $this->fillItemValues($resultItem["ITEMS"][$PID], $row["MAX_VALUE_NUM"]);
                if ($row["VALUE_FRAC_LEN"] > 0)
                    $resultItem["ITEMS"][$PID]["DECIMALS"] = $row["VALUE_FRAC_LEN"];
            } elseif ($resultItem["ITEMS"][$PID]["DISPLAY_TYPE"] === SectionPropertyTable::CALENDAR) {
                $this->fillItemValues($resultItem["ITEMS"][$PID], FormatDate("Y-m-d", $row["MIN_VALUE_NUM"]));
                $this->fillItemValues($resultItem["ITEMS"][$PID], FormatDate("Y-m-d", $row["MAX_VALUE_NUM"]));
            } elseif ($resultItem["ITEMS"][$PID]["PROPERTY_TYPE"] === PropertyTable::TYPE_STRING) {
                $addedKey = $this->fillItemValues($resultItem["ITEMS"][$PID], $lookupDictionary[$row["VALUE"]], true);
                if ($addedKey <> '') {
                    $resultItem["ITEMS"][$PID]["VALUES"][$addedKey]["FACET_VALUE"] = $row["VALUE"];
                    $resultItem["ITEMS"][$PID]["VALUES"][$addedKey]["ELEMENT_COUNT"] = $row["ELEMENT_COUNT"];
                }
            } else {
                $addedKey = $this->fillItemValues($resultItem["ITEMS"][$PID], $row["VALUE"], true);
                if ($addedKey <> '') {
                    $resultItem["ITEMS"][$PID]["VALUES"][$addedKey]["FACET_VALUE"] = $row["VALUE"];
                    $resultItem["ITEMS"][$PID]["VALUES"][$addedKey]["ELEMENT_COUNT"] = $row["ELEMENT_COUNT"];
                }
            }
        }
    }

    public function predictIBSectionFetch($id = array())
    {
        if (!is_array($id) || empty($id)) {
            return;
        }

        $arLinkFilter = array(
            "ID" => $id,
            "GLOBAL_ACTIVE" => "Y",
            "CHECK_PERMISSIONS" => "Y",
        );

        $link = CIBlockSection::GetList(array(), $arLinkFilter, false, array("ID", "IBLOCK_ID", "NAME", "LEFT_MARGIN", "DEPTH_LEVEL", "CODE"));
        while ($sec = $link->Fetch()) {
            $this->cache['G'][$sec['ID']] = $sec;
            $this->cache['G'][$sec['ID']]['DEPTH_NAME'] = str_repeat(".", $sec["DEPTH_LEVEL"]) . $sec["NAME"];
        }
        unset($sec);
        unset($link);
    }

    public function predictIBElementFetch($id = array())
    {
        if (!is_array($id) || empty($id)) {
            return;
        }

        $linkFilter = array(
            "ID" => $id,
            "ACTIVE" => "Y",
            "ACTIVE_DATE" => "Y",
            "CHECK_PERMISSIONS" => "Y",
        );

        $link = CIBlockElement::GetList(array(), $linkFilter, false, false, array("ID", "IBLOCK_ID", "NAME", "SORT", "CODE"));
        while ($el = $link->Fetch()) {
            $this->cache['E'][$el['ID']] = $el;
        }
        unset($el);
        unset($link);
    }

    public function predictHlFetch($userType, $valueIDs)
    {
        $values = call_user_func_array(
            $userType['GetExtendedValue'],
            array(
                $userType,
                array("VALUE" => $valueIDs),
            )
        );

        foreach ($values as $key => $value) {
            $this->cache[$userType['PID']][$key] = $value;
        }
    }


    public function fillItemValues(&$resultItem, $arProperty, $flag = null)
    {
        if (is_array($arProperty)) {
            if (isset($arProperty["PRICE"])) {
                return null;
            }
            $key = $arProperty["VALUE"];
            $PROPERTY_TYPE = $arProperty["PROPERTY_TYPE"];
            $PROPERTY_USER_TYPE = $arProperty["USER_TYPE"];
            $PROPERTY_ID = $arProperty["ID"];
        } else {
            $key = $arProperty;
            $PROPERTY_TYPE = $resultItem["PROPERTY_TYPE"];
            $PROPERTY_USER_TYPE = $resultItem["USER_TYPE"];
            $PROPERTY_ID = $resultItem["ID"];
            $arProperty = $resultItem;
        }

        if ($PROPERTY_TYPE === PropertyTable::TYPE_FILE) {
            return null;
        } elseif ($PROPERTY_TYPE === PropertyTable::TYPE_NUMBER) {
            $convertKey = (float)$key;
            if ($key == '') {
                return null;
            }

            if (
                !isset($resultItem["VALUES"]["MIN"])
                || !array_key_exists("VALUE", $resultItem["VALUES"]["MIN"])
                || doubleval($resultItem["VALUES"]["MIN"]["VALUE"]) > $convertKey
            )
                $resultItem["VALUES"]["MIN"]["VALUE"] = preg_replace("/\\.0+\$/", "", $key);

            if (
                !isset($resultItem["VALUES"]["MAX"])
                || !array_key_exists("VALUE", $resultItem["VALUES"]["MAX"])
                || doubleval($resultItem["VALUES"]["MAX"]["VALUE"]) < $convertKey
            )
                $resultItem["VALUES"]["MAX"]["VALUE"] = preg_replace("/\\.0+\$/", "", $key);

            return null;
        } elseif ($arProperty["DISPLAY_TYPE"] === SectionPropertyTable::CALENDAR) {
            $date = mb_substr($key, 0, 10);
            if (!$date) {
                return null;
            }
            $timestamp = MakeTimeStamp($date, "YYYY-MM-DD");
            if (!$timestamp) {
                return null;
            }

            if (
                !isset($resultItem["VALUES"]["MIN"])
                || !array_key_exists("VALUE", $resultItem["VALUES"]["MIN"])
                || $resultItem["VALUES"]["MIN"]["VALUE"] > $timestamp
            )
                $resultItem["VALUES"]["MIN"]["VALUE"] = $timestamp;

            if (
                !isset($resultItem["VALUES"]["MAX"])
                || !array_key_exists("VALUE", $resultItem["VALUES"]["MAX"])
                || $resultItem["VALUES"]["MAX"]["VALUE"] < $timestamp
            )
                $resultItem["VALUES"]["MAX"]["VALUE"] = $timestamp;

            return null;
        } elseif ($PROPERTY_TYPE === PropertyTable::TYPE_ELEMENT && $key <= 0) {
            return null;
        } elseif ($PROPERTY_TYPE === PropertyTable::TYPE_SECTION && $key <= 0) {
            return null;
        } elseif ($key == '') {
            return null;
        }

        $arUserType = array();
        if ($PROPERTY_USER_TYPE != "") {
            $arUserType = CIBlockProperty::GetUserType($PROPERTY_USER_TYPE);
            if (isset($arUserType["GetExtendedValue"]))
                $PROPERTY_TYPE = "Ux";
            elseif (isset($arUserType["GetPublicViewHTML"]))
                $PROPERTY_TYPE = "U";
        }

        if ($PROPERTY_USER_TYPE === \CIBlockPropertyDateTime::USER_TYPE) {
            $key = call_user_func_array(
                $arUserType["GetPublicViewHTML"],
                array(
                    $arProperty,
                    array("VALUE" => $key),
                    array("MODE" => "SIMPLE_TEXT", "DATETIME_FORMAT" => "SHORT"),
                )
            );
            $PROPERTY_TYPE = PropertyTable::TYPE_STRING;
        }

        $htmlKey = htmlspecialcharsbx($key);
        if (isset($resultItem["VALUES"][$htmlKey])) {
            return $htmlKey;
        }

        $file_id = null;
        $url_id = null;

        switch ($PROPERTY_TYPE) {
            case PropertyTable::TYPE_LIST:
                $enum = CIBlockPropertyEnum::GetByID($key);
                if ($enum) {
                    $value = $enum["VALUE"];
                    $sort = $enum["SORT"];
                    $url_id = toLower($enum["XML_ID"]);
                } else {
                    return null;
                }
                break;
            case PropertyTable::TYPE_ELEMENT:
                if (!isset($this->cache[$PROPERTY_TYPE][$key])) {
                    $this->predictIBElementFetch(array($key));
                }

                if (!$this->cache[$PROPERTY_TYPE][$key])
                    return null;

                $value = $this->cache[$PROPERTY_TYPE][$key]["NAME"];
                $sort = $this->cache[$PROPERTY_TYPE][$key]["SORT"];
                if ($this->cache[$PROPERTY_TYPE][$key]["CODE"])
                    $url_id = toLower($this->cache[$PROPERTY_TYPE][$key]["CODE"]);
                else
                    $url_id = toLower($value);
                break;
            case PropertyTable::TYPE_SECTION:
                if (!isset($this->cache[$PROPERTY_TYPE][$key])) {
                    $this->predictIBSectionFetch(array($key));
                }

                if (!$this->cache[$PROPERTY_TYPE][$key])
                    return null;

                $value = $this->cache[$PROPERTY_TYPE][$key]['DEPTH_NAME'];
                $sort = $this->cache[$PROPERTY_TYPE][$key]["LEFT_MARGIN"];
                if ($this->cache[$PROPERTY_TYPE][$key]["CODE"])
                    $url_id = toLower($this->cache[$PROPERTY_TYPE][$key]["CODE"]);
                else
                    $url_id = toLower($value);
                break;
            case "U":
                if (!isset($this->cache[$PROPERTY_ID]))
                    $this->cache[$PROPERTY_ID] = array();

                if (!isset($this->cache[$PROPERTY_ID][$key])) {
                    $this->cache[$PROPERTY_ID][$key] = call_user_func_array(
                        $arUserType["GetPublicViewHTML"],
                        array(
                            $arProperty,
                            array("VALUE" => $key),
                            array("MODE" => "SIMPLE_TEXT"),
                        )
                    );
                }

                $value = $this->cache[$PROPERTY_ID][$key];
                $sort = 0;
                $url_id = toLower($value);
                break;
            case "Ux":
                if (!isset($this->cache[$PROPERTY_ID]))
                    $this->cache[$PROPERTY_ID] = array();

                if (!isset($this->cache[$PROPERTY_ID][$key])) {
                    $this->cache[$PROPERTY_ID][$key] = call_user_func_array(
                        $arUserType["GetExtendedValue"],
                        array(
                            $arProperty,
                            array("VALUE" => $key),
                        )
                    );
                }

                if ($this->cache[$PROPERTY_ID][$key]) {
                    $value = $this->cache[$PROPERTY_ID][$key]['VALUE'];
                    $file_id = $this->cache[$PROPERTY_ID][$key]['FILE_ID'];
                    $sort = ($this->cache[$PROPERTY_ID][$key]['SORT'] ?? 0);
                    $url_id = toLower($this->cache[$PROPERTY_ID][$key]['UF_XML_ID']);
                } else {
                    return null;
                }
                break;
            default:
                $value = $key;
                $sort = 0;
                $url_id = toLower($value);
                break;
        }

        $keyCrc = abs(crc32($htmlKey));
        $safeValue = htmlspecialcharsex($value);
        $sort = (int)$sort;

        $filterPropertyID = $this->SAFE_FILTER_NAME . '_' . $PROPERTY_ID;
        $filterPropertyIDKey = $filterPropertyID . '_' . $keyCrc;
        $resultItem["VALUES"][$htmlKey] = array(
            "CONTROL_ID" => $filterPropertyIDKey,
            "CONTROL_NAME" => $filterPropertyIDKey,
            "CONTROL_NAME_ALT" => $filterPropertyID,
            "HTML_VALUE_ALT" => $keyCrc,
            "HTML_VALUE" => "Y",
            "VALUE" => $safeValue,
            "SORT" => $sort,
            "UPPER" => ToUpper($safeValue),
            "FLAG" => $flag,
        );

        if ($file_id) {
            $resultItem["VALUES"][$htmlKey]['FILE'] = CFile::GetFileArray($file_id);
        }

        if ($url_id <> '') {
            $error = "";
            $utf_id = \Bitrix\Main\Text\Encoding::convertEncoding($url_id, LANG_CHARSET, "utf-8");
            $resultItem["VALUES"][$htmlKey]['URL_ID'] = rawurlencode(str_replace("/", "-", $utf_id));
        }

        return $htmlKey;
    }


    public function _sort($v1, $v2)
    {
        if (!isset($v1["SORT"]) && !isset($v2["SORT"]) && !isset($v1["UPPER"]) && !isset($v2["UPPER"])) {
            return 0;
        }
        if ($v1["SORT"] < $v2["SORT"])
            return -1;
        elseif ($v1["SORT"] > $v2["SORT"])
            return 1;
        else
            return strcmp($v1["UPPER"], $v2["UPPER"]);
    }

    /*
    This function takes an array (arTuple) which is mix of scalar values and arrays
    and return "rectangular" array of arrays.
    For example:
    array(1, array(1, 2), 3, arrays(4, 5))
    will be transformed as
    array(
        array(1, 1, 3, 4),
        array(1, 1, 3, 5),
        array(1, 2, 3, 4),
        array(1, 2, 3, 5),
    )
    */
    function ArrayMultiply(&$arResult, $arTuple, $arTemp = array())
    {
        if ($arTuple) {
            reset($arTuple);
            $key = key($arTuple);
            $head = $arTuple[$key];
            unset($arTuple[$key]);
            $arTemp[$key] = false;
            if (is_array($head)) {
                if (empty($head)) {
                    if (empty($arTuple))
                        $arResult[] = $arTemp;
                    else
                        $this->ArrayMultiply($arResult, $arTuple, $arTemp);
                } else {
                    foreach ($head as $value) {
                        $arTemp[$key] = $value;
                        if (empty($arTuple))
                            $arResult[] = $arTemp;
                        else
                            $this->ArrayMultiply($arResult, $arTuple, $arTemp);
                    }
                }
            } else {
                $arTemp[$key] = $head;
                if (empty($arTuple))
                    $arResult[] = $arTemp;
                else
                    $this->ArrayMultiply($arResult, $arTuple, $arTemp);
            }
        } else {
            $arResult[] = $arTemp;
        }
    }

    function makeFilter($FILTER_NAME)
    {
        $bOffersIBlockExist = false;
        if (self::$catalogIncluded === null)
            self::$catalogIncluded = Loader::includeModule('catalog');
        if (self::$catalogIncluded) {
            $arCatalog = CCatalogSKU::GetInfoByProductIBlock($this->IBLOCK_ID);
            if (!empty($arCatalog)) {
                $bOffersIBlockExist = true;
            }
        }

        $gFilter = $GLOBALS[$FILTER_NAME];

        $arFilter = array(
            "IBLOCK_ID" => $this->IBLOCK_ID,
            "IBLOCK_LID" => SITE_ID,
            "IBLOCK_ACTIVE" => "Y",
            "ACTIVE_DATE" => "Y",
            "ACTIVE" => "Y",
            "CHECK_PERMISSIONS" => "Y",
            "MIN_PERMISSION" => "R",
            "INCLUDE_SUBSECTIONS" => (($this->arParams["INCLUDE_SUBSECTIONS"] ?? '') !== 'N' ? 'Y' : 'N'),
        );
        if (($this->SECTION_ID > 0) || ($this->arParams["SHOW_ALL_WO_SECTION"] !== "Y")) {
            $arFilter["SECTION_ID"] = $this->SECTION_ID;
        }

        if ($this->arParams['HIDE_NOT_AVAILABLE'] == 'Y')
            $arFilter['AVAILABLE'] = 'Y';

        if (self::$catalogIncluded && $bOffersIBlockExist) {


            if (!empty($gFilter["OFFERS"])) {

                $arSubFilter = $gFilter["OFFERS"];


                $arSubFilter["IBLOCK_ID"] = $this->SKU_IBLOCK_ID;
                $arSubFilter["ACTIVE_DATE"] = "Y";
                $arSubFilter["ACTIVE"] = "Y";
                if ('Y' == $this->arParams['HIDE_NOT_AVAILABLE'])
                    $arSubFilter['AVAILABLE'] = 'Y';
                $arFilter['=SUBQUERY'] = [
                    'FIELD' => 'PROPERTY_' . $this->SKU_PROPERTY_ID,
                    'FILTER' => $arSubFilter,
                ];
            }

            unset($gFilter["OFFERS"]);
        }

        return array_merge($gFilter, $arFilter);
    }


    public function searchProperty($items, $lookupValue)
    {
        foreach ($items as $itemId => $arItem) {
            if (!(isset($arItem["PRICE"]) && $arItem["PRICE"])) {
                $code = toLower($arItem["CODE"]);
                if ($lookupValue === $code)
                    return $itemId;
                if ($lookupValue == intval($arItem["ID"]))
                    return $itemId;
            }
        }
        return null;
    }

    public function searchValue($item, $lookupValue)
    {
        $error = "";
        $searchValue = \Bitrix\Main\Text\Encoding::convertEncoding($lookupValue, LANG_CHARSET, "utf-8", $error);
        if (!$error) {
            $encodedValue = rawurlencode($searchValue);
            foreach ($item as $itemId => $arValue) {
                if ($encodedValue === $arValue["URL_ID"])
                    return $itemId;
            }
        }
        return false;
    }

    public function convertUrlToCheck($url)
    {
        $result = array();
        $smartParts = explode("/", $url);
        foreach ($smartParts as $smartPart) {
            $item = false;
            $smartPart = preg_split("/-(from|to|is|or)-/", $smartPart, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($smartPart as $i => $smartElement) {
                if ($i == 0) {
                    $itemId = $this->searchProperty($this->arResult["ITEMS"], $smartElement);

                    if (isset($itemId))
                        $item = &$this->arResult["ITEMS"][$itemId];
                    else
                        break;
                } elseif ($smartElement === "from") {
                    $result[$item["VALUES"]["MIN"]["CONTROL_NAME"]] = $smartPart[$i + 1];
                } elseif ($smartElement === "to") {
                    $result[$item["VALUES"]["MAX"]["CONTROL_NAME"]] = $smartPart[$i + 1];
                } elseif ($smartElement === "is" || $smartElement === "or") {
                    $valueId = $this->searchValue($item["VALUES"], $smartPart[$i + 1]);
                    if ($valueId !== false) {
                        $result[$item["VALUES"][$valueId]["CONTROL_NAME"]] = $item["VALUES"][$valueId]["HTML_VALUE"];
                    }
                }
            }
            unset($item);
        }
        return $result;
    }

    public function makeSmartUrl($url, $apply, $checkedControlId = false)
    {
        $smartParts = array();

        if ($apply) {
            foreach ($this->arResult["ITEMS"] as $PID => $arItem) {
                $smartPart = array();
                //Prices
                if (isset($arItem["PRICE"]) && $arItem["PRICE"]) {
                    if ($arItem["VALUES"]["MIN"]["HTML_VALUE"] <> '')
                        $smartPart["from"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
                    if ($arItem["VALUES"]["MAX"]["HTML_VALUE"] <> '')
                        $smartPart["to"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
                }

                if ($smartPart) {
                    array_unshift($smartPart, "price-" . $arItem["URL_ID"]);

                    $smartParts[] = $smartPart;
                }
            }

            foreach ($this->arResult["ITEMS"] as $PID => $arItem) {
                $smartPart = array();
                if (isset($arItem["PRICE"]) && $arItem["PRICE"])
                    continue;

                //Numbers && calendar == ranges
                if (
                    $arItem["PROPERTY_TYPE"] === PropertyTable::TYPE_NUMBER
                    || $arItem["DISPLAY_TYPE"] === SectionPropertyTable::CALENDAR
                ) {
                    if ($arItem["VALUES"]["MIN"]["HTML_VALUE"] <> '')
                        $smartPart["from"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
                    if ($arItem["VALUES"]["MAX"]["HTML_VALUE"] <> '')
                        $smartPart["to"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
                } else {
                    foreach ($arItem["VALUES"] as $key => $ar) {
                        if (
                            (
                                $ar["CHECKED"]
                                || $ar["CONTROL_ID"] === $checkedControlId
                            )
                            && mb_strlen($ar["URL_ID"])
                        ) {
                            $smartPart[] = $ar["URL_ID"];
                        }
                    }
                }

                if ($smartPart) {
                    if ($arItem["CODE"])
                        array_unshift($smartPart, toLower($arItem["CODE"]));
                    else
                        array_unshift($smartPart, $arItem["ID"]);

                    $smartParts[] = $smartPart;
                }
            }
        }

        if (!$smartParts)
            $smartParts[] = array("clear");

        return str_replace("#SMART_FILTER_PATH#", implode("/", $this->encodeSmartParts($smartParts)), $url);
    }

    public function encodeSmartParts($smartParts)
    {
        foreach ($smartParts as &$smartPart) {
            $urlPart = "";
            foreach ($smartPart as $i => $smartElement) {
                if (!$urlPart)
                    $urlPart .= $smartElement;
                elseif ($i == 'from' || $i == 'to')
                    $urlPart .= '-' . $i . '-' . $smartElement;
                elseif ($i == 1)
                    $urlPart .= '-is-' . $smartElement;
                else
                    $urlPart .= '-or-' . $smartElement;
            }
            $smartPart = $urlPart;
        }
        unset($smartPart);
        return $smartParts;
    }


    public function setIblockTag()
    {
        if (
            defined('BX_COMP_MANAGED_CACHE')
        ) {
            \CIBlock::registerWithTagCache($this->IBLOCK_ID);
            if ($this->SKU_IBLOCK_ID > 0)
                \CIBlock::registerWithTagCache($this->SKU_IBLOCK_ID);
        }
    }
}
