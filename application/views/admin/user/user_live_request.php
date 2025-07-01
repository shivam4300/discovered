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
			<div class="box-body table-responsive">
			
              <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin/access_user_live_request" data-target-section="tbody" data-column-class="[{className: 'sr'},{className: 'name'},{className: 'live_url'},{className: 'is_live'},{className: 'status'},{className: 'info'},{className: 'action'}]"  data-refresh-dataTablePosition='0' data-filter="user_live" data-orders="[[3,'DESC']]" data-sort="[{ targets: [0], orderable: false}]">
                <thead>
					<tr>
					  <th class="sr">#</th>
					  <th class="name">Name</th>
					  <th class="live_url">Live URL</th>
					  <th class="is_live">Is_live</th>
					  <th class="status">Status</th>
					  <th class="info">Info</th>
					  <th class="action">Action</th>
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
  
		
		  
		  
	