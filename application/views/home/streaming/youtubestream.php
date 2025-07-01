<style>

.dis_Lstrem_wrap {
    font-family: 'Muli';
    max-width: 1300px;
    margin: auto;
    padding: 50px 15px;
}
.dis_Lstrem_ts_video {
    border: 1px solid #e9e9e9;
    background: #f3f3f3;
    margin: 5px;
    height: 315px;
    display: block;
}
.dis_streamStatus_box {
    background: #f7f7f7;
    padding: 5px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #eee;
    margin-bottom: 15px;
    border-radius: 3px;
}
.dis_Ls_ld_list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.dis_streamStatus {
    font-weight: 600;
}

.dis_Lstrem_ldetails {
    padding: 20px 20px;
}
.dis_Ls_ld_lst_ttl {
    font-size: 16px;
    text-transform: capitalize;
    font-weight: 400;
    display: inline-block;
}
.dis_Ls_ld_list > li {
    margin-bottom: 20px;
}
.dis_Lstrem_ts_video iframe {
    height: 100%;
    display: block;
}
.dis_Lstrem_btnLink{
    display: flex;
    justify-content: space-between;
    margin-bottom: 7px;
}
.dis_Lstrem_ts_livebtn, .dis_Lstrem_ts_livebtn:hover, .dis_Lstrem_ts_livebtn:focus{
    color: #eb581f;
    font-size: 16px;
    text-transform: capitalize;
    margin: 10px;
    display: inline-block;
    font-weight: 600;
}
.straming_reloading {
    display: inline-block;
    background: #8f9da5;
    color: #ffff;
    padding: 8px 8px;
    border-radius: 5px;
    display: flex;
    align-items: center;
}
.straming_reloading:hover, .straming_reloading:focus {
    color: #ffff;
}
.dis_Ls_ld_btn_list {
    display: flex;
    flex-wrap: wrap;
}
.dis_Ls_ld_btn_list{
    margin: 0px -5px;
}
.dis_Ls_ld_btn_list > li > a {
    margin: 5px 5px;
}
.dis_Ls_ld_btn_list > li .dis_btn {
    min-width: inherit;
    padding: 0 10px;
    border-radius:5px;
    text-transform: capitalize;
}
.dis_Lstrem_topsec, .dis_Lstrem_secondsec {
    background: #fff;
    box-shadow: 0px 0px 20px 0px rgb(0 0 0 / 9%);
}
.dis-custom-content .dis-custom-result:not(:first-child) {
    display: none;
}
.dis_Ls_ss_tab {
    display: flex;
    flex-wrap: wrap;
    background: rgb(245 245 245);
}
.dis_Ls_ss_tab > li {
    padding: 0px 30px;
}
.dis_Ls_ss_tab > li > a {
    color: #40404c;
    font-weight: 600;
    position: relative;
    display: inline-block;
    padding: 25px 0px;
    text-transform: capitalize;
    letter-spacing: 1px;
    font-size: 18px;
}
.dis_Ls_ss_tab > li.active > a {
    color: rgb(255, 129, 91);
}
.dis_Ls_ss_tab > li > a:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    margin: auto;
    bottom: 0;
    width: 0;
    height: 5px;
    background: rgb(255, 129, 91);
    border-radius: 3px 3px 0px 0px;
    transition: all .3s;
}
.dis_Ls_ss_tab > li.active > a:after {
    width: 100%;
}
.dis_Ls_ss_tab_wrap .dis_Ls_tab_content {
    padding: 30px 30px;
}
textarea.dis_field_input {
    padding: 10px 10px;
    resize: none;
}
.dis_Ls_ss_ti_list .dis_field_wrap {
    display: flex;
}
.dis_filed_btnlist {
    display: flex;
}
.dis_filed_btnlist > li {
    padding-left: 5px;
	min-width:110px;
}
.dis_filed_btnlist .dis_btn {
    border-radius: 5px;
}
.dis_vs_sb_body {
    padding: 20px 20px;
    height: calc(100vh - 144px);
    overflow: auto;
}
.Lstrem_error_wrapper {
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}
.Lstrem_error_inner h2 {
    font-size: 25px;
    font-weight: 600;
}
.streamingLivebtn {
    transition: all 40ms linear;
    box-shadow: 0 0 0 1px #b93802 inset, 0 0 0 2px rgba(255, 255, 255, 0.15) inset, 0 4px 0 0 #AA0000, 0 8px 8px 1px rgba(0, 0, 0, 0.5);
    background-color: #D73814;
    color:#fff;
    padding: 5px 32px 5px 12px;
    font-size: 16px;
    line-height: 1.3333333;
    border-radius: 6px;
    position: relative;
    min-width: 79px;
}
.streamingLivebtn > span {
    vertical-align: middle;
    width: 14px;
    height: 14px;
    border-radius: 100%;
    position: absolute;
    margin: 0 auto;
    border: 1px solid white;
    -webkit-animation: livebtnAni 1.4s infinite ease-in-out;
    animation: livebtnAni 1.4s infinite ease-in-out;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    position: absolute;
    right: 9px;
    bottom: 0;
    top: 0;
    margin: auto;
}
.streamingLivebtn > span:nth-child(1) {
        background-color: rgba(255, 255, 255, 0.3);
    background-color: white;
    -webkit-animation-delay: -0.1s;
    animation-delay: -0.1s;
}
.streamingLivebtn > span:nth-child(2) {
-webkit-animation-delay: 0.16s;
    animation-delay: 0.16s;
}
.streamingLivebtn > span:nth-child(3) {-webkit-animation-delay: 0.42s;animation-delay: 0.42s;border: 1px solid rgba(255, 255, 255, 0.5);}
@keyframes livebtnAni {
  0%, 80%, 100% {
    transform: scale(0.4);
    -webkit-transform: scale(0.4);
  } 40% {
    transform: scale(1.0);
    -webkit-transform: scale(1.0);
  }
}
/* graph css */
.dis_Lsgraph_box .ct-chart.ct-perfect-fourth {
    height: 350px;
}
.dis_Lsgraph_box .ct-chart.ct-perfect-fourth svg {
    height: 100%!important;
    width: 100%!important;
}
.theme_dark .ct-label {
    color: rgb(255 255 255);
}
.theme_dark .ct-grid {
    stroke: rgb(255 255 255 / 34%);
}
/* modal start */
.dis_stream_sp_note{
    font-size: 20px;
    font-weight: 600;
    color: #eb581f;
    margin: 0;
}
.dis_stream_sp_mttl {
    font-size: 20px;
    color: #3f3f59;
    margin: 0;
    font-weight: 700;
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
/* inner modal */
.dis_Lstrem_pp  .dis_video_heading h3 {
    font-size: 14px;
    color: #40404c;
    text-transform: uppercase;
    font-weight: 600;
    padding: 22px 0;
    border-bottom: 1px solid #e3e3e3;
    margin: 0;
}
.dis_Lstrem_pp .dis_Lstrem_pp_body {
    padding: 30px;
}
.dis_Lstrem_pp .dis_s_sp_ip {
    padding-left: 20px;
}
.dis_Lstrem_pp .dis_stream_sp_mttl {
    font-family: var(--muli-font);
}
.dis_Lstrem_pp .modal-body {
    padding: 0;
}
.tab.active {
    display:block;
    /*display: flex;
    flex-wrap: wrap;*/
}

@media (min-width: 768px){
    .dis_Lstrem_topsec {
    display: flex;
    flex-wrap: wrap;
}
.dis_Lstrem_right {
    width: 55%;
}
.dis_Lstrem_left {
    width: 45%;
}
}
</style>
<?php
	$liveData = 0;
	if(isset($medialive[0]['media_info']) && !empty($medialive[0]['media_info']) && $medialive[0]['media_info']){
		$liveData = 1;
		$media_live  	= $medialive[0];
		$media_info 	= json_decode($media_live['media_info'] , true);
    }

	$live_pid  			= ($liveData == 1) ? $media_live['live_pid'] : '';
	$is_live  			= ($liveData == 1) ? $media_live['is_live'] : 0;

    if($is_live == 0){
		redirect(base_url('media_stream'));
	}

	$Destinations 		= isset($media_info['input']['Destinations'])? $media_info['input']['Destinations'] : [] ;
	$input_id 			= isset($media_info['input']['Id'])? $media_info['input']['Id'] : '' ;
	$channel_id 		= isset($media_info['Channel']['Id'])? $media_info['Channel']['Id'] : '' ;

	$accessParam = array(
		'field' => '*',
		'where' => 'post_id='.$live_pid ,
		'limit' => 1,
	);

	$mylive		= 	$this->query_builder->channel_video_list($accessParam);

	$mylive 	= 	isset($mylive['channel'][0])?$mylive['channel'][0]:[];
	$title  	= 	isset($mylive['title'])?$mylive['title']:'NA';
	$categ  	= 	isset($mylive['category_name'])?$mylive['category_name']:'NA';
	$priva  	= 	isset($mylive['privacy_status'])?$mylive['privacy_status']:0;

	$post_key  	= 	isset($mylive['post_key'])?$mylive['post_key']:'';

	$ss 		=	$this->audition_functions->post_status();
	$priva 		= 	isset($ss[$priva])?$ss[$priva]:'NA';

	$hide 		= 	($is_live == 0)?'hide':'';
	$show 		= 	($is_live == 1)?'hide':'';
	$post_key	=	base_url().$this->common->generate_single_content_url_param($post_key , 2);

?>
<div class="dis_simpleLoader hide">
	<div class="dis_simpleLoader_inner">
	</div>
	<p class="dis_streamNot">Creating live stream, this may take a while</p>
</div>

<input type="hidden" value="<?=$is_live;?>" id="is_stream_live">
<div class="dis_Lstrem_wrap full_vh_foooter">
	<div class="alert alert-danger alert-dismissible streamNote <?=$hide;?>" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<strong>Warning!</strong>
		Please do not refresh browser during live streaming, otherwise live streaming will stop.
	</div>

    <div class="dis_Lstrem_topsec m_b_50">
        <div class="dis_Lstrem_left">
            <div class="dis_Lstrem_ts_video">
                <iframe class="<?=$hide;?>"  id="livestream" src="<?= base_url();?>embedcv/<?= $live_pid; ?>?autoplay=true&muted=true&control=true&loop=true&livestream=1" width="100%" frameborder="0"></iframe>
                <div class="Lstrem_error_wrapper <?=$show;?>">
                    <div class="Lstrem_error_inner">
                        <span class="Lstrem_error_icon">
                        <i class="fa fa-refresh fa-spin" style="font-size:48px;"></i>
                        </span>
                        <h2>Connect streaming software to go live</h2>
                        <p class="m_b_20">Once you create video stream, you will be able to go live</p>
                        <a href="#" class="dis_btn h_40 min_width_inherit " data-toggle="modal" data-target="#live_modal">Stream Setup Help</a>
                    </div>
                </div>
            </div>
            <div class="dis_Lstrem_btnLink">
            <a target="_blank" href="<?= $post_key; ?>" class="text-center dis_Lstrem_ts_livebtn <?=$hide;?>">Click Here To Watch Your Stream</a>
            <a href="javascript:;" class="straming_reloading" onclick="$('#livestream').attr('src',$('#livestream').attr('src'))">Reload Streaming</a>
            </div>
        </div>
        <div class="dis_Lstrem_right">
            <div class="dis_Lstrem_ldetails">
                <div class="alert alert-warning" role="alert">
                    <strong> Note! </strong>  You need to start streaming from OBS Software once the Channel Status mentioned below is "RUNNING". If the Channel status is "IDLE" you can click the "Start Channel" button to change the status to "RUNNING" and start your stream.
                </div>
                <div class="dis_streamStatus_box">
                    <p class="dis_streamStatus mp_0">Channel Status - <strong></strong></p>
                    <!-- <p class="dis_streamStatus mp_0">Live Status - <strong> </strong></p> -->
                    <p class="dis_streamStatus mp_0">Status - <strong class="streamingLivebtn" style="display:none;">-</strong></p>
                    <div class="dis_Ls_ld_btn">
                        <ul class="dis_Ls_ld_btn_list">
                            <li>
                                <a href="javascript:;" class="dis_btn StartStremingChannel h_40 min_width_inherit<?=$hide;?>" data-pid="<?= $live_pid; ?>" data-id="<?= $channel_id; ?>" data-state="start" data-inputid="<?= $input_id; ?>">Start Channel</a>
                            </li>
                            <li>
                                <a href="<?= base_url('media_stream'); ?>" class="dis_btn crt_vidoestrm_btn <?=$show;?>" >Create Video/Stream </a>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="dis_Ls_ld_details">
                    <ul class="dis_Ls_ld_list">
                        <li>
                            <label class="dis_Ls_ld_lst_lbl">Title -</label>
                            <h2 class="dis_Ls_ld_lst_ttl mp_0" id="liveTitle"><?= $title; ?></h2>
                        </li>
                        <li>
                            <label class="dis_Ls_ld_lst_lbl">Category -</label>
                            <h2 class="dis_Ls_ld_lst_ttl mp_0"><?= $categ; ?></h2>
                        </li>
                        <li>
                            <label class="dis_Ls_ld_lst_lbl">Privacy -</label>
                            <h2 class="dis_Ls_ld_lst_ttl mp_0"><?= $priva; ?></h2>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="dis_Lstrem_secondsec">
        <div class="dis_Ls_ss_inner">
            <div class="dis_Ls_ss_tab_wrap">
                <ul class="dis_Ls_ss_tab dis-custom-tab">
                    <li class="dis-custom-tab-list active"><a data-href="#stream_setting" href="#stream_setting" class="dis-custom-tab-link">Stream settings</a></li>
                    <li class="dis-custom-tab-list"><a class="dis-custom-tab-link showChartTab" data-href="#analiytics" href="#analiytics">Analytics</a></li>
                </ul>
                <div class="dis-custom-content dis_Ls_tab_content">
                    <div id="stream_setting" class="dis_Ls_tab_result dis-custom-result">
                        <div class="dis_Ls_ss_tab_inner">
                            <?php if(is_array($Destinations)){

                            foreach($Destinations as $key => $desti){

                            $Url = stripslashes($desti['Url']);
                            $key = $key+1;
                            $rtmp_key = 'discovered_live_'.$uid;
                            $Url = explode($rtmp_key.'/' , $Url);
                            // print_r($Url);die;
                            ?>
                            <div class="dis_Ls_ss_ti_list">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="m_b_20">
                                            <label class="dis_field_label">stream Url</label>
                                            <div class="dis_field_wrap">
                                                <input type="text" class="dis_field_input"  value="<?= $Url[0].$rtmp_key; ?>" id="stream_url<?=$key;?>">
                                                <ul class="dis_filed_btnlist">
                                                    <li><button class="dis_btn min_width_inherit copytoclipboard" data-target="#stream_url<?=$key;?>">Copy</button></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="m_b_20">
                                            <label class="dis_field_label">stream Key</label>
                                            <div class="dis_field_wrap">
                                                <input type="text" class="dis_field_input"  value="<?=  $Url[1];?>" id="stream_key<?=$key;?>">
                                                <ul class="dis_filed_btnlist">
                                                    <li><button class="dis_btn min_width_inherit copytoclipboard" data-target="#stream_key<?=$key;?>">Copy</button></li>
                                                    <li><button class="dis_btn min_width_inherit ResetInputKey">reset<span class="hide"><i class="fa fa-spinner fa-pulse fa-fw"></i></span></button></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }} ?>
                        </div>
                    </div>
                    <div id="analiytics" class="dis_Ls_tab_result dis-custom-result">
                        <div class="dis_Ls_ss_tab_inner">
                            <div class="dis_Lsgraph_box" >
                                <div class="ct-chart ct-perfect-fourth"></div>
                                <h4 class="">Frame Health Rate</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dis_vSrm_sb_wrap {
    font-family: 'Muli';
}
.open_stream_sb .dis_vSrm_sb_wrap {
    right: 0;
}
.dis_vSrm_sb_wrap {
    position: fixed;
    right: -800px;
    top: 0;
    width: 800px;
    z-index: 99999;
    background: rgb(255 255 255);
    height: 100%;
    box-shadow: 0px 0px 20px 0px rgb(0 0 0 / 8%);
    transition: all .3s;
}
.dis_vs_sb_header {
    position: relative;
}
.dis_vs_sb_header_ttl {
    font-size: 14px;
    color: #40404c;
    text-transform: uppercase;
    font-weight: 600;
    padding: 22px 0;
    border-bottom: 1px solid #e3e3e3;
    margin: 0;
    text-align: center;
}
.dis_vs_sb_body {
    padding: 20px 20px;
}
.dis_vs_sb_input_list {
    display: flex;
    flex-wrap: wrap;
}
.dis_vs_sb_input_list > li {
    width: 50%;
    padding: 5px 10px;
}
.dis_field_box {
    margin-bottom: 10px;
}
.dis_vs_sb_ut_lbl {
    display: flex;
    border: 1px solid #efecf8;
    border-radius: 6px;
    background: #fdfcff;
    padding: 15px 15px;
    cursor: pointer;
    margin-bottom: 0;
}
.dis_vs_sb_ut_icon {
    background: #e3e3e3;
    width: 370px;
    min-height: 190px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.dis_vs_sb_footer {
    padding: 20px 20px;
    border-top: 1px solid #e3e3e3;
    text-align: right;
}
.dis_vs_sb_close {
    position: absolute;
    top: 50%;
    left: 10px;
    bottom: 0;
    margin: auto;
    font-size: 30px;
    transform: translateY(-50%);
    cursor: pointer;
}
.daterangepicker {
    z-index: 99999;
}
</style>
<div class="dis_vSrm_sb_wrap">
 <form class="SubmitStream" action="Media_stream/submitStream">
    <div class="dis_vs_sb_inner">
        <div class="dis_vs_sb_header">
            <h3 class="dis_vs_sb_header_ttl">Video Details</h3>
            <span class="dis_vs_sb_close">×</span>
        </div>
        <div class="dis_vs_sb_body">
            <div class="dis_sd_upload_thumb">
                <div class="dis_vs_sb_ut_box">
                    <input type="file"  id="Stream_thumb" data-id="#StreamThumb" name="userfile" class="cmn_inputfile dis_vs_sb_ut_input previewFile" accept="image/png, image/jpg, image/jpeg">

                    <label class="dis_vs_sb_ut_icon" for="Stream_thumb" id="StreamThumb"  style="background-repeat: no-repeat;background-size:cover">
                        <span>
                            <svg xmlns="https://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 45 35"><path class="cls-1" fill="#777" fill-rule="evenodd" d="M1348.68,1216.23a12.509,12.509,0,0,0-12.59-12.23,12.654,12.654,0,0,0-8.3,3.09,12.323,12.323,0,0,0-4,6.76h-0.13a10.506,10.506,0,1,0,0,21.01h7.45a0.945,0.945,0,1,0,0-1.89h-7.45a8.616,8.616,0,1,1,0-17.23c0.26,0,.53.02,0.84,0.04a0.954,0.954,0,0,0,1.04-.81,10.4,10.4,0,0,1,3.52-6.46,10.691,10.691,0,0,1,17.7,7.89c0,0.21-.01.42-0.03,0.65l-0.01.1a0.931,0.931,0,0,0,.29.74,0.979,0.979,0,0,0,.77.27,6.45,6.45,0,0,1,.76-0.04,7.426,7.426,0,1,1,0,14.85h-7.83a0.945,0.945,0,1,0,0,1.89h7.83A9.316,9.316,0,1,0,1348.68,1216.23Zm-12.59-7.79a8.068,8.068,0,0,0-7.99,6.87,0.956,0.956,0,0,0,.82,1.07,0.66,0.66,0,0,0,.14.01,0.949,0.949,0,0,0,.94-0.82,6.15,6.15,0,0,1,6.09-5.24A0.945,0.945,0,1,0,1336.09,1208.44Zm4.37,18.61-3.49-3.08a1.6,1.6,0,0,0-2.11,0l-3.5,3.08a0.928,0.928,0,0,0-.07,1.33,0.971,0.971,0,0,0,1.35.08l2.31-2.04v11.63a0.96,0.96,0,0,0,1.92,0v-11.63l2.31,2.04a0.959,0.959,0,0,0,1.35-.08A0.928,0.928,0,0,0,1340.46,1227.05Z" transform="translate(-1313 -1204)"></path></svg>
                        </span>
                    </label>
                </div>
                <h2 class="dis_sd_ut_ttl">Upload Custom Thumbnail</h2>
            </div>
            <!-- <div class="dis_sd_upload_thumb">
                <div class="dis_vs_sb_ut_box">
                    <input type="file" class="cmn_inputfile dis_vs_sb_ut_input" id="dis_vs_sb_ut_thumb">
                    <label class="dis_vs_sb_ut_lbl" for="myfile">
                        <span class="dis_vs_sb_ut_icon">
                            <svg xmlns="https://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 45 35"><path class="cls-1" fill="#777" fill-rule="evenodd" d="M1348.68,1216.23a12.509,12.509,0,0,0-12.59-12.23,12.654,12.654,0,0,0-8.3,3.09,12.323,12.323,0,0,0-4,6.76h-0.13a10.506,10.506,0,1,0,0,21.01h7.45a0.945,0.945,0,1,0,0-1.89h-7.45a8.616,8.616,0,1,1,0-17.23c0.26,0,.53.02,0.84,0.04a0.954,0.954,0,0,0,1.04-.81,10.4,10.4,0,0,1,3.52-6.46,10.691,10.691,0,0,1,17.7,7.89c0,0.21-.01.42-0.03,0.65l-0.01.1a0.931,0.931,0,0,0,.29.74,0.979,0.979,0,0,0,.77.27,6.45,6.45,0,0,1,.76-0.04,7.426,7.426,0,1,1,0,14.85h-7.83a0.945,0.945,0,1,0,0,1.89h7.83A9.316,9.316,0,1,0,1348.68,1216.23Zm-12.59-7.79a8.068,8.068,0,0,0-7.99,6.87,0.956,0.956,0,0,0,.82,1.07,0.66,0.66,0,0,0,.14.01,0.949,0.949,0,0,0,.94-0.82,6.15,6.15,0,0,1,6.09-5.24A0.945,0.945,0,1,0,1336.09,1208.44Zm4.37,18.61-3.49-3.08a1.6,1.6,0,0,0-2.11,0l-3.5,3.08a0.928,0.928,0,0,0-.07,1.33,0.971,0.971,0,0,0,1.35.08l2.31-2.04v11.63a0.96,0.96,0,0,0,1.92,0v-11.63l2.31,2.04a0.959,0.959,0,0,0,1.35-.08A0.928,0.928,0,0,0,1340.46,1227.05Z" transform="translate(-1313 -1204)"></path></svg>
                        </span>
                        <div class="dis_vs_sb_ut_info">
                            <p>Upload Custom Thumbnail</p>
                            <span class="dragdrop_sprt">Supports: JPG, PNG </span>
                        </div>
                    </label>
                </div>
                <h2 class="dis_sd_ut_ttl">Upload Custom Thumbnail</h2>
            </div> -->
            <ul class="dis_vs_sb_input_list">
                <li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">Select Mode</label>
                        <div class="dis_field_wrap">
                            <select class="dis_field_input require"  name="mode" id="mode">
								<option value="">Select Mode</option>
								<?php
								foreach($website_mode as $mode){
									if($mode["mode_id"] != 8)
									echo '<option value="'.$mode["mode_id"].'">'.ucfirst($mode["mode"]).'</option>';
								}
								?>
							</select>
                        </div>

                    </div>
                </li>
                <li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">Select Genre</label>
                        <div class="dis_field_wrap">
                           <select class="dis_field_input require" name="genre" id="genre">
								<option value="">Select Genre</option>
							</select>
                        </div>
                    </div>
                </li>
				<li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">Select Sub Genre</label>
                        <div class="dis_field_wrap">
                           <select class="dis_field_input" name="sub_genre" id="sub_genre">
								<option value="">Select Sub Genre</option>
							</select>

                        </div>
                    </div>
                </li>
                <li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">Select Category (optional)</label>
                        <div class="dis_field_wrap">
                           <select class="dis_field_input" name="category" >
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
                    </div>
                </li>
                <li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">video title</label>
                        <div class="dis_field_wrap">
                           <input type="text"  class="dis_field_input require" placeholder="Video Title" name="title" maxlength="100" >
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">Select Video Language</label>
                        <div class="dis_field_wrap">
                           <select class="dis_field_input require"  name="language">
								<option value="">Select Video Language</option>
								<?php
									foreach($language_list as $list){
										$sel = ($list['id']=='en_US')?'selected="selected"':'';
										echo '<option '.$sel.' value="'.$list['id'].'">'.$list['value'].'</option>';
									}

								?>
							</select>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">Age Restrictions(in year)</label>
                        <div class="dis_field_wrap">
                            <?php $ages = $this->audition_functions->age(); ?>
							<select class="dis_field_input require" name="age_restr" >
								<option value="">Age Restrictions(in year)</option>
									<?php
										foreach($ages as $age){
											$sel = ($age == 'Unrestricted')?'selected="selected"':'';
											echo '<option '.$sel.' value="'.$age.'">'.$age.'</option>';
										}

									?>
							 </select>
                        </div>
                    </div>
                </li>
				 <li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">Select Privacy</label>
                        <div class="dis_field_wrap">
                            <select class="dis_field_input require" name="privacy_status" >
								<?php
										echo '<option value="">Select Privacy</option>';
										foreach($ss as $k=>$v){
											$sel = ($k == 7)?'selected="selected"':'';
											echo '<option '.$sel.' value="'.$k.'">'.$v.'</option>';
										}
									?>
							</select>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dis_field_box">
                        <label class="dis_field_label">video descreption</label>
                        <div class="dis_field_wrap">
                            <textarea class="dis_field_input require" placeholder="Video Description" name="description" ></textarea>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="">
                        <ul class="dis_surl_list m_b_10">
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
                        <div class="dis_field_box m_b_30 text-left dateArea" style="display: none;">
                            <label class="dis_field_label">Select Date Time </label>
                            <div class="dis_field_wrap">
                                <input readonly type="text" class="streamdatepicker dis_field_input" name="scheduled_time">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="">
                        <ul class="dis_surl_list">
                            <li>
                                <div class="dis_surl_list_ttlicon">
                                    <span class="dis_surl_list_icon">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"> <g> <g> <path d="M304,96H112c-8.832,0-16,7.168-16,16c0,8.832,7.168,16,16,16h192c8.832,0,16-7.168,16-16C320,103.168,312.832,96,304,96z"></path> </g> </g> <g> <g> <path d="M240,160H112c-8.832,0-16,7.168-16,16c0,8.832,7.168,16,16,16h128c8.832,0,16-7.168,16-16 C256,167.168,248.832,160,240,160z"></path> </g> </g> <g> <g> <path d="M352,0H64C28.704,0,0,28.704,0,64v320c0,6.208,3.584,11.872,9.216,14.496C11.36,399.488,13.696,400,16,400 c3.68,0,7.328-1.28,10.24-3.712L117.792,320H352c35.296,0,64-28.704,64-64V64C416,28.704,387.296,0,352,0z M384,256 c0,17.632-14.336,32-32,32H112c-3.744,0-7.36,1.312-10.24,3.712L32,349.856V64c0-17.632,14.336-32,32-32h288 c17.664,0,32,14.368,32,32V256z"></path> </g> </g> <g> <g> <path d="M448,128c-8.832,0-16,7.168-16,16c0,8.832,7.168,16,16,16c17.664,0,32,14.368,32,32v270.688l-54.016-43.2 c-2.816-2.24-6.368-3.488-9.984-3.488H192c-17.664,0-32-14.368-32-32v-16c0-8.832-7.168-16-16-16c-8.832,0-16,7.168-16,16v16 c0,35.296,28.704,64,64,64h218.368l75.616,60.512C488.896,510.816,492.448,512,496,512c2.336,0,4.704-0.512,6.944-1.568 C508.48,507.744,512,502.144,512,496V192C512,156.704,483.296,128,448,128z"></path> </g> </g> </svg>
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
                </li>
            </ul>
        </div>
        <div class="dis_vs_sb_footer">
            <ul class="dis_vs_sb_ftr_list">
                <li>
                    <button type="submit" class="sumbmitBtn dis_btn">Create
                        <span class="publish_btn hideme">
                        <i class="fa fa-spinner fa-pulse fa-fw"></i>
                        </span>
                </button>
                </li>
            </ul>
        </div>
    </div>
	</form>
</div>
<!-- modal start -->
<div class="modal dis_Lstrem_pp dis_center_modal fade" id="live_modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
        <div class="modal-body">
            <div class="dis_Lstrem_pp_inner">
                <div class="dis_video_heading text-center">
                    <h3>Download Streaming Software</h3>
                </div>
                <div class="dis_Lstrem_pp_body">
                    <p class="dis_stream_sp_note">*Ignore this if you have already installed the streaming software.</p>
                    <h2 class="dis_stream_sp_mttl p_b_30 p_t_30">You need to download the streaming software to share your screen, capture live video from camera.</h2>
                    <p class="dis_stream_sp_ttl">Instructions for downloading and setting up streaming software -</p>
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
</div>