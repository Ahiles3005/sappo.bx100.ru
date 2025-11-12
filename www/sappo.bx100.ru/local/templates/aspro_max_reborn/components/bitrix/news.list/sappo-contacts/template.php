<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<div class="item-views-wrapper <?=$templateName;?>">
	
	<?if($arResult['SECTIONS']):?>
			<div class="row">
				<div class="col-md-12">
					<div class="contacts-stores shops-list1">
						<?foreach($arResult['SECTIONS'] as $si => $arSection):?>
							<?$bHasSection = (isset($arSection['SECTION']) && $arSection['SECTION'])?>
							<?if($bHasSection):?>
								<?// edit/add/delete buttons for edit mode
								$arSectionButtons = CIBlock::GetPanelButtons($arSection['SECTION']['IBLOCK_ID'], 0, $arSection['SECTION']['ID'], array('SESSID' => false, 'CATALOG' => true));
								$this->AddEditAction($arSection['SECTION']['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['SECTION']['IBLOCK_ID'], 'SECTION_EDIT'));
								$this->AddDeleteAction($arSection['SECTION']['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['SECTION']['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
								<?php /*<div class="section_name" id="<?=$this->GetEditAreaId($arSection['SECTION']['ID'])?>">
									<h4><?=$arSection['SECTION']['NAME'];?></h4>
								</div> */ ?>
							<?endif;?>
							<?foreach($arSection['ITEMS'] as $i => $arItem):?>
								<?
								// edit/add/delete buttons for edit mode
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								// use detail link?
								$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
								// preview picture
								$bImage = (isset($arItem['FIELDS']['PREVIEW_PICTURE']) && strlen($arItem['PREVIEW_PICTURE']['SRC']));
								$imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : false);
								$imageDetailSrc = ($bImage ? $arItem['DETAIL_PICTURE']['SRC'] : false);
								$address = $arItem['PROPERTIES']['ADDRESS']['VALUE'];
								?>


								<div class="item<?=(!$bImage ? ' wti' : '')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
										<div class="left-block-contacts">
											<?if($imageSrc):?>
												<div class="image pull-left">
													<a href="<?=$arItem["DETAIL_PAGE_URL"];?>">
														<img src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>" data-src="<?=$imageSrc;?>" alt="<?=$arItem['NAME'];?>" title="<?=$arItem['NAME'];?>" class="img-responsive lazy"/>
													</a>
												</div>
											<?endif;?>
											<?/*<div class="middle-prop">
												<?if(!$arItem['PROPERTIES']['MAP']['VALUE']):?>
													<div class="show_on_map font_upper colored_theme_text">
														<span class="text_wrap">
															<a href="<?=$arItem["DETAIL_PAGE_URL"];?>">
															<?=CMax::showIconSvg("on_map colored", SITE_TEMPLATE_PATH.'/images/svg/show_on_map.svg');?>
															<span class="text"><?=GetMessage('SHOW_ON_MAP')?></span>
														</span>
													</div>
												<?endif;?>
												
												<?if($arItem['PROPERTIES']['METRO']['VALUE']):?>
													<?foreach($arItem['PROPERTIES']['METRO']['VALUE'] as $metro):?>
														<div class="metro font_upper"><?=CMax::showIconSvg("metro colored", SITE_TEMPLATE_PATH."/images/svg/Metro.svg");?><span class="text muted777"><?=$metro;?></span></div>
													<?endforeach;?>
												<?endif;?>
											</div>*/?>
											<?/*if($arItem['PROPERTIES']['SCHEDULE']['VALUE']):?>
												<div class="schedule"><?=CMax::showIconSvg("clock colored", SITE_TEMPLATE_PATH."/images/svg/WorkingHours.svg");?><span class="text font_xs muted777"><?=$arItem['PROPERTIES']['SCHEDULE']['~VALUE']['TEXT'];?></span></div>
											<?endif;*/?>
											<?/*if($arItem['DISPLAY_PROPERTIES']):?>
												<div>
													<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
														<?if($arProperty["DISPLAY_VALUE"]):?>
															<div class="muted custom_prop <?=strtolower($pid);?>">
																<div class="icons-text schedule grey s25">
																	<i class="fa"></i>
																	<span class="text_custom">

																		<span class="value">
																			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
																				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
																			<?else:?>
																				<?=$arProperty["DISPLAY_VALUE"];?>
																			<?endif?>
																		</span>
																	</span>
																</div>
															</div>
														<?endif?>
													<?endforeach;?>
												</div>
											<?endif;*/?>
										</div>
										<div class="right-block-contacts">
											<div class="title font_mxs darken">
												<a href="<?=$arItem["DETAIL_PAGE_URL"];?>" class="darken">
													<div class="name"><?=$arItem['NAME'];?></div>
												</a>
												<a href="<?=$arItem["DETAIL_PAGE_URL"];?>" class="darken">
													<div class="address"><?=$address;?></div>
												</a>
											</div>
											<div class="middle-prop">
												<?if($arItem['PROPERTIES']['METRO']['VALUE']):?>
													<?foreach($arItem['PROPERTIES']['METRO']['VALUE'] as $metro):?>
														<div class="metro font_upper"><?=CMax::showIconSvg("metro colored", SITE_TEMPLATE_PATH."/images/svg/contacts-metro.svg");?>
															<div class="circle <?=$metro;?>"></div>
															<span class="text muted777"><?=$metro;?></span>
														</div>
													<?endforeach;?>
												<?endif;?>
												<?if($arItem['PROPERTIES']['SCHEDULE']['VALUE']):?>
													<div class="schedule">
														<?=CMax::showIconSvg("", SITE_TEMPLATE_PATH."/images/svg/contacts-clock.svg");?>
														<span class="text font_xs muted777"><?=$arItem['PROPERTIES']['SCHEDULE']['~VALUE']['TEXT'];?></span>
													</div>
												<?endif;?>
												<?if($arItem['PROPERTIES']['PHONE']['VALUE']):?>
													<div class="phones">
														<?foreach($arItem['PROPERTIES']['PHONE']['VALUE'] as $phone):?>
															<div class="phone font_sm darken">
																<?=CMax::showIconSvg("", SITE_TEMPLATE_PATH."/images/svg/contacts-tel.svg");?>
																<span class="text font_xs muted777"><a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $phone);?>" class="black"><?=$phone;?></a></span>
															</div>
														<?endforeach;?>
													</div>
												<?endif?>
												<?if($arItem['PROPERTIES']['EMAIL']['VALUE']):?>
													<div class="emails">
														<div class="email font_sm">
															<?=CMax::showIconSvg("", SITE_TEMPLATE_PATH."/images/svg/contacts-mail.svg");?>
															<span class="text font_xs muted777"><a class="black" href="mailto:<?=$arItem['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];?>"><?=$arItem['PROPERTIES']['EMAIL']['VALUE'];?></a></span>
														</div>
													</div>
												<?endif?>
												<?if($arItem['PREVIEW_TEXT']):?>
													<div class="preview-text">
														<span class="text font_xs muted777"><?=$arItem['PREVIEW_TEXT'];?></span>
													</div>
												<?endif?>
											</div>
											<?if($arItem['DISPLAY_PROPERTIES']):?>
												<div class="custom-prop">
													<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
														<?if($arProperty["DISPLAY_VALUE"]):?>
															<div class="muted custom_prop <?=strtolower($pid);?>">
																<div class="icons-text schedule grey s25">
																	<span class="text_custom">
																		<span class="value">
																			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
																				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
																			<?else:?>
																				<?=$arProperty["DISPLAY_VALUE"];?>
																			<?endif?>
																		</span>
																	</span>
																</div>
															</div>
														<?endif?>
													<?endforeach;?>
												</div>
											<?endif;?>
												
												<?if($arItem['PROPERTIES']['PAY_TYPE']['VALUE'])
												{?>
													<div class="pay_block col-md-4 col-sm-12 col-xs-12 ">
													<?	foreach($arItem['PROPERTIES']['PAY_TYPE']['FORMAT'] as $arPays):?>
															<span class="icon-text grey s20" title="<?=$arPays['UF_NAME'];?>">
																<?if($arPays['UF_ICON_CLASS']):?><i class="fa <?=$arPays['UF_ICON_CLASS'];?>"></i>
																<?elseif($arPays['UF_FILE']):?>
																	<i><img src="<?=CFile::GetPath($arPays['UF_FILE']);?>" height="20" alt="<?=$arPays['UF_NAME'];?>"/></i>
																<?endif;?> 
																<?if(!$arPays['UF_FILE'] && !$arPays['UF_ICON_CLASS']):?>
																	<?=$arPays['UF_NAME'];?>
																<?endif;?>
															</span>
														<?endforeach;?>
													</div>
												<?}?>
										</div>
								</div>
							<?endforeach;?>
						<?endforeach;?>
					</div>
				</div>
			</div>
	<?endif;?>
</div>