
var sigup_acc_type 	= 'standard',
	path 			= window.location.pathname, 
	page 			= path.split("/").pop(),
	giveawayMess  	=  'You have participated in the giveaway successfully';
	
const urlParams = new URLSearchParams(window.location.search);
const autopopup = urlParams.get('popup');
if(autopopup == 'login'){
	$('[data-cls="login_mdl"]').eq(1).click();
}

if(page == 'giveaways'){
	sigup_acc_type = 'express';
}

$(function() {
	
	$(document).on('click','.chooseAccType',function(){
		// sigup_acc_type = $(this).data('acctype');
		$('.dis_signup_grid_wrap').addClass('hide');
		$('.dis_signup_wrapper').removeClass('hide');
		$('#signup_details').modal('hide');
	})
	
	// if(typeof FB != 'undefined'){
	function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
		console.log('statusChangeCallback');
		console.log(response);                   // The current login status of the person.
		if(response.status === 'connected') {   // Logged into your webpage and Facebook.
			testAPI();  
		}else {                                 // Not logged into your webpage or we are unable to tell.
		// document.getElementById('status').innerHTML = 'Please log ' + 'into this webpage.';
		}
	}

	function checkLoginState(){              // Called when a person is finished with the Login Button.
		FB.getLoginStatus(function(response) {   // See the onlogin handler
			statusChangeCallback(response);
		});
	}

	// window.fbAsyncInit = function() {
    
	// };
	function fbinit(){
		let myPromise = new Promise(function(myResolve, myReject){
			FB.init({
				// appId   : 338023836397118,
				appId   : 224158422690703,   /* By shubam modi himanshusofttech as admin in main account*/ //788917895217611
				status  : true,
				xfbml   : true,
				version : 'v4.0'
			});	
		  
			(function(d, s, id) {                      // Load the SDK asynchronously
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "https://connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
				setTimeout(function(){
					myResolve();
				},1000)
			}(document, 'script', 'facebook-jssdk'));
			
		});
		return myPromise;
	}
	
 
	function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
		console.log('Welcome!  Fetching your information.... ');
		FB.api('/me?fields=name,id,email', function(response) {
		  console.log('Successful login for: ' + response.name);
		  	social_login(response);
			console.log(response);
		});
	}
	
	var fab = false;
	function fblogin(){ 
		if(fab){
			FB.login(statusChangeCallback);
		}else{
			loadScript(F_AUTH,function(){
				fbinit().then(function(){
					FB.login(statusChangeCallback);
					fab = true;
				});
				
			}); 
		}
	}

	$(document).on('click','#faceLogin',function(){
		fblogin() // FB.login(statusChangeCallback, {scope: 'email,public_profile', return_scopes: true});
	});

	//if(document.getElementById("faceLogin")){
		//document.getElementById("faceLogin").addEventListener("click", function(){
			//fblogin() // FB.login(statusChangeCallback, {scope: 'email,public_profile', return_scopes: true});
		//});
	//}
	if(document.getElementById("faceSignup")){
		document.getElementById("faceSignup").addEventListener("click", function(){
			fblogin() // FB.login(statusChangeCallback, {scope: 'email,public_profile', return_scopes: true});
		});
	}
	// }
	var goo = false;
	$(document).on('click','.au_google',function(){
		if(goo){
			$('.abcRioButtonIcon').trigger('click');
		}else{
			loadScript(G_AUTH,function(){
				setTimeout(function(){
					$('.abcRioButtonIcon').trigger('click');	
				},500)
			}); 
		}
		
	})
	
	
	
})

	function onSignIn(googleUser) {
		
		var profile = googleUser.getBasicProfile();
		console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
		console.log('Name: ' + profile.getName());
		console.log('Image URL: ' + profile.getImageUrl());
		console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
		let response = {email:profile.getEmail(),name:profile.getName(),id:profile.getId()};
		signOut(response)
	}
	
	function signOut(response) {
		var auth2 = gapi.auth2.getAuthInstance();
		auth2.signOut().then(function () {
			social_login(response);
			console.log('User signed out.');
		});
	}
	
	function social_login(response){
	
	let formData = new FormData(); 
		formData.append('user_email',response.email);
		formData.append('user_name',response.name);
		formData.append('user_social',response.id);
		formData.append("sigup_acc_type", sigup_acc_type);
		if(page == 'giveaways'){
			formData.append("is_giveaways",1);
		}
		const getdiscovered = urlParams.get('getdiscovered');
		if(getdiscovered){
			formData.append("registration_source",'getdiscovered');
		}

		const gamepass = urlParams.get('gamepass');
		if(gamepass){
			formData.append("registration_source",'gamepass');
		}

		manageMyAjaxPostRequestData(formData , base_url+'home/social_login').done(function(resp){
			
			resp = JSON.parse(resp);
			console.log(resp);
			if(resp.status == 1){
				
				if(resp.user != 'newLogin'){
					let path = window.location.href;
						path = path.replace("#", "");

					let params = (new URL(path)).searchParams;
						params = params.get('invite'); 
					
					if(page == 'giveaways'){
						Custom_notify('success',giveawayMess);
					}
					setTimeout(function(){
						if(	base_url 	== 	path ||  
							path 		==  base_url+"sign-up" ||  
							path 		==  base_url+"giveaways" ||  
							path 		==  base_url+"home/messages/activation_success" ||
							path 		==  base_url+"home/messages/pwd_change_success" ||
							params 		!= 	null)
						{	
							window.location = 	base_url+"profile?user="+resp.user;
						}else{
							window.location =	path;
						}
					},1500);
				}else{
					window.location = 	base_url+"dashboard";
				}
			}else
			if(resp.status == 2){
				Custom_notify('error',resp.message);
				
				setTimeout(function(){
					window.location = 	base_url+'sign-up';
				},5000);
			}
			else{
				setTimeout(function(){
					// window.location.href = base_url+'home/logout';
				},3000);
			}
		})
	}
	/* Validate Login Page STARTS*/

	$('.login_form').on('keyup',function(event){
		event.preventDefault();
		if(event.keyCode == 13){
			login_user();
		}
	});

	// Gamification - cookie functions
	function getCookie(name) {
		const value = `; ${document.cookie}`;
		const parts = value.split(`; ${name}=`);
		if (parts.length === 2) return parts.pop().split(';').shift();
	}

	function setCookie(name, value, expirationDays = 365) {
		const date = new Date();
		date.setTime(date.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
		const expires = `expires=${date.toUTCString()}`;
		document.cookie = `${name}=${value};${expires};path=/`;
	}

	
	function login_user(){
		var u_em = $.trim($('#u_email').val());
		var u_pwd = $.trim($('#u_pwd').val());
		var recaptcha_response  = $.trim(getRecaptchaResponse('login-recaptcha'));
		
		$('#u_email').next().text('');
		$('#u_pwd').next().text('');
		
		$('.login_form').removeClass('error');
		$('.login_form').next().text('');
		
			if(u_em != '' ){
				if( u_pwd != '' ){
					
					var emRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,15}(?:\.[a-z]{2})?)$/i;

					if(!emRegex.test(u_em)) {
						$('#u_email').addClass('error');
						$('#u_email').next().text('Please enter a valid email address.');
					}else if(recaptcha_response === ""){
						$('#u_pwd').addClass('error');
						$('#u_pwd').next().text('Please complete the reCAPTCHA.');
						return false;
					}else {
						// Gamification - cookie
						setCookie('GAM_login_user', true);

						var rem_me = $('#rem_me:checked').length;
						
						$('.dis_btn').html('Please Wait  <i class="fa fa-spinner fa-spin post_spinner"></i>').prop('disabled', true);

						grecaptcha.reset();
						
						let formData = new FormData(); 
							
							formData.append('u_em',u_em)
							formData.append('u_pwd',u_pwd)
							formData.append('rem_me',rem_me)
							formData.append("g-recaptcha-response", recaptcha_response);
							if(page == 'giveaways'){
								if(!$('#checkBx1').is(":checked")){
									$('.dis_btn').text('Submit').prop('disabled', false);
									 $('#checkEr1').text('Click here to accept Giveaway\'s terms and conditions.');
									 return false;
								}else{
									 $('#checkEr1').text('');
								}
								formData.append("is_giveaways",1);
							}
							manageMyAjaxPostRequestData(formData , base_url+ 'home/allow_login_access').done(function(resp){
									
								resp = JSON.parse(resp);
								$('.dis_btn').text('Submit').prop('disabled', false);

								if(resp.status == 1){
									$('#myModal').modal('hide');
									
									let path = window.location.href;
								
									let params = (new URL(path)).searchParams;
										params = params.get('invite'); 
									
									if(page == 'giveaways'){
										Custom_notify('success',giveawayMess);
									}
									setTimeout(function(){
										if(	base_url 	== 	path.replace("#", "") ||  
											path 		==  base_url+"sign-up" ||  
											path 		==  base_url+"giveaways" ||  
											path 		==  base_url+"home/messages/activation_success" ||
											path 		==  base_url+"home/messages/pwd_change_success" ||
											params 		!= 	null)
										{
													
											window.location = 	base_url+"profile?user="+resp.user;
										}else{
											window.location =	path;
										}
									},1500);
								}else{
									$('#u_pwd').addClass('error');
									$('#u_pwd').next().text(resp.mess);
								}

							});
						}
					}
					else {
						
						$('#u_pwd').addClass('error');
						$('#u_pwd').next().text('Please enter your password.');
					}
			}
			else {
				$('#u_email').addClass('error');
				$('#u_email').next().text('Please enter your email.');
			}
		
	}
	
	
	$(document).on('keyup','.reg_form',function(e){
		e.preventDefault();
		if (e.keyCode == 13) {
			register_user();
		}
	})
	
	function register_user() {
		var u_em = $.trim($("#user_email").val());
		var u_pwd = $.trim($("#user_pwd").val());
		var user_name = $.trim($('#user_name').val());
		var recaptcha_response  = $.trim(getRecaptchaResponse('signup-recaptcha'));
		
		$(".reg_form").removeClass("error").next().next().text("");
		$("#user_email_error").text("");
		$("#user_pwd_error").text("");
		$("#user_name_error").text("");
		$("#message").text("");

		if( user_name != '' ){
			if (u_em != "") {
				if (u_pwd != "") {
					var emRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,15}(?:\.[a-z]{2})?)$/i;
					var PwRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[#?!@$%^&*-]).{8,}$/;
					if (!emRegex.test(u_em)) {
						$("#user_email").addClass("error");

						$("#user_email_error").text(
							"Please enter a valid email address."
						);
					} else if (!PwRegex.test(u_pwd)) {
						$("#user_pwd").addClass("error");
						$("#user_pwd_error").text(
							"Invalid password, please make sure the password is atleast 8 characters with one number, one capatalized letter and one special character."
						);
					}else if(recaptcha_response === ""){
						$("#user_pwd").addClass("error");
						$("#message").text('Please complete the reCAPTCHA.');
						return false;
					}else {
						$(".sign_btn")
							.text("Processing ")
							.append(
								'<i class="fa fa-spinner fa-spin post_spinner" ></i>'
							)
							.prop("disabled", true);
							
						grecaptcha.reset();

						let formData = new FormData();
						formData.append("u_em", u_em);
						formData.append("u_pwd", u_pwd);
						formData.append("user_name", user_name);
						formData.append("sigup_acc_type", sigup_acc_type);
						formData.append("g-recaptcha-response", recaptcha_response);
						
						if(page == 'giveaways'){ 
							if(!$('#checkBx2').is(":checked")){
								$(".sign_btn").text("SIGN UP").prop("disabled", false);
								$('#checkEr2').text('Click here to accept Giveaway\'s terms and conditions.');
								return false;
							}else{
								$('#checkEr2').text('');
							}
							formData.append("is_giveaways",1);
						}
						
						const getdiscovered = urlParams.get('getdiscovered');
						if(getdiscovered){
							formData.append("registration_source",'getdiscovered');
						}
						let success_message = "We have sent an email with activation link. Click on that link to activate your account. If you don’t see the email, you may need to check your SPAM/ JUNK folder.";
						const gamepass = urlParams.get('gamepass');
						if(gamepass){
							success_message = "You will receive your Game Pass coupon code via email within 24 hours of completing your signup. we have sent an email with activation link. Click on that link to activate your account. If you don’t see the email, you may need to check your SPAM/ JUNK folder.";
							formData.append("registration_source",'gamepass');
						}

						manageMyAjaxPostRequestData(
							formData,
							base_url + "home/register_email"
						).done(function (data) {
							$(".sign_btn").text("SIGN UP").prop("disabled", false);
							if (data == 404) {
								$("#user_pwd").addClass("error");
								$("#message").text("Server error.");
								setInterval(function () {
									window.location.reload(1);
								}, 2000);
							} else if (data == 401) {
								$("#user_pwd").addClass("error");
								$("#message")
									.text(
										"This email is already registered. Please enter a different email ID."
									)
									.css("color", "#eb581f");
							}else if (data == 1){
								$("#user_pwd")
									.next()
									.attr("style", "color:green !important;");
								$("#message")
									.text(success_message)
									.css("color", "green").css("margin-bottom", "10px");
								if(page == 'giveaways'){
									Custom_notify('success',giveawayMess);
								}
								setInterval(function () {
									window.location.reload(1);
								}, 50000);
							}else{
								$("#user_pwd").addClass("error");
								$("#message").text(data);
							}
						});
					}
				} else {
					$("#user_pwd").addClass("error");
					$("#user_pwd_error").text("Please enter your password.");
				}
			} else {
				$("#user_email").addClass("error");
				$("#user_email_error").text("Please enter your email.");
			}
		}
		else {
			$('#user_name').addClass('error');
			$('#user_name_error').text('Please enter your name.');
		}
	}
	
	$(document).on("keypress", "#user_pwd", function () {
		$("#user_pwd_error").text("");
	});
	
	