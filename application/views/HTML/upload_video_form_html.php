<!-- edit text popup-->
<div class="modal fade Audition_popup edit_about_text" id="edit_text" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title update_text_header" id="myModalLabel">update Your text</h4>
      </div>
      <div class="modal-body">
        <div class="upload_img_wrapper">
			<div class="edittext_area">
				<p id="update_text_err" class="help_note"></p>
				<input class="edit_textfield form_field" type="text" placeholder="Heading Text">
				<textarea class="edit_textareafield form_field" rows="2" placeholder="Content Text"></textarea>
				<a class="popup_btn update_btn save_edit_popup_btn"><i class="fa fa-pencil" aria-hidden="true"></i>update</a><span></span>
			</div>
		</div>
      </div>
    </div>
  </div>
</div>
<!-- edit text popup-->
<!-- upload Video-->
<div class="modal fade Audition_popup upload_popup" id="upload_video" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload cover video</h4>
      </div>
      <div class="modal-body">
        <div class="upload_img_wrapper">
			<div class="browse_area">
				<div class="browse_btn_wrapper">
					<p id="upload_video_err" class="help_note"></p>
					<div class="dropzone" id="pro_video_upload" style="min-height: 100px;border: 2px dashed #ff3600;">
						<div class="dz-default dz-message" style="padding: 35px;">
							<i class="fa fa-file-video-o" aria-hidden="true"></i>
							<p class="info_text">Drop a video here or Click to browse</p>
						</div>
					</div>
				</div>
				<p class="help_note"><strong>NOTE:</strong>Video should be in mp4 format.</p>
			</div>
		</div>
      </div>
    </div>
  </div>
</div>
<!-- upload Video-->
<!-- upload Image-->
<div class="modal fade Audition_popup upload_popup" id="upload_image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload profile picture</h4>
      </div>
      <div class="modal-body">
        <div class="upload_img_wrapper">
			<div class="browse_area">
				<div class="browse_btn_wrapper">
					<p id="upload_err" class="help_note"></p>
					<div class="dropzone" id="profile_upload" style="min-height: 100px;border: 2px dashed #ff3600;">
					 <input type='file' id="imgInp" />							<img id="image" src="">
						<div class="dz-default dz-message browse_area" style="padding: 35px;">
							<i class="fa fa-picture-o" aria-hidden="true"></i><p class="info_text">Click to browse </p>
						</div>
						<div id="preview-template" style="display: none;"></div>
					</div>
				</div>
				<p class="help_note"><strong>NOTE:</strong>The best image size is 270x200(WxH).</p>
			</div><button onclick="crop()">Upload</button>
		</div>
      </div>
    </div>
  </div>
</div>
<!-- upload Image-->
<!-- Confirmation Pop Ups -->
<div class="modal fade Audition_popup common_popup" id="confirm_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
</div>


<!-- Confirmation Pop Ups -->
<!-- Full width POPUP -->
<div class="au_video_popup">
	<div class="au_popup_body">
          <a class="speaker" data-video="popup_banner_video">
				<span></span>
		   </a>
		<video   autoplay loop muted  class="banner_video popup_banner_video" >
			<source src="http://app2demo.mintsapp.io/assets/vid/Game_of_throne_7.mp4" type="video/mp4">
		</video>
        <div class="close_btn" id="cover_banner_video"></div>
        <a class="au_tv_logo"><img src="<?php echo $basepath;?>repo/images/logo_on_video.png" alt="Discovered"></a>
		<a class="nav_toggle"><i class="fa fa-bars" aria-hidden="true"></i><!-- <span>removed</span> --></a>
		<div class="audition_menu">
			<div class="au_menu_wrapper genre_sub_menu">
				<div class="main_menu">
				<div class="au_logo"><a href="<?php echo $basepath;?>"><img src="<?php echo $basepath;?>repo/images/logo.png" alt="Discovered"></a></div>
					<div class="video_action_btn">
						<ul class="al_breadcrumb">
							<li><a href="#" class="genre_link">genre</a></li>
							<li><?php echo $gener_text; ?></li>
							<li class="active">up next</li>
						</ul>
						<div class="al_autoplay_btn">
							<h6>autoplay</h6>
							<a href="#" class="info">
								<i class="fa fa-exclamation" aria-hidden="true"></i>
								<span class="audition_tooltip">
									<p>When autoplay is enabled, a suggested video will automatically play next.</p>
								</span>
							</a>
							<div class="checkbox_wrapper">
								<div class="checkbox_btn">
								  <input type="checkbox" value="None" id="autoPlay" name="check" checked />
								  <label for="autoPlay"></label>
								</div>
							</div>
						</div>
					</div>
					<div class="menu_overlay">
						<div class="category_wrapper">
							<ul>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/upnext.jpg" alt="">
									</a><a href="#" class="genre_info">up next</a>
										<div class="category_info"><a href="#" class="category_name">No Sleeep Feat. J. Cole<small>Janet Jackson</small></a></div>
									</div>
								</li>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/upnext1.jpg" alt=""></a>
									</div>
								</li>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/upnext2.jpg" alt=""></a></div>
								</li>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/upnext3.jpg" alt=""></a></div>
								</li>
							</ul>
						</div>
						<div class="current_category">
							<div class="current_song_detail">
								<div class="song_name">
									<a href="#" class="category_name"><?php echo $banner_video_titile; ?>
								</div>
								<span class="song_icon"><a href="#" class="play_volume"><i class="fa fa-volume-up" aria-hidden="true"></i></a></span>
							</div>
						</div>
					</div>
				</div>
			</div><!-- For UpNext Menu-->
			<div class="au_menu_wrapper audition_genres" id="audition_genres"><!-- For Genre Menu-->
				<div class="main_menu">
				<div class="au_logo"><a href="<?php echo $basepath;?>"><img src="<?php echo $basepath;?>repo/images/logo.png" alt="Discovered"></a></div>
					<div class="video_action_btn">
						<ul class="al_breadcrumb">
							<li>up next</li>
							<li class="active">genre</li>
						</ul>
						<div class="al_autoplay_btn">
							<h6>autoplay</h6>
							<a href="#" class="info">
								<i class="fa fa-exclamation" aria-hidden="true"></i>
								<span class="audition_tooltip">
									<p>When autoplay is enabled, a suggested video will automatically play next.</p>
								</span>
							</a>
							<div class="checkbox_wrapper">
								<div class="checkbox_btn">
								  <input type="checkbox" value="None" id="autoPlay" name="check" checked />
								  <label for="autoPlay"></label>
								</div>
							</div>
						</div>
					</div>
					<div class="menu_overlay">
						<div class="category_wrapper">
							<ul>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/artist.jpg" alt=""></a>
										<a href="#" class="genre_info">artists</a>
									</div>
								</li>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/producer.jpg" alt=""></a>
										<a href="#" class="genre_info">producers</a>
									</div>
								</li>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/manager.jpg" alt=""></a>
										<a href="#" class="genre_info">managers</a>
									</div>
								</li>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/director.jpg" alt=""></a>
										<a href="#" class="genre_info">directors</a>
									</div>
								</li>
								<li>
									<div class="menu_artist_img"><a href="#"><img src="<?php echo $basepath;?>repo/images/menu/all_icon.jpg" alt=""></a>
										<a href="#" class="genre_info">All Icons</a>
									</div>
								</li>
							</ul>
						</div>
						<div class="current_category">
							<div class="current_song_detail">
								<div class="song_name">
									<a href="#" class="category_name">pillowtalk<small>zayn</small></a>
								</div>
								<span class="song_icon"><a href="#" class="play_volume"><i class="fa fa-volume-up" aria-hidden="true"></i></a></span>
							</div>
						</div>
					</div>
				</div>
			</div><!-- For Genre Menu-->
		</div>
	</div>
</div>
<!-- Full width POPUP -->
<!-- Banner Section -->

<div class="audition_main_wrapper au_banner_section">
	<div class="user_profile_page">
		<div class="user_profile_wrapper <?php echo ($userContentDetails[0]['uc_video'] != '') ? 'edit_cover_video' : '' ;?>">
			<div class="Flexible-container">
			 <a class="speaker" data-video="cover_banner_video">
			 <span></span>
			 </a>
			<video autoplay loop muted  class="banner_video">

			<source src="http://app2demo.mintsapp.io/assets/vid/Game_of_throne_7.mp4" type="video/mp4">
		  </video>
			    <!--<video autoplay loop muted  class="banner_video">
					<?php //if($userContentDetails[0]['uc_video'] != '') { ?>
						<source src="<?php //echo $basepath;?>uploads/aud_<?php //echo $userDetail[0]['user_id'];?>/videos/<?php //echo $userContentDetails[0]['uc_video'].'?'.date('his');?>" type="video/mp4">
					<?php //} else if($userDetail[0]['user_uname']=='flytetyme'){?>
						<source src="http://app2demo.mintsapp.io/assets/vid/Donnie_After_Dark_Jimmy_Jam_and_Terry_Lewis.mp4" type="video/mp4">

					<?php // } else if($userDetail[0]['user_uname']=='bloomingdale'){?>
					<source src="http://app2demo.mintsapp.io/assets/vid/An_Inside_Look-Spring2017_Michael_Kors_Collection.mp4" type="video/mp4">
					<?php //} ?>

			    </video>-->
			    <?php if(isset($profile_page)) { ?>
				<a class="upload_video" data-toggle="modal" data-target="#upload_video"><i class="fa fa-plus" aria-hidden="true"></i>add cover video</a>
				<?php } ?>
			</div>
			<div class="au_banner_content">
				 <a id="popup_banner_video" class="play_cover_video"><img src="http://discovered.tv/repo/images/banner_logo1.png"></a>
			  </div>
			<div class="dis_scroll_div">
				<a href="#section2"><img src="<?php echo $basepath;?>repo/images/scroll_icon.png" class="img-responsive" alt=""></a>
			</div>

			<!--<div class="user_profile_detail_wrapper">


				<div class="profile_picture <?php //echo ($userContentDetails[0]['uc_pic'] != '') ? 'edit_picture' : '' ;?>">
					<a class="upload_picture" <?php //echo isset($profile_page) ? 'data-toggle="modal" data-target="#upload_image"' : '' ; ?>>
						<div id="pro_pic_div">
						<?php //if($userContentDetails[0]['uc_pic'] != '') { ?>
							<img src="<?php //echo $basepath;?>uploads/aud_<?php //echo $userDetail[0]['user_id'];?>/images/<?php //echo $userContentDetails[0]['uc_pic'];?>" title="<?php //echo $userDetail[0]['user_name'];?>" alt="<?php //echo $userDetail[0]['user_name'];?>">
						<?php //} ?>
						</div>
						<div class="upload_text">
							<i class="fa fa-camera" aria-hidden="true"></i>
							<?php //if(isset($profile_page)) { ?>
							<span><i class="fa fa-plus" aria-hidden="true"></i>add profile picture</span><small class="hover_text">Visually present yourself to Fans</small>
							<?php //} ?>
						</div>
					</a>
				</div>



				<div class="profile_detail">

				<?php
					//$user_page_url = base_url().'user/'.$userDetail[0]['user_uname'];
					//$user_page_image = $userContentDetails[0]['uc_pic'] != '' ? base_url().'uploads/aud_'.$userDetail[0]['user_id'].'/images/'.$userContentDetails[0]['uc_pic'] : base_url().'repo/images/user/user.png' ;
					//$user_page_text = $userDetail[0]['user_name'] .' at auditionlive.com';
				?>
					<div class="basic_deatil">
						<h2 class="profile_name"><?php //echo $userDetail[0]['user_name'];?></h2>

					<div class="user_profile_btn share_icon_btn"><span><i class="fa fa-share" aria-hidden="true"></i>share</span>
						<ul class="share_icon">
							<li class="facebook"><a onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php //echo urlencode($user_page_url);?>','targetWindow','scrollbars=yes,resizable=yes,width=900,height=700');"><i class="fa-brands fa-facebook-f"></i></a></li>

							<li class="gplus"><a onclick="window.open('https://plus.google.com/share?url=<?php// echo urlencode($user_page_url);?>','targetWindow','scrollbars=yes,resizable=yes,width=900,height=700');"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>

							<li class="twitter"><a onclick="window.open('http://twitter.com/share?type=popup&url=<?php //echo urlencode($user_page_url);?>&text=<?php //echo urlencode($user_page_text);?>','targetWindow','scrollbars=yes,resizable=yes,width=900,height=700');" ><i class="fa fa-twitter" aria-hidden="true"></i></a></li>

							<li class="pinterest"><a onclick="window.open('https://pinterest.com/pin/create/button/?display=popup&url=<?php //echo urlencode($user_page_url);?>&media=<?php //echo urlencode($user_page_image);?>','targetWindow','scrollbars=yes,resizable=yes,width=900,height=700');"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>

						</ul>
					</div>

				<?php// if(!isset($profile_page)) { ?>
					<a  class="user_profile_btn">Become a Fan</a>
				<?php //} ?>
					</div>

					<div class="country_deatil">
						<div class="user_genre_detail">
					<?php
						//$type_str = str_replace(',',' / ',rtrim($userContentDetails[0]['uc_type'],','));
					?>
							<span><?php// echo $userDetail[0]['level_name'];?></span><span><?php //echo $type_str;?></span>
						</div>
						<div class="user_location">
							<span><?php //echo $userContentDetails[0]['uc_city'];?> , <?php //echo $userContentDetails[0]['name'];?> , <?php //echo $userContentDetails[0]['country_name'];?></span><!--<span class="user_flag"><img src="<?php //echo $basepath;?>repo/images/user/flag.jpg" alt=""></span>
						</div>
					</div>
				</div>
			</div>-->
		</div><!-- profile picture section -->

<?php /************** BELOW SECTION OF PROFILE PAGE , STARTS   ***************************/ ?>

		<div class="mob_tab">
			<ul>
				<li role="presentation"><a href="#media" aria-controls="media" role="tab" data-toggle="tab"><i class="fa fa-camera-retro" aria-hidden="true"></i></a></li>
				<li role="presentation"><a href="#message" aria-controls="message" role="tab" data-toggle="tab"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
				<li role="presentation"><a href="#merchandise" aria-controls="merchandise" role="tab" data-toggle="tab"><i class="fa fa-shopping-bag" aria-hidden="true"></i></a></li>
			</ul>
		</div>
		<div class="dis_user_data_wrapper" id="section2">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="user_tab_section">
							<ul class="nav nav-tabs audition_tab" role="tablist">
								<li role="presentation" ><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
								<li role="presentation"><a href="#news" aria-controls="news" role="tab" data-toggle="tab">news</a></li>
								<li role="presentation" class="active"><a href="#channel" aria-controls="channel" role="tab" data-toggle="tab">My Channel</a></li>
								<li role="presentation"><a href="#about" aria-controls="about" role="tab" data-toggle="tab">about</a></li>
								<li role="presentation"><a href="#media" aria-controls="media" role="tab" data-toggle="tab" class="mob_hide">Media</a></li>
								<li role="presentation"><a href="#shows" aria-controls="shows" role="tab" data-toggle="tab">Shows</a></li>
								<li role="presentation"><a href="#merchandise" aria-controls="merchandise" role="tab" data-toggle="tab" class="mob_hide">merchandise</a></li>
								<li role="presentation"><a href="#message" aria-controls="message" role="tab" data-toggle="tab" class="mob_hide">message</a></li>
								<li role="presentation"><a href="#more" aria-controls="more" role="tab" data-toggle="tab">more<i class="fa fa-caret-down" aria-hidden="true"></i></a>
									<ul>
										<li><a onclick="location.href = '<?php echo base_url().'dashboard/casting_call'; ?>';">Casting Calls</a></li>
										<li><a onclick="location.href = '';">Total Fans</a></li>
										<li><a onclick="location.href = '';">Video Plays</a></li>
										<li><a onclick="location.href = '';">Suggested Playlists</a></li>
										<li><a onclick="location.href = '';">Favorite Artists</a></li>
										<li><a onclick="location.href = '';">Band Members</a></li>
										<li><a onclick="location.href = '';">Press</a></li>
										<li><a onclick="location.href = '';">Facebook</a></li>
										<li><a onclick="location.href = '';">Twitter</a></li>
										<li><a onclick="location.href = '';">YouTube</a></li>
										<li><a onclick="location.href = '';">Artist Blog</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<!--<div class="dis_upload_video">
							<div class="dis_video_heading">
								<h3>Upload A video</h3>
							</div>
							<div class="dis_select_video">
								<div class="dis_upload_div">
									<input type="file" id="custom_file" name="file" class="inputfile">
									<label for="custom_file"><figure><img src="<?php //echo $basepath;?>repo/images/video_upload.png"></figure> <span>Select Video File </span> To Upload </label>
								</div>
								<h4>OR</h4>
								<div class="image-upload-wrap">
									<input class="file-upload-input" type='file' accept="image/*" />
									<div class="drag-text">
										<h3>Drag and drop a file or select add Image</h3>
									</div>
								</div>
							</div>
						</div>-->
						<div class="dis_upload_video">
							<div class="dis_upload_video_inner">
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="dis_video_thumbnail">
											<div class="dis_video_thumbnail_img">
												 <input type="checkbox" id="thumb1">
												<label for="thumb1">
													<img src="<?php echo $basepath;?>repo/images/upload_video1.jpg" class="img-responsive" alt="">
													<div class="overlay">
														<span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
													</div>
												</label>
											</div>
											<h2>Thumbnail 1</h2>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="dis_video_thumbnail">
											<div class="dis_video_thumbnail_img">
												 <input type="checkbox" id="thumb2">
												<label for="thumb2">
													<img src="<?php echo $basepath;?>repo/images/upload_video2.jpg" class="img-responsive" alt="">
													<div class="overlay">
														<span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
													</div>
												</label>
											</div>
											<h2>Thumbnail 2</h2>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="dis_video_thumbnail">
											<div class="dis_video_thumbnail_img">
												 <input type="checkbox" id="thumb3">
												<label for="thumb3">
													<img src="<?php echo $basepath;?>repo/images/upload_video3.jpg" class="img-responsive" alt="">
													<div class="overlay">
														<span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
													</div>
												</label>
											</div>
											<h2>Thumbnail 3</h2>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div class="dis_video_thumbnail dis_select_video">
											<div class="dis_upload_div">
												<input type="file" id="custom_file" name="file" class="inputfile">
												<label for="custom_file"><figure><img src="<?php echo $basepath;?>repo/images/upload_icon.jpg" class="img-responsive" alt=""></figure></label>
											</div>
											<h2>Upload Custom</h2>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="dis_upload_form dis_signup_form">
										<form>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input">
															<option value="0">Select Mode</option>
															<option value="1">Mode1</option>
															<option value="2">Mode2</option>
															<option value="3">Mode3</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input">
															<option value="0">Select Genre</option>
															<option value="1">Genre1</option>
															<option value="2">Genre2</option>
															<option value="3">Genre3</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input">
															<option value="0">Select Category</option>
															<option value="1">Category1</option>
															<option value="2">Category2</option>
															<option value="3">Category3</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input">
															<option value="0">Select Video Language</option>
															<option value="1">Language1</option>
															<option value="2">Language2</option>
															<option value="3">Language3</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input">
															<option value="0">Age Restrictions</option>
															<option value="1">Restrictions1</option>
															<option value="2">Restrictions2</option>
															<option value="3">Restrictions3</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control dis_signup_input" placeholder="Video Title">
													</div>
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<textarea class="form-control dis_signup_input" placeholder="Video Description"></textarea>
													</div>
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control dis_signup_input" placeholder="Video Tags">
													</div>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="dis_button_div">
													<div class="checkbox dis_checkbox">
														<label>
															<input type="checkbox" value="">
															<i class="input-helper"></i>
															<p>I Have Read And Agree To The <a href="#">Terms &amp; Conditions</a> This Video Belongs To Me And I Am Liable For All The Copyright Issues If Any</p>
														</label>
													</div>
													<button class="dis_host_btn">Publish Video</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<?php /************** BELOW SECTION OF PROFILE PAGE , ENDS   ***************************/ ?>


	</div><!-- user profile page -->

