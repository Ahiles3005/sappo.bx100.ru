<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$this->setFrameMode(true);
$colmd = 12;
$colsm = 12;
?>
<?if($arResult):?>
	<?
	if(!function_exists("ShowSubItems2")){
		function ShowSubItems2($arItem){
			?>
			<?if($arItem["CHILD"]):?>
				<?$noMoreSubMenuOnThisDepth = false;
				$count = count($arItem["CHILD"]);?>
				<?$lastIndex = count($arItem["CHILD"]) - 1;?>
				
				<?foreach($arItem["CHILD"] as $i => $arSubItem):?>
					<?$bLink = strlen($arSubItem['LINK']);?>
					<div class="item<?=($arSubItem["SELECTED"] ? " active" : "")?>">
						<div class="title">
							<?if($bLink):?>
								<a href="<?=$arSubItem['LINK']?>"><?=$arSubItem['TEXT']?></a>
							<?else:?>
								<span><?=$arSubItem['TEXT']?></span>
							<?endif;?>
						</div>
					</div>
					<?/*if(!$noMoreSubMenuOnThisDepth):?>
						<?ShowSubItems($arSubItem);?>
					<?endif;*/?>
					<?$noMoreSubMenuOnThisDepth |= CMax::isChildsSelected($arSubItem["CHILD"]);?>
				<?endforeach;?>
				
			<?endif;?>
			<?
		}
	}
	// print_r($arResult);
	?>
	<div class="bottom-menu second">
		<div class="items">
			<div class="items-title catalog">Каталог
				<div class="item">
					<div class="title"> 
						<a href="/catalog/moyka_i_ukhod/">Мойка и уход</a> 
					</div>
				</div>
				<div class="item">
					<div class="title"> 
						<a href="/catalog/polirovka/">Полировка</a> 
					</div>
				</div>
				<div class="item">
					<div class="title"> 
						<a href="/catalog/zashchitnye_pokrytiya/">Защитные покрытия</a> 
					</div>
				</div>
				<div class="item">
					<div class="title"> 
						<a href="/catalog/oborudovanie/">Оборудование</a> 
					</div>
				</div>
				<div class="item">
					<div class="title"> 
						<a href="/catalog/aksessuary/">Аксессуары</a> 
					</div>
				</div>
				<div class="item">
					<div class="title"> 
						<a href="/catalog/restavratsiya_kozhi/">Реставрация кожи</a> 
					</div>
				</div>
				<div class="item">
					<div class="title"> 
						<a href="/brands/">Бренды</a> 
					</div>
				</div>
			</div>
			
			<div class="items-title useful">Полезное
				<div class="item">
					<div class="title"> <a href="/blog/">Блог</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="/news/">Новости</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="https://sapposchool.ru/?utm_source=sapporu">Обучение</a> </div>
				</div>
			</div>

			<div class="items-title company">Компания
				<div class="item">
					<div class="title"> <a href="/company/">О нас</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="/contacts/">Контакты</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="/company/vacancy/">Вакансии</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="/company/reviews/">Отзывы</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="/certificates/">Сертификаты</a> </div>
				</div>
			</div>

			<div class="items-title clients">Клиентам
				<div class="item">
					<div class="title"> <a href="/help/payment/">Оплата</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="/help/delivery/">Доставка</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="/sale-new/">Распродажа</a> </div>
				</div>
				<div class="item">
					<div class="title"> <a href="/bonus-program/">Программа лояльности</a> </div>
				</div>
				<!-- <div class="item">
					<div class="title"> <a href="/404/">Подарочные сертификаты</a> </div>
				</div> -->
			</div>
    	</div>
	</div>
<?endif;?>