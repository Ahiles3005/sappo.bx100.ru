<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? $onlyImgPart = isset($arParams['ONLY_IMG_MODE']) && $arParams['ONLY_IMG_MODE'] == 'Y';
$licensesMode = isset($arParams['LICENSES_MODE']) && $arParams['LICENSES_MODE'] == 'Y';
$documentsMode = isset($arParams['DOCUMENTS_MODE']) && $arParams['DOCUMENTS_MODE'] == 'Y';


?>



    <form class="search_brands" action="/brands/" method="GET">
        <input id="search_brands" type="text" placeholder="Поиск по бренду" class="search-field" name="brandName" value="<?=$arResult['brandName']?>">
        <button type="submit" class="brands-submit-btn">
            <img src="<?=SITE_TEMPLATE_PATH?>/images/svg/search-btn.svg" alt="Поиск по производителям">
        </button>
    </form>



<? if ($arResult['SECTIONS']): ?>
    <div class="item-views items-list1 <?= ($onlyImgPart ? 'only-img' : '') ?> <?= ($documentsMode ? 'documents-mode' : ''); ?> <?= ($licensesMode ? 'licenses-mode' : ''); ?> <?= $arParams['VIEW_TYPE'] ?> <?= $arParams['VIEW_TYPE'] ?>-type-block <?= ($arParams['SHOW_TABS'] == 'Y' ? 'with_tabs' : '') ?> <?= ($arParams['IMAGE_POSITION'] ? 'image_' . $arParams['IMAGE_POSITION'] : '') ?> <?= ($templateName = $component->{'__parent'}->{'__template'}->{'__name'}) ?>">


        <div class="<?= ($arParams['SHOW_TABS'] == 'Y' ? 'tab-content' : 'group-content') ?>">
            <? // group elements by sections?>
            <? foreach ($arResult['newSort'] as $char => $arSection): ?>

                <div class="tab-pane brands-parent" data-count="<?= count($arSection) ?>">

                    <? if ($arSection['DESCRIPTION']): ?>
                        <div class="text_before_items">
                            <?= $arSection['DESCRIPTION'] ?>
                        </div>
                    <? endif; ?>

                    <div class="section-title">
                        <?= $char == 0 ? '0-9' : strtoupper($char) ?>
                    </div>

                    <div class="row sid items flexbox">
                        <? foreach ($arSection as $i => $arItem): ?>

                            <?
                            // edit/add/delete buttons for edit mode
                            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
                            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                            // use detail link?
                            $bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
                            // preview picture
                            $bImage = isset($arItem['FIELDS']['PREVIEW_PICTURE']) && strlen($arItem['PREVIEW_PICTURE']['SRC']);
                            $imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH . '/images/svg/noimage_brand.svg');
                            $imageDetailSrc = ($bImage ? $arItem['DETAIL_PICTURE']['SRC'] : false);
                            // show active date period
                            $bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));


                            $arFile = CMax::get_file_info($arItem['FIELDS']['DETAIL_PICTURE']['ID']);
                            $fileSize = CMax::filesize_format($arFile['FILE_SIZE']);

                            $arDocFile = [];
                            $docFileSize = $docFileType = '';

                            if (isset($arItem['DISPLAY_PROPERTIES']['DOCUMENT']) && $arItem['DISPLAY_PROPERTIES']['DOCUMENT']['VALUE']) {
                                $arDocFile = CMax::GetFileInfo($arItem['DISPLAY_PROPERTIES']['DOCUMENT']['VALUE']);
                                //var_dump($arDocFile['TYPE']);
                                $docFileSize = $arDocFile['FILE_SIZE_FORMAT'];
                                $docFileType = $arDocFile['TYPE'];
                                $bDocImage = false;
                                if ($docFileType == 'jpg' || $docFileType == 'jpeg' || $docFileType == 'bmp' || $docFileType == 'gif' || $docFileType == 'png') {
                                    $bDocImage = true;
                                }

                            }


                            ?>

                            <? ob_start(); ?>
                            <? // element name?>
                            <? if (strlen($arItem['FIELDS']['NAME'])): ?>

                                <? if ($documentsMode): ?>
                                    <div class="title">
                                        <? if ($arDocFile['SRC']): ?><a href="<?= $arDocFile['SRC'] ?>"
                                                                        class="dark-color <?= ($bDocImage ? 'fancy' : '') ?>"
                                                                        data-caption="<?= $arItem['NAME']; ?>"
                                                                        target="_blank"><? endif; ?>
                                            <?= $arItem['NAME'] ?>
                                            <? if ($arDocFile['SRC']): ?></a><? endif; ?>
                                        <? if ($docFileSize): ?>
                                            <div class="size muted font_xs"><?= $docFileSize; ?></div>
                                        <? endif; ?>
                                    </div>
                                <? else: ?>
                                    <div class="title <?= ($licensesMode && $arParams['VIEW_TYPE'] == 'table' ? '' : 'font_mlg') ?> ">
                                        <? if ($bDetailLink): ?><a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"
                                                                   class="dark-color"><? endif; ?>
                                            <?= $arItem['NAME'] ?>
                                            <? if ($bDetailLink): ?></a><? endif; ?>
                                        <? if ($licensesMode && $fileSize): ?>
                                            <? if ($arParams['VIEW_TYPE'] == 'table'): ?>
                                                <div class="size muted font_xs"><?= $fileSize; ?></div>
                                            <? else: ?>
                                                <span class="size muted font_xs"><?= $fileSize; ?></span>
                                            <? endif; ?>
                                        <? endif; ?>
                                    </div>
                                <? endif; ?>
                            <? endif; ?>

                            <? if (!(($licensesMode || $documentsMode) && $arParams['VIEW_TYPE'] == 'table')): ?>
                                <? // date active period?>
                                <? if ($bActiveDate): ?>
                                    <div class="period">
                                        <? if (strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])): ?>
                                            <span class="date"><?= $arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'] ?></span>
                                        <? else: ?>
                                            <span class="date"><?= $arItem['DISPLAY_ACTIVE_FROM'] ?></span>
                                        <? endif; ?>
                                    </div>
                                    <? unset($arItem['DISPLAY_PROPERTIES']['PERIOD']); ?>
                                <? endif; ?>

                                <? // element preview text?>
                                <? if (strlen($arItem['FIELDS']['PREVIEW_TEXT']) || strlen($arItem['FIELDS']['DETAIL_TEXT'])): ?>
                                    <div class="previewtext <?= ($arParams['VIEW_TYPE'] == 'list' ? 'font_sm line-h-165' : '') ?> <?= ($arParams['VIEW_TYPE'] == 'table' ? 'font_xs' : '') ?> muted777 ">
                                        <div>
                                            <? if (strlen($arItem['FIELDS']['PREVIEW_TEXT'])): ?>
                                                <? if ($arItem['PREVIEW_TEXT_TYPE'] == 'text'): ?>
                                                    <p><?= $arItem['FIELDS']['PREVIEW_TEXT'] ?></p>
                                                <? else: ?>
                                                    <?= $arItem['FIELDS']['PREVIEW_TEXT'] ?>
                                                <? endif; ?>
                                            <? endif; ?>
                                        </div>

                                        <? // element detail text?>
                                        <div>
                                            <? if (strlen($arItem['FIELDS']['DETAIL_TEXT'])): ?>
                                                <? if ($arItem['DETAIL_TEXT_TYPE'] == 'text'): ?>
                                                    <p><?= $arItem['FIELDS']['DETAIL_TEXT'] ?></p>
                                                <? else: ?>
                                                    <?= $arItem['FIELDS']['DETAIL_TEXT'] ?>
                                                <? endif; ?>
                                            <? endif; ?>
                                        </div>
                                    </div>
                                <? endif; ?>


                                <? // button?>
                                <? if (strlen($arItem['DISPLAY_PROPERTIES']['TITLE_BUTTON']['VALUE']) && strlen($arItem['DISPLAY_PROPERTIES']['LINK_BUTTON']['VALUE'])): ?>
                                    <div class="button_wrap">
                                        <a class="btn btn-default btn-sm"
                                           href="<?= $arItem['DISPLAY_PROPERTIES']['LINK_BUTTON']['VALUE'] ?>"
                                           target="_blank">
                                            <?= $arItem['DISPLAY_PROPERTIES']['TITLE_BUTTON']['VALUE'] ?>
                                        </a>
                                    </div>
                                    <? unset($arItem['DISPLAY_PROPERTIES']['TITLE_BUTTON']); ?>
                                    <? unset($arItem['DISPLAY_PROPERTIES']['LINK_BUTTON']); ?>
                                <? endif; ?>

                                <? // element display properties?>
                                <? if ($arItem['DISPLAY_PROPERTIES']): ?>
                                    <div class="properties">
                                        <? foreach ($arItem['DISPLAY_PROPERTIES'] as $PCODE => $arProperty): ?>
                                            <? if (in_array($PCODE, [
                                                'PERIOD',
                                                'TITLE_BUTTON',
                                                'LINK_BUTTON'
                                            ])) continue; ?>
                                            <? if ($documentsMode && $PCODE == 'DOCUMENT') continue; ?>
                                            <? //$bIconBlock = ($PCODE == 'EMAIL' || $PCODE == 'PHONE' || $PCODE == 'SITE');?>
                                            <div class="inner-wrapper">
                                                <div class="property <?= ($bIconBlock ? "icon-block" : ""); ?> <?= strtolower($PCODE); ?>">
                                                    <? if (!$bIconBlock): ?>
                                                        <div class="title-prop font_upper muted777"><?= $arProperty['NAME'] ?></div>
                                                    <? endif; ?>
                                                    <div class="value darken">
                                                        <? if (is_array($arProperty['DISPLAY_VALUE'])): ?>
                                                            <? $val = implode('&nbsp;/&nbsp;', $arProperty['DISPLAY_VALUE']); ?>
                                                        <? else: ?>
                                                            <? $val = $arProperty['DISPLAY_VALUE']; ?>
                                                        <? endif; ?>
                                                        <? if ($PCODE == 'SITE'): ?>
                                                            <!--noindex-->
                                                            <a href="<?= (strpos($arProperty['VALUE'], 'http') === false ? 'http://' : '') . $arProperty['VALUE']; ?>"
                                                               rel="nofollow" target="_blank" class="dark-color">
                                                                <?= strpos($arProperty['VALUE'], '?') === false ? $arProperty['VALUE'] : explode('?', $arProperty['VALUE'])[0] ?>
                                                            </a>
                                                            <!--/noindex-->
                                                        <? elseif ($PCODE == 'EMAIL'): ?>
                                                            <a href="mailto:<?= $val ?>"><?= $val ?></a>
                                                        <? elseif ($PCODE == 'PHONE'): ?>
                                                            <a href="tel:<?= str_replace([
                                                                ' ',
                                                                ',',
                                                                '-',
                                                                '(',
                                                                ')'
                                                            ], '', $arProperty['VALUE']); ?>"
                                                               class="dark-color"><?= $arProperty['VALUE'] ?></a>
                                                        <? else: ?>
                                                            <?= $val ?>
                                                        <? endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <? endforeach; ?>
                                    </div>
                                <? endif; ?>
                                <? if ($arParams['FORM'] == 'Y'): ?>
                                    <button class="btn btn-default" data-event="jqm" data-name="resume"
                                            data-param-id="<?= $arParams["FORM_ID"] ?>"
                                            data-autoload-POST="<?= CMax::formatJsName($arItem['NAME']); ?>"
                                            data-autohide=""><?= $arParams["FORM_BUTTON_TITLE"]; ?></button>
                                <? endif; ?>
                            <? endif; ?>

                            <? $textPart = ob_get_clean(); ?>

                            <? ob_start(); ?>
                            <? if ($bImage || $onlyImgPart): ?>
                                <div class="image <?= ($bImage ? ' w-picture' : ' wo-picture wpi') ?>">
                                    <? if ($bDetailLink): ?>
                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                        <? elseif (isset($arItem['FIELDS']['DETAIL_PICTURE'])): ?>
                                        <a href="<?= $imageDetailSrc ?>"
                                           alt="<?= ($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME']) ?>"
                                           title="<?= ($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME']) ?>"
                                           data-caption="<?= $arItem['NAME']; ?>" class="img-inside fancy">
                                            <? endif; ?>
                                            <img src="<?= \Aspro\Functions\CAsproMax::showBlankImg($imageDetailSrc); ?>"
                                                 data-src="<?= $imageDetailSrc ?>"
                                                 alt="<?= ($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME']) ?>"
                                                 title="<?= ($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME']) ?>"
                                                 class="img-responsive lazy"/>
                                            <? if ($bDetailLink): ?>
                                        </a>
                                        <? elseif (isset($arItem['FIELDS']['DETAIL_PICTURE'])): ?>
                                        <? /*<span class="zoom"></span>*/ ?>
                                        <? if ($licensesMode && $arParams['VIEW_TYPE'] == 'table'): ?>
                                            <span class="zoom_wrap colored_theme_hover_bg-el bordered rounded3 muted">
																<?= CMax::showIconSvg("zoom-arrow", SITE_TEMPLATE_PATH . '/images/svg/enlarge.svg', '', ''); ?>
															</span>
                                        <? endif; ?>
                                    </a>
                                <? endif; ?>
                                </div>
                            <? elseif ($documentsMode && $arDocFile): ?>
                                <div class="file_type <?= $docFileType; ?>">
                                    <i class="icon"></i>
                                </div>
                            <? endif; ?>
                            <? $imagePart = ob_get_clean(); ?>


                            <div class="box-shadow bordered colored_theme_hover_bg-block item-wrap col-md-<?= floor(12 / $arParams['COUNT_IN_LINE']) ?> col-sm-<?= floor(12 / round($arParams['COUNT_IN_LINE'] / 2)) ?> col-xs-12">
                                <div class="item  <?= ($bImage ? '' : ' wti') ?>"
                                     id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <? if (!($bImage || $arDocFile) && !$onlyImgPart): ?>
                                                <div class="text"><?= $textPart ?></div>
                                            <? elseif ($onlyImgPart): ?>
                                                <?= $imagePart ?>
                                                <? // element name?>
                                                <? if (strlen($arItem['FIELDS']['NAME'])): ?>
                                                    <div class="title font_upper muted">
                                                        <? if ($bDetailLink): ?><a
                                                                href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><? endif; ?>
                                                            <?= $arItem['NAME'] ?>
                                                            <? if ($bDetailLink): ?></a><? endif; ?>
                                                    </div>
                                                <? endif; ?>
                                            <? else: ?>
                                                <?= $imagePart ?>
                                                <div class="text"><?= $textPart ?></div>
                                            <? endif; ?>
                                        </div>
                                    </div>
                                    <? if ($documentsMode && $arDocFile): ?>
                                        <a href="<?= $arDocFile['SRC'] ?>"
                                           class="link_absolute <?= ($bDocImage ? 'fancy' : '') ?>"
                                           data-caption="<?= $arItem['NAME']; ?>" target="_blank"></a>
                                    <? endif; ?>
                                </div>
                            </div>

                        <? endforeach; ?>
                    </div>


                </div>
            <? endforeach; ?>
        </div>
    </div>
<? else: ?>
    <div class="item-views items-list1 <?= ($onlyImgPart ? 'only-img' : '') ?> <?= ($documentsMode ? 'documents-mode' : ''); ?> <?= ($licensesMode ? 'licenses-mode' : ''); ?> <?= $arParams['VIEW_TYPE'] ?> <?= $arParams['VIEW_TYPE'] ?>-type-block <?= ($arParams['SHOW_TABS'] == 'Y' ? 'with_tabs' : '') ?> <?= ($arParams['IMAGE_POSITION'] ? 'image_' . $arParams['IMAGE_POSITION'] : '') ?> <?= ($templateName = $component->{'__parent'}->{'__template'}->{'__name'}) ?>">
        <div class="<?= ($arParams['SHOW_TABS'] == 'Y' ? 'tab-content' : 'group-content') ?>">
            <div class="tab-pane brands-parent">
                <div class="text_before_items">
                    По Вашему запросу бренды не найдены
                </div>
            </div>
        </div>
    </div>
<? endif; ?>


<script>
    $(document).ready(function(){
        $(document).on('keyup', '#search_brands', function() {
            var searchInput = $(this);
            // Устанавливаем задержку в 0,5 секунды (500 миллисекунд)
            clearTimeout($.data(this, 'timer'));
            var wait = setTimeout(function() {
                if (searchInput.val().length >= 2 || searchInput.val().length == 0) {
                    $.get('?brandName=' + searchInput.val(), function(data) {
                        var html = $(data).find('.partners').html() || $(data).filter('.partners').html();
                        $('.partners').html(html);
                    });
                }
            }, 500);
            $(this).data('timer', wait);
        })
    })
</script>


<style>

    .section-title {

        font-size: 22px;
        font-weight: 500;
        width: 100%;
        margin: 15px 0 15px;
    }


    .search_brands {
        width: 100%;
        position: relative;
        margin: 25px 0 25px;
    }
    .search_brands .search-field {
        width: 100%;
        background: transparent;
        border: none;
        border-bottom: 1px solid #CBD2DF;
        padding: 0 20px 20px 0;
        font-size: 32px;
        font-weight: 500;
    }
    .search_brands .search-field::-webkit-input-placeholder {
        color: #CBD2DF;
    }
    .search_brands .search-field::-moz-placeholder {
        color: #CBD2DF;
    }
    .search_brands .search-field:-ms-input-placeholder {
        color: #CBD2DF;
    }
    .search_brands .search-field:-moz-placeholder {
        color: #CBD2DF;
    }
    .search_brands .brands-submit-btn {
        position: absolute;
        top: 50%;
        -webkit-transform: translate(0, -50%);
        -ms-transform: translate(0, -50%);
        transform: translate(0, -50%);
        right: 0;
    }

    [type=reset], [type=submit], button, html [type=button] {
        -webkit-appearance: button;
    }

    .brands-submit-btn {
        cursor: pointer;
        -webkit-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        transition: all 0.3s ease;
        background-color: transparent;
        border: none;
    }
    input.search-field:focus, .brands-submit-btn, .brands-submit-btn:focus {
        outline: none;
        border:none;
    }
    input.search-field:focus{
        border-bottom: 1px solid #CBD2DF;
    }
</style>