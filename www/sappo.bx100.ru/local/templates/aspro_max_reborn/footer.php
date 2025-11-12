						<?CMax::checkRestartBuffer();?>
						<?IncludeTemplateLangFile(__FILE__);?>
							<?if(!$isIndex):?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									</div> <?// .maxwidth-theme?>
								<?endif;?>
								</div> <?// .container?>
							<?else:?>
								<?CMax::ShowPageType('indexblocks');?>
							<?endif;?>
							<?CMax::get_banners_position('CONTENT_BOTTOM');?>
						</div> <?// .middle?>
					<?//if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
					<?if(($isIndex && ($isShowIndexLeftBlock || $bActiveTheme)) || (!$isIndex && !$isHideLeftBlock)):?>
						</div> <?// .right_block?>
						<?if($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404")):?>
							<?CMax::ShowPageType('left_block');?>
						<?endif;?>
					<?endif;?>
					</div> <?// .container_inner?>
				<?if($isIndex):?>
					</div>
				<?elseif(!$isWidePage):?>
					</div> <?// .wrapper_inner?>
				<?endif;?>
			</div> <?// #content?>
			<?CMax::get_banners_position('FOOTER');?>
		</div><?// .wrapper?>

        <?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/under_footer.php'));?>
        <?if($APPLICATION->GetProperty("viewed_show") == "Y" || $is404):?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "basket",
                array(
                    "COMPONENT_TEMPLATE" => "basket",
                    "PATH" => SITE_DIR."include/footer/comp_viewed.php",
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php",
                    "PRICE_CODE" => array(
                        0 => "BASE",
                    ),
                    "STORES" => array(
                        0 => "",
                        1 => "",
                    ),
                    "BIG_DATA_RCM_TYPE" => "bestsell",
                    "STIKERS_PROP" => "HIT",
                    "SALE_STIKER" => "SALE_TEXT",
                    "SHOW_DISCOUNT_PERCENT_NUMBER" => "N"
                ),
                false
            );?>
        <?endif;?>

        <?php
        global $isNewCustomTemplate;
        $class = $isNewCustomTemplate ? 'c-footer':'';
        ?>
		<footer id="footer" class="<?=$class?>">

			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/top_footer.php'));?>
		</footer>
		<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/bottom_footer.php'));?>
		<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/microscheme/address.php'));?>
	</body>
</html>