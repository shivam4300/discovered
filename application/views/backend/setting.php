	<script>
	var CountToClone= 0;
	</script>
	<?php
	// echo '<pre>';
	// print_r($usersDetail[0]);die;
	$user_name 		= isset($usersDetail[0])?$usersDetail[0]['user_name'] 	: $usersBillingDetail[0]['billing_name'];
	$user_phone 	= isset($usersDetail[0])?$usersDetail[0]['user_phone'] 	: $usersBillingDetail[0]['billing_contact'];
	
	$user_address 	= isset($usersDetail[0])?$usersDetail[0]['user_address'] : $usersBillingDetail[0]['first_address'];
	$country 		= isset($usersDetail[0])?$usersDetail[0]['uc_country'] 	: $usersBillingDetail[0]['country'];
	$state 			= isset($usersDetail[0])?$usersDetail[0]['uc_state'] 	: $usersBillingDetail[0]['state'];
	$city 			= isset($usersDetail[0])?$usersDetail[0]['uc_city'] 	: $usersBillingDetail[0]['city'];
	// $zip_code 		= isset($usersBillingDetail[0])?$usersBillingDetail[0]['zip_code']: '';
	$zip_code 		= isset($usersDetail[0])?$usersDetail[0]['uc_zipcode']: $usersBillingDetail[0]['zip_code'];
	
	
	// $billing_email 				= isset($usersBillingDetail[0]['billing_email']) ? json_decode($usersBillingDetail[0]['billing_email']): [];
	$tax_entity_type 			= isset($usersBillingDetail[0])?$usersBillingDetail[0]['tax_entity_type']: '';
	$tax_entity_other 			= isset($usersBillingDetail[0])?$usersBillingDetail[0]['tax_entity_other']: '';
	$tax_entity_id 				= isset($usersBillingDetail[0])?$usersBillingDetail[0]['tax_entity_id']: '';
	$second_address 			= isset($usersBillingDetail[0])?$usersBillingDetail[0]['second_address']: '';
	
	
	
	$payment_method_type 		= isset($usersBillingDetail[0])?$usersBillingDetail[0]['payment_method_type']: '';
	$bank_name 					= isset($usersBillingDetail[0])?$usersBillingDetail[0]['bank_name']: '';
	$bank_acc_number 			= isset($usersBillingDetail[0])?$usersBillingDetail[0]['bank_acc_number']: '';
	$routing_number 			= isset($usersBillingDetail[0])?$usersBillingDetail[0]['routing_number']: '';
	$swift_code 				= isset($usersBillingDetail[0])?$usersBillingDetail[0]['swift_code']: '';
	$paypal_id 					= isset($usersBillingDetail[0])?$usersBillingDetail[0]['paypal_id']: '';
	
	
	$user_secondary_email 		= '';
	
	if(isset($usersDetail[0]['user_email'])){
		$user_primary_email = $usersDetail[0]['user_email'];
	}else{
		if(!empty($usersBillingDetail[0]['billing_email_list'])){
			$billing_email_list = json_decode($usersBillingDetail[0]['billing_email_list']);
			$user_primary_email 	= isset($billing_email_list[0]) ? $billing_email_list[0] : '';
			$user_secondary_email 	= isset($billing_email_list[1]) ? $billing_email_list[1] : '';
		}else{
			$user_primary_email = isset($_SESSION['user_email'])? $_SESSION['user_email'] : '' ;
		}
	}
	?>
	
	
	
	<div class="dash_page_section">
		<!-- payouts  page start -->
			<div class="dash_payout_wrapper">
				<!-- top section start -->
				<div class="row">
					<div class="col-xs-12">
						<div class="dash_ctab_wrapper">
							<ul class="">
								<li class="active"><a data-toggle="tab" href="#billing_info">Billing Information</a></li>
								<li><a data-toggle="tab" href="#pay_method">Payment Methods</a></li>
							</ul>

							<div class="tab-content">
								<div id="billing_info" class="tab-pane fade in active">
								<form action="backend/setting/SaveBillingInfo" method="POST" id="BillingForm">
									<div class="">
										<div class="gi_section">
											<h4 class="gi_title">General Information</h4>
											<div class="gi_inner_box">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Name <span class="theme_color">*</span></label>
														  <input type="text" class="form-control require" placeholder="Enter Billing Name" name="billing_name" value="<?= $user_name; ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Phone <span class="theme_color">*</span></label>
														  <input type="text" class="form-control require" placeholder="415-200-6025" name="billing_contact" value="<?= $user_phone; ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Primary Email <span class="theme_color">*</span></label>
														  <input type="text" class="form-control require" placeholder="Enter Email addresss" name="billing_email_list[]" readonly value="<?= $user_primary_email; ?>" data-valid="email" data-error="Please enter valid email Id.">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Secondary Email</label>
														  <input type="text" class="form-control" placeholder="Enter Email addresss" name="billing_email_list[]"  value="<?= $user_secondary_email; ?>" data-valid="email" data-error="Please enter valid email Id.">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="gi_section">
											<h4 class="gi_title">Tax Information</h4>
											<div class="gi_inner_box">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Tax Entity Classification <span class="theme_color">*</span></label>
														    <select  name="tax_entity_type" data-target="select2"  data-option="{closeOnSelect:true,placeholder:'Select Tax Entity',allowHtml:true,allowClear:true,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require form-control" data-error="Please choose tax entity classification.">
															  <?php 
																 echo '<option value=""></option>';
																 foreach($tax_entities as $t){
																	 $selected = ($tax_entity_type == $t['tax_entity_id'])? 'selected="selected"' : '';
																	 echo '<option '. $selected .' value="'.$t['tax_entity_id'].'">'.$t['tax_entity_name'].'</option>'; 
																 }?>
															</select>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Tax Entity - Other</label>
														  <input type="text" class="form-control " placeholder="Please Specify" name="tax_entity_other" value="<?= $tax_entity_other ; ?>" data-error="Please enter tax entity (other).">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="input_lble">Tax Entity ID 
																<i class="fa fa-question-circle cstm_tooltip" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Enter the identification used for your tax filing. Example: SSN/ EIN/ ITIN/ VAT/ GST etc."></i>
																<span class="theme_color">*</span>
															</label>
														  <input type="text" class="form-control require" placeholder="123-45-6789" name="tax_entity_id" value="<?= $tax_entity_id ; ?>" data-error="Please enter tax entity id.">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Address Line 1 <span class="theme_color">*</span></label>
														  <input type="text" class="form-control require" placeholder="Enter first address line" name="first_address" value="<?= $user_address; ?>" data-error="Please enter first address.">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Address Line 2</label>
														  <input type="text" class="form-control" placeholder="Enter Second Address" name="second_address" value="<?= $second_address ; ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Country <span class="theme_color">*</span></label>
														<select  name="country" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' Select Country',allowHtml:true,width: '100%'}"  class="form-control require SelectBySelect2" data-url="backend/setting/getStateArray" data-id="#state" data-error="Please choose country.">
														 <?php 
														 echo '<option value=""></option>';
														 foreach($countries as $c){
															 $selected='';
															 if($c['country_id'] == $country)
																$selected='selected="selected"'; 
															 echo '<option '.$selected.' value="'.$c['country_id'].'">'.$c['country_name'].'</option>'; 
														 }?>
														</select>  
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">State <span class="theme_color">*</span></label>
															<select class="form-control require" name="state" id="state" data-placeholder="Select State" value="<?= $state; ?>" data-error="Please choose state.">
																
															</select>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">City <span class="theme_color">*</span></label>
														  <input type="text" class="form-control require" placeholder="City" name="city" value="<?= $city; ?>" data-error="Please enter city."> 
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Zip Code <span class="theme_color">*</span></label>
														  <input type="text" class="form-control require" placeholder="1661131" name="zip_code" value="<?= $zip_code ; ?>" data-error="Please enter zip code.">
														</div>
													</div>
													
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="terms_conditon">
															<ul>
																<li>
																	<div class="custm_checkbox ">
																	  <input type="checkbox" id="terms1" class="hide_checkbox CheckStatus require" <?= !empty($tax_entity_type) ? 'checked' : ''; ?> >
																	  <label class="backend_checkbox" for="terms1" ></label>
																	</div>
																	<label class="terms_label">I am a U.S. citizen or other U.S. person per US Tax regulations.</label>
																</li>
																<li>
																	<div class="custm_checkbox ">
																	  <input type="checkbox" id="terms2" class="hide_checkbox CheckStatus require" <?= !empty($tax_entity_type) ? 'checked' : ''; ?> >
																	  <label class="backend_checkbox " for="terms2"></label>
																	</div>
																	<label class="terms_label">The Tax ID number shown on this form is your correct taxpayer identification number.</label>
																</li>
																<li>
																	<div class="custm_checkbox ">
																	  <input type="checkbox" id="terms3" class="hide_checkbox CheckStatus" checked disabled>
																	  <label class="backend_checkbox" for="terms3"></label>
																	</div>
																	<label class="terms_label">I am not subject to backup withholding. </label>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="be_btn_wrapper text-right">
											<a class="backend_btn m-r-30 resetForm" data-id="#BillingForm">reset</a>
											<button class="backend_btn color_green" type="submit" >save</button>
										</div>
									</div>
									
									</form>
									
									
									
									
									
								</div>
								<div id="pay_method" class="tab-pane fade">
									
									<form method="POST" action="backend/setting/SavePaymentInfo" id="PaymentForm">
										<div class="paymet_methos_wrapper">
											
											<div class="gi_section">
												<h4 class="gi_title">Payment Methods</h4>
												<p class="pmnt_pera">Note:Discovered pays directly to your Bank account OR thru Paypal. <b>Enter account details for only one of them</b>. If you choose to change it later, you can	override the account information and/or change the payment method as well. Discovered.tv will pick the latest updated info for the next payment batch.</p>
												<div class="gi_inner_box">
													<div class="row">
													<div class="col-md-12">
														<div class="paymrnt_btn_wrappr ">
															<ul>
																<li  <?= ($payment_method_type == 1)? 'class="active"':'' ?>>
																	<div class="custm_checkbox custm_radio">
																	  <input type="radio" id="ach_checkbox" class="hide_checkbox" name="payment_method_type" value="1" 
																	  <?= ($payment_method_type == 1)? 'checked':'' ?>>
																	  <label class="backend_checkbox" for="ach_checkbox">Bank</label>
																	</div>
																</li>
																<li <?= ($payment_method_type == 2)? 'class="active"':'' ?>>
																	<div class="custm_checkbox custm_radio">
																	  <input type="radio" id="pal_checkbox" class="hide_checkbox" name="payment_method_type" value="2" <?= ($payment_method_type == 2)?'checked':'' ?>>
																	  <label class="backend_checkbox" for="pal_checkbox">PayPal</label>
																	</div>
																	
																</li>
															</ul>
															
														</div>
													</div>
													</div>
													
													<div class="row <?= ($payment_method_type == '' || $payment_method_type == 1)? '':'hide'; ?>" id="ach_checkbox_toggle" >
														<div class="col-md-6">
															<div class="form-group">
															  <label class="input_lble">Bank Name <span class="theme_color">*</span></label>
															  <input type="text" class="form-control require ach" placeholder="Enter Bank Name" name="bank_name" value="<?= $bank_name; ?>">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
															  <label class="input_lble">Bank Account Number <span class="theme_color">*</span></label>
															  <input type="text" class="form-control require ach" placeholder="Enter Bank Account Number" name="bank_acc_number" value="<?= base64_decode($bank_acc_number); ?>">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
															  <label class="input_lble">Routing Number <span class="theme_color">*</span></label>
															  <input type="text" class="form-control require ach" placeholder="Enter Routing Number" name="routing_number" value="<?= base64_decode($routing_number); ?>">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
															  <label class="input_lble">SWIFT Code</label>
															  <input type="text" class="form-control" placeholder="Enter Swift Code" name="swift_code" value="<?= base64_decode($swift_code); ?>">
															</div>
														</div>

													</div>
													
													<div class="row <?= ($payment_method_type == 2)? '':'hide'; ?>" id="pal_checkbox_toggle" >
														<div class="col-md-6">
															<div class="form-group">
															  <label class="input_lble">PayPal ID <span class="theme_color">*</span></label>
															  <input type="text" class="form-control <?= ($payment_method_type == 2)? 'require':''; ?> ppal" placeholder="Enter PayPal Id" name="paypal_id" value="<?= base64_decode($paypal_id); ?>">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="be_btn_wrapper text-right" >
												<a class="backend_btn m-r-30 resetForm" data-id="#PaymentForm">reset</a>
												<button class="backend_btn color_green" type="submit" >save</button>
											</div>
										</div>
									</form>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- top section End -->
				
				
			</div>	
		<!-- payout  page End-->
		
	
	</div>
