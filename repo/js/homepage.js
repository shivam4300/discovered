var Poststart = 0;
var Postlimit = 2;
var ControlRequest = false;
var defaultMode = $('#defaultmode').val();
var sliders_store = [];
var sliders_array_for_loop = [];

$(document).ready(function () {
	if (performance.navigation.type == 2) {   //when comes on homepage by click back button
		loadFromlocalStorage(true)
	}else{
		if (typeof ExploreSlider !== 'undefined' || typeof TopGames !== 'undefined') {
			if (defaultMode !== 'store') {
				getSliderNew();
			}else{
				getProducts();
			}
	
			loadFromServer();  //This function will only trigger, Scroll function, 
		}
	}
	
	var CoverSwiper = {};
	if ($('.CoverSwiper').length > 0){
		CoverSwiper = new Swiper('.CoverSwiper .swiper-container', {
			slidesPerView: 1,
			spaceBetween: 0,
			loop: false,
			speed: 500,
			autoplay: 10000,
			autoplayDisableOnInteraction: false,
			// pagination: '.swiper-pagination',
			// paginationClickable: true,
			nextButton: '.swiper-button-next',
			prevButton: '.swiper-button-prev',
		});

		function replaceSrc(index = 0) {
			let s = $('video.banner_video').eq(index);
			let src = s.find('source').attr('data-src');
			if (src.length) {
				s.find('source').attr('src', src);
				s[0].load();
				s[0].play();
			}
		}

		setTimeout(function () {
			replaceSrc(0);
		}, 200);

		CoverSwiper.on('transitionEnd', function () {
			$('.Flexible-container > .speaker').each(function (i) {
				$('video.banner_video').eq(i)[0].pause()
			});

			setTimeout(() => {
				replaceSrc(CoverSwiper.snapIndex);
			}, 100)
		});
	}
});

function loadFromServer(){
	$(window).scroll(function(){
		store("slider_position_"+defaultMode,$(window).scrollTop())
		if ($(window).scrollTop() + $(window).height() > $(document).height() - 100){
			if (ControlRequest){
				ControlRequest = false;
				if(defaultMode !== 'store'){
					getSliderNew();
				}else{
					getProducts();
				}
			}
		}
	});
}

function loadFromlocalStorage(ShouldEmpty){
	if(ShouldEmpty){
		$("#appendSlider")['html']('');
	}
	
	sliders_store = JSON.parse(get("slider_"+defaultMode));
	console.log(sliders_store,'sliders_store');
	sliders_store.forEach((item,index) => {
		let m = 'append';
		$("#appendSlider")[m](item);
		let thhs = $('div.au_artist_slider:last');
		swiperslider(thhs);
		console.log(sliders_array_for_loop[index]['type'] ,'typetype');
		
		if (sliders_array_for_loop[index]['type'] == 'global_top_ten') {
			$('.dis_sh_btnwrap:last').hide();
		}

		if (sliders_array_for_loop[index]['videoData'] !== undefined && sliders_array_for_loop[index]['videoData'].length < 10) {
			$('.dis_sh_btnwrap:last').hide();
		}
	});
	setTimeout(()=>{
		Poststart = +get("slider_start_"+defaultMode);
		$(window).scrollTop(get("slider_position_"+defaultMode));
		ControlRequest = true;
		loadFromServer();
	},300)
}

function getSliderNew() {
	var formData = new FormData();
	formData.append("start", Poststart);
	formData.append("limit", Postlimit);
	
	manageMyAjaxPostRequestData(formData, base_url + 'home/show_homepage_slider_new').done(function (resp) {
		
		if ($.trim(resp).length) {
			resp = JSON.parse(resp);
			if (resp.status == 1) {
				let resData = resp.data;
				console.log(resData,'response1')
				if (resData.length) {
					renderSlider(resData,'step1');
					
					ControlRequest = true;
					Poststart += Postlimit;
					store("slider_start_"+defaultMode,Poststart)
					
				}else{
					setTimeout(function(){
						loadFromlocalStorage(false);
					},500)
					ControlRequest = false;
				}
			}
		} else {
			ControlRequest = false;
		}
	})
}

function renderSlider(resData,step){
	if(step == 'step1'){
		sliders_array_for_loop = [...sliders_array_for_loop,...resData];
	}
	$.each(resData, function (i) {
		if (resData[i]) {
			let t = resData[i]['title'].toUpperCase();
			let h = (t == 'EXPLORE VIDEOS BY GENRES') ? ExploreSlider : (t == 'TOP GAMES') ? TopGames :  getSliderHtml(resData[i]) ;
			
			if(step == 'step1'){
				sliders_store.push(h);
				store("slider_"+defaultMode,JSON.stringify(sliders_store))
			}
			
			
			let m = (Poststart == 0 && i == 0) ? 'html' : 'append';
			$("#appendSlider")[m](h);

			let thhs = $('div.au_artist_slider:last');
			swiperslider(thhs);
			
			/*
			if (defaultMode !== 'spotlight') {
				AdAdsOnChannel(thhs, function () {
					setTimeout(() => {
						swiperslider(thhs);
						removeEmptyAds(thhs);
					}, 100)
				});
			} else {
				setTimeout(() => {
					swiperslider(thhs);
				}, 100)
			}*/

			if (resData[i]['type'] == 'global_top_ten') {
				$('.dis_sh_btnwrap:last').hide();
			}

			if (resData[i]['videoData'] !== undefined && resData[i]['videoData'].length < 10) {
				$('.dis_sh_btnwrap:last').hide();
			}
		}
	});
}

function removeEmptyAds(thhs) {
	setTimeout(() => {
		$(thhs).find('div.dis_no_ads_thumb div').each(function (index, value) {
			if (!$.trim($(this).html()).length) {
				$(this).parent().parent('.swiper-slide').remove();
			}
		});
	}, 1000)
}
$(document).ready(function () {
	if ($('#defaultmode').val() == 'store') {
		$('.au_header_section').addClass('store_mode_active')
	}
});

/* genre load more */

var start_other = 0;
var start_limit = 6;
$(document).ready(function () {
	if ($('[data-load-other-url]').length) {
		$(window).scroll(function () {
			if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {

				if (ControlRequest) {
					ControlRequest = false;
					$('[data-load-other-url]').click();
				}
			}
		});


		$(document).on("click", "[data-load-other-url]", function () {
			var _this = $(this);
			var url = _this.data("load-other-url");
			$(".pro_loader").show();

			var formData = new FormData();

			formData.append("start_other", start_other);
			formData.append("start_limit", start_limit);

			if ($(".pro_loader").attr("data-load") == 1) {
				$(".pro_loader").show();
			}

			manageMyAjaxPostRequestData(formData, base_url + url).done(function (resp) {
				if ($.trim(resp).length > 1) {
					$("#loadMoreGenre").append(resp);
					start_other = start_other + start_limit;
					ControlRequest = true;
				} else {
					_this.hide();
					$(".pro_loader").attr("data-load", 0);
					$(".dis_loadmore_loader").text("-- No more data available --");
				}

				$(".pro_loader").hide();
			});
		});

		$('[data-load-other-url]').click();


	}
})

