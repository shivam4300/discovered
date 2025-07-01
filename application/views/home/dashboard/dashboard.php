<?php //echo $this->common_html->uploadCoverVideoModel();?>

<?php
if(!isset($social)){ ?>
	<div class="au_video_popup">
		<?php echo $this->common_html->au_video_popup($cover_video,$defaultVideo);?>
	</div>
<?php } ?>

<?php

$DP 					= 	get_user_image($uid);
$social_button_title 	= 	'Add Social video';
$remove_social_video 	= 	'';

$whoami 				= 	WhoAmI($uid) ;

if(isset($cover_video['url']) && !empty($cover_video['url'])) {
	$social_button_title = 'Edit Social video';
	$remove_social_video = '<li><a class="remove_profile_video">Remove Social Video</a></li>';
}

$Edit_cover_video = '<div class="upload_video dis_btn" >
									<div class="up_inner" data-toggle="dropdown">
										<span>
											<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px">
											<path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
											 d="M14.290,3.040 L13.693,3.636 L11.047,0.986 L11.643,0.389 C12.192,-0.160 13.081,-0.160 13.630,0.389 L14.290,1.049 C14.837,1.599 14.837,2.489 14.290,3.040 ZM4.693,12.651 L2.046,10.001 L10.252,1.782 L12.898,4.433 L4.693,12.651 ZM0.707,13.993 L1.517,11.063 L3.632,13.181 L0.707,13.993 Z"/>
											</svg>
										</span>'.$social_button_title.'
										<div class="dis_action_content">
											<ul>
												'.$remove_social_video.'
												<li><a class="openModalPopup" data-href="modal/upload_cover_video_popup" data-cls="Audition_popup upload_popup">Upload New Social Video</a></li>
												<!--li><a data-toggle="modal" data-target="#upload_video">Upload New Social Video</a></li-->
											</ul>
										</div>
									</div>
								</div>';
?>

<div class="audition_main_wrapper au_banner_section">
<?php if(isset($social)){ ?>
	<?php if(isset($cover_video)){ ?>
<div class="dis_featuredVideo_slider CoverSwiper">
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<?php foreach($cover_video as $cv){ ?>
			<div class="swiper-slide">
				<div class="audition_main_wrapper au_banner_section">
					<?php echo $this->common_html->au_banner_section($cv);?>
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="swiper-button-next fvs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
   		<div class="swiper-button-prev fvs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
	</div>
</div>
<?php } ?>
<?php }else{	?>

<div class="user_profile_page">
	<?php if( ( isset($cover_video['url']) && !empty($cover_video['url']) ) || ( isset($is_session_uid) && $is_session_uid != 1 ) ){ ?>

		<div class="user_profile_wrapper <?php echo !empty($cover_video['url'])? 'edit_cover_video' : '' ;?>" >
			<div class="Flexible-container">
				<a class="speaker mute" data-video="cover_banner_video">
					<span></span>
				</a>
				<?php $vidurl = isset($cover_video['url']) && !empty($cover_video['url']) ? $cover_video['url'] : AMAZON_URL.$defaultVideo; ?>
				<video autoplay muted loop class="banner_video cover_banner_video">
					<source src="<?= $vidurl ; ?>" type="video/mp4">
				</video>
				<?php
					if(isset($is_session_uid) && $is_session_uid == 1){
						echo $Edit_cover_video;
					}
				?>
			</div>

			<div class="au_banner_content">
				<a  id="popup_banner_video" class="play_cover_video"><img src="<?php echo base_url();?>repo/images/banner_logo1.png"></a>
			</div>

			<div class="dis_scroll_div">
				<a href="#section2"><img src="<?php echo base_url();?>repo/images/scroll_icon.png" class="img-responsive" alt=""></a>
			</div>
		</div>
	<?php }else{ ?>

		<div class="dis_user_profile_banner">
			<img src="<?php echo base_url();?>repo/images/default_profile_banner.jpg" class="img-reponsive" alt="">
				<div class="dis_profile_default_data">
					<h3>Promote Yourself</h3>
					<h4>By Adding Your Own Social Video</h4>
					<div class="clearfix"></div>
					<img src="<?php echo base_url();?>repo/images/down_arrow.png" class="img-reponsive" alt="">
					<?php
						if(is_login()){
							if(isset($is_session_uid) && $is_session_uid == 1){
								echo $Edit_cover_video;
							}
						}else{
							echo '<a class="upload_video dis_btn" data-toggle="modal" data-target="#myModal"><span>+</span>add cover video</a>';
						}
					?>
				</div>

				<div class="dis_scroll_div">
					<a href="#section2"><img src="<?php echo base_url();?>repo/images/scroll_icon.png" class="img-responsive" alt=""></a>
				</div>
		</div>
		<?php } ?>
	</div>
<?php } ?>
</div>

<script>
var adSlot1;
</script>
 <!-- profile picture section -->
<div class="dis_user_data_wrapper" id="section2"  <?php if(isset($social)){ echo 'dis_sigle_social';}?> >
	<!-- Gamification -->
	<div class="container gam-add-side" id="ScrollToHome">
		<div class="row">
			<div class="col-lg-4 col-lg-push-8 col-md-12 gam-wc-wrapper">
				<div id="gam-leaderboard-challenge-root"></div>
			</div>
			<div class="col-lg-8 col-lg-pull-4 col-md-12">
			<?php $ismobdev = is_mobile_device() ; echo isset($common_header)? $common_header : '';?>
				<div class="row">
					<?php if( !$ismobdev || !isset($social)){  ?>
					<div class="col-lg-4 col-md-4" id="ColMd4">
						<!-- sidebar -->
						<div class="right_sidebar_wrapper">
							<?=isset($user_introduction)? $user_introduction : '';?>

							<?php //if(!empty($publish_images)){ ?>
								<div class="sidebar_widget widget photo_gallery">
									<h4 class="widget-title">Photos</h4>
									<ul class="sidebar_widget_list" id="publish_image_content">
									</ul>
									<a  class="dis_see_all load-content" data-load-contnet="image" data-load-contnet-count="0" data-id="publish_image_content">See More Photos</a>
								</div>
							<?php //} ?>

							<?php //if(!empty($publish_videos)){ ?>
								<div class="sidebar_widget widget video_gallery">
									<h4 class="widget-title">Videos </h4>
									<ul class="sidebar_widget_list" id="publish_video_content">

									</ul>
									<a class="dis_see_all load-content" data-load-contnet="video" data-load-contnet-count="0" data-id="publish_video_content">See More Videos</a>
								</div>
							<?php //} ?>
							<?php $d = get_domain_only(base_url()); ?>
							<div class="profile_sidbr_ads dis_add_area" data-height="100">
								<div id="<?= $ismobdev ? $d.'_profile_sidebar_mobile_2' : $d.'_profile_sidebar_desktop_2'; ?>">

								</div>
							</div>
							<div class="sidebar_widget widget dis_fans">
								<h4 class="widget-title">Icon Fans <span id="Icon"></span></h4>
								<span id="icon_fan"></span>
								<a class="dis_see_all load-more-fan" data-load-contnet="icon_fan" data-load-contnet-count="0" data-id="icon_fan">See More Icon Fans</a>
							</div>


							<div class="sidebar_widget widget dis_fans">
								<h4 class="widget-title">Emerging Fans <span id="Emerging"></span></h4>
								<span id="emerging_fan"></span>
								<a class="dis_see_all load-more-fan" data-load-contnet="emerging_fan" data-load-contnet-count="0" data-id="emerging_fan">See More Emerging Fans</a>
							</div>

							<div class="sidebar_widget widget dis_fans">
								<h4 class="widget-title">Brand Fans <span id="Brand"></span></h4>
								<span id="brand_fan"></span>
								<a class="dis_see_all load-more-fan" data-load-contnet="brand_fan" data-load-contnet-count="0" data-id="brand_fan">See More Brand Fans</a>
							</div>

							<div class="sidebar_widget widget dis_fans">
								<h4 class="widget-title">Fans <span id="Fan"></span></h4>
								<span id="fans"></span>
								<a class="dis_see_all load-more-fan" data-load-contnet="fans" data-load-contnet-count="0" data-id="fans">See More Fans</a>
							</div>

							<!--div class="sidebar_widget widget dis_endorsed">
								<h4 class="widget-title">ENDORSED <br> <span>Creators you are Endorsing</span></h4>
								<span id="CreatorsYouEndorsing"></span>
								<a class="dis_see_all load-more-fan" data-load-contnet="CreatorsYouEndorsing" data-load-contnet-count="0" data-id="CreatorsYouEndorsing">SEE More ENDORSED CREATORS</a>
							</div>

							<div class="sidebar_widget widget dis_endorsed">
								<h4 class="widget-title">ENDORSED <br> <span>Brands You Are Endorsing</span></h4>
								<span id="BrandsYouEndorsing"></span>
								<a class="dis_see_all load-more-fan" data-load-contnet="BrandsYouEndorsing" data-load-contnet-count="0" data-id="BrandsYouEndorsing">SEE More ENDORSED CREATORS</a>
							</div>

							<div class="sidebar_widget widget dis_endorsed">
								<h4 class="widget-title">ENDORSEMENTS <br> <span>Creators That Are Endorsing You</span></h4>
								<span id="CreatorsEndorsingYou"></span>
								<a class="dis_see_all load-more-fan" data-load-contnet="CreatorsEndorsingYou" data-load-contnet-count="0" data-id="CreatorsEndorsingYou">SEE More CREATORS ENDORSEMENTS</a>
							</div>

							<div class="sidebar_widget widget dis_endorsed">
								<h4 class="widget-title">ENDORSEMENTS <br> <span>Brands That Are Endorsing You</span></h4>
								<span id="BrandsEndorsingYou"></span>
								<a class="dis_see_all load-more-fan" data-load-contnet="BrandsEndorsingYou" data-load-contnet-count="0" data-id="BrandsEndorsingYou">SEE More BRANDS ENDORSEMENTS</a>
							</div-->


							<div class="sticky_sidebar_wrapper">
								<div class="sticky_sidebar">
									<div class="profile_sidbr_ads dis_add_area" data-height="100">
										<!-- /22019190093/discovered.tv_profile_sidebar_mobile -->
										<div id="<?= $ismobdev ? $d.'_profile_sidebar_mobile' : $d.'_profile_sidebar_desktop'; ?>">

										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<?php }  ?>
					<div class="col-lg-8 col-md-8" id="ColMd8">
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="home">
								<div class="user_tab_wrapper">
									<div class="artist_profile_home dis_profile_data">
										<div class="row">
											<div class="col-12">
												<div id="profile-root"></div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 sm_padding">

												<input type="hidden" id="data-user_id" data-user_id="<?= (isset($uid))?$uid:'';?>">
												<input type="hidden" id="data-social" data-social="<?= (isset($social))?$social:'';?>">

												<?php if(isset($is_session_uid) && $is_session_uid == 1){  ?>

													<div class="user_post_area_main">
														<div class="user_post_area active_area">
															<!-- post area -->
															<div class="post_area_header">
																<ul class="post_section_ul" role="tablist">
																	<li title="fileContent" class="active opacity_textarea">
																		<a class="dis_post_menu_item" href="javascript:;">
																			<div class="dis_post_menu_wrap">
																				<div class="dis_post_menu_left">
																					<span class="action_icon">
																						<svg xmlns="https://www.w3.org/2000/svg" width="21px" height="21px" viewBox="0 0 21 21">
																							<path fill-rule="evenodd" fill-opacity="0" fill="rgb(235, 88, 31)" d="M0.000,0.000 L21.000,0.000 L21.000,21.000 L0.000,21.000 L0.000,0.000 Z"></path>
																							<path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M16.562,18.009 L5.439,18.009 C4.095,18.008 3.002,16.885 3.000,15.506 L3.000,4.899 C3.002,3.520 4.095,2.397 5.438,2.395 L8.958,2.395 C9.429,2.395 9.811,2.788 9.811,3.271 C9.811,3.754 9.429,4.146 8.958,4.146 L5.439,4.146 C5.035,4.147 4.706,4.485 4.706,4.899 L4.706,15.506 C4.706,15.920 5.035,16.257 5.439,16.258 L16.561,16.258 C16.965,16.257 17.294,15.920 17.294,15.505 L17.294,11.891 C17.294,11.408 17.677,11.015 18.147,11.015 C18.617,11.015 19.000,11.408 19.000,11.892 L19.000,15.506 C18.998,16.885 17.905,18.008 16.562,18.009 ZM17.871,4.740 L16.004,2.824 L16.424,2.392 C16.812,1.996 17.439,1.996 17.826,2.392 L18.291,2.869 C18.678,3.268 18.678,3.911 18.291,4.309 L17.871,4.740 ZM9.282,10.111 L10.773,11.642 L8.711,12.229 L9.282,10.111 ZM17.310,5.316 L11.522,11.259 L9.655,9.343 L15.443,3.400 L17.310,5.316 Z"></path>
																						</svg>
																					</span>
																				</div>
																				<div class="dis_post_menu_right">
																					<span class="action_text">Create Post</span>
																					<p class="dis_post_menu_sttl">Non-Monetized Video</p>
																				</div>
																			</div>
																		</a>
																	</li>
																	<?php
																	if($whoami != 4 && isset($sigup_acc_type) && $sigup_acc_type == 'standard'){    /*ONLY FAN CAN'T ACCESS THIS*/
																	?>
																	<li title="Monetize">
																		<a href="<?php echo base_url('monetization'); ?>" class="dis_post_menu_item">
																			<div class="dis_post_menu_wrap">
																				<div class="dis_post_menu_left">
																					<span class="action_icon">
																						<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20" height="40" viewBox="0 0 24 24"><g><path d="M22.25 4H1.75C.785 4 0 4.785 0 5.75v13.5C0 20.215.785 21 1.75 21h7.658c-.118.501-.429 1.314-1.262 2.146A.499.499 0 0 0 8.5 24h7a.5.5 0 0 0 .354-.854c-.831-.831-1.151-1.644-1.275-2.146h7.671c.965 0 1.75-.785 1.75-1.75V5.75C24 4.785 23.215 4 22.25 4zM22 17H2V6h20z" fill="rgb(119, 119, 119)" data-original="rgb(119, 119, 119)" class=""></path><path d="M12.625 10.75h-1.25a.375.375 0 0 1 0-.75h2.375a.75.75 0 0 0 0-1.5h-1v-.75a.75.75 0 0 0-1.5 0v.763a1.87 1.87 0 0 0-1.75 1.862c0 1.034.841 1.875 1.875 1.875h1.25a.375.375 0 0 1 0 .75H10.25a.75.75 0 0 0 0 1.5h1v.75a.75.75 0 0 0 1.5 0v-.763a1.871 1.871 0 0 0 1.75-1.862 1.877 1.877 0 0 0-1.875-1.875z" fill="rgb(119, 119, 119)" data-original="rgb(119, 119, 119)" class=""></path></g>
																						</svg>
																					</span>
																				</div>
																				<div class="dis_post_menu_right">
																					<span class="action_text">Upload Video</span>
																					<p class="dis_post_menu_sttl">Earn Money/Video</p>
																				</div>
																			</div>
																		</a>
																	</li>
																	<li title="Live Stream" class="">
																		<a href="<?= base_url('media_stream');?>" class="dis_post_menu_item">
																			<div class="dis_post_menu_wrap">
																				<div class="dis_post_menu_left">
																					<span class="action_icon">
																						<svg xmlns="https://www.w3.org/2000/svg" width="21px" height="21px"  viewBox="0 0 21 21">
																							<path fill-rule="evenodd"  fill-opacity="0" fill="rgb(235, 88, 31)" d="M0.000,0.000 L21.000,0.000 L21.000,21.000 L0.000,21.000 L0.000,0.000 Z"/>
																							<path fill-rule="evenodd"  fill="rgb(119, 119, 119)" d="M19.701,11.231 C19.510,11.075 19.262,11.011 19.019,11.055 L16.823,11.451 L16.823,10.168 C16.823,9.717 16.450,9.350 15.991,9.350 L15.941,9.350 C16.713,8.543 17.149,7.475 17.149,6.364 C17.149,3.957 15.151,1.998 12.695,1.998 C11.250,1.998 9.912,2.678 9.074,3.828 C8.236,2.678 6.898,1.998 5.453,1.998 C2.997,1.998 0.999,3.957 0.999,6.364 C0.999,7.475 1.435,8.543 2.207,9.350 L2.157,9.350 C1.699,9.350 1.325,9.717 1.325,10.168 L1.325,18.184 C1.325,18.634 1.699,19.002 2.157,19.002 L15.991,19.002 C16.450,19.002 16.823,18.634 16.823,18.184 L16.823,16.899 L19.022,17.298 C19.266,17.335 19.516,17.274 19.701,17.121 C19.892,16.965 20.001,16.735 20.001,16.492 L20.001,11.860 C20.001,11.615 19.892,11.385 19.701,11.231 ZM18.338,12.842 L18.338,15.510 L16.823,15.238 L16.823,13.115 L18.338,12.842 ZM2.662,6.364 C2.662,4.858 3.914,3.634 5.453,3.634 C6.991,3.634 8.242,4.858 8.242,6.364 C8.242,7.870 6.991,9.095 5.453,9.095 C3.914,9.095 2.662,7.870 2.662,6.364 ZM8.698,9.350 C8.833,9.209 8.958,9.058 9.074,8.901 C9.190,9.058 9.315,9.209 9.449,9.350 L8.698,9.350 ZM9.906,6.364 C9.906,4.858 11.157,3.634 12.695,3.634 C14.234,3.634 15.485,4.858 15.485,6.364 C15.485,7.870 14.234,9.095 12.695,9.095 C11.157,9.095 9.906,7.870 9.906,6.364 ZM15.159,10.986 L15.159,17.365 L2.989,17.365 L2.989,10.986 L15.159,10.986 Z"/>
																						</svg>
																					</span>
																				</div>
																				<div class="dis_post_menu_right">
																					<span class="action_text">Go Live</span>
																					<p class="dis_post_menu_sttl">Earn Money/Live Stream</p>
																				</div>
																			</div>
																		</a>
																	</li>

																	<?php } ?>

																</ul>
															<div class="close_opacity"><i class="fa fa-times" aria-hidden="true"></i></div>
															</div>

														<div class="user_tab_content">
															<div class="active" id="create">
																<div class="post_area_body">
																	<span class="profile_icon">
																	<img onerror="this.onerror=null;this.src='<?= base_url("repo/images/user/user.png") ?>'"  src="<?php echo $DP ;?>" alt="" class="img-responsive">
																	</span>
																	<div class="dis_textare_div">
																		<?php $Dlength = $this->dashboard_function->discLength; ?>
																			<textarea class="post_area opacity_textarea" rows="5" placeholder="What You Want People To Know?" id="publish_input" data-length="<?= $Dlength ;?>" maxlength="<?= $Dlength ;?>"></textarea>
																			<div id="openAiBox"></div>

																			<div class="emoji_picker _EmojiPicker" data-target="#SocialEmoji" data-textarea="#publish_input">
																				<img class="" src="<?= base_url('repo/images/emoji/emoji.svg'); ?>" alt="smile svg">
																			</div>
																			<span id="SocialEmoji" class="hide"></span>
																	</div>
																	<span id="input" class="textarea_lenght"></span>
																	<div class="edit_media_section"></div>
																	<div class="hideme uploadSection ">
																		<div class="browse_area">
																			<div class="browse_area_inner">
																				<div class="browse_btn_wrapper">
																					<input type="file" id="uploadFile" name="userfile" class="inputfile" data-id="uploadFile">
																					<div class="" style="text-align:center;">
																						<label for="uploadFile" class="browse_btn_label">
																							<i class="fa fa-file-video-o" aria-hidden="true"></i>
																							<p class="info_text">Drop a video or image here or Click to browse</p>
																						</label>
																					</div>
																				</div>
																				<div class="montz_videouplod_wrap _progress_bar hide">
																					<div class="montz_progress">
																						<div class="monyz_prog_fill _progress_percent" >0%</div>
																					</div>
																					<div class="montz_vid_name">
																						<span class="montz_vid_ttl _progress_title">hayla.mp4</span>
																					</div>
																					<div class="montz_vid_cncl">
																						<a class="dis_btn b_btn _process_abort" data-msg="Are you sure want to remove this file ?">Remove</a>
																					</div>
																					<span>To create this post, press the publish button below</span>
																				</div>
																			</div>
																		</div>
																		<p class="help_note"><strong>NOTE:</strong>Video should be in MP4 OR MOV format, and Image can be of any size</p>
																	</div>
																	<span  id="showerror" class="form-error help-block"></span>
																</div>

																<div class="post_area_footer ">
																	<a onclick="publish_content('0');" class="dis_btn post_b ad_publish_btn">publish <span class="hideme publish_btn"> <i class="fa fa-spinner fa-pulse fa-fw"></i></span> </a>
																</div>
																</div>
															</div>


														</div>
													</div>
													<div class="" style="clear:both;"></div>
												<?php } ?>


												<div id="publish_post">
													<?php echo $skelton ; ?>
												</div>
												<div id="publish_post2"></div>

											</div>


											<div class="dis_loadmore_loader">
												<div class="pro_loader" data-load="1" style="display: none;">
													<div class="">
															<img src="<?=CDN_BASE_URL?>repo/images/section_loader.gif" id="preloader_image" alt="loader">
														<?php //echo $this->common_html->content_loader_html() ; ?>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>
							<!-- Home tab -->
							<div role="tabpanel" class="tab-pane" id="about">
								<div class="user_tab_wrapper">
								<div class="artist_profile_about">
									<div class="row">
										<div class="col-lg-12 col-md-12">
											<div class="artist_about_section p_b_30">
													<div class="artist_about_heading dash_abt">
													<h6 class="tab_title">about</h6>
													<?php if(isset($is_session_uid) && $is_session_uid == 1){ ?>
													<a class="add_content" id="EditAboutMe">
														<span class="ac_span">Edit</span>
														<i class="fa fa-edit" aria-hidden="true"></i>
														<!--span class="info_tooltip">You can add more content by hitting ADD button.</span-->
													</a>
													<?php } ?>
													</div>
												<?php  $abt_arr = json_decode($userDetail[0]['uc_about']);?>

												<div class='artist_about_detail EditAboutMe'>
												<?php	echo (!empty($abt_arr))? $abt_arr :  $this->common_html->content_not_available_html() ; ?>
												</div>

												<?php if(isset($is_session_uid) && $is_session_uid == 1){ ?>
														<div class="about_body hide" >
															<form method="POST" >
																<textarea name="ckeditor" ><?php echo $abt_arr; ?></textarea>
																<div class="text-right">
																<button type="submit" class="dis_btn h_40" id="submitAboutMe">save</button>
																</div>
															</form>
														</div>
												<?php } ?>

											</div>
										</div>
									</div>
								</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="media">
								<div class="user_tab_wrapper">
									<div class="artist_profile_about p_b_30">
										<div class="row">
										<div class="col-lg-12 col-md-12">
											<div class="artist_about_section">
												<div class="artist_media_detail">
												<ul class="nav nav-tabs audition_tab media_tab" role="tablist">

													<li class="active" role="presentation"><a href="#photo" aria-controls="photo" role="tab" data-toggle="tab" aria-expanded="true">SOCIAL PHOTOS</a></li>
													<li role="presentation" >
													<a class="load_post_content" data-type="video" href="#video" aria-controls="video" role="tab" data-toggle="tab">SOCIAL VIDEOS</a></li>

												</ul>
												<div class="tab-content">
													<div role="tabpanel" class="tab-pane active notab-pane" id="photo">
														<div class="user_media_wrapper">
														<ul class="ul_grid" id="image_content">

															<?php echo $this->common_html->content_loader_html(); ?>
															<!--all image content load-->

														</ul>
														</div>
														<div class="profile_media_btn text-center load-content1" data-load-contnet="image" data-load-contnet-count="0" data-id="image_content" style="display:none;">
														<a class="dis_btn h_40" >Load More</a>
														</div>
													</div>
													<div role="tabpanel" class="tab-pane notab-pane" id="video">
														<div class="user_media_wrapper">
														<ul class="ul_grid" id="video_content">

															<?php echo $this->common_html->content_loader_html(); ?>
															<!--all video content load-->

															</ul>
														</div>
														<div class="profile_media_btn text-center load-content1" data-load-contnet="video" data-load-contnet-count="0" data-id="video_content" style="display:none;">
															<a class="dis_btn h_40">Load More</a>
														</div>
													</div>
												</div>

												</div>
											</div>
										</div>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="shows">
								<div class="user_tab_wrapper">
									<div class="artist_profile_about">
										<div class="col-lg-12 col-md-12">
											<div class="artist_about_section">
												<div class="artist_about_heading">
												<h6 class="tab_title">shows</h6>
												</div>
												<div class="artist_about_detail">
												<p>No Shows yet...</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="merchandise">
								<div class="user_tab_wrapper">
									<div class="artist_profile_about">
										<div class="col-lg-11 col-md-11">
											<div class="artist_about_section">
												<div class="artist_about_heading">
												<h6 class="tab_title">merchandise</h6>
												</div>
												<div class="artist_about_detail">
												<p>No Merchandise yet...</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
    <?php /************** BELOW SECTION OF PROFILE PAGE , ENDS   ***************************/ ?>
</div>

<!-- Gamification -->
<?php if(isset($is_session_uid) && $is_session_uid == 1) : ?>
<div id="gam-profile-poll-trivia-root"></div>
<?php endif; ?>

<!-- user profile page -->
<script>
	// setInterval(function(){
	// 	googletag.cmd.push(function() {
	// 		console.log('Refresh Ads');
	// 		googletag.pubads().refresh([adSlot1]);
	// 	});
	// }, 90000);
</script>
