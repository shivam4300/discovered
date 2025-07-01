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
/* font-family: var(--oswald-font); */

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
.bg1{
    background:#1d1f1e;
}
.mf_main_wrap {
    background: #101111;
}
.dis_default_header, .dis_copyright {
    display:none!important;
}
body {
    padding-bottom: 0!important;
}
.main_contnt_wrapper {
    padding-top: 0!important;
}
.mf_banner_wrap {
    background: url(repo/images/metfest/banner_bg.png) no-repeat;
    text-align: center;
    padding: 10.2% 0;
    background-size: cover;
    position: relative;
    background-position: center bottom;
}
.mf_bnr_logo {
    margin-bottom: 27px;
}
/* sec 2 start */
.mf_sec2_wrap {
    padding: 100px 0 100px;
}
.mf_sec2_heading {
    font-size: 38px;
    font-weight: 600;
    color: #fff;
}
.mf_h_bb {
    font-size: 20px;
    margin-bottom: 16px;
    font-weight: 400;
}
/* .mf_h_bb:after {
    position: absolute;
    bottom: -20px;
    left: 0;
    width: 169px;
    height: 4px;
    content: '';
    background: var(--primary-color);
    transform: skewX(-40deg);
}
.mf_h_bb:before {
    position: absolute;
    bottom: -20px;
    left: 0;
    width: 4px;
    height: 4px;
    content: '';
    z-index: 5;
    background: #101111;
    animation: mover 2s infinite alternate;
    transform: skewX(-40deg);
} */
@-webkit-keyframes mover {
     /* 0% { transform: translateX(-10) skewX(-40deg); }
    100% { transform: translateX(80px) skewX(-40deg); } */
}
@keyframes mover {
    0% { left: 10%; }
    100% { left: 90%; }
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
/* down arrow animations */
.mf_down_arrow {
    display: block;
    height: 58px;
}
.mf_down_arrow > span {
    position: absolute;
    width: 90px;
    height: 27px;
    opacity: 0;
    transform: scale(0.3); 
    animation: move-arrow 3s ease-out infinite;
    left: 0;
    right: 0;
    margin: auto;
}
.mf_down_arrow > span:first-child {
    animation: move-arrow 3s ease-out 1s infinite;
}
.mf_down_arrow > span:nth-child(2) {
    animation: move-arrow 3s ease-out 2s infinite;
}
@keyframes move-arrow {
    25% {
      opacity: 1;
   }
    33.3% {
      opacity: 1;
      transform: translateY(10px);
   }
    66.6% {
      opacity: 1;
      transform: translateY(25px);
   }
    100% {
      opacity: 0;
      transform: translateY(35px) scale(0.5);
   }
 }
 .mf_scroll_arrow p {
    color: #EB5821;
    font-weight: 600;
}
.mf_scroll_arrow {
    display: inline-block;
    cursor: pointer;
    position: absolute;
    bottom: 40px;
    left: 0;
    right: 0;
    margin: auto;
}
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
    padding: 14px 43px;
    border-radius: 5px;
    letter-spacing:0.2px;
}
.mf_btn:hover, .mf_btn:focus, .mf_btn:active {
    color: #fff;
}
.mf_btn_text {
    margin-right: 5px;
}
.mf_sec2_list > li {
    padding: 2px 10px;
}
.mf_sec2_list {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px;
}
/* sec 2 end */
/* sec 3 start */
.mf_sec3_wrap {
    background: url(repo/images/metfest/sec3/partners_bg.png) no-repeat center;
    text-align: center;
    padding: 50px 0px 50px;
    background-size: 100% 100%;
}
.mf_sec3_wrap .container-fluid {    
    max-width: 1750px;
    width: 100%;
}
.container-fluid {
}
/* sec 3 end */
/* sec 4 start */
.mf_sec4_wrap {
    padding: 80px 0;
}
/* sec 4 end */
/* sec 5 start */

.mf_sec5_wrap{
    padding: 90px 0px 80px;
}
.mf_sec5_left {
    width: 94px;
    height: 94px;
    background: linear-gradient(90deg, #EB5821 -1.13%, #FF4545 99.93%);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 5px solid #484a49;
    margin-right: 10px;
    flex: none;
    transition: all 0.3s;
}
.mf_sec5_list {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}
.mf_sec5_list > li {
    background: #323535;
    border-radius: 85px;
    padding: 15px;
    width: calc(33.33% - 30px);
    margin: 15px 15px;
    transition: all 0.3s;
}
.mf_sec5_list > li:nth-child(04), .mf_sec5_list > li:nth-child(05) {
    width: calc(50% - 30px);
}
.mf_sec5_pera {
    color: #fff;
    transition: all 0.3s;
}
.mf_sec5_wrap .container{
    max-width:1530px;
    width: 100%;
}
/* sec 5 end */
/* sec 6 start */
.mf_sec6_wrap {
    padding: 100px 0;
}
.mf_accordion_icon {
    width: 30px;
    height: 30px;
    background: linear-gradient(90deg, #EB5821 -1.13%, #FF4545 99.93%);
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    border: 1.4px solid #444849;
}
.mf_accordion_text {
    font-size: 18px;
    color: #fff;
    margin-left: 10px;
}
.mf_accordion_header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #323535;
    border-radius: 10px;
    padding: 15px 15px;  
}
.mf_accordion_header_L {
    display: flex;
    align-items: center
}
.mf_accordion_body {
    background: #323535;
    border-radius: 10px;
    padding: 15px 15px;
    margin-top: 15px;
}
.mf_sec5_list > li:hover {
    background: #fff;
}
.mf_sec5_list > li:hover .mf_sec5_pera {
    color: #41494C;
}
.mf_sec5_list > li:hover .mf_sec5_left {
    border-color: #fff;
}
/* sec 6 end */
/* sec 7 start */
.mf_sec7_wrap {
    padding: 100px 0;
}
.mf_sec7_list > li {
    display: flex;
    background: #323535;
    border-radius: 10px;
    padding: 18px 20px;
    margin-bottom:20px;
}
.mf_sec7_list > li:last-child, .mf_sec8_t_list > li:last-child {
    margin-bottom:0;
}
.mf_sec7_list_l {
    background: #FFFFFF1A;
    border: 4.3px solid #FFFFFF1A;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 20px;
    flex:none;
}
.mf_sec7_heading {
    font-size: 22px;
    font-weight: 600;
}
.mf_sec7_2_heading {
    font-size: 24px;
    font-weight: 600;
}
.mf_sec7_2_box {
    background: #323535;
    border-radius: 10px;
    padding: 35px 40px;
}

.mf_sec7_img {
    position: relative;
}
.mf_sec_img_child {
    position: absolute;
    bottom: 0;
    -webkit-animation: ani_lr 0.9s linear 0s infinite alternate none running;
    animation: ani_lr 0.9s linear 0s infinite alternate none running;
}
.box1 .mf_sec7_img_child {
    left: -60px;
}
.box2 .mf_sec7_img_child {
    right: 0;
}
@-webkit-keyframes ani_lr {
    0% { transform: translateX(0); }
    100% { transform: translateX(5px); }
}
@keyframes ani_lr {
    0% { transform: translateX(0); }
    100% { transform: translateX(5px); }
}
.mf_sec7_btm{
    padding-top:60px;
}
/* sec 7 end */
/* sec 8 start */
.mf_sec8_wrap {
    padding: 100px 0 100px;
}
.mf_sec8_img.box1 .mf_sec8_img_child{
    right: 0;
    bottom:-74px;
}
.mf_sec8_t_list > li {
    padding-left: 39px;
    position: relative;
    margin-bottom: 8px;
    color: #EB5821;
    font-weight: 600;
}
.mf_sec8_t_list > li:after {
    position: absolute;
    left: 0;
    top:2px;
    content: "";
    background: url(repo/images/metfest/sec8/right.svg) no-repeat;
    background-size: 100%;
    width: 25px;
    height: 20px;
}
.mf_sec8_btm {
    padding-top: 150px;
}
.mf_sec8_b_list > li {
    background: #323535;
    border-radius: 10px;
    padding: 20px 15px;
}
.mf_sec8_b_h {
    padding-left: 39px;
    position: relative;
    font-size: 16px;
    font-weight: 600;
    color: #FDEFE9;
}
.mf_sec8_b_h:after {
    position: absolute;
    left: 0;
    content: "";
    background: url(repo/images/metfest/sec8/w_right.svg) no-repeat;
    background-size: 100%;
    width: 25px;
    height: 20px;
}
.mf_sec8_b_des {
    padding: 10px 0 0 38px;
}
.mf_sec8_b_list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 25px;
}
/* sec 8 end */
/* sec 9 Start */
.mf_sec9_wrap {
    padding: 100px 0;
}
/* sec 9 end */
/* sec 10 start */
.mf_sec10_wrap {
    padding: 100px 0 100px;
}
.mf_sub_heading{
    font-size: 20px;
    font-weight: 400;
    line-height: 1.6;s
}
.mf_sec10_wrap .mf_sec7_list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 30px;
}
.mf_sec10_wrap .mf_sec7_list > li {
    margin-bottom: 0;
}

/* sec 10 end */
/* sec 11 start */
.mf_sec11_wrap {
    padding: 100px 0 100px;
}
.mf_sec11_b_list_l {
    width: 70px;
    height: 70px;
    background: linear-gradient(90deg, #EB5821 -1.13%, #FF4545 99.93%);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 3.7px solid #484a49;
    flex: none;
    transition: all 0.3s;
    margin: 0 auto 15px;
}
.mf_sec11_b_list > li {
    background: #323535;
    border-radius: 10px;
    padding: 20px 20px;
    text-align:center;
}
.mf_sec11_b_list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 15px;
}
.mf_sec11_btm {
    margin-top: 130px;
}
/* sec 11 end */
/* sec 12 start */
.mf_sec12_wrap{
    background: url(repo/images/metfest/sec12/bg.png);
    text-align: center;
    padding: 100px 0 100px;
    background-size: cover;
}
/* sec 12 end */
/* sec 13 start */
.mf_sec13_wrap {
    padding: 100px 0 0px;
}
.mf_sec13_img {
    background-size: auto 100% !important;
    width: 100%;
    height: 70px;
    background: url(repo/images/metfest/sec13/partners.png) repeat-x;
    -webkit-animation: slider_left 30s linear infinite;
    animation: slider_left 30s linear infinite;
}

@keyframes slider_left {
    0% {
      background-position: 0%;
    }
  
    100% {
      background-position: 100%;
    }
}
@-webkit-keyframes slider_right {
    0% {
      background-position: 100%;
    }
  
    100% {
      background-position: 0%;
    }
}
/* sec 13 end */
/* sec 14 start */
.mf_sec14_wrap{
    padding: 100px 0 50px;
}
.mf_sec14_wrap  .mf_sub_heading {
    font-size: 17px;
}
.mf_sec8_t_list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 15px;
}
.mf_shape_box {
    padding: 120px 80px 80px;
    position: relative;
}
.mf_sec14_img{
    padding: 22px 0 30px;
    
}
.mf_shape_doller {
    background: url(repo/images/metfest/sec14/ru_bg.png) no-repeat;
    background-size: 100% 100%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    font-size: 30px;
    font-weight: 800;
    max-width: 408px;
    width: 100%;
    height: 61px;
}
.mf_sec14_inner {
    background: #1D1F1F;
    padding: 50px 0;
    border-radius: 10px;
    max-width: 1530px;
    margin: auto auto 50px;
}
.mf_shape_grid .mf_sec8_t_list {
    grid-template-columns: repeat(1, 1fr);
}
.mf_shape_grid {
    display: flex;
    align-items: center;
}
/* sec 14 end */
/* sec 15 start */
.mf_sec15_wrap.hid {
    padding: 0px 0px 100px;
}
.mf_social_list > li {
    color: #B1CDD7;
    font-size: 18px;
    font-weight: 500;
    display: flex;
    align-items: center;
    padding: 2px 10px;
}
.mf_sec15_list .mf_sl_text{
    color: #B1CDD7;
    margin-left: 8px;
}
.mf_social_list {
    display: flex;
    flex-wrap: wrap;
    margin: 0px -10px;
}
.mf_sec15_list {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap:wrap;
}
.mf_sec15_list > li {
    background: url(repo/images/metfest/sec15/bg.png) no-repeat center;
    background-size: 100% 100%;
    padding: 30px;
    max-width: 490px;
    margin: 0 15px;
}
.mf_sec15_list .mf_sec2_heading {
    font-size: 28px;
}
.mf_sign_list {
    display: flex;
    margin: 0 15px;
}
.mf_sign_list > li {
    padding: 5px 15px;
}
/* sec 15 end */
@media (min-width: 1200px){
._1270_container {
    max-width: 1270px;
    width: 100%;
}
.container {
    max-width: 1270px;
    width: 100%;
}
}
@media (max-width: 1499px){
    .mf_sec2_heading {
        font-size: 32px;
    }
    .mf_h_bb {
        font-size: 18px;
    }
    .mf_sec5_left {
        width: 74px;
        height: 74px;
    }
    .mf_sec5_left > img {
    max-width: 30px;
    }
    .mf_sec5_list > li {
    padding: 8px 15px 8px 8px;
    }
    .mf_sec7_heading {
    font-size: 20px;
    }
    .box1 .mf_sec7_img_child {
    left: 0px;
    }
    .mf_shape_box {
        padding: 120px 50px 80px;
    }
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
    .mf_sec8_t_list > li {
    margin-bottom: 2px;
    }
    .mf_shape_box {
    max-width: 1164px;
    margin:auto;
    }
    .mf_shape_doller {
    max-width: 378px;
    height: 56px;
}


}
@media (max-width: 1199px){
    .mf_bnr_logo > img {
    max-width: 130px;
    width: 100%;
    }
    .mf_bnr_mflogo  > img {
    max-width: 590px;
    width: 100%;
    }
    .mf_banner_wrap {
    padding: 50px 0 100px;
    }
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
    .mf_sec2_heading {
    font-size: 30px;
    }
    .mf_sec5_list > li {
    width: calc(50% - 30px);
    }
    .mf_sec14_wrap .container {
    width: 100%;
    max-width:100%;
    }
    .mf_shape_box {
    padding: 72px 40px 50px;    
    }
    .mf_shape_box {
        max-width: 956px;
    }
    .mf_shape_doller {
        max-width: 310px;
        height: 46px;
    }


}
@media (min-width: 992px){
    .mf_shape_box {
    background: url(repo/images/metfest/sec14/bg.png) no-repeat center;
        background-size: 100% 100%;
    }
    .mf_shape_s_box {
    background: url(repo/images/metfest/sec14/bg_small.png) no-repeat center;
    background-size: 100% 100%;
    padding: 50px 30px;
    }
    .mf_shape_doller_p{
        /* display:none; */
    }
        .mf_shape_doller_p {
        position: absolute;
        left: 0;
        right: 0;
        margin: auto;
        top: 0;
    }
    .mf_shape_grid > .mf_sec14_img {
        width: calc(100% - 80px);
        margin-right: 30px;
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
    .mf_sec5_list > li, .mf_sec5_list > li:nth-child(04), .mf_sec5_list > li:nth-child(05) {
    width: 100%;
    }
    .mf_sec8_img.box1 .mf_sec8_img_child {
    bottom: -24px;
    max-width: 220px;
    }
    .mf_sec10_wrap .mf_sec7_list {
    grid-template-columns: repeat(1, 1fr);
    grid-gap: 15px;
    }
    .mf_shape_doller {
    /* display: inline-flex;
    justify-content: center;
    align-items: center;
    font-size: 30px;
    font-weight: 600;
    width: 100%;
    background: #ca5821;
    padding: 10px;
    max-width: max-content;
    margin: auto;
    text-align: center; */
    }
    .mf_shape_box, .mf_shape_s_box  {
        border: 5px solid #ca5821;
        }
        .mf_shape_box {
        padding: 20px;
        }
        .mf_shape_grid { 
            display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }
    .mf_shape_s_box {
        padding: 20px;
    }
    .mf_sec15_list > li:first-child {
    margin-bottom: 40px;
    }
    .mf_sign_list {
    flex-wrap:wrap;
}


}
@media (min-width: 768px){

}
@media (max-width: 767px){
    .mb_xs_30{
        margin-bottom:30px
    }
    .mb_xs_40{
        margin-bottom:40px
    }
    .mb_xs_50{
        margin-bottom:50px
    }
    .mt_xs_30{
        margin-top:30px
    }
    .mt_xs_40{
        margin-top:40px
    }
    .mt_xs_50{
        margin-top:50px
    }
    .mf_sec2_wrap, .mf_sec4_wrap, .mf_sec5_wrap, .mf_sec6_wrap, .mf_sec7_wrap, .mf_sec8_wrap, .mf_sec9_wrap, .mf_sec10_wrap, .mf_sec11_wrap, .mf_sec12_wrap, .mf_sec14_wrap   {
        padding: 50px 0;
    }
    .mf_sec5_list > li {
    border-radius: 10px;
    padding: 15px;
    margin: 8px 15px;
    }
    .mf_sec5_list {
    margin: 0;
    }
    .mf_sec7_list > li {
    display: inherit;
    padding: 18px 20px;
    margin-bottom: 20px;
    }
    .mf_sec7_list_l {
    margin: 0 0 15px;
    }
     .mf_sec7_img_child {
    max-width: 280px;
    }
    .mf_sec8_b_list {
    grid-template-columns: repeat(1, 1fr);
    grid-gap: 15px;
    }
    .mf_sub_heading {
    font-size: 16px;
    }
    .mf_sec11_b_list {
    grid-template-columns: repeat(1, 1fr);
    }
    .mf_sec8_t_list {
    grid-template-columns: repeat(1, 1fr);
    }
    .mf_sec15_list .mf_sec2_heading {
    font-size: 20px;
    }

}
@media (min-width: 576px){
    .mf_sec5_list > li {
    display: flex;
    align-items: center;
}
}
@media (max-width: 575px){
    .mf_banner_wrap {
    padding: 20px 0 100px;
    }
    .mf_scroll_arrow {
    bottom: 20px;
    }
    .mf_sec5_left {
    margin: 0 0 7px;
    }
    .mf_sec7_list_l {
    width: 60px;
    height: 60px;
    }
    .mf_sec7_list_l > img {
    max-width: 30px;
    }
    .mf_sec15_list .mf_sec2_heading {
    font-size: 18px;
    }
}
@media (max-width: 420px){
    .mf_sec2_heading {
    font-size: 22px;
    }
    .mf_h_bb {
    font-size: 16px;
    }
    .mf_pera, p {
    font-size: 14px;
    }
    .mf_accordion_text {
    font-size: 16px;
    }
    .mf_sec7_heading, .mf_sec7_2_heading {
    font-size: 18px;
    }


}

</style>		
</head>
<div class="mf_main_wrap">
    <div class="mf_banner_wrap text-center hid">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mf_bnr_logo d-flex justify-content-center">
                        <img src="<?php echo base_url('repo/images/metfest/metfest_dis.png');?>" class="img-responsive">
                    </div>
                    <div class="mf_bnr_mflogo  d-flex justify-content-center">
                        <img src="<?php echo base_url('repo/images/metfest/metfest_logo.png');?>" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
        <a class="mf_scroll_arrow" href="#sec2">
            <div class="mf_down_arrow" >
                <span>
                    <img src="<?php echo base_url('repo/images/metfest/down_arrow.svg');?>" alt="icon">
                </span>
                <span>
                    <img src="<?php echo base_url('repo/images/metfest/down_arrow.svg');?>" alt="icon">
                </span>
                <span>
                    <img src="<?php echo base_url('repo/images/metfest/down_arrow.svg');?>" alt="icon">
                </span>
            </div>
            <p>Scroll Down</p>
        </a>
    </div>
    <div class="mf_sec2_wrap hid" id="sec2">
        <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6 col-md-push-6">
                    <div class="mf_sec2_img d-flex justify-content-center mb_md_50">
                        <img src="<?php echo base_url('repo/images/metfest/sec2/img1.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6 col-md-pull-6">
                    <h4 class="mf_sec2_heading"> About Discovered </h4>
                    <div class="mf_h_bb_wrap">
                        <h2 class="mf_h_bb">Gaming’s Fair-trade Platform™</h2>
                        <span class="mf_h_border">
                            <span></span>
                        </span>
                    </div>
                    <p class="mf_pera mf_sec2_pera">Discovered is an NMSDC certified minority-owned, gamified, patented, ad-driven, distribution and streaming platform, that’s home to tens of thousands of cross cultural creators who have over 100 million fans and followers, and 22,000+ pieces of long and short form content. </p>
                    <p class="mf_pera mf_sec2_pera m_t_30 m_b_30">Without Discovered, cross cultural creators have limited monetization opportunities, forcing them to enter broader, lower-paying platforms with deeply conflicted interests and a tendency to overlook rising creators until they reach extreme (oftentimes unattainable) viewership metrics.</p>
                    <p class="mf_pera mf_sec2_pera m_b_30">Discovered leverages the power of gamification to provide NMSDC Corporate Members with a scalable solution that combines their media and advertising ecosystems to drive additional, scalable, incremental revenue from advertising. Additionally, partnering with Discovered, assists NMSDC Corporate members in meeting their supplier diversity and DE&I initiatives at scale.</p>
                    <ul class="mf_sec2_list">
                        <li>
                            <a href="<?php echo base_url('spotlight');?>" class="mf_btn" target="_blank">
                                <span class="mf_btn_text">Visit Discovered </span>
                                <span class="mf_btn_icon"><img src="<?php echo base_url('repo/images/metfest/sec2/arrow.svg');?>" class="img-responsive"></span>
                            </a>
                        </li>
                        <li>
                            <a href="https://apps.apple.com/in/app/discovered/id1560271435" target="_blank" class="">
                                <img src="<?php echo base_url('repo/images/metfest/sec2/app.png');?>" class="img-responsive">
                            </a>
                        </li>
                        <li>
                            <a href="https://play.google.com/store/apps/details?id=com.discoveredtv&pli=1" target="_blank" class="">
                                <img src="<?php echo base_url('repo/images/metfest/sec2/google.png');?>" class="img-responsive">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="mf_sec3_wrap hid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="">
                        <img src="<?php echo base_url('repo/images/metfest/sec3/partners_img.png');?>" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mf_sec4_wrap hid">
        <div class="container _1270_container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6">
                    <div class="mf_sec4_img mb_md_30">
                        <img src="<?php echo base_url('repo/images/metfest/sec4_img.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="mf_sec2_heading"> Overview</h4>
                    <div class="mf_h_bb_wrap">
                        <h2 class="mf_h_bb">Media // Esports // Technology June 1 - 2, 2024 Washington, DC</h2>
                        <span class="mf_h_border">
                            <span></span>
                        </span>
                    </div>
                    <p class="mf_pera mf_sec2_pera"> Presented by Discovered and backed by the City of Washington DC, the mission of MET FEST is to serve as the growth engine that fosters participation and interest in STEM Education, and the Technology / Gaming / Motorsport industries amongst underserved youth throughout the Greater Washington, DC region and throughout the broader community of underserved youth nationally</p>
                    <p class="mf_pera mf_sec2_pera m_t_10 m_b_10">MET FEST features amateur, pro-am, high school, and collegiate esports tournaments centered around the most popular brand safe competitive video games.</p>
                    <p class="mf_pera mf_sec2_pera">MET FEST also features a district wide (DC Public Schools) high school game development / STEM competition and a two-day onsite event-based live stream including video content production and promotion.</p>
                    <p class="mf_pera mf_sec2_pera m_t_10">The piece de resistance and finale occurs Sunday evening June 2nd with an in-game global virtual concert streamed to Discovered’s patented gamified broadcast platform, DCTV (DC Cable Network), and into the venue.</p>
                    
                </div>
            
            </div>
        </div>
    </div>
    <div class="mf_sec5_wrap bg1 hid">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mf_sec2_heading text-center m_b_40">Strategic Partnership Snapshot</h4>
                    <ul class="mf_sec5_list">
                        <li>
                            <div class="mf_sec5_left">
                                <img src="<?php echo base_url('repo/images/metfest/sec5/icon1.svg');?>" class="img-responsive">
                            </div>
                            <div class="mf_sec5_right">
                                <p  class="mf_sec5_pera">Our partners have contributed $700,000 for the MET Fest activation, since September 1, 2023.</p>
                            </div>
                        </li>
                        <li>
                            <div class="mf_sec5_left">
                                <img src="<?php echo base_url('repo/images/metfest/sec5/icon2.svg');?>" class="img-responsive">
                            </div>
                            <div class="mf_sec5_right">
                                <p  class="mf_sec5_pera">In collaboration with our partners and sponsors, Discovered will create bespoke activations to drive ROI.</p>
                            </div>
                        </li>
                        <li>
                            <div class="mf_sec5_left">
                                <img src="<?php echo base_url('repo/images/metfest/sec5/icon3.svg');?>" class="img-responsive">
                            </div>
                            <div class="mf_sec5_right">
                                <p  class="mf_sec5_pera">Discovered XR, powered by Microsoft Azure PlayFab, allows Discovered to target and deliver a brands’ most desired KPIs.</p>
                            </div>
                        </li>
                        <li>
                            <div class="mf_sec5_left">
                                <img src="<?php echo base_url('repo/images/metfest/sec5/icon4.svg');?>" class="img-responsive">
                            </div>
                            <div class="mf_sec5_right">
                                <p  class="mf_sec5_pera">Discovered can target market to the KPIs of the sponsor within the multicultural market using Microsoft Azure PlayFab technology. A key advantage is that our users are all “logged in.”</p>
                            </div>
                        </li>
                        <li>
                            <div class="mf_sec5_left">
                                <img src="<?php echo base_url('repo/images/metfest/sec5/icon5.svg');?>" class="img-responsive">
                            </div>
                            <div class="mf_sec5_right">
                                <p  class="mf_sec5_pera">Through MET FEST, Discovered is delivering the multicultural gaming / esports audience and the traditional esports / gaming audience via our video distribution across our app ecosystem and Microsoft integration.</p>
                            </div>
                        </li>
                    </ul>
                </div>
               
            
            </div>
        </div>
    </div>
    <div class="mf_sec6_wrap hid">
        <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6 mb_md_50">
                    <h4 class="mf_sec2_heading">Featured Esports</h4>
                    <div class="mf_h_bb_wrap">
                        <h2 class="mf_h_bb">Showcasing DC’s Gaming Talent</h2>
                        <span class="mf_h_border">
                            <span></span>
                        </span>
                    </div>
                    <p class="mf_pera mf_sec2_pera m_b_20">MET Fest Esports Tournaments feature high profile, community centric Esport titles, with participants spanning the high school, collegiate, and amateur level; with a focus on esports teams located within the DC / Maryland / Virginia area</p>
                    <div class="mf_sec6_img">
                        <img src="<?php echo base_url('repo/images/metfest/sec6/sec6_img.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-5 col-md-push-1">
                    <div class="mf_sec6_acc">                    
                        <div class="mf_accordion_wrap" id="mf_accordion">
                            <div class="mf_accordion_item m_b_20">                                
                                <a class="mf_accordion_header" data-toggle="collapse" data-parent="#mf_accordion" href="#collapseOne">
                                    <div class="mf_accordion_header_L">
                                        <span class="mf_accordion_icon">
                                            <img src="<?php echo base_url('repo/images/metfest/sec6/game.svg');?>" class="img-responsive">
                                        </span>
                                        <span class="mf_accordion_text">High School Esports Competition</span>
                                    </div>                                      
                                    <div class="mf_accordion_header_R">                                            
                                        <span class="mf_accordion_arrow">
                                            <img src="<?php echo base_url('repo/images/metfest/sec6/acc_arrow.svg');?>" class="img-responsive">
                                        </span>
                                    </div>                                        
                                </a>
                                    
                                
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="mf_accordion_body">
                                        <p class="mf_pera ">MET FEST features a regional high school esports (Super Smash Bros. Ultimate and League of Legends) competition and student activation.</p>
                                    </div>                                    
                                </div>                                    
                            </div>
                            <div class="mf_accordion_item m_b_20">                                
                                <a class="mf_accordion_header" data-toggle="collapse" data-parent="#mf_accordion" href="#collapseTwo">
                                    <div class="mf_accordion_header_L">
                                        <span class="mf_accordion_icon">
                                            <img src="<?php echo base_url('repo/images/metfest/sec6/game.svg');?>" class="img-responsive">
                                        </span>
                                        <span class="mf_accordion_text">Regional Collegiate Esports Competition</span>
                                    </div>                                      
                                    <div class="mf_accordion_header_R">                                            
                                        <span class="mf_accordion_arrow">
                                            <img src="<?php echo base_url('repo/images/metfest/sec6/acc_arrow.svg');?>" class="img-responsive">
                                        </span>
                                    </div>                                        
                                </a>                                   
                                
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="mf_accordion_body">
                                        <p class="">MET FEST features postseason League of Legends and Rocket League esports regional competitions featuring collegiate esports teams from the local area, as well as colleges and universities within our regional partner network.</p>
                                    </div>                                    
                                </div>                                    
                            </div>
                            <div class="mf_accordion_item">                                
                                <a class="mf_accordion_header" data-toggle="collapse" data-parent="#mf_accordion" href="#collapseThree">
                                    <div class="mf_accordion_header_L">
                                        <span class="mf_accordion_icon">
                                            <img src="<?php echo base_url('repo/images/metfest/sec6/game.svg');?>" class="img-responsive">
                                        </span>
                                        <span class="mf_accordion_text">STREAMER PRO-AM</span>
                                    </div>                                      
                                    <div class="mf_accordion_header_R">                                            
                                        <span class="mf_accordion_arrow">
                                            <img src="<?php echo base_url('repo/images/metfest/sec6/acc_arrow.svg');?>" class="img-responsive">
                                        </span>
                                    </div>                                        
                                </a>                                   
                                
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="mf_accordion_body">
                                        <p class="mf_pera">10 D.C. high school Gamers will be paired with 10 professional athletes
                                            from DC local professional teams, including: Washington Commanders,
                                            Washington Wizards, Washington Capitals, DC United, and the
                                            Washington Nationals, in a high profile, high stakes celebrity Fortnite
                                            pro-am tournament with a portion of the proceeds raised being donated
                                            to charity!</p>
                                    </div>                                    
                                </div>                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="mf_sec7_wrap bg1 hid">
        <div class="container _1270_container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-12">
                    <h4 class="mf_sec2_heading text-center m_b_40">Event Components</h4>
                </div>
            </div>
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6">
                    <div class="mf_sec7_img box1 mb_md_50">
                        <img src="<?php echo base_url('repo/images/metfest/sec7/img1.png');?>" class="img-responsive">
                        <img src="<?php echo base_url('repo/images/metfest/sec7/img1_child.png');?>" class="img-responsive mf_sec_img_child mf_sec7_img_child">
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="mf_sec7_list">
                        <li>
                            <div class="mf_sec7_list_l">
                                <img src="<?php echo base_url('repo/images/metfest/sec7/icon1.svg');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_sec7_list_R">
                                <h4 class="mf_sec7_heading m_b_10">Discovered Bot Builders Championship™</h4>
                                <p class="mf_pera mf_sec2_pera">City Wide / District Wide gamified robotics development completion, showcasing DC’s best &  brightest future engineers.</p> 
                            </div>                       
                        </li>
                        <li>
                            <div class="mf_sec7_list_l">
                                <img src="<?php echo base_url('repo/images/metfest/sec7/icon2.svg');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_sec7_list_R">
                                <h4 class="mf_sec7_heading m_b_10">Game Champs Showcase™</h4>
                                <p class="mf_pera mf_sec2_pera">City Wide / District Wide game jam showcasing DC’s most creative future game developers.</p> 
                            </div>                       
                        </li>
                        <li>
                            <div class="mf_sec7_list_l">
                                <img src="<?php echo base_url('repo/images/metfest/sec7/icon3.svg');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_sec7_list_R">
                                <h4 class="mf_sec7_heading m_b_10">Student Innovation Challenge™</h4>
                                <p class="mf_pera mf_sec2_pera">City Wide / District Wide STEM curriculum program and competition featuring racing sim STEM centric activations</p> 
                            </div>                       
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mf_sec7_btm">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-md-6 col-md-push-6">
                        <div class="mf_sec7_img box2 mb_md_50">
                            <img src="<?php echo base_url('repo/images/metfest/sec7/img2.png');?>" class="img-responsive">
                            <img src="<?php echo base_url('repo/images/metfest/sec7/img2_child.png');?>" class="img-responsive mf_sec_img_child mf_sec7_img_child">
                        </div>
                    </div>
                    <div class="col-md-6 col-md-pull-6">
                        <div class="mf_sec7_2_box">
                            <h4 class="mf_sec7_2_heading m_b_20">Pioneers Of In-Game Experiences</h4>                    
                            <p class="mf_pera mf_sec2_pera">From partnering with Epic Games to translate Fortnite’s industry-leading in-game experiences into equally powerful broadcast experiences, to creating some of Roblox’s most defining and most visited in-game experiences, the MET FEST Team has played an integral role in shaping this powerful new medium-with millions of happy players served.</p>
                            <p class="mf_pera mf_sec2_pera m_t_10">In tandem with our partners at Warner Music Group, the MET FEST Team will deliver a headliner in-game Roblox concert. It will be accompanied by purchasable in-game items and post event brand activations, extending the MET FEST experience for your brand and the audience.</p>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mf_sec8_wrap hid">
        <div class="container _1270_container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6">
                    <div class="mf_sec8_img box1 p-re mb_md_50">
                        <img src="<?php echo base_url('repo/images/metfest/sec8/img1.png');?>" class="img-responsive">
                        <img src="<?php echo base_url('repo/images/metfest/sec8/img1_child.png');?>" class="img-responsive mf_sec_img_child mf_sec8_img_child">
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="mf_sec2_heading"> The Venue <br> Entertainment & Sports Arena</h4>
                    <div class="mf_h_bb_wrap">
                        <h2 class="mf_h_bb">1100 Oak Drive SE Washington, DC 20032</h2>
                        <span class="mf_h_border">
                            <span></span>
                        </span>
                    </div>                    
                    <p class="mf_pera mf_sec2_pera m_t_10 m_b_20">The Entertainment and Sports Arena opened in 2018 and is owned and operated by Events DC, Washington DC’s official convention and sports authority. The 4,200 seat arena is home court to WNBA Champions, the Washington Mystics, the NBA G League’s Capital City Go-Go, and the Washington Wizards Training Facility.</p>
                    <ul class="mf_sec8_t_list">
                        <li>80,000 Square Feet of Arena Space</li>
                        <li>4,200 Seat Capacity</li>
                        <li>380,000 New Attendees Annually</li>
                        <li>Metro Accessible (3 Min. Walk to Green Line)</li>
                        <li>Advanced High Speed Internet Connectivity</li>
                        <li>Latest Esports Event Hosted: Blast Premier Spring Final (6/23)</li>
                    </ul>
                </div>            
            </div>
            <div class="mf_sec8_btm">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-md-5 col-md-push-7">
                        <div class="mf_sec8_img mb_md_50">
                            <img src="<?php echo base_url('repo/images/metfest/sec8/img2.png');?>" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-7 col-md-pull-5">
                        <h4 class="mf_sec2_heading"> MET FEST Benefits DC Youth</h4>
                        <div class="mf_h_bb_wrap">
                            <span class="mf_h_border">
                                <span></span>
                            </span>
                        </div>                    
                        <p class="mf_pera mf_sec2_pera m_t_10 m_b_20">Currently, DC Public Schools (home to over 97,000 students [84% Multicultural]), while improving its national standing, is still one of the most underserved school districts in the country-across the STEM spectrum. MET Fest is a cornerstone, annual initiative backed by the government of the City of DC to address this inequity</p>
                        <ul class="mf_sec8_b_list">
                            <li>
                                <h1 class="mf_sec8_b_h">Incentivized STEM Activities</h1>
                                <p class="mf_sec8_b_des">Students are required to maintain a predefined GPA in order to participate in MET FEST’s STEM Centric Activations.</p>
                            </li>
                            <li>
                                <h1 class="mf_sec8_b_h">Youth Culture Activation</h1>
                                <p class="mf_sec8_b_des">The City of DC is keenly interested in establishing a premiere youth culture activation for students and young adults. MET Fest is that activation.</p>
                            </li>
                            <li>
                                <h1 class="mf_sec8_b_h">Local Economic Impact</h1>
                                <p class="mf_sec8_b_des">Esports competitions have shown their power to create positive economic impact in the local community. MET Fest has been structured to follow the same pathway of success as its predecessors.</p>
                            </li>
                            <li>
                                <h1 class="mf_sec8_b_h">Industry Access</h1>
                                <p class="mf_sec8_b_des">Via our sponsors’ and publisher partners’ onsite activation teams, industry panels and industry exhibits, DC's underserved youth population will be ensured access to the best and brightest minds across Esports / Gaming / Music / Technology.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mf_sec9_wrap bg1 hid">
        <div class="container _1270_container">            
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6">
                    <div class="mf_sec9_img mb_md_50">
                        <img src="<?php echo base_url('repo/images/metfest/sec9/img.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="mf_sec2_heading"> Corporate Partners Matter</h4>
                    <div class="mf_h_bb_wrap">
                        <h2 class="mf_h_bb">Help MET FEST Strengthen America’s Underserved Youth</h2>
                        <span class="mf_h_border">
                            <span></span>
                        </span>
                    </div>
                    <p class="mf_pera mf_sec2_pera"> NMSDC Corporate Members thoroughly understand the importance of positively and actively shaping the supplier diversity landscape, and correcting the unequal access to wealth-building opportunities that systematically excluded communities of color lack.</p>
                    <p class="mf_pera mf_sec2_pera m_t_10 m_b_10">MET FEST allows NMSDC Corporate Members to address these inequities by deploying strategic investments into an organized system and initiative that provides youth from excluded communities of color with: much needed access to fun and exciting skill based activations that require students to hone the STEM skills they develop during the school year, STEM centric career track building activations, in addition to deploying a youth centric economic engine that not only provides students with an income, but also equips them with the financial literacy needed to make sound financial decisions as they prepare to enter adulthood.</p>
                    <p class="mf_pera mf_sec2_pera">Additionally, by becoming a MET FEST partner, NMSDC Corporate Members and their respective brands will receive unparalleled access and bespoke activations that will reach MET FEST's broad audience, encompassing: students (and their families), educators, the global esports and gaming community of fans and consumers, esports and gaming corporations, local and national media outlets, local governmental officials, members of the U.S. Congress, the Greater Washington, DC area business community, and socially responsible general market consumers within the Greater Washington, DC region and beyond.</p>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="mf_sec10_wrap hid">
        <div class="container _1270_container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mf_sub_heading text-center m_b_10">MET FEST Addressable Market Overview</h4>
                    <h4 class="mf_sec2_heading text-center m_b_40">National Capital Region Consumer Market</h4>
                </div>
                <div class="col-md-12">
                    <ul class="mf_sec7_list">
                        <li>
                            <div class="mf_sec7_list_l">
                                <img src="<?php echo base_url('repo/images/metfest/sec10/icon1.svg');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_sec7_list_R">
                                <h4 class="mf_sec7_heading m_b_10">$540 Billion Economy</h4>
                                <p class="mf_pera mf_sec2_pera">Greater Washington, DC is the 6th largest U.S. region, with a $540 Billion burgeoning regional economy-making it the fourth largest economy in the U.S., and eighth largest in the world.</p> 
                            </div>                       
                        </li>
                        <li>
                            <div class="mf_sec7_list_l">
                                <img src="<?php echo base_url('repo/images/metfest/sec10/icon2.svg');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_sec7_list_R">
                                <h4 class="mf_sec7_heading m_b_10">6 Million Residents</h4>
                                <p class="mf_pera mf_sec2_pera">Home to more than 6 million people, the Greater Washington, DC region is anticipating more than one million new residents over the next 25 years. Nearly half of the population is under the age of 44.</p> 
                            </div>                       
                        </li>
                        <li>
                            <div class="mf_sec7_list_l">
                                <img src="<?php echo base_url('repo/images/metfest/sec10/icon3.svg');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_sec7_list_R">
                                <h4 class="mf_sec7_heading m_b_10">Over 600,000 Students</h4>
                                <p class="mf_pera mf_sec2_pera">Over 560,000 public school students and their parents (predominantly multicultural), and over 100,000 collegiate students, reside in the Greater Washington, DC area.</p> 
                            </div>                       
                        </li>
                        <li>
                            <div class="mf_sec7_list_l">
                                <img src="<?php echo base_url('repo/images/metfest/sec10/icon4.svg');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_sec7_list_R">
                                <h4 class="mf_sec7_heading m_b_10">$117,000 Median HHI</h4>
                                <p class="mf_pera mf_sec2_pera">The median household income for the Greater Washington, DC metro area is $117,000.</p> 
                            </div>                       
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
    <div class="mf_sec11_wrap bg1 hid">
        <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-5 col-md-push-7">
                    <div class="mf_sec11_img mb_md_50">                    
                        <img src="<?php echo base_url('repo/images/metfest/sec11/img.png');?>" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6 col-md-pull-5">
                    <h4 class="mf_sec2_heading">Global Live Stream Distribution</h4>
                    <div class="mf_h_bb_wrap">
                        <h2 class="mf_h_bb">Connecting With Like Minded Fans Around The World</h2>
                        <span class="mf_h_border">
                            <span></span>
                        </span>
                    </div>
                    <p class="mf_pera mf_sec2_pera">The MET FEST on-site live stream will be broadcasted for two days to fans around the world through the web, Samsung Smart TVs, Roku, Android TV, and on Discovered’s Android Mobile and Apple iOS apps.</p>                    
                    <p class="mf_pera mf_sec2_pera m_t_20 m_b_50">The live stream video will be archived and redistributed through Microsoft Surfaces, DCTV, and Discovered TV with companion articles for extended brand activation until MET Fest 2025. Our partners at Office of Cable Television Film and Entertainment Discovered will be producing a TV series about the event, the kids and the schools to highlight the Social Impact of the event. This will further the brand reach for our partners for an additional 12-months after the conclusion of the event.</p>
                    <div class="mf_sec6_img">
                        <img src="<?php echo base_url('repo/images/metfest/sec11/logo.png');?>" class="img-responsive">
                    </div>
                </div>
            </div>
            <div class="mf_sec11_btm">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-lg-6 mb_lg_50">
                        <ul class="mf_sec11_b_list">                    
                            <li>
                                <div class="mf_sec11_b_list_l">
                                    <img src="<?php echo base_url('repo/images/metfest/sec11/icon1.png');?>" class="img-responsive"> 
                                </div>
                                <div class="mf_sec11_b_list_R">
                                    <p class="mf_pera mf_sec2_pera">Content
                                    Distribution
                                    to Over
                                    1.6 Billion
                                    Microsoft Surface Devices</p> 
                                </div>                       
                            </li>
                            <li>
                                <div class="mf_sec11_b_list_l">
                                    <img src="<?php echo base_url('repo/images/metfest/sec11/icon2.png');?>" class="img-responsive"> 
                                </div>
                                <div class="mf_sec11_b_list_R">
                                    <p class="mf_pera mf_sec2_pera">Proprietary Microsoft AI and CHAT GPT Content Integration</p> 
                                </div>                       
                            </li>
                            <li>
                                <div class="mf_sec11_b_list_l">
                                    <img src="<?php echo base_url('repo/images/metfest/sec11/icon3.png');?>" class="img-responsive"> 
                                </div>
                                <div class="mf_sec11_b_list_R">
                                    <p class="mf_pera mf_sec2_pera">MSN Strategic Content Integration (Bing, MS Start, MS Watch, MS Windows, MS Edge, Shopping/ eCommerce)</p> 
                                </div>                       
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="mf_sec2_heading">Digital Distribution Footprint</h4>
                        <div class="mf_h_bb_wrap">
                            <h2 class="mf_h_bb">Bringing MET FEST To The Global Esports Audience Through Microsoft</h2>
                            <span class="mf_h_border">
                                <span></span>
                            </span>
                        </div>
                        <p class="mf_pera mf_sec2_pera">Discovered and Microsoft have partnered to create a more inclusive media and entertainment ecosystem for multicultural creators globally. Our partnership positions Microsoft and your brand to capture and scale positive brand equity within the global multicultural creator community. This positions your brand’s investment in MET Fest as a preeminent creator of a global digital environment and much needed safe space for multicultural content creators and creative businesses</p>  
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <div class="mf_sec12_wrap hid">
        <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-12">
                    <h4 class="mf_sec2_heading text-center m_b_10">Experience In The Game</h4>
                    <h4 class="mf_sub_heading text-center m_b_40">The MET Fest team and partners work with the world’s leading esports publishers, tournament organizers, and competitive organizations to create, manage, and operate events and experiences worthy of fandom.</h4>
                    <div class="mf_sec12_img">
                        <img src="<?php echo base_url('repo/images/metfest/sec12/logos.png');?>" class="img-responsive">
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <div class="mf_sec13_wrap hid">
        <div class="container">
            <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-12">
                    <h4 class="mf_sec2_heading text-center m_b_10">Event Partners</h4>
                    <h4 class="mf_sub_heading text-center m_b_40">MET Fest is presented by Discovered and other world-class partners to deliver a one-of-a-kind experience to the youth within the Greater Washington, DC area. In support of this event, The City of DC has committed $700,000 in cash and in-kind services as the first Founder’s Club Sponsor for the MET Fest activation.</h4>
                </div>
            </div>  
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mf_sec13_img">
                        <!-- <img src="<?php echo base_url('repo/images/metfest/sec13/partners.png');?>" class="img-responsive">  -->
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <div class="mf_sec14_wrap hid">
        <div class="mf_sec14_inner">
            <div class="container">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-md-12">
                        <div class="mf_sponsorship_box">
                            <h4 class="mf_sec2_heading text-center m_b_20">MET FEST Sponsorship Opportunities</h4>
                            <h4 class="mf_sub_heading text-center">As the highest-tier sponsor, the Title Sponsorship with MET FEST 2024 provides maximum exposure to connect your brand across all aspects of the weekend event. <br> <br> This tier includes Category Exclusivity. First Right of Refusal for years 2-5*. <br> <br> (*3-year commitment preferred)</h4>
                            <div class="mf_sec14_img d-flex justify-content-center ">
                                <img src="<?php echo base_url('repo/images/metfest/sec14/title1.png');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_shape_box">
                                <div class="mf_shape_doller_p text-center mb_md_30">
                                    <h2 class="mf_shape_doller">$500,000</h2>
                                </div>
                                <ul class="mf_sec8_t_list">
                                    <li>The brand name in the wordmark such as “The ( Company) MET FEST”</li>
                                    <li>Corporate / Brand Logo placement on Team Jerseys</li>
                                    <li>Event Logo Lock</li>
                                    <li>On-site Brand Presence Within Partner Corridor</li>
                                    <li>Inclusion in Industry Leaders Summit Hosted By City of DC</li>
                                    <li>Brand Integrated Into Virtual Concert Environment</li>
                                    <li>Brand Featured In Student / Parent Collateral, Messaging (DMV Public Schools)</li>
                                    <li>Dedicated Brand Segment Featured In MET FEST Docuseries</li>
                                    <li>Sponsor mentions and Announcements during all event activities.</li>                            
                                    <li>Retail Tie-ins with exposure and incentives to purchase products or services</li>                           
                                    <li>Product/Brand giveaways and Brand Ambassadors on-location</li>
                                    <li>Logo Included in Tournament Trophy Design</li>
                                    <li>Direct Branded Integration into Microsoft Surfaces, MSN (300 Million Visitors Per Day)</li>
                                    <li>Twenty (20) VIP Weekend Event passes for tournament viewing and all event activities.</li>
                                    <li>Logo On Collateral Materials, Tickets, Credentials, Merchandise, interactive screens, indoor / outdoor GOBO signage, all digital assets (social, hyperlink on official website, etc.)</li>
                                    <li>34.2M guaranteed Impressions through Discovered internal and external advertising channels: Display, Pre/Mid-Roll, Native + Social (Total Media Value $700K)</li>
                                    <li>Logo placement and advertising during live streams, behind-the-scenes, and after event videos online.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <div class="mf_sec14_inner">
            <div class="container">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-md-12">
                        <div class="mf_sponsorship_box">
                            <h4 class="mf_sec2_heading text-center m_b_20">MET FEST Sponsorship Opportunities</h4>
                            <h4 class="mf_sub_heading text-center">Presenting Sponsors of the 2024 MET FEST are provided with the maximum exposure to connect your brand across all aspects of the weekend event. <br> <br> This tier includes Category Exclusivity. First Right of Refusal for years 2-5*. <br> <br> (*3-year commitment preferred)</h4>
                            <div class="mf_sec14_img d-flex justify-content-center ">
                                <img src="<?php echo base_url('repo/images/metfest/sec14/title2.png');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_shape_box">
                                <div class="mf_shape_doller_p text-center mb_md_30">
                                    <h2 class="mf_shape_doller">$350,000</h2>
                                </div>
                                <ul class="mf_sec8_t_list">
                                    <li>The brand name included in event title as “The MET FEST 2024 Presented By (Your Company”)</li>
                                    <li>Direct Branded Integration into Microsoft Surfaces, MSN (300 Million Visitors Per Day)</li>
                                    <li>Logo placement and advertising during live streams, behind-the-scenes, and after event videos online.</li>
                                    <li>Fifteen (15) VIP Weekend Event passes for tournament viewing and all event activities.</li>
                                    <li>Logo On Collateral Materials, Tickets, Credentials, Merchandise, interactive screens, indoor / outdoor GOBO signage, all digital assets (social, hyperlink on official website, etc.)</li>
                                    <li>25.6M guaranteed Impressions through Discovered internal and external advertising channels: Display, Pre/Mid-Roll, Native + Social (Total Media Value $525K)</li>
                                    <li>Brand Featured In Student / Parent Collateral, Messaging (DMV Public Schools)</li>
                                    <li>Retail Tie-ins with exposure and incentives to purchase products or services</li>
                                    <li>Event Logo Lock</li>
                                    <li>On-site Brand Presence Within Partner Corridor</li>
                                    <li>Brand Integrated Into Virtual Concert Environment</li>
                                    <li>Dedicated Brand Segment Featured In MET FEST Docuseries</li>
                                    <li>Sponsor mentions and Announcements during all event activities.</li>
                                    <li>Inclusion in Industry Leaders Summit Hosted By City of DC</li>
                                    <li>Corporate / Brand Logo placement on Team Jerseys</li>
                                    <li>Product/Brand giveaways on-location</li>                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
        <div class="mf_sec14_inner">
            <div class="container">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-md-12">
                        <div class="mf_sponsorship_box">
                            <h4 class="mf_sec2_heading text-center m_b_20">MET FEST Sponsorship Opportunities</h4>
                            <h4 class="mf_sub_heading text-center">As the In-Game Title Sponsorship for MET FEST 2024 your brand will gain exposure and be connected with viewers around the world, as we deliver a unique and engaging experience during the live virtual concert. <br> <br> This tier includes Category Exclusivity. First Right of Refusal for years 2-5*. <br> <br> (*3-year commitment preferred)</h4>
                            <div class="mf_sec14_img d-flex justify-content-center ">
                                <img src="<?php echo base_url('repo/images/metfest/sec14/title3.png');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_shape_box">
                                <div class="mf_shape_doller_p text-center mb_md_30">
                                    <h2 class="mf_shape_doller">$250,000</h2>
                                </div>
                                <ul class="mf_sec8_t_list">
                                    <li>Logo On Collateral Materials, Tickets, Credentials, Merchandise, interactive screens, indoor / outdoor GOBO signage, all digital assets (social, hyperlink on official website, etc.)</li>
                                    <li>The brand name included in event title as “The MET FEST 2024 Presented By (Your Company”).</li>
                                    <li>Logo placement and advertising during live streams, behind-the-scenes, and after event videos online.</li>
                                    <li>Direct Branded Integration into Microsoft Surfaces, MSN (300 Million Visitors Per Day)</li>
                                    <li>17.1M guaranteed Impressions through Discovered internal and external advertising channels: Display, Pre/Mid-Roll, Native + Social (Total Media Value $350K)</li>
                                    <li>Brand Featured In Student / Parent Collateral, Messaging (DMV Public Schools)</li>
                                    <li>Retail Tie-ins with exposure and incentives to purchase products or services</li>
                                    <li>Fifteen (15) VIP Weekend Event passes for tournament viewing and all event activities.</li>
                                    <li>Sponsor mentions and Announcements during all event activities.</li>
                                    <li>Dedicated Brand Segment Featured In MET FEST Docuseries</li>
                                    <li>Inclusion in Industry Leaders Summit Hosted By City of DC</li>
                                    <li>Brand Integrated Into Virtual Concert Environment</li>
                                    <li>On-site Brand Presence Within Partner Corridor</li>
                                    <li>Product/Brand giveaways on-location</li>
                                    <li>Event Logo Lock</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> 
                
                    
            </div>
        </div>
        <div class="mf_sec14_inner">
            <div class="container">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-md-12">
                        <div class="mf_sponsorship_box">
                            <h4 class="mf_sec2_heading text-center m_b_20">MET FEST Sponsorship Opportunities</h4>
                            <h4 class="mf_sub_heading text-center">As a Founder’s Club Sponsor of the MET FEST 2024 your brand will have access to deliver bespoke experiences in collaboration <br> with Discovered and your brand team. <br> <br> This tier includes Category Exclusivity.</h4>
                            <div class="mf_sec14_img d-flex justify-content-center ">
                                <img src="<?php echo base_url('repo/images/metfest/sec14/title4.png');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_shape_box">
                                <div class="mf_shape_doller_p text-center mb_md_30">
                                    <h2 class="mf_shape_doller">$200,000</h2>
                                </div>
                                <ul class="mf_sec8_t_list">
                                    <li>13.7M guaranteed Impressions through Discovered internal and external advertising channels: Display, Pre/Mid-Roll, Native + Social (Total Media Value $280K)</li>
                                    <li>Logo On Collateral Materials, Tickets, Credentials, Merchandise, interactive screens, indoor / outdoor GOBO signage, all digital assets (social, hyperlink on official website, etc.)</li>
                                    <li>Logo placement and advertising during live streams, behind-the-scenes, and after event videos online.</li>
                                    <li>The brand name included in event title as “The MET FEST 2024 Presented By (Your Company”).</li>
                                    <li>Direct Branded Integration into Microsoft Surfaces, MSN (300 Million Visitors Per Day)</li>
                                    <li>Ten (10) VIP Weekend Event passes for tournament viewing and all event activities.</li>
                                    <li>Brand Featured In Student / Parent Collateral, Messaging (DMV Public Schools)</li>
                                    <li>Sponsor mentions and Announcements during all event activities.</li>
                                    <li>Dedicated Brand Segment Featured In MET FEST Docuseries</li>
                                    <li>Inclusion in Industry Leaders Summit Hosted By City of DC</li>
                                    <li>Brand Integrated Into Virtual Concert Environment</li>
                                    <li>On-site Brand Presence Within Partner Corridor</li>
                                    <li>Corporate / Brand Logo placement on Team Jerseys</li>
                                    <li>Product/Brand giveaways on-location</li>
                                    <li>Event Logo Lock</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <div class="mf_sec14_inner">
            <div class="container">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-md-12">
                        <div class="mf_sponsorship_box">
                            <h4 class="mf_sec2_heading text-center m_b_20">MET FEST Sponsorship Opportunities</h4>
                            <h4 class="mf_sub_heading text-center">As MET FEST 2024 Supporting Sponsor of the MET FEST 2024 your brand will have access to deliver bespoke experiences in collaboration with Discovered and your brand team.</h4>
                            <div class="mf_sec14_img d-flex justify-content-center ">
                                <img src="<?php echo base_url('repo/images/metfest/sec14/title5.png');?>" class="img-responsive"> 
                            </div>
                            <div class="mf_shape_box">
                                <div class="mf_shape_doller_p text-center mb_md_30">
                                    <h2 class="mf_shape_doller">$100,000</h2>
                                </div>
                                <ul class="mf_sec8_t_list">
                                    <li>Logo On Collateral Materials, Tickets, Credentials, Merchandise, interactive screens, indoor / outdoor GOBO signage, all digital assets (social, hyperlink on official website, etc.)</li>
                                    <li>6.8M guaranteed Impressions through Discovered internal and external advertising channels: Display, Pre/Mid-Roll, Native + Social (Total Media Value $140K)</li>
                                    <li>Logo placement and advertising during live streams, behind-the-scenes, and after event videos online.</li>
                                    <li>The brand name included in event title as “The MET FEST 2024 Presented By (Your Company”).</li>
                                    <li>Direct Branded Integration into Microsoft Surfaces, MSN (300 Million Visitors Per Day)</li>
                                    <li>Ten (10) VIP Weekend Event passes for tournament viewing and all event activities.</li>
                                    <li>Brand Featured In Student / Parent Collateral, Messaging (DMV Public Schools)</li>
                                    <li>Sponsor mentions and Announcements during all event activities.</li>
                                    <li>Inclusion in Industry Leaders Summit Hosted By City of DC</li>
                                    <li>On-site Brand Presence Within Partner Corridor</li>
                                    <li>Corporate / Brand Logo placement on Team Jerseys</li>
                                    <li>Product/Brand giveaways on-location</li>
                                    <li>Event Logo Lock</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <div class="mf_sec14_inner">
            <div class="container">
                <div class="row d-flex flex-wrap align-items-center">
                    <div class="col-md-12">
                        <div class="mf_sponsorship_box">
                            <h4 class="mf_sec2_heading text-center m_b_20">MET FEST Sponsorship Opportunities</h4>
                            <h4 class="mf_sub_heading text-center m_b_50">As MET FEST 2024 Supporting Sponsor of the MET FEST 2024 your brand will have access to deliver bespoke experiences in collaboration with Discovered and your brand team.</h4>
                            <div class="mf_shape_grid">
                                <div class="mf_sec14_img ">
                                    <img src="<?php echo base_url('repo/images/metfest/sec14/title7.png');?>" class="img-responsive"> 
                                    <img src="<?php echo base_url('repo/images/metfest/sec14/title8.png');?>" class="img-responsive"> 
                                    <img src="<?php echo base_url('repo/images/metfest/sec14/title9.png');?>" class="img-responsive"> 
                                </div>
                                <div class="mf_shape_s_box ">
                                    <ul class="mf_sec8_t_list">
                                        <li>Logo On Collateral Materials, Tickets, Credentials, Merchandise, interactive screens, indoor / outdoor GOBO signage, all digital assets (social, hyperlink on official website, etc.)</li>
                                        <li>3.4M guaranteed Impressions through Discovered internal and external advertising channels: Display, Pre/Mid-Roll, Native + Social (Total Media Value $70K)</li>
                                        <li>Logo placement and advertising during live streams, behind-the-scenes, and after event videos online.</li>
                                        <li>Direct Branded Integration into Microsoft Surfaces, MSN (300 Million Visitors Per Day)</li>
                                        <li>Brand Featured In Student / Parent Collateral, Messaging (DMV Public Schools)</li>
                                        <li>Inclusion in Industry Leaders Summit Hosted By City of DC</li>
                                        <li>On-site Brand Presence Within Partner Corridor</li>
                                        <li>Product/Brand giveaways on-location</li>
                                        <li>Category Exclusivity</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <div class="mf_sec15_wrap hid">        
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mf_sec2_heading text-center m_b_50">Contact Us</h4>
                    <ul class="mf_sec15_list">
                        <li>                            
                            <h4 class="mf_sec2_heading">Dustin Mack</h4>
                            <div class="mf_h_bb_wrap">
                                <h2 class="mf_h_bb">SVP Gaming & Business Development</h2>
                                <span class="mf_h_border">
                                    <span></span>
                                </span>
                            </div>
                            <ul class="mf_social_list">
                                <li>
                                    <span class="mf_sl_icon"><img src="<?php echo base_url('repo/images/metfest/sec15/mail.svg');?>" class="img-responsive"></span>
                                    <a href="mailto:dustin@discovered.tv" class="mf_sl_text">dustin@discovered.tv</a>
                                </li>
                                <li>
                                    <span class="mf_sl_icon"><img src="<?php echo base_url('repo/images/metfest/sec15/phone.svg');?>" class="img-responsive"></span>
                                    <a href="tel:+1 678-634-6284" class="mf_sl_text">+1 678-634-6284</a>
                                </li>
                            </ul>
                        </li>
                        <li>                            
                            <h4 class="mf_sec2_heading">Jessica Washington</h4>
                            <div class="mf_h_bb_wrap">
                                <h2 class="mf_h_bb">VP Of Content Development</h2>
                                <span class="mf_h_border">
                                    <span></span>
                                </span>
                            </div>
                            <ul class="mf_social_list">
                                <li>
                                    <span class="mf_sl_icon"><img src="<?php echo base_url('repo/images/metfest/sec15/mail.svg');?>" class="img-responsive"></span>
                                    <a href="mailto:jessica@discovered.tv" class="mf_sl_text">jessica@discovered.tv</a>
                                </li>
                                <li>
                                    <span class="mf_sl_icon"><img src="<?php echo base_url('repo/images/metfest/sec15/phone.svg');?>" class="img-responsive"></span>
                                    <a href="tel:+1 702-712-3072" class="mf_sl_text">+1 702-712-3072</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="mf_bnr_mflogo  d-flex justify-content-center m_t_50">
                        <img src="<?php echo base_url('repo/images/metfest/metfest_logo.png');?>" class="img-responsive">
                    </div>
                    <ul class="mf_sign_list">
                        <li>
                            <img src="<?php echo base_url('repo/images/metfest/sec15/sign2.jpg');?>" class="img-responsive">
                        </li>
                        <li>
                            <img src="<?php echo base_url('repo/images/metfest/sec15/sign1.jpg');?>" class="img-responsive">
                        </li>
                    </ul>
                </div>
            </div> 
        </div>
    </div>






 



</div>
<script>
    // <script type="text/javascript" src="https://discovered.tv/repo/js/jquery.js"></script>
    <!-- <script type="text/javascript" src="https://discovered.tv/repo/js/bootstrap.min.js"></script> -->
</script>