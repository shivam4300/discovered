$(document).ready(function(){ 
	
	var MessStart = 0;
	var MessLimit = 2;
	var Messrequest=true;
	
	$(document).on('click','.intMessage',function(){
		
		var thiss = $(this);
	
		if(thiss.hasClass('mob_hide')){
		  thiss.attr({'aria-controls':'message','role':'tab','data-toggle':'tab'}).removeClass('intMessage');
		  
		 $('li[role="presentation"]').not('.media_tab').removeClass('active');
		  $('a[href="#message"]').parents('li').addClass('active');
		}
		
		if(user_login_id != ''){	
			$('.tab-pane').not('.notab-pane').removeClass('active');
			$('#message').addClass('active');
			
			let formData = new FormData(); 
				
				formData.append('to_uid',$('#data-user_id').data('user_id'));
				formData.append('limit',MessLimit);
				formData.append('start',MessStart);
			
				
				if(Messrequest){
					
					if(thiss.hasClass('dis_btn'))
						thiss.html('Loading  <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled', true);
				
						manageMyAjaxPostRequestData(formData , base_url +  'chat/FeatchMessage').done(function(resp){
							
							if(MessStart == 0)
								$('#fetchMessage').empty();
							
							if(resp.status == 1){
								$('.removeMessBtn').show();
								
								let resData = resp.data;
								 
								$.each(resData, function(index, value) {
									$('#fetchMessage').append(value); 
								})
								
								MessStart+=MessLimit;
								
								if(thiss.hasClass('dis_btn'))
									thiss.html('Load Older Message').prop('disabled', false);
							}else{
								if(MessStart == 0)
								$('#fetchMessage').html(dataNotFound); 
							}
						}).fail(function(){
								$('.removeMessBtn').remove(); 
								Messrequest  = false;
								
								if(MessStart == 0)
								$('#fetchMessage').html(dataNotFound); 
						})  
			}
		}else{
			$('#myModal').modal('show');
		}
	})
	
	$(document).on('click','.OpenReplyBox',function(){
		let mess_id = $(this).attr('data-mess_id')
		$('#ReplyBox'+mess_id).show();
		$('#ReplyButton'+mess_id).show(); 
	})
	
	$(document).on('click','.send_message',function(){
		if(user_login_id != ''){
			let _this = $(this);
			let messa = $.trim($(".chat_message").val());
			if(messa.length){
				let formData; 
				formData = new FormData();  
				formData.append("to_uid",$('#data-user_id').data('user_id'));
				formData.append("message",messa);
				
			   _this.html('Sending  <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled', true);
				
				manageMyAjaxPostRequestData(formData , base_url + 'chat/send_message').done(function(resp){
					if(resp.status == 1){
						getMessage('prepend',resp.mess_id,formData)
					}
					$(".chat_message").val('Hello,')
					_this.html('Send Message').prop('disabled', false);
				})
			}else{
				Custom_notify('error','Message can\'t be empty.');
			}
			
		
		}else{
			$('#myModal').modal('show');
		}
	})
	$(document).on('click','.SentReplyMessage',function(){
		
		if(user_login_id != ''){
			
			let _this 	= $(this);
			let reply_mess_id = _this.attr('data-reply_mess_id');
			let to_uid = _this.attr('data-to_uid');
			
			let messa = $.trim($("#ReplyMessage"+reply_mess_id).val())
			if(messa.length){	
				
				let formData; 
					formData = new FormData();  
					formData.append("to_uid",to_uid);
					formData.append("reply_mess_id",reply_mess_id);
					formData.append("message", messa);
					
				   _this.html('Sending  <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled',true);
					
					manageMyAjaxPostRequestData(formData , base_url + 'chat/send_message').done(function(resp){
						if(resp.status == 1){
							getMessage('replace',reply_mess_id,formData)
						}
						_this.html('Send Message').prop('disabled', false);
					})
			}else{
				Custom_notify('error','Message can\'t be empty.');
			}
		}else{
			$('#myModal').modal('show');
		}
	})
	function getMessage(extend,reply_mess_id,formData){
		manageMyAjaxPostRequestData(formData , base_url +  'chat/FeatchMessage/'+reply_mess_id).done(function(resp){
			if(resp.status == 1){
				let resData = resp.data;
				$.each(resData, function(index, value) {
					if(extend == 'replace')
					$('#parent_post_messsge_'+reply_mess_id).html(value);
				
					if(extend == 'prepend')
					$('#fetchMessage').prepend(value); 
				})
			}
		})
		Custom_notify('success','Your message has been sent')
	}
	function dataNotFound(){
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
	
	
})