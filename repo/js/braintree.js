 var client_token = null;   
$(document).ready(function(){
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
			  currency: storeCurrency,
			  intent: 'capture'
			}, function () {
				
			  $('#loader').remove(); // this will remove the loader
			  paypal.Buttons({
				/*env: 'sandbox', 
				commit: true,
				style: {
					layout: 'horizontal',
					color:  'gold',
					shape:  'rect',
					label:  'paypal',
				  }, */
				 // env: 'sandbox', // or 'production'
				fundingSource: paypal.FUNDING.PAYPAL,

				createOrder: function () {
				  return paypalCheckoutInstance.createPayment({
					flow: 'checkout', // Required
					amount: orderTotalAmt, // Required
					currency: storeCurrency, // Required, must match the currency passed in with loadPayPalSDK

					intent: 'capture', // Must match the intent passed in with loadPayPalSDK

					enableShippingAddress: false,
					shippingAddressEditable: false,
					shippingAddressOverride: shippingAddressObj, /*{
					  recipientName: 'Scruff McGruff',
					  line1: '1234 Main St.',
					  line2: 'Unit 1',
					  city: 'Chicago',
					  countryCode: 'US',
					  postalCode: '60652',
					  state: 'IL',
					  phone: '123.456.7890'
					}*/
				  });
				},

				onApprove: function (data, actions) {
					$('#payment_form').addClass('hideme'); 
					$('#payment_processing_popup').removeClass('hideme');
				  return paypalCheckoutInstance.tokenizePayment(data, function (err, payload) {
					console.log(payload);
						$.ajax({
							type: "POST",
							url: base_url+'BraintreeInt/successBraintree', 
							data : payload,
							success: function(resp) {   
								if(resp['status'] == 1){
									Custom_notify('success',resp['message']);
									$('#payment_processing_popup').addClass('hideme');
									$('#payment_success_popup').removeClass('hideme');
								}else{
									Custom_notify('error',resp['message']);
									$('#payment_failed_popup').removeClass('hideme');
									$('#payment_processing_popup').addClass('hideme');
									$('#payment_form').addClass('hideme');
								}
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								console.log(XMLHttpRequest);
								Custom_notify('error',resp['message']);
								alert('Payment error. Please try again later.');
							}
						});
					
					
					
				  });
				},

				onCancel: function (data) {
				  console.log('PayPal payment cancelled', JSON.stringify(data, 0, 2));
				},

				onError: function (err) {
				  console.log('PayPal error', err.message);
				   Custom_notify('error',err.message);
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