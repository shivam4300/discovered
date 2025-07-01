<div class="dis_signup_wrapper">

	<div class="container-fluid">

		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<div class="dis_signup_div">

					<div class="dis_signup_inner">

						<div class="dis_signup_left">

							<div class="dis_signup_img">

								<img src="<?php echo base_url().'repo/images/signup_img.jpg';?>" class="img-responsive" alt="">

								<div class="dis_overlay">

									<img src="<?php echo base_url().'repo/images/signup_img_logo.png';?>" class="img-responsive" alt="">

								</div>

							</div>

						</div>

						<div class="dis_signup_right">

							<div class="dis_signup_right_inner dis_pritype_account">

								<div class="au_heading">

									<h2>Choose <?= (isset($category[0]['category_name']))?$category[0]['category_name']:''; ?> type(s)</h2>

								</div>
								<div class="dis_choose_type">
								<form method="POST" action="<?= base_url('home/add_primary_type'); ?>" id="primary_form">
								<div class=" dis_account_type dis_primary">
									<div class="row">
										<div class="col-md-12">
											<select class="form-control primay_select"  name="primary_type[]" multiple="multiple" data-placeholder="Search Type" data-target="select2"  data-option="{closeOnSelect:true}" >
											<option></option>
											<?php if(isset($artist_category)){
													if(!empty($artist_category)){
														foreach($artist_category as $artist){
															echo '<option value="'.$artist['category_id'].'">'.$artist['category_name'].'</option>';
														}
													} 
												
												} ?>
											</select>
										</div>
										<span class="help_error" id="check_box"></span>
										<div class="col-md-12">
											<button type="button" onclick="validate_form(this)" data-form="primary_form" class="dis_btn green_btn">Next</button>
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