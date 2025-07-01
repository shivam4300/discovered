<div class="dis_signup_wrapper dis_artist_info">

	<div class="container-fluid">

		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<div class="dis_signup_div with_inherit">

					<div class="dis_signup_inner">

						<div class="dis_signup_left">

							<div class="dis_signup_img">

								<img src="<?php echo base_url().'repo/images/signup_img.jpg';?>" class="img-responsive" alt="">

								<div class="dis_overlay">

									<img src="<?php echo base_url().'repo/images/signup_img_logo.png';?>" class="img-responsive" alt="">

								</div>

							</div>

						</div>

						<div class="dis_signup_right dis_padding_30">

							<div class="dis_signup_right_inner dis_musician_detail  <?php echo ($type==1)?'dic_icon_type':''; ?>">

								<div class="au_heading">

									<h2>Add Your <?= (isset($category[0]['category_name']))?$category[0]['category_name']:''; ?> Details</h2>

								</div>

								<div class="dis_signup_form">

									<form  id="icon_form" method="post"> 
										<div class="row">											
											<!--div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control validate dis_signup_input cut_copy_paste" name="user_name" placeholder="Profile name (Public)*" value="<?= isset($user_details[0]['user_name'])?$user_details[0]['user_name']:'';?>">														
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div-->
											<?php if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard' ){ ?>
											<div class="col-md-6">
												<div class="form-group artist_info_urlwrap">
													<h2 class="pro_titleurl"><?= base_url();?></h2>
													<div class="pro_url_filed">
														<div class="input-group">
															<input type="text" class="form-control validate dis_signup_input cut_copy_paste" name="user_uname" placeholder="Your Profile URL" >
															<div class="login_tooltip">
																<i class="fa fa-question-circle " aria-hidden="true"></i>
															</div>
															<span class="login_cstm_pop">Please enter the user name with no spaces and special characters. Adding your user name in Profile URL field will be your Discovered channel page link. User name entered in this field has to be unique.</span>
														</div>
														<span class="form-error help_error uniq_name_error">
														<?php
															if($this->session->flashdata('uniq_name_error')) {
																echo $this->session->flashdata('uniq_name_error');
															}
														?>
														</span>
													</div>
												</div>
											</div>
											<?php } ?>
											<div class="col-md-6">
												<div class="form-group">
													<?php 
													$readonly 	= '';
													$email 		= '';
													if(isset($user_details[0]['user_email']) && $user_details[0]['user_email'] != 'undefined' && trim($user_details[0]['user_email'] ) != ''){
															$readonly = 'readonly';
															$email = $user_details[0]['user_email'];
													} ?>
													<div class="input-group">
														<input type="text" class="form-control validate dis_signup_input email chkDupEml" name="user_email" <?= $readonly; ?> placeholder="Account email*" value="<?php echo $email;?>">
														
													</div>
													<span class="form-error help_error"></span>

												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<?php $genders = $this->audition_functions->genders(); ?>
														<select class="form-control validate dis_signup_input" name="uc_gender" placeholder="Gender">
															<option value="">Gender</option>
															<?php foreach($genders as $key => $value){
																echo '<option value="'.$key.'"> '.$value.' </option>';
															} ?>
														</select>
														
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<select class="form-control validate dis_signup_input" name="d">
																<option value="0">Date</option>
																<?php for($i=1;$i <= 31;$i++){
																	echo '<option value="'.$i.'">'.$i.'</option>';
																}?>
																</select>
															</div>
															<span class="form-error help_error"></span>
														</div>
													</div>
													<div class="col-md-4">
															<div class="form-group">
																<div class="input-group">
																	<select class="form-control validate dis_signup_input" name="m">
																		<option value="0">Month</option>
																		<?php for($i=1;$i <= 12;$i++){
																			echo '<option value="'.$i.'">'.$i.'</option>';
																		}?>
																	</select>
																</div>
																<span class="form-error help_error"></span>
															</div>
													</div>
													<div class="col-md-4">
															<div class="form-group">
																<div class="input-group">
																	<select class="form-control validate dis_signup_input" name="y">
																		<option value="0">Year</option>
																		<?php for($i=1920;$i <= date('Y');$i++){
																			echo '<option value="'.$i.'">'.$i.'</option>';
																		}?>
																	</select>
																</div>
																<span class="form-error help_error"></span>
															</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control <?php echo ($type != 4)?'validate phone_number':''; ?> dis_signup_input" name="user_phone" placeholder="Phone<?php echo ($type != 4)?'*':''; ?>" value="" data-type="number" maxlength="12">
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control <?php echo ($type != 4)?'validate':''; ?> dis_signup_input" name="user_address" placeholder="Mailing address <?php echo ($type != 4)?'*':''; ?>" value="">
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											<?php if($type == 1){ ?> 
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
													<input type="text" class="form-control dis_signup_input" name="cont_name" placeholder="Reference name" >
														<div class="login_tooltip">
															<i class="fa fa-question-circle " aria-hidden="true"></i>
														</div>
														<span class="login_cstm_pop">Manager, agent, legal representative or entertainment company contact name</span>
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											<?php } ?>
											<div class="col-md-6">	
												<div class="form-group">
													<div class="input-group">
														<select class="form-control validate dis_signup_input" name="country" onchange="getStates(this);">
															<option value="0">Country*</option>
															<?php if(!empty($country)) {
															foreach($country as $solo_country) {
																echo '<option value="'.$solo_country['country_id'].'">'.ucfirst(strtolower($solo_country['country_name'])).'</option>';
															} } ?>
														</select>
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											<?php if($type == 1){ ?> 
											<div class="col-md-6">												
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control dis_signup_input" name="phone" placeholder="Reference phone number" data-type="number" maxlength="12">
														<div class="login_tooltip">
															<i class="fa fa-question-circle " aria-hidden="true"></i>
														</div>
														<span class="login_cstm_pop">Manager, agent, legal representative or entertainment company contact phone number</span>
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											<?php } ?>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control validate dis_signup_input" name="state">
															<option value="0">State*</option>
														</select>
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											
											<?php if($type == 1){ ?> 
											<div class="col-md-6">	
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control dis_signup_input email" name="email" placeholder="Reference email"  >
														<div class="login_tooltip">
															<i class="fa fa-question-circle " aria-hidden="true"></i>
														</div>
														<span class="login_cstm_pop">Manager, agent, legal representative or entertainment company contact email address</span>
													</div>
													<span class="form-error help_error"></span>
												</div>										
											</div>
											<?php } ?>
											
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control validate dis_signup_input" name="city" placeholder="City*">
														
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<input type="text" class="form-control validate dis_signup_input" name="uc_zipcode" placeholder="Zipcode*" data-type="alphaNum">
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											
											
											
											
											<?php if($type == 3){ ?>
											<div class="col-md-6">
												<div class="form-group">
													<div class="input-group">
														<select class="form-control validate dis_signup_input" name="user_cate" placeholder="Level">
															<option value="">Level*</option>
															<?php foreach($level as $val){
																echo '<option value="'.$val['level_id'].'"> '.$val['level_name'].' </option>';
															} ?>
														</select>													
													</div>
													<span class="form-error help_error"></span>
												</div>
											</div>
											<?php } ?>
											<div class="col-md-12">
												<div class="dis_button_div">
													<div class="dis_checkbox checkbox">
														<label>
															<input type="checkbox" value="1"  class="validate" id="check_tnc">
															<i class="input-helper"></i>
															<p>I agree to the Discovered <a target="_blank" href="<?= base_url('terms-and-privacy'); ?>">Terms & Conditions</a>.  I am signing up.</p>
														</label>
														 <span class="form-error help_error check_error"></span>
													</div>											
													
													<button type="button" class="dis_btn" onclick="validate_form(this)" data-form="icon_form">Submit</button> 
													<span class="artistinfo_or">OR</span>
													<a href="<?= base_url('account_type'); ?>" class="dis_change_type">Change account type</a>
												</div>
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

</div>	