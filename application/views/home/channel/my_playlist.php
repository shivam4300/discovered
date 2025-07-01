<?php //echo $this->common_html->uploadCoverVideoModel();?>
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
	<?php if( ( isset($cover_video['url']) && !empty($cover_video['url']) ) || ( isset($is_session_uid) && $is_session_uid != 1 ) ){ ?>

		<div class="user_profile_wrapper <?php echo !empty($cover_video['url'])? 'edit_cover_video' : '' ;?>" >
			<div class="Flexible-container">
				<a class="speaker mute" data-video="cover_banner_video">
					<span></span>
				</a>
				<?php $vidurl = isset($cover_video['url']) && !empty($cover_video['url']) ? $cover_video['url'] : AMAZON_URL.$defaultVideo; ?>
				<video autoplay muted loop class="banner_video cover_banner_video">
					<source src="<?= $vidurl; ?>" type="video/mp4">
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


<!-- Gamification -->
<div class="container-fluid gam-add-side">
	<div class="row">
		<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
			<div class="dis_user_data_wrapper" id="section2">
				<div id="ScrollToHome">
					<?=isset($common_header)? $common_header : '';?>
				</div>
			</div>
			<div class="dis_popular_video chennel_wrapper p_b_40">
				<!-- <div class="container"> -->
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<?=isset($user_introduction)? $user_introduction : '';?>
							<input type="hidden" id="data-user_id" data-user_id="<?= (isset($uid))?$uid:'';?>">
						</div>

						<?=isset($user_featured_video)? $user_featured_video : '';?>
					</div>
				<!-- </div> -->
			</div>
		</div>
		<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 gam-wc-wrapper">
			<div id="gam-leaderboard-challenge-root"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="playlist">
					<div class="dis_viewall_playlist_wrap">
						<!-- <div class="container"> -->
							<div class="row">
								<div class="col-md-12">
									<div class="dis_sliderheading p_b_40">
										<h2 class="dis_sliderheading_ttl muli_font">My Playlists</h2>
										<?php if(isset($is_session_uid) && $is_session_uid == 1){ ?>
										<div class="dis_sh_btnwra">
											<a class="dis_sh_btn muli_font openModalPopup" id="createPlayListBtnn"  data-href="modal/playlist_popup" data-cls="dis_addplaylist_modal dis_center_modal muli_font">Create Playlist
												<span class="dis_sh_btnicon">
													<svg baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
														<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
													</svg>
												</span>
											</a>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div id="playlist_not_found_image"></div>
							<div class="dis_playlist_grid_box" id="myChannelPlaylist"></div>
							</div>
						<!-- </div> -->
					</div>
				</div>
				<div class="dis_playlist_grid_box" id="myChannelPlaylist">
				</div>
			</div>
		</div>
	</div>
</div>












