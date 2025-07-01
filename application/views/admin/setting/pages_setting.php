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
					<div class="col-md-6"></div>
					<div class="col-md-6 ">
						<div class="pull-right box-tools">
							<!--a href="<?=base_url('admin/add_page_data');?>" class="btn btn-flat btn-default ">Add homepage</a-->
						</div>
					</div>
				</div>
			</div>
			
            <div class="box-body table-responsive">
                <table class="table nowrap hover display dataTableAjax" data-refresh-dataTablePosition='0' data-action-url="admin_setting/access_page_setting_list" data-filter="parent_id" data-target-section="tbody" data-column-class="[{className: 'catname'},{className:'parentcatname'},{className: 'status'},{className: 'edit'},{className:'action'},{className:'handler'},{className: 'catname'}]"  data-sort="[{'targets': [1,2,3,4],'visible': false}]">
                  <thead>
                  <tr>
                    <th class="catname">Mode</th>
                    <th class="parentcatname">Image</th>
                    <th class="status">Title</th>
                    <th class="edit">Sub-Title</th>
                    <th class="edit">Text On Cover Image</th>
                    <th class="action">Cover Image Status</th>
                    <th class="handler">Action</th>	
                  </tr>
                </thead>
                <tbody >
                
                </tbody>
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