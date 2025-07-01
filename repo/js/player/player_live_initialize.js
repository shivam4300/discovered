var isMobile = IsMobileDevice(); !isMobile ? $('.videoLoader').show() : '';
var player = '', thumbPlayer = '', seeked = true, IsSpinnerInt, IsSpinnerInt2, Spinnertimer, vastUrl, interval, WatchlistName;
var midRollCount = vidDuration = plyDuration = 0;
var xhrViewCount = 1, seekStep = 10;
var videoid = 'my_video';
var pushState = [];
var adsManager;
var currentVideoIndex = 0;
var isAdsRunning = 0;
var contentEnded = 0;
window.getCustomParam = function () {
	let currentIndex = getCurrentIndex();
	let item = mainPlaylist[currentIndex]?.['single_video'];

	return {
		'VideoUserId': item.user_id,
		'VideoPostId': item.post_id,
		'VideoMode': item.web_mode,
		'VideoGenre': item.genre,
		'VideoCategory': item.mode == 1 ? 'IAB1-6' : (item.mode == 2 ? 'IAB1-5' : (item.mode == 3 ? 'IAB1-7' : 'IAB9-30')),
		'channel': item.user_uname,
		'profile': item.user_name,
		'title': item.title,
		'is_stream_live': item.is_stream_live,
		'language': item.language,
		'duration': item.video_duration,
		'domain': (window.location.href).replace('http://', '').replace('https://', '').replace('www.', '').split(/[/?#]/)[0],
		'UidIdWhoIsWatching': (typeof user_login_id !== 'undefined' && user_login_id !== '') ? user_login_id : 0,
	};
}


window.playerInit = function (videoBidResult = {}) {
	
	document.querySelector(".dis_player_pb_load").style.width = 0;

	if (videoid != 'undefined' && videoid != undefined && videoid != '') {
		var overrideNative = true;
		player = videojs(videoid, {
			playbackRates: [1, 1.5, 2, 2.5],
			controls: true,
			loop: false,
			preload: "auto",
			muted: true,
			autoplay: (showOnlyCastCrew == 1) ? false : true,
			aspectRatio: '16:9',
			userActions: { "doubleClick": false },
			html5: {
				vhs: {
					overrideNative: overrideNative,
					enableLowInitialPlaylist: true
				},
				nativeVideoTracks: !overrideNative,
				nativeAudioTracks: !overrideNative,
				nativeTextTracks: !overrideNative
			},

		}, async function () {
			await setVastUrl(videoBidResult);
			// player.playlist(mainPlaylist);
			
			currentVideoIndex = 0
			window.changeVideoState(currentVideoIndex);
			setAuto();
			
			$('.vjs-control-bar').hide();

			if (!player.muted()) {
				$('#playMute').parent().removeClass('mute');
			}

			(function () {
				/* Here start to generate a thumbanails for progressbar */
				/*
				if (!isMobile) {
					thumbPlayer = videojs('thumb-video', {
						muted: true,
						autoplay: false,
						control: false,
						preload: "auto",
					});

					thumbPlayer.on('seeking', function () {
						seeked = false;
					})

					thumbPlayer.on('seeked', function () {
						seeked = true;
					})
				} else {
					$('#thumb-video').hide();
				}*/
				$('#thumb-video').hide();

				/* End to generate a thumbanails for progressbar */

				player.ready(function () {
					$('.MainSkelton').addClass('hide');
					$('.dis_SV_videoContent').removeClass('hide');

					player.on('loadedmetadata', function () {
						$('.dis_newSv').addClass('hide');
						setNupdateQuality();
					})
					setTimeout(() => {
						$('.dis_newSv').addClass('hide');
					}, 2000)
					player.on('error', function (e) {
						console.log('error', e);
						setNupdateQuality();
					})

					$(window).on('keyup keydown', function (e) {
						if (e.keyCode == 32 && player.children_[0].id == e.target.id) {
							e.preventDefault();
						}
					})

					$('.dis_playerWrap').on('click', function (e) {
						$('#' + player.children_[0].id).focus();
					})

					player.on('keyup', function (event) {
						let t = player.currentTime();
						if (event.code === 'ArrowRight') {
							player.currentTime(t + seekStep);
							$('.forward').addClass('active');
						} else
							if (event.code === 'ArrowLeft') {
								player.currentTime(t - seekStep);
								$('.rewind').addClass('active');
							} else
								if (event.code === 'Space') {
									$('#playPause').click();
								}
					});
					player.on('dblclick', function (event) {
						toggleFullscreen();
					});

					player.on('timeupdate', function () {
						if (this.duration() > 0 && !isNaN(this.duration())) {
							let d = this.duration();
							let width = $('.dis_player_pb_wrap').width();

							if (d != 'Infinity') {
								let p = (this.currentTime() / d) * 100;

								$(".dis_player_pb_play").css("width", p + "%");

								$('.dis_progress_handle ').css({
									'left': width * p / 100
								});
							} else {
								$(".dis_player_pb_play").css("width", "100%");
								$('.dis_progress_handle ').css({
									'left': width
								});
							}


							$('#UpdateTime').html(
								`<li>
										<span class="dis_pcTCT">${formatTime(this.currentTime())}</span>
									</li>
									<li>
										<span class="dis_pcTCT">${d == 'Infinity' ? 'Live' : formatTime(d)}</span>
									</li>`
							);
						}
					})

					player.on('progress', function (e) {
						let buffer = player.buffered();
						let duration = player.duration();
						if (duration > 0) {
							for (let i = 0; i < buffer.length; i++) {
								if (buffer.start(buffer.length - 1 - i) < player.currentTime()) {
									document.querySelector(".dis_player_pb_load").style.width = `${(buffer.end(buffer.length - 1 - i) * 100) / duration
										}%`;
									break;
								}
							}
						}
					})

					player.on('play', function () {
						$('#playPause').find('span').addClass('play').next().text('Pause');
					})

					player.on('pause', function () {
						$('#playPause').find('span').removeClass('play').next().text('Play');
						clearInterval(interval);
					})

					player.on("playing", function (e) {
						$('#my_video_ima-ad-container').css({ display: '' });
						interval = setInterval(countInterval, 1000);
					});

					player.on("waiting", function (e) {
						clearInterval(interval);
					});


					player.on('contentended', function () {
						if(contentEnded == 0){
							initSpinner();
							contentEnded++;
						}
					})

					player.on('ended', function () {
						if(contentEnded == 0){
							initSpinner();
							contentEnded++;
						}
					})

					player.on('contentchanged', function () {
						midRollCount = plyDuration = 0;
						xhrViewCount = 1;
						clearInterval(interval);
					});

					player.on('volumechange', () => {
						store('player_mute', player.muted());
						store('player_volu', player.volume());
					})

					player.on("seeked", function (e) {
						setTimeout(() => {
							$('.player-PrevNext').removeClass('active');
						}, 500)
					});

					getPlayerPlaylist(0);

				});
			})();

			$(document).on('click', '#playPrevious', function () {
				playPrevious();
			});

			$(document).on('click', '#PlayNext', function () {
				playNext();
			});

			$(document).on('click', '#toggleFullScreen', function () {
				toggleFullscreen()
			});

			$(document).on('click', '.forward_rewind ', function () {
				let l = $(this).data('type');
				let t = player.currentTime();
				if (l == 'forward') {
					player.currentTime(t + seekStep);
					$('.forward').addClass('active');
				} else {
					player.currentTime(t - seekStep);
					$('.rewind').addClass('active');
				}
			});

			$(document).on('click', '#playMute', function () {
				let isMuted = player.muted();
				if (!isMuted) {
					$('#playMute').parent().addClass('mute');
					player.muted(true);
				} else {
					$('#playMute').parent().removeClass('mute');
					player.muted(false);
				}
			});

			$(document).on('input', '#playVolume', function () {
				let v = parseFloat($(this).val());
				window.setVolume(false, v);
			});

			$(document).on('click', '.playFromList', function () {

				let index = parseInt($(this).attr('data-index'));
				
				if (currentVideoIndex != index) {
					currentVideoIndex = index;
					window.changeVideoState(index);
				}

			});

			$('.dis_progress_handle').draggable({
				axis: 'x',
				containment: ".dis_player_pb_wrap",
				drag: function (e) {
					if (e.cancelable) {
						let pos = (100 * parseFloat($(this).css("left"))) / (parseFloat($(this).parent().css("width")));

						$('.dis_player_pb_play').css({ 'width': pos + "%" });

						let currentTime = (player.duration() * pos) / 100;
						player.currentTime(currentTime);
					}

				},
				start: function () {
					// player.pause()
					triggerPauseVideo();
				},
				stop: function () {
					// player.play()
					triggerPlayVideo();
				}
			});


			$(document).on('click', '#playPause', function () {
				let isPlaying = !player.paused();
				(!isPlaying) ? triggerPlayVideo() : triggerPauseVideo();
			});


			var cursorPosi = true;
			player.on('useractive', function (e) {

				// $('.dis_playerWrap , #playerHeader').fadeIn('slow');
				// $('.vjs-text-track-display').css({top:'-80px'});
				
			})

			player.on('userinactive', function (e) {

				if (cursorPosi) {
					// $('.dis_playerWrap , #playerHeader').fadeOut('slow');
					// $('.vjs-text-track-display').css({top:'0px'});
				}
			})

			$('.dis_playerWrap').on('mouseleave', function (e) {
				cursorPosi = true;
			});

			$('.dis_playerWrap').on('mouseenter', function (e) {
				cursorPosi = false;
			});

			/*
			$('.dis_player_pb_wrap').on('mouseleave , mouseenter', function (e) {
				if (['0', '4', '5'].indexOf(mainPlaylist[player.playlist.currentItem()].single_video.video_type) > -1 && !isMobile) {
					// console.log('mouseleave');
					if (e.type == 'mouseleave') {
						$('.video-js').eq(1).hide()
					} else
						if (e.type == 'mouseenter') {
							$('.video-js').eq(1).show()
						}
				}
			});
			*/

		});
	} //END OF VIDEO ID CONDITION
}
function triggerPlayVideo() {
	var isPlaying = !player.paused();
	if (!isPlaying) {
		let playPromise = player.play();
		if (typeof playPromise !== 'undefined' && typeof playPromise.then === 'function') {
			playPromise.then(null, e => { });
		}
	}
}
function triggerPauseVideo() {
	var isPlaying = !player.paused();
	if (isPlaying) {
		player.pause();
	}
}
function showOnlyCastCrewTab() {
	setTimeout(() => {
		if (showOnlyCastCrew == 1) {
			
			$('.intCastCrew').trigger('click');
			player && player?.pause();
		}
	}, 5000);
}

showOnlyCastCrewTab();

var playListStart = 0;
var playListLimit = 7;

var onceLoaded = true;
function getPlayerPlaylist(currentIndex = '',isSetSrc=false) {
	getPlaylist = false;

	let item = mainPlaylist[0]['single_video'];
	let f = {
		'uid': item.user_id,
		'pid': item.post_id,
		'tag': item.tag,
		'mod': item.mode,
		'cat': item.category,
		'gen': item.genre,
		'start': playListStart,
		'limit': playListLimit,
		'list': playlist.list_id,
	};

	playListLimit = 8;

	if (onceLoaded) {
		manageMyAjaxPostRequestData(f, base_url + "share/load_player_playlist").done(
			function (resp) {
				let r = JSON.parse(resp);
				if (r.status == 1) {
					if (f.list.length) {
						onceLoaded = false;
						mainPlaylist = r.data;
						currentIndex = mainPlaylist.findIndex((li) => f.pid == li.post_id);
						loadMoreFromTab(r.data);
						setPlaylistSrc();
					} else {
						getPlaylist = true;
						mainPlaylist = [...mainPlaylist, ...r.data];
						loadMoreFromTab(r.data)
						if (playListStart == 0) {
							currentIndex = (r.data.findIndex((x) => x.post_id == f.pid)) + 1
						}
					}
					currentVideoIndex = currentIndex;
					
					if(isSetSrc) setPlaylistSrc();
					
					$('#appendPlaylist').html(renderPlaylist(currentVideoIndex));

					playListStart += playListLimit;
				} else {
					if(isSetSrc){
						setPlaylistSrc();
					}
					$('.loadPlaylistAsVideo').text('No more data available').prop('disabled', true);
				}
			});
	} else {
		// setPlaylistSrc();
	}

}

function loadMoreFromTab(data = '') {
	let authorString = '';
	if (data.length > 0) {
		authorString = getMultipleRandom(data, data.length).reduce(function (prevVal, currVal, idx) {
			return prevVal + loadMoreVideoHtml(currVal)
		}, '')
		$('#loadPlaylistVideo').append(authorString);
	} else {
		authorString = getMultipleRandom(mainPlaylist, playListStart).reduce(function (prevVal, currVal, idx) {
			return prevVal + loadMoreVideoHtml(currVal)
		}, '')
		$('#loadPlaylistVideo').html(authorString);
	}

	$('.loadPlaylistAsVideo').html('See More').prop('disabled', false);
}

function getMultipleRandom(arr, num) {
	const shuffled = [...arr].sort(() => 0.5 - Math.random());

	return shuffled.slice(0, num);
}

function urlParam(name) {
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results == null) {
		return null;
	}
	else {
		return results[1] || 0;
	}
}

function setVastUrl(videoBidResult = {}) {
	return new Promise(function(myResolve, myReject) {
		player.on('ads-manager', function (response) {
			adsManager = response.adsManager;
			var crios = navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPhone/i);

			adsManager.addEventListener(google.ima.AdEvent.Type.CONTENT_PAUSE_REQUESTED, function () {
				isAdsRunning = 1;
				if (!crios) {
					setTimeout(() => { triggerPauseVideo(); }, 1000);
				}
				$('.gam-ad-earn-burn').show();  //gamification
				$('.gam-ad-poll').show();  //gamification
				$('#my_video_ima-ad-container').css({ display: 'block' });
				clearInterval(interval);
			});

			adsManager.addEventListener(google.ima.AdEvent.Type.STARTED, function () {
				if (!crios) {
					setTimeout(() => { triggerPauseVideo(); }, 1000);
				}
				isAdsRunning = 1;
			});

			adsManager.addEventListener(google.ima.AdEvent.Type.LOADED, function () {
				isAdsRunning = 1;
				if (!crios) {
					setTimeout(() => { triggerPauseVideo(); }, 1000);
				}
			});

			adsManager.addEventListener(google.ima.AdEvent.Type.CONTENT_RESUME_REQUESTED, function () {
				isAdsRunning = 0;
				setTimeout(() => { triggerPlayVideo() }, 1000)
				$('#my_video_ima-ad-container').css({ display: '' });
			});

			adsManager.addEventListener(google.ima.AdEvent.Type.SKIPPED, function () {
				isAdsRunning = 0;
				setTimeout(() => { triggerPlayVideo() }, 1000)
				//$('#my_video_ima-ad-container').css({ display: '' }); // commented by nitesh on date 08-Nov-2023
			});

			adsManager.addEventListener(google.ima.AdEvent.Type.VOLUME_CHANGED, function () {
				if (player.muted()) {
					$('#playMute').parent().addClass('mute');
				} else {
					$('#playMute').parent().removeClass('mute');
				}
			});

			adsManager.addEventListener(google.ima.AdEvent.Type.LOG, function (event) {
				let adData = event.getAdData();
				if (adData['adError']) {
					console.log('Non-fatal error occurred: ' + adData['adError'].getMessage());
				}
			});
		})


		var contentPlayer = document.getElementById(videoid);

		if ((navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/Android/i)) && contentPlayer.hasAttribute('controls')) {
			contentPlayer.removeAttribute('controls');
		}

		var initAdDisplayContainer = function () {
			player.ima.initializeAdDisplayContainer();
			wrapperDiv.removeEventListener(startEvent, initAdDisplayContainer);
		}

		var startEvent = 'click';
		if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/Android/i)) {
			startEvent = 'touchend';
		}

		if (isMobile) {
			var wrapperDiv = document.getElementById(videoid);
			wrapperDiv.addEventListener(startEvent, initAdDisplayContainer);
		}

		let CACHEBUSTER = Date.now();
		let page_url = encodeURIComponent(window.location.href);
		let size = encodeURIComponent('400x300|640x480');

		// let currentIndex = player.playlist.currentItem();

		const { VideoUserId, VideoPostId, VideoMode, UidIdWhoIsWatching, domain, is_stream_live } = getCustomParam(currentVideoIndex);

		WatchlistName = "my_watch_history_" + UidIdWhoIsWatching;

		var custom = 'category=' + VideoMode + '&user_id=' + VideoUserId + '&video_id=' + VideoPostId + '&viewer_id=' + UidIdWhoIsWatching;
		var utm_source = urlParam('utm_source');

		if (utm_source !== null) {
			custom += '&source=' + utm_source;
		}

		custom = encodeURIComponent(custom);
		
		// vastUrl = 'https://pubads.g.doubleclick.net/gampad/ads?iu=/22019190093/' + domain + '_video&description_url=' + page_url + '&url=' + page_url + '&tfcd=0&npa=0&sz=' + size + '&cust_params=' + custom + '&vid=' + VideoPostId + '&cmsid=2528975&gdfp_req=1&output=vmap&unviewed_position_start=1&env=vp&impl=s&ad_rule=1&Policy=' + Policy + '&correlator=' + CACHEBUSTER;

		// vastUrl = 'https://pubads.g.doubleclick.net/gampad/ads?iu=/21775744923/external/single_ad_samples&sz=640x480&cust_params=sample_ct%3Dlinear&ciu_szs=300x250%2C728x90&gdfp_req=1&output=vast&unviewed_position_start=1&env=vp&impl=s&correlator=';
		if (typeof player.ima == 'function') {
			
			
			// vastUrl = typeof videoBidResult.adTagUrl !== 'undefined' ? videoBidResult.adTagUrl : vastUrl;
			if(is_stream_live == 1){
				videoBidResult = 'https://pubads.g.doubleclick.net/gampad/live/ads?iu=/22019190093/dtv_livestreaming_web&description_url=https%3A%2F%2Fdiscovered.tv%2F&tfcd=0&npa=0&sz=1x1%7C400x300%7C640x480&cmsid=2528975&vid='+VideoPostId+'&gdfp_req=1&output=vast&unviewed_position_start=1&env=vp&impl=s&devicetype=web&correlator='+CACHEBUSTER;
			}
			
			player.ima({ vastLoadTimeout: 3000, adTagUrl: videoBidResult });
			myResolve()
		}
	});

}

function getCurrentIndex() {
	return mainPlaylist.length == currentVideoIndex ? 0 : currentVideoIndex;
}

var isChatLoaded = false;
function setPlaylistSrc() {
	let currentIndex = getCurrentIndex();
	let cureentVideo = mainPlaylist[currentIndex];
	player.src(cureentVideo['sources']);
	player.poster(cureentVideo['poster']);
	
	triggerPlayVideo();
	
	if(cureentVideo['single_video']['is_chat'] == 1 && cureentVideo['single_video']['is_stream_live'] == 1 ){
		$('#according_chat').show();
		if(isChatLoaded){
			window.loadChat();
		}
	}else{
		$('#according_chat').hide();
	}
}

var AddLoadedOnce = false;
async function setNupdateQuality() {

	$('.video-js').eq(1).hide();

	vidDuration = player.duration() != 'Infinity' ? player.duration() : 30;
	seekTime = player.duration() * 0.5 / 100;

	// let currentIndex = player.playlist.currentItem();
	let currentIndex = getCurrentIndex()

	let item = mainPlaylist[currentIndex].single_video;
	
	window.get_stream(item);

	$('#playerHeader').html(getPlayerHeader(currentIndex));
	$('#playerFooter').html(getPlayerFooter(currentIndex));
	// $('.dis_SV_vDetails').css('padding', '15px 15px 15px');
	$('#playerDetail').html(await getPlayerDetail(currentIndex));

	if(!AddLoadedOnce){
		AddLoadedOnce=true;
		addSheMediaAdsOnSingleVideoOnTheTopRight('#display_right', 'html')
		setTimeout(()=> addSheMediaAdsAdsOnSingleVideoOnTheTop('#display_top', 'html'),600);
		setTimeout(()=>addSheMediaAdsOnSingleVideoOnTheMiddle('#display_middle', 'html'),1200);
		setTimeout(()=>addSheMediaAdsOnSingleVideoOnTheBottom('#display_bottom', 'html'),1800);
	}
	
	AdAdsOnChannel($('.intSlider'), function () { }, is_slider = false);

	window.changeQuality = function (qualityIndex) {
		let qualities = player?.tech_?.vhs?.representations();

		qualities.forEach(quality => quality.enabled(false));
		qualities[qualityIndex].enabled(true);

		setQuality();
	}

	var setQuality = function () {

		if (player.tech_.vhs && typeof player.tech_.vhs.representations == 'function') {

			let qualities = player?.tech_?.vhs?.representations();
			let current = player?.tech_?.vhs?.selectPlaylist().attributes.RESOLUTION.height;

			$('#current_quality').html(`${typeof current != 'undefined' ? current + 'p' : 'Auto'} <span>
				<svg width="5" height="7" viewBox="0 0 5 7" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M4.77008 2.84L2.23008 0.3V0.28L2.17008 0.22C1.87008 -0.0700004 1.40008 -0.0700004 1.11008 0.22C0.820078 0.51 0.820078 0.99 1.11008 1.28L3.19008 3.36L1.12008 5.43C0.830078 5.72 0.830078 6.2 1.12008 6.49C1.25008 6.64 1.44008 6.72 1.65008 6.72C1.84008 6.72 2.03008 6.64 2.18008 6.5L4.78008 3.9C4.92008 3.77 5.01008 3.57 5.01008 3.37C5.01008 3.17 4.93008 2.99 4.79008 2.84H4.77008Z" fill="white"/>
				</svg>
			</span>`);

			showQuality(qualities, current);
		}
	}

	setTimeout(function () {
		setQuality();
	}, 1500);

	function showQuality(qualities, current = '') {
		let list = '';
		if (qualities && qualities.length) {
			qualities?.forEach((item, index) => {
				list += `<li class="${item.height == current ? 'selected' : ''}" onclick="changeQuality(${index})">${item.height}p</li>`;
			})
		} else {
			list = `<li>Auto</li>`;
		}

		$('#playQualities').html(list);
	}

	let playBackRate = [0.25, 0.5, 0.75, 'Normal', 1.25, 1.5, 1.75, 2];

	window.changeRate = function (rate) {
		player.playbackRate(rate == 'Normal' ? 1 : rate);
		setTimeout(() => {
			showPlayBackRate();
		}, 100)
	}

	function showPlayBackRate() {
		let currentRate = player.playbackRate();
		currentRate = currentRate == 1 ? 'Normal' : currentRate

		$('#currentRate').html(`${currentRate}
		<span>
			<svg width="5" height="7" viewBox="0 0 5 7" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M4.77008 2.84L2.23008 0.3V0.28L2.17008 0.22C1.87008 -0.0700004 1.40008 -0.0700004 1.11008 0.22C0.820078 0.51 0.820078 0.99 1.11008 1.28L3.19008 3.36L1.12008 5.43C0.830078 5.72 0.830078 6.2 1.12008 6.49C1.25008 6.64 1.44008 6.72 1.65008 6.72C1.84008 6.72 2.03008 6.64 2.18008 6.5L4.78008 3.9C4.92008 3.77 5.01008 3.57 5.01008 3.37C5.01008 3.17 4.93008 2.99 4.79008 2.84H4.77008Z" fill="white"/>
			</svg>
		</span>`);

		let list = '';
		playBackRate?.forEach((item, index) => {
			list += `<li class="${item == currentRate ? 'selected' : ''}" onclick="changeRate('${item}')">${item}</li>`;
		})
		$('#playBackRate').html(list);
	}
	showPlayBackRate();


	window.changeCaption = function (lang) {
		currentCaption = lang;
		let tracks = player.textTracks();
		for (let i = 0; i < tracks.length; i++) {
			let track = tracks[i];
			track.mode = 'disabled';
			if (track.kind === 'captions' && track.language === lang) {
				track.mode = 'showing';
				window.showCaptions();
			}
		}
	}


	let currentCaption = 'en', isCaptionLoad = false;
	window.showCaptions = function () {
		if (item.captions.length) {
			$('#currentCaption').closest('li').show();
			$('#currentCaption').html(`${currentCaption}
			<span>
				<svg width="5" height="7" viewBox="0 0 5 7" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M4.77008 2.84L2.23008 0.3V0.28L2.17008 0.22C1.87008 -0.0700004 1.40008 -0.0700004 1.11008 0.22C0.820078 0.51 0.820078 0.99 1.11008 1.28L3.19008 3.36L1.12008 5.43C0.830078 5.72 0.830078 6.2 1.12008 6.49C1.25008 6.64 1.44008 6.72 1.65008 6.72C1.84008 6.72 2.03008 6.64 2.18008 6.5L4.78008 3.9C4.92008 3.77 5.01008 3.57 5.01008 3.37C5.01008 3.17 4.93008 2.99 4.79008 2.84H4.77008Z" fill="white"/>
				</svg>
			</span>`);

			let list = '';
			item.captions?.forEach((item, index) => {
				list += `<li class="${item.language == currentCaption ? 'selected' : ''}" onclick="changeCaption('${item.language}')">${item.language}</li>`;

			})
			$('#CaptionSubtitle').html(list);

			if (!isCaptionLoad) {
				isCaptionLoad = true;
				item.captions?.forEach((item, index) => {
					let captionOption = {
						kind: 'captions',
						srclang: item.language,
						label: item.language,
						src: AMAZON_URL + 'aud_' + item.user_id + '/captions/' + item.caption_name
					};
					player.addRemoteTextTrack(captionOption);
				})
				window.changeCaption('en');
			}
		} else {
			$('#currentCaption').closest('li').hide();
		}


	}
	window.showCaptions();
	window.my_watch_history();
	window.showPrevNext();

	$('#appendPlaylist > li').removeClass('active')
	$('#appendPlaylist > li').eq(currentIndex).addClass('active');

	if (currentIndex == 0) {
		let t = eval(urlParam('t'));
		if (t > 0) player.currentTime(t);
	} else {
		$(document).prop('title', mainPlaylist[currentIndex].metaData.title);

		let key = playlist.list_key ? '/' + playlist.list_key : '';

		window.history.pushState({ href: item.post_key, index: currentIndex }, '', '/watch/' + item.post_key + key);

		scrollPlaylist(currentIndex);
	}
}

function playNext() {
	let ply = get('player_next');

	if (ply) {
		window.pribidInit('midroll');
		currentVideoIndex = mainPlaylist.length == currentVideoIndex + 1 ? 0 : currentVideoIndex + 1;
		window.changeVideoState(currentVideoIndex);
	}
}

function playPrevious() {
	currentVideoIndex = currentVideoIndex - 1 < 0 ? mainPlaylist.length - 1 : currentVideoIndex - 1;
	window.changeVideoState(currentVideoIndex);
}

var seeking = 0, seekTime = 0;
$(document).on("click , mousemove", "#progressBar", function (e) {

	let offset = $(this).offset();
	let left = (e.pageX - offset.left);
	let totalWidth = $(this).width();
	let percentage = (left / totalWidth);
	let currentTime = player.duration() * percentage;

	if (e.type == 'click') {
		player.currentTime(currentTime);
		let p = (currentTime / player.duration()) * 100;
		$(this).find('div:nth-child(2)').css("width", p + "%");

		let width = $('.dis_player_pb_wrap').width();

		$('.dis_progress_handle ').css({
			'left': width * p / 100
		});
		seeked = true;
	} else {
		/*
		if (!isMobile) {
			$('.video-js').eq(1).css({ 'left': e.clientX - 190 + 'px' });
			if (currentTime - seeking >= seekTime || currentTime - seeking <= -seekTime) {

				seeking = currentTime;

				if (currentTime && seeked)
					thumbPlayer?.currentTime(currentTime);
			}
		}
		*/
	}

});

function formatTime(seconds) {
	let totalminutes = Math.floor(seconds / 60);

	let hours = Math.floor(totalminutes / 60);
	hours = (hours >= 10) ? hours : "0" + hours;

	let minutes = Math.floor(totalminutes % 60);
	minutes = (minutes >= 10) ? minutes : "0" + minutes;

	seconds = Math.floor(seconds % 60);
	seconds = (seconds >= 10) ? seconds : "0" + seconds;

	if (hours == '00') {
		return minutes + ":" + seconds;
	} else {
		return hours + ":" + (minutes == '60' ? '00' : minutes) + ":" + ((seconds == '60' ? '00' : seconds));
	}
}

// Create fullscreen video button
function toggleFullscreen() {
	let bt = document.querySelector('.dis_SV_vBox');

	if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {  // current working methods
		if (bt.requestFullscreen) {
			bt.requestFullscreen();
		} else if (bt.msRequestFullscreen) {
			bt.msRequestFullscreen();
		} else if (bt.mozRequestFullScreen) {
			bt.mozRequestFullScreen();
		} else if (bt.webkitRequestFullscreen) {
			bt.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
		}
	} else {
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} else if (document.msExitFullscreen) {
			document.msExitFullscreen();
		} else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if (document.webkitExitFullscreen) {
			document.webkitExitFullscreen();
		}
	}
}



function getPlayerFooter(currentIndex) {
	var currentItem = mainPlaylist[currentIndex];
	let item = currentItem['single_video'];

	var html = `<ul class="dis_SV_vdlist1">
			<li>
				<p class="dis_SV_vttl mp_0" onclick="$('.more_text').click()">${item.title}</p>
			</li>
			<li>
				<ul class="dis_SV_vdlist2">
					<!--li>
						<span class="dis_SV_infoIcon">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M6.99992 0.583328C5.73082 0.583328 4.49023 0.959659 3.43501 1.66473C2.3798 2.3698 1.55736 3.37195 1.07169 4.54444C0.586032 5.71694 0.458961 7.00711 0.706549 8.25182C0.954138 9.49654 1.56527 10.6399 2.46265 11.5373C3.36004 12.4347 4.50338 13.0458 5.74809 13.2934C6.9928 13.541 8.28298 13.4139 9.45547 12.9282C10.628 12.4426 11.6301 11.6201 12.3352 10.5649C13.0403 9.50969 13.4166 8.26909 13.4166 6.99999C13.4146 5.2988 12.7379 3.66787 11.535 2.46494C10.332 1.26202 8.70111 0.585335 6.99992 0.583328ZM9.16234 9.16241C9.05295 9.27177 8.9046 9.3332 8.74992 9.3332C8.59524 9.3332 8.4469 9.27177 8.3375 9.16241L6.5875 7.41241C6.4781 7.30304 6.41662 7.15469 6.41659 6.99999V3.49999C6.41659 3.34529 6.47805 3.19691 6.58744 3.08752C6.69684 2.97812 6.84521 2.91666 6.99992 2.91666C7.15463 2.91666 7.303 2.97812 7.4124 3.08752C7.5218 3.19691 7.58325 3.34529 7.58325 3.49999V6.75849L9.16234 8.33758C9.2717 8.44697 9.33313 8.59532 9.33313 8.74999C9.33313 8.90467 9.2717 9.05302 9.16234 9.16241Z" fill="#9C9DA3"/>
							</svg>
						</span>
						<span class="dis_SV_infotext">${item.created_at}</span>
					</li-->
					<!--li>
						<span class="dis_SV_infoIcon">
							<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<g clip-path="url(#clip0_47_155)">
							<path d="M7.5 9.875C8.53553 9.875 9.375 9.03553 9.375 8C9.375 6.96447 8.53553 6.125 7.5 6.125C6.46447 6.125 5.625 6.96447 5.625 8C5.625 9.03553 6.46447 9.875 7.5 9.875Z" fill="#9C9DA3"/>
							<path d="M14.7699 7.36161C12.9949 5.21658 10.3079 3.15179 7.50004 3.15179C4.69166 3.15179 2.00404 5.21802 0.230134 7.36161C-0.0767114 7.73225 -0.0767114 8.27031 0.230134 8.64095C0.676114 9.17988 1.61108 10.2202 2.85983 11.1292C6.00475 13.4186 8.9884 13.4237 12.1402 11.1292C13.389 10.2202 14.324 9.17988 14.7699 8.64095C15.0759 8.27104 15.0775 7.73346 14.7699 7.36161V7.36161ZM7.50004 4.76829C9.2828 4.76829 10.733 6.21852 10.733 8.00128C10.733 9.78405 9.2828 11.2343 7.50004 11.2343C5.71727 11.2343 4.26704 9.78405 4.26704 8.00128C4.26704 6.21852 5.71727 4.76829 7.50004 4.76829Z" fill="#9C9DA3"/>
							</g>
							<defs>
							<clipPath id="clip0_47_155">
							<rect width="15" height="15" fill="white" transform="translate(0 0.5)"/>
							</clipPath>
							</defs>
							</svg>
						</span>
						<span class="dis_SV_infotext">${item.count_views} Views</span>
					</li>
					<li>
						<span class="dis_SV_infoIcon">
							<svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M9.98958 0.75H3.11458C2.35513 0.75091 1.62704 1.05301 1.09002 1.59002C0.553005 2.12704 0.25091 2.85513 0.25 3.61458V8.19792C0.250833 8.85803 0.479218 9.49768 0.896661 10.0091C1.3141 10.5204 1.89508 10.8722 2.54167 11.0052V12.7812C2.54165 12.885 2.56979 12.9868 2.62309 13.0757C2.67639 13.1647 2.75285 13.2376 2.8443 13.2865C2.93576 13.3354 3.03878 13.3586 3.14238 13.3536C3.24598 13.3485 3.34627 13.3155 3.43255 13.2579L6.72396 11.0625H9.98958C10.749 11.0616 11.4771 10.7595 12.0141 10.2225C12.5512 9.68546 12.8533 8.95737 12.8542 8.19792V3.61458C12.8533 2.85513 12.5512 2.12704 12.0141 1.59002C11.4771 1.05301 10.749 0.75091 9.98958 0.75ZM8.84375 7.625H4.26042C4.10847 7.625 3.96275 7.56464 3.8553 7.4572C3.74786 7.34975 3.6875 7.20403 3.6875 7.05208C3.6875 6.90014 3.74786 6.75441 3.8553 6.64697C3.96275 6.53953 4.10847 6.47917 4.26042 6.47917H8.84375C8.9957 6.47917 9.14142 6.53953 9.24886 6.64697C9.35631 6.75441 9.41667 6.90014 9.41667 7.05208C9.41667 7.20403 9.35631 7.34975 9.24886 7.4572C9.14142 7.56464 8.9957 7.625 8.84375 7.625ZM9.98958 5.33333H3.11458C2.96264 5.33333 2.81691 5.27297 2.70947 5.16553C2.60203 5.05809 2.54167 4.91236 2.54167 4.76042C2.54167 4.60847 2.60203 4.46275 2.70947 4.3553C2.81691 4.24786 2.96264 4.1875 3.11458 4.1875H9.98958C10.1415 4.1875 10.2873 4.24786 10.3947 4.3553C10.5021 4.46275 10.5625 4.60847 10.5625 4.76042C10.5625 4.91236 10.5021 5.05809 10.3947 5.16553C10.2873 5.27297 10.1415 5.33333 9.98958 5.33333Z" fill="#9C9DA3"/>
							</svg>
						</span>
						<span class="dis_SV_infotext">${item.count_comments} Comments</span>
					</li-->
				</ul>
			</li>
		</ul>
		<p class="dis_SV_vdes mp_0">${partOfString(item.description, start = 0, end = 50)} <!--a href="#" class="primary_link">Read More</a--></p>
		<ul class="dis_SV_vdlist3">
			<li>

				<a href="${base_url + 'profile?user=' + item.user_uname}" class="dis_SV_UD">
					<span class="dis_SV_UDL">
						<img src="${item?.user_pic}" onerror="this.onerror=null;this.src='${item.user_default_image}'"  alt="image" class="img-responsive">
					</span>
					<div class="dis_SV_UDR">
						<p class="dis_SV_vttl mp_0">${item.user_name}</p>
						<p class="dis_SV_UIcon mp_0">${item.user_level}, United States</p>
					</div>
				</a>

			</li>
			<li>
				<ul class="dis_SV_vdlist4">
					<li>
					${user_login_id != item.user_id ? atob(item.become_a_fan) : ''}
					</li>
					<li>
						<a href="javascript:;" class="dis_SV_btn ${user_login_id == '' ? 'openModalPopup' : (item.isvoted != 1 ? 'yr_vote' : '')} " data-type="new" data-href="modal/login_popup" data-cls="login_mdl" data-pid="${item.post_id}">
							<span class="dis_SV_btnIcon">
								<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M7 12.631C6.92918 12.631 6.85839 12.6127 6.79495 12.5761C6.72605 12.5363 5.08895 11.5858 3.42837 10.1536C2.44416 9.30475 1.65852 8.46284 1.09333 7.65128C0.361937 6.60112 -0.00586292 5.59098 7.06585e-05 4.64891C0.00701596 3.5527 0.399644 2.52179 1.10571 1.74605C1.8237 0.95724 2.78188 0.522858 3.8038 0.522858C5.11348 0.522858 6.31089 1.25649 7.00003 2.41865C7.68917 1.25652 8.88658 0.522858 10.1963 0.522858C11.1617 0.522858 12.0828 0.914802 12.7901 1.6265C13.5662 2.40752 14.0072 3.51106 13.9999 4.65411C13.994 5.59454 13.6193 6.60314 12.8863 7.65185C12.3193 8.463 11.5348 9.30453 10.5544 10.1531C8.89992 11.5852 7.27459 12.5357 7.2062 12.5754C7.14246 12.6125 7.07121 12.631 7 12.631Z" fill="#515151"/>
								</svg>
							</span>
							${item.isvoted ? 'Loved' : 'Love it'}
						</a>
					</li>
					<li>
						<a href="javascript:;" class="dis_SV_btn dtvShareMe" data-share="2|${item.post_id}">
							<span class="dis_SV_btnIcon">
								<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M12.8623 4.54854L9.14792 0.834281C9.05602 0.742376 8.94724 0.696411 8.82149 0.696411C8.69574 0.696411 8.58689 0.742376 8.49506 0.834281C8.40316 0.926237 8.35714 1.03506 8.35714 1.16079V3.01792H6.73215C3.28372 3.01792 1.16796 3.99246 0.384364 5.94149C0.128113 6.58948 0 7.39482 0 8.35724C0 9.16002 0.30712 10.2506 0.92131 11.629C0.935793 11.6629 0.961101 11.7208 0.997436 11.803C1.03375 11.8851 1.06635 11.9577 1.09536 12.0206C1.12448 12.0834 1.15594 12.1366 1.18976 12.1801C1.24774 12.2623 1.31548 12.3036 1.39288 12.3036C1.46542 12.3036 1.52226 12.2794 1.5634 12.231C1.60443 12.1827 1.62499 12.1222 1.62499 12.0498C1.62499 12.0062 1.61894 11.9422 1.60682 11.8575C1.59473 11.7728 1.58866 11.7161 1.58866 11.6871C1.56444 11.3583 1.55235 11.0606 1.55235 10.7948C1.55235 10.3064 1.59473 9.8687 1.67929 9.48182C1.76398 9.09487 1.88124 8.76003 2.03121 8.47707C2.18115 8.19401 2.37453 7.94996 2.61158 7.7444C2.84852 7.53884 3.1036 7.37083 3.37685 7.24023C3.65015 7.1096 3.97175 7.00682 4.34174 6.93189C4.71169 6.85693 5.08406 6.80492 5.45892 6.77588C5.83379 6.74683 6.25817 6.7324 6.73215 6.7324H8.35714V8.58958C8.35714 8.71531 8.40306 8.82416 8.49488 8.91601C8.58687 9.00782 8.69564 9.05383 8.82131 9.05383C8.94704 9.05383 9.05589 9.00782 9.14792 8.91601L12.8622 5.20163C12.9541 5.10972 13 5.00095 13 4.8752C13 4.74947 12.9541 4.64062 12.8623 4.54854Z" fill="#515151"/>
								</svg>
							</span>
							Share
						</a>
					</li>
					<li>
						<div class="default_dd dropdown">
							<button class="dropdown-toggle" data-toggle="dropdown">
								<a href="Jjavascript:;" class="dis_SV_btn">
									<span class="dis_SV_btnIcon">
										<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M6.25 0C2.80208 0 0 2.80208 0 6.25C0 9.69792 2.80208 12.5 6.25 12.5C9.69792 12.5 12.5 9.69792 12.5 6.25C12.5 2.80208 9.69792 0 6.25 0ZM9.29167 5.70833L6.6875 8.3125C6.5625 8.4375 6.40625 8.5 6.25 8.5C6.09375 8.5 5.92708 8.4375 5.8125 8.3125L3.20833 5.70833C3.09375 5.59375 3.02083 5.4375 3.02083 5.27083C3.02083 5.10417 3.08333 4.94792 3.20833 4.83333C3.44792 4.59375 3.85417 4.59375 4.09375 4.83333L6.26042 7L8.42708 4.83333C8.66667 4.59375 9.07292 4.59375 9.3125 4.83333C9.55208 5.07292 9.55208 5.46875 9.3125 5.71875L9.29167 5.70833Z" fill="#515151"/>
										</svg>
									</span>
									More
								</a>
							</button>

							<ul class="dropdown-menu">
								<li><a class="dropdown-item openModalPopup" href="javascript:;" data-cls="dis_addplaylist_modal dis_center_modal muli_font" data-href="${user_login_id == '' ? 'modal/login_popup' : 'modal/playlist_popup/' + item.post_id}" data-cls="${user_login_id == '' ? 'login_mdl' : 'dis_addplaylist_modal dis_center_modal muli_font'}">
										<span class="wb_dd_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g fill="#777777"><path d="M20.68 13.91v-.01c-.88-.93-2.11-1.5-3.48-1.5-2.65 0-4.8 2.15-4.8 4.8 0 1.23.47 2.35 1.23 3.2.88.98 2.15 1.6 3.57 1.6 2.65 0 4.8-2.15 4.8-4.8 0-1.27-.5-2.43-1.32-3.29zm-1.69 4.1h-1.04v1.09c0 .41-.34.75-.75.75s-.75-.34-.75-.75v-1.09h-1.04c-.42 0-.75-.33-.75-.75 0-.41.32-.74.74-.75h1.05v-1c0-.02 0-.04.01-.06.02-.38.35-.69.74-.69.4 0 .73.32.75.72v1.03h1.04c.42 0 .75.34.75.75 0 .42-.33.75-.75.75z" fill="#777777" data-original="#777777" class=""></path><path d="M22 8.73c0 1.19-.19 2.29-.52 3.31-.06.21-.31.27-.49.14a6.346 6.346 0 0 0-3.79-1.24c-3.47 0-6.3 2.83-6.3 6.3 0 1.08.28 2.14.81 3.08.16.28-.03.64-.33.53-2.41-.82-7.28-3.81-8.86-8.81C2.19 11.02 2 9.92 2 8.73c0-3.09 2.49-5.59 5.56-5.59 1.81 0 3.43.88 4.44 2.23a5.549 5.549 0 0 1 4.44-2.23c3.07 0 5.56 2.5 5.56 5.59z" fill="#777777" data-original="#777777" class=""></path></g></g></svg>
										</span>
										Add To Playlist
									</a>
								</li>
								<li><a class="dropdown-item ${user_login_id == '' ? 'openModalPopup' : 'AddToFavriote'}" href="javascript:;" data-post_id="${item.post_id}" data-type="new" data-href="modal/login_popup" data-cls="login_mdl">
										<span class="wb_dd_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18"  x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill="#777777" d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9h-4v4h-2v-4H9V9h4V5h2v4h4z" data-original="#777777" class=""></path></g></svg>
											</span>
										Add To favorites
									</a>
								</li>
								<li><a class="dropdown-item openModalPopup" href="javascript:;" data-href="${user_login_id == '' ? 'modal/login_popup' : 'modal/report_content_popup/0/content/Why-are-you-reporting-this-video'}" data-cls="${user_login_id == '' ? 'login_mdl' : 'dis_Reporting_modal dis_center_modal'}" data-heading="Why are you reporting this video ?" data-viol_id="0" data-parent_id="0" data-type="content" data-related_with="3" data-related_id="${item.post_id}">
										<span class="wb_dd_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="14"  x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 19.66c-.938 0-1.58-.723-1.58-1.66 0-.964.669-1.66 1.58-1.66.963 0 1.58.696 1.58 1.66 0 .938-.617 1.66-1.58 1.66zm.622-6.339c-.239.815-.992.829-1.243 0-.289-.956-1.316-4.585-1.316-6.942 0-3.11 3.891-3.125 3.891 0-.001 2.371-1.083 6.094-1.332 6.942z" style="" fill="#777777" data-original="#030104" class=""></path></g></svg>
										</span>
										Report
									</a>
								</li>
							</ul>
						</div>
					</li>
				</ul>
			</li>
		</ul>`;

	return html;
}

function getPlayerHeader(currentIndex) {
	var currentItem = mainPlaylist[currentIndex];
	let item = currentItem['single_video'];

	var html = `<p class="dis_SV_vb_ttl">${item.title}</p>
			<ul class="dis_SV_vb_list">
				<li>
					<span class="dis_SV_vb_lIcon">
						<svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M12.4566 4.61183C11.3291 4.44941 10.1285 4.333 8.87548 4.26668L10.9687 2.17416L10.1562 1.36205L7.30546 4.2112C7.03882 4.20714 6.77029 4.20443 6.5 4.20443L3.25 0.955994L2.4375 1.7681L4.9029 4.23284C3.44399 4.28091 1.98873 4.40742 0.543428 4.61183C-0.181143 7.54416 -0.181143 10.6086 0.543428 13.541C4.49588 14.0842 8.50412 14.0842 12.4566 13.541C13.1811 10.6086 13.1811 7.54416 12.4566 4.61183ZM10.967 12.0534C7.99967 12.4144 4.99952 12.4144 2.0322 12.0534C1.48891 10.1059 1.48891 8.0469 2.0322 6.09934C4.99952 5.73841 7.99967 5.73841 10.967 6.09934C11.5106 8.04684 11.5107 10.1059 10.9674 12.0534H10.967Z" fill="white"/>
						</svg>
					</span>
					<span class="dis_SV_vb_lText">${item.web_mode}</span>
				</li>
				${(item.genre_name) ?
			`<li>
						<span class="dis_SV_vb_lText">${item.genre_name}</span>
					</li>`
			: ''
		}
			</ul>
			<!--p class="dis_SV_rate mp_0">Rated : ${item.age_restr}</p-->`;

	return html;
}

async function getPlayerDetail(currentIndex) {
	var currentItem = mainPlaylist[currentIndex];
	let item = currentItem['single_video'];
	let b = await window.getRelatedVideo();

	var html = `<ul class="nav dis_SV_Tabs">
			<li class="${(showOnlyCastCrew == 1) ? 'hideme' : 'active'}">
				<a class="dis_SV_TItems" href="#sv_creator" data-toggle="tab" aria-expanded="false">More From ${item.user_name}</a>
			</li>
			<li class="${(showOnlyCastCrew == 1) ? 'hideme' : ''}" onclick="loadMoreFromTab()">
				<a class="dis_SV_TItems" href="#sv_like_videos" data-toggle="tab" aria-expanded="false">More videos like this</a>
			</li>
			<li class="${(showOnlyCastCrew == 1) ? 'hideme' : ''}">
				<a class="dis_SV_TItems" href="#sv_comments" onclick="fetchComment(0,${item.post_id},${item.user_id});this.removeAttribute('onclick')" data-toggle="tab" aria-expanded="true">Comments(${item.count_comments})</a>
			</li>

			<li>
				<a class="dis_SV_TItems intCastCrew" data-post_id="${item.post_id}" href="#sv_cast" role="tab" data-toggle="tab" aria-expanded="false">Cast & Crew</a>
			</li>
		</ul>

		<div class="dis_SV_Tabs_Content">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane ${(showOnlyCastCrew == 1) ? 'hideme' : 'active'}" id="sv_creator">
					<div class="dis_other_video_div singl_view ">
						<div class="row">
							<div id="load_related" class="revideo_inner dis_load_vid sigl_pg_revideo">
								${b}
							</div>
						</div>
						<div class="dis_btndiv">
							<a  class="dis_btn intSlider" data-uid="${item.user_id}">See More
							</a>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane ${(showOnlyCastCrew == 1) ? 'hideme' : ''}" id="sv_like_videos">
					<div class="dis_other_video_div singl_view ">
						<div class="row">
							<div id="loadPlaylistVideo" class="revideo_inner dis_load_vid sigl_pg_revideo">

							</div>
						</div>
						<div class="dis_btndiv">
							<a  class="dis_btn loadPlaylistAsVideo" data-uid="${item.user_id}">See More
							</a>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="sv_comments">
					<div class="dis_vid_commentWrap muli_font">

					</div>
				</div>

				<div role="tabpanel" class="tab-pane" id="sv_cast">
					<div class="dis_cast_div muli_font">
						<ul class="dis_CastCrewList" id="castandcrewhtmlSingleVideo">
							${user_login_id == item.user_id ?
								`<li>
									<div class="dis_CastCrewBox dis_CCB_new openModalPopup" data-href="modal/cast_crew_popup/${item.post_id}" data-cls="dis_add_cast_popup">
										<span class="dis_CCBNIcon">
											<svg xmlns="https://www.w3.org/2000/svg" width="24px" height="23px"><path fill-rule="evenodd" fill="rgb(117, 117, 117)" d="M22.135,9.760 L13.823,9.760 L13.823,1.795 C13.823,1.293 13.651,0.869 13.307,0.522 C12.963,0.177 12.528,0.004 12.004,0.004 C11.479,0.004 11.045,0.177 10.701,0.522 C10.356,0.869 10.184,1.293 10.184,1.795 L10.184,9.760 L1.873,9.760 C1.348,9.760 0.905,9.925 0.545,10.255 C0.184,10.585 0.004,11.002 0.004,11.504 C0.004,11.975 0.184,12.384 0.545,12.729 C0.905,13.075 1.348,13.248 1.873,13.248 L10.184,13.248 L10.184,21.213 C10.184,21.716 10.356,22.140 10.701,22.485 C11.045,22.831 11.479,23.004 12.004,23.004 C12.528,23.004 12.963,22.831 13.307,22.485 C13.651,22.140 13.823,21.716 13.823,21.213 L13.823,13.248 L22.135,13.248 C22.659,13.248 23.101,13.083 23.463,12.753 C23.823,12.423 24.004,12.007 24.004,11.504 C24.004,11.002 23.823,10.585 23.463,10.255 C23.101,9.925 22.659,9.760 22.135,9.760 L22.135,9.760 Z"></path></svg>
										</span>
										<h3 class="dis_CCBNtext">Add New Cast / Crew</h3>
									</div>
								</li>`
							: ''}
						</ul>
					</div>
				</div>
			</div>
		</div>`;

	return html;
}

function renderPlaylist(currentIndex = '') {
	var html = '';
	if (mainPlaylist.length > 0) {

		$.each(mainPlaylist, function (i) {
			let currentItem = mainPlaylist[i];
			let item = currentItem['single_video'];

			let active = currentIndex == i ? 'active' : '';
			html += `<li class="playFromList ${active}" data-index="${i}" >
					<div class="ap_videoWrap">
						<div class="ap_videoImg">
							<img src="${currentItem.poster}" onError="ImageOnLoadError(this,'` + currentItem.poster + `','` + currentItem.errimg + `')"  alt="image" class="img-responsive">
							<span class="dis_videotime">${secondsToHms(eval(item.video_duration) + 1)}</span>
							<!--div class="ap_view">
								<span class="ap_viewI">
									<svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M9.84662 3.57441C8.66323 2.14439 6.8719 0.767868 5.00002 0.767868C3.12777 0.767868 1.33602 2.14535 0.153423 3.57441C-0.0511409 3.8215 -0.0511409 4.18021 0.153423 4.4273C0.450742 4.78659 1.07405 5.48014 1.90655 6.08613C4.00316 7.61241 5.99226 7.61578 8.09349 6.08613C8.92598 5.48014 9.5493 4.78659 9.84662 4.4273C10.0506 4.18069 10.0516 3.82231 9.84662 3.57441ZM5.00002 1.84553C6.18853 1.84553 7.15535 2.81235 7.15535 4.00086C7.15535 5.18936 6.18853 6.15618 5.00002 6.15618C3.81151 6.15618 2.84469 5.18936 2.84469 4.00086C2.84469 2.81235 3.81151 1.84553 5.00002 1.84553Z" fill="white"/>
									</svg>
								</span>
								<span class="ap_viewT">${item.count_views} Views</span>
							</div-->
						</div>
						<div class="ap_videoDet">
							<p class="dis_SV_vttl mp_0">${item.title}</p>
							<!--p class="dis_SV_vdes mp_0">${item.created_at}</p-->
							<!--p class="dis_SV_vdes mp_0">${item.user_name}, ${item.created_at}</p-->
						</div>
					</div>
				</li>`;
		});
	}
	return html;

}

function scrollPlaylist(currentIndex) {
	if (currentIndex) {
		$('#appendPlaylist').stop().animate({
			scrollTop: $('#appendPlaylist').find("[data-index='" + currentIndex + "']")?.position()?.top - $('#appendPlaylist li:first')?.position()?.top // animate the right to the offest().top for the right > p
		}, 1000);
	}
}


/* Start a Player autoplay status code */
var getPlaylist = true;
$(document).ready(function () {
	document.querySelector('#togglePIP').addEventListener("click", async () => {
		try {
			if (document.pictureInPictureElement) {
				await document.exitPictureInPicture();
			} else {
				await document.querySelector('#my_video_html5_api').requestPictureInPicture();
			}
		} catch (err) {
			// Video failed to enter/leave Picture-in-Picture mode.
		}
	});

	window.changeVideoState = function (index) {
		$('.gam-ad-earn-burn').hide(); //gamification
		$('.gam-ad-poll').hide(); //gamification
		adsManager?.stop();
		setTimeout(function () {
			if (mainPlaylist.length - 1 == index && playlist?.list_id?.length == 0) {
				getPlayerPlaylist(index,isSetSrc=true);
			}else{
				setPlaylistSrc();
			}
		}, 1000);
	}

	window.setVolume = function (mute, v) {
		player.muted(mute);
		player.volume(v);
		$('#playVolume').val(v);

		if (v == 0 || mute) {
			$('#playMute').parent().addClass('mute');
			player.muted(true);
		} else
			if (v > 0 && !mute) {
				// $('#playVolume').val(v);
				$('#playMute').parent().removeClass('mute');
				player.muted(false);
			}
	}

	$("#appendPlaylist").scroll(function () {
		if (getPlaylist && ($(this).scrollTop() == $(this)[0].scrollHeight - $(this).height())) {
			getPlayerPlaylist(currentVideoIndex);
		}
	});

	$(document).on('click', '.loadPlaylistAsVideo', function () {
		let _this = $(this);
		getPlayerPlaylist(currentVideoIndex);
		if (!getPlaylist) {
			if (_this.hasClass('dis_btn'))
				_this.html('Loading  <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled', true);
		}
	})


	$(document).on('click', '#player_next', function () {
		let val = ($(this).prop("checked") == true) ? true : false;
		store('player_next', val);
	})

	window.setAuto = function () {
		let ply = get('player_next');

		if (ply == 'true' || ply == null) {
			$('#player_next').attr('checked', 'checked');
			store('player_next', true);
		}

		if (typeof player == 'object') {
			let m = eval(get('player_mute'));
			let v = eval(get('player_volu'));

			window.setVolume(m === null ? true : m, v === null ? 0.5 : v)
		}
	}


	// **********************************Start add view count***************************************

	window.countInterval = function () {
		if (midRollCount > 480) {
			player.trigger('adsready');
			window.pribidInit('midroll');
			midRollCount = 0;
		} else {
			midRollCount++;
		}


		if (vidDuration < 31 && plyDuration == 6) {
			addViewCount();
		} else if (vidDuration > 31 && plyDuration == 30) {
			addViewCount();
		} else {
			plyDuration++;
		}
	}

	function addViewCount() {
		if (xhrViewCount == 1) {
			const { VideoUserId, VideoPostId } = getCustomParam();
			if (VideoPostId.length) {
				$.post(base_url + 'player/AddViewcount', { 'post_id': btoa(VideoPostId), 'user_id': btoa(VideoUserId) }, function (res) {
					res = JSON.parse(res);
					if (res.status == 1) {
					}
				})
				xhrViewCount = 0;
			}
		}
	}
	// **********************************End end view count***************************************




	// **********************************Start watch history***************************************
	window.onbeforeunload = closingCode;

	function closingCode() {
		my_watch_history();
	}

	window.my_watch_history = function () {
		const { VideoPostId } = getCustomParam();
		if (Object.keys(player).length !== 0) {
			let duration = player.currentTime();
			let videoObject = [];
			let list = { vid: VideoPostId, time: duration, plist_id: playlist.list_id }; //insert playlist id in watch history

			if (WatchlistName in localStorage) {
				let oldList = JSON.parse(get(WatchlistName));
				let key = lookup(oldList, VideoPostId);
				if (key >= 0) {
					oldList[key].time = duration;
				} else {
					if (duration !== player.duration()) {
						oldList.push(list);
					}
				}
				store(WatchlistName, JSON.stringify(oldList));
				store('save_watch_history', 0);
			} else {
				if (duration !== player.duration()) {
					videoObject.push(list);
					store(WatchlistName, JSON.stringify(videoObject));
					store('save_watch_history', 0);
				}
			}
		}
	}

	function getMyWatchHistory() {
		if (get(WatchlistName) === null) {
			manageMyAjaxPostRequestData({}, base_url + "home/getWatchHistory").done(
				function (resp) {
					let r = JSON.parse(resp);
					if (r.status == 1) {
						store(WatchlistName, r.data);
					}
				});
		}
	}
	getMyWatchHistory();
	// **********************************End watch history***************************************


	// **********************************Start spinner***************************************

	var max = 5; // How many seconds should this timer go for
	var percentage = max;
	var dialColor = "#dfe8ed";
	var percColor = "#eb581f";
	var dptimechart = document.getElementsByClassName("dis_player_timerchart");
	var dppercent = document.getElementsByClassName("dis_player_timerpercent");

	window.setPercentage = function (percentage) {
		let element = document.createElement('style');
		element.classList.add("mySpinnerCss");
		element.innerHTML = '.dis_player_timerpercent:before {';

		if (percentage > 0.5) {
			if (dptimechart.length)
				dptimechart[0].style.backgroundColor = percColor;

			element.innerHTML += 'background-color: ' + dialColor + ';';
			element.innerHTML += 'transform:rotate(' + (180 - (percentage - 0.5) * 360) + 'deg);';

			if (dppercent.length)
				dppercent[0].style.transform = "rotate(" + (-180 + (percentage - 0.5) * 360) + "deg)";

		} else {
			if (dptimechart.length)
				dptimechart[0].style.backgroundColor = dialColor;

			element.innerHTML += 'background-color: ' + percColor + ';';
			element.innerHTML += 'transform:rotate(' + (percentage * 360) + 'deg);';

			if (dppercent.length)
				dppercent[0].style.transform = "rotate(0deg)";
		}

		element.innerHTML += '}';

		if (dppercent.length)
			dppercent[0].appendChild(element);
	}

	window.updatePercentage = function () {
		percentage -= 0.1;

		if (percentage <= 0) {
			clearInterval(Spinnertimer);
		}

		setPercentage(percentage / max > 0 ? percentage / max : 0);

		let donwtime = Math.ceil(percentage);

		if (document.getElementsByClassName("dis_player_timerfiller").length)
			document.getElementsByClassName("dis_player_timerfiller")[0].innerHTML = donwtime + "s";

		if (donwtime == 0) {
			playNext();
			$('#timerSpinner').hide();
			contentEnded = 0;
		}
	}

	window.initSpinner = function () {
		let ply = get('player_next');

		if (ply == 'true') {
			$('.mySpinnerCss').remove();

			$('#timerSpinner').show();
			percentage = max;
			Spinnertimer = setInterval(updatePercentage, 100);
		}
	}

	// **********************************End spinner***************************************


	// **********************************Start Live Scheduler***************************************
	window.schedule_timer = function (item) {   //To show timer to start live video

		if (typeof (item.is_scheduled) !== 'undefined' && item.is_scheduled == 1) {

			var now = new Date(item.schedule_time);
			var utc_timestamp = Date.UTC(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), now.getMinutes(), now.getSeconds(), now.getMilliseconds());
			let CD = new Date(utc_timestamp).getTime();

			//	let CD = new Date(item.schedule_time + ' UTC').getTime();

			let x = setInterval(function () {
				let now = new Date().getTime();// Get today's date and time
				let distance = CD - now; // Find the distance between now and the count down date

				let days = Math.floor(distance / (1000 * 60 * 60 * 24)); // Time cal for days, hours, minutes and seconds

				let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				let seconds = Math.floor((distance % (1000 * 60)) / 1000);// Output the result in an element with id="demo"

				$('#showSchedulerInfo').html(`
					<h1 class="dis_SSched_ttl">live stream will start in</h1>
					<ul class="dis_SSched_List">
						<li>
							<span class="dis_SSched_count">${days}</span>
							<span class="dis_SSched_text">${days > 1 ? 'Days' : 'Day'} </span>
						</li>
						<li>
							<span class="dis_SSched_count">${hours}</span>
							<span class="dis_SSched_text">Hours</span>
						</li>
						<li>
							<span class="dis_SSched_count">${minutes}</span>
							<span class="dis_SSched_text">Minutes</span>
						</li>
						<li>
							<span class="dis_SSched_count">${seconds}</span>
							<span class="dis_SSched_text">Second</span>
						</li>
					</ul>`
				).removeClass('hide');

				if (distance < 0) { // If the count down is over, write some text
					clearInterval(x);
					$('#showSchedulerInfo').html(`<h1 class="dis_SSched_ttl">Please Wait For The Live Stream To Begin</h1>`).removeClass('hide');
				}
			}, 1000); // Update the count down every 1 second
			SchedulerInterval = x;
		}
	}


	// **********************************End Live Scheduler***************************************


	window.showPrevNext = function () {
		let ci = currentVideoIndex;
		function html(item) {
			return (item) ?
				`<div class="dis_pnp_left">
					<span class="dis_pnp_thumb">
						<img src="${item.poster}" alt="${item.single_video.title}" title="${item.single_video.title}" onerror="this.onerror=null;this.src='${item.errimg}'">
					</span>
				</div>
				<div class="dis_pnp_right">
					<h2 class="dis_pnp_ttl">${item.single_video.title}</h2>
				</div>`: '';

		}

		let item = mainPlaylist[mainPlaylist.length == currentVideoIndex + 1 ? 0 : currentVideoIndex + 1];

		$('#PlayNext').find('.dis_pnp_inner').html(html(item)).removeClass('hide');

		let preIndex = currentVideoIndex - 1 < 0 ? mainPlaylist.length - 1 : currentVideoIndex - 1;

		if (preIndex) {
			$('#playPrevious').find('.dis_p_nextprev_wrap').show();
			$('#playPrevious').find('.dis_pnp_inner').html(html(mainPlaylist[preIndex])).removeClass('hide');
		} else {
			$('#playPrevious').find('.dis_p_nextprev_wrap').hide();
		}


		$('#timerSpinner').html(`<div class="dis_player_timer">
			<div class="dis_pt_thumbWrap">
				<div class="dis_pt_thumb">
					<img src="${item.poster}" title="${item.single_video.title}" alt="${item.single_video.title}" onerror="this.onerror=null;this.src='${item.errimg}'">
				</div>
			</div>
			<div class="dis_pt_right">
				<div class="dis_player_timerchart">
					<div class="dis_player_timerpercent"></div>
					<div class="dis_player_timerfiller"></div>
				</div>
				<p class="dis_player_timer_text hide">Playing Next Video In</p>
				<p class="dis_pt_ttl">${item.single_video.title.slice(0, 60)}...</p>
				<a href="javascript:;" class="dis_pt_btn dis_SV_btn primary_btn" onclick="cancelSpinner(${ci})">Cancel</a>
			</div>
		</div>`).hide();

		window.cancelSpinner = function (index) {
			// $('#timerSpinner').hide();
			// clearInterval(Spinnertimer);
			// player.playlist.next();
			// player.play();
			window.location.reload('true');

		}
	}

	// **********************************Start To get live info ***************************************
	var streamInterval, SchedulerInterval, loadSocket = true;
	window.get_stream = function (item) {
		$('#showStreamInfo').html('').hide();
		$('#showSchedulerInfo').html('').addClass('hide');
		clearInterval(streamInterval);
		clearInterval(SchedulerInterval);

		if (item.is_stream_live == 1) {
			if (loadSocket) {
				loadScript(base_url + '/repo/js/socket.js');
				loadSocket = false
			};

			function fetch_stream_info() {
				$.post(base_url + 'player/getStream/', { 'uid': item.user_id }, function (res) {
					res = JSON.parse(res);
					if (res.status == 1) {
						let data = res.data;
						let state = data.state == 'LIVE' ? 'label-success' : 'label-danger';
						let viewcount = $('#according_chat').attr('data-view_count');

						let h = `<ul class="dis_sv_sslist">
									<li>
										<p class="dis_sv_ss_ttl">Status</p>
										<span class="dis_sv_ss_stl ${state} " id="StrStatus">${data.state}</span>
									</li>

									<li>
										<p class="dis_sv_ss_ttl">Health</p>
										<span class="dis_sv_ss_stl label-info" id="StrHealth">${data.health}</span>
									</li>

									<li>
										<p class="dis_sv_ss_ttl">Duration</p>
										<span class="dis_sv_ss_stl label-warning" id="StrDuration">${data.duration}</span>
									</li>

									<li>
										<p class="dis_sv_ss_ttl">Viewers</p>
										<span class="dis_sv_ss_stl label-primary" id="StrViewers">${viewcount ? viewcount : 0}</span>
									</li>
								</ul>`;
						$('#showStreamInfo').html(h).show();
						$('#showSchedulerInfo').addClass('hide');
					}
				})
			}
			streamInterval = setInterval(() => {
				fetch_stream_info();
			}, 6000);
		}

		window.schedule_timer(item);

	}
	// **********************************End To get live info ***************************************
})
/* End a Player autoplay status code */




window.midrollInit = function (videoBidResult = {}, bids = {}) {
	// vastUrl = typeof videoBidResult.adTagUrl !== 'undefined' ? videoBidResult.adTagUrl : vastUrl;
	console.log('midrollInit',videoBidResult)
	player.ima.requestAds(videoBidResult);
}

window.pribidInit = function (type) {
	// Policy = (blocklist.includes(window.location.pathname)) ? 'blocked' : 'allowed';
	try{
		blogherads.adq.push(async function() {
			if(type == 'preroll'){
				mainPlaylist = await loadvideo();
			}
				
			let param = typeof window.getCustomParam === "function" ? getCustomParam() : {};
			
			if(param?.is_stream_live == 0){
				let vwidth = 640;
				let vheight = 480;
				
					blogherads.getVastTag(function(tag) {
						if(type == 'preroll'){
							playerInit(tag)
						}else{
							midrollInit(tag)
						}
					}, { 
						instanceOnPage : param?.VideoPostId,
						pageNumber : param?.VideoPostId,
						descriptionUrl : window.location.href,
						iabPlcmt : 1,
						duration:param?.duration,
						extraVastQS: '',
						playMode:'auto',
						playMute:0,
						preroll_max_duration: 60,
						preroll_min_duration: 15,
						size:  [vwidth, vheight],
						subAdUnitPath: '',
						targeting : {
							inview: '',
							jw: '',
							player_height: vheight,
							player_width: vwidth,
							videokw: '',
						},
						type: type
					});
			
			}else{
				window.playerInit();
			}
		});
	}catch (e) {
		window.playerInit();
		console.error("outer", e.message);
	}
}

async function loadvideo(){
	return myPromise = new Promise(function(myResolve, myReject) {
		manageMyAjaxPostRequestData({pid:$('#post_id').val()}, base_url + "share/getVideo").done(
		function (resp) {
			let r = JSON.parse(resp);
			if (r.status == 1) {
				myResolve(r.data); // when successful
			}
		});
	});
}

$(function () {
	window.pribidInit('preroll')
})

$(window).bind("popstate", function (e) {
	if (typeof e.originalEvent.state.index !== 'undefined') {
		window.changeVideoState(e.originalEvent.state.index);
	}
});