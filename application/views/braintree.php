<div class="braintree_card d-none">
    
    <a href="javascript:void(0);" class="bt-btn ms_btn"><i class="fa fa-credit-card"></i> Pay via braintree</a>
        <div class="braintree">
            <form method="POST" id="bt-form" action="<?php echo base_url('BraintreeInt/successBraintree');?>">
                <input type="hidden" name="amount" class="payableAmount" value="100" /> 
                <input type="hidden" name="plan_id" value="1" /> 
                <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="100">
                <input type="hidden" name="taxPercent" class="taxPercent" value="0">
                <input type="hidden" name="taxApplied" class="taxApplied" value="0">
                <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                <div class="bt-drop-in-wrapper">
                    <div id="bt-dropin"></div>
                </div>
                <input id="nonce" name="payment_method_nonce" type="hidden" />
                <button class="payment-final-bt ms_btn d-none" type="submit"> pay now</button>
                <div id="pay-errors" role="alert"></div>
            </form>
        </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>

<script>
    var client_token = null;   
    $(function(){
        $('.bt-btn').on('click', function(){
            $(".ms_ajax_loader").show();
            $('.bt-btn').addClass('load');
            $.ajax({
                type: "GET",
                url: 'BraintreeInt/accesstoken', 
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
        });
    });

    function btform(token){
        var payform = document.querySelector('#bt-form');  
        braintree.dropin.create({
            authorization: token,
            selector: '#bt-dropin',  
            paypal: {
                flow: 'vault'
            },
        }, function (createErr, instance) {
            if (createErr) {
                console.log('Create Error', createErr);
                
                $('.preL').fadeOut('fast');
                $(".ms_ajax_loader").hide();
                $('.preloader3').fadeOut('fast');
                return false;
            }
            else{
                $('.bt-btn').hide();
                $('.payment-final-bt').removeClass('d-none');
            }
            payform.addEventListener('submit', function (event) {
                event.preventDefault();
                instance.requestPaymentMethod(function (err, payload) {
                if (err) {
                    console.log('Request Payment Method Error', err);
                    swal({
                        title: "Oops ! ",
                        text: 'Payment Error please try again later !',
                        icon: 'warning'
                    });
                    $('.preL').fadeOut('fast');
                    $(".ms_ajax_loader").hide();
                    $('.preloader3').fadeOut('fast');
                    return false;
                }
                
                document.querySelector('#nonce').value = payload.nonce;
                payform.submit();
                });
            });
        });
    }
</script>