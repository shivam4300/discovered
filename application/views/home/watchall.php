



	<div class="au_artist_wrapper dis_other_video_div single_sellall p_t_40 bg-white"><!-- Artist section -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 text-center">
					<div class="au_heading">
						<h2> <?php if(isset($Title)){echo $Title;}?></h2>
					</div>
				</div>
			</div>

			<div class="">
				<?php
					// echo $other_video;
				?>
				<div id="loadMoreGenre" class="genre_single_grid">

				</div>

			</div>
			<?php echo $this->common_html->pro_loader(); ?>

			<div class="row hide">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="view_all_wrapper">
						<a href="javascript:;" class="dis_seemore_btn dis_loadmore watchall watchLoadMore">load more</a>
					</div>
				</div>
			</div>
		</div>
	</div>
