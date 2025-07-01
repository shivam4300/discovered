(function ($) {
	$(document).ready(function(){
		
		$(document).on('click','.MakeFeatureVideo',function(e){
			let _this = $(this);
			let post_id = $(this).attr('data-post_id');
			let user_id = $(this).attr('data-user_id');
			let subtext = 'Are you really want to update your feature video ?';
			 
			if(confirm(subtext)){ 
				
					let formData = new FormData()
					formData.append("post_id", post_id );
					formData.append("user_id", user_id );
					$('#confirm_popup').modal('hide');
					manageMyAjaxPostRequestData(formData,base_url+'backend/Advertising/MakeFeatureVideo').done(function(resp){
						
						if(resp.status == 1){
							Custom_notify('success',resp.message);
							if(_this.parents('table').attr('data-refresh-dataTablePosition')){
							  dataTableObj[_this.parents('table').attr('data-refresh-dataTablePosition')].ajax.url(base_url+_this.parents('table').attr('data-action-url')).clear().draw();	
							}
						}else{
							Custom_notify('error',resp.message);
						}
					})
			}else{
				e.preventDefault();
			}
		})
		
		
		$('body').on('click', '.common_click', function(){
			$('body').addClass('common_popup_bg');
		});
		
		$('.common_close').on('click', function(){
			$(this).closest('.dis_sharepost_popup').removeClass('open_commonpopup');
			$('body').removeClass('common_popup_bg');
		});
		
		$(document).on('click','.table_actionboxs',function(){
 			$('[data-share-post="embed"]').removeClass('hide').attr('data-frame','<iframe src="'+base_url+'embedcv/'+$(this).attr('data-video')+'" width="560" height="315" frameborder="0" allow="autoplay"></iframe>');
		})
		
		
		
		
		
		/* if($('.tbl_checkbox').length > 0){
				$('.tbl_checkbox input[type="checkbox"]').on('click', function(){
					console.log('asdasda');
					if($(this).prop("checked") == true){
						$(this).parents('.table_content tr').addClass('active');
					}
					else {
						$(this).parents('.table_content tr').removeClass('active');
					}
				});
		} */
	
		
	});
}(jQuery));	

	

