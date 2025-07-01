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
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  
                  <div class="row">
                    
                    <div class="col-md-3">
                      <select class="form-control filter" name="source_status" >
                        <?php if(!empty($soureList)){ 
                          echo '<option value="">Select</option>';
                          foreach($soureList as $key=>$s){
                            echo '<option value="'.$key.'">'.$s.'</option>';
                          } 
                        }	?>
                      </select>
                    </div> 
                    <div class="col-md-9">
                      <div class="pull-right box-tools">
                        <button type="button" class="btn btn-flat btn-default btn-right" id="TotalRecord"> </button>
                       </div>
                    </div>

                  </div>
                </div>
                <div class="box-body table-responsive">
          
                  <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin/access_userSourcelist" 
                    data-target-section="tbody" data-column-class="[{className: 'sr'},{className: 'name'},{className: 'email'},{className: 'createdate'},{className: 'source'}]" data-refresh-dataTablePosition='0' data-filter="filter" data-orders="[[2,'DESC']]" data-sort="[{ targets: [0], orderable: false}]">
                            <thead> 
                      <tr>
                        <th class="sr">#</th>
                        <th class="name">Name</th>
                        <th class="email">Email</th>
                        <th class="createdate">Joining Date</th>
                        <th class="source">Source</th>
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
  
 
  <!-- /.modal-dialog -->
</div>