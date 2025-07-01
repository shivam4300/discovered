<?php
$billing 	= [];
$shipping 	= [];
$cust 		= [];
if(!empty($customer)){ 
	$cust 	  = $customer[0];
	$billing  = $cust['billing'];
	$shipping = $cust['shipping'];
}

$fname  	  = isset($cust['first_name'])? $cust['first_name']: '';
$lname 		  = isset($cust['last_name'])? $cust['last_name']: '';
$uemail 	  = isset($cust['email'])? $cust['email']: '';
$uphone 	  = isset($shipping['phone'])? $shipping['phone']: '';
$country 	  = isset($shipping['country'])? $shipping['country']: '';
$state 		  = isset($shipping['state'])?$shipping['state'] 	: '';
$state_name   = isset($state_name[0])?$state_name[0]['name'] 	: '';
$city 		  = isset($shipping['city'])? $shipping['city']: '';
$address      = isset($shipping['address_1'])? $shipping['address_1']: '';

$b_fname  	  = isset($billing['first_name'])? $billing['first_name']: '';
$b_lname 	  = isset($billing['last_name'])? $billing['last_name']: '';
$b_email 	  = isset($billing['email'])? $billing['email']: '';
$b_phone 	  = isset($billing['phone'])? $billing['phone']: '';
$b_country 	  = isset($billing['country'])? $billing['country']: '';
$b_state 	  = isset($billing['state'])?$billing['state'] 	: '';
$b_city 	  = isset($billing['city'])? $billing['city']: '';
$b_address    = isset($billing['address_1'])? $billing['address_1']: '';
?>

<?php 
$country_name = '';
$countryList = '';
if(!empty($countries)){
	foreach($countries as $c){ 
		$selected = '';
		if(!empty($country) && $c['country_id'] == $country){
			$selected = 'selected';
			$country_name = $c['country_name'];
		}
		$countryList .= '<option value="'.$c['country_id'].'" '.$selected.'>'.$c['country_name'].'</option>';
	}
}
?>




<div class="dis_shippingWrap muli_font">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="dis_shippingInner">
                    <div class="dis_p_header">
                        <h2 class="dis_sp_tpL_ttl mp_0">Shipping Details</h2>
                    </div>
                    <div class="dis_p_body">
						<form method="post" action="store/addShippingAddress" id="add_shipping_address">
							<?php if(!empty($b_fname)){  
									
							?>
							<div class="dis_borderBox m_b_30">
								<div class="dis_borderBox_header">   
									<div>
										<div class="dis_Scheckbox">
											<input type="checkbox" id="pcb1" name="is_same_billing_add" value="1">
											<label for="pcb1"></label>
											<span class="dis_Scheckbox_text">Use This Address & Contact Information To Ship This Order</span>
										</div>
									</div>
								</div>    
								<div class="dis_borderBox_body">  
									<ul class="dis_cart_totalList dis_shipp_adrs">
										<li>
										<p>Name</p>
										<p>: <?=$b_fname.' '.$b_lname;?></p>
										</li>
										<li>
										<p>Address</p>
										<p>: <?=$b_address;?></p>
										</li>
										<li>
										<p>Phone</p>
										<p>: <?=$b_phone;?></p>
										</li>
										<li>
										<p>Email</p>
										<p>: <?=$b_email;?></p>
										</li>
										<li>
										<p>Country</p>
										<p>: <?=$country_name;?></p>
										</li>
										<li>
										<p>State</p>
										<p>: <?=$state_name;?></p>
										</li><li>
										<p>City</p>
										<p>: <?=$b_city;?></p>
										</li>
									</ul> 
								</div>    
							</div> 
							<?php } ?>
							<div class="dis_borderBox">
								<div class="dis_borderBox_header">   
									<div>
										<div class="dis_Scheckbox">
											<input type="checkbox" id="pcb2" name="confirm" value="1">
											<label for="pcb2"></label>
											<span class="dis_Scheckbox_text">Add New Address & Contact Information</span>
										</div>
									</div>
								</div>    
								<div class="dis_borderBox_body">  
									<div class="dis_shipping_filed">
										<div class="row">
											<div class="col-md-6">
												<div class="m_b_30">
													<div class="dis_field_box">
														<label class="dis_field_label">First Name*</label>
														<div class="dis_field_wrap">
															<input type="text"  name="ship_fname" class="dis_field_input require" placeholder="Enter First Name Here" value="<?=$fname;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="m_b_30">
													<div class="dis_field_box">
														<label class="dis_field_label">Last Name*</label>
														<div class="dis_field_wrap">
															<input type="text" name="ship_lname" class="dis_field_input require" placeholder="Enter Last Name Here" value="<?=$lname;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="m_b_30">
													<div class="dis_field_box">
														<label class="dis_field_label">Phone*</label>
														<div class="dis_field_wrap">
															<input type="text" name="ship_phone" class="dis_field_input require" data-valid="mobile" data-error="Please enter valid mobile no." placeholder="Enter Phone Here" value="<?=$uphone;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="m_b_30">
													<div class="dis_field_box">
														<label class="dis_field_label">Email*</label>
														<div class="dis_field_wrap">
															<input type="text" name="ship_email" class="dis_field_input require" data-valid="email" data-error="Please enter valid email address." placeholder="Enter Email Here" value="<?=$uemail;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="m_b_30">
													<div class="dis_field_box">
														<label class="dis_field_label">Country / Region*</label>
														<div class="dis_field_wrap dis_select2">
															<select class="dis_field_input SelectBySelect2 require" name="ship_country" data-target="select2" data-option="{minimumResultsForSearch:-1, placeholder:' Select Country', width:'100%'}"  data-url="settings/getStateArray" data-id="#state">
																<option value="">Select Country</option>
																<?=$countryList;?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="m_b_30">
													<div class="dis_field_box">
														<label class="dis_field_label">State*</label>
														<div class="dis_field_wrap dis_select2">
															<select class="dis_field_input require" name="ship_state" data-target="select2" data-option="{minimumResultsForSearch:-1, placeholder:' Select State', width:'100%'}" id="state" data-placeholder="Select State">
																<?php 
																 echo '<option value="'.$state.'">'.$state_name.'</option>';
																 ?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="m_b_30">
													<div class="dis_field_box">
														<label class="dis_field_label">City*</label>
														<div class="dis_field_wrap">
															<input type="text" name="ship_city" class="dis_field_input require" data-error="Please enter city." placeholder="Enter City Here" value="<?=!empty($shipping['city'])? $shipping['city']: ''?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="m_b_30">
													<div class="dis_field_box">
														<label class="dis_field_label">ZIP Code*</label>
														<div class="dis_field_wrap">
															<input type="text" name="ship_zipcode" class="dis_field_input require" data-valid="number" data-error="Please enter valid zipcode." placeholder="Enter Zip Code Here" value="<?=!empty($shipping['postcode'])? $shipping['postcode']: ''?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div>
													<div class="dis_field_box">
														<label class="dis_field_label">Street Address*</label>
														<div class="dis_field_wrap">
															<textarea class="dis_field_input require" name="ship_address" placeholder="Enter House Number / Street Address"><?=$address;?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="dis_cartFooter p_t_30">
										<div class="dis_cartFooterL">
											<a href="<?=base_url('store/cart');?>" class="dis_linkbtn"> <span class="dis_sp_left"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(247, 101, 45)" d="M2.334,4.670 C2.562,4.670 2.688,4.670 2.814,4.670 C6.586,4.670 10.357,4.675 14.129,4.661 C14.362,4.660 14.655,4.582 14.816,4.432 C15.183,4.91 14.963,3.474 14.465,3.360 C14.323,3.328 14.170,3.327 14.23,3.326 C10.283,3.325 6.543,3.325 2.803,3.325 C2.678,3.325 2.554,3.325 2.363,3.325 C2.470,3.207 2.532,3.131 2.602,3.63 C3.233,2.445 3.866,1.829 4.496,1.210 C4.835,0.878 4.864,0.474 4.577,0.191 C4.294,0.88 3.873,0.63 3.536,0.265 C2.445,1.330 1.356,2.397 0.270,3.466 C0.88,3.820 0.88,4.173 0.271,4.528 C1.356,5.598 2.445,6.664 3.535,7.729 C3.883,8.69 4.271,8.88 4.569,7.794 C4.869,7.498 4.845,7.127 4.494,6.782 C3.799,6.99 3.100,5.419 2.334,4.670 Z"></path></svg></span> Back To Shopping</a>
										</div>
										<div class="dis_cartFooterR">
											<ul class="dis_product_couponList">
												<li>
													<input type="hidden" name="user_id" value="<?=!empty($cust['id'])? $cust['id'] : ''?>">
													<button type="submit" class="dis_OrangeBtn" id="checkout_btn">Proceed To Payments
														<span class="dis_sp_right"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.311,4.671 12.185,4.671 C8.413,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.091 0.036,3.475 0.534,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.325 8.456,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.207 12.467,3.132 12.397,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.089 11.127,-0.063 11.464,0.266 C12.554,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.173 14.729,4.528 C13.643,5.598 12.554,6.665 11.464,7.730 C11.116,8.069 10.728,8.089 10.430,7.795 C10.130,7.499 10.154,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"></path></svg></span>
													</button>
												</li>
											</ul>
										</div>
									</div>
								</div>    
							</div> 
						</form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dis_product_totalBox">
                    <div class="dis_p_header">
                        <div class="dis_p_headerL">
                            <h2 class="dis_sp_tpL_ttl mp_0">Review Your Order</h2>
                        </div>
                        <div class="dis_p_headerR">
                            <a href="<?=base_url('store/cart');?>" class="primary_link">Edit Order</a>
                        </div>
                    </div>
                    <div class="dis_p_body">
                        <div class="dis_cart_T">
                            <ul class="dis_cart_totalList BB" id="shipping_page_cart">
                                <!--li>
                                    <p class="dis_ctl_ttl">Diet Coke Logo Crewneck Tees <span>Quantity - 01</span> </p>
                                    <p class="dis_ctl_ttl">$29.95</p>
                                </li>
                                <li>
                                    <p class="dis_ctl_ttl">Diet Coke Logo Crewneck Tees <span>Quantity - 01</span> </p>
                                    <p class="dis_ctl_ttl">$59.95</p>
                                </li>
                                <li>
                                    <p class="dis_ctl_ttl">Diet Coke Logo Crewneck Tees <span>Quantity - 01</span> </p>
                                    <p class="dis_ctl_ttl">$59.95</p>
                                </li>
                                <li>
                                    <p class="dis_ctl_ttl">Diet Coke Logo Crewneck Tees <span>Quantity - 01</span> </p>
                                    <p class="dis_ctl_ttl">$29.95</p>
                                </li-->
                            </ul>
                            <ul class="dis_cart_totalList BB" id="ship_page_subtotal">
                                <!--li>
                                <p>Sub-Total</p>
                                <p>$119.80</p>
                                </li>
                                <li>
                                <p>Taxes & Charges</p>
                                <p>+  $8.99</p>
                                </li>
                                <li>
                                <p>Shipping Charges</p>
                                <p>+  $2.99</p>
                                </li>
                                <li>
                                <p>Discounts & Offers</p>
                                <p>-  $2.99</p>
                                </li-->
                            </ul>
                        </div>
                        <div class="dis_cart_F">
                            <ul class="dis_cart_totalList" id="ship_page_total">
                                <!--li>
                                <p>Total</p>
                                <p>$188.69</p>
                                </li-->
                            </ul>
                            <!--span class="dis_OrangeBtn dis_GreenBtn  br3 w-100">Your Total Savings : $41</span-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>