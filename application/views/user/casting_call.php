<!-- Full width POPUP -->
<div class="au_video_popup">
	<?php echo $this->common_html->au_video_popup($cover_video);?>
</div>
<!-- Full width POPUP -->
<!-- Banner Section -->
<div class="audition_main_wrapper au_banner_section">
	<?php echo $this->common_html->au_banner_section($cover_video);?>
</div>

	<div class="au_call_banner">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1">
					<div class="au_heading">
						<h2>create a Casting call now!</h2>
						<p>it's always free and easy. Just share your personal casting call with the Discovered community, they simply upload an audition and you get paid - the money's in your PayPal account in seconds.</p>
					</div>
					<div class="user_post_area"><!-- post area -->
						<div class="post_area_header">
							<a onclick="location.href = '';" class="create_post"><span><img src="<?php echo base_url();?>repo/images/profile/edit_icon.png" alt=""></span>create</a>
							<a onclick="location.href = '';" class="upload_video"><span><img src="<?php echo base_url();?>repo/images/profile/video_icon.png" alt=""></span>video/photo</a>
							<a onclick="location.href = '';" class="casting_call active"><span><img src="<?php echo base_url();?>repo/images/profile/casting_icon.png" alt=""></span>casting call</a>
							<a onclick="location.href = '';" class="new_show"><span><img src="<?php echo base_url();?>repo/images/profile/event_icon.png" alt=""></span>show/event</a>
							<a onclick="location.href = '';" class="offer_sale"><span><img src="<?php echo base_url();?>repo/images/profile/flag_icon.png" alt=""></span>offer/sale</a>
						</div>
						<div class="post_area_body">
							<span class="profile_icon"><img src="<?php echo base_url();?>repo/images/user/profile_pick.jpg" alt=""></span>
							<textarea class="post_area" rows="2" placeholder="write something..." contentEditable="true"></textarea>
						</div>
						<div class="post_area_footer">
							<a href="#" class="post_btn">post</a>
						</div>
					</div><!-- post area -->
				</div>
			</div>
		</div>
	</div>

