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
			
			<div class="box-body">
				<div class="row">
					<div class="col-md-3">
						<select class="form-control require FilterTableData filter" name="parent_id" data-url="admin/access_genrelist">
							<option value='' selected>ALL</option>
							<?php foreach($website_mode as $list){ 
								echo '<option value="'.$list["mode_id"].'">'.$list["mode"].'</option>';
							}?>
						</select>
					</div>
					<div class="col-md-3">
						<select class="form-control require FilterTableData filter" name="status" data-url="admin/access_genrelist">
							<option value='' selected>ALL</option>
							<option value='1'>Active</option>
							<option value='0'>Inactive</option>
						</select>
					</div>
					
					<div class="col-md-2">
					<input onclick="this.value = this.checked?1:0" class="FilterTableData filter" name="is_main" data-url="admin/access_genrelist" type="checkbox" >
					Parent Genre
					</div>
					
					<div class="col-md-4">
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-flat btn-default btn-right" data-toggle="modal" data-target="#genremode">Add Genre</button>
							<button type="button" class="btn btn-flat btn-default btn-right" data-toggle="modal" data-target="#subgenre">Add Sub Genre</button>
						</div>
					</div>
				</div>
			</div>
           
			<div class="box-body table-responsive">
				<table class="table nowrap hover display dataTableAjax" data-refresh-dataTablePosition='0' data-action-url="admin/access_genrelist" data-filter="1" data-target-section="tbody" data-column-class="[{className: 'sr'},{className:'image'},{className: 'mode'},{className: 'genre'},{className: 'parentgenre'},{className:'action'},{className:'status'},{className:'slider'},{className:'handler'},{className:'videoCount'},{className:'uploadimg'},{className:'delete'}]"  data-orders="[[0,'DESC']]" data-sort="[{ targets: [4,5,6,7,8], orderable: false}]">
					<thead>
						<tr>
						  	<th class="sr">#</th>
						  	<th class="image">Genre Image</th>
						  	<th class="mode">Mode</th>
						  	<th class="parentgenre">Parent Genre</th>
						  	<th class="genre">Genre</th>
						  	<th class="action">Action</th>
							<th class="status">Status</th>	
						   	<th class="slider">Slider</th>	
						   	<th class="handler">Drag</th>	
						   	<th class="videoCount">Video Count</th>	
						   	<th class="uploadimg">Upload Image</th>	
						   	<th class="delete">Delete</th>	
						</tr>
					</thead>
					<tbody class="sortable" data-url="admin/Reorder_position/mode_of_genre">

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

<div class="modal fade" id="genremode" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add Genre</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin/add_genre" method="POST" class="myFormList" data-redirect="<?= 'admin/genre_list'; ?>">
		<input type="hidden" name="genre_id" id="genre_id1" value="">
          <div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label>Choose Mode</label>
					<select class="form-control require" name="mode_id" id="mode_id1">
						<?php foreach($website_mode as $list){ 
							echo '<option value="'.$list["mode_id"].'">'.$list["mode"].'</option>';
						}?>
						
					  </select>
				</div>
			</div>
            <div class="col-md-12">
				<div class="form-group">
					<label>Genre Title</label>
					<input type="text" class="form-control require" name="genre_name" id="genre_name1" value="" placeholder="Enter Genre...">
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
  

<div class="modal fade" id="subgenre" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add Sub Genre</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin/add_subgenre" method="POST" class="myFormList">
			<input type="hidden" name="genre_id" id="genre_id2" value="">
			<div class="col-md-12">
				<div class="form-group">
					<label>Choose Mode</label>
					<select class="form-control require SelectBySimpleSelect" name="mode_id" id="mode_id2" data-select-url="admin/selectGenreByMode" data-id="#maingenre">
						<?php foreach($website_mode as $list){ 
						echo '<option value="'.$list["mode_id"].'">'.$list["mode"].'</option>';
						}?>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label>Choose Genre</label>
					<select class="form-control require" name="parent_id" id="maingenre" >
						
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label>Sub Genre Title</label>
					<input type="text" class="form-control require" name="genre_name" id="genre_name2"  value="" placeholder="Enter Genre...">
				</div>
			</div>
          <!-- /.row -->
        </div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content"  data-modal-button="1" data-refresh-content="dataTable" data-refresh-dataTablePosition="0" >Save changes</button>
	  </div>
	</div>
  </div>
</div> 
