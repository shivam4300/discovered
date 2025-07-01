<head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800;900&family=Oswald:wght@200;300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap');
:root {
--oswald-font: 'Oswald', sans-serif;
--outfit-font: 'Outfit', sans-serif;
--text-color:#B1CDD7;
--white-color:#ffffff;
--primary-color:#EB5821;
}
*{
    font-family: var(--outfit-font);
}
.outfit{
    font-family: var(--outfit-font);
}
h1, h2, h3, h4, h5, h6{
    color: var(--white-color);
    margin:0
}
p{
    color: var(--text-color);
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
.main_contnt_wrapper {
    padding-top: 0!important;
}
.dis_chg_banner_wrap {
    background: #11060E;
    overflow: hidden;
    padding-top: 93px;
}
.dis_chgBanner_ttl {
    font-size: 46px;
    font-weight: 600;
    color: #fff;
}
.dis_chg_banner_wrap .mf_h_bb_wrap .mf_h_bb {
    font-size: 24px;
    font-weight: 600;
}
.dis_chgBanner_stl {
    font-size: 20px;
    font-weight: 500;
    color: #fff;
    margin-bottom: 40px;
    line-height: 1.3;
}
.dis_chg_banner_wrap .c_container {
    padding-left: 195px;
}
.dis_chg_banner_wrap .row  {
    position: relative;
    z-index: 0;
}
.dis_chg_banner_details {
    padding: 255px 0;
}
.dis_chgBanner_img {
    position: absolute;
    right: 0;
    bottom: 0;
    z-index: -1;
}
.dis_chgBanner_list > li {
    padding: 2px 10px;
}
.dis_chgBanner_list {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px;
}
.dis_chg_heading {
    font-size: 38px;
    font-weight: 600;
    color: #fff;
}
/* border heading */
.mf_h_bb_wrap {
    margin: 10px 0 25px;
}
.mf_h_bb {
    font-size: 20px;
    margin-bottom: 16px;
    font-weight: 400;
}
.mf_h_border {
    width: 169px;
    height: 4px;
    content: '';
    background: var(--primary-color);
    position: relative;
    display: block;
}
.mf_h_border:before {
    position: absolute;
    bottom: 0;
    right: -2px;
    width: 4px;
    height: 4px;
    content: '';
    z-index: 5;
    background: #eb5821;
    transform: skewX(-40deg);
}
.mf_h_border > span{
    position: absolute;
    bottom: 0;
    width: 4px;
    height: 4px;
    content: '';
    z-index: 5;
    background: #101111;
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
.mf_btn {
    font-size: 18px;
    font-weight: 500;
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
    letter-spacing:0.2px;
}
.mf_btn:hover, .mf_btn:focus, .mf_btn:active {
    color: #fff;
}
/* sec 2 start */
.dis_chg_sec2_wrap  .mf_h_bb {
    font-size: 38px;
    font-weight: 600;
}
.dis_chg_sec2_wrap .container-fluid {
    max-width: 1471px;
}
.dis_chg_sec2_wrap {
    padding: 100px 0;
}
.dis_chg_sec2lh {
    font-size: 20px;
    font-weight: 600;
}
.dis_chg_rightText {
    color: #fff;
    position: relative;
    font-size: 16px;
    font-weight: 500;
    padding-left: 40px;
}
.dis_chg_rightText:after {
    position: absolute;
    left: 0;
    top: 2px;
    content: "";
    background: url(repo/images/metfest/sec8/right.svg) no-repeat;
    background-size: 100%;
    width: 25px;
    height: 20px;
}
.dis_chg_link {
    color: #EB5821;
    text-decoration: underline;
}
.dis_chg_link:hover, .dis_chg_link:focus {
    letter-spacing: 1px;
    color: #EB5821;
    text-decoration: underline;
}
.dis_chg_listtext {
    padding-left: 40px;
    margin-top: 5px;
}
.dis_chg_sec2List > li:not(:last-child) {
    margin-bottom: 18px;
}
.dis_chg_sec2_details {
    padding-left: 83px;
}
/* sec 3 start */
.dis_chg_sec3_wrap {
    background: url(repo/images/challenges/sec3_bg.jpg) no-repeat;
    background-size: cover;
    padding: 100px 0;

}
.dis_chg_sec3_inner {
    background: url(repo/images/challenges/video_bg.png) no-repeat;
    background-size: 794px 500px;
    text-align: center;
    width: 794px;
    height: 500px;
    margin: auto;
    padding: 22px;
}
.dis_chg_sec3_inner iframe {
    height: 448px;
    width: 745px;
    margin: auto;
}
/* section 4 start */
.dis_chg_sec4_wrap .mf_h_border {
    width: 99px;
}
.dis_chg_sec4_wrap {
    padding: 100px 0;
}
.dis_chg_Judgesbox {
    background: url(repo/images/challenges/sec4vector.png) no-repeat center;
    background-size: 100% 100%;
    padding: 30px;
    max-width: 422px;
    margin: 0 15px;
    display: flex;
    align-items: center;
    width: 100%;
}
.dis_chg_Judgesstt {
    font-size: 18px;
}
.dis_chg_JudgesDetails .mf_h_bb {
    font-size: 22px;
    font-weight: 600;
}
.dis_chg_JudgesDetails {
    margin-left: 30px;
}
.dis_chg_JudgesDetails .mf_h_bb_wrap {
    margin: 0px 0 12px;
}
.dis_chg_JudgesList {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}
/* section 5 start */
.dis_chg_sec5_wrap .container-fluid {
    max-width: 1336px;
}
.dis_chg_sec5_wrap {
    padding: 0px 0 100px;
}
.dis_chg_Jurorsbox {
    background: url(repo/images/challenges/sec5vector.png) no-repeat center;
    background-size: 100% 100%;
    padding: 71px 30px 65px;
    max-width: 289px;
    margin: 0 15px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}
.dis_chg_JurorsDetails .mf_h_bb_wrap {
    margin: 20px 0 14px;
}
.height2LIne .mf_h_border {
    height: 2px;
}
.height2LIne .mf_h_border:before {
    position: absolute;
    height: 2px;
}
.dis_chg_JurorsDetails .mf_h_border {
    width: 100px;
    margin: auto;
}
.dis_chg_JurorsList {
    display: flex;
    grid-gap: 10px 50px;
}
.dis_chg_headingbg {
    background: url(repo/images/challenges/heading_bg.png) no-repeat center;
    background-size: 589px 76px;
    padding: 25px 15px;
    display: inline-block;
    color: #2B3133;
    font-size: 22px;
    font-weight: 700;
    width: 100%;
}
.dis_chg_sec6_wrap .dis_chg_para {
    font-weight: 500;
    font-size: 20px;
}
.dis_chg_countdown {
    background: #223038;
    max-width: 650px;
    width: 100%;
    padding: 48px 20px 30px;
    border-radius: 5px;
    margin: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: -37px;
}
.dis_chg_countdown > ul {
    display: flex;
    justify-content: center;
    align-items: center;
}
.dis_chg_countdown > ul > li {
    padding: 5px 18px;
    font-size: 20px;
    text-align: center;
    color: var(--text-color);
}
.dis_chg_countdown > ul > li > span {
    display: block;
    margin-bottom: 20px;
    font-weight: 700;
    font-size: 38px;
    color: #fff;
}
/* section 6 start */
.dis_chg_sec6_wrap {
    padding: 50px 0;
    background: url(repo/images/challenges/sec6_bg.png) #101B21 no-repeat;
    background-size: cover;
}
/* section 7  start */
.dis_chg_sec7_wrap {
    padding: 100px 0;
}
.dis_chg_sec7_wrap .container-fluid {
    max-width: 1300px;
}
/* section 8 start */
.dis_chg_sec8_wrap {
    background: url(repo/images/challenges/sec8_bg.png) no-repeat center;
    text-align: center;
    padding: 50px 0px 50px;
    background-size: 100% 100%;
}
.dis_chg_sec8_wrap .dis_chg_heading{
    display: flex;
    justify-content: center;
    align-items: center;
}
.dis_chg_sec8_mic {
    margin-left: 40px;
}
/* section 9 start */
.dis_chg_sec9_wrap{
    padding: 100px 0px;
}
@media (min-width: 1200px){

}
@media (max-width: 1799px){
    .dis_chg_banner_wrap .c_container {
    padding-left: 80px;
}
.dis_chg_banner_details {
    padding: 220px 0;
}
.dis_chgBanner_img {
    max-width: 1154px;
}
}
@media (max-width: 1499px){
    .dis_chg_heading {
        font-size: 32px;
    }
}
@media (max-width: 1599px){
    .dis_chgBanner_ttl {
        font-size: 38px;
    }
    .dis_chgBanner_stl, .dis_chg_sec6_wrap .dis_chg_para {
    font-size: 18px;
}
    .dis_chgBanner_img {
        max-width: 1045px;
    }
    .dis_chg_banner_details {
        padding: 200px 0;
    }
}
@media (max-width: 1499px){

    .mb_lg_30{
        margin-bottom:30px
    }
    .mb_lg_40{
        margin-bottom:40px
    }
    .mb_lg_50{
        margin-bottom:50px
    }
    .mt_lg_30{
        margin-top:30px
    }
    .mt_lg_40{
        margin-top:40px
    }
    .mt_lg_50{
        margin-top:50px
    }
    .dis_chg_sec2_wrap .mf_h_bb {
        font-size: 34px;
    }
    .dis_chg_sec2_details {
        padding-left: 30px;
    }
    .dis_chg_JurorsList {
    grid-gap: 10px 5px;
}
}
@media (max-width: 1399px){
    .dis_chgBanner_img {
        max-width: 965px;
    }
    .dis_chg_banner_details {
        padding: 165px 0;
    }
    .dis_chg_sec2_wrap .mf_h_bb {
        font-size: 32px;
    }
}
@media (max-width: 1199px){
    .mb_md_30{
        margin-bottom:30px
    }
    .mb_md_40{
        margin-bottom:40px
    }
    .mb_md_50{
        margin-bottom:50px
    }
    .mt_md_30{
        margin-top:30px
    }
    .mt_md_40{
        margin-top:40px
    }
    .mt_md_50{
        margin-top:50px
    }
    .dis_chg_heading {
    font-size: 30px;
    }
    .dis_chg_banner_details {
        padding: 100px 0;
    }
    .dis_chgBanner_img {
        max-width: 745px;
    }
    .dis_chg_sec2_wrap, .dis_chg_sec3_wrap, .dis_chg_sec4_wrap, .dis_chg_sec5_wrap, .dis_chg_sec7_wrap, .dis_chg_sec9_wrap{
        padding: 50px 0;
    }
}
@media (max-width: 991px){
    .mb_sm_30{
        margin-bottom:30px
    }
    .mb_sm_40{
        margin-bottom:40px
    }
    .mb_sm_50{
        margin-bottom:50px
    }
    .mt_sm_30{
        margin-top:30px
    }
    .mt_sm_40{
        margin-top:40px
    }
    .mt_sm_50{
        margin-top:50px
    }
    .dis_chgBanner_img {
        top: 0;
        right: 0;
    }
    .dis_chgBanner_img:after {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #00000082;
    }
    .dis_chg_banner_wrap .c_container {
        padding: 0 50px;
    }
    .dis_chg_banner_details {
        padding: 30px 0 50px;
    }
    .dis_chgBanner_ttl {
        font-size: 32px;
    }
    .dis_chg_banner_wrap .mf_h_bb_wrap .mf_h_bb {
        font-size: 20px;
    }
        .mf_btn {
        font-size: 16px;
        padding: 14px 20px;
    }
    .dis_chg_sec3_inner {
    background-size: 700px 450px;
    width: 700px;
    height: 450px;
    }
    .dis_chg_sec3_inner iframe {
        height: 402px;
        width: 652px;
    }
    .dis_chg_JurorsList {
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
}


}
@media (max-width: 767px){
    .dis_chgBanner_ttl {
        font-size: 28px;
    }
    .dis_chg_banner_wrap .mf_h_bb_wrap .mf_h_bb {
        font-size: 18px;
    }
    .dis_chgBanner_stl {
        font-size: 16px;
    }
    .dis_chg_sec2_wrap .mf_h_bb {
        font-size: 28px;
    }
    .dis_chg_sec3_inner iframe {
        height: 270px;
        width: 460px;
    }
    .dis_chg_sec3_inner {
        background-size: 500px 310px;
        width: 500px;
        height: 310px;
    }
    .dis_chg_headingbg {
    background-size: 510px 76px;
    }
    .dis_chg_countdown > ul > li > span {
    margin-bottom: 15px;
    font-size: 32px;
    }
    .dis_chg_countdown > ul > li {
    padding: 5px 18px;
    font-size: 18px;
}
}

@media (max-width: 575px){
    .mf_sec5_list > li {
    display: flex;
    align-items: center;
    }
    .dis_chg_banner_wrap .c_container {
        padding: 0 15px;
    }
    .dis_chgBanner_ttl {
        font-size: 24px;
    }
    .dis_chg_banner_wrap .mf_h_bb_wrap .mf_h_bb {
        font-size: 18px;
    }
    .dis_chgBanner_stl {
    margin-bottom: 25px;
    }
    .dis_chgBanner_list > li {
    padding: 2px 5px;
    }
    .dis_chgBanner_list {
    margin: 0 -5px;
    }
    .mf_btn {
        font-size: 15px;
        padding: 14px 10px;
    }
    .dis_chg_sec3_inner {
        background-size: 450px 300px;
        width: 450px;
        height: 300px;
    }
    .dis_chg_sec3_inner iframe {
        height: 255px;
        width: 405px;
    }
    .dis_chg_headingbg {
    background-size: 450px 76px;
    font-size: 18px;
    }
    .dis_chg_sec8_mic {
        margin-left: 15px;
    }
    .dis_chg_sec8_wrap .dis_chg_heading{
        font-size: 24px;
    }
    .dis_chg_countdown > ul > li > span {
        font-size: 28px;
    }
    .dis_chg_countdown > ul > li {
    font-size: 16px;
    }
}

@media (max-width: 480px){
    .dis_chg_banner_details {
        padding: 0px 0 50px;
    }
    .mf_btn {
        font-size: 14px;
        padding: 7px 10px;
    }
    .dis_chgBanner_list img {
    max-width: 109px;
    }
    .dis_chgBanner_stl {
        font-size: 16px;
    }
    .dis_chg_sec2_wrap .mf_h_bb {
        font-size: 26px;
    }
    .dis_chg_sec3_inner iframe {
        height: 249px;
        width: 375px;
    }
    .dis_chg_sec3_inner {
        background-size: 400px 270px;
        width: 400px;
        height: 270px;
    }
    .dis_chg_sec3_inner {
    padding: 10px;
    }
    .dis_chg_Judgesbox {
        padding: 20px;
    }
    .dis_chg_JudgesDetails .mf_h_bb {
    font-size: 18px;
    }
    .dis_chg_headingbg {
    background-size: 380px 76px;
    font-size: 16px;
    }
    .dis_chg_sec8_wrap .dis_chg_heading {
        font-size: 22px;
        flex-wrap: wrap;
        grid-gap: 10px;
    }
    .dis_chg_countdown > ul > li > span {
        font-size: 26px;
        margin-bottom: 8px
    }
    .dis_chg_countdown > ul > li {
        font-size: 15px;
        padding: 5px 10px;
    }
}
@media (max-width: 420px){
    .dis_chg_heading {
    font-size: 22px;
    }
    .dis_chgBanner_ttl {
        font-size: 22px;
    }
    .dis_chgBanner_list img {
        max-width: 95px;
    }
    .mf_btn {
        font-size: 14px;
        padding: 5px 8px;
    }
    .dis_chg_sec3_inner iframe {
        height: 228px;
        width: 325px;
    }
    .dis_chg_sec3_inner {
        background-size: 350px 250px;
        width: 350px;
        height: 250px;
    }
    .dis_chg_Judgesstt {
        font-size: 14px;
    }
    .dis_chg_JudgesImg > img {
        width: 60px;
    }
    .dis_chg_JudgesDetails .mf_h_bb {
        margin-bottom: 10px;
    }
    .dis_chg_JudgesDetails .mf_h_bb_wrap {
    margin: 0px 0 7px;
    }
    .dis_chg_headingbg {
    background-size: 330px 76px;
    padding: 20px 15px;
    font-size: 14px;
    }

}


</style>
</head>
<div class="dis_chg_main_wrap">
    <div class="dis_chg_banner_wrap">
        <div class="c_container">
            <div class="row row align-items-center flex-wrap  d-flex ">
                <div class="dis_chgBanner_img">
                    <img src="<?php echo base_url('repo/images/challenges/banner_img1.png');?>" class="img-responsive">
                </div>
                <div class="col-lg-6 col-md-8">
                    <div class="dis_chg_banner_details">
                        <h1 class="dis_chgBanner_ttl">Get Discovered Global Challenge</h1>
                        <div class="mf_h_bb_wrap">
                            <h2 class="mf_h_bb">Calling all Artists, Songwriters, & Producers!</h2>
                            <span class="mf_h_border">
                                <span></span>
                            </span>
                        </div>
                        <p class="dis_chgBanner_stl  m_b_20 outfit">Step into the spotlight and join our Global Showcase Challenge.</p>
                        <p class="dis_chgBanner_stl outfit m_b_30">This is not just a competition, but a platform to challenge your boundaries, showcase your unique talent, and gain worldwide recognition</p>
                        <ul class="dis_chgBanner_list">
                            <li>
                                <a href="<?php echo base_url('spotlight');?>" class="mf_btn" target="_blank">
                                    <span class="mf_btn_text">Visit Website</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://apps.apple.com/in/app/discovered/id1560271435" target="_blank" class="">
                                    <img src="<?php echo base_url('repo/images/challenges/app.png');?>" class="img-responsive">
                                </a>
                            </li>
                            <li>
                                <a href="https://play.google.com/store/apps/details?id=com.discoveredtv&pli=1" target="_blank" class="">
                                    <img src="<?php echo base_url('repo/images/challenges/google.png');?>" class="img-responsive">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dis_chg_sec2_wrap ">
        <div class="container-fluid">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6">
                    <div class="dis_chg_sec2_img d-flex justify-content-center mb_md_50">
                        <img src="<?php echo base_url('repo/images/challenges/sec2_img.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dis_chg_sec2_details">
                        <div class="mf_h_bb_wrap">
                            <h2 class="mf_h_bb">Overview Of Challenge</h2>
                            <span class="mf_h_border">
                                <span></span>
                            </span>
                        </div>
                        <p class="mf_pera mf_sec2_pera">Get Discovered is a global competition designed to showcase the talents of artists, songwriters, and producers. Participants will have the opportunity to submit their work, be judged by industry professionals, and gain exposure on a global platform. The competition aims to discover and promote new talent in the music industry. </p>
                        <p class="mf_pera mf_sec2_pera m_t_30 m_b_30">Get In the mix and upload a short clip showcasing the best of your talent for the chance to get seen and win big. Viewers will vote and the judges will decide!</p>
                        <h1 class="dis_chg_sec2lh m_b_20">Upload Your Video Now!</h1>
                        <ul class="dis_chg_sec2List">
                            <li>
                                <span class="dis_chg_rightText">Global Artists Challenge -</span>
                                <p class="dis_chg_listtext">Upload a short clip showcasing the best of your talent <a href="https://discovered.tv/monetization" target="_blank" class="dis_chg_link">here.</a> <br>
                                    (Original Songs Only, No Covers, No Introductions)</p>
                            </li>
                            <li>
                                <span class="dis_chg_rightText">Global Songwriters Challenge - </span>
                                <p class="dis_chg_listtext">Upload a short clip showcasing the best of your talent <a href="https://discovered.tv/monetization" target="_blank" class="dis_chg_link">here.</a> <br>
                                    (Original Songs Only, No Covers, No Introductions)</p>
                            </li>
                            <li>
                                <span class="dis_chg_rightText">Global Producers Challenge - </span>
                                <p class="dis_chg_listtext">Upload a short clip showcasing the best of your talent <a href="https://discovered.tv/monetization" target="_blank" class="dis_chg_link">here.</a> <br>
                                    (Original Songs Only, No Covers, No Introductions)</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dis_chg_sec3_wrap ">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="dis_chg_heading text-center m_b_40">J. Cole’s Insights On The Challenge</h4>
                    <div class="dis_chg_sec3_video">
                        <div class="dis_chg_sec3_inner">
                            <iframe src="https://discovered.tv/embedcv/4216?autoplay=false&loop=true"  frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dis_chg_sec4_wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="dis_chg_heading text-center m_b_50">Get Discovered Pre-Screening Judges </h4>
                    <ul class="dis_chg_JudgesList">
                        <li class="dis_chg_Judgesbox">
                            <div class="dis_chg_JudgesImg">
                                <img src="<?php echo base_url('repo/images/challenges/dp1.png');?>" class="img-responsive">
                            </div>
                            <div class="dis_chg_JudgesDetails">
                                <div class="mf_h_bb_wrap height2LIne">
                                    <h2 class="mf_h_bb">Jessica Washington</h2>
                                    <span class="mf_h_border">
                                        <span></span>
                                    </span>
                                </div>
                                <p class="dis_chg_Judgesstt">Producer, Songwriter</p>
                            </div>
                        </li>
                        <li class="dis_chg_Judgesbox">
                            <div class="dis_chg_JudgesImg">
                                <img src="<?php echo base_url('repo/images/challenges/dp2.png');?>" class="img-responsive">
                            </div>
                            <div class="dis_chg_JudgesDetails">
                                <div class="mf_h_bb_wrap height2LIne">
                                    <h2 class="mf_h_bb">JoJo Brim</h2>
                                    <span class="mf_h_border">
                                        <span></span>
                                    </span>
                                </div>
                                <p class="dis_chg_Judgesstt">Producer</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="dis_chg_sec5_wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="dis_chg_heading text-center m_b_50">Get Discovered Grand Jurors</h4>
                    <ul class="dis_chg_JurorsList">
                        <li>
                            <div class="dis_chg_Jurorsbox">
                                <div class="dis_chg_JurorsImg">
                                    <img src="<?php echo base_url('repo/images/challenges/dp3.png');?>" class="img-responsive">
                                </div>
                                <div class="dis_chg_JurorsDetails">
                                    <div class="mf_h_bb_wrap height2LIne">
                                        <h2 class="mf_h_bb" style="font-weight: 600;">Terry Lewis</h2>
                                        <span class="mf_h_border">
                                            <span></span>
                                        </span>
                                    </div>
                                    <p class="dis_chg_Jurorsstt">World Famous Producer, Songwriter
                                        5X Grammy Winner</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dis_chg_Jurorsbox">
                                <div class="dis_chg_JurorsImg">
                                    <img src="<?php echo base_url('repo/images/challenges/dp4.png');?>" class="img-responsive">
                                </div>
                                <div class="dis_chg_JurorsDetails">
                                    <div class="mf_h_bb_wrap height2LIne">
                                        <h2 class="mf_h_bb" style="font-weight: 600;">Johnta Austin</h2>
                                        <span class="mf_h_border">
                                            <span></span>
                                        </span>
                                    </div>
                                    <p class="dis_chg_Jurorsstt">Singer, Songwriter, Arranger, Record Producer 2X Grammy Award Winner</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dis_chg_Jurorsbox">
                                <div class="dis_chg_JurorsImg">
                                    <img src="<?php echo base_url('repo/images/challenges/dp5.png');?>" class="img-responsive">
                                </div>
                                <div class="dis_chg_JurorsDetails">
                                    <div class="mf_h_bb_wrap height2LIne">
                                        <h2 class="mf_h_bb" style="font-weight: 600;">Dallas Austin</h2>
                                        <span class="mf_h_border">
                                            <span></span>
                                        </span>
                                    </div>
                                    <p class="dis_chg_Jurorsstt">Prolific Songwriter, Record & Film Producer Grammy Award Winner</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dis_chg_Jurorsbox">
                                <div class="dis_chg_JurorsImg">
                                    <img src="<?php echo base_url('repo/images/challenges/dp6.png');?>" class="img-responsive">
                                </div>
                                <div class="dis_chg_JurorsDetails">
                                    <div class="mf_h_bb_wrap height2LIne">
                                        <h2 class="mf_h_bb" style="font-weight: 600;">Bryan Michael Cox</h2>
                                        <span class="mf_h_border">
                                            <span></span>
                                        </span>
                                    </div>
                                    <p class="dis_chg_Jurorsstt">Prolific Songwriter, and Record Producer Broke Beetles’ Chart Record</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="dis_chg_sec6_wrap ">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="dis_chg_heading text-center m_b_20">‘We Doin’ This’</h4>
                    <p class="dis_chg_para text-center m_b_40 white_color" style="font-weight:500;">Let’s make a mark in the industry together. Win “artist of the month” or be one of the Top Ten creators to <br> headline a Hollywood stage where the Three Grand Prize Winners will be announced.</p>
                    <h1 class="dis_chg_headingbg text-center">Don’t wait, the world is ready to discover you!</h1>
                    <div class="dis_chg_countdown">
                        <ul>
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
    <div class="dis_chg_sec7_wrap">
        <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6 col-md-push-6">
                    <div class="dis_chg_sec7_img d-flex justify-content-center mb_md_50">
                        <img src="<?php echo base_url('repo/images/challenges/mockup.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6 col-md-pull-6">
                    <div class="dis_chg_sec2_details">
                        <h4 class="dis_chg_heading">Contest Rules</h4>
                        <div class="mf_h_bb_wrap">
                            <h2 class="mf_h_bb">Rules For Get Discovered Global Challenge</h2>
                            <span class="mf_h_border">
                                <span></span>
                            </span>
                        </div>
                        <p class="mf_pera mf_sec2_pera ">Artists, Songwriters, Producers Upload a short clip showcasing the best of your talent for the chance to get seen and win big. Viewers will vote and the judges will decide! </p>
                        <p class="mf_pera mf_sec2_pera m_t_30 m_b_30 white_color" style="font-weight:600;">(Original Songs Only, No Covers, No Introductions)</p>
                        <p class="mf_pera mf_sec2_pera m_t_30 m_b_30">General Prize Promotion Terms & Conditions <a href="<?php echo base_url('repo/images/challenges/contestRules.pdf');?>" target="_blank" class="dis_chg_link">See Attached</a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dis_chg_sec8_wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="dis_chg_heading">Official Partner <img src="<?php echo base_url('repo/images/challenges/microsoft_logo.png');?>" class="img-responsive dis_chg_sec8_mic"></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="dis_chg_sec9_wrap">
        <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6">
                    <div class="dis_chg_sec9_img d-flex justify-content-center mb_md_50">
                        <img src="<?php echo base_url('repo/images/challenges/mobile_mockup.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dis_chg_sec2_details">
                        <h4 class="dis_chg_heading">Prizes</h4>
                        <div class="mf_h_bb_wrap">
                            <h2 class="mf_h_bb">Prizes For Challenge Winners</h2>
                            <span class="mf_h_border">
                                <span></span>
                            </span>
                        </div>
                        <p class="mf_pera mf_sec2_pera">IK Multimedia, Universal Audio & A.S.C.A.P. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>











</div>
<script>

(function () {
    const second = 1000,
            minute = second * 60,
            hour = minute * 60,
            day = hour * 24;

    let offer = "august 16, 2024 00:30:00",
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