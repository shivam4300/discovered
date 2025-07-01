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
            <div class="box-body table-responsive tab-pane">
			<input type="hidden" value="approved" name="page" class="filter">
			<div class="row">
				<div class="col-xs-2">
					<div class="form-group">
					  <label>Select Mode</label>
					  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Mode',allowHtml:true,allowClear:true}" name="mode"  class="form-control filter" data-action-url="admin/getGenreList" data-id="#user_genres">
							<?php 
							if(isset($web_mode)){
								echo '<option value=""></option>';
								foreach($web_mode as $list){
									echo '<option value="'.$list['mode_id'].'">'.$list['mode'].'</option>';
								}
							}
							?>
						</select>	
					</div>
				</div>
				<div class="col-xs-2">
					<div class="form-group">
					  <label>Select User Type</label>
					  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User Type',allowHtml:true,allowClear:true}" name="user_level"  class="form-control filter" data-action-url="admin/getUserByCategory" data-id="#user_list" data-placeholder="Select User">
							<?php 
							if(isset($category)){
								echo '<option value=""></option>';
								foreach($category as $list){
									echo '<option value="'.$list['category_id'].'">'.$list['category_name'].'</option>';
								}
							}
							?>
						</select>	
					</div>
				</div>
				<div class="col-xs-2">
					<div class="form-group">
					  <label>Select User</label>
					  <select data-target="select2" name="user_id"  class="form-control filter" id="user_list">
						<?php echo '<option value=""></option>'; ?>
						</select>	
					</div>
				</div>
				
				<div class="col-xs-2">
					<div class="form-group">
					  <label>Select Feature video</label>
					  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Video',allowHtml:true,allowClear:true}" name="featured_by_admin"  class="form-control filter" >
							<?php 
								 echo '<option value=""></option>'; 
								 echo '<option value="1">Featured</option>'; 
								 echo '<option value="0">Not Featured</option>'; 
							?>
						</select>	
					</div>
				</div>
				<div class="col-xs-2">
					<div class="form-group">
					  <label>Select Genre</label>
					  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Video',allowHtml:true,allowClear:true}" name="genre"  class="form-control filter" id="user_genres">
							<?php
								echo '<option value=""></option>';  
								// foreach($genre as $key=>$value){
								// 	// echo '<option value="'.$value['genre_id'].'">'.$value['genre_name'].'</option>'; 
								// }
							?>
						</select>	
					</div>
				</div>
				<div class="col-xs-2">
					<div class="form-group">
						<div class="box-header title_heading">
							<h3 class="box-title">Export All</h3>
						</div>
						<div class="form-group"> 
							<div class="box-tools">
								<a href="<?= base_url('admin/export_video_details'); ?>" type="button" class="btn btn-flat btn-primary btn-right">Export</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xs-2">
					<div class="form-group"> 
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-flat btn-default btn-right input-lg" id="TotalRecord">Total Records : 0</button>
						</div>
					</div>
				</div>
			</div>
			<div class="row">	
			<div class="col-md-12">
              <table class="table dt-responsive hover display dataTableAjax" data-action-url="admin/access_channel_video_list/1" data-target-section="tbody" data-column-class="[{className: 'thumbnails'},{className: 'author'},{className: 'title'},{className: 'mode'},{className: 'genre'},{className: 'age_restr'},{className: 'duration'},{className: 'view'},{className: 'status'},{className: 'action'}]"  data-refresh-dataTablePosition='0' data-filter='filter' data-orders="[[0,'DESC']]">
			  
                <thead>
					<tr>
					  <th class="thumbnails" width="250px">Thumbnail</th>
					  <th class="author">Author</th>
					  <th class="title">Title</th>
					  <th class="mode">Mode</th>
					  <th class="genre">Genre</th>
					  <th class="age_restr">Age Restriction</th>
					  <th class="duration">Duration</th>
					  <th class="view">View</th>
					  <th class="status">Status</th>
					  <th class="action">Action</th>
				 	</tr>
                </thead>
                <tbody>
                
                </tbody>
              
              </table>
			  
             </div>
             </div>
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