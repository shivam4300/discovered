<div class="dis_endorseemnt_wrapper">
	<div class="container">
		<div class="endorseemnt_inner">
			<div class="row">
			<div class="col-md-12">
			
				<div class="au_heading">
					<h2>Endorsement agreement</h2>
				</div>
			</div>
			<div class="col-md-12">
				<p class="indorse_accept">I hereby accept the engagement to provide my endorsement for your brand in the United States(Territory).in addition ,it is understood and agreed that with respect to the website, the territory shall be worldwide.</p>
			</div>
			
			<?php if(is_session_uid($endorsee_id)){ ?>
			<div class="col-md-12">
				<?php if($endorsement_status != 2){ ?>
				<div class="checkbox dis_checkbox">
					<label>
						<input type="checkbox" value="" class="check agree">
						<i class="input-helper"></i>
						<p>I Have Read And Agree To The condition.</p>
					</label>
					<span class="form-error help-block" id="check"></span>
				</div>
				<?php } ?>
				
				<div class="endorseemnt_btn text-center">
					<?php if($endorsement_status == 2){ 
						echo '<center>Congratulations! You have accepted this endorsement.<center>';
					}else if($endorsement_status == 3){ ?>
						<a href="javascript:;" class="post_btn">Declined </a>	
						<a href="javascript:;" class="post_btn endorse_me"  data-status="2" data-id="<?= $endorse_id; ?>">Accept </a>	
					<?php }else{ ?>
						<a href="javascript:;" class="dis_btn endorse_me"  data-status="3" data-id="<?= $endorse_id; ?>">Decline </a>	
						<a href="javascript:;" class="dis_btn endorse_me"  data-status="2" data-id="<?= $endorse_id; ?>">Accept </a>
					<?php } ?>
					
				</div>
			
			</div>
			<div class="col-md-12">
				<div class="endorseemnt_btn text-center">
				<?php }
				else if($endorsement_status == 2){
					echo '<center>Your request has previously been accepted. <center>';
				}else if($endorsement_status == 3){
					echo '<center>Your endorsement request has declined.<center>';
				}?>
			</div>
			</div>
		</div>	
		</div>
	</div>
</div>