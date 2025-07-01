<?php echo $this->common_html->uploadCoverVideoModel();?>

<?php
if(!isset($social)){ ?>
	<div class="au_video_popup">
		<?php echo $this->common_html->au_video_popup($cover_video);?>
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
<div class="dis_user_data_wrapper" id="section2"  <?php if(isset($social)){ echo 'dis_single_social';}?> >
	<div class="container gam-add-side" id="ScrollToHome">
		<div class="row">
			<div class="col-lg-8">	
				<?=isset($common_header)? $common_header : '';?>
				<div class="row ">
					<div class="col-lg-4 col-md-4" id="ColMd4">
						<!-- sidebar --> 
						<div class="right_sidebar_wrapper">
						<?=isset($user_introduction)? $user_introduction : '';?>
						</div>
					</div>
					<div class="col-lg-8 col-md-8" id="ColMd8">
						<div id="gam-collection-root"></div>
					</div>
				</div>
			</div>
			
			<div class="col-lg-4 gam-wc-wrapper">
				<div id="gam-leaderboard-challenge-root"></div>
			</div>
		</div>
	</div>
    <?php /************** BELOW SECTION OF PROFILE PAGE , ENDS   ***************************/ ?>
</div>

<!-- Gamification -->
<div class="gam-focus-overlay"></div>
<!-- <div id="gam-fan-profile-sections-tutorial-root"></div>
<div id="gam-creator-profile-sections-tutorial-root"></div> -->
<div id="gam-user-tutorials-root"></div>
