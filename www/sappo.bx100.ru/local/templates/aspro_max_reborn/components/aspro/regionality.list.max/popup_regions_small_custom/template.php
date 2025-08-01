<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;?>

<?if(!$arResult['POPUP']):?>
	<?if($arResult['CURRENT_REGION']):?>
		<div class="region_wrapper">
			<div class="io_wrapper">
				<div class="city_title"><?=Loc::getMessage('CITY_TITLE');?></div>
				<div class="js_city_chooser  animate-load  io_wrapper" data-event="jqm" data-name="city_chooser_small" data-param-url="<?=urlencode($APPLICATION->GetCurUri());?>" data-param-form_id="city_chooser">
					<?=CMax::showIconSvg("mark", SITE_TEMPLATE_PATH."/images/svg/sappo_location2.svg");?>
                    <span><?=$arResult['CURRENT_REGION']['NAME'];?></span>
                    <span class="arrow">
                        <?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH."/images/svg/trianglearrow_down2.svg");?>
                    </span>
				</div>
			</div>
			<?if($arResult['SHOW_REGION_CONFIRM']):?>
				<div class="confirm_region">
					<span class="close colored_theme_hover_text " data-id="<?=$arResult['CURRENT_REGION']['ID'];?>"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg', '', 'light-ignore')?></span>
					<?
					$href = 'data-href="'.$arResult['REGIONS'][$arResult['REAL_REGION']['ID']]['URL'].'"';
					if($arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_TYPE']['VALUE'] == 'SUBDOMAIN' && ($arResult['HOST'].$_SERVER['HTTP_HOST'].$arResult['URI'] == $arResult['REGIONS'][$arResult['REAL_REGION']['ID']]['URL']))
					$href = '';?>
					<div class="title"><?=Loc::getMessage('CITY_TITLE');?> <?=$arResult['REAL_REGION']['NAME'];?>?</div>
					<div class="buttons">
						<span class="btn btn-default aprove" data-id="<?=$arResult['REAL_REGION']['ID'];?>" <?=$href;?>><?=Loc::getMessage('CITY_YES');?></span>
						<span class="btn btn-default white js_city_change"><?=Loc::getMessage('CITY_CHANGE');?></span>
					</div>
				</div>
			<?endif;?>
		</div>
	<?endif;?>
<?else:?>
	<?$onlySearchRow = \Bitrix\Main\Config\Option::get('aspro.max', 'REGIONALITY_SEARCH_ROW', 'N') == 'Y';?>
	<div class="popup_regions <?=($onlySearchRow ? 'only_search' : '')?>">
		<div class="h-search autocomplete-block v3" id="title-search-city">
			<div class="wrapper">
				<input id="search" class="autocomplete text" type="text" placeholder="<?=Loc::getMessage('CITY_PLACEHOLDER');?>">
				<div class="search_btn"><?=CMax::showIconSvg("search2", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?></div>
			</div>
			<?if($arResult['FAVORITS']):?>
				<div class="favorits">
					<span class="title"><?=GetMessage('EXAMPLE_CITY');?></span>
					<div class="cities">
						<?foreach($arResult['FAVORITS'] as $arItem):?>
							<div class="item">
								<a href="<?=$arItem['URL'];?>" data-id="<?=$arItem['ID'];?>" class="name"><?=$arItem['NAME'];?></a>
							</div>
						<?endforeach;?>
					</div>
				</div>
			<?endif;?>
		</div>

		<div class="items only_city">
			<?if($arResult['REGIONS']):?>
				<div class="block cities">
					<div class="items_block">
						<?foreach($arResult['REGIONS'] as $key => $arItem):?>
							<?$bCurrent = ($arResult['CURRENT_REGION']['ID'] == $arItem['ID']);?>
							<div class="item <?=($bCurrent ? 'current' : '');?>" data-id="<?=((isset($arItem['IBLOCK_SECTION_ID']) && $arItem['IBLOCK_SECTION_ID']) ? $arItem['IBLOCK_SECTION_ID'] : 0);?>">
								<?if($bCurrent):?>
									<a href="<?=$arItem['URL'];?>" data-id="<?=$arItem['ID'];?>"><span class="name"><?=$arItem['NAME'];?></span></a>
								<?else:?>
									<a href="<?=$arItem['URL'];?>" data-id="<?=$arItem['ID'];?>" class="name dark_link"><?=$arItem['NAME'];?></a>
								<?endif;?>
							</div>
						<?endforeach;?>
					</div>
				</div>
			<?endif;?>
		</div>

		<?if(\Bitrix\Main\Config\Option::get('aspro.max', 'REGIONALITY_SEARCH_ROW', 'N') != 'Y'):?>
			<script>
				var arRegions = <?=CUtil::PhpToJsObject($arResult['JS_REGIONS']);?>
			</script>
		<?else:?>
			<script>
				var arRegions = [];
			</script>
		<?endif;?>
	</div>
<?endif;?>
