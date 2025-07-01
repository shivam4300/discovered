/*Copyright (c) 2018 ------------------------------------------------------------------
[Master Javascript]
Project : Discovered
-------------------------------------------------------------------*/



if($('div.dropzone').length){
	var VideoUpload = [];
	Dropzone.autoDiscover = false;
}
function erroronimageload(ths){
	ths.onerror=null;ths.src=''+base_url+'repo/images/user/user.png';
}

function errorThumb(ths){
	ths.onerror=null;ths.src=''+base_url+'repo/images/thumbnail.jpg';
}

var dataTableObj=[];

(function ($) {
    "use strict";
	$(document).ajaxStart(function(){
		// $('body').addClass('site_loader');
	});
	$(document).ajaxComplete(function(){
		// $('body').removeClass('site_loader');
	});


	$(document).ready(function () {
		if($('[name="page"]').val() == 'pending'){
			var myHilitor = new Hilitor("content");
			var words = '';
			$.get(base_url+'repo_admin/txt/bad_words.txt', function (data) {
				words = data;
			});
		}

		if($('.daterange').length){
			setTimeout(function(){
			$('.daterange').daterangepicker({
				autoUpdateInput: false,
				locale: {
				  format: 'YYYY/MM/DD',
				  cancelLabel: 'Clear'
				},
				ranges: {
				   'Today': [moment(), moment()],
				   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				   'This Month': [moment().startOf('month'), moment().endOf('month')],
				   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
				   'This Year':[moment().startOf('year'), moment()],
				   'Last Year':[moment().subtract(1, 'years').startOf('year'), moment().subtract(1, 'years').endOf('year')]
				}
			},function(start, end) {});
			},1000);
			$('.daterange').attr("placeholder","Select A Date Range");
		
			$('.daterange').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
				let table = document.querySelector("table");
				if(table.getAttribute('data-action-url') != null)
				dataTableObj[0].ajax.url(base_url+ table.getAttribute('data-action-url')).clear().draw();	
			});
			
			$('.daterange').on('cancel.daterangepicker', function(ev, picker) {
				$(this).val('');
				let table = document.querySelector("table");
				if(table.getAttribute('data-action-url') != null)
				dataTableObj[0].ajax.url(base_url+ table.getAttribute('data-action-url')).clear().draw();		
			});
			setTimeout(function(){$('.daterange').addClass('filter');},1000);
		}
		
		
		 /* initialize simple datatable*/
		if($( ".simpleDataTable" ).length > 0){
			var table = [];
			var simpleDataTableCount = 0;
			setTimeout(function(){ 
				$('table.simpleDataTable').each(function(){
					table[simpleDataTableCount++] = $(this).DataTable();
					console.log(table);
				});
			 }, 1000);
		}
		$('#column3_search').on( 'change', function () {             /* search specific calum with filter or custom serach for static databale*/
			table[$(this).data('table')]
				.columns($(this).data('column'))
				.search( this.value )
				.draw();
		} );
		/* end simple datatable*/
		
		/* initialize Server side datatable*/
		if($( ".display" ).length > 0){
			// var dataTableObj = [];
			var dataTableCount = 0;
			setTimeout(function(){ 
				$('table.display').each(function(){
					dataTableObj[dataTableCount++] = manage_dataTable($(this));
					console.log(dataTableObj);
					if($('#page').val() == 'channel_post_video'){
					// non_profanityIds();
					}	
				});
			 }, 1000);
		}
		
		function non_profanityIds() { 
			let url = 'admin/findNonProfanityIds';
			let formData = {'data': 'data'};
			manageMyAjaxPostRequestData(formData, base_url + url ).done(function (resp) {
				
		});
		}

		function manage_dataTable(_this){
			
			var tableOption = {};
			if(_this.hasClass('dataTableAjax')){
				var actionUrl = base_url+_this.attr('data-action-url');
				 
				tableOption["lengthMenu"] 	= [ 10, 25, 50, 75, 100,500,1000 ];
				tableOption["processing"] 	= true;
				tableOption["searchDelay"] 	= 1500;
				tableOption["dom"] 			= 'Blfrtip';
				if(actionUrl == base_url+'admin/access_channel_video_list/1'){
					tableOption["buttons"] 		= [];
				}else{
					tableOption["buttons"] 		= ['excel'];
				}
				tableOption["language"] 	= {
					  // processing: "<div class='table_loader'><img src='"+base_url+"repo_admin/images/preloader.gif'></div>"
				};
				tableOption["searching"] 	= (typeof _this.attr('data-search') != 'undefined') ? eval( _this.attr('data-search')) : true ;
				tableOption["serverSide"] 	= true;
				tableOption["ajax"] 		= {
					"url":actionUrl,
					"data":function ( d ) {
						if(typeof _this.attr('data-filter') != 'undefined'){
							if( _this.attr('data-filter') == '1'){
								d.staff = $('#staff').val(); 	
							}

							if($('.handler').length){
								Sortable();	
							}
							$('.filter').each(function(){
								d[$(this).attr('name')] =  ($(this).val());
							});
					
							$('.sliderfilter').each(function(){
								d[$(this).attr('name')] =  ($(this).val());
							});
						}	
					}
				};
			
				if(typeof _this.attr('data-column-class') != 'undefined'){
					tableOption["columns"] = eval(_this.attr('data-column-class'));
				}
				
				if(typeof _this.attr('data-orders') != 'undefined'){
					tableOption["order"] = eval(_this.attr('data-orders'));
				}
					
				if(typeof _this.attr('data-pageLength') != 'undefined'){
					tableOption["pageLength"] = eval(_this.attr('data-pageLength'));
				}

				if(typeof _this.attr('data-bLengthChange') != 'undefined'){
					tableOption["bLengthChange"] = eval(_this.attr('data-bLengthChange'));
				}
				
				if(typeof _this.attr('data-sort') != 'undefined'){
					tableOption["columnDefs"] = eval(_this.attr('data-sort'));
				}
					
					tableOption["drawCallback"] = function ( settings) {
										var api = this.api();
										var res = api.rows( {page:'current'} ).data();	
										var tot = api.page.info().recordsTotal;
										 
										if( $('#TotalRecord').length) $('#TotalRecord').text('Total Records : ' + tot);
										 
										$('[data-toggle="tooltip"]').tooltip()
										
										if($('.SelectPostIds').length){
											appendCheckBoxValue();
											setTimeout(()=>{
												appendCheckBoxOrderValue()
											},500)
										}

										if($('[name="page"]').val() == 'pending'){
											myHilitor.apply(words);
										}
									}
					tableOption["destroy"]		=	true;

				if(typeof _this.attr('data-callback') != 'undefined'){ 
					tableOption["fnRowCallback"] = function ( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
										
										let check = eval(_this.attr('data-callback'));
										if (check == -1){
											$('td', nRow).css({'color':'white','background-color':'Red' });
										}
									}
				
				} 
			}
			return _this.DataTable(tableOption);
		}
		/* end Server side datatable*/
		$(document).on('change , keyup','.filter:not(#filter_profanity_words)',function(){
			let table = $(this).parents('.tab-pane').find('table');
			
			dataTableObj[table.attr('data-refresh-dataTablePosition')]?.ajax.url(base_url+table.attr('data-action-url')).clear().draw();	
		})
		
		if($( ".accessAjaxData" ).length > 0){
			$('.accessAjaxData').each(function(){
				manage_ajax_content_data($(this));
			});
		}
		
		$(document).on('keypress' , 'input[data-type="number"]' , function(e){
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});
		
		$(document).on('keypress' , 'input[data-type="float_num"]' , function(evt){
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode == 8 || charCode == 37) {
					return true;
				} else if (charCode == 46 && $(this).val().indexOf('.') != -1) {
					return false;
				} else if (charCode > 31 && charCode != 46 && (charCode < 48 || charCode > 57)) {
					return false;
				}
					return true;
		});
		
	//init select2
	if($('[data-target="select2"]').length){
		$('[data-target="select2"]').each(function(){
			var options = eval('[' + $(this).attr('data-option') + ']');
			if ($.isPlainObject(options[0])) {
				options[0] = $.extend({}, options[0]);
			}
			$(this)[$(this).attr('data-target')].apply($(this), options);
		});
	}

	$(document).on('change' , '.ShowHideCal' , function(e){
		console.log($(this).val())
		dataTableObj[0].columns($(this).val()).visible(true);
	});
	$('.ShowHideCal').on("select2:unselecting", function(e){
		var unselected_value = $(this).val();
		dataTableObj[0].columns(unselected_value).visible(false);
   })
	
		
   $('select[data-action-url]').on('change' ,function(){
	var _thi = $(this);
	var url = _thi.data('action-url');	
	if(url){
		let formData = new FormData();
		
		if($(this).attr('data-mode')){			/*optional - not a part of reusablity */
			formData.append('mode', $(this).attr('data-mode'));	
		}
		let id = $(this).val();
		formData.append('id', id );
		if(id && id.length){
			manageMyAjaxPostRequestData(formData, base_url + url ).done(function (resp) {
				
					if(resp.length){
						
					var resp = JSON.parse(resp);
					
					if (resp['status'] == 1) {
						var data = [];
							$(_thi.attr('data-id')).empty();
							
							data = [{id:'',text:''}];
							
							$.each(resp['data'], function (key, val) {
								if(val.name && val.name.length){
									data.push({id: val.id ,text: val.name});
								}
								
							});
							console.log(resp['data'],'data');
							$(_thi.attr('data-id')).select2(
									{	data: data,
										placeholder		:  _thi.attr('data-placeholder'),	
										closeOnSelect	: 	true,
										allowClear		:	true,
										allowHtml		:	true
									}
							);
					}else{
					
						Custom_notify('error','Something went wrong, please try again.');
					}
				}
			});
		}
	}
});

	$(document).on('click','.openGenrePopup',function(){
		let _this 	= $(this);
		let type 	= _this.attr('data-type');
		let id 		= _this.attr('data-id');
		let mode 	= _this.attr('data-mode');
		let title 	= _this.attr('data-title');
		let parent 	= _this.attr('data-parent');

		if(type == 'Main'){
			$('#genremode').modal('show');
			$('#mode_id1').val(mode);
			$('#genre_name1').val(title);
			$('#genre_id1').val(id);
		}else{
			$('#subgenre').modal('show');
			$('#genre_id2').val(id);
			$('#mode_id2').val(mode).trigger('change');
			setTimeout(function(){
				$('#maingenre').val(parent)
				$('#genre_name2').val(title);
			},2000)
		}
	})


	$('.getUserAndGenreByMode').on('change' ,function(){
		var _thi = $(this);
		var url = _thi.attr('data-url');	
		if(url){
			let formData = new FormData();
			
			$(this).attr('data-mode', $("select[name='mode']").val());
			
			let GnrPlaceHold;
			if($(this).attr('data-mode')){			/*optional - not a part of reusablity */
			
				if($(this).attr('data-mode') == '10'){
					$('#Changelabelname').text('Select Category');
					GnrPlaceHold = 'Select Category';
				}else{
					GnrPlaceHold =  _thi.attr('data-placeholder-genre');
					$('#Changelabelname').text('Select Genre')
				}
				formData.append('mode', $(this).attr('data-mode'));	
			}
			
			if(_thi.attr('name')=='mode'){			/*optional - not a part of reusablity */
				$('#user_type').val('')
			}
			if(_thi.attr('name')=='user_level'){
				formData.append('id', $(this).val());
			}
			
			manageMyAjaxPostRequestData(formData, base_url + url ).done(function (resp) {
					if(resp.length){
					resp = JSON.parse(resp);
					if (resp['status'] == 1) {
						var data = [];
						if(resp['data']['userList']){
							$(_thi.attr('data-id-user')).empty();
							data = [{id:'',text:''}];
							
							$.each(resp['data']['userList'], function (key, val) {
								 data.push({id: val.id ,text: val.name});
							});
							
							$(_thi.attr('data-id-user')).select2(
									{	data: data,
										placeholder		:  _thi.attr('data-placeholder-user'),	
										closeOnSelect	: 	true,
										allowClear		:	true,
										allowHtml		:	true
									}
							);
						}
						
						if(resp['data']['genreList']){
							$(_thi.attr('data-id-genre')).empty();
							data = [{id:'',text:''}];
							
							$.each(resp['data']['genreList'], function (key, val) {
								 data.push({id: val.id ,text: val.name});
							});
							
							$(_thi.attr('data-id-genre')).select2(
									{	data: data,
										placeholder		:  GnrPlaceHold,	
										closeOnSelect	: 	true,
										allowClear		:	true,
										allowHtml		:	true
									}
							);
						}
					}else{
						Custom_notify('error','Something went wrong, please try again.');
					}
				}
			});
		}
	});

	

	$('#filter_by_size').change(function(){
		if($(this).is(':checked')){
			$(this).val('1');
		}else{
			$(this).val('0');
		}
	});
	
	function formatPageOption (opt) {
		
		if (!opt.id) {
			return opt.text;
		} 
		var optimage = $(opt.element).attr('data-src'); 
		var width = '';
		if($(opt.element).attr('data-width')){
			width = $(opt.element).attr('data-width'); 	
		}
		
		
		if(!optimage){
		   return opt.text;
		} else {                    
			var $opt = $(
			   '<span><img style="width:'+width+'"  src="' + optimage + '" /> ' + opt.text + '</span>'
			);
			return $opt;
		}
	};

	

	var mySaveButtonTarget;
		
	$(document).on('click' , '[data-savable="true"]' , function(){ 
		mySaveButtonTarget = $(this);
		var tareget = $(this).closest($(this).attr('data-target'));
		console.log(tareget,'tareget');
		tareget.find('form.myFormList').submit();
	});
		
	
	$('form.myFormList').on('submit' , function(e){
			
			var _this = $(this);
			e.preventDefault();
			var checkValid = checkRequire(_this);
			
			if(checkValid == 0){
				let formData = new FormData(_this[0]); 
				manageMyAjaxPostRequestData(formData , base_url +  $(this).attr('action')).done(function(resp){
					if(resp['status'] == 1){
					
						Custom_notify('success',resp['message']);
						//reset form data
						if(_this.attr('data-reset') == '1'){
							_this[0].reset();
							 $("input[type='hidden']").val(' ');
						}
						
						//redirect after add/update form data
						if(_this.attr('data-redirect')){
							setTimeout(function(){ location.href = base_url+_this.attr('data-redirect'); }, 2000);
						}
						if(_this.attr('data-model-hide')){
							$(_this.attr('data-model-hide')).modal('hide');
						}
						
						// close model after add/update data
						if(mySaveButtonTarget && mySaveButtonTarget.attr('data-modal-button')){
							$('#'+mySaveButtonTarget.closest('[role="dialog"]').attr('id')).modal('hide');
						}
						
						//reload added/updated data in dataTable or normal ajax load
						if(mySaveButtonTarget && mySaveButtonTarget.attr('data-refresh-content')){
							if(mySaveButtonTarget.attr('data-refresh-content') == 'simple'){
								manage_ajax_content_data($('.accessAjaxData'));	
							
							}else if(mySaveButtonTarget.attr('data-refresh-content') == 'dataTable'){
								var targetTableUrl = $($('table.dataTableAjax')[mySaveButtonTarget.attr('data-refresh-dataTablePosition')]).attr('data-action-url'); 
								if(typeof targetTableUrl != 'undefined'){
									dataTableObj[mySaveButtonTarget.attr('data-refresh-dataTablePosition')].ajax.url(base_url+targetTableUrl).clear().draw();
								}
							}
						}
					}
				});
			}
		});
		
		$(document).on('click , change' , '[data-target="#subcategory_form"],[data-target="#sub-subcategory_form" ],[data-target="#attribute"]' , function(e){
			$("input[type='hidden']").val(' ');
			
			manage_ajax_content_data($(this));
		})
		
		$(document).on('click' , 'a[data-modal-id]' , function(){
			var _this 	= 	$(this);
			var url 	=	_this.attr('data-modal-url');
			let formData = new FormData();
			var uid 	=	_this.attr('data-modal-id');
				formData.append('user_id', uid);
				if(_this.attr('data-cat-level') == 1){
					$('#category_name').val(_this.attr('data-cat-name'));
					$('#category_id').val(_this.attr('data-modal-id'));
					$('#category_form').modal('show');
				}else{
					$('#subcategory_form').modal('show');
					manage_ajax_content_data($(this));
					$('#subcategory_name').val(_this.attr('data-cat-name'));
					$('#category_ids').val(_this.attr('data-modal-id'));
					setTimeout(function(){
						$('#categorylist').val(_this.attr('data-parent-id')).change();
					},1000);
				}
				
		});
		
		$('#subcategory_form,#category_form').on('hidden.bs.modal', function () {
			$('#category_id,#category_ids,#category_name,#subcategory_name').val().empty();
		})
	
	

	$(document).on('change' , 'input[data-check-id] , select[data-check-id]' , function(){	
		var _this 		= 	$(this);
		let formData 	= new FormData();
		var status 		= 0;
		var url 		=	_this.attr('data-action-url');
		var id 			=	_this.attr('data-check-id');
		// alert(_this[0].nodeName);
		if(_this[0].nodeName == "SELECT"){
			status = _this.val();
		}else{
			if (_this.is(":checked")) 
			status = 1;
		}
		
		
		
		formData.append('id', id);
		formData.append('status', status);
		
		manageMyAjaxPostRequestData(formData, base_url + url ).done(function (resp) {
			if (resp['status'] == 1) {
				if(_this.parents('table.display').attr('data-refresh-dataTablePosition')){
					dataTableObj[_this.parents('table.display').attr('data-refresh-dataTablePosition')].ajax.url(base_url+_this.parents('table.display').attr('data-action-url')).clear().draw();	
				}
				Custom_notify('success',resp['message']);
			}else{
				Custom_notify('error',resp['message']);
			}
		});	
	});
	
	$('a[data-id]').on('click' , function(){
		let _this = $(this);
		let modal_no = _this.attr('data-modal');
		$('#vote_count'+modal_no).val(_this.attr('data-vote'));
		$('#id'+modal_no).val(_this.attr('data-id'));
		$('#user_form'+modal_no).modal('show');
	})
	
	$(document).on('change','.changeOption',function(){
		let _this = $(this);
		let formData = new FormData();
		formData.append('val', _this.val());
		formData.append('id',_this.data('id'));
		manageMyAjaxPostRequestData(formData , base_url +  _this.data('action-url')).done(function(resp){
			if (resp['status'] == 1) {
				if(_this.parents('table.display').attr('data-refresh-dataTablePosition')){
					dataTableObj[_this.parents('table.display').attr('data-refresh-dataTablePosition')].ajax.url(base_url+_this.parents('table.display').attr('data-action-url')).clear().draw();	
				}
				Custom_notify('success',resp['message']);;
			}else{
				Custom_notify('error',resp['message']);;
			}
		})
	});
	
	$(document).on('click','.setTab',function(){
		let _this = $(this);
		document.cookie = 'setTab' + "=" +  _this.attr('href');
	});
		
	$(document).on('click' , 'a[data-user-id]' , function(){
			let _this 	= 	$(this);
			let url 	=	_this.attr('data-user-url');
			let formData = new FormData();
			let uid 	=	_this.attr('data-user-id');
				formData.append('user_id', uid);
				manageMyAjaxPostRequestData(formData , base_url + url).done(function(resp){
					if (resp['status'] == 1) {
						let data = resp['data'];
						$('#getArtistSubCategory').html(data.artist_subCategory);
						$('select[name="uc_state"]').html(data.state_list);
						
						$('#uc_about').val(data.uc_about);
						$.each(data, function (key, val) {
							$('input[name="'+key+'"]').val(val);
							$('select[name="'+key+'"]').val(val);
							$('#'+key).attr('src',val);
							if(key == 'uc_type'){
								if(val != null && val != '')
								$.each(val.split(","), function(i,e){
									 $("select option[value='" + e + "']").prop("selected", true);
								});
							}
							
							if(key == 'uc_pic'){
								$('#'+key).html('<img class="img-circle" src="'+val+'" alt="User Avatar" onerror="erroronimageload(this)">');
							}
						});
						// $('#aws_s3_profile_video').parents('video')[0].load();
						$('[name="user_uname"] , [name="user_id"] , [name="user_name"] , [name="user_email"] ').prop('readonly',true);
						$('#user_form').modal('show');
					}else{
						Custom_notify('error',resp['message']);;
					}
				})
				
				
		});	
		
	$(document).on('click' ,'.add_user_form' , function(e){
		$('#uc_video').attr('src', '');
		$('#user_name').text('Add User');
		$('#category_name').text('Official');
		$('#uc_about').text('');
		$("input[type='hidden']").val(' ');
		$('#uc_pic').attr('src',base_url +'repo/images/user/user.png');
		$('[name="user_uname"]').prop('readonly',false);
		$('[name="user_id"]').prop('readonly',false);
		$('[name="user_name"]').prop('readonly',false);
		$('[name="user_email"]').prop('readonly',false);
		
		$('form.myFormList')[0].reset();
		$('#user_form').modal('show');
		$('#videoAndImage').hide();
		
	});	
	$('#user_form').on('hidden.bs.modal', function () {
		$('#uc_pic').attr('src',''); 
	})	
	
	$('.SelectBySimpleSelect').on('change' , function(){
		let _this = $(this);
		let formData = new FormData();
		formData.append('id' , _this.val());
		
		if(_this.val() != '' && _this.val() != 0){
			manageMyAjaxPostRequestData(formData, base_url + _this.data('select-url')).done(function(resp){
				console.log(resp);
				var elementId = _this.data('id');
				$(elementId).html(resp);
			}); 
		}
	});	
	
	$('[name="user_email"]').on('change',function(){
		var str = $(this).val();
		str = str.split('@').slice(1);
		if (str[0] !== 'discovered.tv') {
			alert('Please enter the email end with "@discovered.tv"');
			$(this).val('');
			$(this).focus();
		}
	})
	
	$('.dataTableAjax').on('click' ,'a[data-fetch-id]', function(){
		
		var _this 	= 	$(this);
			let formData = new FormData();
			var url =  _this.attr('data-action-url');
			formData.append('id' , _this.attr('data-fetch-id'));
			formData.append('active_status' , _this.attr('data-active-status'));
				manageMyAjaxPostRequestData(formData , base_url +url).done(function(resp){
					var resp = JSON.parse(resp);
					if (resp['status'] == 1) {
						var data = resp['data'];
						console.log(data);
						var detail = '';
						$('#video_detail').html('');
						
						if(url == 'admin/getChannelVideoData'){
							
							$('#background').css('background-image','url('+_this.attr('data-img-src')+')');
							
							var prourl = _this.attr('data-pro-src');
							if(prourl.length){
								$('#profile').attr('src',_this.attr('data-pro-src'));
							}else{
								$('#profile').attr('src',base_url +'repo/images/user/user.png');
							}
						}
						
						$.each(data, function (key, val) {
							detail += '<tr>\
										<td>'+key.toUpperCase().replace("_", " ")+'</td>\
										<td>'+val+'</td>\
									   </tr>';
						});
						$('#video_detail').html(detail);
					}else{
						Custom_notify('error',resp['message']);
					}
				})
	})

	$(document).on('click' , '.ChangeVideoStatus' , function(e){
			e.preventDefault();		
			var _this 		= 	$(this);
			let formData 	= new FormData();
			var url 		=	_this.attr('data-action-url');
			var status 			=	_this.attr('data-status');
			var id 			=	_this.attr('data-post-id');
			
			formData.append('id', id);
			formData.append('status', status);
			let profwords = '';
			_this.parents('tr').find('mark').each((i,j)=>{
				profwords += j.innerText+' | ';
			});

			formData.append('profwords', profwords);
				
				var swals = {
						  title: "Are you sure?",
						  text: (status == 1)? 'want to approve this video':'want to reject this video',
						  icon: (status == 1)? 'success':'warning',
						  buttons: true,
						  dangerMode: (status == 1)? false : true,
						};
					
				swal(swals)
						.then((done) => {
						  if (done) {
							manageMyAjaxPostRequestData(formData, base_url + url ).done(function (resp) {
									if (resp['status'] == 1) {
										Custom_notify('success',resp['message']);
										if(_this.parents('table.display').attr('data-refresh-dataTablePosition')){
											dataTableObj[_this.parents('table.display').attr('data-refresh-dataTablePosition')].ajax.url(base_url+_this.parents('table.display').attr('data-action-url')).clear().draw();	
										}
									}else{
										Custom_notify('error',resp['message']);
									}
							});	
							} 
						});
			
				
		});
		
		$(document).on('click','.play_post_video',function(e){
			e.preventDefault();
			$(this).magnificPopup({
			fixedContentPos: false,
				type: 'iframe',
				gallery: {
					enabled: true
				}
			});	
		});
		
		$(document).on('click' , '.deleteUser' , function(e){
			e.preventDefault();		
			var _this 		= 	$(this);
			let formData 	= new FormData();
			var url 		=	_this.attr('data-action-url');
			var id 			=	_this.attr('id');
			var delete_type =	_this.attr('data-delete-type');
			console.log('data-status');
			formData.append('id', id);
			formData.append('delete_type', delete_type);
				var swals = {
						  title: "Are you sure want to delete this User ?",
						  text: 'Because every details related with this user, will be removed from our records permanently.' ,
						  icon: 'warning',
						  buttons: true,
						  dangerMode: true ,
						};
					
				swal(swals)
						.then((done) => {
						  if (done) {
							manageMyAjaxPostRequestData(formData, base_url + url ).done(function (resp) {
									if (resp['status'] == 1) {
										Custom_notify('success',resp['message']);;
										if(_this.parents('table.display').attr('data-refresh-dataTablePosition')){
											dataTableObj[_this.parents('table.display').attr('data-refresh-dataTablePosition')].ajax.url(base_url+_this.parents('table.display').attr('data-action-url')).clear().draw();	
										}
									}else{
										Custom_notify('error',resp['message']);;
									}
							});	
							} 
						});
			
				
		});
		
		var sortable = 0;
		function Sortable(){
			
			 setTimeout(function(){ 
				$('.sortable tr').each(function(){
					let id	= $(this).find('.handler').find('a').attr('data-id');
					$(this).attr('data-id',id);
				})
			}, 1500);
			
			sortable++;
			
			if(sortable < 1)
				sortableRow();			
		}
		
		if($( ".sortable").length)
		sortableRow();
		
		function sortableRow(){
			console.log('ajaydeep parmar');
			setTimeout(function(){
				Sortable();
				$( ".sortable" ).sortable({
						handle: '.handle',
						helper: function(e, tr){
							var $originals = tr.children();
							var $helper = tr.clone();
							$helper.children().each(function(index){ 
								$(this).width($originals.eq(index).width());// Set helper cell sizes to match the original sizes
							});
							return $helper;
						},
						update: function( event, ui ) {
							var thi = $(this) ;
							setTimeout(function(){  
									var a = thi.sortable("toArray", {
										attribute: "data-id"
									});
									var url 		= thi.data('url');
									let formData 	= new FormData(); 
									formData.append('reorder_id' , JSON.stringify(a)) ;
									
									if(url == 'homepage_covervideo_order'){
										$("#post_ids").val(a.toString());
									}else{
										manageMyAjaxPostRequestData(formData ,base_url + url).done(function(resp){
											 Custom_notify('success','You have updated the orders successfully.');;
										});
									}
							}, 1000);	
						}
				}); 
			},1500)
		}	
		
		 
				
	
	$(document).on('change','.UploadFile',function(){
		var _this =$(this);
		 var file_data = $('#'+_this.attr('id')).prop('files')[0];   
			var form_data = new FormData();                  
			
			form_data.append('userfile', file_data);
			form_data.append('id', _this.attr('data-id'));
			form_data.append('path', _this.attr('data-path'));
			form_data.append('file_type', _this.attr('data-file_type'));
			                            
			$.ajax({
				url:base_url + _this.attr('data-url'), // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(resp){
					var resp = JSON.parse(resp);
					if(resp.status == 1){
						Custom_notify('success',resp['message']);;
						// setTimeout(function(){
							// location.reload();
						// },2000);
						if(_this.parents('table.display').attr('data-refresh-dataTablePosition')){
							dataTableObj[_this.parents('table.display').attr('data-refresh-dataTablePosition')].ajax.url(base_url+_this.parents('table.display').attr('data-action-url')).clear().draw();	
						}
					}else{
						Custom_notify('error',resp['message']);;
					}
					
					
				}
			 });
	})
	
	
	if($('div.dropzone').length){
		var k = 0;
		$('div.dropzone').each(function(){
			var _this = $(this);
			var url = _this.attr('data-url');
			var id = _this.attr('id');
				
			VideoUpload[k] = new Dropzone('#'+id , {
				paramName		: "userfile",
				url				: url , 
				acceptedFiles	: ".mp4",
				// params: {
					 // publish_input: $('#publish_input').val()
				// },	
				// autoProcessQueue: false,
				maxFiles: 1,
				maxFilesize: 100, // MB
				addRemoveLinks:true,
				dictCancelUpload:'Cancel Upload',
				maxfilesexceeded: function(file) {
					this.removeAllFiles();
				},
				init: function() {
				},
				accept: function(file, done) {
					 done();
				},
				success:function(file,response){
					this.removeAllFiles();
				},
				error: function(file, message) {
					$(document).find('.dz-error-message').text('Server Error');
				}
	
			});
			k++;
					console.log(VideoUpload);
		});	
		
		}
		
	$('.default_profile_video').change(function(){
		console.log('default profile')
		var _this = $(this);
		if(_this.val()){
			let formData = new FormData();
			formData.append('post_id', _this.val());
			formData.append('id', $('[name="user_id"]').val());
			
			manageMyAjaxPostRequestData(formData, base_url + _this.data('url') ).done(function (resp) {
					if(resp.length){
					   resp = JSON.parse(resp);
					
					if (resp['status'] == 1) {
						var data = resp['data'] ;
						$('#ShowmMyDefaultProfileVideo').attr('src',data[0].video);
					}else{
						Custom_notify('error','Something went wrong, please try again.');
					}
				}
			});
		}
	})	
		
	$(document).on('change','[name="user_status"] , [name="is_giveaways"]',function(){
			dataTableObj[0].ajax.url(base_url+'admin/access_userlist/0').clear().draw();
	})
	$(document).on('change','.FilterTableData',function(){
			dataTableObj[0].ajax.url(base_url+$(this).attr('data-url')).clear().draw();
	})
	
	
	if($('.ckeditor').length){
		$('.ckeditor').each(function(){
				
			let _this = $(this);
		
			CKEDITOR.replace( _this.attr('id'),{
				filebrowserUploadUrl: base_url+'admin/SaveEnquiryContentImage',
				filebrowserImageBrowseUrl: base_url+'admin/GetEnquiryContentImage?type=Images',
				filebrowserUploadMethod :"form"
			});
			CKEDITOR.config.versionCheck = false;
			// CKEDITOR.config.toolbar 		= 	'Basic';
			CKEDITOR.config.height 			= 	400;
			// CKEDITOR.config.toolbar_Basic 	=	[
													 // { name: 'clipboard', 	items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
													 // { name: 'editing', 	items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] }, //, 'Scayt'
													 // { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
													 // { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
													 // '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
													 // { name: 'insert',	items : ['Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
													 // { name: 'styles', 	items : [ 'Styles','Format','Font','FontSize' ] },
													 // { name: 'colors', 	items : [ 'TextColor','BGColor' ] },
													 // { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
													 // { name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] },
													 // { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
												
												// ];
		
		})
	}
	
	$(document).on('submit','#SaveEnquiry',function(e){
		e.preventDefault();
		var _this = $(this);
		var checkValid = checkRequire(_this);
		
		if(checkValid == 0){
			let formData 	= new FormData(_this[0]);
			let icon_image 	= (formData.get('icon_image')).name;
			console.log(icon_image.name);
				formData.delete('description');
				var data 	= CKEDITOR.instances.description.getData();
					
					function base64Encode(str) {
						return btoa(unescape(encodeURIComponent(str)));
					}
				
					data = JSON.stringify(data);
					data = base64Encode(data);
					formData.append('description',data);
				
					manageMyAjaxPostRequestData(formData , base_url + 'admin_setting/add_new_enquiries').done(function(resp){
						resp = JSON.parse(resp);
						if(resp.status == 1){
							$("input[type='text']").val('');
							$("input[type='hidden']").val('');
							CKEDITOR.instances.description.setData('');
							$("#icon_image").val('');
							$('#ENQUIRIES').modal('hide');
							dataTableObj[0].ajax.url(base_url+'admin_setting/access_help_enquiry/2').clear().draw();
							Custom_notify('success',resp['message']);;
						}else{
							Custom_notify('error',resp['message']);;
						}
					})
		}
	})
	$(document).on('submit','#SaveFAQ',function(e){
		e.preventDefault();
		var _this = $(this);
		var checkValid = checkRequire(_this);
		if(checkValid == 0){
			let formData 	= new FormData(_this[0]);
			manageMyAjaxPostRequestData(formData , base_url + 'admin_setting/add_new_faq').done(function(resp){
				resp = JSON.parse(resp);
				if(resp.status == 1){
					$("input[type='text']").val('');
					$("input[type='hidden']").val('');
					$('#HELP_FAQ').modal('hide');
					dataTableObj[1].ajax.url(base_url+'admin_setting/access_help_enquiry/1').clear().draw();
					Custom_notify('success',resp['message']);;
				}else{
					Custom_notify('error',resp['message']);;
				}
			})
		}
	})
	
	$(document).on('click' , 'a[data-delete-id]' , function(e){
		e.preventDefault();		
		var _this 		= 	$(this);
		let formData 	= 	new FormData();
		var url 		=  _this.attr('data-action-url');
		var id 			=  _this.attr('data-delete-id');
		var field 		=  _this.attr('data-field');
			
			formData.append('id', id);
			formData.append('field', field);
			 
			var swals = {
						  title: "Are you sure want to delete this ?",
						  text: 'If you delete, all the details related with this, will be delete.' ,
						  icon: 'warning',
						  buttons: true,
						  dangerMode: true ,
						};
			
			if(url.search("site_main_data") != -1)
			swals.text = 'If there are slider with the same title in other mode,will be delete';
			
			
			swal(swals)
			.then((done) => {
			  if (done) {
				manageMyAjaxPostRequestData(formData,base_url+url ).done(function (resp) {
						if (resp['status'] == 1) {
							Custom_notify('success',resp['message']);;
							if(_this.parents('table.display').attr('data-refresh-dataTablePosition')){
								dataTableObj[_this.parents('table.display').attr('data-refresh-dataTablePosition')].ajax.url(base_url+_this.parents('table.display').attr('data-action-url')).clear().draw();	
							}
						}else{
							Custom_notify('error',resp['message']);;
						}
				});	
				} 
			});
	});
	
	$(document).on('click','.getEnqury',function(e){
		e.preventDefault();	
		var _this 		= 	$(this);
		if(_this.attr('data-id') != 'undefined' && _this.attr('data-id') != null && _this.attr('data-id') != ''){
			var id 			=	_this.attr('data-id');
			var url			=	"admin/getEnqury/"+id;
			$.get(base_url+url , function(data, status){
				data = JSON.parse(data);
				if(data.status == 1){
					data = data.data;
					console.log(data.data);
					CKEDITOR.instances.description.setData(JSON.parse(data.description) );
					$('#title').val(data.title);
					$('#subject').val(data.subject);
					$('#faq_id').val(data.faq_id);
					$('#show_status').val(data.show_status);
				}else{
					
					Custom_notify('error',data.message);
					
				}
			});
		}else{
			$("input[type='text']").val('');
			$("input[type='hidden']").val('');
			$("#icon_image").val('');
			CKEDITOR.instances.description.setData('');
		}
		$('#ENQUIRIES').modal('show');
	})

	
	$(document).on('click','.getHelp',function(e){
		e.preventDefault();
		
		var _this 		= 	$(this);
		if(_this.attr('data-id') != 'undefined' && _this.attr('data-id') != null && _this.attr('data-id') != ''){
			var id 			=	_this.attr('data-id');
			var url			=	"admin/getEnqury/"+id;
			$.get(base_url+url , function(data, status){
				data = JSON.parse(data);
				if(data.status == 1){
					data = data.data;
					console.log(data.description);
					$('#_faq_id').val(data.faq_id);
					$('#_subject').val(data.subject);
					$('#_description').text(data.description);
					$('#_show_status').val(data.show_status);
				}else{
					
					Custom_notify('error',data.message);
				}
			});
		}else{
			$("input[type='text']").val('');
			$("input[type='hidden']").val('');
			$('#_description').text('');
			$("#icon_image").val('');
		}
		$('#HELP_FAQ').modal('show');	
	})
	
	

	if($('#titleTokenfield').length){
		$('#titleTokenfield').tokenfield({
			autocomplete: {
			  source: function (request, response) {
				  if((request.term).length < 2){
					    jQuery.get(base_url+"admin/getSliderTitle", {
							  query: request.term
						  }, function (data) {
							  data = $.parseJSON(data);
							  response(data);
						  });
				  }
				
			  },
			  delay: 100,
			 
			},
			showAutocompleteOnFocus: false,
			 limit: 1
		
		  });
	}
	
	//This is start of homepage slider for post ids
	$('#selected_video').change(function(){
		if($(this).is(':checked')){
			$(this).val('1');
		}else{
			$(this).val('0');
		}
	});

	var posts_ids = [];
	$(document).on('click , change','.SelectPostIds',function(){
		let _this 	=	$(this);
		let _val	=	_this.val();
		
		if (_this.is(":checked")) {
			let a = posts_ids.indexOf(_val);
			if(a == -1){ 
				posts_ids.push(_val);
			}
			
		}else{
			let position = posts_ids.indexOf(_val);
			if (position > -1) 
			posts_ids.splice(position, 1);
		
		}
		$('#post_ids').val(posts_ids);
		
	})

	function appendCheckBoxValue(){
		$('.checkAll').prop("checked", false);
		for(let i=0; i < posts_ids.length; i++){
			$('#SelectPostIds'+posts_ids[i]).prop('checked', true);
		}
	}

	if($('.updatePostId').length){
		setTimeout(function(){
			let id = $('#post_ids').val();
			posts_ids = id.split(","); 
			
			appendCheckBoxValue()
		},3000)
	}
	//This is end of homepage slider  for post ids
	
	//This is start of homepage slider for order of post ids 
	$('#selected_order_video').change(function(){
		if($(this).is(':checked')){
			$(this).val('1');
		}else{
			$(this).val('0');
		}
	});

	var order_post_ids = [];
	$(document).on('click , change','.SelectOrderPostIds',function(){
		let _this 	=	$(this);
		let _val	=	_this.val();
		
		if (_this.is(":checked")) {
			let a = order_post_ids.indexOf(_val);
			if(a == -1){ 
				order_post_ids.push(_val);
			}
			
		}else{
			let position = order_post_ids.indexOf(_val);
			if (position > -1) 
			order_post_ids.splice(position, 1);
		
		}
		$('#order_post_ids').val(order_post_ids);
		
	})

	function appendCheckBoxOrderValue(){
		
		for(let i= 0; i < order_post_ids.length; i++){
			console.log('appendCheckBoxOrderValue','#SelectOrderPostIds'+order_post_ids[i]);
			$('#SelectOrderPostIds'+order_post_ids[i]).prop('checked', true);
		}
	}

	if($('.updatePostId').length){
		setTimeout(function(){
			let id = $('#order_post_ids').val();
			order_post_ids = id.split(","); 
			
			appendCheckBoxOrderValue()
		},3000)
	}
	//This is end of homepage slider  for order of post ids

	$(document).on('submit','#send_payout',function(e){
		e.preventDefault();
		var _this = $(this);
		
		var swals = {
					  title: "Are you sure you want to Send this Payments ?",
					  text: '' ,
					  icon: 'warning',
					  buttons: true,
					  dangerMode: true ,
					};
			
			swal(swals)
			.then((done) => {
				if (done) {
					let formData 	= new FormData(_this[0]);
					manageMyAjaxPostRequestData(formData,base_url+ _this.attr('action') ).done(function (resp){
						if (resp['status'] == 1) {
							 let payment_mode = parseInt($('[name="payment_mode"]').val());
							 if(payment_mode == 1){ /* ACH */
								let txnid = resp['data'];
								ProcessFile(txnid)
								
							 }else{ /* PayPal */
								 Custom_notify('success',resp['message']);
								 dataTableObj[1].ajax.url(base_url+'admin_finance/access_payouts_report').clear().draw();
								 dataTableObj[2].ajax.url(base_url+'admin_finance/access_payment_history').clear().draw();
							 }
							 	
						}else{
							Custom_notify('error',resp['message']);;
						}
					});	
				} 
			});  
	})
		
	$(document).on('click' , 'a[data-batch]' , function(){
		let _this = $(this);
			_this.html('<i class="fa fa-refresh fa-spin" style="font-size:24px"></i>');
		let formData = new FormData();
		
		manageMyAjaxPostRequestData(formData, _this.attr('data-batch') ).done(function(resp){
			_this.html('<i class="fa fa-refresh fa-spin" style="font-size:24px"></i>');
			dataTableObj[1].ajax.url(base_url+'admin_finance/access_payment_history').clear().draw();
			dataTableObj[2].ajax.url(base_url+'admin_finance/access_payment_batches').clear().draw();
			dataTableObj[3].ajax.url(base_url+'admin_finance/access_payment_statement').clear().draw();
			if (resp['status'] == 1) {
				Custom_notify('success',resp['message']);;
			}else{
				Custom_notify('error',resp['message']);;
			}
		}).then(function(){
			_this.html('<i class="fa fa-refresh fa-spin" style="font-size:24px"></i>');
		}); 
	});	
	
	$(document).on('click' , '.create_preview' , function(){
		let _this 		= $(this);
		let video 		= _this.prev('video')[0];
		let formData 	= new FormData();
		
		formData.set('startTime',player.currentTime());
		formData.set('TotalTime',player.duration()); 
		formData.set('url',player.src())
		
		manageMyAjaxPostRequestData(formData,base_url+ 'admin_setting/create_vid_preview/').done(function (resp){
			if (resp['status'] == 1) {
				Custom_notify('success',resp['message']);;
			}else{
				Custom_notify('error',resp['message']);;
			}
		})
		
	})
	
	$(document).on('click' , '.LoginMe' , function(){
		let _this = $(this);
		let formData = new FormData();
			formData.set('uid',_this.data('uid'))

			manageMyAjaxPostRequestData(formData,base_url+ 'home/AdminLoginMe').done(function (resp){
				if (resp) {
					/* window.location = 	base_url+"profile?user="+resp; */
					window.open(base_url+"profile?user="+resp, '_blank');
				}
			})
		
	})
	
	$(document).on('click', '#filter_profanity_words', function(){
		var $first_th = $('table').find('tr').find('th:nth-child(1)');
		var $first_td = $('table').find('tr').find('td:nth-child(1)');
		$first_th.hide();
		$first_td.hide();
		get_contents();
	})

	function get_contents(){
		let tbl = $('.dataTableAjax');
		if($('#filter_profanity_words').is(':checked')){
			$(tbl).attr('data-filter', '1');
		}else{
			$(tbl).removeAttr('data-filter');
		}
		manage_dataTable(tbl);
	}
	

	$(document).on('click', '#approveAll', function(e){
		let ids = '';
		$('.selectVideoIds').each(function(){
			if ($(this).is(':checked')) {
				ids += $(this).val() + ',';
			}
		});
		if (ids.length > 0) {
			var swals = {
				title: "Are you sure ?",
				text: 'You want to approve all selected videos' ,
				icon: 'success',
				buttons: true,
				dangerMode: false ,
			};
	
			swal(swals)
			.then((done) => {
				if (done) {
					
					
						let formData = {'ids': ids};
						let url = 'admin/Approve_all_selected';
						manageMyAjaxPostRequestData(formData, base_url + url ).done(function (resp) {
							
								if (resp['status'] == 1) {
									Custom_notify('success','Success');
									get_contents();
								}else{
									Custom_notify('error','Something went wrong, please try again.');
								}
							
						});
					
				} 
			});  
		}else{
			Custom_notify('error','Please Select Videos to Approve!');
		}
	});

	
});

/*******document ready function closed here*******/
	function ProcessFile(txnid){
		swal("Are you sure you want to process or remove uploaded file ?", {
		  buttons: {
			cancel	:  "Remove File !",
			catch	:  { text: "Process File !", value: "catch", },
			
		  },
		  closeOnClickOutside :false,
		})
		.then((value) => {
			let url = (value == 'catch')? 'ProcessUploadedFile' : 'RemoveUploadedFile';
			let formData 	= new FormData();
			formData.append('fileId',txnid)
			formData.append('url',url)
			formData.append('level',2)
			manageMyAjaxPostRequestData(formData,base_url+ 'admin/AchPaymentProcess').done(function (resp){
				if (resp['status'] == 1) {
					ConfirmProcessingFile(txnid)
				}else{
					Custom_notify('error',resp['message']);;
				}
			});
		 });
	}	
	function ConfirmProcessingFile(txnid){
		swal("Are you sure you want to confirm processing uploaded file ?", {
		  buttons: {
			cancel	:  "Cancel Processing !",
			catch	:  { text: "Confirm Processing !", value: "catch", },
		  },
		  closeOnClickOutside :false,
		})
		.then((value) => {
			let url = (value == 'catch')? 'ConfirmFileProcessing' : 'CancelProcessingOfUploadedFile';
			let formData 	= new FormData();
			formData.append('fileId',txnid)
			formData.append('url',url)
			formData.append('level',3)
			manageMyAjaxPostRequestData(formData,base_url+ 'admin/AchPaymentProcess').done(function (resp){
				if (resp['status'] == 1) {
					if(value == 'catch'){
						GetConfirmationOnUploadedFile(txnid)
					}else{
						swal("You have canceled the processing ! ");
					}
				}else{
					Custom_notify('error',resp['message']);;
				}
			});
		 });
	}

	function GetConfirmationOnUploadedFile(txnid){
		swal("Are you sure you want to get confirmation on uploaded file ?", {
		  buttons: {
			cancel	:  "Cancel  !",
			catch	:  { text: "Confirm  !", value: "catch", },
		  },
		  closeOnClickOutside :false,
		})
		.then((value) => {
			if(value == 'catch'){
				let formData = new FormData();
					formData.set('fileId',txnid)
					formData.set('url','GetConfirmationOnProcessedFile')
					formData.set('level',4)
					manageMyAjaxPostRequestData(formData,base_url+ 'admin/AchPaymentProcess/').done(function (resp){
						if (resp['status'] == 1) {
							let data = resp['data']['NewDataSet'];
								data = data.FileDetails;
								
								swal("Gotcha!", "You have done the payment!", "success");
							
							let html = '<table class="table table-bordered">';
							let count = data.length;
							
							$.each(data, function (key, val) {
								html += '<tr><td><b>'+val.FieldType +'</b></td> <td> '+val.FieldValue+'</td></tr>';
								count--;
							});
							
							if(count == 0){
								html += '</table>';
							}
							$('.swal-text').html(html);
							
							setTimeout(function(){
								location.reload();
							},4000);
						}else{
							Custom_notify('error',resp['message']);;
						}
					});
			}
		});
	}
	
	/************Streaming request************/
	$(document).on('change','.DisableRequest',function(){
		let _this = $(this);
		let v = _this.val();
		if (v == 0) {
			let id = _this.data('check-id');
			swal({
				text: 'Enter the rejection reason',
				content: "input",
				 button: {
					text: "Save",
					closeModal: true,
				  },
			}).then((inputValue) => {
				let f = new FormData();
					f.append('id',id);
					f.append('reason',inputValue);
					if(inputValue.length){
						manageMyAjaxPostRequestData(f,base_url+'admin/AddDisapproveReason').done(function(resp){
							if (resp['status'] == 1){
								swal("Done",resp['message'], "success");
							}
						})
					}
			});
		}
	})
	
	
	
	$(document).on('change','[data-statement="MyallStatement"]',function(){
		let _this 	= $(this);
		let _clas  	= _this.attr('class');
		let _uid  	= _this.data('uid');
		let total 	= 0; 
		
		$('.'+_clas).each(function(){
			if($(this).is(":checked"))
			total += parseFloat( $(this).data('amount'));
		})
		
		$('#checkUser_'+_uid).prop('checked',(total > 0)?true:false );
		$('#Stetement_total_'+_uid).html('<input class="form-control" readonly type="text" value="'+total+'" name="statement_total[]">');
	})

	
	$(document).on('submit','#send_payout_statement',function(e){
		e.preventDefault();
		var _this = $(this);
		
		var swals = {
					  title: "Are you sure you want to Send this Payments ?",
					  text: '' ,
					  icon: 'warning',
					  buttons: true,
					  dangerMode: true ,
					};
			
			swal(swals)
			.then((done) => {
				if (done) {
					let formData 	= new FormData(_this[0]);
					manageMyAjaxPostRequestData(formData,base_url+ _this.attr('action') ).done(function (resp){
						if (resp['status'] == 1) {
							 let payment_mode = $('[name="payment_mode"]').val();
							 if(parseInt(payment_mode) == 1){ /* ACH */
								let txnid = resp['data'];
								ProcessFileStatement(txnid)
								
							 }else{ /* PayPal */
								 Custom_notify('success',resp['message']);
								 dataTableObj[1].ajax.url(base_url+'admin_finance/access_payouts_report').clear().draw();
								 dataTableObj[2].ajax.url(base_url+'admin_finance/access_payment_history').clear().draw();
							 }
							 	
						}else{
							Custom_notify('error',resp['message']);;
						}
					});	
				} 
			});
	})

	function ProcessFileStatement(txnid){
		swal("Are you sure you want to process or remove uploaded file ?", {
		  buttons: {
			cancel	:  "Remove File !",
			catch	:  { text: "Process File !", value: "catch", },
			
		  },
		  closeOnClickOutside :false,
		})
		.then((value) => {
			let url = (value == 'catch')? 'ProcessUploadedFile' : 'RemoveUploadedFile';
			let formData 	= new FormData();
			formData.append('fileId',txnid)
			formData.append('url',url)
			formData.append('level',2)
			manageMyAjaxPostRequestData(formData,base_url+ 'admin/AchPaymentProcessStatement').done(function (resp){
				if (resp['status'] == 1) {
					ConfirmProcessingFileStatement(txnid)
				}else{
					Custom_notify('error',resp['message']);;
				}
			});
		 });
	}	 
	function ConfirmProcessingFileStatement(txnid){
		swal("Are you sure you want to confirm processing uploaded file ?", {
		  buttons: {
			cancel	:  "Cancel Processing !",
			catch	:  { text: "Confirm Processing !", value: "catch", },
		  },
		  closeOnClickOutside :false,
		})
		.then((value) => {
			let url = (value == 'catch')? 'ConfirmFileProcessing' : 'CancelProcessingOfUploadedFile';
			let formData 	= new FormData();
			formData.append('fileId',txnid)
			formData.append('url',url)
			formData.append('level',3)
			manageMyAjaxPostRequestData(formData,base_url+ 'admin/AchPaymentProcessStatement').done(function (resp){
				if (resp['status'] == 1) {
					if(value == 'catch'){
						GetConfirmationOnUploadedFileStatement(txnid)
					}else{
						swal("You have canceled the processing ! ");
					}
				}else{
					Custom_notify('error',resp['message']);;
				}
			});
		 });
	}

	function GetConfirmationOnUploadedFileStatement(txnid){
		swal("Are you sure you want to get confirmation on uploaded file ?", {
		  buttons: {
			cancel	:  "Cancel  !",
			catch	:  { text: "Confirm  !", value: "catch", },
		  },
		  closeOnClickOutside :false,
		})
		.then((value) => {
			if(value == 'catch'){
				let formData = new FormData();
					formData.set('fileId',txnid)
					formData.set('url','GetConfirmationOnProcessedFile')
					formData.set('level',4)
					manageMyAjaxPostRequestData(formData,base_url+ 'admin/AchPaymentProcessStatement/').done(function (resp){
						if (resp['status'] == 1) {
							let data = resp['data']['NewDataSet'];
								data = data.FileDetails;
								
								swal("Gotcha!", "You have done the payment!", "success");
							
							let html = '<table class="table table-bordered">';
							let count = data.length;
							
							$.each(data, function (key, val) {
								html += '<tr><td><b>'+val.FieldType +'</b></td> <td> '+val.FieldValue+'</td></tr>';
								count--;
							});
							
							if(count == 0){
								html += '</table>';
							}
							$('.swal-text').html(html);
							
							setTimeout(function(){
								location.reload();
							},4000);
						}else{
							Custom_notify('error',resp['message']);
						}
					});
			}
		});
	}
	$('#PreviewTitle').parents('.box-body').hide();
	$(document).on('click','.LinkNpreview',function(){
		var _this = $(this);
		$('#PreviewTitle').parents('.box-body').show();
		let formData = new FormData();
		formData.append('mode',$('.website_mode').val());	
		formData.append('post_id',_this.attr('data-post_id'));	
		formData.append('id',_this.attr('data-user_id'));	
		$('#cover_post_id').val(_this.attr('data-post_id'));
		
		manageMyAjaxPostRequestData(formData, base_url + 'admin_setting/getCoverVideoLink' ).done(function (resp) {
			if (resp['status'] == 1) {
				
				$('#link').val(resp['data']['link']);
				$('#btn_name').val(resp['data']['btn']);
				$('#subtitle').val(resp['data']['subtitle']);
			
			}else{
				Custom_notify('error','Something went wrong, please try again.');
			}
		})
		manageMyAjaxPostRequestData(formData, base_url + 'admin_setting/getClientVideos' ).done(function (resp) {
				if(resp.length){
				var resp = JSON.parse(resp);
				
				if (resp['status'] == 1) {
					let dat 	= resp['data'] ;
					let v 		= dat[0].video; 
					let mime 	= ( v.search("m3u8") > -1 ) ? 'application/x-mpegURL' : 'video/mp4';
					
					// $('#vidSrc').attr('src',dat[0].video);
					$('#PreviewTitle').text(dat[0].name);
					player.src({
						type: mime , src: v 
					});
				}else{
					Custom_notify('error','Something went wrong, please try again.');
				}
			}
		});
	})
	$(document).on('click','.RemoveCoverVideoLink',function(){
		let formData = new FormData();
		formData.append('mode',$('.website_mode').val());	
		formData.append('link',$('#link').val());	
		formData.append('btn',$('#btn_name').val());	
		formData.append('post_id',$('#cover_post_id').val());	
		manageMyAjaxPostRequestData(formData, base_url + 'admin_setting/RemoveCoverVideoLink' ).done(function (resp) {
		
			console.log(resp)
			if (resp['status'] == 1) { 
				$('#link').val('');
				$('#btn_name').val('');
				Custom_notify('success',resp.message);
			}else{
				Custom_notify('error','Something went wrong, please try again.');
			}
		});
	})
	$(document).on('click','.AddCoverVideoLink',function(){
		let formData = new FormData();
		formData.append('mode',$('.website_mode').val());	
		formData.append('link',$('#link').val());	
		formData.append('btn',$('#btn_name').val());	
		formData.append('subtitle',$('#subtitle').val());	
		formData.append('post_id',$('#cover_post_id').val());	
		manageMyAjaxPostRequestData(formData, base_url + 'admin_setting/AddCoverVideoLink' ).done(function (resp) {
			// var resp = JSON.parse(resp);
			console.log(resp)
			if (resp['status'] == 1) { 
				Custom_notify('success',resp.message);
			}else{
				Custom_notify('error','Something went wrong, please try again.');
			}
		});
	})
	$('[data-uvid-new]').change(function(){
		
		var ths = $(this);
		if(ths.val()){
			let formData = new FormData();
			
			var post = ths.parents().parents().find(".dis_setting_checkbox");
			var website_mode=$('.website_mode').val();
			if(website_mode==''){
				Custom_notify('error','Please Select mode first');
				return false;
			}
			formData.append('mode',website_mode);	
			if(post.val()){
				formData.append('id', post.val());
			}
			
			if(post.data('action-url')){
				var url = post.data('action-url');
			}
			console.log(ths.parents(),post.val(),post.data('action-url'));
			//return false;
			formData.append('post_id', ths.val());
			
			manageMyAjaxPostRequestData(formData, base_url + url ).done(function (resp) {
					if(resp.length){
					var resp = JSON.parse(resp);
					console.log(resp);
					if (resp['status'] == 1) {
						var dat = resp['data'] ;
						var srcs = dat[0].video;
						console.log(srcs);
						ths.parents('.row').find('video').attr('src',srcs);
					}else{
						Custom_notify('error','Something went wrong, please try again.');
					}
				}
			});
		}
	})	

	$('.website_mode').change(function(){
		$(".dis_setting_checkbox").attr("data-mode", $(this).val());
	})	

	$(document).on('change', '.previewFile', function () {
        let _this = $(this);
        let file = _this[0].files[0];
    
        let reader = new FileReader();
        reader.addEventListener("load", function () {
            $(_this.data('id')).attr('src', reader.result);
            $(_this.data('id')).css('background-image', 'url(' + reader.result + ')');
        }, false);
    
        if (file) {
            reader.readAsDataURL(file);
        }
        
        if(_this.data('xhr')){
            let f = new FormData();
            f.set('ufile', file);
            f.set('target', _this.data('target'));
            f.set(csrf_name, csrf_hash);
            manageMyAjaxPostRequestData(f, base_url+_this.data('url')).done(function (data) {
                if (data.status == 1) {
                    showNotifications("success", data.message);
                }
            })
        }
        
    });

	$(document).on('click' , 'a[data-clone-id]' , function(e){
		e.preventDefault();
		var _this               =       $(this);
		let formData    		=       new FormData();
		var url                 =  		_this.attr('data-action-url');
		var id                  =  		_this.attr('data-clone-id');

		formData.append('id', id);
		
		var swals = {
			title: "Are you sure want to copy this slider to spotlight mode ?",
			text: 'If you copy, all the details related with this, will be copy.' ,
			icon: 'warning',
			buttons: true,
			dangerMode: true ,
		};

		swal(swals).then((done) => {
			if (done) {
				manageMyAjaxPostRequestData(formData,base_url+url ).done(function (resp) {
					if (resp['status'] == 1) {
							Custom_notify('success',resp['message']);;
							if(_this.parents('table.display').attr('data-refresh-dataTablePosition')){
								dataTableObj[0].ajax.reload();
							}
					}else{
							Custom_notify('error',resp['message']);;
					}
				});     
			} 
		});
	});

	// on change show hide articles category dropdown on homepage sliders page
	$(document).on('change','#select_mode',function(){
		if($(this).val() == 10){
			$('#select_category').closest('.form-group').removeClass('hide');
		}else{
			$('#select_category').closest('.form-group').addClass('hide');
			if($('#select_category').val() != 0){
				$('#select_category').val(0).trigger('change');
			}
		}
	});
		


})(jQuery);
	
	function checkAll(ele,clas) {
		var checkboxes = $('.'+clas);
		if (ele.checked) {
			for (var i = 0; i < checkboxes.length; i++){
				if (checkboxes[i].type == 'checkbox'){
					checkboxes.eq(i).prop("checked",true).trigger("change");
				}
			}
		}else{
			for (var i = 0; i < checkboxes.length; i++){
				if (checkboxes[i].type == 'checkbox'){
					checkboxes.eq(i).prop("checked",false).trigger("change");
				}
			}
		}
	}




