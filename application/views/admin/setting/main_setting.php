<div class="content-wrapper">
	<?php $checkItemData = (isset($page_menu))?explode('|' , $page_menu):array();  
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:''; ?>
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
					
					<div class="container" style="width: 100%">
						<ul class="nav nav-tabs">
							<?php /*
							<li class="<?php if(!isset($_COOKIE['setTab'])){ echo "active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#home">User Vote Level</a></li>
							<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu1">Video Vote Level</a></li>
							 */ ?>
							<li class="<?php if(!isset($_COOKIE['setTab'])){ echo "active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#home">Website Mode</a></li>
							<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu1">Language List</a></li>
							<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu2'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu2">Creator Ads Rate Plan</a></li>
						</ul>

						<div class="tab-content">
							<?php /*
							<div id="home" class="tab-pane fade <?php if(!isset($_COOKIE['setTab'])){ echo "in active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "in active";} } ?>">
								<div class="box-header">
									<h3 class="box-title">User Vote
										<small>Level</small>
									</h3>
									<div class="pull-right box-tools">
										<!--button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#genremode">Add Genre</button-->
									</div>
								</div>
								
								<div class="box-body table-responsive">
									<table class="table table-bordered table-hover simpleDataTable" >
										<thead>
											<tr>
											<th class="user_type">User Type</th>
											<th class="vote_count">User Vote Count</th>
											<th class="edit">Edit</th>
											<th class="action">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($user_level as $list){
												?>
											<tr>
											<td class="user_type"><?= $list['user_type']; ?></td>
											<td class="vote_count"><?= $list['vote_count']; ?></td>
											<td class="edit"><a  data-modal="1" data-id="<?=  $list['type_id']; ?>" data-vote="<?=  $list['vote_count']; ?>"><i class="fa fa-fw fa-edit"></i></a></td>
											<td class="action"><input <?php echo ($list['status'] == 1)?'checked':''; ?> type="checkbox" data-check-id="<?= $list['type_id']; ?>" data-action-url="admin/updateCheckStatus/user_level_type"></td>
											</tr>	
												
												<?php
											}?>
										</tbody>
									</table>
								</div>
							</div>
							
							<div id="menu1" class="tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "in active";} } ?> ">
								<div class="box-header">
									<h3 class="box-title">Video Vote
										<small>Level</small>
									</h3>
									<div class="pull-right box-tools">
										<!--button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#genremode">Add Genre</button-->
									</div>
								</div>	
								
								<div class="box-body table-responsive">
									<table class="table table-bordered table-hover simpleDataTable" >
										<thead>
											<tr>
											<th class="genre">Video Genre</th>
											<th class="vote_count">Video Vote Count</th>
											<th class="edit">Edit</th>
											<th class="action">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($video_level as $list){
												?>
												<tr>
												<td class="genre"><?= $list['genre']; ?></td>
												<td class="vote_count"><?= $list['vote_count']; ?></td>
												<td class="edit"><a  data-modal="2" data-id="<?=  $list['chart_id']; ?>" data-vote="<?=  $list['vote_count']; ?>"><i class="fa fa-fw fa-edit"></i></a></td>
												<td class="action"><input <?php echo ($list['status'] == 1)?'checked':''; ?> type="checkbox" data-check-id="<?= $list['chart_id']; ?>" data-action-url="admin/updateCheckStatus/video_level_chart"></td>
												</tr>	
												<?php
											}?>
										</tbody>
									</table>
								</div>
							</div> 
 							*/ ?>
							<div id="home" class="tab-pane fade <?php if(!isset($_COOKIE['setTab'])){ echo "in active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "in active";} } ?> ">
								<div class="box-header">
								<h3 class="box-title">Website
									<small>Mode</small>
								</h3>
								<div class="pull-right box-tools">
									<!--button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#genremode">Add Genre</button-->
								</div>
								</div>
								<div class="box-body table-responsive">
									<table class="table table-bordered table-hover simpleDataTable" >
										<thead>
											<tr>
											<th class="genre">Icon</th>
											<th class="vote_count">Mode</th>
											<th class="action">Action</th>
											<th class="mode">Default Mode</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($website_mode as $list){
												?>
												<tr>
												<td class="genre" style="background-color:black"><img src="<?= base_url('repo/images/mode_icon/'.$list['icon'].'');?>"></td>
												<td class="vote_count"><?= $list['mode']; ?></td>
												<td class="action"><input <?php echo ($list['status'] == 1)?'checked':''; ?> type="checkbox" data-check-id="<?= $list['mode_id']; ?>" data-action-url="admin/updateCheckStatus/website_mode"></td>
												<td class="action">
												<input name="mode" <?php echo ($list['default_mode_status'] == 1)?'checked':''; ?> type="radio" data-check-id="<?= $list['mode_id']; ?>" data-action-url="admin/updateCheckStatus/default_mode_status"></td>
												</tr>	
												<?php
											}?>
										</tbody>
									</table>
								</div>
							</div>

							<div id="menu1" class="tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "in active";} } ?> ">
								<div class="box-header">
									<h3 class="box-title">Language
										<small>List</small>
									</h3>
									<div class="pull-right box-tools">
										<!--button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#genremode">Add Genre</button-->
									</div>
								</div>
						
								<div class="box-body table-responsive">
									<table class="table table-bordered table-hover simpleDataTable" >
										<thead>
											<tr>
											<th class="language">Language</th>
											<th class="action">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($language_list as $list){
												?>
												<tr>
												<td class="language"><?= $list['value'] ?></td>
												<td class="action"><input <?php echo ($list['status'] == 1)?'checked':''; ?> type="checkbox" data-check-id="<?= $list['id']; ?>" data-action-url="admin/updateCheckStatus/language_list"></td>
												</tr>	
												<?php
											}?>
										</tbody>
									</table>
								</div>
							</div>
							
							<div id="menu2" class="tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu2'){echo "in active";} } ?> ">
								<div class="box-header">
									<h3 class="box-title">Creator's
										<small>Plan</small>
									</h3>
									<div class="pull-right box-tools">
										<button type="button" class="btn btn-block btn-primary getAdsPlanRate">Add Plan Rate</button>
									</div>
								</div>
						
								<div class="box-body table-responsive">
									<table class="table dt-responsive nowrap hover display dataTableAjax" data-refresh-dataTablePosition='0' data-action-url="admin_setting/access_ads_global_rate_details" data-target-section="tbody" data-column-class="[{className: 'rdetail_id'},{className:'plan_name'},{className:'dtv_discount'},{className:'dtv_share'},{className:'creator_share'},{className:'plan_type'},{className:'country'},{className:'status'},{className:'created_at'},{className:'edit'},{className:'delete'}]">
										<thead>
											<tr>
												<th class="rdetail_id">#</th>
												<th class="plan_name">Plan Name</th>
												<!--th class="cpm">CPM</th>
												<th class="cpa">CPA</th-->
												<th class="dtv_discount">DTV Discount</th>
												<th class="dtv_share">DTV Share</th>	
												<th class="creator_share">Creator Share</th>	
												<th class="plan_type">Plan Type</th>	
												<th class="country">Country</th>	
												<th class="status">Status</th>	
												<th class="created_at">Updated At</th>	
												<th class="edit">Edit</th>	
												<th class="delete">Delete</th>	
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>



<div class="modal fade" id="user_form1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">User Vote count</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin/updateParam/user_level_type" method="POST" class="myFormList" data-redirect="<?= 'admin_setting/main'; ?>">
          <div class="row">
            <div class="col-md-12">
				<div class="form-group">
					<label>Vote Count</label>
					<input type="text" class="form-control require" name="vote_count" value="" placeholder="Enter vote Count ..." id="vote_count1">
					<input type="hidden" name="type_id" id="id1" value="" >
				</div>
			</div>
          </div>
          <!-- /.row -->
        </div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content"  data-modal-button="1" >Save changes</button>
	  </div>
	</div>
  </div>
</div>
 

<div class="modal fade" id="user_form2" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">User Vote count</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin/updateParam/video_level_chart" method="POST" class="myFormList" data-redirect="<?= 'admin_setting/main'; ?>">
          <div class="row">
            <div class="col-md-12">
				<div class="form-group">
					<label>Vote Count</label>
					<input type="text" class="form-control require" name="vote_count" value="" placeholder="Enter vote Count ..." id="vote_count2">
					<input type="hidden" name="chart_id" id="id2" value="" >
				</div>
			</div>
          </div>
          <!-- /.row -->
        </div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content"  data-modal-button="1" >Save changes</button>
	  </div>
	</div>
  </div>
</div> 


<div class="modal fade" id="ADD_PALN" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add Plan</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin_setting/AddAdsPlan" method="POST" class="myFormList" >
          <div class="row">
		    <div class="col-md-6">
				<div class="form-group">
					<label>Plan Name*</label>
					<input type="text" class="form-control require" name="plan_name" value="" placeholder="Enter Plan Name*">
					<input type="hidden" name="rdetail_id" value="" >
				</div>
			</div>
            <!--div class="col-md-6">
				<div class="form-group">
					<label>CPM*</label>
					<input type="text" class="form-control require" name="cpm" value="" placeholder="Enter CPM Amount*" data-type="number">
				</div>
			</div-->
			<div class="col-md-6">
				<div class="form-group">
					<label>DTV Discount*</label>
					<input type="text" class="form-control require" name="dtv_discount" value="" placeholder="Enter DTV Discount Percent*" data-type="number" min="1" max="100" >
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>DTV Share*</label>
					<input type="text" class="form-control require" name="dtv_share" value="" placeholder="Enter DTV Share Percent*" data-type="number" min="1" max="100">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Creator Share*</label>
					<input type="text" class="form-control require" name="creator_share" value="" placeholder="Enter Creator Share Percent*" data-type="number" min="1" max="100" readonly>
				</div>
			</div>
			<?php $plan_type = [1 => 'Country', 2 => 'Users', 3 => 'Videos']; ?>
			<div class="col-md-6">
				<div class="form-group">
					<label>Plan Type</label>
					<select class="form-control" name="plan_type"" id="plan_type">
						<?php
							foreach($plan_type as $key => $val){
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
						?>
					 </select>
				</div>
			</div>
			<div class="col-md-6 SelectArea" id="Country_area">
				<div class="form-group">
					<label>Country</label>
					<select class="form-control" name="country"">
						<?php 
						echo '<option value="">Enter Country(optional)</option>';
						foreach($country_list as $list){ 
							echo '<option value="'.$list["country_id"].'">'.$list["country_name"].'</option>';
						}?>
					</select>
				</div>
			</div>

			<div class="col-md-6 SelectArea" id="Users_area">
				<div class="form-group">
					<label>User list</label>
					<select class="js-data-ajax" data-ajax--url="admin_setting/getActiveUserList" data-placeholder="Select User List"  multiple name="user_ids[]">
					
					</select>
				</div>
			</div>
			
			<div class="col-md-6 SelectArea" id="Videos_area">
				<div class="form-group">
					<label>Video list</label>
					<select class="js-data-ajax" data-ajax--url="admin_setting/getActiveVideoList" data-placeholder="Select Video List" multiple name="video_ids[]"></select>
				</div>
			</div>

          </div>
          <!-- /.row -->
        </div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content"  data-modal-button="1" data-refresh-content="dataTable" data-refresh-dataTablePosition="0" data-action-url="admin_setting/access_ads_global_rate_details">Save changes</button>
	  </div>
	</div>
  </div>
</div> 