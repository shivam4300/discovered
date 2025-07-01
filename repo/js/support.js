$('form.supportTicket').on('submit' , function(e){

	var _this = $(this);
	e.preventDefault();

	if (user_login_id != "") {
	var checkValid = checkRequire(_this);

		if(checkValid == 0){
			$('.dis_btn_loading').html('Loading <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled',true);
			let formData = new FormData(_this[0]);
			manageMyAjaxPostRequestData(formData , base_url +  $(this).attr('action')).done(function(resp){
				if(resp['status'] == 1){
					//Custom_notify('success',resp['message']);
					//$(_this).trigger("reset");
					Custom_notify(resp['type'],resp['message']);

					if(resp['type']=="success"){
						$(_this).trigger("reset");
						setTimeout(function(){
							window.location.href=base_url+'support';
						}, 3000);
					}


				}
			});
		}
	}else {
        $("#myModal").modal("show");
    }
});

$(document).on('click' , '.support_team' , function(){
	let _this 	= 	$(this);
	let url 	=	_this.attr('data-user-url');
	let formData = new FormData();
	let uid 	=	_this.attr('data-id');
		formData.append('user_id', uid);
		manageMyAjaxPostRequestData(formData , base_url + url).done(function(resp){
			if (resp['status'] == 1) {
				let data = resp['data'];
				$.each(data, function (key, val) {
					$('input[name="'+key+'"]').val(val);
					$('select[name="'+key+'"]').val(val);
				});
				$('input[name="password"]').val('');
				$('[name="user_email"]').prop('readonly',true);
				$('#user_form').modal('show');
			}else{
				Custom_notify('error',resp['message']);;
			}
		})
});
$(document).on('click' ,'.add_support_form' , function(e){
		$('#user_name').text('Add User');
		$("input[type='hidden']").val('');

		$('form.myFormList')[0].reset();
		$('#user_form').modal('show');

	});

$('form.tickeReplayForm').on('submit' , function(e){
	var _this = $(this);
	e.preventDefault();
	var checkValid = checkRequire(_this);
	if(checkValid == 0){
		$('.dis_btn_loading').html('Loading <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled',true);
		let formData = new FormData(_this[0]);
		manageMyAjaxPostRequestData(formData , base_url +  $(this).attr('action')).done(function(resp){
			if(resp['status'] == 1){
				Custom_notify(resp['type'],resp['message']);
				$(_this).trigger("reset");
				$('.file_name').html('');
				showReplay();
				$('.dis_btn_loading').html('send reply').prop('disabled',false);
				/*if(resp['type']=="success"){
					setTimeout(function(){ window.location.reload(); }, 3000);
				}*/
			}

		}).fail(function(e) {
				$('.dis_btn_loading').html('send reply').prop('disabled',false);
			  });
	}
});
$('form.loginForm').on('submit' , function(e){
	var _this = $(this);
	e.preventDefault();
	var checkValid = checkRequire(_this);
	if(checkValid == 0){
		let formData = new FormData(_this[0]);
		manageMyAjaxPostRequestData(formData , base_url +  $(this).attr('action')).done(function(resp){
			if(resp['status'] == 1){
				Custom_notify(resp['type'],resp['message']);
				//$(_this).trigger("reset");
				if(resp['type']=="success"){
					$(_this).trigger("reset");
					setTimeout(function(){ window.location.href=base_url+'support/dashboard'; }, 3000);
				}

			}
		});
	}
});
var dataTableObjs = [];
if($( ".displays" ).length > 0){

	var dataTableCount = 0;
	setTimeout(function(){
		$('table.displays').each(function(){
			dataTableObjs[dataTableCount++] = manage_dataTables($(this));
		});
	 }, 1000);
}


		function manage_dataTables(_this){
			//_this.destroy();
			var tableOption = {};
			if(_this.hasClass('dataTableAjax')){
				var actionUrl = base_url+_this.attr('data-action-url');
				tableOption["processing"] = true;

				tableOption["language"] = {
					  processing: "<div class='table_loader'><img src='"+base_url+"repo_admin/images/preloader.gif'></div>"
				  };
				tableOption["serverSide"] = true;
				tableOption["ajax"] = {
					 "url":actionUrl,
					 "data":function ( d ) {
						if(typeof _this.attr('data-filter') != 'undefined'){
							$('.filter_data').each(function(){
								d[$(this).attr('name')] =  ($(this).val());
							});
						}
					 }
				};
			}
			if(typeof _this.attr('data-column-class') != 'undefined'){
				tableOption["columns"] = eval(_this.attr('data-column-class'));
			}
			if(typeof _this.attr('data-orders') != 'undefined'){
				tableOption["order"] = eval(_this.attr('data-orders'));
			}
			if(typeof _this.attr('data-sort') != 'undefined'){
				tableOption["columnDefs"] = eval(_this.attr('data-sort'));
			}

			tableOption["drawCallback"] = function ( settings) {
								 var api = this.api();
								 var res = api.rows( {page:'current'} ).data();
							}
							tableOption["destroy"]=true;


			return _this.DataTable(tableOption);
		}
		/* end Server side datatable*/
$('form.adminReplyForm').on('submit' , function(e){
	var _this = $(this);
	e.preventDefault();
	var checkValid = checkRequire(_this);
	if(checkValid == 0){
		$('.dis_btn_loading').html('Loading <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled',true);
		let formData = new FormData(_this[0]);
		manageMyAjaxPostRequestData(formData , base_url +  $(this).attr('action')).done(function(resp){
			if(resp['status'] == 1){
				Custom_notify(resp['type'],resp['message']);

				showReplay();
				if(resp['type']=="success"){
					$(_this).trigger("reset");
					setTimeout(function(){ window.location.href=base_url+'support/dashboard'; }, 3000);
				}

				/*if(resp['type']=="success"){
					setTimeout(function(){ window.location.reload(); }, 3000);
				}*/
				$('.dis_btn_loading').html('send reply').prop('disabled',false);
			}
		}).fail(function(e) {
				$('.dis_btn_loading').html('send reply').prop('disabled',false);
			  });
	}
});

$(document).on('click' ,'.add_department_form' , function(e){
	$('#user_name').text('Add Department');
	$("input[type='hidden']").val('');
	$('form.myFormList')[0].reset();
	$('#user_form').modal('show');
});

$(document).on('click' , '.support_department' , function(){
	let _this 	= 	$(this);
	let name 	=	_this.attr('data-name');
	let uid 	=	_this.attr('data-id');
	$('input[name="id"]').val(uid);
	$('input[name="name"]').val(name);
	$('#user_form').modal('show');
});
$(document).on('change' , '.assign_ticket' , function(){

	let formData = new FormData();
	formData.append('ticket_id', $(this).attr('data-tid'));
	formData.append('user_id', $(this).val());
	manageMyAjaxPostRequestData(formData , base_url +'support/assign_ticket' ).done(function(resp){
		Custom_notify(resp['type'],resp['message']);
		/*$('table.display').each(function(){
			dataTableObj[dataTableCount++] = manage_dataTables($(this));
		});*/
		dataTableObjs[0].ajax.url(base_url+'support/access_support_ticket').draw();
	})
});
$(document).on('change' , '.trsnfer_ticket' , function(){

	let formData = new FormData();
	formData.append('ticket_id', $(this).attr('data-tid'));
	formData.append('department_id', $(this).val());
	manageMyAjaxPostRequestData(formData , base_url +'support/transferTicket' ).done(function(resp){
		Custom_notify(resp['type'],resp['message']);

		dataTableObjs[0].ajax.url(base_url+'support/access_support_ticket').draw();

	})
});
/* Code start by nitesh here */

var ticketStart =0;
var ticketLimit = 10;

$(document).on('change click' , '#filterByStatus , .filterBy ,  #pagination ul li:not(.active)' , function(){
	ticketStart =0;
	if(this.hasAttribute('data')){
		ticketStart = $(this).attr('data');
	}
	getUserTicket();
});

function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
function getUserTicket(){
	if($('#appendTicketList').length){
		var formData = new FormData();
		formData.append("start", ticketStart);
		formData.append("limit", ticketLimit);
		if($("input[name=date_range]").length>0){
			formData.append("date_range", $("input[name=date_range]").val());
		}
		if($(".filterBy").length>0){
			$(".filterBy").each(function() {
				let _this = $(this);
				formData.append(_this.attr('name'), _this.val());
			});
		}

		manageMyAjaxPostRequestData(formData , base_url + 'support/getUserTicket').done(function(resp){
			if((resp.trim()).length){
				resp = JSON.parse(resp);
				if(resp.status == 1){
					$('#appendTicketList li').remove();
					let resData = resp.data;
					if($('#appendTicketList').attr('data-ticket_type')=='backend'){

						var ticketHtml = getDashboardTicketHtml(resData['ticketData']);

					}else if($('#appendTicketList').attr('data-ticket_type')=='frontend'){

						var ticketHtml = getTicketHtml(resData['ticketData']);

					}
					$('#appendTicketList').append(ticketHtml);
					ticketStart += ticketLimit;
					if(resData['pagination'] !==null){
						$('.dis_sprtAdmin_footer').show();
						setTimeout(function(){
							$('#pagination').html(resData['pagination']);
							$('#pagination ul').addClass('dis_pagination');
							$('#pagination ul li a').addClass('dis_pagination_item');
							$('#pagination ul li a:first').html('<span><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="5px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M0.127,3.688 L3.907,0.134 C3.994,0.052 4.111,0.006 4.236,0.006 C4.360,0.006 4.477,0.052 4.564,0.134 L4.842,0.396 C5.024,0.566 5.024,0.843 4.842,1.014 L1.669,3.998 L4.846,6.986 C4.933,7.069 4.982,7.178 4.982,7.295 C4.982,7.412 4.933,7.522 4.846,7.604 L4.568,7.866 C4.480,7.948 4.364,7.994 4.239,7.994 C4.115,7.994 3.998,7.948 3.911,7.866 L0.127,4.308 C0.040,4.226 -0.008,4.116 -0.008,3.999 C-0.008,3.881 0.040,3.771 0.127,3.688 Z"></path></svg></span>');
							$('#pagination ul li a:last').html('<span><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="5px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M4.872,3.688 L1.073,0.134 C0.985,0.052 0.867,0.006 0.742,0.006 C0.617,0.006 0.500,0.052 0.412,0.134 L0.132,0.396 C-0.050,0.566 -0.050,0.843 0.132,1.014 L3.323,3.998 L0.129,6.986 C0.041,7.069 -0.008,7.178 -0.008,7.295 C-0.008,7.412 0.041,7.522 0.129,7.604 L0.408,7.866 C0.496,7.948 0.614,7.994 0.739,7.994 C0.864,7.994 0.981,7.948 1.069,7.866 L4.872,4.308 C4.960,4.226 5.008,4.116 5.008,3.999 C5.008,3.881 4.960,3.771 4.872,3.688 Z"/></svg></span>');
						}, 500);
					}else{
						$('.dis_sprtAdmin_footer').hide();
					}
				}else{
					$('#appendTicketList li').remove();
					$('#pagination').html('');
					$('.dis_sprtAdmin_footer').hide();
				}
			}
		})
	}
}

getUserTicket();

function getTicketHtml(ticketData){
	html='';
	//console.log(ticketData);
	if(ticketData.length>0){
		$.each(ticketData, function (i) {
			var value = ticketData[i];
			let tikcket_status = 'Ticket Open';
			let classsName ='';
			if(value.status==1){
				tikcket_status = 'Ticket Replied';
				classsName ='tf_open';
			}else if(value.status==2){
				tikcket_status = 'Ticket Closed';
				classsName ='tf_closed';
			}else if(value.status==3){
				tikcket_status = 'Customer Replied';
				classsName ='tf_open';
			}
			//	console.log(value);
			html +=`<li>
					<div class="dis_ticketbox">
						<div class="dis_ticketbox_header">
							<span class="dis_ticketFlag ${classsName}">${tikcket_status}</span>
							<div class="dis_tb_headerIcon">
								<span>
									<svg width="15px" height="20px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M13.301,20.000 L1.698,20.000 C0.764,19.997 0.007,19.176 0.005,18.162 L0.005,1.838 C0.007,0.824 0.764,0.003 1.698,-0.000 L8.891,-0.000 L8.891,3.560 C8.892,5.252 10.155,6.623 11.714,6.624 L14.995,6.624 L14.995,18.162 C14.992,19.176 14.235,19.997 13.301,20.000 ZM5.482,6.370 L3.035,6.370 C2.569,6.370 2.190,6.781 2.190,7.287 C2.190,7.792 2.569,8.204 3.035,8.204 L5.482,8.204 C5.948,8.204 6.327,7.792 6.327,7.287 C6.327,6.781 5.948,6.370 5.482,6.370 ZM11.176,10.449 L3.035,10.449 C2.569,10.449 2.190,10.861 2.190,11.367 C2.190,11.872 2.569,12.284 3.035,12.284 L11.176,12.284 C11.642,12.284 12.022,11.872 12.022,11.367 C12.022,10.861 11.642,10.449 11.176,10.449 ZM11.176,14.535 L3.035,14.535 C2.569,14.535 2.190,14.947 2.190,15.452 C2.190,15.958 2.569,16.370 3.035,16.370 L11.176,16.370 C11.642,16.370 12.022,15.958 12.022,15.452 C12.022,14.947 11.642,14.535 11.176,14.535 ZM10.021,3.560 L10.021,0.864 L14.210,5.398 L11.714,5.398 C10.779,5.397 10.021,4.575 10.021,3.560 Z"/></svg>
								</span>
							</div>
							<div class="dis_tb_headerDetails">
								<h2 class="dis_tb_hd_ttl m_b_5 mp_0">Ticket ID #${value.ticket_no}</h2>
								<ul class="dis_tb_hd_list dis_ticketbox_infoicon_list d-flex">
									<li>
										<div class="dis_ticketbox_infoicon">
											<span class="dis_ticketbox_infoicon_icon">
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.722,13.000 L1.907,13.000 C1.299,13.000 0.805,12.471 0.805,11.828 L0.805,2.612 C0.805,1.980 1.288,1.466 1.875,1.447 L1.875,3.020 C1.875,3.731 2.420,4.304 3.092,4.304 L3.860,4.304 C4.532,4.304 5.084,3.731 5.084,3.020 L5.084,1.442 L8.545,1.442 L8.545,3.020 C8.545,3.731 9.097,4.304 9.769,4.304 L10.537,4.304 C11.209,4.304 11.754,3.731 11.754,3.020 L11.754,1.447 C12.341,1.466 12.824,1.980 12.824,2.612 L12.824,11.828 C12.824,12.470 12.329,13.000 11.722,13.000 ZM11.397,6.494 C11.397,6.215 11.184,5.990 10.921,5.990 L2.687,5.990 C2.424,5.990 2.211,6.215 2.211,6.494 L2.211,11.254 C2.211,11.532 2.424,11.758 2.687,11.758 L10.921,11.758 C11.184,11.758 11.397,11.532 11.397,11.254 L11.397,6.494 ZM9.734,11.070 L8.761,11.070 C8.607,11.070 8.482,10.938 8.482,10.775 L8.482,9.745 C8.482,9.582 8.607,9.451 8.761,9.451 L9.734,9.451 C9.888,9.451 10.013,9.582 10.013,9.745 L10.013,10.775 C10.013,10.938 9.888,11.070 9.734,11.070 ZM9.734,8.497 L8.761,8.497 C8.607,8.497 8.482,8.365 8.482,8.202 L8.482,7.172 C8.482,7.009 8.607,6.877 8.761,6.877 L9.734,6.877 C9.888,6.877 10.013,7.009 10.013,7.172 L10.013,8.202 C10.013,8.365 9.888,8.497 9.734,8.497 ZM7.301,11.070 L6.328,11.070 C6.174,11.070 6.049,10.938 6.049,10.775 L6.049,9.745 C6.049,9.582 6.174,9.451 6.328,9.451 L7.301,9.451 C7.455,9.451 7.580,9.582 7.580,9.745 L7.580,10.775 C7.580,10.938 7.455,11.070 7.301,11.070 ZM7.301,8.497 L6.328,8.497 C6.174,8.497 6.049,8.365 6.049,8.202 L6.049,7.172 C6.049,7.009 6.174,6.877 6.328,6.877 L7.301,6.877 C7.455,6.877 7.580,7.009 7.580,7.172 L7.580,8.202 C7.580,8.365 7.455,8.497 7.301,8.497 ZM4.868,11.070 L3.895,11.070 C3.741,11.070 3.616,10.938 3.616,10.775 L3.616,9.745 C3.616,9.582 3.741,9.451 3.895,9.451 L4.868,9.451 C5.022,9.451 5.147,9.582 5.147,9.745 L5.147,10.775 C5.147,10.938 5.022,11.070 4.868,11.070 ZM4.868,8.497 L3.895,8.497 C3.741,8.497 3.616,8.365 3.616,8.202 L3.616,7.172 C3.616,7.009 3.741,6.877 3.895,6.877 L4.868,6.877 C5.022,6.877 5.147,7.009 5.147,7.172 L5.147,8.202 C5.147,8.365 5.022,8.497 4.868,8.497 ZM10.519,3.461 L9.759,3.461 C9.529,3.461 9.342,3.263 9.342,3.019 L9.342,0.441 C9.342,0.197 9.529,-0.000 9.759,-0.000 L10.519,-0.000 C10.749,-0.000 10.936,0.197 10.936,0.441 L10.936,3.019 C10.936,3.263 10.749,3.461 10.519,3.461 ZM3.849,3.461 L3.090,3.461 C2.859,3.461 2.672,3.263 2.672,3.019 L2.672,0.441 C2.672,0.197 2.859,-0.000 3.090,-0.000 L3.849,-0.000 C4.079,-0.000 4.266,0.197 4.266,0.441 L4.266,3.019 C4.266,3.263 4.079,3.461 3.849,3.461 Z"/></svg>
											</span>
											<span class="dis_ticketbox_infoicon_ttl mp_0">Created Date - ${value.created_at}
											</span>
										</div>
									</li>
									<li>
										<div class="dis_ticketbox_infoicon">
											<span class="dis_ticketbox_infoicon_icon">
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M8.867,13.000 L1.132,13.000 C0.509,12.998 0.005,12.464 0.003,11.805 L0.003,1.195 C0.005,0.536 0.509,0.002 1.132,-0.000 L5.928,-0.000 L5.928,2.314 C5.928,3.414 6.770,4.305 7.810,4.305 L9.996,4.305 L9.996,11.805 C9.995,12.464 9.490,12.998 8.867,13.000 ZM3.655,4.140 L2.023,4.140 C1.713,4.140 1.460,4.408 1.460,4.737 C1.460,5.065 1.713,5.333 2.023,5.333 L3.655,5.333 C3.965,5.333 4.218,5.065 4.218,4.737 C4.218,4.408 3.965,4.140 3.655,4.140 ZM7.451,6.792 L2.023,6.792 C1.713,6.792 1.460,7.060 1.460,7.389 C1.460,7.717 1.713,7.985 2.023,7.985 L7.451,7.985 C7.762,7.985 8.015,7.717 8.015,7.389 C8.015,7.060 7.762,6.792 7.451,6.792 ZM7.451,9.448 L2.023,9.448 C1.713,9.448 1.460,9.715 1.460,10.043 C1.460,10.372 1.713,10.640 2.023,10.640 L7.451,10.640 C7.762,10.640 8.015,10.372 8.015,10.043 C8.015,9.715 7.762,9.448 7.451,9.448 ZM6.680,2.314 L6.680,0.562 L9.473,3.509 L7.810,3.509 C7.186,3.508 6.681,2.974 6.680,2.314 Z"></path></svg>
											</span>
											<span class="dis_ticketbox_infoicon_ttl mp_0">Department - ${value.department_name}
											</span>
										</div>
									</li>
									${(value.reply_count>0)?'<li>    										<div class="dis_ticketbox_infoicon">  											<span class="dis_ticketbox_infoicon_icon">												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="12px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M0.388,5.430 C1.171,3.414 3.287,2.405 6.735,2.405 L8.360,2.405 L8.360,0.484 C8.360,0.354 8.406,0.241 8.498,0.147 C8.590,0.052 8.699,0.004 8.825,0.004 C8.950,0.004 9.059,0.052 9.151,0.147 L12.865,3.989 C12.957,4.084 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.569 12.865,4.665 L9.151,8.507 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.507 C8.406,8.412 8.360,8.300 8.360,8.169 L8.360,6.248 L6.735,6.248 C6.261,6.248 5.837,6.263 5.462,6.293 C5.087,6.323 4.715,6.377 4.345,6.455 C3.975,6.532 3.653,6.638 3.380,6.773 C3.107,6.909 2.852,7.082 2.615,7.295 C2.378,7.508 2.184,7.760 2.034,8.053 C1.884,8.346 1.767,8.692 1.683,9.092 C1.598,9.493 1.555,9.945 1.555,10.451 C1.555,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.550 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.824 1.608,11.887 1.566,11.937 C1.525,11.987 1.468,12.011 1.396,12.011 C1.318,12.011 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.098,11.719 C1.069,11.654 1.037,11.579 1.000,11.494 C0.964,11.409 0.939,11.349 0.924,11.314 C0.310,9.888 0.003,8.760 0.003,7.929 C0.003,6.934 0.131,6.101 0.388,5.430 Z"/></svg>											</span>											<span class="dis_ticketbox_infoicon_ttl mp_0">Replied On - '+value.updated_at+'</span>										</div>									</li>':''}

								</ul>
							</div>
						</div>
						<div class="dis_ticketbox_body">
							<h2 class="dis_ticketbox_ttl mp_0">${value.subject}</h2>
							<p class="dis_ticketbox_des">${nl2br(value.message)}</p>
							<a href="${base_url}support/ticketSingle/${value.id}" class="dis_ticketbox_view primary_link">View Full Thread
								<span><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="10px"><path fill-rule="evenodd" fill="rgb(235, 88, 31)" d="M12.812,4.532 L8.587,0.192 C8.466,0.068 8.305,0.000 8.134,0.000 C7.962,0.000 7.801,0.068 7.681,0.192 L7.297,0.586 C7.177,0.710 7.110,0.875 7.110,1.051 C7.110,1.227 7.177,1.398 7.297,1.522 L9.762,4.059 L0.632,4.059 C0.279,4.059 -0.000,4.343 -0.000,4.706 L-0.000,5.263 C-0.000,5.626 0.279,5.938 0.632,5.938 L9.790,5.938 L7.297,8.490 C7.177,8.613 7.110,8.774 7.110,8.950 C7.110,9.126 7.177,9.289 7.297,9.413 L7.681,9.806 C7.801,9.930 7.962,9.997 8.134,9.997 C8.305,9.997 8.466,9.929 8.587,9.805 L12.813,5.465 C12.933,5.341 13.000,5.175 12.999,4.999 C13.000,4.822 12.933,4.656 12.812,4.532 Z"/></svg></span>
							</a>
						</div>
					</div>
				</li>`;
		});

	}else{
		html=`<li><div class="no_result_inner text-center">
				<svg xmlns="http://www.w3.org/2000/svg" width="144px" height="141px" class="no_resultsvg">
				<path class="a" fill-rule="evenodd" fill="rgb(232, 233, 234)" d="M102.927,67.238 C102.868,85.522 87.999,100.297 69.715,100.239 C51.430,100.180 36.656,85.311 36.714,67.027 C36.773,48.742 51.642,33.968 69.926,34.026 C88.210,34.085 102.985,48.954 102.927,67.238 Z"></path>
				<path class="b" fill-rule="evenodd" fill="rgb(189, 194, 203)" d="M142.570,51.942 L139.426,51.942 L139.426,55.084 C139.426,55.647 138.969,56.103 138.406,56.103 C137.843,56.103 137.387,55.647 137.387,55.084 L137.387,51.942 L134.243,51.942 C133.681,51.942 133.224,51.485 133.224,50.923 C133.224,50.360 133.681,49.904 134.243,49.904 L137.387,49.904 L137.387,46.761 C137.387,46.198 137.843,45.742 138.406,45.742 C138.969,45.742 139.426,46.198 139.426,46.761 L139.426,49.904 L142.570,49.904 C143.133,49.904 143.589,50.360 143.589,50.923 C143.589,51.485 143.133,51.942 142.570,51.942 ZM104.580,90.518 L136.892,123.739 C138.772,125.671 139.787,128.219 139.749,130.915 C139.711,133.611 138.624,136.130 136.689,138.009 C134.756,139.888 132.206,140.902 129.510,140.864 C126.815,140.825 124.295,139.740 122.414,137.806 L98.385,113.101 C98.384,113.100 98.382,113.098 98.381,113.097 L89.621,104.090 C84.913,106.619 79.742,108.234 74.339,108.822 C73.530,108.910 72.711,108.975 71.904,109.017 C61.657,109.542 51.580,106.270 43.533,99.805 C34.789,92.781 29.307,82.790 28.097,71.674 C26.887,60.557 30.091,49.622 37.118,40.882 C44.146,32.143 54.142,26.664 65.264,25.454 C76.387,24.245 87.326,27.447 96.070,34.471 C104.814,41.496 110.296,51.486 111.506,62.603 C112.400,70.815 110.871,79.021 107.084,86.332 C106.309,87.837 105.456,89.237 104.580,90.518 ZM126.034,134.289 C126.975,135.256 128.234,135.799 129.582,135.818 C130.929,135.837 132.204,135.330 133.171,134.390 C134.139,133.450 134.682,132.192 134.701,130.844 C134.720,129.497 134.213,128.223 133.273,127.257 L111.177,104.540 L103.939,111.573 L126.034,134.289 ZM100.411,107.945 L107.649,100.912 L101.466,94.555 C98.813,97.625 96.603,99.380 96.565,99.410 C95.738,100.098 94.887,100.750 94.015,101.369 L100.411,107.945 ZM106.621,63.134 C104.499,43.645 87.504,29.164 67.929,30.167 C67.221,30.203 66.504,30.261 65.795,30.338 C55.976,31.405 47.152,36.243 40.947,43.958 C34.743,51.674 31.914,61.328 32.982,71.142 C34.051,80.957 38.891,89.777 46.610,95.979 C54.330,102.181 63.987,105.007 73.808,103.939 C81.616,103.090 88.817,99.855 94.631,94.585 L94.665,94.554 C97.561,91.925 100.015,88.869 101.959,85.472 C105.858,78.663 107.470,70.939 106.621,63.134 ZM77.256,64.907 C76.232,64.904 75.358,64.539 74.636,63.812 C73.914,63.086 73.554,62.211 73.557,61.187 C73.560,60.164 73.926,59.291 74.652,58.569 C75.379,57.848 76.255,57.488 77.279,57.491 C78.303,57.495 79.176,57.860 79.899,58.586 C80.621,59.313 80.980,60.187 80.977,61.211 C80.974,62.235 80.609,63.107 79.882,63.829 C79.155,64.550 78.279,64.910 77.256,64.907 ZM80.400,78.271 C80.554,78.754 80.514,79.223 80.280,79.675 C80.047,80.128 79.689,80.431 79.205,80.584 C78.722,80.737 78.248,80.697 77.785,80.463 C77.322,80.230 77.014,79.862 76.862,79.360 C76.384,77.813 75.494,76.560 74.193,75.600 C72.892,74.640 71.430,74.157 69.807,74.152 C68.184,74.147 66.719,74.620 65.412,75.571 C64.105,76.523 63.207,77.770 62.719,79.315 C62.563,79.816 62.257,80.182 61.803,80.412 C61.348,80.643 60.880,80.680 60.397,80.524 C59.895,80.367 59.529,80.062 59.299,79.608 C59.068,79.154 59.031,78.685 59.187,78.203 C59.909,75.868 61.249,73.990 63.204,72.567 C65.161,71.144 67.365,70.437 69.819,70.445 C72.272,70.453 74.474,71.174 76.420,72.610 C78.367,74.045 79.694,75.932 80.400,78.271 ZM62.418,64.860 C61.394,64.856 60.521,64.491 59.798,63.765 C59.076,63.039 58.716,62.164 58.720,61.140 C58.723,60.117 59.088,59.244 59.815,58.522 C60.542,57.800 61.417,57.441 62.441,57.444 C63.465,57.447 64.338,57.812 65.060,58.539 C65.783,59.265 66.142,60.140 66.139,61.164 C66.136,62.187 65.771,63.060 65.044,63.782 C64.317,64.503 63.441,64.863 62.418,64.860 ZM87.267,12.903 C83.760,12.903 80.899,10.043 80.899,6.538 C80.899,3.026 83.760,0.173 87.267,0.173 C90.781,0.173 93.636,3.026 93.636,6.538 C93.636,10.043 90.781,12.903 87.267,12.903 ZM87.267,2.211 C84.882,2.211 82.938,4.154 82.938,6.538 C82.938,8.922 84.882,10.865 87.267,10.865 C89.653,10.865 91.597,8.922 91.597,6.538 C91.597,4.154 89.653,2.211 87.267,2.211 ZM29.003,17.857 L31.183,20.122 C31.573,20.527 31.561,21.172 31.155,21.562 C30.750,21.953 30.104,21.940 29.714,21.535 L27.534,19.270 L25.268,21.449 C24.863,21.839 24.217,21.827 23.827,21.421 C23.437,21.016 23.449,20.371 23.855,19.981 L26.120,17.802 L23.941,15.538 C23.550,15.132 23.563,14.487 23.968,14.097 C24.374,13.707 25.020,13.719 25.410,14.125 L27.589,16.389 L29.855,14.210 C30.261,13.819 30.906,13.832 31.296,14.237 C31.687,14.643 31.675,15.288 31.269,15.678 L29.003,17.857 ZM25.581,113.255 C25.676,112.700 26.202,112.327 26.758,112.421 C27.312,112.516 27.686,113.043 27.591,113.597 L27.063,116.694 L30.162,117.222 C30.717,117.318 31.090,117.844 30.996,118.398 C30.901,118.953 30.375,119.326 29.820,119.232 L26.720,118.704 L26.192,121.801 C26.097,122.356 25.571,122.729 25.015,122.634 C24.824,122.601 24.653,122.517 24.517,122.397 C24.258,122.172 24.120,121.821 24.182,121.459 L24.710,118.361 L21.611,117.833 C21.419,117.800 21.249,117.716 21.112,117.596 C20.854,117.370 20.716,117.020 20.777,116.657 C20.872,116.102 21.398,115.730 21.954,115.824 L25.053,116.352 L25.581,113.255 ZM10.330,73.926 C8.941,74.905 7.257,75.292 5.576,75.005 C4.407,74.804 3.337,74.293 2.467,73.532 C2.089,73.201 1.746,72.829 1.453,72.411 C0.469,71.028 0.086,69.339 0.369,67.664 C0.578,66.466 1.100,65.406 1.847,64.552 C3.261,62.936 5.452,62.072 7.718,62.455 C11.177,63.052 13.517,66.342 12.925,69.805 C12.637,71.475 11.714,72.943 10.330,73.926 ZM7.375,64.466 C5.022,64.070 2.788,65.654 2.381,68.006 C1.980,70.354 3.565,72.597 5.923,72.998 C7.063,73.191 8.207,72.927 9.149,72.262 C10.097,71.592 10.719,70.602 10.913,69.463 C11.314,67.106 9.729,64.872 7.375,64.466 Z"></path>
				</svg>
				<p>No Tickets Found.</p>
				</div></li>`;
	}
	return html;
}


function getDashboardTicketHtml(ticketData){
	html='';
	if(ticketData.length>0){
		$.each(ticketData, function (i) {
			var value = ticketData[i];
			let tikcket_status = 'Ticket Open';
			let status_value=2;
			let status_lable="Close";
			let classsName ='';
			let teme_name='';
			let assign_by_name_data="";
			let last_replied_data="";
			let technology_data="";
			if(value.status==1){
				tikcket_status = 'Ticket Replied';
				classsName ='tf_open';
			}else if(value.status==2){
				tikcket_status = 'Ticket Closed';
				classsName ='tf_closed';
				status_value=0;
				status_lable="Open";
			}else if(value.status==3){
				tikcket_status = 'Customer Replied';
				classsName ='tf_open';
			}
			var acceptData="";
			//console.log(value.accept_by);
			if(value.accept_by==null){
			acceptData=`<li class="custom_dropdown_item" >
				<a href="#" class="custom_dd_anchr accept_ticket" data-id="${value.id}">
					<span class="custom_dd_icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14.031" viewBox="0 0 14 14.031">
						<path fill-rule="evenodd" fill="rgb(64 64 76)" d="M1647.3,731.916a0.7,0.7,0,0,0-.66-0.583,0.663,0.663,0,0,0-.74.441,1.338,1.338,0,0,0-.01.631,5.466,5.466,0,0,1-1.31,4.933,5.411,5.411,0,0,1-4.72,1.76,5.55,5.55,0,0,1-4.85-5.677,5.482,5.482,0,0,1,5.49-5.284,8.461,8.461,0,0,1,1.06.113l0.3,0.047a0.751,0.751,0,1,0,.31-1.466c-0.05-.013-0.1-0.027-0.15-0.038a6.991,6.991,0,0,0-8.27,5.078,6.658,6.658,0,0,0,2.37,7.192,6.921,6.921,0,0,0,4.38,1.564,7,7,0,0,0,2.95-.656,6.625,6.625,0,0,0,3.75-4.3,7.991,7.991,0,0,0,.12-3.664Zm-6.53,1.524-0.24.25a0.593,0.593,0,0,1-.08-0.077l-0.53-.522c-0.35-.348-0.7-0.7-1.05-1.042a0.751,0.751,0,1,0-1.06,1.057c0.05,0.054.1,0.105,0.19,0.2l1.87,1.861a0.775,0.775,0,0,0,1.27,0l3.89-3.887c0.73-.719,1.45-1.437,2.16-2.165a0.971,0.971,0,0,0,.3-0.677,0.863,0.863,0,0,0-.42-0.62,0.7,0.7,0,0,0-.91.21l-4.56,4.562C1641.32,732.869,1641.05,733.153,1640.77,733.44Z" transform="translate(-1633.5 -726.594)"/>
					</svg>
					</span>
					<span class="custom_dd_text">Accept</span>
				</a>
			</li>`
										}
			if(value.suooprt_teme_name!=null){
				teme_name=`<li>
								<div class="dis_ticketbox_infoicon">
						 			<span class="dis_ticketbox_infoicon_icon">
										<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.096,1.904 C9.868,0.676 8.236,-0.000 6.500,-0.000 C4.764,-0.000 3.131,0.676 1.904,1.904 C0.676,3.131 -0.000,4.764 -0.000,6.500 C-0.000,8.236 0.676,9.868 1.904,11.096 C3.131,12.324 4.764,13.000 6.500,13.000 C8.236,13.000 9.868,12.324 11.096,11.096 C12.324,9.868 13.000,8.236 13.000,6.500 C13.000,4.764 12.324,3.131 11.096,1.904 ZM6.500,12.238 C4.802,12.238 3.274,11.497 2.223,10.321 C2.875,8.593 4.544,7.363 6.500,7.363 C5.238,7.363 4.215,6.340 4.215,5.078 C4.215,3.816 5.238,2.793 6.500,2.793 C7.762,2.793 8.785,3.816 8.785,5.078 C8.785,6.340 7.762,7.363 6.500,7.363 C8.456,7.363 10.125,8.593 10.777,10.321 C9.726,11.497 8.198,12.238 6.500,12.238 Z"/></svg>
									</span>
									<span class="dis_ticketbox_infoicon_ttl mp_0">Accepted By - ${value.suooprt_teme_name}</span>
								</div>
							</li>`;
			}

			if(value.last_replied!=null){
				last_replied_data=`<li>
								<div class="dis_ticketbox_infoicon">
						 			<span class="dis_ticketbox_infoicon_icon">
										<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.096,1.904 C9.868,0.676 8.236,-0.000 6.500,-0.000 C4.764,-0.000 3.131,0.676 1.904,1.904 C0.676,3.131 -0.000,4.764 -0.000,6.500 C-0.000,8.236 0.676,9.868 1.904,11.096 C3.131,12.324 4.764,13.000 6.500,13.000 C8.236,13.000 9.868,12.324 11.096,11.096 C12.324,9.868 13.000,8.236 13.000,6.500 C13.000,4.764 12.324,3.131 11.096,1.904 ZM6.500,12.238 C4.802,12.238 3.274,11.497 2.223,10.321 C2.875,8.593 4.544,7.363 6.500,7.363 C5.238,7.363 4.215,6.340 4.215,5.078 C4.215,3.816 5.238,2.793 6.500,2.793 C7.762,2.793 8.785,3.816 8.785,5.078 C8.785,6.340 7.762,7.363 6.500,7.363 C8.456,7.363 10.125,8.593 10.777,10.321 C9.726,11.497 8.198,12.238 6.500,12.238 Z"/></svg>
									</span>
									<span class="dis_ticketbox_infoicon_ttl mp_0">Last Replied By - ${value.last_replied}</span>
								</div>
							</li>`;
						}
			if(value.assign_by_name!=null){
				assign_by_name_data=`<li>
								<div class="dis_ticketbox_infoicon">
						 			<span class="dis_ticketbox_infoicon_icon">
										<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.096,1.904 C9.868,0.676 8.236,-0.000 6.500,-0.000 C4.764,-0.000 3.131,0.676 1.904,1.904 C0.676,3.131 -0.000,4.764 -0.000,6.500 C-0.000,8.236 0.676,9.868 1.904,11.096 C3.131,12.324 4.764,13.000 6.500,13.000 C8.236,13.000 9.868,12.324 11.096,11.096 C12.324,9.868 13.000,8.236 13.000,6.500 C13.000,4.764 12.324,3.131 11.096,1.904 ZM6.500,12.238 C4.802,12.238 3.274,11.497 2.223,10.321 C2.875,8.593 4.544,7.363 6.500,7.363 C5.238,7.363 4.215,6.340 4.215,5.078 C4.215,3.816 5.238,2.793 6.500,2.793 C7.762,2.793 8.785,3.816 8.785,5.078 C8.785,6.340 7.762,7.363 6.500,7.363 C8.456,7.363 10.125,8.593 10.777,10.321 C9.726,11.497 8.198,12.238 6.500,12.238 Z"/></svg>
									</span>
									<span class="dis_ticketbox_infoicon_ttl mp_0">Admin Assign - ${value.assign_by_name}</span>
								</div>
							</li>`;
						}
			if(value.technology!=''){
				technology_data=`<li>
								<div class="dis_ticketbox_infoicon">
						 			<span class="dis_ticketbox_infoicon_icon">
										<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.096,1.904 C9.868,0.676 8.236,-0.000 6.500,-0.000 C4.764,-0.000 3.131,0.676 1.904,1.904 C0.676,3.131 -0.000,4.764 -0.000,6.500 C-0.000,8.236 0.676,9.868 1.904,11.096 C3.131,12.324 4.764,13.000 6.500,13.000 C8.236,13.000 9.868,12.324 11.096,11.096 C12.324,9.868 13.000,8.236 13.000,6.500 C13.000,4.764 12.324,3.131 11.096,1.904 ZM6.500,12.238 C4.802,12.238 3.274,11.497 2.223,10.321 C2.875,8.593 4.544,7.363 6.500,7.363 C5.238,7.363 4.215,6.340 4.215,5.078 C4.215,3.816 5.238,2.793 6.500,2.793 C7.762,2.793 8.785,3.816 8.785,5.078 C8.785,6.340 7.762,7.363 6.500,7.363 C8.456,7.363 10.125,8.593 10.777,10.321 C9.726,11.497 8.198,12.238 6.500,12.238 Z"/></svg>
									</span>
									<span class="dis_ticketbox_infoicon_ttl mp_0">Issue On - ${value.technology}</span>
								</div>
							</li>`;
			}
			html +=`<li>
						<div class="dis_sprtAdmin_ticketbox dis_comn_whiteborder">
							<div class="dis_sprtAdmin_tb_left">
								<div class="dis_sprtAdmin_tb_thumb">
									<span>
										<img src="${value.uc_pic}" alt="thumb" class="img-responsive" onerror="this.onerror=null;this.src='${value.uc_pic_er}'">
									</span>
								</div>
								<div class="dis_sprtAdmin_tb_details">
									<a class="dis_tb_hd_ttl m_b_5 mp_0 m_b_5" href="${base_url+'support/ticketDetails/'+value.id}">${value.subject}</a>
									<ul class="dis_tb_hd_list dis_ticketbox_infoicon_list d-flex">
										<li>
											<div class="dis_ticketbox_infoicon">
												<span class="dis_ticketbox_infoicon_icon">
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.096,1.904 C9.868,0.676 8.236,-0.000 6.500,-0.000 C4.764,-0.000 3.131,0.676 1.904,1.904 C0.676,3.131 -0.000,4.764 -0.000,6.500 C-0.000,8.236 0.676,9.868 1.904,11.096 C3.131,12.324 4.764,13.000 6.500,13.000 C8.236,13.000 9.868,12.324 11.096,11.096 C12.324,9.868 13.000,8.236 13.000,6.500 C13.000,4.764 12.324,3.131 11.096,1.904 ZM6.500,12.238 C4.802,12.238 3.274,11.497 2.223,10.321 C2.875,8.593 4.544,7.363 6.500,7.363 C5.238,7.363 4.215,6.340 4.215,5.078 C4.215,3.816 5.238,2.793 6.500,2.793 C7.762,2.793 8.785,3.816 8.785,5.078 C8.785,6.340 7.762,7.363 6.500,7.363 C8.456,7.363 10.125,8.593 10.777,10.321 C9.726,11.497 8.198,12.238 6.500,12.238 Z"/></svg>
												</span>
												<span class="dis_ticketbox_infoicon_ttl mp_0"><a href="${value.href}">${value.user_name}</a> - ${value.category_name}</span>
											</div>
										</li>
										<li>
											<div class="dis_ticketbox_infoicon">
												<span class="dis_ticketbox_infoicon_icon">
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M8.867,13.000 L1.132,13.000 C0.509,12.998 0.005,12.464 0.003,11.805 L0.003,1.195 C0.005,0.536 0.509,0.002 1.132,-0.000 L5.928,-0.000 L5.928,2.314 C5.928,3.414 6.770,4.305 7.810,4.305 L9.996,4.305 L9.996,11.805 C9.995,12.464 9.490,12.998 8.867,13.000 ZM3.655,4.140 L2.023,4.140 C1.713,4.140 1.460,4.408 1.460,4.737 C1.460,5.065 1.713,5.333 2.023,5.333 L3.655,5.333 C3.965,5.333 4.218,5.065 4.218,4.737 C4.218,4.408 3.965,4.140 3.655,4.140 ZM7.451,6.792 L2.023,6.792 C1.713,6.792 1.460,7.060 1.460,7.389 C1.460,7.717 1.713,7.985 2.023,7.985 L7.451,7.985 C7.762,7.985 8.015,7.717 8.015,7.389 C8.015,7.060 7.762,6.792 7.451,6.792 ZM7.451,9.448 L2.023,9.448 C1.713,9.448 1.460,9.715 1.460,10.043 C1.460,10.372 1.713,10.640 2.023,10.640 L7.451,10.640 C7.762,10.640 8.015,10.372 8.015,10.043 C8.015,9.715 7.762,9.448 7.451,9.448 ZM6.680,2.314 L6.680,0.562 L9.473,3.509 L7.810,3.509 C7.186,3.508 6.681,2.974 6.680,2.314 Z"></path></svg>
												</span>
												<span class="dis_ticketbox_infoicon_ttl mp_0">Ticket ID #${value.ticket_no}</span>
											</div>
										</li>
										<li>
											<div class="dis_ticketbox_infoicon">
												<span class="dis_ticketbox_infoicon_icon">
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.722,13.000 L1.907,13.000 C1.299,13.000 0.805,12.471 0.805,11.828 L0.805,2.612 C0.805,1.980 1.288,1.466 1.875,1.447 L1.875,3.020 C1.875,3.731 2.420,4.304 3.092,4.304 L3.860,4.304 C4.532,4.304 5.084,3.731 5.084,3.020 L5.084,1.442 L8.545,1.442 L8.545,3.020 C8.545,3.731 9.097,4.304 9.769,4.304 L10.537,4.304 C11.209,4.304 11.754,3.731 11.754,3.020 L11.754,1.447 C12.341,1.466 12.824,1.980 12.824,2.612 L12.824,11.828 C12.824,12.470 12.329,13.000 11.722,13.000 ZM11.397,6.494 C11.397,6.215 11.184,5.990 10.921,5.990 L2.687,5.990 C2.424,5.990 2.211,6.215 2.211,6.494 L2.211,11.254 C2.211,11.532 2.424,11.758 2.687,11.758 L10.921,11.758 C11.184,11.758 11.397,11.532 11.397,11.254 L11.397,6.494 ZM9.734,11.070 L8.761,11.070 C8.607,11.070 8.482,10.938 8.482,10.775 L8.482,9.745 C8.482,9.582 8.607,9.451 8.761,9.451 L9.734,9.451 C9.888,9.451 10.013,9.582 10.013,9.745 L10.013,10.775 C10.013,10.938 9.888,11.070 9.734,11.070 ZM9.734,8.497 L8.761,8.497 C8.607,8.497 8.482,8.365 8.482,8.202 L8.482,7.172 C8.482,7.009 8.607,6.877 8.761,6.877 L9.734,6.877 C9.888,6.877 10.013,7.009 10.013,7.172 L10.013,8.202 C10.013,8.365 9.888,8.497 9.734,8.497 ZM7.301,11.070 L6.328,11.070 C6.174,11.070 6.049,10.938 6.049,10.775 L6.049,9.745 C6.049,9.582 6.174,9.451 6.328,9.451 L7.301,9.451 C7.455,9.451 7.580,9.582 7.580,9.745 L7.580,10.775 C7.580,10.938 7.455,11.070 7.301,11.070 ZM7.301,8.497 L6.328,8.497 C6.174,8.497 6.049,8.365 6.049,8.202 L6.049,7.172 C6.049,7.009 6.174,6.877 6.328,6.877 L7.301,6.877 C7.455,6.877 7.580,7.009 7.580,7.172 L7.580,8.202 C7.580,8.365 7.455,8.497 7.301,8.497 ZM4.868,11.070 L3.895,11.070 C3.741,11.070 3.616,10.938 3.616,10.775 L3.616,9.745 C3.616,9.582 3.741,9.451 3.895,9.451 L4.868,9.451 C5.022,9.451 5.147,9.582 5.147,9.745 L5.147,10.775 C5.147,10.938 5.022,11.070 4.868,11.070 ZM4.868,8.497 L3.895,8.497 C3.741,8.497 3.616,8.365 3.616,8.202 L3.616,7.172 C3.616,7.009 3.741,6.877 3.895,6.877 L4.868,6.877 C5.022,6.877 5.147,7.009 5.147,7.172 L5.147,8.202 C5.147,8.365 5.022,8.497 4.868,8.497 ZM10.519,3.461 L9.759,3.461 C9.529,3.461 9.342,3.263 9.342,3.019 L9.342,0.441 C9.342,0.197 9.529,-0.000 9.759,-0.000 L10.519,-0.000 C10.749,-0.000 10.936,0.197 10.936,0.441 L10.936,3.019 C10.936,3.263 10.749,3.461 10.519,3.461 ZM3.849,3.461 L3.090,3.461 C2.859,3.461 2.672,3.263 2.672,3.019 L2.672,0.441 C2.672,0.197 2.859,-0.000 3.090,-0.000 L3.849,-0.000 C4.079,-0.000 4.266,0.197 4.266,0.441 L4.266,3.019 C4.266,3.263 4.079,3.461 3.849,3.461 Z"></path></svg>
												</span>
												<span class="dis_ticketbox_infoicon_ttl mp_0">
													Created Date - ${value.created_at}</span>
											</div>
										</li>

										${(value.reply_count>0)?'<li>											<div class="dis_ticketbox_infoicon">												<span class="dis_ticketbox_infoicon_icon">													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="12px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M0.388,5.430 C1.171,3.414 3.287,2.405 6.735,2.405 L8.360,2.405 L8.360,0.484 C8.360,0.354 8.406,0.241 8.498,0.147 C8.590,0.052 8.699,0.004 8.825,0.004 C8.950,0.004 9.059,0.052 9.151,0.147 L12.865,3.989 C12.957,4.084 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.569 12.865,4.665 L9.151,8.507 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.507 C8.406,8.412 8.360,8.300 8.360,8.169 L8.360,6.248 L6.735,6.248 C6.261,6.248 5.837,6.263 5.462,6.293 C5.087,6.323 4.715,6.377 4.345,6.455 C3.975,6.532 3.653,6.638 3.380,6.773 C3.107,6.909 2.852,7.082 2.615,7.295 C2.378,7.508 2.184,7.760 2.034,8.053 C1.884,8.346 1.767,8.692 1.683,9.092 C1.598,9.493 1.555,9.945 1.555,10.451 C1.555,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.550 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.824 1.608,11.887 1.566,11.937 C1.525,11.987 1.468,12.011 1.396,12.011 C1.318,12.011 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.098,11.719 C1.069,11.654 1.037,11.579 1.000,11.494 C0.964,11.409 0.939,11.349 0.924,11.314 C0.310,9.888 0.003,8.760 0.003,7.929 C0.003,6.934 0.131,6.101 0.388,5.430 Z"></path></svg>												</span>												<span class="dis_ticketbox_infoicon_ttl mp_0">													Replied On - '+value.updated_at+'</span>											</div>										</li>':''}
										${teme_name} ${last_replied_data} ${assign_by_name_data}${technology_data}
									</ul>
								</div>
							</div>
							<div class="dis_sprtAdmin_tb_right">
								<div class="custom_dropdown_wrap right">
									<span class="custom_dropdown_btn">
										<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
											<path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
										</svg>
									</span>
									<ul class="custom_dropdown_menu">
										${acceptData}
										<li class="custom_dropdown_item" >
											<a href="javascript:void(0)" class="custom_dd_anchr transfer_Modal" data-id="${value.id}">
												<span class="custom_dd_icon">
													<svg xmlns="http://www.w3.org/2000/svg" width="18" height="15" viewBox="0 0 18 15">
														<path fill-rule="evenodd" fill="rgb(64 64 76)" d="M1624.96,808.534c-1.22.209-3.4,0.329-4.53,0.62a5.943,5.943,0,0,0-4.66,5.27c-0.2,1.3-.21,2.629-0.3,3.945a0.567,0.567,0,0,0,.45.645,0.574,0.574,0,0,0,.66-0.363c0.19-.384.38-0.769,0.59-1.143a5.385,5.385,0,0,1,3.13-2.543,21.579,21.579,0,0,1,4.66-.545v0.416c0,1.154.01,2.308,0,3.462a0.649,0.649,0,0,0,.35.682,0.626,0.626,0,0,0,.73-0.215q3.555-3.353,7.13-6.7a0.619,0.619,0,0,0,0-1.061q-3.57-3.363-7.15-6.726a0.607,0.607,0,0,0-.7-0.2,0.632,0.632,0,0,0-.36.677c0.01,1.154,0,2.308,0,3.462v0.316Zm6.69,3c-1.87,1.765-3.72,3.5-5.62,5.286v-2.993a0.545,0.545,0,0,0-.65-0.654c-1.03.073-3.07,0.117-4.08,0.288a6.346,6.346,0,0,0-3.89,2.058c-0.1.107-.19,0.214-0.28,0.322a7.645,7.645,0,0,1,.08-1.184,4.831,4.831,0,0,1,3.8-4.37,23.6,23.6,0,0,1,4.34-.385,0.582,0.582,0,0,0,.68-0.7v-2.951C1627.94,808.037,1629.78,809.77,1631.65,811.531Z" transform="translate(-1615.47 -804.031)"/>
													</svg>
												</span>
												<span class="custom_dd_text">Transfer</span>
											</a>
										</li>
										<li class="custom_dropdown_item" >
											<a href="javascript:void(0)" class="custom_dd_anchr change_ticket_status" data-id="${value.id}" data-status="${status_value}">
												<span class="custom_dd_icon">
												<svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 13 14">
													<path fill-rule="evenodd" fill="rgb(64 64 76)" d="M1647.73,698.2l-0.52-.522a1.546,1.546,0,0,0,.73-1.313V689.2a1.546,1.546,0,0,0-1.54-1.549h-8.23l-1.42-1.431a0.777,0.777,0,0,0-.54-0.224,0.762,0.762,0,0,0-.54.224,0.775,0.775,0,0,0,0,1.082l0.41,0.412a1.552,1.552,0,0,0-1.11,1.486v7.158a1.546,1.546,0,0,0,1.54,1.549h1.31v1.035a1.037,1.037,0,0,0,.61.958,1.037,1.037,0,0,0,1.12-.162l2.1-1.831h3.64l1.37,1.369A0.761,0.761,0,0,0,1647.73,698.2Zm-9.15-1.817-2.08-.018,0.01-7.176h1.03l6.23,7.194h-2.4a0.756,0.756,0,0,0-.5.187l-1.53,1.331v-0.752A0.764,0.764,0,0,0,1638.58,696.38Zm7.82-7.194c0.01,0,.01.008,0.01,0.018l-0.01,7.176h-0.48l-6.23-7.194h6.71Z" transform="translate(-1634.97 -686)"/>
												</svg>

												</span>
												<span class="custom_dd_text">${status_lable}</span>
											</a>
										</li>
										<li class="custom_dropdown_item" >
											<a href="${base_url+'support/ticketDetails/'+value.id}" class="custom_dd_anchr" data-id="${value.id}" data-status="${status_value}">
												<span class="custom_dd_icon">
													<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14">
														<path fill-rule="evenodd" fill="rgb(64 64 76)" d="M1644.72,770.573h-6.36a0.889,0.889,0,0,0,0,1.756h6.36A0.889,0.889,0,0,0,1644.72,770.573Zm-2.56,2.635h-3.87a0.907,0.907,0,0,0,0,1.756h3.87A0.907,0.907,0,0,0,1642.16,773.208Zm4.46-5.778h-9.38a2.356,2.356,0,0,0-2.3,2.394v10.842a0.773,0.773,0,0,0,.43.7,0.707,0.707,0,0,0,.78-0.106l2.93-2.532h7.54a2.359,2.359,0,0,0,2.31-2.4v-6.5A2.358,2.358,0,0,0,1646.62,767.43Zm0.83,2.394v6.5a0.844,0.844,0,0,1-.83.858h-7.81a0.716,0.716,0,0,0-.47.179l-1.92,1.661v-9.2a0.842,0.842,0,0,1,.82-0.858h9.38A0.844,0.844,0,0,1,1647.45,769.824Z" transform="translate(-1634.94 -767.438)"/>
													</svg>
												</span>
												<span class="custom_dd_text">Reply</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<span class="dis_ticketFlag ${classsName}">${tikcket_status}</span>
						</div>
					</li>`;
		});

	}else{
		html=`<li><div class="no_result_inner text-center">
				<svg xmlns="http://www.w3.org/2000/svg" width="144px" height="141px" class="no_resultsvg">
				<path class="a" fill-rule="evenodd" fill="rgb(232, 233, 234)" d="M102.927,67.238 C102.868,85.522 87.999,100.297 69.715,100.239 C51.430,100.180 36.656,85.311 36.714,67.027 C36.773,48.742 51.642,33.968 69.926,34.026 C88.210,34.085 102.985,48.954 102.927,67.238 Z"></path>
				<path class="b" fill-rule="evenodd" fill="rgb(189, 194, 203)" d="M142.570,51.942 L139.426,51.942 L139.426,55.084 C139.426,55.647 138.969,56.103 138.406,56.103 C137.843,56.103 137.387,55.647 137.387,55.084 L137.387,51.942 L134.243,51.942 C133.681,51.942 133.224,51.485 133.224,50.923 C133.224,50.360 133.681,49.904 134.243,49.904 L137.387,49.904 L137.387,46.761 C137.387,46.198 137.843,45.742 138.406,45.742 C138.969,45.742 139.426,46.198 139.426,46.761 L139.426,49.904 L142.570,49.904 C143.133,49.904 143.589,50.360 143.589,50.923 C143.589,51.485 143.133,51.942 142.570,51.942 ZM104.580,90.518 L136.892,123.739 C138.772,125.671 139.787,128.219 139.749,130.915 C139.711,133.611 138.624,136.130 136.689,138.009 C134.756,139.888 132.206,140.902 129.510,140.864 C126.815,140.825 124.295,139.740 122.414,137.806 L98.385,113.101 C98.384,113.100 98.382,113.098 98.381,113.097 L89.621,104.090 C84.913,106.619 79.742,108.234 74.339,108.822 C73.530,108.910 72.711,108.975 71.904,109.017 C61.657,109.542 51.580,106.270 43.533,99.805 C34.789,92.781 29.307,82.790 28.097,71.674 C26.887,60.557 30.091,49.622 37.118,40.882 C44.146,32.143 54.142,26.664 65.264,25.454 C76.387,24.245 87.326,27.447 96.070,34.471 C104.814,41.496 110.296,51.486 111.506,62.603 C112.400,70.815 110.871,79.021 107.084,86.332 C106.309,87.837 105.456,89.237 104.580,90.518 ZM126.034,134.289 C126.975,135.256 128.234,135.799 129.582,135.818 C130.929,135.837 132.204,135.330 133.171,134.390 C134.139,133.450 134.682,132.192 134.701,130.844 C134.720,129.497 134.213,128.223 133.273,127.257 L111.177,104.540 L103.939,111.573 L126.034,134.289 ZM100.411,107.945 L107.649,100.912 L101.466,94.555 C98.813,97.625 96.603,99.380 96.565,99.410 C95.738,100.098 94.887,100.750 94.015,101.369 L100.411,107.945 ZM106.621,63.134 C104.499,43.645 87.504,29.164 67.929,30.167 C67.221,30.203 66.504,30.261 65.795,30.338 C55.976,31.405 47.152,36.243 40.947,43.958 C34.743,51.674 31.914,61.328 32.982,71.142 C34.051,80.957 38.891,89.777 46.610,95.979 C54.330,102.181 63.987,105.007 73.808,103.939 C81.616,103.090 88.817,99.855 94.631,94.585 L94.665,94.554 C97.561,91.925 100.015,88.869 101.959,85.472 C105.858,78.663 107.470,70.939 106.621,63.134 ZM77.256,64.907 C76.232,64.904 75.358,64.539 74.636,63.812 C73.914,63.086 73.554,62.211 73.557,61.187 C73.560,60.164 73.926,59.291 74.652,58.569 C75.379,57.848 76.255,57.488 77.279,57.491 C78.303,57.495 79.176,57.860 79.899,58.586 C80.621,59.313 80.980,60.187 80.977,61.211 C80.974,62.235 80.609,63.107 79.882,63.829 C79.155,64.550 78.279,64.910 77.256,64.907 ZM80.400,78.271 C80.554,78.754 80.514,79.223 80.280,79.675 C80.047,80.128 79.689,80.431 79.205,80.584 C78.722,80.737 78.248,80.697 77.785,80.463 C77.322,80.230 77.014,79.862 76.862,79.360 C76.384,77.813 75.494,76.560 74.193,75.600 C72.892,74.640 71.430,74.157 69.807,74.152 C68.184,74.147 66.719,74.620 65.412,75.571 C64.105,76.523 63.207,77.770 62.719,79.315 C62.563,79.816 62.257,80.182 61.803,80.412 C61.348,80.643 60.880,80.680 60.397,80.524 C59.895,80.367 59.529,80.062 59.299,79.608 C59.068,79.154 59.031,78.685 59.187,78.203 C59.909,75.868 61.249,73.990 63.204,72.567 C65.161,71.144 67.365,70.437 69.819,70.445 C72.272,70.453 74.474,71.174 76.420,72.610 C78.367,74.045 79.694,75.932 80.400,78.271 ZM62.418,64.860 C61.394,64.856 60.521,64.491 59.798,63.765 C59.076,63.039 58.716,62.164 58.720,61.140 C58.723,60.117 59.088,59.244 59.815,58.522 C60.542,57.800 61.417,57.441 62.441,57.444 C63.465,57.447 64.338,57.812 65.060,58.539 C65.783,59.265 66.142,60.140 66.139,61.164 C66.136,62.187 65.771,63.060 65.044,63.782 C64.317,64.503 63.441,64.863 62.418,64.860 ZM87.267,12.903 C83.760,12.903 80.899,10.043 80.899,6.538 C80.899,3.026 83.760,0.173 87.267,0.173 C90.781,0.173 93.636,3.026 93.636,6.538 C93.636,10.043 90.781,12.903 87.267,12.903 ZM87.267,2.211 C84.882,2.211 82.938,4.154 82.938,6.538 C82.938,8.922 84.882,10.865 87.267,10.865 C89.653,10.865 91.597,8.922 91.597,6.538 C91.597,4.154 89.653,2.211 87.267,2.211 ZM29.003,17.857 L31.183,20.122 C31.573,20.527 31.561,21.172 31.155,21.562 C30.750,21.953 30.104,21.940 29.714,21.535 L27.534,19.270 L25.268,21.449 C24.863,21.839 24.217,21.827 23.827,21.421 C23.437,21.016 23.449,20.371 23.855,19.981 L26.120,17.802 L23.941,15.538 C23.550,15.132 23.563,14.487 23.968,14.097 C24.374,13.707 25.020,13.719 25.410,14.125 L27.589,16.389 L29.855,14.210 C30.261,13.819 30.906,13.832 31.296,14.237 C31.687,14.643 31.675,15.288 31.269,15.678 L29.003,17.857 ZM25.581,113.255 C25.676,112.700 26.202,112.327 26.758,112.421 C27.312,112.516 27.686,113.043 27.591,113.597 L27.063,116.694 L30.162,117.222 C30.717,117.318 31.090,117.844 30.996,118.398 C30.901,118.953 30.375,119.326 29.820,119.232 L26.720,118.704 L26.192,121.801 C26.097,122.356 25.571,122.729 25.015,122.634 C24.824,122.601 24.653,122.517 24.517,122.397 C24.258,122.172 24.120,121.821 24.182,121.459 L24.710,118.361 L21.611,117.833 C21.419,117.800 21.249,117.716 21.112,117.596 C20.854,117.370 20.716,117.020 20.777,116.657 C20.872,116.102 21.398,115.730 21.954,115.824 L25.053,116.352 L25.581,113.255 ZM10.330,73.926 C8.941,74.905 7.257,75.292 5.576,75.005 C4.407,74.804 3.337,74.293 2.467,73.532 C2.089,73.201 1.746,72.829 1.453,72.411 C0.469,71.028 0.086,69.339 0.369,67.664 C0.578,66.466 1.100,65.406 1.847,64.552 C3.261,62.936 5.452,62.072 7.718,62.455 C11.177,63.052 13.517,66.342 12.925,69.805 C12.637,71.475 11.714,72.943 10.330,73.926 ZM7.375,64.466 C5.022,64.070 2.788,65.654 2.381,68.006 C1.980,70.354 3.565,72.597 5.923,72.998 C7.063,73.191 8.207,72.927 9.149,72.262 C10.097,71.592 10.719,70.602 10.913,69.463 C11.314,67.106 9.729,64.872 7.375,64.466 Z"></path>
				</svg>
				<p>No Tickets Found.</p>
			</div></li>`;
	}
	return html;
}

$(document).on('click' , '.accept_ticket' , function(){
	let _this 	= 	$(this);
	let formData = new FormData();
	let uid 	=	_this.attr('data-id');
		formData.append('ticket_id', uid);
		manageMyAjaxPostRequestData(formData , base_url +'support/adminTicketAccept' ).done(function(resp){
			Custom_notify(resp['type'],resp['message']);
			ticketStart =0;
			getUserTicket();
		})
});

$(document).on('click' , '.transfer_Modal' , function(){
	let _this 	= 	$(this);
	let uid 	=	_this.attr('data-id');

	$("#ticket_id").val(uid);
	$("#transferTicket").modal('show');
});


$('form.transferTicketForm').on('submit' , function(e){
	var _this = $(this);
	e.preventDefault();
	var checkValid = checkRequire(_this);
	if(checkValid == 0){
		let formData = new FormData(_this[0]);
		manageMyAjaxPostRequestData(formData , base_url +  $(this).attr('action')).done(function(resp){
			if(resp['status'] == 1){
				Custom_notify(resp['type'],resp['message']);
				//$(_this).trigger("reset");
				ticketStart =0;
				getUserTicket();
					$("#transferTicket").modal('hide');
			}
		});
	}
});

function showReplay(){
	let _this 	= 	$(this);
	let formData = new FormData();
	let uid 	=	$('input[name="ticket_id"]').val();
		formData.append('ticket_id', uid);
		manageMyAjaxPostRequestData(formData , base_url +'support/TicketLimit' ).done(function(resp){
			$("#showReplay").html(resp.message)
		})
}
if($("#showReplay").length>0){
	showReplay();
}

$(document).on('click' , '.change_ticket_status' , function(){
	let _this 	= 	$(this);
	let formData = new FormData();
	let uid 	=	_this.attr('data-id');
	let status 	=	_this.attr('data-status');
		formData.append('ticket_id', uid);
		formData.append('status', status);
		manageMyAjaxPostRequestData(formData , base_url +'support/change_ticket_status' ).done(function(resp){
			Custom_notify(resp['type'],resp['message']);
			ticketStart =0;
			getUserTicket();
		})
});

$(document).on('change' , '#file1' , function(){
	if($(this).get(0).files.length>2){
		$(this).val('');
		$('.file_name').html('');
		Custom_notify('error',"You can only upload a maximum of 2 files");
		return false;
	}
	
	let file_name="";
	for(var i=0;i< $(this).get(0).files.length;i++){
		let file_size_mb=Math.round($(this).get(0).files[i].size/1048576);
		let file_type=$(this).get(0).files[i].type;
		if(file_size_mb>20){
			$(this).val('');
			$('.file_name').html('');
			Custom_notify('error',"You can only upload a maximum of 20 MB files");
			return false;
		}
		  var file = $(this).get(0).files[i].name;
		  var ext = file.split('.').pop();
		  //console.log(ext);
		  if(ext!="mp4" && ext!="pdf" && ext!="jpeg" && ext!="jpg" && ext!="png" && ext!="docx"){
		    $(this).val('');
			$('.file_name').html('');
			Custom_notify('error',"You can only upload mp4,pdf,jpeg,png,docx files");
			return false;
		  }
		file_name+=$(this).get(0).files[i].name+', ';
	}
	$('.file_name').html(file_name);
});

/*$(document).on('click' , '.file_data' , function(){
	let _this 	= 	$(this);
	let html='<iframe src="'+_this.attr('data-url')+'">';
	$(".dis_field_box").html(html);
	$('#showFileData').modal('show');
});*/


$( document ).ready(function(){

   if ($(".datePicker_new").length){
	    $(".datePicker_new").daterangepicker({
	        // singleDatePicker: true,
	        showDropdowns: true,
	        minYear: 1920,
	        autoUpdateInput: false,
	        // maxYear: parseInt(moment().format('YYYY'),10),
	        locale: {
	            format: "YYYY-MM-DD",
	            cancelLabel: 'Clear'
	        },
	    });
	}

	$('.datePicker_new').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
      dataTableObjs[0].ajax.url(base_url+'support/access_support_ticket').clear().draw();
  	});

	$('.datePicker_new').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
		dataTableObjs[0].ajax.url(base_url+'support/access_support_ticket').clear().draw();
	});

	$(document).on('change','#f_tech_status,#f_ticket_status,#f_department,.datePicker_new',function(){
		dataTableObjs[0].ajax.url(base_url+'support/access_support_ticket').clear().draw();
	})

	$(document).on('click','.clear_filter',function(){
		$('.datePicker_new').val('');
		$('#f_ticket_status').val('');
		$('#f_department').val('');
		$('#f_tech_status').val('All');
		dataTableObjs[0].ajax.url(base_url+'support/access_support_ticket').clear().draw();
	})

});
