<div class="dis_signup_wrapper">
	<div class="dis_gami_su_video_wrap">
		<video width="1000px" height="1000px" autoplay muted loop>
  		<source src="https://serverguys-s3-trans-cdn.discovered.tv/admin/Discovered+Video+Ad+30-Aug-2022+(1).mp4" type="video/mp4">
		</video>
	</div>
	<div class="container-fluid">

		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<div class="dis_signup_div">

					<div class="dis_signup_inner">

						<div class="dis_signup_left">

							<div class="dis_signup_img">

								<img src="<?php echo base_url().'repo/images/signup_img.jpg';?>" class="img-responsive" alt="">

								<div class="dis_overlay">

									<img src="<?php echo base_url().'repo/images/signup_img_logo.png';?>" class="img-responsive" alt="">

								</div>

							</div>

						</div>

						<div class="dis_signup_right">

							<div class="dis_signup_right_inner dis_choose_account">

								<div class="au_heading">

									<h2>Choose Your Account Type</h2>

								</div>
								<div class="dis_choose_type">
									<div class="dis_account_type">
  
										<?php if(!empty($artist_category)) {
											$pieces = array_chunk($artist_category, ceil(count($artist_category) / 2));
											$i=0;
											foreach($pieces[0] as $solo_list) {
												echo '<a class="dis_btn c_type" href="'.base_url('primary_type/'.$solo_list['category_id']).'">'.ucwords($solo_list['category_name']).'</a>';
											$i++;
											}
										?>
										<?php
											$i=0;
											foreach($pieces[1] as $solo_list) {
												echo '<a class="dis_btn c_type" href="'.base_url('primary_type/'.$solo_list['category_id']).'">'.ucwords($solo_list['category_name']).'</a>';
											$i++;
											}
											?>	
									</div>
											<?php
											} 

											else {

												redirect(base_url());

											} ?>
								</div>
								<p class="comn_anyhelp"><small>Any help needed ? <a target="_blank" href="<?= base_url('help?scroll=1'); ?>">Click here for help</a></small></p>
								<?php 
								if(isset($_SESSION['upgrade_account']) && $_SESSION['upgrade_account'] == 1){
									echo '<a href="'.base_url('home/CanclUpgrdAccProcess').'" class="dis_btn min_width_inherit b-r-5 m_t_20"> Back </a>';
								};
								?>
								
						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>	

<!-- Gamification -->
<div id="gam-signup-root"></div>
