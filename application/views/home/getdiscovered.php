<head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
:root {
--open-font: "Open Sans", sans-serif;
--anton-font: "Anton", sans-serif;
--text-color:#B1CDD7;
--white-color:#ffffff;
--primary-color:#EB5821;
}
.gd_main_wrapper{
    font-family: var(--open-font);
}
.anton{
    font-family: var(--anton-font);
}
.gd_main_wrapper h1, .gd_main_wrapper h2, .gd_main_wrapper h3, .gd_main_wrapper h4, .gd_main_wrapper h5, .gd_main_wrapper h6{
    color: var(--white-color);
    margin:0
}
.align_center{
    display: flex;
    flex-wrap: wrap;
    align-items: center;

}
.weight700{
    font-weight:700;
}
.weight400{
    font-weight:400;
}
.underline{
    text-decoration: underline;
}
.gd_main_wrapper p{
    color: var(--white-color);
    margin:0
}
.text-center{
    text-align:center
}
.d-flex{
    display: flex;
}
.justify-content-center{
    justify-content: center;
}
.align-items-center{
    align-items:center
}
.flex-wrap{
    flex-wrap:wrap
}
.p-re{
    position: relative;
}
.dis_chg_main_wrap p {
    font-family: var(--outfit-font)!important;
}
.dis_chg_main_wrap {
    background: #0E171C;
    font-family: var(--outfit-font)!important;
}
.white_color{
    color:white;
}
body {
    padding-bottom: 0!important;
}
.gd_main_wrapper {
    background: #0e171c;
}
.gd_banner_video_wrap {
    position: relative;
}
.gd_banner_video_wrap .gd_banner_video {
    background: #000;
    width: 100%;
    height: 100%;
}
.gd_banner_wrapper{
    position: relative;
}
.gd_banner_logo {
    position: absolute;
    top: 180px;
    left: 100px;
}
.getDiscoveredLogo{
    position: relative;
}
.EnterNowImg {
    position: absolute;
    right: 0;
    bottom: 50px;
    cursor: pointer;
    -webkit-animation: zoomeffect 2s infinite;
	-moz-animation: zoomeffect 2s infinite;
	-o-animation: zoomeffect 2s infinite;
	-ms-animation: zoomeffect 2s infinite;
	animation: zoomeffect 2s infinite;
}
.gd_banner_btm_vector {
    margin-top: -220px;
    position: relative;
    margin-bottom: 20px;
}
.gd_heading1 {
    font-size: 50px;
    letter-spacing: 3px;
    text-transform: uppercase;
    line-height: 1.5;
}
/* border heading */
.mf_h_border {
    width: 300px;
    height: 8px;
    content: '';
    background: var(--primary-color);
    position: relative;
    display: block;
    margin-top: 20px;
}
.mf_h_border:before {
    position: absolute;
    bottom: 0;
    right: -5px;
    width: 8px;
    height: 8px;
    content: '';
    z-index: 5;
    background: #eb5821;
    transform: skewX(-40deg);
}
.mf_h_border > span{
    position: absolute;
    bottom: 0;
    width: 8px;
    height: 8px;
    content: '';
    z-index: 5;
    background: #000000;
    animation: mover 2s infinite alternate;
    transform: skewX(-40deg);
}
.mf_h_bb_wrap {
    margin: 10px 0 25px;
}
@keyframes mover {
    0% { left: 10%; }
    100% { left: 90%; }
}
/* border heading */
.rotate{
    transform: rotate(-3deg);
}
.gd_headingSub {
    font-size: 30px;
    font-weight: 700;
    color: #fff;
    line-height: 1.5;
}
.gd_sec2_wrapper .gd_headingSub{
    margin:50px 0 50px;
}
.gd_sec2_wrapper {
    padding: 100px 0;
    background: url(repo/images/getdiscovered/sec2_bg.jpg) no-repeat;
    background-size: cover;
    position: relative;
    z-index: 0;
}
.gd_sec2_wrapper:after {
    content: '';
    position: absolute;
    z-index: -1;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: hwb(0deg 0% 100% / 30%);
}
.gd_btn {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    background: #eb581f;
    transition: all 0.3s linear;
    -ms-transition: all 0.3s linear;
    -o-transition: all 0.3s linear;
    -webkit-transition: all 0.3s linear;
    -moz-transition: all 0.3s linear;
    display: flex;
    align-items: center;
    max-width: max-content;
    padding: 14px 34px;
    border-radius: 5px;
    letter-spacing: 0.2px;
}
.gd_btn:hover, .gd_btn:focus, .gd_btn:active {
    color: #fff;
}
.gd_sec4_wrapper {
    padding: 100px 0;
}
.gd_sec4_wrapper .container {
    max-width: 1310px;
    width: 100%;
}
.gd_sec4_wrapper ul {
    padding-left: 22px;
    margin: 20px 0;
}
.gd_sec4_wrapper ul li {
    color: #ffffff;
    font-size: 20px;
    margin-bottom: 20px;
    list-style-type: disclosure-closed;
}

.gd_sec5_wrapper {
    position: relative;
}
.gd_bg_wrapperTop {
    position: absolute;
}
.gd_bg_wrapper:before {
    content: "";
    position: absolute;
    bottom: -230px;
    left: 0;
    right: 0;
    background: url(repo/images/getdiscovered/round.webp) no-repeat;
    width: 100%;
    height: 1500px;
    background-size: contain;
    background-position: center center;
}
.gd_sec6_wrapper {
    padding: 100px 0;
    position: relative;
    z-index: 0;
}
.gd_sec6_wrapper .gd_secImg {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    z-index: -1;
}
.gd_sec6_wrapper .gd_sec_details {
    max-width: 990px;
    padding: 0 10px 0 120px;
}
.gd_sec6_wrapper .mf_h_border {
    width: calc(80%);
}
.gd_sec6_wrapper .gd_heading1 {
    font-size: 95px;
    position: relative;
}
.gd_sec6_wrapper .gd_heading1 .gd_click {
    position: absolute;
    right: -60px;
    bottom: -30px;
}
.gd_here {
    position: relative;
}
.gd_here:hover {
    color: #eb581f;
}
.gd_sec6_wrapper .gd_sec_details {
    max-width: 1030px;
    padding: 100px 0 100px 180px;
}
.gd_sec6_wrapper .gd_headingSub {
    margin-top: 90px;
}
.gd_bg_wrapper {
    padding: 0 0 100px;
    position: relative;
    z-index: 0;
    overflow: hidden;
}
.gd_bg_wrapper:after {
    content: "";
    position: absolute;
    top: 485px;
    left: 0;
    right: 0;
    background: url(repo/images/getdiscovered/round.webp) no-repeat;
    z-index: -1;
    width: 100%;
    height: 1500px;
    background-size: contain;
    background-position: center center;
    display: flex;
    justify-content: center;
    align-items: center;
}
.gd_sec7_wrapper {
    padding: 100px 0 0;
}
.gd_sec7_video {
    position: relative;
}
.gd_sec7_video iframe {
    width: 975px;
    height: 550px;
    border-radius: 10px;
    margin: 0px auto;
    text-align: center;
    display: flex;
    justify-content: center;
}
.gd_sec7_btm {
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.gd_sec7_wrapper .gd_headingSub {
    margin: 50px auto 50px;
}

.gd_sec7_video:after {
    content: "";
    position: absolute;
    top: -76px;
    background: url(repo/images/getdiscovered/what.png) no-repeat;
    background-size: 700px 123px;
    width: 700px;
    height: 123px;
    left: 50px;
}
.gd_sec8_wrapper {
    padding: 100px 0;
    background: url(repo/images/getdiscovered/bg-orange.webp) no-repeat;
    background-size: cover;
    position: relative;
}
.gd_judgeImg2 {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
}
.gd_secImg.johnta {
    display: flex;
    justify-content: flex-end;
}
.gd_sec10_wrapper {
    padding-bottom: 40px;
}
.gd_sec11_wrapper .gd_headingSub {
    padding-top: 50px;
}
.gd_btnLink, .gd_btnLink:hover, .gd_btnLink:focus  {
    font-size: 30px;
    font-weight: 700;
    color: var(--primary-color);
    display:inline-block;
}
.gd_sec11_wrapper {
    padding: 100px 0;
}

@media (min-width: 992px){
    .verticle_sec_center {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }
    .gd_sec4_wrapper .gd_secImg {
        display: flex;
        justify-content: end;
    }
}
@media (max-width: 1800px){
    .gd_sec6_wrapper .gd_heading1 {
        font-size: 60px;
    }
    .gd_sec6_wrapper .gd_heading1 .gd_click {
        right: -47px;
        bottom: -9px;
    }
    .gd_click img {
        width: 60px;
    }
    .gd_banner_logo img {
        max-width: 500px;
    }
    .EnterNowImg {
        bottom: 30px;
    }
}
@media (max-width: 1600px){
    .gd_sec6_wrapper .gd_sec_details {
        max-width: 680px;
        padding: 100px 0 100px 100px;
    }
    .gd_sec7_video iframe {
        width: 780px;
        height: 440px;
    }
    .gd_sec7_video:after {
        top: -56px;
        background-size: 600px 105px;
        width: 600px;
        height: 105px;
        left: 110px;
    }

}
@media (max-width: 1400px){
        .gd_banner_logo {
        left: 50px;
    }
    .gd_banner_video_wrap .gd_banner_video {
        /* height: 700px; */
    }
    .gd_banner_logo img {
        max-width: 400px;
    }
    .EnterNowImg {
        bottom: -10px;
    }


}
@media (max-width: 1300px){
    .gd_sec6_wrapper .gd_secImg img {
        max-width: 450px;
    }
}
@media (max-width: 1199px){
    .gd_banner_logo {
        top: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .gd_banner_logo img {
        max-width: 330px;
    }
    .gd_heading1 {
        font-size: 38px;
    }
    .gd_headingSub, .gd_btn_text {
        font-size: 25px;
    }
    .gd_sec6_wrapper .gd_sec_details {
        max-width: 560px;
        padding: 100px 0 100px 40px;
    }
    .gd_sec6_wrapper .gd_secImg img{
        max-width: 400px;
    }
    .gd_sec6_wrapper .gd_heading1 {
        font-size: 40px;
    }
    .gd_sec6_wrapper .container-fluid{
        padding: 0;
    }
    .gd_sec6_wrapper .gd_sec_details {
        max-width: 100%;
        padding: 0px 50px;
    }
    .gd_sec6_wrapper .gd_secImg {
    display: none;
    }
    .gd_sec6_wrapper .gd_heading1 .gd_click {
        right: -42px;
        bottom: -11px;
    }
    .gd_click img {
        width: 45px;
    }
    .gd_sec7_video:after {
        top: -49px;
        background-size: 500px 88px;
        width: 500px;
        height: 88px;
        left: 51px;
    }
    .gd_sec11_wrapper .gd_heading1 br {
        display: none;
    }

}

@media (max-width: 991px){
    .gd_banner_logo img {
        max-width: 270px;
    }
    .EnterNowImg img {
        max-width: 130px;
    }
    .gd_sec2_wrapper .gd_secImg img{
    max-width: 200px;
    }
    .img_center {
        display: flex;
        justify-content: center;
        width: 250px;
        margin: 0 auto 50px;
    }
    .gd_sec7_video iframe {
        width: 600px;
        height: 338px;
    }
    .EnterNowImg {
        bottom: -30px;
    }
    .gd_banner_btm_vector {
        margin-top: -10px;
    }
    .gd_sec2_wrapper {
    padding: 0 0 100px;
    }
    .gd_sec7_video:after {
        top: -100px;
        background-size: 500px 88px;
        width: 500px;
        height: 88px;
        left: 0;
        right: 0;
        margin: auto;
        transform: rotate(4deg);
    }
}




@media (max-width: 767px){
    .gd_heading1 {
        font-size: 32px;
    }
    .gd_sec7_video iframe {
        width: 500px;
        height: 282px;
    }

    .gd_judgeImg1 img,  .gd_judgeImg2 img{
        max-width:100%;
    }
    .gd_banner_video_wrap .gd_banner_video {
        /* height: 380px; */
    }
    .gd_banner_logo img {
        max-width: 170px;
    }
    .EnterNowImg img {
        max-width: 120px;
    }

}
@media (max-width: 575px){
    .gd_sec7_video iframe {
        width: 400px;
        height: 226px;
    }
    .gd_sec7_video:after {
        background-size: 350px 62px;
        width: 350px;
        height: 62px;

    }
    .gd_bg_wrapper:before {
        bottom: -520px;
    }
    .gd_sec6_wrapper .gd_headingSub {
        margin-top: 50px;
    }
    .gd_bg_wrapper:after {
        top: 645px;
    }
}
@media (max-width: 480px){

}
@media (max-width: 420px){
    .gd_heading1 {
        font-size: 24px;
    }
    .gd_headingSub, .gd_btn_text {
        font-size: 20px;
    }
    .gd_sec7_video iframe {
        width: 350px;
        height: 197px;
    }
    .gd_banner_video_wrap .gd_banner_video {
        /* height: 220px; */
    }
    .gd_banner_logo {
        left: 20px;
    }
    .gd_banner_logo .getDiscoveredLogo > img {
        max-width: 150px;
    }

}
@media (max-width: 380px){
    .gd_sec7_video iframe {
        width: 310px;
        height: 175px;
    }
    .EnterNowImg {
        bottom: -10px;
        max-width: 90px;
    }
    .gd_sec7_video:after {
        background-size: 310px 55px;
        width: 310px;
        height: 55px;
    }
}

</style>
</head>

<?php $is_login	=   is_login(); ?>

<div class="gd_main_wrapper">
    <div class="gd_banner_wrapper">
        <div class="gd_banner_video_wrap">
            <!-- <iframe src="https://local.app-discovered.tv/embedcv/294?controls=false&autoplay=true&muted=false&loop=false" width="560" height="315" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe> -->
            <!-- <img src="<?php echo base_url('repo/images/getdiscovered/bannerImg.png');?>" class="img-responsive"> -->
            <video class="gd_banner_video" autoplay muted playsinline loop>
                <source src="<?php echo base_url('repo/images/getdiscovered/video.mp4');?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="gd_banner_logo">
            <div class="getDiscoveredLogo">
                <img src="<?php echo base_url('repo/images/getdiscovered/bannerLogo.webp');?>" class="img-responsive">
                <div class="EnterNowImg">
                <?php if($is_login){ ?>
                    <a href="<?=base_url('monetization/getdiscovered');?>"><img src="<?php echo base_url('repo/images/getdiscovered/enterNow.png');?>" class="img-responsive"></a>
                <?php }else{ ?>
                    <img src="<?php echo base_url('repo/images/getdiscovered/enterNow.png');?>" class="img-responsive openModalPopup" data-href="modal/login_popup?getdiscovered=1" data-cls="login_mdl" onclick="OpenRoute('monetization/getdiscovered')">
                <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="gd_sec2_wrapper">
        <div class="gd_banner_btm_vector">
            <img src="<?php echo base_url('repo/images/getdiscovered/banner_vector1.webp');?>" class="img-responsive">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="gd_secImg img_center">
                        <img src="<?php echo base_url('repo/images/getdiscovered/sec2_img.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="gd_sec_details">
                        <div class="rotate">
                            <h1 class="gd_heading1 anton">Step into the spotlight and join our world music challenge! </h1>
                            <span class="mf_h_border">
                                <span></span>
                            </span>
                        </div>
                        <p class="gd_headingSub">This is not just a competition, but a platform to challenge your boundaries, showcase your unique talent, and gain worldwide recognition.</p>
                        <?php if(!$is_login){ ?>
                            <a href="<?=base_url('sign-up?getdiscovered=1');?>" class="gd_btn">
                                <span class="gd_btn_text">Sign-up today</span>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gd_sec3_wrapper">
        <img src="<?php echo base_url('repo/images/getdiscovered/logos_img.jpg');?>" class="img-responsive">
    </div>
    <div class="gd_bg_wrapper">
        <div class="gd_bg_wrapperTop">
            <img src="<?php echo base_url('repo/images/getdiscovered/gd_bg_topVector.webp');?>" class="img-responsive">
        </div>
        <div class="gd_sec4_wrapper">
            <div class="container">
                <div class="row verticle_sec_center">
                    <div class="col-md-6 col-md-push-6">
                        <div class="gd_secImg img_center">
                            <img src="<?php echo base_url('repo/images/getdiscovered/PhoneLeaderboard.png');?>" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-6 col-md-pull-6">
                        <div class="gd_sec_details">
                            <div class="rotate">
                                <h1 class="gd_heading1 anton"> <span class="main-color anton"> upload your video </span> and get discovered now!</h1>
                                <span class="mf_h_border">
                                    <span></span>
                                </span>
                            </div>
                            <p class="gd_headingSub p_t_50">Fans vote, creators share and climb the leaderboard. </p>
                            <ul>
                                <li>Sharing your videos on social network</li>
                                <li>Receiving votes on your videos</li>
                                <li>Have your videos shared by fans</li>
                                <li>Acquiring new fans</li>
                            </ul>
                            <p class="gd_headingSub">Get in the mix and upload a video showcasing the best of your talent for the chance to get seen and win big.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="gd_sec7_wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="gd_sec7_video">
                            <iframe src="https://dev.discovered.tv/embedcv/6929?controls=true&autoplay=false&muted=true&loop=true" width="560" height="315" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>
                        </div>
                        <div class="gd_sec7_btm">
                            <p class="gd_headingSub">Get Discovered is a global competition designed to showcase the talents of artists, songwriters, and producers. Participants will have the opportunity to submit their work, be judged by industry professionals, and gain exposure on a global platform. The competition aims to discover and promote new talent in the music industry.</p>
                            <?php if(!$is_login){ ?>
                                <a href="<?=base_url('sign-up?getdiscovered=1');?>" class="gd_btn">
                                    <span class="gd_btn_text">Sign-up today</span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="gd_sec6_wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="gd_secImg">
                            <img src="<?php echo base_url('repo/images/getdiscovered/fist_mic_logo.png');?>" class="img-responsive">
                        </div>
                        <div class="gd_sec_details">
                            <div class="rotate">
                            <?php
                                if($is_login){
									$href = 'href="'.base_url('monetization/getdiscovered').'" ';
                                }else{
                                    $href = 'class="openModalPopup gd_here main-color anton" data-href="modal/login_popup?getdiscovered=1" data-cls="login_mdl" onclick="OpenRoute(\'monetization/getdiscovered\')"';
                                }
                            ?>
                                <h1 class="gd_heading1 anton">Upload a Video showcasing the best of your talent <a <?=$href;?> class="gd_here main-color anton"> here  <div class="gd_click">
                            <img src="<?php echo base_url('repo/images/getdiscovered/Clicck.png');?>" class="img-responsive">
                        </div> </a>   </h1>
                                <span class="mf_h_border">
                                    <span></span>
                                </span>
                            </div>
                            <p class="gd_headingSub"> Original Songs Only, No Covers, No Introductions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="gd_sec5_wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <img src="<?php echo base_url('repo/images/getdiscovered/producerChallenge.webp');?>" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="gd_sec_wrapper">
            <img src="<?php echo base_url('repo/images/getdiscovered/secimg2.jpg');?>" class="img-responsive">
        </div>
        <div class="gd_sec_wrapper">
            <img src="<?php echo base_url('repo/images/getdiscovered/secimg3.jpg');?>" class="img-responsive">
        </div> -->
    </div>
    <div class="gd_sec8_wrapper">
        <img src="<?php echo base_url('repo/images/getdiscovered/rotate2.webp');?>" class="img-responsive">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="gd_judgeImg1">
                        <img src="<?php echo base_url('repo/images/getdiscovered/jessicaImg.png');?>" class="img-responsive">
                    </div>
                    <div class="gd_judgeImg2">
                        <img src="<?php echo base_url('repo/images/getdiscovered/jojImg.webp');?>" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
        <img src="<?php echo base_url('repo/images/getdiscovered/rotate3.webp');?>" class="img-responsive">
        <div class="gd_sec9_wrapper">
            <div class="container">
                <div class="row m_b_50 align_center">
                    <div class="col-md-6">
                        <div class="gd_secImg m_b_50">
                            <img src="<?php echo base_url('repo/images/getdiscovered/terry_lewis.png');?>" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="gd_sec_details m_b_50">
                            <p class="gd_headingSub">Five-time grammy winner, and world-famous record producer and songwriter, and member of legendary production duo Jimmy Jam and Terry Lewis. </p>
                        </div>
                    </div>
                </div>
                <div class="row m_b_50 align_center">
                    <div class="col-md-6 col-md-push-6">
                        <div class="gd_secImg johnta m_b_50">
                            <img src="<?php echo base_url('repo/images/getdiscovered/johnta.png');?>" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-6 col-md-pull-6">
                        <div class="gd_sec_details m_b_50">
                            <p class="gd_headingSub">Grammy winning songwriter, record producer, and film producer known for his work with artists like Madonna, TLC, and Boy II Men. Austin has produced over 60 hit Billboard Hot 100 singles. </p>
                        </div>
                    </div>
                </div>
                <div class="row m_b_50 align_center">
                    <div class="col-md-6">
                        <div class="gd_secImg m_b_50">
                            <img src="<?php echo base_url('repo/images/getdiscovered/dallas.png');?>" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="gd_sec_details m_b_50">
                            <p class="gd_headingSub">Two-time grammy winning singer, songwriter, and record producer, best known for his work with So So Def Records and artists like Mariah Carey and Mary J. Blige. </p>
                        </div>
                    </div>
                </div>
                <div class="row align_center">
                    <div class="col-md-6 col-md-push-6">
                        <div class="gd_secImg johnta  m_b_50">
                            <img src="<?php echo base_url('repo/images/getdiscovered/bryan.webp');?>" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-6 col-md-pull-6">
                        <div class="gd_sec_details m_b_50">
                            <p class="gd_headingSub">Grammy winning songwriter and producer known for his work with Usher, Mariah Carey,  Mary J Blige and Toni Braxton; Cox holds the Guiness World Record for the longest period of chart success-previously held by The Beatles. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gd_sec10_wrapper ">
        <img src="<?php echo base_url('repo/images/getdiscovered/bottom.webp');?>" class="img-responsive">
    </div>
    <div class="gd_sec11_wrapper">
        <div class="gd_banner_btm_vector">
            <img src="<?php echo base_url('repo/images/getdiscovered/contest_Rules.webp');?>" class="img-responsive">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="gd_secImg img_center">
                        <img src="<?php echo base_url('repo/images/getdiscovered/rulesMockup.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="gd_sec_details">
                        <div class="rotate">
                            <h1 class="gd_heading1 anton">Rules For The </br> Get Discovered Challenge </h1>
                            <span class="mf_h_border">
                                <span></span>
                            </span>
                        </div>
                        <p class="gd_headingSub">Artists, Songwriters, Producers upload a  video showcasing the best of your talent for the chance to get seen and win big. Viewers will vote & the judges will decide! (Original Songs Only, No Covers, No Introductions) General Prize Promotion Terms & Conditions </p>

                            <a href="<?php echo base_url('repo/images/challenges/contestRules.pdf');?>" target="_blank"  class="gd_btnLink m_t_20">
                                <span class="gd_btn_text">See Attached</span>
                            </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



