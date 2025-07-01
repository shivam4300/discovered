
<div class="content-wrapper">
<?php 	
	$checkItemData = (isset($page_menu))?explode('|' , $page_menu):array(); 
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:'';
	
	$selected=$title=$post_ids=$cate=$post_order_ids="";
	$mode = $slider_type  = $search_query = $slider_mode =  '';
	$query_type='Search';
	if(isset($update)){
		$mode			=	isset($update[0]['mode'])?$update[0]['mode']:'';
		$slider_mode	=	isset($update[0]['slider_mode'])?$update[0]['slider_mode']:'';
		$post_ids		=	isset($update[0]['data'])?$update[0]['data']:'';
		$post_order_ids	=	isset($update[0]['data_order'])?$update[0]['data_order']:'';
		$title			=	isset($update[0]['slider_title'])?$update[0]['slider_title']:'';
		$cate			=	isset($update[0]['category_id'])?$update[0]['category_id']:'';
		$user			=	isset($update[0]['user'])?$update[0]['user']:'';
		$genre_id		=	isset($update[0]['genre'])?$update[0]['genre']:'';
		$slider_type	=	isset($update[0]['slider_type'])?$update[0]['slider_type']:'';
		$query_type		= 	isset($update[0]['query_type'])?$update[0]['query_type']:'';
		$search_query	=	isset($update[0]['search_query'])?$update[0]['search_query']:'';

		if(empty($mode)){                // in case of add new slider , select mode will be empty , so it will be equal to slider mode;
			$mode = $slider_mode;
		}
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
				<a href="<?= base_url('admin_setting/home_sliders'); ?>" type="button" class="btn btn-block btn-primary" >Show Sliders</a>
			  </div>
            </div>
            <div class="box-body table-responsive">
			<form action="admin_setting/SaveHomePageSliders" method="POST" class="myFormList" data-redirect="admin_setting/home_sliders">
			<div class="container-fluid">
				<div class="row">
					<?php if($mode != 10){  // not required in articles mode ?> 
					<div class="col-lg-2">
						<div class="form-group">
						<label>Select Query Type *</label>
						<div class="dis_select2">
							<select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Type',allowHtml:true,allowClear:true}" name="query_type"  class="form-control  require" data-error="Please Select the Query type" 
							onchange="$(this).val() == 'Search' ? $('#search_query').show() : $('#search_query').hide()">
									<?php 

										echo '<option value=""></option>';
										foreach(['Search','SeeAll'] as $list){
											$select = ($list == $query_type)? 'selected="selected"' : '';
											echo '<option '.$select.' value="'.$list.'">'.$list.'</option>';
										}
									
									?>
								</select>	
							</div>
						</div>
					</div>
					<div class="col-lg-4" >
						<div class="form-group" id="search_query">
							<label>Enter Text For Query *</label>
							<div class="dis_select2">
								<input  type="text" class="form-control" placeholder="" name="search_query"  data-error="Please Enter the search query" value="<?= $search_query; ?>" >	
							</div>
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
						<label>Select Type *</label>
						<div class="dis_select2">
							<select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Type',allowHtml:true,allowClear:true}" name="slider_type"  class="form-control  require sliderfilter" data-error="Please Select the slider type">
									<?php 

										echo '<option value=""></option>';
										foreach(['single','playlist'] as $list){
											$select = ($list == $slider_type)? 'selected="selected"' : '';
											echo '<option '.$select.' value="'.$list.'">'.$list.'</option>';
										}
									
									?>
								</select>	
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="col-lg-<?=($mode == 10) ? 6 : 2 ; ?>">
						<div class="form-group">
						<label>Select Mode *</label>
						<div class="dis_select2">
							<input type="hidden" name="slider_mode" value="<?=  $slider_mode; ?>">
							<select  data-target="select2" data-option="{<?= $slider_mode != 8 && $slider_mode != 9  ? 'disabled:\'readonly\'':'disabled:false'; ?>,closeOnSelect:false,placeholder:'Select Mode',allowHtml:true,allowClear:true}" name="mode"  class="form-control  require sliderfilter getUserAndGenreByMode" data-error="Please Select the website mode" data-url="admin/getUserByMode" data-id-user="#user_list" data-id-genre="#genre" data-placeholder-user="Select User" data-placeholder-genre="Select Genre" data-mode="1">
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
					</div>
					<div class="col-lg-<?=($mode == 10) ? 6 : 2 ; ?>">
						<div class="form-group">
						<label>Select User Type</label>
						<select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User Type',allowHtml:true,allowClear:true}" name="user_level"  class="form-control sliderfilter getUserAndGenreByMode" data-url="admin/getUserByMode" data-id-user="#user_list" data-id-genre="#genre" data-placeholder-user="Select User" data-placeholder-genre="Select Genre" data-mode="1" id="user_type">
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
					<div class="col-lg-6">
						<div class="form-group">
						<label>Select User</label> 
						<!--span style="color:red;">
							(NOTE:- Select user only if you want to display all the videos of this user when clicking "See All" slider button on frontend)
						</span-->
						<select data-target="select2" name="user_id"  class="form-control sliderfilter" id="user_list"  data-placeholder="Select Genre" data-option="{closeOnSelect:false,placeholder:'Select User Type',allowHtml:true,allowClear:true}" multiple="multiple">
							<?php
							if(isset($user_list)){
									echo '<option value=""></option>';
									foreach ($user_list as $key => $value) {
										if($value['user_id']==$user['id']){
											$selected="selected";
										}else{
											$selected="";

										}
										echo '<option value="'.$value['user_id'].'" '.$selected.'>'.$value['user_name'].'</option>';
									}
							}else{
								echo '<option value=""></option>';
							}
							
							?>
							</select>	
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
						<label id="Changelabelname">Select <?= $mode == 10 ? 'Category' : 'Genre';?> </label>
						<select data-target="select2" name="genre"  class="form-control sliderfilter" id="genre"  data-option="{closeOnSelect:false,placeholder:'Select Genre',allowHtml:true,allowClear:true}" >
							<?php 
							if(isset($genre)){
								echo '<option value=""></option>';
								foreach ($genre as  $genrevalue) {
									$select = ($genrevalue['id'] === $genre_id)? 'selected="selected"' : '';
									echo '<option '.$select.' value="'.$genrevalue['id'].'">'.$genrevalue['name'].'</option>'; 
								}
							}else{
								echo '<option value=""></option>'; 
							}
							
							?>
							</select>	
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
						<label>Title *</label>
							<input  type="text" class="form-control require" placeholder="" name="title" maxlength="100" id="titleTokenfield" data-error="Please Enter the Title" value="<?= $title; ?>">
						</div>
					</div>
					<div class="col-md-3">
					<a class="btn btn-primary form-control" style="margin-top:25px" onclick="$('.display').removeClass('hide'); $('#post_ids').trigger('change');">Search</a>
					</div>
					<div class="col-md-3">
						
						<button type="submit" class="btn btn-primary form-control" style="margin-top:25px">Submit</button>
						<input  type="hidden"  name="post_ids" id="post_ids" class="require filter" data-error="Please Choose atleast one video " value="<?= $post_ids; ?>">
						<input  type="hidden"  name="order_post_ids" id="order_post_ids" class="filter" value="<?= $post_order_ids; ?>">
						
						<?php if(isset($update)){
							echo '<input type="hidden" value="'.$update[0]['id'].'" name="id" class="updatePostId">';
						}?>
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
						<label>Select this checkbox to get selected videos only</label> 
							<input  type="checkbox" class="filter" placeholder="" name="selected_video" id="selected_video" value="<?= isset($update)? 1:0; ?>" <?= isset($update)? 'checked':''; ?>>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						<label>Select this checkbox to get slider videos</label> 
							<input  type="checkbox" class="filter" placeholder="" name="selected_order_video" id="selected_order_video">
						</div>
					</div>
				</div>
			</div>
			
			</form>
				
              <table class="table table-bordered table-hover  display dataTableAjax <?//= !isset($update)? 'hide':''; ?>" data-action-url="admin_setting/access_slider_videos" data-target-section="tbody" data-column-class="[{className: 'order'},{className: 'checkinput'},{className: 'orderinput'},{className: 'thumbnails'},{className: 'author'},{className: 'title'},{className: 'mode'},{className: 'genre'},{className: 'view_count'},{className: 'vote_count'},{className: 'action'}]"  data-refresh-dataTablePosition='0' data-filter='filter' data-sort="[{'targets': [1],'orderable': false},{'targets': [0,10],'visible': false}]">
                <thead>
					<tr>
					<th class="order">Order</th>
					<th class="checkinput"><input type="checkbox"  class="checkAll" onclick="checkAll(this,'SelectPostIds')"></th>
					<th class="orderinput">Show on Slider</th>
					<th class="thumbnails">Thumbnails</th>
					<th class="author">Author</th>
					<th class="title">Title</th>
					<th class="mode">Mode</th>
					<th class="genre">Genre</th>
					<th class="view_count">View</th>
					<th class="vote_count">Vote</th>
					<th class="action">Action</th>
					</tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
					<tr>
					<th class="order">Order</th>
					   	<th class="checkinput">#</th>	
						<th class="orderinput">Show on Slider</th>				  
						<th class="thumbnails">Thumbnails</th>
						<th class="author">Author</th>
						<th class="title">Title</th>
						<th class="mode">Mode</th>
						<th class="genre">Genre</th>
						<th class="view_count">View</th>
						<th class="vote_count">Vote</th>
						<th class="action">Action</th>
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
  

	
