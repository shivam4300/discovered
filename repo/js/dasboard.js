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
		this.tooltip();	
		this.toggle_menu();	
		this.data_table();	
		this.profile_toggle();	
		// this.custom_scrollbar();	
		this.action_toggle();	
		this.sidebar_active();	
		this.select_box();	
		this.select_box_2();	
		this.checkbox_active();	
		this.range_picker();	
		this.range2_picker();	
		this.donut_chart();	
		this.line_chart();	
		this.cricular_progbar();	
		this.cricular_progbardot();	
		this.pay_check_active();	
		this.common_remove();
		this.commonPopup();
			
		},
		
		/*-------------- Discovered Functions Calling ---------------------------------------------------
		---------------------------------------------------------------------------------------------------
		---------------------------------------------------------------------------------------------------*/
	// tooltip  
	tooltip: function() {
			$('[data-toggle="tooltip"]').tooltip();
	},

	
	/*------------------------------------------------------------------*/ 
	commonPopup : function(){
		
		$(".common_click").on('click', function(){
			$(".dis_common_popup.dis_stmnt_popup").toggleClass("open_commonpopup");
			$("body").addClass("common_popup_bg");
		});
		
		// $("body").on('click', '.common_click', function(){
			// $(".dis_common_popup.dis_stmnt_popup").toggleClass("open_commonpopup");
			// $("body").addClass("common_popup_bg");
		// });
	
		// $(".common_close").on('click', function(){
			 // $(this).closest(".dis_common_popup").removeClass("open_commonpopup");
			 // $("body").removeClass("common_popup_bg");
		// });
	},
	
	
	/*------------------------------------------------------------------*/ 
	
	// popup common colse
	common_remove: function(){
		$(".close_common").click(function(e){
			$(".dis_common_popup").removeClass("open_commonpopup");
			$("body").removeClass("toggle_animation");
			
	});
	},
	/*------------------------------------------------------------------*/ 
	
	// checkbox active class 
	pay_check_active: function() {
		if($('.paymrnt_btn_wrappr').length > 0){
			$('.paymrnt_btn_wrappr li').on('click', function(){
				if($(".paymrnt_btn_wrappr li input[type='radio']").is(':checked')){
				  $('.paymrnt_btn_wrappr li').removeClass('active');
				  $(this).addClass('active');
				}
			});
		}
	},
	
	
	
	/*------------------------------------------------------------------*/ 
	
	
	// MObile toggle
	toggle_menu: function() {
		if($('.toggle_menuicon').length > 0){
			$('.toggle_menuicon').on('click', function(){
				$('body').toggleClass('open');
				
			});
			
		}
	},
	/*------------------------------------------------------------------*/ 
	
	// DATA TABLE
	data_table: function() {
		if($('#example').length > 0){
			$('#example').DataTable();
		}
	},
	/*------------------------------------------------------------------*/ 
	
	/*------------------------------------------------------------------*/ 
	//start custom scroll bar
	// custom_scrollbar: function() {
		// if($('.port_sidebar_wrapper').length > 0){
			// $('.port_sidebar_wrapper').mCustomScrollbar({
			// moveDragger:true,
			// scrollEasing:"easeOut"
			// });
		// }
	// },
	/*------------------------------------------------------------------*/ 
	// profile toggle
		profile_toggle: function() {
			if($('.user_caret').length > 0){	
				$('.user_caret').on('click',function(){
				  $(this).parent('.header_profile').toggleClass("open");
					});
					$('.header_profile').on('click', function(e){
						e.stopPropagation();
					});
					$('.profile_dropdown').on('click', function(e){ 
						e.stopPropagation();
					});
					$('body').on('click', function(){ 
                    $('.header_profile').removeClass('open');
					}); 
		}
	},	
	/*------------------------------------------------------------------*/	
	
	/*------------------------------------------------------------------*/ 
	// data table action toggle
		action_toggle: function() {
			if($('.table_actionboxs').length > 0){	
				$('.table_actionboxs').on('click',function(){
					$(this).toggleClass("open");
					
					

					});
					$('.action_drop').on('click', function(e){ 
						e.stopPropagation();
					});
					$('body').on('click', function(){ 
                    $('.table_actionboxs').removeClass('open');
					});
					$('.table_actionboxs').on('click', function(e){
						e.stopPropagation();
					});
		}
	},	
	/*------------------------------------------------------------------*/

	// sidebar active class
	sidebar_active: function() {
		if($('.sidebar_main_menu > li > a').length > 0){	
			$('.sidebar_main_menu > li > a').on('click' , function(e){
				$('.sidebar_main_menu > li > a').removeClass('active');
				$(this).addClass('active');
			});
		}
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
		if($('.rangepicker').length > 0){
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
		$('input.rangepicker').val('Date');
	},
	/*------------------------------------------------------------------*/ 
	range2_picker: function() {
		if($('.rangepicker.dash').length > 0){
			$('.rangepicker').daterangepicker({
			timePicker: false,
			opens: 'center',
			drops: 'up',
			startDate: moment().startOf('hour'),
			endDate: moment().startOf('hour').add(32, 'hour'),
			locale: {
			  format: 'DD-MM-YYYY'
			}
		  });
		}
		$('input.rangepicker.dash').val('Custom');
	},
	/*------------------------------------------------------------------*/ 
	donut_chart: function() {
		if($('.dash_donut_chart .ct-chart').length > 0){
			var chart = new Chartist.Pie('.dash_donut_chart .ct-chart', {
			  labels: [1, 2, 3, 4, 5, 6],
			series: [
						{meta: 'description', value: 20},
						{meta: 'description', value: 10},
						{meta: 'description', value: 20},
						{meta: 'description', value: 10},
						{meta: 'description', value: 15},
						{meta: 'description', value: 25}
						
					]
			}, {
			  donut: true,
			  showLabel: false,
			  donutWidth: 35,
			  plugins: [
				Chartist.plugins.tooltip()
			  ]
			});
		}
	},
	/*------------------------------------------------------------------*/ 
	line_chart: function() {
		if($('.dash_earning_line .ct-chart').length > 0){
			new Chartist.Line('.dash_earning_line .ct-chart', {
			  labels: ['Jan', 'Fab', , 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
				series: [
						[
						  {meta: 'description', value: 0 },
						  {meta: 'description', value: 1},
						  {meta: 'description', value: 2},
						  {meta: 'description', value: 3},
						  {meta: 'description', value: 2},
						  {meta: 'description', value: 2},
						  {meta: 'description', value: 2},
						  {meta: 'description', value: 7}
						]
					]
			  
			},
			{
			  fullWidth: true,
			  chartPadding: {
				right: 20
			  },
			  plugins: [
				Chartist.plugins.tooltip()
			  ]
			  
				
			});

		}
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
		if($('.circlePercent').length > 0){
			function setProgress(elem, percent) {
		  var
			degrees = percent * 3.6,
			transform = /MSIE 9/.test(navigator.userAgent) ? 'msTransform' : 'transform';
		  elem.querySelector('.counter').setAttribute('data-percent', Math.round(percent));
		  elem.querySelector('.progressEnd').style[transform] = 'rotate(' + degrees + 'deg)';
		  elem.querySelector('.progress').style[transform] = 'rotate(' + degrees + 'deg)';
		  if (percent >= 50 && !/(^|\s)fiftyPlus(\s|$)/.test(elem.className))
			elem.className += ' fiftyPlus';
		}

		(function() {
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
		})();
		}
	},
	/*------------------------------------------------------------------*/ 
	
	};
	discovered.init();
}(jQuery));	





	



	
	// $(document).ready(function(){
	// $('#datetimepicker1').datetimepicker();
           
	// });

