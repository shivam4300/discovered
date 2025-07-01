<script>
	var website_mode = "<?= $website_mode; ?>";
	var loader = `<?= $this->common_html->content_loader_html(); ?>`;
</script>
<div class="dis_getDis_monetize_wrap">
	<div class="dis_monetize_wrap dis_getDis_monetize">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="montiz_upld_wrap">
						<div class="cmn_upbox">
							<div class="mu_body cmn_upbox_body">
								<div class="dis_getlogo text-center">
									<img src="<?= base_url('repo/images/getDiscovered.png'); ?>"  alt="image">
								</div>
								<div class="dis_getuplodVector">
									<img src="<?= base_url('repo/images/upload_vector.png'); ?>"  alt="image" class="img-responsive">
								</div>

								<?php if($_SESSION['is_ele']){ ?>

								<?php } ?>
								<div class="dis_getdis_CLWRAp m_t_20 text-center">
									<span class="dis_get_cate_text">Choose Your Category - </span>
									<ul class="dis_getdis_categryList">
										<?php if(isset($category) && !empty($category)){
												foreach($category as $cat){
										?>
										<li>
											<input type="radio"  name="video_category" class="cate_radio_button" id="<?=$cat['category_id']?>"  value="<?=$cat['category_id']?>">
											<label class="" for="<?=$cat['category_id']?>"><?=$cat['category_name']?></label>
										</li>
										<?php 	}

										} ?>
									</ul>

								</div>

								<div class="" id="uploadArea">
									<label class="mu_upld_box inputfile" for="channel_video_uploads" data-id="channel_video_uploads">
									<input type="file" id="channel_video_uploads" name="userfile" class="mu_upload_area inputfile hide">
										<div class="mu_upld_boxinner">
											<div class="dis_g_left">
												<span class="mu_upld_boxicon"><img src="<?= base_url('repo/images/cloud.svg'); ?>"  alt="icon"></span>
											</div>
											<div class="dis_g_right">
												<h2 class="dis_getDDYV">Drag & Drop Your Video Here </h2>
												<a class="dis_getBYV"><span>OR</span> Browse Your Video</a>
											</div>
										</div>
									</label>
								</div>


								<div class="dis_monetize_mwrap">
									<div class="montz_videouplod_wrap _progress_bar hide" >
										<div class="montz_progress">
											<div class="monyz_prog_fill _progress_percent" >
												0%
											</div>
										</div>
										<div class="montz_vid_name">
											<span class="montz_vid_ttl _progress_title"></span>
										</div>
										<div class="montz_vid_cncl">
											<a class="dis_btn b_btn _process_abort">Cancel</a>
										</div>
									</div>
								</div>
								<p class="dis_getsupport text-center">*Only MP4 & MOV Video Files Are Supported</p>
								<div class="clearfix"></div>
							</div>


							<?php if(isset($uid) && ($uid == '478' || $uid == '2229' || $uid == '4135') ){ ?>
							<div class="mu_body cmn_upbox_body dis_simultv">
								<div class="cmn_upbox_innerbody text-center">
									<form action="<?= base_url('dashboard/upload_channel_video'); ?>" method="post" enctype= multipart/form-data>
									<div class="dis_upload_div">
										<!--<input type="file" id="custom_files" name="userfile" class="inputfile">-->
										<label>
											<figure>
												<span class="mu_upld_boxicon"><img src="<?= base_url('repo/images/cloud.svg'); ?>"  alt="icon"></span>
											</figure>
											<span >Enter HLS URL</span> To MONETIZE VIDEO
										</label>
									</div>

									<div class="dis_selecthls_wrapper">
										<div class="hls_input">
											<!-- <textarea class="form-control" id="m3u8"></textarea> -->
											<input type="text" class="dis_input_filed" id="m3u8" placeholder="Enter HLs URL"/>

										</div>
										<div class="hls_btn">
											<a class="dis_btn  SetHlsUrl">Submit</a>
										</div>
									</div>
									</form>
								</div>
							</div>
							<?php }  ?>



						</div>
						<p class="mu_subtitle getdisocvered">Before uploading the videos, please review  the <a class="link_color" target="_blank" href="<?= base_url('policies'); ?>">Terms &amp; Conditions</a>. </p>
					</div>
				</div>
			</div>
			<div class="LoadMonetizePage">
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="montiz_details_prg hideme">
						<form class="submitBulkUploadForm" action="dashboard/AddBulkUploadVideos">
						<div class="cmn_upbox">
							<div class="cmn_upbox_header text-center">
								<h4 class="mu_title">Enter Video Details</h4>
							</div>
							<div class="cmn_upbox_body">
								<div class="dis_bu_glboletopbar">
									<p>Select Global Mode and Genre</p>
									<ul class="dis_bu_glboleSelect">
										<li>
											<div class="dis_field_wrap dis_select2">
												<select class="primay_select dis_field_input GloabalMode" name="mode" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}">
													<option value="">Select Mode*</option>
													<?= $website_mode; ?>
												</select>
											</div>
										</li>
										<li>
											<div class="dis_field_wrap dis_select2">
												<select class="primay_select dis_field_input GloabalGenre" name="genre" data-target="select2" data-option="{minimumResultsForSearch:1, width:'100%'}">
													<option value="">Select Genre*</option>
												</select>
											</div>
										</li>
									</ul>
								</div>
								<ul class="cmn_upbox_multibox ">


								</ul>

							</div>
						</div>
						<div class="montiz_details_btn text-center m_t_30">
							<div class="checkbox dis_checkbox">
								<label>
									<input type="checkbox" value="" class="check">
									<i class="input-helper"></i>
									<p>I have read the <a target="_blank" href="<?= base_url('policies'); ?>">Terms &amp; Conditions</a> , and agree that the video(s) belongs to me and I am liable for all the copyright issues if any</p>
								</label>
								<span class="form-error help-block" id="check"></span>
							</div>

							<button type="submit" class="dis_btn">Publish Video<span class="publish_btn hideme">
							<i class="fa fa-spinner fa-pulse fa-fw"></i></span></button>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bulk_upload_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="MoreDetails">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title update_text_header">Update More Details</h4>
	  </div>
	  <div class="modal-body" id="MoreBulkVideoDetails">


	  </div>
	</div>
  </div>
</div>












