<style>
    .dis_sinfo_des {
    font-family: 'Muli', sans-serif;
    font-size: 18px;
    font-weight: 600;
    color: #222;
    margin: 0;
    padding: 20px 0 20px;
}
.dis_s_sp_ip {
    list-style-type: decimal;
    font-family: 'Muli', sans-serif;
}
.dis_s_sp_ip li {
    color: #222;
    font-weight: 600;
    padding-bottom: 10px;
}
</style>
<?php

$id 			= isset($ivs_info[0]['id'])?$ivs_info[0]['id']:'';
$user_id 		= isset($ivs_info[0]['user_id'])?$ivs_info[0]['user_id']:'';
$live_pid 		= isset($ivs_info[0]['live_pid'])?$ivs_info[0]['live_pid']:'';
$channel_arn 	= isset($ivs_info[0]['ivs_info'])?json_decode($ivs_info[0]['ivs_info'],true):[];
$status 		= (int) isset($ivs_info[0]['status'])?$ivs_info[0]['status']: 3 ;
$is_live 		= isset($ivs_info[0]['is_live'])?$ivs_info[0]['is_live']: 0 ;

$stream_url 	= isset($channel_arn['channel']['ingestEndpoint'])?$channel_arn['channel']['ingestEndpoint']:'';
$stream_key 	= isset($channel_arn['streamKey']['value'])?$channel_arn['streamKey']['value']:'';
?>

<?php 

if($status == 0 || $status == 2 || $status == 3){ ?> <!--When status is disable or requested -->
<div class="dis_steaming_info_wrap ">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="dis_sinfo_inner">
                    <div class="dis_sinfo_box">
                        <h2 class="dis_sinfo_ttl">Discovered Live Streaming</h2>
                        <p class="dis_sinfo_des">Here are the steps & information needed for streaming on discovered -</p>
                        <ul class="dis_s_sp_ip">
                            <li>You need to be approved for streaming.</li>
                            <li>Upon approval you can stream immediately or schedule it for a later date.</li>
                            <li>You would need to provide basic video/stream details.</li>
                            <li>Once the streaming ends, the video is automatically uploaded in your channel and will continue to be monetized.</li>
                            <li>You can choose not to monetize by setting the video to offline/private mode from dashboard.</li>
                            <li>You need to have a high speed internet connection and a high resolution camera attached to your computer.</li>
                        </ul>
                        <div class="dis_sinfo_btn m_t_30">
                            <a href="javascript:;" class="dis_btn <?= ($status == 3)? 'RequestToLiveStream' : '' ?>" >
							<?= ($status == 2)? 'Requested' : ( ($status == 0) ? 'Rejected' : 'Request' ) ; ?> To Live Stream</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }else{ ?>

<?php if($is_live  == 0){ ?>
<div class="dis_steaming_dtls_wrap">
	<div class="container">
		<!-- <div class="row">
			<div class="col-md-12">
				<div class="dis_steaming_dtls_inner">
					<div class="dis_steaming_dtls_box">
						<div class="dis_sd_header text-center">
							<h4 class="mu_title">Live Streaming Details</h4>
						</div>
						<div class="dis_sd_body">
							
					    </div>
					</div>
				</div>
			</div>
		</div> -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="dis_upload_video">
                    <div class="dis_video_heading">
                        <h3>Stream Info</h3>
                    </div>
                    <div class="dis_upload_video_inner">                        
                        <div class="row">
                            <div class="dis_upload_form dis_signup_form">
                                <form class="SubmitStream" action="Streaming/submitStream">
                                    <div class="col-md-4">
                                        <div class="dis_sd_upload_thumb">
                                            <div class="dis_sd_ut_box">
                                                <input type="file" id="Stream_thumb" name="userfile" class="cmn_inputfile previewFile" data-id="#StreamThumb">												
                                                <label class="Stream_thumb_icon" for="Stream_thumb" id="StreamThumb" style="  background-repeat: no-repeat;background-size:cover">
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
                                        </div>
                                        <div class="col-sm-6">
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
                                                    <div class="input-group">
														<textarea class="form-control dis_signup_input" placeholder="Video Description" name="editor" ></textarea>
													</div>
                                                </div>
                                                <span  class="form-error help-block"></span>
                                            </div>
                                        </div>                                        
                                    </div> 
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
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
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
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
                                    <!-- <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <select class="form-control dis_signup_input required" >
                                                    <option value="">Share this video to social page as well ?</option>                                                        
                                                </select>
                                            </div>
                                            <span class="form-error help-block"></span>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
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
                                    <div class="col-lg-12">
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

                                    
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="dis_button_div">
                                            <button type="submit" class="dis_btn sumbmitBtn" >Submit<span class="publish_btn hideme"> 
                                            <i class="fa fa-spinner fa-pulse fa-fw"></i></span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
	</div>
</div>

<?php }else{ ?>
<div class="dis_steaming_url_wrap">
    <div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="dis_steaming_url_inner">
					<div class="dis_surl_box">
						<div class="dis_surl_box_header text-center">
							<h4 class="dis_surl_title">Live Streaming Details</h4>
						</div>
						<div class="dis_surl_body">
                            <div class="dis_surl_body_inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="dis_su_field m_b_30">
                                            <label class="dis_su_label">Stream Url</label>
                                            <div class="dis_su_fieldbtn">
                                            
											   <input type="text" class="dis_su_input"  placeholder="" value="rtmps://<?= $stream_url;?>:443/app/" id="stream_url">
                                                <button class="dis_surl_copybtn copytoclipboard" data-target="#stream_url">Copy</button>
                                            </div>
                                        </div>
                                        <div class="dis_su_field m_b_30">
                                            <label class="dis_su_label">Stream Key</label>
                                            <div class="dis_su_fieldbtn">
                                                <input type="text" class="dis_su_input"  placeholder="" value="<?= $stream_key;?>" id="stream_key">
                                                <button class="dis_surl_copybtn copytoclipboard" data-target="#stream_key">Copy</button>
                                            </div>
                                        </div>    
										<?php 
										
										$post_keys = $this->share_url_encryption->share_single_page_link_creator('2|'.$live_pid,'encode','id');
										
										?>		
                                        <div class="dis_surl_btn">
                                            <a target="_blank" href="<?= base_url('watch?p='.$post_keys[0]); ?>" class="dis_btn">Go To Live Stream</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
					    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php } ?>

<?php } ?>