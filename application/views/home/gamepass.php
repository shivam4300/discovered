<style>
.dis_gamepassPage {
    background: var(--main_bg_color);
    padding: 50px 0;
    color: #fff;
}
.dis_redeemHeding {
    font-size: 26px;
    font-weight: 700;
    margin: 0 0 20px;
}
.dis_redeemSub {
    font-size: 18px;
}
.dis_redeemAlready .primary_link {
    font-weight: bold;
}
.dis_redeembtm {
    font-style: italic;
    font-size: 14px;
}
.dis_redeemLeft img{
    animation: changeImage 15s infinite; /* The animation runs every 15s */
}
.dis_redeemRight {
    width: calc(100% - 430px);
    margin-left: 30px;
}
.dis_redeemRight .dis_btn {
    min-width: inherit;
    padding: 0 30px;
    margin:27px 0 26px;
}
.dis_GP_passWrap {
    border: 1px solid rgb(71, 85, 93);
    border-radius: 12px;
    background-color: rgb(3, 5, 7);

    overflow: hidden;
}
.dis_GP_passHeader {
    background-image: -moz-linear-gradient(180deg, rgb(3, 5, 7) 0%, rgb(63, 74, 87) 51%, rgb(3, 5, 7) 100%);
    background-image: -webkit-linear-gradient(180deg, rgb(3, 5, 7) 0%, rgb(63, 74, 87) 51%, rgb(3, 5, 7) 100%);
    background-image: -ms-linear-gradient(180deg, rgb(3, 5, 7) 0%, rgb(63, 74, 87) 51%, rgb(3, 5, 7) 100%);
    padding: 10px 10px;
    display: flex;
    justify-content: center;
}
.dis_GP_passBody {
    padding: 20px;
    text-align: center;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}
.dis_GP_quality {
    font-size: 22px;
    text-transform: uppercase;
    font-weight: 900;
}
.dis_GP_quality > span {
    margin-top: 6px;
    color: var(--primary_color);
    display: block;
    font-size: 30px;
}
.dis_GPLeftRight, .dis_gpsc_right {
    width: calc(100% - 724px);
    margin-left: 30px;
}
.dis_GPLeft, .dis_gpsc_left {
    width: 685px;
}
.dis_gamepassWrap {
    padding: 40px 0 35px;
}
.dis_GP_trial {
    margin: 17px 0 7px;
}
.dis_GPLeft iframe {
    border-style: solid;
    border-width: 1px;
    border-color: rgb(71, 85, 93);
    border-radius: 12px;
    background-color: rgb(3, 5, 7);
}
.dis_gamepassFooterText > span {
    font-weight: 700;
}
.dis_gamepassFooterText >   a {
    color:#ffffff;
}
.dis_gpaReg_model .modal-body {
    padding: 40px;
}
.dis_gpaReg_model .dis_btn {
    padding: 0px 20px;
}
.dis_gpaReg_model {
    z-index: 999999999;
}
.dis_gamepassFooterSec {
    display: flex;
}
.dis_field_label {
    font-weight: 700;
    font-size:20px;
}
.theme_dark .dis_field_label {
    color: #fff;
    font-weight: 600;
}
.theme_dark .dis_btn, .theme_dark .dis_btn:hover {
    color: #fff;
    background: #eb581f;
    border: 1px solid #eb581f;
}
.theme_dark .dis_field_input {
    border-color: rgb(96 98 98);
}

@keyframes changeImage {
            0% {
                background-image: url('image1.jpg');
            }
            33% {
                background-image: url('image2.jpg');
            }
            66% {
                background-image: url('image3.jpg');
            }
            100% {
                background-image: url('image1.jpg');
            }
        }
@media (min-width: 768px) {
    .dis_redeemWrap {
    display: flex;
    align-items: center;
}
.dis_redeemLeft {
    width: 400px;
}
}
@media (min-width: 992px) {
.dis_gamepassWrap {
    display: flex;
}
}
@media (max-width: 1199px) {
.dis_GPLeftRight, .dis_gpsc_right {
    width: calc(100% - 610px);
    margin-left: 10px;
}
.dis_GPLeft, .dis_gpsc_left {
    width: 600px;
}
.dis_GP_quality {
    margin: 8px 0 0;
}
.dis_redeemRight {
    width: calc(100% - 420px);
    margin-left: 20px;
}
.dis_redeemRight .dis_btn {
    margin: 15px 0 15px;
}
.dis_redeemHeding {
    font-size: 22px;
    margin: 0 0 15px;
}
.dis_gp_notice h1 br{
    display:none;
}

}
@media (max-width: 991px) {
.dis_GPLeftRight, .dis_gpsc_right {
    margin-left: 0;
    max-width: 400px;
    width: 100%;
    margin: 20px auto;
}
.dis_GPLeft, .dis_gpsc_left {
    width: 100%;
}
.dis_gamepassFooterSec {
    flex-direction: column-reverse;
}

}
@media (max-width: 767px) {
    .dis_redeemRight {
        width: 100%;
        margin: 20px 0 0;
    }
    .dis_gamepassWrap {
        padding: 20px 0 10px;
    }
}
.dis_gp_notice {
    background: var(--main_bg_color);
    color: #fff;
    text-align: center;
}
.dis_gp_noticeInner {
    padding: 10px 5px;
    background: var(--sec_bg_color);
}
.dis_gp_notice h1 {
    font-size: 18px;
    line-height: 1.3;
    margin: 0;
    font-weight: 700;
}
.dis_gp_notice p {
    font-size: 22px;
    color:var(--text_color);
}
@media (max-width:480px) {
.dis_gp_notice h1, .dis_gp_notice p {
    font-size: 15px;
}
}
</style>
<!--div class="dis_gp_notice">
    <div class="dis_gp_noticeInner">
        <h1>We ran into an unexpected issue during our maintenance and need to take the campaign offline to fix it. <br> We'll be back up and running soon! Thank you for your patience</h1-->
        <!-- <p>Thank you for your patience</p> -->
    <!--/div>
</div-->
<div class="dis_gamepassPage">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="top_ads"></div>
                <div class="dis_redeemWrap">
                    <div class="dis_redeemLeft">
                        <img src="<?php echo base_url('repo/images/gamepass/image1.jpg');?>" class="img-responsive" id="changeImg">
                    </div>
                    <!--div class="dis_redeemRight">
                        <h1 class="dis_redeemHeding">Redeem your free trial</h1>
                        <p class="dis_redeemSub">PC Game Pass is designed for PC players. Get new games day one, play some of the biggest games of the year, or try out franchises you’ve always been curious about.</p>
                        <a href="<?=base_url('sign-up?gamepass=1');?>" class="dis_btn">Sign Up To Redeem</a>

                        <p class="dis_redeemAlready">Or  <a class="primary_link" href="javascript:;" data-toggle="modal" data-target="#gamepass_email_popup" data-backdrop="static" data-keyboard="false"> Click Here </a> if you are already registered on Discovered</p>
                        <p class="dis_redeembtm">Subscription continues automatically at the regular monthly price unless canceled through your Microsoft Account. <a class="primary_link underline" href="https://www.xbox.com/en-US/legal/subscription-terms" target="_blank"> See terms. </a></p>
                    </div-->
                    <div class="dis_redeemRight">
                        <h1 class="dis_redeemHeding">🎉 Congratulations, Gamers! 🎉</h1>
                        <p class="dis_redeemSub">Thank you for signing up for our PC Game Pass trial giveaway! Get ready to dive into an epic world of gaming with access to a vast library of amazing titles. Whether you're into action, adventure, strategy, or indie gems, there's something for everyone.</p>

                        <p class="dis_redeemAlready">Stay tuned for more updates and happy gaming! 🎮🚀</p>
                        <p class="dis_redeembtm">#GameOn #PCGamePass #LevelUp</p>
                    </div>
                </div>
                <div class="dis_gamepassWrap">
                    <div class="dis_GPLeft">
                         <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://discovered.tv/embedcv/3331971?controls=true&autoplay=true&muted=false&loop=true"  frameborder="0" allow="autoplay" allowfullscreen="true" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Vidintrux Teaser"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
                    </div>
                    <div class="dis_GPLeftRight">
                        <div class="dis_GP_passWrap">
                            <div class="dis_GP_passHeader">
                                <img src="<?php echo base_url('repo/images/gamepass/passlogo.png');?>" class="img-responsive">
                            </div>
                            <div class="dis_GP_passBody">
                                <h1 class="dis_GP_quality">Play high quality <span> PC GAMEs </span></h1>
                                <img src="<?php echo base_url('repo/images/gamepass/pass_trial.png');?>" class="img-responsive dis_GP_trial">
                                <h1 class="dis_GP_quality">PC Game Pass On Us!</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dis_gamepassFooterSec">

                    <div class="dis_gpsc_left">
                        <p class="dis_gamepassFooterText"><span>(PARTNER TERMS).</span> 
                        Redeem at <a href="https://www.xbox.com/promotions/game-pass-offer/partner-trial" target="_blank" class="underline"> https://www.xbox.com/promotions/game-pass-offer/partner-trial </a> Valid for new Xbox Game Pass members only. 
                        Valid payment method required. Unless you cancel, you will be charged the then-current regular membership rate when the promotional 
                        period ends. Limit: 1 per person/account. Game Pass code can be redeemed in United States, Canada, and United Kingdom.</p>
                    </div>
                    <div class="dis_gpsc_right">
                        <div id="side_ads"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade dis_gpaReg_model dis_center_modal" id="gamepass_email_popup" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
        <div class="modal-body">
            <form id="gamepass_email_form">
                <div class="dis_field_box m_b_30">
                    <label class="dis_field_label m_b_10">Validate your email</label>
                    <div class="dis_field_wrap">
                        <input type="text" class="dis_field_input" placeholder="Enter Your Email" name="validate_email" id="validate_email">
                        <span class="form-error help-block"></span>
                    </div>
                </div>
                <div class="dis_field_box m_b_30">
                        <div id="gamepass-recaptcha"></div>
                </div>
                <a href="javascript:;" class="dis_btn b-r-5" id="gamepass_form_submit">Get Free PC GamePass Trial  </a>
            </form>
        </div>
      </div>
    </div>
</div>


 <!-- PHP Block that passes image paths to JS -->
<script>
    window.addEventListener("load", (event) => {
        AddSheMediaAdsOnSingleArticleOnTheTop('#top_ads', 'html');
        addSheMediaAdsOnSingleVideoOnTheTopRight('#side_ads', 'html');
    });
    var images = [
        '<?php echo base_url("repo/images/gamepass/image1.jpg"); ?>',
        '<?php echo base_url("repo/images/gamepass/image2.jpg"); ?>'
    ];

</script>

<script src="path/to/custom.js"></script>
