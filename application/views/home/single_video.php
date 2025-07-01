<?php
$levellist = $this->valuelist->level();
$modelist = $this->valuelist->mode();

$user_id 			= isset($p_uid)?$p_uid:'';
$created_at 		= isset($single_video['created_at'])?$single_video['created_at']:'';
$mode 				= isset($single_video['mode'])?$single_video['mode']:'';

$web_mode 			= isset($modelist[$mode])?$modelist[$mode]:'';

$age_restr 			= isset($single_video['age_restr'])?$single_video['age_restr']:'';
$uploaded_video	 	= isset($single_video['uploaded_video'])?$single_video['uploaded_video']:'';
$image_name 		= isset($single_video['image_name'])?$single_video['image_name']:'';
$title 				= isset($single_video['title'])?$single_video['title']:'';
$description 		= isset($single_video['description'])?$single_video['description']:'';
$genre_id 			= isset($single_video['genre'])?$single_video['genre']:'';
$genre_name 		= isset($single_video['genre_name'])?ucfirst($single_video['genre_name']):'';
$category 			= isset($single_video['category'])?$single_video['category']:'';
$user_level 		= isset($single_video['user_level'])?$single_video['user_level']:'';
$language 			= isset($single_video['language'])?$single_video['language']:'';
$duration 			= isset($single_video['video_duration'])?$single_video['video_duration']:0;
$tag 				= isset($single_video['tag'])?$single_video['tag']:'';
$privacy_status 	= isset($single_video['privacy_status'])?$single_video['privacy_status']:'';
$count_views 		= number_format_short(isset($single_video['count_views'])?$single_video['count_views']:0 , 1);
$count_votes 		= number_format_short(isset($single_video['count_votes'])?$single_video['count_votes']:0 , 1);
$iva_id 			= isset($single_video['iva_id'])?$single_video['iva_id']:0;
$is_video_processed = isset($single_video['is_video_processed'])?$single_video['is_video_processed']:0;

$user_uname 		= isset($single_video['user_uname'])?$single_video['user_uname']:'';
$user_name 			= isset($single_video['user_name'])?$single_video['user_name']:'';
$user_level 		= isset($levellist[$user_level ])?$levellist[$user_level ]:'';

$country_name 		= '';

$video_type  		= isset($single_video['video_type'])?$single_video['video_type']: 0 ;
$is_stream_live 	= isset($single_video['is_stream_live'])?$single_video['is_stream_live']: 0 ;
$schedule_time 		= isset($single_video['schedule_time'])? date('m/d/Y h:i:s a',strtotime($single_video['schedule_time'])) : '' ;
$is_scheduled 		= isset($single_video['is_scheduled'])? $single_video['is_scheduled'] : '' ;
$is_chat 			= isset($single_video['is_chat'])? $single_video['is_chat'] : 0 ;

$streming  			= isset($single_video['ivs_info'])?	json_decode($single_video['ivs_info'],true): [];
$strArn 			= isset($streming['channel']['arn'])?$streming['channel']['arn'] : '';

$ages 				= $this->audition_functions->age();

$is_mobile_device 	= is_mobile_device();

$is_login 			= is_login();

$is_session_uid 	= is_session_uid($user_id);

$ss 				= $this->audition_functions->post_status();
$d 					= get_domain_only(base_url());
$rand_id 			= uniqid(rand());
$dis_sv_toptads_mobile 			= 'div-gpt-ad-9845178-'.$rand_id.'1';
$dis_sv_toptads_desktop 		= 'div-gpt-ad-2841486-'.$rand_id.'2';

$dis_sv_leftads_desktop_up 		= 'div-gpt-ad-2841486-'.$rand_id.'3-up';
$dis_sv_leftads_desktop_down 	= 'div-gpt-ad-2841486-'.$rand_id.'4-down';

$dis_sv_righttads_desktop_up 	= 'div-gpt-ad-2841486-'.$rand_id.'5-up';
$dis_sv_righttads_desktop_down 	= 'div-gpt-ad-2841486-'.$rand_id.'6-down';

$dis_sv_btmtads_mobile 			= 'div-gpt-ad-9845178-'.$rand_id.'7';
$dis_sv_btmtads_desktop 		= 'div-gpt-ad-2841486-'.$rand_id.'8';
?>


<script>
var utm_source 			= '<?php echo isset($_GET["utm_source"])?$_GET["utm_source"]: "discovered" ; ?>';
var is_mobile_device 	= '<?php echo $is_mobile_device ? "mobile" : "desktop" ; ?>';

var dis_sv_toptads_mobile = '<?php echo $dis_sv_toptads_mobile; ?>';
var dis_sv_toptads_desktop = '<?php echo $dis_sv_toptads_desktop; ?>';

var dis_sv_leftads_desktop_up = '<?php echo $dis_sv_leftads_desktop_up; ?>' ;
var dis_sv_leftads_desktop_down = '<?php echo $dis_sv_leftads_desktop_down; ?>';

var dis_sv_righttads_desktop_up = '<?php echo $dis_sv_righttads_desktop_up; ?>';
var dis_sv_righttads_desktop_down = '<?php echo $dis_sv_righttads_desktop_down; ?>';

var dis_sv_btmtads_mobile = '<?php echo $dis_sv_btmtads_mobile; ?>';
var dis_sv_btmtads_desktop = '<?php echo $dis_sv_btmtads_desktop; ?>' ;


window.getCustomParam = function () {
	let UidIdWhoIsWatching = 0;
	if (typeof user_login_id !== 'undefined' && user_login_id !== '') {
		UidIdWhoIsWatching = user_login_id;
	}
	return {
		'VideoUserId'	: <?= $user_id; ?>,
		'VideoPostId'	: <?= $post_id; ?>,
		'VideoMode'		: '<?= $web_mode; ?>',
		'VideoGenre'	: <?= $genre_id; ?>,
		'VideoCategory'	: '<?= $mode == 1 ? 'IAB1-6' : ( $mode == 2  ? 'IAB1-5' : ( $mode == 3  ? 'IAB1-7' :'IAB9-30')); ?>',  //IAB category
		'channel'		: '<?= $user_uname; ?>',
		'profile'		: '<?= $user_name; ?>',
		'title'			: '<?= addcslashes($title, "'");  ?>',
		'is_stream_live': <?= $is_stream_live; ?>,
		'language'		: '<?= $language; ?>',
		'duration'		: <?= $duration; ?>,
		'UidIdWhoIsWatching': UidIdWhoIsWatching,
		'domain'		: (window.location.href).replace('http://', '').replace('https://', '').replace('www.', '').split(/[/?#]/)[0]
	};
}

</script>

<input type="hidden" id="PlayNext" value="1">
<input type="hidden" id="VideoUserId" value="<?php echo $user_id; ?>">
<input type="hidden" id="VideoPostId" value="<?php echo $post_id; ?>">
<input type="hidden" id="VideoMode" value="<?php echo $web_mode ; ?>">
<input type="hidden" id="VideoTag" 	value="<?php echo $tag ; ?>">

<input type="hidden" id="category_" value="<?php echo $category ; ?>">
<input type="hidden" id="genre_" value="<?php echo $genre_id ; ?>">
<input type="hidden" id="mode_" value="<?php echo $mode ; ?>">
<input type="hidden" id="list_" value="<?php echo ($this->uri->segment(3) !== FALSE)? $this->uri->segment(3)  : ''; ?>">

<input type="hidden" id="strArn" value="<?php echo $strArn ; ?>">
<input type="hidden" id="is_stream_live" value="<?php echo $is_stream_live ; ?>">


<div class="audition_main_wrapper ">
	<div class="dis_video_single_wrapper singlevideo_ads <?= ($is_stream_live  == 1)? 'live_straming_on': '';?>">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dis_singVideoWrap">
					<div class="dis_singVideoLeft">
						<?php if(!isset($_GET['castCrew'])){ ?>
						<!-- <div class="dis_sv_toptads">
							<div class="sv_ads">
								<?php if($is_mobile_device){ ?>
									<div id='<?= $d.'_'.$dis_sv_toptads_mobile; ?>'>

									</div>
								<?php }else{ ?>
									<div id='<?= $d.'_'.$dis_sv_toptads_desktop; ?>'>

									</div>
								<?php } ?>
							</div>
						</div> -->

						<div class="dis_user_post_data" id="parent_post_content_483">
							<div class="dis_user_post_content">
								<div class="dis_post_img">
								<!-- for_ads  class for mobile display ads in video player -->
									<div class="video-adjust">
										<?php
											$FilterData=$this->share_url_encryption->FilterIva($user_id,$iva_id,$image_name,trim($uploaded_video),false,'.m3u8',$is_video_processed);
											$ThumbImage = isset($FilterData['webp'])?$FilterData['webp']:'';
											$videoFile 	= isset($FilterData['video'])?$FilterData['video']:'';

                      $video_id = 'new_video1'.rand();
											$mime_type = $this->share_url_encryption->mime_type($videoFile);
										?>

                    <div class="box" src="<?php echo $videoFile ;?>" mime="<?php echo $mime_type ;?>">
                      <div id="gam-videos-root" class="<?= $video_id ?>"></div>
											<div class="dis_videoLoader videoLoader" style="display:none;"></div>
                      <video id="<?= $video_id ?>"  class="video-js vjs-fluid" poster='<?= $ThumbImage ; ?>' vidid = "<?php echo base_url('embedcv/'.$post_id); ?>" single= "<?php echo $post_key; ?>" playsinline >
												<?php
													foreach($captions as $caption){
														$default = ($caption['language'] == 'en')? 'default':'';
														echo '<track kind="captions" src="https://s3.amazonaws.com/discovered.tv.new-1/aud_215/captions/'.$caption['caption_name'].'" srclang="'.$caption['language'].'" label="'.$caption['language'].'"  '. $default .'>';
													}
												?>

											</video>

											<h4 class="ctm_videotitle"><?php echo $title; ?></h2>
											<div class="video_nextmain">
												<div class="video_nextwrapper">
													<div class="video_nextinnner custom_scrol">
														<div class="top_bar">
															<p class="top_title">Up/Next</p>
															<div class="auto_play_wraapper">
																<input type="checkbox" class="switch_tgl" id="player_next">
																<label class="tgl_btn" for="player_next"></label>
															</div>
														</div>

														<div class="video_nextbox_Wrap" id="UpNext">

														</div>
													</div>
													<div class="video_nexttoggle">
													</div>
												</div>
											</div>
											<div id="timerSpinner">

											</div>
											<div class="vjs-prev-btn vjs-PrevNext rewind" data-type="rewind">
												<div class="vjs-PrevNext-inner">
													<span class="vjs_prev_arrows vjs_1"></span>
													<span class="vjs_prev_arrows vjs_2"></span>
													<span class="vjs_prev_arrows vjs_3"></span>
												</div>
											</div>
											<div class="vjs-next-btn vjs-PrevNext forward"  data-type="forward">
												<div class="vjs-PrevNext-inner">
													<span class="vjs_prev_arrows vjs_1"></span>
													<span class="vjs_prev_arrows vjs_2"></span>
													<span class="vjs_prev_arrows vjs_3"></span>
												</div>
											</div>

										</div>

									</div>
								</div>

								<div class="dis_user_post_new_footer">
									<div class="dis_title_views">
										<h3><?php echo $title; ?></h3>
										<!--p><?php  // echo $count_views ;?> Views <span></span></p-->
									</div>
									<ul class="dis_vote_share_box">
										<!--li>
										</li-->
										<li>
											<?php if(is_login()){ ?>
												<a class="like_post <?php echo ($isvoted == 0)?'yr_vote':'active'; ?>" data-pid="<?= $post_id; ?>">
											<?php }else{ ?>
												<a class="like_post  <?php echo ($isvoted == 0)?'openModalPopup':'active'; ?>" data-href="modal/login_popup" data-cls="login_mdl">
											<?php } ?>
												<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20">
												<path fill-rule="evenodd"  fill-opacity="0" fill="rgb(119, 119, 119)"
												d="M0.000,0.000 L20.000,0.000 L20.000,20.000 L0.000,20.000 L0.000,0.000 Z"/>
												<path fill-rule="evenodd"  fill="rgb(119, 119, 119)"
												d="M17.173,4.105 C16.290,3.111 15.065,2.564 13.725,2.564 C11.830,2.564 10.640,3.674 9.974,4.604 C9.973,4.605 9.972,4.607 9.971,4.608 C9.970,4.606 9.969,4.605 9.968,4.604 C9.303,3.674 8.113,2.564 6.218,2.564 C4.878,2.564 3.653,3.111 2.768,4.105 C1.937,5.041 1.479,6.290 1.479,7.620 C1.479,9.062 2.035,10.388 3.231,11.794 C4.253,13.000 5.703,14.225 7.384,15.644 L7.440,15.690 C8.041,16.198 8.663,16.725 9.343,17.314 C9.518,17.465 9.741,17.549 9.972,17.549 C10.208,17.549 10.426,17.464 10.619,17.297 C11.280,16.725 11.901,16.199 12.502,15.691 L12.578,15.628 C14.249,14.216 15.692,12.998 16.712,11.794 C17.908,10.386 18.465,9.059 18.465,7.620 C18.465,6.289 18.006,5.040 17.173,4.105 ZM4.200,5.359 C4.715,4.779 5.432,4.461 6.218,4.461 C7.297,4.461 8.006,5.135 8.411,5.701 C8.797,6.242 8.996,6.780 9.064,6.987 C9.193,7.377 9.557,7.641 9.972,7.641 C10.386,7.641 10.751,7.377 10.879,6.987 C10.947,6.779 11.147,6.238 11.531,5.702 C11.937,5.135 12.646,4.461 13.725,4.461 C14.511,4.461 15.228,4.779 15.743,5.359 C16.267,5.949 16.556,6.751 16.556,7.620 C16.556,9.781 14.473,11.539 11.323,14.199 L11.287,14.229 C10.861,14.589 10.425,14.957 9.972,15.346 C9.558,14.992 9.157,14.653 8.749,14.308 L8.617,14.197 C5.468,11.537 3.387,9.781 3.387,7.620 C3.387,6.750 3.676,5.948 4.200,5.359 Z"/>
												</svg><span id="yr_vote"><?php echo ($isvoted == 1)?'Loved':'Love'; ?> (<?php echo $count_votes ; ?>)</span></a>

										</li>
										<?php

										if(WhoAmI($is_login) != 4 ){
										if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard'){ ?>
										<li>
											<a class="favourite_post openModalPopup" data-href="modal/playlist_popup/<?php echo $post_id; ?>" data-cls="dis_addplaylist_modal dis_center_modal muli_font">
											<svg
													xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20">
													<path fill-rule="evenodd"  fill-opacity="0" fill="rgb(119, 119, 119)"
													d="M0.000,0.000 L20.000,0.000 L20.000,20.000 L0.000,20.000 L0.000,0.000 Z"/>
													<path fill-rule="evenodd"  fill="rgb(119, 119, 119)"
													d="M16.000,13.000 L16.000,16.999 L14.000,16.999 L14.000,13.000 L10.000,13.000 L10.000,11.000 L14.000,11.000 L14.000,7.000 L16.000,7.000 L16.000,11.000 L20.000,11.000 L20.000,13.000 L16.000,13.000 ZM-0.000,7.000 L12.000,7.000 L12.000,9.000 L-0.000,9.000 L-0.000,7.000 ZM-0.000,3.000 L12.000,3.000 L12.000,5.000 L-0.000,5.000 L-0.000,3.000 ZM8.000,13.000 L-0.000,13.000 L-0.000,11.000 L8.000,11.000 L8.000,13.000 Z"/>
													</svg> <span> Add To Playlist </span></a>
										</li>
										<?php }} ?>
										<li>
											<?php if(is_login()){ ?>
												<a class="favourite_post AddToFavriote <?php echo ($isMyFavorite == 1)?'active':''; ?>" data-post_id="<?php echo $post_id; ?>">
											<?php }else{ ?>
												<a class="favourite_post openModalPopup <?php echo ($isMyFavorite == 1)?'active':''; ?>" data-href="modal/login_popup" data-cls="login_mdl">
											<?php } ?>
												<svg
													xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20">
													<path fill-rule="evenodd"  fill-opacity="0" fill="rgb(119, 119, 119)"
													d="M0.000,0.000 L20.000,0.000 L20.000,20.000 L0.000,20.000 L0.000,0.000 Z"/>
													<path fill-rule="evenodd"  fill="rgb(119, 119, 119)"
													d="M16.000,13.000 L16.000,16.999 L14.000,16.999 L14.000,13.000 L10.000,13.000 L10.000,11.000 L14.000,11.000 L14.000,7.000 L16.000,7.000 L16.000,11.000 L20.000,11.000 L20.000,13.000 L16.000,13.000 ZM-0.000,7.000 L12.000,7.000 L12.000,9.000 L-0.000,9.000 L-0.000,7.000 ZM-0.000,3.000 L12.000,3.000 L12.000,5.000 L-0.000,5.000 L-0.000,3.000 ZM8.000,13.000 L-0.000,13.000 L-0.000,11.000 L8.000,11.000 L8.000,13.000 Z"/>
													</svg> <span><?php echo ($isMyFavorite == 1)?'Added To favorites':'Add To favorites'; ?> </span></a>
										</li>
										<li>
											<a class="share_post dtvShareMe" data-share="2|<?php echo $post_id; ?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20">
											<path fill-rule="evenodd"  fill-opacity="0" fill="rgb(119, 119, 119)"
											d="M0.000,0.000 L20.000,0.000 L20.000,20.000 L0.000,20.000 L0.000,0.000 Z"/>
											<path fill-rule="evenodd"  fill="rgb(119, 119, 119)"
											d="M17.878,7.029 L13.021,2.216 C12.901,2.097 12.758,2.037 12.594,2.037 C12.430,2.037 12.287,2.097 12.167,2.216 C12.047,2.335 11.987,2.476 11.987,2.639 L11.987,5.045 L9.862,5.045 C5.352,5.045 2.585,6.308 1.561,8.834 C1.226,9.674 1.058,10.717 1.058,11.964 C1.058,13.005 1.460,14.418 2.263,16.204 C2.282,16.248 2.315,16.323 2.362,16.429 C2.410,16.536 2.453,16.630 2.490,16.712 C2.529,16.793 2.570,16.862 2.614,16.918 C2.690,17.025 2.778,17.078 2.880,17.078 C2.974,17.078 3.049,17.047 3.102,16.984 C3.156,16.922 3.183,16.843 3.183,16.749 C3.183,16.693 3.175,16.610 3.159,16.500 C3.144,16.390 3.136,16.317 3.136,16.279 C3.104,15.853 3.088,15.468 3.088,15.123 C3.088,14.490 3.144,13.923 3.254,13.422 C3.365,12.920 3.518,12.486 3.714,12.120 C3.910,11.753 4.163,11.437 4.473,11.170 C4.783,10.904 5.117,10.686 5.474,10.517 C5.831,10.348 6.252,10.214 6.736,10.117 C7.219,10.020 7.707,9.953 8.197,9.915 C8.687,9.878 9.242,9.859 9.862,9.859 L11.987,9.859 L11.987,12.265 C11.987,12.428 12.047,12.570 12.167,12.688 C12.287,12.807 12.429,12.867 12.594,12.867 C12.758,12.867 12.900,12.807 13.021,12.688 L17.878,7.875 C17.998,7.756 18.058,7.615 18.058,7.452 C18.058,7.289 17.998,7.148 17.878,7.029 Z"/>
											</svg> Share</a>
										</li>
										<li>
											<a class="dis_videoREoption dis_actiondiv">
												<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
													<path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
												</svg>
												<div class="dis_action_content">
													<ul>
														<?php if(is_login()){ ?>
															<li  class="raise_flag_report openModalPopup" data-href="modal/report_content_popup/0/content/Why-are-you-reporting-this-video" data-cls="dis_Reporting_modal dis_center_modal" data-heading="Why are you reporting this video ?" data-viol_id="0" data-parent_id="0" data-type="content" data-related_with="3" data-related_id="<?= $post_id; ?>">Report</li>
														<?php }else{ ?>
															<li class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">Report</li>
														<?php } ?>
													</ul>
												</div>
											</a>
										</li>
									</ul>
								</div>

								<?php if($video_type == 2 && $is_stream_live  == 1) { ?>
								<input type="hidden" value="<?= $schedule_time; ?>" id="schedule_time" data-is_sechdule="<?= $is_scheduled; ?>">
								<!-- Streaming -->
								<div class="dis_livestream_videostatus">
									<ul class="dis_livestream_vslist">
										<li>
											<p class="dis_ls_vs_ttl">Status</p>
											<span class="label label-danger" id="StrStatus">OFFLINE</span>
										</li>
										<li>
											<p class="dis_ls_vs_ttl">Health</p>
											<span class="label label-info" id="StrHealth">-</span>
										</li>
										<li>
											<p class="dis_ls_vs_ttl">Duration</p>
											<span class="label label-info" id="StrDuration">-</span>
										</li>
										<li>
											<p class="dis_ls_vs_ttl">Viewers</p>
											<span class="label label-info" id="StrViewers">-</span>
										</li>
									</ul>
								</div>
								<?php } ?>

								<!--<p><strong><?php //echo $title; ?></strong></p>-->
								<?php if(!$is_mobile_device){ ?>
								<div class="dis_sv_leftads">
									<div class="sv_ads">
										<div id='<?= $d.'_'.$dis_sv_leftads_desktop_up; ?>'>

										</div>
									</div>
									<br>
									<div class="sv_ads">
										<div id='<?= $d.'_'.$dis_sv_leftads_desktop_down; ?>'>

										</div>
									</div>
								</div>

								<?php } ?>


							</div>
							<div class="dis_user_post_header">
								<div class="dis_headerDetails">
									<div class="dis_user_img">
										<img src="<?= get_user_image($user_id); ?>" alt="" onerror="this.onerror=null;this.src='<?= user_default_image(); ?>'">
									</div>
									<div class="dis_user_detail">
										<h3><a href="<?= base_url('channel?user=').$user_uname ; ?>"><?php echo $user_name; ?></a>, <br>
											<p><?php echo (!empty($user_level))?$user_level.' ':''; ;?> <?php echo $country_name;?></p></h3>
											<!--p class="published_date">published <?php //echo $created_at; ?></p-->
									</div>
								</div>
								<div class="dis_headerBtn">
									<ul class="dis_btn_grp">
										<li>
											<?php
											if($is_session_uid != 1)
											echo FanButton($user_id)['old']; ?>
										</li>
										<li><a  class="dis_fanbtn dis_bgclr_yellow"><?= $web_mode.' / '.$genre_name;?></a></li>
										<li><a class="dis_fanbtn"><?= isset($ages[$age_restr])?$ages[$age_restr] : $age_restr;?></a></li>
									</ul>
								</div>
							</div>

						</div>

						<div class="dis_sv_btmtads">
							<div class="sv_ads">
								<?php if($is_mobile_device){ ?>
									<div id='<?= $d.'_'.$dis_sv_btmtads_mobile;?>'>

									</div>
								<?php }else{ ?>
									<div id='<?= $d.'_'.$dis_sv_btmtads_desktop; ?>'>

									</div>
								<?php } ?>
							</div>
						</div>
						<?php } ?>

						<div class="dis_video_single_tabs">
							<!-- Nav tabs -->
							<?php if(!isset($_GET['castCrew'])){ ?>
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#sv_creator"  aria-controls="sv_creator" role="tab" data-toggle="tab" aria-expanded="false">More From <?php $l =  explode(' ', $user_name ); echo isset($l[0]) ? $l[0] : 'this creator'; ?></a></li>
								<li role="presentation"><a href="#des" aria-controls="des" role="tab" data-toggle="tab" aria-expanded="true">Synopsis</a></li>
								<li role="presentation" class=""><a href="#sv_comments" onclick="fetchComment(0,<?php echo $post_id; ?>,<?php echo $user_id; ?>);this.removeAttribute('onclick')" aria-controls="sv_comments" role="tab" data-toggle="tab" aria-expanded="false">Comments</a></li>
								<li role="presentation" class=""><a class="intCastCrew" data-post_id="<?php echo $post_id; ?>" aria-controls="sv_cast" role="tab" data-toggle="tab" aria-expanded="false"><?= $is_mobile_device ? 'Cast' : 'Cast &amp; Crew' ?></a></li>
							</ul>
							<?php } ?>
							<!-- Tab panes -->
							<div class="tab-content">
								<?php if(!isset($_GET['castCrew'])){ ?>
									<div role="tabpanel" class="tab-pane active" id="sv_creator">
										<div class="dis_other_video_div singl_view sigl_pg_revideo"><!-- Artist section -->
											<div class="row">
												<div id="load_related" class="revideo_inner dis_load_vid">
													<?php $loaderhtml  =  $this->common_html->content_loader_html(); ?>
												</div>
											</div>
											<div class="dis_btndiv">
												<a  class="dis_btn intSlider" data-uid="<?php echo $user_id; ?>">	See More
												</a>
											</div>
										</div>
									</div>
									<div role="tabpanel" class="tab-pane" id="des">
										<div class="dis_tabdata dis_details_tab appendData2">

											<?php echo nl2br($description); ?>
										</div>
									</div>
								<?php } ?>


								<div role="tabpanel" class="tab-pane <?= isset($_GET['castCrew'])? 'active' : '';?>" id="sv_cast">
									<div class="dis_cast_div muli_font">
										<ul class="dis_CastCrewList" id="castandcrewhtmlSingleVideo">
											<?php if(!empty($is_login) && $is_session_uid) { ?>
											<li>
												<div class="dis_CastCrewBox dis_CCB_new openModalPopup" data-href="modal/cast_crew_popup/<?php echo $post_id; ?>" data-cls="dis_add_cast_popup">
													<span class="dis_CCBNIcon">
														<svg xmlns="https://www.w3.org/2000/svg" width="24px" height="23px"><path fill-rule="evenodd" fill="rgb(117, 117, 117)" d="M22.135,9.760 L13.823,9.760 L13.823,1.795 C13.823,1.293 13.651,0.869 13.307,0.522 C12.963,0.177 12.528,0.004 12.004,0.004 C11.479,0.004 11.045,0.177 10.701,0.522 C10.356,0.869 10.184,1.293 10.184,1.795 L10.184,9.760 L1.873,9.760 C1.348,9.760 0.905,9.925 0.545,10.255 C0.184,10.585 0.004,11.002 0.004,11.504 C0.004,11.975 0.184,12.384 0.545,12.729 C0.905,13.075 1.348,13.248 1.873,13.248 L10.184,13.248 L10.184,21.213 C10.184,21.716 10.356,22.140 10.701,22.485 C11.045,22.831 11.479,23.004 12.004,23.004 C12.528,23.004 12.963,22.831 13.307,22.485 C13.651,22.140 13.823,21.716 13.823,21.213 L13.823,13.248 L22.135,13.248 C22.659,13.248 23.101,13.083 23.463,12.753 C23.823,12.423 24.004,12.007 24.004,11.504 C24.004,11.002 23.823,10.585 23.463,10.255 C23.101,9.925 22.659,9.760 22.135,9.760 L22.135,9.760 Z"></path></svg>
													</span>
													<h3 class="dis_CCBNtext">Add New Cast / Crew</h3>
												</div>
											</li>
											<?php } ?>

										</ul>
									</div>
								</div>
							<?php if(!isset($_GET['castCrew'])){ ?>
								<div role="tabpanel" class="tab-pane" id="sv_comments">
									<div class="dis_vid_commentWrap muli_font">

									</div>
								</div>
							<?php } ?>
							</div>
						</div>
					</div>
					<div class="dis_singVideoRight">
						<?php
							if($is_chat == 0){
							?>
							<div class="dis_sv_righttads">
								<div class="sv_ads">
									<div id='<?= $d.'_'.$dis_sv_righttads_desktop_up; ?>'>

									</div>
								</div>
								<br>

								<div class="sv_ads">
									<div id='<?= $d.'_'.$dis_sv_righttads_desktop_down; ?>'>

									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
if($is_chat == 1){

?>
<div class="dis_stream_chatwrap dis_SB_PT_chatwrap dis_SB_C_chatwrap">
	<section class="dis_sc_head_inner">
		<div class="dis_sc_head">
			<h1 class="dis_sc_topttl">Live Chat</h1>
		</div>
		<div class="dis_sc_message_area">

		</div>
		<div class="dis_streamchat_sendbox">
			<textarea id="textareas" cols="30" rows="1" placeholder="Write a message..."></textarea>
			<div class="emoji_picker _EmojiPicker" data-target="#SocialEmoji" data-textarea="#textareas">
				<img class="" src="<?= base_url('repo/images/emoji/emoji.svg'); ?>" alt="smile svg">
			</div>
			<span id="SocialEmoji" class="hide"></span>
		</div>
	</section>
</div>
<?php  } ?>
