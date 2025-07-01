
	var bucketRegion = "us-east-1";
	var sts = '';

	var path = window.location.pathname;
	var page = path.split("/").pop();
	
	loadScript(base_url + 'repo/js/aws/aws-sdk-2.747.0.min.js', function(){ 
			AWS.config.region = bucketRegion;
			// sts = new AWS.STS({apiVersion: '2011-06-15'});
			sts = new AWS.STS({apiVersion: 'latest'});
	});
	
	function GetTokenData(url, data) {
		let formData= new FormData();
		formData = {};
		return new Promise((resolve, reject) => {
				manageMyAjaxPostRequestData(formData , url ).
				then(function(response	){ 
					response = JSON.parse(response);
					if(response.status == 1){
						return resolve(response);
					}else{
						return reject(response.message);
					}
				})
		})
	}

	function GetIdTokens(){
		return  GetTokenData(base_url + 'dashboard/GetIdTokens' )
		  .then(data => {
			return data;
		}).catch(e=>{
			console.log(e.message);
		});
	}

	function GetWebIdentity(){
		
		return  GetIdTokens().then(function(data){
			return new Promise((resolve, reject) => {
				var params = {
				  RoleArn: data.RoleArn, /* required */
				  RoleSessionName: data.RoleSessionName, /* required */
				  WebIdentityToken: data.Token, /* required */
				  DurationSeconds : data.TokenDuration
				};
				var myAwsCr = {};
				sts.assumeRoleWithWebIdentity(params, function(err, Crd) {
					if (err){
						reject(err, err.stack); // an error occurred
					}else{
						
						let obj = {
							region: bucketRegion,
							accessKeyId: Crd.Credentials.AccessKeyId,
							secretAccessKey: Crd.Credentials.SecretAccessKey,
							sessionToken : Crd.Credentials.SessionToken,
						}
						/*
						if (typeof (Storage) !== 'undefined'){
							let newObj = Object.assign(obj);
							newObj.exp_in = data.expire_in;
							localStorage.setItem('cit', JSON.stringify(newObj) );
						}
						*/
						
						resolve(obj) 
					}    
				});
				
			}) 
			
		}).catch(e=>{
			console.log(e.message);
		})
		
	}

	function SetGetToken(){
		return new Promise((resolve, reject) => {
			/*
			if (typeof (Storage) !== 'undefined'){
				let cit = localStorage.getItem('cit');
				if(cit && cit.length){
					cit = JSON.parse(cit);
					var date = new Date(cit.exp_in +' UTC');
					var diff = date - new Date();
					if(diff >= 0){
						delete cit.exp_in; 
						AWS.config.update(cit);
						return resolve();
					}
				}
			} */
			
			GetWebIdentity().then(function(Credentials){
				AWS.config.update(Credentials);
				resolve();
			}).catch(function(e){
				console.log(e.message);
				reject()
			})
		})
	}
	
	var  uploadS3 = {};
	var _progress = $('._progress_percent');
	_progress.parent().hide();
	function ProcessUpload(file,target,bucket_name = BUCKET_NAME,index=0) {
		_progress = $('._progress_percent');
		_progress.eq(index).css('opacity', '1');
		return new Promise((resolve, reject) => {
			SetGetToken().then(function(){
				uploadS3 = new AWS.S3.ManagedUpload({ // Use S3 ManagedUpload class as it supports multipart uploads
					params: {
					Bucket: bucket_name,
					Key: target,
					Body: file,
					ACL: "public-read",
					ContentType:file.type
					}
				});
				
				uploadS3.on('httpUploadProgress', function (progress) {
					let per = Math.round(progress.loaded/progress.total*100);
					console.log('httpUploadProgress',per);
					_progress.eq(index).width(per+'%').text(per+ '%');
					_progress.eq(index).parent().show();
					
				});
				
				var promise = uploadS3.promise();
				promise.then(
					function(data) {
						_progress.eq(index).parent().hide();
						return resolve(data);
					},
					function(err) {
						_progress.eq(index).parent().hide()
						return reject("There was an error uploading your file: " + err.message);
					}
				);
			
			})	
		})
	}

	function getFileSize() {
		return new Promise((resolve, reject) => {
			SetGetToken().then(async function(){
				console.log("AWS", AWS);
				
				await AWS.S3.headObject({ Key: "aud_830/chat/6og42VBgU9tfWNOvt7To.png", Bucket: "discovered.tv.transcoder.new" })
				.promise()
				.then(res => resolve(res.ContentLength));			
			})	
		})
	}
	
	$(document).on('click','._process_abort',function(){
		let text = 'Yes';
		let subtext = 'Are you sure want to cancel the upload process ?';
		if($(this).attr('data-msg')){
			subtext = $(this).attr('data-msg');
		}
		let functions = '_process_abort()';
		confirm_popup_function(text,subtext,functions)
	})
	var video_duration = 0;
	
	function _process_abort(){
		$('#confirm_popup').modal('hide');

		if(!$.isEmptyObject(uploadS3))
		uploadS3.abort();
		
		if(!$.isEmptyObject(AjaxR))
		AjaxR[0].abort();
	
		$('._progress_bar').addClass('hide');
		
		
		$('#uploadArea').show(); /* ONLY FOR BULK*/
		
		
		_progress.width('0%').text(0)
		_progress.parent().hide();
		$('#channel_video_uploads , #uploadFile , #pro_video_upload').val(''); 
		
		if(pub_btn && pub_btn.length)
		pub_btn.text("publish").attr("onclick", "publish_content('0');");
		
		xsend = 1;
		
	}
	
	$(document).on('change','#channel_video_uploads',function(){
		if(xsend == 0){
			server_error_popup_function('Already uploading process is running.');
			return false;
		}
		// for get-discovered uploader page
		if($('input[name="video_category"]').length > 0){ 
			var selectedValue = $('input[name="video_category"]:checked').val();
			if (!selectedValue) {
				$(this).val('');
				return server_error_popup_function("Please select category.");
			}
		}
		// for get-discovered uploader page

		var files 	= document.getElementById("channel_video_uploads").files;
		var file 	= files[0];
		var sizeInMb 	= (file.size / 1024 / 1024).toFixed(2);
		
		if (!files.length) {
			return server_error_popup_function("Please select a file to upload first.");
		}else
		if (file.type != "video/mp4" && file.type != "video/quicktime"  ) {
			return server_error_popup_function("Please select a correct file format.");
		}else
		if(sizeInMb > 50000){
			return server_error_popup_function("Please select a file less than 50 GB.");
		}
		

		xsend = 0;
		
		let is_safari 	= /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
		let frames 		= (is_safari) ? 1 : 3;
		
		GenerateThumbs(file,frames).then(function(ThumbResponse){
			if(window.location.href != base_url+'monetize'){
				$('#uploadArea').hide();
			}

			$('._progress_bar').removeClass('hide');
			$('._progress_title').text(file.name);
		
			let formData= new FormData();
			formData.set('file_name',file.name);

			if(page == 'getdiscovered'){ // for getdiscovered uploader page
				formData.set('upload_source','getdiscovered');
			}

			manageMyAjaxPostRequestData(formData , base_url +  'dashboard/create_channel_post').
				done(function(data){
				response 	= $.parseJSON(data);
				let post_id = response.post_id;
				
				
				
				ProcessUpload(file,response.target).
				then(function(data){
					$('._progress_title').text('Please wait....');
					
					formData.set('Key', data.Key);
					formData.set('Location',data.Location);
					formData.set('is_safari',is_safari);
					console.log('GenerateThumbs');
					
					
						for(let k=0;k < ThumbResponse.length;k++){
							formData.set(k, ThumbResponse[k]); 
						}
						
						formData.set('post_id',post_id);
						formData.set('video_duration',video_duration);
						
						manageMyAjaxPostRequestData(formData , base_url +  'dashboard/front_uploaded_video').
						done(function(data){
							response = $.parseJSON(data);
							
							let title = ( ucfirst( (file.name).replace(/[^A-Z0-9!|]/ig, " ") ).replace("Mp4", "")).replace(/ +(?= )/g,'');
							let thumbArr = {} , k = 0;
							
							$('.LoadMonetizePage').load(base_url+'dashboard/upload_channel_video/single/true/0' ,function( res , status, xhr ){
								// InitializeCKeditor();
								getPlaylist(post_id);
								$('.dis_select_video ').show()
								$('#uploadChannelVideo').hide();
								$('#VideoPostId').val(post_id);
								$('[name="privacy_status"]').val(7) ;
								$('.publish_btn').addClass('hideme');
								$('.montiz_upld_wrap').parents('div.row').addClass('hideme');

								// for get-discovered uploader page
								if(selectedValue) {
									// Append the new option to the select
									$('select[name="category"]').append(new Option(selectedValue, selectedValue));
									// Set the new option as the selected value
									$('select[name="category"]').val(selectedValue);
									//$('select[name="category"]').prop('disabled', true);
									$('select[name="category"]').closest('.form-group').parent().hide();
								}
								// for get-discovered uploader page
								
								thumbArr = response.thumbs;
								for(k ; k < thumbArr.length ; k++){
									if(thumbArr[k]['name'] !== ''){
										renderChannelThumb(thumbArr[k]['name'],thumbArr[k]['thumb_id'],user_login_id,'prepend');
									}else{
										$('#nothumberr').show().css('color','red'); 
									}
								}
								
								$("[name='title']").val(title); 
								intializeTokenField('#tag');
								initOpenAi(); //from common js
							})
						})
				
				}).catch(function(err){
					setTimeout(()=>{
						return server_error_popup_function(err);
					},500)
					
				})
			})	/* END OF CREATE CHANNEL POST */
		
		}).catch(function(err){
			xsend = 1;
			setTimeout(()=>{
				return server_error_popup_function(err);
			},500)
		});
		
	})
	
	function GenerateThumbs(file,frame_count){
		return new Promise((resolve, reject) => {	
		var _CANVAS = document.createElement("canvas"),_CTX = _CANVAS.getContext("2d"),_VIDEO = document.createElement("VIDEO"),Blobs=[],CT=0;
			
			_VIDEO.setAttribute("src",URL.createObjectURL(file));
			_VIDEO.load();
			
			console.log(navigator.userAgent);
			console.log(navigator.appVersion);
			
			_VIDEO.addEventListener('loadedmetadata', function() { 
				console.log('loadedmetadata')
					video_duration = Math.floor(_VIDEO.duration);
				let point = video_duration/frame_count;
							
					_CANVAS.width = _VIDEO.videoWidth;
					_CANVAS.height = _VIDEO.videoHeight;
					
					console.log(_VIDEO.videoWidth +'x'+ _VIDEO.videoHeight)
					
					logThumb = () => {
						_VIDEO.currentTime = CT;
						CT =  CT+point;
					};
					logThumb();
			});
		
			let j = 0
			console.log('onseeked')
			
			_VIDEO.onseeked = () => {
				console.log(' _VIDEO.videoWidth:'+ _VIDEO.videoWidth)
				console.log(' _VIDEO.videoHeight:'+ _VIDEO.videoHeight)
				if(CT <= video_duration){
					_CTX.drawImage(_VIDEO, 0, 0, _VIDEO.videoWidth, _VIDEO.videoHeight);
					let image = _CANVAS.toDataURL('image/jpeg')
					Blobs.push(dataURLtoBlob(image));
					j++;
					logThumb()
					console.log('j:'+j + ' f:' +frame_count);
					if(j == frame_count){
						console.log('j:'+j + ' f:' +frame_count);
						return resolve(Blobs);
					}
				}
			}

			setTimeout(function(){
				if(j == 0){
					return reject('There is some issue with video, Please attempt with other video clip');	
				}
			},3000)
			
			
			
		})		
	}
		
	function dataURLtoBlob(dataurl) {
		console.log(dataurl);
		var arr = dataurl.split(',');
		// console.log('array:' + arr[0].match(/:(.*?);/) );
		// var mime = arr[0].match(/:(.*?);/)[1];
		mime = 'image/jpeg';
		var bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
		
		while(n--){
			u8arr[n] = bstr.charCodeAt(n);
		}
		return new Blob([u8arr], {type:mime});
	}
	
	$(document).on('change','#pro_video_upload',function(){
		$('#upload_video_err').text('');
		if(xsend == 0){
			$('#upload_video_err').text('Already uploading process is running.');
			return false;
		}
		
		var files = document.getElementById("pro_video_upload").files;
		console.log(files);
		var file = files[0];
		var sizeInMb 	= (file.size / 1024 / 1024).toFixed(2);
		if (!files.length) {
			return $('#upload_video_err').text("Please choose a file to upload first.");
		}else
		if (file.type != "video/mp4" && file.type != "video/quicktime"  ) {
			return $('#upload_video_err').text("Please select a correct file format.");
		}else
		if(sizeInMb > 1000){
			return $('#upload_video_err').text("Please select a file less than 1 GB.");
		}

		xsend = 0;
		$('._progress_bar').removeClass('hide');
		$('._progress_title').text(file.name);
		
		let target = "aud_"+user_login_id+"/videos/";
		target = target + makeid(20) +'.'+  (file.name).split('.').pop();
		
	    let indexNum = 	($('._progress_percent').length>1) ? 1 : 0 ;

		ProcessUpload(file,target,BUCKET_NAME,indexNum).
		then(function(data){
			$('._progress_title').text('Please wait....');
			let formData= new FormData();
			formData.set('Key', data.Key);
			formData.set('Location',data.Location)
			
			manageMyAjaxPostRequestData(formData , base_url +  'dashboard/upload_profile_video').
			done(function(response){
				if (response.status == 1) {
					window.location.reload(1);
				} else {
					$("#upload_video_err").html("<span style='color:red;'>Something went wrong ! Please try again.</span>");
					return false;
				}
			})
		}).
		catch(function(err){
			 return $('#upload_video_err').text(err);
		})
	})
	
	
	
	
	$(function() {
		// preventing page from redirecting
		$(document).on("dragover drop", "html", function(e) {
			e.preventDefault();
			e.stopPropagation();
			// console.log(e);
		});
		// Drag enter  Drag over
		$(document).on('dragenter dragover','.inputfile', function (e) {
			e.stopPropagation();
			e.preventDefault();
			// console.log(e);
		});
		
		// Drop
		$(document).on('drop', '.inputfile', function (e) {
			e.stopPropagation();
			e.preventDefault();
			let id = $(this).attr('data-id');
			console.log(e.originalEvent.dataTransfer.files);
			document.getElementById(id).files = e.originalEvent.dataTransfer.files;
			$('#'+id).trigger('change');
		});

		
	});

