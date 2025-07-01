
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_html {
	private $uid;
    public function __construct()
	{
        $this->CI = get_instance();

		if(isset($this->CI->session->userdata['user_login_id']))
		$this->uid =  $this->CI->session->userdata['user_login_id'];
	}



	function content_loader_html(){
		return '<div class="dis_preloader">
					<div class="dis_status"><img src="'.CDN_BASE_URL . 'repo/images/section_loader.gif' .'" id="preloader_image" alt="loader">
					</div>
				</div>';
	}

	function content_not_available_html(){
		return '<div class="no_result_wraaper">
					<div class="no_result_inner">
						<!--img class="" src="'.CDN_BASE_URL . 'repo/images/not_found.png'.'" alt="images"-->
						<svg xmlns="http://www.w3.org/2000/svg" width="144px" height="141px" class="no_resultsvg">
						<path class="a" fill-rule="evenodd"  fill="rgb(232, 233, 234)"
						 d="M102.927,67.238 C102.868,85.522 87.999,100.297 69.715,100.239 C51.430,100.180 36.656,85.311 36.714,67.027 C36.773,48.742 51.642,33.968 69.926,34.026 C88.210,34.085 102.985,48.954 102.927,67.238 Z"/>
						<path class="b" fill-rule="evenodd"  fill="rgb(189, 194, 203)"
						 d="M142.570,51.942 L139.426,51.942 L139.426,55.084 C139.426,55.647 138.969,56.103 138.406,56.103 C137.843,56.103 137.387,55.647 137.387,55.084 L137.387,51.942 L134.243,51.942 C133.681,51.942 133.224,51.485 133.224,50.923 C133.224,50.360 133.681,49.904 134.243,49.904 L137.387,49.904 L137.387,46.761 C137.387,46.198 137.843,45.742 138.406,45.742 C138.969,45.742 139.426,46.198 139.426,46.761 L139.426,49.904 L142.570,49.904 C143.133,49.904 143.589,50.360 143.589,50.923 C143.589,51.485 143.133,51.942 142.570,51.942 ZM104.580,90.518 L136.892,123.739 C138.772,125.671 139.787,128.219 139.749,130.915 C139.711,133.611 138.624,136.130 136.689,138.009 C134.756,139.888 132.206,140.902 129.510,140.864 C126.815,140.825 124.295,139.740 122.414,137.806 L98.385,113.101 C98.384,113.100 98.382,113.098 98.381,113.097 L89.621,104.090 C84.913,106.619 79.742,108.234 74.339,108.822 C73.530,108.910 72.711,108.975 71.904,109.017 C61.657,109.542 51.580,106.270 43.533,99.805 C34.789,92.781 29.307,82.790 28.097,71.674 C26.887,60.557 30.091,49.622 37.118,40.882 C44.146,32.143 54.142,26.664 65.264,25.454 C76.387,24.245 87.326,27.447 96.070,34.471 C104.814,41.496 110.296,51.486 111.506,62.603 C112.400,70.815 110.871,79.021 107.084,86.332 C106.309,87.837 105.456,89.237 104.580,90.518 ZM126.034,134.289 C126.975,135.256 128.234,135.799 129.582,135.818 C130.929,135.837 132.204,135.330 133.171,134.390 C134.139,133.450 134.682,132.192 134.701,130.844 C134.720,129.497 134.213,128.223 133.273,127.257 L111.177,104.540 L103.939,111.573 L126.034,134.289 ZM100.411,107.945 L107.649,100.912 L101.466,94.555 C98.813,97.625 96.603,99.380 96.565,99.410 C95.738,100.098 94.887,100.750 94.015,101.369 L100.411,107.945 ZM106.621,63.134 C104.499,43.645 87.504,29.164 67.929,30.167 C67.221,30.203 66.504,30.261 65.795,30.338 C55.976,31.405 47.152,36.243 40.947,43.958 C34.743,51.674 31.914,61.328 32.982,71.142 C34.051,80.957 38.891,89.777 46.610,95.979 C54.330,102.181 63.987,105.007 73.808,103.939 C81.616,103.090 88.817,99.855 94.631,94.585 L94.665,94.554 C97.561,91.925 100.015,88.869 101.959,85.472 C105.858,78.663 107.470,70.939 106.621,63.134 ZM77.256,64.907 C76.232,64.904 75.358,64.539 74.636,63.812 C73.914,63.086 73.554,62.211 73.557,61.187 C73.560,60.164 73.926,59.291 74.652,58.569 C75.379,57.848 76.255,57.488 77.279,57.491 C78.303,57.495 79.176,57.860 79.899,58.586 C80.621,59.313 80.980,60.187 80.977,61.211 C80.974,62.235 80.609,63.107 79.882,63.829 C79.155,64.550 78.279,64.910 77.256,64.907 ZM80.400,78.271 C80.554,78.754 80.514,79.223 80.280,79.675 C80.047,80.128 79.689,80.431 79.205,80.584 C78.722,80.737 78.248,80.697 77.785,80.463 C77.322,80.230 77.014,79.862 76.862,79.360 C76.384,77.813 75.494,76.560 74.193,75.600 C72.892,74.640 71.430,74.157 69.807,74.152 C68.184,74.147 66.719,74.620 65.412,75.571 C64.105,76.523 63.207,77.770 62.719,79.315 C62.563,79.816 62.257,80.182 61.803,80.412 C61.348,80.643 60.880,80.680 60.397,80.524 C59.895,80.367 59.529,80.062 59.299,79.608 C59.068,79.154 59.031,78.685 59.187,78.203 C59.909,75.868 61.249,73.990 63.204,72.567 C65.161,71.144 67.365,70.437 69.819,70.445 C72.272,70.453 74.474,71.174 76.420,72.610 C78.367,74.045 79.694,75.932 80.400,78.271 ZM62.418,64.860 C61.394,64.856 60.521,64.491 59.798,63.765 C59.076,63.039 58.716,62.164 58.720,61.140 C58.723,60.117 59.088,59.244 59.815,58.522 C60.542,57.800 61.417,57.441 62.441,57.444 C63.465,57.447 64.338,57.812 65.060,58.539 C65.783,59.265 66.142,60.140 66.139,61.164 C66.136,62.187 65.771,63.060 65.044,63.782 C64.317,64.503 63.441,64.863 62.418,64.860 ZM87.267,12.903 C83.760,12.903 80.899,10.043 80.899,6.538 C80.899,3.026 83.760,0.173 87.267,0.173 C90.781,0.173 93.636,3.026 93.636,6.538 C93.636,10.043 90.781,12.903 87.267,12.903 ZM87.267,2.211 C84.882,2.211 82.938,4.154 82.938,6.538 C82.938,8.922 84.882,10.865 87.267,10.865 C89.653,10.865 91.597,8.922 91.597,6.538 C91.597,4.154 89.653,2.211 87.267,2.211 ZM29.003,17.857 L31.183,20.122 C31.573,20.527 31.561,21.172 31.155,21.562 C30.750,21.953 30.104,21.940 29.714,21.535 L27.534,19.270 L25.268,21.449 C24.863,21.839 24.217,21.827 23.827,21.421 C23.437,21.016 23.449,20.371 23.855,19.981 L26.120,17.802 L23.941,15.538 C23.550,15.132 23.563,14.487 23.968,14.097 C24.374,13.707 25.020,13.719 25.410,14.125 L27.589,16.389 L29.855,14.210 C30.261,13.819 30.906,13.832 31.296,14.237 C31.687,14.643 31.675,15.288 31.269,15.678 L29.003,17.857 ZM25.581,113.255 C25.676,112.700 26.202,112.327 26.758,112.421 C27.312,112.516 27.686,113.043 27.591,113.597 L27.063,116.694 L30.162,117.222 C30.717,117.318 31.090,117.844 30.996,118.398 C30.901,118.953 30.375,119.326 29.820,119.232 L26.720,118.704 L26.192,121.801 C26.097,122.356 25.571,122.729 25.015,122.634 C24.824,122.601 24.653,122.517 24.517,122.397 C24.258,122.172 24.120,121.821 24.182,121.459 L24.710,118.361 L21.611,117.833 C21.419,117.800 21.249,117.716 21.112,117.596 C20.854,117.370 20.716,117.020 20.777,116.657 C20.872,116.102 21.398,115.730 21.954,115.824 L25.053,116.352 L25.581,113.255 ZM10.330,73.926 C8.941,74.905 7.257,75.292 5.576,75.005 C4.407,74.804 3.337,74.293 2.467,73.532 C2.089,73.201 1.746,72.829 1.453,72.411 C0.469,71.028 0.086,69.339 0.369,67.664 C0.578,66.466 1.100,65.406 1.847,64.552 C3.261,62.936 5.452,62.072 7.718,62.455 C11.177,63.052 13.517,66.342 12.925,69.805 C12.637,71.475 11.714,72.943 10.330,73.926 ZM7.375,64.466 C5.022,64.070 2.788,65.654 2.381,68.006 C1.980,70.354 3.565,72.597 5.923,72.998 C7.063,73.191 8.207,72.927 9.149,72.262 C10.097,71.592 10.719,70.602 10.913,69.463 C11.314,67.106 9.729,64.872 7.375,64.466 Z"/>
						</svg>
						<p>No Results Found.</p>
					</div>
				</div>';
	}

	function content_not_available_html_invitelink(){
		return '<div class="no_result_wraaper no_invite">
					<div class="no_result_inner">
						<!--img class="" src="'.CDN_BASE_URL . 'repo/images/not_found.png'.'" alt="images"-->
						<svg xmlns="http://www.w3.org/2000/svg" width="144px" height="141px" class="no_resultsvg">
						<path class="a" fill-rule="evenodd"  fill="rgb(232, 233, 234)"
						 d="M102.927,67.238 C102.868,85.522 87.999,100.297 69.715,100.239 C51.430,100.180 36.656,85.311 36.714,67.027 C36.773,48.742 51.642,33.968 69.926,34.026 C88.210,34.085 102.985,48.954 102.927,67.238 Z"/>
						<path class="b" fill-rule="evenodd"  fill="rgb(189, 194, 203)"
						 d="M142.570,51.942 L139.426,51.942 L139.426,55.084 C139.426,55.647 138.969,56.103 138.406,56.103 C137.843,56.103 137.387,55.647 137.387,55.084 L137.387,51.942 L134.243,51.942 C133.681,51.942 133.224,51.485 133.224,50.923 C133.224,50.360 133.681,49.904 134.243,49.904 L137.387,49.904 L137.387,46.761 C137.387,46.198 137.843,45.742 138.406,45.742 C138.969,45.742 139.426,46.198 139.426,46.761 L139.426,49.904 L142.570,49.904 C143.133,49.904 143.589,50.360 143.589,50.923 C143.589,51.485 143.133,51.942 142.570,51.942 ZM104.580,90.518 L136.892,123.739 C138.772,125.671 139.787,128.219 139.749,130.915 C139.711,133.611 138.624,136.130 136.689,138.009 C134.756,139.888 132.206,140.902 129.510,140.864 C126.815,140.825 124.295,139.740 122.414,137.806 L98.385,113.101 C98.384,113.100 98.382,113.098 98.381,113.097 L89.621,104.090 C84.913,106.619 79.742,108.234 74.339,108.822 C73.530,108.910 72.711,108.975 71.904,109.017 C61.657,109.542 51.580,106.270 43.533,99.805 C34.789,92.781 29.307,82.790 28.097,71.674 C26.887,60.557 30.091,49.622 37.118,40.882 C44.146,32.143 54.142,26.664 65.264,25.454 C76.387,24.245 87.326,27.447 96.070,34.471 C104.814,41.496 110.296,51.486 111.506,62.603 C112.400,70.815 110.871,79.021 107.084,86.332 C106.309,87.837 105.456,89.237 104.580,90.518 ZM126.034,134.289 C126.975,135.256 128.234,135.799 129.582,135.818 C130.929,135.837 132.204,135.330 133.171,134.390 C134.139,133.450 134.682,132.192 134.701,130.844 C134.720,129.497 134.213,128.223 133.273,127.257 L111.177,104.540 L103.939,111.573 L126.034,134.289 ZM100.411,107.945 L107.649,100.912 L101.466,94.555 C98.813,97.625 96.603,99.380 96.565,99.410 C95.738,100.098 94.887,100.750 94.015,101.369 L100.411,107.945 ZM106.621,63.134 C104.499,43.645 87.504,29.164 67.929,30.167 C67.221,30.203 66.504,30.261 65.795,30.338 C55.976,31.405 47.152,36.243 40.947,43.958 C34.743,51.674 31.914,61.328 32.982,71.142 C34.051,80.957 38.891,89.777 46.610,95.979 C54.330,102.181 63.987,105.007 73.808,103.939 C81.616,103.090 88.817,99.855 94.631,94.585 L94.665,94.554 C97.561,91.925 100.015,88.869 101.959,85.472 C105.858,78.663 107.470,70.939 106.621,63.134 ZM77.256,64.907 C76.232,64.904 75.358,64.539 74.636,63.812 C73.914,63.086 73.554,62.211 73.557,61.187 C73.560,60.164 73.926,59.291 74.652,58.569 C75.379,57.848 76.255,57.488 77.279,57.491 C78.303,57.495 79.176,57.860 79.899,58.586 C80.621,59.313 80.980,60.187 80.977,61.211 C80.974,62.235 80.609,63.107 79.882,63.829 C79.155,64.550 78.279,64.910 77.256,64.907 ZM80.400,78.271 C80.554,78.754 80.514,79.223 80.280,79.675 C80.047,80.128 79.689,80.431 79.205,80.584 C78.722,80.737 78.248,80.697 77.785,80.463 C77.322,80.230 77.014,79.862 76.862,79.360 C76.384,77.813 75.494,76.560 74.193,75.600 C72.892,74.640 71.430,74.157 69.807,74.152 C68.184,74.147 66.719,74.620 65.412,75.571 C64.105,76.523 63.207,77.770 62.719,79.315 C62.563,79.816 62.257,80.182 61.803,80.412 C61.348,80.643 60.880,80.680 60.397,80.524 C59.895,80.367 59.529,80.062 59.299,79.608 C59.068,79.154 59.031,78.685 59.187,78.203 C59.909,75.868 61.249,73.990 63.204,72.567 C65.161,71.144 67.365,70.437 69.819,70.445 C72.272,70.453 74.474,71.174 76.420,72.610 C78.367,74.045 79.694,75.932 80.400,78.271 ZM62.418,64.860 C61.394,64.856 60.521,64.491 59.798,63.765 C59.076,63.039 58.716,62.164 58.720,61.140 C58.723,60.117 59.088,59.244 59.815,58.522 C60.542,57.800 61.417,57.441 62.441,57.444 C63.465,57.447 64.338,57.812 65.060,58.539 C65.783,59.265 66.142,60.140 66.139,61.164 C66.136,62.187 65.771,63.060 65.044,63.782 C64.317,64.503 63.441,64.863 62.418,64.860 ZM87.267,12.903 C83.760,12.903 80.899,10.043 80.899,6.538 C80.899,3.026 83.760,0.173 87.267,0.173 C90.781,0.173 93.636,3.026 93.636,6.538 C93.636,10.043 90.781,12.903 87.267,12.903 ZM87.267,2.211 C84.882,2.211 82.938,4.154 82.938,6.538 C82.938,8.922 84.882,10.865 87.267,10.865 C89.653,10.865 91.597,8.922 91.597,6.538 C91.597,4.154 89.653,2.211 87.267,2.211 ZM29.003,17.857 L31.183,20.122 C31.573,20.527 31.561,21.172 31.155,21.562 C30.750,21.953 30.104,21.940 29.714,21.535 L27.534,19.270 L25.268,21.449 C24.863,21.839 24.217,21.827 23.827,21.421 C23.437,21.016 23.449,20.371 23.855,19.981 L26.120,17.802 L23.941,15.538 C23.550,15.132 23.563,14.487 23.968,14.097 C24.374,13.707 25.020,13.719 25.410,14.125 L27.589,16.389 L29.855,14.210 C30.261,13.819 30.906,13.832 31.296,14.237 C31.687,14.643 31.675,15.288 31.269,15.678 L29.003,17.857 ZM25.581,113.255 C25.676,112.700 26.202,112.327 26.758,112.421 C27.312,112.516 27.686,113.043 27.591,113.597 L27.063,116.694 L30.162,117.222 C30.717,117.318 31.090,117.844 30.996,118.398 C30.901,118.953 30.375,119.326 29.820,119.232 L26.720,118.704 L26.192,121.801 C26.097,122.356 25.571,122.729 25.015,122.634 C24.824,122.601 24.653,122.517 24.517,122.397 C24.258,122.172 24.120,121.821 24.182,121.459 L24.710,118.361 L21.611,117.833 C21.419,117.800 21.249,117.716 21.112,117.596 C20.854,117.370 20.716,117.020 20.777,116.657 C20.872,116.102 21.398,115.730 21.954,115.824 L25.053,116.352 L25.581,113.255 ZM10.330,73.926 C8.941,74.905 7.257,75.292 5.576,75.005 C4.407,74.804 3.337,74.293 2.467,73.532 C2.089,73.201 1.746,72.829 1.453,72.411 C0.469,71.028 0.086,69.339 0.369,67.664 C0.578,66.466 1.100,65.406 1.847,64.552 C3.261,62.936 5.452,62.072 7.718,62.455 C11.177,63.052 13.517,66.342 12.925,69.805 C12.637,71.475 11.714,72.943 10.330,73.926 ZM7.375,64.466 C5.022,64.070 2.788,65.654 2.381,68.006 C1.980,70.354 3.565,72.597 5.923,72.998 C7.063,73.191 8.207,72.927 9.149,72.262 C10.097,71.592 10.719,70.602 10.913,69.463 C11.314,67.106 9.729,64.872 7.375,64.466 Z"/>
						</svg>
						<p>You have not invited any user yet.</p>
						<span class="dis_noin">Start inviting people to Discovered!</span>
					</div>
				</div>';
	}

	function pro_loader(){
		return '<div class="dis_loadmore_loader">
					<img class="pro_loader" data-load="1" style="display:none;" src="'.base_url('repo/images/Rolling-1s-200px.gif').'">
				</div>	';
	}

	function swiper_slider_new($content,$area='outer',$queen_world=false){  /*inner,outer*/
		$autoplay=array("2000","2500","3000");
		$random_keys=array_rand($autoplay,1);
		$auto = $autoplay[$random_keys];
		$inner = '';
		$errimg	= thumb_default_image();

		foreach($content  as $video ){
			$image_name	=	$video['image_name'];
			$user_id	=	$video['user_id'];
			$post_id	=	$video['post_id'];
			$iva_id		=	$video['iva_id'];
			//$slug		=	urlencode($video['slug']);
			$full_title	=	$video['title'];
			$title		=  (strlen($full_title)< 20)?$full_title:substr($full_title,0,20)."...";


			$FilterData = $this->CI->share_url_encryption->FilterIva($user_id,$iva_id,$image_name,'',true);
			$img 	= 	$FilterData['thumb'];
			$webp 	= 	isset($FilterData['webp'])?$FilterData['webp']:$img;

			$is_session_uid = (is_session_uid($user_id))?1:0;

			$href = base_url().$this->CI->common->generate_single_content_url_param($video['post_key'] , 2);

			$onclick = '';
			if(!isset($_POST['FromSinglePage'])){
				$onclick = '<ul class="dis_cardS_oplist">
								<li>
									<div class="dis_sld_preview" onclick="openVideoDiscription('.$post_id.','.$is_session_uid.')">
										<span class="preview_txt">Preview</span>
										<span class="pre_icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
											<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
											</svg>
										</span>
									</div>
								</li>
							</ul>';
			}

			/*Temporary*/
			// if($queen_world == true){
				// $inner .= $this->sliderInnerSectionForQueen();
			// }

			$inner .= '<div class="swiper-slide" data-post_delete_id="'.$post_id.'">
				<div class="dis_post_video_data">
					<div class="dis_postvideo_img">
					<img src="'.$webp.'" class="img-responsive" alt="Discovered" onError="ImageOnLoadError(this,\''.$img.'\',\''.$errimg.'\')">
						<div class="dis_overlay">
							<div class="dis_overlay_inner">
								<a href="'. $href  .'"class="ch1">
									<img src="'.base_url('repo/images/play_icon.png').'" alt="" class="img-responsive">
								</a>
							</div>
						</div>

					</div>

					'.$onclick.'

					<div class="dis_postvideo_content">
						<h3><a href="'.$href .'" title="'.$full_title.'">'. strtolower($title).'</a></h3>
					</div>
				</div>
			</div>';

		/*Temporary*/
		// if($queen_world == true){
			// $inner .= $this->sliderInnerSectionForQueen();
		// }



		}

		if($inner != ''){
			$outer = '<div class="au_artist_slider" data-autoplay="'.$auto.'">
						<div class="swiper-container">
							'.$inner.'
						</div>
					</div>';
		}else{
			// $outer = '<h3 class="text-center">Content not available.</h3>';
			$outer = $this->content_not_available_html();
		}


		return ($area == 'inner')?$inner:$outer;
	}
	function swiper_slider($content,$area='outer',$queen_world=false){  /*inner,outer*/
		$autoplay=array("2000","2500","3000");
		$random_keys=array_rand($autoplay,1);
		$auto = $autoplay[$random_keys];
		$inner = '';
		$errimg	= thumb_default_image();

		foreach($content  as $video ){
			$image_name	=	$video['image_name'];
			$user_id	=	$video['user_id'];
			$post_id	=	$video['post_id'];
			$iva_id		=	$video['iva_id'];
			//$slug		=	urlencode($video['slug']);
			$full_title	=	$video['title'];
			$title		=  (strlen($full_title)< 20)?$full_title:substr($full_title,0,20)."...";
			$uploaded_video 	= 	isset($video['uploaded_video'])?$video['uploaded_video']:'';
			$is_vid_processed 	= 	isset($video['is_video_processed'])?$video['is_video_processed']:0;
			$video_type 		= 	isset($video['video_type'])?$video['video_type']:'';
			$video_duration	= isset($video['video_duration'])?$video['video_duration']:0;

			$FilterData = $this->CI->share_url_encryption->FilterIva($user_id,$iva_id,$image_name,trim($uploaded_video),true,'.m3u8',$is_vid_processed);
			$img 	= 	$FilterData['thumb'];
			$webp 	= 	isset($FilterData['webp'])?$FilterData['webp']:$img;

			$previewFile='';
			if(isset($FilterData['video'])){
				$videoFile 	 = $FilterData['video'];
				$previewFile = $this->CI->share_url_encryption->getPreviewFile($videoFile,$video_type);
			}
			$is_session_uid = (is_session_uid($user_id))?1:0;

			$href = base_url().$this->CI->common->generate_single_content_url_param($video['post_key'] , 2);

			$onclick = '';
			if(!isset($_POST['FromSinglePage'])){
				$onclick = '<ul class="dis_cardS_oplist">
								<li>
									<div class="dis_sld_preview openModalPopup" data-href="modal/video_popup/'.$post_id.'" data-cls="dis_custom_video_popup">
										<span class="preview_txt">Preview</span>
										<span class="pre_icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
											<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
											</svg>
										</span>
									</div>
								</li>
							</ul>';
			}

			/*Temporary*/
			// if($queen_world == true){
				// $inner .= $this->sliderInnerSectionForQueen();
			// }

			$inner .= '<div class="swiper-slide play_preview_common" data-post_delete_id="'.$post_id.'" data-preview-src="'.$previewFile.'">
				<div class="dis_post_video_data">
					<div class="dis_postvideo_img">
					<img src="'.$webp.'" class="img-responsive" alt="Discovered" onError="ImageOnLoadError(this,\''.$img.'\',\''.$errimg.'\')">
						<div class="dis_previewvideo">
							<video autoplay muted loop playsinline preload="metadata" width="100%" height="100%">
								<source src="" type="video/mp4">
							</video>
						</div>
						<div class="dis_overlay">
							<div class="dis_overlay_inner">
								<a  href="'. $href  .'"class="ch2">
									<img src="'.base_url('repo/images/play_icon.png').'" alt="" class="img-responsive temporary_hide">
								</a>
							</div>
						</div>
						<span class="dis_videotime">'.gmdate("H:i:s", $video_duration).'</span>
					</div>

					'.$onclick.'

					<div class="dis_postvideo_content">
						<h3><a href="'.$href .'" title="'.$full_title.'">'. strtolower($title).'</a></h3>
					</div>
				</div>
			</div>';

		/*Temporary*/
		// if($queen_world == true){
			// $inner .= $this->sliderInnerSectionForQueen();
		// }



		}

		if($inner != ''){
			$outer = '<div class="au_artist_slider" data-autoplay="'.$auto.'">
						<div class="swiper-container">
							<div class="swiper-wrapper">
								'.$inner.'
							</div>
							<div class="swiper-button-next cs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
							<div class="swiper-button-prev cs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
						</div>
					</div>';
		}else{
			// $outer = '<h3 class="text-center">Content not available.</h3>';
			$outer = $this->content_not_available_html();
		}


		return ($area == 'inner')?$inner:$outer;
	}

	function swiper_slider_without_html($content,$watch = [], $playlist_id = ''){  /*inner,outer*/
		$this->CI->config->load('foreign_chars');

		$errimg			= 	thumb_default_image();
		$array			=	[];

		$f 				= $this->CI->config->item('foreign_characters');

		$playlist_id 	= is_array($playlist_id) ? $playlist_id : [$playlist_id];

		foreach($content  as $key => $video ){

			$image_uid  = $video['user_id'];
			$image_name = $video['image_name'];

			if(isset($video['playlist_thumb']) && $video['playlist_thumb'] !=''){
				$image_uid  = $video['playlist_user_id'];
				$image_name = $video['playlist_thumb'];
			}

			$user_id			=	$video['user_id'];
			$post_id			=	$video['post_id'];
			$iva_id				=	$video['iva_id'];
			$full_title			=	checkForeignChar(utf8_encode($video['title']));
			$title				=   (strlen($full_title)< 30)?$full_title:substr($full_title,0,30)."...";
			$video_duration 	=   $video['video_duration'];
			$mode_name  		=   isset($video['mode'])?ucfirst($video['mode']):'';
			$created_at 		=   isset($video['created_at'])?date('d M Y' , strtotime($video['created_at'])) : '';
			$uploaded_video 	= 	isset($video['uploaded_video'])?$video['uploaded_video']:'';
			$is_vid_processed 	= 	isset($video['is_video_processed'])?$video['is_video_processed']:0;
			$video_type 		= 	isset($video['video_type'])?$video['video_type']:'';
			$schedule_time      =  	isset($video['schedule_time'])? date('d-M-Y H:i' , strtotime($video['schedule_time'])):'';

			$FilterData 		=  	$this->CI->share_url_encryption->FilterIva($image_uid,$iva_id,$image_name,trim($uploaded_video),true,'.m3u8',$is_vid_processed);
			$img 				= 	$FilterData['thumb'];
			$webp 				= 	isset($FilterData['webp'])?$FilterData['webp']:$img;

			$previewFile='';
			if(isset($FilterData['video'])){
				$videoFile 	= $FilterData['video'];
				$previewFile=$this->CI->share_url_encryption->getPreviewFile($videoFile,$video_type);
			}

			$is_session_uid = (is_session_uid($user_id))?1:0;

			$href = base_url().$this->CI->common->generate_single_content_url_param($video['post_key'] , 2);



			$PlaylistUrl='';
			$plid = '';
			if(!empty($playlist_id)){
				$plid = (sizeof($playlist_id) > 1) ? isset($playlist_id[$key])?$playlist_id[$key]:'' : $playlist_id[0];
				$PlaylistUrl = $this->CI->share_url_encryption->share_single_page_link_creator(2 .'|'.$post_id,'encode','',array('list'=> $plid ));
			}

			$currentTime=0;
			$progressBarPer=0;
			if(!empty($watch)){
				$watch_key = $this->searchForId($post_id, $watch);
				if($watch_key !==null){
					$currentTime = round($watch[$watch_key]['time']);
					$plid 	 	 = isset($watch[$watch_key]['plist_id']) ? $watch[$watch_key]['plist_id'] : '';
					$PlaylistUrl = $this->CI->share_url_encryption->share_single_page_link_creator(2 .'|'.$post_id,'encode','',array('list'=> $plid ));
				}

				if($currentTime>0 && $video_duration>0){
					$progressBarPer = round($currentTime/$video_duration*100);
				}
				$href .='?t='.$currentTime.'';
				$PlaylistUrl .='?t='.$currentTime.'';
			}


			$full_title = preg_replace(array_keys($f), array_values($f), $full_title);
			$title 		= preg_replace(array_keys($f), array_values($f), $title);

			$video_ids_count = 0;
			if(isset($video['video_ids']) && !empty($video['video_ids'])){
				$video_ids_count = sizeof(explode('|',$video['video_ids'])) - 1;
			}

			$array[] = array(
				'is_session_uid'=>$is_session_uid,
				'post_id'		=>$post_id,
				'full_title'	=>$full_title,
				'title'			=>$title,
				'img'			=>$img,
				'webp'			=>$webp,
				'errimg'		=>$errimg,
				'play_icon' 	=>CDN_BASE_URL . 'repo/images/play_icon.png',
				'url'			=>$href,
				'is_session_uid'=>$is_session_uid,
				'video_duration'=>$video_duration,
				'currentTime'	=>$currentTime,
				'progressBarPer'=>$progressBarPer,
				'mode_name'		=>$mode_name,
				'created_at'	=>$created_at,
				'PlaylistUrl' 	=>$PlaylistUrl,
				'previewFile'	=>$previewFile,
				'schedule_time' =>$schedule_time,
				'playlist_id'	=>$plid,
				'video_ids_count'=>$video_ids_count
			);

		}
		return $array;
	}

	function searchForId($id, $array) {
		foreach ($array as $key => $val) {
		   if ($val['vid'] === $id) {
			   return $key;
		   }
		}
		return null;
	}


	function sliderInnerSectionForQueen(){
		$queen ='';
		for($i=0;$i<1;$i++){
				$queen .= '<div class="swiper-slide">
						<div class="dis_post_video_data">
							<div class="dis_postvideo_img">
							<img src="'.CDN_BASE_URL.'repo/images/queen_world.jpg" class="img-responsive" alt="QUEENS WORLD">
								<div class="dis_overlay">
									<div class="dis_overlay_inner">
										<a  href="" class="ch3">
											<img src="'.CDN_BASE_URL.'repo/images/play_icon.png" alt="" class="img-responsive">
										</a>
									</div>
								</div>

							</div>
							<div class="dis_postvideo_content">
								<h3><a>To Be Announced</a></h3>
							</div>
						</div>
					</div>';
		}
		return $queen;

	}

	function au_video_popup($cover_video =array(),$defaultVideo=''){
		$vidurl = isset($cover_video['url']) && !empty($cover_video['url']) ? $cover_video['url'] : AMAZON_URL.$defaultVideo;
		$str = '<div class="au_video_popup">
				<div class="au_popup_body">
				   <a class="speaker mute" data-video="popup_banner_video">
						<span></span>
				   </a>
					<video  id="popup_BGvideo" loop muted  class="banner_video popup_banner_video" >
						<source src="'.$vidurl.'">
					</video>

					<div class="close_btn" id="cover_banner_video"></div>
					<a class="au_tv_logo"><img src="'.CDN_BASE_URL . 'repo/images/logo_on_video.png'.'" alt="Discovered"></a>

					</div>
					</div>';
			return $str	;
	}
	function au_banner_section($cover_video = array(),$i = 1){
		$url 		= isset($cover_video['preview'])?$cover_video['preview']: (  isset($cover_video['url'])?$cover_video['url'] :'' ) ;
		$hrf 		= isset($cover_video['post_key'])?$cover_video['post_key']:'javascript:;';
		$post_id 	= isset($cover_video['post_id'])?$cover_video['post_id']:'';
		$title 		= isset($cover_video['title'])?$cover_video['title']:'';
		$link 		= isset($cover_video['AdsBtn']['link'])?$cover_video['AdsBtn']['link']:'';
		$btnNam 	= isset($cover_video['AdsBtn']['btn'])?$cover_video['AdsBtn']['btn']:'';
		$subtitle 	= isset($cover_video['AdsBtn']['subtitle'])?$cover_video['AdsBtn']['subtitle']:'';
		$adsBtn = '';
		if(!empty($link)){
			$adsBtn = '<a href="'.$link.'" class="dis_btn dis_featuredSlider_btn">'.$btnNam.'</a>';
		}
		if(!empty($subtitle)) $subtitle = '<div ><h2 class="dis_bner_heading muli_font">'. $subtitle .'</h2></div>';

		$str = '<div class="au_banner_section">
					<div class="dis_main_video">
						<div class="Flexible-container">
							<a  class="speaker mute" data-video="cover_banner_video_'.$post_id.'">
								<span></span>
							</a>
							<video preload="none" loop muted playsinline class="video-stream html5-main-video banner_video cover_banner_video_'.$post_id.'">
								<source src="" type="video/mp4" data-src="'.$url.'">
							</video>
						</div>
						<div class="au_banner_content">
							<div class="">
							<a href="'.$hrf.'"  class="play_cover_video">
								<img src="'.CDN_BASE_URL.'repo/images/mini_logo.webp" onError="this.onerror=null;this.src=\''.CDN_BASE_URL.'repo/images/mini_logo.png\';" width="120" height="147">
							</a>
							</div>
							<div class="">
							'.$subtitle.'
							<a class="openModalPopup" data-href="modal/video_popup/'.$post_id.'" data-cls="dis_custom_video_popup"><h1>'.$title.'</h1></a>
							</div>
							'.$adsBtn.'
						</div>
					</div>
				</div>';
		return 	$str;
	}

	function loadModeVideo($content,$thumb=true,$routePlayer=false){
		$inner = '';
		$errimg	= thumb_default_image();
		foreach($content  as $video ){
			$image_name	=	$video['image_name'];
			$user_id	=	$video['user_id'];
			$post_id	=	$video['post_id'];
			$iva_id		=	$video['iva_id'];
			$post_key	=	$video['post_key'];
			$video_duration	= isset($video['video_duration'])?$video['video_duration']:0;
			// $slug		=	urlencode($video['slug']);
			$full_title		=	$video['title'];
			$title		=  (strlen($full_title)< 20)?$full_title:substr($full_title,0,20)."...";
			$uploaded_video 	= 	isset($video['uploaded_video'])?$video['uploaded_video']:'';
			$is_vid_processed = 	isset($video['is_video_processed'])?$video['is_video_processed']:0;
			$video_type 		= 	isset($video['video_type'])?$video['video_type']:'';

			$FilterData = $this->CI->share_url_encryption->FilterIva($user_id,$iva_id,$image_name,trim($uploaded_video),$thumb,'.m3u8',$is_vid_processed);
			$img 	= 	$FilterData['thumb'];
			$webp 	= 	isset($FilterData['webp'])?$FilterData['webp']:$img;

			$previewFile='';
			if(isset($FilterData['video'])){
				$videoFile 	= $FilterData['video'];
				$previewFile=$this->CI->share_url_encryption->getPreviewFile($videoFile,$video_type);
			}

			$href = base_url($this->CI->common->generate_single_content_url_param($post_key, 2));

			$is_session_uid = (is_session_uid($user_id))?1:0;

			$onclick = '';
			//if(!isset($_POST['FromSinglePage'])){
				$onclick = '<!--div class="dis_sld_preview" onclick="openVideoDiscription('.$post_id.','.$is_session_uid.')"-->
								<ul class="dis_cardS_oplist">
								<li>
									<div class="dis_sld_preview openModalPopup" data-href="modal/video_popup/'.$post_id.'" data-cls="dis_custom_video_popup">
										<span class="preview_txt">Preview</span>
										<span class="pre_icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
											<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
											</svg>
										</span>
									</div>
								</li>
							</ul>';
			//}


			$class="text-center play_preview_common";

			if($routePlayer){
				$playbutton = '<a href="javascript:;" href_post_key="'.$href.'" href_post_id="'.base_url('embedcv/'.$post_id).'" class="dis_seeAll_loadmore_a RoutePlayer"><img class="temporary_hide" src="'.CDN_BASE_URL . 'repo/images/play_icon.png'.'"></a>';
			}else{
				$playbutton = '<a href="'.$href.'" class="dis_seeAll_loadmore_a "><img class="temporary_hide" src="'.CDN_BASE_URL . 'repo/images/play_icon.png'.'"></a>';
			}

			$inner .= '<div class="'.$class.'" data-post_delete_id="'.$post_id.'" data-preview-src="'.$previewFile.'">
							<div class="dis_post_video_data dis_cardS_oplistWrap">
								<div class="dis_postvideo_img">

									<img src="'.$webp.'" class="img-responsive" alt="Discovered" onError="ImageOnLoadError(this,\''.$img.'\',\''.$errimg.'\')">
									<div class="dis_previewvideo">
										<video autoplay muted loop playsinline preload="metadata" width="100%" height="100%">
											<source src="" type="video/mp4">
										</video>
									</div>

									<div class="dis_overlay loadmore_testing1">
										'.$playbutton.'
									</div>
									<span class="dis_videotime">'.gmdate("H:i:s", $video_duration).'</span>
								</div>
								'. $onclick .'
								<div class="dis_postvideo_content">
									<h3><a href="'.$href .'" title="'.$full_title.'">'. strtolower($title).'</a></h3>
									<!--h3 data-toggle="modal" onclick="openVideoDiscription('.$post_id.','.$is_session_uid.')">'. $title .'</h3-->
								</div>
							</div>
						</div>';
		}
		return  $inner;
	}

	function loadModeVideoNew($content,$thumb=true){
		$data = [];
		$inner = '';
		$errimg	= thumb_default_image();
		foreach($content  as $key => $video ){
			$image_name		=	$video['image_name'];
			$user_id		=	$video['user_id'];
			$post_id		=	$video['post_id'];

			$iva_id			=	$video['iva_id'];
			$post_key		=	$video['post_key'];
			$video_duration	= 	isset($video['video_duration'])?$video['video_duration']:0;
			$full_title		=	$video['title'];
			$title			=  (strlen($full_title)< 20)?$full_title:substr($full_title,0,20)."...";

			$data[$key]['post_id'] = $post_id;
			$data[$key]['video'] = $video;
			$data[$key]['video_duration'] = $video_duration;
			$data[$key]['full_title'] = $full_title;
			$data[$key]['title'] = $title;

			$uploaded_video = 	isset($video['uploaded_video'])?$video['uploaded_video']:'';
			$video_type 	= 	isset($video['video_type'])?$video['video_type']:'';
			$is_vid_processed = 	isset($video['is_video_processed'])?$video['is_video_processed']:0;

			$FilterData = $this->CI->share_url_encryption->FilterIva($user_id,$iva_id,$image_name,trim($uploaded_video),$thumb,'.m3u8',$is_vid_processed);
			$img 	= 	$FilterData['thumb'];
			$webp 	= 	isset($FilterData['webp'])?$FilterData['webp']:$img;

			$data[$key]['img'] = $img;
			$data[$key]['poster'] = $webp;

			$previewFile='';
			if(isset($FilterData['video'])){
				$videoFile 	= $FilterData['video'];
				$previewFile=$this->CI->share_url_encryption->getPreviewFile($videoFile,$video_type);
			}
			$data[$key]['previewFile'] = $previewFile;

			$href = base_url($this->CI->common->generate_single_content_url_param($post_key, 2));
			$data[$key]['href'] = $href;

			$data[$key]['is_session_uid'] = (is_session_uid($user_id))?1:0;


			$data[$key]['class'] = "text-center play_preview_common";
			$data[$key]['errimg'] = $errimg;

		}
		return  $data;
	}

	/*	USER LEVEL| 1= 'ICON' ,2='EMERGING',3='BRAND',4='FAN'	*/

	function WhomIFollow($user_id,$user_level,$limit,$start){

		$IFollows = $this->CI->DatabaseModel->select_data('become_a_fan.user_id,users.user_name,users.user_uname',' become_a_fan', array('become_a_fan.following_id'=>$user_id,'user_level'=>$user_level,'users.user_status'=> 1),array($limit,$start),array('users','users.user_id = become_a_fan.user_id'),['become_a_fan.fan_id','DESC']);

		$fan = '';
		if(isset($IFollows[0])){
			$fan = '<ul class="sidebar_widget_list">';
				foreach($IFollows as $IFollow){
					$fan .= '<li>
							   <div class="dis_fan_imgdiv">
								  <img src="'.get_user_image($IFollow['user_id']).'" class="img-reponsive" alt="" onError="this.onerror=null;this.src=\''.user_default_image().'\';">
								  <div class="overlay"></div>
							   </div>
							   <a href="'.base_url('profile?user='.$IFollow['user_uname'].'').'"><h3>'.$IFollow['user_name'].'</h3></a>
							</li>';
				}
			$fan .= '</ul>';
		}

		return $fan;
	}
	function WhoFollowMe($user_id,$user_level,$limit,$start){

		$FollowMe = $this->CI->DatabaseModel->select_data('become_a_fan.following_id,become_a_fan.user_id,users.user_name,users.user_uname',' become_a_fan', array('become_a_fan.user_id'=>$user_id,'user_level'=>$user_level,'users.user_status'=> 1),array($limit,$start),array('users','users.user_id = become_a_fan.following_id'),['become_a_fan.fan_id','DESC']);

		$fan = '';
		if(isset($FollowMe[0])){
			$fan = '<ul class="sidebar_widget_list">';
			foreach($FollowMe as $Follow){
				$fan .= '<li>
						   <div class="dis_fan_imgdiv">
							  <img src="'.get_user_image($Follow['following_id']).'" class="img-reponsive" alt="" onError="this.onerror=null;this.src=\''.user_default_image().'\';">
							  <div class="overlay"></div>
						   </div>
						   <a href="'.base_url('profile?user='.$Follow['user_uname'].'').'"><h3>'.$Follow['user_name'].'</h3></a>
						</li>';
			}
			$fan .= '</ul>';
		}

		return $fan;
	}

	function WhomIEndorse($user_id,$user_level,$limit,$start){

		$IEndorsed = $this->CI->DatabaseModel->select_data('become_a_endorser.endorsee_id,users.user_name,users.user_uname',' become_a_endorser', array('become_a_endorser.endorsement_status'=>2,'become_a_endorser.endorser_id'=>$user_id,'user_level'=>$user_level),array($limit,$start),array('users','users.user_id = become_a_endorser.endorsee_id'),'rand()');

		$Endo = '';
		if(isset($IEndorsed[0])){
			$Endo = '<ul class="sidebar_widget_list">';
				foreach($IEndorsed as $IEndorse){
					$Endo .= '<li>
							   <div class="dis_fan_imgdiv">
								  <img src="'.get_user_image($IEndorse['endorsee_id']).'" class="img-reponsive" alt="" onError="this.onerror=null;this.src=\''.user_default_image().'\';">
								  <div class="overlay">
								  <!--ul>
										<li><a href="#">Edit</a></li>
										<li><a href="#">cancel</a></li>
									</ul-->
								  </div>
							   </div>
							   <a href="'.base_url('profile?user='.$IEndorse['user_uname'].'').'"><h3>'.$IEndorse['user_name'].'</h3></a>
							</li>';
				}
			$Endo .= '</ul>';
		}

		return $Endo;
	}
	function WhoEndorseMe($user_id,$user_level,$limit,$start){

		$EndorsedMe = $this->CI->DatabaseModel->select_data('become_a_endorser.endorser_id,users.user_name,users.user_uname',' become_a_endorser', array('become_a_endorser.endorsement_status'=>2,'become_a_endorser.endorsee_id'=>$user_id,'user_level'=>$user_level),array($limit,$start),array('users','users.user_id = become_a_endorser.endorser_id'),'rand()');

		$Endo = '';
		if(isset($EndorsedMe[0])){
			$Endo = '<ul class="sidebar_widget_list">';
				foreach($EndorsedMe as $Endorse){
					$Endo .= '<li>
							   <div class="dis_fan_imgdiv">
								  <img src="'.get_user_image($Endorse['endorser_id']).'" class="img-reponsive" alt="" onError="this.onerror=null;this.src=\''.user_default_image().'\';">
								  <div class="overlay">
								  <!--ul>
										<li><a href="#">Edit</a></li>
										<li><a href="#">cancel</a></li>
									</ul-->
								  </div>
							   </div>
							   <a href="'.base_url('profile?user='.$Endorse['user_uname'].'').'"><h3>'.$Endorse['user_name'].'</h3></a>
							</li>';
				}
			$Endo .= '</ul>';
		}

		return $Endo;
	}




	function notification_popup($user,$limit=10,$start=0,$type=null){

		$cond = array('to_user'=>$user);

		if(!empty($type)){
			$cond['noti_type'] = $type;
		}

		$this->CI->DatabaseModel->update_data('notifications',array('view_status'=>1),$cond);


		$popup = '';

		$notificaions = $this->CI->DatabaseModel->select_data('users.user_uname,users.user_name,notifications.*',' notifications',$cond ,array($limit,$start),array('users','users.user_id = notifications.from_user'),array('noti_id','DESC'));

		if(isset($notificaions[0])){
			foreach($notificaions as $notify){

				$mess 	= $this->CI->audition_functions->getNotiStatus($notify['noti_status'],$notify['noti_type']);
				$link 	= $this->CI->audition_functions->getNotiLink($notify['noti_status'],$notify['noti_type'],$notify['reference_id']);


				$sharedProfileName 	= '';
				if(($notify['noti_status']  == 1 || $notify['noti_status']  == 3) && $notify['noti_type'] == 4){                /*for sharing prfile and channel*/
					$sharedProfileName 	= $this->CI->audition_functions->getSharedProfileName($notify['noti_status'],$notify['noti_type'],$notify['reference_id']);
				}else

				$channelTitleName 	= '';
				if($notify['noti_status']  == 4 && $notify['noti_type'] == 4){     /*for sharing prfile and channel*/
					$channelTitleName 	= $this->CI->audition_functions->getChannelTitleName($notify['noti_status'],$notify['noti_type'],$notify['reference_id']);
				}else

				if(in_array($notify['noti_status'],[1,2,3]) && $notify['noti_type'] == 12){
					$mess .= '#DTV'.sprintf("%04d", $notify['reference_id']);
				}


				$pro_words = !empty($sharedProfileName)? $sharedProfileName : $channelTitleName;

				$user_image = get_user_image($notify['from_user']);

				if((int) $notify['from_user'] == 1)
					$user_image = CDN_BASE_URL . 'repo/images/mini_logo.webp';



				$popup .= '<div class="noti_wrapper">
								<div class="left">
									<a class="noti_img" href="'.base_url('profile?user='.$notify['user_uname']).'">
										<img src="'.$user_image.'" title="'.$notify['user_name'].'" class="img-responsive">
									</a>
									<div class="content">
									<a class="info" '.$link.'>'.$notify['user_name'].' '.$mess.' '.$pro_words.'</a>
										<!--span>from : ' .$notify['user_name']. '</span-->
									</div>
								</div>
								<p>'.time_elapsed_string($this->CI->common->manageTimezone($notify['created_at']),false) .'</p>
							</div>';
			}
		}else{
			// $popup =  '<center>No notification available.</center>';
		}

		return $popup;
	}

	public function castAndCrew($post_id,$cast_image_id = null){
		$join  				= 	['multiple',[
			['users_content' , 'channel_cast_images.cast_user_id = users_content.uc_userid','left'],
			['users' , 'users.user_id = users_content.uc_userid','left']
		]];
		$cond 				= 	$cast_image_id == null ? ['post_id'=>$post_id] : ['post_id'=>$post_id,'cast_image_id' => $cast_image_id] ;

		$cast_crews 		= 	$this->CI->DatabaseModel->select_data('channel_cast_images.*,users_content.uc_pic,users.user_uname,users.user_name','channel_cast_images use INDEX(post_id)',$cond,'',$join);
		//print_r($cast_crews );die;
		$result = '';
		foreach($cast_crews as $cast){
			$user_id 			= 	isset($cast['user_id'])			?	$cast['user_id']			:	'';
			$cast_image_id 		=	isset($cast['cast_image_id'])	?	$cast['cast_image_id']		:	'';
			$cast_user_id 		=	isset($cast['cast_user_id']) && !empty($cast['cast_user_id'])	?	$cast['cast_user_id'] :	'';
			$post_id 			=	isset($cast['post_id'])			?	$cast['post_id']			:	'';
			$cast_real_name 	=	(isset($cast['user_name']) && !empty($cast['user_name'])) ?	$cast['user_name']	: $cast['cast_real_name'];
			$user_uname 		=	isset($cast['user_uname'])		?	$cast['user_uname']		:	'';
			$cast_script_name 	=	isset($cast['cast_script_name'])?	$cast['cast_script_name']	:	'';
			// $profession 		=	isset($cast['profession'])		?	$cast['profession']			:	'';
			$profession 		=	[];

			//$uc_pic 			=	(isset($cast['uc_pic'])	&& !empty($cast['uc_pic']))	? $cast['uc_pic'] :	((isset($cast['image_name']) && !empty($cast['image_name'])) ? $cast['image_name'] : '' );

			$uc_pic 			=	(isset($cast['image_name'])	&& !empty($cast['image_name']))	? $cast['image_name'] :	((isset($cast['uc_pic']) && !empty($cast['uc_pic'])) ? $cast['uc_pic'] : '' );

			if(isset($cast['image_name']) && !empty($cast['image_name'])){
				$img 	= 	AMAZON_URL .'aud_'.$user_id .'/images/'.$uc_pic;
			}else{
				$img 	= 	AMAZON_URL .'aud_'.$cast_user_id .'/images/'.$uc_pic;
			}

			$edit_area 			= 	'';
			//$img 				= 	AMAZON_URL .'aud_'.$cast_user_id .'/images/'.$uc_pic;

			if(!empty(is_login()) && is_session_uid($user_id )) {
				$edit_area = '<div class="dis_overlay">
								<span class="dis_edit_cast_icon openModalPopup" data-href="modal/cast_crew_popup/'.$post_id.'/'.$cast_image_id.'" data-cls="dis_add_cast_popup">
									<svg xmlns="https://www.w3.org/2000/svg" width="16px" height="16px"><path fill-rule="evenodd"  fill="rgb(255, 90, 90)" d="M14.925,1.075 C14.240,0.388 13.327,0.009 12.355,0.009 C11.384,0.009 10.471,0.388 9.786,1.075 L0.845,10.012 C0.747,10.110 0.684,10.236 0.665,10.375 L0.004,15.279 C-0.025,15.474 0.042,15.673 0.182,15.814 C0.301,15.933 0.464,16.001 0.630,16.001 C0.659,16.001 0.687,16.000 0.715,15.997 L3.671,15.597 C3.839,15.574 3.989,15.487 4.091,15.352 C4.195,15.217 4.239,15.049 4.217,14.882 C4.169,14.534 3.840,14.291 3.502,14.336 L1.373,14.623 L1.752,11.815 L5.088,15.150 C5.327,15.393 5.760,15.385 5.984,15.151 L14.925,6.214 C15.613,5.526 15.992,4.613 15.992,3.643 C15.992,2.671 15.613,1.759 14.925,1.075 ZM11.328,3.806 L3.433,11.702 L2.195,10.463 L10.090,2.568 L11.328,3.806 ZM13.436,5.910 L5.540,13.805 L4.335,12.600 L12.230,4.705 L13.436,5.910 ZM14.721,3.643 C14.721,4.131 14.578,4.590 14.305,4.985 L11.015,1.695 C11.407,1.426 11.877,1.279 12.357,1.279 C12.986,1.279 13.580,1.524 14.031,1.970 C14.475,2.413 14.721,3.006 14.721,3.643 Z"/>
									</svg>
								</span>
							</div>

							<div class="dis_delete_img delete_cast_img" data-cast_id="'. $cast_image_id.'" data-post_id="'. $post_id.'">
								<span aria-hidden="true">×</span>
							</div>';
			}

			$href =  $user_uname ? 'href="'.base_url('profile?user='.$user_uname).'"' : 'style="pointer-events: none"';
			$result		.= '
							<li>
								<div class="dis_cast_data">
									<div class="dis_cast_img">
										<img src="'.$img.'" class="img-responsive" alt="" onError="this.onerror=null;this.src=\''.user_default_image().'\';">
										'.$edit_area.'
									</div>
									<div class="dis_cast_content">
										<a class="dis_CCB_username" '. $href .'>'. $cast_real_name .'</a>
										<h3 class="dis_CCB_scriptname">As '.$cast_script_name .'</h3>
									</div>
								</div>
							</li>';
		}

		return $result;
	}


	/*  common model start from here */ /* Not used anywhere now */
	function uploadCoverVideoModel(){
		return '<div class="modal fade Audition_popup upload_popup" id="upload_video" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Upload social video</h4>
					  </div>
					  <div class="modal-body">
						<div class="upload_img_wrapper">
						<p id="upload_video_err" class="help_note help_error"></p>
							<div class="browse_area">
								<div class="browse_area_inner">
									<div class="browse_btn_wrapper">

											<div class="">
											<input type="file" id="pro_video_upload" name="userfile" class="inputfile" data-id="pro_video_upload">
												<label for="pro_video_upload" class="browse_btn_label">
												<i class="fa fa-file-video-o" aria-hidden="true"></i>
												<p class="info_text">Drop a video here or Click to browse</p>
												</label>
											</div>

									</div>
									<div class="montz_videouplod_wrap _progress_bar hide">
										<div class="montz_progress">
											<div class="monyz_prog_fill _progress_percent">
												0%
											</div>
										</div>
										<div class="montz_vid_name">
											<span class="montz_vid_ttl _progress_title">file name .mp4</span>
										</div>
										<div class="montz_vid_cncl">
											<a class="dis_btn b_btn _process_abort">Cancel</a>
										</div>
									</div>
								</div>
								<p class="help_note"><strong>NOTE:</strong>Video should be in MP4 or MOV format.</p>
							</div>
						</div>
					  </div>
					</div>
				</div>
			</div>';
	}
	function ConfirmNotificationModal(){
		return '<div class="modal fade Audition_popup common_popup" id="confirm_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header" id="conf_header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-exclamation" aria-hidden="true"></i></h4>
			  </div>
			  <div class="modal-body">
				<div class="common_popup_wrapper">
					<p id="conf_text" class="conf_text"></p>
					<div class="btn_wrapper">
						<a class="popup_btn" id="conf_btn">Yes</a>
						<a class="popup_btn close_popup" data-parent="confirm_popup">Close</a>
					</div>
				</div>
			  </div>
			</div>
		  </div>
		</div>';
	}
}

/* End of file Audition_functions.php */
?>
