<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
$urlback = htmlspecialchars($_GET['url']);

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;?>

<div class="h-search h-search-mobile autocomplete-block v4" id="title-search-city">
	<div class="wrapper">
		<div class="search_btn"><?=CMax::showIconSvg("search2", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?></div>
		<input id="search-mobile" class="text" type="text" placeholder="<?=Loc::getMessage('CITY_PLACEHOLDER');?>">
	</div>
</div>

<?if(\Bitrix\Main\Config\Option::get('aspro.max', 'REGIONALITY_SEARCH_ROW', 'N') != 'Y'):?>
	<script>
		var arRegions = <?= '[]'; //CUtil::PhpToJsObject($arResult['JS_REGIONS']); ?>
	</script>
<?else:?>
	<script>
		var arRegions = [];
	</script>
<?endif;?>

<script type="text/javascript">
    console.log('v1', $("#search-mobile") )
    $("#search-mobile").autocomplete({
        minLength: 2,
        source: function(request, response) {
            $.getJSON( arMaxOptions['SITE_DIR']+'ajax/city_select.php', {
                term: request.term,
                url: '<?=$urlback;?>'
            }, response );

        },
        appendTo : $("#search-mobile").parent(),
        select: function(event, ui) {
            $.removeCookie('current_region');
            $.cookie('current_region', ui.item.ID, {path: '/',domain: arMaxOptions['SITE_ADDRESS']});
            $("#search-mobile").val(ui.item.label);
            return false;
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        var region = (item.REGION ? " ("+item.REGION +")" : "");
        return $("<li>")
            .append("<a href='" + item.HREF + "' class='cityLink'>" + item.label +region +"</a>")
            .appendTo(ul);
    }

</script>