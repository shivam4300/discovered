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

	<?php if(isset($sub_genres) && !empty($sub_genres) ){ ?>
	<div class="au_artist_wrapper singl_view"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading">
						<h2><?php echo isset($page_info['title'])?$page_info['title']:''; ?></h2>
					</div> -->
					<div class="dis_sliderheading single_genre_testing1">
						<h2 class="dis_sliderheading_ttl muli_font"><?php echo isset($page_info['title'])?$page_info['title']:''; ?></h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="au_artist_slider " data-autoplay="3500">
					<div class="swiper-container owl-carousel owl-theme owl-loaded">
						<div class="swiper-wrapper">
							<?php foreach($sub_genres as $sub){ ?>
							<div class="swiper-slide owl-item">
								<div class="dis_post_video_data">
									<div class="dis_postvideo_img">
									<a href="<?php echo base_url('genre?g=').$sub['genre_slug'].'&l=2'; ?>">
										<img onerror="this.onerror=null;this.src='<?php echo base_url('repo/images/thumbnail.jpg'); ?>';" src="<?php echo CDN_BASE_URL . 'repo_admin/images/genre/'.$sub['image'] ;?>" class="img-responsive" alt="">
									</a>

									</div>
									<div class="dis_postvideo_content">
										<h3><a href="<?php echo base_url('genre?g=').$sub['genre_slug'].'&l=2'; ?>"><?php echo $sub['genre_name']; ?></a></h3>
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
	<?php } ?>

	<?php if(isset($most_popular_videos) && !empty($most_popular_videos) ){ ?>
	<div class="au_artist_wrapper singl_view m_t_40"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading ">
						<h2>Most Popular <?php echo isset($genre_name)? $genre_name: ''; ?> Videos</h2>
					</div> -->

					<div class="dis_sliderheading single_genre_testing2">
						<h2 class="dis_sliderheading_ttl muli_font">Most Popular <?php echo isset($genre_name)? $genre_name: ''; ?> Videos</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<?php echo $this->common_html->swiper_slider($most_popular_videos);  ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if(isset($top_videos_ofthe_month) && !empty($top_videos_ofthe_month)){ ?>
	<div class="au_artist_wrapper singl_view bg-white"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading ">
						<h2>Top <?php echo isset($genre_name)? $genre_name: ''; ?> Videos Of The Month</h2>
					</div> -->
					<div class="dis_sliderheading single_genre_testing3">
						<h2 class="dis_sliderheading_ttl muli_font">Top <?php echo isset($genre_name)? $genre_name: ''; ?> Videos Of The Month</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<?php echo $this->common_html->swiper_slider($top_videos_ofthe_month);  ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if(isset($new_realeased_video) && !empty($new_realeased_video)){ ?>
	<div class="au_artist_wrapper singl_view"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading">
						<h2>New Released <?php echo isset($genre_name)? $genre_name: ''; ?> Videos</h2>
					</div> -->
					<div class="dis_sliderheading single_genre_testing4">
						<h2 class="dis_sliderheading_ttl muli_font">New Released <?php echo isset($genre_name)? $genre_name: ''; ?> Videos</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<?php echo $this->common_html->swiper_slider($new_realeased_video);  ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="au_artist_wrapper dis_other_video_div singl_view bg-white p_t_40">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<div class="au_heading line_none single_genre_testing5">
						<h2>Other <?php echo isset($genre_name)? $genre_name: ''; ?> Videos</h2>
					</div>
					<!-- <div class="dis_sliderheading single_genre_testing5">
						<h2 class="dis_sliderheading_ttl muli_font">Other <?php echo isset($genre_name)? $genre_name: ''; ?> Videos</h2>
					</div> -->
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div id="loadMoreGenre" class="dis_load_vid genre_single_grid"></div>
				</div>
			</div>
			<?php echo $this->common_html->pro_loader();?>
			<div class="row hide">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="view_all_wrapper">
						<a href="javascript:;" class="dis_btn m_t_30 dis_loadmore" data-load-other-url="genre/loadMoreGenre">load more</a>
					</div>
				</div>
			</div>
		</div>
	</div>