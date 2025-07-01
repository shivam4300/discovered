<?php
$levellist 			= $this->valuelist->level();
$modelist 			= $this->valuelist->mode();

$user_id 			= isset($single_video['user_id'])?$single_video['user_id']:'';
$mode 				= isset($single_video['mode'])?$single_video['mode']:'';

$age_restr 			= isset($single_video['age_restr'])?$single_video['age_restr']:'';
$title 				= isset($single_video['title'])?$single_video['title']:'';
$description 		= isset($single_video['description'])?$single_video['description']:'';
$genre_id 			= isset($single_video['genre'])?$single_video['genre']:'';
$genre_name 		= isset($single_video['genre_name'])?ucfirst($single_video['genre_name']):'Select Genre';
$subgenre_id 		= isset($single_video['sub_genre'])?$single_video['sub_genre']:'';

$category 			= isset($single_video['category'])?$single_video['category']:'';
$language 			= isset($single_video['language'])?$single_video['language']:'';
$tag 				= isset($single_video['tag'])?$single_video['tag']:'';
$privacy_status 	= isset($single_video['privacy_status'])?$single_video['privacy_status']:'';
$post_id 			= isset($single_video['post_id'])?$single_video['post_id']:'';
$post_key 			= isset($single_video['post_key'])?$single_video['post_key']:'';

$ages 				= $this->audition_functions->age();
// print_r($single_video);die;
$is_session_uid 	= is_session_uid($user_id);

$ss 				= $this->audition_functions->post_status();

?>

	<div class="dis_user_data_wrapper" id="section2">
		<div class="container">
				<div class="row" id="SubmitChannelVideoDetail">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="dis_upload_video">
							<div class="dis_video_heading">

								<h3>
								<?php
								if(isset($single_video['user_id'])){
									echo 'EDIT VIDEO';
								}else{
									echo 'MONETIZE VIDEO/ UPLOAD TO YOUR CHANNEL';
								}
								?>
								</h3>
							</div>
							<div class="dis_upload_video_inner">
								<h4>Select an Auto-Generated Thumbnail of Your Choice (1-3) or Upload A Custom made 1080 x 771 JPEG Thumbnail Image</h4>
								<div class="row dis_custom_row" id="theDiv">
									<div class="dis_custom_width">
										<div class="dis_video_thumbnail dis_select_video" style="display:none;">
											<div class="dis_upload_div">
												<input type="file" id="custom_file" name="file" class="inputfile">
												<input type="hidden" id="uid" value="<?= $user_id; ?>" class="inputfile">
												<label for="custom_file"><figure><img src="<?php echo base_url() ?>repo/images/upload_icon2.jpg" class="img-responsive" alt=""> <svg xmlns="https://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 45 35"><path class="cls-1" fill= "#777" fill-rule="evenodd" d="M1348.68,1216.23a12.509,12.509,0,0,0-12.59-12.23,12.654,12.654,0,0,0-8.3,3.09,12.323,12.323,0,0,0-4,6.76h-0.13a10.506,10.506,0,1,0,0,21.01h7.45a0.945,0.945,0,1,0,0-1.89h-7.45a8.616,8.616,0,1,1,0-17.23c0.26,0,.53.02,0.84,0.04a0.954,0.954,0,0,0,1.04-.81,10.4,10.4,0,0,1,3.52-6.46,10.691,10.691,0,0,1,17.7,7.89c0,0.21-.01.42-0.03,0.65l-0.01.1a0.931,0.931,0,0,0,.29.74,0.979,0.979,0,0,0,.77.27,6.45,6.45,0,0,1,.76-0.04,7.426,7.426,0,1,1,0,14.85h-7.83a0.945,0.945,0,1,0,0,1.89h7.83A9.316,9.316,0,1,0,1348.68,1216.23Zm-12.59-7.79a8.068,8.068,0,0,0-7.99,6.87,0.956,0.956,0,0,0,.82,1.07,0.66,0.66,0,0,0,.14.01,0.949,0.949,0,0,0,.94-0.82,6.15,6.15,0,0,1,6.09-5.24A0.945,0.945,0,1,0,1336.09,1208.44Zm4.37,18.61-3.49-3.08a1.6,1.6,0,0,0-2.11,0l-3.5,3.08a0.928,0.928,0,0,0-.07,1.33,0.971,0.971,0,0,0,1.35.08l2.31-2.04v11.63a0.96,0.96,0,0,0,1.92,0v-11.63l2.31,2.04a0.959,0.959,0,0,0,1.35-.08A0.928,0.928,0,0,0,1340.46,1227.05Z" transform="translate(-1313 -1204)"/></svg></figure></label>
											</div>
											<img src="<?= base_url('repo/images/loader.gif'); ?>" id="thumbloader" alt="" style="position: absolute;top: 14px;left:0;right:0;margin:auto;display:none;" >
											<h2>Upload Custom Thumbnail</h2>
										</div>
									</div>
								</div>
								<h4 style="display:none;" id="nothumberr" style="text-align:center;padding-bottom:10px;color:red;">Something went wrong while creating thumbnails for your video, please try uploading a custom thumbnail.</h4>

								<div class="row">
									<div class="dis_upload_form dis_signup_form">
										<form method="POST" action="dashboard/submitchannelform" class="channelform">
											<input type="hidden" name="post_id" value="<?= $post_id; ?>" id="VideoPostId" data-post_key="<?= $post_key; ?>">
											<input type="hidden" name="post_uid" value="<?= $user_id; ?>">

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="upload_playlist_Wrap">
													<div class="upl_inner">
														<div class="upl_box">
															<h2 class="upl_ttl dis_playlisttgl">Playlist</h2>
															<span class="dis_cross_sign"></span>
														</div>

														<div class="upl_dd_wrap" class="dis_playlistview">
															<div class="upl_dd_header">
																<h2 class="upl_dd_head_ttl">Playlist</h2>
																<h2 class="upl_dd_head_new newpl_click" data-id="NewPlaylist">Create New PlayList</h2>
															</div>
															<ul class="upl_dd" id="PlayListArea">

															</ul>
															<div class="upl_dd_middle hideme" id="ShowPlayListForm">
																<div class="upl_dd_newpl_inner">
																	<div class="upl_dd_pl_filed">
																		<textarea placeholder="Playlist Title" class="upl_dd_pl_inner" id="playlistTitle" maxlength="50"></textarea>
																	</div>
																	<div class="upl_dd_pl_filed">
																		<select class="upl_dd_pl_inner" id="PlayListStatus">
																			<?php
																			foreach($ss as $k=>$v){
																				$s = ($k == 7)?'selected="selected"':'';
																				echo '<option '.$s.' value="'.$k.'">'.$v.'</option>';
																			}
																			?>
																		</select>
																	</div>
																</div>
															</div>
															<div class="upl_dd_footer">
																<div class="upl_dd_foot_inner hideme">
																	<h2 class="upl_dd_foot_new newpl_click" data-id="CancelPlaylist">Cancel</h2>
																	<a href="javascript:;" class="upl_dd_foot_btn createNewPlaylist" data-page="singlepage">Create</a>
																</div>
															</div>
														</div>
													</div>
													<span class="form-error help-block"></span>
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input required"  name="mode" id="mode">
															<option value="">Select Mode</option>
															<?php
															foreach($website_mode as $wmode){
																if($wmode["mode_id"] != 8)
																$sel=($mode == $wmode["mode_id"])?'selected="selected"':'';
																echo '<option '.$sel.' value="'.$wmode["mode_id"].'">'.ucfirst($wmode["mode"]).'</option>';
															}
															?>
														</select>
													</div>
													<span class="form-error help-block"></span>
												</div>
											</div>

											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input required" name="genre" id="genre">
															<?php
																if(isset($genre_list) && !empty($genre_list)){
																	foreach($genre_list as $genre){
																		$sel=($genre_id  == $genre["genre_id"])?'selected="selected"':'';
																		echo '<option  '.$sel.' value="'.$genre["genre_id"].'">'.$genre["genre_name"].'</option>';
																	}
																}
															?>
														</select>
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input" name="sub_genre" id="sub_genre">
															<?php
																if(isset($sub_genre_list) && !empty($sub_genre_list)){
																	foreach($sub_genre_list as $subgenre){
																		$sel=($subgenre_id  == $subgenre["genre_id"])?'selected="selected"':'';
																		echo '<option  '.$sel.' value="'.$subgenre["genre_id"].'">'.$subgenre["genre_name"].'</option>';
																	}
																}
															?>
														</select>
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input" name="category" >
															<option value="">Select Category (optional)</option>
															<?php
																foreach($catDetail as $cat){
																	// $selected = ($cat['category_name'] == 'Other') ? 'selected="selected"' : '';
																	$selected=($cat['category_id']==$category)?'selected="selected"':'';
																	echo '<option '.$selected.' value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
																}

															?>

														</select>
														<div class="login_tooltip">
															<i class="fa fa-question-circle " aria-hidden="true"></i>
														</div>
														<span class="login_cstm_pop">Choosing the Category, will help fans to view Video content based on Category code linked to each video. If you don't make a selection, system will auto-categorize as OTHER</span>
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<input type="text"  class="form-control dis_signup_input required" placeholder="Video Title" name="title" maxlength="100" value="<?= $title; ?>">
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input required"  name="language" >
															<option value="">Select Video Language</option>
															<?php
																foreach($language_list as $list){
																	if(empty($language)){
																		$sel = ($list['id']=='en_US')  ? 'selected="selected"' : '';
																	}else{
																		$sel = ($list['id']==$language)?'selected="selected"':'';
																	}
																	echo '<option '.$sel.' value="'.$list['id'].'">'.$list['value'].'</option>';
																}

															?>
														</select>
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
													<?php $ages = $this->audition_functions->age(); ?>
														<select class="form-control dis_signup_input required" name="age_restr" >
																<option value="">Age Restrictions(in year)</option>
																<?php
																	foreach($ages as $k=>$age){
																		$sel = isset($ages[$age_restr]) && ($ages[$age_restr] == $age) ?'selected="selected"' : '';
																		echo '<option '.$sel.' value="'.$k.'">'.$age.'</option>';
																	}
																?>
														 </select>
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<?php if(!isset($single_video)){ ?>
											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input required" name="social" >
															<option value="" selected="selected">Share this video to social page as well ?</option>
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input required" name="social_cover_video" >
															<option value="" selected="selected">Share this video as social cover video ?</option>
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<?php } ?>

											<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control dis_signup_input required" name="privacy_status" >
																<?php
																	foreach($ss as $k=>$v){
																		$s=($privacy_status==$k)?'selected="selected"':0;
																		echo '<option '.$s.' value="'.$k.'">'.$v.'</option>';
																	}
																?>
														</select>

													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="form-group custom_descriptio">
													<div class="">
														<input  type="text" class="dis_signup_input tokenfield" placeholder="Video Keywords Tag" name="tag" maxlength="100" id="tag" value="<?= trim($tag);?>">
														<div class="login_tooltip">
															<i class="fa fa-question-circle" aria-hidden="true"></i>
														</div>
														<span class="login_cstm_pop">Please add keywords tag to boost the performance of your content.You can bulk copy comma separated tags. A total of 65 thousand characters can be used for tags</span>
													</div>
													<span  class="form-error help-block"></span>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="form-group">
													<label class="from_filedlble">Enter your Description</label>
													<div class="input-group description">
														<textarea class="form-control dis_signup_input classpadding required" placeholder="Video Description"  id="description" name="description"  row="20"><?= $description ; ?></textarea>
														<div id="openAiBox"></div>
													</div>
													<span  class="form-error help-block"  id="editorErr"></span>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="dis_button_div" >

														<div class="checkbox dis_checkbox <?= isset($single_video['user_id']) ? 'hide' : '';  ?>">
															<label>
																<input type="checkbox" value="" class="check" <?= isset($single_video['user_id']) ? 'checked' : '';  ?> >
																<i class="input-helper"></i>
																<p>By Publishing, I Confirm That I Have Read the <a target="_blank" href="<?= base_url('policies');?>">Terms &amp; Conditions</a>, Own This Video, and Assume Responsibility For Any Copyright Violations. </p>
															</label>
															<span  class="form-error help-block" id="check"></span>
														</div>


													<button type="submit" class="dis_btn"  data-form="icon_form">
													<?php
														if(isset($single_video['user_id'])){
															echo 'UPDATE VIDEO';
														}else{
															echo 'PUBLISH VIDEO';
														}
														?>
													<span class="publish_btn">
													<i class="fa fa-spinner fa-pulse fa-fw"></i></span></button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<?php /************** BELOW SECTION OF PROFILE PAGE , ENDS   ***************************/ ?>



