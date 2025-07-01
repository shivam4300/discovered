/*
Copyright (c) 2019 
discovered backed
------------------------------------------------------------------


------------------------------------------------------------------*/

(function ($) {
	"use strict";
	var discovered = {
		initialised: false,
		version: 1.0,
		mobile: false,
		init: function () {

			if(!this.initialised) {
				this.initialised = true;
			} else {
				return;
			}

			/*-------------- Discovered Functions Calling ---------------------------------------------------
			------------------------------------------------------------------------------------------------*/
		this.tooltip();	
		this.pay_check_active();	
		this.toggle_menu();	
		this.data_table();	
		this.profile_toggle();	
		// this.nioti_toggle();	
		this.action_toggle();	
		this.sidebar_active();	
		this.select_box();	
		this.select_box_2();	
		this.checkbox_active();	
		this.range_picker();	
		this.range2_picker();	
		this.donut_chart();	
		this.line_chart();		
		// this.cricular_progbar();	
		// this.cricular_progbardot();
		this.commonPopup();	
		this.commondropdown();	
		this.menu_toggle();	
		this.checkselect_box();	
			
		},
		
		/*-------------- Discovered Functions Calling ---------------------------------------------------
		---------------------------------------------------------------------------------------------------*/
		// tooltip  
		commondropdown: function() {
			if($('.dash_cmn_dd_wrap').length > 0){
				// $(document).on("click", ".dash_cmn_dd_btn", function(e){
				// 	e.stopPropagation();
				// 	$(this).parent().toggleClass('open');					
				// });
				// $('.dash_min_dd_body').on('click', function(e){ 
				// 	e.stopPropagation();
				// });
				// $('body').on('click', function(){ 
				// 	$('.dash_cmn_dd_wrap').removeClass('open');
				// });
				const $menu = $('.dash_cmn_dd_wrap');
				$(document).mouseup(e => {
				if (!$menu.is(e.target) // if the target of the click isn't the container...
				&& $menu.has(e.target).length === 0) // ... nor a descendant of the container
				{
					$menu.removeClass('open');
				}
				});
				$('.dash_cmn_dd_btn').on('click', () => {
				$menu.toggleClass('open');
				});
			}
		},	
		
		/*------------------------------------------------------------------*/ 
		// tooltip  
		tooltip: function() {
			if($('.cstm_tooltip').length > 0){
				setTimeout(function(){$('[data-toggle="tooltip"]').tooltip();},100);
			}	
		},	
		/*------------------------------------------------------------------*/ 
		
		commonPopup : function(){
			
			$("body").on('click', '.common_click', function(){
			 // $(".dis_common_popup").toggleClass("open_commonpopup");
				$("body").addClass("common_popup_bg");
			});
		
			$(".common_close").on('click', function(){
				  $(this).closest(".dis_common_popup").removeClass("open_commonpopup");
				  $("body").removeClass("common_popup_bg");
			 });
		},
		
		
		/*------------------------------------------------------------------*/ 
		
		
	pay_check_active: function() {
		if($('.paymrnt_btn_wrappr').length > 0){
			$('.paymrnt_btn_wrappr li').on('click', function(){
				if($(".paymrnt_btn_wrappr li input[type='radio']").is(':checked')){
				  $('.paymrnt_btn_wrappr li').removeClass('active');
				  $(this).addClass('active');
				 
				  if( $(this).find('.hide_checkbox').attr('id') == 'ach_checkbox'){
					  $('#ach_checkbox_toggle').removeClass('hide');
					  $('#pal_checkbox_toggle').addClass('hide');
					  $('.ach').addClass('require');
					  $('.ppal').removeClass('require');
				  }else{
					  $('#ach_checkbox_toggle').addClass('hide')
					  $('#pal_checkbox_toggle').removeClass('hide')
					   $('.ach').removeClass('require');
					   $('.ppal').addClass('require');
				  }
				  
				}
			});
		}
	},
	
	// MObile toggle
	toggle_menu: function() {
		if($('.toggle_click').length > 0){
			$('.toggle_click').on('click', function(){
				$('body').toggleClass('open_tgl');
				
			});
			
		}
	},
	/*------------------------------------------------------------------*/ 
	
	// DATA TABLE
	data_table: function() {
		// if($('#example').length > 0){
			// $('#example').DataTable();
		// }
	},
	/*------------------------------------------------------------------*/ 
	// profile toggle
		profile_toggle: function() {
			if($('.top_information').length > 0){	
				$('.header_profile').on('click',function(e){
					$(this).parent().toggleClass("open");
					e.stopPropagation(e);
				});
				// $('.profile_dropdown').on('click', function(e){ 
					// e.stopPropagation();
				// });
				$('body').on('click', function(){ 
					$('.top_information ul li').removeClass('open');
				}); 
		}
	},	
		
	/*------------------------------------------------------------------*/	

	// notification toggle
	// 	nioti_toggle: function() {
	// 		if($('.top_information').length > 0){	
	// 			$('.noti_icon').on('click',function(e){
	// 				$(this).parent().toggleClass("open");
	// 				e.stopPropagation(e);
	// 			});
	// 			$('.head_noti_inner').on('click', function(e){ 
	// 				e.stopPropagation();
	// 			});
	// 			$('body').on('click', function(){ 
	// 				$('.header_notification').removeClass('open');
	// 			}); 
	// 	}
	// },	
		
	
	
	/*------------------------------------------------------------------*/ 
	// data table action toggle
		action_toggle: function() {
			if($('.table_actionboxs').length > 0){	
					$(document).on('click','.table_actionboxs',function(){
						$(this).addClass("open");
					});
					$('.action_drop').on('click', function(e){ 
						e.stopPropagation();
					});
					$('body').on('click', function(){ 
						$('.table_actionboxs').removeClass('open');
					});
					$(document).on('click','.table_actionboxs',function(e){
						e.stopPropagation();
					});
			}
	},	
	/*------------------------------------------------------------------*/

	// sidebar active class
	sidebar_active: function() {
		// if($('.sidebar_main_menu > li > a').length > 0){	
		// 	$('.sidebar_main_menu > li > a').on('click' , function(e){
		// 		$('.sidebar_main_menu > li > a').removeClass('active');
		// 		$(this).addClass('active');
		// 	});
		// }
	},
	
	/*------------------------------------------------------------------*/ 
	/* select2*/
	select_box: function() {
		if($('.dash_select_box').length > 0){
			$('.dash_select_box').each(function(){ 
				$(this).select2({
					placeholder: $(this).attr('data-placeholder'),
					width: '100%',
				});
			});
		}
	},
	/* select2*/
	checkselect_box: function() {
		if($('.multi-checkbox select').length > 0){
			$('.multi-checkbox select').each(function(){ 
				$(this).select2({
					placeholder: $(this).attr('data-placeholder'),
					width: '100%',
					closeOnSelect: false,
					dropdownParent:  $(this).parent(),
				});
			});
		}
	},
	
	/* selectbox without search */ 
	select_box_2: function() {
		if($('.dash_selectbox_without_search').length > 0){
			$('.dash_selectbox_without_search').each(function(){  
				$(this).select2({
					placeholder: $(this).attr('data-placeholder'),
					width: '100%',
					minimumResultsForSearch: -1,
				});
			});
		}
	},
	
	/*------------------------------------------------------------------*/ 
	// if check box is check add class in li
	checkbox_active: function() {
		if($('.tbl_checkbox').length > 0){
				$('.tbl_checkbox input[type="checkbox"]').on('click', function(){
					console.log('asdasda');
					if($(this).prop("checked") == true){
						$(this).parents('.table_content tr').addClass('active');
					}
					else {
						$(this).parents('.table_content tr').removeClass('active');
					}
				});
		}
	},
	/*------------------------------------------------------------------*/
	range_picker: function() {
		/*if($('.rangepicker').length > 0){
			$('.rangepicker').daterangepicker({
			timePicker: false,
			opens: 'center',
			startDate: moment().startOf('hour'),
			endDate: moment().startOf('hour').add(32, 'hour'),
			locale: {
			  format: 'DD-MM-YYYY'
			}
		  });
		}
		$('input.rangepicker').val('Date'); */
	},
	/*------------------------------------------------------------------*/ 
	range2_picker: function() {
		
	},
	/*------------------------------------------------------------------*/ 
	donut_chart: function() {
		
	},
	/*------------------------------------------------------------------*/ 
	line_chart: function() {
		
	},
	/*------------------------------------------------------------------*/ 

	/*------------------------------------------------------------------*/ 
	cricular_progbar: function() {
		if($('.my-progress-bar').length > 0){
				$(".my-progress-bar").circularProgress({
					line_width: 6,
					color: "#baa5ff",
					starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
					percent: 0, // percent starts from
					percentage: true,
				}).circularProgress('animate', 80, 2000);
		}
	},
	/*------------------------------------------------------------------*/ 
	
	/*------------------------------------------------------------------*/ 
	cricular_progbardot: function() {
		/*if($('.circlePercent').length > 0){
			function setProgress(elem, percent) {
				var
				degrees = percent * 3.6,
				transform = /MSIE 9/.test(navigator.userAgent) ? 'msTransform' : 'transform';
				elem.querySelector('.counter').setAttribute('data-percent', Math.round(percent));
				elem.querySelector('.progressEnd').style[transform] = 'rotate(' + degrees + 'deg)';
				elem.querySelector('.progress').style[transform] = 'rotate(' + degrees + 'deg)';
				if (percent >= 50 && !/(^|\s)fiftyPlus(\s|$)/.test(elem.className))
				elem.className += ' fiftyPlus';
			
				var elem = document.querySelector('.circlePercent'),
				percent = 0,
				stopped = false,
				stopPercent = 59; //Enter variable name to make it dynamic
				(function animate() {
					setProgress(elem, (percent += .25));
					if (percent < 100 && !stopped)
					  setTimeout(animate, 20);
					if (percent == stopPercent) {
					  stopped = true;
					}
				})();
			
			}

			
		}*/
	},
	/*------------------------------------------------------------------*/ 
	// sub menu 2 start
	menu_toggle: function() {

			$('.sidebar_main_menu > li > a').click(function(e) {
			var dropDown = $(this).closest('.menu_item_has_children').find('.dis_sub_menu');
			$(this).closest('.menu_item_has_children').find('.dis_sub_menu').not(dropDown).slideUp();	
			if ($(this).hasClass('active')) {
				$(this).removeClass('active');
			} else {
				$(this).addClass('active');
				$('.ps-navbar-wrapper .menu > li > a').not(this).removeClass('active');
			}	
			dropDown.stop(false, true).slideToggle();
			})

	},
	// sub menu 2 end
	/*------------------------------------------------------------------*/ 
	
	};
	discovered.init();
	// website loader
	// $(window).on('load', function() {
	// 	$(".website_status").fadeOut(1000);
	// 	$(".website_preloader").delay(1000).fadeOut("slow");
	// });


	
	
	
	window.onload = function() {
	setTimeout(function()
	{
	document.body.style.opacity="100";
	},1000);
	};

}(jQuery));	





	



	
	// $(document).ready(function(){
	// $('#datetimepicker1').datetimepicker();
           
	// });

