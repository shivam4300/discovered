<?php 	
	$checkItemData = (isset($page_menu))?explode('|' , $page_menu):array(); 
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:'';
?>
<div class="content-wrapper">
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
              <div class="pull-right box-tools"> 
                    <button type="button" class="btn btn-flat btn-default btn-right editAttribute" data-id="" data-name="">Add Attribute</button>
                </div>
            </div>
            <!-- /.box-header -->
			
            <div class="box-body table-responsive">
                
              <table class="table dt-responsive nowrap hover display dataTableAjax" data-search="false" data-action-url="admin_store/show_attributes" data-target-section="tbody" data-column-class="[{className: 'sr'},{className: 'name'},{className: 'slug'},{className: 'edit'},{className: 'delete'}]"  data-refresh-dataTablePosition='0' data-orders="[]" data-sort="[{ targets: [0,1,2], orderable: false}]">
                <thead>
                  <tr>
                    <th class="sr">#</th>
                    <th class="name">Name</th> 
                    <th class="slug">Slug</th>
                    <th class="edit">Edit</th>
                    <th class="delete">Delete</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
			</div>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  

   
<div class="modal fade" id="attribute" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">	
        <form action="admin_store/addEditAttribute" method="POST" class="myFormList" data-model-hide="#attribute">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Attribute</h4>
            </div>
            <div class="modal-body dis_user_data_modelbody">
                <div class="box-body">
                <p>Attributes let you define extra product data, such as size or color.</p>  
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Attribute Name</label>
                            <input type="text" class="form-control" placeholder="Enter attribute name" value="" name="name" id="attr_name">
                            <input type="hidden" class="form-control" placeholder="attribute id" value="" name="id" id="attr_id">
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </form>  
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> 
            <button  type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content" data-refresh-content="dataTable" data-refresh-dataTablePosition="0">Create</button>
        </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        $(document).on('click','.editAttribute',function(){
            $('#attr_id').val($(this).attr('data-id'));
            $('#attr_name').val($(this).attr('data-name'));
            $('#attribute').modal('show');
        }) 
    }, 2000);
</script>