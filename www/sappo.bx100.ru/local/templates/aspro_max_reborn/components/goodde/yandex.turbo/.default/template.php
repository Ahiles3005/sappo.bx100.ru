<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);



?>

<?/*='<?xml version="1.0" encoding="'.SITE_CHARSET.'"?>'*/?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">
<channel>
<title><?=$arResult["NAME"].(mb_strlen($arResult["SECTION"]["NAME"])>0?" / ".$arResult["SECTION"]["NAME"]:"")?></title>
<link><?=$arResult['HTTPS'].$arResult["SERVER_NAME"]?></link>
<turbo:cms_plugin>62668D13197952BEA072E87B176AA7BE</turbo:cms_plugin>
<description><?=mb_strlen($arResult["SECTION"]["DESCRIPTION"])>0?$arResult["SECTION"]["DESCRIPTION"]:$arResult["DESCRIPTION"]?></description>

<?foreach($arResult["ITEMS"] as $arItem):?>
<item turbo="<?=$arResult['ITEM_STATUS']?>">
	<title><?=$arItem["title"]?></title>
	<link><?=$arItem["link"]?></link>
	<?if($arItem["category"]):?>
		<category><?=$arItem["category"]?></category>
	<?endif?>
	<pubDate><?=$arItem["pubDate"]?></pubDate>
	<turbo:content>
		<![CDATA[
			<header>
				<h1><?=$arItem["page_title"]?></h1>
				<?if(mb_strlen($arResult['MENU']) > 0):?>
				<menu>
				<?=$arResult['MENU']?>
				</menu>
				<?endif?>
		   </header>
        <?
        $image = $arItem['ELEMENT']['DETAIL_PICTURE'] ?? $arItem['ELEMENT']['PREVIEW_PICTURE'];
        if ($image ):?>
            <figure>
                <img alt="header_image" src="<?= $image?>" title="Главная фотография">
            </figure>
        <? endif; ?>
			<?=$arItem['full-text']?>
			<p>&nbsp;</p>
			<?
			if($arResult['FEEDBACK']['ITEMS'] && is_array($arResult['FEEDBACK']['ITEMS']))
			{
				foreach($arResult['FEEDBACK']['ITEMS'] as $key => $arFeedback)
				{
					switch($key) 
					{
						case 'left':
							?>
							<div data-block="widget-feedback" data-stick="left">
								<?
								foreach($arFeedback['TYPE'] as $arVal)
								{
									if($arVal['TYPE'] == 'callback')
									{
										?>
										<div data-type="<?=$arVal['TYPE']?>" data-send-to="<?=$arVal['VALUE']?>" <?=(isset($arResult['FORM']['AGREEMENT_COMPANY']) ? 'data-agreement-company="'.$arResult['FORM']['AGREEMENT_COMPANY'].'"' : '')
										?> <?=(isset($arResult['FORM']['AGREEMENT_LINK']) ? 'data-agreement-link="'.$arResult['FORM']['AGREEMENT_LINK'].'"' : '')?>></div>
										<?
									}
									else
									{
										?>
										<div data-type="<?=$arVal['TYPE']?>" <?=(isset($arVal['VALUE']) ? 'data-url="'.$arVal['VALUE'].'"' : '')?>></div>
										<?
									}
								}
								?>
							</div> 
							<?
							break;
						case 'right':
							?>
							<div data-block="widget-feedback" data-stick="right">
								<?
								foreach($arFeedback['TYPE'] as $arVal)
								{
									if($arVal['TYPE'] == 'callback')
									{
										?>
										<div data-type="<?=$arVal['TYPE']?>" data-send-to="<?=$arVal['VALUE']?>"<?=(isset($arResult['FORM']['AGREEMENT_COMPANY']) ? 'data-agreement-company="'.$arResult['FORM']['AGREEMENT_COMPANY'].'"' : '')
										?> <?=(isset($arResult['FORM']['AGREEMENT_LINK']) ? 'data-agreement-link="'.$arResult['FORM']['AGREEMENT_LINK'].'"' : '')?>></div>
										<?
									}
									else
									{
										?>
										<div data-type="<?=$arVal['TYPE']?>" <?=(isset($arVal['VALUE']) ? 'data-url="'.$arVal['VALUE'].'"' : '')?>></div>
										<?
									}
								}
								?>
							</div> 
							<?
							break;
						case 'false':
							?>
							<div data-block="widget-feedback" data-stick="false" <?=(isset($arResult['FEEDBACK']['TITLE']) ? 'data-title="'.$arResult['FEEDBACK']['TITLE'].'"' : '')?>>
								<?
								foreach($arFeedback['TYPE'] as $arVal)
								{
									if($arVal['TYPE'] == 'callback')
									{
										?>
										<div data-type="<?=$arVal['TYPE']?>" data-send-to="<?=$arVal['VALUE']?>" <?=(isset($arResult['FORM']['AGREEMENT_COMPANY']) ? 'data-agreement-company="'.$arResult['FORM']['AGREEMENT_COMPANY'].'"' : '')
										?> <?=(isset($arResult['FORM']['AGREEMENT_LINK']) ? 'data-agreement-link="'.$arResult['FORM']['AGREEMENT_LINK'].'"' : '')?>></div>
										<?
									}
									else
									{
										?>
										<div data-type="<?=$arVal['TYPE']?>" <?=(isset($arVal['VALUE']) ? 'data-url="'.$arVal['VALUE'].'"' : '')?>></div>
										<?
									}
								}
								?>
							</div> 
							<?
							break;
					}
				}
			}
			?>
			<?if($arResult['SHARE'] && is_array($arResult['SHARE'])):?>
			<div data-block="share" data-network="<?=implode(', ', $arResult['SHARE'])?>"></div> 
			<?endif?>
		]]>
	</turbo:content>
	<?if(is_array($arItem['RELATED']) && $arParams['SHOW_RELATED'] === 'Y'):?>
	<yandex:related<?=($arItem['INFINITY'] ? ' type="infinity"' : '')?>>
		<?foreach($arItem['RELATED'] as $arRelated):?>
		<link url="<?=$arRelated["link"]?>"<?=(is_array($arRelated['enclosure']) ? ' img="'.$arRelated['enclosure']['url'].'"' : '')?> ><?=$arRelated["title"]?></link>
		<?endforeach?>
	</yandex:related>
	<?endif?>
</item>
<?endforeach?>
</channel>
</rss>