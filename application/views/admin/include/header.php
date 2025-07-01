<!DOCTYPE html>
<html>
<head>

<?php
	$checkItemData = (isset($page_menu))?explode('|' , $page_menu):array();
	$currentMainMenu = (isset($checkItemData[0]))?$checkItemData[0]:'';
	$currentSubMenu = (isset($checkItemData[1]))?$checkItemData[1]:'';
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:'';
	$currentPageName = (isset($checkItemData[3]))?$checkItemData[3]:'';

?>
	<script> var base_url = '<?= base_url(); ?>'; </script>
	<title>Discovered | <?= $pageTitle; ?></title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="shortcut icon" type="image/ico" href="<?php echo base_url();?>repo/images/favicon.png" />
	<link rel="stylesheet" href="<?= base_url('repo_admin/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('repo_admin/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('repo_admin/plugins/css/ionicons.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('repo/js/plugin/magnific_popup/magnific-popup.css'); ?>"  type="text/css" />
	<link rel="stylesheet" href="<?= base_url('repo_admin/css/skins.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('repo_admin/plugins/css/toastr.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('repo_admin/plugins/js/jqueryUi/jquery-ui.min.css'); ?>">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

	<?php if(in_array($currentPageName , array('dashboard'))){ ?>
    <link href="<?=base_url('repo/css/daterangepicker.css');?>" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="<?= base_url('repo_admin/plugins/css/jquery-jvectormap.css'); ?>">

	<?php } ?>

	<?php if(in_array($currentPageName , array(
          'userlist',
          'categorylist',
          'mainsetting',
          'channel_video',
          'approved_video',
          'rejected_video',
          'helpAndFaq',
          'report',
          'HomepageSlider',
          'genrelist',
          'Support',
          'department',
          'pagesetting',
          'attributes',
          'attribute_terms',
          'categories',
          'store_request',
          'flags_report',
          'flags_group',
          'firebase_notification',
		      'blog_category',
          'filmhub_list'
          )
      )){
  ?>
		<link rel="stylesheet" href="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.css">
    <link href="<?=base_url('repo/css/daterangepicker.css');?>" rel="stylesheet" type="text/css" />
    <?php } ?>

	<?php if(in_array($currentPageName , array('pagesetting'))){ ?>
		<link rel="stylesheet" href="<?= base_url('repo_admin/css/dropzone.css'); ?>" />
	<?php } ?>

	<?php if(in_array($currentPageName , array('pagesetting','approved_video','report','HomepageSlider','userlist','mrss_generator','firebase_notification','mainsetting','flags_report','filmhub_list'))){ ?>
		<link rel="stylesheet" href="<?= base_url('repo_admin/css/select2.min.css'); ?>">
	<?php } ?>
	<?php if(in_array($currentPageName , array('report'))){ ?>
		<link href="<?php echo base_url();?>repo/css/daterangepicker.css" rel="stylesheet" type="text/css" />
	<?php } ?>
	<?php if(in_array($currentPageName , array('HomepageSlider'))){ ?>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.css" rel="stylesheet" type="text/css" />
	<?php } ?>
  <link rel="stylesheet" href="<?= base_url('repo_admin/css/admin.css'); ?>">
  <!-- Google Font -->

</head>
<body class="hold-transition skin-red sidebar-mini sidebar-collapse">
<!-- preloader start -->
<div class="admin_preloader">
	<div class="admin_status">
		<img src="https://test.discovered.tv/repo_admin/images/site_loader.gif" alt="preloader"></div>
</div>
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b>TV</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Discovered TV</b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="<?= base_url(); ?>repo/images/banner_logo1.png" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->

                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>

                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?= base_url(); ?>repo/images/banner_logo1.png" class="user-image" alt="User Image">
              <span class="hidden-xs">Admin</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?= base_url(); ?>repo_admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  Admin
                  <small></small>
                </p>
              </li>
              <!-- Menu Body -->

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?= base_url('auth/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= base_url(); ?>repo/images/banner_logo1.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->

      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="<?php echo ($currentMainMenu == 'main_dashboard')?'active':''; ?>">
          <a  href="<?php echo ($currentMainMenu != 'main_dashboard')? base_url('admin_dashboard'): '' ; ?>">
          <a  <?php echo ($currentMainMenu != 'main_dashboard')? 'href="' .base_url("admin_dashboard") .'"' : '' ; ?>>
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <li class="treeview <?php echo ($currentMainMenu == 'main_userlist')?'active':''; ?>">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Manage Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($currentSubMenu == 'sub_userlist')?'active':''; ?>"><a href="<?= base_url('admin/userlist'); ?>"><i class="fa fa-circle-o"></i>User List</a></li>
            <li class="<?php echo ($currentSubMenu == 'sub_sourceuserlist')?'active':''; ?>"><a href="<?= base_url('admin/userSourceList'); ?>"><i class="fa fa-circle-o"></i>User Source List</a></li>
            <li class="<?php echo ($currentSubMenu == 'official_user_list')?'active':''; ?>"><a href="<?= base_url('admin/official_user_list'); ?>"><i class="fa fa-circle-o"></i>Official Profiles</a></li>
            <li class="<?php echo ($currentSubMenu == 'live_request')?'active':''; ?>"><a href="<?= base_url('admin/user_live_request'); ?>"><i class="fa fa-circle-o"></i>User Request For Live </a></li>
            <li class="<?php echo ($currentSubMenu == 'media_request')?'active':''; ?>"><a href="<?= base_url('admin/media_live_request'); ?>"><i class="fa fa-circle-o"></i>Media Request For Live </a></li>
          </ul>
        </li>

		    <li class="treeview <?php echo ($currentMainMenu == 'main_category_listt')?'active':''; ?>">
          <a href="#">
            <i class="fa fa-life-ring"></i>
            <span>Manage Category</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($currentSubMenu == 'category_list')?'active':''; ?>"><a href="<?= base_url('admin/category_list'); ?>"><i class="fa fa-circle-o"></i>Category List</a></li>
          </ul>
        </li>

		    <li class="treeview <?php echo ($currentMainMenu == 'main_channel_video')?'active':''; ?>">
          <a href="#">
            <i class="fa fa-video-camera"></i>
            <span>Manage Video</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($currentSubMenu == 'sub_channel_video')?'active':''; ?>"><a href="<?= base_url('admin/channel_video_list'); ?>"><i class="fa fa-circle-o"></i>Pending Approval</a></li>
            <li class="<?php echo ($currentSubMenu == 'sub_approved_video')?'active':''; ?>"><a href="<?= base_url('admin/approved_video_list'); ?>"><i class="fa fa-circle-o"></i>Approved Videos</a></li>
            <li class="<?php echo ($currentSubMenu == 'sub_rejected_video')?'active':''; ?>"><a href="<?= base_url('admin/rejected_video_list'); ?>"><i class="fa fa-circle-o"></i>Rejected Videos</a></li>

          </ul>
        </li>

		    <li class="treeview <?php echo ($currentMainMenu == 'main_genre')?'active':''; ?>">
          <a href="#">
            <i class="fa fa-tags"></i>
            <span>Manage Genre</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($currentSubMenu == 'genre_list')?'active':''; ?>"><a href="<?= base_url('admin/genre_list'); ?>"><i class="fa fa-circle-o"></i>Genre List</a></li>
          </ul>
        </li>
		    <li class="treeview <?php echo ($currentMainMenu == 'support')?'active':''; ?>">
          <a href="#">
            <i class="fa fa-commenting"></i>
            <span>Manage Support</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($currentSubMenu == 'department')?'active':''; ?>"><a href="<?= base_url('admin/department'); ?>"><i class="fa fa-circle-o"></i>Department</a></li>
            <li class="<?php echo ($currentSubMenu == 'team_list')?'active':''; ?>"><a href="<?= base_url('admin/support'); ?>"><i class="fa fa-circle-o"></i>Team List</a></li>
            <li class="<?php echo ($currentSubMenu == 'support_ticket')?'active':''; ?>"><a href="<?= base_url('admin/support_ticket'); ?>"><i class="fa fa-circle-o"></i>Support Ticket</a></li>
          </ul>
        </li>
        <li class="treeview <?php echo ($currentMainMenu == 'flags')?'active':''; ?>">
          <a href="#">
            <i class="fa fa-commenting"></i>
            <span>Manage Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($currentSubMenu == 'flags_group')?'active':''; ?>"><a href="<?= base_url('admin_setting/flags_group'); ?>"><i class="fa fa-circle-o"></i>Flag & Report Group</a></li>
          </ul>
        </li>

        <li class="treeview <?php echo ($currentMainMenu == 'store')?'active':''; ?>">
          <a href="#">
            <i class="fa fa-shopping-bag"></i>
            <span>Manage Store</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($currentSubMenu == 'store_request')?'active':''; ?>"><a href="<?= base_url('admin_store/store_request'); ?>"><i class="fa fa-circle-o"></i>User Request For Store</a></li>
            <li class="<?php echo ($currentSubMenu == 'store_attributes')?'active':''; ?>"><a href="<?= base_url('admin_store/attributes'); ?>"><i class="fa fa-circle-o"></i>Attributes</a></li>
            <li class="<?php echo ($currentSubMenu == 'store_attribute_terms')?'active':''; ?>"><a href="<?= base_url('admin_store/attribute_terms'); ?>"><i class="fa fa-circle-o"></i>Attribute Terms</a></li>
            <li class="<?php echo ($currentSubMenu == 'store_categories')?'active':''; ?>"><a href="<?= base_url('admin_store/categories'); ?>"><i class="fa fa-circle-o"></i>Categories</a></li>
          </ul>
        </li>

        <li class="treeview <?php echo ($currentMainMenu == 'blogs')?'active':''; ?>">
          <a href="#">
            <i class="fa fa-shopping-bag"></i>
            <span>Manage Blogs</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($currentSubMenu == 'blog_category')?'active':''; ?>"><a href="<?= base_url('admin_blog/create_blog_category'); ?>"><i class="fa fa-circle-o"></i>Create Blog Category</a></li>
          </ul>
        </li>

        <li class="header">SETTING</li>
        <li><a href="<?= base_url('admin_setting/main'); ?>"><i class="fa fa-circle-o text-red"></i> <span>Setting</span></a></li>
        <li><a href="<?= base_url('admin_setting/pages'); ?>"><i class="fa fa-circle-o text-yellow"></i> <span>Cover Videos</span></a></li>
        <li><a href="<?= base_url('admin_setting/helpAndFaq'); ?>"><i class="fa fa-circle-o text-aqua"></i> <span>Help And Faq</span></a></li>
        <li><a href="<?= base_url('admin_setting/home_sliders'); ?>"><i class="fa fa-circle-o text-green"></i> <span>Homepage Slider</span></a></li>
        <li><a href="<?= base_url('admin_mrss'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>MRSS FEED Generator</span></a></li>
        <li><a href="<?= base_url('admin_filmhub'); ?>"><i class="fa fa-circle-o text-yellow"></i> <span>Filmhub</span></a></li>
        <li><a href="<?= base_url('admin_notification/firebase'); ?>"><i class="fa fa-circle-o text-blue"></i> <span>Send Push Notification</span></a></li>
        <li class="header">FINANCE</li>
        <li><a href="<?= base_url('admin_finance/report'); ?>"><i class="fa fa-circle-o text-fuchsia"></i> <span>Finance Report</span></a></li>


      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
