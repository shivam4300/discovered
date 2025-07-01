	
	<?php
	
	$user_name 		= isset($usersDetail[0])?$usersDetail[0]['user_name'] 	: '';
	$user_phone 	= isset($usersDetail[0])?$usersDetail[0]['user_phone'] 	: '';
	$uc_gender 		= isset($usersDetail[0])?$usersDetail[0]['uc_gender'] 	: '';
	$uc_dob 		= isset($usersDetail[0])?$usersDetail[0]['uc_dob'] 	: '';
	$uc_type 		= isset($usersDetail[0])? explode(',',$usersDetail[0]['uc_type']) 	: '';
	
	$user_address 	= isset($usersDetail[0])?$usersDetail[0]['user_address'] : '';
	$country 		= isset($usersDetail[0])?$usersDetail[0]['uc_country'] 	: '';
	$state 			= isset($usersDetail[0])?$usersDetail[0]['uc_state'] 	: '';
	$city 			= isset($usersDetail[0])?$usersDetail[0]['uc_city'] 	: '';
	$uc_zipcode 	= isset($usersDetail[0])?$usersDetail[0]['uc_zipcode'] 	: '';
	$state_name 	= isset($state_name[0])?$state_name[0]['name'] 	: '';
	
	?>
	
	
<div class="dis_profileedit_wrapper">

	<ul class="">
		<li class="active"><a data-toggle="tab" href="#home">Profile</a></li>
		<li><a data-toggle="tab" href="#menu1">Password reset</a></li>
		<li class="hide"><a data-toggle="tab" href="#delete">Delete Account</a></li>
		<!--?php if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'express'){ ?-->
		<li><a data-toggle="tab" href="#menu2">Upgrade Account</a></li>
		<!--?php } ?-->
	</ul>

	<div class="tab-content">
		<div id="home" class="tab-pane fade in active">
			<div class="container">
				<div class="row">
				<form action="<?php echo base_url('settings/UpdateUserInfo'); ?>" method="POST" id="UserUpdateForm" data-form="UserUpdateForm" >
					<div class="col-md-12">
						<div class="profile_edit_inner">
							<h1 class="profile_edit_title">edit Profile</h1>
							
							<div class="edit_box">
								<div class="edit_thumb">
									<a href="" class="pro_icon">
										<img class="img-reponsive"  src="<?php echo get_user_image($uid) ;?>" title="<?php echo $user_name;?>"  onerror="this.onerror=null;this.src='<?= base_url("repo/images/user/user.png")?>'" >
									</a>
									<span class="p_edit_w" data-toggle="dropdown">
										<i class="fa fa-pencil" aria-hidden="true"></i>
									</span>
									<div class="dis_action_content">
										<ul>
											<li class="Remove_profile_picture">Remove Profile Picture</li>
											<!--li class="" data-toggle="modal" data-target="#upload_image">Change Profile Picture</li--> 
											<li class="openModalPopup" data-href="modal/upload_profile_picture_popup" data-cls="Audition_popup upload_popup">Upload Profile Picture</li>
										</ul>
									</div>
									<!-- <i class="fa fa-pencil-square-o" aria-hidden="true"></i> -->
								</div>
								<div class="pe_forminner dis_signup_form">
									
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" class="form-control validate cut_copy_paste" name="user_name" placeholder="Profile Name (Public)*" value="<?= $user_name; ?>">
													<span class="form-error help-block"></span>		
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select class="form-control validate" name="uc_gender" placeholder="gender">
													<?php $genders = $this->audition_functions->genders(); ?>
														<option value="0">Gender</option>
														<?php foreach($genders as $key=>$value){
															$selected =  ($key == $uc_gender)?  'selected="selected"' : '';
															echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
														}?>
													</select>
													<span class="form-error help-block"></span>		 	
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<input class="form-control simple_rang datePicker validate" type="text" name="uc_dob"  value="<?php echo !empty($uc_dob) && ($uc_dob != '0000-00-00') ? $uc_dob : date('Y-m-d') ;?>"  />
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" class="form-control validate" name="user_phone" placeholder="Phone" value="<?php echo $user_phone; ?>" data-type="number">
													<span class="form-error help-block"></span>														
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" class="form-control validate" name="user_address" placeholder="Address" value="<?php echo $user_address; ?>">
													<span class="form-error help-block"></span>														
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select  name="uc_country" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' Select Country',allowHtml:true,width: '100%'}"  class="form-control validate SelectBySelect2" data-url="settings/getStateArray" data-id="#state" >
													<?php 
													echo '<option value=""></option>';
													foreach($countries as $c){
														$selected='';
														if($c['country_id'] == $country)
															$selected='selected="selected"'; 
														echo '<option '.$selected.' value="'.$c['country_id'].'">'.$c['country_name'].'</option>'; 
													}?>
													</select> 
													<span class="form-error help-block"></span>		
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select class="form-control validate" name="uc_state" id="state" data-placeholder="Select State" value="<?= $state; ?>">
													<?php 
													echo '<option value="'.$state.'">'.$state_name.'</option>';
													?>
													</select>
													<span class="form-error help-block"></span>		
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" class="form-control validate" name="uc_city" placeholder="City" value="<?php echo $city; ?>">	
													<span class="form-error help-block"></span>		
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" class="form-control validate dis_signup_input" name="uc_zipcode" placeholder="Zipcode*" data-type="alphaNum"  value="<?php echo $uc_zipcode; ?>">
													<span class="form-error help-block"></span>
												</div>
											</div>
											<?php if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard' ){ ?>
											<div class="col-md-6">
												<div class="form-group">
													<select class="form-control primay_select validate"  name="uc_type" multiple="multiple" placeholder="Type(s)*" data-placeholder="Search Type" data-target="select2"  data-option="{closeOnSelect:true}" >
													<option></option>
													<?php if( isset($artist_category) && !empty($artist_category) ) {
															foreach($artist_category as $artist){
																$selected = in_array($artist['category_id'],$uc_type)?'selected="selected"' : '';
																echo '<option '.$selected.' value="'.$artist['category_id'].'">'.$artist['category_name'].'</option>';
															}
														} ?>
													</select>
													<span class="form-error help-block"></span>		
												</div>
											</div>
											<?php } ?>
										</div>
									
								</div>
							</div>
						</div>
						<div class="pe_btn m_t_30 text-center">
							<a class="dis_btn" onclick="UpdateUserForm('#UserUpdateForm')">Update</a>
						</div>
						
					</div>
				</form>
				</div>
			</div>	
		</div>
		<div id="menu1" class="tab-pane fade reset_p">
			<div>
				<div class="container">
					<div class="row">
						<form action="<?php echo base_url('settings/UpdateUserPassword'); ?>" method="POST" id="PassUpdateForm" data-form="PassUpdateForm" >
						<div class="col-md-12">
							<div class="profile_edit_inner">
								<h1 class="profile_edit_title">Password reset</h1>
								
								<div class="edit_box">
									
									<div class="pe_forminner dis_signup_form">
										
											<div class="row">
												<!--div class="col-md-4">
													<div class="form-group">
														<input type="text" class="form-control require" name="old_password" placeholder="Old Password" value="">	
														<span class="form-error help-block"></span>		
													</div>
												</div-->
												<div class="col-md-6">
													<div class="form-group">
													<input type="password" class="form-control require pwd" name="new_password" placeholder="New Password" id="eye_new" value="">
													<span toggle="#eye_new" class="dis_eyeIcon fa fa-eye dis-toggle-password" aria-hidden="true"></span>
													<span class="form-error help-block"></span>														
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="password" class="form-control require repwd" name="confirm_password" placeholder="Confirm Password" id="eye_confirm" value="">	
														<span toggle="#eye_confirm" class="dis_eyeIcon fa fa-eye dis-toggle-password" aria-hidden="true"></span>
														<span class="form-error help-block"></span>		
													</div>
												</div>
												
											</div>
										
									</div>
								</div>
							</div>
							<div class="pe_btn m_t_30 text-center">
								<a class="dis_btn" onclick="PassUpdateForm('#PassUpdateForm')">Update</a>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div id="menu2" class="tab-pane fade reset_p">
			<div>
				<div class="container">
					<div class="row"> 
						<form action="<?php echo base_url('settings/UpdateUserSetting'); ?>" method="POST" id="PassUpdateForm" data-form="PassUpdateForm" >
						<div class="col-md-12">
							<div class="profile_edit_inner">
								<h1 class="profile_edit_title">Upgrade Account</h1>
								<div class="edit_box">
									<div class="dis_signup_form">
										<div class="row">
											<div class="col-md-12 text-center">
												<p>Click on the below given button to Upgrade or change your account type.</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="pe_btn m_t_30 text-center">
								<a href="<?= base_url('home/ExpressToStandard'); ?>" class="dis_btn">Upgrade Account</a>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="delete" class="tab-pane fade">			
			<div class="container">
				<div class="row">					
					<div class="col-md-12">
						<div class="profile_edit_inner">
							<h1 class="profile_edit_title">Delete Account</h1>
							<div class="edit_box">
								<div class="dis_signup_form">
									<div class="row">
										<div class="col-md-12">
											<div class="muli_font">
												<!-- <ul class="dis_userdelete_radio">
													<li>
														<input type="radio" id="delete_acount" name="radio-group" checked>
														<label for="delete_acount">Permanent Delete Account</label>
													</li>
													<li>
														<input type="radio" id="deactivate" name="radio-group">
														<label for="deactivate">Temporary Deactivate Account</label>
													</li>												
												</ul> -->
												<ul class="dis_userdelete_radio">
													<li>
														<input type="radio" id="delete_acount" name="is_deleted_status" value="2" class="deleteMyAcc">
														<label for="delete_acount" class="dis_userdelete_btn">Permanently Delete Account</label>
													</li>
													<li>
														<p class="dis_userdelete_OR mp_0">OR</p>
													</li>
													<li>			
														<input type="radio" id="deactivate" name="is_deleted_status" value="3" class="deactivateMyAcc">											
														<label for="deactivate" class="dis_userdelete_btn">Temporarily Deactivate Account</label>
													</li>												
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>
		
	</div>


		
	
</div>

<!-- <div class="dis_common_popup dis_embed_urlvideo custom_scrol open_commonpopu">
	<div class="common_popup_inner">
		<div class="dis_euvideo_box">
			<div class="dis_euvideo_url">
				<iframe class="dis_euvideo_iframe" width="100%" src="https://test.discovered.tv/embedcv/128" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>
			</div>
			<div class="dis_euvideo_details">
				<div class="dis_euvd_header">
					<p class="dis_euvd_ttl"> Embed Video</p>
					<span class="common_close">
						<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" width="11px" height="11px"><g><path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#EB581F"></path></g> </svg>
					</span>
				</div>
				<div class="dis_euvd_body">
					<div class="dis_euvd_ta_wrap">
						<textarea><iframe width="560" height="315" src="https://www.youtube.com/embed/EqNZKZjPWq0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></textarea>
					</div>
					<div class="dis_euvd_options">
						<p>EMBED OPTIONS</p>
						<div class="dis_checkbox">
							<label>
								<input type="checkbox" value="" class="check">
								<i class="input-helper"></i>
								<p>Show player controls.</p>
							</label>
						</div>
						<div class="dis_checkbox">
							<label>
								<input type="checkbox" value="" class="check">
								<i class="input-helper"></i>
								<p>Enable privacy-enhanced mod</p>
							</label>
						</div>
					</div>
				</div>
				<div class="dis_euvd_footer">
					<a class="dis_euvd_copybtn" href="">Copy</a>
				</div>
			</div>	
		</div>	
	</div>	
</div> -->



<div class="modal fade muli_font delete_deactivate_modal dis_center_modal " id="deleteuser_account" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="dis_modal_title" id="formTitle1">Delete User Account</h4>
        </div>
        <div class="modal-body">
			<div class="dis_deleteacc_tm">
				<h2 class="dis_deleteacc_h">Important Note</h2>
				<p class="dis_deleteacc_p">Before clicking "Agree And Continue" please review the following points :</p>
				<ol class="dis_deleteacc_notes">
					<li>Deleting the account is an irrevocable process and all your videos, photos, comments, social, chats, and any other activity on Discovered will be deleted from all Discovered Platforms.</li>
					<li>Any unpaid balance will be settled during the next periodic run of the payment cycle.</li>
					<li>Before deleting your account, we highly recommended you to log in and download a copy of your information (like revenue activity, dashboard reports, posts, etc.). Once deleted, you will not have access to this information.</li>
				</ol>
			
				<div class="dis_deleteacc_clnder">
					<label class="dis_field_label" id="formTitle2">Select date of deleting account</label>
					<div class="dis_field_wrap">
						<input readonly type="text" class="datepicker dis_field_input" name="deleteOrDeactivateDate" id="deleteOrDeactivateDate">
					</div>
				</div>
				<div>
					<button class="dis_btn brandon_font agreeForDeleteOrDeactivate" >Agree And Continue</button>
				</div>
			</div>

			<div class="dis_deleteacc_reason hide">			
				<!--div class="m_b_20">
					<label class="dis_field_label">Enter your Password</label>
					<div class="dis_field_wrap">
						<input type="password" class="dis_field_input">
					</div>
				</div-->
				<div class="m_b_20">
					<label class="dis_field_label"  id="formTitle3">Reason for deleting account</label>
					<div class="dis_field_wrap">
						<textarea class="dis_field_input" placeholder="Mention the reason for deleting your account." id="reason"></textarea>
					</div>
				</div>
				<div>
					<button class="dis_btn brandon_font submitDltOrDactvtAccRqst">Submit</button>
				</div>
			</div>
        </div>
      </div>
    </div>
  </div>

