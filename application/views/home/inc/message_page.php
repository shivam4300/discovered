<div class="dis_signup_wrapper full_vh_foooter">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-0">
				<div class="dis_signup_div dis_msgdiv">
					<div class="dis_signup_inner">
						<div class="dis_signup_left">
							<div class="dis_signup_img">
								<img src="<?php echo base_url().'repo/images/msg_page_img.png';?>" class="img-responsive" alt="">
							</div>
						</div>
						<div class="dis_signup_right">
							<div class="dis_signup_right_inner">
								<div class="au_heading">
									<h2><?php echo ($type=='success' || $type=='upload_success') ? 'Congratulations !' : 'Oops !' ;?></h2>
									<p class="upload_suc_msg p_t_10"><?= $msg; ?></p>
									<?php 
										if($type=='upload_success'){
											$un = isset($_SESSION['user_uname'])?$_SESSION['user_uname']:'my';
											echo '<p class="upload_suc_msg">Go To <a href="'.base_url('channel?user='.$un ).'">Your Channel</a></p>';
										}
									?>
									
								
								</div>
								<?php echo '<img class="img-responsive upload_suc_img" src="'.base_url().'repo/images/'.$type.'.png" alt="'.$type.'">'; ?>
								<div class="clearfix"></div>
								<a type="button" class="dis_btn" href="<?php if(isset($path)){ echo base_url().$path ;}else{ echo base_url() ; }  ?>"><?php echo  ($type=='upload_success')?'Click Here To Watch Video': 'Home'; ?></a >
								<?php if($type=='upload_success'){  ?>
									<p class="upload_suc_msg p_t_10">OR</p>
									<a type="button" class="dis_btn" href="<?php if(isset($path)){ echo base_url().$path.'/add_castcrew' ;}else{ echo base_url() ; }  ?>"><?php echo  ($type=='upload_success')?'Add New Cast / Crew': 'Home'; ?></a >
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
<?php if(isset($icon_inactive)){ ?>	
	setTimeout(function(){
		window.location = '<?= base_url('home/logout'); ?>';
	},50000);
<?php } ?>	
</script>	
