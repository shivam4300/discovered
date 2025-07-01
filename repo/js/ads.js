//background class dis_ads_bg 2;
var is_mobile_device = IsMobileDevice();
var dyanamic_id = 1;
var domain = window.location.href.replace('http://', '').replace('https://', '').split(/[/?#]/)[0];

var GlobaldynadSlot = [];
function CallGoogleDisplayAds(SlotIds, DivIds, SlotSizes) {
	return;
	let unit = '/22019190093/' + domain;

	let dynadSlot = [];
	if (typeof googletag != 'undefined') {
		if (
			typeof pbjs !== 'undefined' &&
			typeof pbjs.rp !== 'undefined' &&
			typeof pbjs.rp.requestVideoBids !== 'undefined'
		) {
			pbjs.adserverCalled = false;
		}


		googletag.cmd.push(function () {
			googletag.pubads().enableSingleRequest();
			// googletag.pubads().collapseEmptyDivs();
			googletag.pubads().enableLazyLoad({
				fetchMarginPercent: 10,
				renderMarginPercent: 0
			});
			googletag.enableServices();

			Policy = blocklist.includes(window.location.pathname) ? 'blocked' : 'allowed';

			for (let i = 0; i < SlotIds.length; i++) {
				let adunitpath = unit + '_' + SlotIds[i];
				let SlotSize = SlotSizes[i];
				let SlotDivId = domain + '_' + DivIds[i];

				let slot = googletag.defineSlot(adunitpath, SlotSize, SlotDivId)
				slot.addService(googletag.pubads());
				slot.setTargeting('Policy', Policy);
				slot.setTargeting('category', $('#defaultmode').val());
				slot.setTargeting('viewer_id', user_login_id);

				dynadSlot.push(slot);
				GlobaldynadSlot.push(slot);

			}
			// for(let i=0; i < SlotIds.length; i++){googletag.display(domain+'_'+DivIds[i])}
			if (typeof pbjs !== 'undefined' && pbjs.que) {
				callMagnite(dynadSlot, Policy);
			}
		});
	}
}

function callMagnite(dynadSlot, Policy) {
	pbjs.que.push(function () { // request pbjs bids when it loads
		let slotTargeting = {
			Policy: Policy,
			viewer_id: user_login_id,
			category: $('#defaultmode').val(),
		};

		let conf = pbjs.getConfig('rubicon') || {};
		conf.fpkvs = slotTargeting;

		pbjs.setConfig({ rubicon: conf });

		pbjs.rp.requestBids({
			callback: callAdserver,
			gptSlotObjects: dynadSlot,
			data: slotTargeting,
			sizeMappings: {},
		});
	});

	// function that calls the ad-server
	function callAdserver(gptSlots) {
		if (pbjs.adserverCalled) return;
		pbjs.adserverCalled = true;
		googletag.cmd.push(function () {
			googletag.pubads().refresh(gptSlots);
			addHeadingToAds()
		});
	}
	// failsafe in case PBJS doesn't load
	setTimeout(function () {
		//  callAdserver(dynadSlot);
	}, 3500);

}

// Check if an element is in the viewport
function isElementInViewport(elementId) {
	var element = document.getElementById(elementId);
	if (!element) return false;
	var rect = element.getBoundingClientRect();
	return (
		rect.top >= 0 &&
		rect.left >= 0 &&
		rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
		rect.right <= (window.innerWidth || document.documentElement.clientWidth)
	);
}

CallAdsInterval();

function CallAdsInterval() {
	if (typeof pbjs !== 'undefined' && typeof pbjs.rp !== 'undefined' && typeof pbjs.rp.requestVideoBids !== 'undefined') {
		setInterval(function () {
			reinitializedAds();
		}, 30000);
		setTimeout(() => {
			reinitializedAds();
		}, 8000)
	}
}

function reinitializedAds() {
	pbjs.adserverCalled = false;
	if (GlobaldynadSlot.length) {
		var slots = googletag.pubads().getSlots();

		let viewPortSlot = [];
		slots.forEach(function (slot) {
			if (isElementInViewport(slot.getSlotElementId())) {
				viewPortSlot.push(slot);
			}
		});
		callMagnite(viewPortSlot);
	}
}

function RemoveNotLoadedAds() {
	var slots = googletag.pubads().getSlots();
	slots.forEach(function (slot) {
		googletag.pubads().addEventListener('slotRenderEnded', (function (slot) {
			return function (event) {
				checkAdLoaded(slot);
			};
		})(slot));
	});
}
function checkAdLoaded(slot) {
	var divId = slot.getSlotElementId();
	var divElement = document.getElementById(divId);
	if (divElement !== null && divElement.innerHTML.trim() === '') {
		console.log('Ad did not load for slot: ' + divId);
		var divElement = document.getElementById(divId); // Remove the empty div associated with the ad slot
		if (divElement) {
			var headingDiv = findHeadingSibling(divElement);
			if (headingDiv) {
				console.log('Removed empty div heading: ' + divId);
				divElement.parentNode.removeChild(headingDiv);
			}
			divElement.parentNode.removeChild(divElement);
			console.log('Removed empty div with id: ' + divId);
		} else {
			console.log('Empty div not found with id: ' + divId);
		}
	} else {
		console.log('Ad has loaded for slot: ' + divId);
	}
}

function findHeadingSibling(element) {
	var sibling = element.previousElementSibling;
	while (sibling) {
		if (sibling.classList.contains('ads_heading_div')) {
			return sibling;
		}
		sibling = sibling.previousElementSibling;
	}
	return null;
}

function addHeadingToAds() {
	var adContainers = document.getElementsByClassName('my_custom_ads_class'); // Find all containers where Google Ads are being rendered
	var newDiv = document.createElement('span');
	newDiv.className = 'ads_heading_div';

	var heading = document.createElement('h6'); // Create a new heading element
	heading.textContent = 'Advertisement';

	newDiv.appendChild(heading);

	for (var i = 0; i < adContainers.length; i++) { // Iterate through each container and insert the heading before it
		var adContainer = adContainers[i];
		if (!adContainer.firstChild?.classList?.contains('ads_heading_div')) {
			adContainer.prepend(newDiv.cloneNode(true));
		} else {
			// console.log('Heading div already exists for ad container ' + i);
		}
	}
}

setTimeout(function () {
	// RemoveNotLoadedAds();
}, 3000);



if ($('#publish_post').length) {
	if (is_mobile_device) {
		let slotIds = ['profile_sidebar_desktop', 'profile_sidebar_desktop_2'];
		let divIds = ['profile_sidebar_desktop', 'profile_sidebar_desktop_2'];
		let SlotSizes = [
			[[300, 250], [160, 600], 'fluid', [300, 600]],
			[[300, 250], [160, 600], 'fluid', [300, 600]]
		];
		setTimeout(() => {
			CallGoogleDisplayAds(slotIds, divIds, SlotSizes);
		}, 2000)
	}
}
function AdAdsOnChannel_google(thiss, callback) {
	let _this = thiss.find('.swiper-wrapper');

	let div_id = `div-gpt-ad-1590764234846-` + dyanamic_id;
	let html = `<div id='` + div_id + `' style="margin-right:30px;"></div>`;

	let slide = _this.find('.swiper-slide');
	let lent = slide.length;

	lent = Math.round(lent / 2);
	slide.eq(lent - 1).after(html);

	CallGoogleDisplayAds(['profile_post_mobile'], [div_id], [size]);
	dyanamic_id++;
	callback();
}



function AddDynamicAds(element, addType) {
	let size = ['fluid', [728, 90], [468, 60]];
	let ad_id = 'profile_post_Desktop' + dyanamic_id;
	let h = '90px';
	if (is_mobile_device) {
		size = [[320, 100], [300, 50], [300, 100], 'fluid', [320, 50]];
		let ad_id = 'profile_post_mobile' + dyanamic_id;
		h = '50px';
	}

	let ads =
		`<div class="dis_user_post_data dis_add_area left" data-height="60" style="max-height:600px;min-height:${h};">
						<div id='` +
		domain +
		'_' +
		ad_id +
		`'></div>
				   </div>`;

	if (addType == 'html') {
		$(element).html(ads);
	} else {
		$(element).append(ads);
	}

	CallGoogleDisplayAds([ad_id], [ad_id], [size]);

	dyanamic_id++;
}

function checkAdsHide() {
	setTimeout(function () {
		$('.dis_add_area').each(function () {
			let thss = $(this);
			if (thss.attr('data-height')) {
				if (thss.height() < thss.attr('data-height')) {
					thss.addClass('hide');
				}
			}
		});
	}, 3000);
}




function AdAdsOnChannel(thiss, callback, is_slider = true) {
	return;
	let _this = thiss.find('.swiper-wrapper');
	let id = `taboola-thumbnails-300x250-${dyanamic_id}`;

	if (is_slider) {
		let html =
			`<div class="swiper-slide">
				<div class="dis_post_video_data dis_no_ads_thumb">
					<div id='${id}'></div>
				</div>
			</div>`;
		let slide = _this.find('.swiper-slide');
		let lent = slide.length;
		lent = Math.round(lent / 2);
		slide.eq(lent - 1).before(html);
	} else {
		let cls = Array.from(thiss?.[0]?.classList);
		let specific_cls = '';

		if (cls.includes("intSlider")) {
			specific_cls = 'dis_postvideo_height185';
		}

		let html =
			`<div class="text-center">
				<div class="dis_post_video_data">
					<div class="dis_postvideo_img ${specific_cls}" style="overflow:hidden;">
						<div id='${id}'></div>
					</div>
				</div>
			</div>`;
		$('#load_related').append(html);
	}

	window._taboola = window._taboola || [];
	_taboola.push({
		mode: 'thumbnails-300x250',
		container: id,
		placement: 'Thumbnails-300x250-' + dyanamic_id,
		target_type: 'mix'
	});

	dyanamic_id++;
	callback();
}

function AddAdsOnSinglePageOnTheSidebar() {
	$('.sv_ads').find('div').empty();
	let slotIds = ['sidebar_1', 'sidebar_2', 'sidebar_3'];
	let h = '250px';
	let md = 'desktop';
	if (is_mobile_device) {
		md = 'mobile';
		h = '50px';
	}
	// let SlotSizes = [
	// 	[[300, 250], [300, 600], [160, 600], 'Fluid'],
	// 	[[300, 250], [300, 600], [160, 600], 'Fluid'],
	// 	[[300, 250], [300, 600], [160, 600], 'Fluid'],
	// ];
	let SlotSizes = [
		[300, 250],
		[300, 600],
		[160, 600],
		[320, 600],
		[120, 600],
		[336, 280],
		[250, 250],
		[300, 100],
		[320, 480],
		[300, 400],
		[320, 180],
		'Fluid'
	]
	slotIds.map((item) => {
		let adContainer = `<div class="1 dis_ads_bg m_b_30 text-center my_custom_ads_class" id='` + domain + '_' + item + `' style="max-height:630px;min-height:${h};height: max-content;"></div>`;
		$('#' + item).html(adContainer)
	})
	CallGoogleDisplayAds(slotIds, slotIds, SlotSizes);
}

// function AddDynamicArticleAdsOnSinglePage(element, addType) {
// 	// let size = ['fluid', [728, 90], [300, 250], [970, 90], [970, 250]];
// 	let size = dimensions = [
// 		[728, 90],
// 		[300, 250],
// 		[970, 90],
// 		[970, 250],
// 		[600, 315],
// 		[600, 500],
// 		[600, 600],
// 		[320, 180],
// 		[360, 300],
// 		[336, 336],
// 		[300, 400],
// 		[320, 480],
// 		[336, 280],
// 		'fluid'
// 	];
// 	let h = '90px';
// 	let md = 'desktop';
// 	if (is_mobile_device) {
// 		size = ['fluid', [300, 250], [320, 50], [320, 100], [300, 50], [300, 100]];
// 		md = 'mobile';
// 		h = '50px';
// 	}

// 	let ad_id = 'inarticle_' + md + dyanamic_id;
// 	let ads = `<div id="inarticle_${dyanamic_id}"><div class="m_b_30 text-center my_custom_ads_class" id='` + domain + '_' + ad_id + `' style="max-height:600px;min-height:${h};height:max-content;"></div></div>`;

// 	dyanamic_id++;

// 	if (addType == 'html') {
// 		$(element).html(ads)
// 	} else
// 		if (addType == 'append') {
// 			$(element).append(ads)
// 		}
// 	CallGoogleDisplayAds([ad_id], [ad_id], [size]);
// }



// function addAdsOnArticleHomepageOnTheTop() {
// 	let slotIds = ['articlehome_topbox_1', 'articlehome_topbox_2'];
// 	let SlotSizes = [[[300, 250]], [[300, 250]]];

// 	let h = '250px';
// 	let md = 'desktop';
// 	if (is_mobile_device) {
// 		md = 'mobile';
// 		h = '50px';
// 	}

// 	slotIds.map((item) => {
// 		let adContainer = `<div class="dis_ads_bg 3 m_b_30 text-center my_custom_ads_class" id='` + domain + '_' + item + `' style="max-height:600px;min-height:${h};height: max-content;"></div>`;
// 		$('#' + item).html(adContainer)
// 	})

// 	CallGoogleDisplayAds(slotIds, slotIds, SlotSizes);
// }

// function addDyanamicAdsOnArticleHomepage() {
// 	let size = ['fluid', [728, 90], [300, 250], [970, 90], [970, 250]];
// 	let h = '90px';
// 	let md = 'desktop';

// 	if (is_mobile_device) {
// 		size = ['fluid', [300, 250], [320, 50], [320, 100], [300, 50], [300, 100]];
// 		md = 'mobile';
// 		h = '50px';
// 	}
// 	dyanamic_id++;
// 	let slodId = 'articlehome_billboard_' + md + dyanamic_id;
// 	let adContainer = `<div class="dis_ads_bg 4 m_b_30 text-center my_custom_ads_class" id='` + domain + '_' + slodId + `' style="max-height:600px;min-height:${h};height: max-content;"></div>`;

// 	$('#content').append(adContainer)
// 	CallGoogleDisplayAds([slodId], [slodId], [size]);
// }

function addDyanamicSidebarAdsOnArticleHomepage() {
	size = ['fluid', [160, 600], [300, 600], [300, 250], [300, 100], [120, 600], [300, 50]];
	let h = '90px';
	let md = 'desktop';

	if (is_mobile_device) {
		md = 'mobile';
		h = '50px';
	}
	dyanamic_id++;
	let slodId = 'articlehome_sidebar_' + md + dyanamic_id;

	let adContainer = `<div class="dis_ads_bg 5 m_b_30 text-center my_custom_ads_class" id='` + domain + '_' + slodId + `' style="max-height:640px;min-height:${h};height: max-content;"></div>`;

	$('#sidebar').append(adContainer);
	CallGoogleDisplayAds([slodId], [slodId], [size]);
}

// function addAdsOnSingleArticleOnTheTop(element, method) {
// 	let size = ['fluid', [728, 90], [300, 250], [970, 90], [970, 250]];
// 	let h = '90px';
// 	let md = 'desktop';
// 	if (is_mobile_device) {
// 		size = ['fluid', [300, 250], [320, 50], [320, 100], [300, 50], [300, 100]];
// 		md = 'mobile';
// 		h = '50px';
// 	}

// 	let slodId = 'article_top';
// 	let adContainer = `<div id="article_top"><div class="dis_ads_bg 6 m_b_30 text-center my_custom_ads_class" id='` + domain + '_' + slodId + `' style="max-height:600px;min-height:${h};height: max-content;"></div></div>`;

// 	method == 'html' ? $(element).html(adContainer) : $(element).append(adContainer);
// 	CallGoogleDisplayAds([slodId], [slodId], [size]);
// }



function taboolaMidArticleCategory(element, addType) {
	return;
	let html = `<div class="7 dis_ads_bg m_b_30 text-center" style="max-height:450px;height:450px;overflow:hidden;">
			<div class="my_custom_ads_class" id='taboola-mid-article-category-` + dyanamic_id + `' ></div>
		</div>`;

	addType == 'html' ? $(element).html(html) : $(element).append(html);

	window._taboola = window._taboola || [];
	_taboola.push({
		mode: 'thumbnails-mid-category',
		container: 'taboola-mid-article-category-' + dyanamic_id,
		placement: 'Mid Article Category-' + dyanamic_id,
		target_type: 'mix',
	});
	dyanamic_id++;
}

function taboolaBelowArticleThumbnailsFeed(element, addType) {
	return;
	let html = `<div class="8 dis_ads_bg m_b_30 text-center">
			<div class="my_custom_ads_class" id='taboola-below-article-thumbnails-feed-` + dyanamic_id + `'></div>
		</div>`;

	addType == 'html' ? $(element).html(html) : $(element).append(html);

	window._taboola = window._taboola || [];
	_taboola.push({
		mode: 'alternating-thumbnails-a-feed',
		container: 'taboola-below-article-thumbnails-feed-' + dyanamic_id,
		placement: 'Below Article Thumbnails Feed-' + dyanamic_id,
		target_type: 'mix'
	});

	dyanamic_id++;
}

function AdTaboolaAdsOnArticleSidebar(element, addType) {
	return;
	let id = `taboola-sidebar-${dyanamic_id}`;

	let html = `<div class="9 text-center dis_ads_bg m_b_30">
			<div class="my_custom_ads_class" id='${id}'></div>
		</div>`;

	addType == 'html' ? $(element).html(html) : $(element).append(html);

	window._taboola = window._taboola || [];
	_taboola.push({
		mode: 'thumbnails-rr-sidebar',
		container: id,
		placement: 'Sidebar-' + dyanamic_id,
		target_type: 'mix'
	});

	dyanamic_id++;
}






// function addAdsOnSingleVideoOnTheTop(element, method) {
// 	let size = ['fluid', [728, 90], [970, 90], [468, 60]];
// 	let h = '90px';
// 	let md = 'desktop';
// 	if (is_mobile_device) {
// 		size = ['fluid', [468, 60], [250, 250], [200, 200], [300, 100], [300, 50], [320, 100], [320, 50], [300, 250]];
// 		md = 'mobile';
// 		h = '50px';
// 	}

// 	let slodId = 'display_top';

// 	// GlobaldynadSlot.map((item,index)=>{
// 	// 	if(item.getAdUnitPath().search(slodId) > -1){
// 	// 		googletag.destroySlots([GlobaldynadSlot[index]]);
// 	// 		GlobaldynadSlot.splice(index, 1);
// 	// 	}
// 	// })


// 	let adContainer = `<div class="10 dis_ads_bg m_b_5 m_t_5 text-center my_custom_ads_class" id='` + domain + '_' + slodId + `' style="max-height:600px;min-height:${h};height: max-content;"></div>`;

// 	method == 'html' ? $(element).html(adContainer) : $(element).append(adContainer);
// 	CallGoogleDisplayAds([slodId], [slodId], [size]);
// }

// function addAdsOnSingleVideoOnTheMiddle(element, method) {
// 	let size = ['fluid', [728, 90], [970, 250], [970, 90], [250, 250], [336, 280], [300, 250], [468, 60], [970, 90], [970, 250], [600, 315], [600, 500], [600, 600]];
// 	let h = '90px';
// 	let md = 'desktop';
// 	if (is_mobile_device) {
// 		size = ['fluid', [300, 250], [336, 280], [250, 250], [200, 200], [300, 100], [300, 50], [320, 100], [320, 50], [320, 480], [300, 400], [336, 336], [360, 300], [360, 120], [320, 180]];
// 		md = 'mobile';
// 		h = '50px';
// 	}

// 	let slodId = 'display_middle';

// 	let adContainer = `<div class="11 dis_ads_bg m_b_30 text-center my_custom_ads_class" id='` + domain + '_' + slodId + `' style="max-height:630px;min-height:${h};height: max-content;"></div>`;

// 	method == 'html' ? $(element).html(adContainer) : $(element).append(adContainer);
// 	CallGoogleDisplayAds([slodId], [slodId], [size]);
// }

// function addAdsOnSingleVideoOnTheBottom(element, method) {
// 	let size = ['fluid', [728, 90], [970, 250], [970, 90], [250, 250], [336, 280], [300, 250], [468, 60], [970, 90], [970, 250], [600, 315], [600, 500], [600, 600]];
// 	let h = '90px';
// 	let md = 'desktop';
// 	if (is_mobile_device) {
// 		size = ['fluid', [300, 250], [336, 280], [250, 250], [200, 200], [300, 100], [300, 50], [320, 100], [320, 50], [320, 480], [300, 400], [336, 336], [360, 300], [360, 120], [320, 180]];
// 		md = 'mobile';
// 		h = '50px';
// 	}

// 	let slodId = 'display_bottom';

// 	let adContainer = `<div class="12 dis_ads_bg m_b_10 m_t_10  text-center my_custom_ads_class" id='` + domain + '_' + slodId + `' style="max-height:600px;min-height:${h};height: max-content;"></div>`;

// 	method == 'html' ? $(element).html(adContainer) : $(element).append(adContainer);
// 	CallGoogleDisplayAds([slodId], [slodId], [size]);
// }

// function addAdsOnSingleVideoOnTheTopRight(element, method) {
// 	let size = ['fluid', [300, 250], [300, 50], [300, 100]];
// 	let h = '90px';
// 	let md = 'desktop';
// 	if (is_mobile_device) {
// 		md = 'mobile';
// 		h = '50px';
// 	}

// 	let slodId = 'display_right';

// 	let adContainer = `<div class="13 dis_ads_bg text-center my_custom_ads_class" id='` + domain + '_' + slodId + `' style="max-height:600px;min-height:${h};height: max-content;"></div>`;

// 	method == 'html' ? $(element).html(adContainer) : $(element).append(adContainer);
// 	CallGoogleDisplayAds([slodId], [slodId], [size]);
// }







function AddSheMediaArticleAdsOnSinglePage(element, addType) {
	let ads = `<div id="responsive-incontent-${dyanamic_id}" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	console.log(ads, 'ads')
	if (addType == 'html') {
		$(element).html(ads)
	} else
	if (addType == 'append') {
		$(element).append(ads)
	}
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728, 0], 'banner'],
			[[0, 0], 'mobileincontent']
		], `responsive-incontent-${dyanamic_id}`).display();
	});
	dyanamic_id++;
}

function AddSheMediaAdsOnSingleArticleOnTheTop(element, method) {
	let ads = `<div id="responsive-banner-top" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	
	if (method == 'html'){
		$(element).html(ads)
	} else
	if (method == 'append') {
		$(element).append(ads)
	}
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728, 0], 'banner'],
			[[0, 0], 'tinybanner']
		], `responsive-banner-top`).display();
	});
}

function AddSheMediaAdsOnSingleArticleOnTheBottom(element, method) {
	let ads = `<div id="responsive-banner-bottom" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	
	if (method == 'html'){
		$(element).html(ads)
	} else
	if (method == 'append'){
		$(element).append(ads)
	}
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728, 0], 'banner'],
			[[0, 0], 'tinybanner']
		], `responsive-banner-bottom`).display();
	});
}



function addSheMediaAdsAdsOnSingleVideoOnTheTop(element, method) {
	let ads = `<div id="Responsive-banner-top" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	console.log(ads, 'ads')
	if (method == 'html') {
		$(element).html(ads)
	} else
	if (method == 'append') {
		$(element).append(ads)
	}
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728, 0], 'banner'],
			[[0, 0], 'tinybanner']
		], `Responsive-banner-top`).display();
	});
}

function addSheMediaAdsOnSingleVideoOnTheTopRight(element, method){
	let ads = `<div id="Sidebar-medrec-02" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	console.log(ads, 'ads')
	if (method == 'html') {
		$(element).html(ads)
	} else
	if (method == 'append') {
		$(element).append(ads)
	}
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728, 0], 'medrec'],
		], `Sidebar-medrec-02`).display();
	});

}

function addSheMediaAdsOnSingleVideoOnTheMiddle(element, method){
	let ads = `<div id="Responsive-incontent-01" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	console.log(ads, 'ads')
	if (method == 'html') {
		$(element).html(ads)
	} else
	if (method == 'append') {
		$(element).append(ads)
	}
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728, 0], 'banner'],
			[[0, 0], 'mobileincontent']
		], `Responsive-incontent-01`).display();
	});
}

function addSheMediaAdsOnSingleVideoOnTheBottom(element, method){
	let ads = `<div id="Responsive-banner-bottom" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	console.log(ads, 'ads')
	if (method == 'html') {
		$(element).html(ads)
	} else
	if (method == 'append') {
		$(element).append(ads)
	}
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728, 0], 'banner'],
			[[0, 0], 'tinybanner']
		], `Responsive-banner-bottom`).display();
	});
}


function addSheMediaAdsOnArticleHomepageOnTheTop(){
	let ads1 = `<div id="Sidebar-medrec-01" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	$('#articlehome_topbox_1').html(ads1)
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728,0], 'medrec']
		],`Sidebar-medrec-01`).display();
	});
	let ads2 = `<div id="Sidebar-medrec-02" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	$('#articlehome_topbox_2').html(ads2)
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728,0], 'medrec']
		],`Sidebar-medrec-02`).display();
	});
}


function addSheMediaDyanamicAdsOnArticleHomepage(element, addType) {
	let ads = `<div id="responsive-incontent-${dyanamic_id}" style="text-align:center;" class="m_b_30 text-center my_custom_ads_class shemedia-ad-callout"></div>`;
	$('#content').append(ads);
	blogherads.adq.push(function () {
		blogherads.defineResponsiveSlot([
			[[728, 0], 'banner'],
			[[0, 0], 'mobileincontent']
		], `responsive-incontent-${dyanamic_id}`).display();
	});
	dyanamic_id++;
}

