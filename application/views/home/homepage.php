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

	<?php 
	if(isset($sub_genres) && !empty($sub_genres) ){ 
		
		$subGenreLoop = '';
		foreach($sub_genres as $sub){ 
			$subGenreLoop .= 
				'<div class="swiper-slide">
					<div class="dis_post_video_data">
						<div class="dis_postvideo_img">
							<a href="'.base_url('genre?g=').$sub['genre_slug'].'&l=2">
								<img onerror="this.onerror=null;this.src="'.base_url('repo/images/thumbnail.jpg').'";" src="'.CDN_BASE_URL . 'repo_admin/images/genre/'.$sub['image'].'" class="img-responsive" alt="">
							</a>					
						</div>
						<div class="dis_postvideo_content">
							<h3><a href="'.base_url('genre?g=').$sub['genre_slug'].'&l=2 ">'.$sub['genre_name'].'</a></h3>
						</div>
					</div>
				</div>'
			;
		}
		
		$TopGames = '<div class="au_artist_wrapper singl_view m_t_50 padding_bottom_0">
		<div class="container-flui">
			<div class="">
				<div class="">
					<div class="dis_sliderheading">
						<div class="dis_sliderheadingL">
						<h2 class="dis_sliderheading_ttl muli_font">
						Top Games
						</h2>
						</div>
					</div>
				</div>
			</div>
			<div class="">
				<div class="au_artist_slider" data-autoplay="3500">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							'.$subGenreLoop.'
						</div>
						<div class="swiper-button-next cs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
   						<div class="swiper-button-prev cs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div> 
					</div>
				</div>
			</div>
		</div>
	</div>';	
	
	}	
	?>
		
	<?php if(isset($rndmGenr) ){ 
		$insideder = '';
		foreach($rndmGenr as $rndmGenrData){ 
		$insideder .= '<div class="swiper-slide">
						<div class="dis_post_video_data">
							<div class="dis_postvideo_img">
								<a href="'.base_url('genre?g=').$rndmGenrData['genre_slug'].'">
									<img onerror="this.onerror=null;this.src=\''. base_url('repo/images/thumbnail.jpg')  .'\';" src="'. CDN_BASE_URL . 'repo_admin/images/genre/'.$rndmGenrData['image'] .'" class="img-responsive" alt="">
								</a>
							</div>
							<div class="dis_postvideo_content">
								<h3><a href="'. base_url('genre?g=').$rndmGenrData['genre_slug'] .' ">'. $rndmGenrData['genre_name'] .'</a></h3>
							</div>
						</div>
					</div>';
		}
		
		$outSlide = '<div class="au_artist_wrapper explore_video_by_genre">
			<div class="container-flu">
				<div class="ro">
					<div class="">
						<div class="dis_sliderheading">
							<div class="dis_sliderheadingL">
								<h2 class="dis_sliderheading_ttl muli_font">Explore Videos By Genres</h2>
							</div>
							<div class="dis_sh_btnwrap hide">
								<a href="'.base_url('genre?g=all').'" class="dis_sh_btn muli_font">See All<span class="dis_sh_btnicon">
									<svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
									<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
									</svg>
									</span>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="">
					<div class="au_artist_slider " data-autoplay="4000">
						<div class="swiper-container">
							<div class="swiper-wrapper">
								'.$insideder.'
							</div>
							<div class="swiper-button-next cs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
							<div class="swiper-button-prev cs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div> 
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	?>
	
	<script>
		var ExploreSlider = `<?= isset($outSlide)? $outSlide : ''; ?>`;
		var TopGames = `<?= isset($TopGames)? $TopGames : ''; ?>`;
	</script>
		
	<div id="appendSlider"  class="slider_rotate">
		<?php
			$h = '';
			for($j=0;$j<2;$j++){
				$h .= '<div class="dis_slider_skeleten ">
				<div class="dis_skeleton_line heading dsl_15 dslW_50"></div>
				<ul class="dis_sliderSkeletn">';
				for($i=0;$i<=4;$i++){
					$h .= '<li>
					
					<div class="dis_skeletonRectangle"></div>
					<div class="dis_skeleton_line dsl_15 dslW_50"></div>
					</li>';
				}
				$h .= ' </ul></div>';
			}
			echo $h;
			
		?>
	</div>
	
			