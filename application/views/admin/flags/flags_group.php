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
                      <!-- /.box-header -->
                      <div class="box-body">
                        <div class="row user_listpg_sec">
                          <div class="col-md-7">
                            <div class="col-md-3">
                              <div class="form-group">
                                <div class="box-header title_heading">
                                  <h3 class="box-title">Select Type</h3>
                                </div>
                                <select class="form-control filter input-lg" name="related_with">
                                    <option value='' selected>Type</option>
                                    <option value='1' >User</option>
                                    <option value='2' >Social</option>
                                    <option value='3' >Channel</option>
                                    <option value='4' >Social Comment</option>
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
                            <div class="col-md-3">
                              <div class="form-group">
                                <div class="box-header title_heading">
                                  <h3 class="box-title">Select Action Status</h3>
                                </div>
                                <select class="form-control filter input-lg" name="action_status">
                                    <option value='' selected>Status</option>
                                    <option value='0' >Action Pending</option>
                                    <option value='1' >Action Done</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="box-body table-responsive">
                          <!--div class="row"> 
                            <div class="col-xs-2">
                              <div class="form-group"> 
                                <div class="pull-right box-tools">
                                  <button type="button" class="btn btn-flat btn-default btn-right input-lg" id="TotalRecord">Total Records : 0</button>
                                </div>
                              </div>
                            </div> 
                          </div--> 
                          <div class="row">	  
                              <div class="col-md-12">
                                  <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin_setting/show_flags_group_report" data-target-section="tbody" data-column-class="[{className: 'sr'},{className: 'type'},{className: 'title'},{className: 'total'},{className: 'action'},{className: 'view'},{className: 'edit'}]"  data-refresh-dataTablePosition='0' data-filter='filter' data-orders="[0,'DESC']"> 
                            
                                    <thead>
                                      <tr>
                                          <th class="sr">#</th>
                                          <th class="type">Type</th>
                                          <th class="title">Title</th>
                                          <th class="total">Total</th>
                                          <th class="action">Last Action</th>
                                          <th class="view">View</th>
                                          <th class="edit">Edit</th>
                                            
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
  

  <div class="modal fade" id="OpenFlagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Write about the action you have taken</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="admin_setting/add_flag_action" class="myFormList" data-reset="1" data-model-hide="#OpenFlagModal">
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Action:</label>
                        <input type="hidden" value="" id="related_with" name="related_with">
                        <input type="hidden" value="" id="related_id" name="related_id">
                        <textarea class="form-control require" id="action" name="action"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-savable="true" data-refresh-content="dataTable" data-refresh-dataTablePosition="0" data-action-url="admin_setting/show_flags_group_report" data-target=".modal-content">Submit</button>
            </div>
        </div>
    </div>
    </div>