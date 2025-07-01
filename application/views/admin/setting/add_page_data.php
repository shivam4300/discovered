<style>	
.select2-selection--single{
height:1% !important; 
}	
</style>	
<div class="content-wrapper">
<?php 	
	$checkItemData = (isset($page_menu))?explode('|' , $page_menu):array(); 
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:'';
	
	if(isset($page_data) && !empty($page_data)){
		$page_datas = current($page_data);
	}else{
		$page_datas="";
	}
	// echo '<pre>';print_r($page_datas);
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
			
			<div class="container" style="width: 100%">
			  
			  <div class="tab-content">
				<div id="home" >
					<div class="box-header">
					  
					  <div class="pull-right box-tools">
						
					  </div>
					</div>
					
					
					<form action="admin_setting/page_setting_insert" class="myFormList" data-redirect="admin_setting/pages">
					<input type="hidden" name="id" value="<?=(isset($page_datas['id']))?$page_datas['id']:''?>">
					<div class="row <?=(isset($page_datas['cover_image_status']) && $page_datas['cover_image_status']==0)?'hide':''?>">
						<div class="col-md-6">
							<div class="box-body ">
								<div class="box box-success">
									<div class="box-header with-border">
										<div class="form-group">
										  <label>Cover Image Status</label>
										   <select class="form-control input-lg require" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_image_status">
										   <option value="1" <?=(isset($page_datas['cover_image_status']) && $page_datas['cover_image_status']==1)?'selected':''?>>Enable</option>
										   <option value="0" <?=(isset($page_datas['cover_image_status']) && $page_datas['cover_image_status']==0)?'selected':''?>>Disable</option>
										   </select>	
										</div>
										<div class="form-group">
										  <label>Select Mode</label>
										   <select class="form-control input-lg require website_mode" placeholder="Enter Text On Cover Image" autocomplete="off" name="website_mode" onchange="updateSliderMode(this.value)">
										   <option value="">Select Mode</option>
										   <?php foreach($web_mode as $value){ 
											$selected="";
												if(isset($page_datas['website_mode'])){
													if($page_datas['website_mode']==$value['mode_id']){
														$selected="selected";
													}
												}
											?>
											<option value="<?=$value['mode_id']?>" <?=$selected?>><?=$value['mode']?></option>
										   <?php }?>
										   </select>
										   <script>
											function updateSliderMode(val){
												$('[name="mode"]').val(val);
											}
										   </script>
										</div>
										<h3 class="box-title">Homepage Cover Image</h3>
										
										<div class="dis_upload_div">
											<input type="file" id="custom_music" name="file" class="inputfile " style="display:none;" >
											<label for="custom_music">
											<div class="input-group input-group-lg">
												<span class="input-group-addon"><figure><svg xmlns="https://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 45 35"><path class="cls-1" fill="#777" fill-rule="evenodd" d="M1348.68,1216.23a12.509,12.509,0,0,0-12.59-12.23,12.654,12.654,0,0,0-8.3,3.09,12.323,12.323,0,0,0-4,6.76h-0.13a10.506,10.506,0,1,0,0,21.01h7.45a0.945,0.945,0,1,0,0-1.89h-7.45a8.616,8.616,0,1,1,0-17.23c0.26,0,.53.02,0.84,0.04a0.954,0.954,0,0,0,1.04-.81,10.4,10.4,0,0,1,3.52-6.46,10.691,10.691,0,0,1,17.7,7.89c0,0.21-.01.42-0.03,0.65l-0.01.1a0.931,0.931,0,0,0,.29.74,0.979,0.979,0,0,0,.77.27,6.45,6.45,0,0,1,.76-0.04,7.426,7.426,0,1,1,0,14.85h-7.83a0.945,0.945,0,1,0,0,1.89h7.83A9.316,9.316,0,1,0,1348.68,1216.23Zm-12.59-7.79a8.068,8.068,0,0,0-7.99,6.87,0.956,0.956,0,0,0,.82,1.07,0.66,0.66,0,0,0,.14.01,0.949,0.949,0,0,0,.94-0.82,6.15,6.15,0,0,1,6.09-5.24A0.945,0.945,0,1,0,1336.09,1208.44Zm4.37,18.61-3.49-3.08a1.6,1.6,0,0,0-2.11,0l-3.5,3.08a0.928,0.928,0,0,0-.07,1.33,0.971,0.971,0,0,0,1.35.08l2.31-2.04v11.63a0.96,0.96,0,0,0,1.92,0v-11.63l2.31,2.04a0.959,0.959,0,0,0,1.35-.08A0.928,0.928,0,0,0,1340.46,1227.05Z" transform="translate(-1313 -1204)"></path></svg></figure></span>
												<input type="text" class="form-control" placeholder="Click on icon for upload Image" disabled style="height:65px">
											  </div>
											</label>
										</div>
										<div class="box box-success">
											<div class="box-header with-border">
											  <h3 class="box-title">Homepage Detail</h3>
											</div> 
										
											<div class="form-group">
											  <label>Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Title" autocomplete="off" name="cover_image_title" value="<?=(isset($page_datas['cover_image_title']))?$page_datas['cover_image_title']:''?>">	
											</div>
											<div class="form-group">
											  <label>Sub-Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Sub-Title" autocomplete="off" name="cover_image_subtitle" value="<?=(isset($page_datas['cover_image_subtitle']))?$page_datas['cover_image_subtitle']:''?>">	
											</div>
											<div class="form-group">
											  <label>Text On Cover Image</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_over_image" value="<?=(isset($page_datas['cover_over_image']))?$page_datas['cover_over_image']:''?>">	
											</div>
										</div>
									</div>
								</div>	
								
							</div>
						</div>
						
					
						<div class="col-md-6">
							<div class="box-body ">
								<div class="box box-success">
									<?php if(isset($page_datas['cover_image'])){ ?>
									<img src="<?=base_url('repo_admin/images/homepage/').$page_datas['cover_image'];?>" style="max-width: 100%;">
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row <?=(isset($page_datas['cover_image_status']) && $page_datas['cover_image_status']==0)?'hide':''?>">
						<div class="col-md-6">
							<div class="box-body ">
								<div class="box box-success">
									
									<div class="box box-success">
										<div class="box-header with-border">
											<h3 class="box-title">Default Profile Cover Video</h3>
										</div> 
										
										<!--div class="form-group">
											<label>Select User</label>
											<select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User',allowHtml:true,allowClear:true,templateResult: formatPageOption,templateSelection: formatPageOption}" name="user_id" class="form-control dis_setting_checkbox" data-action-url="admin_setting/getClientVideos" data-id="#my_post_id" data-placeholder="select Video">
												<option></option>
												<?php 
													/*if(isset($user_list) && !empty($user_list)){
														
														foreach($user_list as $list){
															$selected = '';
															if(isset($page_datas) && !empty($page_datas)){
																if($list['user_id'] == $page_datas['user_id']){
																	$selected  = 'selected="selected"';
																}
															}
															if(!empty($list["uc_pic"])){
																$img =get_user_image($list['user_id']);															
															}else{
																$img = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnnHdV5DwUgEsNsyE1LR8w2KwYhQAI6-tEZRs_Il9Klhw50YUwtg';	
															}
															
															echo '<option '.$selected.' data-width="90px" data-src="'.$img.'" value="'.$list['user_id'].'">'.$list['user_name'].'</option>';
														}
													}*/
													?>
											</select>
										</div-->

										<div class="form-group">
											<label>Select User</label>
											<select data-ajax--url="admin_setting/getActiveUserList" data-option="{closeOnSelect:false,placeholder:'Select User',allowHtml:true,allowClear:true,templateResult: formatPageOption,templateSelection: formatPageOption}" name="user_id" class="form-control dis_setting_checkbox js-data-ajax" data-action-url="admin_setting/getClientVideos" data-id="#my_post_id" data-placeholder="select Video">
												<option>Select User</option>
												<?php 
													if(isset($page_datas['user_id']) && !empty($page_datas['user_id']) && isset($page_datas['user_name']) && !empty($page_datas['user_name'])){
														echo '<option value="'.$page_datas['user_id'].'" selected>'.$page_datas['user_name'].'</option>';
													}
												?>
											</select>
										</div>
										 
										<div class="form-group">
											<label>Select Video</label>
											<select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Video',allowHtml:true,allowClear:true}" name="default_profile_video" class="form-control default_profile_video" id="my_post_id" data-url="admin_setting/getClientVideos">
												<?php 
												$VidUrl = '';
													if(isset($ModesVideo)){
														foreach($ModesVideo as $list){
															$selected = '';
															if(isset($page_datas) && !empty($page_datas)){
																if($list['id'] == $page_datas['default_profile_video']){
																	$selected  = 'selected="selected"';
																	$VidUrl = $list['video'];
																}
															}
															echo '<option '.$selected .' value="'.$list['id'].'">'.$list['name'].'</option>';
														}
													}
												?>
											</select>	
										</div>
											
											

									</div>

								</div>
							</div>
								
						</div> 

						<div class="col-md-6">
							<div class="box-body ">
								<div class="box box-success">
									
									<video style="width: 100%;" controls src="<?= $VidUrl; ?>" id="ShowmMyDefaultProfileVideo">
									
									</video>
								</div>
							</div>
						</div>
						
					</div>
 
					<div class="row">
						<div class="col-md-6">
							<div class="box-body table-responsive tab-pane">
								<input type="hidden" value="" name="user_level">
								<!--input type="hidden" value="" name="user_id"-->
								<input type="hidden" value="" name="genre">
								<input type="hidden" value="<?= isset($page_datas['website_mode'])?$page_datas['website_mode']:''; ?>" name="mode" class="filter"> 
								<input  type="hidden"  name="post_ids" id="post_ids" class="require filter updatePostId" data-error="Please Choose atleast one video " value="<?php echo  isset($page_datas['cover_video'])?$page_datas['cover_video']:''; ?>">
								 
								
								<div class="box box-danger">
									<div class="box-header">
									  <h3 class="box-title">Select this checkbox to get selected videos only</h3>
									</div>
									<div class="box-body">
									<!--?= !empty($page_datas['cover_video'])?'checked':''?-->
									 <input  type="checkbox" class="filter" placeholder="" name="selected_video" id="selected_video" value="0" >
									</div>
								</div>
								
								<style>.dt-buttons{display:none;}</style>	
								<table class="table table-bordered table-hover  display dataTableAjax" data-action-url="admin_setting/access_slider_videos" data-target-section="tbody" data-column-class="[{className:'handler'},{className: 'checkinput'},{className: 'orderinput'},{className: 'thumbnail'},{className: 'author'},{className: 'title'},{className: 'mode'},{className: 'genre'},{className: 'view_count'},{className: 'vote_count'},{className: 'action'}]"  data-refresh-dataTablePosition='0' data-filter='filter' data-sort="[{'targets': [1],'orderable': false},{'targets': [2,3,6,7],'visible': false}]">
									<thead>
										<tr>
										  <th class="handler">Order</th>
										  <th class="checkinput"><input type="checkbox"  class="checkAll" onclick="checkAll(this,'SelectPostIds')"></th>
										  <th class="orderinput">slider Order</th>
										  <th class="thumbnail">Thumbnail</th>
										  <th class="author">Author</th>
										  <th class="title">Title</th>
										  <th class="mode">Mode</th>
										  <th class="genre">Genre</th>
										  <th class="view_count">View</th>
										  <th class="vote_count">Vote</th>
										  <th class="action">Action</th>
										</tr>
									</thead>
									<tbody class="sortable" data-url="homepage_covervideo_order">
									
									</tbody>
									<tfoot> 
										<tr>
										  <th class="handler">Order</th>
										  <th class="checkinput">#</th>
										  <th class="thumbnail">Thumbnail</th>
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
							<div class="box-footer">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
							</form>
						</div>
						
						<div class="col-md-6">
							<div class="box-body">
								<div class="box box-danger">
									<div class="box-header">
									  <h3 class="box-title">Link & Preview</h3>
									</div>
									<div class="box-body" id="PreviewTitle">
									 NA
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<div class="">
												<input type="text" id="subtitle" class="form-control" placeholder="Enter SubTitle">
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="">
												<input type="text" id="btn_name" class="form-control" placeholder="Enter Button Name">
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<div class="input-group"> 
												<input type="text" id="link" class="form-control" placeholder="Enter Link">
												<input type="hidden" id="cover_post_id">
												<span class="input-group-btn">
												  <a type="button" class="btn btn-info btn-flat AddCoverVideoLink">Update</a> 
												</span>
												<span class="input-group-btn" >
												  <a type="button" class="btn btn-danger btn-flat RemoveCoverVideoLink" style="margin-left:5px;">Delete</a> 
												</span>
											</div>
										</div>
									</div>
								</div>
								<link href="https://test.discovered.tv/repo/css/player/videojs.css" rel="stylesheet" type="text/css" />
								<div class="box box-success">
									<video class="video-js"  style="width: 100%;"  src="" id="vidSrc">
									</video>
									<a class="btn btn-primary pull-right create_preview">Create Preview</a>
								</div>
								<script type="text/javascript" src="https://test.discovered.tv/repo/js/player/videojs.min.js"></script>
								<script>
								
									var player = videojs('vidSrc' , {
											controls:true,
											html5: {
												hls: {
													overrideNative: true,
													enableLowInitialPlaylist :true,
													smoothQualityChange :true
												}
												
											}
										},function(){
											
										}); 
								</script>
							</div>
						</div>
						
					</div>
				</div>
			  </div>
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



<div class="modal fade" id="user_form1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">User Vote count</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="admin/updateParam/user_level_type" method="POST" class="myFormList" data-redirect="<?= 'admin/main_setting'; ?>">
          <div class="row">
            <div class="col-md-12">
				<div class="form-group">
					<label>Vote Count</label>
					<input type="text" class="form-control require" name="vote_count" value="" placeholder="Enter vote Count ..." id="vote_count1">
					<input type="hidden" name="type_id" id="id1" value="" >
				</div>
			</div>
          </div>
          <!-- /.row -->
        </div>
		</form>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" data-savable="true" data-target=".modal-content"  data-modal-button="1" >Save changes</button>
	  </div>
	</div>
  </div>
</div>
