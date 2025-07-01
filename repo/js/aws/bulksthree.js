	function ucfirst(str){
		return str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
			return letter.toUpperCase();
		});	
	}
	
	var  bulkS3upload = {};
	
	function BulkProcessUpload(file,target,index,Key) {
		let file_name = file.name ;
		
		return new Promise((resolve, reject) => {
			SetGetToken().then(function(){
				bulkS3upload[index] = new AWS.S3.ManagedUpload({ // Use S3 ManagedUpload class as it supports multipart uploads
					params: {
					Bucket: BUCKET_NAME, 
					Key: Key,
					Body: file,
					ACL: "public-read",
					ContentType:file.type
					}
				});
				
				bulkS3upload[index].on('httpUploadProgress', function (progress) {
					let per = Math.round(progress.loaded/progress.total*100);
					target.width(per+'%');
					target.closest('.mdetails_prowrap').find('.progress.progress-value .progress-bar').width(per+'%').text(per+'%');
					// console.log('progress: ' + per , 'file_name : '+ file_name);
				});
				
				var promise = bulkS3upload[index].promise();
				promise.then(
					function(data) {
						// console.log('file_namea:' + file_name)
						return resolve(data);
					},
					function(err) {
						return reject("There was an error uploading your file: " + err.message);
					}
				);
			
			})	
		})
	}
	
	
	function BulkGenerateThumbs(file,frame_count , target , index ){
		return new Promise((resolve, reject) => {	
		let _CANVAS = document.createElement("canvas"),_CTX = _CANVAS.getContext("2d"),_VIDEO = document.createElement("VIDEO"),Blobs=[],CT=0;
			
			_VIDEO.setAttribute("src",URL.createObjectURL(file));
			_VIDEO.load();
			
			let	video_duration = 0 ;
			_VIDEO.addEventListener('loadeddata', function() { 
					video_duration = Math.floor(_VIDEO.duration);
					let point = video_duration/frame_count;
							
					_CANVAS.width 	= _VIDEO.videoWidth;
					_CANVAS.height 	= _VIDEO.videoHeight;
					// console.log('video_duration:'+ video_duration + ' index:' +index);
					logThumb = () => {
						_VIDEO.currentTime = CT;
						CT =  CT+point;
					};
					logThumb();
			});
			let j = 0
			_VIDEO.onseeked = () => {
				if(CT <= video_duration){
					_CTX.drawImage(_VIDEO, 0, 0, _VIDEO.videoWidth, _VIDEO.videoHeight);
					let image = _CANVAS.toDataURL('image/jpeg')
					Blobs.push({'video_duration': video_duration , 'image' : dataURLtoBlob(image) });
					// console.log(Blobs  , ' index:' +index );
					j++;// console.log('j:'+j + ' f:' +frame_count);
					if(j == frame_count){
						// console.log('j:'+j + ' index:' +index);
						return resolve(Blobs);
					}
					logThumb()
				}
			}
			
		})		
	}
	
	var filescount = 0 ;
	var uploadrequest = 0 ;
	
	
	$(document).on('change','#channel_bulk_upload',function(){
		if(xsend == 0){
			server_error_popup_function('Already uploading process is running.');
			return false;
		}
		
		var files = document.getElementById("channel_bulk_upload").files;
		
		if (!files.length) {
			return server_error_popup_function("Please choose a file to upload first.");
		}
		
		filescount = files.length ;
		for(let i = 0; i < filescount ; i++){
			if (files[i].type != "video/mp4" && files[i].type != "video/quicktime"  ) {
				return server_error_popup_function("Please choose a correct file format.");
			}
		}
		if (files.length > 10) {
			return server_error_popup_function("You can upload maximum 10 files at once.");
		}
		xsend = 0;
		
		for(let i = 0; i < filescount ; i++){
			let html = `<li>
						<div class="cmn_upbox_innerbody">
							<span class="montiz_details_sn">`+(i+1)+`</span>
							<div class="mdetails_data">
								<div class="mdetails_fileds">
									<div class="mdetails_inputprogrs">
										<div class="mdetails_prowrap">
											<p class="mdetails_prottl">Waiting....</p>
											<div class="progress" >
											  <div class="progress-bar ProgressBar"></div>
											</div>
											<div class="progress progress-value">
												<div class="progress-bar">0%</div>
											</div>
										</div>
										<div class="mdetails_input">
											<div class="mdetails_inputbox">
												<div class="mdetails_field input-group">
													<select class="cmn_upbox_filed dis_signup_input SelectBySimpleSelect require"  data-error="Please select mode." data-url="getGenreList" data-id="#genre`+i+`" name="mode[]">
														<option value="">Select Mode*</option>
														`+website_mode+`													
													</select>
												</div>
												<div class="mdetails_field input-group">
													<select class="cmn_upbox_filed dis_signup_input require"  name="genre[]" id="genre`+i+`" data-error="Please select genre.">
														<option value="">Select Genre*</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="mdetails_btmfield">
										<div class="row">
											<div class="col-lg-6">
												<div class="mdetails_field input-group">
													<input type="text" class="cmn_upbox_filed dis_signup_input require" name="title[]" placeholder="Video Title*" data-error="Please enter title.">
												</div>
											</div>
											<div class="col-lg-6">
												<div class="mdetails_field">
													<input type="text" class="cmn_upbox_filed dis_signup_input tokenfield" placeholder="Video Tag" name="tag[]" id="tokenfieldId`+i+`">
												</div>
											</div>
										</div>											
										<div class="mdetails_field input-group">
											<input type="text" class="cmn_upbox_filed dis_signup_input" placeholder="Video Description" name="description[]" data-error="Please enter video description.">
											<!--p class="mv_contenteditable moveHtmlContent" contenteditable="true" placeholder="Video Description" data-error="Please enter video description."></p-->

										</div>
									</div>
									<!--p class="montiz_details_other showMoreDetailOnPopup hide" data-index="`+i+`">Add/Edit Video Details (optional)</p-->
								</div>
								<div class="mdetails_thumbwrap">
									<ul class="mdetails_thumblist">
										`+loader+`
									</ul>	
								</div>	
							</div>										
						</div>
					</li>`;
			$('.cmn_upbox_multibox').append(html);
			
			let file_name = files[i].name;
			$('.mdetails_prottl').eq(i).text(file_name);
			let title = ( ucfirst( (file_name).replace(/[^A-Z0-9!|]/ig, " ") ).replace("Mp4", "")).replace(/ +(?= )/g,'');
			$('input[name="title[]"]').eq(i).val(title); 
						
		}
		$('.montiz_upld_wrap').parents('div.row').addClass('hideme');
		$('.montiz_details_prg').removeClass('hideme');
		
		uploadbulkprocess(0 , files);
		intializeTokenField();
	})
	
	function uploadbulkprocess(i , files){
		let target = $('.ProgressBar').eq(i);
		let formData= new FormData();
			formData.set('file_name',files[i].name)
			
			manageMyAjaxPostRequestData(formData , base_url +  'dashboard/create_channel_post').
			done(function(data){
			response 	= $.parseJSON(data);
			let post_id = response.post_id;
				
				target.parents('li').append('<input type="hidden" value="'+post_id+'" name="post_id[]">');
				
				
				BulkProcessUpload(files[i] , target , i , response.target ).
				then(function(data){
					
					let formData= new FormData();
						formData.set('Key', data.Key);
						formData.set('Location',data.Location)
						
					let is_safari 	= /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
					let frames 		= (is_safari) ? 1 : 3;
					
					BulkGenerateThumbs(files[i]  , frames , target , i ).then(function(response){
						for(let k=0;k < response.length;k++){
							formData.set(k, response[k].image);
							formData.set('video_duration',response[k].video_duration);
						}
						
						formData.set('post_id',post_id);
						manageMyAjaxPostRequestData(formData , base_url +  'dashboard/front_uploaded_video').
						done(function(data){
							uploadrequest++;
							if(uploadrequest < filescount){
								uploadbulkprocess(uploadrequest , files);
							}
							
							response = $.parseJSON(data);
							RenderBulkVideoThumbnails(response , i);
							$('.showMoreDetailOnPopup').eq(i).removeClass('hide').attr('data-pub_id',post_id);

						})
					});
				}).
				catch(function(err){
					 return server_error_popup_function(err);
				}) 
			}) 
	}
	
	function RenderBulkVideoThumbnails(response, i){
		
		let thumbArr 	= response.thumbs;
		let publisID 	= response.pubId;
		
		let ThumbPath 	= AMAZON_URL + 'aud_'+user_login_id	+'/images/' ;
		let Thumbnails 	= '';
		for(let k=0; k < thumbArr.length ; k++){
			
			let Image = thumbArr[k]['name'];
			let Thumb = ThumbPath + Image;
			
			if( Image.search("images") > -1)
				Thumb = Image;
			
			let ThmID = thumbArr[k]['thumb_id'];
			let ActTh = (thumbArr[k]['active_thumb'] == 1) ? 'active' : '';
			
			
				Thumbnails += `<li class="MakeThumbActive `+ActTh+`" data-pub_id="`+publisID+`">
								<div class="md_thumbimg_wrap ThumbSelect" data-thumb_id="`+ThmID+`">
									<div class="md_thumbimg">
										<img src="`+Thumb+`" class="img-responsive" alt="`+Image+`">
									</div>
									<div class="overlay">
										<span><i class="fa fa-check-circle" aria-hidden="true"></i></span>	
									</div>
								</div>
								<div class="mdetails_thumcontent">
									<h2 class="md_thumtitle">Select Thumbnail `+(k+1)+`</h2>
								</div>
							</li>`;
		}
		
		let c = (thumbArr.length < 4)? '': 'hideme'; 
		Thumbnails += `<li class="`+c+`">
						<input type="file" id="MyCustomThumb`+i+`" name="file" class="mu_upload_area MyCustomThumb" data-pub_id="`+publisID+`">
						<label class="md_thumbimg_wrap" for="MyCustomThumb`+i+`">
							<div class="md_thumbimg">
								<img src="`+base_url+`repo/images/monetize_upload.jpg" class="img-responsive" alt="image">
							</div>
						</label>
						<div class="mdetails_thumcontent">
							<h2 class="md_thumtitle">Upload Custom</h2>
						</div>
					</li>`;
		
		 $('.mdetails_thumblist').eq(i).html(Thumbnails);
	}
	
	
	$(document).on('submit' ,'.submitBulkUploadForm',async function(e){
		e.preventDefault();
		let _this = $(this) , _btn = $('.publish_btn');
		if(uploadrequest == filescount){
			let formdata = new FormData(_this[0]) ;
			console.log(formdata,'formdata')
			let loopdescriptions = Object.entries( formdata.getAll('description[]'));
			
			let descriptions = formdata.getAll('description[]');
			let titles = formdata.getAll('title[]')
			let k = 0;
			
			for await(const [i,description] of loopdescriptions ) {
				let ProfanityWords = await checkProfanityWords(descriptions[i],titles[i]);
				if(ProfanityWords.status == 0){
					Custom_notify('error',ProfanityWords.msg);
					k = 1;
				}
			}

			let checkValid = checkRequire(_this);
			if(checkValid == 0 && k == 0){
				if($('.check').is(":checked")){
						_btn.removeClass('hideme');
						manageMyAjaxPostRequestData(formdata, base_url +  $(this).attr('action')).done(function(resp){
							_btn.addClass('hideme');
							if(resp.status == 1){
								Custom_notify('success',resp.message);
								setTimeout(()=>{ window.location = resp.redurl },3000);
							}
						});
				}else{
					Custom_notify('error','please accept Discovered\'s terms and conditions.');
				}
			}
		}else{
			Custom_notify('error','Please wait for upload all videos before submit.');
		}
	})
	
	$(document).on('change','.MyCustomThumb',function(){
		let _this = $(this);
		let input = _this[0].files[0];
		if(input != 'undefined' && input != undefined){
			// let allowedExtensions =  ['image/png', 'image/jpg','image/jpeg'];
			var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
			console.log('input : '  , input);
			// var filePath 	= input.value;

			// if($.inArray( input.type , allowedExtensions )){
			if(allowedExtensions.exec(input.name)){
			
			let formData= new FormData();
				formData.set("image", input);
				formData.set('post_id',_this.attr('data-pub_id'))
				
				manageMyAjaxPostRequestData(formData , base_url +  'dashboard/upload_channel_thumb/bulk').done(function(data){
					if(data == 1){
						Custom_notify('error','Invalid file size.');
					}else{
						response = $.parseJSON(data);
						// index = (_this.attr('id')).match(/\d+/)[0] /* its return number from string*/
						index = $(".MyCustomThumb").index(_this);
						RenderBulkVideoThumbnails(response , index );							
					}
				})
			}else{
				Custom_notify('error','Please upload file having extensions .jpeg/.jpg/.png/ only.');
				return false;
			}
		}
	});
	
	
var popup_index = '';
$(document).on('click','.showMoreDetailOnPopup',function(){
	let _this = $(this);
	let pub_id 	= _this.attr('data-pub_id');
	popup_index = _this.attr('data-index');
	if(pub_id && pub_id.length){
		
		$('#MoreBulkVideoDetails').empty().load(base_url+'share/single_video/'+pub_id+'/true' ,function( response, status, xhr ) {
			
			$('.singlevideo_ads').removeClass('singlevideo_ads'); 
			$('.dis_user_post_data , .dis_sv_toptads , .dis_sv_btmtads').hide();
			$('.nav-tabs').hide();
			$('#getThumb').trigger('click');
			
			$('[name="title"]').val($("[name='title[]']").eq( popup_index).val() ) ;
			$('[name="tag"]').val( $("[name='tag[]']").eq(popup_index).val() ) ;
			$('[name="privacy_status"]').val(7) ;
			$('#age_restr').val('Unrestricted');
			
			$('#MoreDetails').modal('show');
			
			intializeTokenField('#tag');
			// InitializeCKeditor();
			$('#description').val( $("[name='description[]']").eq(popup_index).val()  ) ;
			// CKEDITOR.instances['editor'].setData( $("[name='description[]']").eq(popup_index).val() )
			
			$('#mode').val( $("[name='mode[]']").eq(popup_index).val() ).trigger('change') ;
			setTimeout(()=>{ 
				getThumb = true; 
				$('#genre').val($("[name='genre[]']").eq(popup_index).val() );
			},2000);
		})
	}
})
	
$(document).on('click',"[data-form='icon_form']" , function(){
	$("[name='title[]']").eq(popup_index).val( $('#title').val() );
	$("[name='tag[]']").eq(popup_index).val( $('[name="tag"]').val() );
	$('#tokenfieldId'+popup_index).tokenfield('setTokens',  $('[name="tag"]').val(), false, false);
	$("[name='mode[]']").eq(popup_index).val( $('#mode').val() ).trigger('change') ;
	// $("[name='description[]']").eq(popup_index).val( CKEDITOR.instances.editor.getData() );
	$("[name='description[]']").eq(popup_index).val( $('#description').val() );
	// $(".moveHtmlContent").eq(popup_index).html( $('[name="description"]').val() );
	setTimeout(()=>{ 
		$("[name='genre[]']").eq(popup_index).val( $('#genre').val() );
	},2000);
})
// $(document).on('keyup copy paste cut mouseleave mouseout','.moveHtmlContent',function(){
// 	let _this = $(this);
// 	_this.prev().val(_this.html());
// })