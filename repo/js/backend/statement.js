(function ($) {
	$(document).ready(function(){
		
		$(document).on('click','.GetStateMent',function(e){
			
			var _this = $(this);
			var formData = new FormData();
				formData.append('statement_id',_this.data('statement_id'));
				manageMyAjaxPostRequestData(formData, base_url+ 'backend/statement/getStatementDetails').done(function(resp){
					resp = JSON.parse(resp)
					
					if(resp['status'] == 1){
						 $('#Show_statement').html(resp['data']);
						 $(".dis_common_popup.dis_stmnt_popup").toggleClass("open_commonpopup");

					}
				})
		})
	
	});
}(jQuery));			