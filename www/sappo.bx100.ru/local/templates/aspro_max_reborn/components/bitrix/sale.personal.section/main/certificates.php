<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->SetTitle('Подарочные сертификаты');
$APPLICATION->AddChainItem('Подарочные сертификаты');
$APPLICATION->IncludeComponent(
    "dev:personal.certificates","",
                   [   'IBLOCK_ID' => 42,
                       'HL_BLOCK_ID' => 6,
                       'CURRENCY' => 'RUB',
                       'AJAX_PATH' => '/local/components/custom/gift.certificates/ajax.php'],
                   false,
                   ["HIDE_ICONS" => 'Y']
);
?>