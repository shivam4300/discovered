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
			'deletedate' => 'Delete Date',
			'view' => 'Edit',
			'iva' => 'Is Iva',
			'epa' => 'Is Ele',
			'action' => 'Status',
			'founder' => 'Is FC',
			'delete' => 'Delete',
			'login' => 'Login',
			'register_by' => 'Registered By'
		);
		$tableindex = array(
			'sr' => true, 
			'name' => true,
			'email' => true,
			'phone' => false,
			//'level' => false,
			'acctype' => true,
			//'type' => false,
			//'country' => false,
			//'state' => false,
			'tcv' => false,
			'createdat' => true,
			'deletedate' => false,
			'view' => true,
			'iva' => false,
			'epa' => false,
			'action' => true,
			'founder' => false,
			'delete' => false,
			'login' => true,
			'register_by' => 'Registered By'
		); 
	?>
    <!-- Main content -->
    <section class="content">
      	<div class="row">
        	<div class="col-xs-12">
          		<div class="box tab-pane">
            		<!-- /.box-header -->
						<div class="box-body">
							<div class="row user_listpg_sec">
								<ul class="dis_utlist">
									<li>
										<div class="form-group">
											<div class="box-header title_heading">
												<h3 class="box-title">User Status</h3>
											</div>
											<select class="form-control filter input-lg" name="user_status">
											<option value='1' selected>Active</option>
													<option value='2' >Inactive</option>
													<option value='3' >Blocked</option>
													<!-- <option value='4' >Inactive Icon</option> -->
													<option value='5'>Incomplete</option>
													<option value='6'>Deleted</option>
													<!--option value='5' >Giveaways</option-->
											</select>
										</div>
									</li>
									<li>
										<div class="form-group">
											<div class="box-header title_heading">
												<h3 class="box-title">Account Type</h3>
											</div>
											<select class="form-control filter input-lg" name="user_acc_type">
												<option value=''>Account Type</option>
												<option value='standard'>Standard</option>
												<option value='express'>Express</option>
											</select>
										</div>
									</li>
									<li>
										<div class="form-group">
											<div class="box-header title_heading">
												<h3 class="box-title">Giveaways</h3>
											</div>
											<select class="form-control filter input-lg" name="is_giveaways">
												<option value=''>Select Giveaways</option>
												<option value='1'>Giveaways</option>
											</select>
										</div>
									</li>
									<li>
										<div class="form-group">
											<div class="box-header title_heading">
												<h3 class="box-title">User Type</h3>
											</div>
											<select class="form-control filter input-lg" name="user_type">
												<option value=''>User Type</option>
												<?php 
												if(isset($artist_category_list)){
													foreach($artist_category_list as $list){
														echo '<option value="'.$list['category_id'].'">'.$list['category_name'].'</option>';
													}
												}
												?>
											</select>
										</div>
									</li>
									<!-- <li>
										<div class="form-group">
											<div class="box-header title_heading">
												<h3 class="box-title">Incomplete User</h3>
											</div>
											<select class="form-control filter input-lg" name="incomplete_user">
												<option value=''>Select User</option>
												<option value='1'>Incomplete</option>
											</select>
										</div>
									</li> -->
									<li>
										<div class="form-group">
											<div class="box-header title_heading">
												<h3 class="box-title">Date Range</h3>
											</div>
											<div class="form-group"> 
												<input class="form-control input-lg daterange" type="text" autocomplete="off" name="date_range" placeholder="Date Range" value="">	
											</div>
										</div>
									</li>
									<li class="visible_records">
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
									</li>
									<li class="visible_column">
										<div class="form-group">											
											<div class="box-header title_heading">
												<h3 class="box-title">Visible table columns</h3>
											</div>											
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
									</li>
									<li><div class="form-group">
										<div class="box-header title_heading">
												<h3 class="box-title">Export All</h3>
											</div>
											<div class="form-group"> 
												<div class="box-tools">
													<a href="<?= base_url('admin/export_user_details'); ?>" type="button" class="btn btn-flat btn-primary btn-right">Export</a>
												</div>
											</div>
										</div>
									</li>
								</ul>
								
							</div>
						</div>
            			<div class="box-body table-responsive">
			
							<table class="table table-hover  dt-responsive nowrap hover display dataTableAjax" data-action-url="admin/access_userlist/0" 
							data-target-section="tbody" 
							data-column-class=" <?=$className;?>"  
							data-refresh-dataTablePosition='0' data-filter="filter" data-orders="[[6,'DESC']]" data-sort="[{ targets: [7,8,9,10,11,12], orderable: false},{targets : [ <?=$targets;?> ],visible: false}]">
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
		
		
		<div class="row">
		<div class="col-md-12">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black dis_user_header" style="height: auto;">
			<video width="100%" height="100%" autoplay loop muted >
			  <source src="" type="video/mp4" id="aws_s3_profile_video">
			</video>
              <!--h3 class="widget-user-username " id="user_name">Elizabeth Pierce</h3>
              <h5 class="widget-user-desc" id="category_name">Web Designer</h5-->
            </div>
            <div class="widget-user-image" id="uc_pic">
              
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">3,200</h5>
					<span class="description-text">FOLLOWERS</span>
                   
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
				    <h5 class="description-header">3,200</h5>
                    <span class="description-text">FOLLOWING</span>
                    
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header">35</h5>
                    <span class="description-text">POSTS</span>
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
					<input type="text" class="form-control" value="" name="user_uname" readonly>
					<input type="hidden" class="form-control" placeholder="Enter ..." name="user_id"  value="" readonly>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>User Name</label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="user_name" readonly>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>User Email</label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="user_email" readonly>
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
							<label>Artist Type</label>
							<select class="form-control SelectBySimpleSelect" name="user_level" data-select-url="admin/getArtistSubCategory" data-id="#getArtistSubCategory">
							<?php 
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
					<label>Artist Sub-Type</label>
					<select class="form-control dis_multi_select" name="uc_types[]" multiple="multiple" id="getArtistSubCategory">
					
					<select>
				</div>
			</div>
			
          </div>
		  
          <!-- /.row -->
		<div class="row">
			<div class="col-xs-12">	
				<h2 class="dis_title">Reference Details</h2>
			</div>
			<div class="col-md-4">	
				<div class="form-group">
					<label>Refrence Name</label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="uc_name">
				</div>
			</div>	
			<div class="col-md-4">	
				<div class="form-group">
					<label>Refrence Phone</label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="uc_phone">
				</div>
			</div>
			<div class="col-md-4">	
				<div class="form-group">
					<label>Refrence Email</label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="uc_email">
				</div>
			</div>
		</div>
		<!--div class="row">	
			<div class="col-md-6">
				<div class="form-group">
					<label>Refrence First Address </label>
					<input type="text" class="form-control" placeholder="Enter ..." name="uc_addr1"  value="" >
				</div>
			</div>	
			<div class="col-md-6">
				<div class="form-group">
					<label>Refrence Second Address </label>
					<input type="text" class="form-control" placeholder="Enter ..." value="" name="uc_addr2">
				</div>
			</div>
		</div-->
		
		
		  
		  
		  
		  
        </div>
	  </div>
	  </form>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button  type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content" data-refresh-content="dataTable" data-refresh-dataTablePosition="0">Save changes</button>
	  </div>
	</div>
	
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>