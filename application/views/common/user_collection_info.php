<div class="sidebar_widget widget user_profile homepage_intro">
	<h4 class="widget-title">Profile</h4>
	<div class="dis_user_data">
		<!-- <div class="pro_share common_click" data-share-profile="<?php //echo $other_user; ?> ">
		<i class="fa fa-share " aria-hidden="true"></i>
		</div> -->
		<div class="dis_profileOption">
			<div class="dis_actiondiv">
				<span class="dis_profileOptionIcon">
					<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
						<path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
					</svg>
				</span>
				<div class="dis_action_content">
					<ul>
						<li class="common_click pro_share" data-share-profile="<?php echo $other_user; ?> ">Share Profile</li>
						<?php if(isset($is_session_uid) && $is_session_uid != 1){  ?>  
							<?php if(is_login()){  ?> 
								<!--li class="raise_flag_report" data-heading="Why are you reporting this account ?" data-viol_id="0" data-parent_id="0" data-type="account" data-related_with="1" data-related_id="<?= $uid; ?>">Report</li-->
								<li class="raise_flag_report openModalPopup" data-href="modal/report_content_popup/0/account/<?=str_replace(' ','-','Why are you reporting this account');?>" data-cls="dis_Reporting_modal dis_center_modal"  data-heading="Why are you reporting this account ?" data-viol_id="0" data-parent_id="0" data-type="account" data-related_with="1" data-related_id="<?= $uid; ?>">Report</li>

							<?php }else{ ?>
								<li class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">Report</li>
							<?php } ?>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
				   
		<?php if(isset($userDetail[0])){  ?>
		<div class="p_profieicon">
			<a href="<?php echo str_replace('_thumb','',$DP); ?>" class="pro_icon sidebar_zoom overlay">
				<img class="img-reponsive" id="itemDetail" src="<?php echo $DP;?>" title="<?php echo $userDetail[0]['user_name'];?>" alt="<?php echo $userDetail[0]['user_name'];?>" onerror="this.onerror=null;this.src='<?= user_default_image(); ?>'" >
			<?php } ?>
			</a>
			<?php if(isset($is_session_uid) && $is_session_uid == 1){  ?>  
			
			<span class="p_edit_w" data-toggle="dropdown">
				<i class="fa fa-pencil" aria-hidden="true"></i>
			</span>
				
			<div class="dis_action_content">
				<ul>
				<?php 
				
				if(trim($DP) != CDN_BASE_URL.'repo/images/user/user.png' ){ ?>
					<li class="Remove_profile_picture">Remove Profile Picture</li>
				<?php } ?>
					<!--li class="" data-toggle="modal" <?php if(isset($is_session_uid) && $is_session_uid == 1){ echo 'data-target="#upload_image"'; }  ?>>Upload Profile Picture</li-->
					<li class="openModalPopup" data-href="modal/upload_profile_picture_popup" data-cls="Audition_popup upload_popup">Upload Profile Picture</li> 
				</ul>
			</div>
		   
		   <?php } ?>
		</div>

		<!--ul>
			<?php //if(trim($DP) != trim(base_url('repo/images/user/user.png'))){ ?>
			<li class="Remove_profile_picture">Remove Profile Picture</li>
			<?php //} ?>
			<li class="">Change Profile Picture</li> 
		</ul-->
		<h3><?php echo $userDetail[0]['user_name'];?></h3>

		<?php if(isset($referral_name)){
			echo '<p class="invit_user">
					(<a href="'. base_url('profile?user='.$referral_by ) .'">
						Invited by '.$referral_name.'
					 </a>)
				  </p>';
		}?>

		<p>
		<?php echo (isset($userDetail[0]['category_name']))? $userDetail[0]['category_name']:'';?> 
		<?php echo (isset($sub_catname) && !empty($sub_catname))? '('.$sub_catname.')' :'';?> 
		<br> 
		<?php echo ucwords( $userDetail[0]['uc_city']);?> 
		<?php echo (isset($userDetail[0]['name']))? ','.$userDetail[0]['name']:'';?>
		<br>  
		<?php echo (isset($userDetail[0]['country_name']))? ','. $userDetail[0]['country_name']:'';?></p>
		<!--span><?php //echo date_format(date_create( $userDetail[0]['user_regdate'] ) , 'M d, Y');?></span-->
		
		<div class="conditional_button">
		<?php if(isset($is_session_uid) && $is_session_uid != 1){
			echo FanButton($uid)['old'];
			echo '<span class="EndorseButton">';
			// echo EndorseButton($uid);
			echo '</span>';
		} ?>
		</div>
	</div>
</div>
<?php if(isset($userDetail[0]['is_fc_member']) && $userDetail[0]['is_fc_member'] == 1){ ?>
<div class="sidebar_widget widget founders_club">
	<h2 class="dis_fcm">
	<span class="dis_fcm_icon">
		<img src="<?php echo base_url('repo/images/founder_club.svg');?>"></a>	
	</span>
	Founders Club Member
	</h2>
</div>
<?php } ?>