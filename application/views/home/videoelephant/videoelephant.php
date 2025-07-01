<style>
.dis_velepnt_modal .modal-body {
    padding: 25px;
}

.dis_video h3 {
    padding-bottom: 40px;
}
.dis_video .dis_btn {
    min-width: 150px;
    margin: 0 10px 10px 10px;
}
.dis_slide_title h3 {
    padding-bottom: 40px;
}

.dis_velepnt_topsec .cmn_upbox_body {
    padding: 30px 30px;
}
.dis_ve_ts_urlbox .dis_field_label {
    font-size: 20px;
    font-weight: 500;
    text-transform: uppercase;
    color: #40404c;
    margin-bottom: 20px;
}
.dis_ve_ss_thumb_overlay {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgb(0 0 0 / 47%);
}
.dis_ve_ss_thumb_loader > i {
    font-size: 40px;
    color: #fd6e38;
}
.dis_ve_ss_thumb_play {
    cursor: pointer;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.dis_ve_ss_thumb_play > img {
    width: 50px;
}
.dis_ve_ss_dleft {
    width: 314px;
}
.dis_ve_ss_thumb {
    position: relative;
}
.dis_ve_ss_thumb > img {
    width: 100%;
    height: 177px;
}
.dis_ve_ss_input_list {
    display: flex;
    flex-wrap: wrap;
    margin: -7px -1%;
}
.dis_ve_ss_input_list > li {
    width: 33.33%;
    padding: 7px 1%;
}
.dis_ve_ss_input_list > li:last-chlid {
    margin-bottom: 0px;
}
.dis_ve_ss_input_list > li.dis_ve_ss_input_list_100 {
    width: 100%;
}
.montiz_details_sn_right {
    width: 7px;
    height: 13px;
    border: 2px solid;
    transform: rotate(
45deg) translate(-2px, -1px);
    border-left: 0;
    border-top: 0;
}
.dis_velepnt_scndpsec .montiz_details_sn {
    position: relative;
}
.montiz_details_sn_right, .active_sn .montiz_details_sn_digit {
    display: none;
}
.active_sn .montiz_details_sn_right {
    display: block;
}
.dis_velepnt_scndpsec .montiz_details_sn.active_sn {
    background-color: rgb(91 175 91);
}
.dis_velepnt_detailsWrap .montiz_details_btn .dis_btn {
    min-width: inherit;
    padding: 0 20px;
}
/* claass added */
.dis_videoelephant_inner {
    width: 100%;
}
.dis_videoelephant_wrap.videoelephant_dataload {
    display: flex;
}
.dis_videoelephant_wrap.videoelephant_dataload {
    height: calc(100vh - 144px);
}
.dis_videoelephant_wrap.videoelephant_dataload .dis_velepnt_detailsWrap {
    display: none;
}
@media (max-width: 767px){
    .dis_ve_ss_dright {
    padding-top: 15px;
}
}
@media (max-width: 480px){
    .dis_ve_ss_input_list > li {
    width: 100%;
    padding: 7px 1%;
}
.dis_ve_ss_dleft {
    max-width: 480px;
    width: 100%;
}
.dis_ve_ss_thumb > img {
    width: 100%;
}
}
@media (min-width: 768px){
.dis_velepnt_scndpsec .cmn_upbox_innerbody {
    padding: 20px 20px;
}
.dis_velepnt_scndpsec .mdetails_data {
    width: calc(100% - 45px);
    margin-left: 15px;
}
.dis_ve_ss_details {
    display: flex;
    width: 100%;
}
.dis_ve_ss_dright {
    width: calc(100% - 330px);
    padding-left: 15px;
}
.dis_ve_ts_urlbox {
    display: flex;
    flex-wrap:wrap;
}
.dis_ve_ts_urlbox_filed .dis_input_filed {
    width: 100%;
}
.dis_ve_ts_urlbox_filed {
    width: calc(100% - 260px);
    margin-right: 10px;
}
}
@media (min-width: 992px){
.dis_velepnt_scndpsec .cmn_upbox_innerbody {
    padding: 40px 30px;
}
}

</style>

<div class="dis_videoelephant_wrap videoelephant_dataload">
    <div class="dis_videoelephant_inner">
        <div class="dis_velepnt_topsec m_b_50">
            <div class="cmn_upbox">
                <div class="cmn_upbox_header text-center">
                    <h4 class="mu_title">Enter MRSS URL</h4>
                </div>
                <div class="cmn_upbox_body">
                    <form class="videoElephant" action="Videoelephant/getMrssVideoElephanta" method="post">
                    <div class="dis_ve_ts_urlbox">
                        <div class="dis_ve_ts_urlbox_filed">
                            <input type="text" class="dis_input_filed require" placeholder="Enter MRSS URL" name="mrss_url" data-valid="url" data-error="Please enter valid url">
                        </div>
                        <div class="dis_ve_ts_urlbox_fbtn">
                            <button type="submit" class="dis_btn">Fetch & Upload Videos</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="dis_velepnt_detailsWrap">
            <form class="submitVideoElephantForm" action="Videoelephant/AddVideosInVideoElephant">
                <div class="dis_velepnt_scndpsec">
                    <div class="cmn_upbox">
                        <div class="cmn_upbox_header text-center">
                            <h4 class="mu_title">videoElephant Video Details</h4>
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
											<select class="primay_select dis_field_input GloabalGenre" name="genre" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}">
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
                </div>
                <div class="montiz_details_btn text-center m_t_30">
                    <!-- <button type="submit" class="dis_btn">Publish Videos To Your Channel<span class="publish_btn hideme">  -->
                    <button type="button" class="dis_btn confirm_slider_popup">Publish Videos To Your Channel<span class="publish_btn hideme"> 
                    <i class="fa fa-spinner fa-pulse fa-fw"></i></span></button>
                </div>
            </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade dis_velepnt_modal" id="velepnt_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
        <div class="modal-body">
            <div class="dis_compensation_ifram ele_ifram">
                 <!-- <iframe id="" src="https://test.discovered.tv/embedcv/2208?autoplay=false&muted=true&control=true" width="100%" height="480px" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe> -->
            </div>
        </div>
      </div>
    </div>
</div>


<div class="modal fade dis_velepnt_modal" id="slider_popup">
    <div class="modal-dialog modal">
      <div class="modal-content">
        <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
        <div class="modal-body dis_video">
            <div class="text-center">
                <h3 >Add these videos to a slider on homepage?</h3>
                <!-- <div class="m_b_50">
                    <input type="text" id="slider_title" class="dis_field_input" placeholder="Enter Slider Title" name="slider_title">
                </div> -->
               <button type="button" class="dis_btn confirm_yes">Yes</button>
               <button type="button" class="dis_btn confirm_no">No</button>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade dis_velepnt_modal" id="slider_title_popup">
    <div class="modal-dialog modal">
      <div class="modal-content">
        <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
        <div class="modal-body dis_slide_title">
            <div class="text-center">
                <h3 >Slider Title</h3>
                <div class="m_b_30">
                    <select class="dis_field_input primay_select getSlidarList" name="slider_mode" id="slider_mode">
                        <option value="">Select Mode</option>
                        <?php foreach($web_mode as $web_modes){ ?>
                            <option value="<?=$web_modes['mode_id']?>"><?=$web_modes['mode']?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="m_b_30">
                    <select class="dis_field_input primay_select slider_id" name="slider_id" id="slider_id">
                        <option value="">Select Slider</option>
                        
                    </select>
                   <!--  <input type="text" id="slider_title" class="dis_field_input" placeholder="Enter Slider Title" name="slider_title"> -->
                </div>
               <button type="button" class="dis_btn confirm_yes_submit">Submit</button>
                
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade dis_velepnt_modal" id="video_skip">
    <div class="modal-dialog modal">
      <div class="modal-content">
        <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
        <div class="modal-body dis_video">
            <div class="text-center">
                <h3 >Are you sure you want to delete this video ?</h3>
               <input type="hidden" name="delete_video_id" id="delete_video_id">
               <input type="hidden" name="delete_video_index" id="delete_video_index">
               <button type="button" class="dis_btn confirm_delete">Yes</button>
               <button type="button" class="dis_btn" data-dismiss="modal">No</button>
            </div>
        </div>
      </div>
    </div>
</div>
