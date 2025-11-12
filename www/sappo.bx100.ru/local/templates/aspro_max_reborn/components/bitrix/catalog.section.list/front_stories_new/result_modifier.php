<?

use \Bitrix\Main\Type\Collection;

$sortOrder = $arParams['SORT_ORDER'] == 'ASC' ? SORT_ASC : SORT_DESC;
$sortOrder2 = $arParams['SORT_ORDER_2'] == 'ASC' ? SORT_ASC : SORT_DESC;

if ($arResult['SECTIONS']) {

    global $arRegion;

    /*add key - SECTION_ID*/
    $arTmpSections = [];
    foreach ($arResult['SECTIONS'] as $arSecion) {
        $arTmpSections[$arSecion['ID']] = $arSecion;
    }
    $arResult['SECTIONS'] = $arTmpSections;
    unset($arTmpSections);


    $arFilter = [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y',
    ];
    $arSelect = [
        'ID',
        'IBLOCK_ID',

    ];
    $arSections = CMaxCache::CIBLockSection_GetList(
        [
            'CACHE' => [
                'TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']),
                'MULTI' => 'Y',
                'GROUP' => 'ID'
            ]
        ],
        $arFilter,
        false,
        $arSelect,
        false
    );


    if ($arSections) {
        foreach ($arResult['SECTIONS'] as $key => $arSecion) {
            if (!$arSections[$key]) {
                unset($arResult['SECTIONS'][$key]);
            }
        }
    } else {
        $arResult['SECTIONS'] = [];
    }


    if ($arResult['SECTIONS']) {
        $arSectionsIDs = array_keys($arResult['SECTIONS']);
        $arElementsFilter = [
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'IBLOCK_SECTION_ID' => $arSectionsIDs,
            'ACTIVE' => 'Y',
        ];
        $arElementsSelect = [
            'ID',
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
            'PROPERTY_BTN_LINK',

        ];


        $res = CIBlockElement::GetList([], $arElementsFilter, false, false, $arElementsSelect);

        while ($ob = $res->GetNextElement()) {
            $fields = $ob->GetFields(); // указанные в $arSelect поля
            $_sectionId = intval($fields['IBLOCK_SECTION_ID']);
            $properties = $ob->GetProperties();
            $link = $properties['BTN_LINK']['VALUE'] ?? false;
            if($link){
                $arElements[$_sectionId] = $link;
            }

        }

        if ($arElements) {
            foreach ($arSections as $key => $arSection) {
                $_sectionId = intval($arSection[0]['ID']);


                if (array_key_exists($_sectionId, $arElements)) {


                    $arResult['SECTIONS'][$key]['CHILDS'][] = $arElements[$_sectionId];
                } else {
                    unset($arResult['SECTIONS'][$key]);
                }
            }
        } else {
            $arResult['SECTIONS'] = [];
        }
    }



    if ($arResult['SECTIONS']) {
        \Bitrix\Main\Type\Collection::sortByColumn($arResult['SECTIONS'], [
            $arParams['SORT'] => $sortOrder,
            $arParams['SORT_2'] => $sortOrder2
        ]);
    }
}
?>