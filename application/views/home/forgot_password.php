<?php if($key == '0') { ?>
<!-- forgot password -->
	<div class="au_forget_password">
		<div class="container">
			<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">
				<div class="au_form_section text-center">
					<div class="au_box_login">
						<div class="au_box_form">
							<div class="au_heading">
								<h2>Password Recovery</h2>
								<p>Enter the email address associated with your account, and we’ll email you a link to reset your password.</p>
							</div>
							<form action="" method="post" id="forgot_form">
							  <div class="form-group text-left">
								<input type="text" class="form-control validate email" name="forgot_email" placeholder="Enter Your E-mail">
								<span class="form-error help-block"></span>
							  </div>
							  <div class="form-group text-left">
									<div id="forgot-recaptcha" class="dis_recaptcha_wrap"></div>
                              </div>
                                        
							  <button type="button" class="dis_btn" onclick="validate_form(this)" data-form="forgot_form" >Send Reset Link</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	
	<!-- reset password -->
	<div class="au_forget_password">
		<div class="container">
			<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">
				<div class="au_form_section text-center">
					
					<div class="au_box_login">
						<div class="au_box_form">
							<div class="au_heading">
								<h2>Set New Password</h2>
							</div>
							<form action="" method="post" id="reset_form">
							  <div class="form-group text-left position-realtive">
							  	<span toggle="#user_pwd" class="dis_eyeIcon fa fa-eye dis-toggle-password" aria-hidden="true"></span>
								<input type="password" id="user_pwd" class="form-control validate pwd" name="pwd" placeholder="New Password">
								<span class="form-error help-block" id="user_pwd_error"></span>
							  </div>
							  
							  <div class="form-group text-left position-realtive">
							  	<span toggle="#eye_forgotconfirm" class="dis_eyeIcon fa fa-eye dis-toggle-password" aria-hidden="true"></span>
								<input id="eye_forgotconfirm" type="password" class="form-control validate repwd eye_forgotconfirm" placeholder="Confirm Password">
								<span class="form-error help-block"></span>
							  </div>
							  <button type="button" class="dis_btn" onclick="validate_form(this)" data-form="reset_form" >Reset Password</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } ?>
