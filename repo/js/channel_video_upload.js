
$(document).ready(function () {
	ToggleSubGenre();

	if ($('#SubmitChannelVideoDetail').length) { //for monetize video edit page
		getThumbs();
	}
})

var getThumb = true;
function getThumbs() {
	if (getThumb) {
		getThumb = false;

		let formData = new FormData();
		// formData.append('post_id',$('.AddToFavriote').attr('data-post_id'));
		formData.append('post_id', $('#VideoPostId').val());
		manageMyAjaxPostRequestData(formData, base_url + 'dashboard/GetChannelThumbs').done(function (data) {
			data = $.parseJSON(data);
			if (data.status == 1) {

				let thumbArr = data.thumbs;
				uid = $('#uid').val();
				if (thumbArr.length < 4) {
					$('.dis_select_video').show();
				}
				thmbcount1 = 1;
				for (let k = 0; k < thumbArr.length; k++) {
					renderChannelThumb(thumbArr[k]['name'], thumbArr[k]['thumb_id'], uid, 'append', thumbArr[k]['active_thumb']);
				}

			}
		})

		if ($('#VideoPostId').length) {
			getPlaylist($('#VideoPostId').val());  /*******from playlist.js****/
		}
	}
}

var thumb = 0;

$(document).on('change', '#custom_file', function () {
	$('#thumbloader').show();

	var uid = $('#uid').val();
	var input = document.getElementById("custom_file");
	var filePath = input.value;
	var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

	if (allowedExtensions.exec(filePath)) {

		let formData = new FormData();
		file = input.files[0];

		formData.append("image", file);
		formData.append('post_id', $('#VideoPostId').val())

		manageMyAjaxPostRequestData(formData, base_url + 'dashboard/upload_channel_thumb').done(function (data) {
			$('#thumbloader').hide();
			if (data == 1) {
				server_error_popup_function('Invalid size.');
			} else {
				data = $.parseJSON(data);
				console.log(data.name);
				var image = data.name;
				renderChannelThumb(image, data.thumb_id, uid, 'append');

				var TotalThumb = $('.dis_custom_width').length;
				if (TotalThumb > 3) {
					$('.dis_select_video').hide();
				}
				$('.MakeThumbActive').removeClass('active');

				if (popup_index && popup_index.length) {
					setTimeout(() => {
						$("div.ThumbSelect[data-thumb_id='" + data.thumb_id + "']").trigger('click');	 /* from bulsthree.js , trigger for getting thumbs*/
					}, 2000)
				}
			}
		})
	} else {
		$('#thumbloader').hide();
		server_error_popup_function('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
		return false;
	}
	input.value = '';

});


var thmbcount = 3;
var thmbcount1 = 1;
function renderChannelThumb(image, thumb_id, uid, extend, active = '') {

	let cont = (thmbcount < 1) ? 'Select Custom Thumbnail' : 'Select Thumbnail ' + thmbcount;
	let deleButn = '';
	let act = '';

	if (extend == 'append') {
		deleButn = '<div class="dis_img_delete delete_thumb_img" data-thumb_id = "' + thumb_id + '"  ><span aria-hidden="true">×</span></div>';
		act = 'active';
	}
	// let rotateThumb = '<div class="dis_rotatethumb"><i class="fa fa-repeat" aria-hidden="true"></i></div>';
	let rotateThumb = '';

	if (active.length) {
		cont = 'Select Thumbnail ' + thmbcount1;
		act = (active == 1) ? 'active' : '';
		thmbcount1++;
	}

	let ActImage = image;
	let isIvaVideo = image.split('/');
	let imag = '';
	if (isIvaVideo.length > 1) {
		imag = ActImage;
	} else {
		imag = AMAZON_URL + 'aud_' + uid + '/images/' + ActImage;
	}
	imag = imag.replace('&amp;', '&');

	console.log('image' + imag);
	console.log(act);

	let dt = new Date();
	setTimeout(function () {
		if (ActImage.length) {
			let errimg = base_url + 'repo/images/thumbnail.jpg';
			let thumHtml = '<div class="dis_custom_width">\
									<div class="MakeThumbActive dis_video_thumbnail '+ act + '">\
										<div class="dis_video_thumbnail_img">\
											<label for="thumb'+ thumb_id + '" >\
											<div class="ThumbSelect" data-thumb_id="'+ thumb_id + '">\
												<a><img alt="" src="'+ imag + '" class="img-responsive" onerror="this.onerror=null;this.src=\'' + errimg + '\';"></a>\
												<div class="overlay">\
													<span><i class="fa fa-check-circle" aria-hidden="true"></i></span>\
												</div>\
											</div>\
											'+ deleButn + rotateThumb + '\
											</label>\
										</div>\
										<h2> '+ cont + '</h2>\
									</div>\
								</div>';
			if (extend == 'append') {
				thumb = 1;

				$('#theDiv').append(thumHtml);
			} else {
				$('#theDiv').prepend(thumHtml);
			}
		}
	}, 500);

	thmbcount--;
}

$(document).on('click', '.ThumbSelect', function () {
	let _this = $(this);
	let thumb_id = _this.data('thumb_id');
	let formData = new FormData();
	formData.append('thumb_id', thumb_id);

	manageMyAjaxPostRequestData(formData, base_url + 'dashboard/updateThumbStatus').done(function (resp) {
		response = $.parseJSON(resp);
		if (response.status == 1) {
			thumb = 1;
			if (_this.parents('li').find('.MakeThumbActive').length) {
				_this.parents('li').find('.MakeThumbActive').removeClass("active");         /* For Bulk Upload Videos*/
			} else {
				_this.parents('#theDiv').find('.MakeThumbActive').removeClass("active");        /* For Single Upload And Edit Videos*/

				if (typeof popup_index != 'undefined' && popup_index.length)
					RenderBulkVideoThumbnails(response, popup_index);	 /* from bulsthree.js , showMoreDetailOnPopup function*/
			}
			_this.parents('.MakeThumbActive').addClass("active");
			Custom_notify('success', 'Thumb has been successfully updated.');
		} else {
			alert('Something went wrong, please try again');
		}
	})
})

$(document).on('click', '.delete_thumb_img', function () {
	var _this = $(this);
	$('#conf_btn').show();
	$('#conf_header').addClass('notification_popup').removeClass('success_popup');
	$('#conf_title').text('Delete');
	$('#conf_text').text('Are you sure to remove this custom thumbnail ?');
	$('#conf_btn').attr('onclick', 'delete_thumb(' + _this.data('thumb_id') + ')');
	$('#conf_btn').text('Delete');
	$('#confirm_popup').modal('show');

});

function delete_thumb(thumb_id) {
	var formData = new FormData();
	formData.append('thumb_id', thumb_id);
	manageMyAjaxPostRequestData(formData, base_url + 'dashboard/DeleteThumb').done(function (resp) {
		if (resp == 0) {
			server_error_popup_function('Something went wrong, please try again.');
		} else
			if (resp == 1) {
				thumb = 0;
				$(document).find("[data-thumb_id='" + thumb_id + "']").parents('.dis_custom_width').remove();
				$('.dis_select_video').show();
				$('#confirm_popup').modal('hide');

				if (popup_index && popup_index.length) {   /* FOR REMOVING THUMBS FROM BULK S3 SECTION */
					$(document).find("div.ThumbSelect[data-thumb_id='" + thumb_id + "']").parents('.MakeThumbActive').remove();
					$('.MyCustomThumb').parents('li').removeClass('hideme');
				} else {
					$('.MakeThumbActive').removeClass("active");
				}

			} else
				if (resp == 2) {
					server_error_popup_function('You can\'t delete this last thumb');
				}
	})
}
$(document).on('click', '.SetHlsUrl', function () {
	let m3u8 = $.trim($('#m3u8').val());
	let error = '';

	if (m3u8.length && m3u8 != '') {
		let M3u8Array = m3u8.split('.m3u8');
		if (M3u8Array[1] != 'undefined' && M3u8Array[1] != undefined) {
			$.post(base_url + "dashboard/AddHlSURL", { 'uploaded_video': m3u8 }, function (data) {
			}).done(function (data) {
				console.log(data);
				data = $.parseJSON(data);
				if (data.status == 1) {
					$('.LoadMonetizePage').load(base_url + 'dashboard/upload_channel_video/single/true', function (response, status, xhr) {
						intializeTokenField('#tag');
						// InitializeCKeditor();
						getPlaylist(data.pubId);  /*******from playlist.js****/

						$('#uploadChannelVideo').hide();

						$('#VideoPostId').val(data.pubId);
						$('[name="privacy_status"]').val(7);
						$('.publish_btn').addClass('hideme');
						$('.montiz_upld_wrap').parents('div.row').addClass('hideme');
						
						initOpenAi(); //from common js
					})
				} else {
					error = data.message;
				}
			})

		} else {
			error = 'please enter valid URL.';
		}
	} else {
		error = 'please enter URL.';

	}

	if (error.length && error != '') {
		server_error_popup_function(error);
	}
})

$('.publish_btn').addClass('hideme');
$(document).on('submit', '.channelform', async function (e) {
	e.preventDefault();

	let _this = $(this);
	let formData = new FormData(_this[0]);
	let ProfanityWords = await checkProfanityWords(formData.get('description'), formData.get('title'));

	if (ProfanityWords.status == 1) {

		if (thumb >= 1) {

			var checkValid = checkRequire(_this);

			if (checkValid == 0) {

				if ($('.check').is(":checked")) {

					$('.publish_btn').removeClass('hideme');
					$('#check').html('');
					$('.form-error').html('');

					manageMyAjaxPostRequestData(formData, base_url + $(this).attr('action')).done(function (resp) {
						$('.publish_btn').addClass('hideme');
						console.log(resp);
						if (window.location.href == base_url + 'monetization' || window.location.href == base_url + 'monetization/getdiscovered') {
							if (resp == 1) {
								redirect('home/messages/upload_success', 2000);
							} else {
								redirect('home/messages/upload_failed', 2000);
							}
						} else {
							if (resp == 1) {
								Custom_notify('success', 'Information has been successfully updated.');
								redirect('watch/' + $('#VideoPostId').attr('data-post_key'), 3000);
							} else {
								Custom_notify('error', 'No updates to save.');
							}

						}

					});
				} else {
					$('.form-error').html('');
					$('#check').html('Click here to accept DiscoveredTv\'s terms and conditions.');
				}
			} else {
				console.log(checkValid, 'checkValid');
				// server_error_popup_function('Please select one thumbnail.');
			}
		} else {
			server_error_popup_function('Please select one thumbnail.');
		}
	} else {
		Custom_notify('error', ProfanityWords.msg);
	}
})

$(document).on('click', '.dis_rotatethumb', function () {
	let ths = $(this);
	let path = ths.parents('.dis_video_thumbnail_img').find('img').attr('src');
	paths = path.split("?");
	let dt = new Date();

	let formData = new FormData();
	formData.append('imgPath', paths[0]);

	manageMyAjaxPostRequestData(formData, base_url + 'dashboard/RotateImage').done(function (result) {
		if (result == 1) {
			ths.parents('.dis_video_thumbnail_img').find('img').attr('src', path + '?q=' + dt.getTime());
		}
	})
})

if ($('.tokenfield').length) {
	intializeTokenField();
}

function intializeTokenField(selector = '.tokenfield') {
	if (typeof $(selector).tokenfield == 'function') {
		$(selector).tokenfield({
			autocomplete: {
				source: function (request, response) {
					// jQuery.get(base_url+"dashboard/getTaglist", {
					jQuery.post(node_url + "getTaglist", {
						query: request.term
					}, function (data) {
						// data = $.parseJSON(data);
						response(data);
					});
				},
				delay: 100
			},
			showAutocompleteOnFocus: false,

		});

		$(selector).on('tokenfield:createtoken', function (event) {
			var existingTokens = $(this).tokenfield('getTokens');
			$.each(existingTokens, function (index, token) {
				if (token.value === event.attrs.value)
					event.preventDefault();
			});
		});
	}
}

if ($('#uploadChannelVideo').length) {
	$("html, body").animate({ scrollTop: 900 });
}

$(document).on('change', '[name="mode"]', function () {
	let mode_id = $(this).val();
	if (mode_id.length) {
		
		manageMyAjaxPostRequestData({ id: $(this).val() }, node_url + 'getGenreList').done(function (resp) {
			$('[name="genre"]').html(resp);
		})
	}
})

$(document).on('change', '[name="genre"]', function () {
	manageMyAjaxPostRequestData({ genre_id: $(this).val() }, node_url + 'getSubGenreList').done(function (resp) {
		
		$('[name="sub_genre"]').html(resp);
		
		if(resp.length < 40){
			$('[name="sub_genre"]').parent().parent().parent().hide()	
		}else{
			$('[name="sub_genre"]').parent().parent().parent().show()	
		}
	})

})

$(document).on('change', '.GloabalMode', function () {
	$('[name="mode[]"]').val($(this).val()).trigger('change');
})

$(document).on('change', '.GloabalGenre', function () {
	$('[name="genre[]"]').val($(this).val()).trigger('change');
})

function ToggleSubGenre() {
	let sub_genre = $('[name="sub_genre"]').parent().parent().parent();

	if ($('[name="sub_genre"]').length && $('[name="sub_genre"]').val() != '') {
		sub_genre.show();
	} else {
		sub_genre.hide();
	}
}




var startVideo = 0;
var limitVideo = 3;
var requestVideo = true;

$(document).on('click', '.intSlider', function () {
	let _this = $(this);
	if (_this[0].nodeName == 'LI') _this.removeClass('intSlider');
	
	$('.tab-pane').removeClass('active');
	$('#sv_creator').addClass('active');

	window.getRelatedVideo('click',_this);
})

window.getRelatedVideo = function(type="",_this= {}){
	let f = new FormData();
	
	if(type == ''){
		startVideo = 0
	}
	
	f.append('limit', limitVideo);
	f.append('FromSinglePage', 1);
	f.append('start', startVideo);
	
	if (type == 'click') {
		f.append('uid', _this.attr('data-uid'));

		if (_this.hasClass('dis_btn'))
		_this.html('Loading  <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled', true);

		manageMyAjaxPostRequestData(f, base_url + 'player/getRelatedVideoNew').done(function (data) {
			
			data =  JSON.parse(data);
			if(data.status == 1 && data.data.length > 0){
				var authorString = data.data.reduce(function(prevVal,currVal,idx){
					return prevVal + loadMoreVideoHtml(currVal)
				}, '')
				
				if(startVideo == 0) 
				{
					$('#load_related').html(authorString);
				}else 
				{
					$('#load_related').append(authorString);
				}
				AdAdsOnChannel(_this, function () { }, is_slider = false);
				if(type == 'click'){
					startVideo += limitVideo;
				}
				if (_this.hasClass('dis_btn')) _this.html('See More').prop('disabled', false);
			}else{
				if (_this.hasClass('dis_btn')) {
					_this.removeClass('intSlider');
					_this.text('No more data available');
					requestVideo = false;
				}
			}
		})
	}else{
		
		return new Promise(function(myResolve, myReject) {
			const { VideoUserId } = getCustomParam(0);
			f.append('uid', VideoUserId);
			manageMyAjaxPostRequestData(f, base_url + 'player/getRelatedVideoNew').done(function (data) {
				data =  JSON.parse(data);
				if(data.status == 1){

					startVideo += limitVideo;
					
					var authorString = data.data.reduce(function(prevVal,currVal,idx){
						return prevVal + loadMoreVideoHtml(currVal)
					}, '')

					myResolve(authorString);
					
				}
				
			})
		});
	}
	
	
}
var CastRequest = true;
$(document).on('click', '.intCastCrew', function () {
	var thiss = $(this);

	$('.tab-pane').removeClass('active');
	$('#sv_cast').addClass('active');
	$(this).removeClass('intCastCrew');
	if (CastRequest) {
		let formData = new FormData();
		formData.append('pid', thiss.attr('data-post_id'));

		manageMyAjaxPostRequestData(formData, base_url + 'player/getCastCrew').done(function (resp) {
			resp = $.parseJSON(resp);
			if (resp.status == 1) {
				if (resp.data) {
					$('#castandcrewhtmlSingleVideo').append(resp.data);
				} else {
					$('.dis_cast_div').append('<center class="dis_cast_nofound">No Cast & Crew available</center>');
				}


				CastRequest = true;
			}
		})
	}
})

/***************** START OF CAST AND CREW SECTION ******************/


$(document).on('change', '#cast_file', function () {
	var inp = document.getElementById('cast_file');
	$('.dis_upload_div').find('span').text(inp.files[0].name);
})

$(document).on('click', '.delete_cast_img', function () {
	var _this = $(this);
	$('#conf_btn').show();
	$('#conf_header').addClass('notification_popup');
	$('#conf_title').text('Delete');
	$('#conf_text').text('Do you really want to delete this Cast ?');
	$('#conf_btn').attr('onclick', 'delete_cast(' + _this.data('cast_id') + ',' + _this.data('post_id') + ')');
	$('#conf_btn').text('Delete');
	$('#confirm_popup').modal('show');

});

function delete_cast(cast_id, post_id) {
	var formData = new FormData();
	formData.append('cast_id', cast_id);
	formData.append('post_id', post_id);
	manageMyAjaxPostRequestData(formData, base_url + 'dashboard/DeleteCast').done(function (resp) {
		if (resp != 0) {
			$(document).find("[data-cast_id='" + cast_id + "']").parents('.dis_cast_data').parent().remove();
			$('#confirm_popup').modal('hide');
		} else {
			alert('something went wrong, please try again');
		}
	})
}

function checkFilevalidation(inp) {
	/* if(inp.files.length === 0){
		alert('Please upload an image'); 
		return false;
	}else  */
	if (inp.files.size > 2048000) {
		alert('Maximum 2M size allowed');
		return false;
	}
	return true;
}


/*start of add cast and crew form*/
$(document).on('submit', 'form.castform', function (e) {
	e.preventDefault();
	let _this = $(this);
	let btntxt = $('.dis_host_dev').text();
	let inp = document.getElementById('cast_file');
	let cast_id = $('#cast_id').val();

	if (checkRequire(_this) == 0) {
		$('.publish_btn').removeClass('hideme');
		$('.form-error').text('');

		$('.dis_host_dev').html('Wait...  <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled', true);
		manageMyAjaxPostRequestData(new FormData(_this[0]), base_url + $(this).attr('action')).done(function (resp) {
			$('.dis_host_dev').html(btntxt).prop('disabled', false);

			resp = $.parseJSON(resp);
			if (resp.status == 1) {
				$('.publish_btn').addClass('hideme');
				$('form.castform')[0].reset();
				$('.no_result_wraaper').remove();
				$('.dis_cast_div  > center').remove();
				if (cast_id.length == 0) {
					$('#castandcrewhtmlSingleVideo').append(resp.data);
				} else {
					$(document).find("[data-cast_id='" + resp.cast_image_id + "']").parents('li').remove();
					$('#castandcrewhtmlSingleVideo').append(resp.data);
				}
				CastRequest = true;
				
				$('#myCommonModal').modal('hide');
			}
		});
	}
})


/***************** END OF CAST AND CREW SECTION ******************/




/************** VOTING SECTION OF CHANNEL STARTS ************************/
$(document).on('click', '.yr_vote', function () {
	if (user_login_id != '') {
		var _th = $(this);
		if (!_th.is('[disabled=disabled]')) {
			
			let f = new FormData();
				f.append('post_id', _th.attr('data-pid'));
			
			_th.removeClass('yr_vote').addClass('active');
			manageMyAjaxPostRequestData(f, base_url + 'dashboard/GiveYourVote').done(function (resp) {
				if (resp == 1) {
					let type = _th.attr('data-type');
					if(type == 'old'){
						$('#yr_vote').html('Loved');
					}else{
						_th.html(`<span class="dis_SV_btnIcon">
						<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M7 12.631C6.92918 12.631 6.85839 12.6127 6.79495 12.5761C6.72605 12.5363 5.08895 11.5858 3.42837 10.1536C2.44416 9.30475 1.65852 8.46284 1.09333 7.65128C0.361937 6.60112 -0.00586292 5.59098 7.06585e-05 4.64891C0.00701596 3.5527 0.399644 2.52179 1.10571 1.74605C1.8237 0.95724 2.78188 0.522858 3.8038 0.522858C5.11348 0.522858 6.31089 1.25649 7.00003 2.41865C7.68917 1.25652 8.88658 0.522858 10.1963 0.522858C11.1617 0.522858 12.0828 0.914802 12.7901 1.6265C13.5662 2.40752 14.0072 3.51106 13.9999 4.65411C13.994 5.59454 13.6193 6.60314 12.8863 7.65185C12.3193 8.463 11.5348 9.30453 10.5544 10.1531C8.89992 11.5852 7.27459 12.5357 7.2062 12.5754C7.14246 12.6125 7.07121 12.631 7 12.631Z" fill="#515151"/>
						</svg>
					</span>
					Loved`)
					}
					
				}
			})
		}
	} else {
		$('#myModal').modal('show');
	}
});

/************** VOTING SECTION OF CHANNEL Video END ************************/

/************** COMMENT SECTION START ************************/

var CommentField = function (parent_com_id) {
	let dfimg = base_url + 'repo/images/user/user.png';
	let uimg = $('.dis_userinfo > a > img').attr('src');
	let field = `<div class="dis_vid_commentFieldWrap">
			<div class="dis_vid_cmntImg">
				<img src="${uimg}" alt="image" class="img-fluid" onerror="this.onerror=null;this.src='${dfimg}';">
			</div>
			<textarea class="dis_vid_cmnTextarea" placeholder="Add Your Comment Here" id="com_text_area_${parent_com_id}"></textarea>
			${ShowAiPrompt(className = 'dis_monetize_comment', idName = 'com_text_area_' + parent_com_id)}
			<div class="emoji_picker _EmojiPicker" data-target="#emoji${parent_com_id}" data-textarea="#com_text_area_${parent_com_id}">
				<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" viewBox="0 0 512 512" ><g><g><g><path d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40s216,96.897,216,216S375.103,472,256,472z" fill="#000000" data-original="#000000"></path></g></g><g><g><path d="M368.993,285.776c-0.072,0.214-7.298,21.626-25.02,42.393C321.419,354.599,292.628,368,258.4,368c-34.475,0-64.195-13.561-88.333-40.303c-18.92-20.962-27.272-42.54-27.33-42.691l-37.475,13.99c0.42,1.122,10.533,27.792,34.013,54.273C171.022,389.074,212.215,408,258.4,408c46.412,0,86.904-19.076,117.099-55.166c22.318-26.675,31.165-53.55,31.531-54.681L368.993,285.776z" fill="#000000" data-original="#000000"></path></g></g><g><g><circle cx="168" cy="180.12" r="32" fill="#000000" data-original="#000000"></circle></g></g><g><g><circle cx="344" cy="180.12" r="32" fill="#000000" data-original="#000000"></circle></g></g></g></svg>
			</div>
			<span id="emoji${parent_com_id}" class="hide"></span>
			${(user_login_id != '') ? `<span class="dis_vid_cmntFSubmit" onclick="AddComment(this , ${parent_com_id})">` : `<span class="dis_vid_cmntFSubmit openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">`}
			
				<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.6665 6.375C13.6665 6.41564 13.6546 6.45539 13.6323 6.48937C13.61 6.52335 13.5782 6.55007 13.541 6.56625L1.04125 11.9828C1.00104 12.0002 0.95634 12.0046 0.913527 11.9951C0.870714 11.9857 0.831966 11.963 0.802799 11.9303C0.773633 11.8975 0.755534 11.8565 0.751079 11.8128C0.746624 11.7692 0.75604 11.7253 0.777987 11.6874L3.23418 7.42914C3.25783 7.38817 3.29471 7.35646 3.33876 7.33921L5.80331 6.375L3.33878 5.41088C3.29473 5.39363 3.25785 5.36191 3.2342 5.32094L0.777987 1.06262C0.75604 1.02468 0.746624 0.98077 0.751079 0.937158C0.755534 0.893546 0.773633 0.852451 0.802799 0.819723C0.831966 0.786995 0.870714 0.764302 0.913527 0.754875C0.95634 0.745447 1.00104 0.749765 1.04125 0.767215L13.541 6.18375C13.5782 6.19993 13.61 6.22665 13.6323 6.26063C13.6546 6.29461 13.6665 6.33436 13.6665 6.375Z" fill="#9C9DA3"/>
					</svg>
			</span>
		</div>`
	return field;
};

var CommentBox = function (item, parent_com_id) {
	let dfimg = base_url + 'repo/images/user/user.png';

	let box = `<div class="dis_vid_commentBox">
			<div class="dis_vid_IcommentBox">
				<div class="dis_vid_cmntImg">
					<img src="${AMAZON_URL}aud_${item.user_id}/images/${item.uc_pic}" alt="image" class="img-fluid" onerror="this.onerror=null;this.src='${dfimg}';">
				</div>
				<div class="dis_vid_cmntdet">
					<a href="${base_url}profile?user=${item.user_uname}" class="dis_vc_ttl">${item.user_name}</a>
					<ul class="dis_vci_Ilist">
						<!--li>
							<p class="dis_vc_time">
								<span class="dis_vci_IIcon">
									<svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M6 0.5C4.81331 0.5 3.65328 0.851894 2.66658 1.51118C1.67989 2.17047 0.910851 3.10754 0.456725 4.2039C0.00259973 5.30025 -0.11622 6.50665 0.115291 7.67054C0.346802 8.83443 0.918247 9.90352 1.75736 10.7426C2.59648 11.5818 3.66557 12.1532 4.82946 12.3847C5.99335 12.6162 7.19974 12.4974 8.2961 12.0433C9.39246 11.5891 10.3295 10.8201 10.9888 9.83342C11.6481 8.84673 12 7.68669 12 6.5C11.9981 4.90928 11.3654 3.38424 10.2406 2.25943C9.11576 1.13462 7.59072 0.501877 6 0.5ZM8.022 8.522C7.91971 8.62426 7.781 8.6817 7.63636 8.6817C7.49173 8.6817 7.35302 8.62426 7.25073 8.522L5.61436 6.88563C5.51206 6.78337 5.45458 6.64465 5.45455 6.5V3.22727C5.45455 3.08261 5.51201 2.94387 5.61431 2.84158C5.7166 2.73929 5.85534 2.68182 6 2.68182C6.14466 2.68182 6.2834 2.73929 6.38569 2.84158C6.48799 2.94387 6.54545 3.08261 6.54545 3.22727V6.27418L8.022 7.75073C8.12426 7.85301 8.1817 7.99173 8.1817 8.13636C8.1817 8.281 8.12426 8.41971 8.022 8.522Z" fill="#9C9DA3"></path>
									</svg>
								</span>	
								${getTimeAgo1(item.com_date)}</p>
						</li-->
						${parent_com_id == 0 ? `<li>
							<a href="javascript:;" onclick="fetchComment(${item.com_id},${video_post_id},${video_user_id})">
								<p class="dis_vc_time" >
									<span class="dis_vci_IIcon">
										<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M0.387991 5.93C1.17099 3.914 3.28699 2.906 6.73499 2.906H8.35999V0.984997C8.35999 0.854997 8.40599 0.741997 8.49799 0.646997C8.58999 0.551997 8.69899 0.504997 8.82499 0.504997C8.94999 0.504997 9.05899 0.551997 9.15099 0.646997L12.865 4.49C12.957 4.585 13.003 4.697 13.003 4.827C13.003 4.957 12.957 5.07 12.865 5.165L9.15099 9.008C9.05899 9.102 8.94999 9.15 8.82499 9.15C8.69899 9.15 8.58999 9.102 8.49799 9.008C8.40599 8.913 8.35999 8.8 8.35999 8.67V6.749H6.73499C6.26199 6.749 5.83699 6.763 5.46199 6.794C5.08799 6.823 4.71499 6.877 4.34499 6.955C3.97499 7.033 3.65399 7.139 3.37999 7.274C3.10699 7.409 2.85199 7.583 2.61499 7.795C2.37799 8.008 2.18399 8.261 2.03399 8.554C1.88499 8.846 1.76699 9.192 1.68299 9.593C1.59799 9.993 1.55599 10.446 1.55599 10.951C1.55599 11.226 1.56799 11.534 1.59199 11.874C1.59199 11.904 1.59799 11.963 1.60999 12.051C1.62199 12.138 1.62799 12.204 1.62799 12.249C1.62799 12.325 1.60799 12.387 1.56699 12.437C1.52499 12.487 1.46799 12.512 1.39599 12.512C1.31899 12.512 1.25099 12.469 1.19299 12.384C1.15899 12.339 1.12799 12.284 1.09899 12.219C1.06999 12.154 1.03699 12.079 1.00099 11.994C0.963991 11.909 0.938991 11.85 0.924991 11.814C0.309991 10.388 0.00299072 9.26 0.00299072 8.43C0.00299072 7.434 0.130991 6.601 0.387991 5.93Z" fill="#9C9DA3"></path>
										</svg>
									</span>
									reply
								</p>
							</a>
						</li>`
			: ''}
						
					</ul>
					<p class="dis_vc_cmnt contentText">${partOfString(item.message.replace(/(?:\r\n|\r|\n)/g, '<br>'), start = 0, end = 210)}</p>
					${item.msg_count > 0 ? `<a href="javascript:;" onclick="fetchComment(${item.com_id},${video_post_id},${video_user_id})" class="dis_vc_userReply" aria-expanded="true">View Replies</a>` : ''}
				</div>
			</div>
		</div>`
	return box;
};

var CommentMsgIcon = function () {
	let icon = `<span class="dis_comentMsg">
			<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M10.1591 0H3.34091C2.58773 0.000902186 1.86566 0.300499 1.33308 0.833074C0.800501 1.36565 0.500902 2.08772 0.5 2.84089V7.38631C0.500826 8.04097 0.727324 8.67533 1.14132 9.18247C1.55531 9.6896 2.13148 10.0385 2.77273 10.1704V11.9317C2.77271 12.0346 2.80062 12.1355 2.85348 12.2238C2.90634 12.312 2.98216 12.3843 3.07286 12.4328C3.16356 12.4813 3.26573 12.5043 3.36848 12.4993C3.47122 12.4943 3.57068 12.4616 3.65625 12.4045L6.92046 10.2272H10.1591C10.9123 10.2263 11.6343 9.92671 12.1669 9.39413C12.6995 8.86156 12.9991 8.13949 13 7.38631V2.84089C12.9991 2.08772 12.6995 1.36565 12.1669 0.833074C11.6343 0.300499 10.9123 0.000902186 10.1591 0ZM9.02273 6.81814H4.47727C4.32658 6.81814 4.18206 6.75827 4.07551 6.65172C3.96895 6.54517 3.90909 6.40065 3.90909 6.24996C3.90909 6.09927 3.96895 5.95475 4.07551 5.8482C4.18206 5.74164 4.32658 5.68178 4.47727 5.68178H9.02273C9.17342 5.68178 9.31794 5.74164 9.42449 5.8482C9.53105 5.95475 9.59091 6.09927 9.59091 6.24996C9.59091 6.40065 9.53105 6.54517 9.42449 6.65172C9.31794 6.75827 9.17342 6.81814 9.02273 6.81814ZM10.1591 4.54542H3.34091C3.19022 4.54542 3.0457 4.48556 2.93914 4.37901C2.83259 4.27245 2.77273 4.12794 2.77273 3.97725C2.77273 3.82656 2.83259 3.68204 2.93914 3.57548C3.0457 3.46893 3.19022 3.40907 3.34091 3.40907H10.1591C10.3098 3.40907 10.4543 3.46893 10.5609 3.57548C10.6674 3.68204 10.7273 3.82656 10.7273 3.97725C10.7273 4.12794 10.6674 4.27245 10.5609 4.37901C10.4543 4.48556 10.3098 4.54542 10.1591 4.54542Z" fill="#C4C4C4"></path>
				</svg>
		</span>`;
	return icon;
};

var MoreComment = function (parent_com_id) {
	let more = `<div class="dis_vc_Loadmore">
			<a href="javascript:;" class="dis_vc_userReply" data-start="0" data-limit="5" data-comm_id="${parent_com_id}" onclick="fetchMoreComment(${parent_com_id})">${parent_com_id == 0 ? 'Load More Comments' : 'View More Replies'} </a>
		</div>`;
	return more;
};

var ReplyList = function (parent_com_id) {
	let list = `<ul class="dis_comentReplyList" style="display:none;">
			<li id="childCom_${parent_com_id}">
				<ul class="dis_vid_commentList" >
					<li>
						${CommentField(parent_com_id)}
						${CommentMsgIcon()}
					</li>
				</ul>
				${MoreComment(parent_com_id)}
			</li>
		</ul>`;
	return list;
};

var LoadComment = function (comments, parent_com_id) {
	let result = '';
	result += CommentField(parent_com_id);

	result += `<ul class="dis_vid_commentList m_t_30">
			 ${LoopTheComment(comments, parent_com_id)}
		</ul>`;

	if (comments.length > mc(parent_com_id, 'limit')) {
		result += MoreComment(parent_com_id);
	}

	return result;
};

var LoopTheComment = function (comments, parent_com_id) {
	let result = '';
	let loopCnt = 1;
	let limit = mc(parent_com_id, 'limit');

	comments.map(function (item, index) {
		if (loopCnt++ <= limit) {
			result += `<li>
					${CommentBox(item, parent_com_id)}
					${parent_com_id == 0 ? ReplyList(item.com_id) : CommentMsgIcon()}
				</li>`;

		}
	})
	return result;
};

var ttwemoji = false;
var video_post_id='';
var video_user_id='';

var fetchComment = function (parent_com_id,post_id,user_id) {
	video_post_id = post_id;
	video_user_id = user_id;
	if (ttwemoji) {
		loadComment();
	} else {
		loadScript(CDN_BASE_URL + TWEMOJI_JS, function () {
			loadComment();
			ttwemoji = true;
		});
	}

	function loadComment() {
		let f = new FormData();

		f.append('post_id', video_post_id);
		f.append('parent_com_id', parent_com_id);
		f.append('start', mc(parent_com_id, 'start'));
		f.append('limit', mc(parent_com_id, 'limit'));

		manageMyAjaxPostRequestData(f, base_url + 'player/getPostComments').done(function (resp) {
			if (resp.status == 1) {
				if (parent_com_id == 0) {
					$('.dis_vid_commentWrap').append(LoadComment(resp.comments, parent_com_id));
				} else {
					$('#childCom_' + parent_com_id).parents('.dis_comentReplyList').slideToggle('slow');
					$('#childCom_' + parent_com_id).find('ul').append(LoopTheComment(resp.comments, parent_com_id));
					$('[onclick="fetchComment(' + parent_com_id + ','+ video_post_id+','+ video_user_id +')"]').attr('onclick', `$('#childCom_${parent_com_id}').slideToggle('slow')`);
				}
				if (!IsMobileDevice()) {
					$('.contentText').each(function (index) {
						if (!IsMobileDevice()) twemoji.parse(document.querySelectorAll(".contentText")[index], { folder: '72x72', ext: '.png', });
					})
				}
				if (resp.comments.length) {
					mc(parent_com_id).attr('data-start', parseInt(mc(parent_com_id, 'start')) + parseInt(mc(parent_com_id, 'limit')));
				} else {
					mc(parent_com_id).parent().hide();
				}
			}
		})
	}
};

var fetchMoreComment = function (parent_com_id, com_id = '') {

	let f = new FormData();
	
	f.append('post_id', video_post_id);
	f.append('parent_com_id', parent_com_id);
	f.append('start', mc(parent_com_id, 'start'));
	f.append('limit', mc(parent_com_id, 'limit'));
	f.append('com_id', com_id);

	manageMyAjaxPostRequestData(f, base_url + 'player/getPostComments').done(function (resp) {
		if (resp.status == 1) {
			if (parent_com_id == 0) {
				if (com_id) { //In case of when we just prepend the comment direcly on adding comment
					$('.dis_vid_commentList.m_t_30').prepend(LoopTheComment(resp.comments, parent_com_id));
				} else {
					$('.dis_vid_commentList.m_t_30').append(LoopTheComment(resp.comments, parent_com_id));
				}
			} else {
				if (com_id) { //In case of when we just prepend the comment direcly on adding comment
					$('#childCom_' + parent_com_id).find('ul:first').find('li:first').after(LoopTheComment(resp.comments, parent_com_id));
				} else {
					$('#childCom_' + parent_com_id).find('ul:first').append(LoopTheComment(resp.comments, parent_com_id));
				}
			}

			if (!IsMobileDevice()) {
				$('.contentText').each(function (index) {
					if (!IsMobileDevice()) twemoji.parse(document.querySelectorAll(".contentText")[index], { folder: '72x72', ext: '.png', });
				})
			}

			if (!com_id) {   //In case of when we append the comments, In that case we increase the start and limit
				if (resp.comments.length) {
					mc(parent_com_id).attr('data-start', parseInt(mc(parent_com_id, 'start')) + parseInt(mc(parent_com_id, 'limit')));
				} else {
					mc(parent_com_id).parent().hide();
				}
			}

		}
	})
};

var mc = function (parent_com_id, type = '') {  //more comment section
	let c = $('[data-comm_id="' + parent_com_id + '"]');
	if (type == 'start' || type == 'limit') {
		let v = (type == 'limit') ? 5 : 0;
		return c.length ? c.attr('data-' + type) : v;
	} else {
		return c;
	}
};

var AddComment = function (_this, parent_com_id){
	if (user_login_id != '') {
		_this = $(_this);
		
		let m = $.trim(_this.siblings('textarea').val());
		if (m.length) {
			let f = new FormData();
			f.append('post_id', video_post_id);
			f.append('user_id', video_user_id);
			f.append('parent_com_id', parent_com_id);
			f.append('message', m);
			manageMyAjaxPostRequestData(f, base_url + 'player/AddComment').done(function (resp) {
				if (resp.status == 1) {
					fetchMoreComment(parent_com_id, resp.com_id);
					_this.siblings('textarea').val('');
					Custom_notify('success', resp.message);
				}
			})
		} else {
			_this.siblings('textarea').focus();
		}
	} else {
		$('#myModal').modal('show');
	}
}


/************** COMMENT SECTION END ************************/


