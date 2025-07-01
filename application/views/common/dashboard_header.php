<?php $whoami 				= 	WhoAmI($uid) ; ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="user_tab_section">
			<ul id="gam-focus-index" class="nav nav-tabs audition_tab" role="tablist">

				<?php $other_user = isset($userDetail[0]['user_uname'])	? $userDetail[0]['user_uname'] : ''; ?>
				
				<?php if(isset($social) || in_array($page_info['page'],['my_channel','collection','my_playlist','about'])){ ?>
					<li role="presentation" class="<?=($page_info['page'] == 'dashboard')?'active' : '';?>"><a href="<?= base_url('profile?user='.$other_user);  ?>">Social</a></li>
				<?php } else { ?>
					<li role="presentation" class="active"><a href="#home" aria-controls="news" role="tab" data-toggle="tab">Social</a></li>
				<?php } ?>
				
				<?php if(isset($is_session_uid) && $is_session_uid == 1){ ?>
					<!-- Gamification -->
					<li role="presentation" class="gam-focus-element gam-el-1 <?=($page_info['page'] == 'collection')?'active' : '';?>"><a href="<?= base_url('collection?user='.$other_user.''); ?>">Collection</a><div id="gam-focus-el-1"></div></li>
				<?php } ?>
				
				<?php if($whoami != 4 && isset($sigup_acc_type) && $sigup_acc_type == 'standard'){ ?>	
					<li role="presentation" class="<?=($page_info['page'] == 'my_channel')?'active' : '';?>"><a href="<?= base_url('channel?user='.$other_user) ; ?>">Channel</a></li>
					<!--li role="presentation"><a href="<?= base_url('channel?user='.$other_user .'#playlist'); ?>" aria-controls="playlist" >Playlist</a></li-->
				<?php }	?>				

				<li role="presentation" class="<?=($page_info['page'] == 'my_playlist')?'active' : '';?>"><a href="<?= base_url('playlist?user='.$other_user .''); ?>" aria-controls="playlist" >Playlist</a></li>
				
				<li role="presentation" class="<?=($page_info['page'] == 'about')?'active' : '';?>"><a href="<?= base_url('about?user='.$other_user .''); ?>">About</a></li>

				<!--<li role="presentation" class="temporary_disable hideme"><a title="Coming Soon" class="mob_hide">Media</a></li>-->
				
				 <li role="presentation" class="temporary_disable hideme"><a href="#media" aria-controls="media" role="tab" data-toggle="tab" class="mob_hide" onclick="load_image_content(this)">Media</a></li>
				 
				<?php if(isset($is_session_uid) && $is_session_uid == 1){ ?>
					<?php echo '<li role="presentation" class=""><a title="Live Chat"  href="'.base_url('messenger?user='.$_GET['user']).'"  class="mob_hide intMessage">message <span class="dis_chat_msgcount hide" id="count_unread_msg">0</span></a></li>'; ?>
					<!-- '<li role="presentation" class=""><a title="Live Chat"  href="'.base_url('messenger?user='.$_GET['user']).'"  class="mob_hide intMessage">message <span class="dis_chat_msgcount hide" id="count_unread_msg">0</span></a></li>'; -->
				<?php } ?>
				
				<li role="presentation" class="temporary_disable hideme"><a title="Coming Soon"  aria-controls="shows" role="tab" data-toggle="tab">Tickets</a></li>
				
				<li role="presentation" class="temporary_disable hideme"><a title="Coming Soon" aria-controls="merchandise" role="tab" data-toggle="tab" class="mob_hide">merchandise</a></li>
				 
				<?php if(isset($is_session_uid) && $is_session_uid == 1){ ?>
					<?php echo (isset($_SESSION['is_iva']) && $_SESSION['is_iva'])?'<li role="presentation"><a href="'.base_url('search/search_iva').'">IVA Data Mapping</a></li>':''; 
				} ?>
				<?php //if(isset($is_session_uid) && $is_session_uid == 1){ ?>
					<?php //echo (isset($_SESSION['is_ele']) && $_SESSION['is_ele'])?'<li role="presentation"><a href="'.base_url('monetization_by_mrss').'">MRSS</a></li>':''; 
				//} ?> 
				<li role="presentation" class=""><a href="<?=base_url('article?user='.$other_user.'');?>" title="Coming Soon">Articles</a></li>
				 <!--li role="presentation">
				 <a href="#more" aria-controls="more" role="tab" data-toggle="tab">more<i class="fa fa-caret-down" aria-hidden="true"></i></a>
					<ul>
						
					   <li><a >Casting Calls</a></li>
					   <li><a >Total Fans</a></li>
					   <li><a >Video Plays</a></li>
					   <li><a >Suggested Playlists</a></li>
					   <li><a >Favorite Artists</a></li>
					   <li><a >Band Members</a></li>
					   <li><a >Press</a></li>
					   <li><a >Facebook</a></li>
					   <li><a >Twitter</a></li>
					   <li><a >YouTube</a></li>
					   <li><a >Artist Blog</a></li>
					   <li role="presentation"><a href="#channel" aria-controls="channel" role="tab" data-toggle="tab" class="mob_hide">UPLOAD A VIDEO</a></li>
					</ul>
				 </li-->
			</ul>
		</div>
	</div>
</div>