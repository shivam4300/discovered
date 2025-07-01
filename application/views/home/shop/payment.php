<?php 

if(isset($_SESSION['orderData'])){ 
$payment_url  = isset($_SESSION['orderData']['payment_url']) ? $_SESSION['orderData']['payment_url'] : '';
$shipping	  = isset($_SESSION['orderData']['shipping']) ? $_SESSION['orderData']['shipping'] : [];
$fname  	  = isset($shipping['first_name'])? $shipping['first_name']: '';
$lname 		  = isset($shipping['last_name'])? $shipping['last_name']: '';
$uemail 	  = isset($shipping['email'])? $shippingt['email']: '';
$uphone 	  = isset($shipping['phone'])? $shipping['phone']: '';
$country 	  = isset($shipping['country'])? $shipping['country']: '';
$state 		  = isset($shipping['state'])?$shipping['state'] 	: '';
$city 		  = isset($shipping['city'])? $shipping['city']: '';
$postcode 	  = isset($shipping['postcode'])? $shipping['postcode']: '';
$address      = isset($shipping['address_1'])? $shipping['address_1']: '';	
$currency 	  = isset($_SESSION['orderData']['currency'])? $_SESSION['orderData']['currency']: '';	
$cart_total   = $this->cart->total();
?>

<script>
var orderTotalAmt = '<?=$cart_total;?>';
var storeCurrency  = '<?=$currency;?>';
var shippingAddressObj = {
								  recipientName: '<?=$fname.' '.$lname;?>',
								  line1: '<?=$address;?>',
								  line2: '<?=$address;?>',
								  city: '<?=$city;?>',
								  countryCode: '<?=$country;?>',
								  postalCode: '<?=$postcode;?>',
								  state: '<?=$state;?>',
								  phone: '<?=$uphone;?>',
							} 
</script>




<div class="dis_paymentWrap muli_font" id="payment_form">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="dis_paymentInner">
                    <div class="dis_p_header">
                        <h2 class="dis_sp_tpL_ttl mp_0">Payment</h2>
                    </div>
                    <div class="dis_p_body">
                        <div class="dis_borderBox">
                            <div class="dis_borderBox_header">   
                                <div>
                                    <div class="dis_Scheckbox">
                                        <span class="dis_Scheckbox_text">Shipping Details</span>
                                    </div>
                                </div>
                            </div>    
                            <div class="dis_borderBox_body">  
                                <ul class="dis_cart_totalList dis_shipp_adrs">
                                    <li>
										<p>Name</p>
										<p>: <?=$fname.' '.$lname;?></p>
										</li>
										<li>
										<p>Address</p>
										<p>: <?=$address;?></p>
										</li>
										<li>
										<p>Phone</p>
										<p>: <?=$uphone;?></p>
										</li>
										<!--li>
										<p>Email</p>
										<p>: <?=$uemail;?></p>
										</li--->
										<li>
										<p>Country</p>
										<p>: <?=$country;?></p>
										</li>
										<li>
										<p>State</p>
										<p>: <?=$state;?></p>
										</li><li>
										<p>City</p>
										<p>: <?=$city;?></p>
									</li>
									<li>
										<p>ZIP Code</p>
										<p>: <?=$postcode;?></p>
									</li>
                                </ul> 
                            </div>    
                        </div> 
                        <div class="dis_borderBox">
                            <div class="dis_borderBox_header">   
                                <div>
                                    <div class="dis_Scheckbox">
                                        <span class="dis_Scheckbox_text">Choose Payment Option</span>
                                    </div>
                                </div>
                            </div>    
                            <div class="dis_borderBox_body">  
                                <div class="dis_p_paymentRAdio">
                                    <div class="row">
                                        <div class="col-xl-12">
											<div>
												<div id="paypal-button">
                                                <div id="loader">
                                                    <div class="dis_skeleton">
                                                        <!-- <div class="dis_skeleton_left">
                                                            <div class="dis_skeletonCircle"></div>
                                                        </div> -->
                                                        <div class="dis_skeleton_right">
                                                            <div class="dis_skeleton_line"></div>
                                                            <div class="dis_skeleton_line"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
											</div>
                                            <!--div class="dis_p_payradio">
                                                <input id="paypal" type="radio" name="optradio" checked>  
                                                <label for="paypal" class="">  
                                                    <img src="<?= base_url('repo/images/products/store_pay.png'); ?>" />                                         
                                                </label>
                                            </div-->
                                        </div>
                                        <!--div class="col-sm-6">
                                        <div class="dis_p_payradio">
                                                <input id="stripe" type="radio" name="optradio">  
                                                <label for="stripe" class="">  
                                                    <img src="<?= base_url('repo/images/products/store_stripe.png'); ?>" />                                         
                                                </label>
                                            </div>
                                        </div-->
                                    </div>
                                    
                                </div>
                                <div class="dis_cartFooter p_t_30">
                                    <div class="dis_cartFooterL">
                                        <a href="#" class="dis_linkbtn"> <span class="dis_sp_left"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(247, 101, 45)" d="M2.334,4.670 C2.562,4.670 2.688,4.670 2.814,4.670 C6.586,4.670 10.357,4.675 14.129,4.661 C14.362,4.660 14.655,4.582 14.816,4.432 C15.183,4.91 14.963,3.474 14.465,3.360 C14.323,3.328 14.170,3.327 14.23,3.326 C10.283,3.325 6.543,3.325 2.803,3.325 C2.678,3.325 2.554,3.325 2.363,3.325 C2.470,3.207 2.532,3.131 2.602,3.63 C3.233,2.445 3.866,1.829 4.496,1.210 C4.835,0.878 4.864,0.474 4.577,0.191 C4.294,0.88 3.873,0.63 3.536,0.265 C2.445,1.330 1.356,2.397 0.270,3.466 C0.88,3.820 0.88,4.173 0.271,4.528 C1.356,5.598 2.445,6.664 3.535,7.729 C3.883,8.69 4.271,8.88 4.569,7.794 C4.869,7.498 4.845,7.127 4.494,6.782 C3.799,6.99 3.100,5.419 2.334,4.670 Z"></path></svg></span> Review Your Order</a>
                                    </div>
                                    <!--div class="dis_cartFooterR">
                                        <ul class="dis_product_couponList">
                                            <li>
                                                <a href="<?=$payment_url; ?>" class="dis_OrangeBtn">Place Your Order</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div-->
                                </div>
                            </div>    
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<div class="dis_S_modal dis_S_success muli_font hideme" id="payment_processing_popup">
    <div class="dis_S_modal_inner">
        <span class="dis_SM_icon">
           <?php echo $this->common_html->content_loader_html(); ?>  
        </span>
        <h2 class="dis_SM_ttl m_b_20">Please wait till the payment is processed...</h2>
        <p class="dis_SM_des m_b_30"> Please do not refresh page!<br>Or close browser.</p>
        <div>
        <!--a href="#" class="dis_OrangeBtn">Track Your Order</a-->
        </div>
    </div>
</div>

<div class="dis_S_modal dis_S_success muli_font hideme" id="payment_success_popup">
    <div class="dis_S_modal_inner">
        <span class="dis_SM_icon">
            <img src="<?= base_url('repo/images/products/store_sucess.png'); ?>" />    
        </span>
        <h2 class="dis_SM_ttl m_b_20">Payment Successful</h2>
        <p class="dis_SM_des m_b_30"> Your order is placed successfully!<br>You will be receiving a confirmation email shortly with order details.</p>
        <div>
        <a href="<?=base_url('store/orders');?>" class="dis_OrangeBtn">Track Your Order</a>
        </div>
    </div>
</div>
<div class="dis_S_modal dis_S_failed muli_font hideme"id="payment_failed_popup">
    <div class="dis_S_modal_inner">
        <span class="dis_SM_icon">
            <img src="<?= base_url('repo/images/products/store_failed.png'); ?>" />    
        </span>
        <h2 class="dis_SM_ttl m_b_20">Payment Failed</h2>
        <p class="dis_SM_des m_b_30"> Something went wrong while placing your order.<br> Please try again after some time or contact us at <a href="mailto:help@discovered.tv" class="primary_link">help@discovered.tv</a>  <br>with your Transaction ID <a href="#" class="dis_SM_id"> #dtv78134</a></p>
        <div>
        <!--a href="#" class="dis_OrangeBtn">Go Back To Payments</a-->
        </div>
    </div>
</div>

<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script-->
<!--script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script-->
<!-- Load the client component. -->
<!--script src="https://js.braintreegateway.com/web/3.85.3/js/client.min.js"></script-->

<!-- Load the PayPal Checkout component. -->
<!--script src="https://js.braintreegateway.com/web/3.85.3/js/paypal-checkout.min.js"></script-->

<!--script>
    var client_token = null;   
    $(function(){
        //$('.bt-btn').on('click', function(){
            $(".ms_ajax_loader").show();
            $('.bt-btn').addClass('load');
            $.ajax({
                type: "GET",
                url: base_url+'BraintreeInt/accesstoken', 
                success: function(t) {   
                    t = JSON.parse(t);
                    console.log(t)
                    console.log(t.client)
                    $(".ms_ajax_loader").hide();
                    if(t.client != null){
                        client_token = t.client;
                        btform(client_token);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $(".ms_ajax_loader").hide();
                    console.log(XMLHttpRequest);
                    $('.bt-btn').removeClass('load');
                    alert('Payment error. Please try again later.');
                }
            });
        //});
    });
	
 function btform(client_token){	
	// Create a client.
		braintree.client.create({
		  authorization: client_token
		}, function (clientErr, clientInstance) {

		  // Stop if there was a problem creating the client.
		  // This could happen if there is a network error or if the authorization
		  // is invalid.
		  if (clientErr) {
			console.error('Error creating client:', clientErr);
			return;
		  }

		  // Create a PayPal Checkout component.
		  braintree.paypalCheckout.create({
			client: clientInstance
		  }, function (paypalCheckoutErr, paypalCheckoutInstance) {
			paypalCheckoutInstance.loadPayPalSDK({
			  currency: 'USD',
			  intent: 'capture'
			}, function () {
			  paypal.Buttons({
				fundingSource: paypal.FUNDING.PAYPAL,

				createOrder: function () {
				  return paypalCheckoutInstance.createPayment({
					flow: 'checkout', // Required
					amount: 10.00, // Required
					currency: 'USD', // Required, must match the currency passed in with loadPayPalSDK

					intent: 'capture', // Must match the intent passed in with loadPayPalSDK

					/* enableShippingAddress: false,
					shippingAddressEditable: false,
					shippingAddressOverride: {
					  recipientName: 'Scruff McGruff',
					  line1: '1234 Main St.',
					  line2: 'Unit 1',
					  city: 'Chicago',
					  countryCode: 'US',
					  postalCode: '60652',
					  state: 'IL',
					  phone: '123.456.7890'
					} */
				  });
				},

				onApprove: function (data, actions) {
					
				  return paypalCheckoutInstance.tokenizePayment(data, function (err, payload) {
					// Submit `payload.nonce` to your server
					console.log(payload);
						$.ajax({
							type: "POST",
							url: base_url+'BraintreeInt/successBraintree', 
							data : payload,
							success: function(t) {   
								t = JSON.parse(t);
								console.log(t)
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								$(".ms_ajax_loader").hide();
								console.log(XMLHttpRequest);
								$('.bt-btn').removeClass('load');
								alert('Payment error. Please try again later.');
							}
						});
					
					
					
				  });
				},

				onCancel: function (data) {
				  console.log('PayPal payment cancelled', JSON.stringify(data, 0, 2));
				},

				onError: function (err) {
				  console.error('PayPal error', err);
				}
			  }).render('#paypal-button').then(function () {
				// The PayPal button will be rendered in an html element with the ID
				// `paypal-button`. This function will be called when the PayPal button
				// is set up and ready to be used
				//alert();
			  });

			});

		  });

		});
 }

</script-->
