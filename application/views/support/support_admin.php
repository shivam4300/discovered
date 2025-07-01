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
              <button type="button" class="btn btn-flat btn-default btn-right add_support_form" >Create Support Profile</button>
            </div>
            <!-- /.box-header -->
			
            <div class="box-body table-responsive">
              <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="support/access_support_team" data-target-section="tbody" data-column-class="[{className: 'sr'},{className: 'name'},{className: 'email'},{className: 'view'},{className: 'action'},{className: 'mail'},{className: 'login_support'}]"  data-refresh-dataTablePosition='0' data-orders="[[0,'DESC']]" data-sort="[{ targets: [0,1,2], orderable: false}]">
                <thead>
					<tr>
					  <th class="sr">#</th>
					  <th class="name">Name</th>
					  <th class="email">Email</th>
					  <th class="view">Edit</th>
					  <th class="action">Status</th>
					  <th class="mail">Send Email </th>
					  <th class="login_support">Login</th>
					</tr>
                </thead>
                <tbody></tbody>
                
                
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
  
 <div class="modal fade" id="user_form" role="dialog">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">	
	<form action="support/createSupportTeam" method="POST" class="myFormList" data-model-hide="#user_form">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">User Details</h4>
	  </div>
	  <div class="modal-body dis_user_data_modelbody">
		<div class="box-body">
		<input type="hidden" name="id">
        <div class="row">
            <div class="col-md-3">
				<div class="form-group">
					<label>Department</label>
					<?php //print_r($department);?>
					<select class="form-control" name="support_department">
						<option value="">Select Department</option>	
						<?php foreach($department as $key => $value){ ?>
						  <option value="<?=$value['id']?>"><?=$value['name']?></option>	
						<?php } ?>
					<select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>User Name</label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="user_name" >
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>User Email</label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="user_email" >
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" placeholder="Enter ..." value="" name="password" >
				</div>
			</div>        
            
			
          </div>
          <!-- /.row -->
        </div>
	  </div>
		</form>  
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> 
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content" data-refresh-content="dataTable" data-refresh-dataTablePosition="0">Create</button>
	  </div>
	</div>

	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
