/*Copyright (c) 2018 
------------------------------------------------------------------
[Master Javascript]
Project : Discovered
-------------------------------------------------------------------*/
(function($) {
    "use strict";
	$(document).ready(function () {
		toastr.options = {
			"closeButton": false,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "toast-top-center",
			"preventDuplicates": true,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		}
		
		/***********************************/
		/**********Login sectio start ******/
		/***********************************/
		$('.login_form').on('keyup',function(event){
                event.preventDefault();
                if(event.keyCode == 13){
                   $('#loginMe').trigger('click');
                }
        });
		
		$('#loginMe').click(function () {
			var _this = $(this);
			var targetForm = $(this).closest('form');
			var checkValid = checkRequire(targetForm);
			if(checkValid == 0){
				var formdata = new FormData(targetForm[0]);
				$.ajax({
						url: targetForm.attr('action'),
						method: "post",
						data : formdata,
						processData: false,
						contentType: false,
						success: function (resp) {
							if(resp['status'] == 1){
								toastr.success(resp.message);
								setTimeout(function(){ window.location.replace('admin_dashboard'); }, 500);
							}else{
								toastr.error('Something went wrong, please try again.');
							}
						},
						error : function(resp){
							if(resp.responseJSON.message){
								toastr.warning(resp.responseJSON.message);
							}else{
								toastr.error('Something went wrong, please try again.');	
							}
						}

					});
			}
		});
		
		/***********************************/
		/**********Login sectio end ********/
		/***********************************/
		
    });
	
})(jQuery);