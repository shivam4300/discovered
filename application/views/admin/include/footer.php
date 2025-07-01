
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="<?= base_url(); ?>">Discovered</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->

      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->
<?php 
	$checkItemData = (isset($page_menu))?explode('|' , $page_menu):array();
	$currentMainMenu = (isset($checkItemData[0]))?$checkItemData[0]:'';
	$currentSubMenu = (isset($checkItemData[1]))?$checkItemData[1]:'';
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:'';
	$currentPageName = (isset($checkItemData[3]))?$checkItemData[3]:'';
?>

<script src="<?= base_url(); ?>repo_admin/js/jquery.min.js"></script>
<script src="<?= base_url(); ?>repo_admin/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>repo_admin/plugins/js/fastclick.js"></script>
<script src="<?= base_url(); ?>repo_admin/plugins/js/jquery.slimscroll.min.js"></script>
<script src="<?= base_url(); ?>repo_admin/js/adminlte.js"></script>
<script src="<?= base_url(); ?>repo_admin/js/demo.js"></script>
<!--script src="<?= base_url(); ?>repo_admin/plugins/js/toastr.min.js"></script-->
<script src="<?= base_url(); ?>repo_admin/js/valid.js"></script>
<script src="<?= base_url(); ?>repo_admin/js/sweetalert.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>repo/js/plugin/magnific_popup/jquery.magnific-popup.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>repo/js/plugin/magnific_popup/swiper-magnific-popup.js"></script>	
<script src="<?= base_url(); ?>repo_admin/plugins/js/jqueryUi/jquery-ui.min.js"></script>

<?php if(in_array($currentPageName , array('channel_video'))){ ?>
  <script src="<?php echo base_url('repo/js/highlight.js') ?>"> </script>
<?php } ?>

<?php 
if(in_array($currentPageName,array('report','Support','department','dashboard','userlist','flags_group'))){  ?>
	<script type="text/javascript" src="<?= base_url();?>repo/js/plugin/moment/moment.min.js"></script>
	<script type="text/javascript" src="<?= base_url();?>repo/js/daterangepicker.js"></script>
<?php } ?>

<?php if(in_array($currentPageName , array('dashboard'))){ ?>
<script src="<?= base_url(); ?>repo_admin/plugins/js/Chart.js"></script>
<script src="<?= base_url(); ?>repo_admin/js/pages/dashboard.js"></script>
<script src="<?= base_url(); ?>repo_admin/plugins/js/jquery.sparkline.min.js"></script>
<script src="<?= base_url(); ?>repo_admin/plugins/js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?= base_url(); ?>repo_admin/plugins/js/jquery-jvectormap-world-mill-en.js"></script> 

<?php } ?>

 
<?php if(in_array($currentPageName , array('pagesetting'))){ ?>
<script type="text/javascript" src="<?= base_url(); ?>repo/js/dropzone/dropzone.js"></script>
<?php } ?>

<?php if(in_array($currentPageName , array('pagesetting','approved_video','report','HomepageSlider','userlist','mrss_generator','firebase_notification','mainsetting','flags_report','filmhub_list'))){ ?>
  <script type="text/javascript" src="<?= base_url(); ?>repo_admin/js/select2.min.js"></script>
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
<script src="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

<?php } ?>


<?php if(in_array($currentPageName , array('helpAndFaq'))){ ?>
  <script src="https://cdn.ckeditor.com/4.13.1/full/ckeditor.js"></script>
<?php }?>



<?php 
if(in_array($currentPageName,array('HomepageSlider'))){  ?>
	<script type="text/javascript" src="<?= base_url('repo/js/bootstrap-tokenfield.min.js'); ?>"></script>;
<?php } ?>

<?php 
if(!in_array($currentPageName,array('dashboard'))){  ?>
	<script src="<?= base_url(); ?>repo_admin/js/main_custom.js"></script> 
<?php } ?>

<?php 
if(in_array($currentPageName,array('flags_group','mrss_generator','mainsetting','filmhub_list','pagesetting'))){  ?>
	<script src="<?= base_url(); ?>repo_admin/js/admin.js"></script> 
<?php } ?>

<?php 
if(in_array($currentPageName,array('Support','department'))){  ?>
  <script type="text/javascript" src="<?= base_url('repo/js/support.js'); ?>"></script>;
<?php } ?>

</body>
</html>