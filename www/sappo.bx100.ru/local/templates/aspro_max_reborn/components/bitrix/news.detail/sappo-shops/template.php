<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use Bitrix\Main\Loader,
      Bitrix\Main\Localization\Loc;

      Bitrix\Main\Page\Asset::getInstance()->addJs("/contacts/script.js");

Loader::includeModule('aspro.max');

class CustomCmax extends \Cmax {
    public static function drawShopDetail($arShop, $arParams, $showMap="Y"){
        global $APPLICATION;
        $mapLAT = $mapLON = 0;
        $arPlacemarks = array();
        $arPhotos = array();
        if(is_array($arShop)){
            if(isset($arShop['IBLOCK_ID'])){
                $arShop['LIST_URL'] = $arShop['LIST_PAGE_URL'];
                $arShop['TITLE'] = (in_array('NAME', $arParams['FIELD_CODE']) ? strip_tags($arShop['~NAME']) : '');
                $arShop['ADDRESS'] = $arShop['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'];
                $arShop['NAME'] = $arShop['TITLE'];
                $arShop['PHONE'] = $arShop['DISPLAY_PROPERTIES']['PHONE']['VALUE'];
                $arShop['EMAIL'] = $arShop['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];
                $arShop['BUTTONS'] = $arShop['PROPERTIES']['marsh']['~VALUE']['TEXT'];
                if(strToLower($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['VALUE']['TYPE']) == 'html'){
                    $arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
                }
                else{
                    $arShop['SCHEDULE'] = nl2br($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
                }
                $arShop['URL'] = $arShop['DETAIL_PAGE_URL'];
                $arShop['METRO_PLACEMARK_HTML'] = '';
                if($arShop['METRO'] = $arShop['DISPLAY_PROPERTIES']['METRO']['VALUE']){
                    if(!is_array($arShop['METRO'])){
                        $arShop['METRO'] = array($arShop['METRO']);
                    }
                    foreach($arShop['METRO'] as $metro){
                        $arShop['METRO_PLACEMARK_HTML'] .= '<div class="metro"><i></i>'.$metro.'</div>';
                    }
                }
                $arShop['DESCRIPTION'] = $arShop['DETAIL_TEXT'];
                $imageID = ((in_array('DETAIL_PICTURE', $arParams['FIELD_CODE']) && $arShop["DETAIL_PICTURE"]['ID']) ? $arShop["DETAIL_PICTURE"]['ID'] : false);
                if($imageID){
                    $arShop['IMAGE'] = CFile::ResizeImageGet($imageID, array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL);
                    $arPhotos[] = array(
                        'ID' => $arShop["DETAIL_PICTURE"]['ID'],
                        'ORIGINAL' => ($arShop["DETAIL_PICTURE"]['SRC'] ? $arShop["DETAIL_PICTURE"]['SRC'] : $arShop['IMAGE']),
                        'PREVIEW' => $arShop['IMAGE'],
                        'DESCRIPTION' => (strlen($arShop["DETAIL_PICTURE"]['DESCRIPTION']) ? $arShop["DETAIL_PICTURE"]['DESCRIPTION'] : $arShop['ADDRESS']),
                    );
                }
                if(is_array($arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['VALUE'])) {
                    foreach($arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['VALUE'] as $i => $photoID){
                        $arPhotos[] = array(
                            'ID' => $photoID,
                            'ORIGINAL' => CFile::GetPath($photoID),
                            'PREVIEW' => CFile::ResizeImageGet($photoID, array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL),
                            'DESCRIPTION' => $arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['DESCRIPTION'][$i],
                        );
                    }
                }

                $arShop['GPS_S'] = false;
                $arShop['GPS_N'] = false;
                if($arStoreMap = explode(',', $arShop['DISPLAY_PROPERTIES']['MAP']['VALUE'])){
                    $arShop['GPS_S'] = $arStoreMap[0];
                    $arShop['GPS_N'] = $arStoreMap[1];
                }

                if($arShop['GPS_S'] && $arShop['GPS_N']){
                    $mapLAT += $arShop['GPS_S'];
                    $mapLON += $arShop['GPS_N'];
                    $str_phones = '';
                    if($arShop['PHONE'])
                    {
                        foreach($arShop['PHONE'] as $phone)
                        {
                            $str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
                        }
                    }

                    $html = self::prepareItemMapHtml($arShop);

                    $arPlacemarks[] = array(
                        "ID" => $arShop["ID"],
                        "LAT" => $arShop['GPS_S'],
                        "LON" => $arShop['GPS_N'],
                        // "TEXT" => $arShop["TITLE"],
                        "TEXT" => $html
                    );
                }
            }
            else{
                $arShop["TITLE"] = strip_tags(htmlspecialchars_decode($arShop["TITLE"]));
                $arShop["ADDRESS"] = htmlspecialchars_decode($arShop["ADDRESS"]);
                $arShop["ADDRESS"] = (strlen($arShop["TITLE"]) ? $arShop["TITLE"].', ' : '').$arShop["ADDRESS"];
                $arShop["DESCRIPTION"] = htmlspecialchars_decode($arShop['DESCRIPTION']);
                $arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['SCHEDULE']);
                if($arShop["IMAGE_ID"]  && $arShop["IMAGE_ID"] != "null"){
                    $arShop['IMAGE'] = CFile::ResizeImageGet($arShop["IMAGE_ID"], array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL );
                    $arPhotos[] = array(
                        'ID' => $arShop["PREVIEW_PICTURE"]['ID'],
                        'ORIGINAL' => CFile::GetPath($arShop["IMAGE_ID"]),
                        'PREVIEW' => $arShop['IMAGE'],
                        'DESCRIPTION' => (strlen($arShop["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arShop["PREVIEW_PICTURE"]['DESCRIPTION'] : $arShop["ADDRESS"]),
                    );
                }
                if(is_array($arShop['MORE_PHOTOS'])) {
                    foreach($arShop['MORE_PHOTOS'] as $photoID){
                        $arPhotos[] = array(
                            'ID' => $photoID,
                            'ORIGINAL' => CFile::GetPath($photoID),
                            'PREVIEW' => CFile::ResizeImageGet($photoID, array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL ),
                            'DESCRIPTION' => $arShop["ADDRESS"],
                        );
                    }
                }

                $str_phones = '';
                if($arShop['PHONE'])
                {
                    $arShop['PHONE'] = explode(",", $arShop['PHONE']);
                    foreach($arShop['PHONE'] as $phone)
                    {
                        $str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
                    }
                }
                if($arShop['GPS_S'] && $arShop['GPS_N']){
                    $mapLAT += $arShop['GPS_N'];
                    $mapLON += $arShop['GPS_S'];

                    $html = self::prepareItemMapHtml($arShop, "Y");

                    $arPlacemarks[] = array(
                        "ID" => $arShop["ID"],
                        "LON" => $arShop['GPS_S'],
                        "LAT" => $arShop['GPS_N'],
                        "TEXT" => $html,
                        "HTML" => $html
                    );
                }
            }
            ?>
            <?if($arShop['DESCRIPTION']):?>
                <div class="previewtext muted777"><?=$arShop['DESCRIPTION'];?></div>
            <?endif;?>
            <div class='info-wrapper'>
                <div class="item item-shop-detail1">
                    <div class="left_block_store <?=($showMap ? '' : 'margin0')?>">
                            <div class="top_block">
                                <?if(strlen($arShop['ADDRESS'])):?>
                                    <div class="address">
                                        <div class="name"><?=$arShop['NAME']?></div>
                                        <div class="value darken"><?=$arShop['ADDRESS']?></div>
                                    </div>
                                <?endif;?>
                            <div class="properties">
                                <?if($arShop["METRO"]):?>
                                    <?foreach($arShop["METRO"] as $metro):?>
                                        <div class="property metro"><?=CMax::showIconSvg("metro colored", SITE_TEMPLATE_PATH."/images/svg/contacts-metro.svg");?>
                                            <div class="circle <?=$metro;?>"></div>
                                            <div class="value darken"><?=$metro;?></div>
                                        </div>
                                    <?endforeach;?>
                                <?endif;?>
                                <?if($arShop["SCHEDULE"]):?>
                                    <div class="property schedule">
                                        <?=CMax::showIconSvg("", SITE_TEMPLATE_PATH."/images/svg/contacts-clock.svg");?>
                                        <div class="value darken"><?=$arShop["SCHEDULE"];?></div>
                                    </div>
                                <?endif;?>
                                <?if($arShop["PHONE"]):?>
                                    <div class="property phone">
                                        <?foreach($arShop["PHONE"] as $phone):?>
                                            <?=CMax::showIconSvg("", SITE_TEMPLATE_PATH."/images/svg/contacts-tel.svg");?>
                                            <div class="value phone darken">
                                                <a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $phone);?>" rel="nofollow" class="black"><?=$phone;?></a>
                                            </div>
                                        <?endforeach;?>
                                    </div>
                                <?endif?>
                                <?if(strlen($arShop["EMAIL"])):?>
                                    <div class="property email">
                                        <?=CMax::showIconSvg("", SITE_TEMPLATE_PATH."/images/svg/contacts-mail.svg");?>
                                        <div class="value darken"><a class="dark-color" rel="nofollow" href="mailto:<?=$arShop["EMAIL"];?>"><?=$arShop["EMAIL"];?></a></div>
                                    </div>
                                <?endif;?>
                            </div>
                            <?=$arShop['BUTTONS'] = $arShop['PROPERTIES']['marsh']['~VALUE']['TEXT'];?>
                        </div>
                        <?//endif;?>
                        <?if($arPhotos):?>
                            <!-- noindex-->
                            <div class="gallery_wrap swipeignore">
                                <div class="big-gallery-block text-center">
                                    <div class="owl-carousel owl-theme owl-bg-nav short-nav" data-slider="content-detail-gallery__slider" data-plugin-options='{"items": "1", "autoplay" : false, "autoplayTimeout" : "3000", "smartSpeed":1000, "dots": true, "nav": true, "loop": false, "rewind":true, "margin": 10}'>
                                    <?foreach($arPhotos as $i => $arPhoto):?>
                                        <div class="item">
                                        <a href="<?=$arPhoto['ORIGINAL']?>" class="fancy" data-fancybox="item_slider" target="_blank" title="<?=$arPhoto['DESCRIPTION']?>">
                                            <div class="lazy" data-src="<?=$arPhoto['PREVIEW']['src']?>" style="background-image:url('<?=\Aspro\Functions\CAsproMax::showBlankImg($arPhoto['PREVIEW']['src']);?>')"></div>
                                        </a>
                                        </div>
                                    <?endforeach;?>
                                    </div>
                                </div>
                            </div>
                            <!-- /noindex-->
                        <?endif;?>
                    </div>

                </div>
                <?if($showMap == "Y"):?>
                    <div class="item map-full padding0">
                        <div class="right_block_store contacts_map">
                            <?if(abs($mapLAT) > 0 && abs($mapLON) > 0 && $showMap=="Y"):?>
                                <?if($arParams["MAP_TYPE"] != "0"):?>
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:map.google.view",
                                        "",
                                        array(
                                            "INIT_MAP_TYPE" => "ROADMAP",
                                            "MAP_DATA" => serialize(array("google_lat" => $mapLAT, "google_lon" => $mapLON, "google_scale" => 16, "PLACEMARKS" => $arPlacemarks)),
                                            "MAP_WIDTH" => "100%",
                                            "MAP_HEIGHT" => "100%",
                                            "CONTROLS" => array(
                                            ),
                                            "OPTIONS" => array(
                                                0 => "ENABLE_DBLCLICK_ZOOM",
                                                1 => "ENABLE_DRAGGING",
                                            ),
                                            "MAP_ID" => "",
                                            "ZOOM_BLOCK" => array(
                                                "POSITION" => "right center",
                                            ),
                                            "COMPONENT_TEMPLATE" => "map",
                                            "API_KEY" => $arParams["GOOGLE_API_KEY"],
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO"
                                        ),
                                        false, array("HIDE_ICONS" =>"Y")
                                    );?>
                                <?else:?>
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:map.yandex.view",
                                        "map",
                                        array(
                                            "INIT_MAP_TYPE" => "ROADMAP",
                                            "MAP_DATA" => serialize(array("yandex_lat" => $mapLAT, "yandex_lon" => $mapLON, "yandex_scale" => 17, "PLACEMARKS" => $arPlacemarks)),
                                            "MAP_WIDTH" => "100%",
                                            "MAP_HEIGHT" => "100%",
                                            "CONTROLS" => array(
                                                0 => "ZOOM",
                                                1 => "SMALLZOOM",
                                                3 => "TYPECONTROL",
                                                4 => "SCALELINE",
                                            ),
                                            "OPTIONS" => array(
                                                0 => "ENABLE_DBLCLICK_ZOOM",
                                                1 => "ENABLE_DRAGGING",
                                            ),
                                            "MAP_ID" => "",
                                            "ZOOM_BLOCK" => array(
                                                "POSITION" => "right center",
                                            ),
                                            "COMPONENT_TEMPLATE" => "map",
                                            "API_KEY" => $arParams["GOOGLE_API_KEY"],
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO"
                                        ),
                                        false, array("HIDE_ICONS" =>"Y")
                                    );?>
                                <?endif;?>
                            <?endif;?>
                        </div>
                        <div class="contacts_links">
                            <div class='text'>Связаться</div>
                            <?=$arShop['BUTTONS']?>                            
                        </div>
                    </div>
                <?endif;?>
                </div>
            <?
        }
        else{
            LocalRedirect(SITE_DIR.'contacts/');
        }
    }
}
CustomCmax::drawShopDetail($arResult, $arParams, "Y");?>