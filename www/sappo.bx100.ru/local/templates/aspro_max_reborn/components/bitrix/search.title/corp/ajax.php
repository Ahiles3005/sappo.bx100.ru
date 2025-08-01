<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (empty($arResult["CATEGORIES"])) return;?>
<div class="bx_searche scrollbar">
	<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>

        <div class="search-title">
            <?=$arCategory["TITLE"]?>
        </div>
		<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<?//=$arCategory["TITLE"]?>
			<?if(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]]) && $category_id !== "all"):
				$arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];?>
				<a class="bx_item_block" href="<?=$arItem["URL"]?>">
					<div class="maxwidth-theme">
						<div class="bx_img_element">
							<?if(is_array($arElement["PICTURE"])):?>
								<img src="<?=$arElement["PICTURE"]["src"]?>">
							<?else:?>
								<img src="<?=SITE_TEMPLATE_PATH?>/images/svg/noimage_product.svg" width="38" height="38">
							<?endif;?>
						</div>
						<div class="bx_item_element">
							<span><?=$arItem["NAME"]?></span>
							<div class="price cost prices">
								<div class="title-search-price">
									<?if(isset($arElement["MIN_PRICE"]) && $arElement["MIN_PRICE"]){?>
										<?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"] < $arElement["MIN_PRICE"]["VALUE"]):?>
											<div class="price"><?=$arElement["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></div>
											<div class="price discount">
												<strike><?=$arElement["MIN_PRICE"]["PRINT_VALUE"]?></strike>
											</div>
										<?else:?>
											<div class="price"><?=$arElement["MIN_PRICE"]["PRINT_VALUE"]?></div>
										<?endif;?>

									<?}elseif($arElement["PRICES"]["Цена"]["VALUE"] < $arElement["PRICES"]["Цена без скидки"]["VALUE"]){?>
										<?foreach($arElement["PRICES"] as $code=>$arPrice):?>
										<?if ($code !== "Цена без скидки"):?>
											<?if($arPrice["CAN_ACCESS"]):?>
												<?if (count($arElement["PRICES"])>1):?>
													<div class="search_price_wrap">
												<?endif;?>
												<?if($arElement["PRICES"]["Цена"]["VALUE"] < $arElement["PRICES"]["Цена без скидки"]["VALUE"]):?>
													<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></div>
													<div class="price discount">
														<strike><?=$arElement["PRICES"]["Цена без скидки"]["VALUE"]?></strike>₽
													</div>
												<?else:?>
													<div class="price"><?=$arPrice["PRINT_VALUE"]?></div>
												<?endif;?>

												<?if (count($arElement["PRICES"])>1):?>
													</div>
												<?endif;?>
											<?endif;?>
											<?endif;?>
										<?endforeach;?>

									<?}else{?>
										<?foreach($arElement["PRICES"] as $code=>$arPrice):?>
										<?if ($code !== "Цена без скидки"):?>
											<?if($arPrice["CAN_ACCESS"]):?>
												<?if (count($arElement["PRICES"])>1):?>
													<div class="search_price_wrap">
													<?/*<div class="price_name"><?=$arResult["PRICES"][$code]["TITLE"];?></div>*/?>
												<?endif;?>
												<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
													<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></div>
													<div class="price discount">
														<strike><?=$arPrice["PRINT_VALUE"]?></strike>
													</div>
												<?else:?>
													<div class="price"><?=$arPrice["PRINT_VALUE"]?></div>
												<?endif;?>
												<?if (count($arElement["PRICES"])>1):?>
													</div>
												<?endif;?>
											<?endif;?>
											<?endif;?>
										<?endforeach;?>
									<?}?>
								</div>
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</a>
			<?elseif($category_id !== "all"):?>
				<?if($arItem["MODULE_ID"]):?>
					<a class="bx_item_block others_result" href="<?=$arItem["URL"]?>">
						<div class="maxwidth-theme">
							<div class="bx_item_element">
								<span><?=$arItem["NAME"]?></span>
							</div>
							<div style="clear:both;"></div>
						</div>
					</a>
				<?endif;?>
			<?endif;?>
		<?endforeach;?>
	<?endforeach;?>
</div>

<?if(isset($arResult["CATEGORIES"]['all']) ):?>
	<?foreach($arResult["CATEGORIES"]['all']["ITEMS"] as $i => $arItem):?>
		<div class="bx_item_block all_result">
			<div class="bx_item_element">
				<a class="all_result_title btn btn-transparent btn-wide round-ignore" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
			</div>
			<div style="clear:both;"></div>
		</div>
	<?endforeach;?>
<?endif;?>