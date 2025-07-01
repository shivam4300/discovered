/****************************** start for single page and upload monetize video page **********************************************************************/
function getPlaylist(post_id, appendId) {

	let formData = new FormData();
	formData.append('post_id', post_id);
	$('#playlist_post_id').val(post_id);
	manageMyAjaxPostRequestData(formData, base_url + 'backend/playlist/getMyPlaylist').done(function (data) {
		if (data.status == 1) {
			if (data.playlist.length) {
				appendPlayList(data.playlist, appendId);
			} else {
				$('.newpl_click[data-id="NewPlaylist"]').trigger('click');
				$('.newpl_click[data-id="NewPlaylistComm"]').trigger('click');
			}
		}
	})
}

var plStart = 0;
var plLimit = 8;
function getMyChannelPlaylist() {
	if ($('#data-user_id').attr('data-user_id') !== undefined) {
		let formData = new FormData();
		formData.append("start", plStart);
		formData.append("limit", plLimit);
		formData.append("uid", $('#data-user_id').attr('data-user_id'));
		manageMyAjaxPostRequestData(formData, base_url + 'channel/getMyChannelPlaylist').done(function (resp) {
			if (resp.status == 1 && resp.data != '') {

				let result = resp.data;
				if (result.videoData.length > 0) {
					let playlistHtml = appendMyChannelPlaylist(result.videoData);
					$('#myChannelPlaylist').append(playlistHtml);
				} else {
					let playlistHtml = appendMyChannelPlaylist(result.videoData = []);
					$('#playlist_not_found_image').append(playlistHtml);
				}
				if (plStart == 0) {
					$('#my_playList_append').append(getPlaylistSliderHtml(result));
					let thhs = $('div.au_artist_slider:first');

					AdAdsOnChannel(thhs, function () {
						setTimeout(() => {
							swiperslider(thhs);
						}, 200)
					})
				}
				ControlRequestPlaylist = true;
				plStart += plLimit;
			} else {
				if (plStart == 0) {
					let result = [];
					let playlistHtml = appendMyChannelPlaylist(result);
					$('#playlist_not_found_image').append(playlistHtml);
				}
			}

		})
	}
}


function appendPlayList(playlist, appendId) {
	let list = '';
	$.each(playlist, function (index, list) {
		list = `<li>
						<div class="checkbox dis_checkbox">
							<label>
								<input type="checkbox" value="`+ list.playlist_id + `" class="check check_vid_count" name="playlist_ids[]" ` + list.checked + ` data-video_count="` + list.video_ids_count + `">
								<i class="input-helper"></i>
								<p>`+ list.title + ` (` + list.video_ids_count + `)</p>
							</label>
							<span class="form-error help-block"></span>
						</div>
					</li>`;
		if (appendId == 'PlayListAreaComm') {
			$('#PlayListAreaComm').append(list);
		} else {
			$('#PlayListArea').append(list);
		}
	});
}

if ($('#myChannelPlaylist').length) {
	getMyChannelPlaylist();
}

function submitAddToPlaylist() {
	let formData = new FormData();
	formData.append('post_id', $('#playlist_post_id').val());
	let playlist_ids = $("input[name='playlist_ids[]']:checked:enabled").map(function () { return $(this).val(); }).get();
	formData.append('playlist_ids', playlist_ids);

	manageMyAjaxPostRequestData(formData, base_url + 'dashboard/videoAddToPlaylist').done(function (resp) {
		if (resp.status == 1) {
			//$("#addToPlaylistModal").modal('hide');
			$("#myCommonModal").modal('hide');

			Custom_notify('success', resp.message);
		}
	})
}
var ControlRequestPlaylist = false;
$(window).scroll(function () {
	// console.log('scroll')
	if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
		if (ControlRequestPlaylist) {
			ControlRequestPlaylist = false;
			getMyChannelPlaylist();

		}
	}
});

$(document).on('click', '.check_vid_count', function () {
	if ($(this).attr('data-video_count') > 49) {
		return false;
	}
});

$(document).on('click', '.AddToPlaylist', function () {   // not used now
	alert()
	if (user_login_id != '') {
		let post_id = $(this).attr('data-post_id');
		$('.createNewPlaylistComm').attr('data-page', 'singlepage');
		$('.newpl_click[data-id="CancelPlaylistComm"]').trigger('click').show();
		$('.newpl_click[data-id="NewPlaylistComm"]').show();
		$('#PlayListAreaComm').removeClass('hideme');
		$("#video_popup").modal('hide');
		if (post_id !== '') {
			$('#PlayListAreaComm').html('');
			getPlaylist(post_id, 'PlayListAreaComm');
			$('#addToPlaylistModal').modal();
		}
	} else {
		$('#video_popup').modal('hide');
		$('#myModal').modal('show');
	}
});

$(document).on('click', '#submitAddToPlaylist', function () {
	submitAddToPlaylist();
});

$(document).on('click', '#createPlayListBtn', function () {
	if (user_login_id != '') {
		$("#addToPlaylistModal").modal();
		$('.createNewPlaylistComm').attr('data-page', 'playlist');
		$('.newpl_click[data-id="NewPlaylistComm"]').trigger('click').hide();
		$('.newpl_click[data-id="CancelPlaylistComm"]').hide();
		$('#PlayListAreaComm').addClass('hideme');
		$('#playlist_post_id').val('');
	} else {
		$('#myModal').modal('show');
	}

});

var ajaxR = true;
$(document).on('click', '.createNewPlaylist', function () {
	let _this = $(this);
	let f = new FormData();
	f.set('playlistTitle', $('#playlistTitle').val());
	f.set('PlayListStatus', $('#PlayListStatus').val());
	if (ajaxR) {
		ajaxR = false;
		manageMyAjaxPostRequestData(f, base_url + 'backend/playlist/createNewPlaylist').done(function (data) {
			ajaxR = true;
			if (data.status == 1) {
				appendPlayList(data.playlist, '');
				$('#playlistTitle').val('')
				$('#ShowPlayListForm').addClass('hideme');
				$('.upl_dd_foot_inner.center .BtnDone').removeClass('hideme');
				$('.newpl_click[data-id="CancelPlaylist"]').trigger('click');

				if (_this.attr('data-page') == 'playlist') {
					$("#addToPlaylistModal").modal('hide');
					let l = data.playlist;
					window.location.href = base_url + 'playlist/' + l[0].playlist_id;
				}
			}

		}).fail(() => {
			ajaxR = true;
		})
	}
});

var ajaxR = true;
$(document).on('click', '.createNewPlaylistComm', function () {
	let _this = $(this);
	let f = new FormData();
	f.set('playlistTitle', $('#playlistTitleComm').val());
	f.set('PlayListStatus', $('#PlayListStatusComm').val());
	if (ajaxR) {
		ajaxR = false;
		manageMyAjaxPostRequestData(f, base_url + 'backend/playlist/createNewPlaylist').done(function (data) {
			ajaxR = true;
			if (data.status == 1) {
				appendPlayList(data.playlist, 'PlayListAreaComm');

				if (_this.attr('data-page') == 'playlist') {
					$("#addToPlaylistModal").modal('hide');
					let l = data.playlist;
					window.location.href = base_url + 'playlist/' + l[0].playlist_id;
				} else {
					$('#PlayListAreaComm').removeClass('hideme');
					$('#playlistTitleComm').val('')
					$('#ShowPlayListFormComm').addClass('hideme');
					$('.upl_dd_foot_inner.center .BtnDone').removeClass('hideme');
					$('.newpl_click[data-id="CancelPlaylistComm"]').trigger('click');
				}
			}

		}).fail(() => {
			ajaxR = true;
		})
	}
});



$(document).on('click', '.newpl_click', function () {
	let _this = $(this);
	let target_id = _this.data('id');
	$('.newpl_click').parent().removeClass('hideme');
	//_this.parent().addClass('hideme');

	if (target_id == 'NewPlaylistComm') {
		$('#ShowPlayListFormComm').removeClass('hideme');
		$('.upl_dd_foot_inner.center , .BtnDone').addClass('hideme');
	} else if (target_id == 'NewPlaylist') {
		$('#ShowPlayListForm').removeClass('hideme');
		$('.upl_dd_foot_inner.center , .BtnDone').addClass('hideme');
	} else {
		$('#ShowPlayListFormComm').addClass('hideme');
		$('#ShowPlayListForm').addClass('hideme');
		$('.upl_dd_foot_inner.center , .BtnDone').removeClass('hideme');
		$('.upl_dd_foot_inner.center').find('a[data-target="#playlistsec"]').trigger('click');
		_this.parent().addClass('hideme');
	}

});

/****************************** end for single page and upload monetize video page **********************************************************************/


/*Playlist code start */

var playlistStart = 0;
var playlistLimit = 50;
function getMyPlaylistVideo() {
	if ($('#playlist_id').length) {
		let list = $('#playlist_id').val();
		var formData = new FormData();
		formData.append("start", playlistStart);
		formData.append("limit", playlistLimit);
		formData.append("playlist_id", list);

		manageMyAjaxPostRequestData(formData, base_url + 'backend/playlist/getMyPlaylistVideo').done(function (resp) {
			if ((resp.trim()).length) {
				resp = JSON.parse(resp);
				let resData = resp.data;
				let playlist_thumb = resData['playlist_thumb'];
				let pllisthtml = $('#playListHtml');
				let dtvShareMe = $('.dtvShareMe');

				if (resp.status == 1) {

					pllisthtml.find('li').remove();
					$('.playListNoResult').addClass('hide');

					pllisthtml.append(getPlaylistVideoHtml(resData['playlist_video']));

					setTimeout(function () {
						if (playlist_thumb == '') {
							$('#remove_playlist_thumb').addClass('hide');
							var firstItemImg = pllisthtml.find('li:first div.dis_pl_sgl_vb_thumb img').attr('src');
							playlist_thumb = firstItemImg;
						} else {
							$('#remove_playlist_thumb').removeClass('hide');
						}


						let video_id = pllisthtml.find('li:first').attr('id');

						dtvShareMe.attr('data-share', '2|' + video_id + '|' + list).attr('data-share-embedlist', base_url + 'embedcv/' + video_id + '/' + list);

						$('#firstVideoThumb').attr('src', playlist_thumb);
						$('.play_cover_video').attr('href', pllisthtml.find('li:first').attr('data-playlist_url'));

						pllisthtml.find('li').length == 0 ? dtvShareMe.addClass('hide') : dtvShareMe.removeClass('hide');
					}, 1000);
					$('#videoCount').text(resData['playlist_video'].length);
					playlistStart += playlistLimit;
				} else {

					if (playlist_thumb == '') {
						$('#firstVideoThumb').attr('src', resp.defalutImg);
					}
					$('.play_cover_video').attr('href', '');
					$('#videoCount').text(0);
					pllisthtml.find('li').remove();
					$('.playListNoResult').removeClass('hide');
					pllisthtml.find('li').length == 0 ? dtvShareMe.addClass('hide') : dtvShareMe.removeClass('hide');
				}
			}
		})
	}
}

setTimeout(function () {
	getMyPlaylistVideo();
}, 1000);

function actionOnPlaylist(video_id, action, privacyStatus = 0, reorder_list = '') {
	if ($('#playlist_id').length) {
		var formData = new FormData();
		formData.append("video_id", video_id);
		formData.append("action_type", action);
		formData.append("playlist_id", $('#playlist_id').val());
		formData.append("privacy_status", privacyStatus);
		formData.append("reorder_list", reorder_list);

		manageMyAjaxPostRequestData(formData, base_url + 'backend/playlist/actionOnPlaylist').done(function (resp) {
			if (resp.status == 1) {
				if (action !== 'statusChange') {
					getMyPlaylistVideo();
				}
				Custom_notify('success', resp.message);
			}
		})
	}
}

function delete_playlist(playlist_id) {
	if (playlist_id !== '') {

		var formData = new FormData();
		formData.append("playlist_id", playlist_id);

		$("#conf_btn")
			.text("Deleting ")
			.append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
			.prop("disabled", true);

		manageMyAjaxPostRequestData(formData, base_url + 'backend/playlist/deletePlaylist').done(function (resp) {
			if (resp.status == 1) {
				//window.location.href=resp.redurl;
				$('.modal').modal('hide');

				$("#conf_btn").text("Delete").prop("disabled", false);
				if ($("div.swiper-container").length > 0) {
					$(document)
						.find("[data-post_playlist_id='" + playlist_id + "']")
						.remove();
					//plStart = 0;
					//getMyChannelPlaylist();
					// swiper.forEach()
					updateSwiper();
				}


				setTimeout(function () {
					success_popup_function("It\'s deleted successfully.");
				}, 500);

				if (window.location.href.indexOf("channel") == -1) {
					setTimeout(function () {
						//window.history.back();
						location.reload();
					}, 1000);
				}


			}
		})
	}
}
$(document).on('click', '.updateSwiper', () => {
	updateSwiper();
})
function updateSwiper() {

	$("div.swiper-container").each(function (index, item) {
		swiper[index].update();
	});
}

$(function () {
	if ($('#playlist_id').length) {
		$("#playListHtml").sortable({
			scroll: true,
			scrollSensitivity: 20,
			scrollSpeed: 40,
			handle: ".handle",
			items: "> li",
			axis: 'y',
			update: function (event, ui) {
				var data = JSON.stringify($(this).sortable('toArray'));
				//var drropedItemImg = $(ui.item).find('div.dis_pl_sgl_vb_thumb img').attr('src');
				//var data = $(this).sortable("toArray", {attribute: "data-id"});
				//var drropedItemImg =$(this).find('li:first div.dis_pl_sgl_vb_thumb img').attr('src');
				//$('#firstVideoThumb').attr('src' , drropedItemImg);
				actionOnPlaylist('', 're-ordered', '', data);
			}
		});
		$("#playListHtml").disableSelection();
	}
});

$(document).on('keyup', '.search_video_content', function (event) {
	let ths = $(this);
	let storeTarget;
	let search = $.trim(ths.val());
	let key = event.keyCode;
	if (search.toString().length > 0) {
		var formData = new FormData();
		formData.append('search', search);
		if ($('[name="mode_id"]').length) {
			formData.append('mode_id', $('[name="mode_id"]').val());
		}
		manageMyAjaxPostRequestData(formData, base_url + 'search/get_my_video_content/video').done(function (resp) {
			resp = JSON.parse(resp);
			if (resp.status == 1 && search.length > 0) {
				$('#searchVideoAppend li').remove();
				$('.searchVideoNoResult').hide();
				let list = getSearchVideoHtml(resp.data);
				$('#searchVideoAppend').append(list);
			} else {
				$('.searchVideoNoResult').show();
				$('.searchVideoNoResult p').text('No Results Found');
				$('#searchVideoAppend li').remove();
			}
		})
	} else {
		$('.searchVideoNoResult').show();
		$('.searchVideoNoResult p').text('Search Your Video');
		$('#searchVideoAppend li').remove();
	}
});

function getPlaylistVideoHtml(playlistVideo) {
	html = '';   //- Uploaded On ${value.created_at}
	if (playlistVideo.length > 0) {
		$.each(playlistVideo, function (i) {
			var value = playlistVideo[i];
			html += ` <li id="${value.post_id}" data-playlist_url="${value.PlaylistUrl}">
						<div class="dis_pl_sgl_vbox">
							<div class="dis_pl_sgl_vb_left">
								<div class="dis_pl_sgl_vb_drag handle">
									<span class="dis_pl_sgl_vb_drag_icon">
										<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="16px"><path fill-rule="evenodd" fill="rgb(150, 167, 175)" d="M7.743,9.093 C6.917,9.093 6.246,8.422 6.246,7.594 C6.246,6.765 6.917,6.094 7.743,6.094 C8.570,6.094 9.240,6.765 9.240,7.594 C9.240,8.422 8.570,9.093 7.743,9.093 ZM7.743,3.095 C6.917,3.095 6.246,2.424 6.246,1.595 C6.246,0.767 6.917,0.096 7.743,0.096 C8.570,0.096 9.240,0.767 9.240,1.595 C9.240,2.424 8.570,3.095 7.743,3.095 ZM1.757,15.091 C0.930,15.091 0.260,14.420 0.260,13.592 C0.260,12.764 0.930,12.092 1.757,12.092 C2.583,12.092 3.253,12.764 3.253,13.592 C3.253,14.420 2.583,15.091 1.757,15.091 ZM1.757,9.093 C0.930,9.093 0.260,8.422 0.260,7.594 C0.260,6.765 0.930,6.094 1.757,6.094 C2.583,6.094 3.253,6.765 3.253,7.594 C3.253,8.422 2.583,9.093 1.757,9.093 ZM1.757,3.095 C0.930,3.095 0.260,2.424 0.260,1.595 C0.260,0.767 0.930,0.096 1.757,0.096 C2.583,0.096 3.253,0.767 3.253,1.595 C3.253,2.424 2.583,3.095 1.757,3.095 ZM7.743,12.092 C8.570,12.092 9.240,12.764 9.240,13.592 C9.240,14.420 8.570,15.091 7.743,15.091 C6.917,15.091 6.246,14.420 6.246,13.592 C6.246,12.764 6.917,12.092 7.743,12.092 Z"/></svg>
									</span>
								</div>
								<div class="dis_pl_sgl_vb_thumb">
									<img src="${value.webp}" alt="Discovered" class="img-reposnive" onError="ImageOnLoadError(this,'${value.img}','${value.errimg}')">
								</div>
								<div class="dis_pl_sgl_vb_detls">
									<h2 class="dis_pl_sgl_tb_dttl">${value.title}</h2>
									<h3 class="dis_pl_sgl_tb_dsttl">${value.mode_name} Mode </h3>
								</div>
							</div>
							<div class="dis_pl_sgl_vb_right">
								<span class="dis_pl_sgl_vb_remove_icon" onclick="actionOnPlaylist(${value.post_id},'removeVideo')"></span>
							</div>
						</div>
					</li> `;
		});
	}
	return html;
}


function getSearchVideoHtml(searchVideo) {
	html = '';  //- Uploaded On ${value.created_at}
	if (searchVideo.length > 0) {
		$.each(searchVideo, function (i) {
			var value = searchVideo[i];
			html += `<li>
					<div class="dis_plsglsbsrlist_box">
						<div class="dis_plsglsbsrlist_left">
							<div class="dis_plsglsbsrll_thumb">
								<img src="${value.webp}" alt="Discovered" class="img-reposnive" onError="ImageOnLoadError(this,'${value.img}','${value.errimg}')">
							</div>
						</div>
						<div class="dis_plsglsbsrll_right">
							<h2 class="dis_pl_sgl_tb_dttl">${value.title}</h2>
							<h3 class="dis_pl_sgl_tb_dsttl">${value.web_mode} Mode </h3>
						</div>
						<div class="dis_plsglsbsrll_addplay">
							<a href="javascript:;" class="dis_addplaybtn" onclick="actionOnPlaylist(${value.post_id},'addToPlaylist')">
								<span class="dis_btn_icon">
								<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="13px"><path fill-rule="evenodd"  fill="rgb(255, 255, 255)"d="M14.000,10.000 L13.985,13.000 L12.015,13.000 L12.012,10.014 L9.000,10.019 L9.000,8.008 L12.000,8.000 L12.015,4.991 L13.985,4.991 L14.000,8.000 L17.000,8.008 L17.000,10.019 L14.000,10.000 ZM-0.000,3.989 L10.012,3.989 L10.012,5.984 L-0.000,5.984 L-0.000,3.989 ZM-0.000,-0.000 L10.012,-0.000 L10.012,1.994 L-0.000,1.994 L-0.000,-0.000 ZM6.987,10.010 L-0.000,10.010 L-0.000,8.015 L6.987,8.015 L6.987,10.010 Z"/></svg>
								</span>
								Add To Playlist
							</a>
						</div>
					</div>
				</li>`;
		});
	}
	return html;

}

function appendMyChannelPlaylist(playlist) {
	console.log('playlist : ', playlist);
	let other_user = $('#data-user_id').attr('data-user_id');
	html = '';
	if (playlist.length > 0) {
		$.each(playlist, function (i) {
			var value = playlist[i];
			let actionBtn = '';

			let href = value.video_ids_count == 0 ? value.edithref : value.href;

			html += `<div data-post_playlist_id="${value.playlist_id}">
						<div class="dis_viewall_playlist_box">
							<div class="dis_post_video_data">
								<div class="dis_postvideo_img">
									<img src="${value.webp}" class="img-responsive" alt="Discovered" onError="ImageOnLoadError(this,'${value.img}','${value.errimg}')">
									<div class="dis_overlay">
										<div class="dis_overlay_inner">
											<a href="${href}" class="dis_play_icon">
												<img src="${base_url}repo/images/playlist_icon.png" alt="" class="img-responsive">
											</a>
										</div>
									</div>
									<div class="pl_count_wrap">
										${value.video_ids_count}
									</div>`;

			actionBtn = `<li><a href="javascript:;" class="dtvShareMe" data-share="2|${value.first_video_id}|${value.playlist_id}" data-share-embedlist="${base_url}embedcv/${value.first_video_id}/${value.playlist_id}" title="Share">Share</a></li>`;

			if (user_login_id == other_user) {
				actionBtn = `<li><a href="${value.edithref}">Edit</a></li>${actionBtn}
													<li><a href="javascript:;" class="delete_playlist" data-playlist_id="${value.playlist_id}">Delete</a></li>`;
			}
			html += `<div class="dis_actiondiv dis_playlist_actiond">
												<span>
												<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
													<path fill-rule="evenodd" fill="rgb(255 255 255)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
												</svg>
												</span>
												<div class="dis_action_content" id="">
													<ul>
														${actionBtn}
													</ul>
												</div>
											</div>`;

			html += `</div>
								<div class="dis_postvideo_content">
									<h3><a href="${value.href}" title="ddd">${value.title}</a></h3>
								</div>
							</div>
						</div>
					</div>`;
		});
	} else {
		html = `<div class="no_result_inner text-center">
				<svg xmlns="http://www.w3.org/2000/svg" width="144px" height="141px" class="no_resultsvg">
				<path class="a" fill-rule="evenodd" fill="rgb(232, 233, 234)" d="M102.927,67.238 C102.868,85.522 87.999,100.297 69.715,100.239 C51.430,100.180 36.656,85.311 36.714,67.027 C36.773,48.742 51.642,33.968 69.926,34.026 C88.210,34.085 102.985,48.954 102.927,67.238 Z"></path>
				<path class="b" fill-rule="evenodd" fill="rgb(189, 194, 203)" d="M142.570,51.942 L139.426,51.942 L139.426,55.084 C139.426,55.647 138.969,56.103 138.406,56.103 C137.843,56.103 137.387,55.647 137.387,55.084 L137.387,51.942 L134.243,51.942 C133.681,51.942 133.224,51.485 133.224,50.923 C133.224,50.360 133.681,49.904 134.243,49.904 L137.387,49.904 L137.387,46.761 C137.387,46.198 137.843,45.742 138.406,45.742 C138.969,45.742 139.426,46.198 139.426,46.761 L139.426,49.904 L142.570,49.904 C143.133,49.904 143.589,50.360 143.589,50.923 C143.589,51.485 143.133,51.942 142.570,51.942 ZM104.580,90.518 L136.892,123.739 C138.772,125.671 139.787,128.219 139.749,130.915 C139.711,133.611 138.624,136.130 136.689,138.009 C134.756,139.888 132.206,140.902 129.510,140.864 C126.815,140.825 124.295,139.740 122.414,137.806 L98.385,113.101 C98.384,113.100 98.382,113.098 98.381,113.097 L89.621,104.090 C84.913,106.619 79.742,108.234 74.339,108.822 C73.530,108.910 72.711,108.975 71.904,109.017 C61.657,109.542 51.580,106.270 43.533,99.805 C34.789,92.781 29.307,82.790 28.097,71.674 C26.887,60.557 30.091,49.622 37.118,40.882 C44.146,32.143 54.142,26.664 65.264,25.454 C76.387,24.245 87.326,27.447 96.070,34.471 C104.814,41.496 110.296,51.486 111.506,62.603 C112.400,70.815 110.871,79.021 107.084,86.332 C106.309,87.837 105.456,89.237 104.580,90.518 ZM126.034,134.289 C126.975,135.256 128.234,135.799 129.582,135.818 C130.929,135.837 132.204,135.330 133.171,134.390 C134.139,133.450 134.682,132.192 134.701,130.844 C134.720,129.497 134.213,128.223 133.273,127.257 L111.177,104.540 L103.939,111.573 L126.034,134.289 ZM100.411,107.945 L107.649,100.912 L101.466,94.555 C98.813,97.625 96.603,99.380 96.565,99.410 C95.738,100.098 94.887,100.750 94.015,101.369 L100.411,107.945 ZM106.621,63.134 C104.499,43.645 87.504,29.164 67.929,30.167 C67.221,30.203 66.504,30.261 65.795,30.338 C55.976,31.405 47.152,36.243 40.947,43.958 C34.743,51.674 31.914,61.328 32.982,71.142 C34.051,80.957 38.891,89.777 46.610,95.979 C54.330,102.181 63.987,105.007 73.808,103.939 C81.616,103.090 88.817,99.855 94.631,94.585 L94.665,94.554 C97.561,91.925 100.015,88.869 101.959,85.472 C105.858,78.663 107.470,70.939 106.621,63.134 ZM77.256,64.907 C76.232,64.904 75.358,64.539 74.636,63.812 C73.914,63.086 73.554,62.211 73.557,61.187 C73.560,60.164 73.926,59.291 74.652,58.569 C75.379,57.848 76.255,57.488 77.279,57.491 C78.303,57.495 79.176,57.860 79.899,58.586 C80.621,59.313 80.980,60.187 80.977,61.211 C80.974,62.235 80.609,63.107 79.882,63.829 C79.155,64.550 78.279,64.910 77.256,64.907 ZM80.400,78.271 C80.554,78.754 80.514,79.223 80.280,79.675 C80.047,80.128 79.689,80.431 79.205,80.584 C78.722,80.737 78.248,80.697 77.785,80.463 C77.322,80.230 77.014,79.862 76.862,79.360 C76.384,77.813 75.494,76.560 74.193,75.600 C72.892,74.640 71.430,74.157 69.807,74.152 C68.184,74.147 66.719,74.620 65.412,75.571 C64.105,76.523 63.207,77.770 62.719,79.315 C62.563,79.816 62.257,80.182 61.803,80.412 C61.348,80.643 60.880,80.680 60.397,80.524 C59.895,80.367 59.529,80.062 59.299,79.608 C59.068,79.154 59.031,78.685 59.187,78.203 C59.909,75.868 61.249,73.990 63.204,72.567 C65.161,71.144 67.365,70.437 69.819,70.445 C72.272,70.453 74.474,71.174 76.420,72.610 C78.367,74.045 79.694,75.932 80.400,78.271 ZM62.418,64.860 C61.394,64.856 60.521,64.491 59.798,63.765 C59.076,63.039 58.716,62.164 58.720,61.140 C58.723,60.117 59.088,59.244 59.815,58.522 C60.542,57.800 61.417,57.441 62.441,57.444 C63.465,57.447 64.338,57.812 65.060,58.539 C65.783,59.265 66.142,60.140 66.139,61.164 C66.136,62.187 65.771,63.060 65.044,63.782 C64.317,64.503 63.441,64.863 62.418,64.860 ZM87.267,12.903 C83.760,12.903 80.899,10.043 80.899,6.538 C80.899,3.026 83.760,0.173 87.267,0.173 C90.781,0.173 93.636,3.026 93.636,6.538 C93.636,10.043 90.781,12.903 87.267,12.903 ZM87.267,2.211 C84.882,2.211 82.938,4.154 82.938,6.538 C82.938,8.922 84.882,10.865 87.267,10.865 C89.653,10.865 91.597,8.922 91.597,6.538 C91.597,4.154 89.653,2.211 87.267,2.211 ZM29.003,17.857 L31.183,20.122 C31.573,20.527 31.561,21.172 31.155,21.562 C30.750,21.953 30.104,21.940 29.714,21.535 L27.534,19.270 L25.268,21.449 C24.863,21.839 24.217,21.827 23.827,21.421 C23.437,21.016 23.449,20.371 23.855,19.981 L26.120,17.802 L23.941,15.538 C23.550,15.132 23.563,14.487 23.968,14.097 C24.374,13.707 25.020,13.719 25.410,14.125 L27.589,16.389 L29.855,14.210 C30.261,13.819 30.906,13.832 31.296,14.237 C31.687,14.643 31.675,15.288 31.269,15.678 L29.003,17.857 ZM25.581,113.255 C25.676,112.700 26.202,112.327 26.758,112.421 C27.312,112.516 27.686,113.043 27.591,113.597 L27.063,116.694 L30.162,117.222 C30.717,117.318 31.090,117.844 30.996,118.398 C30.901,118.953 30.375,119.326 29.820,119.232 L26.720,118.704 L26.192,121.801 C26.097,122.356 25.571,122.729 25.015,122.634 C24.824,122.601 24.653,122.517 24.517,122.397 C24.258,122.172 24.120,121.821 24.182,121.459 L24.710,118.361 L21.611,117.833 C21.419,117.800 21.249,117.716 21.112,117.596 C20.854,117.370 20.716,117.020 20.777,116.657 C20.872,116.102 21.398,115.730 21.954,115.824 L25.053,116.352 L25.581,113.255 ZM10.330,73.926 C8.941,74.905 7.257,75.292 5.576,75.005 C4.407,74.804 3.337,74.293 2.467,73.532 C2.089,73.201 1.746,72.829 1.453,72.411 C0.469,71.028 0.086,69.339 0.369,67.664 C0.578,66.466 1.100,65.406 1.847,64.552 C3.261,62.936 5.452,62.072 7.718,62.455 C11.177,63.052 13.517,66.342 12.925,69.805 C12.637,71.475 11.714,72.943 10.330,73.926 ZM7.375,64.466 C5.022,64.070 2.788,65.654 2.381,68.006 C1.980,70.354 3.565,72.597 5.923,72.998 C7.063,73.191 8.207,72.927 9.149,72.262 C10.097,71.592 10.719,70.602 10.913,69.463 C11.314,67.106 9.729,64.872 7.375,64.466 Z"></path>
				</svg>
				<p>No Playlist Found.</p>
				</div>`;
	}
	return html;
}



function getPlaylistSliderHtml(resData) {
	var color = resData['color'];
	var title = resData['title'];
	var href = resData['href'];
	var autoPlay = resData['auto'];
	var videoData = resData['videoData'];
	var other_user = $('#data-user_id').attr('data-user_id');
	var inner = '';

	if (videoData.length > 0) {
		var html = `<div class="">
					<div class="">
						<div class="">
							<div class="dis_sliderheading">
								<div class="dis_sliderheadingL">
									<h2 class="dis_sliderheading_ttl muli_font">${title}</h2>
								</div>
								<div class="dis_sh_btnwrap">
									<a class="dis_sh_btn muli_font" href="#playlist" aria-controls="playlist" role="tab" data-toggle="tab" aria-expanded="false" onclick="$('.PlayListTab').click()" >See All Playlist
									<span class="dis_sh_btnicon">
										<svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
										<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
										</svg>
									</span>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="">
						<div class="">
							<div class="au_artist_slider" data-autoplay="2000">
								<div class="swiper-container owl-carousel owl-theme owl-loaded">
									<div class="swiper-wrapper">`;


		$.each(videoData, function (i) {
			let actionBtn = '';
			var value = videoData[i];
			let href = value.video_ids_count == 0 ? value.edithref : value.href;

			html += `<div class="swiper-slide owl-item" data-post_playlist_id="${value.playlist_id}">
						<div class="dis_post_video_data">
							<div class="dis_postvideo_img">
							<img src="${value.webp}" class="img-responsive" alt="Discovered" onError="ImageOnLoadError(this,'${value.img}','${value.errimg}')">
								<div class="dis_overlay">
									<div class="dis_overlay_inner">
										<a  href="${href}" class="dis_play_icon ">
											<img src="${base_url}repo/images/playlist_icon.png" alt="" class="img-responsive">
										</a>
									</div>
								</div>
								<div class="pl_count_wrap">
									${value.video_ids_count}
								</div>`;

			actionBtn = `<li><a href="javascript:;" class="dtvShareMe" data-share="2|${value.first_video_id}|${value.playlist_id}" data-share-embedlist="${base_url}embedcv/${value.first_video_id}/${value.playlist_id}" title="Share">Share</a></li>`;

			if (user_login_id == other_user) {
				actionBtn = `<li><a href="${value.edithref}">Edit</a></li>${actionBtn}
											<li><a href="javascript:;" class="delete_playlist" data-playlist_id="${value.playlist_id}">Delete</a></li>`;
			}

			html += `<div class="dis_actiondiv dis_playlist_actiond">
								<span>
								<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
									<path fill-rule="evenodd" fill="rgb(255 255 255)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
								</svg>
								</span>
								<div class="dis_action_content" id="">
									<ul>
										${actionBtn}
									</ul>
								</div>
							  </div>`;

			html += `</div>
							<div class="dis_postvideo_content">
								<h3><a href="${href}" title="${value.title}">${value.title}</a></h3>
							</div>
						</div>
					</div>`;

		});


		// html +=`</div>
		// 			</div>
		// 		</div>
		// 	</div>
		// 	<div class="row">
		// 		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
		// 			<div class="view_all_wrapper">
		// 				<a class="dis_btn b_btn" href="#playlist" aria-controls="playlist" role="tab" data-toggle="tab" aria-expanded="false" onclick="$('.PlayListTab').click()" >See All Playlist</a>
		// 			</div>
		// 		</div>
		// 	</div>
		// </div>`;

		html += `</div>
							</div>
						</div>
					</div>
				</div>
			</div>`;

		return html;
	}
}
/*Playlist code end */



$(document).on('click', '#playlistTitleEditBtn', function () {
	$('#PlaylistTitle').attr('contenteditable', 'true').focus();
	$(this).hide();
	$('#playlistTitleSaveBtn').removeClass('hide');
	var $pos = $('#PlaylistTitle').text().length;
	setCursor($pos);
});

$(document).on('click', '#playlistTitleSaveBtn', function () {
	updatePlaylistTitle();
});

$(document).on('focusout', '#PlaylistTitle', function () {
	updatePlaylistTitle();
});

$(document).on('keypress', '#PlaylistTitle', function (e) {
	if ($(this).text().length >= 50) {
		$('#PlaylistTitle').attr('contenteditable', 'true').focus();
		Custom_notify('error', 'You have reached your maximum limit of characters allowed.');
		e.preventDefault();
		return false;
	}
});

$(document).on('click', '#remove_playlist_thumb', function () {
	if ($('#playlist_id').length) {
		var _this = $(this);
		confirm_popup_function(
			"Delete",
			"Are you sure you want to delete this playlist image?",
			"delete_playlist_thumb()"
		);
	}
});

function delete_playlist_thumb() {

	var formData = new FormData();
	formData.append("playlist_id", $('#playlist_id').val());
	$("#conf_btn")
		.text("Deleting ")
		.append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
		.prop("disabled", true);
	manageMyAjaxPostRequestData(formData, base_url + 'backend/playlist/removePlaylistThumb').done(function (resp) {
		if (resp.status == 1) {
			$('.modal').modal('hide');
			getMyPlaylistVideo();
			//Custom_notify('success',resp.message);
			$('#remove_playlist_thumb').addClass('hide');
			setTimeout(function () {
				success_popup_function("It\'s deleted successfully.");
			}, 500);
		}
	});
}

$(document).on('change', '#playlistthumb', function () {
	$('#thumbloader').show();
	var btnTxt = $('.dis_pl_thumbchnage').text();
	var playlist_id = $('#playlist_id').val();
	var input = document.getElementById("playlistthumb");
	var filePath = input.value;
	var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

	if (allowedExtensions.exec(filePath)) {

		let formData = new FormData();
		file = input.files[0];
		formData.append("image", file);
		formData.append('playlist_id', playlist_id);
		$('.dis_pl_thumbchnage').html('Uploading  <i class="fa fa-spinner fa-spin post_spinner"></i>');
		manageMyAjaxPostRequestData(formData, base_url + 'backend/playlist/upload_playlist_thumb').done(function (data) {
			$('#thumbloader').hide();
			if (data == 1) {
				server_error_popup_function('Invalid size.');
				$('.dis_pl_thumbchnage').html(btnTxt);
			} else {
				data = $.parseJSON(data);
				var image = data.name;
				$('#firstVideoThumb').attr('src', image);
				$('.dis_pl_thumbchnage').html(btnTxt);
				$('#remove_playlist_thumb').removeClass('hide');
				Custom_notify('success', 'Playlist cover image upload successfully.');
			}
		})
	} else {
		$('.dis_pl_thumbchnage').html(btnTxt);
		$('#thumbloader').hide();
		server_error_popup_function('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
		return false;
	}
	input.value = '';
});


function updatePlaylistTitle() {
	let _this = $('#playlistTitleSaveBtn');
	let playlist_title = $.trim($('#PlaylistTitle').text());
	if (playlist_title.length == 0) {
		$('#PlaylistTitle').attr('contenteditable', 'true').focus();
		Custom_notify('error', 'Please enter playlist title');
	} else if (playlist_title.length > 50) {
		$('#PlaylistTitle').attr('contenteditable', 'true').focus();
		Custom_notify('error', 'Please enter maximum 50 characters.');
		return false;
	} else {

		$('#PlaylistTitle').attr('contenteditable', 'false');
		_this.addClass('hide');
		$('#playlistTitleEditBtn').show();
		let formData = new FormData();
		formData.append("playlist_id", $('#playlist_id').val());
		formData.append("playlist_title", playlist_title);
		manageMyAjaxPostRequestData(formData, base_url + 'backend/playlist/updatePlaylist').done(function (resp) {
			if (resp.status == 1) {
				Custom_notify('success', resp.message);
			}
		});
	}
}

/* Set coursor possition code start */
function setCaret(pos) {
	var el = document.getElementById("PlaylistTitle")
	var range = document.createRange()
	var sel = window.getSelection()

	range.setStart(el.childNodes[2], pos)
	range.collapse(true)

	sel.removeAllRanges()
	sel.addRange(range)
}


function setCursor(pos) {
	if (pos > 0) {
		var tag = document.getElementById("PlaylistTitle");

		// Creates range object
		var setpos = document.createRange();

		// Creates object for selection
		var set = window.getSelection();

		// Set start position of range
		setpos.setStart(tag.childNodes[0], pos);

		// Collapse range within its boundary points
		// Returns boolean
		setpos.collapse(true);

		// Remove all ranges set
		set.removeAllRanges();

		// Add range with respect to range object.
		set.addRange(setpos);

		// Set cursor on focus
		tag.focus();
	}
}

/* Set coursor possition code end */