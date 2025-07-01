var player 				= '';
var Policy 				= 'allowed';
var click 				= true;

var isVideoEnded = true;
var adsDuration = 0;

function urlParam(name) {
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results == null) {
		return null;
	}
	else {
		return results[1] || 0;
	}
}

var loop = eval(urlParam('loop'));
loop = loop == null || loop == false ? false : true;

var autoplay = eval(urlParam('autoplay'));
autoplay = autoplay == null || autoplay == true ? true : false;

var muted = eval(urlParam('muted'));
muted = muted == null || muted == true ? true : false;

var isInViewPort = eval(urlParam('isInViewPort'));
isInViewPort = isInViewPort == null || isInViewPort == true ? true : false;

var controls = eval(urlParam('controls'));
controls = controls == null || controls == true ? true : false;

window.playerInit = function () {
	var single, duration = 1, VidDuration = 1, interval = "",Cont=0;
	var videoid = document.querySelector('.box video').getAttribute('id');

	const { VideoUserId, VideoPostId, VideoMode, UidIdWhoIsWatching, domain } = getCustomParam();
	
	var CurrentVDetail = {
		'uid': VideoUserId,
		'pid': VideoPostId,
		'tag': document.getElementById('VideoTag').value,
		'mod': document.getElementById('mode_').value,
		'cat': document.getElementById('category_').value,
		'gen': document.getElementById('genre_').value,
		'list': document.getElementById('list_').value,
		'start': Cont,
		'limit': 10
	};

	var is_live = document.getElementById('is_stream_live').value;

	if (videoid != 'undefined' && videoid != undefined && videoid != '') {
		var overrideNative = true;
		
		player = videojs(videoid, {
			playbackRates: [1, 1.5, 2, 2.5],
			controls: controls,
			loop: loop,
			preload: "auto",
			muted: muted,
			autoplay: autoplay,
			aspectRatio: '16:9',
			html5: {
				vhs: {
					overrideNative: overrideNative,
					enableLowInitialPlaylist: true,
				},
				nativeVideoTracks: !overrideNative,
				nativeAudioTracks: !overrideNative,
				nativeTextTracks: !overrideNative
			}
		}, function () {
			var srcs = document.querySelector('.box').getAttribute('src');
			var mime = document.querySelector('.box').getAttribute('mime');

			async function mediaPakageUrl() {
				vastTags = { 1: '24008711', 2: '24008715', 3: '24008712', 7: '24008713', 'social': '24008714' };

				placementId = typeof vastTags[CurrentVDetail.mod] == 'undefined' ? vastTags['1'] : vastTags[CurrentVDetail.mod];

				let adsParam = `?ads.placementId=${placementId}&ads.viewerid=${UidIdWhoIsWatching}&ads.video_id=${VideoPostId}&ads.userid=${VideoUserId}&ads.genreid=${CurrentVDetail.gen}&ads.categoryid=${CurrentVDetail.cat}&ads.devicetype=web`;

				srcs = srcs + adsParam + '&aws.logMode=DEBUG';

				loadSrc();
				common();
			}

			window.loadSrc = function () {
				player.src({ type: mime, src: srcs });
			}

			if (is_live == 1) {
				mediaPakageUrl();
			} else {
				loadSrc();
				common(function () {
					
				});
			}

			function common(callback) {
				single = player.tagAttributes.single;

				var brandContainer = document.querySelector('.vjs-brand-container');
				if (brandContainer) {
					brandContainer.remove();
				}

				var fullscreenControl = document.querySelector('.vjs-fullscreen-control');
				if (fullscreenControl) {
					var newElement = document.createElement('div');
					newElement.className = 'vjs-brand-container';
					newElement.innerHTML = '<a class="vjs-brand-container-link" href="' + single + '" title="discovered.tv" target="_top"><img src="' + base_url + '/repo/images/favicon.png"></a>';

					fullscreenControl.parentNode.insertBefore(newElement, fullscreenControl);
				}

				player.on("play", function (e) {
					VidDuration = (player.duration() != 'Infinity') ? player.duration() : 30;
				});

				player.on("pause", function (e) {
					clearInterval(interval);
				});

				player.on("playing", function (e) {
					VidDuration = (player.duration()) ? player.duration() : 30;
					interval = setInterval(countInterval, 1000);
				});

				player.on("waiting", function (e) {
					clearInterval(interval);
				});

				player.on("adserror", function (e) {
					console.log('Ad error:', e.data.AdError.data.errorMessage);
				});

				player.on('contentended', function () {
					console.log('Content ended');
				})
				
				player.on("ended", function (e) { 
					takeMeToNextVideo();   
				});   
				
 
				player.on('error', function () {
					let error = player.error(); 
					if (error.code == 4 || error.code == 2) {  
						setTimeout(() => {
						 	player.src({ type: mime, src: srcs });
							player.load();  
						}, 500)
					}
					if (is_live == 1) {
						setTimeout(() => {
							document.querySelector('.vjs-modal-dialog-content').textContent = 'Please Wait For The Live Stream To Begin.';
						}, 1000)
					}
				});

				if (autoplay) {
					var promise = player.play();
					if (promise !== undefined) {
						promise.then(function () {
							// console.log(' Autoplay started!');
						}).catch(function (error) {
							// console.log(' Autoplay not started!');
						});
					}
				}

				player.controls(controls);
				player.muted(muted);
				player.volume(0.5);

				callback();
			}  

			function takeMeToNextVideo() {
				if (isVideoEnded) {
					if (localStorage.getItem('player_next') == 'true') {
						if(click) document.querySelector('.video_nexttoggle').click();
						
						setTimeout(function () {
							TriggerNextVideo()
						}, 2000)
					}
				}
			}
			
			player.on('ads-manager', function (response) {
				var adsManager = response.adsManager;
				adsManager.addEventListener(google.ima.AdEvent.Type.STARTED, function () {
					const ad = adsManager.getCurrentAd();
					adsDuration = ad.getDuration();
					console.log(adsDuration,'adsDuration');
					
				});
				
				adsManager.addEventListener(google.ima.AdEvent.Type.CONTENT_PAUSE_REQUESTED, function () {
					player.muted(true);
					triggerPauseVideo()
				});

				adsManager.addEventListener(google.ima.AdEvent.Type.CONTENT_RESUME_REQUESTED, function () {
					player.muted(false);
					triggerPlayVideo();
				});
			})

			function triggerPlayVideo() {
				setTimeout(function () {
					var isPlaying = !player.paused();
					if (!isPlaying) {
						player.play();
					}
				}, 10);
			}

			setTimeout(function () {
				if (player.length) {
					var contentPlayer = document.getElementById(videoid + '_html5_api');
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

					var wrapperDiv = document.getElementById(videoid);
					wrapperDiv.addEventListener(startEvent, initAdDisplayContainer);
				}
			}, 100)
		});

		function TriggerNextVideo() {
			let Target 	= document.querySelector('.video_nextbox');
			let HrefTarget 	= Target?.querySelector('a');
			let activeTarget= document.querySelector('.video_nextbox.active');
			  
			if (activeTarget?.nextElementSibling) {
				HrefTarget = activeTarget.nextElementSibling.querySelector('a');
			}		 	
			
			let href_post_key = HrefTarget.children.item(0).getAttribute('href_post_key');
			let href_post_id = HrefTarget.children.item(0).getAttribute('href_post_id');
			
			loadNewVideo(href_post_key, href_post_id);
		} 


		function triggerPauseVideo() {
			setTimeout(function () {
				var isPlaying = !player.paused();
				if (isPlaying) {
					player.pause();
				}
			}, 1);
		}

		var XhrEnable = 1;
		function addViewCount() {
			if (XhrEnable == 1) {
				if (VideoPostId) {
					fetch(base_url + '/player/AddViewcount', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						},
						body: new URLSearchParams({
							'post_id': btoa(VideoPostId),
							'user_id': btoa(VideoUserId)
						}).toString()
					})
						.then(response => response.json())
						.then(res => {
							if (res.status == 1) {
								// Handle success case
							}
						})
						.catch(error => {
							console.error('Error:', error);
						});
				}
				XhrEnable = 0;
			}
		}


		var currDur = 0;
		function countInterval() {
			currDur = duration++;
			if (VidDuration < 31 && currDur == 6) {
				addViewCount();
			} else if (VidDuration > 31 && currDur == 5) {
				addViewCount();
			}
		}

		document.querySelector('.box').addEventListener('contextmenu', function (event) {
			event.preventDefault(); // DISABLE RIGHT MENU IN VIDEO PLAYER
		});

	} //END OF VIDEO ID CONDITION
	
	document.querySelector('.video_nexttoggle').addEventListener('click', function() {
		var _this = this;
		if (click) {
			LoadPlayerNextVideo(CurrentVDetail).then(
				function (value) {
					_this.parentNode.classList.toggle('open');
					click = false;
				},
				function (error) {
					// console.log(error);
				}
			);
		}else{
			_this.parentNode.classList.toggle('open');
		}
	});


	

	// Make sure LoadPlayerNextVideo is properly defined and returns a Promise
	window.LoadPlayerNextVideo = function (CurrentVDetail) {
		return new Promise(function (resolve, reject) {
			fetch(base_url + '/player/LoadPlayerNextVideo', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: new URLSearchParams(CurrentVDetail).toString()
			})
				.then(response => response.text())
				.then(res => {
					// Your existing logic to handle the response
					// Make sure this section is properly parsing and handling data
					if (res && res.length > 3) {
						let resp = JSON.parse(res);
						if (resp.status == 1) {
							let resData = resp.data,
							AMAZON_URL = resp.AMAZON_URL,
							divs = '',
							errimg = base_url + '/repo/images/thumbnail.jpg';

							resData.forEach(function (val) {
								let img = AMAZON_URL + `aud_` + val.user_id + `/images/` + val.image_name + '.webp';
								let thumbImg = img.replace(".jpg", "_thumb.jpg");
								let href_post_key = base_url + `watch/` + val.post_key;
								let href_post_id = base_url + `withOutSheMedia/` + val.post_id;
								
								active_video = (VideoPostId == +val.post_id) ? 'active' : '';
								
								if ((CurrentVDetail.list).length) {
									href_post_key += '/' + CurrentVDetail.list;
									href_post_id += '/' + document.getElementById('pListId').value;
								}

								divs += `<div class="video_nextbox ` + active_video + `">
									<div class="video_nexttumb">
										<a class="video_href">
											<img class="RoutePlayer" href_post_key="`+ href_post_key + `" href_post_id="` + href_post_id + `" src="` + thumbImg + `" class="img-responsive" onError="ImageOnLoadError(this,'` + img + `','` + errimg + `')">
										</a>
									</div>
									<div class="video_nextdata" title="`+ val.title + `">
										<p>`+ (val.title).slice(0, 45) + `.</p>
									</div>
								</div>`;
							});

							setTimeout(function () {
								document.getElementById('UpNext').innerHTML = divs;
							}, 200);
						} else {
							div = `<div class="video_nextbox">
								<div class="video_nextdata">
									<p>No related video available.</p>
								</div>
							</div>`;
							document.getElementById('UpNext').innerHTML = div;
						}

						if (CurrentVDetail.list !== '') {
							CurrentVDetail.list = '';
							resolve();
						} else {
							resolve();
						}
					} else {
						div = `<div class="video_nextbox">
							<div class="video_nextdata">
								<p>No related video available.</p>
							</div>
						</div>`;
						document.getElementById('UpNext').innerHTML = div;
					}
				})
				.catch(error => {
					reject(error);
				});
		});
	}

	document.addEventListener('click', function (event) {
		if (event.target.matches('#player_next')) {
			let val = (event.target.checked === true) ? true : false;
			localStorage.setItem('player_next', val);
		}
	});

	var clickNext = true;
	document.addEventListener('click', function (event) {
		if (event.target.matches('.RoutePlayer')) {
			event.preventDefault();

			if (clickNext) {
				clickNext = false;
				
				let href_post_key = event.target.getAttribute('href_post_key');
				let href_post_id = event.target.getAttribute('href_post_id');
				loadNewVideo(href_post_key, href_post_id);
			}
		}
	});


	window.loadNewVideo = function (href_post_key, href_post_id) {
		window.location = href_post_id+window.location.search;
	}



	let schedule_time = document.querySelector('#schedule_time');

	function schedule_timeer() {
		let ST = document.querySelector('#schedule_time').value;
		let CD = new Date(ST + ' UTC').getTime();
		let x = setInterval(function () {
			let now = new Date().getTime(); // Get today's date and time
			let distance = CD - now; // Find the distance between now and the count down date
			let days = Math.floor(distance / (1000 * 60 * 60 * 24)); // Time cal for days, hours, minutes and seconds
			let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			let seconds = Math.floor((distance % (1000 * 60)) / 1000); // Output the result in an element with class "vjs-modal-dialog-content"

			document.querySelector('.vjs-modal-dialog-content').textContent = `${days} day | ${hours}:${minutes}:${seconds}s - Remaining For The Live Stream To Begin`;

			if (distance < 0) { // If the count down is over, write some text
				clearInterval(x);
				document.querySelector('.vjs-modal-dialog-content').textContent = 'Please Wait For The Live Stream To Begin';
			}
		}, 1000); // Update the count down every 1 second
	}


	if (schedule_time && schedule_time.length && schedule_time.data('is_sechdule') == 1) {
		setTimeout(() => { schedule_timeer() }, 2000);
	}




	function setAutoPlay() {
		let ply = localStorage.getItem('player_next');
		if (ply === 'true' || ply === null) {
			document.getElementById('player_next').setAttribute('checked', 'checked');
			localStorage.setItem('player_next', true);
		}
	}
	setAutoPlay();




}

function ImageOnLoadError(_this, src1, src2) {
	_this.src = src1;
	_this.onload = function () {
		_this.onerror = null;
	};
	_this.onerror = function () {
		_this.src = src2;
		_this.onerror = null;
	};
}


document.addEventListener('DOMContentLoaded', async function () {
	window.playerInit();
	window.addEventListener('popstate', function (e) {
		window.location.reload(false);
	});
});


