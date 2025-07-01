<style>

.dis_aboutus_sec_one .dis_aboutus_sec_inner {
    padding: 80px 0;
}
.dis_au_s2_img{
    display: inline-block;
}
.dis_bold_ttl {
    font-size: 42px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin: 20px 0 0 0;
    line-height: 1.2;
}
.dis_aboutus_para {
    font-size: 18px;
    font-weight: 700;
    line-height: 28px;
}
.dis_aboutus_sec_one {
    background: url('../repo/images/aboutpage/about_topbg_light.jpg');
    background-size: cover;
}

.dis_aboutus_sec_two {
    padding: 100px 0;
    background-size: cover;
}
.dis_aboutus_sec_three {
    background: url('../repo/images/aboutpage/aboutus_bg3_light.jpg');
    background-size: cover;
    padding: 80px 0;
}

.dis_aboutus_sub_heading {
    font-size: 26px;
     color: rgb(0 0 0);
    font-weight: 700;
    letter-spacing: 1px;
}
.dis_aboutus_s3l_inner .dis_aboutus_sub_heading {
    margin-bottom: 22px;
}
.dis_au_our_vision {
    margin-bottom: 50px;
}
.dis_aboutus_sec_fourfive .dis_aboutus_list > li {
    color: var(--white_color);
}
.dis_aboutus_list > li {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 34px;
    font-family: 'Muli', sans-serif;
    position: relative;
    padding-left: 50px;
    line-height: 30px;
}
.dis_aboutus_list > li:last-child {
    margin-bottom: 0;
}
.dis_aboutus_list > li:after {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    margin: auto;
    left: 0;
    background-image: url(../repo/images/aboutpage/aboutus_bullet.png);
    background-size: 30px 30px;
    height: 30px;
    width: 30px;
}
.dis_aboutus_sec_four {
    padding: 100px 0;
}
.dis_aboutus_s4_box {
    background: var(--sec_bg_color);
    width: 100%;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    flex-direction: column;
}
.dis_aboutus_bgheading {
    margin-bottom: 40px;
    display: inline-block;
}
.dis_aboutus_sec_fourfive .container-fluid, .dis_aboutus_sec_seven .container-fluid{
    max-width: 1170px;
}
.dis_aboutus_sec_five {
    padding-bottom: 125px;
}
.dis_aboutus_sec_six {
    padding: 120px 0 100px;
    text-align: center;
    background: url(../repo/images/aboutpage/compensation_bg_light.jpg);
    background-size: cover;
}
.dis_au_btn_list {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: -5px;
    flex-wrap: wrap;
}
.dis_au_btn_list > li {
    margin: 5px;
}
.dis_aboutus_sec_seven {
    padding: 100px 0 90px;
}
.dis_aboutus_sec_seven .dis_aboutus_para {
    font-size: 28px;
    margin-bottom: 40px;
}
.dis_aboutus_sec_eight {
    background: var(--sec_bg_color);
}
.dis_au_eight_box {
}
.dis_aboutus_sec_nine{
    padding: 90px 0 100px;
}
.dis_aboutus_sec_nine .dis_aboutus_para {
    font-size: 32px;
    line-height: 1.3;
}
.dis_aboutus_ifram {
    margin: 58px 0 60px;
}
.dis_aboutus_ifram video, .dis_aboutus_ifram iframe{
    border: 13px solid var(--main_bg_color);
}
.dis_au_eight_right {
    background: #1f2b32;
    padding: 80px 10px;
}
.dis_au_eight_right:after {
    width: 105px;
    height: 100%;
    background: #1f2b32;
    transform: rotate( 0deg) skew( -12deg , 0deg);
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: -53px;
    content: "";
    z-index: -1;
}
.dis_au_et_img > img, .dis_au_el_img > img {
    margin: auto;
}
.dis_au_et_img {
    margin-bottom: 45px;
    display: block;
}
 .dis_aboutus_wrap .dis_btn {
    padding: 15px 52px;
    min-width: inherit;
    height: inherit;
    line-height: inherit;
}
.dis_aboutus_wrap .dis_btn.gray_btn:hover {
    background: rgb(95 102 106 / 0%);
    border-color: #5f666a;
    color: #5f666a;
}
.dis_cmn_close {
    background: transparent;
    border: none;
    font-size: 30px;
    padding: 0;
    margin: 0;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 1;
    border-radius: 46px;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.dis_creator_compensation_model .modal-body {
    padding: 25px;
}
.dis_creator_compensation_model {
  text-align: center;
  padding: 0!important;
}

.dis_creator_compensation_model:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.dis_creator_compensation_model .modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
.dis_about_download {
    display: flex;
    flex-wrap: wrap;
    margin-top: 20px;
}
.dis_about_download .app_download_icon img {
    max-width: 160px;
}
/* black theme */

.theme_dark .dis_aboutus_sec_one {
    background: url('../repo/images/aboutpage/about_topbg.jpg');
}
.theme_dark .dis_aboutus_sec_three {
    background: url('../repo/images/aboutpage/aboutus_bg3.jpg');
    background-size: cover;
}
.theme_dark .dis_aboutus_sec_six {
    background: url(../repo/images/aboutpage/compensation_bg.jpg);
}
.theme_dark .au_light_img, .theme_dark .au_light_img, .au_dark_img{
    display: none;
}
.theme_dark .au_dark_img{
    display: block;
}
.theme_dark .dis_aboutus_sec_nine{
    background: var(--sec_bg_color);
}
.theme_dark .dis_aboutus_wrap .dis_btn {
    color: #fff;
    background: #EB5821;
    border-color: #EB5821;
}
.theme_dark .dis_aboutus_wrap .dis_btn:hover {
    background: rgb(235 88 33 / 0%);
    color: #EB5821;
}
.theme_dark .dis_aboutus_wrap .dis_btn.gray_btn, .dis_aboutus_wrap .dis_btn.gray_btn {
    background: #5f666a;
    border-color: #5f666a;
}
/* black theme */
@media (min-width: 1200px) and (max-width: 1550px){
.dis_aboutus_wrap .container{
    max-width: 1170px!important;
    width:100%!important;
}
}

@media (min-width: 768px){
    .dis_aboutus_sec_six .dis_aboutus_para {
    font-size: 28px;
    margin-bottom: 27px;
}
}
@media (min-width: 992px){
.dis_au_eight_right {
    padding: 80px 10px;
    position: absolute;
    right: 0;
    z-index: 1;
}
.dis_au_eight_box {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}
.dis_au_eight_left {
    width: 50%;
}
.dis_au_eight_right {
    width: 40%;
}
.dis_au_eight_right {
    padding: 30px 0px;
}
.dis_aboutus_s4_box {
    flex-direction: inherit;
}
.dis_aboutus_s4_col {
    width: 50%;
}
.dis_au_five_img > img {
    float: right;
}
.dis_aboutus_sec_five .dis_aboutus_s4r_inner {
    padding-left: 100px;
}
.dis_aboutus_s7l_inner {
    padding-top: 100px;
}
}
@media (min-width: 1200px){
    .dis_au_eight_left {
    width: 50%;
}
.dis_au_eight_right {
    width: 40%;
}
.dis_au_eight_right {
    padding: 30px 0px;
}

}
@media (min-width: 1400px){
    .dis_au_eight_left {
    width: 50%;
}
.dis_au_eight_right {
    width: 40%;
}
.dis_au_eight_right {
    padding: 80px 0px;
}
}
@media (min-width: 1800px){
    .dis_au_eight_left {
    width: 58%;
}
.dis_au_eight_right {
    width: 42%;
}
.dis_au_eight_right {
    padding: 80px 0px;
}
}
@media (max-width: 1600px){
    .dis_aboutus_sec_three, .theme_dark .dis_aboutus_sec_three {
    background-position: -221px;
}
}
@media (max-width: 1470px){
    .dis_aboutus_sec_three, .theme_dark .dis_aboutus_sec_three {
    background-position: -353px;
}
}
@media (max-width: 1200px){
    .dis_aboutus_sec_three, .theme_dark .dis_aboutus_sec_three {
    background-position: -453px;
}
}
@media (max-width: 1199px){
    .dis_aboutus_sec_three, .theme_dark .dis_aboutus_sec_three {
    background-position: inherit;
}
.dis_aboutus_ifram iframe {
    height: 510px;
}
}

@media (max-width: 991px){
    .dis_aboutus_s2l_inner img, .dis_au_four_img img, .dis_aboutus_s7l_inner img {
    margin: auto;
    margin-bottom: 40px;
}
.dis_au_five_img img{
    margin-top: 40px;
}
.dis_aboutus_s4_box {
    padding: 30px 20px;
}
.dis_aboutus_ifram iframe {
    height: 388px;
}
}
@media (max-width: 576px){
    .dis_aboutus_ifram iframe {
    height: 295px;
}
}




</style>
<div class="dis_aboutus_wrap">
    <div class="dis_aboutus_inner">
        <div class="dis_aboutus_inner">
            <!-- <div class="dis_aboutus_sec dis_aboutus_sec_one">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dis_aboutus_sec_inner text-center">
                                <span class="dis_au_s2_img">
                                    <img src="<?php echo base_url('repo/images/aboutpage/about_logo.png'); ?>" alt="image" class="img-responsive au_dark_img">
                                    <img src="<?php echo base_url('repo/images/aboutpage/about_logo_light.png'); ?>" alt="image" class="img-responsive au_light_img">
                                </span>
                                <p class="dis_bold_ttl">Fair Trade Media</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="dis_aboutus_sec dis_aboutus_sec_two dis_main_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="dis_aboutus_s2l_inner">
                                <span>
                                    <img src="<?php echo base_url('repo/images/aboutpage/what_is_dis.png'); ?>" alt="image" class="img-responsive">
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dis_aboutus_s2r_inner">
                                <p class="dis_aboutus_para">Developed to give content creators a global alternative distribution source and fair revenue share, Discovered is a globally connected digital platform and social network that generates revenue for musicians, filmmakers, gaming streamers, esports teams / esports athletes, and video game developers. The Discovered platform is free for creators and fans and its compensation system is supported by global advertising revenue. </p>
                                <div class="m_t_30 dis_aboutus_btnwrap">
                                    <a href="<?=base_url('sign-up');?>" class="dis_btn" target="_blank">Sign Up Now</a>
                                    <ul class="dis_about_download">
                                        <li>
                                            <a href="https://play.google.com/store/apps/details?id=com.discoveredtv" class="" target="_blank">
                                                <span class="app_download_icon">
                                                    <img src="<?php echo base_url('repo/images/googleplay_white.png'); ?>" class="app_download_light" alt="">
                                                    <img src="<?php echo base_url('repo/images/googleplay_dark.png'); ?>" class="app_download_dark" alt="">
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://apps.apple.com/in/app/discovered/id1560271435" class="" target="_blank">
                                                <span class="app_download_icon">
                                                    <img src="<?php echo base_url('repo/images/app_white.png'); ?>" class="app_download_light" alt="">
                                                    <img src="<?php echo base_url('repo/images/app_dark.png'); ?>" class="app_download_dark" alt="">
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_aboutus_sec_nine text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dis_aboutus_inner">
                                <p class="dis_aboutus_para">Discover Everything</p>
                                <div class="dis_aboutus_ifram">
                                    <!--video loop="" controls="" width="100%">
                                        <source type="video/mp4" src="https://s3-cdn.discovered.tv/aud_398/videos/9PJH5pAUZRtHQsS0Hul7.mp4">
                                    </video-->
									 <iframe src="https://discovered.tv/embedcv/4216?autoplay=false&loop=true" width="100%" height="655px" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>
                                </div>
                                <div class="dis_aboutus_ifram">
                                    <!-- <iframe src="https://discovered.tv/embedcv/1882" width="100%" height="628px" frameborder="0" allowfullscreen="true" ></iframe>  -->
                                </div>
                                <ul class="dis_au_btn_list">
                                    <li><a href="<?=base_url('sign-up');?>" class="dis_btn" target="_blank">Sign Up Now</a></li>
                                    <li><a href="<?=base_url('help');?>" class="dis_btn gray_btn" target="_blank">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_aboutus_sec_seven dis_main_bg">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p class="dis_aboutus_para">Creators The Way You Get Paid On Discovered</p>
                        </div>
                        <div class="col-md-6">
                            <div class="dis_aboutus_s7l_inner">
                                <a href="javascript:;" class="" data-toggle="modal" data-target="#creator_compensation">
                                    <img src="<?php echo base_url('repo/images/aboutpage/aboutus_seven_light.png'); ?>" alt="image" class="img-responsive au_light_img">
                                    <img src="<?php echo base_url('repo/images/aboutpage/aboutus_seven.png'); ?>" alt="image" class="img-responsive au_dark_img">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dis_aboutus_s7r_inner">
                                <ul class="dis_aboutus_list">
                                    <li>1. Sign-up On Discovered On  App Store, Play Store, Tablet, Desktop, Or Mobile Web.</li>
                                    <li>2. Set Up Your Dashboard And Enter Your Bank (ACH) Or Paypal Info.</li>
                                    <li>3. Create Your Content.</li>
                                    <li>4. Upload Your Content To Your Personal Channel (Monetize Video Button)</li>
                                    <li>5. Share From Your Discovered Channel To Your Social Audience Everywhere On Instagram, Facebook, Twitter, Pinterest, Tik Tok, Etc. And Promote It. </li>
                                    <li>6. Video Ads Will Run On Your Content</li>
                                    <li>7. You Follow Your Earnings In Your Dashboard & Get Paid Via Bank Wire Or Paypal.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_aboutus_sec dis_aboutus_sec_three">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="dis_aboutus_s3l_inner">
                                <div class="dis_au_our_vision">
                                    <p class="dis_aboutus_sub_heading">Our Vision</p>
                                    <p class="dis_aboutus_para">Empower “fair trade” for the global creative community. </p>
                                </div>
                                <div class="dis_au_our_mission">
                                    <p class="dis_aboutus_sub_heading">Our Mission</p>
                                    <p class="dis_aboutus_para">Disrupt the revenue share paradigm, by shifting it toward and not away from content creators by offering a 1-stop shop for monetization, social, distribution, merchandising, marketing, and promotional platform-across the entertainment spectrum.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_aboutus_sec_fourfive dis_aboutus_sec_four dis_main_bg">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dis_aboutus_s4_box">
                                <div class="dis_aboutus_s4_col">
                                    <div class="dis_aboutus_s4l_inner">
                                        <span class="dis_au_four_img">
                                            <img src="<?php echo base_url('repo/images/aboutpage/aboutus_audience.png'); ?>" alt="image" class="img-responsive">
                                        </span>
                                    </div>
                                </div>
                                <div class="dis_aboutus_s4_col">
                                    <div class="dis_aboutus_s4r_inner">
                                        <span class="dis_aboutus_bgheading">
                                            <img src="<?php echo base_url('repo/images/aboutpage/heading_oudience.png'); ?>" alt="image" class="img-responsive">
                                        </span>
                                        <ul class="dis_aboutus_list">
                                            <li>Our Creators = 100mm+ Fans, Followers</li>
                                            <li>Thousands Of Creators On Discovered</li>
                                            <li>Desktop, Mobile App, CTV, Console</li>
                                            <li>Live Streaming For Creators</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_aboutus_sec_fourfive dis_aboutus_sec_five dis_main_bg">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dis_aboutus_s4_box">
                                <div class="dis_aboutus_s4_col">
                                    <div class="dis_aboutus_s4r_inner">
                                        <span class="dis_aboutus_bgheading">
                                            <img src="<?php echo base_url('repo/images/aboutpage/heading_principle.png'); ?>" alt="image" class="img-responsive">
                                        </span>
                                        <ul class="dis_aboutus_list">
                                            <!-- <li>No Content Production Costs</li> -->
                                            <li>Social Equity / Fair Trade</li>
                                            <li>Free To Creators</li>
                                            <li>Free To Viewers</li>
                                            <li>Ad Driven</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="dis_aboutus_s4_col">
                                    <div class="dis_aboutus_s4l_inner">
                                        <span class="dis_au_five_img">
                                            <img src="<?php echo base_url('repo/images/aboutpage/aboutus_principle.jpg'); ?>" alt="image" class="img-responsive">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_aboutus_sec_eight">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="dis_au_eight_box">
                                <div class="dis_au_eight_left">
                                    <span class="">
                                        <img src="https://test.discovered.tv/repo/images/aboutpage/game.jpg" alt="image" class="img-responsive">
                                    </span>
                                </div>
                                <div class="dis_au_eight_right">
                                    <span class="dis_au_et_img">
                                        <img src="https://test.discovered.tv/repo/images/aboutpage/heading_distri.png" alt="image" class="img-responsive">
                                    </span>
                                    <span class="dis_au_el_img">
                                        <img src="https://test.discovered.tv/repo/images/aboutpage/logo.png" alt="image" class="img-responsive">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_aboutus_sec_six">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dis_aboutus_inner">
                                <p class="dis_aboutus_para">Discovered Creates Positive Brand Equity For Brands And Agencies</p>
                                <p class="dis_aboutus_para">That Support Thousands Of Emerging Creators On Discovered.</p>
                                <ul class="dis_au_btn_list">
                                    <li><a href="<?=base_url('sign-up');?>" class="dis_btn" target="_blank">Sign Up Now</a></li>
                                    <li><a href="<?=base_url('help');?>" class="dis_btn gray_btn" target="_blank">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade dis_creator_compensation_model" id="creator_compensation" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
        <div class="modal-body">
            <div class="dis_compensation_ifram">
                 <iframe id="creator_poup" src="" width="100%" height="480px" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>
            </div>
        </div>
      </div>
    </div>
</div>
<script>
setTimeout(function(){
	$('#creator_compensation').on('hidden.bs.modal', function () {
	  $('#creator_poup').attr('src','')
	})
	$('#creator_compensation').on('shown.bs.modal', function (e) {
	  $('#creator_poup').attr('src','https://discovered.tv/embedcv/3989?autoplay=true&loop=true')
	})
}, 3000);
</script>