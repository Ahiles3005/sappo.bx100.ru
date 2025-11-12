<?
global $arTheme, $arRegion;
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>
<div class="mobileheader-v4">
	<div class="burger pull-left">
		<div class="burger-king">
			<span></span>
			<span></span>
			<span></span>
		</div>
	</div>
	<div class="logo-block ">
		<div class="logo<?=$logoClass?>">
			<?=CMax::ShowLogo();?>
		</div>
	</div>
	<div class="right-icons pull-right">		
		<div class="pull-right">
			<div class="wrap_icon">
				<button class="top-btn inline-search-show twosmallfont">
					<?=CMax::showIconSvg("search", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?>
				</button>
			</div>
		</div>
		<div class="pull-right">
			<div class="wrap_icon wrap_phones">
				<button class="top-btn inline-phone-show">
					<?
						$phone = ($arRegion ? $arRegion['PHONES'][0]['PHONE'] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_'.'0']);
						$href = ($arRegion ? $arRegion['PHONES'][0]['HREF'] : $arBackParametrs['HEADER_PHONES_array_PHONE_HREF_'.'0']);
						if(!strlen($href)){
							$href = 'javascript:;';
						}
					?>
					<a class="dark-color <?=(empty($description)?'no-decript':'')?>" rel="nofollow" href="<?=$href?>">
						<?=CMax::showIconSvg('phone', SITE_TEMPLATE_PATH.'/images/svg/Phone.svg');?>
					</a>
				</button>
			</div>
		</div>
	</div>
</div>