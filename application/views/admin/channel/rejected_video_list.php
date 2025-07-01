<style>
  .thumbnails{
    width:100px;
  }
  .author{
    width:116px;
  }
  .btn-width{
    width:106px;
  }
  </style>
<div class="content-wrapper">
<?php 	
	$checkItemData = (isset($page_menu))?explode('|' , $page_menu):array(); 
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:'';
?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <?= $pageTitle; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url('admin') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a><?= $pageTitle; ?></a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?= $pageTitle; ?></h3>
            </div>
            <!-- /.box-header -->
            <input type="hidden" value="rejected" name="page" class="filter">
            <div class="box-body table-responsive">
              <table class="table dt-responsive hover display dataTableAjax" data-action-url="admin/access_channel_video_list/2" data-target-section="tbody" data-column-class="[{className: 'thumbnails'},{className: 'author'},{className: 'title'},{className: 'mode'},{className: 'genre'},{className: 'age_restr'},{className: 'duration'},{className: 'view'},{className: 'action'}]"  data-refresh-dataTablePosition='0' data-orders="[[0,'DESC']]" data-filter="1">
                <thead>
					<tr>
					  <th class="thumbnails">Thumbnail</th>
					  <th class="author">Author</th>
					  <th class="title">Title</th>
					  <th class="mode">Mode</th>
					  <th class="genre">Genre</th>
					  <th class="age_restr">Age Restriction</th>
					   <th class="duration">Duration(in min)</th>
					  <th class="view">View</th>
					  <th class="action">Action</th>
					  <!--th class="Status">Status</th-->
					  
					</tr>
                </thead>
                <tbody>
                
                </tfoot>
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

         
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
<div class="modal fade" id="channel_video" role="dialog">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">	
	
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Video Details</h4>
	  </div>
	  <div class="modal-body dis_user_data_modelbody">
		<div class="box-body">
		
		
		<div class="row">
		<div class="col-md-12">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" id="background" style="background: url(https://adminlte.io/themes/AdminLTE/dist/img/photo1.png) center center; --darkreader-inline-bgcolor: initial;" data-darkreader-inline-bgcolor="">
              <!--h3 class="widget-user-username">Elizabeth Pierce</h3>
              <h5 class="widget-user-desc">Web Designer</h5-->
            </div>
            <div class="widget-user-image">
              <img class="img-circle" id="profile" src="https://adminlte.io/themes/AdminLTE/dist/img/user3-128x128.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">3,200</h5>
                    <span class="description-text">SALES</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">13,000</h5>
                    <span class="description-text">FOLLOWERS</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header">35</h5>
                    <span class="description-text">PRODUCTS</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
		  <table class="table table-striped">
				<thead>
				  <tr>
					<th style="width:50%">#</th>
					<th>Details</th>
				  </tr>
				</thead>
				<tbody id="video_detail">
				  
				</tbody>
			  </table>
		</div>
       </div>
      </div>
	 </div>
	   <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	
	  </div>
	</div>
	 
	
	</div>
	</div>
	
</script>