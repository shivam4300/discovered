<?php $cnah =  $this->common_html->content_not_available_html();
$open = 1;
if(isset($is_session_uid) && $is_session_uid != 1){
	$open = 0;
}
?>
<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
	<h2 class="my_channel_heading">Featured Video</h2>
	<div class="dis_userhome_video" id="feature_area<?= isset($feature_pid)?$feature_pid:''; ?>">
		<?php if(isset($single_video) && !empty($single_video)){ ?>	
			<div class="pro_chennel_video dis_cardS_oplistWrap">
				<video autoplay loop muted class="feature_video">
					<source src="<?= $feature_video ; ?>" type="video/mp4">
				</video>
				<ul class="dis_cardS_oplist">
					<li>
						<div class="dis_sld_preview openModalPopup" data-href="modal/video_popup/<?=$feature_pid;?>" data-cls="dis_custom_video_popup">
							<span class="preview_txt">Preview</span>
							<span class="pre_icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
								<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
								</svg>
							</span>
						</div>
					</li>
					<?php if(isset($is_session_uid) && $is_session_uid == 1){ ?>
					<li>
						<div class="dis_sld_preview" onclick="redirect('monetize/<?=$feature_pid;?>',10)">
							<span class="preview_txt">Edit</span>
							<span class="pre_icon">
								<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9.68872 1.21677L8.78255 0.310524C8.36849 -0.103518 7.69486 -0.103498 7.28083 0.310524L6.92615 0.665231L9.33403 3.07331L9.68872 2.71861C10.1037 2.30359 10.1038 1.63183 9.68872 1.21677Z" fill="white"></path>
								<path d="M0.429919 7.35832L0.00490067 9.65365C-0.0126579 9.74851 0.0175764 9.84595 0.085799 9.91418C0.1541 9.98248 0.25156 10.0127 0.346306 9.99509L2.64146 9.57004L0.429919 7.35832Z" fill="white"></path>
								<path d="M6.51185 1.07957L0.746063 6.84581L3.15395 9.25388L8.91974 3.48766L6.51185 1.07957Z" fill="white"></path>
								</svg>
							</span>
						</div>
					</li>
					<li>
						<div class="dis_sld_preview delete_channel_video" data-post_id="<?=$feature_pid;?>">
							<span class="preview_txt">Delete</span>
							<span class="pre_icon">
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10.5 1.8H1.5C1.34087 1.8 1.18826 1.86321 1.07574 1.97574C0.963216 2.08826 0.900002 2.24087 0.900002 2.4C0.900002 2.55913 0.963216 2.71174 1.07574 2.82426C1.18826 2.93679 1.34087 3 1.5 3H1.8V10.2C1.80015 10.6773 1.98983 11.1351 2.32737 11.4726C2.6649 11.8102 3.12266 11.9999 3.6 12H8.4C8.87735 11.9999 9.33512 11.8102 9.67266 11.4727C10.0102 11.1351 10.1999 10.6774 10.2 10.2V3H10.5C10.6591 3 10.8117 2.93679 10.9243 2.82426C11.0368 2.71174 11.1 2.55913 11.1 2.4C11.1 2.24087 11.0368 2.08826 10.9243 1.97574C10.8117 1.86321 10.6591 1.8 10.5 1.8ZM5.4 9.4C5.4 9.55913 5.33679 9.71174 5.22427 9.82427C5.11174 9.93679 4.95913 10 4.8 10C4.64087 10 4.48826 9.93679 4.37574 9.82427C4.26322 9.71174 4.2 9.55913 4.2 9.4V5.4C4.2 5.24087 4.26322 5.08826 4.37574 4.97574C4.48826 4.86321 4.64087 4.8 4.8 4.8C4.95913 4.8 5.11174 4.86321 5.22427 4.97574C5.33679 5.08826 5.4 5.24087 5.4 5.4V9.4ZM7.8 9.4C7.8 9.55913 7.73679 9.71174 7.62427 9.82427C7.51174 9.93679 7.35913 10 7.2 10C7.04087 10 6.88826 9.93679 6.77574 9.82427C6.66322 9.71174 6.6 9.55913 6.6 9.4V5.4C6.6 5.24087 6.66322 5.08826 6.77574 4.97574C6.88826 4.86321 7.04087 4.8 7.2 4.8C7.35913 4.8 7.51174 4.86321 7.62427 4.97574C7.73679 5.08826 7.8 5.24087 7.8 5.4V9.4Z" fill="white"></path>
								<path d="M4.8 1.2H7.2C7.35913 1.2 7.51174 1.13679 7.62426 1.02426C7.73678 0.911742 7.8 0.75913 7.8 0.6C7.8 0.44087 7.73678 0.288258 7.62426 0.175736C7.51174 0.0632141 7.35913 0 7.2 0H4.8C4.64087 0 4.48825 0.0632141 4.37573 0.175736C4.26321 0.288258 4.2 0.44087 4.2 0.6C4.2 0.75913 4.26321 0.911742 4.37573 1.02426C4.48825 1.13679 4.64087 1.2 4.8 1.2V1.2Z" fill="white"></path>
								</svg>
							</span>
						</div>
					</li>
					<?php } ?> 
				</ul>
				<div class="chennel_play">
					<a href="<?php echo $single_video; ?>">
					<img src="<?= base_url('repo/images/banner_logo1.png'); ?>" class="img-responsive" alt="video-logo"/>
					</a>
				</div>
				<div class="feature_vidmute">
					<a class="speaker mute" data-video="feature_video">
						<span></span>
					</a>
				</div>
			</div>
			<div class="chnl_btm_wrp">
				<h3 class="chennel_vtitle"><?php echo $title ; ?></h3>
				
				<?php if(isset($is_session_uid) && $is_session_uid == 1){
					echo '<div class="gam-rules-modal-wrapper"><a href="'.base_url('backend/advertising').'" class="dis_btn h_40">choose featured video</a><div id="gam-discovered-rules-modal-root"></div></div>';
				}?>
				
			</div>
		<?php }else{
			echo $cnah;
		} ?>
	
	</div>
</div>