  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        NEWS LIST
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#">News</a></li>
        <li class="active">View</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
              <span class="pull-right">
                <a href="<?php echo base_url('add_news');?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New News</a>
                <button class="btn btn-sm btn-danger multiple_news_delete"><i class="fa fa-trash-o"></i> Bulk Delete</a>
              </span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="news_list_tbl" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><label>
                  <input type="checkbox" onclick="checkAll('newschkbxAll', 'newschkbx')" class="minimal newschkbxAll">
                </label></th>
                    <th>Date Of News</th>
                    <th>Heading</th>
                    <th>Sub-Heading</th>
                    <th>Publish in Papers</th>
                    <th class="no-sort">Action</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>

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
    <div class="modal fade" id="news_detail_modal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title news_detail_heading"></h4>
          </div>
          <div class="modal-body news_detail_result">
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>