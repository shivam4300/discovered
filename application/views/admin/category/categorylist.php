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
						 <select class="form-control FilterTableData filter" name="parent_id" data-url="admin/access_categorylist">
							<option value='' selected>ALL</option>
							<option value='1' >Icon</option>
							<option value='2' >Emerging</option>
							<option value='3' >Brand</option>
							<option value='4' >Fan</option>
							<option value='130' >Official</option>
						  </select>
					</div>
					<div class="col-md-3">
						 <select class="form-control FilterTableData filter" name="cate_status" data-url="admin/access_categorylist">
							<option value='' selected>ALL</option>
							<option value='1' >Active</option>
							<option value='0' >Inactive</option>
						  </select>
					</div>
					<div class="col-md-6">
						<div class="pull-right box-tools">
							<!--button type="button" class="btn btn-flat btn-default btn-right"  data-toggle="modal"  data-target="#category_form" >Add Category</button-->
							<button type="button" class="btn btn-flat btn-default btn-right"  data-toggle="modal"  data-target="#subcategory_form" data-action-url="admin/get_categorylist" data-target-section="#categorylist">Add Sub Category</button>
							<!--button type="button" class="btn btn-flat btn-default btn-right"  data-toggle="modal"  data-target="#sub-subcategory_form" data-action-url="admin/get_categorylist/1" data-target-section="#categorylist1">Add Sub Sub-Category</button-->
						</div>
					</div>
				</div>
			</div>
			
            <div class="box-body table-responsive">
			  <table class="table nowrap hover display dataTableAjax" data-refresh-dataTablePosition='0' data-action-url="admin/access_categorylist" data-filter="1" data-target-section="tbody" data-column-class="[{className: 'catname'},{className:'parentcatname'},{className: 'status'},{className: 'edit'},{className:'action'},{className:'getDiscovered'},{className:'handler'}]"  >
                <thead>
					<tr>
					  <th class="catname">Category Name</th>
					  <th class="parentcatname">Parent Category Name</th>
					  <th class="status">Category Status</th>
					  <th class="edit">Edit</th>
					  <th class="action">Action</th>
					  <th class="getDiscovered">Get Discovered</th>
					   <th class="handler">Drag</th>	
					</tr>
                </thead>
                <tbody class="sortable" data-url="admin/Reorder_position/artist_category">
                
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
  
<div class="modal fade" id="category_form" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Category</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin/addCategoryData" method="POST" class="myFormList" data-reset="1">
          <div class="row">
            <div class="col-md-12">
				<div class="form-group">
					<label>Category Name</label>
					<input type="text" class="form-control require" name="category_name" value="" placeholder="Enter Category Name ..." id="category_name">
					<input type="hidden" name="category_id" id="category_id" value="" >
				</div>
			</div>
          </div>
          <!-- /.row -->
        </div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content" data-refresh-content="dataTable" data-refresh-dataTablePosition="0"  data-modal-button="1" >Save changes</button>
	  </div>
	</div>
  </div>
</div>

<div class="modal fade" id="subcategory_form" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Sub Category</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin/addCategoryData" method="POST" class="myFormList" data-reset="1">
          <div class="row">
            <div class="col-md-12">
				<div class="form-group">
					<label>Category Name</label>
					<select class="form-control require" name="parent_id" placeholder="Enter Category Name ..." id="categorylist">
						
					<select>
				</div>
				<div class="form-group">
					<label>Category Name</label>
					<input type="text" class="form-control require" value="" name="category_name" placeholder="Enter Category Name ..." id="subcategory_name">
					<input type="hidden" name="category_id" id="category_ids" value="">
				</div>
			</div>
          </div>
         </form>
        </div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content" data-refresh-content="dataTable" data-refresh-dataTablePosition="0"  data-modal-button="2" >Save changes</button>
	  </div>
	</div>
  </div>
</div>

<div class="modal fade" id="sub-subcategory_form" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Sub Sub-Category</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin/addCategoryData" method="POST" class="myFormList" data-reset="1">
          <div class="row">
            <div class="col-md-12">
				<div class="form-group">
					<label>Category Name</label>
					<select class="form-control require SelectBySimpleSelect"  placeholder="Enter Category Name ..." id="categorylist1" data-select-url="admin/get_categorylist/2/" data-id="#subcategorylist">
						
					<select>
				</div>
				
				<div class="form-group">
					<label>Sub Category Name</label>
					<select class="form-control require" name="parent_id" placeholder="Enter Sub Category Name ..." id="subcategorylist" >
						
					<select>
				</div>
				<div class="form-group">
					<label>Sub-SubCategory Name</label>
					<input type="text" class="form-control require" value="" name="category_name" placeholder="Enter Category Name ..." id="subcategory_name">
					<input type="hidden" name="category_id" id="category_ids" value="">
				</div>
			</div>
          </div>
         </form>
        </div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content" data-refresh-content="dataTable" data-refresh-dataTablePosition="0"  data-modal-button="2" >Save changes</button>
	  </div>
	</div>
  </div>
</div>
