	function checkRequire(formId){
		
		if($('#myMessage').length){ $('#myMessage').remove(); } 
		
		var check = 0;
		$('#er_msg').remove();
		var target = (typeof formId == 'object')? $(formId):$('#'+formId);
		
		target.find('input , textarea , select').each(function(){
			
			var email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;	 
			var url = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;
			var websiteUrl = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/;
			var image = /\.(jpe?g|gif|png|PNG|JPE?G)$/;
			//var mobile = /((?:\+|00)[17](?: |\-)?|(?:\+|00)[1-9]\d{0,2}(?: |\-)?|(?:\+|00)1\-\d{3}(?: |\-)?)?(0\d|\([0-9]{3}\)|[1-9]{0,3})(?:((?: |\-)[0-9]{2}){4}|((?:[0-9]{2}){4})|((?: |\-)[0-9]{3}(?: |\-)[0-9]{4})|([0-9]{7}))/g;
			var mobile = /^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/i;

			var facebook = /^(https?:\/\/)?(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/;
			var twitter = /^(https?:\/\/)?(www\.)?twitter.com\/[a-zA-Z0-9(\.\?)?]/;
			var google_plus = /^(https?:\/\/)?(www\.)?plus.google.com\/[a-zA-Z0-9(\.\?)?]/;
			var number = /^[\s()+-]*([0-9][\s()+-]*){1,20}$/; ///^[0-9]{1,10}$/;
			var password = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#])[A-Za-z\d$@$!%*?&#]{8,}$/;
			var pdfimage = /\.(pdf|PDF)$/;
			var float_num = /^[-+]?[0-9]+\.[0-9]+$/;
			
			if($(this).hasClass('require')){
				if((typeof $(this).val() == 'object' && isEmpty($(this).val()) == true) || (typeof $(this).val() != 'object' && $(this).val().trim() == '')){ 
					check = 1;
				console.log($(this));
					let err = $(this).attr('data-error');
					Custom_notify('error',(err && err.length)?err:'This is required');
					$(this).addClass('error');
					$(this).focus();
					return false; 
				}else{
					$(this).removeClass('error');
				} 
			}else if($(this).hasClass('required')){
				if((typeof $(this).val() == 'object' && isEmpty($(this).val()) == true) || (typeof $(this).val() != 'object' && $(this).val().trim() == '')){ 
					check = 1;
					$('.form-error').html('');
					$(this).parents(".form-group").find(".form-error").html('This is required');
					$(this).addClass('error');
					$(this).focus();
					return false; 
				}else{
					$(this).removeClass('error');
				} 
			}
			
		
			if((typeof $(this).val() == 'object' && isEmpty($(this).val()) == true) || (typeof $(this).val() != 'object' && $(this).val().trim() != '')){
			
				var valid = $(this).attr('data-valid'); 
				
				if(typeof valid != 'undefined'){
					if(!eval(valid).test($(this).val().trim())){
						$(this).addClass('error');	
						//$(this).focus();
						check = 1;
						
						Custom_notify('error',$(this).attr('data-error'));
						return false; 
					}else{
						$(this).removeClass('error');
					}
				}
			}
		});
		return check;
	}


	function isEmpty(obj) {
		for(var key in obj) {
			if(obj.hasOwnProperty(key))
				return false;
		}
		return true;
	}
	
	var AjaxR = {};
	function manageMyAjaxPostRequestData(targetForm , targetUrl, i = 0 ){
		
		let token = document.cookie.replace(/(?:(?:^|.*;\s*)AuthTkn\s*\=\s*([^;]*).*$)|^.*$/, "$1");
		let option = {
			url: targetUrl,
			method: "post",
			data : targetForm,
			headers: {
				'Authorization':'Bearer '+ token,
			},
			error : function(resp){
				console.log(resp,'resp');
				if(resp.responseJSON && resp.responseJSON.message){
					Custom_notify('error',resp.responseJSON.message);
				}else{
					Custom_notify('error','Something went wrong, please try again.');
				}
			}
		};
		if(targetForm instanceof FormData){
			option.processData = false;
			option.contentType = false;
		}
		return AjaxR[i] =  $.ajax(option);
	}
	
	function Custom_notify(type,text){
		if(type == 'success'){
			$('.success_noti').removeClass('hide').find('.bottom').html(text);
			$('.error_noti').addClass('hide');	
		}else{
			$('.error_noti').removeClass('hide').find('.bottom').html(text);
			$('.success_noti').addClass('hide');
		}
		setTimeout(function(){
			$('.toster_popup').addClass('hide');	
		},9000)
	}
	function manage_ajax_content_data(_this){
		var actionUrl = base_url +_this.attr('data-action-url');
		
		$.ajax({
			method : 'post',
			url : actionUrl,
			success : function(resp){
				resp = $.parseJSON(resp);
				// console.log(resp);
				if(resp['status'] == 1){
					if(resp['data']){
						// console.log(_this.find(_this.attr('data-target-section')));
						$(_this.attr('data-target-section')).html(resp['data']);
					}else{
						$(_this.attr('data-target-section')).html('Data Not Available');
					}
					
					
					if(resp['pagination']){
						$('#pagination').html(resp['pagination']);
					}
					
					
				}else{
					Custom_notify('error','Something went wrong, please try again.');
				}
			},
			error : function(){
				Custom_notify('error','Something went wrong, please try again.');
			}
		});
	}