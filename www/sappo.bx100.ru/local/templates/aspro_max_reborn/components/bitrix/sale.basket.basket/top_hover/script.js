var basketTimeout;
var totalSum;

function delete_all_items(type){
	var index=(type=="delay" ? "2" : "1");
	if(type == "na")
		index = 4;
	$.post( arMaxOptions['SITE_DIR']+'ajax/showBasketHover.php', 'PARAMS='+$("#basket_form").find("input#fly_basket_params").val()+'&TYPE='+index+'&CLEAR_ALL=Y', $.proxy(function( data ) {
		basketTop('reload');
		$('.in-cart').hide();
		$('.in-cart').closest('.button_block').removeClass('wide');
		$('.to-cart').removeClass('clicked');
		$('.to-cart').attr("data-quantity", 1);
		$('.to-cart').closest(".js-offers-calc").length
			? $('.to-cart').closest(".js-offers-calc").find('input[type=text]').val(1)
			: $('.to-cart').closest(".js-notice-block").find('input[type=text]').val(1)
		$('.counter_block').removeClass('in');
		$('.counter_block').find('.plus').removeClass('disabled');
		$('.to-cart').show();
		$('.wish_item.added').hide();
		$('.wish_item:not(.added)').show();
		$('.wish_item.to').show();
		$('.wish_item.in').hide();
		$('.banner_buttons.with_actions .wraps_buttons .basket_item_add').removeClass('added');
		$('.banner_buttons.with_actions .wraps_buttons .wish_item_add').removeClass('added');

		var eventdata = {action:'loadBasket'};
		BX.onCustomEvent('onCompleteAction', [eventdata]);

	}));
}

function deleteProduct(basketId, itemSection, item, th){
	function _deleteProduct(basketId, itemSection, product_id){
		arStatusBasketAspro = {};
		$.post( arMaxOptions['SITE_DIR']+'ajax/item.php', 'delete_item=Y&item='+product_id, $.proxy(function(){
			basketTop('reload');
			$('.to-cart[data-item='+product_id+']').removeClass('clicked');
			$('.to-cart[data-item='+product_id+']').attr("data-quantity", 1);
			$('.to-cart[data-item='+product_id+']').closest(".js-offers-calc").length
				? $('.to-cart[data-item='+product_id+']').closest(".js-offers-calc").find('input[type=text]').val(1)
				: $('.to-cart[data-item='+product_id+']').closest(".js-notice-block").find('input[type=text]').val(1)
			$('.counter_block[data-item='+product_id+']').removeClass('in');
			$('.counter_block[data-item='+product_id+']').find('.plus').removeClass('disabled');
			$('.to-cart[data-item='+product_id+']').show();
			reloadBasketCounters();
		}));
	}
	var product_id=th.attr("product-id");
	if(checkCounters()){
		delFromBasketCounter(item);
		setTimeout(function(){
			_deleteProduct(basketId, itemSection, product_id);
		}, 100);
	}
	else{
		_deleteProduct(basketId, itemSection, product_id);
	}
}