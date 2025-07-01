</div>  <!-- start from header end --> <!---main_contnt_wrapper end- (Header page) ----->



<?php $is_login	=  is_login(); ?>
<?php if(!isset($noFooter)){ ?>

	<div class="clearfix"></div>

	<div class="dis_copyright">
		<div class="dis_footerClose" data-toggle="dropdown">
			<span class="dis_footerCloseBg">
				<svg width="21" height="86" viewBox="0 0 21 86" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M14.5262 79.8652H9.64692C8.11617 79.8652 6.63326 80.4098 5.46925 81.3869L0 86V0L5.43736 4.61315C6.60137 5.60626 8.08428 6.15087 9.61503 6.15087H14.5103C18.098 6.15087 21 9.06612 21 12.6701V73.3619C21 76.9659 18.098 79.8812 14.5103 79.8812L14.5262 79.8652Z" fill="#1c1c1c"></path>
				</svg>
			</span>
			<span class="dis_footerCloseIcon">
				<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M0.816739 5.53223L6.40857 0.192204C6.53715 0.0694712 6.71182 0 6.89376 0C7.07571 0 7.25038 0.0671555 7.37895 0.192204L7.79137 0.585874C8.06307 0.840602 8.06307 1.25743 7.79379 1.51216L3.09714 5.99537L7.79865 10.4855C7.92722 10.6083 8 10.775 8 10.9487C8 11.1223 7.92965 11.2891 7.79865 11.4141L7.38623 11.8078C7.25766 11.9305 7.08299 12 6.90104 12C6.71909 12 6.54443 11.9328 6.41585 11.8078L0.816739 6.46314C0.688163 6.33809 0.615385 6.17368 0.615385 5.99768C0.615385 5.82169 0.685737 5.65496 0.816739 5.53223Z" fill="#ffffff"></path>
				</svg>
			</span>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="dis_copyright_inner">
						<div class="dis_footer_links copyright_box">
							<a href="<?php echo base_url('policies'); ?>">policies</a>
							<a href="<?php echo base_url('terms-and-privacy'); ?>">Terms</a>
							<a clas="dis_footerHelp" href="<?php echo base_url('help'); ?>">Help</a>
							<!-- <a href="<?php echo base_url('giveaways'); ?>">Giveaways</a> -->
							<a href="<?php echo base_url('/home/about'); ?>">About Us</a>
							<a href="javascript:;" class="common_click c_tb">Theme</a>
							<?php if($is_login){ ?>
							<a href="<?php echo base_url('support'); ?>">Support</a>
						<?php }else{ ?>
							<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl" onclick="OpenRoute('support')">Support</a>

						<?php } ?>
							<a href="https://mailchi.mp/discovered/discovered-newsletters" target="_blank">Newsletters</a>
						</div>
						<div class="copyright_box">
							<div class="au_bottom_footer">
								<p>Copyright &copy; <?php echo date('Y'); ?> <a href="<?php echo base_url() ?>">Discovered USA, Inc. </a> , All Rights Reserved.</p>
							</div>
						</div>

						<div class="copyright_box">
							<div class="au_social_icon">
								<ul>
									<li><a href="https://www.facebook.com/DiscoveredUSA" class="facebook" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
									<li><a href="https://twitter.com/Discoveredtv1" class="twitter" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>
									<li><a href="https://www.instagram.com/discoveredglobal/" class="instagram" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
									<li><a href="https://www.linkedin.com/company/discovered-tv" class="linkedin" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } ?>

</div> <!--audition_main_wrapper end (Header page)----->

<div style="display: none;" id="popup_chat" class="dis_SB_C_chatwrap dis_C_chatwrap dis_userchat_wrap">
	<section class="dis_sc_head_inner">
		<div class="dis_sc_head" id="data_for_msging" data-friend="OLD" data-id="" data-user_uname="" data-key="" data-full_name="">
			<div class="dis_chatHL" >
				<div class="dis_sc_headImg" >
					<img id="profile_img_chat_section" height="40" src="" alt="">
				</div>
				<a href="#" id="single_chat_title" class="dis_sc_topttl"></a>
			</div>
			<div  class="dis_chatHR">
				<span id="close_popup_chat" class="dis_cross_sign"></span>
			</div>

		</div>
		<div id="single_chat_messages" class="dis_sc_message_area">

		</div>
		<div class="dis_streamchat_sendbox">
			<form class="sendMessage">
				<button class="dis_streamchat_sendIcon">
					<img src="<?= base_url('repo/images/send_img.png');?>" class="img-responsive" alt="send">
				</button>
				<textarea class="write_msg" cols="30" rows="1" placeholder="Write a message..."></textarea>
				<div class="emoji_picker _EmojiPicker" data-target="#chat_msg_emoji" data-textarea=".write_msg">
					<img class="" src="<?=base_url(); ?>repo/images/emoji/emoji.svg" alt="smile svg">
				</div>

				<span id="chat_msg_emoji" class="hide"></span>
			</form>
		</div>
	</section>
</div>

<!--App download popup start-->
<div class="dis_common_popup app_download">
	<div class="common_popup_inner">
		<h4 class="popup_heading">Download App</h4>
		<div class="app_download_inner">
			<div class="app_download_btnwrap">
				<a href="https://play.google.com/store/apps/details?id=com.discoveredtv" class="" target="_blank">
					<span class="app_download_icon">
						<img src="<?= CDN_BASE_URL ;?>repo/images/googleplay_white.webp" class="app_download_light" onError="ImageOnLoadError(this,'<?= CDN_BASE_URL ;?>repo/images/googleplay_white.png')" width="179px" height="53px">
						<img src="<?= CDN_BASE_URL ;?>repo/images/googleplay_dark.webp" class="app_download_dark" onError="ImageOnLoadError(this,'<?= CDN_BASE_URL ;?>repo/images/googleplay_dark.png')" width="179px" height="53px">
					</span>
				</a>
				<a href="https://apps.apple.com/in/app/discovered/id1560271435" class="" target="_blank">
					<span class="app_download_icon">
						<img src="<?= CDN_BASE_URL ;?>repo/images/app_white.webp" class="app_download_light" onError="ImageOnLoadError(this,'<?= CDN_BASE_URL ;?>repo/images/app_white.png')" width="179px" height="53px">
						<img src="<?= CDN_BASE_URL ;?>repo/images/app_dark.webp" class="app_download_dark" onError="ImageOnLoadError(this,'<?= CDN_BASE_URL ;?>repo/images/app_dark.png')" width="179px" height="53px">
					</span>
				</a>
			</div>
		</div>
		<span class="common_close">
			<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" width="11px" height="11px"><g><path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#EB581F"/></g> </svg>
		</span>
	</div>
</div>

<!--theme toggle popup start-->
<div class="dis_common_popup dark_popup">
	<div class="common_popup_inner sharepost_inner">
		<h4 class="popup_heading">Theme Options</h4>
		<div class="switcher_main_wrapper">
			<h4>Light</h4>
			<div class="">
				<input type="checkbox" class="switcher_input" id="dw_switcher"/>
					<label for="dw_switcher" class="toggle">
					<span class="toggle__handler">
					<span class="crater crater--1"></span>
					<span class="crater crater--2"></span>
					<span class="crater crater--3"></span>
					</span>
					<span class="star star--1"></span>
					<span class="star star--2"></span>
					<span class="star star--3"></span>
					<span class="star star--4"></span>
					<span class="star star--5"></span>
					<span class="star star--6"></span>
					<span class="bg_efft"></span>
				</label>
			</div>
			<h4>Dark</h4>
		</div>
		<span class="common_close">
			<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" width="11px" height="11px"><g><path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#EB581F"/></g> </svg>
		</span>
	</div>
</div>
<!--theme toggle popup start-->

<!-- Common Modal Start -->
<div class="modal fade" id="myCommonModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		</div>
	</div>
</div>
<!-- Common Modal End -->

<!-- Confirmation Pop Ups -->
<div class="modal fade Audition_popup common_popup"  id="confirm_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" id="conf_header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-exclamation" aria-hidden="true"></i></h4>
			</div>
			<div class="modal-body">
				<div class="common_popup_wrapper">
					<p id="conf_text" class="conf_text"></p>
					<div class="btn_wrapper">
						<a class="popup_btn" id="conf_btn">Yes</a>
						<a class="popup_btn close_popup" data-parent="confirm_popup">Close</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include('share_popup.php'); ?>
<script type="text/javascript" src="<?= CDN_BASE_URL ;?>repo/js/jquery.js"></script>
<script type="text/javascript" src="<?= CDN_BASE_URL ;?>repo/js/bootstrap.min.js"></script>
<?php

if(isset($page_info['page'])){

echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo_admin/js/valid.js"></script>';

if(!in_array($page_info['page'],array('store_single_page','article_mode'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/jquery-ui.min.js"></script>';
}

if(in_array($page_info['page'],array('store_single_page','article_mode','single_blog'))){
	echo '<script async type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.1.3/swiper-bundle.min.js"></script>';
}
if(in_array($page_info['page'],array('about'))){
	echo ' <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>';
}

if(in_array($page_info['page'],array('blogs'))){
	echo '<script defer type="text/javascript" src="'.CDN_BASE_URL.'repo/js/jquery-ui-touch-punch.min.js"></script>';
}

if(in_array($page_info['page'],array('store_single_page','article_mode','single_blog','single_video'))){
	echo '<script defer type="text/javascript" src="'.CDN_BASE_URL.'repo/js/plugin/moment/moment.min.js"></script>';
}

if(in_array($page_info['page'],array('store_payment'))){
	echo '<script type="text/javascript" src="https://js.braintreegateway.com/web/3.85.3/js/client.min.js"></script>';
	echo '<script type="text/javascript" src="https://js.braintreegateway.com/web/3.85.3/js/paypal-checkout.min.js"></script>';
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/braintree.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('my_channel','homepage','all_genre','all_category','single_genre','single_category','dashboard','store','my_shop','my_playlist','store_single_page'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/plugin/swiper/swiper.min.js"></script>';
}
if(in_array($page_info['page'],array('dashboard','blogs','single_blog','about'))){
	echo '<script defer src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>'; //4.13.0
}
if(in_array($page_info['page'],array('dashboard','store_single_page','socketchat','my_channel','my_playlist'))){
	echo '<script defer type="text/javascript" src="'.CDN_BASE_URL.'repo/js/plugin/magnific_popup/jquery.magnific-popup.min.js"></script>';
}

if(in_array($page_info['page'],array('single_video','dashboard','single_publish_post','my_channel','single_blog'))){
	echo '<script type="text/javascript" src="https://imasdk.googleapis.com/js/sdkloader/ima3.js"></script>';
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/player/videojs_v8.6.1.min.js"></script>';
	if(in_array($page_info['page'],array('single_video','dashboard','single_blog'))){
		echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/player/videojs.ads.min.js"></script>';
		echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/player/videojs.ima.min.js"></script>';
		echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/player/videojs-contrib-quality-levels-v4.0.0.min.js"></script>';
		echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/player/videojs-http-source-selector.min.js"></script>';
		echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>';
	}
}

if(in_array($page_info['page'],array('dashboard','single_publish_post'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/player/videojs-share.min.js"></script>';
}

if(in_array($page_info['page'],array('dashboard','my_channel','setting'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/cropper.min.js"></script>';
}

if(in_array($page_info['page'],array('dashboard','single_publish_post','setting','mutual_friends','my_channel','my_playlist'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/dashboard.js"></script>';
	// echo '<script type="text/javascript" src="'.base_url('repo/js/chat_messages.js?q='.time()).'"></script>';
}



if(in_array($page_info['page'],array('search','help'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/search.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('homepage','primary_type','setting','playlist','videoelephant','ViewThread','dashboard','Dashboard','upload_official_video','store','store_single_page','store_payment','mutual_friends','my_shop','single_blog','blogs','Support','firebase_chat'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo_admin/js/select2.min.js"></script>';
}
if(in_array($page_info['page'],array('videoelephant'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/videoelephanta.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('setting','streaming','Dashboard','setting','ViewThread','socketchat'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/plugin/moment/moment.min.js"></script>';
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/daterangepicker.js"></script>';
}
if(in_array($page_info['page'],array('streaming'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/chartist.min.js"></script>';
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/chartist-plugin-tooltip.min.js"></script>';
}

if(!in_array($page_info['page'],array('single_video','homepage'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/code_custom.js?q='.VRSN.'"></script>';
}

echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.5.0/socket.io.min.js" ></script>';

if( $is_login ){
	if(in_array($page_info['page'],array('socketchat'))){
		echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/socketchat.js?q='.VRSN.'"></script>';
	}

}


echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/common.js?q='.VRSN.'"></script>';

if(in_array($page_info['page'],array('single_video'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/player/player_live_initialize.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('homepage','my_channel','playlist','single_video','upload_official_video','all_genre','all_category','single_genre','single_category','search','watchAll','my_playlist'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/backend/playlist.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('upload_official_video','streaming','blogs'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/bootstrap-tokenfield.min.js"></script>';
}

if(in_array($page_info['page'],array('single_video','upload_official_video','streaming'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/channel_video_upload.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('blogs','single_blog','article_mode'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/article.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('upload_official_video','dashboard','socketchat','my_channel'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/aws/sthree.js?q='.VRSN.'"></script>';
	if(in_array($page_info['page'],array('upload_official_video')))
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/aws/bulksthree.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('streaming'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/streaming.js?q='.VRSN.'"></script>';
}




if(in_array($page_info['page'],array('primary_type','setting','playlist','videoelephant','ViewThread','Dashboard','upload_official_video','store','store_single_page','store_payment','mutual_friends','my_shop'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/backend/store.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('Support','ViewThread','Dashboard','TicketSingle','SupportLogin','ticketDetails'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/support.js?q='.VRSN.'"></script>';
}

if(in_array($page_info['page'],array('Dashboard'))){
	echo '<script src="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
		  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
		  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>';
}

if($is_login && isset($page_info['page']) && $page_info['page'] == 'firebase_chat'){
	echo '<script type="text/javascript" src="'.BASE_URL.'repo/js/firebase_chat.js?q='.VRSN.'"></script>';
}

if($is_login && isset($page_info['page']) && $page_info['page'] != 'firebase_chat'){
	echo '<script type="text/javascript" src="'.BASE_URL.'repo/js/firebase_notification.js?q='.VRSN.'"></script>';
}


}

if(!$is_login || (isset($page_info['page']) && $page_info['page'] == 'giveaways') ){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/auth.js?q='.VRSN.'"></script>';
	echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
}

	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/custom.js?q='.VRSN.'"></script>';
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/share.js?q='.VRSN.'"></script>';
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/ads.js?q='.VRSN.'"></script>';

if(in_array($page_info['page'],array('homepage','store','dashboard','all_genre','all_category','single_genre','single_category'))){
	echo '<script type="text/javascript" src="'.CDN_BASE_URL.'repo/js/homepage.js?q='.VRSN.'"></script>';
}
?>

<div id="gam-services"></div>
<div id="gam-badges-root"></div>

<script>
$(function() {
	$('.publish_btn').addClass('hideme');
		$("#custom_files").change(function(){
			var input = document.getElementById('custom_files');
				if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#myimage').attr('src', e.target.result);
					$('#imgBar').show();
					$('#removebrowse').hide();
					$('.dis_custom_row').find('h2').text(input.files[0].name);
				}
				reader.readAsDataURL(input.files[0]);
			}
		});

		$(document).on('click','.remove_thumb_img',function(){
			 $('#imgBar').hide();
			$('#removebrowse').show();
			$('#custom_files').val('');
			$('.dis_custom_row').find('h2').text('');
		})




});
</script>

<script>
	window.APP_CONFIG = {
		BASE_PATH: '/',
		AS_STACK_APP_ID: "<?php echo PLAYFAB_XR_APP_ID ?>",
		PLAYFAB_APP_ID: "<?php echo PLAYFAB_TITLE_ID ?>",
		XR_API: "<?php echo PLAYFAB_XR_URI ?>",
		REALTIME_API: "<?php echo REALTIME_URI ?>",
	};
</script>

<script src="<?= CDN_BASE_URL ;?>repo/gamification/assets/built/js/gamification.js?q=<?= VRSN ;?>" type="text/javascript" />



<script type="text/javascript">
window._taboola = window._taboola || [];
_taboola.push({flush: true});
</script>

<!-- Quantcast Tag -->
<script type="text/javascript">
window._qevents = window._qevents || [];

(function() {
var elem = document.createElement('script');
elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
elem.async = true;
elem.type = "text/javascript";
var scpt = document.getElementsByTagName('script')[0];
scpt.parentNode.insertBefore(elem, scpt);
})();

window._qevents.push({
qacct:"p-w1DCtHTX2bfBB",
uid:"__INSERT_EMAIL_HERE__"
});
</script>

<noscript>
<div style="display:none;">
	<img src="//pixel.quantserve.com/pixel/p-w1DCtHTX2bfBB.gif" border="0" height="1" width="1" alt="Quantcast"/>
</div>
</noscript>
<!-- End Quantcast tag -->

<!--- UNDERDOGMEDIA EDGE_discovered.tv JavaScript ADCODE START--->
<!-- <script data-cfasync="false" language="javascript" async src="https://udmserve.net/udm/img.fetch?sid=15222;tid=1;dt=6;"></script> -->
<!--- UNDERDOGMEDIA EDGE_discovered.tv JavaScript ADCODE END--->

<script src="https://cdn.jsdelivr.net/gh/givingproject/give-tapp-js@latest/give-tapp.js"></script>
<script>GiveTapp.giveTapp()</script>
</body>
</html>
