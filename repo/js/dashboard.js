
if ($('#publish_post').length) {
	loadScript(TWEMOJI_JS);
}
$(document).ready(function () {
	if ($('#publish_post').length) {
		var Poststart = 0, Postlimit = 9, ControlRequest = false;

		let pub_uid = $('#data-user_id').data('user_id');
		let formData = new FormData();

		formData.set("pub_uid", pub_uid);
		formData.set("social", $('#data-social').data('social'));
		formData.set("start", Poststart);
		formData.set("limit", Postlimit);

		manageMyAjaxPostRequestData(formData, base_url + 'dashboard/GetPublishPost').done(function (resp) {
			resp = JSON.parse(resp);

			if (resp.status == 1) {
				let resData = resp.data;
				AddDynamicAds('#publish_post', 'html')
				if (resData.length) {

					$.each(resData, function (index, value) {
						setTimeout(function () {

							if ((value.post).trim().length > 0) {
								$('#publish_post').append(value.post);
								$('.dis_user_post_data').find('.dis_user_post_data').find('.dis_user_post_footer').remove();
								intializeVideoJsContent('last');
							}
						}, 200)
					})
					ControlRequest = true;

					if (pub_uid == user_login_id) getUserYouMayKnowSlider();

					setTimeout(function () {
						$('.contentText').each(function (index) {

							richLinkCode($(this));
							if (!IsMobileDevice() && twemoji) 
								twemoji?.parse(document.querySelectorAll(".contentText")[index], { folder: '72x72', ext: '.png', });

						})
					}, 1000);

				} else {
					$('.dis_loadmore_loader').html(dataNotFound);
				}
			} else {
				$('.dis_loadmore_loader').html(dataNotFound);
			}
		})

		var loader = $('.pro_loader');
		$(window).scroll(function () {
			if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
				if (ControlRequest && $('#home').hasClass('active')) {
					ControlRequest = false;
					if (loader.attr('data-load') == 1) {
						Poststart += Postlimit
						formData.set("start", Poststart);
						formData.set("limit", Postlimit);
						loader.show();
						$('.close_btn').trigger('click');
						manageMyAjaxPostRequestData(formData, base_url + 'dashboard/GetPublishPost').done(function (resp) {
							resp = JSON.parse(resp);
							if (resp.status == 1) {
								let resData = resp.data;
								if (resData.length) {
									$.each(resData, function (index, value) {
										setTimeout(function () {
											if ((value.post).trim().length > 0) {
												$('#publish_post2').append(value.post);
												$('.dis_user_post_data').find('.dis_user_post_data').find('.dis_user_post_footer').remove();
												intializeVideoJsContent('last');
												ControlRequest = true;
											}
											richLinkCode($('.contentText').last());
											if (!IsMobileDevice()) {
												let nodes = document.querySelectorAll(".contentText");
												twemoji?.parse(nodes[nodes.length - 1], { folder: '72x72', ext: '.png', });
											}

										}, 200)
									});
									$('.commment_p').find('img').removeClass('_PostEmoji');
								} else {
									loader.attr('data-load', 0);
									$('.dis_loadmore_loader').text('-- No more data available --');
								}
								AddDynamicAds('#publish_post2', 'append');
								// checkAdsHide();
							} else {
								location.href = base_url;
							}
							loader.hide();
						})
					}
				}
			}
		});

		function getMyFanCounts() {
			let uid = $('#data-user_id').attr('data-user_id');
			$.post(node_url + 'getMyFanCounts', { user_id: uid, cate_id: '' }, function (data, status) {
				if(typeof data === 'object' || Array.isArray(data)){
					data.forEach((element, index) => {
						$('#' + element.category_name).text(`(${element.usersWhoFollowedMe})`);
					});
				}
				
			});
		}
		getMyFanCounts();
	}

	$("[data-length]").keyup(function () {
		let ths = $(this), leng, clength;
		leng = ths.data('length');
		clength = (ths.val()).length;
		$('#input').show().text(leng - clength);
	});

	$(document).on('click', '.load_post_content', function () {
		let _this = $(this);

		if (_this.data('type') == 'photo') {

			if (_this.hasClass('mob_hide')) {
				_this.attr({ 'aria-controls': 'media', 'role': 'tab', 'data-toggle': 'tab' }).removeClass('load_post_content');

				$('li[role="presentation"]').not('.media_tab').removeClass('active');
				$('a[href="#media"]').parents('li').addClass('active');
				$('.tab-pane').not('.notab-pane').removeClass('active');

				$('#media').addClass('active');

				$(".load-content").first().trigger('click');
			}

		} else {
			_this.removeClass('load_post_content');
			$(".load-content").last().trigger('click');
		}

	})

	var limit = 3;

	$(document).on('click', '.load-content', function () {
		var ths = $(this);
		var btnTxt = ths.text();
		ths.prop('disabled', true);
		ths.html('Loading  <i class="fa fa-spinner fa-spin post_spinner"></i>');

		let formData;
		formData = new FormData();
		formData.append("type", ths.attr('data-load-contnet'));
		formData.append("uid", $('#data-user_id').attr('data-user_id'));

		formData.delete("start");
		formData.delete("limit");

		let contnetCount = parseInt(ths.attr('data-load-contnet-count'));

		if (contnetCount == 0)
			$('#' + ths.attr('data-id')).html();

		formData.append("start", contnetCount);
		formData.append("limit", (formData.get('type') == 'video' ? limit - 1 : limit));


		manageMyAjaxPostRequestData(formData, base_url + 'dashboard/GetPublishContent').done(function (resp) {
			ths.show();

			if (contnetCount == 0)
				$('#' + ths.attr('data-id')).empty();


			resp = JSON.parse(resp);
			if ((resp.count) > 0) {

				ths.attr('data-load-contnet-count', contnetCount + (formData.get('type') == 'video' ? limit - 1 : limit))

				$('#' + ths.attr('data-id')).append(resp.str);

				magnificPopupImage();
				magnificPopupvideo();

				if (resp.count < (formData.get('type') == 'video' ? limit - 1 : limit)) {
					ths.hide();
				}
				ths.prop('disabled', false);
				ths.html(btnTxt);
				$("ul#" + ths.attr('data-id')).animate({ scrollTop: $('ul#' + ths.attr('data-id') + ' li:last').offset().top - 30 }, 2000);
			} else {
				//$('#'+ths.attr('data-id')).append(dataNotFound);  
				ths.hide();
			}
		})
	})


	$(document).on('click', '.load-more-fan', function () {
		var ths = $(this);
		var btnTxt = ths.text();
		ths.prop('disabled', true);
		ths.html('Loading  <i class="fa fa-spinner fa-spin post_spinner"></i>');

		let formData;
		formData = new FormData();
		formData.append("type", ths.attr('data-load-contnet'));
		formData.append("uid", $('#data-user_id').data('user_id'));

		formData.delete("start");
		formData.delete("limit");

		let contnetCount = parseInt(ths.attr('data-load-contnet-count'));

		if (contnetCount == 0)
			$('#' + ths.attr('data-id')).html();

		formData.append("start", contnetCount);
		formData.append("limit", limit);


		manageMyAjaxPostRequestData(formData, base_url + 'dashboard/getMyFanList').done(function (resp) {
			ths.show();

			if (contnetCount == 0)
				$('#' + ths.attr('data-id')).empty();

			resp = JSON.parse(resp);
			if ((resp.count) > 0) {

				ths.attr('data-load-contnet-count', contnetCount + limit)

				$('#' + ths.attr('data-id')).append(resp.str);

				if (resp.count < limit) {
					ths.hide();
				}
				ths.prop('disabled', false);
				ths.html(btnTxt);

			} else {
				//$('#'+ths.attr('data-id')).append(dataNotFound);  
				ths.hide();
			}

		})
	})

	function loadFans() {

		let formData;
		formData = new FormData();
		formData.append("type", 'All');
		formData.append("uid", $('#data-user_id').data('user_id'));

		formData.delete("start");
		formData.delete("limit");

		let contnetCount = 0;
		formData.append("start", contnetCount);
		formData.append("limit", limit);

		manageMyAjaxPostRequestData(formData, base_url + 'dashboard/getMyFanList').done(function (resp) {
			resp = JSON.parse(resp);

			$.each(resp.fanData, function (key, val) {
				if (val !== '') {
					$('.load-more-fan').attr('data-load-contnet-count', contnetCount + limit)
					$('#' + key).append(val);
				} else {
					$('#' + key).parent().closest('div').hide();
				}

			});
			if (resp.fanData.brand_fan_count <= 3) {
				$("[data-id=brand_fan]").hide();
			}
			if (resp.fanData.emerging_fan_count <= 3) {
				$("[data-id=emerging_fan]").hide();
			}
			if (resp.fanData.icon_fan_count <= 3) {
				$("[data-id=icon_fan]").hide();
			}
			if (resp.fanData.fan_count <= 3) {
				$("[data-id=fan]").hide();
			}
			// if(resp.fanData.CreatorsYouEndorsingCount<=3){
			// 	$("[data-id=CreatorsYouEndorsing]").hide();
			// }
			// if(resp.fanData.BrandsYouEndorsingCount<=3){
			// 	$("[data-id=BrandsYouEndorsing]").hide();
			// }
			// if(resp.fanData.CreatorsEndorsingYouCount<=3){
			// 	$("[data-id=CreatorsEndorsingYou]").hide();
			// }
			// if(resp.fanData.BrandsEndorsingYouCount<=3){
			// 	$("[data-id=BrandsEndorsingYou]").hide();
			// }
		})
	}

	// Calling this function on load
	loadFans();

	$(document).on('click', '.load-content-old', function () {
		var ths = $(this);
		ths.prop('disabled', true);
		ths.find('a').html('Loading  <i class="fa fa-spinner fa-spin post_spinner"></i>');

		let formData;
		formData = new FormData();
		formData.append("type", ths.attr('data-load-contnet'));
		formData.append("uid", $('#data-user_id').data('user_id'));

		formData.delete("start");
		formData.delete("limit");

		let contnetCount = parseInt(ths.attr('data-load-contnet-count'));

		if (contnetCount == 0)
			$('#' + ths.attr('data-id')).html();

		formData.append("start", contnetCount);
		formData.append("limit", limit);


		manageMyAjaxPostRequestData(formData, base_url + 'dashboard/GetPublishContent').done(function (resp) {
			ths.show();

			if (contnetCount == 0)
				$('#' + ths.attr('data-id')).empty();


			resp = JSON.parse(resp);
			if ((resp.count) > 0) {

				ths.attr('data-load-contnet-count', contnetCount + limit)

				$('#' + ths.attr('data-id')).append(resp.str);

				$('.zoom_icon').magnificPopup({
					type: 'inline',
					fixedContentPos: false,
					fixedBgPos: true,
					overflowY: 'auto',
					midClick: true,
					gallery: {
						enabled: true,
						navigateByImgClick: true,
						preload: [0, 1]
					},

					removalDelay: 300,
					mainClass: 'my_zoom_in',
					callbacks: {
						open: function () {

						},
						close: function () {

						},
						change: function () {

						}
					}

				});

				if (resp.count < 3) {
					ths.hide();
				}
				ths.prop('disabled', false);
				ths.find('a').html('Show More');

			} else {
				$('#' + ths.attr('data-id')).append(dataNotFound);
				ths.hide();
			}
		})
	})


	function magnificPopupImage() {
		if ($('.sidebar_zoom').length) {
			$('.sidebar_zoom').magnificPopup({
				type: 'image',
				gallery: {
					enabled: true
				}
			});
		}
	}

	function magnificPopupvideo() {
		if ($('.play_video').length) {
			$(".play_video").magnificPopup({
				fixedContentPos: false,
				type: 'iframe',
				gallery: {
					enabled: true
				}
			});
		}
	}

	magnificPopupImage();
	magnificPopupvideo();

	$(document).on('click', '.Remove_profile_picture', function () {
		let text = 'Remove';
		let subtext = 'Are you really want to remove your profile picture ?';
		let functions = 'RemoveMyProfile("picture")';
		confirm_popup_function(text, subtext, functions)
	})

	$(document).on('click', '#EditAboutMe', function () {
		$('.artist_about_detail').addClass('hide')
		$('.about_body').removeClass('hide')
	})





});
function dataNotFound() {
	return `<div class="no_result_wraaper">
					<div class="no_result_inner">
						<svg xmlns="http://www.w3.org/2000/svg" width="144px" height="141px" class="no_resultsvg">
						<path class="a" fill-rule="evenodd" fill="rgb(232, 233, 234)" d="M102.927,67.238 C102.868,85.522 87.999,100.297 69.715,100.239 C51.430,100.180 36.656,85.311 36.714,67.027 C36.773,48.742 51.642,33.968 69.926,34.026 C88.210,34.085 102.985,48.954 102.927,67.238 Z"></path>
						<path class="b" fill-rule="evenodd" fill="rgb(189, 194, 203)" d="M142.570,51.942 L139.426,51.942 L139.426,55.084 C139.426,55.647 138.969,56.103 138.406,56.103 C137.843,56.103 137.387,55.647 137.387,55.084 L137.387,51.942 L134.243,51.942 C133.681,51.942 133.224,51.485 133.224,50.923 C133.224,50.360 133.681,49.904 134.243,49.904 L137.387,49.904 L137.387,46.761 C137.387,46.198 137.843,45.742 138.406,45.742 C138.969,45.742 139.426,46.198 139.426,46.761 L139.426,49.904 L142.570,49.904 C143.133,49.904 143.589,50.360 143.589,50.923 C143.589,51.485 143.133,51.942 142.570,51.942 ZM104.580,90.518 L136.892,123.739 C138.772,125.671 139.787,128.219 139.749,130.915 C139.711,133.611 138.624,136.130 136.689,138.009 C134.756,139.888 132.206,140.902 129.510,140.864 C126.815,140.825 124.295,139.740 122.414,137.806 L98.385,113.101 C98.384,113.100 98.382,113.098 98.381,113.097 L89.621,104.090 C84.913,106.619 79.742,108.234 74.339,108.822 C73.530,108.910 72.711,108.975 71.904,109.017 C61.657,109.542 51.580,106.270 43.533,99.805 C34.789,92.781 29.307,82.790 28.097,71.674 C26.887,60.557 30.091,49.622 37.118,40.882 C44.146,32.143 54.142,26.664 65.264,25.454 C76.387,24.245 87.326,27.447 96.070,34.471 C104.814,41.496 110.296,51.486 111.506,62.603 C112.400,70.815 110.871,79.021 107.084,86.332 C106.309,87.837 105.456,89.237 104.580,90.518 ZM126.034,134.289 C126.975,135.256 128.234,135.799 129.582,135.818 C130.929,135.837 132.204,135.330 133.171,134.390 C134.139,133.450 134.682,132.192 134.701,130.844 C134.720,129.497 134.213,128.223 133.273,127.257 L111.177,104.540 L103.939,111.573 L126.034,134.289 ZM100.411,107.945 L107.649,100.912 L101.466,94.555 C98.813,97.625 96.603,99.380 96.565,99.410 C95.738,100.098 94.887,100.750 94.015,101.369 L100.411,107.945 ZM106.621,63.134 C104.499,43.645 87.504,29.164 67.929,30.167 C67.221,30.203 66.504,30.261 65.795,30.338 C55.976,31.405 47.152,36.243 40.947,43.958 C34.743,51.674 31.914,61.328 32.982,71.142 C34.051,80.957 38.891,89.777 46.610,95.979 C54.330,102.181 63.987,105.007 73.808,103.939 C81.616,103.090 88.817,99.855 94.631,94.585 L94.665,94.554 C97.561,91.925 100.015,88.869 101.959,85.472 C105.858,78.663 107.470,70.939 106.621,63.134 ZM77.256,64.907 C76.232,64.904 75.358,64.539 74.636,63.812 C73.914,63.086 73.554,62.211 73.557,61.187 C73.560,60.164 73.926,59.291 74.652,58.569 C75.379,57.848 76.255,57.488 77.279,57.491 C78.303,57.495 79.176,57.860 79.899,58.586 C80.621,59.313 80.980,60.187 80.977,61.211 C80.974,62.235 80.609,63.107 79.882,63.829 C79.155,64.550 78.279,64.910 77.256,64.907 ZM80.400,78.271 C80.554,78.754 80.514,79.223 80.280,79.675 C80.047,80.128 79.689,80.431 79.205,80.584 C78.722,80.737 78.248,80.697 77.785,80.463 C77.322,80.230 77.014,79.862 76.862,79.360 C76.384,77.813 75.494,76.560 74.193,75.600 C72.892,74.640 71.430,74.157 69.807,74.152 C68.184,74.147 66.719,74.620 65.412,75.571 C64.105,76.523 63.207,77.770 62.719,79.315 C62.563,79.816 62.257,80.182 61.803,80.412 C61.348,80.643 60.880,80.680 60.397,80.524 C59.895,80.367 59.529,80.062 59.299,79.608 C59.068,79.154 59.031,78.685 59.187,78.203 C59.909,75.868 61.249,73.990 63.204,72.567 C65.161,71.144 67.365,70.437 69.819,70.445 C72.272,70.453 74.474,71.174 76.420,72.610 C78.367,74.045 79.694,75.932 80.400,78.271 ZM62.418,64.860 C61.394,64.856 60.521,64.491 59.798,63.765 C59.076,63.039 58.716,62.164 58.720,61.140 C58.723,60.117 59.088,59.244 59.815,58.522 C60.542,57.800 61.417,57.441 62.441,57.444 C63.465,57.447 64.338,57.812 65.060,58.539 C65.783,59.265 66.142,60.140 66.139,61.164 C66.136,62.187 65.771,63.060 65.044,63.782 C64.317,64.503 63.441,64.863 62.418,64.860 ZM87.267,12.903 C83.760,12.903 80.899,10.043 80.899,6.538 C80.899,3.026 83.760,0.173 87.267,0.173 C90.781,0.173 93.636,3.026 93.636,6.538 C93.636,10.043 90.781,12.903 87.267,12.903 ZM87.267,2.211 C84.882,2.211 82.938,4.154 82.938,6.538 C82.938,8.922 84.882,10.865 87.267,10.865 C89.653,10.865 91.597,8.922 91.597,6.538 C91.597,4.154 89.653,2.211 87.267,2.211 ZM29.003,17.857 L31.183,20.122 C31.573,20.527 31.561,21.172 31.155,21.562 C30.750,21.953 30.104,21.940 29.714,21.535 L27.534,19.270 L25.268,21.449 C24.863,21.839 24.217,21.827 23.827,21.421 C23.437,21.016 23.449,20.371 23.855,19.981 L26.120,17.802 L23.941,15.538 C23.550,15.132 23.563,14.487 23.968,14.097 C24.374,13.707 25.020,13.719 25.410,14.125 L27.589,16.389 L29.855,14.210 C30.261,13.819 30.906,13.832 31.296,14.237 C31.687,14.643 31.675,15.288 31.269,15.678 L29.003,17.857 ZM25.581,113.255 C25.676,112.700 26.202,112.327 26.758,112.421 C27.312,112.516 27.686,113.043 27.591,113.597 L27.063,116.694 L30.162,117.222 C30.717,117.318 31.090,117.844 30.996,118.398 C30.901,118.953 30.375,119.326 29.820,119.232 L26.720,118.704 L26.192,121.801 C26.097,122.356 25.571,122.729 25.015,122.634 C24.824,122.601 24.653,122.517 24.517,122.397 C24.258,122.172 24.120,121.821 24.182,121.459 L24.710,118.361 L21.611,117.833 C21.419,117.800 21.249,117.716 21.112,117.596 C20.854,117.370 20.716,117.020 20.777,116.657 C20.872,116.102 21.398,115.730 21.954,115.824 L25.053,116.352 L25.581,113.255 ZM10.330,73.926 C8.941,74.905 7.257,75.292 5.576,75.005 C4.407,74.804 3.337,74.293 2.467,73.532 C2.089,73.201 1.746,72.829 1.453,72.411 C0.469,71.028 0.086,69.339 0.369,67.664 C0.578,66.466 1.100,65.406 1.847,64.552 C3.261,62.936 5.452,62.072 7.718,62.455 C11.177,63.052 13.517,66.342 12.925,69.805 C12.637,71.475 11.714,72.943 10.330,73.926 ZM7.375,64.466 C5.022,64.070 2.788,65.654 2.381,68.006 C1.980,70.354 3.565,72.597 5.923,72.998 C7.063,73.191 8.207,72.927 9.149,72.262 C10.097,71.592 10.719,70.602 10.913,69.463 C11.314,67.106 9.729,64.872 7.375,64.466 Z"></path>
						</svg>
						<p>No Results Found.</p>
					</div>
				</div>`;
}



function trigger_media_tab(ths, type) {
	$('html, body').animate({ scrollTop: 500 }, 500, function () {
		$(".mob_hide[href='#media']").trigger('click');
		if (type == 'video') {
			$("[href='#video']").trigger('click');
		}
		if (type == 'photo') {
			$("[href='#photo']").trigger('click');
		}
	});
}

function load_image_content(ths) {
	ths.onclick = null;
	$(".load-content").first().trigger('click');
}

function load_video_content(ths) {
	ths.onclick = null;
	$(".load-content").last().trigger('click');
}

setTimeout(function () {
	$(".load-content").first().trigger('click');
	$(".load-content").last().trigger('click');
}, 1000);





function getUserYouMayKnowSlider() {
	$.post(node_url + "usersYouMayKnow", { user_id: user_login_id, start: 0, limit: 10, cate_id: '', subcate_id: '', search: '', country_ids: '' }, function (data, status) {
		if (data.status == 1) {
			var slider = '';
			(data.result).map(function (item, index) {
				slider += `<div class="swiper-slide">
							<div class="">
								<div class="profile_box text-center">
									<a href="${base_url}profile?user=${item.user_uname}" class="prof_img">
										<img class="img-reponsive" src="${AMAZON_URL}aud_${item.user_id}/images/${item.uc_pic}" onError="ImageOnLoadError(this,'${base_url}repo/images/user/user.png')">
										<h3>${item.user_name}</h3>
									</a>
									<p>${item.category_name}, ${item.uc_city}, ${item.name}, ${item.country_name}</p>
									<div class="text-center">
										<a href="javascript:;" class="dis_fanbtn dis_bgclr_orange becomeFan" data-uid="${item.user_id}">Become A Fan</a>
									</div>
								</div>
							</div>	
						</div>`;
			})
			var UYMKS = `<div class="dis_suggetion_sliderWrap">
				<div class="dis_sliderheading">
					<div class="dis_sliderheadingL">
						<h2 class="dis_sliderheading_ttl muli_font">People You May Know</h2>
					</div>
					<div class="dis_sh_btnwrap">
						<a href="${base_url + 'dashboard/directory'}" class="dis_sh_btn muli_font">See all
							<span class="dis_sh_btnicon">
								<svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12"><path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
								</svg>
							</span>
						</a>
					</div>
				</div>
				<div class="swiper-container">
					<div class="swiper-wrapper">
						${slider}
					</div>
					<div class="swiper-button-next sgsn-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
					<div class="swiper-button-prev sgsn-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>  
				</div>
			</div>`;
			$('#publish_post').append(UYMKS);
			new Swiper('.dis_suggetion_sliderWrap .swiper-container', {
				slidesPerView: 3,
				spaceBetween: 10,
				grabCursor: false,
				loop: true,
				nextButton: '.swiper-button-next',
				prevButton: '.swiper-button-prev',
				breakpoints: {
					1400: {
						slidesPerView: 3
					},
					1024: {
						slidesPerView: 3
					},
					768: {
						slidesPerView: 2
					},
					640: {
						slidesPerView: 2
					},
					481: {
						slidesPerView: 2
					}
				}
			});
		}
	});
}


var ActiveDirecotryTab = 'addtodirectory';
var MyFollowingId = [];
function getUserDirectory(dir_start = 0, dir_limit = 10, param = { cate_id: '', subcate_id: '', search: '', country_ids: '' }) {
	let URL = (ActiveDirecotryTab == 'addtodirectory') ? 'usersYouMayKnow' : ((ActiveDirecotryTab == 'mydirectory') ? 'usersIFollowed' : 'usersWhoFollowedMe');

	$.post(node_url + URL, { user_id: user_login_id, start: dir_start, limit: dir_limit, ...param }, function (data, status) {
		if (data.status == 1) {
			if ((ActiveDirecotryTab == 'addtodirectory')) {
				MyFollowingId = data.myFollowingId;
			}
			if (dir_start == 0)
				$('#' + ActiveDirecotryTab).html(showDirectoryUsers(data.result));
			else
				$('#' + ActiveDirecotryTab).append(showDirectoryUsers(data.result));
		} else {
			if (dir_start == 0)
				$('#' + ActiveDirecotryTab).html(dataNotFound);
			// else	
			// 	$('#'+ActiveDirecotryTab).append(dataNotFound);
		}
	});
}

function showDirectoryUsers(result) {
	let html = '';

	result.map(function (item, index) {
		html += `<div class="col-lg-3 col-md-4 col-sm-6 col-md-4 ">
						<div class="profile_box text-center">
							<a href="${base_url}profile?user=${item.user_uname}" class="prof_img">
								<img class="img-reponsive" src="${AMAZON_URL}aud_${item.user_id}/images/${item.uc_pic}" onError="ImageOnLoadError(this,'${base_url}repo/images/user/user.png')">
								<h3>${item.user_name}</h3>
							</a>
							<p>
							${(typeof item.category_name != 'object' ? item.category_name : '') +
			(typeof item.uc_city != 'object' && item.uc_city.length > 0 ? ', ' + item.uc_city : '') +
			(typeof item.name != 'object' ? ', ' + item.name : '') +
			(typeof item.country_name != 'object' ? ', ' + item.country_name : '')
			}  
							</p>
							<div class="text-center">
								<a href="javascript:;" class="dis_fanbtn dis_bgclr_orange becomeFan" data-uid="${item.user_id}">${MyFollowingId.includes(item.user_id) ? 'You Are A Fan' : 'Become A Fan'}  </a>
							</div>
						</div>
					</div>`;
	})
	return html;
}

function GetCountOfAllDirectories() {
	$.post(node_url + 'countAllMyUsersDirectories', { user_id: user_login_id, cate_id: '' }, function (data, status) {

		Object.entries(data).forEach(element => {
			if (element[0] != 'usersWhoFollowedMe') {
				$('#' + element[0]).text(`(${element[1]})`);
			} else {

				let count = 0;
				element[1].forEach(result => {
					count += result['usersWhoFollowedMe'];
				})
				$('#' + element[0]).text(`(${count})`);
			}
		});
	});
}

if ($('#' + ActiveDirecotryTab).length) {

	var dir_start = 0; // directory limit and start 
	var dir_limit = 12;
	getUserDirectory(dir_start, dir_limit);

	$(window).scroll(function () {
		if ($(window).scrollTop() == $(document).height() - $(window).height()) {
			dir_start += dir_limit; // increment the start parameter by the limit
			getUserDirectory(dir_start, dir_limit, {
				cate_id: $('#selectMyMutualFriend').val(),
				subcate_id: $('#selectMySubMutualFriends').val(),
				search: $('#searchMyMutualFriend').val(),
				country_ids: $('#selectCountry').val()
			});       
		}
	});

	GetCountOfAllDirectories();
}


$(document).on('change , keyup', '.filterUserDirectory', function () {
	dir_start = 0; dir_limit = 12;
	getUserDirectory(dir_start, dir_limit, {
		cate_id: $('#selectMyMutualFriend').val(),
		subcate_id: $('#selectMySubMutualFriends').val(),
		search: $('#searchMyMutualFriend').val(),
		country_ids: $('#selectCountry').val()
	});
})

$(document).on('click', '.ActiveDirecotryTab', function () {
	ActiveDirecotryTab = $(this).data('type');
	$('#selectMyMutualFriend').change();
	// $("#searchMyMutualFriend").val('');
	// $("#selectMyMutualFriend").select2("val", " ");
	// $("#selectMySubMutualFriends").select2("val", " ");
	// $("#selectCountry").select2("val", " ");
});




/****************************************************Users Direcotry********************************************************/