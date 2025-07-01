
(function ($) {
	"use strict";
	var Audition = {
		initialised: false,
		version: 1.0,
		mobile: false,
		init: function () {

			if(!this.initialised) {
				this.initialised = true;
			} else {
				return;
			}

			/*-------------- Audition Functions Calling ---------------------------------------------------
			------------------------------------------------------------------------------------------------*/
			this.preloader();
			this.RTL();
			this.dark_toggle();
			this.Common_popupclick();
			this.Height();
			this.Audition_menu();
			this.Check_box();
			this.On_scroll();
			this.Multiple_select();
			this.Menu_small_width();
			this.show_opacity();
			this.Close_Opacity();
			this.Scrollbar();
			this.comment_popup_mob();
			this.common_remove();
			this.BGColorOnHover();
			this.SearchToggle();
			this.FormFieldAnimation();
			this.DivHeight();
			this.CheckboxActive();
			this.WithoutBannerHeader();
			this.LoadMore();
			this.FooterPopup();
			this.commonPopup();
			this.json_pro_data_loaders();
			this.json_pro_bott_loader();
			this.playernext_tgl();
			this.before_login_overlay();
			this.grid_view_search();
			this.model_outside_not_closed();
			this.ads_fix_scrl();
			this.strem_chat();
			this.signup_box();
		},
		
		/*-----------------------------------------------------
			
		-----------------------------------------------------*/
		/*-----------------------------------------------------
			Preloader
		-----------------------------------------------------*/
		preloader: function () {
			$(window).on('load', function () {
				$(".preloader_wrapper").removeClass('preloader_active');
			});
			jQuery(window).on('load', function () {
				setTimeout(function () {
					jQuery('.preloader_open').addClass('loaded');
				}, 100);
			});
		},
		/*-----------------------------------------------------
			
		-----------------------------------------------------*/
		signup_box: function(){
			if($('.dis_signup_grid_wrap').length > 0){
				
				var bodyheight = $(window). height();
				var defaultheight = 147;
				var divheight = $('.dis_signup_grid_box').innerHeight();
				var headerheight = $('.au_header_section').innerHeight();
				var fotterheight = $('.dis_copyright').innerHeight();				
				var innerbodyheight = parseFloat(bodyheight)-parseFloat(headerheight )-parseFloat(fotterheight);
				// var spaceheight = parseFloat(innerbodyheight)-parseFloat(defaultheight);
				var scrollheight = parseFloat(innerbodyheight)-parseFloat(defaultheight)
				$('.dis_signup_grid_box').css('height', scrollheight);

				// alert(scrollheight);
			}
		},
		/*-----------------------------------------------------
			live sreaming chat board position is chnage on the each screen size .
		-----------------------------------------------------*/
		strem_chat: function(){
			if($('.dis_stream_chatwrap').length > 0){
				var headerheight = $('.au_header_section').innerHeight();
				$('.dis_stream_chatwrap').css('top', headerheight);
			}
		},
		/*-------------- Audition Functions definition ---------------------------------------------------
		---------------------------------------------------------------------------------------------------*/
		// ads fixed on scroll	
		ads_fix_scrl: function(){
			$(window).on('load', function () {
				if($('.sticky_sidebar ').length > 0){
					var w = window.innerWidth;
					if (w >= 991) {
					  var $window = $(window);  
					  var $sidebar = $(".sticky_sidebar");
					  var $sidebarOffset = $sidebar.offset();
					  
					  $window.scroll(function() {
						if($window.scrollTop() > $sidebarOffset.top) {
						  $sidebar.addClass("fixed");   
							} else {
						  $sidebar.removeClass("fixed");   
						}
					  });
					}
				}
			})
		},
		
		//** When we click outside the "sneak peek view model" it should not be closed**/ this add for only this model
		model_outside_not_closed: function(){
			if($('.dis_custom_video_popup ').length > 0){
				$('.dis_custom_video_popup ').modal({
				show:false,
				backdrop:'static'
				});
			}
		},
		grid_view_search: function(){
			if($('.dis_search_video_warapper').length > 0){
				$('.g_view').on('click', function(){
					$('.dis_search_video_warapper').addClass('view');
			
				});	
				$('.f_view').on('click', function(){
					$('.dis_search_video_warapper').removeClass('view');
			
				});	
			}
		},
		
		before_login_overlay: function() {
			if($('.audition_main_wrapper').length){
				if(user_login_id == ''){
				$(".audition_main_wrapper").addClass('before_log');	 
				}
			else{
				$(".audition_main_wrapper").removeClass('before_log');	
				}	
			}

		},
		
		
		RTL: function () {
			var rtl_attr = $("html").attr('dir');
			if(rtl_attr){
				$('html').find('body').addClass("rtl");	
			}		
		},
		
		playernext_tgl: function(){
			if($('.video_nexttoggle').length > 0){
				$('.video_nexttoggle').on('click', function(){
					$(this).parent().toggleClass('open');
			
				});	
			}
		},
		
		dark_toggle: function() {
			
		if($('.switcher_main_wrapper').length > 0){
				$('.switch_t').on('click', function(e){ 
					$(this).parent().toggleClass('open');
                }); 
				var dw_switcher = $('#dw_switcher');  
				dw_switcher.on('click', function(){
					console.log('asdasda');
					if($(this).prop("checked") == true){
						changeSkin('theme_dark');
					}
					else {
						$('body').removeClass('theme_dark');
						changeSkin('');
					}
				});
				
				function changeSkin(cls){
					$('body').addClass(cls)
					store('Theme',cls);
					
					if(cls == 'theme_dark'){
						dw_switcher.attr('checked','checked');
					}
					return false
				}
				setup();
				function setup() {
					var tmp = get('Theme');
					
					if(tmp == null){
						changeSkin('theme_dark');
					}else{
						changeSkin(tmp);
					}
					
				}
				
			}
			
		},
		
		json_pro_data_loaders: function() {
			
			if($('#pro_data_loader').length){
				var params = {

				container: document.getElementById('pro_data_loader'),

				renderer: 'svg',

				loop: true,

				autoplay: true,

				animationData: animationData.profileLoader

				};

				var anim;

				anim = lottie.loadAnimation(params);
			}
			

		},
		
		json_pro_bott_loader: function() {
			
			if($('#pro_btm_loader').length){
				var params = {

				container: document.getElementById('pro_btm_loader'),

				renderer: 'svg',

				loop: true,

				autoplay: true,

				animationData: animationData.profileLoader

				};

				var anim;

				anim = lottie.loadAnimation(params);
			
			}

		},
		
		Common_popupclick: function() {
			  $(".common_click.c_tb").click(function(){
			   $(".dark_popup").toggleClass("open_commonpopup");
			$("body").addClass("common_popup_bg");
			});

			$(".common_click.app_link_wrap").click(function(){
				$(".app_download").toggleClass("open_commonpopup");
				 $("body").addClass("common_popup_bg");
			 });
		},
		
		Height:function() {
			 var h = $(window).innerHeight() - 70;
			 $(".media_gallery img").css("max-height", h);
		},
		Audition_menu:function() {
			$(".nav_toggle").click(function() {
				$(".au_menu_wrapper").toggleClass('open_menu');
			});
			$(".genre_link").click(function(event) {
				event.preventDefault();
				$(".audition_menu").addClass('open_genre_menu');
			});
		},
		Check_box:function() {
			var $on = '.checkbox_wrapper';
			$($on).css({
			  'background':'none',
			  'border':'none',
			  'box-shadow':'none'
			});
		},
		 On_scroll:function() { 
			   $(window).scroll(function() {
			 	 var wh = window.innerWidth;
			 	 var window_top = $(window).scrollTop() + 1;
			 	 if (window_top > 200) {
			 		  $('.au_header_section').addClass('fixed_menu');
			 	  } else {
			 		 $('.au_header_section').removeClass('fixed_menu');
			 	  }
			   });
		 },

		Multiple_select:function() {
			
			$(".multi_select .selected_option a").on('click', function() {
			  $(this).closest(".input-group").toggleClass('fg-toggled');
			  $(".multi_select .mutliSelect").slideToggle('fast');
			});

			$(".multi_select .mutliSelect ul li a").on('click', function() {
			  $(".multi_select .mutliSelect").hide();
			});

			function getSelectedValue(id) {
				return $("#" + id).find(".selected_option a span.value").html();
			}

			$(document).bind('click', function(e) {
			  var $clicked = $(e.target);
			  if (!$clicked.parents().hasClass("multi_select")) $(".multi_select .mutliSelect").hide();
			});
			
			
			var primary = [];
			$('.mutliSelect input[type="checkbox"]').on('click', function() {
			
			  var title = $(this).closest('.mutliSelect').find('input[type="checkbox"]').val();
			  title = $(this).val() + ",";
			 // console.log($('.check_box:checkbox:checked').val());
			   if ($(this).is(':checked')) {
				 var res = title.split("|");
				 var html = '<span title="' + res[1] + '">' + res[1] + '</span>';
					
					$('.multiSel').append(html);
					$(".hide_select").hide();
					primary.push(res[0]);
					$('#primary_type').val(primary);
				// console.log(primary);
			  }else{
				 	var res = title.split("|");
					$('span[title="' + res[1] + '"]').remove();
				
					primary = $.grep(primary, function(value) {
					  return value != res[0];
					}); 
					$('#primary_type').val(primary);
					// console.log(primary);
					if(!primary.length){
						$(".hide_select").show();
					}
			 }
			});
		},
		 

		Menu_small_width:function() {
			$(".header_menu_wrapper").removeClass('slide_menu');  
			$(".desktop_menu > li:has(ul) > a").on('click', function(e) {
				var w = window.innerWidth;
				if (w <= 991) {
					e.preventDefault();
					$(this).parent('.desktop_menu li').children('ul').slideToggle();
				}
			});
			$(".desktop_menu li ul li a").on('click', function(e) {
				var w = window.innerWidth;
				if (w <= 991) {
					e.preventDefault();
					$(this).parent('.desktop_menu li ul li').children('.half_wrapper_parent').slideToggle();
					 
				}
			});
			
		},
		show_opacity:function () {
			$(".opacity_textarea").click(function() {
				$("body").addClass('userpost_popup');
			});
		},
		Close_Opacity: function(){
			$(".close_opacity").click(function(){
				$("body").removeClass('userpost_popup');
			});
		},
		Scrollbar:function () {
			[].forEach.call(document.querySelectorAll('.scrollbar_content'), function (el) {
			  Ps.initialize(el);
			});
		},
		
		comment_popup_mob:function () {
			$(".comment_post").click(function() {
				$(".media_gallery_popup .comment_wrapper").addClass('open_comment_popup');
			});
			$(".close_comment_popup").click(function() {
				$(".media_gallery_popup .comment_wrapper").removeClass('open_comment_popup');
			});
		},
		
		common_remove: function(){
		$(".close_common").click(function(e){
			$("body").removeClass("toggle_animation");
	});
	},
					
		BGColorOnHover: function(){
			$(".au_menu .desktop_menu li, .au_menu .desktop_menu li ul, .others_info > ul > li").hover(function(){
				$('.au_header_section').toggleClass('bgcolor');
			});
		},
		SearchToggle: function(){
			$(document).on("click", ".dis_search_toggle", function(e){
				$(".dis_after_login_search").toggleClass('open');
			});
			
			document.addEventListener('click', e => {
				let ST 	= document.querySelector('.dis_search_toggle > svg');
				let ALS = document.querySelector('.dis_after_login_search');
				let AMW = document.querySelector('div.audition_main_wrapper > div.au_header_section > div.menu_wrapper > div > ul > li > a');
				let AHS =document.querySelector('div.au_header_section> div.menu_wrapper > div > ul > li > div');
				// console.log('e:'+typeof e);
				// if(!e.target.isSameNode( ST ) && typeof e == 'object' && !e.path[3].isSameNode( ALS ) && !e.target.isSameNode( AMW ) && AHS.classList.contains('open')){
					// console.log(ALS)
					if(!e.target.isSameNode( ST ) && typeof e == 'object' && !e.path?.[3].isSameNode?.( ALS ) && !e.target.isSameNode( AMW ) && AHS.classList.contains('open') && !ALS.contains(e.target)){
						// alert(e.target.className);
					document.querySelector('div.menu_wrapper > div > ul > li > div').classList.remove('open');
				}
			});
			
		},
		FormFieldAnimation: function(){
			$('body').on('focus', '.dis_signup_input', function(){
				$(this).closest('.input-group').addClass('fg-toggled');
			});
			$('body').on('blur', '.dis_signup_input', function(){
				$(this).closest('.input-group').removeClass('fg-toggled');
			});
		},
		DivHeight: function(){
			var h = window.innerHeight;
			$(".dis_landingpage_left").css("height", h);
			$(".dis_landingpage_right").css("height", h);
			$(".dis_landing_page_wrapper .dis_fullwidth").css("height", h);
		},
		CheckboxActive: function(){
			$('.dis_video_thumbnail_img input').click(function () {
				$('input:not(:checked)').parent().parent().removeClass("active");
				$('input:checked').parent().parent().addClass("active");
			});
			$('input:checked').parent().parent().addClass("active");
		},
		WithoutBannerHeader: function(){
			if ($("body div.audition_main_wrapper").hasClass("au_banner_section")) {
			   $(".au_header_section").removeClass("dis_default_header");
			}
			else if ($("body div.audition_main_wrapper").hasClass("movie_page")) {
				 $(".au_header_section").removeClass("dis_default_header");
			}
			else{
				$(".au_header_section").addClass("dis_default_header");
			}					
					
		},
		LoadMore: function(){
			$('.dis_loadmore').click(function(){
				$('.dis_loadmore_data').show("slow");
			})
		},
		FooterPopup: function(){
			
			$(".play_footer_video").click(function(){
				$(".au_footer_video_popup").addClass('open_popup');
			
			$('video.popup_footer_video')[0].play();
			$('video.popup_footer_video').prop('muted', false); 				

				
			});
		},
		commonPopup : function(){
			$("body").on('click', '.common_click', function(){
				$("body").addClass("common_popup_bg");
			});
		
			$(".common_close").on('click', function(){
				$(this).closest(".dis_common_popup").removeClass("open_commonpopup");
				$("body").removeClass("common_popup_bg");
			});
		}
	
		
	};
	Audition.init();

	
})(jQuery);

 //policies page scroll always top
 
$(".dis_policies_wrapper .nav-tabs > li > a").on("click", function() {
    $(".dis_policies_wrapper .tab-content").scrollTop(0);
});










// live stream chat board js start
// The height of the box changes when scrolling.

$(window).scroll(function () {	
	var bodyheight = $(window). height();
	var headerheight = $('.au_header_section').innerHeight();
	var fotterheight = $('.dis_copyright').innerHeight();
	if($(window).scrollTop() + $(window).height() > $(document).height() - fotterheight) {
	headerheight = parseFloat(bodyheight)-parseFloat(headerheight )-parseFloat(fotterheight);
	$('.dis_stream_chatwrap').css('height', headerheight);
	}
	else{
		headerheight = parseFloat(bodyheight)-parseFloat(headerheight );
		$('.dis_stream_chatwrap').css('height', headerheight);
	}
});

// live sreaming chat board position is chnage from the top when resize the window.

$(window).on('resize', function(){
	var headerheight = $('.au_header_section').innerHeight();
	$('.dis_stream_chatwrap').css('top', headerheight);
});



        // ------------step-dis_stream_step_inner-------------
		// $(document).ready(function () {
		// 	$('.nav-tabs > li a[title]').tooltip();
			
		// 	//Dis_stream_step_inner
		// 	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		
		// 		var target = $(e.target);
			
		// 		if (target.parent().hasClass('disabled')) {
		// 			return false;
		// 		}
		// 	});
		
		// 	$(".stream_sp_next_step").click(function (e) {
		
		// 		var active = $('.dis_stream_step_inner .nav-tabs li.active');
		// 		active.next().removeClass('disabled');
		// 		nextTab(active);
		
		// 	});
		// 	$(".stream_sp_prev_step").click(function (e) {
		
		// 		var active = $('.dis_stream_step_inner .nav-tabs li.active');
		// 		prevTab(active);
		
		// 	});
		// });
		
		// function nextTab(elem) {
		// 	$(elem).next().find('a[data-toggle="tab"]').click();
		// }
		// function prevTab(elem) {
		// 	$(elem).prev().find('a[data-toggle="tab"]').click();
		// }
		
		
		// $('.nav-tabs').on('click', 'li', function() {
		// 	$('.nav-tabs li.active').removeClass('active');
		// 	$(this).addClass('active');
		// });


		// ------------step-dis_stream_step_inner-------------
$(document).ready(function () {    
    $(".stream_sp_next_step").click(function (e) {
        var $active = $('.dis_stream_sp_toplist li.active');
        nextTab($active);
    });
    $(".stream_sp_prev_step").click(function (e) {
        var $active = $('.dis_stream_sp_toplist li.active');
        prevTab($active);
    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}