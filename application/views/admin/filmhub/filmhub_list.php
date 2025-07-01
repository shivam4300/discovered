<style>
.btn-right{
	float:right;
	margin-left:5px;
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
			<div class="box-body">
				<div class="row">
					<div class="col-md-3">
						<select class="form-control FilterTableData filter" name="parent_id" data-url="admin_filmhub/access_filmhublist">
							<option value='' selected>ALL</option>
							<option value='1' >Pending</option>
							<option value='2' >Processing</option>
							<option value='3' >Complete</option>
						    <option value='4' >Failed</option> 
						</select>
					</div>
					<div class="col-md-3">
						<select class="form-control FilterTableData filter" name="type" data-url="admin_filmhub/access_filmhublist">
							<option value='' selected>ALL</option>
							<option value='Series' >Series</option>
							<option value='Single Work' >Single Work</option>
							<option value='Episode' >Episode</option>
							<option value='Movie' >Movie</option>
							<option value='Show' >Show</option>
						</select>
					</div>
					<div class="col-md-6">
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-flat btn-default btn-right" id="refresh_filmhub_list" data-film-url="filmhub/refreshFilmhubObjectList">Refresh</button>
							<!--button type="button" class="btn btn-flat btn-default btn-right"  data-toggle="modal"  data-target="#subcategory_form" data-action-url="admin/get_categorylist" data-target-section="#categorylist">Add Sub Category</button>
							<button type="button" class="btn btn-flat btn-default btn-right"  data-toggle="modal"  data-target="#sub-subcategory_form" data-action-url="admin/get_categorylist/1" data-target-section="#categorylist1">Add Sub Sub-Category</button-->
						</div>
					</div>
				</div>
			</div>
			
            <div class="box-body table-responsive">
			  <table class="table nowrap hover display dataTableAjax" data-refresh-dataTablePosition='0' data-action-url="admin_filmhub/access_filmhublist" data-filter="true" data-target-section="tbody" data-column-class="[{className: 'sr'},{className: 'type'},{className:'prefix'},{className:'assign_to'},{className: 'status'},{className:'action'}]" >
                <thead>
					<tr>
					  	<th class="sr">#</th>
					  	<th class="type">Type</th>
					  	<th class="prefix">Prefix</th>
						<th class="assign_to">Assigned To User</th>
					  	<th class="status">Status</th>
					  	<th class="action">Action</th>	
					</tr>
                </thead>
                <tbody>
                
                </tfoot>
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
<div class="modal fade" id="select_user_for_filmhub_popup" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Users</h4>
	  </div>
	  <div class="modal-body">
	  	<form action="filmhub/ingest_film" method="POST" class="myFormList" data-reset="1" id="filmhub_form">
	 	 	<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Prefix Name</label>
							<input type="text" class="form-control require" value="" name="prefix_name" placeholder="Prefix Name ..." id="prefix_name" readonly>
						</div>
						<div class="form-group SelectArea" id="Users_area"> 
							<input type="hidden" name="film_id" id="film_id" value="">
							<label>User list</label>
							<select class="form-control require js-data-ajax" data-ajax--url="admin_setting/getActiveUserList" data-placeholder="Select User List" name="filmhub_uid" id="filmhub_uid">
							<select>
						</div>
						<ul class="list-group" id="listgroup">
							
						</ul>
					</div>
				</div>
        	</div>
		</form>
	  </div>

	  <div class="modal-footer">
		<!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
		<button type="button" class="btn btn-primary getYamlFile">Submit</button>
	  </div>
	</div>
  </div>
</div>



