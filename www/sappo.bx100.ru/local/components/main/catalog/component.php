<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
        use Bitrix\Highloadblock\HighloadBlockTable;
        CModule::IncludeModule('highloadblock');
/** @global CMain $APPLICATION */
if (isset($arParams["USE_FILTER"]) && $arParams["USE_FILTER"]=="Y")
{
	$arParams["FILTER_NAME"] = trim($arParams["FILTER_NAME"]);
	if ($arParams["FILTER_NAME"] === '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
		$arParams["FILTER_NAME"] = "arrFilter";
}
else
	$arParams["FILTER_NAME"] = "";

//default gifts
if(empty($arParams['USE_GIFTS_SECTION']))
{
	$arParams['USE_GIFTS_SECTION'] = 'Y';
}
if(empty($arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT']))
{
	$arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'] = 3;
}
if(empty($arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT']))
{
	$arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'] = 4;
}
if(empty($arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT']))
{
	$arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'] = 4;
}

$arParams['ACTION_VARIABLE'] = (isset($arParams['ACTION_VARIABLE']) ? trim($arParams['ACTION_VARIABLE']) : 'action');
if ($arParams["ACTION_VARIABLE"] == '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["ACTION_VARIABLE"]))
	$arParams["ACTION_VARIABLE"] = "action";

$smartBase = ($arParams["SEF_URL_TEMPLATES"]["section"]? $arParams["SEF_URL_TEMPLATES"]["section"]: "#SECTION_ID#/");
$arDefaultUrlTemplates404 = array(
	"sections" => "",
	"section" => "#SECTION_ID#/",
	"element" => "#SECTION_ID#/#ELEMENT_ID#/",
	"compare" => "compare.php?action=COMPARE",
	"smart_filter" => $smartBase."filter/#SMART_FILTER_PATH#/apply/"
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
	"SECTION_ID",
	"SECTION_CODE",
	"ELEMENT_ID",
	"ELEMENT_CODE",
	"action",
);

if($arParams["SEF_MODE"] == "Y")
{
	$arVariables = array();

	$engine = new CComponentEngine($this);
	if (\Bitrix\Main\Loader::includeModule('iblock'))
	{
		$engine->addGreedyPart("#SECTION_CODE_PATH#");
		$engine->addGreedyPart("#SMART_FILTER_PATH#");
		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
	}
	$arUrlTemplates = CComponentEngine::makeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
	$arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_FOLDER"],
		$arUrlTemplates,
		$arVariables
	);

	if ($componentPage === "smart_filter")
		$componentPage = "section";

	if(!$componentPage && isset($_REQUEST["q"]))
		$componentPage = "search";

	$b404 = false;
	if(!$componentPage)
	{
		$componentPage = "sections";
		$b404 = true;
	}

	if($componentPage == "section")
	{
		if (isset($arVariables["SECTION_ID"]))
			$b404 |= (intval($arVariables["SECTION_ID"])."" !== $arVariables["SECTION_ID"]);
		else
			$b404 |= !isset($arVariables["SECTION_CODE"]);
	}
//    if ($arParams['IBLOCK_ID'] == 42) {
//        // Проверка на нахождение на старой ссылке (Редирект на новую)
//
//        $catalogUrl = $APPLICATION->GetCurDir();
//
//        $result = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['=NAME' => "CatalogFilterData"]]);
//        if ($row = $result->fetch()) {
//            $hlblockId = $row["ID"];
//        }
//        if ($hlblockId) {
//            $hlblock = HighloadBlockTable::getById($hlblockId)->fetch();
//            $entity = HighloadBlockTable::compileEntity($hlblock);
//            $entityDataClass = $entity->getDataClass();
//            $hlblockDataQuery = $entityDataClass::getList(
//                [
//                    "select" => ["*"],
//                    "filter" => ["UF_CATALOG_FILTER_URL" => $catalogUrl],
//                ]
//            );
//        }
//        $hlBlockData = $hlblockDataQuery->Fetch();
//
//        if (!empty($hlBlockData['UF_CATALOG_FILTER_URL_NEW']) && !isset($_GET['ajax'])) {
//            LocalRedirect($hlBlockData['UF_CATALOG_FILTER_URL_NEW'], false, '301 Moved permanently');
//        } else {
//            // Проверка на нахождение на новой ссылке (Формирование массива параметров)
//            $result = HighloadBlockTable::getList(['filter' => ['=NAME' => "CatalogFilterData"]]);
//            if ($row = $result->fetch()) {
//                $hlblockId = $row["ID"];
//            }
//            if ($hlblockId) {
//                $hlblock = HighloadBlockTable::getById($hlblockId)->fetch();
//                $entity = HighloadBlockTable::compileEntity($hlblock);
//                $entityDataClass = $entity->getDataClass();
//                $hlblockDataQuery = $entityDataClass::getList(
//                    [
//                        "select" => ["*"],
//                        "filter" => ["UF_CATALOG_FILTER_URL_NEW" => $catalogUrl],
//                    ]
//                );
//            }
//                $hlBlockData = $hlblockDataQuery->Fetch();
//
//            if (!empty($hlBlockData['ID'])) {
//                $arCatalogUrl = explode('/', $catalogUrl);
//                $sectionCode = $arCatalogUrl[count($arCatalogUrl) - 3];
//                $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'CODE' => $sectionCode);
//                $arSection = CIBlockSection::GetList([], $arFilter)->Fetch();
//
//                // Полный путь из разделов каталога до текущего
//                $codePath = str_replace('/catalog/', '', makeFullPath($arSection['ID']));
//                $codePath = str_replace($arSection['CODE'] . '/', $arSection['CODE'], $codePath);
//
//                // Параметры фильтра
//                $filterPath = str_replace('/apply/', '', explode('/filter/', $hlBlockData['UF_CATALOG_FILTER_URL'])[1]);
//
//                $arVariables = array(
//                    'SECTION_CODE_PATH' => $codePath,
//                    'SECTION_ID' => $arSection['ID'],
//                    'SECTION_CODE' => $arSection['CODE'],
//                    'SMART_FILTER_PATH' => $filterPath,
//                    'IS_CUSTOM_CPU' => 'Y',
//                    'CUSTOM_VALUES' => array(
//                        'CUSTOM_URL' => $hlBlockData['UF_CATALOG_FILTER_URL_NEW'],
//                        'PAGE_META' => array(
//                            'TITLE' => $hlBlockData['UF_CATALOG_FILTER_TITLE'],
//                            'DESC' => $hlBlockData['UF_CATALOG_FILTER_DESCRIPTION'],
//                            'TEXT' => $hlBlockData['UF_CATALOG_FILTER_TEXT'],
//                            'H1' => $hlBlockData['UF_CATALOG_FILTER_H1']
//                        )
//                    ),
//                );
//
//                $b404 = false;
//                $componentPage = 'section';
//            }
//        }
//    }
    /* ------ END Настройка ручного ЧПУ фильтра ------ */

	if($b404 && CModule::IncludeModule('iblock'))
	{

		$folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
		if ($folder404 != "/") {
            $folder404 = "/" . trim($folder404, "/ \t\n\r\0\x0B") . "/";
        }
		if (mb_substr($folder404, -1) == "/"){

            $folder404 .= "index.php";
        }

        file_get_contents('https://webhook.site/ad56c3e4-edf7-425c-8dfc-4816c38ba084?='.json_encode([
                $folder404,
                $APPLICATION->GetCurPage(true)
            ]));

        if ($folder404 != $APPLICATION->GetCurPage(true))
		{
            //проверим, не является ли это элементом в продукт

            $curUrl = explode('/', $arVariables["SECTION_CODE_PATH"]);
            $elemId = (int) end($curUrl);

            file_get_contents('https://webhook.site/ad56c3e4-edf7-425c-8dfc-4816c38ba084?='.json_encode($elemId));

            $rsElements = CIBlockElement::GetList(array("ID" => 'asc'), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => $elemId, "!PROPERTY_PRODUCT" => false), false, false, array("ID", "NAME", "DETAIL_PAGE_URL"));
            $rsElements->SetUrlTemplates("/product/#ELEMENT_CODE#/");


            if($arElement = $rsElements->GetNext())
            {
                if($arElement["ID"] == $elemId){
                    LocalRedirect($arElement["DETAIL_PAGE_URL"], false, '301 Moved permanently');
                }
            }

			\Bitrix\Iblock\Component\Tools::process404(
				""
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SHOW_404"] === "Y")
				,$arParams["FILE_404"]
			);
		}
	}

	CComponentEngine::initComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
	$arResult = array(
		"FOLDER" => $arParams["SEF_FOLDER"],
		"URL_TEMPLATES" => $arUrlTemplates,
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}
else
{
	$arVariables = array();

	$arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
	CComponentEngine::initComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

	$componentPage = "";

	$arCompareCommands = array(
		"COMPARE",
		"DELETE_FEATURE",
		"ADD_FEATURE",
		"DELETE_FROM_COMPARE_RESULT",
		"ADD_TO_COMPARE_RESULT",
		"COMPARE_BUY",
		"COMPARE_ADD2BASKET"
	);

	if(isset($arVariables["action"]) && in_array($arVariables["action"], $arCompareCommands))
		$componentPage = "compare";
	elseif(isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0)
		$componentPage = "element";
	elseif(isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> '')
		$componentPage = "element";
	elseif(isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0)
		$componentPage = "section";
	elseif(isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] <> '')
		$componentPage = "section";
	elseif(isset($_REQUEST["q"]))
		$componentPage = "search";
	else
		$componentPage = "sections";

	$currentPage = htmlspecialcharsbx($APPLICATION->GetCurPage())."?";
	$arResult = array(
		"FOLDER" => "",
		"URL_TEMPLATES" => array(
			"section" => $currentPage.$arVariableAliases["SECTION_ID"]."=#SECTION_ID#",
			"element" => $currentPage.$arVariableAliases["SECTION_ID"]."=#SECTION_ID#"."&".$arVariableAliases["ELEMENT_ID"]."=#ELEMENT_ID#",
			"compare" => $currentPage."action=COMPARE",
		),
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}

$this->IncludeComponentTemplate($componentPage);