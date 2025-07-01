
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
	<div class="au_artist_wrapper slider_rotate m_t_50" ><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<!-- <div class="au_heading">
						<h2><?php echo isset($page_info['title'])?$page_info['title']:''; ?></h2>
					</div> -->
					<div class="dis_sliderheading">
						<h2 class="dis_sliderheading_ttl muli_font"><?php echo isset($page_info['title'])?$page_info['title']:''; ?></h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<?php echo $this->common_html->swiper_slider($category_video_by_group);  ?>
				</div>
			</div>
		</div>
	</div>
	
	
	<div id="appendGenreSlider" data-url="icon/GetCategorVideoSlider" data-load="1">
	
	</div>
	<?php echo $this->common_html->pro_loader(); ?>

<!--service start-->
<!--service end-->