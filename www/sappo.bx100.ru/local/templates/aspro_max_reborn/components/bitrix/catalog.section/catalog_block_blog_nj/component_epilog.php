<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;

if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY'])){
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency){?>
	<script type="text/javascript">
		BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
	</script>
	<?}
}?>
<?
global $APPLICATION;
if (isset($arResult['NAME']) && isset($arResult['MIN_PRICE'])){
	$APPLICATION->SetPageProperty("title",$arResult['NAME']." - купить по цене от ".$arResult['MIN_PRICE']." руб. в Москве и СПб в интернет-магазине товаров для детейлинга Sappo.ru");
	$APPLICATION->SetPageProperty("description",$arResult['NAME']." - купить в интернет-магазине товаров для детейлинга Sappo.ru ✅ Цены от ".$arResult['MIN_PRICE']." руб. ✅ Доставка по Москве, СПб и всей России. ✅ Большой каталог автохимии и автокосметики для детейлинга авто Sappo.ru");
}

?>
<?
if(!isset($arResult['NAME']) || empty($arResult['NAME'])){
	$arResult['NAME']=$APPLICATION->GetTitle();
}

if(!isset($arResult['NAME']) || empty($arResult['NAME'])){
	$arResult['NAME']=$APPLICATION->GetPageProperty("title");
}
?>

<?if($arResult['IMAGES']){
    $APPLICATION->IncludeFile('/include/microscheme/section.php',$arResult,[]);
}?>