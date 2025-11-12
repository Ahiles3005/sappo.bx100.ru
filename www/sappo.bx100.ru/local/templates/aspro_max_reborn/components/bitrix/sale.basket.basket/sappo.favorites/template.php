<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

\Bitrix\Main\UI\Extension::load("ui.fonts.ruble");

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */

global $arRegion;
$documentRoot = Main\Application::getDocumentRoot();
$storeId = $arRegion['LIST_STORES'][0];
$arForDelItems = array();

foreach($arResult['BASKET_ITEM_RENDER_DATA'] as $key => $value) {
    if($value['DELAYED'] != 1){
        continue;
    }
	$rsStoreProduct = \Bitrix\Catalog\StoreProductTable::getList(array(
		'filter' => array('=PRODUCT_ID'=>$arResult['BASKET_ITEM_RENDER_DATA'][$key]['PRODUCT_ID'],'=STORE_ID'=>$storeId),
		'select' => array('AMOUNT'),
	));

	while ($arStoreProduct = $rsStoreProduct->fetch()) {

		$arrAmount['CURRENT_STORE_AMOUNT'] = $arStoreProduct['AMOUNT'];
		$arResult['BASKET_ITEM_RENDER_DATA'][$key]+=$arrAmount;

	}

	if($arResult['BASKET_ITEM_RENDER_DATA'][$key]['QUANTITY'] > $arResult['BASKET_ITEM_RENDER_DATA'][$key]['CURRENT_STORE_AMOUNT']) {

		$arResult['BASKET_ITEM_RENDER_DATA'][$key]['QUANTITY'] = $arResult['BASKET_ITEM_RENDER_DATA'][$key]['CURRENT_STORE_AMOUNT'];

	}

	if($arResult['BASKET_ITEM_RENDER_DATA'][$key]['QUANTITY'] == $arResult['BASKET_ITEM_RENDER_DATA'][$key]['CURRENT_STORE_AMOUNT']) {

		$arResult['BASKET_ITEM_RENDER_DATA'][$key]['PLUS_DISABLE'] = 'disable';

	}

	if($arResult['BASKET_ITEM_RENDER_DATA'][$key]['CURRENT_STORE_AMOUNT'] == 0) {

		$arForDelItems[] = $arResult['BASKET_ITEM_RENDER_DATA'][$key]['ID'];
		$arResult['BASKET_ITEM_RENDER_DATA'][$key]['OUT_OF_STOCK'] = 0;
		$arResult['BASKET_ITEM_RENDER_DATA'][$key]['PLUS_DISABLE'] = 'disable';
		$arResult['BASKET_ITEM_RENDER_DATA'][$key]['MINUS_DISABLE'] = 'disable';

	}
}



if (empty($arParams['TEMPLATE_THEME']))
{
	$arParams['TEMPLATE_THEME'] = Main\ModuleManager::isModuleInstalled('bitrix.eshop') ? 'site' : 'blue';
}

if ($arParams['TEMPLATE_THEME'] === 'site')
{
	$templateId = Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', $component->getSiteId());
	$templateId = preg_match('/^eshop_adapt/', $templateId) ? 'eshop_adapt' : $templateId;
	$arParams['TEMPLATE_THEME'] = Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', $component->getSiteId());
}

if (!empty($arParams['TEMPLATE_THEME']))
{
	if (!is_file($documentRoot.'/bitrix/css/main/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
	{
		$arParams['TEMPLATE_THEME'] = 'blue';
	}
}

if (!isset($arParams['DISPLAY_MODE']) || !in_array($arParams['DISPLAY_MODE'], array('extended', 'compact')))
{
	$arParams['DISPLAY_MODE'] = 'extended';
}

$arParams['USE_DYNAMIC_SCROLL'] = isset($arParams['USE_DYNAMIC_SCROLL']) && $arParams['USE_DYNAMIC_SCROLL'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_FILTER'] = isset($arParams['SHOW_FILTER']) && $arParams['SHOW_FILTER'] === 'N' ? 'N' : 'Y';

$arParams['PRICE_DISPLAY_MODE'] = isset($arParams['PRICE_DISPLAY_MODE']) && $arParams['PRICE_DISPLAY_MODE'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['TOTAL_BLOCK_DISPLAY']) || !is_array($arParams['TOTAL_BLOCK_DISPLAY']))
{
	$arParams['TOTAL_BLOCK_DISPLAY'] = array('top');
}

if (empty($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = 'props,sku,columns';
}

if (is_string($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = explode(',', $arParams['PRODUCT_BLOCKS_ORDER']);
}

$arParams['USE_PRICE_ANIMATION'] = isset($arParams['USE_PRICE_ANIMATION']) && $arParams['USE_PRICE_ANIMATION'] === 'N' ? 'N' : 'Y';
$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

if ($arParams['USE_GIFTS'] === 'Y')
{
	$arParams['GIFTS_BLOCK_TITLE'] = isset($arParams['GIFTS_BLOCK_TITLE']) ? trim((string)$arParams['GIFTS_BLOCK_TITLE']) : Loc::getMessage('SBB_GIFTS_BLOCK_TITLE');

	CBitrixComponent::includeComponentClass('bitrix:sale.products.gift.basket');

	$giftParameters = array(
		'SHOW_PRICE_COUNT' => 1,
		'PRODUCT_SUBSCRIPTION' => 'N',
		'PRODUCT_ID_VARIABLE' => 'id',
		'USE_PRODUCT_QUANTITY' => 'N',
		'ACTION_VARIABLE' => 'actionGift',
		'ADD_PROPERTIES_TO_BASKET' => 'Y',
		'PARTIAL_PRODUCT_PROPERTIES' => 'Y',

		'BASKET_URL' => $APPLICATION->GetCurPage(),
		'APPLIED_DISCOUNT_LIST' => $arResult['APPLIED_DISCOUNT_LIST'],
		'FULL_DISCOUNT_LIST' => $arResult['FULL_DISCOUNT_LIST'],

		'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
		'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_SHOW_VALUE'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

		'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
		'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
		'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],

		'DETAIL_URL' => isset($arParams['GIFTS_DETAIL_URL']) ? $arParams['GIFTS_DETAIL_URL'] : null,
		'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
		'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
		'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
		'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
		'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
		'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
		'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
		'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

		'PRODUCT_ROW_VARIANTS' => '',
		'PAGE_ELEMENT_COUNT' => 0,
		'DEFERRED_PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
			SaleProductsGiftBasketComponent::predictRowVariants(
				$arParams['GIFTS_PAGE_ELEMENT_COUNT'],
				$arParams['GIFTS_PAGE_ELEMENT_COUNT']
			)
		),
		'DEFERRED_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],

		'ADD_TO_BASKET_ACTION' => 'BUY',
		'PRODUCT_DISPLAY_MODE' => 'Y',
		'PRODUCT_BLOCKS_ORDER' => isset($arParams['GIFTS_PRODUCT_BLOCKS_ORDER']) ? $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'] : '',
		'SHOW_SLIDER' => isset($arParams['GIFTS_SHOW_SLIDER']) ? $arParams['GIFTS_SHOW_SLIDER'] : '',
		'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
		'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',
		'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

		'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
		'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
		'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
	);
}

\CJSCore::Init(array('fx', 'popup', 'ajax'));

$this->addExternalCss('/bitrix/css/main/bootstrap.css');
$this->addExternalCss($templateFolder.'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css');

$this->addExternalJs($templateFolder.'/js/mustache.js');
$this->addExternalJs($templateFolder.'/js/action-pool.js');
$this->addExternalJs($templateFolder.'/js/filter.js');
$this->addExternalJs($templateFolder.'/js/component.js');

$mobileColumns = isset($arParams['COLUMNS_LIST_MOBILE'])
	? $arParams['COLUMNS_LIST_MOBILE']
	: $arParams['COLUMNS_LIST'];
$mobileColumns = array_fill_keys($mobileColumns, true);

$jsTemplates = new Main\IO\Directory($documentRoot.$templateFolder.'/js-templates');
/** @var Main\IO\File $jsTemplate */
foreach ($jsTemplates->getChildren() as $jsTemplate)
{
	include($jsTemplate->getPath());
}

$displayModeClass = $arParams['DISPLAY_MODE'] === 'compact' ? ' basket-items-list-wrapper-compact' : '';

$arID = array();
$arBasketItems = array();
$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL"
    ),
    false,
    false,
    array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "PRODUCT_PROVIDER_CLASS")
);
while ($arItems = $dbBasketItems->Fetch()) {
    if ('' != $arItems['PRODUCT_PROVIDER_CLASS'] || '' != $arItems["CALLBACK_FUNC"]) {
        $arID[] = $arItems["ID"];
    }
}
if (!empty($arID)) {
    $dbBasketItems = CSaleBasket::GetList(
        array(
            "NAME" => "ASC",
            "ID" => "ASC"
        ),
        array(
            "ID" => $arID,
            "ORDER_ID" => "NULL"
        ),
        false,
        false,
        array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "PRODUCT_PROVIDER_CLASS", "NAME")
    );
    while ($arItems = $dbBasketItems->Fetch()) {
        $arBasketItemsID[] = $arItems['PRODUCT_ID'];
    }

    $warm_delivery = false;
    $arSelect = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_TEPLAYA_DOSTAVKA");
    $arFilter = array("IBLOCK_ID" => 42, "ID" => $arBasketItemsID);
    $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
    while ($arrRes = $res->Fetch()) {
        if ($arrRes['PROPERTY_TEPLAYA_DOSTAVKA_VALUE'] == 'Да') {
            $warm_delivery = true;
        }
    }

    if ($warm_delivery): ?>
        <div id="warm_delivery" style="display: none">
            <div class="warm_delivery_info_overlay"></div>
            <div class="dyn_mp_jqm_frame jqmWindow popup jqm-init MAIN scrollblock show warm_delivery">
                <div class="form marketing-popup popup-text-info .default ">
                    <a href="#" class="close jqmClose">
                        <?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?>
                    </a>
                    <img src='/local/templates/aspro_max_reborn/images/warm_delivery_popup.png' alt='sappo-teplaya-dostavka-vashih-tovarov-i-zakazov.jpg'>
                    <div class="popup-text-info__title font_exlg darken option-font-bold">Температурный режим</div>
                    <div class="popup-text-info__text font_sm">
                        <p>В корзине присутствуют товары с отметкой «Теплая доставка», которые не подлежат заморозке — рекомендуем выбрать <b>доставку компанией DPD</b>, у которой присутствует услуга транспортировки с сохранением температурного режима.</p>
                        <div class="popup-text-info__btn">
                            <a class="btn btn-default btn-lg" href="/order/?get_dpd=Y">Так и сделаю</a>
                            <a class="btn btn-transparent-border-color btn-lg has-ripple" href="/order/">Окей, решу позже</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $('body').on('click', '.basket-btn-checkout', function (e) {
                e.preventDefault();

                var popup = $("#warm_delivery").contents();
                $('#warm_delivery .popup').css({'z-index':'3000', 'opacity':'1'});
                $('#popup_warm_delivery_wrapper').append(popup).css({'z-index':'3000', 'display':'flex'});
                $('body').css({'overflow':'hidden'});
            })

            $('.warm_delivery a.close').on('click', function (e) {
                e.preventDefault();

                $('#popup_warm_delivery_wrapper').css({'display':'none'});
                $('body').css({'overflow':''});
            })
        </script>

    <?elseif (!empty($arForDelItems)): ?>
		<div id="basket_info" style="display: none">
			<div class="basket_info_overlay"></div>
			<div class="dyn_mp_jqm_frame jqmWindow popup jqm-init MAIN scrollblock show basket_info">
				<div class="form marketing-popup popup-text-info .default ">
					<a href="#" class="close jqmClose">
						<?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?>
					</a>
					<div class="popup-text-info__title font_exlg darken option-font-bold">Корзина немного обновилась</div>
					<div class="popup-text-info__text font_sm">
						<p>Ой, кажется, некоторые из товаров, которые вы хотели приобрести, <b>уже раскупили.</b> Оформить заказ, исключив недоступные товары?</p>
						<div class="popup-text-info__btn">
							<a id='to_order' class="btn btn-default btn-lg">Оформить заказ</a>
							<a class="btn btn-transparent-border-color btn-lg has-ripple">Изменить корзину</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			let scrollWidth = window.innerWidth - document.documentElement.clientWidth;

			$('body').on('click', '.basket-btn-checkout', function (e) {
				e.preventDefault();

				var popup = $("#basket_info").contents();
				$('#basket_info .popup').css({'z-index':'3000', 'opacity':'1'});
				$('#popup_basket_info_wrapper').append(popup).css({'z-index':'3000', 'display':'flex'});
				$('body').css({'overflow':'hidden'});

				//fix body
				if (window.matchMedia('(min-width: 992px)').matches) {
                    $('#header .logo_and_menu-row.longs, #header .top-block, #content, #footer .footer-inner').css({'padding-right': scrollWidth});
				}
			})

			$('.basket_info a.close').on('click', function (e) {
				e.preventDefault();

				$('#popup_basket_info_wrapper').css({'display':'none'});
				$('body').css({'overflow':''});

				//unfix body
				if (window.matchMedia('(min-width: 992px)').matches) {
					$('#header .logo_and_menu-row.longs, #header .top-block, #content, #footer .footer-inner').css({'padding-right': ''});
				}
			})
    	</script>

    <?else:?>
		<script>
            $('body').on('click', '.basket-btn-checkout', function (e) {
                e.preventDefault();
				if (window.location.search.indexOf('utm') !== -1) {
					location.href = '/order' + window.location.search;
				} else {
					location.href = '/order'
				}
            })
        </script>
	<?endif;?>

<?
}
?>

<?
if (empty($arResult['ERROR_MESSAGE']))
{
	if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP')
	{
		?>
		<div data-entity="parent-container">
			<div class="catalog-block-header"
					data-entity="header"
					data-showed="false"
					style="display: none; opacity: 0;">
				<?=$arParams['GIFTS_BLOCK_TITLE']?>
			</div>
			<?
			$APPLICATION->IncludeComponent(
				'bitrix:sale.products.gift.basket',
				'.default',
				$giftParameters,
				$component
			);
			?>
		</div>
		<?
	}

	if ($arResult['BASKET_ITEM_MAX_COUNT_EXCEEDED'])
	{
		?>
		<div id="basket-item-message">
			<?=Loc::getMessage('SBB_BASKET_ITEM_MAX_COUNT_EXCEEDED', array('#PATH#' => $arParams['PATH_TO_BASKET']))?>
		</div>
		<?
	}
	?>
	<div id="basket-root" class="bx-basket bx-<?=$arParams['TEMPLATE_THEME']?> bx-step-opacity" style="opacity: 0;">
		<?
		if (
			$arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y'
			&& in_array('top', $arParams['TOTAL_BLOCK_DISPLAY'])
		)
		{
			?>
			<div class="row">
				<div class="col-xs-12" data-entity="basket-total-block"></div>
			</div>
			<?
		}
		?>

		<div class="row">
			<div class="col-xs-12">
				<div class="alert alert-warning alert-dismissable" id="basket-warning" style="display: none;">
					<span class="close" data-entity="basket-items-warning-notification-close">&times;</span>
					<div data-entity="basket-general-warnings"></div>
					<div data-entity="basket-item-warnings">
						<?=Loc::getMessage('SBB_BASKET_ITEM_WARNING')?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="basket-items-list-wrapper basket-items-list-wrapper-height-fixed basket-items-list-wrapper-light<?=$displayModeClass?>"
					id="basket-items-list-wrapper">
                    <div class="basket-items-list-header" data-entity="basket-items-list-header">
                        <span>Товары</span>
                        <div class="basket-items-list-header-filter">
                            <a href="javascript:void(0)" class="basket-items-list-header-filter-item active"
                                data-entity="basket-items-count" data-filter="all"></a>
                            <a href="javascript:void(0)" class="basket-items-list-header-filter-item"
                                data-entity="basket-items-count" data-filter="delayed"></a>
                            <a href="javascript:void(0)" class="basket-items-list-header-filter-item"
                                data-entity="basket-items-count" data-filter="not-available"></a>
                        </div>
                    </div>
					<div class="basket-items-list-container" id="basket-items-list-container">
						<div class="basket-items-list-overlay" id="basket-items-list-overlay" style="display: none;"></div>
						<div class="basket-items-list" id="basket-item-list">
							<div class="basket-search-not-found" id="basket-item-list-empty-result" style="display: none;">
								<div class="basket-search-not-found-icon"></div>
								<div class="basket-search-not-found-text">
									<?=Loc::getMessage('SBB_FILTER_EMPTY_RESULT')?>
								</div>
							</div>
                            <table class="basket-items-list-table" id="basket-item-table">
                                <tr class="basket-items-list-header hidden-xs">
                                    <td class="basket-items-list-header-item basket-items-list-header-name">Наименование</td>
                                    <td class="basket-items-list-header-item basket-items-list-header-bonus">Бонусы</td>
                                    <td class="basket-items-list-header-item basket-items-list-header-quantity">Количество</td>
                                    <td class="basket-items-list-header-item basket-items-list-header-price">Стоимость</td>
                                </tr>
                            </table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?
		if (
			$arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y'
			&& in_array('bottom', $arParams['TOTAL_BLOCK_DISPLAY'])
		)
		{
			?>
			<div class="row">
				<div class="col-xs-12" data-entity="basket-total-block"></div>
			</div>
			<?
		}
		?>
	</div>
	<?
	if (!empty($arResult['CURRENCIES']) && Main\Loader::includeModule('currency'))
	{
		CJSCore::Init('currency');

		?>
		<script>
			BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
		</script>
		<?
	}

	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
	$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
	$messages = Loc::loadLanguageFile(__FILE__);
	?>
	<script>
		BX.message(<?=CUtil::PhpToJSObject($messages)?>);
		BX.Sale.BasketComponent.init({
			result: <?=CUtil::PhpToJSObject($arResult, false, false, true)?>,
			params: <?=CUtil::PhpToJSObject($arParams)?>,
			template: '<?=CUtil::JSEscape($signedTemplate)?>',
			signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
			siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
			siteTemplateId: '<?=CUtil::JSEscape($component->getSiteTemplateId())?>',
			templateFolder: '<?=CUtil::JSEscape($templateFolder)?>'
		});
	</script>
	<?
	if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM')
	{
		?>
		<div data-entity="parent-container">
			<div class="catalog-block-header"
					data-entity="header"
					data-showed="false"
					style="display: none; opacity: 0;">
				<?=$arParams['GIFTS_BLOCK_TITLE']?>
			</div>
			<?
			$APPLICATION->IncludeComponent(
				'bitrix:sale.products.gift.basket',
				'.default',
				$giftParameters,
				$component
			);
			?>
		</div>
		<?
	}
}
elseif ($arResult['EMPTY_BASKET'])
{
	include(Main\Application::getDocumentRoot().$templateFolder.'/empty.php');
}
else
{
	ShowError($arResult['ERROR_MESSAGE']);
}
