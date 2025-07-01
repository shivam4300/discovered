<script>
	var website_mode = "<?= $website_mode; ?>";
	var loader = `<?= $this->common_html->content_loader_html(); ?>`;
</script>
<div class="dis_monetize_wrap">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="montiz_upld_wrap">
					<div class="cmn_upbox">
						<div class="cmn_upbox_header text-center">
							<h4 class="mu_title">MONETIZE VIDEO/ UPLOAD TO YOUR CHANNEL</h4>
						</div>
						
						<div class="mu_body cmn_upbox_body">
							<p class="mu_subtitle">Before uploading the videos, please review  the <a class="link_color" target="_blank" href="<?= base_url('policies'); ?>">Terms &amp; Conditions</a>. </p>
							<?php if($_SESSION['is_ele']){ ?>
							<div class="dis_videoelephant_btnwrap">
								<div class="dis_velephant_bw_left">

									<h2 class="dis_velephant_bwttl">MRSS Feed</h2>
									<h3 class="dis_velephant_bw_sttl">Get Video Content From MRSS Feed</h3>
								</div>
								<div class="dis_velephant_bw_right">
									<a href="<?=base_url('monetization_by_mrss');?>" class="dis_btn">Get Videos</a>
								</div>
							</div>
							<?php } ?>
							<div class="cmn_upbox_innerbody text-center" id="uploadArea">
								<label class="mu_upld_box inputfile" for="channel_video_uploads" data-id="channel_video_uploads">
								<input type="file" id="channel_video_uploads" name="userfile" class="mu_upload_area inputfile">
									<div class="mu_upld_boxinner">
										<span class="mu_upld_boxicon"><img src="<?= base_url('repo/images/cloud.svg'); ?>"  alt="icon"></span>
										<h2 class="mu_upld_ttl mu_upld_sttl">Upload Single Video</h2>
										<h2 class="mu_upld_dragttl">Drag & Drop Your Video Here <span>OR</span></h2>
										<a class="dis_btn">Browse Video</a>
										<p class="mu_upld_note">*Only MP4 & MOV Video Files Are Supported</p>
									</div>
								</label>
								
								<label class="mu_upld_box inputfile" for="channel_bulk_upload" data-id="channel_bulk_upload">
								<input type="file" id="channel_bulk_upload" name="userfile" class="mu_upload_area inputfile" multiple>
									<div class="mu_upld_boxinner">
										<span class="mu_upld_boxicon"><img src="<?= base_url('repo/images/cloud.svg'); ?>"  alt="icon"></span>
										<h2 class="mu_upld_ttl">Upload Multiple Videos <br>(Max 10 videos at once)</h2>
										<h2 class="mu_upld_dragttl">Drag & Drop Your Video Here <span>OR</span></h2>
										<a class="dis_btn">Browse Video</a>
										<p class="mu_upld_note">*Only MP4 & MOV Video Files Are Supported</p>
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