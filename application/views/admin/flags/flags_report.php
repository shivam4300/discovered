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
            <!--div class="box-header">
              <h3 class="box-title"><?= $pageTitle; ?></h3>
            </div-->
            <div class="box-body">
              <div class="row user_listpg_sec">
                <div class="col-md-7">
                  <div class="col-md-4">
                    <label>Select Category</label>
                    <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Category',allowHtml:true,allowClear:true}" name="viol_cate"  class="form-control filter" data-action-url="admin_setting/getViolSubCategory" data-id="#subcate_list" data-placeholder="Select Sub Category">
                            <?php 
                            if(isset($viol_cate)){
                                echo '<option value=""></option>';
                                foreach($viol_cate as $list){
                                    echo '<option value="'.$list['viol_id'].'">'.$list['violations_title'].'</option>';
                                }
                            }
                            ?>
                    </select>	
                  </div>
                  <div class="col-md-4">                        
                      <label>Select Sub Category</label>
                      <select data-target="select2" name="viol_subcate"  class="form-control filter" id="subcate_list">
                          <?php echo '<option value="">Select Category</option>'; ?>
                      </select>	
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body table-responsive">
			        <div class="row">
                <div class="col-xs-2">
                  <div class="form-group"> 
                    <div class="pull-right box-tools">
                      <button type="button" class="btn btn-flat btn-default btn-right input-lg" id="TotalRecord">Total Records : 0</button>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" class="filter" value="<?= $related_with; ?>" name="related_with">
              <input type="hidden" class="filter" value="<?= $related_id; ?>" name="related_id">
			        <div class="row">	
                <div class="col-md-12">
                  <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin_setting/show_flags_report" data-target-section="tbody" data-column-class="[{className: 'sr'},{className: 'category'},{className: 'subcategory'},{className: 'message'},{className: 'status'},{className: 'created_at'},{className: 'raisedby'},{className: 'action'}]"  data-refresh-dataTablePosition='0' data-filter='filter' data-orders="[[0,'DESC']]">
                    <thead>
                      <tr>
                        <th class="sr">#</th>
                        <!--th class="type">Type</th>
                        <th class="title">Title</th-->
                        <th class="category">Category</th>
                        <th class="subcategory">Sub Category</th>
                        <th class="message">Message</th>
                        <th class="status">Status</th>
                        <th class="created_at">Created At</th>
                        <th class="raisedby">Raised By</th>
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
  

	
</script>