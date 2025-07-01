var dataTableObj = [];
(function ($) {
	
	$(document).ready(function(){
			
		if($('[data-target="select2"]').length){
			$('[data-target="select2"]').each(function(){
				var options = eval('[' + $(this).attr('data-option') + ']');
				if ($.isPlainObject(options[0])) {
					options[0] = $.extend({}, options[0]);
				}
				console.log(options);
				$(this)[$(this).attr('data-target')].apply($(this), options);
				
			});
		}	
		$(document).on('change', '.SelectBySelect2' ,function(){
			var _this = $(this);
			
			var formData = new FormData();
				formData.append('id', _this.val() );	
				
				manageMyAjaxPostRequestData(formData , base_url +  _this.data('url')).done(function(resp){
					resp = $.parseJSON(resp);
					if(resp['status'] == 1){
						var data = [];
						$(_this.attr('data-id')).empty().trigger('change');
						data = [{id:'',text:''}];
						
						$.each(resp['data'], function (key, val) {
							 data.push({id: val.id ,text: val.name});
						});
						
						$(_this.attr('data-id')).select2(
								{	data: data,
									placeholder: _this.attr('data-placeholder'),	
									closeOnSelect: false
								}
						);
					}
				})
		});	
		
		/* initialize Server side datatable*/
		if($( ".display" ).length > 0){
			
			var dataTableCount = 0;
			setTimeout(function(){ 
				$('table.display').each(function(){
					dataTableObj[dataTableCount++] = manage_dataTable($(this));
				});
			 }, 1000);
		}
		

		function manage_dataTable(_this){
			
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
						if($('#checkall').prop("checked")){
							$('#checkall').trigger('click');
						}
						if(typeof _this.attr('data-filter') != 'undefined'){
							$('.filter').each(function(){
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
			tableOption["columnDefs"]= [{
				orderable: false,
				targets: 0
			}];
			return _this.DataTable(tableOption);
		}
		/* end Server side datatable*/
		
		
		$(document).on('change','[name="length"]',function(){
			 dataTableObj[0].page.len($(this).val()).draw();
		})
		
		$(document).on('change , keyup','.filter',function(){
			let table = $(this).parents('.table_area').find('table');
			dataTableObj[table.data('table-position')].ajax.url(base_url+table.data('action-url')).clear().draw();	
		})
		setTimeout(function(){$('.daterange').addClass('filter');},1000);
		
		if($('.daterange').length){
			setTimeout(function(){
			$('.daterange').daterangepicker({
				// startDate: moment().startOf('month'),
				// endDate: moment().endOf('month'),
				autoUpdateInput: false,
				locale: {
				  format: 'YYYY/MM/DD',
				  cancelLabel: 'Clear'
				 
				}
			},function(start, end) {});
			},1000);
			$('.daterange').attr("placeholder","Date");
		}
		
		
		
		$('.daterange').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
			let table = $(this).parents('.table_area').find('table');
			dataTableObj[table.data('table-position')].ajax.url(base_url+table.data('action-url')).clear().draw();	
		});
		
		$('.daterange').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
			let table = $(this).parents('.table_area').find('table');
			dataTableObj[table.data('table-position')].ajax.url(base_url+table.data('action-url')).clear().draw();	
		});
		
		$(document).on('click' ,'.status_toggle' , function(e){
			var _this = $(this);
			var formData = new FormData();
				formData.append('id',_this.data('id') );
				formData.append('status',(_this.is(':checked')) ? 1 : 0  );
				
				manageMyAjaxPostRequestData(formData , base_url+ $(this).data('url')).done(function(resp){
					resp = $.parseJSON(resp);
					if(resp['status'] == 1){
					let table = $(this).parents('.table_area').find('table');
					dataTableObj[table.data('table-position')].ajax.url(base_url+table.data('action-url')).clear().draw();	
					}
				})
		});
		
		$(document).on('change' ,'.ChangeOption' , function(e){
			var _this = $(this);
			var formData = new FormData();
				formData.append('id',_this.data('id'));
				formData.append('status',_this.val());
				
				manageMyAjaxPostRequestData(formData , base_url+ $(this).data('url')).done(function(resp){
					if(resp['status'] == 1){
					let table = _this.parents('.table_area').find('table');
					dataTableObj[table.data('table-position')].ajax.url(base_url+table.data('action-url')).clear().draw();	
					}
				})
		});
		
		
		if($('.print_pdf').length){
			let ht2cn = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js';
			let jspdf = '//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js';
			loadScript(ht2cn, function(){
				loadScript(jspdf); 
			});
			
			$(document).on('click' ,'.print_pdf' , function(e){
				let _this 	= $(this);
				let id 		= _this.data('id');
				let element = document.getElementById(id);
					html2canvas(element, {
						 onrendered: function (canvas) {
								var imgageData 	= canvas.toDataURL("image/jpg");
								var pdf 		= new jsPDF("p", "mm", "a4");
								var width 		= pdf.internal.pageSize.width;    
								var height 		= pdf.internal.pageSize.height;
									pdf.addImage(imgageData, 'jpg', 0, 0,width,height,"SLOW");
									pdf.save("statement.pdf");
	
							 }
						 });
			})
			
		}
		
		/**************************************check all option*************************************************************/
		$(document).on('click', '#checkall' , function() {
			
			if ($(this).prop("checked") && $('select[name="delete_status"]').val()=='0'){
				$(".checked_video").prop('checked', true);
				$("#delete_video_btn").removeClass('hide');
				$('.table_content tr').addClass('active');
			}else{
				$(".checked_video").prop("checked",false);
				$("#delete_video_btn").addClass('hide');
				$('.table_content tr').removeClass('active');
			}
		});
		
		$(document).on('click', '.checked_video' , function() {
		 
			if ($(this).prop("checked")){
				$(this).closest('.table_content tbody tr').addClass('active');
			}else{
				$(this).closest('.table_content tbody tr').removeClass('active');
			}
			
			if ($('.checked_video:checked').length && $('select[name="delete_status"]').val()=='0'){
				$("#delete_video_btn").removeClass('hide');
			}else{
				$("#delete_video_btn").addClass('hide');
			}
			
			if ($('.checked_video:checked').length == $('.checked_video').length){
				$('#checkall').prop('checked',true);
				$('#checkall').closest('.table_content tr').addClass('active');
			}else {
				$('#checkall').prop('checked',false);
				$('#checkall').closest('.table_content tr').removeClass('active');
			}
		});
		
		$(document).on('click', '#delete_video_btn' , function() {
			var r = confirm("Are you sure you want to delete this selected data?");
			if (r == true) {
				let _this 	= $(this);
				let type = _this.data('type');
				var id_Arr = $.map($('input[name="'+type+'"]:checked'), function(c){return c.value; });
				if(id_Arr.length>0){
					
					var formData = new FormData();
					formData.append(type,id_Arr);
					
					manageMyAjaxPostRequestData(formData , base_url+ _this.data('action-url')).done(function(resp){
						if(resp['status'] == 1){
							Custom_notify('success',resp.message);
							let table = _this.parents('.table_area').find('table');
							dataTableObj[table.data('table-position')].ajax.url(base_url+table.data('action-url')).clear().draw();	
						}
					})
					
				}
			} 
		});
		/**************************************check all option*************************************************************/
		

		function iframe(embd,isResponsive=false) {
			/********available also in main common.js*********/
			if(isResponsive){
				return (
					`&lt;div style=&quot;position:relative; width:100%; overflow:hidden; padding-top:56.25%;&quot&gt;&lt;iframe src="${embd}" frameborder="0" allow="autoplay" allowfullscreen="true"  style=&quot;position:absolute; top:0; left:0;bottom:0; right:0; width:100%; height:100%; border:none;&quot&gt;&lt;/iframe&gt;&lt;/div&gt;`
				)
			}else{
				return (
					`&lt;iframe src="` +
					embd +
					`" width="560" height="315" frameborder="0" allow="autoplay" allowfullscreen="true" &gt;&lt;/iframe&gt;`
				);
			}
		}
		
		function iframeJS(embd,isResponsive=false) {
			/********available also in main common.js*********/
			if(isResponsive){
				return (
					`<div id="iframe-container" style="position:relative; width:100%; overflow:hidden; padding-top:56.25%"></div>
					<script>
						var iframe = document.createElement('iframe');
						iframe.src = "${embd}";
						iframe.frameBorder = "0";
						iframe.allow = "autoplay";
						iframe.allowFullscreen = true;
						iframe.style = "position:absolute; top:0; left:0;bottom:0; right:0; width:100%; height:100%; border:none;"
						document.getElementById('iframe-container').appendChild(iframe)
					</script>`
				)
			}else{
				return (
					`<div id="iframe-container"></div>
					<script>
						var iframe = document.createElement('iframe');
						iframe.src = "${embd}";
						iframe.frameBorder = "0";
						iframe.width = "560";
						iframe.height = "315";
						iframe.allow = "autoplay";
						iframe.allowFullscreen = true;
						document.getElementById('iframe-container').appendChild(iframe)
					</script>`
				);
			}
		}

		window.ImageOnLoadError = function(_this,src1,src2){
			_this.src = src1;
			_this.onload = function() {
				_this.onerror=null;
			};
			_this.onerror = function(){
				_this.src = src2;
				_this.onerror=null;
			};
		}
	});
}(jQuery));	

	

