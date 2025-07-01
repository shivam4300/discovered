
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

<?php if(isset($most_popular_videos)){ ?>
	<div class="au_artist_wrapper m_t_50"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading">
						<h2>Most Popular <?php echo isset($category_name)? $category_name: ''; ?> Videos</h2>
					</div> -->
					<div class="dis_sliderheading">
						<h2 class="dis_sliderheading_ttl muli_font">Most Popular <?php echo isset($category_name)? $category_name: ''; ?> Videos</h2>
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

	<?php if(isset($top_videos_ofthe_month)){ ?>
	<div class="au_artist_wrapper"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading">
						<h2>Top <?php echo isset($category_name)? $category_name: ''; ?> Videos Of The Month</h2>
					</div> -->
					<div class="dis_sliderheading">
						<h2 class="dis_sliderheading_ttl muli_font">Top <?php echo isset($category_name)? $category_name: ''; ?> Videos Of The Month</h2>
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
	<?php if(isset($new_realeased_video)){ ?>
	<div class="au_artist_wrapper"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading">
						<h2>New Released <?php echo isset($category_name)? $category_name: ''; ?> Videos</h2>
					</div> -->
					<div class="dis_sliderheading">
						<h2 class="dis_sliderheading_ttl muli_font">New Released <?php echo isset($category_name)? $category_name: ''; ?> Videos</h2>
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

	<div class="au_artist_wrapper dis_other_video_div bg-white p_t_40"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<div class="au_heading">
						<h2>Other <?php echo isset($category_name)? $category_name: ''; ?> Videos</h2>
					</div>
					<!-- <div class="dis_sliderheading">
						<h2 class="dis_sliderheading_ttl muli_font">Other <?php echo isset($category_name)? $category_name: ''; ?> Videos</h2>
					</div> -->
				</div>
			</div>

			<div class="">

				<div id="loadMoreGenre" class="dis_load_vid genre_single_grid">
					
				</div>

			</div>
			<div class="row hide">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="view_all_wrapper">
						<a href="javascript:;" class="dis_btn dis_loadmore" data-load-other-url="icon/loadMoreCateVideo">load more</a>
					</div>
				</div>
			</div>
		</div>
	</div>