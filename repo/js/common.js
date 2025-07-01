var Policy = 'allowed';
var blocklist = [
	'/watch/ZajkAwZ0Atnn',
	'/watch/ZajkAGt1ADnn',
	'/watch/ZajkAwN5BDnn',
	'/watch/ZajkAwZ0Ztnn',
	'/profile?user=kiannajay',
];

function ImageOnLoadError(_this, src1, src2) {
	_this.src = src1;
	_this.onload = function () {
		_this.onerror = null;
	};
	_this.onerror = function () {
		console.log(src2, 'src2')
		_this.src = src2;
		_this.onerror = null;
	};
}

function OpenRoute(href) {
	$('#myModal').modal('show');
	history.pushState('', '', base_url + href);
}

function IsMobileDevice() {
	let isMobile = false;
	if (
		/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(
			navigator.userAgent
		) ||
		/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
			navigator.userAgent.substr(0, 4)
		)
	) {
		isMobile = true;
	}
	return isMobile;
}

function makeid(length) {
	var result = '';
	var characters =
		'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	var charactersLength = characters.length;
	for (var i = 0; i < length; i++) {
		result += characters.charAt(Math.floor(Math.random() * charactersLength));
	}
	return result;
}

/************** success popup function STARTS ****************/

function success_popup_function(text) {
	if (!text.length) {
		text = "It's done.";
	}
	$('#conf_header').addClass('success_popup');
	$('#conf_title').text('Congratulations');
	$('#conf_text').text(text);
	$('#conf_btn').hide();
	$('#confirm_popup').modal('show');
}

/************** success popup function ENDS ****************/

/************** server error popup function STARTS ****************/

function server_error_popup_function(text) {
	if (!text.length) {
		text = 'Server error.';
	}

	$('#conf_header').addClass('error_popup');
	$('#conf_title').text('Error');
	$('#conf_text').text(text);
	$('#conf_btn').hide();
	$('#confirm_popup').modal('show');
}

/************** server error popup function ENDS ****************/

/************** server error popup function STARTS ****************/
function confirm_popup_function(text, subtext, functions) {
	$('#conf_title').text(text);
	$('#conf_text').text(subtext);
	$('#conf_header').addClass('notification_popup').removeClass('success_popup');
	$('#conf_btn').show().attr('onclick', functions).text(text);
	$('#confirm_popup').modal('show');
}
/************** server error popup function ENDS ****************/

/********************************************************************/
/************************ Main CKEDITOR Code START ********************/
/********************************************************************/
function InitializeCKeditor() {
	if (typeof CKEDITOR == 'undefined') {
		return false;
	}
	CKEDITOR.replace('ckeditor');
	CKEDITOR.config.toolbar = 'Basic';
	CKEDITOR.config.height = 200;
	CKEDITOR.config.forcePasteAsPlainText = true;
	CKEDITOR.config.forcePasteAsPlainText = 'allow-word';
	CKEDITOR.config.toolbar_Basic = [
		// { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		// { name: 'clipboard', items : [ 'PasteText','PasteFromWord' ] },
		{
			name: 'editing',
			items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker'],
		}, //, 'Scayt'
		{
			name: 'basicstyles',
			items: [
				'Bold',
				'Italic',
				'Underline',
				'Strike',
				'Subscript',
				'Superscript',
				'-',
				'RemoveFormat',
			],
		},
		{
			name: 'paragraph',
			items: [
				'NumberedList',
				'BulletedList',
				'-',
				'Outdent',
				'Indent',
				'-',
				'Blockquote',
				'CreateDiv',
				'-',
				'JustifyLeft',
				'JustifyCenter',
				'JustifyRight',
				'JustifyBlock',
				'-',
				'BidiLtr',
				'BidiRtl',
			],
		},
		{
			name: 'insert',
			items: [
				'Table',
				'HorizontalRule',
				'Smiley',
				'SpecialChar',
				'PageBreak',
				'Iframe',
			],

		},
		// { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'styles', items: ['Format', 'Font', 'FontSize'] },
		{ name: 'colors', items: ['TextColor', 'BGColor'] },
		// { name: 'document', items : [ 'Source'] },
		// { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
		// { name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] },
		// { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'links', items: ['Link'] },
		// { name: 'insert', items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
	];

	// CKEDITOR.instances.ckeditor.on('paste', function(evt) {

	// });
}
if ($('[name="ckeditor"]').length) {
	setTimeout(function () {
		InitializeCKeditor();
	}, 500);
	if (document.querySelector('#submitAboutMe')) {
		document.querySelector('#submitAboutMe').addEventListener('click', (e) => {
			e.preventDefault();
			let data = CKEDITOR.instances.ckeditor.getData();
			let formData = new FormData();
			formData.append('uc_about', JSON.stringify(data));

			manageMyAjaxPostRequestData(
				formData,
				base_url + 'dashboard/save_about_me'
			).done(function (resp) {
				resp = JSON.parse(resp);
				// CKEDITOR.instances.editor.setData(data, function(){});
				$('.EditAboutMe').removeClass('hide').html(data);
				$('.about_body').addClass('hide');
				Custom_notify('success', 'Your information has been updated');
			});
		});
	}
}
$(document).on('click', '#EditAboutMe', function () {
	$('.EditAboutMe').addClass('hide');
	$('.about_body').removeClass('hide');
});
/********************************************************************/
/************************ Main CKEDITOR Code EDN ********************/
/********************************************************************/

/************** Add to favorites OF CHANNEL Video STARTS ************************/
$(document).on('click', '.AddToFavriote', function () {
	if (user_login_id != '') {
		var _this = $(this);
		console.log(_this, '_this');
		let formData = new FormData();
		formData.append('post_id', _this.attr('data-post_id'));
		manageMyAjaxPostRequestData(
			formData,
			base_url + 'share/add_to_favorite'
		).done(function (resp) {
			let svg = '';
			if (_this.attr('data-type')) {
				svg = `<span class="wb_dd_icon">
						<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18"  x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill="#777777" d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9h-4v4h-2v-4H9V9h4V5h2v4h4z" data-original="#777777" class=""></path></g></svg>
						</span>`;
				if (resp == 0) {
					_this.html(svg + ' Add To Favorites');
					Custom_notify('success', 'Removed from favorites.');
				} else {
					_this.html(svg + ' Added To Favorites');
					Custom_notify('success', 'Added To Favorites.');
				}
			} else {
				if (resp == 0) {
					_this.find('span').html('Add To Favorites');
					_this.removeClass('active');
					Custom_notify('success', 'Removed from favorites.');
				}
				if (resp == 1) {
					_this.find('span').html('Added To Favorites');
					_this.addClass('active');
					Custom_notify('success', 'Added To Favorites.');
				}
			}
		});
	}
});
/************** Add to favorites OF CHANNEL Video END ************************/
function is_valid_json(text) {
	if (
		/^[\],:{}\s]*$/.test(
			text
				.replace(/\\["\\\/bfnrtu]/g, '@')
				.replace(
					/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
					']'
				)
				.replace(/(?:^|:|,)(?:\s*\[)+/g, '')
		)
	) {
		return true;
	} else {
		return false;
	}
}

/************** Notification  Start ************************/
$('body').on('click', '.show_notification', function (e) {
	if (user_login_id != '') {
		let ths = $(this);
		let target = ths.data('target');
		let data = {};

		$('.pro_loader').show();

		if (!ths.hasClass('open')) {
			manageMyAjaxPostRequestData(
				new FormData(),
				base_url + 'dashboard/getNotification'
			).done(function (resp) {
				if (is_valid_json(resp)) {
					resp = JSON.parse(resp);
					$(target).html(resp.data);
					//$('.NotiCount').hide();

					//if (resp.data == '<center>No notification available.</center>') {
					//	$('.ClearMyNotification').hide();
					//}
				}
			});
		}
	} else {
		$('#myModal').modal('show');
	}
});

$('.toggle_btn').click(function (e) {
	$('body').toggleClass('toggle_animation');
});

$(document).on('click', '.ClearMyNotification', function () {
	confirm_popup_function(
		'YES',
		'Are you sure you want to clear all notification ?',
		'ClearMyNotification()'
	);
});
function ClearMyNotification() {
	if (user_login_id != '') {
		$('.pro_loader').show();
		$.post('dashboard/clearNotification', function (data) {
			$('#show_notification').html(
				'<center>No Notification Available</center>'
			);
			$('#confirm_popup').modal('hide');
			$('.show_notification').addClass('open');
		});
	} else {
		$('#myModal').modal('show');
	}
}

/**************  Notification  END ************************/

/************** Make a fan STARTS ************************/

$(document).on('click', '.becomeFan', function (e) {
	if (user_login_id != '') {
		let ths = $(this);
		let user_id = ths.data('uid');
		let type = ths.data('type');

		if (user_id.toString().length > 0) {
			let formData = new FormData();
			formData.append('user_id', Number(user_id));

			manageMyAjaxPostRequestData(
				formData,
				base_url + 'dashboard/becomeFan'
			).done(function (resp) {
				let svg =
					type == 'new'
						? `<span class="dis_SV_btnIcon">
							<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M13.086 5.51563L10.3608 8.17213L11.0044 11.9241C11.0324 12.0882 10.965 12.254 10.8303 12.352C10.7541 12.4076 10.6636 12.4356 10.573 12.4356C10.5034 12.4356 10.4334 12.4189 10.3696 12.3853L6.99995 10.6138L3.63076 12.3848C3.48376 12.4627 3.30482 12.45 3.17007 12.3516C3.03532 12.2536 2.96795 12.0878 2.99595 11.9237L3.63951 8.1717L0.913884 5.51563C0.794884 5.39926 0.751572 5.22513 0.803197 5.0672C0.854822 4.90926 0.991759 4.79332 1.1567 4.76926L4.92313 4.22238L6.60751 0.809009C6.75495 0.510197 7.24495 0.510197 7.39238 0.809009L9.07676 4.22238L12.8432 4.76926C13.0081 4.79332 13.1451 4.90882 13.1967 5.0672C13.2483 5.22557 13.205 5.39882 13.086 5.51563Z" fill="#515151"/>
							</svg>
						</span>`
						: '';
				if (resp == 1) {
					ths.html(svg + ' You are a fan');
				} else {
					ths.html(svg + ' Become a fan');
				}
			});
		}
	}
});
/************** Make a fan END ************************/

/************** swiper Slider start ************************/
var swiper = [];
var swiperCount = 0;
function swiperslider(swipe) {
	var slideItemCount = swipe.find('.swiper-slide').length;

	var sliderLoop = true;
	var sliderCenteredSlides = false;

	if (slideItemCount < 6) { //allign center and loop false when item will be less than 6
		sliderLoop = false;
		swipe
			.find('.swiper-wrapper')
			.css('ustify-content', 'initial')
			.addClass('dis-add-gustify');
	}

	let swip = {
		pagination: false,
		slidesPerView: 5,
		centeredSlides: sliderCenteredSlides,
		spaceBetween: 10,
		grabCursor: false,
		autoplayDisableOnInteraction: false,
		// centerInsufficientSlides:true,
		autoplay: false, //swipe.data('autoplay'),
		loop: sliderLoop,
		breakpoints: {
			1400: {
				slidesPerView: 4,
			},
			1200: {
				slidesPerView: 3,
			},
			992: {
				slidesPerView: 3,
			},
			768: {
				slidesPerView: 2,
			},
			640: {
				slidesPerView: 2,
			},
			399: {
				slidesPerView: 1,
			},
		},
		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',
	};

	if (swipe.find('.swiper-slide').length < 7) {
		swip.autoplay = '';
	}

	swiper[swiperCount++] = new Swiper(swipe.find('.swiper-container'), swip);
}

$(document).ready(function () {
	if (
		localStorage.getItem('popup') != 1 &&
		typeof user_login_id != 'undefined' &&
		user_login_id != ''
	) {
		$('#HowIComeONDis').modal({ backdrop: 'static', keyboard: false }, 'show');
	}

	let link = $('link[type="image/png"]').remove().attr('href');
	$(
		'<link href="' + link + '" rel="shortcut icon" type="image/png" />'
	).appendTo('head');

	setTimeout(() => {
		let errImg =
			base_url +
			'repo/images/nothumb/' +
			($('body').hasClass('theme_dark') ? 'dark.jpg' : 'light.jpg');
		$('.dis_postvideo_img').each(function (item, index) {
			let obj = $(this).find('img');
			if (obj.attr('src') == base_url + 'repo/images/thumbnail.jpg') {
				obj.attr('src', errImg);
			}
		});
	}, 1000);
});


var IsCoverVideoMuted = true;
$(document).on('click', '.speaker', function (event) {
	var _this = $(this);
	if (_this.attr('data-video') == 'play_sneak_peak') {
		$('.Flexible-container > .banner_video').prop('muted', true);
		$('.Flexible-container > .speaker').addClass('mute');
		deleteCookie('popup_audio');

		if (_this.hasClass('mute')) {
			setCookie('popup_audio', false, 1);
			_this.removeClass('mute');
			$('.' + _this.attr('data-video')).prop('muted', false);
		} else {
			setCookie('popup_audio', true, 1);
			_this.addClass('mute');
			$('.' + _this.attr('data-video')).prop('muted', true);
		}
	} else if (_this.attr('data-video')) {
		if ($('.speaker').hasClass('mute')) {
			IsCoverVideoMuted = false;
			$('.speaker').removeClass('mute');
			$('.banner_video').prop('muted', false);
		} else {
			IsCoverVideoMuted = true;
			$('.speaker').addClass('mute');
			$('.banner_video').prop('muted', true);
		}
	} else if (_this.attr('data-videojs')) {
		let myPlayer = videojs.getPlayer(_this.attr('data-videojs'));
		if (myPlayer.muted()) {
			myPlayer.muted(false);
			_this.removeClass('mute');
		} else {
			myPlayer.muted(true);
			_this.addClass('mute');
		}
	}
});
function deleteCookie(cname) {
	document.cookie = cname + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
}
function setCookie(cname, cvalue, exdays) {
	const d = new Date();
	d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
	let expires = 'expires=' + d.toUTCString();
	document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}

function getCookie(cname) {
	let name = cname + '=';
	let ca = document.cookie.split(';');
	for (let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return '';
}

if ($('.speaker').length) {
	$(window).scroll(function () {
		var
			hT = $('.CoverSwiper:first')?.offset()?.top,
			hH = $('.CoverSwiper:first').outerHeight(),
			wH = $(window).height(),
			wS = $(this).scrollTop(),
			hT2 = $('.user_tab_section:first')?.offset()?.top,
			hH2 = $('.user_tab_section:first').outerHeight()
			;
		if ((wS > (hT + hH - wH) + 150) || wS >= (hT2 + hH2 - wH)) {
			// console.log('true hai');
			if (!$('.speaker').hasClass('mute')) {
				$('.speaker').addClass('mute');
				$('.banner_video').prop('muted', true);
			}
		} else {
			// console.log(IsCoverVideoMuted + ' false hai');
			if (!IsCoverVideoMuted) {
				$('.speaker').removeClass('mute');
				$('.banner_video').prop('muted', false);
			}
		}
	});
}

/************** swiper Slider END ************************/

/********************************************************************/
/************************ Main Search Code Start ********************/
/********************************************************************/

$(document).on('mouseover', '#appendHead li, #appendPage li', function (event) {
	let ths = $(this);
	ths.parent().find('.active').removeClass('active');
	let storeTarget = ths.focus().addClass('active');
});
$(document).on('click', '#appendHead li, #appendPage li', function (event) {
	let ths = $(this);
	ths.parent().hide();
	let storeTarget = ths.focus().addClass('active');
	$('.search_content').val(storeTarget.text());
	ths.parent().prev('form').submit();
	ths.parents('form').submit();
});
$(document).on('click', 'body', function (event) {
	$('#appendHead').hide();
	$('#appendPage').hide();
});

$(document).on('dblclick', '.search_content', function (event) {
	let ths = $(this);
	let appendElement = ths.data('search');
	$(appendElement).show();
});
$(document).on('keyup', '.search_content', function (event) {
	let ths = $(this);
	let storeTarget;
	let search = $.trim(ths.val());
	let key = event.keyCode;

	let appendElement = ths.data('search');
	$(appendElement).show();

	if (key == 40 || key == 38) {
		if ($(appendElement + ' li.active').length != 0) {
			if (event.keyCode == 40) {
				storeTarget = $(appendElement).find('li.active').next();
			}

			if (event.keyCode == 38) {
				storeTarget = $(appendElement).find('li.active').prev();
			}

			$(appendElement + ' li.active').removeClass('active');
			storeTarget.focus().addClass('active');
			$(this).val(storeTarget.text());
		} else {
			storeTarget = $(appendElement)
				.find('li:first')
				.focus()
				.addClass('active');
			$(this).val(storeTarget.text());
		}
	} else if (key == 8 || (key != 40 && key != 38 && key != 13)) {
		if (search.toString().length > 0) {
			var formData = new FormData();
			var url = 'search/search_content';
			formData.append('search', search);
			if ($('[name="mode_id"]').length) {
				formData.append('mode_id', $('[name="mode_id"]').val());
			}
			if ($('[name="genre_id"]').length) {
				formData.append('genre_id', $('[name="genre_id"]').val());
			}

			if ($('[data-search="#appendUl"]').is(':focus')) {
				url = $('[data-search="#appendUl"]').attr('data-api_url');
				formData.append('keywords', search);
				formData.append(
					'searchType',
					$('[data-search="#appendUl"]').attr('data-searchType')
				);
				formData.append(
					'mode',
					$('[data-search="#appendUl"]').attr('data-mode')
				);
				formData.append(
					'U_B_C_id',
					$('[data-search="#appendUl"]').attr('data-post')
				);
				formData.append('my_user_uname', user_uname);
				formData.append('publish_status', $('#complete_status').val());
			}
			manageMyAjaxPostRequestData(formData, base_url + url).done(function (
				resp
			) {
				resp = JSON.parse(resp);
				if (resp.length > 0 && search.length > 0) {
					let list = '';

					if ($('[data-search="#appendUl"]').is(':focus')) {
						$.each(resp, function (key, val) {
							let link = '';
							if (val.type == 'ar_title') {
								link = base_url + 'article/' + val.ar_id + '/' + val.ar_slug;
							}
							if (val.type == 'ar_user_name') {
								link = base_url + 'article?user=' + val.ar_id;
							}
							if (val.type == 'ar_tag') {
								link = base_url + 'article?tag=' + val.value;
							}

							list +=
								'<li data-link="' +
								link +
								'" class="list-group-item list-group-item-action">' +
								val.value +
								'</li>';
						});
					} else {
						for (let i = 0; i < resp.length; i++) {
							list +=
								'<li class="list-group-item list-group-item-action">' +
								resp[i] +
								'</li>';
						}
					}
					$(appendElement).html(list);
				} else {
					$(appendElement).html(
						'<li class="list-group-item list-group-item-action">No data found</li>'
					);
				}
			});
		} else {
			$(appendElement).hide();
		}
	} else if (key == 13) {
		$(appendElement).hide();
	}
	return;
});
$('.close_popup').click(function () {
	var popup = $(this).attr('data-parent');
	$('#' + popup).modal('hide');
});

/********************************************************************/
/************************ Main Search Code End ********************/
/********************************************************************/

/********************************************************************/
/************************ Preview Rich Link Code Start ********************/
/********************************************************************/
function FetchUrl(url) {
	return new Promise(function (resolve, reject) {
		// $.ajax({url: 'https://cors-anywhere.herokuapp.com/' + url}).
		$.ajax({
			method: 'POST',
			url: base_url + 'node/metadata',
			data: { url: url },
		})
			.done(function (meta) {
				return resolve(meta);
			})
			.fail(function (e) {
				return reject(e);
			});
	});
}

function richLinkCode(_th) {
	if (_th !== 'undefined') {
		let links = urlify(_th.text());
		if (links && links.length) {
			let url = links[0];
			// featchUrlData(url).then(function(html){
			FetchUrl(url).then(function (html) {
				let pathArray = url.split('/');
				let orign = pathArray[0] + '//' + pathArray[2];

				let tit = html.title ? html.title : '';
				let img = html.image ? html.image : html.src;
				let des = html.description
					? html.description
					: html.Description
						? html.Description
						: html['twitter:description']
							? html['twitter:description']
							: html.keywords;

				if (des && des.length > 150) {
					des = des ? des.slice(0, 150) + '.....' : '';
				}

				let richPrv =
					`<div class="linkpreview_img">
										<img src="` +
					img +
					`" class="img-reponsive" alt="" onerror= "this.onerror=null;this.src='` +
					orign +
					img +
					`'"; loading="lazy">
									</div>
									<div class="linkpreview_data">
										<h3 class="linkpreview_title">` +
					tit +
					`</h3>
										<p class="linkpreview_des">` +
					des +
					`</p>
										<span class="linkpreview_link">` +
					orign +
					`</span>
									</div>`;
				_th.next('.post_linkpreview').removeClass('hide').html(richPrv);
				_th.next('.post_linkpreview').attr('href', url);
			});
		}
	}
}

function richLinkCodeOld(_th) {
	if (_th !== 'undefined') {
		let links = urlify(_th.text());
		if (links && links.length) {
			let url = links[0];
			featchUrlData(url).then(function (html) {
				let pathArray = url.split('/');
				let orign = pathArray[0] + '//' + pathArray[2];

				let tit = html.title ? html.title : '';
				let img = html.image ? html.image : html.src;
				let des = html.description
					? html.description
					: html.Description
						? html.Description
						: html['twitter:description']
							? html['twitter:description']
							: html.keywords;

				let richPrv =
					`<div class="linkpreview_img">
										<img src="` +
					img +
					`" class="img-reponsive" alt="" onerror= "this.onerror=null;this.src='` +
					orign +
					img +
					`'"; loading="lazy">
									</div>
									<div class="linkpreview_data">
										<h3 class="linkpreview_title">` +
					tit +
					`</h3>
										<p class="linkpreview_des">` +
					des +
					`</p>
										<span class="linkpreview_link">` +
					orign +
					`</span>
									</div>`;
				_th.next('.post_linkpreview').removeClass('hide').html(richPrv);
				_th.next('.post_linkpreview').attr('href', url);
			});
		}
	}
}

function urlify(text) {
	return text.match(/\bhttps?:\/\/\S+/gi);
}

function featchUrlData(url) {
	return new Promise(async function (resolve, reject) {
		url = url.replace('https', 'http');
		return await FetchHtml(url)
			.then(function (html) {
				html = getMetaData(html);
				return resolve(html);
			})
			.catch(function (e) {
				url = url.replace('http', 'https');
				FetchHtml(url)
					.then(function (html) {
						html = getMetaData(html);
						return resolve(html);
					})
					.catch(function (e) {
						return reject(e);
					});
			});
	});
}

function FetchHtml(url) {
	return new Promise(function (resolve, reject) {
		// $.ajax({url: 'https://cors-anywhere.herokuapp.com/' + url}).
		$.ajax({ url: 'https://cors.bridged.cc/' + url })
			.done(function (html) {
				return resolve($(html));
			})
			.fail(function (e) {
				return reject(e);
			});
	});
}
function getMetaData(html) {
	var rich = [
		'description',
		'Description',
		'twitter:description',
		'keywords',
		'Keywords',
		'image',
		'src',
		'title',
	];
	var datas = [];
	for (i = 0; i < rich.length; i++) {
		if (rich[i] == 'src')
			datas[rich[i]] = html.find('img').attr('src') || false;
		else datas[rich[i]] = getMetaContent(html, rich[i]) || false;

		if (rich.length - 1 == i) {
			return datas;
		}
	}
}
function getMetaContent(html, name) {
	return html
		.filter((index, tag) => tag && tag.name && tag.name == name)
		.attr('content');
}

/********************************************************************/
/************************ Preview Rich Link Code End ********************/
/********************************************************************/

/********************************************************************/
/************************ Emoji Picker Code Start ********************/
/********************************************************************/
// if($('._EmojiPicker').length){
var smile =
	'😀 😃 😄 😁 😆 😅 😂 🤣 ☺️ 😊 😇 🙂 🙃 😉 😌 😍 🥰 😘 😗 😙 😚 😋 😛 😝 😜 🤪 🤨 🧐 🤓 😎 🤩 🥳 😏 😒 😞 😔 😟 😕 🙁 ☹️ 😣 😖 😫 😩 🥺 😢 😭 😤 😠 😡 🤬 🤯 😳 🥵 🥶 😱 😨 😰 😥 😓 🤗 🤔 🤭 🤫 🤥 😶 😐 😑 😬 🙄 😯 😦 😧 😮 😲 🥱 😴 🤤 😪 😵 🤐 🥴 🤢 🤮 🤧 😷 🤒 🤕 🤑 🤠 😈 👿 👹 👺 🤡 💩 👻 💀 ☠️ 👽 👾 🤖 🎃 😺 😸 😹 😻 😼 😽 🙀 😿 😾';
var people =
	'👶 🧒 👦 👧 🧑 👱 👨 🧔 👨‍🦰 👨‍🦱 👨‍🦳 👨‍🦲 👩 👩‍🦰 🧑‍🦰 👩‍🦱 🧑‍🦱 👩‍🦳 🧑‍🦳 👩‍🦲 🧑‍🦲 👱‍♀️ 👱‍♂️ 🧓 👴 👵 🙍 🙍‍♂️ 🙍‍♀️ 🙎 🙎‍♂️ 🙎‍♀️ 🙅 🙅‍♂️ 🙅‍♀️ 🙆 🙆‍♂️ 🙆‍♀️ 💁 💁‍♂️ 💁‍♀️ 🙋 🙋‍♂️ 🙋‍♀️ 🧏 🧏‍♂️ 🧏‍♀️ 🙇 🙇‍♂️ 🙇‍♀️ 🤦 🤦‍♂️ 🤦‍♀️ 🤷 🤷‍♂️ 🤷‍♀️ 🧑‍⚕️ 👨‍⚕️ 👩‍⚕️ 🧑‍🎓 👨‍🎓 👩‍🎓 🧑‍🏫 👨‍🏫 👩‍🏫 🧑‍⚖️ 👨‍⚖️ 👩‍⚖️ 🧑‍🌾 👨‍🌾 👩‍🌾 🧑‍🍳 👨‍🍳 👩‍🍳 🧑‍🔧 👨‍🔧 👩‍🔧 🧑‍🏭 👨‍🏭 👩‍🏭 🧑‍💼 👨‍💼 👩‍💼 🧑‍🔬 👨‍🔬 👩‍🔬 🧑‍💻 👨‍💻 👩‍💻 🧑‍🎤 👨‍🎤 👩‍🎤 🧑‍🎨 👨‍🎨 👩‍🎨 🧑‍✈️ 👨‍✈️ 👩‍✈️ 🧑‍🚀 👨‍🚀 👩‍🚀 🧑‍🚒 👨‍🚒 👩‍🚒 👮 👮‍♂️ 👮‍♀️ 🕵 🕵️‍♂️ 🕵️‍♀️ 💂 💂‍♂️ 💂‍♀️ 👷 👷‍♂️ 👷‍♀️ 🤴 👸 👳 👳‍♂️ 👳‍♀️ 👲 🧕 🤵 👰 🤰 🤱 👼 🎅 🤶 🦸 🦸‍♂️ 🦸‍♀️ 🦹 🦹‍♂️ 🦹‍♀️ 🧙 🧙‍♂️ 🧙‍♀️ 🧚 🧚‍♂️ 🧚‍♀️ 🧛 🧛‍♂️ 🧛‍♀️ 🧜 🧜‍♂️ 🧜‍♀️ 🧝 🧝‍♂️ 🧝‍♀️ 🧞 🧞‍♂️ 🧞‍♀️ 🧟 🧟‍♂️ 🧟‍♀️ 💆 💆‍♂️ 💆‍♀️ 💇 💇‍♂️ 💇‍♀️ 🚶 🚶‍♂️ 🚶‍♀️ 🧍 🧍‍♂️ 🧍‍♀️ 🧎 🧎‍♂️ 🧎‍♀️ 🧑‍🦯 👨‍🦯 👩‍🦯 🧑‍🦼 👨‍🦼 👩‍🦼 🧑‍🦽 👨‍🦽 👩‍🦽 🏃 🏃‍♂️ 🏃‍♀️ 💃 🕺 🕴 👯 👯‍♂️ 👯‍♀️ 🧖 🧖‍♂️ 🧖‍♀️ 🧘 🧑‍🤝‍🧑 👭 👫 👬 💏 👨‍❤️‍💋‍👨 👩‍❤️‍💋‍👩 💑 👨‍❤️‍👨 👩‍❤️‍👩 👪 👨‍👩‍👦 👨‍👩‍👧 👨‍👩‍👧‍👦 👨‍👩‍👦‍👦 👨‍👩‍👧‍👧 👨‍👨‍👦 👨‍👨‍👧 👨‍👨‍👧‍👦 👨‍👨‍👦‍👦 👨‍👨‍👧‍👧 👩‍👩‍👦 👩‍👩‍👧 👩‍👩‍👧‍👦 👩‍👩‍👦‍👦 👩‍👩‍👧‍👧 👨‍👦 👨‍👦‍👦 👨‍👧 👨‍👧‍👦 👨‍👧‍👧 👩‍👦 👩‍👦‍👦 👩‍👧 👩‍👧‍👦 👩‍👧‍👧 🗣 👤 👥 👣';
var clothing =
	'🧳 🌂 ☂️ 🧵 🧶 👓 🕶 🥽 🥼 🦺 👔 👕 👖 🧣 🧤 🧥 🧦 👗 👘 🥻 🩱 🩲 🩳 👙 👚 👛 👜 👝 🎒 👞 👟 🥾 🥿 👠 👡 🩰 👢 👑 👒 🎩 🎓 🧢 ⛑ 💄 💍 💼';

var gestures =
	`👋 🤚 🖐 ✋ 🖖 👌 🤏 ✌️ 🤞 🤟 🤘 🤙 👈 👉 👆 🖕 👇 ☝️ 👍 👎 ✊ 👊 🤛 🤜 👏 🙌 👐 🤲 🤝 🙏 ✍️ 💅 🤳 💪 🦾 🦵 🦿 🦶 👂 🦻 👃 🧠 🦷 🦴 👀 👁 👅 👄 💋 🩸 ` +
	`👋🏻 🤚🏻 🖐🏻 ✋🏻 🖖🏻 👌🏻 🤏🏻 ✌🏻 🤞🏻 🤟🏻 🤘🏻 🤙🏻 👈🏻 👉🏻 👆🏻 🖕🏻 👇🏻 ☝🏻 👍🏻 👎🏻 ✊🏻 👊🏻 🤛🏻 🤜🏻 👏🏻 🙌🏻 👐🏻 🤲🏻 🙏🏻 ✍🏻 💅🏻 🤳🏻 💪🏻 🦵🏻 🦶🏻 👂🏻 🦻🏻 👃🏻 👶🏻 🧒🏻 👦🏻 👧🏻 🧑🏻 👨🏻 👩🏻 🧑🏻‍🦱 👨🏻‍🦱 👩🏻‍🦱 🧑🏻‍🦰 👨🏻‍🦰 👩🏻‍🦰 👱🏻 👱🏻‍♂️ 👱🏻‍♀️ 🧑🏻‍🦳 👩🏻‍🦳 👨🏻‍🦳 🧑🏻‍🦲 👨🏻‍🦲 👩🏻‍🦲 🧔🏻 🧓🏻 👴🏻 👵🏻 🙍🏻 🙍🏻‍♂️ 🙍🏻‍♀️ 🙎🏻 🙎🏻‍♂️ 🙎🏻‍♀️ 🙅🏻 🙅🏻‍♂️ 🙅🏻‍♀️ 🙆🏻 🙆🏻‍♂️ 🙆🏻‍♀️ 💁🏻 💁🏻‍♂️ 💁🏻‍♀️ 🙋🏻 🙋🏻‍♂️ 🙋🏻‍♀️ 🧏🏻 🧏🏻‍♂️ 🧏🏻‍♀️ 🙇🏻 🙇🏻‍♂️ 🙇🏻‍♀️ 🤦🏻 🤦🏻‍♂️ 🤦🏻‍♀️ 🤷🏻 🤷🏻‍♂️ 🤷🏻‍♀️ 🧑🏻‍⚕️ 👨🏻‍⚕️ 👩🏻‍⚕️ 🧑🏻‍🎓 👨🏻‍🎓 👩🏻‍🎓 🧑🏻‍🏫 👨🏻‍🏫 👩🏻‍🏫 🧑🏻‍⚖️ 👨🏻‍⚖️ 👩🏻‍⚖️ 🧑🏻‍🌾 👨🏻‍🌾 👩🏻‍🌾 🧑🏻‍🍳 👨🏻‍🍳 👩🏻‍🍳 🧑🏻‍🔧 👨🏻‍🔧 👩🏻‍🔧 🧑🏻‍🏭 👨🏻‍🏭 👩🏻‍🏭 🧑🏻‍💼 👨🏻‍💼 👩🏻‍💼 🧑🏻‍🔬 👨🏻‍🔬 👩🏻‍🔬 🧑🏻‍💻 👨🏻‍💻 👩🏻‍💻 🧑🏻‍🎤 👨🏻‍🎤 👩🏻‍🎤 🧑🏻‍🎨 👨🏻‍🎨 👩🏻‍🎨 🧑🏻‍✈️ 👨🏻‍✈️ 👩🏻‍✈️ 🧑🏻‍🚀 👨🏻‍🚀 👩🏻‍🚀 🧑🏻‍🚒 👨🏻‍🚒 👩🏻‍🚒 👮🏻 👮🏻‍♂️ 👮🏻‍♀️ 🕵🏻 🕵🏻‍♂️ 🕵🏻‍♀️ 💂🏻 💂🏻‍♂️ 💂🏻‍♀️ 👷🏻 👷🏻‍♂️ 👷🏻‍♀️ 🤴🏻 👸🏻 👳🏻 👳🏻‍♂️ 👳🏻‍♀️ 👲🏻 🧕🏻 🤵🏻 👰🏻 🤰🏻 🤱🏻 👼🏻 🎅🏻 🤶🏻 🦸🏻 🦸🏻‍♂️ 🦸🏻‍♀️ 🦹🏻 🦹🏻‍♂️ 🦹🏻‍♀️ 🧙🏻 🧙🏻‍♂️ 🧙🏻‍♀️ 🧚🏻 🧚🏻‍♂️ 🧚🏻‍♀️ 🧛🏻 🧛🏻‍♂️ 🧛🏻‍♀️ 🧜🏻 🧜🏻‍♂️ 🧜🏻‍♀️ 🧝🏻 🧝🏻‍♂️ 🧝🏻‍♀️ 💆🏻 💆🏻‍♂️ 💆🏻‍♀️ 💇🏻 💇🏻‍♂️ 💇🏻‍♀️ 🚶🏻 🚶🏻‍♂️ 🚶🏻‍♀️ 🧍🏻 🧍🏻‍♂️ 🧍🏻‍♀️ 🧎🏻 🧎🏻‍♂️ 🧎🏻‍♀️ 🧑🏻‍🦯 👨🏻‍🦯 👩🏻‍🦯 🧑🏻‍🦼 👨🏻‍🦼 👩🏻‍🦼 🧑🏻‍🦽 👨🏻‍🦽 👩🏻‍🦽 🏃🏻 🏃🏻‍♂️ 🏃🏻‍♀️ 💃🏻 🕺🏻 🕴🏻 🧖🏻 🧖🏻‍♂️ 🧖🏻‍♀️ 🧗🏻 🧗🏻‍♂️ 🧗🏻‍♀️ 🏇🏻 🏂🏻 🏌🏻 🏌🏻‍♂️ 🏌🏻‍♀️ 🏄🏻 🏄🏻‍♂️ 🏄🏻‍♀️ 🚣🏻 🚣🏻‍♂️ 🚣🏻‍♀️ 🏊🏻 🏊🏻‍♂️ 🏊🏻‍♀️ ⛹🏻 ⛹🏻‍♂️ ⛹🏻‍♀️ 🏋🏻 🏋🏻‍♂️ 🏋🏻‍♀️ 🚴🏻 🚴🏻‍♂️ 🚴🏻‍♀️ 🚵🏻 🚵🏻‍♂️ 🚵🏻‍♀️ 🤸🏻 🤸🏻‍♂️ 🤸🏻‍♀️ 🤽🏻 🤽🏻‍♂️ 🤽🏻‍♀️ 🤾🏻 🤾🏻‍♂️ 🤾🏻‍♀️ 🤹🏻 🤹🏻‍♂️ 🤹🏻‍♀️ 🧘🏻 🧘🏻‍♂️ 🧘🏻‍♀️ 🛀🏻 🛌🏻 🧑🏻‍🤝‍🧑🏻 👬🏻 👭🏻 👫🏻 ` +
	`👋🏼 🤚🏼 🖐🏼 ✋🏼 🖖🏼 👌🏼 🤏🏼 ✌🏼 🤞🏼 🤟🏼 🤘🏼 🤙🏼 👈🏼 👉🏼 👆🏼 🖕🏼 👇🏼 ☝🏼 👍🏼 👎🏼 ✊🏼 👊🏼 🤛🏼 🤜🏼 👏🏼 🙌🏼 👐🏼 🤲🏼 🙏🏼 ✍🏼 💅🏼 🤳🏼 💪🏼 🦵🏼 🦶🏼 👂🏼 🦻🏼 👃🏼 👶🏼 🧒🏼 👦🏼 👧🏼 🧑🏼 👨🏼 👩🏼 🧑🏼‍🦱 👨🏼‍🦱 👩🏼‍🦱 🧑🏼‍🦰 👨🏼‍🦰 👩🏼‍🦰 👱🏼 👱🏼‍♂️ 👱🏼‍♀️ 🧑🏼‍🦳 👨🏼‍🦳 👩🏼‍🦳 🧑🏼‍🦲 👨🏼‍🦲 👩🏼‍🦲 🧔🏼 🧓🏼 👴🏼 👵🏼 🙍🏼 🙍🏼‍♂️ 🙍🏼‍♀️ 🙎🏼 🙎🏼‍♂️ 🙎🏼‍♀️ 🙅🏼 🙅🏼‍♂️ 🙅🏼‍♀️ 🙆🏼 🙆🏼‍♂️ 🙆🏼‍♀️ 💁🏼 💁🏼‍♂️ 💁🏼‍♀️ 🙋🏼 🙋🏼‍♂️ 🙋🏼‍♀️ 🧏🏼 🧏🏼‍♂️ 🧏🏼‍♀️ 🙇🏼 🙇🏼‍♂️ 🙇🏼‍♀️ 🤦🏼 🤦🏼‍♂️ 🤦🏼‍♀️ 🤷🏼 🤷🏼‍♂️ 🤷🏼‍♀️ 🧑🏼‍⚕️ 👨🏼‍⚕️ 👩🏼‍⚕️ 🧑🏼‍🎓 👨🏼‍🎓 👩🏼‍🎓 🧑🏼‍🏫 👨🏼‍🏫 👩🏼‍🏫 🧑🏼‍⚖️ 👨🏼‍⚖️ 👩🏼‍⚖️ 🧑🏼‍🌾 👨🏼‍🌾 👩🏼‍🌾 🧑🏼‍🍳 👨🏼‍🍳 👩🏼‍🍳 🧑🏼‍🔧 👨🏼‍🔧 👩🏼‍🔧 🧑🏼‍🏭 👨🏼‍🏭 👩🏼‍🏭 🧑🏼‍💼 👨🏼‍💼 👩🏼‍💼 🧑🏼‍🔬 👨🏼‍🔬 👩🏼‍🔬 🧑🏼‍💻 👨🏼‍💻 👩🏼‍💻 🧑🏼‍🎤 👨🏼‍🎤 👩🏼‍🎤 🧑🏼‍🎨 👨🏼‍🎨 👩🏼‍🎨 🧑🏼‍✈️ 👨🏼‍✈️ 👩🏼‍✈️ 🧑🏼‍🚀 👨🏼‍🚀 👩🏼‍🚀 🧑🏼‍🚒 👨🏼‍🚒 👩🏼‍🚒 👮🏼 👮🏼‍♂️ 👮🏼‍♀️ 🕵🏼 🕵🏼‍♂️ 🕵🏼‍♀️ 💂🏼 💂🏼‍♂️ 💂🏼‍♀️ 👷🏼 👷🏼‍♂️ 👷🏼‍♀️ 🤴🏼 👸🏼 👳🏼 👳🏼‍♂️ 👳🏼‍♀️ 👲🏼 🧕🏼 🤵🏼 👰🏼 🤰🏼 🤱🏼 👼🏼 🎅🏼 🤶🏼 🦸🏼 🦸🏼‍♂️ 🦸🏼‍♀️ 🦹🏼 🦹🏼‍♂️ 🦹🏼‍♀️ 🧙🏼 🧙🏼‍♂️ 🧙🏼‍♀️ 🧚🏼 🧚🏼‍♂️ 🧚🏼‍♀️ 🧛🏼 🧛🏼‍♂️ 🧛🏼‍♀️ 🧜🏼 🧜🏼‍♂️ 🧜🏼‍♀️ 🧝🏼 🧝🏼‍♂️ 🧝🏼‍♀️ 💆🏼 💆🏼‍♂️ 💆🏼‍♀️ 💇🏼 💇🏼‍♂️ 💇🏼‍♀️ 🚶🏼 🚶🏼‍♂️ 🚶🏼‍♀️ 🧍🏼 🧍🏼‍♂️ 🧍🏼‍♀️ 🧎🏼 🧎🏼‍♂️ 🧎🏼‍♀️ 🧑🏼‍🦯 👨🏼‍🦯 👩🏼‍🦯 🧑🏼‍🦼 👨🏼‍🦼 👩🏼‍🦼 🧑🏼‍🦽 👨🏼‍🦽 👩🏼‍🦽 🏃🏼 🏃🏼‍♂️ 🏃🏼‍♀️ 💃🏼 🕺🏼 🕴🏼 🧖🏼 🧖🏼‍♂️ 🧖🏼‍♀️ 🧗🏼 🧗🏼‍♂️ 🧗🏼‍♀️ 🏇🏼 🏂🏼 🏌🏼 🏌🏼‍♂️ 🏌🏼‍♀️ 🏄🏼 🏄🏼‍♂️ 🏄🏼‍♀️ 🚣🏼 🚣🏼‍♂️ 🚣🏼‍♀️ 🏊🏼 🏊🏼‍♂️ 🏊🏼‍♀️ ⛹🏼 ⛹🏼‍♂️ ⛹🏼‍♀️ 🏋🏼 🏋🏼‍♂️ 🏋🏼‍♀️ 🚴🏼 🚴🏼‍♂️ 🚴🏼‍♀️ 🚵🏼 🚵🏼‍♂️ 🚵🏼‍♀️ 🤸🏼 🤸🏼‍♂️ 🤸🏼‍♀️ 🤽🏼 🤽🏼‍♂️ 🤽🏼‍♀️ 🤾🏼 🤾🏼‍♂️ 🤾🏼‍♀️ 🤹🏼 🤹🏼‍♂️ 🤹🏼‍♀️ 🧘🏼 🧘🏼‍♂️ 🧘🏼‍♀️ 🛀🏼 🛌🏼 🧑🏼‍🤝‍🧑🏼 👬🏼 👭🏼 👫🏼 ` +
	`👋🏽 🤚🏽 🖐🏽 ✋🏽 🖖🏽 👌🏽 🤏🏽 ✌🏽 🤞🏽 🤟🏽 🤘🏽 🤙🏽 👈🏽 👉🏽 👆🏽 🖕🏽 👇🏽 ☝🏽 👍🏽 👎🏽 ✊🏽 👊🏽 🤛🏽 🤜🏽 👏🏽 🙌🏽 👐🏽 🤲🏽 🙏🏽 ✍🏽 💅🏽 🤳🏽 💪🏽 🦵🏽 🦶🏽 👂🏽 🦻🏽 👃🏽 👶🏽 🧒🏽 👦🏽 👧🏽 🧑🏽 👨🏽 👩🏽 🧑🏽‍🦱 👨🏽‍🦱 👩🏽‍🦱 🧑🏽‍🦰 👨🏽‍🦰 👩🏽‍🦰 👱🏽 👱🏽‍♂️ 👱🏽‍♀️ 🧑🏽‍🦳 👨🏽‍🦳 👩🏽‍🦳 🧑🏽‍🦲 👨🏽‍🦲 👩🏽‍🦲 🧔🏽 🧓🏽 👴🏽 👵🏽 🙍🏽 🙍🏽‍♂️ 🙍🏽‍♀️ 🙎🏽 🙎🏽‍♂️ 🙎🏽‍♀️ 🙅🏽 🙅🏽‍♂️ 🙅🏽‍♀️ 🙆🏽 🙆🏽‍♂️ 🙆🏽‍♀️ 💁🏽 💁🏽‍♂️ 💁🏽‍♀️ 🙋🏽 🙋🏽‍♂️ 🙋🏽‍♀️ 🧏🏽 🧏🏽‍♂️ 🧏🏽‍♀️ 🙇🏽 🙇🏽‍♂️ 🙇🏽‍♀️ 🤦🏽 🤦🏽‍♂️ 🤦🏽‍♀️ 🤷🏽 🤷🏽‍♂️ 🤷🏽‍♀️ 🧑🏽‍⚕️ 👨🏽‍⚕️ 👩🏽‍⚕️ 🧑🏽‍🎓 👨🏽‍🎓 👩🏽‍🎓 🧑🏽‍🏫 👨🏽‍🏫 👩🏽‍🏫 🧑🏽‍⚖️ 👨🏽‍⚖️ 👩🏽‍⚖️ 🧑🏽‍🌾 👨🏽‍🌾 👩🏽‍🌾 🧑🏽‍🍳 👨🏽‍🍳 👩🏽‍🍳 🧑🏽‍🔧 👨🏽‍🔧 👩🏽‍🔧 🧑🏽‍🏭 👨🏽‍🏭 👩🏽‍🏭 🧑🏽‍💼 👨🏽‍💼 👩🏽‍💼 🧑🏽‍🔬 👨🏽‍🔬 👩🏽‍🔬 🧑🏽‍💻 👨🏽‍💻 👩🏽‍💻 🧑🏽‍🎤 👨🏽‍🎤 👩🏽‍🎤 🧑🏽‍🎨 👨🏽‍🎨 👩🏽‍🎨 🧑🏽‍✈️ 👨🏽‍✈️ 👩🏽‍✈️ 🧑🏽‍🚀 👨🏽‍🚀 👩🏽‍🚀 🧑🏽‍🚒 👨🏽‍🚒 👩🏽‍🚒 👮🏽 👮🏽‍♂️ 👮🏽‍♀️ 🕵🏽 🕵🏽‍♂️ 🕵🏽‍♀️ 💂🏽 💂🏽‍♂️ 💂🏽‍♀️ 👷🏽 👷🏽‍♂️ 👷🏽‍♀️ 🤴🏽 👸🏽 👳🏽 👳🏽‍♂️ 👳🏽‍♀️ 👲🏽 🧕🏽 🤵🏽 👰🏽 🤰🏽 🤱🏽 👼🏽 🎅🏽 🤶🏽 🦸🏽 🦸🏽‍♂️ 🦸🏽‍♀️ 🦹🏽 🦹🏽‍♂️ 🦹🏽‍♀️ 🧙🏽 🧙🏽‍♂️ 🧙🏽‍♀️ 🧚🏽 🧚🏽‍♂️ 🧚🏽‍♀️ 🧛🏽 🧛🏽‍♂️ 🧛🏽‍♀️ 🧜🏽 🧜🏽‍♂️ 🧜🏽‍♀️ 🧝🏽 🧝🏽‍♂️ 🧝🏽‍♀️ 💆🏽 💆🏽‍♂️ 💆🏽‍♀️ 💇🏽 💇🏽‍♂️ 💇🏽‍♀️ 🚶🏽 🚶🏽‍♂️ 🚶🏽‍♀️ 🧍🏽 🧍🏽‍♂️ 🧍🏽‍♀️ 🧎🏽 🧎🏽‍♂️ 🧎🏽‍♀️ 🧑🏽‍🦯 👨🏽‍🦯 👩🏽‍🦯 🧑🏽‍🦼 👨🏽‍🦼 👩🏽‍🦼 🧑🏽‍🦽 👨🏽‍🦽 👩🏽‍🦽 🏃🏽 🏃🏽‍♂️ 🏃🏽‍♀️ 💃🏽 🕺🏽 🕴🏽 🧖🏽 🧖🏽‍♂️ 🧖🏽‍♀️ 🧗🏽 🧗🏽‍♂️ 🧗🏽‍♀️ 🏇🏽 🏂🏽 🏌🏽 🏌🏽‍♂️ 🏌🏽‍♀️ 🏄🏽 🏄🏽‍♂️ 🏄🏽‍♀️ 🚣🏽 🚣🏽‍♂️ 🚣🏽‍♀️ 🏊🏽 🏊🏽‍♂️ 🏊🏽‍♀️ ⛹🏽 ⛹🏽‍♂️ ⛹🏽‍♀️ 🏋🏽 🏋🏽‍♂️ 🏋🏽‍♀️ 🚴🏽 🚴🏽‍♂️ 🚴🏽‍♀️ 🚵🏽 🚵🏽‍♂️ 🚵🏽‍♀️ 🤸🏽 🤸🏽‍♂️ 🤸🏽‍♀️ 🤽🏽 🤽🏽‍♂️ 🤽🏽‍♀️ 🤾🏽 🤾🏽‍♂️ 🤾🏽‍♀️ 🤹🏽 🤹🏽‍♂️ 🤹🏽‍♀️ 🧘🏽 🧘🏽‍♂️ 🧘🏽‍♀️ 🛀🏽 🛌🏽 🧑🏽‍🤝‍🧑🏽 👬🏽 👭🏽 👫🏽 ` +
	`👋🏾 🤚🏾 🖐🏾 ✋🏾 🖖🏾 👌🏾 🤏🏾 ✌🏾 🤞🏾 🤟🏾 🤘🏾 🤙🏾 👈🏾 👉🏾 👆🏾 🖕🏾 👇🏾 ☝🏾 👍🏾 👎🏾 ✊🏾 👊🏾 🤛🏾 🤜🏾 👏🏾 🙌🏾 👐🏾 🤲🏾 🙏🏾 ✍🏾 💅🏾 🤳🏾 💪🏾 🦵🏾 🦶🏾 👂🏾 🦻🏾 👃🏾 👶🏾 🧒🏾 👦🏾 👧🏾 🧑🏾 👨🏾 👩🏾 🧑🏾‍🦱 👨🏾‍🦱 👩🏾‍🦱 🧑🏾‍🦰 👨🏾‍🦰 👩🏾‍🦰 👱🏾 👱🏾‍♂️ 👱🏾‍♀️ 🧑🏾‍🦳 👨🏾‍🦳 👩🏾‍🦳 🧑🏾‍🦲 👨🏾‍🦲 👩🏾‍🦲 🧔🏾 🧓🏾 👴🏾 👵🏾 🙍🏾 🙍🏾‍♂️ 🙍🏾‍♀️ 🙎🏾 🙎🏾‍♂️ 🙎🏾‍♀️ 🙅🏾 🙅🏾‍♂️ 🙅🏾‍♀️ 🙆🏾 🙆🏾‍♂️ 🙆🏾‍♀️ 💁🏾 💁🏾‍♂️ 💁🏾‍♀️ 🙋🏾 🙋🏾‍♂️ 🙋🏾‍♀️ 🧏🏾 🧏🏾‍♂️ 🧏🏾‍♀️ 🙇🏾 🙇🏾‍♂️ 🙇🏾‍♀️ 🤦🏾 🤦🏾‍♂️ 🤦🏾‍♀️ 🤷🏾 🤷🏾‍♂️ 🤷🏾‍♀️ 🧑🏾‍⚕️ 👨🏾‍⚕️ 👩🏾‍⚕️ 🧑🏾‍🎓 👨🏾‍🎓 👩🏾‍🎓 🧑🏾‍🏫 👨🏾‍🏫 👩🏾‍🏫 🧑🏾‍⚖️ 👨🏾‍⚖️ 👩🏾‍⚖️ 🧑🏾‍🌾 👨🏾‍🌾 👩🏾‍🌾 🧑🏾‍🍳 👨🏾‍🍳 👩🏾‍🍳 🧑🏾‍🔧 👨🏾‍🔧 👩🏾‍🔧 🧑🏾‍🏭 👨🏾‍🏭 👩🏾‍🏭 🧑🏾‍💼 👨🏾‍💼 👩🏾‍💼 🧑🏾‍🔬 👨🏾‍🔬 👩🏾‍🔬 🧑🏾‍💻 👨🏾‍💻 👩🏾‍💻 🧑🏾‍🎤 👨🏾‍🎤 👩🏾‍🎤 🧑🏾‍🎨 👨🏾‍🎨 👩🏾‍🎨 🧑🏾‍✈️ 👨🏾‍✈️ 👩🏾‍✈️ 🧑🏾‍🚀 👨🏾‍🚀 👩🏾‍🚀 🧑🏾‍🚒 👨🏾‍🚒 👩🏾‍🚒 👮🏾 👮🏾‍♂️ 👮🏾‍♀️ 🕵🏾 🕵🏾‍♂️ 🕵🏾‍♀️ 💂🏾 💂🏾‍♂️ 💂🏾‍♀️ 👷🏾 👷🏾‍♂️ 👷🏾‍♀️ 🤴🏾 👸🏾 👳🏾 👳🏾‍♂️ 👳🏾‍♀️ 👲🏾 🧕🏾 🤵🏾 👰🏾 🤰🏾 🤱🏾 👼🏾 🎅🏾 🤶🏾 🦸🏾 🦸🏾‍♂️ 🦸🏾‍♀️ 🦹🏾 🦹🏾‍♂️ 🦹🏾‍♀️ 🧙🏾 🧙🏾‍♂️ 🧙🏾‍♀️ 🧚🏾 🧚🏾‍♂️ 🧚🏾‍♀️ 🧛🏾 🧛🏾‍♂️ 🧛🏾‍♀️ 🧜🏾 🧜🏾‍♂️ 🧜🏾‍♀️ 🧝🏾 🧝🏾‍♂️ 🧝🏾‍♀️ 💆🏾 💆🏾‍♂️ 💆🏾‍♀️ 💇🏾 💇🏾‍♂️ 💇🏾‍♀️ 🚶🏾 🚶🏾‍♂️ 🚶🏾‍♀️ 🧍🏾 🧍🏾‍♂️ 🧍🏾‍♀️ 🧎🏾 🧎🏾‍♂️ 🧎🏾‍♀️ 🧑🏾‍🦯 👨🏾‍🦯 👩🏾‍🦯 🧑🏾‍🦼 👨🏾‍🦼 👩🏾‍🦼 🧑🏾‍🦽 👨🏾‍🦽 👩🏾‍🦽 🏃🏾 🏃🏾‍♂️ 🏃🏾‍♀️ 💃🏾 🕺🏾 🕴🏾 🧖🏾 🧖🏾‍♂️ 🧖🏾‍♀️ 🧗🏾 🧗🏾‍♂️ 🧗🏾‍♀️ 🏇🏾 🏂🏾 🏌🏾 🏌🏾‍♂️ 🏌🏾‍♀️ 🏄🏾 🏄🏾‍♂️ 🏄🏾‍♀️ 🚣🏾 🚣🏾‍♂️ 🚣🏾‍♀️ 🏊🏾 🏊🏾‍♂️ 🏊🏾‍♀️ ⛹🏾 ⛹🏾‍♂️ ⛹🏾‍♀️ 🏋🏾 🏋🏾‍♂️ 🏋🏾‍♀️ 🚴🏾 🚴🏾‍♂️ 🚴🏾‍♀️ 🚵🏾 🚵🏾‍♂️ 🚵🏾‍♀️ 🤸🏾 🤸🏾‍♂️ 🤸🏾‍♀️ 🤽🏾 🤽🏾‍♂️ 🤽🏾‍♀️ 🤾🏾 🤾🏾‍♂️ 🤾🏾‍♀️ 🤹🏾 🤹🏾‍♂️ 🤹🏾‍♀️ 🧘🏾 🧘🏾‍♂️ 🧘🏾‍♀️ 🛀🏾 🛌🏾 🧑🏾‍🤝‍🧑🏾 👬🏾 👭🏾 👫🏾` +
	`👋🏿 🤚🏿 🖐🏿 ✋🏿 🖖🏿 👌🏿 🤏🏿 ✌🏿 🤞🏿 🤟🏿 🤘🏿 🤙🏿 👈🏿 👉🏿 👆🏿 🖕🏿 👇🏿 ☝🏿 👍🏿 👎🏿 ✊🏿 👊🏿 🤛🏿 🤜🏿 👏🏿 🙌🏿 👐🏿 🤲🏿 🙏🏿 ✍🏿 💅🏿 🤳🏿 💪🏿 🦵🏿 🦶🏿 👂🏿 🦻🏿 👃🏿 👶🏿 🧒🏿 👦🏿 👧🏿 🧑🏿 👨🏿 👩🏿 🧑🏿‍🦱 👨🏿‍🦱 👩🏿‍🦱 🧑🏿‍🦰 👨🏿‍🦰 👩🏿‍🦰 👱🏿 👱🏿‍♂️ 👱🏿‍♀️ 🧑🏿‍🦳 👨🏿‍🦳 👩🏿‍🦳 🧑🏿‍🦲 👨🏿‍🦲 👩🏿‍🦲 🧔🏿 🧓🏿 👴🏿 👵🏿 🙍🏿 🙍🏿‍♂️ 🙍🏿‍♀️ 🙎🏿 🙎🏿‍♂️ 🙎🏿‍♀️ 🙅🏿 🙅🏿‍♂️ 🙅🏿‍♀️ 🙆🏿 🙆🏿‍♂️ 🙆🏿‍♀️ 💁🏿 💁🏿‍♂️ 💁🏿‍♀️ 🙋🏿 🙋🏿‍♂️ 🙋🏿‍♀️ 🧏🏿 🧏🏿‍♂️ 🧏🏿‍♀️ 🙇🏿 🙇🏿‍♂️ 🙇🏿‍♀️ 🤦🏿 🤦🏿‍♂️ 🤦🏿‍♀️ 🤷🏿 🤷🏿‍♂️ 🤷🏿‍♀️ 🧑🏿‍⚕️ 👨🏿‍⚕️ 👩🏿‍⚕️ 🧑🏿‍🎓 👨🏿‍🎓 👩🏿‍🎓 🧑🏿‍🏫 👨🏿‍🏫 👩🏿‍🏫 🧑🏿‍⚖️ 👨🏿‍⚖️ 👩🏿‍⚖️ 🧑🏿‍🌾 👨🏿‍🌾 👩🏿‍🌾 🧑🏿‍🍳 👨🏿‍🍳 👩🏿‍🍳 🧑🏿‍🔧 👨🏿‍🔧 👩🏿‍🔧 🧑🏿‍🏭 👨🏿‍🏭 👩🏿‍🏭 🧑🏿‍💼 👨🏿‍💼 👩🏿‍💼 🧑🏿‍🔬 👨🏿‍🔬 👩🏿‍🔬 🧑🏿‍💻 👨🏿‍💻 👩🏿‍💻 🧑🏿‍🎤 👨🏿‍🎤 👩🏿‍🎤 🧑🏿‍🎨 👨🏿‍🎨 👩🏿‍🎨 🧑🏿‍✈️ 👨🏿‍✈️ 👩🏿‍✈️ 🧑🏿‍🚀 👨🏿‍🚀 👩🏿‍🚀 🧑🏿‍🚒 👨🏿‍🚒 👩🏿‍🚒 👮🏿 👮🏿‍♂️ 👮🏿‍♀️ 🕵🏿 🕵🏿‍♂️ 🕵🏿‍♀️ 💂🏿 💂🏿‍♂️ 💂🏿‍♀️ 👷🏿 👷🏿‍♂️ 👷🏿‍♀️ 🤴🏿 👸🏿 👳🏿 👳🏿‍♂️ 👳🏿‍♀️ 👲🏿 🧕🏿 🤵🏿 👰🏿 🤰🏿 🤱🏿 👼🏿 🎅🏿 🤶🏿 🦸🏿 🦸🏿‍♂️ 🦸🏿‍♀️ 🦹🏿 🦹🏿‍♂️ 🦹🏿‍♀️ 🧙🏿 🧙🏿‍♂️ 🧙🏿‍♀️ 🧚🏿 🧚🏿‍♂️ 🧚🏿‍♀️ 🧛🏿 🧛🏿‍♂️ 🧛🏿‍♀️ 🧜🏿 🧜🏿‍♂️ 🧜🏿‍♀️ 🧝🏿 🧝🏿‍♂️ 🧝🏿‍♀️ 💆🏿 💆🏿‍♂️ 💆🏿‍♀️ 💇🏿 💇🏿‍♂️ 💇🏿‍♀️ 🚶🏿 🚶🏿‍♂️ 🚶🏿‍♀️ 🧍🏿 🧍🏿‍♂️ 🧍🏿‍♀️ 🧎🏿 🧎🏿‍♂️ 🧎🏿‍♀️ 🧑🏿‍🦯 👨🏿‍🦯 👩🏿‍🦯 🧑🏿‍🦼 👨🏿‍🦼 👩🏿‍🦼 🧑🏿‍🦽 👨🏿‍🦽 👩🏿‍🦽 🏃🏿 🏃🏿‍♂️ 🏃🏿‍♀️ 💃🏿 🕺🏿 🕴🏿 🧖🏿 🧖🏿‍♂️ 🧖🏿‍♀️ 🧗🏿 🧗🏿‍♂️ 🧗🏿‍♀️ 🏇🏿 🏂🏿 🏌🏿 🏌🏿‍♂️ 🏌🏿‍♀️ 🏄🏿 🏄🏿‍♂️ 🏄🏿‍♀️ 🚣🏿 🚣🏿‍♂️ 🚣🏿‍♀️ 🏊🏿 🏊🏿‍♂️ 🏊🏿‍♀️ ⛹🏿 ⛹🏿‍♂️ ⛹🏿‍♀️ 🏋🏿 🏋🏿‍♂️ 🏋🏿‍♀️ 🚴🏿 🚴🏿‍♂️ 🚴🏿‍♀️ 🚵🏿 🚵🏿‍♂️ 🚵🏿‍♀️ 🤸🏿 🤸🏿‍♂️ 🤸🏿‍♀️ 🤽🏿 🤽🏿‍♂️ 🤽🏿‍♀️ 🤾🏿 🤾🏿‍♂️ 🤾🏿‍♀️ 🤹🏿 🤹🏿‍♂️ 🤹🏿‍♀️ 🧘🏿 🧘🏿‍♂️ 🧘🏿‍♀️ 🛀🏿 🛌🏿 🧑🏿‍🤝‍🧑🏿 👬🏿 👭🏿 👫🏿`;

var animal =
	'🐶 🐱 🐭 🐹 🐰 🦊 🐻 🐼 🐨 🐯 🦁 🐮 🐷 🐽 🐸 🐵 🙈 🙉 🙊 🐒 🐔 🐧 🐦 🐤 🐣 🐥 🦆 🦅 🦉 🦇 🐺 🐗 🐴 🦄 🐝 🐛 🦋 🐌 🐞 🐜 🦟 🦗 🕷 🕸 🦂 🐢 🐍 🦎 🦖 🦕 🐙 🦑 🦐 🦞 🦀 🐡 🐠 🐟 🐬 🐳 🐋 🦈 🐊 🐅 🐆 🦓 🦍 🦧 🐘 🦛 🦏 🐪 🐫 🦒 🦘 🐃 🐂 🐄 🐎 🐖 🐏 🐑 🦙 🐐 🦌 🐕 🐩 🦮 🐕‍🦺 🐈 🐓 🦃 🦚 🦜 🦢 🦩 🕊 🐇 🦝 🦨 🦡 🦦 🦥 🐁 🐀 🐿 🦔 🐾 🐉 🐲 🌵 🎄 🌲 🌳 🌴 🌱 🌿 ☘️ 🍀 🎍 🎋 🍃 🍂 🍁 🍄 🐚 🌾 💐 🌷 🌹 🥀 🌺 🌸 🌼 🌻 🌞 🌝 🌛 🌜 🌚 🌕 🌖 🌗 🌘 🌑 🌒 🌓 🌔 🌙 🌎 🌍 🌏 🪐 💫 ⭐️ 🌟 ✨ ⚡️ ☄️ 💥 🔥 🌪 🌈 ☀️ 🌤 ⛅️ 🌥 ☁️ 🌦 🌧 ⛈ 🌩 🌨 ❄️ ☃️ ⛄️ 🌬 💨 💧 💦 ☔️ ☂️ 🌊 🌫';
var food =
	'🍏 🍎 🍐 🍊 🍋 🍌 🍉 🍇 🍓 🍈 🍒 🍑 🥭 🍍 🥥 🥝 🍅 🍆 🥑 🥦 🥬 🥒 🌶 🌽 🥕 🧄 🧅 🥔 🍠 🥐 🥯 🍞 🥖 🥨 🧀 🥚 🍳 🧈 🥞 🧇 🥓 🥩 🍗 🍖 🦴 🌭 🍔 🍟 🍕 🥪 🥙 🧆 🌮 🌯 🥗 🥘 🥫 🍝 🍜 🍲 🍛 🍣 🍱 🥟 🦪 🍤 🍙 🍚 🍘 🍥 🥠 🥮 🍢 🍡 🍧 🍨 🍦 🥧 🧁 🍰 🎂 🍮 🍭 🍬 🍫 🍿 🍩 🍪 🌰 🥜 🍯 🥛 🍼 ☕️ 🍵 🧃 🥤 🍶 🍺 🍻 🥂 🍷 🥃 🍸 🍹 🧉 🍾 🧊 🥄 🍴 🍽 🥣 🥡 🥢 🧂';
var activity =
	'⚽️ 🏀 🏈 ⚾️ 🥎 🎾 🏐 🏉 🥏 🎱 🪀 🏓 🏸 🏒 🏑 🥍 🏏 🥅 ⛳️ 🪁 🏹 🎣 🤿 🥊 🥋 🎽 🛹 🛷 ⛸ 🥌 🎿 ⛷ 🏂 🪂 🏋️ 🏋️‍♂️ 🏋️‍♀️ 🤼 🤼‍♂️ 🤼‍♀️ 🤸‍♀️ 🤸 🤸‍♂️ ⛹️ ⛹️‍♂️ ⛹️‍♀️ 🤺 🤾 🤾‍♂️ 🤾‍♀️ 🏌️ 🏌️‍♂️ 🏌️‍♀️ 🏇 🧘 🧘‍♂️ 🧘‍♀️ 🏄 🏄‍♂️ 🏄‍♀️ 🏊 🏊‍♂️ 🏊‍♀️ 🤽 🤽‍♂️ 🤽‍♀️ 🚣 🚣‍♂️ 🚣‍♀️ 🧗 🧗‍♂️ 🧗‍♀️ 🚵 🚵‍♂️ 🚵‍♀️ 🚴 🚴‍♂️ 🚴‍♀️ 🏆 🥇 🥈 🥉 🏅 🎖 🏵 🎗 🎫 🎟 🎪 🤹 🤹‍♂️ 🤹‍♀️ 🎭 🩰 🎨 🎬 🎤 🎧 🎼 🎹 🥁 🎷 🎺 🎸 🪕 🎻 🎲 ♟ 🎯 🎳 🎮 🎰 🧩';
var travel =
	'🚗 🚕 🚙 🚌 🚎 🏎 🚓 🚑 🚒 🚐 🚚 🚛 🚜 🦯 🦽 🦼 🛴 🚲 🛵 🏍 🛺 🚨 🚔 🚍 🚘 🚖 🚡 🚠 🚟 🚃 🚋 🚞 🚝 🚄 🚅 🚈 🚂 🚆 🚇 🚊 🚉 ✈️ 🛫 🛬 🛩 💺 🛰 🚀 🛸 🚁 🛶 ⛵️ 🚤 🛥 🛳 ⛴ 🚢 ⚓️ ⛽️ 🚧 🚦 🚥 🚏 🗺 🗿 🗽 🗼 🏰 🏯 🏟 🎡 🎢 🎠 ⛲️ ⛱ 🏖 🏝 🏜 🌋 ⛰ 🏔 🗻 🏕 ⛺️ 🏠 🏡 🏘 🏚 🏗 🏭 🏢 🏬 🏣 🏤 🏥 🏦 🏨 🏪 🏫 🏩 💒 🏛 ⛪️ 🕌 🕍 🛕 🕋 ⛩ 🛤 🛣 🗾 🎑 🏞 🌅 🌄 🌠 🎇 🎆 🌇 🌆 🏙 🌃 🌌 🌉 🌁';
var object =
	'⌚️ 📱 📲 💻 ⌨️ 🖥 🖨 🖱 🖲 🕹 🗜 💽 💾 💿 📀 📼 📷 📸 📹 🎥 📽 🎞 📞 ☎️ 📟 📠 📺 📻 🎙 🎚 🎛 🧭 ⏱ ⏲ ⏰ 🕰 ⌛️ ⏳ 📡 🔋 🔌 💡 🔦 🕯 🪔 🧯 🛢 💸 💵 💴 💶 💷 💰 💳 💎 ⚖️ 🧰 🔧 🔨 ⚒ 🛠 ⛏ 🔩 ⚙️ 🧱 ⛓ 🧲 🔫 💣 🧨 🪓 🔪 🗡 ⚔️ 🛡 🚬 ⚰️ ⚱️ 🏺 🔮 📿 🧿 💈 ⚗️ 🔭 🔬 🕳 🩹 🩺 💊 💉 🩸 🧬 🦠 🧫 🧪 🌡 🧹 🧺 🧻 🚽 🚰 🚿 🛁 🛀 🧼 🪒 🧽 🧴 🛎 🔑 🗝 🚪 🪑 🛋 🛏 🛌 🧸 🖼 🛍 🛒 🎁 🎈 🎏 🎀 🎊 🎉 🎎 🏮 🎐 🧧 ✉️ 📩 📨 📧 💌 📥 📤 📦 🏷 📪 📫 📬 📭 📮 📯 📜 📃 📄 📑 🧾 📊 📈 📉 🗒 🗓 📆 📅 🗑 📇 🗃 🗳 🗄 📋 📁 📂 🗂 🗞 📰 📓 📔 📒 📕 📗 📘 📙 📚 📖 🔖 🧷 🔗 📎 🖇 📐 📏 🧮 📌 📍 ✂️ 🖊 🖋 ✒️ 🖌 🖍 📝 ✏️ 🔍 🔎 🔏 🔐 🔒 🔓';
var symbol =
	'❤️ 🧡 💛 💚 💙 💜 🖤 🤍 🤎 💔 ❣️ 💕 💞 💓 💗 💖 💘 💝 💟 ☮️ ✝️ ☪️ 🕉 ☸️ ✡️ 🔯 🕎 ☯️ ☦️ 🛐 ⛎ ♈️ ♉️ ♊️ ♋️ ♌️ ♍️ ♎️ ♏️ ♐️ ♑️ ♒️ ♓️ 🆔 ⚛️ 🉑 ☢️ ☣️ 📴 📳 🈶 🈚️ 🈸 🈺 🈷️ ✴️ 🆚 💮 🉐 ㊙️ ㊗️ 🈴 🈵 🈹 🈲 🅰️ 🅱️ 🆎 🆑 🅾️ 🆘 ❌ ⭕️ 🛑 ⛔️ 📛 🚫 💯 💢 ♨️ 🚷 🚯 🚳 🚱 🔞 📵 🚭 ❗️ ❕ ❓ ❔ ‼️ ⁉️ 🔅 🔆 〽️ ⚠️ 🚸 🔱 ⚜️ 🔰 ♻️ ✅ 🈯️ 💹 ❇️ ✳️ ❎ 🌐 💠 Ⓜ️ 🌀 💤 🏧 🚾 ♿️ 🅿️ 🈳 🈂️ 🛂 🛃 🛄 🛅 🚹 🚺 🚼 🚻 🚮 🎦 📶 🈁 🔣 ℹ️ 🔤 🔡 🔠 🆖 🆗 🆙 🆒 🆕 🆓 0️⃣ 1️⃣ 2️⃣ 3️⃣ 4️⃣ 5️⃣ 6️⃣ 7️⃣ 8️⃣ 9️⃣ 🔟 🔢 #️⃣ *️⃣ ⏏️ ▶️ ⏸ ⏯ ⏹ ⏺ ⏭ ⏮ ⏩ ⏪ ⏫ ⏬ ◀️ 🔼 🔽 ➡️ ⬅️ ⬆️ ⬇️ ↗️ ↘️ ↙️ ↖️ ↕️ ↔️ ↪️ ↩️ ⤴️ ⤵️ 🔀 🔁 🔂 🔄 🔃 🎵 🎶 ➕ ➖ ➗ ✖️ ♾ 💲 💱 ™️ ©️ ®️ 〰️ ➰ ➿ 🔚 🔙 🔛 🔝 🔜 ✔️ ☑️ 🔘 🔴 🟠 🟡 🟢 🔵 🟣 ⚫️ ⚪️ 🟤 🔺 🔻 🔸 🔹 🔶 🔷 🔳 🔲 ▪️ ▫️ ◾️ ◽️ ◼️ ◻️ 🟥 🟧 🟨 🟩 🟦 🟪 ⬛️ ⬜️ 🟫 🔈 🔇 🔉 🔊 🔔 🔕 📣 📢 👁‍🗨 💬 💭 🗯 ♠️ ♣️ ♥️ ♦️ 🃏 🎴 🀄️ 🕐 🕑 🕒 🕓 🕔 🕕 🕖 🕗 🕘 🕙 🕚 🕛 🕜 🕝 🕞 🕟 🕠 🕡 🕢 🕣 🕤 🕥 🕦 🕧';
var flag =
	'🏳️ 🏴 🏁 🚩 🏳️‍🌈 🏴‍☠️ 🇦🇫 🇦🇽 🇦🇱 🇩🇿 🇦🇸 🇦🇩 🇦🇴 🇦🇮 🇦🇶 🇦🇬 🇦🇷 🇦🇲 🇦🇼 🇦🇺 🇦🇹 🇦🇿 🇧🇸 🇧🇭 🇧🇩 🇧🇧 🇧🇾 🇧🇪 🇧🇿 🇧🇯 🇧🇲 🇧🇹 🇧🇴 🇧🇦 🇧🇼 🇧🇷 🇮🇴 🇻🇬 🇧🇳 🇧🇬 🇧🇫 🇧🇮 🇰🇭 🇨🇲 🇨🇦 🇮🇨 🇨🇻 🇧🇶 🇰🇾 🇨🇫 🇹🇩 🇨🇱 🇨🇳 🇨🇽 🇨🇨 🇨🇴 🇰🇲 🇨🇬 🇨🇩 🇨🇰 🇨🇷 🇨🇮 🇭🇷 🇨🇺 🇨🇼 🇨🇾 🇨🇿 🇩🇰 🇩🇯 🇩🇲 🇩🇴 🇪🇨 🇪🇬 🇸🇻 🇬🇶 🇪🇷 🇪🇪 🇪🇹 🇪🇺 🇫🇰 🇫🇴 🇫🇯 🇫🇮 🇫🇷 🇬🇫 🇵🇫 🇹🇫 🇬🇦 🇬🇲 🇬🇪 🇩🇪 🇬🇭 🇬🇮 🇬🇷 🇬🇱 🇬🇩 🇬🇵 🇬🇺 🇬🇹 🇬🇬 🇬🇳 🇬🇼 🇬🇾 🇭🇹 🇭🇳 🇭🇰 🇭🇺 🇮🇸 🇮🇳 🇮🇩 🇮🇷 🇮🇶 🇮🇪 🇮🇲 🇮🇱 🇮🇹 🇯🇲 🇯🇵 🎌 🇯🇪 🇯🇴 🇰🇿 🇰🇪 🇰🇮 🇽🇰 🇰🇼 🇰🇬 🇱🇦 🇱🇻 🇱🇧 🇱🇸 🇱🇷 🇱🇾 🇱🇮 🇱🇹 🇱🇺 🇲🇴 🇲🇰 🇲🇬 🇲🇼 🇲🇾 🇲🇻 🇲🇱 🇲🇹 🇲🇭 🇲🇶 🇲🇷 🇲🇺 🇾🇹 🇲🇽 🇫🇲 🇲🇩 🇲🇨 🇲🇳 🇲🇪 🇲🇸 🇲🇦 🇲🇿 🇲🇲 🇳🇦 🇳🇷 🇳🇵 🇳🇱 🇳🇨 🇳🇿 🇳🇮 🇳🇪 🇳🇬 🇳🇺 🇳🇫 🇰🇵 🇲🇵 🇳🇴 🇴🇲 🇵🇰 🇵🇼 🇵🇸 🇵🇦 🇵🇬 🇵🇾 🇵🇪 🇵🇭 🇵🇳 🇵🇱 🇵🇹 🇵🇷 🇶🇦 🇷🇪 🇷🇴 🇷🇺 🇷🇼 🇼🇸 🇸🇲 🇸🇦 🇸🇳 🇷🇸 🇸🇨 🇸🇱 🇸🇬 🇸🇽 🇸🇰 🇸🇮 🇬🇸 🇸🇧 🇸🇴 🇿🇦 🇰🇷 🇸🇸 🇪🇸 🇱🇰 🇧🇱 🇸🇭 🇰🇳 🇱🇨 🇵🇲 🇻🇨 🇸🇩 🇸🇷 🇸🇿 🇸🇪 🇨🇭 🇸🇾 🇹🇼 🇹🇯 🇹🇿 🇹🇭 🇹🇱 🇹🇬 🇹🇰 🇹🇴 🇹🇹 🇹🇳 🇹🇷 🇹🇲 🇹🇨 🇹🇻 🇻🇮 🇺🇬 🇺🇦 🇦🇪 🇬🇧 🏴󠁧󠁢󠁥󠁮󠁧󠁿 🏴󠁧󠁢󠁳󠁣󠁴󠁿 🏴󠁧󠁢󠁷󠁬󠁳󠁿 🇺🇳 🇺🇸 🇺🇾 🇺🇿 🇻🇺 🇻🇦 🇻🇪 🇻🇳 🇼🇫 🇪🇭 🇾🇪 🇿🇲 🇿🇼';
var newEmoji =
	'🥲 🥸 🤌 🫀 🫁 🥷 🤵‍♂️ 👰‍♂️ 👰‍♀️ 👩‍🍼 🧑‍🍼 👨‍🍼 🧑‍🎄 🫂 🐈‍⬛ 🦬 🦣 🦫 🐻‍❄️ 🦤 🪶 🦭 🪲 🪳 🪰 🪱 🪴 🫐 🫒 🫑 🫓 🫔 🫕 🫖 🧋 🪨 🪵 🛖 🛻 🛼 🪄 🪅 🪆 🪡 🪢 🩴 🪖 🪗 🪘 🪙 🪃 🪚 🪛 🪝 🪜 🛗 🪞 🪟 🪠 🪤 🪣 🪥 🪦 🪧 🏳️‍⚧️';

var epicker = true,
	txa = '',
	E = '';

var twemoji = false;
$(document).on('click', '._EmojiPicker', function (e) {
	let O = $(this);

	if (twemoji) {
		loadEmoji();
	} else {
		loadScript(CDN_BASE_URL + TWEMOJI_JS, function () {
			loadEmoji();
			twemoji = true;
		});
	}
	function loadEmoji() {
		let t = $(O.attr('data-target')),
			a = '',
			l = '',
			img = [
				'smile',
				'gestures',
				'people',
				'clothing',
				'animal',
				'food',
				'activity',
				'travel',
				'object',
				'symbol',
				'flag',
			];
		t.removeClass('hide');

		txa = $(O.attr('data-textarea'));

		if (epicker) {
			for (x = 0; x < img.length; x++) {
				a = x == 0 ? 'active' : '';
				l +=
					`<li class="` +
					a +
					`">
								<a data-toggle="tab" href="#` +
					img[x] +
					`" data-emotab="` +
					x +
					`">
									<img src="` +
					CDN_BASE_URL +
					`repo/images/emoji/` +
					img[x] +
					`.svg" alt="` +
					img[x] +
					`">
								</a>
							</li>`;
				a = x == 0 ? 'in active' : '';
				E +=
					`<div id="` +
					img[x] +
					`" class="tab-pane fade ` +
					a +
					`">
								<ul class="emoji-list">
									` +
					eval(img[x]) +
					`
								</ul>
							</div>`;
			}
			E =
				`<div class="emoji_dropdown">
							<ul class="emoji_topbar">
								` +
				l +
				`
							</ul>
							<div class="tab-content">
								` +
				E +
				`
							</div>
						</div>`;
			t.html(E);

			twemoji.parse(document.querySelectorAll('.emoji-list')[0], {
				folder: '72x72',
				ext: '.png',
				className: '_PostEmoji',
			});
			E = $('.emoji_dropdown').parent().html();
			epicker = false;
		} else {
			t.html(E);
		}
	}
});


$(document).on('click', '._PostEmoji', function () {
	let cursorPosition = txa.prop('selectionStart');
	let text = txa.val();
	let utf8Emoji = $(this).attr('alt');
	let output = [
		text.slice(0, cursorPosition),
		utf8Emoji,
		text.slice(cursorPosition),
	].join('');
	txa
		.val(output)
		.focus()
		.prop('selectionEnd', cursorPosition + 2);
});

$(document).mouseup(function (e) {
	let container = $('.emoji_dropdown');
	if (!container.is(e.target) && container.has(e.target).length === 0) {
		container.parent().empty();
	}
});

$(document).on('click', '[data-emotab]', function () {
	let ET = $(this).attr('data-emotab');
	twemoji.parse(document.querySelectorAll('.emoji-list')[ET], {
		folder: '72x72',
		ext: '.png',
		className: '_PostEmoji',
	});
});

function renderCommEmoji() {
	if (!IsMobileDevice()) {
		$('.dis_comment_data').each(function (i) {
			twemoji.parse(document.querySelectorAll('.dis_comment_data > p')[i], {
				folder: '72x72',
				ext: '.png',
				className: '_PostEmoji',
			});
		});
	}
}

// }
/********************************************************************/
/************************ Emoji Picker Code End ********************/
/********************************************************************/

$(document).on('change', '.SelectBySimpleSelect', function () {
	let _this = $(this);
	let url = _this.data('url');
	let check_url = url.split('/').length;
	let elementId = _this.data('id');
	let base = check_url > 1 ? base_url : node_url;

	// if(_this.val() != '' && _this.val() != 0){
	if (_this.val() != '' && _this.val() != 0) {
		manageMyAjaxPostRequestData({ id: _this.val() }, base + url).done(function (
			resp
		) {
			$(elementId).html(resp);
		});
	} else if (_this.val() == ' ') {
		$(elementId).html('<option value="">All Sub Category </option>');
	}
});

$(document).on('change', '.previewFile', function () {
	let _this = $(this);
	let file = _this[0].files[0];

	let reader = new FileReader();
	reader.addEventListener(
		'load',
		function () {
			$(_this.data('id')).attr('src', reader.result);
			$(_this.data('id')).css('background-image', 'url(' + reader.result + ')');
		},
		false
	);

	if (file) {
		reader.readAsDataURL(file);
	}

	if (_this.data('xhr')) {
		let f = new FormData();
		f.set('ufile', file);
		f.set('target', _this.data('target'));
		f.set(csrf_name, csrf_hash);
		manageMyAjaxPostRequestData(f, base_url + _this.data('url')).done(function (
			data
		) {
			if (data.status == 1) {
				showNotifications('success', data.message);
			}
		});
	}
});

$(document).on('click', '.copytoclipboard', function () {
	let _this = $(this);
	let target = $(_this.attr('data-target'));
	let type = target.attr('type');

	type == 'password' ? target.attr('type', 'text') : '';

	clipboard(_this);

	_this.html('Copied !');

	type == 'password' ? target.attr('type', 'password') : '';
	setTimeout(function () {
		_this.html('Copy');
	}, 2000);
	// window.prompt("Copy to clipboard: Ctrl+C, Enter", $(this).attr('data-target'));
});


function clipboard(_this) {
	let target = $(_this.attr('data-target'));
	let isSafariBrowser = () => navigator.userAgent.indexOf('Safari') > -1 && navigator.userAgent.indexOf('Chrome') <= -1;
	if (isSafariBrowser()) {
		let t = document.querySelector(_this.attr('data-target'));
		let range = document.createRange();
		range.selectNodeContents(t);

		let selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(range);
		t.setSelectionRange(0, 999999);
	} else {
		//target.select();
	}
	
	let textToCopy = target ? target.val() : 'No content to copy';
	console.log('Text content:', textToCopy);

	// Use the Clipboard API to copy the text
	if (navigator.clipboard) {
		navigator.clipboard.writeText(textToCopy)
			.then(() => {
				console.log('Text copied to clipboard');
			})
			.catch(err => {
				console.error('Unable to copy text: ', err);
			});
	} else {
		// Fallback to execCommand if Clipboard API is not supported
		try {
			target.select();
			document.execCommand('copy');
			console.log('Text copied to clipboard');
		} catch (err) {
			console.error('Unable to copy text: ', err); 
		}
	}

	//document.execCommand('copy');
	return;
}



function store(name, val) {
	if (typeof Storage !== 'undefined') {
		localStorage.setItem(name, val);
	} else {
		window.alert('Please use a modern browser to properly view this template!');
	}
}
function get(name) {
	if (typeof Storage !== 'undefined') {
		return localStorage.getItem(name);
	} else {
		window.alert('Please use a modern browser to properly view this template!');
	}
}
function ShowPopup() {
	var tmp = get('ShowPopup');
	if (tmp == null) {
		$('.dis_heighlight_popup').addClass('popup_show');
	}
}

function iframe(embd,isResponsive=false) {
	/********available also in backend common.js*********/
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
	/********available also in backend common.js*********/
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
function lookup(arr, id) {
	console.log('arr:', typeof arr);
	if (arr != null && arr.length > 0) {
		for (var i = 0, len = arr.length; i < len; i++) {
			if (arr[i] != null && arr[i].vid === id) return i;
		}
	}
	return -1;
}

function getSliderHtml(resData) {
	var color = resData['color'];
	var title = resData['title'];
	var type = resData['type'];
	var href = resData['href'];
	var autoPlay = resData['auto'];
	var videoData = resData['videoData'];
	var playAllUrl = resData['play_all_url'];
	var inner = '';

	if ($('#defaultmode').val() == 'live') {
		if ($('#' + type).length) {
			$('#' + type).removeClass('hideme');
		}

		if ($('#live_' + title + '_' + type).length) {
			$('#live_' + title + '_' + type).removeClass('hideme');
		}
	}
	if (videoData.length > 0) {
		var html = `<div class="au_artist_wrapper">
						<div class="container-flui">
							<div class="ro">
								<div class="">
									<div class="dis_sliderheading">
										<div class="dis_sliderheadingL">
											<h2 class="dis_sliderheading_ttl muli_font">${title}</h2>`;

		if (playAllUrl != undefined && playAllUrl != '') {
			html += `<a href="${playAllUrl}" class="dis_slider_playall muli_font">
												<span class="dis_slider_paIcon">
													<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15.094 17.75"> <path fill="rgb(235 88 31)" fill-rule="evenodd" d="M0.811,9.024c0-2.2.038-4.394-.012-6.589A2.161,2.161,0,0,1,4.12.494C7.575,2.578,11.079,4.58,14.56,6.619a2.34,2.34,0,0,1,.069,4.194Q9.363,14.184,4.092,17.547a2.055,2.055,0,0,1-3.076-.856,2.617,2.617,0,0,1-.2-1C0.8,13.47.811,11.247,0.811,9.024Z" transform="translate(-0.813 -0.188)"/></svg>
												</span>
												<span class="dis_slider_patxt">play all videos</span>
												</a>`;
		}

		html += `</div>
										<div class="dis_sh_btnwrap">
											${href}
										</div>
									</div>
								</div>
							</div>`;

		let tt = 1;
		// value.is_session_uid

		let errImg =
			base_url +
			'repo/images/nothumb/' +
			($('body').hasClass('theme_dark') ? 'dark.jpg' : 'light.jpg');
		$.each(videoData, function (i) {
			var value = videoData[i];

			var onclick = `<ul class="dis_cardS_oplist">
								<li>
									<div class="dis_sld_preview openModalPopup" data-href="modal/video_popup/${value.post_id
				}${value.playlist_id ? '/' + value.playlist_id : ''
				}" data-cls="dis_custom_video_popup">
										<span class="preview_txt">Preview</span>
										<span class="pre_icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
											<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"/>
											</svg>
										</span>
									</div>
								</li>
								${value.playlist_id == '' &&
					value.is_session_uid &&
					user_login_id &&
					user_login_id !== ''
					? `<li>
									<div class="dis_sld_preview" onclick="redirect('${'monetize/' + value.post_id
					}',10)">
										<span class="preview_txt">Edit</span>
										<span class="pre_icon">
											<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9.68872 1.21677L8.78255 0.310524C8.36849 -0.103518 7.69486 -0.103498 7.28083 0.310524L6.92615 0.665231L9.33403 3.07331L9.68872 2.71861C10.1037 2.30359 10.1038 1.63183 9.68872 1.21677Z" fill="white"/>
											<path d="M0.429919 7.35832L0.00490067 9.65365C-0.0126579 9.74851 0.0175764 9.84595 0.085799 9.91418C0.1541 9.98248 0.25156 10.0127 0.346306 9.99509L2.64146 9.57004L0.429919 7.35832Z" fill="white"/>
											<path d="M6.51185 1.07957L0.746063 6.84581L3.15395 9.25388L8.91974 3.48766L6.51185 1.07957Z" fill="white"/>
											</svg>
										</span>
									</div>
								</li>
								<li>
									<div class="dis_sld_preview delete_channel_video" data-post_id="${value.post_id
					}">
										<span class="preview_txt">Delete</span>
										<span class="pre_icon">
											<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M10.5 1.8H1.5C1.34087 1.8 1.18826 1.86321 1.07574 1.97574C0.963216 2.08826 0.900002 2.24087 0.900002 2.4C0.900002 2.55913 0.963216 2.71174 1.07574 2.82426C1.18826 2.93679 1.34087 3 1.5 3H1.8V10.2C1.80015 10.6773 1.98983 11.1351 2.32737 11.4726C2.6649 11.8102 3.12266 11.9999 3.6 12H8.4C8.87735 11.9999 9.33512 11.8102 9.67266 11.4727C10.0102 11.1351 10.1999 10.6774 10.2 10.2V3H10.5C10.6591 3 10.8117 2.93679 10.9243 2.82426C11.0368 2.71174 11.1 2.55913 11.1 2.4C11.1 2.24087 11.0368 2.08826 10.9243 1.97574C10.8117 1.86321 10.6591 1.8 10.5 1.8ZM5.4 9.4C5.4 9.55913 5.33679 9.71174 5.22427 9.82427C5.11174 9.93679 4.95913 10 4.8 10C4.64087 10 4.48826 9.93679 4.37574 9.82427C4.26322 9.71174 4.2 9.55913 4.2 9.4V5.4C4.2 5.24087 4.26322 5.08826 4.37574 4.97574C4.48826 4.86321 4.64087 4.8 4.8 4.8C4.95913 4.8 5.11174 4.86321 5.22427 4.97574C5.33679 5.08826 5.4 5.24087 5.4 5.4V9.4ZM7.8 9.4C7.8 9.55913 7.73679 9.71174 7.62427 9.82427C7.51174 9.93679 7.35913 10 7.2 10C7.04087 10 6.88826 9.93679 6.77574 9.82427C6.66322 9.71174 6.6 9.55913 6.6 9.4V5.4C6.6 5.24087 6.66322 5.08826 6.77574 4.97574C6.88826 4.86321 7.04087 4.8 7.2 4.8C7.35913 4.8 7.51174 4.86321 7.62427 4.97574C7.73679 5.08826 7.8 5.24087 7.8 5.4V9.4Z" fill="white"/>
											<path d="M4.8 1.2H7.2C7.35913 1.2 7.51174 1.13679 7.62426 1.02426C7.73678 0.911742 7.8 0.75913 7.8 0.6C7.8 0.44087 7.73678 0.288258 7.62426 0.175736C7.51174 0.0632141 7.35913 0 7.2 0H4.8C4.64087 0 4.48825 0.0632141 4.37573 0.175736C4.26321 0.288258 4.2 0.44087 4.2 0.6C4.2 0.75913 4.26321 0.911742 4.37573 1.02426C4.48825 1.13679 4.64087 1.2 4.8 1.2V1.2Z" fill="white"/>
											</svg>
										</span>
									</div>
								</li>`
					: ''
				}
							</ul>`;

			let dura_section = '';
			if (
				value.playlist_id &&
				value.playlist_id !== '' &&
				value.video_ids_count
			) {
				// this is for playlist video count
				dura_section = `<span class="dis_videotime">${value.video_ids_count} ${value.video_ids_count > 1 ? 'Videos' : 'Video'
					}</span>`;
			} else {
				dura_section =
					value.video_duration != 0
						? `<span class="dis_videotime">${secondsToHms(
							value.video_duration
						)}</span>`
						: '';
			}

			inner += `<div class="swiper-slide play_preview_common" data-post_delete_id="${value.post_id}" data-preview-src="${value.previewFile}" >
							<div class="dis_post_video_data dis_cardS_oplistWrap">
								<div class="dis_postvideo_img">
								<!--div class="dis_slidernoThumb">
									<span class="dis_SNT_icon">
										<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="47px" height="36px"><path fill-rule="evenodd" fill="rgb(163, 163, 163)" d="M44.15,0.0 L2.984,0.0 C1.336,0.0 0.0,1.315 0.0,2.938 L0.0,33.61 C0.0,34.684 1.336,35.999 2.984,35.999 L44.15,35.999 C45.663,35.999 46.999,34.684 46.999,33.61 L46.999,2.938 C46.999,1.315 45.663,0.0 44.15,0.0 ZM44.15,2.938 L44.15,24.311 L38.134,19.46 C37.250,18.254 35.891,18.286 35.47,19.118 L29.95,24.979 L17.359,11.174 C16.472,10.131 14.847,10.121 13.946,11.152 L2.984,23.699 L2.984,2.938 L44.15,2.938 ZM31.333,10.653 C31.333,8.421 33.169,6.612 35.436,6.612 C37.703,6.612 39.539,8.421 39.539,10.653 C39.539,12.885 37.703,14.693 35.436,14.693 C33.169,14.693 31.333,12.885 31.333,10.653 Z"/></svg>
									</span>
									<span class="dis_SNT_text">Thumbnail <br>Not Available</span>
								</div-->

								<img src="${value.webp}" class="img-responsive" alt="Discovered" onError="ImageOnLoadError(this,'` + value.img + `','` + errImg + `')" loading="lazy">

								${dura_section}

								<div class="dis_previewvideo">
									<video autoplay muted loop playsinline preload="metadata" width="100%" height="100%">
										<source src="" type="video/mp4">
									</video>
								</div>`;

			if (type == 'Upcoming') {
				//format the local time
				var schedule_time = moment
					.utc(value.schedule_time)
					.local()
					.format('DD-MMM-YYYY hh:mm:ss A');
				inner += `<p class="dis_scheduled_for mp_0">Scheduled for ${schedule_time}</p>`;
			}

			if (type == 'global_top_ten') {
				inner += `<div class="dis_topten">
										<img src="${base_url}repo/images/top_ten/top_ten${tt++}.png" alt="image" class="img-responsive" loading="lazy">
									</div>`;
			}

			inner += `<div class="dis_overlay">
									<div class="dis_overlay_inner">
										<a  href="${value.PlaylistUrl}"class="">
											 <img src="${value.play_icon}" alt="image" class="img-responsive temporary_hide hide" loading="lazy">
										</a>
									</div>
								</div>`;

			if (title == 'Continue Watching') {
				inner += `<div class="dis_player_progress">
												<div class="dis_player_bar" style="width:${value.progressBarPer}%">

												</div>
											</div>`;
			}

			inner += `</div>
								${onclick}
								<div class="dis_postvideo_content">
									<h3><a href="${value.PlaylistUrl}" title="${value.full_title
				}">${convert_accented_characters(value.title)}</a></h3>
								</div>
							</div>
						</div>`;
		});

		html += `<div class="au_artist_slider" data-autoplay="${autoPlay}">
							<div class="swiper-container">
								<div class="swiper-wrapper">
									${inner}
								</div>
								<div class="swiper-button-next fvs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
								<div class="swiper-button-prev fvs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
							</div>
						</div>`;

		html += `
				</div>
			</div>`;

		return html;
	}
}

function loadMoreVideoHtml(currVal) {
	let html = `<div class="text-center play_preview_common" data-post_delete_id="${currVal.post_id}" data-preview-src="${currVal.previewFile}">
		<div class="dis_post_video_data dis_cardS_oplistWrap">
			<div class="dis_postvideo_img dis_postvideo_height185">
				<img src="${currVal.poster}" class="img-responsive" alt="Discovered" onError="ImageOnLoadError(this,'` + currVal.img + `','` + currVal.errimg + `')" >
				<div class="dis_previewvideo">
					<video autoplay="" muted="" loop="" playsinline="" preload="metadata" width="100%" height="100%">
						<source src="" type="video/mp4">
					</video>
				</div>

				<div class="dis_overlay loadmore_testing1">
					<a href="${currVal.href}" class="dis_seeAll_loadmore_a "><img class="temporary_hide" src="${base_url + 'repo/images/play_icon.png'}"></a>
				</div>
				<span class="dis_videotime">${secondsToHms(currVal.video_duration)}</span>
			</div>
			<ul class="dis_cardS_oplist">
			<li>
				<div class="dis_sld_preview openModalPopup" data-href="modal/video_popup/${currVal.post_id}" data-cls="dis_custom_video_popup">
					<span class="preview_txt">Preview</span>
					<span class="pre_icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
						<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
						</svg>
					</span>
				</div>
			</li>
		</ul>
			<div class="dis_postvideo_content">
				<h3><a href="${currVal.href}" title="${currVal.full_title}">${currVal.title}</a></h3>
			</div>
		</div>
	</div>`;
	return html;
}
function secondsToHms(d) {
	d = Number(d);
	// d = d + 1;
	var h = Math.floor(d / 3600);
	var m = Math.floor((d % 3600) / 60);
	var s = Math.floor((d % 3600) % 60);
	let hrs = '';
	if (h == 0) {
		hrs = '';
	} else {
		hrs = ('0' + h).slice(-2) + ':';
	}
	return hrs + ('0' + m).slice(-2) + ':' + ('0' + s).slice(-2);
}

function convert_accented_characters(str) {
	var conversions = new Object();
	conversions['ae'] = 'ä|æ|ǽ';
	conversions['oe'] = 'ö|œ';
	conversions['ue'] = 'ü';
	conversions['Ae'] = 'Ä';
	conversions['Ue'] = 'Ü';
	conversions['Oe'] = 'Ö';
	conversions['A'] = 'À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ';
	conversions['a'] = 'à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª';
	conversions['C'] = 'Ç|Ć|Ĉ|Ċ|Č';
	conversions['c'] = 'ç|ć|ĉ|ċ|č';
	conversions['D'] = 'Ð|Ď|Đ';
	conversions['d'] = 'ð|ď|đ';
	conversions['E'] = 'È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě';
	conversions['e'] = 'è|é|ê|ë|ē|ĕ|ė|ę|ě';
	conversions['G'] = 'Ĝ|Ğ|Ġ|Ģ';
	conversions['g'] = 'ĝ|ğ|ġ|ģ';
	conversions['H'] = 'Ĥ|Ħ';
	conversions['h'] = 'ĥ|ħ';
	conversions['I'] = 'Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ';
	conversions['i'] = 'ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı';
	conversions['J'] = 'Ĵ';
	conversions['j'] = 'ĵ';
	conversions['K'] = 'Ķ';
	conversions['k'] = 'ķ';
	conversions['L'] = 'Ĺ|Ļ|Ľ|Ŀ|Ł';
	conversions['l'] = 'ĺ|ļ|ľ|ŀ|ł';
	conversions['N'] = 'Ñ|Ń|Ņ|Ň';
	conversions['n'] = 'ñ|ń|ņ|ň|ŉ';
	conversions['O'] = 'Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ';
	conversions['o'] = 'ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º';
	conversions['R'] = 'Ŕ|Ŗ|Ř';
	conversions['r'] = 'ŕ|ŗ|ř';
	conversions['S'] = 'Ś|Ŝ|Ş|Š';
	conversions['s'] = 'ś|ŝ|ş|š|ſ';
	conversions['T'] = 'Ţ|Ť|Ŧ';
	conversions['t'] = 'ţ|ť|ŧ';
	conversions['U'] = 'Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ';
	conversions['u'] = 'ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ';
	conversions['Y'] = 'Ý|Ÿ|Ŷ';
	conversions['y'] = 'ý|ÿ|ŷ';
	conversions['W'] = 'Ŵ';
	conversions['w'] = 'ŵ';
	conversions['Z'] = 'Ź|Ż|Ž';
	conversions['z'] = 'ź|ż|ž';
	conversions['AE'] = 'Æ|Ǽ';
	conversions['ss'] = 'ß';
	conversions['IJ'] = 'Ĳ';
	conversions['ij'] = 'ĳ';
	conversions['OE'] = 'Œ';
	conversions['f'] = 'ƒ';
	// conversions["â"] = '’';
	for (var i in conversions) {
		var re = new RegExp(conversions[i], 'g');
		str = str.replace(re, i);
	}
	return str;
}

var UidIdWhoIsWatching = 0;

if (typeof user_login_id !== 'undefined' && user_login_id !== '') {
	UidIdWhoIsWatching = user_login_id;
}
var WatchlistName = 'my_watch_history_' + UidIdWhoIsWatching;

function save_watch_list() {
	if (WatchlistName in localStorage) {
		var saveList = get('save_watch_history');
		var watch_history = JSON.parse(get(WatchlistName));
		watch_history = watch_history.filter(function (e) { return e != null; });
		if (saveList == 0 || saveList == null) {
			$.post(base_url + "/home/saveWatchHistory", { watch_history: JSON.stringify(watch_history) }, function (result) {
				let r = JSON.parse(result);
				if (r.status == 1)
					store('save_watch_history', 1);
			});
		}
	}
}

if (window.location.href.indexOf('embedcv') == -1) {
	setTimeout(function () {
		save_watch_list();
	}, 1000);
}

$(document).on('click', '.CloseHighLightPopup', function () {
	let _this = $(this);
	store('ShowPopup', 1);
	if (_this.data('target') == 'signup') {
		redirect('sign-up');
	} else {
		$('.dis_heighlight_popup').removeClass('popup_show');
	}
});

function redirect(url, time = 2000) {
	setTimeout(() => {
		window.location.href = base_url + url;
	}, time);
}

/***************** Get direct tab by url  ******************/
function isJSON(str) {
	try {
		return JSON.parse(str) && !!str;
	} catch (e) {
		return false;
	}
}

function nl2br(str, is_xhtml) {
	if (typeof str === 'undefined' || str === null) {
		return '';
	}
	var breakTag =
		is_xhtml || typeof is_xhtml === 'undefined' ? '<br />' : '<br>';
	return (str + '').replace(
		/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
		'$1' + breakTag + '$2'
	);
}

$(document).on('click', '#show_more', function () {
	$('.nav-tabs a[href="#detailDescription"]').tab('show');
});

$(document).on('click', '.delete_channel_video', function () {
	var _this = $(this);
	confirm_popup_function(
		'Delete',
		'Are you sure you want to delete this video?',
		'delete_channel_video(' + _this.attr('data-post_id') + ')'
	);
});



$(document).on('mouseleave', '.play_preview_common', function () {
	$($(this)).find('.dis_previewvideo video source').attr('src', '');
});

$(document).on('mouseover', '.play_preview_common', function () {
	if (IsMobileDevice()) {
		let video_url = $(this).find('.dis_overlay a').attr('href');
		if ($('.search_video_data').length > 0) {
			video_url = $(this).find('.search_video_data a').attr('href');
		}
		window.location.href = video_url;
	} else {
		getVideoSrc($(this));
	}
});

$(document).on('mouseover', '.au_artist_slider', function (e) {
	let swiperIndex = $(this).index('.au_artist_slider');
	swiper?.[swiperIndex]?.stopAutoplay();
});
$(document).on('mouseleave', '.au_artist_slider', function () {
	let swiperIndex = $(this).index('.au_artist_slider');
	swiper?.[swiperIndex]?.startAutoplay();
});

function getVideoSrc(_this) {
	// console.log(_this.attr('data-preview-src'));
	let previewSrc = _this.attr('data-preview-src');
	$(_this).find('.dis_previewvideo video source').attr('src', previewSrc);
	$(_this).find('.dis_previewvideo video').load();
	//$(_this).find('.dis_previewvideo video source').play();
}

$(document).on('change', '[name="mySource"]', function () {
	if ($(this).val() == 'other') {
		$('#sourceField').removeClass('hide');
	} else {
		$('#sourceField').addClass('hide');
	}
});

$(document).on('click', '#SkipPopup', function () {
	store('popup', 1);
	$('#HowIComeONDis').modal('hide');
	$('.modal-backdrop').remove();
	submit_how_to_discovered_us('skip');
});

$(document).on('click', '#SubmitPopup', function () {
	if (user_login_id != '') {
		let mySource = '',
			myCustSource = $('[name="myCustSource"]').val();

		$('[name="mySource"]').each(function () {
			if ($(this).prop('checked') == true) {
				mySource = $(this).val();
			}
		});

		myCustSource = mySource == 'other' ? myCustSource : '';

		submit_how_to_discovered_us(mySource, myCustSource);
	}
});
function submit_how_to_discovered_us(mySource, myCustSource = '') {
	let f = new FormData();
	f.set('mySource', mySource);
	f.set('myCustSource', myCustSource);

	manageMyAjaxPostRequestData(
		f,
		base_url + 'dashboard/submit_howtodiscoveredus'
	).done(function (resp) {
		let r = JSON.parse(resp);
		if (r.status == 1) {
			Custom_notify('success', 'Thanks for your feedback !');
			// store('popup', 1);
			$('#HowIComeONDis').modal('hide');
			$('.modal-backdrop').remove();
		} else {
			Custom_notify('error', r.message);
		}
	});
}

function delete_channel_video(post_id) {
	var formData = new FormData();
	formData.append('post_id', post_id);

	if ($('#feature_area' + post_id).length) {
		$('#feature_area' + post_id)
			.empty()
			.html(cnah);
	}
	$('#conf_btn')
		.text('Deleting ')
		.append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
		.prop('disabled', true);

	manageMyAjaxPostRequestData(
		formData,
		base_url + 'dashboard/DeleteChannelVideo'
	).done(function (resp) {
		if (resp != 0) {
			$('.modal').modal('hide');

			$(document)
				.find("[data-post_delete_id='" + post_id + "']")
				.remove();

			if ($('#feature_area' + post_id).length) {
				$('#feature_area' + post_id)
					.empty()
					.html(cnah);
			}
			$('.dis_select_video').show();

			$('#conf_btn').text('Delete').prop('disabled', false);

			// swiper.forEach()
			$('div.swiper-container').each(function (index, item) {
				let thhs = $(this);
				// console.log(item);
				swiper[index].update();
			});
			setTimeout(function () {
				success_popup_function('Your video has been deleted');
				if ($('.dataTableAjax')) $('.dataTableAjax').DataTable().ajax.reload();
			}, 1000);
		} else {
			server_error_popup_function('something went wrong, please try again');
		}
	});
}

function allowAlphaNumericSpace(e) {
	var code = 'charCode' in e ? e.charCode : e.keyCode;
	if (
		!(code == 32) && // space
		!(code > 47 && code < 58) && // numeric (0-9)
		!(code > 64 && code < 91) && // upper alpha (A-Z)
		!(code > 96 && code < 123)
	) {
		// lower alpha (a-z)
		e.preventDefault();
	}
}

$(document).on('click', '#close_popup_chat', function (e) {
	$('#popup_chat').fadeOut();
});

function checkProfanityWords(description, title) {
	return new Promise(function (resolve, reject) {
		$.get(base_url + 'repo_admin/txt/bad_words.txt', function (badtxt) {
			badtxt = badtxt.split(' ');
			badtxtlength = badtxt.length;
			const hasWord = (str, word) =>
				str
					.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, ' ')
					.split(/\s+/)
					.includes(word);
			for (let i = 0; i < badtxtlength; i++) {
				if (hasWord(description, badtxt[i]) || hasWord(title, badtxt[i])) {
					return resolve({
						status: 0,
						msg:
							'Your title/description contains some profanity words as : ' +
							badtxt[i]?.toUpperCase() +
							', So please remove it before submit.',
					});
				}
			}
			return resolve({ status: 1 });
		});
	});
}

var Parentable, _table_area;

$(document).on('click', '.openModalPopup', function () {
	let _this = $(this),
		_table_area = _this.parents('._table_area'),
		url = _this.attr('data-href'),
		cls = _this.attr('data-cls');

	if (_table_area.length > 0) Parentable = _table_area.find('table');

	if (url)
		$('#myCommonModal')
			.find('.modal-content')
			.empty()
			.load(base_url + url);
	$('#myCommonModal')
		.find('.modal-dialog')
		.removeClass()
		.addClass('modal-dialog');
	$('#myCommonModal')
		.removeClass($('#myCommonModal').attr('class'))
		.addClass('modal');

	if (cls) $('#myCommonModal').addClass(cls);
	$('#myCommonModal').modal({ backdrop: 'static', keyboard: false }, 'show');

	$('body').css('overflow', 'hidden');
	
	
	setTimeout(function () {
		if($("#login-recaptcha").length > 0){
			renderRecaptcha('login-recaptcha');
		}
	}, 1000);
	
});

setTimeout(function () {
	if($("#signup-recaptcha").length > 0){
		renderRecaptcha('signup-recaptcha');
	}

	if($("#forgot-recaptcha").length > 0){
		renderRecaptcha('forgot-recaptcha');
	}
	if($("#gamepass-recaptcha").length > 0){
		renderRecaptcha('gamepass-recaptcha');
	}

}, 1000);

var recaptchaWidgets = {}; // Store the widget instances for both forms
 // Common function to render reCAPTCHA for both Signup and Login
 function renderRecaptcha(recaptchaDivId) {
	grecaptcha.ready(function() {
		// Render reCAPTCHA only if it's not rendered yet
		if (!recaptchaWidgets[recaptchaDivId]) {
			recaptchaWidgets[recaptchaDivId] = grecaptcha.render(recaptchaDivId, {
				sitekey: RECAPTCHA_SITE_KEY, // Replace with your reCAPTCHA site key
				/*callback: function(response) {
					
					console.log(modalType + " reCAPTCHA response:", response);
				}*/
			});
		}
	});
}

// Reset reCAPTCHA widget (for the modal being closed)
function resetRecaptcha(modalType) {
	if (recaptchaWidgets[modalType]) {
		delete recaptchaWidgets[modalType];
		grecaptcha.reset(recaptchaWidgets[modalType]);
	}
}


// Get reCAPTCHA response for either Signup or Login by ID
function getRecaptchaResponse(modalType) {
	// Use the modalType ('signup' or 'login') to get the corresponding widget ID
	var response = grecaptcha.getResponse(recaptchaWidgets[modalType]);
	//console.log(modalType + " reCAPTCHA response:", response);
	return response;
}

$('#myCommonModal').on('hidden.bs.modal', function () {
	$('body').css('overflow', 'auto');
	resetRecaptcha('login-recaptcha');
});

$('#gamepass_email_popup').on('hidden.bs.modal', function () {
	resetRecaptcha('gamepass-recaptcha');
});

$(document).on('click', '.pause_sneak_peak', function () {
	$('video.play_sneak_peak')[0].pause();
	$('video.play_sneak_peak').prop('muted', true);
});

var getMenuRequest = true;
$(document).on('mouseover', '#getBrowseMenu', function () {
	if (getMenuRequest) {
		$('#browseMenuAppend')
			.empty()
			.load(base_url + 'modal/browse_header_menu');
		getMenuRequest = false;
	}
});

$(document).on('click', '.remove_profile_video', function () {
	let text = 'Remove';
	let subtext = 'Are you really want to remove your social video ?';
	let functions = 'RemoveMyProfile("video")';
	confirm_popup_function(text, subtext, functions);
});

function RemoveMyProfile(type) {
	$('#conf_btn')
		.text('Removing ')
		.append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
		.prop('disabled', true);
	let formData = new FormData();
	formData.append('type', type);
	manageMyAjaxPostRequestData(
		formData,
		base_url + 'dashboard/RemoveMyProfile'
	).done(function (resp) {
		if (resp.status == 1) {
			location.reload(true);
		} else {
			$('#confirm_popup').modal('hide');
			server_error_popup_function(resp.message);
		}
	});
}

function intializeSelect2AutoComplete() {
	loadScript(base_url + 'repo_admin/js/select2.min.js', function () {
		loadStyle(base_url + 'repo_admin/css/select2.min.css', function () {
			$('.js-data-ajax').each((i) => {
				$('.js-data-ajax')
					.eq(i)
					.select2({
						tags: true,
						ajax: {
							url: base_url + $('.js-data-ajax').eq(i).attr('data-ajax--url'),
							dataType: 'json',
							method: 'POST',
							delay: 250,
							placeholder: $('.js-data-ajax').eq(i).attr('data-placeholder'),
							data: function (params) {
								var query = {
									search: params.term,
								};
								return query;
							},
							processResults: function (res) {
								let result = [];
								$.each(res.data.list, function (key, item) {
									if (item.name.length)
										result.push({ id: item.id, text: item.name });
								});
								return {
									results: result,
								};
							},
						},
					});
			});
		});
	});
}

function getLocalTime(date) {
	let stillUtc = moment.utc(date).toDate();
	let local = moment(stillUtc).local().format('YYYY/MM/DD HH:mm:ss');
	return new Date(local).toLocaleDateString('en-us', {
		year: 'numeric',
		month: 'short',
		day: 'numeric',
	});
}

function addKeyWordInName(path, keyword) {
	let path_arr = path.split('/');
	let name = path_arr.pop();
	let name_arr = name.split('.');
	let new_name = name_arr[0] + keyword + '.' + name_arr[1];
	let new_path = path_arr.join('/') + '/' + new_name;

	return new_path;
}

// CHECK IF IMAGE EXISTS
function checkIfImageExists(url, callback) {
	const img = new Image();
	img.src = url;

	if (img.complete) {
		callback(true);
	} else {
		img.onload = () => {
			callback(true);
		};

		img.onerror = () => {
			callback(false);
		};
	}
}

const MINUTE = 60;
const HOUR = MINUTE * 60;
const DAY = HOUR * 24;
const WEEK = DAY * 7;
const MONTH = DAY * 30;
const YEAR = DAY * 365;

function getTimeAgo1(date) {
	let stillUtc = moment.utc(date).toDate();
	let local = moment(stillUtc).local().format('YYYY/MM/DD HH:mm:ss');

	date = new Date(Date.parse(local)).getTime();
	const secondsAgo = Math.round((Date.now() - Number(date)) / 1000);

	if (secondsAgo < MINUTE) {
		// return secondsAgo + ` second${secondsAgo !== 1 ? "s" : ""} ago`;
		return 'Just Now';
	}

	let divisor;
	let unit = '';

	if (secondsAgo < HOUR) {
		[divisor, unit] = [MINUTE, 'minute'];
	} else if (secondsAgo < DAY) {
		[divisor, unit] = [HOUR, 'hour'];
	} else if (secondsAgo < WEEK) {
		[divisor, unit] = [DAY, 'day'];
	} else if (secondsAgo < MONTH) {
		[divisor, unit] = [WEEK, 'week'];
	} else if (secondsAgo < YEAR) {
		[divisor, unit] = [MONTH, 'month'];
	} else {
		[divisor, unit] = [YEAR, 'year'];
	}

	const count = Math.floor(secondsAgo / divisor);
	return `${count} ${unit}${count > 1 ? 's' : ''} ago`;
}

function partOfString(string, start = 0, end = 500) {
	let subString =
		string.length < end
			? string
			: string.substr(start, end) + '<span>...</span>';
	if (string.length > end) {
		subString +=
			'<span class="more_text_content" style="display:none;">' +
			string.substr(end) +
			'</span>';
		subString +=
			'<a href="javascript:;" class="more_text dis_vc_read">Read More</a>';
	}
	return subString;
}

/************** Read More STARTS ************************/

$(document).on('click', '.more_text', function () {
	let ths = $(this);
	if (ths.text() == 'Read More') {
		ths.text('..Read Less');
		ths.prev('span.more_text_content').css({ display: 'inline' });
		ths.prev().prev().hide();
	} else {
		ths.text('Read More');
		ths.prev('span.more_text_content').css({ display: 'none' });
		ths.prev().prev().show();
	}
});
/************** Read More END ************************/

/************** Start Trigger socket_chat.js and popup msg ************************/
var socketjs = false;
$(document).on('click', '#header_message_icon', function () {
	if (socketjs) {
		triggerMsg();
	} else {
		loadScript(CDN_BASE_URL + MOMENT_JS, function () {
			loadScript(base_url + SOCKET_CHAT_JS, function () {
				triggerMsg();
				socketjs = true;
			});
		});
	}
	function triggerMsg() {
		let skeleton = '';
		for (let i = 0; i < 5; i++) {
			skeleton +=
				'<div class="dis_skeleton"><div class="dis_skeleton_left"><div class="dis_skeletonCircle"></div></div><div class="dis_skeleton_right"><div class="dis_skeleton_line"></div><div class="dis_skeleton_line"></div></div></div>';
		}
		$('#show_message').html(skeleton);
		load_popup();
	}
});
/************** End Trigger socket_chat.js and popup msg ************************/

$(document).on('click', '.ShowCastNCrew', function () {
	$(this).removeClass('ShowCastNCrew');
	let f = new FormData();
	f.set('pid', $(this).attr('data-post_id'));
	manageMyAjaxPostRequestData(f, base_url + 'player/getCastCrew/').done(
		function (response) {
			response = JSON.parse(response);
			if (response.status == 1) {
				$('#castandcrewhtml').html(response.data);
				$('.delete_cast_img').hide();
				$('.dis_edit_cast_icon ').hide();
			}
		}
	);
});

$(document).on('click', '.ShowEpisode', function () {
	$(this).removeClass('ShowEpisode');
	let f = new FormData();
	f.set('playlist_id', $(this).attr('data-playlist_id'));
	manageMyAjaxPostRequestData(
		f,
		base_url + 'dashboard/getMyPlaylistVideo'
	).done(function (response) {
		response = JSON.parse(response);
		if (response.status == 1) {
			let resData = response.data;
			//$href =  $user_uname ? 'href="'.base_url('profile?user='.$user_uname).'"' : 'style="pointer-events: none"';
			let playlistVideo = resData['playlist_video'];
			let episodeHtml = '';
			if (playlistVideo.length > 0) {
				$.each(playlistVideo, function (i) {
					var value = playlistVideo[i];
					episodeHtml += `<li>
								<div class="dis_cast_data">
									<div class="dis_cast_img">
										<img src="${value.img}" class="img-responsive" alt="" onError="this.onerror=null;this.src='${value.errimg}'">
										<div class="dis_overlay">
										</div>
									</div>
									<div class="dis_cast_content">
										<a href="${value.PlaylistUrl}" class="dis_CCB_username">${value.title}</a>
										<!--h3 class="dis_CCB_scriptname">As '.$cast_script_name .'</h3-->
									</div>
								</div>
							</li>`;
				});
			}
			$('#episodeHtml').html(episodeHtml);
			$('.delete_cast_img').hide();
			$('.dis_edit_cast_icon ').hide();
		}
	});
});

$(document).ready(function () {
	window.initOpenAi = () => {
		if ($('#publish_input').length || $('#description').length) {
			var idName = $('#publish_input').length ? 'publish_input' : 'description';
			var className = $('#publish_input').length ? 'socialpage_ai' : 'monetizepage_ai';
			$('#openAiBox').html(ShowAiPrompt(className, idName));
			initSelect2()

			document.onselectionchange = () => {
				let selection = document.getSelection();
				let classlist = selection?.anchorNode?.classList?.value;

				if (classlist?.includes('dis_textare_div') || classlist?.includes('description')) {
					let textarea = document.getElementById(idName);
					let start = textarea.selectionStart;
					let finish = textarea.selectionEnd;
					let content = textarea.value.substring(start, finish);
					if (content) {
						$('[name="content"]').val(content);

						$('.dis_ai_text_btn').addClass('tada_animation');
						setTimeout(function () {
							$('.dis_ai_text_btn').removeClass('tada_animation');
						}, 3000)
					}
				}
			};
		}

		if (typeof CKEDITOR !== 'undefined') {
			CKEDITOR?.on('instanceReady', function (event) {
				event.editor.on('selectionChange', function () {
					let index = this.name.replace('editor', '');
					let content = this.getSelection().getSelectedText();
					if (content.trim()) {
						$('[name="content"]')?.eq(parseInt(index))?.val(this.getSelection().getSelectedText());

						$('.dis_ai_text_btn').eq(index).addClass('tada_animation');
						setTimeout(function () {
							$('.dis_ai_text_btn').eq(index).removeClass('tada_animation');
						}, 3000)
					}

				});
			});
		}

	}
	initOpenAi();
})

function ShowAiPrompt(className = '', idName = '') {
	return `<div class="dis_ai_text_main ${className}" >
				<span class="dis_ai_text_btn dis_ai_toggle" data-toggle="tooltip" data-placement="top" title="AI Text Generation">
					<img src="${base_url + 'repo/images/ai_btn.svg'}"/>
				</span>
				<div class="dis_ai_text_box_wrap">
					<div class="dis_ai_tb_header">
						<h1 class="dis_ai_tbh_text mp_0">AI Text Generator</h1>
						<span class="dis_ai_tbh_cross dis_ai_toggle">
							<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2.10878 0.361809C1.62637 -0.120603 0.844222 -0.120603 0.361809 0.361809C-0.120603 0.844222 -0.120603 1.62637 0.361809 2.10878L3.25303 5L0.361809 7.89122C-0.120603 8.37363 -0.120603 9.15578 0.361809 9.63819C0.844222 10.1206 1.62637 10.1206 2.10878 9.63819L5 6.74697L7.89122 9.63819C8.37363 10.1206 9.15578 10.1206 9.63819 9.63819C10.1206 9.15578 10.1206 8.37363 9.63819 7.89122L6.74697 5L9.63819 2.10878C10.1206 1.62637 10.1206 0.844222 9.63819 0.361809C9.15578 -0.120603 8.37363 -0.120603 7.89122 0.361809L5 3.25303L2.10878 0.361809Z" fill="#9C9DA3"/>
							</svg>
						</span>
					</div>
					<div class="dis_ai_tb_body">
						<div class="dis_ai_typeBox">
							<div class="dis_ai_inputWrap position-relative">
								<textarea name="content" rows="1" class="dis_ai_fieldInput checkForChange" placeholder="Enter prompt here to generate text"></textarea>
								<span class="dis_ai_inpitEdit">
									<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M11.1205 3.61359L11.4163 3.31779C11.784 2.95003 11.9839 2.45436 11.9839 1.9427C11.9839 1.42305 11.776 0.935376 11.4163 0.567622C11.0485 0.199867 10.5528 0 10.0412 0C9.52153 0 9.03386 0.207861 8.6661 0.567622L8.3703 0.863424L11.1205 3.61359ZM7.85064 1.38308L0.383622 8.85809C0.327659 8.91406 0.287686 8.994 0.279692 9.08195L-0.00012207 11.5923C-0.00012207 11.6482 -0.000121997 11.7042 0.023862 11.7522C0.0398514 11.8001 0.07183 11.8481 0.111803 11.8881C0.151777 11.928 0.199745 11.96 0.247713 11.976C0.295681 11.992 0.351643 12 0.407606 12L2.91793 11.7202C2.99788 11.7122 3.07783 11.6722 3.14178 11.6163L10.6008 4.13324L7.85064 1.38308Z" fill="#9C9DA3"/>
									</svg>
								</span>
							</div>
							<textarea style="display:none" class="dis_ai_fieldInput" id="${idName + 't'}"></textarea>
							<div class="dis_ai_typeoption">
								<ul class="dis_ai_optionList">
									<li>
										<div class="dis_field_wrap dis_select2">
											<select name="max_token" class="dis_field_input checkForChange" data-target="select2" data-option="{minimumResultsForSearch: -1}">
												<option value="50">Short</option>
												<option value="100">Medium</option>
												<option value="150">Long</option>
											</select>
										</div>
									</li>
									<!--li>
										<div name="top_p" class="dis_field_wrap dis_select2 checkForChange">
											<select class="dis_field_input" data-target="select2" data-option="{minimumResultsForSearch: -1}">
												<option value="1">Tone1</option>
												<option value="2">Tone2</option>
												<option value="3">Tone3</option>
											</select>
										</div>
									</li-->
								</ul>
								<ul class="dis_ai_optionList">
									<li style="display:none">
										<a class="dis_ai_btns organe_trans inseOpenAiText" data-target="${idName}">
											Insert
										</a>
									</li>
									<li>
										<a class="dis_ai_btns organe_trans geneOpenAiText">
											<span class="">
												<svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M3.02399 2.91901L4.06455 3.48851C4.19814 3.56116 4.24735 3.72756 4.1747 3.86114C4.14892 3.90801 4.11142 3.94551 4.06455 3.97129L3.02399 4.53844C2.97712 4.56422 2.93962 4.60172 2.91384 4.64859L2.34435 5.68915C2.27169 5.82274 2.1053 5.87196 1.97171 5.7993C1.92484 5.77352 1.88734 5.73603 1.86156 5.68915L1.29441 4.64859C1.26863 4.60172 1.23113 4.56422 1.18426 4.53844L0.143702 3.97129C0.0101161 3.89864 -0.0390996 3.73224 0.0335521 3.59866C0.0593318 3.55179 0.0968295 3.51429 0.143702 3.48851L1.18426 2.91901C1.23113 2.89323 1.26863 2.85574 1.29441 2.80886L1.86391 1.77065C1.93656 1.63706 2.1053 1.58785 2.23888 1.66284C2.28341 1.68862 2.32091 1.72612 2.34669 1.77065L2.91619 2.80886C2.93962 2.85574 2.97712 2.89323 3.02399 2.91901ZM10.7696 8.47336L11.8102 9.04285C11.9438 9.11551 11.993 9.2819 11.9203 9.41549C11.8945 9.46236 11.857 9.49986 11.8102 9.52564L10.7696 10.0951C10.7227 10.1209 10.6852 10.1584 10.6595 10.2053L10.09 11.2458C10.0173 11.3794 9.84857 11.4286 9.71498 11.356C9.66811 11.3302 9.63061 11.2927 9.60483 11.2458L9.04003 10.2029C9.01425 10.1561 8.97675 10.1186 8.92988 10.0928L7.88932 9.52329C7.75573 9.45064 7.70651 9.2819 7.77917 9.14832C7.80495 9.10144 7.84244 9.06395 7.88932 9.03817L8.92988 8.46867C8.97675 8.44289 9.01425 8.40539 9.04003 8.35852L9.60952 7.3203C9.68217 7.18672 9.85091 7.1375 9.9845 7.21015C10.0314 7.23593 10.0689 7.27343 10.0946 7.3203L10.6641 8.36086C10.6852 8.41008 10.7227 8.44758 10.7696 8.47336ZM6.2652 1.72846L6.94719 2.10109C7.0339 2.15031 7.06671 2.26046 7.01984 2.34717C7.00343 2.37764 6.97765 2.40108 6.94719 2.41983L6.2652 2.79246C6.23473 2.80886 6.20895 2.83464 6.19255 2.86511L5.81991 3.5471C5.7707 3.63381 5.66055 3.66662 5.57383 3.61975C5.54337 3.60335 5.51993 3.57757 5.50118 3.5471L5.12855 2.86511C5.11214 2.83464 5.08636 2.80886 5.0559 2.79246L4.37391 2.41983C4.2872 2.37295 4.25438 2.2628 4.30126 2.17375C4.31766 2.14328 4.34344 2.1175 4.37391 2.10109L5.0559 1.72846C5.08636 1.71206 5.11214 1.68628 5.12855 1.65581L5.50118 0.973821C5.5504 0.887107 5.66055 0.854297 5.74726 0.901169C5.77773 0.917574 5.80116 0.943354 5.81991 0.973821L6.19255 1.65581C6.20895 1.68628 6.23473 1.71206 6.2652 1.72846ZM11.7328 2.04016L10.7696 1.07694C10.4134 0.720711 9.83451 0.718368 9.47594 1.0746L0.886624 9.66625C0.530396 10.0225 0.528053 10.6014 0.886624 10.9599L1.84985 11.9231C2.20607 12.2794 2.78494 12.2817 3.14352 11.9231L11.7328 3.33383C12.0891 2.9776 12.0891 2.39873 11.7328 2.04016ZM8.38382 5.2884L7.52137 4.42595L10.0642 1.8808L10.9266 2.74324L8.38382 5.2884Z" fill="#EB581F"/>
												</svg>
											</span>
											Generate
										</a>
									</li>
									<li style="display:none">
										<a class="dis_ai_btns organe_trans minib_btn" onclick="$(this).parent().prev().find('.geneOpenAiText').prop('disabled',false).click()">
											<span class="">
												<svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M6.2924 2.22155V0.276844C6.29189 0.221839 6.30772 0.16792 6.33789 0.121923C6.36806 0.0759264 6.4112 0.0399222 6.46186 0.0184747C6.51334 -0.0027164 6.57 -0.00795382 6.62449 0.00344149C6.67898 0.0148368 6.72879 0.0423393 6.76747 0.0823833L9.82346 3.13837C9.8752 3.19042 9.90425 3.26084 9.90425 3.33423C9.90425 3.40763 9.8752 3.47804 9.82346 3.53009L6.76747 6.58609C6.7286 6.62526 6.67896 6.65197 6.62486 6.66281C6.57075 6.67366 6.51465 6.66816 6.46369 6.647C6.41273 6.62584 6.36922 6.58998 6.33872 6.544C6.30821 6.49802 6.29209 6.444 6.2924 6.38882V4.44412C4.10717 4.59014 2.40788 6.40273 2.40299 8.59281C2.39788 10.8943 4.25947 12.7642 6.56096 12.7693C8.86245 12.7744 10.7323 10.9128 10.7375 8.61133C10.7375 8.53766 10.7667 8.46699 10.8188 8.4149C10.8709 8.3628 10.9416 8.33353 11.0153 8.33353H12.6822C12.7558 8.33353 12.8265 8.3628 12.8786 8.4149C12.9307 8.46699 12.96 8.53766 12.96 8.61133C12.96 12.1403 10.0991 15.0011 6.57017 15.0011C3.0412 15.0778 0.118202 12.2792 0.0414991 8.75025C-0.0351715 5.22125 2.76343 2.29828 6.2924 2.22155Z" fill="#EB581F"/>
												</svg>
											</span>
										</a>
									</li>
									<li style="display:none">
										<a class="dis_ai_btns copyAiText minib_btn" data-target="#${idName + 't'}">
											<span class="">
												<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12.0836 0.5H5.7164C5.07576 0.500741 4.46157 0.755563 4.00857 1.20857C3.55556 1.66157 3.30074 2.27576 3.3 2.9164V3.3H2.9164C2.27576 3.30074 1.66157 3.55556 1.20857 4.00857C0.755563 4.46157 0.500741 5.07576 0.5 5.7164V12.0836C0.500741 12.7242 0.755563 13.3384 1.20857 13.7914C1.66157 14.2444 2.27576 14.4993 2.9164 14.5H9.2836C10.5072 14.5 11.511 13.5816 11.6678 12.4H12.0836C12.7242 12.3993 13.3384 12.1444 13.7914 11.6914C14.2444 11.2384 14.4993 10.6242 14.5 9.9836V2.9164C14.4993 2.27576 14.2444 1.66157 13.7914 1.20857C13.3384 0.755563 12.7242 0.500741 12.0836 0.5ZM13.1 9.9836C13.1 10.5436 12.6443 11 12.0836 11H11.7V5.7164C11.6993 5.07576 11.4444 4.46157 10.9914 4.00857C10.5384 3.55556 9.92424 3.30074 9.2836 3.3H4.7V2.9164C4.7 2.3564 5.1557 1.9 5.7164 1.9H12.0836C12.6436 1.9 13.1 2.3557 13.1 2.9164V9.9836Z" fill="white"/>
												</svg>
											</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<p class="dis_ai_note">*Disclaimer: This response is AI-generated and may not reflect human opinions or sentiments.</p>
					</div>
				</div>
			</div>`;
}

$(document).on('click', '.geneOpenAiText', function (event) {
	event.preventDefault();
	let target = $(this);
	let selector = target.parents('.dis_ai_typeBox');

	let f = new FormData();
	f.set('content', selector.find('textarea:first').val());
	f.set('max_token', selector.find('select:first').val());
	f.set('tone', selector.find('select:last').val());

	manageMyAjaxPostRequestData(
		f,
		base_url + 'settings/geneOpenAiText'
	).done(function (response) {
		console.log(response, 'response');
		if (response.status == 1) {
			$(selector.find('textarea:last')).show().val((response.data.choices[0].message.content)).attr('readonly', 'readonly');

			target.parent().prev().show();
			target.parent().next().show();
			target.parent().next().next().show();
			target.parent().hide();
		}
	});
});

$(document).on('change keyup', '.checkForChange', function (event) {
	$(this).parents('.dis_ai_typeBox').find('.dis_ai_optionList:last').find('li').eq(0).hide()
	$(this).parents('.dis_ai_typeBox').find('.dis_ai_optionList:last').find('li').eq(1).show()
	$(this).parents('.dis_ai_typeBox').find('.dis_ai_optionList:last').find('li').eq(2).hide()
})

$(document).on('click', '.copyAiText', function (event) {
	let _this = $(this);
	let outerHtml = _this.find('span')[0].outerHTML;
	_this.find('span').html('Copied');
	_this.find('span').css('color', 'white');

	clipboard(_this);
	document.execCommand('copy');
	setTimeout(function () {
		_this.html(outerHtml)
	}, 1000);
})

$(document).on('click', '.inseOpenAiText', function (event) {
	let source = $(this);
	let selector = source.parents('.dis_ai_typeBox');
	let content = selector.find('textarea:last').val().trim();

	if (content.length > 0) {
		let target = source.attr('data-target');

		if (typeof CKEDITOR !== 'undefined')
			CKEDITOR.instances[target]?.setData(content.replace(/(?:\r\n|\r|\n)/g, '<br>'));

		$('#' + target)?.val(content);

		source.parent().hide();
		source.parent().next().show();
		source.parent().next().next().hide();
		source.parent().next().next().next().hide();
		$('.dis_ai_text_main').removeClass('open');
	}
})

