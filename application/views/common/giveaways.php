<style>
/* giveaway page */
.dis_gaway_tablist {
    justify-content: center;
    list-style: none;
    align-items: center;
    background: #fff;
    box-shadow: 0px 0px 30px 0px rgb(0 0 0 / 10%);
    padding: 0;
    margin: 0;
    display: inline-flex;
    margin-bottom: 50px;
    border-radius: 10px;
}
.dis_gaway_tablist > li> a {
    color: #484848;
    display: inline-block;
    padding: 15px 15px;
    min-width: 140px;
    font-family: 'Muli';
    font-weight: 600;
    font-size: 18px;
    border-radius: 8px;
}
.dis_gaway_tablist > li.active > a {
    background: #eb581f;
    color: #fff;

}
.dis_gaway_signup .dis_checkbox {
    /* margin-top: -25px;
    margin-bottom: 32px;
    text-align: left; */
    margin-top: -12px;
    margin-bottom: 32px;
    text-align: left;
}
.dis_gaway_signup_tab2 .form-group .help-block {
    position: absolute;
    bottom: -26px;
}
.theme_dark .dis_gaway_tablist {
    box-shadow: 0px 0px 30px 0px rgb(0 0 0 / 38%);
    border: 1px solid rgb(46 61 69);
}
.theme_dark .dis_gaway_signup_box, .theme_dark .dis_gaway_tablist {
    background: var(--sec_bg_color);
}
.theme_dark .dis_gaway_social_list > li > a > i {
    border-color: #172025;
}
.theme_dark .dis_gaway_counter {
    background: var(--sec_bg_color);
    box-shadow: 0px 0px 30px 0px rgb(0 0 0 / 32%);
}
.theme_dark .dis_gaway_counter_list > li > span, .theme_dark .dis_gaway_tablist > li> a {
    color: var(--white_color);
}
@media (min-width: 420px) {
    .dis_gaway_counter_list > li {
    padding: 5px 10px;
    font-size: 22px;
}
.dis_gaway_or:before, .dis_gaway_or:after {
    width: 100px;
}
.dis_gaway_sl_text {
    padding: 0 20px;
}
.dis_gaway_social_list > li > a > i {
    width: 50px;
    font-size: 20px;
}
}
@media (min-width: 576px) {
    .dis_gaway_counter_list > li {
    padding: 10px 25px;
    font-size: 25px;
}
}
@media (min-width: 768px){
    .dis_gaway_counter {
    padding: 40px 40px;
}
.dis_gaway_counter_list > li {
    padding: 10px 40px;
}
.dis_gaway_signup_box {
    padding: 40px 40px;
}
.dis_gaway_or:before, .dis_gaway_or:after {
    width: 200px;
}
}
/* giveaway page */
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
.theme_dark .dis_aboutus_sec_one {
    background: url('../repo/images/aboutpage/about_topbg.jpg');
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
.theme_dark .dis_aboutus_sec_three {
    background: url('../repo/images/aboutpage/aboutus_bg3.jpg');
}
.dis_aboutus_sub_heading {
    font-size: 26px;
     color: rgb(0 0 0);
    font-weight: 700;
    letter-spacing: 1px;
    line-height: 1.3;
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
.theme_dark .dis_aboutus_sec_six {
    background: url(../repo/images/aboutpage/compensation_bg.jpg);
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
.theme_dark .au_light_img, .theme_dark .au_light_img, .au_dark_img{
    display: none;
}
.theme_dark .au_dark_img{
    display: block;
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
.theme_dark .dis_aboutus_sec_nine{
    background: var(--sec_bg_color);
}
.dis_aboutus_sec_nine .dis_aboutus_para{
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
.theme_dark .dis_aboutus_wrap .dis_btn {
    color: var(--white_color);
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
    .dis_aboutus_sec_three {
    background-position: -221px;
}
}
@media (max-width: 1470px){
    .dis_aboutus_sec_three {
    background-position: -353px;
}
}
@media (max-width: 1200px){
    .dis_aboutus_sec_three {
    background-position: -453px;
}
}
@media (max-width: 1199px){
    .dis_aboutus_sec_three {
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
            <div class="dis_aboutus_sec dis_g_aboutus_sec dis_aboutus_sec_two dis_main_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="dis_aboutus_s2l_inner">
                                <span>
                                    <img src="<?php echo base_url('repo/images/giveaway/giveaway.png'); ?>" alt="image" class="img-responsive">
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dis_aboutus_s2r_inner">
                                <p class="dis_aboutus_sub_heading m_b_30">About Discovered Giveaways</p>
                                <p class="dis_aboutus_para">Discovered Giveaways is the #1 place to enter exclusive giveaways curated by the the Discovered team.</p>

                                <p class="dis_aboutus_para">Whether you’re looking for a chance to win a game console or other amazing gifts, check out the Discovered Giveaways channel as often as you can.
                                </p>
                                <div class="m_t_30 dis_aboutus_btnwrap">
                                    <a href="https://discovered.tv/channel?user=dtvgiveaways" class="dis_btn" target="_blank">Visit Discovered Giveaways </a>
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
                                <p class="dis_aboutus_para">HOW TO ENTER</p>
                                <div class="dis_aboutus_ifram">
                                    <iframe src="https://discovered.tv/embedcv/4700?controls=true&autoplay=false&muted=false&loop=true" width="100%" height="655px" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>
                                </div>
                                <ul class="dis_au_btn_list">
                                    <li><a href="#dis_gaway_nsletter" class="dis_btn">Enter Giveaway</a></li>
                                    <!-- <li><a href="<?=base_url('help');?>" class="dis_btn gray_btn" target="_blank">Contact Us</a></li>    -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_gaway_price text-center dis_main_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dis_aboutus_inner">
                                <p class="dis_aboutus_sub_heading heading m_b_20">PERFECT 10 DISCOVERED GIVEAWAY</p>
                                <p class="dis_aboutus_sub_heading m_b_30">Enter to win: September 1, 2021 thru December 31, 2022</p>
                                <p class="dis_aboutus_sub_heading m_b_50">Grand Prize Winner Receives  </p>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-1 col-lg-10">
                            <div class="col-md-6">
                                <div class="dis_aboutus_inner">
                                    <span class="dis_gaway_left_img">
                                        <img src="https://test.discovered.tv/repo/images/giveaway/d_play_station.png" alt="image" class="img-responsive">
                                    </span>
                                    <p class="dis_aboutus_sub_heading">Playstation 5</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dis_aboutus_inner">
                                    <span class="dis_gaway_right_img">
                                        <img src="https://test.discovered.tv/repo/images/giveaway/d_grand_theft.png" alt="image" class="img-responsive">
                                    </span>
                                    <p class="dis_aboutus_sub_heading">Grand Theft Auto 5</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="dis_aboutus_sec_six dis_gaway_entries text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dis_aboutus_inner">
                                <p class="dis_aboutus_sub_heading  heading m_b_50">Total Entries - <?= $giveawaysCount; ?>0</p>
                            </div>
                            <div class="dis_aboutus_inner">
                                <div class="dis_gaway_counter">
                                    <p class="dis_aboutus_sub_heading  heading m_b_40">Days Left</p>
                                    <div id="countdown">
                                        <ul class="dis_gaway_counter_list">
                                        <li><span id="days">0</span>Days</li>
                                        <li><span id="hours">0</span>Hours</li>
                                        <li><span id="minutes">0</span>Minutes</li>
                                        <li><span id="seconds">0</span>Seconds</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dis_gaway_nsletter dis_main_bg text-center" id="dis_gaway_nsletter">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dis_aboutus_inner">
                                <p class="dis_aboutus_sub_heading  m_b_20">PERFECT 10 DISCOVERED GIVEAWAY</p>
                                <p class="dis_aboutus_sub_heading m_b_50">Sign Up/Fill Details To Enter Giveaway </p>
                                <ul class="dis_gaway_tablist">
                                    <li class="active"><a data-toggle="pill" href="#tab1">I am new to Discovered</a></li>
                                    <li><a data-toggle="pill" href="#tab2">I already have an account</a></li>
                                </ul>
                                <div class="tab-content dis_gaway_signup_box">
                                    <div id="tab1" class="tab-pane fade in active">
                                        <div class="dis_gaway_signup_box_inner">
                                            <div class="dis_gaway_social_wrap">
                                                <ul class="dis_gaway_social_list">

                                                    <li>
                                                        <a class="facebook"  href="javascript:;" taget="_blank" id="faceSignup">
                                                            <i class="fa-brands fa-facebook-f"></i>
                                                            <span class="dis_gaway_sl_text">Join With Facebook</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="goolge au_google"  href="javascript:;" taget="_blank">
                                                        <i class="fa fa-google" aria-hidden="true"></i>
                                                        <span class="dis_gaway_sl_text">Join With Google</span>
                                                        </a>
                                                    </li>
                                                    <div  class="g-signin2 hide " data-onsuccess="onSignIn"></div>
                                                </ul>

                                            </div>
                                            <p class="dis_gaway_or">OR</p>
                                            <div class="dis_gaway_signup">
                                                <div class="dis_signup_form">
                                                    <form>
                                                        <div class="form-group">

                                                            <div class="input-group">
                                                                <input type="text" class="form-control dis_signup_input reg_form" id="user_name" placeholder="Enter Your Name">

                                                                <div class="input-group-addon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="16px"><path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M0.599,15.433 L0.593,15.433 C0.262,15.433 -0.001,15.143 0.026,14.803 C0.134,13.417 0.579,12.146 1.377,10.992 C2.290,9.672 3.497,8.741 5.017,8.162 C3.701,7.107 3.043,5.763 3.182,4.074 C3.282,2.861 3.813,1.851 4.721,1.066 C6.527,-0.497 9.192,-0.273 10.767,1.572 C12.315,3.387 12.256,6.403 10.001,8.145 C12.938,9.418 14.606,11.605 14.961,14.796 C15.000,15.142 14.736,15.447 14.396,15.447 L14.396,15.447 L14.363,15.447 C14.068,15.447 13.825,15.214 13.797,14.912 C13.634,13.135 12.899,11.649 11.554,10.472 C10.389,9.453 9.024,8.958 7.494,8.942 C4.421,8.910 1.462,11.403 1.165,14.900 C1.139,15.201 0.893,15.433 0.599,15.433 ZM10.655,4.486 C10.655,2.695 9.229,1.231 7.488,1.234 C5.758,1.238 4.340,2.692 4.333,4.469 C4.326,6.261 5.745,7.732 7.486,7.736 C9.228,7.740 10.655,6.276 10.655,4.486 Z"></path></svg></div>
                                                            </div>
                                                            <span id="user_name_error" class="form-error help-block"></span>
                                                        </div>
                                                        <div class="form-group">

                                                            <div class="input-group">

                                                                <input type="text" class="form-control dis_signup_input" id="user_email" placeholder="Enter Your Email">

                                                                <div class="input-group-addon"><svg xmlns="http://www.w3.org/2000/svg" width="18px" height="15px"><path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.950,14.032 L2.110,14.032 C0.964,14.032 0.032,13.024 0.032,11.783 L0.032,2.282 C0.032,1.041 0.964,0.032 2.110,0.032 L14.954,0.032 C16.100,0.032 17.032,1.045 17.029,2.286 L17.029,11.783 C17.029,13.024 16.097,14.032 14.950,14.032 ZM16.079,11.783 L16.079,2.286 C16.079,1.612 15.573,1.064 14.950,1.064 L2.110,1.064 C1.488,1.064 0.982,1.612 0.982,2.286 L0.982,11.783 C0.982,12.456 1.488,13.005 2.110,13.005 L14.954,13.005 C15.576,13.005 16.082,12.456 16.082,11.783 L16.079,11.783 ZM14.943,11.931 C14.848,12.038 14.725,12.091 14.598,12.091 C14.482,12.091 14.363,12.045 14.271,11.950 L10.044,7.603 L8.859,8.753 C8.771,8.840 8.655,8.886 8.543,8.886 C8.430,8.886 8.318,8.844 8.226,8.757 L7.073,7.641 L2.821,11.946 C2.729,12.038 2.613,12.083 2.497,12.083 C2.371,12.083 2.244,12.026 2.149,11.920 C1.970,11.710 1.980,11.387 2.170,11.193 L6.359,6.949 L2.153,2.880 C1.956,2.689 1.938,2.366 2.114,2.152 C2.290,1.939 2.589,1.920 2.786,2.111 L7.336,6.518 C7.364,6.541 7.389,6.564 7.414,6.591 C7.414,6.595 7.417,6.598 7.421,6.602 L8.539,7.683 L14.271,2.114 C14.468,1.924 14.767,1.943 14.943,2.152 C15.119,2.366 15.101,2.689 14.908,2.880 L10.751,6.914 L14.925,11.204 C15.115,11.398 15.122,11.726 14.943,11.931 Z"></path></svg></div>

                                                            </div>
                                                        <span id="user_email_error" class="form-error help-block"></span>
                                                        </div>

                                                        <div class="form-group">

                                                            <div class="input-group">

                                                                <input type="password" class="form-control dis_signup_input" id="user_pwd" placeholder="Enter Your Password">

                                                                <div class="input-group-addon"><svg xmlns="http://www.w3.org/2000/svg" width="12px" height="17px"><path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M9.403,17.000 L2.597,17.000 C1.166,17.000 -0.000,15.764 -0.000,14.246 L-0.000,8.327 C-0.000,7.820 0.389,7.408 0.867,7.408 L10.000,7.408 L10.000,5.194 C10.000,2.852 8.204,0.951 5.998,0.951 C3.790,0.951 1.996,2.856 1.996,5.194 C1.996,5.458 1.797,5.669 1.548,5.669 C1.299,5.669 1.099,5.458 1.099,5.194 C1.099,2.327 3.298,-0.000 5.998,-0.000 C8.702,-0.000 10.897,2.331 10.897,5.194 L10.897,7.408 L11.133,7.408 C11.611,7.408 12.000,7.820 12.000,8.327 L12.000,14.246 C12.000,15.764 10.834,17.000 9.403,17.000 ZM11.103,8.359 L0.900,8.359 L0.900,14.246 C0.900,15.243 1.664,16.049 2.601,16.049 L9.403,16.049 C10.343,16.049 11.103,15.239 11.103,14.246 L11.103,8.359 ZM6.002,14.053 C5.075,14.053 4.324,13.254 4.324,12.275 C4.324,11.296 5.075,10.496 6.002,10.496 C6.925,10.496 7.679,11.292 7.679,12.275 C7.679,13.254 6.928,14.053 6.002,14.053 ZM6.002,11.444 C5.570,11.444 5.221,11.820 5.221,12.275 C5.221,12.729 5.570,13.102 6.002,13.102 C6.433,13.102 6.782,12.725 6.782,12.271 C6.782,11.817 6.433,11.444 6.002,11.444 Z"></path></svg></div>

                                                            </div>
                                                            <span id="user_pwd_error" class="form-error help-block"></span>
                                                        </div>
                                                            <span id="message"></span>
                                                        <div class="dis_checkbox">
                                                            <label>
                                                                <input type="checkbox" class="check" id="checkBx2">
                                                                <i class="input-helper"></i>
                                                                <p>I have read and agree to the <a target="_blank" href="<?=base_url('giveaway-rules');?>">Terms &amp; Conditions</a></p>
                                                            </label>
                                                            <span class="form-error help-block" id="checkEr2"></span>
                                                        </div>
                                                        <div class="dis_button_div">
                                                            <button type="button" class="dis_btn sign_btn" onclick="register_user()">Sign Up & Enter Giveaway</button>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab2" class="tab-pane fade">
                                        <div class="dis_gaway_signup_box_inner dis_gaway_signup_tab2">
                                            <div class="dis_gaway_social_wrap">
                                                <ul class="dis_gaway_social_list">

                                                    <li>
                                                        <a class="facebook"  href="javascript:;" taget="_blank" id="faceLogin">
                                                            <i class="fa-brands fa-facebook-f"></i>
                                                            <span class="dis_gaway_sl_text">Login With Facebook</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="goolge au_google"  href="javascript:;" taget="_blank">
                                                        <i class="fa fa-google" aria-hidden="true"></i>
                                                        <span class="dis_gaway_sl_text">Login With Google</span>
                                                        </a>
                                                    </li>
                                                    <div  class="g-signin2 hide " data-onsuccess="onSignIn"></div>
                                                </ul>

                                            </div>
                                            <p class="dis_gaway_or">OR</p>
                                            <div class="dis_gaway_signup">
                                                <div class="dis_signup_form">
                                                    <form>
                                                        <div class="form-group">

                                                            <div class="input-group">

                                                                <input type="text" class="form-control dis_signup_input" id="u_email" placeholder="Enter Your Email">
                                                                <span class="form-error help-block"></span>
                                                                <div class="input-group-addon"><svg xmlns="http://www.w3.org/2000/svg" width="18px" height="15px"><path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.950,14.032 L2.110,14.032 C0.964,14.032 0.032,13.024 0.032,11.783 L0.032,2.282 C0.032,1.041 0.964,0.032 2.110,0.032 L14.954,0.032 C16.100,0.032 17.032,1.045 17.029,2.286 L17.029,11.783 C17.029,13.024 16.097,14.032 14.950,14.032 ZM16.079,11.783 L16.079,2.286 C16.079,1.612 15.573,1.064 14.950,1.064 L2.110,1.064 C1.488,1.064 0.982,1.612 0.982,2.286 L0.982,11.783 C0.982,12.456 1.488,13.005 2.110,13.005 L14.954,13.005 C15.576,13.005 16.082,12.456 16.082,11.783 L16.079,11.783 ZM14.943,11.931 C14.848,12.038 14.725,12.091 14.598,12.091 C14.482,12.091 14.363,12.045 14.271,11.950 L10.044,7.603 L8.859,8.753 C8.771,8.840 8.655,8.886 8.543,8.886 C8.430,8.886 8.318,8.844 8.226,8.757 L7.073,7.641 L2.821,11.946 C2.729,12.038 2.613,12.083 2.497,12.083 C2.371,12.083 2.244,12.026 2.149,11.920 C1.970,11.710 1.980,11.387 2.170,11.193 L6.359,6.949 L2.153,2.880 C1.956,2.689 1.938,2.366 2.114,2.152 C2.290,1.939 2.589,1.920 2.786,2.111 L7.336,6.518 C7.364,6.541 7.389,6.564 7.414,6.591 C7.414,6.595 7.417,6.598 7.421,6.602 L8.539,7.683 L14.271,2.114 C14.468,1.924 14.767,1.943 14.943,2.152 C15.119,2.366 15.101,2.689 14.908,2.880 L10.751,6.914 L14.925,11.204 C15.115,11.398 15.122,11.726 14.943,11.931 Z"></path></svg>
                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="form-group">

                                                            <div class="input-group">

                                                                <input type="password" class="form-control dis_signup_input" id="u_pwd" placeholder="Enter Your Password">
                                                                <span class="form-error help-block"></span>
                                                                <div class="input-group-addon"><svg xmlns="http://www.w3.org/2000/svg" width="12px" height="17px"><path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M9.403,17.000 L2.597,17.000 C1.166,17.000 -0.000,15.764 -0.000,14.246 L-0.000,8.327 C-0.000,7.820 0.389,7.408 0.867,7.408 L10.000,7.408 L10.000,5.194 C10.000,2.852 8.204,0.951 5.998,0.951 C3.790,0.951 1.996,2.856 1.996,5.194 C1.996,5.458 1.797,5.669 1.548,5.669 C1.299,5.669 1.099,5.458 1.099,5.194 C1.099,2.327 3.298,-0.000 5.998,-0.000 C8.702,-0.000 10.897,2.331 10.897,5.194 L10.897,7.408 L11.133,7.408 C11.611,7.408 12.000,7.820 12.000,8.327 L12.000,14.246 C12.000,15.764 10.834,17.000 9.403,17.000 ZM11.103,8.359 L0.900,8.359 L0.900,14.246 C0.900,15.243 1.664,16.049 2.601,16.049 L9.403,16.049 C10.343,16.049 11.103,15.239 11.103,14.246 L11.103,8.359 ZM6.002,14.053 C5.075,14.053 4.324,13.254 4.324,12.275 C4.324,11.296 5.075,10.496 6.002,10.496 C6.925,10.496 7.679,11.292 7.679,12.275 C7.679,13.254 6.928,14.053 6.002,14.053 ZM6.002,11.444 C5.570,11.444 5.221,11.820 5.221,12.275 C5.221,12.729 5.570,13.102 6.002,13.102 C6.433,13.102 6.782,12.725 6.782,12.271 C6.782,11.817 6.433,11.444 6.002,11.444 Z"></path></svg></div>

                                                            </div>

                                                        </div>
                                                        <div class="dis_checkbox">
                                                            <label>
                                                                <input type="checkbox" class="check" id="checkBx1">
                                                                <i class="input-helper"></i>
                                                                <p>I have read and agree to the <a target="_blank" href="<?=base_url('giveaway-rules');?>">Terms &amp; Conditions</a></p>
                                                            </label>
                                                            <span id="checkEr1" class="form-error help-block"></span>
                                                        </div>
                                                        <div class="dis_button_div">
                                                            <button type="button" class="dis_btn sign_btn" onclick="login_user()">Submit & Enter Giveaway</button>
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
                                <p class="dis_aboutus_para">Stay Tuned For Upcoming Discovered Giveaways</p>
                                <ul class="dis_au_btn_list">
                                    <li><a href="#dis_gaway_nsletter" class="dis_btn">Enter Giveaway</a></li>
                                    <!-- <li><a href="<?=base_url('giveaway-rules');?>" class="dis_btn gray_btn" target="_blank">Enter To Win</a></li>    -->
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


        (function () {
            const second = 1000,
                    minute = second * 60,
                    hour = minute * 60,
                    day = hour * 24;

            let offer = "december 31, 2024 12:30:00",
                countDown = new Date(offer).getTime(),
                x = setInterval(function() {

                    let now = new Date().getTime(),
                        distance = countDown - now;
                    if (distance > 0) {
                    document.getElementById("days").innerText = Math.floor(distance / (day)),
                    document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
                    document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
                    document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);
                    }
                    //do something later when date is reached
                    if (distance < 0) {
                    let headline = document.getElementById("headline"),
                        countdown = document.getElementById("countdown");

                    headline.innerText = "Offer is End!";
                    countdown.style.display = "none";

                    clearInterval(x);
                    }
                    //seconds
                }, 0)
            }());



</script>