<!DOCTYPE html>
<style>.au_header_section{	display:none;}.au_footer_section{ 	display:none;}</style>

<!-- Full width POPUP -->
<div class="au_video_popup">
	<?php echo $this->common_html->au_video_popup($cover_video);?>
</div>
<!-- Full width POPUP -->
<!-- Banner Section -->
<div class="audition_main_wrapper au_banner_section">
	<?php echo $this->common_html->au_banner_section($cover_video);?>
</div>

<div class="dis_landing_page_wrapper" id="section2">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-6">
				<div class="dis_email_capture_div">
					<h5>Already A Member?</h5>
					 <form method="post" id="myFunction">
					   <div class="form-group">						
							<input type="password" class="form-control" placeholder="Enter Password Here"  id="temp_session" autocomplete="off">		
						</div>			
						<button type="submit" onclick="myFunction()" class="dis_host_btn ">Enter Site</button>
						<span id="error" class="form-error help-block"></span>
					</form>
				</div>
			</div>
			<div class="col-lg-6 col-md-6">
				<div class="dis_email_capture_div">
					<h5>Be The First to know</h5>
					<form id="saveEmails">
					   <div class="form-group">						
							<input type="text" class="form-control" placeholder="Enter Your Email" id="em">					
						</div>
					   <button class="dis_host_btn em_sub">submit</button>				
					   <span id="em_err" class="form-error help-block"></span>
					</form>
				 </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url('repo/js/jquery.js') ?>"></script>
<script>
$("#myFunction").submit(function(e) {
   e.preventDefault();
	myFunction();
});
$("#saveEmails").submit(function(e) {
   e.preventDefault();
   saveEmails();
});
function myFunction() {
	var txt;
	if ($('#temp_session').val() == null || $('#temp_session').val() == "") {
		$('#error').text('Please enter password to access Discovered.TV .');
	} else {
		var basepath = '<?= base_url(); ?>';
		var dataArr = {};
		dataArr['password'] = $('#temp_session').val();
		$.post(basepath + "temp/login", dataArr, function (data, status) {
			
			if (data == 0) {
				$('#error').text('Invalid Password');
			} else {
				// window.location.reload(1);
				// window.location.href = basepath;
				 location.replace(basepath+'sign-up');
			}
		});
	}

}

function saveEmails() {
   var em = $.trim($('#em').val());
   if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(em))
      var valid = true;
   else
      var valid = false;

	if (em == "" || !valid) {
		$('#em_err').text('Please enter valid email address.');
	} else {
		$('.em_sub').text('Wait...');
		var basepath = '<?= base_url();?>';
		$.post(basepath + "temp/saveEmails", {'em':em}, function (data, status) {
			if (data == 0) {
				$('#em_err').text('Something went wrong. Please, try again.');
			} else {
				$('#em_err').html('<span style="color: green;">Thank you.</span>');
				setTimeout(function(){ window.location.reload(1); }, 2000);
			}
		});
	}

}
</script>