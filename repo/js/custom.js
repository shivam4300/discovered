(function ($) {
	'use strict';
	window.Audition = {
		initialised: false,
		version: 1.0,
		mobile: false,
		init: function () {
			if (!this.initialised) {
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
			this.commonPopup();
			this.FormFieldAnimation();
			this.DivHeight();
			this.CheckboxActive();
			this.WithoutBannerHeader();
			this.LoadMore();
			this.FooterPopup();
			this.playernext_tgl();
			this.before_login_overlay();
			this.grid_view_search();
			this.model_outside_not_closed();
			this.ads_fix_scrl();
			this.strem_chat();
			this.player_timer();
			this.tooltip();
			this.grid_view_product();
			this.custom_tab();
			this.Product_price_range();
			// this.graph();
			this.product_slider();
			this.Rproduct_slider();
			this.quantity();
			this.cart_click();
			// this.magnific_popup();
			this.datePicker();
			this.sticky();
			this.read_more();
			this.art_tgl();
			this.player_setting();
			this.ai_text_toggle();
			this.rd_about_slider();
		},

		/*-----------------------------------------------------
			re design page testimonial slider
		-----------------------------------------------------*/
		rd_about_slider: function () {
			if ($('.dis_rd_about_sec8_testimonial ').length > 0) {
				var swiper = new Swiper(".dis_rd_about_testi_thumb", {
					loop: true,
					spaceBetween: 10,
					slidesPerView: 1,
					freeMode: true,
					watchSlidesVisibility: true,
					watchSlidesProgress: true,
				});
				var swiper2 = new Swiper(".dis_rd_about_testi_gallery", {
					loop: true,
					spaceBetween: 10,
					autoplay: {
						delay: 4500,
						disableOnInteraction: false,
					},
					navigation: {
						nextEl: ".dis_rd_a_t_controlsNext",
						prevEl: ".dis_rd_a_t_controlsPrev",
					},
					thumbs: {
						swiper: swiper,
					},
				});
			}
		},
		// rd_about_slider: function () {
		// 	var swiper = new Swiper(".dis_rd_about_thumb", {
		// 		loop: true,
		// 		spaceBetween: 10,
		// 		slidesPerView: 3,
		// 		speed: 800,
		// 		// autoplay: {
		// 		// 	delay: 1500,
		// 		// 	disableOnInteraction: false,
		// 		// },
		// 		centeredSlides: true,
		// 		allowTouchMove: false,
		// 		effect: 'coverflow',
		// 		coverflowEffect: {
		// 			rotate: 0,
		// 			stretch: 10,
		// 			depth: 0,
		// 			modifier: 1,
		// 			slideShadows: false
		// 		},

		// 	});
		// 	var swiper2 = new Swiper(".dis_rd_about_details", {
		// 		spaceBetween: 10,
		// 		loop: true,
		// 		allowTouchMove: false,
		// 		speed: 800,
		// 		slidesPerView: 1,
		// 		navigation: {
		// 			nextEl: ".dis_rd_about_thumb .swiper-button-next",
		// 			prevEl: ".dis_rd_about_thumb .swiper-button-prev",
		// 		},
		// 		thumbs: {
		// 			swiper: swiper,
		// 		},
		// 	});
		// },
		/*-----------------------------------------------------
			ai_text_toggle option
		-----------------------------------------------------*/
		ai_text_toggle: function () {
			$(document).on('click', '.dis_ai_toggle', function () {
				$('.dis_ai_text_main').removeClass('open');
				$(this).parent('.dis_ai_text_main').toggleClass('open');
			});
		},
		/*-----------------------------------------------------
			player setting option
		-----------------------------------------------------*/
		player_setting: function () {
			$('.innerOption').on('click', function () {
				$('.dis_settingOptionList > li').removeClass('active');
				$(this).parents().addClass('active');
			});
			$('.dis_pc_settingBack').on('click', function () {
				$('.dis_settingOptionList > li').removeClass('active');
			});
			// active
			$('.dis_stingInnerOption > li').on('click', function () {
				$('.dis_stingInnerOption > li').removeClass('selected');
				$(this).addClass('selected');
			});
		},

		// article sibebar toggle
		art_tgl: function () {
			$('.dis_art_tgl').on('click', function (e) {
				var w = window.innerWidth;
				if (w <= 767) {
					e.preventDefault();
					$('body').toggleClass('tgl_open');
				}
			});
		},
		//Read more
		read_more: function () {
			if ($('#vc').length > 0) {
				$('#vc').on('click', function () {
					// $(".more_content").not($(this).siblings(".more_content")).slideUp();
					// $(".dis_vc_userReply").not(this).text("Read More");
					// $(this).siblings('.more_content').slideToggle();
					if ($(this).text() == 'View Replies (4)') {
						$(this).text('Hide Replies');
					} else {
						$(this).text('View Replies (4)');
					}
				});
			}
		},
		/*-----------------------------------------------------
			Sticky Sidebar slider
		-----------------------------------------------------*/
		sticky: function () {
			if ($('.dis_articlesWrap').length > 0) {

				if (typeof current_page !== 'undefined' && (current_page == 'article_single' || current_page == 'article_mode')) {

					setTimeout(() => {

						var distance = $('.fixedAds')?.offset()?.top + $('.fixedAds')?.height();

						$(window).scroll(function () {

							if ($(window).scrollTop() >= distance && distance !== 0 && $(window).width() > 1000) {
								$('.fixedAds').addClass('fixed');
							} else {
								$('.fixedAds').removeClass('fixed');
							}
						});
					}, 2500);
				}
			}
		},
		/*-----------------------------------------------------
			Articles slider
		-----------------------------------------------------*/
		// initialize in article js

		/*-----------------------------------------------------
			prodcut
		-----------------------------------------------------*/
		datePicker: function () {
			if ($('.datepicker').length > 0) {
				$('.datepicker').daterangepicker({
					timePicker: false,
					singleDatePicker: true,
					startDate: moment().startOf('hour'),
					drops: 'up',
					minDate: 'today',
					locale: {
						format: 'YYYY-MM-DD',
					},
				});
			}
		},
		/*-----------------------------------------------------
			prodcut single magnific popup
		-----------------------------------------------------*/
		magnific_popup: function () {
			if ($('.view').length > 0) {
				$('.view').magnificPopup({
					type: 'image',
					mainClass: 'mfp-with-zoom',
					gallery: {
						enabled: true,
					},
					zoom: {
						enabled: true,
						duration: 300,
						easing: 'ease-in-out',
					},
				});
			}
		},

		/*-----------------------------------------------------
			cart toggle
		-----------------------------------------------------*/
		cart_click: function () {
			$('.cart_toggle').on('click', function (e) {
				e.stopPropagation();
				$('.cart_ddtoggle').toggleClass('active');
			});
			$('.dis_card_dd_list').on('click', function (e) {
				e.stopPropagation();
			});
			$('body').on('click', function () {
				$('.cart_ddtoggle').removeClass('active');
			});
		},
		/*-----------------------------------------------------
			Products Quantity
		-----------------------------------------------------*/
		quantity: function () {
			var quantity = 0;
			$(document).on('click', '.quantity-plus', function (e) {
				e.preventDefault();
				var quantity = Number($(this).siblings('.quantity').val());
				$(this)
					.siblings('.quantity')
					.val(quantity + 1);
			});
			$(document).on('click', '.quantity-minus', function (e) {
				e.preventDefault();
				var quantity = Number($(this).siblings('.quantity').val());
				if (quantity > 0) {
					$(this)
						.siblings('.quantity')
						.val(quantity - 1);
				}
			});
		},
		/*-----------------------------------------------------
			single Prodcut slider
		-----------------------------------------------------*/
		product_slider: function () {
			if ($('.dis_productsImg_wrap').length > 0) {
				var swiper = new Swiper('.dis_productsImg_wrap .mySwiper', {
					loop: true,
					spaceBetween: 10,
					slidesPerView: 4,
					freeMode: true,
					watchSlidesProgress: true,
				});
				var swiper2 = new Swiper('.dis_productsImg_wrap .mySwiper2', {
					loop: true,
					spaceBetween: 10,
					navigation: {
						nextEl: '.swiper-button-next',
						prevEl: '.swiper-button-prev',
					},
					thumbs: {
						swiper: swiper,
					},
				});
			}
		},
		/*-----------------------------------------------------
			Related Prodcut slider
		-----------------------------------------------------*/
		Rproduct_slider: function () {
			if ($('.dis_sp_sliderwrap').length > 0) {
				var swiper = new Swiper('.mySwiper', {
					loop: true,
					spaceBetween: 10,
					slidesPerView: 4,
				});
			}
		},
		/*-----------------------------------------------------
			Preloader
		-----------------------------------------------------*/
		preloader: function () {
			setTimeout(function () {
				$('.preloader_wrapper').removeClass('preloader_active');
			}, 800);
			setTimeout(function () {
				jQuery('.preloader_open').addClass('loaded');
			}, 800);
		},
		/*-----------------------------------------------------

		-----------------------------------------------------*/
		tooltip: function () {
			if ($('[data-toggle="tooltip"]').length > 0) {
				$('[data-toggle="tooltip"]').tooltip();
			}
		},
		/*-----------------------------------------------------

		-----------------------------------------------------*/
		/*-----------------------------------------------------

		-----------------------------------------------------*/
		player_timer: function () {
			// if($('.dis_signup_grid_wrap').length > 0){
			// }
		},
		/*-----------------------------------------------------

		-----------------------------------------------------*/
		signup_box: function () {
			if ($('.dis_signup_grid_wrap').length > 0) {
				var bodyheight = $(window).height();
				var defaultheight = 147;
				var divheight = $('.dis_signup_grid_box').innerHeight();
				var headerheight = $('.dis_m_header_wrap').innerHeight();
				var fotterheight = $('.dis_copyright').innerHeight();
				var innerbodyheight =
					parseFloat(bodyheight) -
					parseFloat(headerheight) -
					parseFloat(fotterheight);
				// var spaceheight = parseFloat(innerbodyheight)-parseFloat(defaultheight);
				var scrollheight =
					parseFloat(innerbodyheight) - parseFloat(defaultheight);
				$('.dis_signup_grid_box').css('height', scrollheight);

				// alert(scrollheight);
			}
		},
		/*-----------------------------------------------------
			live sreaming chat board position is chnage on the each screen size .
		-----------------------------------------------------*/
		strem_chat: function () {
			if ($('.dis_SB_C_chatwrap').length > 0) {
				var headerheight = $('.dis_m_header_wrap').innerHeight();
				$('.dis_SB_C_chatwrap').css('top', headerheight);
			}
		},
		/*-------------- Audition Functions definition ---------------------------------------------------
		---------------------------------------------------------------------------------------------------*/
		// ads fixed on scroll
		ads_fix_scrl: function () {

			$(document).ready(function () {
				if ($('.sticky_sidebar ').length > 0) {
					var w = window.innerWidth;
					if (w >= 991) {
						var $window = $(window);
						var $sidebar = $('.sticky_sidebar_wrapper');
						var $sidebarInner = $('.sticky_sidebar');
						var added_height = 10;

						$window.scroll(function () {
							var header_height = $('.dis_m_header_wrap').innerHeight();
							var ads_height = $('.sticky_sidebar_wrapper').innerHeight();
							var rightBox = $('.right_sidebar_wrapper').innerHeight();
							var audition = $('.user_profile_page').innerHeight();
							$('.sticky_sidebar.fixed').css(
								'top',
								header_height + added_height
							);
							if (
								$window.scrollTop() >
								rightBox + audition + 180 - header_height
							) {
								$sidebarInner.addClass('fixed');
							} else {
								$sidebarInner.removeClass('fixed');
							}
						});
					}
				}
			});
		},

		//** When we click outside the "sneak peek view model" it should not be closed**/ this add for only this model
		model_outside_not_closed: function () {
			if ($('.dis_custom_video_popup ').length > 0) {
				$('.dis_custom_video_popup ').modal({
					show: false,
					backdrop: 'static',
				});
			}
		},
		grid_view_search: function () {
			if ($('.dis_search_video_warapper').length > 0) {
				$('body').addClass('view');
				$('.g_view').on('click', function () {
					$('body').addClass('view');
				});
				$('.f_view').on('click', function () {
					$('body').removeClass('view');
				});
			}
		},

		before_login_overlay: function () {
			if ($('.audition_main_wrapper').length) {
				if (user_login_id == '') {
					$('.audition_main_wrapper').addClass('before_log');
				} else {
					$('.audition_main_wrapper').removeClass('before_log');
				}
			}
		},

		RTL: function () {
			var rtl_attr = $('html').attr('dir');
			if (rtl_attr) {
				$('html').find('body').addClass('rtl');
			}
		},

		playernext_tgl: function () {
			if ($('.video_nexttoggle').length > 0) {
				$(document).on('click', '.video_nexttoggle', function () {
					$(this).parent().toggleClass('open');
				});
			}
		},

		dark_toggle: function () {
			if ($('.switcher_main_wrapper').length > 0) {
				$('.switch_t').on('click', function (e) {
					$(this).parent().toggleClass('open');
				});
				var dw_switcher = $('#dw_switcher');
				dw_switcher.on('click', function () {
					if ($(this).prop('checked') == true) {
						changeSkin('theme_dark');
					} else {
						$('body').removeClass('theme_dark');
						changeSkin('');
					}
				});

				if (!$('body').hasClass('theme_dark') && typeof page !== 'undefined' && page == "getdiscovered") {
					dw_switcher.trigger('click');
				}

				function changeSkin(cls) {
					$('body').addClass(cls);
					store('Theme', cls);
					document.cookie = "Theme=" + cls;
					if (cls == 'theme_dark') {
						dw_switcher.attr('checked', 'checked');
					}
					return false;
				}

				window.setup = function () {
					var tmp = get('Theme');

					if (tmp) {
						changeSkin(tmp);
					} else {
						$('body').removeClass('theme_dark');
						changeSkin('');
					}
				}
			}
		},

		Common_popupclick: function () {
			$(".common_click.c_tb").click(function () {
				$(".dark_popup").toggleClass("open_commonpopup");
				window.setup();
				$("body").addClass("common_popup_bg");
			});

			$('.common_click.app_link_wrap').click(function () {
				$('.app_download').toggleClass('open_commonpopup');
				$('.popup_heading').text('Download App');
				$('body').addClass('common_popup_bg');
			});
		},

		Height: function () {
			var h = $(window).innerHeight() - 70;
			$('.media_gallery img').css('max-height', h);
		},
		Audition_menu: function () {
			$('.nav_toggle').click(function () {
				$('.au_menu_wrapper').toggleClass('open_menu');
			});
			$('.genre_link').click(function (event) {
				event.preventDefault();
				$('.audition_menu').addClass('open_genre_menu');
			});
		},
		Check_box: function () {
			var $on = '.checkbox_wrapper';
			$($on).css({
				background: 'none',
				border: 'none',
				'box-shadow': 'none',
			});
		},
		On_scroll: function () {
			$(window).scroll(function () {
				var wh = window.innerWidth;
				var window_top = $(window).scrollTop() + 1;
				if (window_top > 200) {
					$('.dis_m_header_wrap').addClass('fixed_menu');
				} else {
					$('.dis_m_header_wrap').removeClass('fixed_menu');
				}
			});
		},

		Multiple_select: function () {
			$('.multi_select .selected_option a').on('click', function () {
				$(this).closest('.input-group').toggleClass('fg-toggled');
				$('.multi_select .mutliSelect').slideToggle('fast');
			});

			$('.multi_select .mutliSelect ul li a').on('click', function () {
				$('.multi_select .mutliSelect').hide();
			});

			function getSelectedValue(id) {
				return $('#' + id)
					.find('.selected_option a span.value')
					.html();
			}

			$(document).bind('click', function (e) {
				var $clicked = $(e.target);
				if (!$clicked.parents().hasClass('multi_select'))
					$('.multi_select .mutliSelect').hide();
			});

			var primary = [];
			$('.mutliSelect input[type="checkbox"]').on('click', function () {
				var title = $(this)
					.closest('.mutliSelect')
					.find('input[type="checkbox"]')
					.val();
				title = $(this).val() + ',';
				// console.log($('.check_box:checkbox:checked').val());
				if ($(this).is(':checked')) {
					var res = title.split('|');
					var html = '<span title="' + res[1] + '">' + res[1] + '</span>';

					$('.multiSel').append(html);
					$('.hide_select').hide();
					primary.push(res[0]);
					$('#primary_type').val(primary);
					// console.log(primary);
				} else {
					var res = title.split('|');
					$('span[title="' + res[1] + '"]').remove();

					primary = $.grep(primary, function (value) {
						return value != res[0];
					});
					$('#primary_type').val(primary);
					// console.log(primary);
					if (!primary.length) {
						$('.hide_select').show();
					}
				}
			});
		},

		Menu_small_width: function () {
			$('.header_menu_wrapper').removeClass('slide_menu');
			$('.desktop_menu > li:has(ul) > a').on('click', function (e) {
				var w = window.innerWidth;
				if (w <= 1199) {
					e.preventDefault();
					$(this).parent('.desktop_menu li').children('ul').slideToggle();
				}
			});
			$(document).on('click', '.dis_browseResultWrap .menu_title', function () {
				var w = window.innerWidth;
				if (w <= 1199) {
					// e.preventDefault();
					// $('.desktop_menu li ul li').children('.half_wrapper_parent').slideUp();
					// $(this).parent('.desktop_menu li ul li').children('.half_wrapper_parent').slideDown();

					if ($(this).hasClass('active')) {
						$(this).removeClass('active');
						$(this).siblings('.half_wrapper_parent').slideUp();
					} else {
						$('.dis_browseResultWrap .menu_title').removeClass('active');
						$(this).addClass('active');
						$('.half_wrapper_parent').slideUp();
						$(this).siblings('.half_wrapper_parent').slideDown();
					}
				}
			});
		},
		show_opacity: function () {
			$('.opacity_textarea').click(function () {
				$('body').addClass('userpost_popup');
				$('li[title="Monetize"]').hide();
				$('li[title="Live Stream"]').hide();
				$(".uploadSection").removeClass("hideme");
			});
		},
		Close_Opacity: function () {
			$('.close_opacity').click(function () {
				$('body').removeClass('userpost_popup');
				$('li[title="Monetize"]').show();
				$('li[title="Live Stream"]').show();
				$(".uploadSection").addClass("hideme");
			});
		},
		Scrollbar: function () {
			[].forEach.call(
				document.querySelectorAll('.scrollbar_content'),
				function (el) {
					Ps.initialize(el);
				}
			);
		},

		comment_popup_mob: function () {
			$('.comment_post').click(function () {
				$('.media_gallery_popup .comment_wrapper').addClass(
					'open_comment_popup'
				);
			});
			$('.close_comment_popup').click(function () {
				$('.media_gallery_popup .comment_wrapper').removeClass(
					'open_comment_popup'
				);
			});
		},

		common_remove: function () {
			$('.close_common').click(function (e) {
				$('body').removeClass('toggle_animation');
			});
		},

		BGColorOnHover: function () {
			$(
				'.au_menu .desktop_menu li, .au_menu .desktop_menu li ul, .others_info > ul > li'
			).hover(function () {
				$('.dis_m_header_wrap').toggleClass('bgcolor');
			});
		},
		commonPopup: function () {
			$('body').on('click', '.common_click', function () {
				$('body').addClass('common_popup_bg');
			});

			$('.common_close').on('click', function () {
				$(this).closest('.dis_common_popup').removeClass('open_commonpopup');
				$('body').removeClass('common_popup_bg');
			});
		},
		FormFieldAnimation: function () {
			$('body').on('focus', '.dis_signup_input', function () {
				$(this).closest('.input-group').addClass('fg-toggled');
			});
			$('body').on('blur', '.dis_signup_input', function () {
				$(this).closest('.input-group').removeClass('fg-toggled');
			});
		},
		DivHeight: function () {
			var h = window.innerHeight;
			$('.dis_landingpage_left').css('height', h);
			$('.dis_landingpage_right').css('height', h);
			$('.dis_landing_page_wrapper .dis_fullwidth').css('height', h);
		},
		CheckboxActive: function () {
			$('.dis_video_thumbnail_img input').click(function () {
				$('input:not(:checked)').parent().parent().removeClass('active');
				$('input:checked').parent().parent().addClass('active');
			});
			$('input:checked').parent().parent().addClass('active');
		},
		WithoutBannerHeader: function () {
			if ($('body div.audition_main_wrapper').hasClass('au_banner_section')) {
				$('.dis_m_header_wrap').removeClass('dis_default_header');
			} else if ($('body div.audition_main_wrapper').hasClass('movie_page')) {
				$('.dis_m_header_wrap').removeClass('dis_default_header');
			} else {
				$('.dis_m_header_wrap').addClass('dis_default_header');
			}
		},
		LoadMore: function () {
			$('.dis_loadmore').click(function () {
				$('.dis_loadmore_data').show('slow');
			});
		},
		FooterPopup: function () {
			$('.play_footer_video').click(function () {
				$('.au_footer_video_popup').addClass('open_popup');

				$('video.popup_footer_video')[0].play();
				$('video.popup_footer_video').prop('muted', false);
			});
		},

		graph: function () { },
		grid_view_product: function () {
			if ($('.dis_product_shopsd').length > 0) {
				$('.f_view').on('click', function () {
					$('.dis_product_shopsd').addClass('view');
				});
				$('.g_view').on('click', function () {
					$('.dis_product_shopsd').removeClass('view');
				});
			}
		},
		// custom tab
		custom_tab: function () {
			$('.dis-custom-tab .dis-custom-tab-link').click(function () {
				// Check for active
				$('.dis-custom-tab .dis-custom-tab-list').removeClass('active');
				$(this).parent().addClass('active');

				// Display active tab
				let currentTab = $(this).attr('data-href');
				$('.dis-custom-result').hide();
				$(currentTab).show();
				return false;
			});
		},
		// product price range slider
		Product_price_range: function () {
			if ($('#slider-range').length > 0) {
				$('#slider-range').slider({
					range: true,
					min: 100,
					max: 1000,
					step: 1,
					values: [100, 200],
					slide: function (e, ui) {
						var min = Math.floor(ui.values[0]);
						$('.slider-time').html(min + '$');

						var max = Math.floor(ui.values[1]);

						$('.slider-time2').html(max + '$');

					}
				});
			}
		},
	};
	Audition.init();
})(jQuery);

//policies page scroll always top

$('.dis_policies_wrapper .nav-tabs > li > a').on('click', function () {
	$('.dis_policies_wrapper .tab-content').scrollTop(0);
});

// live stream chat board js start
// The height of the box changes when scrolling.

$(window).scroll(function () {
	var bodyheight = $(window).height();
	var headerheight = $('.dis_m_header_wrap').innerHeight();
	var fotterheight = $('.dis_copyright').innerHeight();
	// if (
	// 	$(window).scrollTop() + $(window).height() >
	// 	$(document).height() - fotterheight
	// ) {
	// 	headerheight =
	// 		parseFloat(bodyheight) -
	// 		parseFloat(headerheight) -
	// 		parseFloat(fotterheight);
	// 	$('.dis_stream_chatwrap').css('height', headerheight);
	// } else {
	// 	headerheight = parseFloat(bodyheight) - parseFloat(headerheight);
	// 	$('.dis_SB_C_chatwrap').css('height', headerheight);
	// }
});

// live streaming chat board position is change from the top when resize the window.

$(window).on('resize', function () {
	var headerheight = $('.dis_m_header_wrap').innerHeight();
	$('.dis_SB_C_chatwrap').css('top', headerheight);
});


function nextTab(elem) {
	$(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
	$(elem).prev().find('a[data-toggle="tab"]').click();
}


// ------------step-dis_stream_step_inner-------------
$(document).ready(function () {
	$(document).on('click', '.stream_sp_next_step', function (e) {
		var $active = $('.dis_stream_sp_toplist li.active');
		nextTab($active);
	});
	$(document).on('click', '.stream_sp_prev_step', function (e) {
		var $active = $('.dis_stream_sp_toplist li.active');
		prevTab($active);
	});

	function nextTab(elem) {
		$(elem).next().find('a[data-toggle="tab"]').click();
	}
	function prevTab(elem) {
		$(elem).prev().find('a[data-toggle="tab"]').click();
	}


	$(".dis_vs_sb_close").click(function (e) {
		$("body").removeClass("open_stream_sb");
	});

	if ($('.dis_daterangePicker .daterange').length > 0) {
		$('.dis_daterangePicker .daterange').daterangepicker({
			timePicker: false,
			autoUpdateInput: false,
			opens: 'center',
			startDate: moment().startOf('hour'),
			endDate: moment().startOf('hour').add(32, 'hour'),
			locale: {
				format: 'YYYY/MM/DD',
			},
		});
		$('input.daterange').val('');

	}

	$('.daterange').on('cancel.daterangepicker', function (ev, picker) {
		$(this).val('');
		ticketStart = 0;
		getUserTicket();
	});

	$('.daterange').on('apply.daterangepicker', function (ev, picker) {
		$(this).val(
			picker.startDate.format('YYYY/MM/DD') +
			' - ' +
			picker.endDate.format('YYYY/MM/DD')
		);
		ticketStart = 0;
		getUserTicket();
	});

	setTimeout(() => {
		if ($('.custom_dropdown_wrap').length > 0) {
			$(document).on('click', '.custom_dropdown_btn', function (e) {
				$(this).parent().toggleClass('open');
				e.stopPropagation();
			});
			$(document).on('click', function () {
				$('.custom_dropdown_wrap').removeClass('open');
			});
			$('.custom_dropdown_menu').on('click', function (e) {
				// e.stopPropagation();
			});
		}
	}, 500);

	if ($('.dis_userchat_iconsBox_search').length > 0) {
		$(document).on('click', '.dis_userchat_iconsBox_search', function (e) {
			$('.dis_chat_pp_search').addClass('open');
			e.stopPropagation();
		});
		$(document).on('click', '.dis_chat_pp_search_close', function (e) {
			$('.dis_chat_pp_search').removeClass('open');
			e.stopPropagation();
		});
	}

	if ($('.dis_chat_contactsList').length > 0) {
		$(document).on('click', '.dis_chat_contactsList > li', function (e) {
			$('body').addClass('open');
			e.stopPropagation();
		});
		$(document).on('click', '.dis_userchat_back', function (e) {
			$('body').removeClass('open');
			e.stopPropagation();
		});
	}
	$(document).on('click', 'a[data-toggle="tab"]', function () {
		if ($('#chat_message').hasClass('active')) {
			$('#ColMd4').hide();
			$('#ColMd8')
				.removeClass('col-lg-8 col-md-8')
				.addClass('col-lg-12 col-md-12');
		} else {
			$('#ColMd4').show();
			$('#ColMd8')
				.removeClass('col-lg-12 col-md-12')
				.addClass('col-lg-8 col-md-8');
		}
	});
});


// sticky footer on scroll
$(window).scroll(function () {
	var scroll = $(window).scrollTop();
	if (scroll >= 0.4 * $(window).height()) {
		$('body').addClass('sticky_footer');
	} else {
		$('body').removeClass('sticky_footer');
	}
});
$(window).scroll(function () {
	var fotterheight = $('.dis_copyright').innerHeight();
	$('.sticky_video').css('bottom', fotterheight);

});

if ($('#appendChannelSlider').length > 0) {
	var mode_list = [1, 2, 3, 7, ''];
	var m_index = 0;
	$(document).ready(function () {
		setTimeout(() => {
			getChannelSlider();
		}, 500);
		setTimeout(() => {
			getChannelSlider();
		}, 1500);
	});
	var PostChannelStart = 0;
	var PostChannelLimit = 2;
	var ChannelControlRequest = false;
	$(window).scroll(function () {
		if ($(window).scrollTop() + $(window).height() > $(document).height() - 400) {
			if (ChannelControlRequest) {
				ChannelControlRequest = false;
				getChannelSlider();
			}
		}
	});

	function getChannelSlider() {
		mode_id = mode_list[m_index];
		if (mode_id == undefined) {
			return false;
		}
		m_index++;
		var formData = new FormData();
		formData.append('start', PostChannelStart);
		formData.append('limit', PostChannelLimit);
		formData.append('user', $('.pro_share').attr('data-share-profile'));
		formData.append('mode_id', mode_id);
		manageMyAjaxPostRequestData(
			formData,
			base_url + 'channel/show_channel_slider'
		).done(function (resp) {
			if (resp.trim().length) {
				resp = JSON.parse(resp);
				if (resp.status == 1) {
					let resData = resp.data;
					$.each(resData, function (i) {
						var sliderHtml = getSliderHtml(resData[i]);

						$('#appendChannelSlider').append(sliderHtml);

						let thhs = $('div.au_artist_slider:last');
						swiperslider(thhs);

						/*if (sliderHtml != undefined) {
							AdAdsOnChannel(thhs, function () {
								setTimeout(() => {
									swiperslider(thhs);
								}, 200);
							});
						}*/ // commented by nitesh for my channel slider issue fixed

						if (
							resData[i]['videoData'] !== undefined &&
							resData[i]['videoData'].length > 0 &&
							resData[i]['videoData'].length < 10
						) {
							$('.dis_sh_btnwrap:last').hide();
						}
					});

					PostChannelStart += PostChannelLimit;

					ChannelControlRequest = true;
				}
			} else {
				ChannelControlRequest = true;
				$('html, body').animate(
					{ scrollTop: $(window).scrollTop() - 200 + 'px' },
					300
				);
			}
		});
	}
}


$(window).on('load', function () {
	var bodyheight = $(window).height();
	var M_cont_height = $('.main_contnt_wrapper').innerHeight();
	var headerheight = $('.dis_m_header_wrap').innerHeight();
	var fotterheight = $('.dis_copyright').innerHeight();
	// var Final_bodyheight = parseFloat(bodyheight) + parseFloat(headerheight);
	var innerbodyheight = parseFloat(bodyheight) - (parseFloat(headerheight) + parseFloat(fotterheight));
	$('.full_vh_foooter').css('min-height', innerbodyheight);
	$('.main_contnt_wrapper').css('margin-top', headerheight);
	$('.main_contnt_wrapper').css('padding-bottom', fotterheight);
});

$(document).ready(function () {
	$('.dis-Naccordion-list > li > .dis-Naccordion-data').hide();
	$('.dis-Naccordion-list > li.active > .dis-Naccordion-data').slideDown();
	$('.dis_accordion_header').click(function () {
		$(this).parent().find('.dis-Naccordion-data').slideToggle();
		if ($(this).parent().hasClass('active')) {
			$(this).parent().removeClass('active');
		} else {
			$(this).parent().addClass('active');
		}
		return false;
	});
});

$(document).ready(function () {
	$('.upl_box .dis_cross_sign').click(function () {
		$('.upload_playlist_Wrap').removeClass('open');
	});
	$(document).on('click', '.upl_box', function () {
		$('.upload_playlist_Wrap').toggleClass('open');
	});
	$(document).on('click', '.BtnDone', function () {
		$('.upload_playlist_Wrap').toggleClass('open');
	});
});


$(document).on('click', '.dis-toggle-password', function () {
	$(this).toggleClass('fa-eye fa-eye-slash');
	var input = $($(this).attr('toggle'));
	if (input.attr('type') == 'password') {
		input.attr('type', 'text');
	} else {
		input.attr('type', 'password');
	}
});

// On click of help scrolls to the help page
$(window).load(function () {
	setTimeout(function () {
		if ($('.dis_help_wrapper').length > 0) {
			function urlParam(name) {
				var results = new RegExp('[?&]' + name + '=([^&#]*)').exec(
					window.location.href
				);
				if (results == null) {
					return null;
				} else {
					return results[1] || 0;
				}
			}

			if (urlParam('scroll') == 1) {
				$('.enqry_dropbtn > a').trigger('click');
				$('html, body').animate(
					{
						scrollTop: $(
							'.iav_enqry_wrapper .panel-group .panel-default:first-child ol'
						).offset().top,
					},
					500
				);
				$('ol  > li').each(function (e, u) {
					console.log(e, u);
					if (e == 3) {
						let t = $(this).html();
						$(this).html('<mark>' + t + '</mark>');
					}
				});
			}
		}
	}, 500);
});

// profile page full screen cover video
/* Get into full screen */
function GoInFullscreen(element) {
	if (element.requestFullscreen) element.requestFullscreen();
	else if (element.mozRequestFullScreen) element.mozRequestFullScreen();
	else if (element.webkitRequestFullscreen) element.webkitRequestFullscreen();
	else if (element.msRequestFullscreen) element.msRequestFullscreen();
}

/* Get out of full screen */
function GoOutFullscreen() {
	if (document.exitFullscreen) document.exitFullscreen();
	else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
	else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
	else if (document.msExitFullscreen) document.msExitFullscreen();
}

/* Is currently in full screen or not */
function IsFullScreenCurrently() {
	var full_screen_element =
		document.fullscreenElement ||
		document.webkitFullscreenElement ||
		document.mozFullScreenElement ||
		document.msFullscreenElement ||
		null;

	// If no element is in full-screen
	if (full_screen_element === null) return false;
	else return true;
}

$('#popup_banner_video').on('click', function () {
	GoInFullscreen($('.au_video_popup').get(0));
});
$('#cover_banner_video').on('click', function () {
	GoOutFullscreen();
});


$(document).ready(function () {
	$('.dis_settingTogl').on('click', function (e) {
		e.stopPropagation();
		$(this).parent().toggleClass('active');
	});
	$('.dis_settingOption').on('click', function (e) {
		e.stopPropagation();
	});
	$(document).on('click', function () {
		$('.dis_pcSettingWrap ').removeClass('active');
	});
});



// new single video sidebar toggle
$(document).ready(function () {
	$(".div_SV_btn").click(function (e) {
		e.stopPropagation();
		$("body").toggleClass("open")
	});
	$(document).on('click', function () {
		$('body').removeClass("open");
	});
});




// game pass page image changes effects

$(document).ready(function () {
	if ($('.dis_gamepassPage').length) {
		var currentImageIndex = 0;

		// Function to change the image
		function changeImage() {
			currentImageIndex = (currentImageIndex + 1) % images.length;
			$('#changeImg').fadeOut(300, function () {
				// Change the image source
				$(this).attr('src', images[currentImageIndex])
					.fadeIn(300); // Fade in the new image
			});
		}

		// Change image every 5 seconds
		setInterval(changeImage, 5000);
	}
});


