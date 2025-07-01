<!DOCTYPE html>
<html lang="en">
<!-- Header Start -->
<head>
	<title><?php echo (isset($page_info))? $page_info['title'] : 'Discovered.TV'; ?></title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" type="image/ico" href="<?php echo base_url();?>repo/images/favicon.png" /> 
	<link href="<?= CDN_BASE_URL ;?>repo/css/bootstrap.css" rel="stylesheet" type="text/css" /> 
	<link href="<?= base_url() ;?>repo/css/font-awesome.css" rel="stylesheet" type="text/css" /> 
	
	<?php $login_user_id = is_login(); ?>
	
	<script>
		var base_url = '<?php echo base_url(); ?>';
		const user_login_id	=	'<?php echo $login_user_id ; ?>';
		const AMAZON_URL 	= 	'<?php echo AMAZON_URL; ?>';
	</script>
	
	<?php if(isset($page_info['page'])){?>
		
		<?php if(in_array($page_info['page'], ['dashboard']) ){ ?> 
			<link href="<?= CDN_BASE_URL ;?>repo/css/chartist.min.css" rel="stylesheet" type="text/css" /> 
			<link href="<?= CDN_BASE_URL ;?>repo/css/chartist-plugin-tooltip.css" rel="stylesheet" type="text/css" /> 
		<?php } ?>

		<?php if(in_array($page_info['page'], ['advertising','payouts','statements','playlist','products'])){ ?> 
			<link href="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.css" rel="stylesheet" type="text/css"/>
		<?php } ?>
		
		<?php if(in_array($page_info['page'], ['dashboard','advertising','payouts','statements','products'])){ ?> 
			<link href="<?= CDN_BASE_URL ;?>repo/css/daterangepicker.css" rel="stylesheet" type="text/css" /> 
		<?php } ?>

		<?php if(in_array($page_info['page'], ['advertising'])){ ?> 
			<link href="<?= BASE_URL ;?>repo/css/style.css" rel="stylesheet" type="text/css" /> 
		<?php } ?>

		<?php if(in_array($page_info['page'], ['advertising','payouts','setting','statements','playlist','products','create_product','view_order'])){ ?> 
			<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
			<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.css" rel="stylesheet" type="text/css" />
		<?php } ?>
	<?php } ?>	
	
	<link href="<?=base_url() ;?>repo/css/backend/dashboard.css?q=<?= VRSN ;?>" rel="stylesheet" type="text/css" />
	

</head>

<body>

<!-- site loader start -->
<!-- <div class="website_preloader">
	<div class="website_status" >
		<img src="https://test.discovered.tv/repo/images/loader.gif" id="preloader_image" alt="loader">
	</div>
</div> -->
<!-- site loader end -->

<?php include('sidebar.php'); ?>
<?php  $user_uname = isset($_SESSION['user_uname'])?$_SESSION['user_uname']:''; ?>

<!-- page main wrapper start -->
<div class="dis_dash_main_warappeer">
	<!-- header sectipon start -->
	<div class="dis_dashboard_header_wrapper">
		<div class="dash_header_inner">
			<div class="dash_topmenu">				
						<div class="th_left">
							<div class="top_search custome_hide hide">
								<div class="search_box">
									<input type="search" placeholder="Search Dashboard">
										<svg xmlns="https://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 485.213 485.213"><g><g><g>
											<path d="M471.882,407.567L360.567,296.243c-16.586,25.795-38.536,47.734-64.331,64.321l111.324,111.324    c17.772,17.768,46.587,17.768,64.321,0C489.654,454.149,489.654,425.334,471.882,407.567z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#818494"/>
											<path d="M363.909,181.955C363.909,81.473,282.44,0,181.956,0C81.474,0,0.001,81.473,0.001,181.955s81.473,181.951,181.955,181.951    C282.44,363.906,363.909,282.437,363.909,181.955z M181.956,318.416c-75.252,0-136.465-61.208-136.465-136.46    c0-75.252,61.213-136.465,136.465-136.465c75.25,0,136.468,61.213,136.468,136.465    C318.424,257.208,257.206,318.416,181.956,318.416z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#818494"/>
											<path d="M75.817,181.955h30.322c0-41.803,34.014-75.814,75.816-75.814V75.816C123.438,75.816,75.817,123.437,75.817,181.955z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#818494"/>
											</g>
										</g></g> 
										</svg>
								</div>
							</div>
							<div class="th_btn">
								<a href="<?= base_url('profile?user='.$user_uname) ?>" class="backend_btn gray"> <i class="fa fa-long-arrow-left" aria-hidden="true"></i> <span class="mobile_text_hide"> Back To </span> Profile</a>
							</div>
						</div>
					
						<div class="top_information">
							<ul>
								<li>
									<?php  	
									$userOut =  $this->DatabaseModel->select_data('users.outstanding','users use INDEX(user_id)',['user_id'=>$login_user_id],1);
									?>
									<div class="th_btn">
										<a class="backend_btn h_50 max_width">O/S - $<?= isset($userOut[0]['outstanding'])?$userOut[0]['outstanding']:0; ?></a>
									</div>
								</li>
								<li class="">
									<div class="header_notification">
										<div class="noti_icon" data-toggle="dropdown">
											<i class="fa fa-bell-o" aria-hidden="true"></i>
											<span>0</span>
										</div>
										<ul class="head_noti_inner">
											<li>
												<img src="https://test.discovered.tv/repo/images/backend/dash_hnoti.png" alt="images">
												<h4 class="no_nitifttl">No Notification Yet!</h4>
											</li>

										</ul>
									</div>
								</li>
								<li>
									<div class="header_profile" >
											<p class="admin_name"><span>Howdy,</span>
											<?php echo $user_name = isset($_SESSION['user_name'])?$_SESSION['user_name']:''; ?>
											
											
											</p>
												<img src="<?= get_user_image($login_user_id) ;?>" onerror="this.onerror=null;this.src='<?= user_default_image(); ?>'" alt="<?= $user_name; ?>">
											<i class="fa fa-sort-desc user_caret" aria-hidden="true" ></i>
												<ul class="profile_dropdown">
													<li>
														<a href="<?= base_url('profile?user='.$user_uname) ?>">
														 <span class="pro_drop">
														 <svg viewBox="-42 0 512 512.002" xmlns="http://www.w3.org/2000/svg" width="13px" height="13px"><path d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0"/><path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0"/></svg></span>
														  Profile
														</a>
													</li>
													<li>
														<a href="<?= base_url('home/logout'); ?>">
														 <span class="pro_drop">
														 <svg xmlns="http://www.w3.org/2000/svg" width="12px" height="12px" viewBox="0 0 9 10">
														  <defs>
															<style>
															  .cls-144 {
																fill: #ffffff;
																fill-rule: evenodd;
															  }
															</style>
														  </defs>
														  <path class="cls-144" d="M4.771,8.714H1.38a0.14,0.14,0,0,1-.136-0.142V1.445A0.14,0.14,0,0,1,1.38,1.3H4.771A0.635,0.635,0,0,0,5.39.654,0.635,0.635,0,0,0,4.771,0H1.38A1.41,1.41,0,0,0,.008,1.445V8.572A1.41,1.41,0,0,0,1.38,10.013H4.771A0.65,0.65,0,0,0,4.771,8.714ZM8.816,4.547L6.525,2.172a0.719,0.719,0,0,0-.437-0.188,0.6,0.6,0,0,0-.438.193,0.673,0.673,0,0,0,.006.918L6.876,4.359h-3.8a0.65,0.65,0,0,0,0,1.3h3.8L5.656,6.922a0.676,0.676,0,0,0,0,.919,0.6,0.6,0,0,0,.44.192,0.6,0.6,0,0,0,.434-0.187L8.816,5.471A0.67,0.67,0,0,0,9,5.009,0.661,0.661,0,0,0,8.816,4.547Z"/>
														</svg>

															<!--svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 11.9" width="13px" height="13px">
															<style type="text/css">
																.st0{fill:#FFFFFF;}
															</style>
															<g id="XMLID_2_">
																<g>
																	<path id="XMLID_20_" class="st0" d="M11.6,7.6c-0.3,0.3-0.3,0.7,0,1c0.1,0.1,0.3,0.2,0.5,0.2c0.2,0,0.4-0.1,0.5-0.2l2.2-2.2
																		c0,0,0,0,0-0.1c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0-0.1c0,0,0,0,0,0c0,0,0,0,0-0.1
																		c0,0,0,0,0,0c0,0,0,0,0-0.1c0,0,0,0,0,0c0,0,0,0,0-0.1c0,0,0,0,0-0.1s0,0,0-0.1c0,0,0,0,0-0.1c0,0,0,0,0,0c0,0,0,0,0-0.1
																		c0,0,0,0,0,0c0,0,0,0,0-0.1c0,0,0,0,0,0c0,0,0,0,0-0.1c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0
																		c0,0,0,0,0-0.1l-2.2-2.2c-0.3-0.3-0.7-0.3-1,0c-0.3,0.3-0.3,0.7,0,1l0.9,0.9H5.1c-0.4,0-0.7,0.3-0.7,0.7c0,0.4,0.3,0.7,0.7,0.7
																		h7.4L11.6,7.6z"/>
																	<path id="XMLID_19_" class="st0" d="M0,5.9c0,3.3,2.7,5.9,5.9,5.9c2,0,3.8-1,4.9-2.6c0.2-0.3,0.1-0.8-0.2-1
																		c-0.3-0.2-0.8-0.1-1,0.2c-0.8,1.2-2.2,2-3.7,2c-2.5,0-4.5-2-4.5-4.5s2-4.5,4.5-4.5c1.5,0,2.9,0.7,3.7,2c0.2,0.3,0.7,0.4,1,0.2
																		c0.3-0.2,0.4-0.7,0.2-1C9.8,1,7.9,0,5.9,0C2.7,0,0,2.7,0,5.9z"/>
																</g>
															</g>
															</svg-->
															</span>
														  Logout
														</a>
													</li>
												</ul>
									</div>
								</li>
							</ul>
							<div class="toggle_menuicon toggle_click">
								<span>
									<div class="line_menu line_half first_line"></div>
									<div class="line_menu"></div>
									<div class="line_menu line_half last_line"></div>
								</span>
							</div>
						</div>
						
						
				
				
				
			</div>
		</div>
	</div>
	<!-- header section End -->

