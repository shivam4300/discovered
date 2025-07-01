<!DOCTYPE html>
<html lang="en">
<!-- Header Start -->
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	<?php
	defined('BASE_URL')      	OR define('BASE_URL', base_url() );
	$pageTitle 				= 	isset($page_info['title'])? $page_info['title'] : ' A globally connected digital platform & social network.';
	$siteName 				= 	PROJECT;

	$metaTitle 				= 	PROJECT . ' - A globally connected digital platform & social network.';
	$metaDescription 		= 	'A globally connected digital platform and social network that generates revenue for filmmakers, musicians, and content creators.';
	$metaImage 				=  	CDN_BASE_URL. 'repo/images/discovered-logo.png';
	$metaEmbed 				=  	 '';
	?>

	<?php
	$uid 					= 	'';
	$header_menu 			= 	$this->audition_functions->get_header_menu();

	$get_user_fullname 		= 	'Someone';
	$user_uname 	   		= 	'';
	$user_pic 				=   '';
	$user_email 			=   '';
	$is_login				=   is_login();
	if($is_login) {
		$uid 				= 	$is_login;
		$cuser 				= 	get_user($uid);
		$get_user_fullname 	=	(isset($cuser[0]['user_name']) && !empty($cuser[0]['user_name']))? $cuser[0]['user_name'] : '';
		$user_uname 		=	(isset($cuser[0]['user_uname']) && !empty($cuser[0]['user_uname']))? $cuser[0]['user_uname'] : '';
		$user_page_image 	=	(isset($cuser[0]['uc_pic']) && !empty($cuser[0]['uc_pic']))?create_upic($uid,$cuser[0]['uc_pic']) : '';
		$user_pic 			=	(isset($cuser[0]['uc_pic']) && !empty($cuser[0]['uc_pic']))? $cuser[0]['uc_pic'] : '';
		$user_email 		= 	(isset($_SESSION['user_email']) && !empty($_SESSION['user_email']))? $_SESSION['user_email'] : '';
	}
	if(isset($metaData)){
		$pageTitle 			= 	$metaTitle = !empty($metaData['title'])?$metaData['title'] : $pageTitle;
		$metaDescription 	= 	!empty($metaData['description'])?$metaData['description'] : $metaDescription;
		$metaImage 			= 	!empty($metaData['image'])?$metaData['image'] : $metaImage ;
		$metaEmbed 			= 	!empty($metaData['embed'])?$metaData['embed'] : '' ;
	}

	$token 					= 	(isset($_SESSION['token']) && !empty($_SESSION['token']))? $_SESSION['token'] :'';
	$account_type 			= 	(isset($_SESSION['account_type']) && !empty($_SESSION['account_type']))? $_SESSION['account_type'] :'';

	$website_mode			=	$homepage_url = BASE_URL;
	
	if(isset($_SESSION['website_mode']['name'])){
		$website_mode 		= $_SESSION['website_mode']['name'];
		if($is_login){
			$homepage_url 		= BASE_URL. $website_mode ;
			if($website_mode == 'social'){
				$homepage_url 	=  BASE_URL . 'profile?user=social' ;
			}
		}
	}
	?>
	<input type="hidden" id="defaultmode" value="<?= $website_mode; ?>">

	<title><?php echo PROJECT . ' | '. $pageTitle; ?></title>
	<meta name="msvalidate.01" content="4C7844BE5D683AEE91378E9028593E3C" />
	<meta name="author" content="<?php echo $pageTitle; ?>">

	<meta name="MobileOptimized" content="320">
	<meta name="title" content="<?php echo $metaTitle; ?>">
	<meta name="description" content="<?php echo htmlentities($metaDescription); ?>">
	<meta name="image" content="<?php echo $metaImage; ?>">
	<meta name="site" content="<?php echo $siteName; ?>">

	<meta property="og:site_name" content="<?php echo $siteName; ?>" >
	<meta property="og:title" content="<?php echo $metaTitle; ?>">
	<meta property="og:description" content="<?php echo htmlentities($metaDescription); ?>">
	<meta property="og:image" content="<?php echo $metaImage; ?>">
	<meta property="og:image:secure_url" content="<?php echo dirname($metaImage); ?>" />
	<meta property="og:image:width" content="640" />
	<meta property="og:image:height" content="442" />
	<meta property="og:url" content="<?= current_url().'?'.$_SERVER['QUERY_STRING']; ?>">
	<meta property="fb:app_id" content="338023836397118">
	<meta property="og:type" content="video.movie"/>
	<meta name="monetag" content="c941e97ab49d7595e5f3fcd772329193">
	<meta name="google-site-verification" content="OK9YK3mVEe3F4-Owglcy9hP7rnWzyFwtMV-ztfbCGVM" />

	<?php if(!empty($metaEmbed)){ ?>

	<meta property="og:video" content="<?= $metaEmbed; ?>"/>
	<meta property="og:video:url" content="<?= $metaEmbed; ?>"/>
	<meta property="og:video:secure_url" content="<?= $metaEmbed; ?>"/>
	<meta property="og:video:width" content="315"/>
	<meta property="og:video:height" content="560"/>
	<meta property="og:video:type" content="application/x-shockwave-flash"/>

	<meta name="twitter:card" content="player"></meta>
	<meta name="twitter:player" content="<?= $metaEmbed; ?>"></meta>
	<meta name="twitter:player:height" content="315"></meta>
	<meta name="twitter:player:width" content="560"></meta>
	<meta name="twitter:secureurl:player_url" content="<?= $metaEmbed; ?>"></meta>
	<meta name="twitter::text:player_height" content="315"></meta>
	<meta name="twitter::text:player_width" content="560"></meta>
	<?php }else{ ?>
		<meta name="twitter:card" content="summary"></meta>
	<?php } ?>

	<meta name="twitter:title" content="<?php echo $metaTitle; ?>">
	<meta name="twitter:description" content="<?php echo htmlentities($metaDescription); ?>">
	<meta name="twitter:image" content="<?php echo $metaImage; ?>">
	<meta name="twitter:site" content="<?php echo $siteName; ?>">
	<meta name="twitter:creator" content="<?php echo $siteName; ?>">
	<input type="hidden" id='account_owner' value="<?= $get_user_fullname; ?>">
	<meta charset="UTF-8">
	<link rel="shortcut icon" type="image/png" href="<?= CDN_BASE_URL .'repo/images/favicon.png' ;?>" />

	<link href="https://fonts.googleapis.com/css?family=Muli:200,300,400,600,700,800,900" rel="stylesheet">
	<link href="https://use.typekit.net/bal4nht.css" type="text/css" rel="stylesheet">
	<link href="<?= CDN_BASE_URL ;?>repo/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<!-- <link href="<?= BASE_URL ;?>repo/css/font-awesome.css" rel="stylesheet" type="text/css" /> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<?php if(isset($page_info['page'])){?>

		<?php if(in_array($page_info['page'],array('blogs','single_blog','article_mode'))){  ?>
			<link href="<?= CDN_BASE_URL ;?>repo/css/articles.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>
		<?php if(in_array($page_info['page'],array('Support','TicketSingle','ViewThread','Dashboard','SupportLogin'))){  ?>
			<link href="<?= CDN_BASE_URL ;?>repo/css/support.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>
		<?php if(in_array($page_info['page'],array('policies','terms_privacy'))){  ?>
			<link href="<?= CDN_BASE_URL ;?>repo/css/term_policy.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>
		<?php if(in_array($page_info['page'], ['single_video']) ){ ?>
			<link defer href="<?= CDN_BASE_URL ;?>repo/css/single_video.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>
		<?php if(in_array($page_info['page'], ['my_shop','store','store_single_page','store_payment', 'shop']) ){ ?>
			<link defer href="<?= CDN_BASE_URL ;?>repo/css/store_mode.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>
		<?php if(in_array($page_info['page'], ['socketchat','firebase_chat']) ){ ?>
			<link defer href="<?= CDN_BASE_URL ;?>repo/css/socket_chat.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>
		<?php if(in_array($page_info['page'],array('store_single_page','article_mode','my_channel','dashboard','my_playlist'))){  ?>
			<link defer href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" type="text/css" />
		<?php } ?>
		<?php if(in_array($page_info['page'],array('store_single_page','article_mode','my_channel','dashboard','my_playlist'))){  ?>
			<link defer href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.1.3/swiper-bundle.min.css" rel="stylesheet" type="text/css" />
		<?php } ?>
		<?php if(in_array($page_info['page'],array('about'))){  ?>
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
		<?php } ?>

		<?php if(in_array($page_info['page'],array('my_channel','homepage','all_genre','all_category','single_genre','single_category','dashboard','store','my_shop','store_single_page'))){  ?>
			<link defer href="<?= CDN_BASE_URL ;?>repo/js/plugin/swiper/swiper.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>

		<?php if(in_array($page_info['page'],array('socketchat'))){ ?>
			<link defer href="<?= CDN_BASE_URL ;?>repo/js/plugin/magnific_popup/magnific-popup.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>

		<?php if(in_array($page_info['page'], ['dashboard','setting', 'my_channel']) ){ ?>
		<link defer href="<?= CDN_BASE_URL ;?>repo/css/cropper.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>

		<?php if(in_array($page_info['page'], ['upload_official_video','dashboard','single_blog']) ){ ?>
		<link defer href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet">
		<link defer href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.css" rel="stylesheet" type="text/css" />
		<!--script src="//imasdk.googleapis.com/pal/sdkloader/pal.js"></script-->
		<?php } ?>

		<?php if(in_array($page_info['page'], ['blogs']) ){ ?>
			<link rel="stylesheet" href="<?= base_url('repo_admin/plugins/js/jqueryUi/jquery-ui.min.css'); ?>">
			<link defer href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.css" rel="stylesheet" type="text/css" />
		<?php } ?>

		<?php if(in_array($page_info['page'], ['single_video','dashboard','single_publish_post','single_blog']) ){ ?>
		<link href="<?= CDN_BASE_URL ;?>repo/css/player/videojs_v8.6.1.css" rel="stylesheet" />
		<!-- <link defer href="<?= CDN_BASE_URL ;?>repo/css/player/videojs.css" rel="stylesheet" type="text/css" /> -->
		<link defer href="<?= CDN_BASE_URL ;?>repo/css/player/videojs.ads.css" rel="stylesheet" type="text/css" />
		<link defer href="<?= CDN_BASE_URL ;?>repo/css/player/videojs.ima.css" rel="stylesheet" type="text/css" />
		<?php } ?>

		<?php if(in_array($page_info['page'], ['primary_type','setting','playlist','videoelephant','ViewThread','dashboard','Dashboard','upload_official_video','store','store_single_page','mutual_friends','my_shop','single_blog','blogs','Support']) ){ ?>
		<link defer rel="stylesheet" href="<?= CDN_BASE_URL ;?>repo_admin/css/select2.min.css" type="text/css" >
		<?php } ?>

		<?php if(in_array($page_info['page'], ['Dashboard']) ){ ?>
			<link rel="stylesheet" href="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.css">
		<?php } ?>
		<?php if(in_array($page_info['page'], ['setting','streaming','Dashboard','setting','ViewThread']) ){ ?>
		<link href="<?= CDN_BASE_URL ;?>repo/css/daterangepicker.css" rel="stylesheet" type="text/css" />
		<?php } ?>

		<?php if(in_array($page_info['page'], ['streaming']) ){ ?>
		<link href="<?= CDN_BASE_URL ;?>repo/css/chartist.min.css" rel="stylesheet" type="text/css" />
		<link href="<?= CDN_BASE_URL ;?>repo/css/chartist-plugin-tooltip.css" rel="stylesheet" type="text/css" />
		<?php } ?>

		<?php if(in_array($page_info['page'], ['perks', 'creatorinfo', 'about']) ){ ?>
		<link href="<?= CDN_BASE_URL ;?>repo/css/new_style.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<?php } ?>

		<link href="<?= CDN_BASE_URL ;?>repo/css/common_style.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<link href="<?= CDN_BASE_URL ;?>repo/css/darkstyle.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<link href="<?= CDN_BASE_URL ;?>repo/css/header.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<link href="<?= CDN_BASE_URL ;?>repo/css/style.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<link href="<?= CDN_BASE_URL ;?>repo/css/responsive.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
		<link href="<?= CDN_BASE_URL ;?>repo/gamification/assets/built/css/gamification.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
	<?php } ?>

	<?php if(isset($page_info['page'])){
			if(in_array($page_info['page'], ['dashboard','single_video','single_blog','article_mode','my_channel']) ){  ?>
				<!-- Dynamic Provisioning -->
				<!-- <script async src="//micro.rubiconproject.com/prebid/dynamic/9041.js"></script> -->
				<script async src="//micro.rubiconproject.com/prebid/dynamic/26344.js"></script>

				<!-- Test Provisioning -->
				<!-- <script async src="//ads.rubiconproject.com/prebid/9041_discovered_test.js"></script> -->
				<script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
				<script>
					var pbjs = pbjs || {};
					pbjs.que = pbjs.que || [];
					window.googletag = window.googletag || {cmd: []};
				</script>
	<?php 	} } ?>

	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5W7CPPQ');</script>

	<script src="https://www.googletagservices.com/tag/js/gpt.js" async></script>

	<?php if(empty($uid) || (isset($page_info['page']) && $page_info['page'] == 'giveaways') ){ ?>
		<meta name="google-signin-client_id" content="519992397823-7ts4k24tiag0008cfk890s2dmh7895jp.apps.googleusercontent.com">
	<?php } ?>

	<!-- Google tag (gtag.js) given by jaseem on date 12-Dec-2023 --->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-HEKNEC8Q1L"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-HEKNEC8Q1L');
	</script>
	<!-- Google tag (gtag.js) given by jaseem on date 12-Dec-2023 --->

	<!--script type="text/javascript">!(function(o,_name){function n(){(n.q=n.q||[]).push(arguments)}n.v=1,o[_name]=o[_name]||n;!(function(o,t,n,c){function e(n){(function(){try{return(localStorage.getItem("v4ac1eiZr0")||"").split(",")[4]>0}catch(o){}return!1})()&&(n=o[t].pubads())&&n.setTargeting("admiral-engaged","true")}(c=o[t]=o[t]||{}).cmd=c.cmd||[],typeof c.pubads===n?e():typeof c.cmd.unshift===n?c.cmd.unshift(e):c.cmd.push(e)})(window,"googletag","function");})(window,String.fromCharCode(97,100,109,105,114,97,108));!(function(t,c,i){i=t.createElement(c),t=t.getElementsByTagName(c)[0],i.async=1,i.src="https://truculentrate.com/v2hyfESPBRq3agHEBbPYbqCh9tQ_OfXZ75s_O_qsVY5e0ehpeF_L8Tb0",t.parentNode.insertBefore(i,t)})(document,"script");;;!(function(t,n,i,e,o){function a(){for(var t=[],i=0;i<arguments.length;i++)t.push(arguments[i]);if(!t.length)return o;"ping"===t[0]?t[2]({gdprAppliesGlobally:!!n.__cmpGdprAppliesGlobally,cmpLoaded:!1,cmpStatus:"stub"}):t.length>0&&o.push(t)}function c(t){if(t&&t.data&&t.source){var e,o=t.source,a="string"==typeof t.data&&t.data.indexOf("__tcfapiCall")>=0;(e=a?((function(t){try{return JSON.parse(t)}catch(n){}})(t.data)||{}).__tcfapiCall:(t.data||{}).__tcfapiCall)&&n[i](e.command,e.version,(function(t,n){var i={__tcfapiReturn:{returnValue:t,success:n,callId:e.callId}};o&&o.postMessage(a?JSON.stringify(i):i,"*")}),e.parameter)}}!(function f(){if(!window.frames[e]){var n=t.body;if(n){var i=t.createElement("iframe");i.style.display="none",i.name=e,n.appendChild(i)}else setTimeout(f,5)}})(),o=[],a.v=1,"function"!=typeof n[i]&&(n[i]=n[i]||a,n.addEventListener?n.addEventListener("message",c,!1):n.attachEvent&&n.attachEvent("onmessage",c))})(document,window,"__tcfapi","__tcfapiLocator");;!(function(n,t,i,u,e,o,r){function a(n){if(n){var u=(n.data||{}).__uspapiCall;u&&t[i](u.command,u.version,(function(t,i){n.source.postMessage({__uspapiReturn:{returnValue:t,success:i,callId:u.callId}},"*")}))}}!(function f(){if(!window.frames[u]){var t=n.body;if(t){var i=n.createElement("iframe");i.style.display="none",i.name=u,t.appendChild(i)}else setTimeout(f,5)}})();var s={getUSPData:function(n,t){return n!==1?t&&t(null,!1):t&&t({version:null,uspString:null},!1)}};function c(n,t,i){return s[n](t,i)}c.v=1,"function"!=typeof t[i]&&(t[i]=t[i]||c,t.addEventListener?t.addEventListener("message",a,!1):t.attachEvent&&t.attachEvent("onmessage",a)),o=n.createElement(e),r=n.getElementsByTagName(e)[0],o.src="https://truculentrate.com/v2faynMvgvvZbX9IBjPtWGiyXGELCSCM-Spa45TMfzME4wnbUyV4zIm5HentsJRTuXT3sE_FF",r.parentNode.insertBefore(o,r)})(document,window,"__uspapi","__uspapiLocator","script");</script-->

	<script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/c94c91750915e959e288849d0/c4da91033eaec59e8ffd05800.js");</script>

	<script>
	const base_url 			= 	'<?= BASE_URL ; ?>';
	const node_url 			= 	base_url+'node/';
	const AMAZON_URL 		= 	'<?php echo AMAZON_URL; ?>';
	const AMAZON_TRANCODE_URL 	= 	'<?php echo AMAZON_TRANCODE_URL; ?>';
	const user_login_id		=	'<?php echo $is_login; ?>';
	const csrfName 			= 	'<?php echo $this->security->get_csrf_token_name(); ?>';
    const csrfHash 			= 	'<?php echo $this->security->get_csrf_hash(); ?>';
	const user_uname		=	'<?= $user_uname; ?>';
	const user_name			=	'<?= addcslashes($get_user_fullname, "'");  ?>';
	const user_pic			=	'<?= $user_pic ?>';
	const user_email		=	'<?= $user_email ?>';
	const CDN_BASE_URL  	=   '<?= CDN_BASE_URL ?>';
	const DEFAULT_CURRENCY_SYMBOL  = '<?= DEFAULT_CURRENCY_SYMBOL ?>';
	const BUCKET_NAME  		= '<?= MAIN_BUCKET ?>';
	const TRANS_BUCKET_NAME = '<?= TRAN_BUCKET ?>';


	const TWEMOJI_JS  		=  '<?= TWEMOJI_JS ?>';
	const MOMENT_JS  		=  '<?= MOMENT_JS ?>';
	const SOCKET_CHAT_JS  	=  '<?= SOCKET_CHAT_JS ?>';
	const G_AUTH  			=  '<?= G_AUTH ?>';
	const F_AUTH  			=  '<?= F_AUTH ?>';
	const RECAPTCHA_SITE_KEY  =  '<?= RECAPTCHA_SITE_KEY ?>';

	const gam_base_url 		= 	'<?php echo addslashes($pageTitle); ?>';
	<?php

	if(!isset($_SESSION['TimeZoneOffset'])){
		echo 'window.onload = function(){ getTimeZoneOffset(); }';
	}?>

	function getTimeZoneOffset(){
		var d = new Date();
		let n = d.getTimezoneOffset();

		$.post(base_url+'home/SetTimeZoneOffSet',{'offset':n},function(res){
			console.log('Time zone offset has set out');
		})
	}
	</script>

	<?php if(isset($page_info['page'])){
		if(in_array($page_info['page'], ['dashboard','single_video','single_blog','article_mode','my_channel','gamepass']) ){
		?>
		<!-- BLOGHER ADS Begin header tag by jaseem-29/08/24-->
		<script type="text/javascript">
			var blogherads = blogherads || {};
				blogherads.adq = blogherads.adq || [];
		</script>
		<script type="text/javascript" async="async" data-cfasync="false" src="https://ads.blogherads.com/static/blogherads.js"></script>
		<script type="text/javascript" async="async" data-cfasync="false" src="https://ads.blogherads.com/sk/12/124/1242403/31822/header.js"></script>

		<!-- BLOGHER ADS End header tag -->
		<?php }
	} ?>


	<script>
		var dayMs 	= 864e5,
		cb 			= parseInt(Date.now() / dayMs),
		c 			= document.head || document.body || document.documentElement;

		function loadScript(src, cb) {
			let s = document.createElement('script');
			s.src = src;
			c.appendChild(s);
			s.onload = cb;
			s.onerror = cb;
			return s;
		}
		function loadStyle(src,cb){
			let s = document.createElement('link');
			s.rel = 'stylesheet';
			s.type = 'text/css';
			s.media = 'screen';
			s.href = src;
			c.appendChild(s);
			s.onload = cb;
			s.onerror = cb;
		}
	</script>

	<link rel="manifest" href="/manifest.json">

	<?php if(isset($page_info['page'])){
	if(in_array($page_info['page'],['my_channel','single_genre','all_genre','single_category','all_category','single_video','store','store_single_page','my_shop','article_mode','single_blog']) ){
	?>
	<!-- Taboola old script -->
	<script type="text/javascript">
		window._taboola = window._taboola || [];
		_taboola.push({article:'auto'});
		!function (e, f, u, i) {
			if (!document.getElementById(i)){
			e.async = 1;
			e.src = u;
			e.id = i;
			f.parentNode.insertBefore(e, f);
			}
		}(document.createElement('script'),
		document.getElementsByTagName('script')[0],
		'//cdn.taboola.com/libtrc/disoveredtv-publishernetwork/loader.js',
		'tb_loader_script');
		if(window.performance && typeof window.performance.mark == 'function')
			{window.performance.mark('tbl_ic');}
	</script>
	<!-- Taboola old script -->

	<!-- Taboola Pixel Code -->
	<script type='text/javascript'>
		window._tfa = window._tfa || [];
		window._tfa.push({notify: 'event', name: 'page_view', id: 1242370});
		!function (t, f, a, x) {
				if (!document.getElementById(x)) {
					t.async = 1;t.src = a;t.id=x;f.parentNode.insertBefore(t, f);
				}
		}(document.createElement('script'),
		document.getElementsByTagName('script')[0],
		'//cdn.taboola.com/libtrc/unip/1242370/tfa.js',
		'tb_tfa_script');
	</script>
	<!-- End of Taboola Pixel Code -->

	<!-- Taboola Pixel Code -->
	<script>
		_tfa.push({notify: 'event', name: 'view_content', id: 1242370, quantity: 'QUANTITY_PARAM'});
	</script>
	<!-- End of Taboola Pixel Code -->

	<?php }} ?>

	<script type="text/javascript">
		(function(c,l,a,r,i,t,y){
			c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
			t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
			y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
		})(window, document, "clarity", "script", "fn8ys92m7q");
	</script>

	<script type="text/javascript"> var infolinks_pid = 3423452; var infolinks_wsid = 0; </script>
	<script type="text/javascript" src="//resources.infolinks.com/js/infolinks_main.js"></script>

	<!-- Added by Teja -->
	<!--script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9328997896825317"
     crossorigin="anonymous"></script--> <!-- Commented by jaseem  -->

	 <!-- Meta Pixel Codes2
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '613621197499991');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=613621197499991&ev=PageView&noscript=1"
	/></noscript>

	End Meta Pixel Code -->
</head>
	<!-- Header End -->
	<!-- Body Start -->
<body class="custom_scrol <?= isset($_COOKIE['Theme'])? $_COOKIE['Theme'] : '';  ?>">

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5W7CPPQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<script>
		function ImageOnLoadError(_this,src1,src2){
			_this.src = src1;
			_this.onload = function() {
				_this.onerror=null;
			};
			_this.onerror = function(){
				_this.src = src2;
				_this.onerror=null;
			};
		}
	</script>

	<?php if(!isset($noHeader)) { ?>
	<div class="audition_main_wrapper">
		<div class="dis_m_header_wrap au_header_section  <?= !$is_login ? 'au_before_login': ''; ?><?= $this->uri->segment(1) == 'support' ? 'dis_supportHeader' : ''; ?> ">
			<div class="dis_m_header_logo_wrap">
				<div class="dis_m_header_logo">
					<a href="<?php echo $homepage_url ;?>">
						<img class="main_logo" src="<?= CDN_BASE_URL;?>repo/images/logo.webp" alt="logo" onerror="this.onerror=null;this.src='<?= CDN_BASE_URL;?>repo/images/logo.png'">
						<img class="mobile_logo" src="<?= CDN_BASE_URL;?>repo/images/mini_logo.webp" alt="mobile-logo" onerror="this.onerror=null;this.src='<?= CDN_BASE_URL;?>repo/images/mini_logo.png'">
					</a>
				</div>
			</div>
			<div class="dis_m_header_left">
					<div class="dis_m_header_sb_logo">
						<a href="<?php echo $homepage_url ;?>">
							<img src="<?= CDN_BASE_URL;?>repo/images/logo.webp" alt="logo" onerror="this.onerror=null;this.src='<?= CDN_BASE_URL;?>repo/images/logo.png'">
						</a>
						<div class="toggle_btn"><span></span><span></span><span></span></div>
					</div>

				<div class="dis_m_header_search_wrap <?=(isset($hede_menu))?'hide':''?>">
					<div class="dis_m_header_search_box dis_after_login_search">
						<form action="<?= base_url('search'); ?>" method="GET">
							<input type="text" class="search_content " name="search_query" placeholder="Discover Everything" id="" data-search="#appendHead" autocomplete="off">
							<button type="submit" class="dis_search_btn"><svg xmlns="https://www.w3.org/2000/svg" width="14px" height="14px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M13.608,13.607 C13.096,14.119 12.266,14.119 11.754,13.607 L8.545,10.398 C9.288,9.920 9.921,9.288 10.399,8.544 L13.608,11.753 C14.120,12.265 14.120,13.096 13.608,13.607 ZM5.250,10.495 C2.354,10.495 0.005,8.146 0.005,5.250 C0.005,2.353 2.354,0.005 5.250,0.005 C8.147,0.005 10.495,2.353 10.495,5.250 C10.495,8.146 8.147,10.495 5.250,10.495 ZM5.250,1.316 C3.081,1.316 1.317,3.081 1.317,5.250 C1.317,7.419 3.081,9.183 5.250,9.183 C7.420,9.183 9.184,7.419 9.184,5.250 C9.184,3.081 7.420,1.316 5.250,1.316 ZM3.065,5.250 L2.191,5.250 C2.191,3.563 3.564,2.190 5.250,2.190 L5.250,3.064 C4.045,3.064 3.065,4.045 3.065,5.250 Z"></path></svg></button>
						</form>
						<ul class="dis_m_header_search_list list-group dis_listgroup custom_scrol" id="appendHead" data-append="#appendHead">
						</ul>
					</div>
				</div>
				<div class="dis_m_header_menu_Wrap">
					<div class="dis_m_header_menu_li dis_m_header_modeWrap active">
						<div class="dis_m_header_menu_button modeButton">
							<span class="dis_m_header_menu_button_text"><?= $website_mode .' '. 'mode'; ?></span>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
						</div>
						<ul class="dis_m_header_mode_list dis_m_header_menu_list">
								<?php
									$mainModeMenu = '';
									foreach($header_menu as $menu){
										$icon = explode('.',$menu['icon']);
										$icon = $icon[0].'_thumb.'.$icon[1];
										$url = base_url($menu['mode']);
										$active = ($website_mode == $menu['mode']) ? 'active' : '';
										$href = 'href="'.$url.'" ';
										if($menu['mode_id'] == 4 ){ /*social*/
											if($is_login){
												$href = 'href="'.base_url('profile?user='.$menu['mode']).'" ';
											}else{
												$href = 'class="openModalPopup dis_m_header_mode_items" data-href="modal/login_popup" data-cls="login_mdl" onclick="OpenRoute(\'profile?user=social\')"';
											}
										}
										$mainModeMenu .= '<li><a '.$href.' class="dis_m_header_mode_items '.$active.'">'.$menu['mode'].'</a></li>';
									}
								?>
							<?php echo $mainModeMenu; ?>
						</ul>
					</div>
					<div class="dis_m_header_menu_li dis_more_menu">
						<div class="dis_m_header_menu_button moreButton" data-toggle="dropdown">
							<span class="dis_m_header_menu_button_text">More</span>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
						</div>
						<ul class="dis_more_menu_item_list dis_m_header_menu_list">
							<?php
								if(!$is_login && (isset($page_info['page']) && $page_info['page'] != 'giveaways') ){
								$params = ($page_info['page']=='getdiscovered') ? '?getdiscovered=1' : '';
							?>
								<li class="hide_on_desktop">
									<a href="<?=base_url('/home/perks');?>" class="dis_more_menu_item">Perks</a>
								</li>
								<li>
									<a href="<?=base_url('/home/about');?>" class="dis_more_menu_item">About Us</a>
								</li>
							<?php } ?>
							<li class="hide_on_desktop">
								<a href="<?=base_url('monetization');?>" class="dis_more_menu_item">Monetize Video</a>
							</li>
							
							<!-- <li>
								<a href="<?=base_url('gamepass');?>" class="dis_more_menu_item">Game Pass</a>
							</li> -->
							<li>
								<a href="<?=base_url('shop');?>" class="dis_more_menu_item">Shop</a>
							</li>
							<li>
								<a href="javascript:;" title="Download App"  class="dis_more_menu_item app_link_wrap common_click">Download App</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="dis_m_header_right <?=(isset($hede_menu))?'hide':''?>">
				<ul class="dis_m_header_options_round">
					<?php
						if(!$is_login && (isset($page_info['page']) && $page_info['page'] != 'giveaways') ){
						$params = ($page_info['page']=='getdiscovered') ? '?getdiscovered=1' : '';
					?>
					<li class="hide_on_mobile">
						<a href="<?=base_url('/home/perks');?>" class="headerBtnBorder">Perks</a>

					</li>
					<li class="dis_m_header_cmnOptionWrap">
						<a href="javascript:;" class="headerSignInBtn" data-toggle="dropdown">
							<span class="headerSignInBtn_text"> Sign In </span>
							<span class="headerSignInBtn_icon right">
								<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M9 4.25V14.75" stroke="black" style="stroke:black;stroke-opacity:1;" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M14.25 9.5L9 14.75L3.75 9.5" stroke="black" style="stroke:black;stroke-opacity:1;" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
						</a>
						<ul class="dis_m_header_register_list dis_m_header_cmn_option">

								<li>
								<a href="<?=base_url('sign-up').$params;?>" class="">Sign-Up</a>
								</li>
								<li class="login">
									<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">login</a>
								</li>

						</ul>
					<li>
					<?php } ?>
					<?php if($this->uri->segment(2) != 'payment' &&  $website_mode == 'store'){ ?>
						<li>
							<a href="javascript:;" class="dis_header_round cart_toggle" onclick="loadMyCart();show_wishlist()">
							<div class="dis_notification  dis_hdr_chatwrap">
								<div class="inner_noty close_common">
									<span>
									<img src="<?= BASE_URL;?>repo/images/cart.svg" alt="cart" class="img-responsive">
									</span>
									<span id="wishlist_count" style="display: none;" class="NotiCount">0</span>
								</div>
							</div>
							</a>
							<div class="dis_card_dd cart_ddtoggle">

								<div class="dis_card_dd_list dis-custom-tab">
									<div class="active dis_card_dd_LI dis-custom-tab-list">
										<div data-toggle="pill" data-href="#cart" class="dis_card_dd_link dis-custom-tab-link">
											<span class="dis_cdl_icon">
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="15px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M13.608,4.988 C13.212,6.700 12.816,8.411 12.416,10.120 C12.283,10.688 11.970,10.948 11.430,10.949 C8.897,10.950 6.364,10.950 3.830,10.949 C3.236,10.948 2.905,10.610 2.832,9.972 C2.550,7.504 2.262,5.37 1.975,2.569 C1.965,2.480 1.945,2.392 1.924,2.274 C1.705,2.274 1.510,2.281 1.314,2.273 C0.925,2.255 0.639,1.935 0.647,1.536 C0.654,1.149 0.936,0.842 1.314,0.831 C1.678,0.820 2.43,0.827 2.407,0.828 C2.909,0.830 3.141,1.42 3.213,1.582 C3.316,2.366 3.411,3.152 3.511,3.937 C3.520,4.15 3.537,4.92 3.556,4.201 C3.672,4.201 3.781,4.201 3.890,4.201 C6.937,4.201 9.985,4.201 13.32,4.202 C13.579,4.202 13.740,4.419 13.608,4.988 ZM4.684,11.913 C5.430,11.912 6.38,12.570 6.33,13.371 C6.27,14.158 5.422,14.803 4.689,14.804 C3.944,14.805 3.335,14.146 3.340,13.346 C3.345,12.559 3.951,11.914 4.684,11.913 ZM10.93,11.913 C10.825,11.922 11.422,12.575 11.420,13.363 C11.418,14.163 10.801,14.813 10.55,14.804 C9.323,14.794 8.725,14.141 8.727,13.353 C8.729,12.554 9.346,11.904 10.93,11.913 Z"/></svg>
											</span>
										My Cart
									</div>
									</div>
									<div class="dis_card_dd_LI dis-custom-tab-list">
										<div data-toggle="pill" data-href="#wishlist" class="dis_card_dd_link dis-custom-tab-link">
										<span class="dis_cdl_icon">
											<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="12px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M6.755,2.268 C7.250,1.502 7.891,0.973 8.739,0.706 C10.111,0.275 11.625,0.783 12.493,1.999 C13.337,3.181 13.478,4.459 12.917,5.796 C12.420,6.978 11.576,7.915 10.646,8.782 C9.551,9.803 8.340,10.675 7.57,11.452 C6.879,11.560 6.713,11.612 6.515,11.493 C4.649,10.364 2.908,9.88 1.534,7.387 C0.897,6.599 0.395,5.736 0.280,4.713 C0.69,2.844 1.278,0.967 3.149,0.625 C4.562,0.367 5.679,0.890 6.545,1.980 C6.612,2.64 6.671,2.152 6.755,2.268 Z"/></svg>
										</span>
										My Wishlist</div>
									</div>
								</div>
								<div class="dis_card_dd_content">
									<div class="dis-custom-content">
										<div id="cart" class="dis-custom-result">
											<div class="tab_content_cart dis_card_dd_inner">
												<div class="disCBL" id="cartItems">

												</div>
												<div class="dis_ddcart_footer hideme" id="cart_footer">
													<div class="dis_ddcart_fList">
														<p class="dis_ddcart_fc">
															<span>
																<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="15px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M13.608,4.988 C13.212,6.700 12.816,8.411 12.416,10.120 C12.283,10.688 11.970,10.948 11.430,10.949 C8.897,10.950 6.364,10.950 3.830,10.949 C3.236,10.948 2.905,10.610 2.832,9.972 C2.550,7.504 2.262,5.37 1.975,2.569 C1.965,2.480 1.945,2.392 1.924,2.274 C1.705,2.274 1.510,2.281 1.314,2.273 C0.925,2.255 0.639,1.935 0.647,1.536 C0.654,1.149 0.936,0.842 1.314,0.831 C1.678,0.820 2.43,0.827 2.407,0.828 C2.909,0.830 3.141,1.42 3.213,1.582 C3.316,2.366 3.411,3.152 3.511,3.937 C3.520,4.15 3.537,4.92 3.556,4.201 C3.672,4.201 3.781,4.201 3.890,4.201 C6.937,4.201 9.985,4.201 13.32,4.202 C13.579,4.202 13.740,4.419 13.608,4.988 ZM4.684,11.913 C5.430,11.912 6.38,12.570 6.33,13.371 C6.27,14.158 5.422,14.803 4.689,14.804 C3.944,14.805 3.335,14.146 3.340,13.346 C3.345,12.559 3.951,11.914 4.684,11.913 ZM10.93,11.913 C10.825,11.922 11.422,12.575 11.420,13.363 C11.418,14.163 10.801,14.813 10.55,14.804 C9.323,14.794 8.725,14.141 8.727,13.353 C8.729,12.554 9.346,11.904 10.93,11.913 Z"/></svg>
															</span>
															Manage Cart
														</p>
														<p class="dis_ddcart_sc" id="cart_subtotal">Sub-Total : $00.00</p>
													</div>
													<a href="<?=base_url('store/cart');?>" class="dis_OrangeBtn w-100">Proceed To Checkout <span class="dis_sp_right"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.311,4.671 12.185,4.671 C8.413,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.091 0.036,3.475 0.534,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.325 8.456,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.207 12.467,3.132 12.397,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.089 11.127,-0.063 11.464,0.266 C12.554,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.173 14.729,4.528 C13.643,5.598 12.554,6.665 11.464,7.730 C11.116,8.069 10.728,8.089 10.430,7.795 C10.130,7.499 10.154,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"></path></svg></span></a>
												</div>
											</div>
										</div>
										<div id="wishlist" class="dis-custom-result">
											<div class="tab_content_wishlist dis_card_dd_inner">
												<div id="wishlist_append" class="disCBL">

												</div>
												<div id="wishlist_footer" class="dis_ddcart_footer">
													<a href="<?php echo base_url('store/wishlist') ?>" class="dis_ddwisthbtn text-center">Manage Wishlist</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
					<?php  } ?>
						<!-- <li>
							<a href="javascript:;" class="dis_header_round app_link_wrap common_click" title="Download App">
								<img src="<?= BASE_URL;?>repo/images/downloadApp.svg" alt="Download App" title="Download App" class="img-responsive" width="13px" height="20px">
							</a>
						</li> -->
					<?php if($is_login) { ?>

						<!-- Gamification -->
						<li id="gam-leaderboard-modal-root"></li>

					<?php  if($account_type != 4 && isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard') { ?>
						<li>
							<div class="dis_header_round" title="Monetize Video" data-toggle="dropdown">
								<img src="<?= BASE_URL;?>repo/images/header_upload.svg" alt="upload" class="img-responsive">
							</div>
							<div class="dis_upload_video_dd">
								<div class="dis_uv_dd_list">
									<a href="<?= base_url('profile?user='.$user_uname); ?>" class="dis_uv_dd_l_item">
										<h1>Create Post</h1>
										<p>Non-Monetized Video</p>
									</a>
									<a href="<?php echo base_url('monetization'); ?>" class="dis_uv_dd_l_item">
										<h1>Upload Video</h1>
										<p>Earn Money/Video</p>
									</a>
									<a href="<?php echo base_url('media_stream'); ?>" class="dis_uv_dd_l_item">
										<h1>Go Live</h1>
										<p>Earn Money/Live Stream</p>
									</a>
								</div>
							</div>
						</li>
					<?php } ?>
						<li class="hide_on_mobile">
							<a href="<?php echo base_url('dashboard/directory');?>" class="dis_header_round" title="Directory">
								<img src="<?= BASE_URL;?>repo/images/headeruser.svg" alt="creators" title="Directory" class="img-responsive">
							</a>
						</li>

					<?php

					if($page_info['page'] !== 'socketchat'){ ?>
						<li>
							<div id="loading_title" title="loading" class="dis_notification  dis_hdr_chatwrap">
								<div id="msg_icon" class="inner_noty close_common" data-toggle="dropdown">
									<span id="header_message_icon" class="dis_header_round">
										<img src="<?= BASE_URL;?>repo/images/message.svg" alt="notification" class="img-responsive">
									</span>

									<span id="message_count" style="display: none;" class="NotiCount" >0</span>
								</div>
								<div class="noti_drop" >
									<div class="noti_header">
										<div class="dis_hdrsearch">
											<input id="SearchUser" type="search" class="dis_hdrsearchInput" placeholder="Search here...">
											<span class="dis_hdrsearch_icon">
												<svg xmlns="https://www.w3.org/2000/svg" width="14px" height="14px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M13.608,13.607 C13.096,14.119 12.266,14.119 11.754,13.607 L8.545,10.398 C9.288,9.920 9.921,9.288 10.399,8.544 L13.608,11.753 C14.120,12.265 14.120,13.096 13.608,13.607 ZM5.250,10.495 C2.354,10.495 0.005,8.146 0.005,5.250 C0.005,2.353 2.354,0.005 5.250,0.005 C8.147,0.005 10.495,2.353 10.495,5.250 C10.495,8.146 8.147,10.495 5.250,10.495 ZM5.250,1.316 C3.081,1.316 1.317,3.081 1.317,5.250 C1.317,7.419 3.081,9.183 5.250,9.183 C7.420,9.183 9.184,7.419 9.184,5.250 C9.184,3.081 7.420,1.316 5.250,1.316 ZM3.065,5.250 L2.191,5.250 C2.191,3.563 3.564,2.190 5.250,2.190 L5.250,3.064 C4.045,3.064 3.065,4.045 3.065,5.250 Z"></path></svg>
											</span>
										</div>
									</div>
									<div class="noti_data custom_scrol" id="show_message">
									</div>
									<div class="noti_footer">
										<a href="<?php echo base_url('messenger?user='.$user_uname);?>" id="mark_all_read" class="">See all messages</a>
									</div>
								</div>
							</div>
						</li>
					<?php } ?>

					<li>
						<div class="dis_notification  notification_bell show_notification" data-target="#show_notification">
							<div class="inner_noty close_common" data-toggle="dropdown">
								<span class="dis_header_round">
									<img src="<?= BASE_URL;?>repo/images/notification.svg" alt="notification" class="img-responsive">
								</span>
								<?php $noticount =  $this->audition_functions->getTotalNotiCount(0);
										//if($noticount ==0){$Notdis =  'style="display:none;"';}else{$Notdis = ''; }
								?>
								<span class="NotiCount" dis-notif-count="<?php echo $noticount; ?>"><?php echo $noticount; ?></span>
							</div>
							<div class="noti_drop">
								<div class="noti_header">
									<h4>New Notification</h4>
									<span  class=""></span>
								</div>
								<div class="noti_body">
									<!-- Gamificiation - add profile picture notifcation -->
									<div id="gam-notifications-root">

									</div>
									<div class="noti_data" id="show_notification">

									</div>
								</div>
								<div class="noti_footer">
									<a class="ClearMyNotification">Clear all</a>
								</div>
							</div>


						</div>
					</li>

					<li>
						<div class="dis_m_header_profile dis_m_header_cmnOptionWrap close_common">
							<a href="javascript:;" data-toggle="dropdown">
								<img src="<?php echo $user_page_image; ?>" onerror="this.onerror=null;this.src='<?= user_default_image(); ?>'" title="<?php echo $get_user_fullname;?>" class="img-responsive" alt="">
							</a>
							<ul class="dis_m_header_profile_list dis_m_header_cmn_option">
								<li class="login"><a href="<?= base_url('profile?user='.$user_uname); ?>">My Social</a></li>
								<?php  if($account_type != 4 && isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard') { ?>
								<li class="login"><a href="<?= base_url('channel?user='.$user_uname);?>">My Channel</a></li>
								<li class="login"><a href="<?= base_url('backend/dashboard'); ?>">Dashboard</a></li>
								<?php } ?>
								<!--li class="login"><a href="<?= base_url('store/orders'); ?>">My Orders</a></li-->
								<li class="login"><a href="<?= base_url('help'); ?>">Help</a></li>
								<li class="login shareInviteLink common_click" data-user_uname="<?php echo $user_uname; ?>"><a>Invite Link</a></li>
								<li class="login" ><a href="<?= base_url('search?search_query=&referral_by='.$user_uname.'&hide=search|video');?>">Invited People</a></li>
								<li class="login"><a href="<?= base_url('settings'); ?>">Settings</a></li>
								<li class="login"><a href="<?= base_url('home/logout'); ?>">Sign Out</a></li>
							</ul>
						</div>
					</li>

					<?php } ?>
				</ul>
				<div class="toggle_btn"><span></span><span></span><span></span></div>
			</div>
		</div>

<?php } ?>

<!-- Common website modal which are using on many pages-->

<?php if($is_login){ ?>
<div class="modal fade in" id="endorsementsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		<h3 class="text-center">Endoresement Detail</h3>
		</div>
		<div class="modal-body">
				<button type="button" class="btn endorser" data-endorsee_id="">Submit</button>
				<button type="button" class="btn" data-dismiss="modal" aria-label="Close">
				Close</button>
				<br>
				Note:If agreement is not accepted yet,can be cancel by submit again.
		</div>
	</div>
	</div>
</div>

<?php } ?>

<?php if (!empty($cuser[0]['HDYDU'] && $is_login)) {
	setcookie('hdydu_closed', 'yes');
} ?>

<?php if(empty($cuser[0]['HDYDU']) && $is_login ){ ?>
	<div class="dis_us_modal dis_center_modal modal fade hide gam-modal-HowIComeONDis" id="HowIComeONDis" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body mp_0">
					<div class="muli_font">
						<h2 class="dis_bmodalTll mp_0 m_b_20">How Did You Discover Us?</h2>
					</div>
					<div class="m_b_20 muli_font">
						<?php $slist = $this->valuelist->SingupSourelist();?>
						<ul class="dis_discoveredUs_List">
							<?php  foreach( $slist as $k => $v){ ?>
							<li>
								<div class="dis_checkbox">
									<label>
										<input type="radio" value="<?= $k ;?>" class="check" name="mySource" >
										<i class="input-helper"></i>
										<p><?= $v ;?></p>
									</label>
								</div>
							</li>
							<?php  } ?>
							<div class="dis_field_box hide" id="sourceField">
								<div class="dis_field_wrap">
									<input type="text" class="dis_field_input" placeholder="Type Here..." name="myCustSource">
								</div>
							</div>
						</ul>
					</div>
					<div class="">
						<ul class="dis_discoveredUs_btn">
							<li>
								<a href="javascript:;" id="SkipPopup" class="dis_btn gam-hdydu-event">skip</a>
							</li>
							<li>
								<a href="javascript:;" id="SubmitPopup" class="dis_btn gam-hdydu-event">Submit</a>
							</li>
						</ul>
					</div>

				</div>
			</div>
			<!-- <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button> -->
		</div>
	</div>

	<!-- Gamification -->
	<div id="gam-modal-manager-root"></div>
<?php } ?>

<div class="main_contnt_wrapper full_vh_foooter">  <!--End in footer start - CONTENT MAIN WRAPPER CLASS-->





<script>
document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.dis_m_header_menu_li');

    // Initially show the first submenu and set active
    menuItems[0].classList.add('active');
    menuItems[0].querySelector('.dis_m_header_menu_button').classList.add('active');

    menuItems.forEach(item => {
        const button = item.querySelector('.dis_m_header_menu_button');

        button.addEventListener('click', function () {
            const isActive = item.classList.contains('active');

            // Close all menus and remove 'active' class
            menuItems.forEach(i => {
                i.classList.remove('active');
                i.querySelector('.dis_m_header_menu_button').classList.remove('active');
            });

            // If this menu wasn't already active, open it and add 'active' class
            if (!isActive) {
                item.classList.add('active');
                button.classList.add('active');
            }
        });
    });
});
</script>




