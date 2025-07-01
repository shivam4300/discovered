

<?php echo $this->common_html->uploadCoverVideoModel();?>
	<!-- Full width POPUP -->
<div class="au_video_popup">
	<?php echo $this->common_html->au_video_popup($cover_video);?>
</div>

<?php $cnah =  $this->common_html->content_not_available_html();?>

<script>
	const 	cnah = `<?=$cnah?>`;
</script>

<div class="audition_main_wrapper au_banner_section">
	<div class="user_profile_page">
	<?php if(isset($cover_video['url']) && !empty($cover_video['url'])){ ?>
		
		<div class="user_profile_wrapper <?php echo !empty($cover_video['url'])? 'edit_cover_video' : '' ;?>" >
			<div class="Flexible-container">
				<a class="speaker mute" data-video="cover_banner_video">
					<span></span>
				</a> 
			
				<video autoplay muted loop class="banner_video cover_banner_video">
					<source src="<?= $cover_video['url'] ?>" type="video/mp4">
				</video>
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
					
				</div>
				
				<div class="dis_scroll_div">
					<a href="#section2"><img src="<?php echo base_url();?>repo/images/scroll_icon.png" class="img-responsive" alt=""></a>
				</div>
		</div>
		<?php } ?>
	</div>
</div>

		
<div class="dis_user_data_wrapper" id="section2">
	<div class="container"  id="ScrollToHome">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="user_tab_section">
					<ul class="nav nav-tabs audition_tab" role="tablist">
						<?php $prolink = (isset($other_user))?'?user='.$other_user:''; ?>
						<li role="presentation"><a href="<?php echo base_url('profile'.$prolink); ?>" aria-controls="about" > Social</a></li>
						<li role="presentation" class="updateSwiper"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Channel</a></li>
						<!--li role="presentation" class="active" onclick="setTimeout(function(){ swiperslider($('#store')); },1000)"><a href="#store" aria-controls="store" role="tab" data-toggle="tab" >Store</a></li-->
						<li role="presentation" class="active" onclick="setTimeout(function(){ swiperslider($('#store')); },1000)"><a href="<?php echo base_url('shop'.$prolink); ?>" >Store</a></li>
						<li role="presentation"><a class="PlayListTab" href="#playlist" aria-controls="playlist" role="tab" data-toggle="tab">playlist</a></li>
						<li role="presentation"><a href="<?= base_url('profile'.$prolink.'#about'); ?>" aria-controls="about" >About</a></li>
						<?php echo '<li role="presentation" class=""><a title="Live Chat"  href="'.base_url('profile'.$prolink.'#chat_message').'" class="mob_hide">message</a></li>'; ?>
						<li role="presentation" class="temporary_disable hideme"><a title="Coming Soon" aria-controls="media" class="mob_hide">Media</a></li>
						<li role="presentation" class="temporary_disable hideme"><a title="Coming Soon" aria-controls="message" class="mob_hide">message</a></li>
						<li role="presentation" class="temporary_disable hideme"><a title="Coming Soon" aria-controls="shows" class="mob_hide">Tickets</a></li>
						<li role="presentation" class="temporary_disable hideme"><a title="Coming Soon" aria-controls="merchandise" class="mob_hide">merchandise</a></li>
						
						<?php if(isset($is_session_uid) && $is_session_uid == 1){ 
							echo (isset($_SESSION['is_iva']) && $_SESSION['is_iva'])?'<li role="presentation"><a href="'.base_url('search/search_iva').'">IVA Data Mapping</a></li>':''; 
						} ?> 
						<?php if(isset($is_session_uid) && $is_session_uid == 1){ ?>
							<?php echo (isset($_SESSION['is_ele']) && $_SESSION['is_ele'])?'<li role="presentation"><a href="'.base_url('Videoelephant').'">Videoelephant</a></li>':''; 
						} ?> 
						<!--li role="presentation"><a href="<?= base_url('dashboard#more'); ?>" aria-controls="more" >more
						
						<i class="fa fa-caret-down" aria-hidden="true"></i></a>
						
							<ul>
							
								<li><a >Casting Calls</a></li>
								<li><a >Total Fans</a></li>
								<li><a >Video Plays</a></li>
								<li><a >Suggested Playlists</a></li>
								<li><a >Favorite Artists</a></li>
								<li><a >Band Members</a></li>
								<li><a >Press</a></li>
								<li><a >Facebook</a></li>
								<li><a >Twitter</a></li>
								<li><a >YouTube</a></li>
								<li><a >Artist Blog</a></li>
							</ul>
						</li-->
					</ul> 
				</div>
			</div>
		</div>
	</div>
</div>
		
		
		
		
 <div class="tab-content">
	<div class="dis_popular_video chennel_wrapper p_b_40"> 
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="sidebar_widget widget user_profile homepage_intro">
						<h4 class="widget-title">Introduction</h4>
						<div class="dis_user_data dis_min_height">
						<div class="pro_share common_click" data-share-profile="<?php echo $other_user; ?>">
							<i class="fa fa-share " aria-hidden="true"></i>
						</div>
						
						<input type="hidden" id="data-user_id" data-user_id="<?= (isset($uid))?$uid:'';?>">
						
						<?php if(isset($userDetail[0])){ ?>
						
						<a data-toggle="modal">
							<img class="img-reponsive" id="itemDetail" src="<?php echo $DP;?>" title="<?php echo $userDetail[0]['user_name'];?>" alt="<?php echo $userDetail[0]['user_name'];?>" onerror="this.onerror=null;this.src='<?= user_default_image(); ?>'" >
						</a>
						
						<h3><?php echo $userDetail[0]['user_name'];?></h3>
						
						<?php 
							if(isset($referral_name)){
								echo '<p class="invit_user">(<a href="'. base_url('profile?user='.$referral_by ) .'">Invited by '.$referral_name.'</a>)</p>';
							}
						?>
						
						<p>
							<?=  (isset($userDetail[0]['category_name']))? $userDetail[0]['category_name']:''; ?> 
							<?=  (isset($sub_catname) && !empty($sub_catname))? '('.$sub_catname.')' :'';?> 
							<br> 
							<?=   ucwords( $userDetail[0]['uc_city']);?> 
							<?=  (isset($userDetail[0]['name']))? ','.$userDetail[0]['name']:'';?>  
							<?=  (isset($userDetail[0]['country_name']))? ','.$userDetail[0]['country_name']:'';?>
						</p>
						
						<!--span><?php echo date_format(date_create ( $userDetail[0]['user_regdate'] ) , 'M d, Y');?></span-->
						
						<?php } ?>
						
							<div class="conditional_button">
							<?php
							
								$open = 1;
								if(isset($is_session_uid) && $is_session_uid != 1){
									echo FanButton($uid);
									echo '<span class="EndorseButton">';
									// echo EndorseButton($uid);
									echo '</span>';
									
									$open = 0;
								} 
							?>
							</div>
						</div>
					</div>
					
					<?php if(isset($userDetail[0]['is_fc_member']) && $userDetail[0]['is_fc_member'] == 1){ ?>
					<div class="sidebar_widget widget founders_club">
					<h2 class="dis_fcm">
					<span class="dis_fcm_icon">
						<img src="<?php echo base_url('repo/images/founder_club.svg');?>"></a>	
					</span>
					Founders Club Member
					</h2>
					</div>
					<?php } ?>
					
				</div>
				
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<h2 class="my_channel_heading">Featured Video</h2>
					<div class="dis_userhome_video" id="feature_area<?= isset($feature_pid)?$feature_pid:''; ?>"> 
						<?php if(isset($single_video) && !empty($single_video)){ ?>	
							<div class="pro_chennel_video">
								<video autoplay loop muted class="feature_video">
									<source src="<?= $feature_video ; ?>" type="video/mp4">
								</video>
								<div class="dis_sld_preview" onclick="openVideoDiscription(<?= $feature_pid; ?>,<?= $open; ?>)">
									<span class="preview_txt">Sneak Peek</span>
									<span class="pre_icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
										<defs>
										<style>
											.cls-1 {
											fill: #f0e9e9;
											fill-rule: evenodd;
											}
										</style>
										</defs>
										<path class="cls-1" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
									</svg>
									</span> 
								</div>
								<div class="chennel_play">
									<a href="<?php echo $single_video; ?>">
									<img src="<?= base_url('players/img/logo.png'); ?>" class="img-responsive" alt="video-logo"/>
									</a>
								</div>
								<div class="feature_vidmute">
									<a class="speaker mute" data-video="feature_video">
										<span></span>
									</a>
								</div>
							</div>
							<div class="chnl_btm_wrp">
								<h3 class="chennel_vtitle"><?php echo $title ; ?></h3>
								
								<?php if(isset($is_session_uid) && $is_session_uid == 1){
									echo '<a href="'.base_url('backend/advertising').'" class="dis_btn h_40">choose featured video</a>';
								}?>
								
							</div>
						<?php }else{
							echo $cnah;
						} ?>
					
					</div>
				</div>
			</div>
		</div>
	</div>
 	
	
	<!--div role="tabpanel" class="tab-pane active" id="home">
		<div class="dis_popular_video playlist_slider p_b_40" id="my_playList_append"></div>
		<div id="appendChannelSlider"></div>
		<div id="home_image_video_upload"></div>
	</div-->

	<div role="tabpanel" class="tab-pane active" id="store">
		<div class="dis_channelProducts_slider" id="appendMyShopSlider">
			<!--iv class="">
				<div class="dis_sliderheading">
					<h2 class="dis_sliderheading_ttl muli_font">Our New Product Teasers</h2>
					<div class="dis_sh_btnwrap">
						<a href="" class="dis_sh_btn muli_font">See all
							<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">	<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path></svg></span>
						</a>
					</div>
				</div>
				<div class="au_artist_slider" data-autoplay="2000">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="dis_product_box">
									<div class="dis_product_img">
										<img src="<?php echo base_url('repo/images/products/product1.png'); ?>" class="img-responsive" alt="Product Image">
										<div class="dis_product_overlay">
											<ul class="dis_product_detailsList">
												<li>
													<a href="#" data-toggle="tooltip" title="View Product" role="tooltip">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink"width="21px" height="14px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)"d="M10.497,4.202 C8.948,4.234 7.718,5.511 7.751,7.053 C7.783,8.596 9.065,9.820 10.614,9.788 C12.139,9.756 13.360,8.516 13.360,6.996 C13.343,5.438 12.061,4.187 10.497,4.202 ZM10.497,0.012 C5.895,-0.009 1.742,2.754 -0.000,6.996 C1.742,11.237 5.896,14.001 10.498,13.979 C15.100,14.002 19.255,11.238 20.996,6.996 C19.255,2.753 15.099,-0.011 10.497,0.012 ZM10.497,11.651 C7.889,11.676 5.754,9.593 5.726,6.996 C5.767,4.371 7.937,2.277 10.572,2.318 C13.149,2.359 15.228,4.429 15.269,6.996 C15.240,9.593 13.105,11.677 10.497,11.651 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Add To Cart">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="21px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M19.214,6.789 C18.646,9.237 18.078,11.685 17.505,14.131 C17.315,14.943 16.867,15.316 16.094,15.317 C12.464,15.319 8.835,15.319 5.205,15.316 C4.354,15.316 3.879,14.832 3.774,13.920 C3.370,10.388 2.957,6.858 2.547,3.327 C2.532,3.199 2.504,3.074 2.474,2.905 C2.160,2.905 1.880,2.915 1.600,2.903 C1.043,2.878 0.633,2.420 0.644,1.849 C0.654,1.295 1.058,0.856 1.599,0.840 C2.121,0.824 2.644,0.834 3.166,0.836 C3.885,0.839 4.218,1.143 4.320,1.915 C4.468,3.037 4.604,4.161 4.747,5.284 C4.761,5.396 4.784,5.506 4.811,5.662 C4.978,5.662 5.134,5.662 5.290,5.662 C9.656,5.662 14.022,5.662 18.389,5.663 C19.172,5.663 19.403,5.974 19.214,6.789 ZM6.428,16.696 C7.497,16.695 8.368,17.636 8.360,18.782 C8.353,19.908 7.486,20.831 6.435,20.832 C5.368,20.834 4.495,19.891 4.502,18.746 C4.510,17.621 5.378,16.698 6.428,16.696 ZM14.177,16.696 C15.226,16.709 16.082,17.643 16.078,18.772 C16.075,19.915 15.192,20.846 14.123,20.832 C13.074,20.819 12.218,19.884 12.221,18.756 C12.224,17.614 13.108,16.683 14.177,16.696 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Wishlist icon">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M10.184,3.466 C10.894,2.263 11.813,1.431 13.029,1.012 C14.995,0.335 17.166,1.134 18.410,3.043 C19.620,4.899 19.822,6.908 19.018,9.007 C18.305,10.864 17.095,12.336 15.762,13.698 C14.193,15.301 12.457,16.671 10.617,17.892 C10.362,18.061 10.124,18.143 9.841,17.955 C7.165,16.182 4.669,14.178 2.700,11.506 C1.787,10.268 1.068,8.913 0.902,7.307 C0.600,4.370 2.333,1.422 5.015,0.885 C7.041,0.481 8.642,1.301 9.884,3.014 C9.979,3.145 10.064,3.284 10.184,3.466 Z"/></svg>
													</a>
												</li>
											</ul>
											<div class="dis_product_btnwrap">
												<a href="#" class="dis_black_btn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_product_content">
										<a class="dis_product_ttl muli_font" href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">Our feelings in games #GerçekMucize</a></h3>
										<h2 class="dis_product_price mp_0"><span class="dis_cutPrice">$45.95</span>$4.95</h2>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="dis_product_box">
									<div class="dis_product_img">
										<img src="<?php echo base_url('repo/images/products/product2.png'); ?>" class="img-responsive" alt="Product Image">
										<div class="dis_product_overlay">
											<div class="dis_catgry_btnwrap">
												<a href="#" class="dis_black_Lbtn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_product_content">
										<a class="dis_product_ttl muli_font" href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">Our feelings in games #GerçekMucize</a></h3>
										<ul class="dis_pro_rating">
											<li>
												<a href="#">
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="12" height="12" viewBox="0 0 511.99143 511" ><g><path xmlns="http://www.w3.org/2000/svg" d="m510.652344 185.882812c-3.371094-10.367187-12.566406-17.707031-23.402344-18.6875l-147.796875-13.417968-58.410156-136.75c-4.3125-10.046875-14.125-16.53125-25.046875-16.53125s-20.738282 6.484375-25.023438 16.53125l-58.410156 136.75-147.820312 13.417968c-10.835938 1-20.011719 8.339844-23.402344 18.6875-3.371094 10.367188-.257813 21.738282 7.9375 28.925782l111.722656 97.964844-32.941406 145.085937c-2.410156 10.667969 1.730468 21.699219 10.582031 28.097656 4.757813 3.457031 10.347656 5.183594 15.957031 5.183594 4.820313 0 9.644532-1.28125 13.953125-3.859375l127.445313-76.203125 127.421875 76.203125c9.347656 5.585938 21.101562 5.074219 29.933593-1.324219 8.851563-6.398437 12.992188-17.429687 10.582032-28.097656l-32.941406-145.085937 111.722656-97.964844c8.191406-7.1875 11.308594-18.535156 7.9375-28.925782zm-252.203125 223.722657" fill="rgb(238 180 6)" data-original="rgb(238 180 6)"></path></g></svg>
												</a>
											</li>
											<li>
												<a href="#">
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="12" height="12" viewBox="0 0 511.99143 511" ><g><path xmlns="http://www.w3.org/2000/svg" d="m510.652344 185.882812c-3.371094-10.367187-12.566406-17.707031-23.402344-18.6875l-147.796875-13.417968-58.410156-136.75c-4.3125-10.046875-14.125-16.53125-25.046875-16.53125s-20.738282 6.484375-25.023438 16.53125l-58.410156 136.75-147.820312 13.417968c-10.835938 1-20.011719 8.339844-23.402344 18.6875-3.371094 10.367188-.257813 21.738282 7.9375 28.925782l111.722656 97.964844-32.941406 145.085937c-2.410156 10.667969 1.730468 21.699219 10.582031 28.097656 4.757813 3.457031 10.347656 5.183594 15.957031 5.183594 4.820313 0 9.644532-1.28125 13.953125-3.859375l127.445313-76.203125 127.421875 76.203125c9.347656 5.585938 21.101562 5.074219 29.933593-1.324219 8.851563-6.398437 12.992188-17.429687 10.582032-28.097656l-32.941406-145.085937 111.722656-97.964844c8.191406-7.1875 11.308594-18.535156 7.9375-28.925782zm-252.203125 223.722657" fill="rgb(238 180 6)" data-original="rgb(238 180 6)"></path></g></svg>
												</a>
											</li>
											<li>
												<a href="#">
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="12" height="12" viewBox="0 0 511.99143 511" ><g><path xmlns="http://www.w3.org/2000/svg" d="m510.652344 185.882812c-3.371094-10.367187-12.566406-17.707031-23.402344-18.6875l-147.796875-13.417968-58.410156-136.75c-4.3125-10.046875-14.125-16.53125-25.046875-16.53125s-20.738282 6.484375-25.023438 16.53125l-58.410156 136.75-147.820312 13.417968c-10.835938 1-20.011719 8.339844-23.402344 18.6875-3.371094 10.367188-.257813 21.738282 7.9375 28.925782l111.722656 97.964844-32.941406 145.085937c-2.410156 10.667969 1.730468 21.699219 10.582031 28.097656 4.757813 3.457031 10.347656 5.183594 15.957031 5.183594 4.820313 0 9.644532-1.28125 13.953125-3.859375l127.445313-76.203125 127.421875 76.203125c9.347656 5.585938 21.101562 5.074219 29.933593-1.324219 8.851563-6.398437 12.992188-17.429687 10.582032-28.097656l-32.941406-145.085937 111.722656-97.964844c8.191406-7.1875 11.308594-18.535156 7.9375-28.925782zm-252.203125 223.722657" fill="rgb(238 180 6)" data-original="rgb(238 180 6)"></path></g></svg>
												</a>
											</li>
											<li>
												<a href="#">
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="12" height="12" viewBox="0 0 511.99143 511" ><g><path xmlns="http://www.w3.org/2000/svg" d="m510.652344 185.882812c-3.371094-10.367187-12.566406-17.707031-23.402344-18.6875l-147.796875-13.417968-58.410156-136.75c-4.3125-10.046875-14.125-16.53125-25.046875-16.53125s-20.738282 6.484375-25.023438 16.53125l-58.410156 136.75-147.820312 13.417968c-10.835938 1-20.011719 8.339844-23.402344 18.6875-3.371094 10.367188-.257813 21.738282 7.9375 28.925782l111.722656 97.964844-32.941406 145.085937c-2.410156 10.667969 1.730468 21.699219 10.582031 28.097656 4.757813 3.457031 10.347656 5.183594 15.957031 5.183594 4.820313 0 9.644532-1.28125 13.953125-3.859375l127.445313-76.203125 127.421875 76.203125c9.347656 5.585938 21.101562 5.074219 29.933593-1.324219 8.851563-6.398437 12.992188-17.429687 10.582032-28.097656l-32.941406-145.085937 111.722656-97.964844c8.191406-7.1875 11.308594-18.535156 7.9375-28.925782zm-252.203125 223.722657" fill="rgb(238 180 6)" data-original="rgb(238 180 6)"></path></g></svg>
												</a>
											</li>
											<li>
												<a href="#">
												<svg xmlns="http://www.w3.org/2000/svg" width="12.81" height="12" viewBox="0 0 12.81 12">
													<path fill="#6a767d" fill-rule="evenodd" d="M1292.55,363.683c0.12-.7.24-1.42,0.37-2.137q0.135-.776.27-1.549a0.341,0.341,0,0,0-.12-0.351c-0.87-.825-1.74-1.657-2.6-2.485a0.436,0.436,0,0,1-.21-0.49,0.514,0.514,0,0,1,.5-0.286c1.18-.168,2.36-0.34,3.54-0.5a0.366,0.366,0,0,0,.33-0.231c0.53-1.064,1.07-2.123,1.59-3.186a0.485,0.485,0,0,1,.46-0.343,0.48,0.48,0,0,1,.43.341c0.53,1.069,1.07,2.136,1.6,3.206a0.331,0.331,0,0,0,.29.207c1.17,0.16,2.33.331,3.5,0.5,0.09,0.013.18,0.023,0.27,0.044a0.357,0.357,0,0,1,.21.618c-0.09.1-.19,0.194-0.29,0.289-0.81.773-1.61,1.549-2.42,2.316a0.368,0.368,0,0,0-.13.374l0.63,3.563a0.408,0.408,0,0,1-.14.468,0.466,0.466,0,0,1-.53-0.019c-1.08-.558-2.16-1.111-3.23-1.673a0.386,0.386,0,0,0-.41,0q-1.62.853-3.27,1.691A0.4,0.4,0,0,1,1292.55,363.683Z" transform="translate(-1290.25 -352.125)"/>
												</svg>
												</a>
											</li>
										</ul>
										<h2 class="dis_product_price mp_0"><span class="dis_cutPrice">$45.95</span>$4.95</h2>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="dis_product_box">
									<div class="dis_product_img">
										<img src="<?php echo base_url('repo/images/products/product3.png'); ?>" class="img-responsive" alt="Product Image">
										<div class="dis_product_overlay">
											<ul class="dis_product_detailsList">
												<li>
													<a href="#" data-toggle="tooltip" title="View Product" role="tooltip">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink"width="21px" height="14px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)"d="M10.497,4.202 C8.948,4.234 7.718,5.511 7.751,7.053 C7.783,8.596 9.065,9.820 10.614,9.788 C12.139,9.756 13.360,8.516 13.360,6.996 C13.343,5.438 12.061,4.187 10.497,4.202 ZM10.497,0.012 C5.895,-0.009 1.742,2.754 -0.000,6.996 C1.742,11.237 5.896,14.001 10.498,13.979 C15.100,14.002 19.255,11.238 20.996,6.996 C19.255,2.753 15.099,-0.011 10.497,0.012 ZM10.497,11.651 C7.889,11.676 5.754,9.593 5.726,6.996 C5.767,4.371 7.937,2.277 10.572,2.318 C13.149,2.359 15.228,4.429 15.269,6.996 C15.240,9.593 13.105,11.677 10.497,11.651 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Add To Cart">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="21px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M19.214,6.789 C18.646,9.237 18.078,11.685 17.505,14.131 C17.315,14.943 16.867,15.316 16.094,15.317 C12.464,15.319 8.835,15.319 5.205,15.316 C4.354,15.316 3.879,14.832 3.774,13.920 C3.370,10.388 2.957,6.858 2.547,3.327 C2.532,3.199 2.504,3.074 2.474,2.905 C2.160,2.905 1.880,2.915 1.600,2.903 C1.043,2.878 0.633,2.420 0.644,1.849 C0.654,1.295 1.058,0.856 1.599,0.840 C2.121,0.824 2.644,0.834 3.166,0.836 C3.885,0.839 4.218,1.143 4.320,1.915 C4.468,3.037 4.604,4.161 4.747,5.284 C4.761,5.396 4.784,5.506 4.811,5.662 C4.978,5.662 5.134,5.662 5.290,5.662 C9.656,5.662 14.022,5.662 18.389,5.663 C19.172,5.663 19.403,5.974 19.214,6.789 ZM6.428,16.696 C7.497,16.695 8.368,17.636 8.360,18.782 C8.353,19.908 7.486,20.831 6.435,20.832 C5.368,20.834 4.495,19.891 4.502,18.746 C4.510,17.621 5.378,16.698 6.428,16.696 ZM14.177,16.696 C15.226,16.709 16.082,17.643 16.078,18.772 C16.075,19.915 15.192,20.846 14.123,20.832 C13.074,20.819 12.218,19.884 12.221,18.756 C12.224,17.614 13.108,16.683 14.177,16.696 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Wishlist icon">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M10.184,3.466 C10.894,2.263 11.813,1.431 13.029,1.012 C14.995,0.335 17.166,1.134 18.410,3.043 C19.620,4.899 19.822,6.908 19.018,9.007 C18.305,10.864 17.095,12.336 15.762,13.698 C14.193,15.301 12.457,16.671 10.617,17.892 C10.362,18.061 10.124,18.143 9.841,17.955 C7.165,16.182 4.669,14.178 2.700,11.506 C1.787,10.268 1.068,8.913 0.902,7.307 C0.600,4.370 2.333,1.422 5.015,0.885 C7.041,0.481 8.642,1.301 9.884,3.014 C9.979,3.145 10.064,3.284 10.184,3.466 Z"/></svg>
													</a>
												</li>
											</ul>
											<div class="dis_product_btnwrap">
												<a href="#" class="dis_black_btn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_product_content">
										<a class="dis_product_ttl muli_font" href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">Our feelings in games #GerçekMucize</a></h3>
										<h2 class="dis_product_price mp_0"><span class="dis_cutPrice">$45.95</span>$4.95</h2>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="dis_product_box">
									<div class="dis_product_img">
										<img src="<?php echo base_url('repo/images/products/product4.png'); ?>" class="img-responsive" alt="Product Image">
										<div class="dis_product_overlay">
											<ul class="dis_product_detailsList">
												<li>
													<a href="#" data-toggle="tooltip" title="View Product" role="tooltip">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink"width="21px" height="14px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)"d="M10.497,4.202 C8.948,4.234 7.718,5.511 7.751,7.053 C7.783,8.596 9.065,9.820 10.614,9.788 C12.139,9.756 13.360,8.516 13.360,6.996 C13.343,5.438 12.061,4.187 10.497,4.202 ZM10.497,0.012 C5.895,-0.009 1.742,2.754 -0.000,6.996 C1.742,11.237 5.896,14.001 10.498,13.979 C15.100,14.002 19.255,11.238 20.996,6.996 C19.255,2.753 15.099,-0.011 10.497,0.012 ZM10.497,11.651 C7.889,11.676 5.754,9.593 5.726,6.996 C5.767,4.371 7.937,2.277 10.572,2.318 C13.149,2.359 15.228,4.429 15.269,6.996 C15.240,9.593 13.105,11.677 10.497,11.651 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Add To Cart">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="21px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M19.214,6.789 C18.646,9.237 18.078,11.685 17.505,14.131 C17.315,14.943 16.867,15.316 16.094,15.317 C12.464,15.319 8.835,15.319 5.205,15.316 C4.354,15.316 3.879,14.832 3.774,13.920 C3.370,10.388 2.957,6.858 2.547,3.327 C2.532,3.199 2.504,3.074 2.474,2.905 C2.160,2.905 1.880,2.915 1.600,2.903 C1.043,2.878 0.633,2.420 0.644,1.849 C0.654,1.295 1.058,0.856 1.599,0.840 C2.121,0.824 2.644,0.834 3.166,0.836 C3.885,0.839 4.218,1.143 4.320,1.915 C4.468,3.037 4.604,4.161 4.747,5.284 C4.761,5.396 4.784,5.506 4.811,5.662 C4.978,5.662 5.134,5.662 5.290,5.662 C9.656,5.662 14.022,5.662 18.389,5.663 C19.172,5.663 19.403,5.974 19.214,6.789 ZM6.428,16.696 C7.497,16.695 8.368,17.636 8.360,18.782 C8.353,19.908 7.486,20.831 6.435,20.832 C5.368,20.834 4.495,19.891 4.502,18.746 C4.510,17.621 5.378,16.698 6.428,16.696 ZM14.177,16.696 C15.226,16.709 16.082,17.643 16.078,18.772 C16.075,19.915 15.192,20.846 14.123,20.832 C13.074,20.819 12.218,19.884 12.221,18.756 C12.224,17.614 13.108,16.683 14.177,16.696 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Wishlist icon">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M10.184,3.466 C10.894,2.263 11.813,1.431 13.029,1.012 C14.995,0.335 17.166,1.134 18.410,3.043 C19.620,4.899 19.822,6.908 19.018,9.007 C18.305,10.864 17.095,12.336 15.762,13.698 C14.193,15.301 12.457,16.671 10.617,17.892 C10.362,18.061 10.124,18.143 9.841,17.955 C7.165,16.182 4.669,14.178 2.700,11.506 C1.787,10.268 1.068,8.913 0.902,7.307 C0.600,4.370 2.333,1.422 5.015,0.885 C7.041,0.481 8.642,1.301 9.884,3.014 C9.979,3.145 10.064,3.284 10.184,3.466 Z"/></svg>
													</a>
												</li>
											</ul>
											<div class="dis_product_btnwrap">
												<a href="#" class="dis_black_btn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_product_content">
										<a class="dis_product_ttl muli_font" href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">Our feelings in games #GerçekMucize</a></h3>
										<h2 class="dis_product_price mp_0"><span class="dis_cutPrice">$45.95</span>$4.95</h2>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="dis_product_box">
									<div class="dis_product_img">
										<img src="<?php echo base_url('repo/images/products/product1.png'); ?>" class="img-responsive" alt="Product Image">
										<div class="dis_product_overlay">
											<ul class="dis_product_detailsList">
												<li>
													<a href="#" data-toggle="tooltip" title="View Product" role="tooltip">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink"width="21px" height="14px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)"d="M10.497,4.202 C8.948,4.234 7.718,5.511 7.751,7.053 C7.783,8.596 9.065,9.820 10.614,9.788 C12.139,9.756 13.360,8.516 13.360,6.996 C13.343,5.438 12.061,4.187 10.497,4.202 ZM10.497,0.012 C5.895,-0.009 1.742,2.754 -0.000,6.996 C1.742,11.237 5.896,14.001 10.498,13.979 C15.100,14.002 19.255,11.238 20.996,6.996 C19.255,2.753 15.099,-0.011 10.497,0.012 ZM10.497,11.651 C7.889,11.676 5.754,9.593 5.726,6.996 C5.767,4.371 7.937,2.277 10.572,2.318 C13.149,2.359 15.228,4.429 15.269,6.996 C15.240,9.593 13.105,11.677 10.497,11.651 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Add To Cart">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="21px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M19.214,6.789 C18.646,9.237 18.078,11.685 17.505,14.131 C17.315,14.943 16.867,15.316 16.094,15.317 C12.464,15.319 8.835,15.319 5.205,15.316 C4.354,15.316 3.879,14.832 3.774,13.920 C3.370,10.388 2.957,6.858 2.547,3.327 C2.532,3.199 2.504,3.074 2.474,2.905 C2.160,2.905 1.880,2.915 1.600,2.903 C1.043,2.878 0.633,2.420 0.644,1.849 C0.654,1.295 1.058,0.856 1.599,0.840 C2.121,0.824 2.644,0.834 3.166,0.836 C3.885,0.839 4.218,1.143 4.320,1.915 C4.468,3.037 4.604,4.161 4.747,5.284 C4.761,5.396 4.784,5.506 4.811,5.662 C4.978,5.662 5.134,5.662 5.290,5.662 C9.656,5.662 14.022,5.662 18.389,5.663 C19.172,5.663 19.403,5.974 19.214,6.789 ZM6.428,16.696 C7.497,16.695 8.368,17.636 8.360,18.782 C8.353,19.908 7.486,20.831 6.435,20.832 C5.368,20.834 4.495,19.891 4.502,18.746 C4.510,17.621 5.378,16.698 6.428,16.696 ZM14.177,16.696 C15.226,16.709 16.082,17.643 16.078,18.772 C16.075,19.915 15.192,20.846 14.123,20.832 C13.074,20.819 12.218,19.884 12.221,18.756 C12.224,17.614 13.108,16.683 14.177,16.696 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Wishlist icon">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M10.184,3.466 C10.894,2.263 11.813,1.431 13.029,1.012 C14.995,0.335 17.166,1.134 18.410,3.043 C19.620,4.899 19.822,6.908 19.018,9.007 C18.305,10.864 17.095,12.336 15.762,13.698 C14.193,15.301 12.457,16.671 10.617,17.892 C10.362,18.061 10.124,18.143 9.841,17.955 C7.165,16.182 4.669,14.178 2.700,11.506 C1.787,10.268 1.068,8.913 0.902,7.307 C0.600,4.370 2.333,1.422 5.015,0.885 C7.041,0.481 8.642,1.301 9.884,3.014 C9.979,3.145 10.064,3.284 10.184,3.466 Z"/></svg>
													</a>
												</li>
											</ul>
											<div class="dis_product_btnwrap">
												<a href="#" class="dis_black_btn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_product_content">
										<a class="dis_product_ttl muli_font" href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">Our feelings in games #GerçekMucize</a></h3>
										<h2 class="dis_product_price mp_0"><span class="dis_cutPrice">$45.95</span>$4.95</h2>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="dis_product_box">
									<div class="dis_product_img">
										<img src="<?php echo base_url('repo/images/products/product1.png'); ?>" class="img-responsive" alt="Product Image">
										<div class="dis_product_overlay">
											<ul class="dis_product_detailsList">
												<li>
													<a href="#" data-toggle="tooltip" title="View Product" role="tooltip">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink"width="21px" height="14px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)"d="M10.497,4.202 C8.948,4.234 7.718,5.511 7.751,7.053 C7.783,8.596 9.065,9.820 10.614,9.788 C12.139,9.756 13.360,8.516 13.360,6.996 C13.343,5.438 12.061,4.187 10.497,4.202 ZM10.497,0.012 C5.895,-0.009 1.742,2.754 -0.000,6.996 C1.742,11.237 5.896,14.001 10.498,13.979 C15.100,14.002 19.255,11.238 20.996,6.996 C19.255,2.753 15.099,-0.011 10.497,0.012 ZM10.497,11.651 C7.889,11.676 5.754,9.593 5.726,6.996 C5.767,4.371 7.937,2.277 10.572,2.318 C13.149,2.359 15.228,4.429 15.269,6.996 C15.240,9.593 13.105,11.677 10.497,11.651 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Add To Cart">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="21px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M19.214,6.789 C18.646,9.237 18.078,11.685 17.505,14.131 C17.315,14.943 16.867,15.316 16.094,15.317 C12.464,15.319 8.835,15.319 5.205,15.316 C4.354,15.316 3.879,14.832 3.774,13.920 C3.370,10.388 2.957,6.858 2.547,3.327 C2.532,3.199 2.504,3.074 2.474,2.905 C2.160,2.905 1.880,2.915 1.600,2.903 C1.043,2.878 0.633,2.420 0.644,1.849 C0.654,1.295 1.058,0.856 1.599,0.840 C2.121,0.824 2.644,0.834 3.166,0.836 C3.885,0.839 4.218,1.143 4.320,1.915 C4.468,3.037 4.604,4.161 4.747,5.284 C4.761,5.396 4.784,5.506 4.811,5.662 C4.978,5.662 5.134,5.662 5.290,5.662 C9.656,5.662 14.022,5.662 18.389,5.663 C19.172,5.663 19.403,5.974 19.214,6.789 ZM6.428,16.696 C7.497,16.695 8.368,17.636 8.360,18.782 C8.353,19.908 7.486,20.831 6.435,20.832 C5.368,20.834 4.495,19.891 4.502,18.746 C4.510,17.621 5.378,16.698 6.428,16.696 ZM14.177,16.696 C15.226,16.709 16.082,17.643 16.078,18.772 C16.075,19.915 15.192,20.846 14.123,20.832 C13.074,20.819 12.218,19.884 12.221,18.756 C12.224,17.614 13.108,16.683 14.177,16.696 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Wishlist icon">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M10.184,3.466 C10.894,2.263 11.813,1.431 13.029,1.012 C14.995,0.335 17.166,1.134 18.410,3.043 C19.620,4.899 19.822,6.908 19.018,9.007 C18.305,10.864 17.095,12.336 15.762,13.698 C14.193,15.301 12.457,16.671 10.617,17.892 C10.362,18.061 10.124,18.143 9.841,17.955 C7.165,16.182 4.669,14.178 2.700,11.506 C1.787,10.268 1.068,8.913 0.902,7.307 C0.600,4.370 2.333,1.422 5.015,0.885 C7.041,0.481 8.642,1.301 9.884,3.014 C9.979,3.145 10.064,3.284 10.184,3.466 Z"/></svg>
													</a>
												</li>
											</ul>
											<div class="dis_product_btnwrap">
												<a href="#" class="dis_black_btn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_product_content">
										<a class="dis_product_ttl muli_font" href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">Our feelings in games #GerçekMucize</a></h3>
										<h2 class="dis_product_price mp_0"><span class="dis_cutPrice">$45.95</span>$4.95</h2>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="dis_post_video_data dis_product_box">
									<div class="dis_postvideo_img">
										<img src="<?php echo base_url('repo/images/products/product5.png'); ?>" class="img-responsive" alt="Product Image">
										<div class="dis_product_overlay">
											<ul class="dis_product_detailsList">
												<li>
													<a href="#" data-toggle="tooltip" title="Hooray!">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink"width="21px" height="14px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)"d="M10.497,4.202 C8.948,4.234 7.718,5.511 7.751,7.053 C7.783,8.596 9.065,9.820 10.614,9.788 C12.139,9.756 13.360,8.516 13.360,6.996 C13.343,5.438 12.061,4.187 10.497,4.202 ZM10.497,0.012 C5.895,-0.009 1.742,2.754 -0.000,6.996 C1.742,11.237 5.896,14.001 10.498,13.979 C15.100,14.002 19.255,11.238 20.996,6.996 C19.255,2.753 15.099,-0.011 10.497,0.012 ZM10.497,11.651 C7.889,11.676 5.754,9.593 5.726,6.996 C5.767,4.371 7.937,2.277 10.572,2.318 C13.149,2.359 15.228,4.429 15.269,6.996 C15.240,9.593 13.105,11.677 10.497,11.651 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Hooray!">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="21px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M19.214,6.789 C18.646,9.237 18.078,11.685 17.505,14.131 C17.315,14.943 16.867,15.316 16.094,15.317 C12.464,15.319 8.835,15.319 5.205,15.316 C4.354,15.316 3.879,14.832 3.774,13.920 C3.370,10.388 2.957,6.858 2.547,3.327 C2.532,3.199 2.504,3.074 2.474,2.905 C2.160,2.905 1.880,2.915 1.600,2.903 C1.043,2.878 0.633,2.420 0.644,1.849 C0.654,1.295 1.058,0.856 1.599,0.840 C2.121,0.824 2.644,0.834 3.166,0.836 C3.885,0.839 4.218,1.143 4.320,1.915 C4.468,3.037 4.604,4.161 4.747,5.284 C4.761,5.396 4.784,5.506 4.811,5.662 C4.978,5.662 5.134,5.662 5.290,5.662 C9.656,5.662 14.022,5.662 18.389,5.663 C19.172,5.663 19.403,5.974 19.214,6.789 ZM6.428,16.696 C7.497,16.695 8.368,17.636 8.360,18.782 C8.353,19.908 7.486,20.831 6.435,20.832 C5.368,20.834 4.495,19.891 4.502,18.746 C4.510,17.621 5.378,16.698 6.428,16.696 ZM14.177,16.696 C15.226,16.709 16.082,17.643 16.078,18.772 C16.075,19.915 15.192,20.846 14.123,20.832 C13.074,20.819 12.218,19.884 12.221,18.756 C12.224,17.614 13.108,16.683 14.177,16.696 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="#" data-toggle="tooltip" title="Hooray!">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M10.184,3.466 C10.894,2.263 11.813,1.431 13.029,1.012 C14.995,0.335 17.166,1.134 18.410,3.043 C19.620,4.899 19.822,6.908 19.018,9.007 C18.305,10.864 17.095,12.336 15.762,13.698 C14.193,15.301 12.457,16.671 10.617,17.892 C10.362,18.061 10.124,18.143 9.841,17.955 C7.165,16.182 4.669,14.178 2.700,11.506 C1.787,10.268 1.068,8.913 0.902,7.307 C0.600,4.370 2.333,1.422 5.015,0.885 C7.041,0.481 8.642,1.301 9.884,3.014 C9.979,3.145 10.064,3.284 10.184,3.466 Z"/></svg>
													</a>
												</li>
											</ul>
											<div class="dis_product_btnwrap">
												<a href="#" class="dis_black_btn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_postvideo_content">
										<h3><a href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">Our feelings in games #GerçekMucize</a></h3>
										<h2 class="dis_product_price mp_0">$4.95</h2>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="dis_post_video_data dis_product_box">
									<div class="dis_postvideo_img">
										<img src="<?php echo base_url('repo/images/products/product6.png'); ?>" class="img-responsive" alt="Discovered">
										<div class="dis_product_overlay">
											<div class="dis_catgry_btnwrap">
												<a href="#" class="dis_black_Lbtn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_postvideo_content">
										<h3><a href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">Coke Zero Sugar - It’s the #BestCokeEver</a></h3>
										<h2 class="dis_product_price mp_0">$4.95</h2>
									</div>
								</div>
							</div>
						</div>
						<div class="swiper-button-next fvs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
						<div class="swiper-button-prev fvs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div> 
					</div>
				</div>
			</div-->
		</div>
	</div>
</div>












	