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
          <div class="box tab-pane">
            <div class="box-header">
              <h3 class="box-title"><?= $pageTitle; ?></h3>
              <button type="button" class="btn btn-flat btn-default btn-right add_user_form" >Create Official Profile</button>
            </div>
            <!-- /.box-header -->
			<?php
				$tableArray = array(
					'sr' => '#',
					'name' => 'Name',
					'email' => 'Email',
					'phone' => 'Contact',
					//'level' => 'Category',
					'acctype' => 'Acc Type',
					//'type' => 'Type',
					//'country' => 'Country',
					//'state' => 'State',
					'tcv' => 'Video Count',
					'createdat' => 'Joining Date',
					'deleteddat' => 'Deleted Date',
					'view' => 'Edit',
					'iva' => 'Is Iva',
					'epa' => 'Is Ele',
					'action' => 'Status',
					'founder' => 'Is FC',
					'delete' => 'Delete',
					'login' => 'Login'
				);
				$tableindex = array(
					'sr' => true,
					'name' => true,
					'email' => true,
					'phone' => false,
					//'level' => true,
					'acctype' => true,
					//'type' => true,
					//'country' => false,
					//'state' => false,
					'tcv' => false,
					'createdat' => true,
					'deleteddat' => false,
					'view' => true,
					'iva' => false,
					'epa' => false,
					'action' => true,
					'founder' => false,
					'delete' => false,
					'login' => true
				); 
			?>
            <div class="box-body table-responsive">
				<div class="row user_listpg_sec">
					<div class="col-md-7">
						<div class="col-md-3">
							<div class="form-group hide">
								<div class="box-header title_heading">
									<h3 class="box-title">User Status</h3>
								</div>
								<select class="form-control filter input-lg" name="user_status">
									<option value='1' selected>Active</option>
									<option value='2' >Inactive</option>
									<option value='3' >Blocked</option>
									<option value='4' >Inactive Icon</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group hide">
								<div class="box-header title_heading">
									<h3 class="box-title">Select Giveaways</h3>
								</div>
								<select class="form-control filter input-lg" name="is_giveaways">
									<option value=''>Select Giveaways</option>
									<option value='1'>Giveaways</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<div class="box-header title_heading">
									<h3 class="box-title">Select Date</h3>
								</div>
								<div class="form-group"> 
									<input class="form-control input-lg daterange" type="text" autocomplete="off" name="date_range" placeholder="Select A Date Range" value="">	
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="box-header title_heading">
									<h3 class="box-title">Records</h3>
								</div>
								<div class="form-group"> 
									<div class="box-tools">
										<button type="button" class="btn btn-flat btn-default btn-right input-lg" id="TotalRecord">Total Records : 0</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<div class="col-md-12">
								<div class="box-header title_heading">
									<h3 class="box-title">Visible table columns</h3>
								</div>
							</div>
							<div class="col-md-12">
								<div class="multi-checkbox">
									<select multiple data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Column',allowHtml:true,allowClear:false,dropdownParent:  $(this).parent()}" class="form-control input-lg ShowHideCal" >
									<?php
										$m	=	0;
										$targets = []; 
										$className = '[';
										foreach($tableArray as $k => $v){
											$select = '';
											if($tableindex[$k] == false){
												array_push($targets,$m);
											}else{
												$select = 'selected';
											}
											echo '<option '. $select .' value="'.$m.'">'.$v.'</option>';
											$m++;

											$className .= "{ className :'". $k ."'}," ;
										}

										$targets = implode(',',$targets);
										$className .= ']';
										
										
									?>
									</select>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<table class="table table-hover  dt-responsive nowrap hover display dataTableAjax" data-action-url="admin/access_userlist/1" 
					data-target-section="tbody" 
					data-column-class=" <?=$className;?>"  
					data-refresh-dataTablePosition='0' data-filter="filter" data-orders="[[13,'DESC']]" data-sort="[{ targets: [5,7,8,9,10,11,12], orderable: false},{targets : [ <?=$targets;?> ],visible: false}]">
					<thead> 
						<tr>
							<?php
								foreach($tableArray as $k => $v){
									echo ' <th class="'.$k .'">'.$v.'</th>';
								}
							?>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
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
	<form action="admin/updateUserDetails" method="POST" class="myFormList" data-model-hide="#user_form">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">User Details</h4>
	  </div>
	  <div class="modal-body dis_user_data_modelbody">
		<div class="box-body">
		
		
		<div class="row" id="videoAndImage">
		<div class="col-md-12">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black dis_user_header">
			<video width="320" height="240" autoplay loop id="uc_video">
			  <source src="" type="video/mp4" >
			</video>
              <h3 class="widget-user-username " id="user_name">Add User</h3>
              <h5 class="widget-user-desc" id="category_name">Official</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" id="uc_pic" src="<?= base_url('repo/images/user/user.png'); ?>" alt="User Avatar">
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
          <!-- /.widget-user -->
        </div>
        </div>
	
        <div class="row">
            <div class="col-md-3">
				<div class="form-group">
					<label>Uniq User Name</label>
					<input type="text" class="form-control" value="" name="user_uname"  placeholder="Enter ..." >
					<input type="hidden" class="form-control"  name="user_id"  value="" >
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
					<label>User Phone</label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="user_phone">
				</div>
			</div>           
            <div class="col-md-3">
				<div class="form-group">
					<label>User Address </label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="user_address">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>User Country</label>
					<select class="form-control SelectBySimpleSelect" name="uc_country" data-select-url="admin/getStateFromCountry" data-id="#uc_states">
					<?php 
					echo '<option value="">Select Country</option>';	
						foreach($country_list as $list){
							echo '<option value="'.$list['country_id'].'">'.$list['country_name'].'</option>';	
						}
						
					?>
						
					<select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>User State</label>
					<select class="form-control" name="uc_state" id="uc_states">
					 <?php 
						// foreach($state_list as $list){
							// echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';	
						// }
						
					?>
						
					<select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>User City</label>
					<input type="text" class="form-control" placeholder="Enter ..." name="uc_city">
				</div>
            </div>
			<div class="col-md-9">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>User Register Date</label>
							<input type="text" class="form-control" placeholder="Enter ..." name="user_regdate" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>User Website</label>
							<input type="text" class="form-control" placeholder="Enter ..." name="uc_website">
						</div>
					</div>
				   <div class="col-md-4">
						<div class="form-group">
							<label>Account type</label>
							<select class="form-control SelectBySimpleSelect" name="user_level" data-select-url="admin/getArtistSubCategory" data-id="#getArtistSubCategory">
							<?php 
								echo '<option value="">Select Category</option>';	
								foreach($artist_category_list as $list){
									echo '<option value="'.$list['category_id'].'">'.$list['category_name'].'</option>';	
								}
								
							?>
							<select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>User description</label>
							<textarea name="uc_about" class="form-control" id="uc_about"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label> User Designation</label>
					<select class="form-control dis_multi_select" name="uc_types[]" multiple="multiple" id="getArtistSubCategory">
					
					<select>
				</div>
			</div>
			
          </div>
          <!-- /.row -->
        </div>
	  </div>
		</form>  
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> 
		<button  type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content" data-refresh-content="dataTable" data-refresh-dataTablePosition="0">Create Official Profile</button>
	  </div>
	</div>

	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
