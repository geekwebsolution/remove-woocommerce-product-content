jQuery(document).ready( function(){
	jQuery("#rwpc_single_page").click(function(){
		jQuery("input:checkbox[name='rwpc_single_checkbox[]']").not(this).prop('checked', this.checked);
	});
	jQuery("#rwpc_product_page").click(function(){
		jQuery("input:checkbox[name='rwpc_productList_checkbox[]']").not(this).prop('checked', this.checked);
	});
});