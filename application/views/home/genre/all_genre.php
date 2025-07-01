	<!-- Banner Section -->
	<?php if(isset($cover_video)){ ?>
	<div class="dis_featuredVideo_slider CoverSwiper">
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<?php foreach($cover_video as $i=> $cv){ ?>
				<div class="swiper-slide">
					<div class="audition_main_wrapper au_banner_section">
						<?php echo $this->common_html->au_banner_section($cv,$i);?> 
					</div>	
				</div>
				<?php } ?>
			</div>
			<div class="swiper-button-next fvs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
			<div class="swiper-button-prev fvs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>  
		</div>
	</div>
	<?php } ?>
	
	<div class="au_artist_wrapper singl_view m_t_50 padding_bottom_0"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading">
						<h2><?php echo isset($page_info['title'])?$page_info['title']:''; ?></h2>
					</div> -->
					<div class="dis_sliderheading all_genre_testing">
						<h2 class="dis_sliderheading_ttl muli_font"><?php echo isset($page_info['title'])?$page_info['title']:''; ?></h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="au_artist_slider " data-autoplay="3500">
						<div class="swiper-container">
							<div class="swiper-wrapper">
								<?php foreach($mode_of_genre as $genre){ ?>
								<div class="swiper-slide">
									<div class="dis_post_video_data">
										<div class="dis_postvideo_img">
										<a href="<?php echo base_url('genre?g=').$genre['genre_slug']; ?>">
											<img onerror="this.onerror=null;this.src='<?php echo base_url('repo/images/thumbnail.jpg'); ?>';" src="<?php echo CDN_BASE_URL . 'repo_admin/images/genre/'.$genre['image'] ;?>" class="img-responsive" alt="">
										</a>					
									
										</div>
										<div class="dis_postvideo_content">
											<h3><a href="<?php echo base_url('genre?g=').$genre['genre_slug']; ?>"><?php echo $genre['genre_name']; ?></a></h3>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
							<div class="swiper-button-next cs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
   							<div class="swiper-button-prev cs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	
	<div id="appendGenreSlider" data-url="genre/GetGenreSlider" class="slider_rotate" data-load="1">
	
	</div>
	<?php echo $this->common_html->pro_loader(); ?>

<!--service start-->
<!--service end-->