<?global $APPLICATION, $arRegion, $arSite, $arTheme, $bIndexBot, $is404, $isForm, $isIndex;?>

<?CMax::ShowPageType('footer');?>

<?include_once('top_footer_custom.php');?>

<!-- marketnig popups -->
<?$APPLICATION->IncludeComponent(
	"aspro:marketing.popup.max", 
	".default", 
	array(),
	false, array('HIDE_ICONS' => 'Y')
);?>
<!-- /marketnig popups -->