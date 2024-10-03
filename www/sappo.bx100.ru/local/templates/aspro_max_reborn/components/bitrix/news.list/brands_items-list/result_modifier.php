<?

$brandName = array_key_exists('brandName', $_REQUEST ?? []) ? trim($_REQUEST['brandName']) : '';

$arResult['brandName'] = $brandName;
if (strlen($brandName) > 0) {
    $brandName = strtolower($brandName);
    foreach ($arResult['ITEMS'] as $k => $arItem) {
        $itemName = strtolower($arItem['NAME']);

        if (strstr($itemName, $brandName) === false) {
            unset($arResult['ITEMS'][$k]);
        }
    }
}


foreach($arResult['ITEMS'] as $arItem){
	if($SID = $arItem['IBLOCK_SECTION_ID']){
		$arSectionsIDs[] = $SID;
	}
}

if($arSectionsIDs){
	$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs, 'ACTIVE' => 'Y'));
}

// group elements by sections
foreach($arResult['ITEMS'] as $arItem){
	$SID = ($arItem['IBLOCK_SECTION_ID'] ? $arItem['IBLOCK_SECTION_ID'] : 0);
	$arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']] = $arItem;
}

// unset empty sections
if(is_array($arResult['SECTIONS'])){
	foreach($arResult['SECTIONS'] as $i => $arSection){
		if(!$arSection['ITEMS']){
			unset($arResult['SECTIONS'][$i]);
		}
	}
}


$arResult['newSort'] = [];

foreach ($arResult['SECTIONS'] as $SID => $arSection) {
    foreach ($arSection['ITEMS'] as $i => $arItem) {
        $firstChar = strtolower(substr($arItem['NAME'],0,1));
        if(is_numeric($firstChar)){
            $arResult['newSort'][0][] = $arItem;
        }else{
            $arResult['newSort'][$firstChar][] = $arItem;
        }
    }
}



?>