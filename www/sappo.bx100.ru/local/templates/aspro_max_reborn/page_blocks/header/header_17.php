<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader, $dopClass;
$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader = true;
$dopClass .= ' high_one_row_header';
?>
<div class="header-wrapper header-v17">
    <div class="logo_and_menu-row longs">
		<div class="logo-row paddings">
            <div class="maxwidth-theme">
				<div class="row">
                    <div class="col-md-12">
                        <div class="logo-block pull-left floated">
                            <div class="logo<?=$logoClass?>">
								<?=CMax::ShowLogo();?>
							</div>
						</div>

						<div class="pull-left menu_fixed">
                            <div class="menu-row">
                                <div class="menu-only">
                                    <nav class="mega-menu">
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/menu/menu.only_catalog.php",
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "",
												"AREA_FILE_RECURSIVE" => "Y",
												"EDIT_TEMPLATE" => "include_area.php"
											),
											false, array("HIDE_ICONS" => "Y")
										);?>
									</nav>
								</div>
							</div>
						</div>

						<div class="right-icons1 pull-right wb">
							<div class="pull-right longest">
                                <?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
							</div>
						</div>
                        
						<div class="search_wrap pull-left">
							<div class="search-block inner-table-block">
                                <?$APPLICATION->IncludeComponent(
									"bitrix:main.include",
									"",
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
										"EDIT_TEMPLATE" => "include_area.php",
										'SEARCH_ICON' => 'Y',
									)
								);?>
							</div>
						</div>
                    <div class="top-block-item phone pull-right">
                        <div class="phone-block">
                            <?if($bPhone):?>
                                <div class="inline-block">
                                    <?CMax::ShowHeaderPhones('no-icons');?>
                                </div>
                            <?endif?>
                            <?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
                            if( in_array('HEADER', $callbackExploded) ):?>
                                <div class="inline-block">
                                    <span class="callback-block animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
                                </div>
                            <?endif;?>
                        </div>
                    </div>

					</div>
				</div>
			
			</div>
		</div><?// class=logo-row?>
	</div>
</div>
<div class="top-block top-block-v1 header-v16">
    <div class="maxwidth-theme">		
        <div class="wrapp_block">
            <div class="row">
                <div class="items-wrapper flexbox flexbox--row justify-content-between">
                    <?if($arRegions):?>
                        <div class="top-block-item">
                            <div class="top-description no-title">
                                <?\Aspro\Functions\CAsproMax::showRegionList();?>
                            </div>
                        </div>
                    <?endif;?>

                    
                    <div class="menus">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                            array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "PATH" => SITE_DIR."include/menu/menu.topest2.php",
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "include_area.php"
                            ),
                            false
                        );?>
                    </div>
                    <div class="right-icons top-block-item logo_and_menu-row showed">
                        <div class="pull-right">
                            <div class="sappo-header-sbonus">

                                <?
                                \Bitrix\Main\Loader::includeModule('kilbil.bonus');
                                $APPLICATION->IncludeComponent(
                                    'kilbil.bonus:bonuses.client',
                                    '',
                                    [
                                        'USER_PHONE' => '' // номер телефона пользователя
                                    ],
                                );
                                if(\App\User::isAuth()) { ?>


                                <div class="wrap_icon inner-table-block1 person">
                                    <?=CMax::showCabinetLink(true, true, 'big');?>
                                </div>

                                <?php } else { ?>
                                    <a  title="Мой кабинет" class="personal-link dark-color animate-load" href="/auth/"><i class="svg inline big svg-inline-cabinet" aria-hidden="true" title="Мой кабинет"><svg class="" width="18" height="18" viewBox="0 0 18 18"><path data-name="Ellipse 206 copy 4" class="cls-1" d="M909,961a9,9,0,1,1,9-9A9,9,0,0,1,909,961Zm2.571-2.5a6.825,6.825,0,0,0-5.126,0A6.825,6.825,0,0,0,911.571,958.5ZM909,945a6.973,6.973,0,0,0-4.556,12.275,8.787,8.787,0,0,1,9.114,0A6.973,6.973,0,0,0,909,945Zm0,10a4,4,0,1,1,4-4A4,4,0,0,1,909,955Zm0-6a2,2,0,1,0,2,2A2,2,0,0,0,909,949Z" transform="translate(-900 -943)"></path></svg></i><span class="wrap"><span class="name">Войти</span></span></a>
                               <?php  } ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>