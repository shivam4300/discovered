<style>
.dis_stream_sp_toplist > li > a:hover, .dis_stream_sp_toplist > li > a:focus {
    background: transparent;
}
section.dis_stream_stepprocess_wrap {
    padding: 80px 0;
}
.dis_stream_sp_toplist {
    display: flex;
    flex-wrap: wrap;
}
.dis_stream_sp_toplist li {
    width: 33.3%;
    text-align: center;
}
.dis_stream_sp_round {
    display: inline-flex;
    height: 35px;
    width: 35px;
    text-align: center;
    background-color: #f5f5f5;
    border: 2px solid #d1d4df;
    border-radius: 50%;
    color: #d1d4df;
    font-weight: 500;
    justify-content: center;
    align-items: center;
}
.dis_stream_sp_toplist li:before {
    background-color: #d1d4df;
    bottom: 0;
    content: "";
    height: 2px;
    position: absolute;
    right: 0;
    top: 26px;
    width: 100%;
    z-index: -1;
    left: 50%;
}
.dis_stream_sp_toplist li:last-child:before{
    display: none;
}
.dis_stream_sp_bttl {
    font-size: 16px;
    font-weight: 600;
    color: #9ca0ad;
    margin: 0;
    padding-top: 20px;
}
.dis_stream_sp_toplist li.active .dis_stream_sp_round {
    background: #eb581f;
    border-color: #eb581f;
    color: #ffffff;
}
.dis_stream_sp_toplist li.active .dis_stream_sp_bttl {
    color: #eb581f;
}
.dis_stream_sp_btwrap {
    width: 100%;
    float: left;
}
.dis_stream_sp_btnlist {
    list-style: none;
    padding: 0;
    margin: 0;
    justify-content: flex-end;
    display: flex;
    flex-wrap: wrap;
    margin: -5px;
}
.dis_stream_sp_btnlist > li {
    margin: 5px;
}
.dis_stream_sp_btnlist .dis_btn {
    min-width: inherit;
    padding: 0 30px;
}
.dis_stream_sp_mttl {
    font-size: 20px;
    color: #3f3f59;
    margin: 0;
    font-weight: 700;
}
.dis_stream_sp_note{
    font-size: 20px;
    font-weight: 600;
    color: #eb581f;
    margin: 0;
}
.dis_stream_sp_ttl{
    font-size: 18px;
    font-weight: 600;
    color: #222;
    margin: 0;
    padding-bottom: 10px;
}
.dis_s_sp_ip .primary_link {
    word-break: break-all;
}
.dis_s_sp_ip {
    list-style-type: decimal;
    font-family: 'Muli', sans-serif;
}
.dis_sinfo_inner .dis_s_sp_ip {
    padding-left: 40px;
}
.dis_s_sp_ip li {
    color: #222;
    font-weight: 600;
    padding-bottom: 10px;
}
.dis_s_sp_ip li:last-child {
    padding-bottom: 0;
}
.dis_stream_sp_iframe {
    border: 4px solid #c1c1c1;
    border-radius: 3px;
    margin-bottom: 30px;
}
.dis_stream_sp_des {
    font-size: 18px;
    text-align: center;
    color: #3f3f59;
    font-weight: 700;
}
.primary_link, .primary_link:hover, .primary_link:focus {
    color: #eb581f;
}
 /* request page field */
 .dis_steaming_request_filed .dis_sinfo_des {
    font-weight: 700;
}
 .dis_stream_labelinput label {
    font-size: 16px;
    font-family: 'Muli';
    color: #222;
    position: relative;
    font-weight: 600;
    margin:0;
}
.dis_stream_req_lbl {
    position: relative;
}
.dis_stream_labelinput label .login_tooltip, .dis_stream_labelinput .login_cstm_pop {
    right: -18px;
}
.dis_stream_labelinput {
    position: relative;
}
.dis_stream_labelinput input {
    border: 1px solid #969696;
    box-shadow: none;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 5px;
    max-width: 60px;
    outline: 0;
    text-align: center;
}
.dis_stream_labelinput input::placeholder{
    color: rgb(218 218 218);
}
.dis_stream_labelinput input::-ms-input-placeholder{
    color: rgb(218 218 218);
}
.dis_stream_labelinput input::-ms-input-placeholder {
    color: rgb(218 218 218);
}
/* Remove  Arrows From Input Number  */
/* Chrome, Safari, Edge, Opera */
.dis_stream_labelinput input::-webkit-outer-spin-button, .dis_stream_labelinput input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
/* Firefox */
.dis_stream_labelinput input[type=number] {
  -moz-appearance: textfield;
}
.dis_stream_req_input{
    padding-top:10px ;
}
.dis_steaming_request_filed .login_cstm_pop {
    font-family: 'Muli', sans-serif;
}
.dis_steaming_info_wrap .dis_btn, .dis_steaming_request_filed .dis_btn{
    min-width: inherit;
    padding: 0 20px;
}
/***** */
.dis_stream_sp_tab_inner .dis_upload_form.dis_signup_form {
    padding-top: 0;
    padding-bottom:0;
}
.dis_stream_sp_tab_inner {
    font-family: 'Muli', sans-serif;
}
.dis_stream_sp_tab_inner .dis_upload_video_inner {
    padding: 0px 10px 20px;
}
.dis_stream_sp_tab_inner .dis_upload_video {
    margin: 50px 0 0px;
    text-align: left;
    padding-bottom: 0;
}
/********dar mode */
.theme_dark .dis_sinfo_inner{
    border-color:var(--border_color);
    background-color: var(--sec_bg_color);
}
.theme_dark .dis_sinfo_ttl, .theme_dark .dis_stream_labelinput input, .theme_dark .dis_stream_sp_note  {
    color: var(--white_color);
}
.theme_dark .dis_stream_labelinput input {
    border-color: #8f9da5;
    background-color: var(--sec_bg_color);
}
.theme_dark .dis_surl_list_ttl, .theme_dark .dis_su_label, .theme_dark .dis_stream_labelinput label {
    color: var(--text_color);
}
.theme_dark .dis_su_input {
    border-color:var(--border_color);
    background-color: var(--sec_bg_color);
    color: var(--text_color);
}
.theme_dark .dis_stream_sp_des, .theme_dark .dis_stream_sp_mttl, .theme_dark .dis_stream_sp_ttl, .theme_dark .dis_s_sp_ip li, .theme_dark .dis_sinfo_des {
    color: var(--text_color);
}
.theme_dark .dis_surl_list_icon svg path, .theme_dark .dis_surl_list_icon svg rect{
    fill: var(--white_color);
}
.theme_dark .dis_stream_sp_round {
    background:  var(--sec_bg_color);
}
.theme_dark .dis_surl_copybtn {
    background: rgb(51, 71, 81);
    border-color:rgb(51, 71, 81);
}
/* *----TRsponsive----* */
@media (min-width: 1200px) and (max-width: 1550px){
.dis_stream_stepprocess_wrap .container {
    width: 1170px!important;
}
}
@media (min-width: 992px) {
    .dis_stream_sp_tab_one .dis_surl_list > li:first-child {
    margin-right: 80px;
}
.dis_stream_sp_tab_inner .dis_upload_video_inner {
    padding: 0px 50px 50px;
}
}
@media (min-width: 576px) {
    .dis_stream_labelinput {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
}
.dis_stream_req_lbl {
    position: relative;
    width: calc(100% - 70px);
}
.dis_stream_req_input{
    padding-left:10px ;
}
}
@media (max-width: 992px) {
    .dis_stream_sp_tab_inner .dis_upload_video_inner {
    padding: 0px 20px 20px;
}
}
@media (max-width: 575px) {
    .dis_sinfo_inner {
    padding: 20px 20px;
}
}
</style>
<?php
    
    $liveData = 0;
	if(isset($medialive[0]['stream_info']) && !empty($medialive[0]['stream_info']) ){ 
		$liveData       = 1;
		$media_live  	= $medialive[0];
		$media_info 	= json_decode($media_live['media_info'] , true);
	}
    
	$live_pid  			= ($liveData == 1) ? $media_live['live_pid'] : '';
	$is_live  			= ($liveData == 1) ? $media_live['is_live'] : 0;
	$status  			= (int) ($liveData == 1) ? $media_live['status'] : 3;


	$Destinations 		= isset($media_info['input']['Destinations'])? $media_info['input']['Destinations'] : [] ;
	$input_id 			= isset($media_info['input']['Id'])? $media_info['input']['Id'] : '' ;
	$channel_id 		= isset($media_info['Channel']['Id'])? $media_info['Channel']['Id'] : '' ;
	
if($is_live == 1 && !empty($channel_id)){ 
	redirect(base_url('media_stream/mstream'));
}	

?>
<input type="hidden" value="<?= $live_pid; ?>" id="live_pid">
<input type="hidden" value="<?= $status; ?>" id="request_status">

<?php if($status == 0 || $status == 2 || $status == 3 || $status == 4){ ?> <!--When status is disable or requested or hold -->

<div class="dis_steaming_cmn_req dis_steaming_info_wrap" id="StreamInfo">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="dis_sinfo_inner">
                    <div class="dis_sinfo_box">
                        <h2 class="dis_sinfo_ttl ">Discovered Live Streaming</h2>
                        <p class="dis_sinfo_des p_b_20 p_t_20">Here are the steps & information needed for streaming through  discovered -</p>
                        <?php if($status == 3){ ?>
							<ul class="dis_s_sp_ip">
								<li> You need a one time approval for streaming.</li>
								<li>Upon approval you can stream immediately or schedule it for a later date.</li>
								<li>For every stream you would need to provide basic video/stream details.</li>
								<li>Once the streaming ends, the video is automatically uploaded in your channel and will continue to be monetized.</li>
								<li>You can choose not to monetize by setting the video to offline/private mode from dashboard.</li>
							</ul>
							<p class="dis_sinfo_des p_b_20 p_t_30">Prerequisites</p>
							<ul class="dis_s_sp_ip">
								<li>You need to have a high speed internet connection and a high resolution camera attached to your computer.</li>
							</ul>
						<div class="dis_sinfo_btn m_t_30">
                            <a href="javascript:;" class="dis_btn" onclick="$('#StreamForm').removeClass('hide');$('#StreamInfo').addClass('hide');">Continue</a>
                        </div>
						<?php }else{ ?>
						<div class="dis_sinfo_btn m_t_30">
                            <!--a href="javascript:;" class="dis_btn" >Back</a-->
							<a href="javascript:;" class="dis_btn <?= ($status == 3)? 'RequestToLiveStream' : '' ?>" >
							<?= ($status == 2)? 'Requested' : ( ($status == 0) ? 'declined' : 'Request' ) ; ?> To Live Stream</a>
						</div>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="dis_steaming_cmn_req dis_steaming_request_filed hide" id="StreamForm">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="dis_sinfo_inner">
                    <div class="dis_steaming_rf_box"> 
                        <p class="dis_sinfo_des p_b_30">Please fill these basic details to request live streaming.</p>
						<form action="Media_stream/requestToLiveStream" class="RequestToLiveStream"> 
                            <div class="dis_stream_labelinput m_b_10">
                                <div class="dis_stream_req_lbl">    
                                    <label>Average number of viewers per stream</label>
                                </div>
                                <div class="dis_stream_req_input">
                                    <input type="number" class="require" name="number_of_viewers" placeholder="1" min="1" data-error="Please enter all the streaming video details before requesting.">
                                </div>
                            </div>
                            <div class="dis_stream_labelinput p_b_20">
                                <div class="dis_stream_req_lbl">    
                                    <label>Average Duration per stream in minutes
                                        <div class="login_tooltip">
                                            <i class="fa fa-question-circle " aria-hidden="true"></i>
                                        </div>
                                        <span class="login_cstm_pop">For emerging creators, expectation is to have a minimum duration of 10 minutes per stream </span>
                                    </label>
                                    
                                </div>
                                <div class="dis_stream_req_input">
                                    <input type="number" class="require" name="average_stream_duration" placeholder="1" min="1" data-error="Please enter all the streaming video details before requesting.">
                                </div>
                            </div>
                            <div class="dis_stream_labelinput p_b_20">
                                <div class="dis_stream_req_lbl">    
                                    <label>Number of Streams per month are you likely to broadcast</label>
                                </div>
                                <div class="dis_stream_req_input">
                                    <input type="number" class="require" name="streams_per_month" placeholder="1" min="1" data-error="Please enter all the streaming video details before requesting.">
                                </div>
                            </div>
                            <div class="dis_stream_labelinput">
                                <div class="dis_stream_req_lbl">    
                                    <label> Total number of streams you plan to broadcast</label>
                                </div>
                                <div class="dis_stream_req_input">
                                    <input type="number" class="require" name="total_number_of_stream" placeholder="1" min="1" data-error="Please enter all the streaming video details before requesting.">
                                </div>
                            </div>
							<div class="dis_sinfo_btn m_t_30">
								<a href="javascript:;" class="dis_btn" onclick="$('#StreamInfo').removeClass('hide');$('#StreamForm').addClass('hide');">Back</a>
								<button class="dis_btn" type="<?= ($status == 3)? 'submit':'button'; ?>"><?= ($status == 2)? 'Requested' : ( ($status == 0) ? 'declined' : 'Request' ) ; ?> To Live Stream</button>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php }else{
	// redirect(base_url('media_stream/mstreming')); 
?>

<section class="dis_stream_stepprocess_wrap full_vh_foooter">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="dis_stream_step_inner">
                        <div class="dis_stream_step_tbar">
                            <div class="connecting-line"></div>
                            <ul class="nav dis_stream_sp_toplist">
                                <li class="active">
                                    <a href="#step1" data-toggle="tab" style="pointer-events: none;">
                                        <span class="dis_stream_sp_round">1 </span>
                                        <p class="dis_stream_sp_bttl">Enter Video/Stream Details</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#step2" data-toggle="tab" style="pointer-events: none;">
                                        <span class="dis_stream_sp_round">2</span>
                                        <p class="dis_stream_sp_bttl">Download Streaming Software</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#step3" data-toggle="tab" style="pointer-events: none;">
                                        <span class="dis_stream_sp_round">3</span>
                                        <p class="dis_stream_sp_bttl">Set Up Streaming Software</p>
                                    </a>
                                </li>
                            </ul>
                        </div>        
                        
                        <div class="tab-content">
                            <div class="tab-pane active" id="step1">
                                <div class="dis_stream_sp_tab_inner dis_stream_sp_tab_one">
                                    <form class="SubmitStream" action="Media_stream/submitStream">
                                    <div class="dis_upload_video">
                                        <div class="dis_video_heading">
                                            <h3>Video Details</h3>
                                        </div>
                                        <div class="dis_upload_video_inner">                        
                                            <div class="row">
                                                <div class="dis_upload_form dis_signup_form">
                                                    <!-- <form class="SubmitStream" action="Streaming/submitStream"> -->
														<div class="col-md-4">
															<div class="dis_sd_upload_thumb">
																<div class="dis_sd_ut_box">
																	<input type="file" id="Stream_thumb" name="userfile" class="cmn_inputfile previewFile" data-id="#StreamThumb" accept="image/png, image/jpg, image/jpeg">												
																	<label class="Stream_thumb_icon" for="Stream_thumb" id="StreamThumb" style="background-repeat: no-repeat;background-size:cover">
																		<span>
																			<svg xmlns="https://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 45 35"><path class="cls-1" fill="#777" fill-rule="evenodd" d="M1348.68,1216.23a12.509,12.509,0,0,0-12.59-12.23,12.654,12.654,0,0,0-8.3,3.09,12.323,12.323,0,0,0-4,6.76h-0.13a10.506,10.506,0,1,0,0,21.01h7.45a0.945,0.945,0,1,0,0-1.89h-7.45a8.616,8.616,0,1,1,0-17.23c0.26,0,.53.02,0.84,0.04a0.954,0.954,0,0,0,1.04-.81,10.4,10.4,0,0,1,3.52-6.46,10.691,10.691,0,0,1,17.7,7.89c0,0.21-.01.42-0.03,0.65l-0.01.1a0.931,0.931,0,0,0,.29.74,0.979,0.979,0,0,0,.77.27,6.45,6.45,0,0,1,.76-0.04,7.426,7.426,0,1,1,0,14.85h-7.83a0.945,0.945,0,1,0,0,1.89h7.83A9.316,9.316,0,1,0,1348.68,1216.23Zm-12.59-7.79a8.068,8.068,0,0,0-7.99,6.87,0.956,0.956,0,0,0,.82,1.07,0.66,0.66,0,0,0,.14.01,0.949,0.949,0,0,0,.94-0.82,6.15,6.15,0,0,1,6.09-5.24A0.945,0.945,0,1,0,1336.09,1208.44Zm4.37,18.61-3.49-3.08a1.6,1.6,0,0,0-2.11,0l-3.5,3.08a0.928,0.928,0,0,0-.07,1.33,0.971,0.971,0,0,0,1.35.08l2.31-2.04v11.63a0.96,0.96,0,0,0,1.92,0v-11.63l2.31,2.04a0.959,0.959,0,0,0,1.35-.08A0.928,0.928,0,0,0,1340.46,1227.05Z" transform="translate(-1313 -1204)"></path></svg>
																		</span> 
																	</label>    
																</div>
																<h2 class="dis_sd_ut_ttl">Upload Custom Thumbnail</h2>
															</div>
														</div>
														<div class="col-md-8">
															<div class="col-sm-6">
																<div class="form-group">
																	<div class="input-group">
																	<select class="form-control dis_signup_input required"  name="mode" id="mode">
																		<option value="">Select Mode</option>
																		<?php
																		foreach($website_mode as $mode){
																			if($mode["mode_id"] != 8)
																			echo '<option value="'.$mode["mode_id"].'">'.ucfirst($mode["mode"]).'</option>';
																		}
																		?>
																	</select>
																	</div>
																	<span class="form-error help-block"></span>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group">
																	<div class="input-group">
																	<select class="form-control dis_signup_input required" name="genre" id="genre">
																		<option value="">Select Genre</option>
																	</select>
																	</div>
																	<span  class="form-error help-block"></span>
																</div>
															</div>															                        <div class="col-sm-6">
																<div class="form-group">
																	<div class="input-group">
																	<select class="form-control dis_signup_input" name="sub_genre" id="sub_genre">
																		<option value="">Select Sub Genre</option>
																	</select>
																	</div>
																	<span  class="form-error help-block"></span>
																</div>
															</div>
															
															<div class="col-sm-6">
																<div class="form-group">
																	<div class="input-group">
																		<select class="form-control dis_signup_input" name="category" >
																			<option value="">Select Category (optional)</option>
																			<?php 
																				foreach($catDetail as $cat){
																					$selected = ($cat['category_name'] == 'Other') ? 'selected="selected"' : '';
																					echo '<option '.$selected.' value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
																				}
																				
																			?>
																		</select>
																		<div class="login_tooltip">
																			<i class="fa fa-question-circle " aria-hidden="true"></i>
																		</div>
																		<span class="login_cstm_pop">Choosing the Category, will help fans to view Video content based on Category code linked to each video. If you don't make a selection, system will auto-categorize as OTHER</span>
																	</div>
																	<span class="form-error help-block"></span>
																</div>
															</div>                                        
															<div class="col-sm-6">
																<div class="form-group">
																	<div class="input-group">
																		<input type="text"  class="form-control dis_signup_input required" placeholder="Video Title" name="title" maxlength="100" >
																	</div>
																	<span  class="form-error help-block"></span>
																</div>
															</div>                                        
															<div class="col-sm-6">
																<div class="form-group">
																	<div class="input-group">																		
																		<textarea class="form-control dis_signup_input" placeholder="Video Description" name="description" ></textarea>																		
																	</div>
																	<span  class="form-error help-block"></span>
																</div>
															</div>
                                                                                                   
														</div> 
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
															<div class="form-group">
																<div class="input-group">
																	<select class="form-control dis_signup_input required"  name="language" >
																		<option value="">Select Video Language</option>
																		<?php 
																			foreach($language_list as $list){
																				$sel = ($list['id']=='en_US')?'selected="selected"':'';
																				echo '<option '.$sel.' value="'.$list['id'].'">'.$list['value'].'</option>';
																			}
																			
																		?>
																	</select>
																</div>
																<span class="form-error help-block"></span>
															</div>
														</div>
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
															<div class="form-group">
																<div class="input-group">
																<?php $ages = $this->audition_functions->age(); ?>
																	<select class="form-control dis_signup_input required" name="age_restr" >
																		<option value="">Age Restrictions(in year)</option>
																			<?php 
																				foreach($ages as $age){
																					$sel = ($age == 'Unrestricted')?'selected="selected"':'';
																					echo '<option '.$sel.' value="'.$age.'">'.$age.'</option>';
																				}
																				
																			?>
																	 </select>
																	
																</div>
																<span class="form-error help-block"></span>
															</div>
														</div>
														<!-- <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
															<div class="form-group">
																<div class="input-group">
																	<select class="form-control dis_signup_input required" >
																		<option value="">Share this video to social page as well ?</option>                                                        
																	</select>
																</div>
																<span class="form-error help-block"></span>
															</div>
														</div> -->
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
															<div class="form-group">
																<div class="input-group">
																	<select class="form-control dis_signup_input required" name="privacy_status" >
																		<?php
																			$ss =$this->audition_functions->post_status();
																				echo '<option value="">Select Privacy</option>';
																				foreach($ss as $k=>$v){
																					$sel = ($k == 7)?'selected="selected"':'';
																					echo '<option '.$sel.' value="'.$k.'">'.$v.'</option>';
																				} 
																			?>
																	</select>
																</div>
																<span class="form-error help-block"></span>
															</div>
														</div>
														<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
															<ul class="dis_surl_list m_b_30">
																<li>
																	<div class="dis_surl_list_ttlicon">
																		<span class="dis_surl_list_icon">
																			<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M452,40h-24V0h-40v40H124V0H84v40H60C26.916,40,0,66.916,0,100v352c0,33.084,26.916,60,60,60h392c33.084,0,60-26.916,60-60V100C512,66.916,485.084,40,452,40z M472,452c0,11.028-8.972,20-20,20H60c-11.028,0-20-8.972-20-20V188h432V452z M472,148H40v-48c0-11.028,8.972-20,20-20h24v40h40V80h264v40h40V80h24c11.028,0,20,8.972,20,20V148z"></path></g></g><g><g><rect x="76" y="230" width="40" height="40"></rect></g></g><g><g><rect x="156" y="230" width="40" height="40"></rect></g></g><g><g><rect x="236" y="230" width="40" height="40"></rect></g></g><g><g><rect x="316" y="230" width="40" height="40"></rect></g></g><g><g><rect x="396" y="230" width="40" height="40"></rect></g></g><g><g><rect x="76" y="310" width="40" height="40"></rect></g></g><g><g><rect x="156" y="310" width="40" height="40"></rect></g></g><g><g><rect x="236" y="310" width="40" height="40"></rect></g></g><g><g><rect x="316" y="310" width="40" height="40"></rect></g></g><g><g><rect x="76" y="390" width="40" height="40"></rect></g></g><g><g><rect x="156" y="390" width="40" height="40"></rect></g></g><g><g><rect x="236" y="390" width="40" height="40"></rect></g></g><g><g><rect x="316" y="390" width="40" height="40"></rect></g></g><g><g><rect x="396" y="310" width="40" height="40"></rect></g></g></svg>
																		</span>
																		<span class="dis_surl_list_ttl">Schedule Stream</span>
																	</div>
																</li>
																<li class="active">
																	<label class="tgl_switch_btn">
																	  <input type="checkbox" class="tgl_sb_input" name="schedule">
																	  <span class="tgl_sb_btn"></span>
																	</label>
																</li>
															</ul>
															
															<div class="dis_su_field m_b_30 text-left dateArea" style="display:none;">
																<label class="dis_su_label">Select Date Time </label>
																<div class="dis_su_fieldbtn">
																	<input readonly type="text" class="streamdatepicker dis_su_input" name="scheduled_time">
																</div>
															</div>
														</div>
														<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
															<ul class="dis_surl_list">
																<li>
																	<div class="dis_surl_list_ttlicon">
																		<span class="dis_surl_list_icon">
																			<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"> <g> <g> <path d="M304,96H112c-8.832,0-16,7.168-16,16c0,8.832,7.168,16,16,16h192c8.832,0,16-7.168,16-16C320,103.168,312.832,96,304,96z" /> </g> </g> <g> <g> <path d="M240,160H112c-8.832,0-16,7.168-16,16c0,8.832,7.168,16,16,16h128c8.832,0,16-7.168,16-16 C256,167.168,248.832,160,240,160z"/> </g> </g> <g> <g> <path d="M352,0H64C28.704,0,0,28.704,0,64v320c0,6.208,3.584,11.872,9.216,14.496C11.36,399.488,13.696,400,16,400 c3.68,0,7.328-1.28,10.24-3.712L117.792,320H352c35.296,0,64-28.704,64-64V64C416,28.704,387.296,0,352,0z M384,256 c0,17.632-14.336,32-32,32H112c-3.744,0-7.36,1.312-10.24,3.712L32,349.856V64c0-17.632,14.336-32,32-32h288 c17.664,0,32,14.368,32,32V256z"/> </g> </g> <g> <g> <path d="M448,128c-8.832,0-16,7.168-16,16c0,8.832,7.168,16,16,16c17.664,0,32,14.368,32,32v270.688l-54.016-43.2 c-2.816-2.24-6.368-3.488-9.984-3.488H192c-17.664,0-32-14.368-32-32v-16c0-8.832-7.168-16-16-16c-8.832,0-16,7.168-16,16v16 c0,35.296,28.704,64,64,64h218.368l75.616,60.512C488.896,510.816,492.448,512,496,512c2.336,0,4.704-0.512,6.944-1.568 C508.48,507.744,512,502.144,512,496V192C512,156.704,483.296,128,448,128z"/> </g> </g> </svg>
																		</span>
																		<span class="dis_surl_list_ttl"> Enable Chat</span>
																	</div>
																</li>
																<li class="active">
																	<label class="tgl_switch_btn">
																	<input type="checkbox" class="tgl_sb_input" checked="" name="is_chat">
																	<span class="tgl_sb_btn"></span>
																	</label>
																</li>
															</ul>
														</div> 
													 
														<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <ul class="dis_stream_sp_btnlist">
                                                                <li><button type="submit" class="sumbmitBtn dis_btn stream_sp_btn stream_sp_next_step">Continue<span class="publish_btn hideme"> 
																<i class="fa fa-spinner fa-pulse fa-fw"></i></span></button></li>
                                                            </ul>
                                                        </div> -->
													<!-- </form> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="dis_stream_sp_btwrap m_t_30">
                                        <ul class="dis_stream_sp_btnlist">
                                            <li><button type="submit" class="sumbmitBtn dis_btn stream_sp_btn">Continue
												<span class="publish_btn hideme">
													<i class="fa fa-spinner fa-pulse fa-fw"></i>
												</span></button>
											</li>
                                        </ul>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane" id="step2">
                                <div class="dis_stream_sp_tab_inner">
                                    <div class="dis_upload_video">
                                        <div class="dis_video_heading">
                                            <h3>Download Streaming Software</h3>
                                        </div>
                                        <div class="dis_upload_video_inner">                        
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="dis_download_Software">
                                                        <p class="dis_stream_sp_note">*Ignore this step if you have already installed the streaming software.</p>
                                                        <h2 class="dis_stream_sp_mttl p_b_30 p_t_30">You need to download the streaming software to share your screen, capture live video from camera.</h2>
                                                        
                                                        <p class="dis_stream_sp_ttl">Instructions for downloading and setting up OBS streaming software -</p>
                                                        <ul class="dis_s_sp_ip">
                                                            <li>Click here - <a class="primary_link" href="https://obsproject.com/download" target="_blank"> https://obsproject.com/download </a>to download OBS software.</li>
                                                            <li>Based on your Operating system you can choose the version of OBS software and hit download button.</li>
                                                            <li>Once the software is downloaded, you need to install it.</li>
                                                            <li> After the software is installed successfully, you need to follow the instructions mentioned in the next step.</li>
                                                        </ul>
                                                    </div>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="dis_stream_sp_btwrap m_t_30">
                                        <ul class="dis_stream_sp_btnlist">
                                            <!--li><button type="button" class="dis_btn stream_sp_btn stream_sp_prev_step">Back</button></li-->
                                            <li><!--button type="button" class="dis_btn stream_sp_btn stream_sp_next_step">Continue</button-->
                                        
                                            <a href="javascript:;" class="dis_btn stream_sp_btn CreateChannel">Continue<span class="publish_btn_channel hideme">
													<i class="fa fa-spinner fa-pulse fa-fw"></i>
												</span></a>                                    
                                        </li>
                                                                                                    
                                        </ul>
                                    </div>                                  
                                </div>   
                            </div>
                            <div class="tab-pane" id="step3">
                                <div class="dis_stream_sp_tab_inner">
                                    <div class="dis_upload_video">
                                        <div class="dis_video_heading">
                                            <h3>Set Up Streaming Software</h3>
                                        </div>
                                        <div class="dis_upload_video_inner">                        
                                            <div class="row">
                                                <div class="col-lg-offset-1 col-lg-10">                                                    
                                                    <p class="dis_stream_sp_des m_b_10">After installing OBS software, you need to configure the settings to enable live streaming.</p>
                                                    <!-- <p class="dis_stream_sp_des m_b_30">Watch the video below to see how OBS software needs to be set-up<br> or <a class="primary_link" href="<?= base_url('repo/images/streaming_pdf.pdf'); ?>" target="_blank"> click here </a>to follow the instructions.</p> -->
                                                    <p class="dis_stream_sp_des m_b_30"> <a class="primary_link" href="<?= base_url('repo/images/streaming_pdf.pdf'); ?>" target="_blank"> Click here </a>to follow the instructions on how OBS software needs to be set-up. </p>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-lg-offset-2 col-lg-8">
                                                    <div class="dis_stream_sp_iframe">                                                        
                                                        <video loop="" controls="" width="100%">
                                                            <source type="video/mp4" src="https://s3-cdn.discovered.tv/aud_343/videos/eJ7HqF0xw7HciN7gQVi8.mp4">
                                                        </video>
                                                    </div>
                                                </div> -->
                                                <?php if(is_array($Destinations) && !empty($Destinations)){
														
														foreach($Destinations as $key => $desti){
													
															$Url = stripslashes($desti['Url']);
															$key = $key+1;
															$rtmp_key = 'discovered_live_'.$uid;
															$Url = explode($rtmp_key.'/' , $Url);
															
												?>
														<div class="col-lg-6">
															<div class="dis_su_field m_b_30">
																<label class="dis_su_label">Server</label>
																<div class="dis_su_fieldbtn">
																	
																	<input type="text" class="dis_su_input"  value="<?= $Url[0].$rtmp_key; ?>" id="stream_url<?=$key;?>">
																	<button class="dis_surl_copybtn copytoclipboard" data-target="#stream_url<?=$key;?>">Copy</button>
																</div>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="dis_su_field m_b_30">
																<label class="dis_su_label">Stream Key</label>
																<div class="dis_su_fieldbtn">
																	<input type="password" class="dis_su_input"  value="<?=  $Url[1];?>" id="stream_key<?=$key;?>">
																	<button class="dis_surl_copybtn copytoclipboard" data-target="#stream_key<?=$key;?>">Copy</button>
																</div>
															</div> 
														</div>
												<?php }
                                                }else{ ?>
                                                        <div class="col-lg-6">
                                                                <div class="dis_su_field m_b_30">
                                                                    <label class="dis_su_label">Server</label>
                                                                    <div class="dis_su_fieldbtn">
                                                                        <input type="text" class="dis_su_input"  value="" id="stream_url">
                                                                        <button class="dis_surl_copybtn copytoclipboard" data-target="#stream_url">Copy</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="dis_su_field m_b_30">
                                                                    <label class="dis_su_label">Stream Key</label>
                                                                    <div class="dis_su_fieldbtn">
                                                                        <input type="password" class="dis_su_input"  value="" id="stream_key">
                                                                        <button class="dis_surl_copybtn copytoclipboard" data-target="#stream_key">Copy</button>
                                                                    </div>
                                                                </div> 
                                                            </div>
												<?php } ?>
											</div>
                                        </div>
                                    </div>
                                    <div class="dis_stream_sp_btwrap m_t_30">
                                        <ul class="dis_stream_sp_btnlist">
                                            <li><button type="button" class="dis_btn stream_sp_btn stream_sp_prev_step">Back</button></li>
                                            <!--li><button type="button" class="dis_btn stream_sp_btn GetCurrentStreamInfo">Reset Stream Key</button></li-->
                                            <!--li><button type="button" class="dis_btn stream_sp_btn stream_sp_next_step">Continue</button></li--> 
										<?php
										$href = 'javascript:;';
										
										if($live_pid != 0){
										$post_keys =$this->share_url_encryption->share_single_page_link_creator('2|'.$live_pid,'encode','id');
										$href = base_url('watch?p='.$post_keys[0]);
										}
										?>	
										 <!--li><a href="javascript:;" class="dis_btn stream_sp_btn stream_sp_next_step StartStremingChannel" data-id="<?= $channel_id; ?>" data-state="start" data-inputid="<?= $input_id; ?>" id="LiveKey">Continue</a></li-->   											
										 <!-- <li><a href="<?= base_url('media_stream/mstream'); ?>" class="dis_btn stream_sp_btn stream_sp_next_step">Continue</a></li>  -->
                                         <li><a class="dis_btn stream_sp_btn stream_sp_next_step StartChannel" data-id="<?= $channel_id; ?>" data-state="start" data-inputid="<?= $input_id; ?>" data-href="<?= base_url('media_stream/mstream'); ?>">Continue</a></li>                                      											
                                        </ul>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php } ?>

