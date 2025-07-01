<style>	
.select2-selection--single{
height:1% !important; 
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
			
			<div class="container" style="width: 100%">
			   <ul class="nav nav-tabs">
				<li class="<?php if(!isset($_COOKIE['setTab'])){ echo "active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#home">Music Homepage</a></li>
				<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu1">Movie Homepage</a></li>
				<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu2'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu2">TV Homepage</a></li>
				<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu3'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu3">Gaming Homepage</a></li>
				<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu4'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu4">Social Homepage</a></li>
			
			  </ul>

			  <div class="tab-content">
				<div id="home" class="tab-pane fade <?php if(!isset($_COOKIE['setTab'])){ echo "in active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "in active";} } ?>">
					<div class="box-header">
					  <h3 class="box-title">Music
						<small>Homepage</small>
					  </h3>
					  <div class="pull-right box-tools">
						
					  </div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<div class="box-header with-border">
								  <h3 class="box-title">Homepage Cover Image</h3>
								</div>
								<!--div class="dropzone" data-url="<?= base_url('admin/uploadVideo/Homepage/music') ; ?>" id="Umusic">
									<div class="dz-default dz-message" style="padding: 35px;">
										<i class="fa fa-file-video-o" aria-hidden="true"></i>
										<p class="info_text">Drop a video here or Click to browse</p>
									</div>
								</div-->
								
										<div class="dis_upload_div">
											<input type="file" id="custom_music" name="file" class="inputfile UploadFile" style="display:none;" data-file_type="jpeg|jpg|png|gif" data-path="repo_admin/images/homepage" data-url="admin/uploadedfile/music/homepage" data-id="cover_image">
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
										<form action="admin/updateWebsiteInfo/music/homepage" class="myFormList" >
											<div class="form-group">
											  <label>Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Title" autocomplete="off" name="cover_image_title" value="<?php echo  $this->audition_functions->get_website_info('cover_image_title','homepage','music','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Sub-Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Sub-Title" autocomplete="off" name="cover_image_subtitle" value="<?php echo  $this->audition_functions->get_website_info('cover_image_subtitle','homepage','music','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Text On Cover Image</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_over_image" value="<?php echo  $this->audition_functions->get_website_info('cover_over_image','homepage','music','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Cover Image Status</label>
											   <select class="form-control input-lg require" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_image_status">
											   <option value="1" <?php echo ($this->audition_functions->get_website_info('cover_image_status','homepage','music','text') ==1)? 'selected' : ''; ?>>Enable</option>
											   <option value="0" <?php echo ($this->audition_functions->get_website_info('cover_image_status','homepage','music','text') ==0)? 'selected' : ''; ?>>Disable</option>
											   </select>	
											</div>
										</div>
										
									
							</div>
						</div>	
						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<img src="<?php echo $this->audition_functions->get_cover_image('music').'?q='.time(); ?>" style="max-width: 100%;">
							</div>
						</div>
					</div>
					</div>
					
				

				<div class="row">
						<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
										<div class="box box-success">
										<div class="box-header with-border">
										  <h3 class="box-title">Homepage Video</h3>
										</div> 
										<form action="admin/updateWebsiteInfo/music/homepage" class="myFormList" >
											
											<div class="form-group">
											  <label>Select User</label>
											  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User',allowHtml:true,allowClear:true,templateResult: formatPageOption,templateSelection: formatPageOption}" name="user_id" class="form-control require dis_setting_checkbox" data-action-url="admin/getClientVideos" data-id="#post_id1" data-placeholder="select Video"  data-mode="1">
													<option></option>
												<?php 
													if(isset($user_list) && !empty($user_list)){
														
														foreach($user_list as $list){
															$selected = '';
															if(isset($homepage_music) && !empty($homepage_music)){
																if($list['user_id'] == $homepage_music['user_id']){
																	$selected  = 'selected="selected"';
																}
															}
															if(!empty($list["uc_pic"])){
																$img =get_user_image($list['user_id']);															
															}else{
																// $img = base_url('repo/images/user/user.png');	
																$img = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnnHdV5DwUgEsNsyE1LR8w2KwYhQAI6-tEZRs_Il9Klhw50YUwtg';	
															}
															
															echo '<option '.$selected.' data-width="90px" data-src="'.$img.'" value="'.$list['user_id'].'">'.$list['user_name'].'</option>';
														}
													}
													?>
											</select>
												
											</div>
											
											<div class="form-group">
											  <label>Select Video</label>
											  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Video',allowHtml:true,allowClear:true}" name="cover_video" data-uvid="1" class="form-control require"  id="post_id1">
													<?php if(isset($musc_vid)){
														
															
															foreach($musc_vid as $list){
																$selected = '';
																if(isset($homepage_music) && !empty($homepage_music)){
																	if($list['id'] == $homepage_music['post_id']){
																		$selected  = 'selected="selected"';
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
							
						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<?php 
								$musicvid = '';	
								if(isset($homepage_music) && !empty($homepage_music)){
										$musicvid = $homepage_music['url'];
									} ?>
								<video style="width: 100%;" controls src="<?= $musicvid; ?>" id="musicVid">
								  
								</video>
								<button class="btn btn-primary create_preview">Create Preview</button>
							</div>
						
						</div>
					</div>
					</div>
					
				</div>
				
				
				
				
				<div id="menu1" class="tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "in active";} } ?> ">
				<div class="box-header">
				  <h3 class="box-title">Movie
					<small>Homepage</small>
				  </h3>
				  <div class="pull-right box-tools">
					<!--button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#genremode">Add Genre</button-->
				  </div>
				</div>	
				<div class="row">
						<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<div class="box-header with-border">
								  <h3 class="box-title">Homepage Cover Image</h3>
								</div>
								<!--div class="dropzone" data-url="<?= base_url('admin/uploadVideo/Homepage/movies') ; ?>" id="Umusic">
									<div class="dz-default dz-message" style="padding: 35px;">
										<i class="fa fa-file-video-o" aria-hidden="true"></i>
										<p class="info_text">Drop a video here or Click to browse</p>
									</div>
								</div-->
								
										<div class="dis_upload_div">
											<input type="file" id="custom_movies" name="file" class="inputfile UploadFile" style="display:none;" data-file_type="jpeg|jpg|png|gif" data-path="repo_admin/images/homepage" data-url="admin/uploadedfile/movies/homepage" data-id="cover_image">
											<label for="custom_movies">
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
										<form action="admin/updateWebsiteInfo/movies/homepage" class="myFormList" >
											<div class="form-group">
											  <label>Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Title" autocomplete="off" name="cover_image_title" value="<?php echo  $this->audition_functions->get_website_info('cover_image_title','homepage','movies','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Sub-Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Sub-Title" autocomplete="off" name="cover_image_subtitle" value="<?php echo  $this->audition_functions->get_website_info('cover_image_subtitle','homepage','movies','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Text On Cover Image</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_over_image" value="<?php echo  $this->audition_functions->get_website_info('cover_over_image','homepage','movies','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Cover Image Status</label>
											   <select class="form-control input-lg require" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_image_status">
											   <option value="1" <?php echo ($this->audition_functions->get_website_info('cover_image_status','homepage','movies','text') ==1)? 'selected' : ''; ?>>Enable</option>
											   <option value="0" <?php echo ($this->audition_functions->get_website_info('cover_image_status','homepage','movies','text') ==0)? 'selected' : ''; ?>>Disable</option>
											   </select>	
											</div>
										</div>
										
									
							</div>
						</div>	
						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<img src="<?php echo $this->audition_functions->get_cover_image('movies').'?q='.time(); ; ?>" style="max-width: 100%;">
							</div>
						</div>
					</div>
					</div>	
					<div class="row">
						<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
										<div class="box box-success">
										<div class="box-header with-border">
										  <h3 class="box-title">Homepage Video</h3>
										</div> 
										<form action="admin/updateWebsiteInfo/movies/homepage" class="myFormList" >
											
											<div class="form-group">
											  <label>Select User</label>
											  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User',allowHtml:true,allowClear:true,templateResult: formatPageOption,templateSelection: formatPageOption}" name="user_id" class="form-control require" data-action-url="admin/getClientVideos" data-id="#post_id2" data-placeholder="select Video" data-mode="2">
													<option></option>
												<?php 
													if(isset($user_list) && !empty($user_list)){
														
														foreach($user_list as $list){
															$selected = '';
															if(isset($homepage_movies) && !empty($homepage_movies)){
																if($list['user_id'] == $homepage_movies['user_id']){
																	$selected  = 'selected="selected"';
																}
															}
															if(!empty($list["uc_pic"])){
																$img =get_user_image($list['user_id']);															
															}else{
																// $img = base_url('repo/images/user/user.png');	
																$img = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnnHdV5DwUgEsNsyE1LR8w2KwYhQAI6-tEZRs_Il9Klhw50YUwtg';	
															}
															
															echo '<option '.$selected.' data-width="90px" data-src="'.$img.'" value="'.$list['user_id'].'">'.$list['user_name'].'</option>';
														}
													}
													?>
											</select>
											
											</div>
											
											<div class="form-group">
											  <label>Select Video</label>
											  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Video',allowHtml:true,allowClear:true}" name="cover_video" data-uvid="2" class="form-control require"  id="post_id2">
													<?php if(isset($movi_vid)){
														
															
															foreach($movi_vid as $list){
																$selected = '';
																if(isset($homepage_movies) && !empty($homepage_movies)){
																	if($list['id'] == $homepage_movies['post_id']){
																		$selected  = 'selected="selected"';
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
							
						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<?php 
								$moviesvid = '';	
								if(isset($homepage_movies) && !empty($homepage_movies)){
										$moviesvid = $homepage_movies['url'];
									} ?>
								<video style="width: 100%;" controls src="<?= $moviesvid; ?>" id="moviesVid">
								  
								</video>
								<button class="btn btn-primary create_preview">Create Preview</button>
							</div>
						</div>
					</div>
					</div>
					
				
					
				
				</div>
				<div id="menu2" class="tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu2'){echo "in active";} } ?> ">
					<div class="box-header">
						  <h3 class="box-title">TV
							<small>Homepage</small>
						  </h3>
						  <div class="pull-right box-tools">
							
						  </div>
					</div>
					
						<div class="row">
						<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<div class="box-header with-border">
								  <h3 class="box-title">Homepage Cover Image</h3>
								</div>
								<!--div class="dropzone" data-url="<?= base_url('admin/uploadVideo/Homepage/television') ; ?>" id="Umusic">
									<div class="dz-default dz-message" style="padding: 35px;">
										<i class="fa fa-file-video-o" aria-hidden="true"></i>
										<p class="info_text">Drop a video here or Click to browse</p>
									</div>
								</div-->
								
										<div class="dis_upload_div">
											<input type="file" id="custom_television" name="file" class="inputfile UploadFile" style="display:none;" data-file_type="jpeg|jpg|png|gif" data-path="repo_admin/images/homepage" data-url="admin/uploadedfile/television/homepage" data-id="cover_image">
											<label for="custom_television">
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
										<form action="admin/updateWebsiteInfo/television/homepage" class="myFormList" >
											<div class="form-group">
											  <label>Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Title" autocomplete="off" name="cover_image_title" value="<?php echo  $this->audition_functions->get_website_info('cover_image_title','homepage','television','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Sub-Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Sub-Title" autocomplete="off" name="cover_image_subtitle" value="<?php echo  $this->audition_functions->get_website_info('cover_image_subtitle','homepage','television','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Text On Cover Image</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_over_image" value="<?php echo  $this->audition_functions->get_website_info('cover_over_image','homepage','television','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Cover Image Status</label>
											   <select class="form-control input-lg require" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_image_status">
											   <option value="1" <?php echo ($this->audition_functions->get_website_info('cover_image_status','homepage','television','text') ==1)? 'selected' : ''; ?>>Enable</option>
											   <option value="0" <?php echo ($this->audition_functions->get_website_info('cover_image_status','homepage','television','text') ==0)? 'selected' : ''; ?>>Disable</option>
											   </select>	
											</div>
										</div>
										
									
							</div>
						</div>	
						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<img src="<?php echo $this->audition_functions->get_cover_image('television').'?q='.time(); ; ?>" style="max-width: 100%;">
							</div>
						</div>
					</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
										<div class="box box-success">
										<div class="box-header with-border">
										  <h3 class="box-title">Homepage Video</h3>
										</div> 
										<form action="admin/updateWebsiteInfo/television/homepage" class="myFormList" >
											
											<div class="form-group">
											  <label>Select User</label>
											  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User',allowHtml:true,allowClear:true,templateResult: formatPageOption,templateSelection: formatPageOption}" name="user_id" class="form-control require" data-action-url="admin/getClientVideos" data-id="#post_id3" data-placeholder="select Video" data-mode="3">
													<option></option>
												<?php 
													if(isset($user_list) && !empty($user_list)){
														
														foreach($user_list as $list){
															$selected = '';
															if(isset($homepage_television) && !empty($homepage_television)){
																if($list['user_id'] == $homepage_television['user_id']){
																	$selected  = 'selected="selected"';
																}
															}
															if(!empty($list["uc_pic"])){
																$img =get_user_image($list['user_id']);															
															}else{
																// $img = base_url('repo/images/user/user.png');	
																$img = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnnHdV5DwUgEsNsyE1LR8w2KwYhQAI6-tEZRs_Il9Klhw50YUwtg';	
															}
															
															echo '<option '.$selected.' data-width="90px" data-src="'.$img.'" value="'.$list['user_id'].'">'.$list['user_name'].'</option>';
														}
													}
													?>
											</select>
											
											</div>
											
											<div class="form-group">
											  <label>Select Video</label>
											  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Video',allowHtml:true,allowClear:true}" name="cover_video" data-uvid="3" class="form-control require"  id="post_id3">
													<?php if(isset($tv_vid)){
														
															
															foreach($tv_vid as $list){
																$selected = '';
																if(isset($homepage_television) && !empty($homepage_television)){
																	if($list['id'] == $homepage_television['post_id']){
																		$selected  = 'selected="selected"';
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
							
						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<?php 
								$televisionvid = '';	
								if(isset($homepage_television) && !empty($homepage_television)){
										$televisionvid = $homepage_television['url'];
									} ?>
								<video style="width: 100%;" controls src="<?= $televisionvid; ?>" id="televisionVid">
								  
								</video>
								<button class="btn btn-primary create_preview">Create Preview</button>
							</div>
						</div>
					</div>
					</div>					
					
				</div>
				
				
				
				
				
				
				
				<div id="menu3" class="tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu3'){echo "in active";} } ?> ">
					<div class="box-header">
						  <h3 class="box-title">Gaming
							<small>Homepage</small>
						  </h3>
						  <div class="pull-right box-tools">
							
						  </div>
					</div>
					
						<div class="row">
						<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<div class="box-header with-border">
								  <h3 class="box-title">Homepage Cover Image</h3>
								</div>
								
								
										<div class="dis_upload_div">
											<input type="file" id="custom_gaming" name="file" class="inputfile UploadFile" style="display:none;" data-file_type="jpeg|jpg|png|gif" data-path="repo_admin/images/homepage" data-url="admin/uploadedfile/gaming/homepage" data-id="cover_image">
											<label for="custom_gaming">
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
										<form action="admin/updateWebsiteInfo/gaming/homepage" class="myFormList" >
											<div class="form-group">
											  <label>Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Title" autocomplete="off" name="cover_image_title" value="<?php echo  $this->audition_functions->get_website_info('cover_image_title','homepage','gaming','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Sub-Title</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Cover Image Sub-Title" autocomplete="off" name="cover_image_subtitle" value="<?php echo  $this->audition_functions->get_website_info('cover_image_subtitle','homepage','gaming','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Text On Cover Image</label>
											   <input class="form-control input-lg require" type="text" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_over_image" value="<?php echo  $this->audition_functions->get_website_info('cover_over_image','homepage','gaming','text'); ?>">	
											</div>
											<div class="form-group">
											  <label>Cover Image Status</label>
											   <select class="form-control input-lg require" placeholder="Enter Text On Cover Image" autocomplete="off" name="cover_image_status">
											   <option value="1" <?php echo ($this->audition_functions->get_website_info('cover_image_status','homepage','gaming','text') ==1)? 'selected' : ''; ?>>Enable</option>
											   <option value="0" <?php echo ($this->audition_functions->get_website_info('cover_image_status','homepage','gaming','text') ==0)? 'selected' : ''; ?>>Disable</option>
											   </select>	
											</div>
										</div>
										
									
							</div>
						</div>	
						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<img src="<?php echo $this->audition_functions->get_cover_image('gaming').'?q='.time(); ; ?>" style="max-width: 100%;">
							</div>
						</div>
					</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
										<div class="box box-success">
										<div class="box-header with-border">
										  <h3 class="box-title">Homepage Video</h3>
										</div> 
										<form action="admin/updateWebsiteInfo/gaming/homepage" class="myFormList" >
											
											<div class="form-group">
											  <label>Select User</label>
											  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User',allowHtml:true,allowClear:true,templateResult: formatPageOption,templateSelection: formatPageOption}" name="user_id" class="form-control require" data-action-url="admin/getClientVideos" data-id="#post_id7" data-placeholder="select Video" data-mode="7">
													<option></option>
												<?php 
													if(isset($user_list) && !empty($user_list)){
														
														foreach($user_list as $list){
															$selected = '';
															if(isset($homepage_gaming) && !empty($homepage_gaming)){
																if($list['user_id'] == $homepage_gaming['user_id']){
																	$selected  = 'selected="selected"';
																}
															}
															if(!empty($list["uc_pic"])){
																$img =get_user_image($list['user_id']);															
															}else{
																// $img = base_url('repo/images/user/user.png');	
																$img = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnnHdV5DwUgEsNsyE1LR8w2KwYhQAI6-tEZRs_Il9Klhw50YUwtg';	
															}
															
															echo '<option '.$selected.' data-width="90px" data-src="'.$img.'" value="'.$list['user_id'].'">'.$list['user_name'].'</option>';
														}
													}
													?>
											</select>
											
											</div>
											
											<div class="form-group">
											  <label>Select Video</label>
											  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Video',allowHtml:true,allowClear:true}" name="cover_video" data-uvid="7" class="form-control require"  id="post_id7">
													<?php if(isset($game_vid)){
														
															
															foreach($game_vid as $list){
																$selected = '';
																if(isset($homepage_gaming) && !empty($homepage_gaming)){
																	if($list['id'] == $homepage_gaming['post_id']){
																		$selected  = 'selected="selected"';
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
							
						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box-body ">
							<div class="box box-success">
								<?php 
								$gamingid = '';	
								if(isset($homepage_gaming) && !empty($homepage_gaming)){
										$gamingid = $homepage_gaming['url'];
									} ?>
								<video style="width: 100%;" controls src="<?= $gamingid; ?>" id="gamingid">
								  
								</video>
								<button class="btn btn-primary create_preview">Create Preview</button>
							</div>
						</div>
					</div>
					</div>					
					
				</div>
				
				
				
				
				
				

				
				<div id="menu4" class="tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu4'){echo "in active";} } ?> ">
				
					<div class="box-header">
					  <h3 class="box-title">Social
						<small>Homepage</small>
					  </h3>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="box-body ">
								<div class="box box-success">
											<div class="box box-success">
											<div class="box-header with-border">
											  <h3 class="box-title">Social Homepage Video</h3>
											</div> 
											<form action="admin/updateWebsiteInfo/social/homepage" class="myFormList" >
												
												<div class="form-group">
												  <label>Select User</label>
												  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User',allowHtml:true,allowClear:true,templateResult: formatPageOption,templateSelection: formatPageOption}" name="user_id" class="form-control require" data-action-url="admin/getClientVideos" data-id="#post_id4" data-placeholder="select Video">
														<option></option>
													<?php 
														if(isset($user_list) && !empty($user_list)){
															
															foreach($user_list as $list){
																$selected = '';
																if(isset($homepage_social) && !empty($homepage_social)){
																	if($list['user_id'] == $homepage_social['user_id']){
																		$selected  = 'selected="selected"';
																	}
																}
																if(!empty($list["uc_pic"])){
																	$img =get_user_image($list['user_id']);															
																}else{
																	// $img = base_url('repo/images/user/user.png');	
																	$img = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnnHdV5DwUgEsNsyE1LR8w2KwYhQAI6-tEZRs_Il9Klhw50YUwtg';	
																}
																
																echo '<option '.$selected.' data-width="90px" data-src="'.$img.'" value="'.$list['user_id'].'">'.$list['user_name'].'</option>';
															}
														}
														?>
												</select>
												
												</div>
												
												<div class="form-group">
												  <label>Select Video</label>
												  <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Video',allowHtml:true,allowClear:true}" name="cover_video" data-uvid="4" class="form-control require"  id="post_id4">
														<?php if(isset($social_vid)){
															
																
																foreach($social_vid as $list){
																	$selected = '';
																	if(isset($homepage_social) && !empty($homepage_social)){
																		if($list['id'] == $homepage_social['post_id']){
																			$selected  = 'selected="selected"';
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
								
							<div class="box-footer">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
							</form>
						</div>
						
						<div class="col-md-6">
							<div class="box-body ">
								<div class="box box-success">
									<?php 
									$socialvid = '';	
									if(isset($homepage_social) && !empty($homepage_social)){
											$socialvid = $homepage_social['url'];
										} ?>
									<video style="width: 100%;" controls src="<?= $socialvid; ?>" id="socialVid">
									  
									</video>
									<button class="btn btn-primary create_preview">Create Preview</button>
								</div>
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
