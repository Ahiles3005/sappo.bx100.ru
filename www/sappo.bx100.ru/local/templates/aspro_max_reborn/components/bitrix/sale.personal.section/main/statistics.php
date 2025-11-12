<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle('Статистика');
$APPLICATION->AddChainItem('Статистика');
?>

<?$APPLICATION->IncludeComponent(
    "dev:statistics",
    "",
    [
            "IBLOCK_ID" => 42
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);?>