(function ($) {
	$(document).ready(function(){
	
		/*********************Setting Start******************/
		$(document).on('change','[name="tax_entity_type"]',function(){
			if($(this).val() == 6){
				$('[name="tax_entity_other"]').addClass('require');
			}else{
				$('[name="tax_entity_other"]').removeClass('require');
			}
		})
		
		/*
		$(document).on('click','.AddFiled',function(){
			if(CountToClone < 2){
				let _this = $(this);
				let cln = (_this.parents('.input_with_addmore').clone())[0].outerHTML;
				$('#NewField').append(cln + '<br>');
				
				$('.RandomId').each(function(i){
					let rand = Math.random()
					$(this).attr('id',rand);
					$(this).attr('value',i+1);
					$(this).next('.RandomFor').attr('for',rand);
					if(i>0)
					$(this).parents('.input_with_addmore').find('.cut').addClass('RemoveFiled');
				})
				CountToClone++;
				
			}
		})
		
		$(document).on('click','.RemoveFiled',function(){
				$(this).parents('.input_with_addmore').next('br').remove();
				$(this).parents('.input_with_addmore').remove();
				CountToClone--;
				return false;
		})
		
		$(document).on('click','.RandomId',function(){
			let target = $(this).parents('.checkbox_inputs').find('[name="billing_email_list[]"]');
			($(this).is(":checked"))? target.addClass('require') : target.removeClass('require');
		})
		
		
		*/
		
		$(document).on('submit','#BillingForm',function(e){
			e.preventDefault();
			var _this = $(this);
			
			if(checkRequire(_this) == 0){
				/*
				if($('input[name="billing_email[]"]:checked').length == 0){
					
					Custom_notify('error','Please choose one billing email address.')
					return false;
				}
				*/
				let i=0;
				$('.CheckStatus').each(function(){
					if(!$(this).is(":checked")){
						i++;
						Custom_notify('error','We can save only if your profile accepts all the terms and conditions. ')
						return false;
					}
				},)
				
				if(i==0){
					var formData = new FormData(_this[0]);
						manageMyAjaxPostRequestData(formData, base_url+ 'backend/setting/SaveBillingInfo').done(function(resp){
							if(resp['status'] == 1){
								 Custom_notify('success',resp['message']);
							}
						})
				}
			}
			
		})
		$(document).on('submit','#PaymentForm',function(e){
			e.preventDefault();
			var _this = $(this);
			
			if($('input[name="payment_method_type"]:checked').length == 0){
					Custom_notify('error','Please choose one Payment Method.');
					return false;
			}
			
			if(checkRequire(_this) == 0){
				var formData = new FormData(_this[0]);
				
				manageMyAjaxPostRequestData(formData,base_url+'backend/setting/SavePaymentInfo').done(function(resp){
					if(resp['status'] == 1){
						 Custom_notify('success',resp['message']);
					}
				})
				
			}
			
		})
		
		if($('#state').length){
				
			setTimeout(function(){
				$('.SelectBySelect2').trigger('change');
				setTimeout(function(){
					$('#state').val($('#state').attr('value')).trigger('change');
					$('[name="tax_entity_type"]').trigger('change');
				},2000);
			},1000);
		}
		
		$(document).on('click','.resetForm',function(){
			let form_id = $(this).attr('data-id');
				$(form_id).find('.require').prop("checked", false);
				$(form_id).find('.form-control').each(function(){
					if(!$(this).attr('readonly'))
					$(this).val('').trigger('change');
					Custom_notify('success','Form reset successfully.');
				});
		})
		
															
		/*********************Setting End******************/
		
		
	
	});
}(jQuery));			