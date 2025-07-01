
<div class="content-wrapper">
<?php 	
	$checkItemData = (isset($page_menu))?explode('|' , $page_menu):array(); 
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:'';
	
	$selected=$title=$post_ids=$cate="";
	$mode='';
	if(isset($update)){
		// echo '<pre>';print_r($update);die;
		$mode			=	$update[0]['mode'];
		$post_ids		=	$update[0][$update[0]['type']];
		$title			=	$update[0]['slider_title'];
		$cate			=	$update[0]['cate'];
		$user			=	$update[0]['user'];
	}
	
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
    <section class="content tab-pane">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?= $pageTitle; ?></h3>
			   <div class="pull-right box-tools">
				<a href="<?= base_url('admin/to_the_sliders'); ?>" type="button" class="btn btn-block btn-primary" >Show Sliders</a>
			  </div>
            </div>
            <div class="box-body table-responsive">
			<form action="admin/SaveSliders" method="POST" class="myFormList" data-redirect="admin/to_the_sliders">
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
					  <label>Select Mode *</label>
					
					  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Mode',allowHtml:true,allowClear:true}" name="mode"  class="form-control filter require" data-error="Please Select the website mode">
							<?php 
							if(isset($web_mode)){
								echo '<option value=""></option>';
								foreach($web_mode as $list){
									$select = ($list['mode_id'] == $mode)? 'selected="selected"' : '';
									echo '<option '.$select.' value="'.$list['mode_id'].'">'.$list['mode'].'</option>';
								}
							}
							?>
						</select>	
					</div>
				</div>
				<div class="col-xs-6">
					<div class="form-group">
					  <label>Select User Type</label>
					  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User Type',allowHtml:true,allowClear:true}" name="user_level"  class="form-control filter " data-action-url="admin/getUserByCategory" data-id="#user_list" data-placeholder="Select User">
						<?php	if(isset($category)){
									echo '<option value=""></option>';
									foreach($category as $list){
										$select = ($list['category_id'] == $cate)? 'selected="selected"' : '';
										echo '<option '.$select.' value="'.$list['category_id'].'">'.$list['category_name'].'</option>';
									}
								} 	?>
						</select>	
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
					  <label>Select User</label>
					  <select data-target="select2" name="user_id"  class="form-control filter" id="user_list"  data-action-url="admin/getGenreByCategory" data-id="#genre" data-placeholder="Select Genre" data-option="{closeOnSelect:false,placeholder:'Select User Type',allowHtml:true,allowClear:true}">
						<?php
						
						if(isset($user))
							echo '<option selected value="'.$user['id'].'">'.$user['name'].'</option>';
						else
							echo '<option value=""></option>';
						?>
						</select>	
					</div>
				</div>
				<div class="col-xs-6">
					<div class="form-group">
					  <label>Select Genre</label>
					  <select data-target="select2" name="genre"  class="form-control filter" id="genre">
						<?php 
						echo '<option value=""></option>'; 
						 ?>
						</select>	
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
					 <label>Title *</label>
						<input  type="text" class="form-control require" placeholder="" name="title" maxlength="100" id="titleTokenfield" data-error="Please Enter the Title" value="<?= $title; ?>">
					</div>
				</div>
				<div class="col-xs-6">
					<button type="submit" class="btn btn-primary form-control" style="margin-top:25px">Submit</button>
					<input  type="hidden"  name="post_ids" id="post_ids" class="require" data-error="Please Choose atleast one video " value="<?= $post_ids; ?>">
					<?php if(isset($update)){
						echo '<input type="hidden" value="" name="id" class="updatePostId">';
					}?>
				</div>
			</div>
			</form>
				
              <table class="table table-bordered table-hover  display dataTableAjax" data-action-url="admin/access_slider_videos" data-target-section="tbody" data-column-class="[{className: 'checkinput'},{className: 'thumbnail'},{className: 'author'},{className: 'title'},{className: 'mode'},{className: 'genre'},{className: 'view_count'},{className: 'vote_count'}]"  data-refresh-dataTablePosition='0' data-filter='filter' data-sort="[{'targets': [0],'orderable': false}]">
                <thead>
					<tr>
					  <th class="checkinput"><input type="checkbox"  class="checkAll" onclick="checkAll(this,'SelectPostIds')"></th>
					  <th class="thumbnail">Thumbnail</th>
					  <th class="author">Author</th>
					  <th class="title">Title</th>
					  <th class="mode">Mode</th>
					  <th class="genre">Genre</th>
					  <th class="view_count">View</th>
					  <th class="vote_count">Vote</th>
					</tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
					<tr>
					   <th class="checkinput">#</th>
					  <th class="thumbnail">Thumbnail</th>
					  <th class="author">Author</th>
					  <th class="title">Title</th>
					  <th class="mode">Mode</th>
					  <th class="genre">Genre</th>
					  <th class="view_count">View</th>
					  <th class="vote_count">Vote</th>
					</tr>
                </tfoot>
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
  

	
