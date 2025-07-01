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
                           <i class="fa fa-picture-o" aria-hidden="true"></i>
                           <p class="info_text">Click to browse </p>
                        </div>
                        <div id="preview-template" style="display: none;"></div>
                     </div>
                  </div>
                  <p class="help_note"><strong>NOTE:</strong>The best image size is 270x200(WxH).</p>
               </div>
               <button onclick="crop()">Upload</button>	
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
           <a class="speaker mute" data-video="popup_banner_video">
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
<div class="audition_main_wrapper au_banner_section"><div class="user_profile_page">   <div class="user_profile_wrapper <?php //echo ($userContentDetails[0]['uc_video'] != '') ? 'edit_cover_video' : '' ;?>">      <div class="Flexible-container">         <a  class="speaker mute" data-video="cover_banner_video">         <span></span>         </a> 			 <video autoplay loop muted  class="banner_video cover_banner_video">				<?php if($banner_video != '') { ?>					<source src="http://app2demo.mintsapp.io/assets/vid/Game_of_throne_7.mp4" type="video/mp4">				<?php }?>			 </video>
		
         <?php if(isset($profile_page)) { ?>
         <a class="upload_video" data-toggle="modal" data-target="#upload_video"><i class="fa fa-plus" aria-hidden="true"></i>
		 add cover video</a>
         <?php } ?>
      </div>
      <div class="au_banner_content">
         <a  id="popup_banner_video" class="play_cover_video"><img src="<?php echo $basepath;?>repo/images/banner_logo1.png"></a>
      </div>
      <div class="dis_scroll_div">
         <a href="#section2"><img src="<?php echo $basepath;?>repo/images/scroll_icon.png" class="img-responsive" alt=""></a>
      </div>
   </div>
   <!-- profile picture section -->
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
				  
                     <li role="presentation" class="active"><a href="<?= base_url('home/test_page'); ?>" >Home</a></li>
                     <li role="presentation"><a href="#home" aria-controls="news" role="tab" data-toggle="tab">Profile</a></li>
                     <li role="presentation"><a href="#about" aria-controls="about" role="tab" data-toggle="tab">about</a></li>
                     <li role="presentation"><a href="#media" aria-controls="media" role="tab" data-toggle="tab" class="mob_hide">Media</a></li>
                     <li role="presentation"><a href="#shows" aria-controls="shows" role="tab" data-toggle="tab">Shows</a></li>
                     <li role="presentation"><a href="#merchandise" aria-controls="merchandise" role="tab" data-toggle="tab" class="mob_hide">merchandise</a></li>
                     <li role="presentation"><a href="#message" aria-controls="message" role="tab" data-toggle="tab" class="mob_hide">message</a></li>
                     <li role="presentation">
                        <a href="#more" aria-controls="more" role="tab" data-toggle="tab">more<i class="fa fa-caret-down" aria-hidden="true"></i></a>
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
            <div class="col-lg-12 col-md-12">
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="home">
                     <div class="user_tab_wrapper">
                        <div class="artist_profile_home dis_profile_data">
                           <div class="row">
                              <div class="col-lg-12 col-md-12 sm_padding">
                                 <div class="show_opacity"></div>
                                 <div class="user_post_area active_area">
                                    <!-- post area -->
                                    <div class="post_area_header">
                                       <ul class="post_section_ul" role="tablist">
                                          <li class="active initial_step" title="create">
                                             <a>
                                                <span class="action_icon">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="21px" height="21px"  viewBox="0 0 21 21">
                                                      <path fill-rule="evenodd"  fill-opacity="0" fill="rgb(235, 88, 31)" d="M0.000,0.000 L21.000,0.000 L21.000,21.000 L0.000,21.000 L0.000,0.000 Z"/>
                                                      <path fill-rule="evenodd"  fill="rgb(119, 119, 119)" d="M16.562,18.009 L5.439,18.009 C4.095,18.008 3.002,16.885 3.000,15.506 L3.000,4.899 C3.002,3.520 4.095,2.397 5.438,2.395 L8.958,2.395 C9.429,2.395 9.811,2.788 9.811,3.271 C9.811,3.754 9.429,4.146 8.958,4.146 L5.439,4.146 C5.035,4.147 4.706,4.485 4.706,4.899 L4.706,15.506 C4.706,15.920 5.035,16.257 5.439,16.258 L16.561,16.258 C16.965,16.257 17.294,15.920 17.294,15.505 L17.294,11.891 C17.294,11.408 17.677,11.015 18.147,11.015 C18.617,11.015 19.000,11.408 19.000,11.892 L19.000,15.506 C18.998,16.885 17.905,18.008 16.562,18.009 ZM17.871,4.740 L16.004,2.824 L16.424,2.392 C16.812,1.996 17.439,1.996 17.826,2.392 L18.291,2.869 C18.678,3.268 18.678,3.911 18.291,4.309 L17.871,4.740 ZM9.282,10.111 L10.773,11.642 L8.711,12.229 L9.282,10.111 ZM17.310,5.316 L11.522,11.259 L9.655,9.343 L15.443,3.400 L17.310,5.316 Z"/>
                                                   </svg>
                                                </span>
                                                <span class="action_text">create post</span>
                                             </a>
                                          </li>
                                          <li title="live">
                                             <a>
                                                <span class="action_icon">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="21px" height="21px"  viewBox="0 0 21 21">
                                                      <path fill-rule="evenodd"  fill-opacity="0" fill="rgb(235, 88, 31)" d="M0.000,0.000 L21.000,0.000 L21.000,21.000 L0.000,21.000 L0.000,0.000 Z"/>
                                                      <path fill-rule="evenodd"  fill="rgb(119, 119, 119)" d="M19.701,11.231 C19.510,11.075 19.262,11.011 19.019,11.055 L16.823,11.451 L16.823,10.168 C16.823,9.717 16.450,9.350 15.991,9.350 L15.941,9.350 C16.713,8.543 17.149,7.475 17.149,6.364 C17.149,3.957 15.151,1.998 12.695,1.998 C11.250,1.998 9.912,2.678 9.074,3.828 C8.236,2.678 6.898,1.998 5.453,1.998 C2.997,1.998 0.999,3.957 0.999,6.364 C0.999,7.475 1.435,8.543 2.207,9.350 L2.157,9.350 C1.699,9.350 1.325,9.717 1.325,10.168 L1.325,18.184 C1.325,18.634 1.699,19.002 2.157,19.002 L15.991,19.002 C16.450,19.002 16.823,18.634 16.823,18.184 L16.823,16.899 L19.022,17.298 C19.266,17.335 19.516,17.274 19.701,17.121 C19.892,16.965 20.001,16.735 20.001,16.492 L20.001,11.860 C20.001,11.615 19.892,11.385 19.701,11.231 ZM18.338,12.842 L18.338,15.510 L16.823,15.238 L16.823,13.115 L18.338,12.842 ZM2.662,6.364 C2.662,4.858 3.914,3.634 5.453,3.634 C6.991,3.634 8.242,4.858 8.242,6.364 C8.242,7.870 6.991,9.095 5.453,9.095 C3.914,9.095 2.662,7.870 2.662,6.364 ZM8.698,9.350 C8.833,9.209 8.958,9.058 9.074,8.901 C9.190,9.058 9.315,9.209 9.449,9.350 L8.698,9.350 ZM9.906,6.364 C9.906,4.858 11.157,3.634 12.695,3.634 C14.234,3.634 15.485,4.858 15.485,6.364 C15.485,7.870 14.234,9.095 12.695,9.095 C11.157,9.095 9.906,7.870 9.906,6.364 ZM15.159,10.986 L15.159,17.365 L2.989,17.365 L2.989,10.986 L15.159,10.986 Z"/>
                                                   </svg>
                                                </span>
                                                <span class="action_text">Live</span>
                                             </a>
                                          </li>
                                          <li title="video">
                                             <a>
                                                <span class="action_icon">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="21px" height="21px" viewBox="0 0 21 21">
                                                      <path fill-rule="evenodd"  fill-opacity="0" fill="rgb(235, 88, 31)" d="M0.000,0.000 L21.000,0.000 L21.000,21.000 L0.000,21.000 L0.000,0.000 Z"/>
                                                      <path fill-rule="evenodd"  fill="rgb(119, 119, 119)" d="M20.160,18.002 L0.840,18.002 C0.376,18.002 -0.001,17.630 -0.001,17.173 L-0.001,3.826 C-0.001,3.370 0.376,2.998 0.840,2.998 L20.160,2.998 C20.624,2.998 21.001,3.370 21.001,3.826 L21.001,17.173 C21.001,17.630 20.624,18.002 20.160,18.002 ZM1.681,16.345 L9.285,16.345 C7.384,14.474 7.275,14.367 7.116,14.210 C7.004,14.101 6.871,13.969 6.357,13.464 L4.832,11.964 L1.681,15.065 L1.681,16.345 ZM19.319,4.655 L1.681,4.655 L1.681,12.724 L4.238,10.208 C4.556,9.894 5.108,9.894 5.427,10.208 L7.716,12.460 L12.999,7.295 C13.312,6.990 13.852,6.984 14.173,7.285 L19.319,12.130 L19.319,4.655 ZM19.319,14.422 L13.602,9.040 L8.907,13.631 L11.665,16.345 L19.319,16.345 L19.319,14.422 ZM7.829,9.529 C6.601,9.529 5.603,8.546 5.603,7.337 C5.603,6.130 6.601,5.147 7.829,5.147 C9.056,5.147 10.053,6.130 10.053,7.337 C10.053,8.546 9.056,9.529 7.829,9.529 ZM7.829,6.804 C7.528,6.804 7.285,7.044 7.285,7.337 C7.285,7.632 7.528,7.871 7.829,7.871 C8.129,7.871 8.371,7.632 8.371,7.337 C8.371,7.044 8.129,6.804 7.829,6.804 Z"/>
                                                   </svg>
                                                </span>
                                                <span class="action_text">video/photo</span>
                                             </a>
                                          </li>
                                          <li title="casting">
                                             <a href="<?php echo base_url().'dashboard/casting_call'; ?>">
                                                <span class="action_icon">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="21px" height="21px" viewBox="0 0 21 21">
                                                      <path fill-rule="evenodd"  fill-opacity="0" fill="rgb(235, 88, 31)" d="M0.000,0.000 L21.000,0.000 L21.000,21.000 L0.000,21.000 L0.000,0.000 Z"/>
                                                      <path fill-rule="evenodd"  fill="rgb(119, 119, 119)" d="M18.417,4.000 L2.583,4.000 C1.710,4.000 1.000,4.697 1.000,5.555 L1.000,16.445 C1.000,17.303 1.710,18.000 2.583,18.000 L18.417,18.000 C19.290,18.000 20.000,17.303 20.000,16.445 L20.000,5.555 C20.000,4.697 19.290,4.000 18.417,4.000 ZM3.684,16.445 C3.636,15.868 3.225,15.444 2.583,15.403 L2.583,13.499 C4.030,13.904 5.170,15.024 5.581,16.445 L3.684,16.445 ZM7.758,16.445 C7.067,13.543 5.367,11.863 2.583,11.401 L2.583,9.537 C6.216,10.051 9.090,12.876 9.614,16.445 L7.758,16.445 ZM18.417,16.445 L11.758,16.445 C11.100,11.128 7.972,8.056 2.583,7.471 L2.583,5.555 L18.417,5.555 L18.417,16.445 L18.417,16.445 Z"/>
                                                   </svg>
                                                </span>
                                                <span class="action_text">casting call</span>
                                             </a>
                                          </li>
                                          <li title="show">
                                             <a>
                                                <span class="action_icon">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="21px" height="21px" viewBox="0 0 21 21">
                                                      <path fill-rule="evenodd"  fill-opacity="0" fill="rgb(235, 88, 31)" d="M0.000,0.000 L21.000,0.000 L21.000,21.000 L0.000,21.000 L0.000,0.000 Z"/>
                                                      <path fill-rule="evenodd"  fill="rgb(119, 119, 119)" d="M16.487,18.000 L4.513,18.000 C3.679,18.000 3.000,17.332 3.000,16.511 L3.000,6.084 C3.000,5.262 3.679,4.594 4.513,4.594 L6.090,4.594 L6.090,3.723 C6.090,3.324 6.419,3.000 6.825,3.000 L6.942,3.000 C7.348,3.000 7.677,3.324 7.677,3.723 L7.677,4.594 L13.305,4.594 L13.305,3.723 C13.305,3.324 13.634,3.000 14.040,3.000 L14.156,3.000 C14.562,3.000 14.892,3.324 14.892,3.723 L14.892,4.594 L16.487,4.594 C17.321,4.594 18.000,5.262 18.000,6.084 L18.000,16.511 C18.000,17.332 17.321,18.000 16.487,18.000 ZM16.289,7.199 L4.711,7.199 L4.711,16.317 L16.289,16.317 L16.289,7.199 ZM8.372,10.565 L9.570,10.378 L10.109,9.243 C10.183,9.089 10.335,8.990 10.500,8.990 C10.665,8.990 10.817,9.089 10.890,9.243 L11.430,10.378 L12.628,10.565 C12.792,10.590 12.928,10.711 12.979,10.876 C13.030,11.040 12.987,11.221 12.869,11.342 L12.004,12.230 L12.205,13.479 C12.233,13.650 12.166,13.822 12.032,13.925 C11.956,13.982 11.866,14.011 11.776,14.011 C11.707,14.011 11.637,13.994 11.574,13.959 L10.500,13.373 L9.426,13.959 C9.280,14.040 9.102,14.026 8.968,13.925 C8.834,13.822 8.767,13.650 8.795,13.479 L8.996,12.230 L8.131,11.342 C8.012,11.221 7.970,11.040 8.021,10.875 C8.072,10.711 8.208,10.590 8.372,10.565 Z"/>
                                                   </svg>
                                                </span>
                                                <span class="action_text">show/event</span>
                                             </a>
                                          </li>
                                       </ul>
                                       <div class="close_opacity"><i class="fa fa-times" aria-hidden="true"></i></div>
                                    </div>
                                    <div class="user_tab_content">
                                       <input type="hidden" id="publishArray" value="" >
                                       <div class="active" id="create">
                                          <div class="post_area_body">
                                             <span class="profile_icon">
                                                <img src="<?php echo $basepath;?>repo/images/user/user.png" alt="">	
                                             </span>
                                             <div class="dis_textare_div">
                                                <textarea class="post_area opacity_textarea" rows="5" placeholder="What You Want People To Know?" id="publish_input"></textarea>
                                             </div>
                                             <div class="edit_media_section"></div>
                                             <div class="hideme allSections video_contentdiv">
                                                <div class="browse_area">
                                                   <div class="browse_btn_wrapper">
                                                      <p id="upload_video_err" class="help_note"></p>
                                                      <div class="dropzone" id="home_image_video_upload">
                                                         <div class="dz-default dz-message">
                                                            <i class="fa fa-file-video-o" aria-hidden="true"></i>
                                                            <p class="info_text">Drop a video or image here or Click to browse</p>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <p class="help_note"><strong>NOTE:</strong>Video should be in mp4 format, and Image can be of any size</p>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="post_area_footer">
                                             <!-- <a href="#" class="post_btn">boost post</a>-->
                                             <a onclick="publish_content('0');" class="post_btn">publish <span class="hideme publish_btn"> <i class="fa fa-spinner fa-pulse fa-fw"></i></span> </a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- post area -->
                                 <div id="publish_show_div">
                                    <?php if(!empty($publish_content)) { 										
									foreach($publish_content as $solo_pub) { 											
									$pub_ID = $solo_pub['pub_id'];					
									if( $solo_pub['pub_media'] == '' ) {			
										$text_content = $solo_pub['pub_content'];		
										$display_content = '';			
										$pub_format = 'text';	
									}						
									else {					
									$j_data = explode('|',$solo_pub['pub_media']);	
									$text_content = $solo_pub['pub_content'];		
									$display_content = $j_data[0];					
									$pub_format = $j_data[1];		
									}									
									?>					
									
                                    <div class="dis_user_post_data" id="parent_post_content_<?php echo $pub_ID; ?>">
                                       <div class="dis_user_post_header">
                                          <div class="dis_user_img">														
										  <img src="<?php echo $basepath;?>uploads/aud_<?php echo $userDetail[0]['user_id'];?>/images/<?php echo $userContentDetails[0]['uc_pic'];?>" alt="">																													
										  </div>
                                          <div class="dis_user_detail">
										  
                                             <h3><?php echo $userDetail[0]['user_name'];?></h3>
											 
                                             <p class="published_date timing_detail" data-time="<?php echo $solo_pub['pub_date'];?>"> </p>
                                             </a>															
                                             <div class="dis_actiondiv">
                                                <span>
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
                                                      <path fill-rule="evenodd"  fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"/>
                                                   </svg>
                                                </span>
                                             </div>
                                          </div>
                                       
									   
									   </div>
                                       <?php $uniq_ID = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);											
									   
									   if( $pub_format == 'video' ) { ?>		
                                       <div class="dis_user_post_content">
                                          <div class="dis_post_img" id="media_complete_sec_<?php echo $pub_ID; ?>">
                                             <div class="Flexible-container">
                                                <video>
                                                   <source src="<?php echo $basepath;?>uploads/aud_<?php echo $userDetail[0]['user_id'];?>/videos/<?php echo $display_content;?>" type="video/mp4" id="media_<?php echo $pub_ID; ?>">
                                                </video>
                                             </div>
                                             <div class="overlay">
												<a href="<?php echo $basepath; ?>uploads/aud_<?php echo $userDetail[0]['user_id'];?>/videos/<?php echo $display_content;?>" id="<?php echo $uniq_ID;?>" class="play_btn play_post_video">
													<img src="<?php echo $basepath;?>repo/images/play_icon.png">
												</a>
											 </div>
                                          </div>
                                          <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.</p>
                                          <p><strong>#Happy #Amanda</strong></p>
                                       </div>
                                       <?php } elseif( $pub_format == 'image' ) { ?>														
                                       <div class="dis_user_post_content">
                                          <div class="dis_post_img" id="media_complete_sec_<?php echo $pub_ID; ?>">
                                             <img class="img-responsive" src="<?php echo $basepath;?>uploads/aud_<?php echo $userDetail[0]['user_id'];?>/images/<?php echo $display_content;?>" id="media_<?php echo $pub_ID; ?>">																
                                          </div>
                                          <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.</p>
                                          <p><strong>#Happy #Amanda</strong></p>
                                       </div>
                                       <?php }else{ ?>														
                                       <div class="dis_user_post_content">
                                          <p id="text_<?php echo $pub_ID; ?>"><?php echo nl2br($text_content);?></p>
                                       </div>
                                       <?php } ?>
                                      

									  <div class="dis_user_post_footer">
                                          <ul class="dis_meta">
                                             <li>
                                                <?php $user_like = $this->audition_functions->get_total_likes($pub_ID,'post',$this->session->userdata['user_login_id']);
												if( $user_like == 'yes' ) { ?>																
													<a class="like_post l_p_text_<?php echo $pub_ID;?>" onclick="unlike_post(<?php echo $pub_ID;?>,'post')">
													   <span class="Un-Love_now">
														  <svg  xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 25 25">
															 <path fill-rule="evenodd"  fill="#ff2a43" d="M12.500,25.000 C5.596,25.000 -0.000,19.404 -0.000,12.500 C-0.000,5.596 5.596,-0.000 12.500,-0.000 C19.404,-0.000 25.000,5.596 25.000,12.500 C25.000,19.404 19.404,25.000 12.500,25.000 ZM17.114,8.933 C16.538,8.337 15.775,8.012 14.960,8.012 C14.146,8.012 13.381,8.340 12.804,8.936 L12.503,9.246 L12.198,8.931 C11.621,8.334 10.854,8.005 10.040,8.005 C9.228,8.005 8.462,8.333 7.888,8.925 C7.312,9.521 6.995,10.312 6.997,11.154 C6.997,11.995 7.317,12.784 7.893,13.379 L12.275,17.908 C12.335,17.970 12.417,18.004 12.496,18.004 C12.576,18.004 12.657,17.973 12.718,17.910 L17.109,13.389 C17.686,12.793 18.003,12.003 18.003,11.161 C18.005,10.320 17.690,9.528 17.114,8.933 Z"/>
														  </svg>
													   </span>
													   <?=  $this->audition_functions->like_name($pub_ID,'post'); ?>  & <?php $count_like = $this->audition_functions->get_total_likes($pub_ID,'post');  if($count_like > 0) { echo $count_like .' '. 'Others'; } ?> 
													</a>
                                                 <!--a class="like_post l_p_text_<?php echo $pub_ID;?>" onclick="unlike_post(<?php echo $pub_ID;?>,'post')"><span class="Un-Love_now"><i class="fa fa-heart" aria-hidden="true"></i></span>Un-Love</a-->															
                                             </li>
                                             <?php }else { ?>																
												 <a class="like_post l_p_text_<?php echo $pub_ID;?>" onclick="like_post(<?php echo $pub_ID;?>,'post')">
													<span class="Un-Love_now">
													   <svg  xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 25 25">
														  <path fill-rule="evenodd"  fill="rgb(196, 196, 196)" d="M12.500,25.000 C5.596,25.000 -0.000,19.404 -0.000,12.500 C-0.000,5.596 5.596,-0.000 12.500,-0.000 C19.404,-0.000 25.000,5.596 25.000,12.500 C25.000,19.404 19.404,25.000 12.500,25.000 ZM17.114,8.933 C16.538,8.337 15.775,8.012 14.960,8.012 C14.146,8.012 13.381,8.340 12.804,8.936 L12.503,9.246 L12.198,8.931 C11.621,8.334 10.854,8.005 10.040,8.005 C9.228,8.005 8.462,8.333 7.888,8.925 C7.312,9.521 6.995,10.312 6.997,11.154 C6.997,11.995 7.317,12.784 7.893,13.379 L12.275,17.908 C12.335,17.970 12.417,18.004 12.496,18.004 C12.576,18.004 12.657,17.973 12.718,17.910 L17.109,13.389 C17.686,12.793 18.003,12.003 18.003,11.161 C18.005,10.320 17.690,9.528 17.114,8.933 Z"/>
													   </svg>
													</span>    
												  <?php $count_like = $this->audition_functions->get_total_likes($pub_ID,'post');  if($count_like > 0) { echo $this->audition_functions->like_name($pub_ID,'post').' ' . '&'.' '. $count_like .' '. 'Others'; }else{ echo "Love";} ?> 
												 </a>
                                             <!--a class="like_post l_p_text_<?php echo $pub_ID;?>" onclick="like_post(<?php echo $pub_ID;?>,'post')"><span class="like_now"><i class="fa fa-heart" aria-hidden="true"></i></span><span class="post_action_text">love</span></a-->	<?php } ?>															
                                             <li>
                                                <a href="#">
                                                   <span>
                                                      <svg  xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 25 25">
                                                         <path fill-rule="evenodd"  fill="rgb(196, 196, 196)" d="M12.499,-0.001 C5.606,-0.001 -0.002,4.887 -0.002,10.894 C-0.002,12.994 0.684,15.028 1.985,16.787 C1.739,19.425 1.079,21.383 0.120,22.311 C-0.006,22.433 -0.038,22.621 0.042,22.776 C0.113,22.914 0.259,23.000 0.415,23.000 C0.434,23.000 0.453,22.998 0.473,22.995 C0.642,22.972 4.562,22.426 7.398,20.841 C9.008,21.470 10.723,21.789 12.499,21.789 C19.393,21.789 25.000,16.901 25.000,10.894 C25.000,4.887 19.393,-0.001 12.499,-0.001 ZM6.666,12.508 C5.746,12.508 4.999,11.784 4.999,10.894 C4.999,10.004 5.746,9.280 6.666,9.280 C7.585,9.280 8.332,10.004 8.332,10.894 C8.332,11.784 7.585,12.508 6.666,12.508 ZM12.499,12.508 C11.580,12.508 10.833,11.784 10.833,10.894 C10.833,10.004 11.580,9.280 12.499,9.280 C13.419,9.280 14.166,10.004 14.166,10.894 C14.166,11.784 13.419,12.508 12.499,12.508 ZM18.333,12.508 C17.414,12.508 16.666,11.784 16.666,10.894 C16.666,10.004 17.414,9.280 18.333,9.280 C19.252,9.280 20.000,10.004 20.000,10.894 C20.000,11.784 19.252,12.508 18.333,12.508 Z"/>
                                                      </svg>
                                                   </span>
                                                   Comments(0)
                                                </a>
                                             </li>
                                             <li>
                                                <a href="#">
                                                   <span>
                                                      <svg  xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 25 25">
                                                         <path fill-rule="evenodd"  fill="rgb(196, 196, 196)" d="M12.500,-0.001 C5.596,-0.001 -0.000,5.596 -0.000,12.499 C-0.000,19.402 5.596,24.999 12.500,24.999 C19.403,24.999 25.000,19.402 25.000,12.499 C25.000,5.596 19.403,-0.001 12.500,-0.001 ZM11.109,12.499 C11.109,12.760 11.062,13.009 10.979,13.241 L14.468,14.919 C14.869,14.480 15.445,14.203 16.087,14.203 C17.300,14.203 18.283,15.186 18.283,16.399 C18.283,17.612 17.300,18.595 16.087,18.595 C14.874,18.595 13.890,17.612 13.890,16.399 C13.890,16.283 13.902,16.169 13.920,16.058 L10.206,14.271 C9.843,14.537 9.397,14.696 8.912,14.696 C7.699,14.696 6.716,13.713 6.716,12.499 C6.716,11.286 7.699,10.303 8.912,10.303 C9.397,10.303 9.843,10.462 10.206,10.727 L13.920,8.941 C13.903,8.830 13.891,8.717 13.891,8.600 C13.891,7.387 14.874,6.404 16.087,6.404 C17.300,6.404 18.284,7.387 18.284,8.600 C18.284,9.813 17.300,10.797 16.087,10.797 C15.446,10.797 14.869,10.519 14.468,10.080 L10.979,11.758 C11.062,11.990 11.109,12.239 11.109,12.499 Z"/>
                                                      </svg>
                                                   </span>
                                                   Share Post
                                                </a>
                                             </li>
                                          </ul> 
										  <div class="dis_comment" id="com_append_<?= $pub_ID; ?>">
												<?php echo $this->audition_functions->get_commets($pub_ID);?>		
										  </div>
                                          <div class="dis_comment_form">
                                             <div class="dis_user_pic">																
											 <img src="<?php echo $basepath;?>uploads/aud_<?php echo $userDetail[0]['user_id'];?>/images/<?php echo $userContentDetails[0]['uc_pic'];?>" alt="" class="img-responsive">															</div>
                                             <div class="dis_form">																
											 <textarea placeholder="Your Comment Here..." class="comment_textarea form-control com_text_<?php echo $pub_ID;?>_0"></textarea>															
											 <button class="dis_form_submit" onclick="save_comment('pub_<?php echo $pub_ID;?>', 'parent_0')">
											 <img src="http://discovered.tv/repo/images/send_img.png" class="img-responsive" alt=""></button>  	
											 </div>
											 
                                          </div>
                                       </div>
                                    </div>
                                    <?php }} ?>																						
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
                           <div class="col-lg-11 col-md-11">
                              <div class="artist_about_section">
                                 <div class="artist_about_heading">
                                    <h6 class="tab_title">about</h6>
                                    <?php if(isset($profile_page)) { ?>
                                    <a class="add_content" onclick="open_edit_popup('','uc_about')"><i class="fa fa-plus" aria-hidden="true"></i>add<span class="info_tooltip">You can add more content by hitting ADD button.</span></a>
                                    <?php } ?>
                                 </div>
                                 <?php
                                    if($userContentDetails[0]['uc_about'] != '') {
                                    	echo '<div id="uc_about_section">';
                                    	$abt_arr = json_decode($userContentDetails[0]['uc_about']);
                                    	
                                    	for($i=0;$i<count($abt_arr);$i++) {
                                    		echo '<div class="artist_about_detail"><h5 class="tab_subtitle" id="uc_about_h2_'.$i.'">'.$abt_arr[$i]->h2.'</h5><a class="edit_text" onclick="open_edit_popup('.$i.',\'uc_about\')"><img src="'.$basepath.'repo/images/profile/edit_icon.png"></a><p id="uc_about_text_'.$i.'">'.$abt_arr[$i]->text.'</p></div>';
                                    	}
                                    	echo '</div>';
                                    }
                                    else {
                                    	echo '<div id="uc_about_section" class="empty_section"><div class="artist_about_detail"><p> There is nothing to show up. </p></div></div>';
                                    }
                                    ?>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="media">
                     <div class="user_tab_wrapper">
                        <div class="artist_profile_about">
                           <div class="col-lg-11 col-md-11">
                              <div class="artist_about_section">
                                 <div class="artist_media_detail">
                                    <ul class="nav nav-tabs audition_tab media_tab" role="tablist">
                                       <li role="presentation" class="active"><a href="#photo" aria-controls="photo" role="tab" data-toggle="tab">photo</a></li>
                                       <li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab">video</a></li>
                                       <li role="presentation"><a href="#audio" aria-controls="audio" role="tab" data-toggle="tab">audio</a></li>
                                    </ul>
                                    <div class="tab-content">
                                       <div role="tabpanel" class="tab-pane active" id="photo">
                                          <div class="user_media_wrapper">
                                             <div class="grid">
                                               
											   <div class="grid-item">
                                                   <a href="#media_popup_grid" class="zoom_icon user_media"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/sHiMDb1.jpg" alt=""></a>
                                                   <div class="media_action">
                                                      <ul>
                                                         <li>
                                                            <a class="like_post">
                                                            <span class="like_now"><i class="fa fa-heart" aria-hidden="true"></i></span><span class="post_action_text">love</span>
                                                            </a>
                                                         </li>
                                                         <li>
                                                            <a class="comment_btn"><i class="fa fa-comment" aria-hidden="true"></i><span>10</span></a>
                                                         </li>
                                                      </ul>
                                                   </div>
                                                   <!-- image magnific popup --->
                                                   <div id="media_popup_grid" class="media_gallery_popup mfp-hide">
                                                      <div class="media_gallery">
                                                         <div class="media_gallery_section">
                                                            <img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/sHiMDb1.jpg" alt="">
                                                            <div class="media_action">
                                                               <ul>
                                                                  <li>
                                                                     <a class="like_post">
                                                                     <span class="like_now"><i class="fa fa-heart" aria-hidden="true"></i></span><span class="post_action_text">love</span>
                                                                     </a>
                                                                  </li>
                                                                  <li>
                                                                     <a class="share_post share_btn"><i class="fa fa-share" aria-hidden="true"></i><span class="post_action_text">share</span></a>
                                                                  </li>
                                                               </ul>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="comment_section">
                                                         <!-- comment section -->
                                                         <div class="post_meta">
                                                            <a onclick="location.href = '';" class="profile_icon">
                                                            <img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/LQH31fF.jpg" alt=""/>
                                                            </a>
                                                            <div class="post_data">
                                                               <a onclick="location.href = '';" class="post_title">priyanka vishwakarma<span class="published_date timing_detail">published 2 days ago</span></a>
                                                               <div class="post_detail">
                                                                  <p id="text_22">Hello</p>
                                                               </div>
                                                            </div>
                                                         </div>
                                                         <div class="post_action_btn">
                                                            <a class="like_post" onclick="">
                                                            <span class="like_now"><i class="fa fa-heart" aria-hidden="true"></i></span>
                                                            love</a>
                                                            <a class="share_post"><i class="fa fa-share" aria-hidden="true"></i>share</a> 
                                                         </div>
                                                         <div class="like_county">
                                                            <a class="like_count"><span><i class="fa fa-heart" aria-hidden="true"></i></span></a> 
                                                         </div>
                                                         <div class="comment_wrapper">
                                                            <div class="user_comment scrollbar_content">
                                                               <ul>
                                                                  <li>
                                                                     <div class="post_comment_wrapper">
                                                                        <span class="authore_img"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/LQH31fF.jpg" alt=""></span>
                                                                        <div class="post_comment">
                                                                           <p><a onclick="location.href = '';" class="authore_name">authore name</a>comment text here</p>
                                                                           <div class="comment_action_btn">
                                                                              <a onclick="location.href = '';">like</a>
                                                                              <a class="reply_btn">reply</a>
                                                                              <a class="time">1 hour</a>
                                                                           </div>
                                                                        </div>
                                                                     </div>
                                                                     <ul class="child_comment">
                                                                        <li>
                                                                           <div class="post_comment_wrapper">
                                                                              <span class="authore_img"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/LQH31fF.jpg" alt=""></span>
                                                                              <div class="post_comment">
                                                                                 <p><a onclick="location.href = '';" class="authore_name">authore name</a>comment text here</p>
                                                                                 <div class="comment_action_btn">
                                                                                    <a onclick="location.href = '';">like</a>
                                                                                    <a class="reply_btn">reply</a>
                                                                                    <a class="time">1 hour</a>
                                                                                    <div class="add_comment reply_form">
                                                                                       <!-- reply form -->
                                                                                       <span class="authore_img"><img src="images/user/profile_pick.jpg" alt=""></span>
                                                                                       <textarea class="comment_textarea" rows="1" placeholder="Write a reply..."></textarea>
                                                                                       <span class="enter_btn"><img src="images/profile/enter.png"></span>
                                                                                    </div>
                                                                                    <!-- reply form -->
                                                                                 </div>
                                                                              </div>
                                                                           </div>
                                                                        </li>
                                                                     </ul>
                                                                  </li>
                                                               </ul>
                                                            </div>
                                                            <div class="add_comment reply_form">
                                                               <!-- reply form -->
                                                               <span class="authore_img"><img src="images/user/profile_pick.jpg" alt=""></span>
                                                               <textarea class="comment_textarea" rows="1" placeholder="Write a reply..."></textarea>
                                                               <span class="enter_btn"><img src="images/profile/enter.png"></span>
                                                            </div>
                                                            <!-- reply form -->
                                                            <div class="add_comment">
                                                               <!-- comment form -->
                                                               <span class="authore_img"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/LQH31fF.jpg" alt=""></span>
                                                               <textarea class="comment_textarea" rows="1" placeholder="Write a comment..."></textarea>
                                                               <span class="enter_btn" onclick=""><img src="http://discovered.tv/repo/images/send_img.png"></span>
                                                            </div>
                                                            <!-- comment form -->
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <!-- image magnific popup --->
                                                </div>
												
                                                <div class="grid-item">
                                                   <a href="#media_popup_grid" class="zoom_icon user_media"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/sHiMDb1.jpg" alt=""></a>
                                                   <!-- image magnific popup --->
                                                   <div id="media_popup_grid" class="media_gallery_popup mfp-hide">
                                                      <div class="media_gallery">
                                                         <div class="media_gallery_wrapper">
                                                            <div class="media_gallery_section">
                                                               <img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/YOkGbVd.jpg" alt="">
                                                               <div class="media_action">
                                                                  <ul>
                                                                     <li>
                                                                        <a class="like_post">
                                                                        <span class="like_now"><i class="fa fa-heart" aria-hidden="true"></i></span><span class="post_action_text">love</span>
                                                                        </a>
                                                                     </li>
                                                                     <li>
                                                                        <a class="share_post share_btn"><i class="fa fa-share" aria-hidden="true"></i></a>
                                                                     </li>
                                                                  </ul>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="comment_section">
                                                         <!-- comment section -->
                                                         <div class="post_meta">
                                                            <a onclick="location.href = '';" class="profile_icon">
                                                            <img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/LQH31fF.jpg" alt=""/>
                                                            </a>
                                                            <div class="post_data">
                                                               <a onclick="location.href = '';" class="post_title">priyanka vishwakarma<span class="published_date timing_detail">published 2 days ago</span></a>
                                                               <div class="post_detail">
                                                                  <p id="text_22">Hello</p>
                                                               </div>
                                                            </div>
                                                         </div>
                                                         <div class="post_action_btn">
                                                            <a class="like_post" onclick="">
                                                            <span class="like_now"><i class="fa fa-heart" aria-hidden="true"></i></span>
                                                            <span class="post_action_text">love</span></a>
                                                            <a class="share_post"><i class="fa fa-share" aria-hidden="true"></i><span class="post_action_text">share</span></a> 
                                                         </div>
                                                         <div class="like_county">
                                                            <a class="like_count"><span><i class="fa fa-heart" aria-hidden="true"></i></span></a> 
                                                         </div>
                                                         <div class="comment_wrapper">
                                                            <div class="user_comment scrollbar_content">
                                                               <ul>
                                                                  <li>
                                                                     <div class="post_comment_wrapper">
                                                                        <span class="authore_img"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/LQH31fF.jpg" alt=""></span>
                                                                        <div class="post_comment">
                                                                           <p><a onclick="location.href = '';" class="authore_name">authore name</a>comment text here</p>
                                                                           <div class="comment_action_btn">
                                                                              <a onclick="location.href = '';">like</a>
                                                                              <a class="reply_btn">reply</a>
                                                                              <a class="time">1 hour</a>
                                                                           </div>
                                                                        </div>
                                                                     </div>
                                                                     <ul class="child_comment">
                                                                        <li>
                                                                           <div class="post_comment_wrapper">
                                                                              <span class="authore_img"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/LQH31fF.jpg" alt=""></span>
                                                                              <div class="post_comment">
                                                                                 <p><a onclick="location.href = '';" class="authore_name">authore name</a>comment text here</p>
                                                                                 <div class="comment_action_btn">
                                                                                    <a onclick="location.href = '';">like</a>
                                                                                    <a class="reply_btn">reply</a>
                                                                                    <a class="time">1 hour</a>
                                                                                    <div class="add_comment reply_form">
                                                                                       <!-- reply form -->
                                                                                       <span class="authore_img"><img src="images/user/profile_pick.jpg" alt=""></span>
                                                                                       <textarea class="comment_textarea" rows="1" placeholder="Write a reply..."></textarea>
                                                                                       <span class="enter_btn"><img src="images/profile/enter.png"></span>
                                                                                    </div>
                                                                                    <!-- reply form -->
                                                                                 </div>
                                                                              </div>
                                                                           </div>
                                                                        </li>
                                                                     </ul>
                                                                  </li>
                                                               </ul>
                                                            </div>
                                                            <div class="add_comment reply_form">
                                                               <!-- reply form -->
                                                               <span class="authore_img"><img src="images/user/profile_pick.jpg" alt=""></span>
                                                               <textarea class="comment_textarea" rows="1" placeholder="Write a reply..."></textarea>
                                                               <span class="enter_btn"><img src="images/profile/enter.png"></span>
                                                            </div>
                                                            <!-- reply form -->
                                                            <div class="add_comment">
                                                               <!-- comment form -->
                                                               <span class="authore_img"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/LQH31fF.jpg" alt=""></span>
                                                               <textarea class="comment_textarea" rows="1" placeholder="Write a comment..."></textarea>
                                                               <span class="enter_btn" onclick=""><img src="<?php echo $basepath;?>repo/images/profile/enter.png"></span>
                                                            </div>
                                                            <!-- comment form -->
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <!-- image magnific popup --->
                                                </div>
                                                <div class="grid-item">
                                                   <a href="#media_popup" class="zoom_icon user_media"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/sHiMDb1.jpg" alt=""></a>
                                                   <!-- image magnific popup --->
                                                </div>
                                                <div class="grid-item">
                                                   <a href="#media_popup" class="zoom_icon user_media"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/sHiMDb1.jpg" alt=""></a>
                                                   <!-- image magnific popup --->
                                                </div>
                                                <div class="grid-item">
                                                   <a href="#media_popup" class="zoom_icon user_media"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/sHiMDb1.jpg" alt=""></a>
                                                   <!-- image magnific popup --->
                                                </div>
                                                <div class="grid-item">
                                                   <a href="#media_popup" class="zoom_icon user_media"><img src="http://hostingsites.co.in/developer2/aud_code/uploads/aud_2/images/sHiMDb1.jpg" alt=""></a>
                                                   <!-- image magnific popup --->
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
                  <div role="tabpanel" class="tab-pane" id="shows">
                     <div class="user_tab_wrapper">
                        <div class="artist_profile_about">
                           <div class="col-lg-11 col-md-11">
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
                  <div role="tabpanel" class="tab-pane" id="message">
                     <div class="user_tab_wrapper">
                        <div class="artist_profile_about">
                           <div class="col-lg-11 col-md-11">
                              <div class="message_section">
                                 <div class="message_header">
                                    <h5 class="msg_titile">new message</h5>
                                    <div class="msg_recipient">
                                       <label>to</label><input type="text" class="recipient_input" />
                                    </div>
                                 </div>
                                 <div class="msg_body">
                                    <ul class="message_list">
                                       <li>
                                          <div class="msg_wrapper">
                                             <span class="sender_img"><img src="<?php echo $basepath;?>uploads/aud_<?php echo $userDetail[0]['user_id'];?>/images/<?php echo $userContentDetails[0]['uc_pic'];?>" alt=""></span>
                                             <div class="msg_detail">
                                                <a onclick="location.href = '';" class="res_name">recipient name</a>
                                                <p>message detail will be here.</p>
                                             </div>
                                             <div class="sending_time"><i class="fa fa-comment-o" aria-hidden="true"></i>25/8 10pm</div>
                                          </div>
                                       </li>
                                       <li>
                                          <div class="msg_wrapper">
                                             <span class="sender_img"><img src="<?php echo $basepath;?>uploads/aud_<?php echo $userDetail[0]['user_id'];?>/images/<?php echo $userContentDetails[0]['uc_pic'];?>" alt=""></span>
                                             <div class="msg_detail">
                                                <a onclick="location.href = '';" class="res_name">recipient name</a>
                                                <p>message detail will be here.</p>
                                             </div>
                                             <div class="sending_time"><i class="fa fa-comment-o" aria-hidden="true"></i>25/8 10pm</div>
                                          </div>
                                       </li>
                                       <li>
                                          <div class="msg_wrapper">
                                             <span class="sender_img"><img src="<?php echo $basepath;?>uploads/aud_<?php echo $userDetail[0]['user_id'];?>/images/<?php echo $userContentDetails[0]['uc_pic'];?>" alt=""></span>
                                             <div class="msg_detail">
                                                <a onclick="location.href = '';" class="res_name">recipient name</a>
                                                <p>message detail will be here.</p>
                                             </div>
                                             <div class="sending_time"><i class="fa fa-comment-o" aria-hidden="true"></i>25/8 10pm</div>
                                          </div>
                                       </li>
                                    </ul>
                                    <div class="message_box_wrapper">
                                       <div class="message_textarea">
                                          <textarea rows="2" class="msg_box" placeholder="write a reply"></textarea>
                                          <div class="textarea_btn">
                                             <a class="text_info"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                                             <a class="add_sticker"><i class="fa fa-smile-o" aria-hidden="true"></i></a>
                                          </div>
                                          <div class="attachment_btn">
                                             <a class="attach_file"><span><i class="fa fa-paperclip" aria-hidden="true"></i>add files</span><input type="file"></a>
                                             <a class="attach_file"><span><i class="fa fa-camera" aria-hidden="true"></i>add photos</span><input type="file"></a>
                                             <a class="send_msg_btn">Press Enter to send<i class="fa fa-check-square-o" aria-hidden="true"></i></a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="more">...</div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php /************** BELOW SECTION OF PROFILE PAGE , ENDS   ***************************/ ?>
</div>
<!-- user profile page --><!-- upload Image-->
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
                        <div>						 <input type='file' id="imgInp" />											 <img id="image" src="">										 </div>
                        <div class="dz-default dz-message browse_area" style="padding: 35px;">
                           <i class="fa fa-picture-o" aria-hidden="true"></i>
                           <p class="info_text">Click to browse </p>
                        </div>
                        <div id="preview-template" style="display: none;"></div>
                     </div>
                  </div>
                  <p class="help_note"><strong>NOTE:</strong>The best image size is 270x200(WxH).</p>
               </div>
               <button id="cropit" onclick="crop()" disabled>Upload</button>			
            </div>
         </div>
      </div>
   </div>
</div>
<!-- upload Image-->