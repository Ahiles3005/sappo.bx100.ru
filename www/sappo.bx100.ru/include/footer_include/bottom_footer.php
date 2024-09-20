<?global $APPLICATION, $arRegion, $arSite, $arTheme, $bIndexBot, $is404, $isForm, $isIndex;?>

<div class="bx_areas">
	<?CMax::ShowPageType('bottom_counter');?>
</div>
<?CMax::ShowPageType('search_title_component');?>
<?CMax::setFooterTitle();
CMax::showFooterBasket();?>
<div id="popup_iframe_wrapper"></div>
<div id="popup_warm_delivery_wrapper"></div>
<div id="popup_basket_info_wrapper"></div>
<?include_once('bottom_footer_custom.php');?>