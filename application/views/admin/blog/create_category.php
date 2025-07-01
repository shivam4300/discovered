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
                    <button type="button" class="btn btn-flat btn-default btn-right editCategory" data-id="" data-name="">Add Category</button>
                </div>
            </div>
            <!-- /.box-header -->
			
            <div class="box-body table-responsive">
                
              <table class="table dt-responsive nowrap hover display dataTableAjax" data-search="true" data-action-url="admin_blog/show_categories" data-target-section="tbody" data-column-class="[{className: 'sr'},{className: 'thumb'},{className: 'name'},{className: 'slug'},{className: 'status'},{className: 'slider'},{className: 'edit'},{className: 'handler'}]"  data-refresh-dataTablePosition='0' data-orders="[]" data-sort="[{ targets: [0,1,2], orderable: false}]">
                <thead>
                  <tr>
                    <th class="sr">#</th> 
                    <th class="thumb">Thumb</th> 
                    <th class="name">Name</th>
                    <th class="slug">Slug</th>
                    <th class="status">Status</th>
                    <th class="slider">Slider</th>
                    <th class="edit">Edit</th>
                    <th class="handler">Drag</th>
                    <!--th class="delete">Delete</th-->
                  </tr>
                </thead>
                <tbody class="sortable" data-url="admin/Reorder_position/article_categories"></tbody>
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
  

   
<div class="modal fade" id="category" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">	
        <form action="admin_blog/addEditCategory" method="POST" class="myFormList" data-model-hide="#category">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Category</h4>
            </div>
            <div class="modal-body dis_user_data_modelbody">
                <div class="box-body">
               
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" class="form-control" placeholder="Enter category name" value="" name="name" id="cate_name">
                            <input type="hidden" class="form-control" placeholder="category id" value="" name="id" id="cate_id">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Category Image</label>
                            <input type="file" class="form-control" placeholder="Browse Image" value="" name="cate_img" >
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
        $(document).on('click','.editCategory',function(){
            $('#cate_id').val($(this).attr('data-id'));
            $('#cate_name').val($(this).attr('data-name'));
            $('#category').modal('show');
        }) 
    }, 2000);
</script>