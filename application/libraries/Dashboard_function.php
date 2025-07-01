
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_function{
	private $uid; 
	public $discLength; 
	public $commLength; 
	public $uc_epic; 
	public $login_uc_pic; 
	
	public function __construct(){
        $this->CI 			= get_instance();
		$this->uid 			= is_login();
		$this->discLength 	= 5000;
		$this->commLength 	= 500;
		$this->uc_epic 		= user_default_image();
		$this->login_uc_pic = get_user_image($this->uid);
		$this->CI->load->library('share_url_encryption');
	}

	public function get_publish_data($publish_id=''){ 
		
		$strArr		= [];
		$social 	= isset($_POST['social'])?$_POST['social']:null;;
		$uid 		= isset($_POST['pub_uid'])?$_POST['pub_uid']:$this->uid ;
		$start 		= isset($_POST['start'])?$_POST['start']:0;
		$limit 		= isset($_POST['limit'])?$_POST['limit']:1;
		
		if($social == null){    /*WHEN CURRENT PAGE IS NOT A SOCIAL PAGE*/
			$cond = array('pub_uid'=>$uid);
			
			if(!is_session_uid($uid)){   /* FOR OTHER USER	*/
				$AmIFanOfHim = AmIFollowingHim($uid);  
				if(isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)){
					$cond = 'publish_data.pub_status IN(6,7)  AND publish_data.pub_uid = '.$uid.'';/* PRIVATE,PUBLIC*/
				}else{
					$cond = array('publish_data.pub_status'=>7,'publish_data.pub_uid'=>$uid);	/* ONLY PUBLIC*/
				}
			}

			if(isset($_SESSION['suggested_fids'])){
				unset($_SESSION['suggested_fids']);
				unset($_SESSION['my_fids']);
				unset($_SESSION['fids']);
			}
		}else{
			
			if(!isset($_SESSION['fids'])){
				$suggestions = $this->CI->DatabaseModel->select_data('stop_suggestions','users_content',array('uc_userid'=>$uid),1);
				
				$_SESSION['suggestions'] =  $suggestions = isset($suggestions[0]['stop_suggestions']) ? $suggestions[0]['stop_suggestions'] : 'OK';
				
				$fids = [0];
				$following = $this->CI->DatabaseModel->select_data('user_id','become_a_fan use INDEX (following_id)',array('following_id'=>$uid));
			
				$my_fids = [];
				if(isset($following[0])){
					foreach($following as $fid){
						if($fid['user_id'] != $uid){
							array_push($fids,$fid['user_id']);
							array_push($my_fids,$fid['user_id']);
						}
					}
				}
				$_SESSION['my_fids'] = $my_fids;
				
				$suggested_fids = [];
				
				if($suggestions == "OK" || $suggestions == "FEWER"){
					$following = $this->CI->DatabaseModel->select_data('user_id','become_a_fan use INDEX (following_id)','following_id IN('.implode(',',$fids).') ');
					if(isset($following[0])){
						
						foreach($following as $fid){
							if($fid['user_id'] != $uid){
								array_push($fids,$fid['user_id']);
								array_push($suggested_fids,$fid['user_id']);
							}
						}
					}
				}
				
				$_SESSION['suggested_fids'] = $suggested_fids;
				$_SESSION['fids'] = $fids = array_unique($fids);

				
			}else{
				$fids = $_SESSION['fids'];
			}
			
			
			if(!empty($fids)){
				$cond = 'publish_data.pub_status IN(6,7)  AND publish_data.pub_uid IN('.implode(',', $fids).') ';
			}else{
				return '';
			}	
		}
		
		
		if(isset($_POST['publish_id']) && $_POST['publish_id'] != 0 && empty($publish_id)){   /*IN CASE OF UPDATE ONLY*/
			$cond = array('publish_data.pub_id'=>$_POST['publish_id']);
		}else 
		if(!empty($publish_id)){
			$cond = array('publish_data.pub_id'=>$publish_id);     /*In case of shared post ONLY*/
		}
		
		
		$order = ($social == null)? array('publish_data.pub_id','desc') :'rand()';
		
		$publish_content = $this->CI->DatabaseModel->select_data('publish_data.pub_id','publish_data use INDEX(pub_status,pub_uid)',$cond,array($limit ,$start),'', $order );
		
		$search_pub_id = implode(',',array_column($publish_content, 'pub_id'));
		
		if(!empty($search_pub_id)){
			
			$join = array('multiple' , array(
				array(	'users', 
						'users.user_id 	= publish_data.pub_uid'),
				array(	'users_content', 
						'users_content.uc_userid= users.user_id'),
			));

			$order = 'FIELD(publish_data.pub_id, '.$search_pub_id.')';
			
			$publish_content = $this->CI->DatabaseModel->select_data('publish_data.*,users.user_name,users.user_uname,users_content.uc_gender,uc_pic','publish_data use INDEX(pub_id)','pub_id IN('.$search_pub_id.')' ,'',$join,$order);
			
			
			if(isset($publish_content) && !empty($publish_content)){
				foreach($publish_content as $index => $content){
					
					$pubId 		 = $content['pub_id'];
					$pub_uid 	 = $content['pub_uid'];
					$pub_date 	 = $this->CI->common->manageTimezone($content['pub_date']);
					$pub_media 	 = $content['pub_media'];
					$pub_content = $content['pub_content'];
					$pub_reason  = $content['pub_reason'];
					$user_uname  = $content['user_uname'];
					$user_name   = $content['user_name'];
					$uc_pic   	 = $content['uc_pic'];
					$uc_gender   = $content['uc_gender'];
					$share_pid   = $content['share_pid'];
					$share_uid   = $content['share_uid'];
					$channel_post_id   = $content['channel_post_id'];
					$is_vid_processed  = $content['is_video_processed'];
					
					$shared_data = '';
					$ShareMeNow  = $pubId;
					if(!empty(trim($share_pid))){
						$shared_data = $this->get_publish_data($share_pid);              /*In case of shared post*/
						$shared_data = isset($shared_data[0]['post'])?$shared_data[0]['post']:'';
						
						if(empty(trim($shared_data))){
							$shared_data = '<div class="post_delete">
												<div class="delete_top">
													<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <h4>
													Sorry,this content isn\'t available right now.</h4>
												</div>
												<p>The link you followed may be expired or deleted, or the page may only be visible to an audience you\'re not in.</p>
											</div>';
						}
						$ShareMeNow  = $share_pid;
					}

					$pub_format = '';
					if(!empty($pub_media)){
						$p_data = explode('|',$pub_media);
						$display_content = $p_data[0];
						$pub_format = $p_data[1];
						
						$ThumbImage =  base_url('repo/images/thumbnail.jpg');
							if(sizeof($p_data) == 3){
								$ThumbImage = AMAZON_URL."aud_".$pub_uid."/images/".$p_data[2];
							}
					}
					
					$dyamicClass 	=	"";
					$media_section 	= 	"";
						
						if( $pub_format == 'video' && $channel_post_id != NULL) {
						
						
						$Filter = $this->CI->share_url_encryption->FilterSocialVideo($pubId,$pub_uid,$display_content,$is_vid_processed,'.mp4');
						$link = $this->CI->share_url_encryption->share_single_page_link_creator('2|'.$channel_post_id , 'encode');
						
						$media_section =	'<div class="dis_user_post_content ForAutoPlay">
												<div class="dis_post_img">
													<div  class="box '.$dyamicClass.'"> 
														<video id="my_video_'.$pubId.'"  class="video-js vjs-big-play-centered vjs-default-skin "
																poster="'.$ThumbImage.'"
																vidid = "'.base_url('embed/').$pubId. '">
																<source src="'.$Filter['video'].'" type="'.$Filter['mime_type'].'" id="media_'.$pubId.'">
														</video>
														<span class="dragRemove">
														</span>
														<a class="chel_shardvid" href="'.$link.'" target="_blank">
															<img src="'.base_url('repo/images/mini_logo.webp').'" class="img-responsive" alt="video-logo">
														</a>
														<div class="feature_vidmute">
															<a class="speaker mute" data-videojs="my_video_'.$pubId.'">
																<span></span>
															</a>
														</div>
													</div>
													<!--div class="featured_flag"><span>Official Video</span></div-->
												</div> 
													'.$shared_data.'
												<span class="contentText" id="text_'.$pubId.'" data-text="'.$pub_content.'">'.nl2br($this->partOfString($pub_content)).'</span>
												<p><strong></strong>#Shared From Channel</p>
											</div>';
						}elseif( $pub_format == 'video' ) {
						
						
						$Filter = $this->CI->share_url_encryption->FilterSocialVideo($pubId,$pub_uid,$display_content,$is_vid_processed);
						
						
						$media_section =	'<div class="dis_user_post_content ForAutoPlay">
												<div class="dis_post_img">
													<div  class="box '.$dyamicClass.'"> 
														<video controls id="my_video_'.$pubId.'"  class="video-js vjs-big-play-centered vjs-default-skin "
																poster="'.$ThumbImage.'"
																vidid = "'.base_url('embed/').$pubId. '" >
																<source src="'.$Filter['video'].'" type="'.$Filter['mime_type'].'" id="media_'.$pubId.'">
														</video>
														<span class="dragRemove">
														
														</span>
													</div>
												</div> 
													'.$shared_data.'
												<span class="contentText" id="text_'.$pubId.'" data-text="'.$pub_content.'">'.nl2br($this->partOfString($pub_content)).'</span>
												<!--p><strong></strong></p-->
											</div>';
						}elseif( $pub_format == 'image' ) {
							$img = AMAZON_URL.'aud_'.$pub_uid.'/images/'.$display_content;
						$media_section =' 	<div class="dis_user_post_content">
												<div class="dis_post_img">
													<img class="img-responsive" src="'.$img.'" id="media_'.$pubId.'" onerror="ImageOnLoadError(this,\''.$img.'\',\''.$ThumbImage.'\')">																
												</div>
												'.$shared_data.'
												<span class="contentText" id="text_'.$pubId.'" data-text="'.$pub_content.'">'.nl2br($this->partOfString($pub_content)).'</span>
												<!--p><strong></strong></p-->
												
											</div>';
						}else{
						$media_section =' 	<div class="dis_user_post_content">
												'.$shared_data.'	
												<span class="contentText" id="text_'.$pubId.'" data-text="'.$pub_content.'">'.nl2br($this->partOfString($pub_content)).'</span>
												<!--p><strong></strong></p-->
												
												<a target="_blank" href="`+url+`" class="post_linkpreview hide">
													
												</a>
												
											</div>';
						}

						
						
						
						$like 			= $this->like($pubId);
						$commets_count 	= $this->get_main_commets_count($pubId);
						
						$uc_ppic = (!empty($uc_pic))? create_upic($pub_uid,$uc_pic) : '';
					
						
						$footer = '<div class="dis_user_post_footer">
									<div class="wholoveit likecount_wrap">'.$like[1].'</div> 
										<ul class="dis_meta">
												<li class="l_p_text_'.$pubId.'">
												'.$like[0].'
												<li>'; 
												if(is_login()){
													$footer .='<a class="trigger_comment" onclick="get_comment('.$pubId.',\'html\')" id="com_disbl_'.$pubId.'" count="'. $commets_count .'">';
												}else{
													$footer .='<a class="trigger_comment openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">';
												}
												$footer .='<span>
														<svg  xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 25 25">
															<path fill-rule="evenodd"  fill="rgb(196, 196, 196)" d="M12.499,-0.001 C5.606,-0.001 -0.002,4.887 -0.002,10.894 C-0.002,12.994 0.684,15.028 1.985,16.787 C1.739,19.425 1.079,21.383 0.120,22.311 C-0.006,22.433 -0.038,22.621 0.042,22.776 C0.113,22.914 0.259,23.000 0.415,23.000 C0.434,23.000 0.453,22.998 0.473,22.995 C0.642,22.972 4.562,22.426 7.398,20.841 C9.008,21.470 10.723,21.789 12.499,21.789 C19.393,21.789 25.000,16.901 25.000,10.894 C25.000,4.887 19.393,-0.001 12.499,-0.001 ZM6.666,12.508 C5.746,12.508 4.999,11.784 4.999,10.894 C4.999,10.004 5.746,9.280 6.666,9.280 C7.585,9.280 8.332,10.004 8.332,10.894 C8.332,11.784 7.585,12.508 6.666,12.508 ZM12.499,12.508 C11.580,12.508 10.833,11.784 10.833,10.894 C10.833,10.004 11.580,9.280 12.499,9.280 C13.419,9.280 14.166,10.004 14.166,10.894 C14.166,11.784 13.419,12.508 12.499,12.508 ZM18.333,12.508 C17.414,12.508 16.666,11.784 16.666,10.894 C16.666,10.004 17.414,9.280 18.333,9.280 C19.252,9.280 20.000,10.004 20.000,10.894 C20.000,11.784 19.252,12.508 18.333,12.508 Z"/>
														</svg>
													</span>
													<span>Comments('. $commets_count .')</span>
												</a>
												</li>
												<li>
												<a class="share_post">
													<span>
														<svg  xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 25 25">
															<path fill-rule="evenodd"  fill="rgb(196, 196, 196)" d="M12.500,-0.001 C5.596,-0.001 -0.000,5.596 -0.000,12.499 C-0.000,19.402 5.596,24.999 12.500,24.999 C19.403,24.999 25.000,19.402 25.000,12.499 C25.000,5.596 19.403,-0.001 12.500,-0.001 ZM11.109,12.499 C11.109,12.760 11.062,13.009 10.979,13.241 L14.468,14.919 C14.869,14.480 15.445,14.203 16.087,14.203 C17.300,14.203 18.283,15.186 18.283,16.399 C18.283,17.612 17.300,18.595 16.087,18.595 C14.874,18.595 13.890,17.612 13.890,16.399 C13.890,16.283 13.902,16.169 13.920,16.058 L10.206,14.271 C9.843,14.537 9.397,14.696 8.912,14.696 C7.699,14.696 6.716,13.713 6.716,12.499 C6.716,11.286 7.699,10.303 8.912,10.303 C9.397,10.303 9.843,10.462 10.206,10.727 L13.920,8.941 C13.903,8.830 13.891,8.717 13.891,8.600 C13.891,7.387 14.874,6.404 16.087,6.404 C17.300,6.404 18.284,7.387 18.284,8.600 C18.284,9.813 17.300,10.797 16.087,10.797 C15.446,10.797 14.869,10.519 14.468,10.080 L10.979,11.758 C11.062,11.990 11.109,12.239 11.109,12.499 Z"/>
														</svg>
													</span>
													Share Post
													<div class="dis_action_content">
														<ul>
															<li class="dtvShareMe common_click" data-share="1|'.$pubId.'">Share To Other\'s </li>';
															if(is_login()){
																$footer .='<li class="ShareMeNow" data-share_pid="'.$ShareMeNow.'">Share On My Profile</li>';
															}else{
																$footer .='<li class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">Share On My Profile</li>';
															} 
												$footer .='</ul>
													</div>
												</a>
												</li>
												
											</ul>
											<div class="dis_comment" id="com_append_'.$pubId.'" data-comment="0">
												
											</div> 
											<div class="dis_comment_form">
												<div class="dis_user_pic">	
													<img src="'.$this->login_uc_pic.'" alt="'.$user_name.'" onerror="this.onerror=null;this.src=\''.$this->uc_epic.'\'">										
												</div>
												
												<div class="dis_form">																
														<textarea maxlength="'.$this->commLength.'" placeholder="Your Comment Here..." class="comment_textarea form-control com_text_'.$pubId.'_0"></textarea>';
														
														if(is_login()){
															$footer .='<button class="dis_form_submit" onclick="save_comment(\'pub_'.$pubId.'\',\'parent_0\')"><img src="'.base_url().'repo/images/send_img.png" class="img-responsive" alt=""></button>'; 
														}else{ 
															$footer .='<button class="dis_form_submit openModalPopup" data-href="modal/login_popup" data-cls="login_mdl"><img src="'.base_url().'repo/images/send_img.png" class="img-responsive" alt=""></button>'; 
														}	
														
														$footer .='<div class="emoji_picker _EmojiPicker" data-target="#CommentEmoji_'.$pubId.'" data-textarea=".com_text_'.$pubId.'_0">
														<img class="" src="'.base_url('repo/images/emoji/emoji.svg').'" alt="Emoji">
														</div>
														<span id="CommentEmoji_'.$pubId.'" class="hide"></span>
												
												</div>
											</div>
										</div>';	
							
						
						
						$message 			= $this->getPublishReason($pub_reason,$uc_gender,$share_uid);
						
						$suggested_message 	= 	isset($_SESSION['suggested_fids']) && 
												in_array($pub_uid,$_SESSION['suggested_fids']) && 
												isset($_SESSION['my_fids']) && 
												!in_array($pub_uid,$_SESSION['my_fids']) ? 
												' (Suggested for you)' : '';
						
						$fan_btn 			=  $suggested_message != "" ? '<div class="dis_social_fan_btn">'. FanButton($pub_uid)['old'] .'</div>': "" 	 ;
						
						
						$menu = $ulmenu = $flag_report = '';  // if Social mode is active OR in case of other User
						
						if($social != NULL || !is_session_uid($pub_uid)){
							if(is_login()){  
								$flag_report = '<li class="raise_flag_report openModalPopup" data-href="modal/report_content_popup/0/content/Why-are-you-reporting-this-post" data-cls="dis_Reporting_modal dis_center_modal" data-heading="Why are you reporting this post ?" data-viol_id="0" data-parent_id="0" data-type="content" data-related_with="2" data-related_id="'.$pubId.'"><a>Report</a></li>';
							}else{
								$flag_report ='<li class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl"><a>Report</a></li>';	
							}
							$ulmenu =  $flag_report; 
						}

						if($social == NULL && is_session_uid($pub_uid)){
							
							$ulmenu =  '<li><a onclick="drop_actions(\'edit\','.$pubId.')">Edit</a></li>
										<li><a onclick="drop_actions(\'delete\','.$pubId.')">Delete</a></li>
										<li><a onclick="drop_actions(\'audience\','.$pubId.')">Audience</a></li>'; 

						}else if($suggested_message != ""){

							$few = isset($_SESSION['suggestions']) && $_SESSION['suggestions'] != "FEWER" ? 
							'<li><a onclick="drop_actions(\'FEWER\')">See fewer suggestions</a></li>' : '';
							
							$ulmenu = 	$few.' <li><a onclick="drop_actions(\'STOP\')">I don\'t want suggestions</a></li> '.$flag_report ;
						}

						$menu =  ' <div class="dis_actiondiv">
											<span>
												<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
													<path fill-rule="evenodd"  fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"/>
												</svg>
											</span>
											<div class="dis_action_content" id="post_drop_'.$pubId.'">
												<ul>'.$ulmenu.'</ul>
											</div>
										</div>'; 
							
						

						$str ='
							<div class="dis_user_post_data" id="parent_post_content_'.$pubId.'">
								<div class="dis_user_post_header">
									<div class="dis_user_img">														
										<img src="'.$uc_ppic.' " alt="'.$user_name.'" onerror="this.onerror=null;this.src=\''.$this->uc_epic.'\'">																													
									</div>
									<div class="dis_user_detail">
										<h3>
											<a href="'.base_url('profile?user=').$user_uname.'">'.$user_name.'</a> 
											<span class="dis_pub_reason">'. $message . ' ' . $suggested_message . '   </span>  
										</h3> 
										'. $fan_btn .'
									<!--p class="published_date"> '.time_elapsed_string($pub_date,false).' </p-->
									'.$menu.'
									</div>
								</div>
								'.$media_section.'
								'.$footer.'
							</div> ';
									
						if(isset($_SESSION['suggestions']) && $_SESSION['suggestions'] == "FEWER" && $suggested_message != ""){	
							if( (rand(0,10) >= 8)  || (sizeof($strArr) == 0 && (sizeof($publish_content) - 1) == $index) ){
								array_push($strArr,array('post'=>$str,'pub_format'=>$pub_format,'pub_uid'=>$pub_uid));
							}
						}else{
							array_push($strArr,array('post'=>$str,'pub_format'=>$pub_format,'pub_uid'=>$pub_uid));
						}
						
				
				}
			}
		}
		return $strArr;
	}
   
	
	
	
	public function get_commets(){
     	$limit 			= 5;
		$uid 			= isset($_POST['pub_uid'])?$_POST['pub_uid']:$this->uid;
		$pub_id 		= isset($_POST['pub_id'])?$_POST['pub_id']:'';
		$start 			= isset($_POST['start'])?$_POST['start']:0;
		$limit 			= isset($_POST['limit'])?$_POST['limit']:$limit;
		
		$com_html='';		
		
		$comments_Details = $this->CI->DatabaseModel->select_data('*','comments use INDEX(com_pubid,com_parentid)',array('com_pubid'=>$pub_id ,'com_parentid'=> 0),array($limit+1,$start),'',array('com_id','DESC')); 
		
		if(!empty($comments_Details)) {
			$checkCount = 1;
			
			foreach($comments_Details as $solo_comments ) {
				$com_id 		= $solo_comments['com_id'];
				$com_uid 		= $solo_comments['com_uid'];
				$com_pubid 		= $solo_comments['com_pubid'];
				$com_text 		= $solo_comments['com_text'];
				$com_parentid 	= $solo_comments['com_parentid'];
				
				if($checkCount++ <= $limit){
					$where = array('com_pubid'=>$pub_id,'com_parentid'=>$com_id);
					$count = $this->CI->DatabaseModel->aggregate_data('comments use INDEX(com_pubid,com_parentid)','com_id','COUNT', $where);
					
					$countReply = ($count>0)?'<span class="comment_subrelpay" id="com_disbl_'.$com_id.'" onclick="get_comment_reply('.$pub_id.',\'append\',' .$com_id.')">'.$count.' reply </span>' : '';
			
					$reply 		= 	'<a onclick="show_reply_box('.$com_id.')"><svg  xmlns="http://www.w3.org/2000/svg" width="13px" height="12px" viewBox="0 0 13 12"><path fill-rule="evenodd"  fill="rgb(235, 88, 31)" d="M0.388,5.430 C1.171,3.414 3.287,2.406 6.735,2.406 L8.360,2.406 L8.360,0.485 C8.360,0.355 8.406,0.242 8.498,0.147 C8.590,0.052 8.699,0.005 8.825,0.005 C8.950,0.005 9.059,0.052 9.151,0.147 L12.865,3.990 C12.957,4.085 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.570 12.865,4.665 L9.151,8.508 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.508 C8.406,8.413 8.360,8.300 8.360,8.170 L8.360,6.249 L6.735,6.249 C6.262,6.249 5.837,6.263 5.462,6.294 C5.088,6.323 4.715,6.377 4.345,6.455 C3.975,6.533 3.654,6.639 3.380,6.774 C3.107,6.909 2.852,7.083 2.615,7.295 C2.378,7.508 2.184,7.761 2.034,8.054 C1.885,8.346 1.767,8.692 1.683,9.093 C1.598,9.493 1.556,9.946 1.556,10.451 C1.556,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.551 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.825 1.608,11.887 1.567,11.937 C1.525,11.987 1.468,12.012 1.396,12.012 C1.319,12.012 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.099,11.719 C1.070,11.654 1.037,11.579 1.001,11.494 C0.964,11.409 0.939,11.350 0.925,11.314 C0.310,9.888 0.003,8.760 0.003,7.930 C0.003,6.934 0.131,6.101 0.388,5.430 Z"/></svg> reply</a>&nbsp';
					
					$delete 	= 	(is_session_uid($com_uid))?
									'<a class="del_comment" data-com_id="'.$com_id.'" data-parent_id="'.$com_parentid.'" data-pub_id="'.$com_pubid.'"><svg 
									 xmlns="http://www.w3.org/2000/svg"
									 xmlns:xlink="http://www.w3.org/1999/xlink"
									 width="11px" height="13px">
									<path fill-rule="evenodd"  fill="rgb(234, 86, 41)"
									 d="M-0.000,2.051 L-0.000,0.935 C-0.000,0.738 0.209,0.579 0.466,0.579 L3.665,0.579 L3.665,0.119 C3.665,0.053 3.735,-0.000 3.820,-0.000 L7.180,-0.000 C7.265,-0.000 7.335,0.053 7.335,0.119 L7.335,0.579 L10.534,0.579 C10.791,0.579 11.000,0.738 11.000,0.935 L11.000,2.051 L-0.000,2.051 ZM9.997,12.427 C9.980,12.747 9.633,13.000 9.212,13.000 L1.880,13.000 C1.459,13.000 1.113,12.747 1.095,12.427 L0.571,3.002 L10.521,3.002 L9.997,12.427 ZM3.759,5.231 C3.759,5.104 3.618,5.001 3.445,5.001 L2.944,5.001 C2.771,5.001 2.631,5.104 2.631,5.231 L2.631,10.771 C2.631,10.898 2.771,11.001 2.944,11.001 L3.445,11.001 C3.618,11.001 3.759,10.898 3.759,10.771 L3.759,5.231 ZM6.015,5.231 C6.015,5.104 5.875,5.001 5.702,5.001 L5.200,5.001 C5.027,5.001 4.887,5.104 4.887,5.231 L4.887,10.771 C4.887,10.898 5.028,11.001 5.200,11.001 L5.702,11.001 C5.875,11.001 6.015,10.898 6.015,10.771 L6.015,5.231 ZM8.272,5.231 C8.272,5.104 8.131,5.001 7.958,5.001 L7.457,5.001 C7.284,5.001 7.143,5.104 7.143,5.231 L7.143,10.771 C7.143,10.898 7.284,11.001 7.457,11.001 L7.958,11.001 C8.131,11.001 8.272,10.898 8.272,10.771 L8.272,5.231 Z"/>
									</svg> delete</a>&nbsp':'';
					$report 	= 	(!is_session_uid($com_uid))?
									'<a class="raise_flag_report openModalPopup" data-href="modal/report_content_popup/0/content/Why-are-you-reporting-this-comment" data-cls="dis_Reporting_modal dis_center_modal" data-heading="Why are you reporting this comment ?" data-viol_id="0" data-parent_id="0" data-type="content" data-related_with="4" data-related_id="'.$com_id.'"><svg xmlns="http://www.w3.org/2000/svg" width="11px" height="13px" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 421 512.44"><path fill-rule="evenodd"  fill="rgb(234, 86, 41)" d="M58.78 298.03v214.41H0V0h241.91c5.07 0 9.26 3.74 9.98 8.62l9.78 49.76h149.22c5.6 0 10.11 4.55 10.11 10.11v277.9c0 5.56-4.51 10.11-10.11 10.11H220.2c-5.31 0-9.66-4.11-10.06-9.35l-7.94-49.12H58.78z"/></svg> report</a>':'';

					$cuser 		= 	get_user($com_uid);
					$solo_upic 	=	(isset($cuser[0]['uc_pic']) && !empty($cuser[0]['uc_pic']))	? create_upic($com_uid,$cuser[0]['uc_pic']) : '';
					$solo_uname =	(isset($cuser[0]['user_uname']) && !empty($cuser[0]['user_uname']))? $cuser[0]['user_uname'] : '';
					$solo_name 	=	(isset($cuser[0]['user_name']) && !empty($cuser[0]['user_name']))? $cuser[0]['user_name'] : '';
					
				
					$com_html .=
					'<ol class="comment comment_div" data-id="show_comments_'.$pub_id.'">
						<li class="comment_list">
							<div class="dis_comment_div">
								
								<div class="dis_comment_img">
									<img src="'.$solo_upic.'" class="img-responsive" alt="'.$solo_name.'" onerror="this.onerror=null;this.src=\''.$this->uc_epic.'\'">
								</div>
								
								<div class="dis_comment_data">
									<h3>
										<a href="'.base_url('profile?user=').$solo_uname.'">'.$solo_name.' <!-- - <span class="time" >'.time_elapsed_string($this->CI->common->manageTimezone($solo_comments['com_date']) ,false).'</span>--></a> 
									</h3>
									
									<span class="dis_reply">
									'.$reply.' 
									'.$delete.' 
									'.$report.' 
									</span>
									
									<p class="commment_p">'.nl2br($this->partOfString($com_text,0,200)).'</p>
									<br>'.$countReply.'
								</div>
								
							</div>
							<ul class="child " data-id="show_comments_'.$com_id.'" data-comment="0">
								
							</ul>
						</li>
					</ol>
										
					<div class="dis_comment_form reply_box_'.$com_id.'" style="display:none;">
						<div class="dis_user_pic">																
							<img src="'. $this->login_uc_pic .'" class="img-responsive" onerror="this.onerror=null;this.src=\''.$this->uc_epic.'\'">															
						</div>
						<div class="dis_form">																
							<textarea  maxlength="'.$this->commLength.'" placeholder="Your Comment Here..." class="comment_textarea form-control com_text_'.$pub_id.'_'.$com_id.'" ></textarea>															
							
							<button class="dis_form_submit" onclick="save_comment(\'pub_'.$pub_id.'\', \'parent_'.$com_id.'\')"><img  src="'.base_url().'repo/images/profile/enter.png" class="img-responsive" alt=""></button> 
							
							<div class="emoji_picker _EmojiPicker" data-target="#CommentEmoji_'.$pub_id.'_'.$com_id.'" data-textarea=".com_text_'.$pub_id.'_'.$com_id.'">
								<img class="" src="'.base_url('repo/images/emoji/emoji.svg').'" alt="smile svg">
							</div>
							<span id="CommentEmoji_'.$pub_id.'_'.$com_id.'" class="hide"></span>
							
						</div>
					</div>';
				}
			}
			
			if(sizeof($comments_Details) > $limit ){
					$com_html .=
					'<div class="text-center"><span class="comment_viewmor" style="display:none;" id="com_view_more_'.$pub_id.'" onclick="get_comment('.$pub_id.',\'append\')">view more</span></div>';
			}
		
		}
		return $com_html;
	}
	
	
	
	public function get_commets_reply(){
     	$limit 			= 5;
		$uid 			= isset($_POST['pub_uid'])?$_POST['pub_uid']:$this->uid;
		$pub_id 		= isset($_POST['pub_id'])?$_POST['pub_id']:'';
		$parent_id 		= isset($_POST['parent_id'])?$_POST['parent_id']:'';
		$start 			= isset($_POST['start'])?$_POST['start']:0;
		$limit 			= isset($_POST['limit'])?$_POST['limit']:$limit;
		
		$com_html='';		
		
		$reply_details = $this->CI->DatabaseModel->select_data('*','comments use INDEX(com_pubid,com_parentid)',array('com_pubid'=>$pub_id ,'com_parentid'=>$parent_id),array($limit+1,$start),'',array('com_id','DESC'));
		
		if(!empty($reply_details)){ 
			$checkCount = 1;
			// $uc_pic = get_user_image($uid);
			foreach($reply_details as $solo_reply) {
				if($checkCount++ <= $limit){
					$com_uid 		= 	$solo_reply['com_uid'];
					$com_parentid 	= 	$solo_reply['com_parentid'];
					$com_id 	= 	$solo_reply['com_id'];
					$com_text 	= 	$solo_reply['com_text'];
					$pub_id 	= 	$solo_reply['com_pubid'];
					$com_date 	=	$solo_reply['com_date'];
					
					$where = array('com_pubid'=>$pub_id,'com_parentid'=>$com_id);
					$count = $this->CI->DatabaseModel->aggregate_data('comments use INDEX(com_pubid,com_parentid)','com_id','COUNT', $where);
					
					$countReply='';
					
					$reply = '<span class="dis_reply">';
					
					if(is_session_uid($com_uid)){
						$reply .= '<a class="del_comment" data-com_id="'.$com_id.'" data-parent_id="'.$com_parentid.'" data-pub_id="'.$pub_id.'"><svg 
						 xmlns="http://www.w3.org/2000/svg"
						 xmlns:xlink="http://www.w3.org/1999/xlink"
						 width="11px" height="13px">
						<path fill-rule="evenodd"  fill="rgb(234, 86, 41)"
						 d="M-0.000,2.051 L-0.000,0.935 C-0.000,0.738 0.209,0.579 0.466,0.579 L3.665,0.579 L3.665,0.119 C3.665,0.053 3.735,-0.000 3.820,-0.000 L7.180,-0.000 C7.265,-0.000 7.335,0.053 7.335,0.119 L7.335,0.579 L10.534,0.579 C10.791,0.579 11.000,0.738 11.000,0.935 L11.000,2.051 L-0.000,2.051 ZM9.997,12.427 C9.980,12.747 9.633,13.000 9.212,13.000 L1.880,13.000 C1.459,13.000 1.113,12.747 1.095,12.427 L0.571,3.002 L10.521,3.002 L9.997,12.427 ZM3.759,5.231 C3.759,5.104 3.618,5.001 3.445,5.001 L2.944,5.001 C2.771,5.001 2.631,5.104 2.631,5.231 L2.631,10.771 C2.631,10.898 2.771,11.001 2.944,11.001 L3.445,11.001 C3.618,11.001 3.759,10.898 3.759,10.771 L3.759,5.231 ZM6.015,5.231 C6.015,5.104 5.875,5.001 5.702,5.001 L5.200,5.001 C5.027,5.001 4.887,5.104 4.887,5.231 L4.887,10.771 C4.887,10.898 5.028,11.001 5.200,11.001 L5.702,11.001 C5.875,11.001 6.015,10.898 6.015,10.771 L6.015,5.231 ZM8.272,5.231 C8.272,5.104 8.131,5.001 7.958,5.001 L7.457,5.001 C7.284,5.001 7.143,5.104 7.143,5.231 L7.143,10.771 C7.143,10.898 7.284,11.001 7.457,11.001 L7.958,11.001 C8.131,11.001 8.272,10.898 8.272,10.771 L8.272,5.231 Z"/>
						</svg> delete</a>&nbsp&nbsp'; 
					}

					if(!is_session_uid($com_uid)){
						$reply .= '<a class="raise_flag_report openModalPopup" data-href="modal/report_content_popup/0/content/Why-are-you-reporting-this-comment" data-cls="dis_Reporting_modal dis_center_modal" data-heading="Why are you reporting this comment ?" data-viol_id="0" data-parent_id="0" data-type="content" data-related_with="4" data-related_id="'.$com_id.'"><svg xmlns="http://www.w3.org/2000/svg" width="11px" height="13px" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 421 512.44"><path fill-rule="evenodd" fill="rgb(234, 86, 41)" d="M58.78 298.03v214.41H0V0h241.91c5.07 0 9.26 3.74 9.98 8.62l9.78 49.76h149.22c5.6 0 10.11 4.55 10.11 10.11v277.9c0 5.56-4.51 10.11-10.11 10.11H220.2c-5.31 0-9.66-4.11-10.06-9.35l-7.94-49.12H58.78z"/></svg> report</a>'; 
					}

					$reply .= '</span>'; 
					
					$cuser = get_user($com_uid);
					$solo_upic =(isset($cuser[0]['uc_pic']) && !empty($cuser[0]['uc_pic']))?create_upic($com_uid,$cuser[0]['uc_pic']) : '';
					$solo_uname =(isset($cuser[0]['user_uname']) && !empty($cuser[0]['user_uname']))? $cuser[0]['user_uname'] : '';
					$solo_name =(isset($cuser[0]['user_name']) && !empty($cuser[0]['user_name']))? $cuser[0]['user_name'] : '';
					 
					$com_html .= 
					'<li>
						<div class="dis_comment_div">
							<div class="dis_comment_img">
								<img src="'.$solo_upic.'" class="img-responsive" alt="'.$solo_name.'" onerror="this.onerror=null;this.src=\''.$this->uc_epic.'\'">
							</div>
							<div class="dis_comment_data">
								<h3><a href="'.base_url('profile?user=').$solo_uname.'">'.$solo_name.' <!-- - <span class="time">'.time_elapsed_string($this->CI->common->manageTimezone($com_date) ,false).'</span>-->  </a></h3>
								'.$reply.'
								<p>'.$com_text.'.</p>
								<br>'.$countReply.'
							</div>
						</div>
						<ul class="child " data-id="show_comments_'.$com_id.'" data-comment="0";>
						
						</ul>
					</li>
					';
											
				}
			}
			if(sizeof($reply_details) > $limit){
					$com_html .=
					'<div class="text-center"><span style="display:none;" class="comment_viewmor" id="com_view_more_'.$parent_id.'" onclick="get_comment_reply('.$pub_id.',\'append\',' .$parent_id.',\'no\')">view more</span></div>';
			}
			
		}
		
		return $com_html;
	}
	
	
	
	
	public function like($pubId){
	
		$count_like = 	$this->get_total_likes($pubId);
		$like_name 	=	$this->like_name($pubId);
		
		$user_like 	= 	$this->get_total_likes($pubId,$this->uid);	
			if( $user_like == 'yes' ) {
				 
				 $wholiked = $like_name; 
				
				 if($count_like > 1) { 
					$wholiked .= ' & '; 
				 } 
				 if($count_like > 1) { 
					$wholiked .= ($count_like-1) .' '. 'others'; 
				 }
					if(!empty($like_name))	
					$wholiked .= ' Loved it';	
					
					$wholike = ' Love it';	
				
				if(is_login()){
					$like = '<a  onclick="unlike_post('.$pubId.',\'post\')">';
				}else{
					$like ='<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">';
				}
					$like .= '<span class="Un-Love_now">
							  <svg  xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 25 25">
								 <path fill-rule="evenodd"  fill="#ff2a43" d="M12.500,25.000 C5.596,25.000 -0.000,19.404 -0.000,12.500 C-0.000,5.596 5.596,-0.000 12.500,-0.000 C19.404,-0.000 25.000,5.596 25.000,12.500 C25.000,19.404 19.404,25.000 12.500,25.000 ZM17.114,8.933 C16.538,8.337 15.775,8.012 14.960,8.012 C14.146,8.012 13.381,8.340 12.804,8.936 L12.503,9.246 L12.198,8.931 C11.621,8.334 10.854,8.005 10.040,8.005 C9.228,8.005 8.462,8.333 7.888,8.925 C7.312,9.521 6.995,10.312 6.997,11.154 C6.997,11.995 7.317,12.784 7.893,13.379 L12.275,17.908 C12.335,17.970 12.417,18.004 12.496,18.004 C12.576,18.004 12.657,17.973 12.718,17.910 L17.109,13.389 C17.686,12.793 18.003,12.003 18.003,11.161 C18.005,10.320 17.690,9.528 17.114,8.933 Z"/>
							  </svg>
						   </span>
						   '.$wholike.'
						</a>';
						
										 
			}else{
					
					$wholiked = '';
				
				if($count_like > 0) { 
					$wholiked = $like_name; 
				}
				if($count_like > 1) { 
			
					$wholiked .= ' & '. ($count_like -  1).' '. 'others'; 
				} 	
					if(!empty($like_name))
					$wholiked .= ' Loved it';	
					
					$wholike = ' Love it';	

				if(is_login()){
					$like = '<a  onclick="like_post('.$pubId.',\'post\')">';
				}else{
					$like ='<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">';
				}
					$like .= '<span class="Un-Love_now">
						   <svg  xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 25 25">
							  <path fill-rule="evenodd"  fill="rgb(196, 196, 196)" d="M12.500,25.000 C5.596,25.000 -0.000,19.404 -0.000,12.500 C-0.000,5.596 5.596,-0.000 12.500,-0.000 C19.404,-0.000 25.000,5.596 25.000,12.500 C25.000,19.404 19.404,25.000 12.500,25.000 ZM17.114,8.933 C16.538,8.337 15.775,8.012 14.960,8.012 C14.146,8.012 13.381,8.340 12.804,8.936 L12.503,9.246 L12.198,8.931 C11.621,8.334 10.854,8.005 10.040,8.005 C9.228,8.005 8.462,8.333 7.888,8.925 C7.312,9.521 6.995,10.312 6.997,11.154 C6.997,11.995 7.317,12.784 7.893,13.379 L12.275,17.908 C12.335,17.970 12.417,18.004 12.496,18.004 C12.576,18.004 12.657,17.973 12.718,17.910 L17.109,13.389 C17.686,12.793 18.003,12.003 18.003,11.161 C18.005,10.320 17.690,9.528 17.114,8.933 Z"/>
						   </svg>
						</span>
					   '.$wholike.'
					 </a>';
			}
		return array($like,$wholiked);
	}
	
	
	public function get_total_likes($pubID,$userID=''){
        if( $userID != '' ) {
			$user_liked =$this->CI->DatabaseModel->select_data('likes_id','likes use INDEX(like_pubid,like_uid)',array('like_pubid'=>$pubID,'like_uid'=>$userID),1);
        	return !empty($user_liked) ? 'yes' : 'no' ;
        }
        else {
			$tot_likes = $this->CI->DatabaseModel->aggregate_data('likes use INDEX(like_pubid)','likes_id','COUNT',array('like_pubid'=>$pubID));
			return (int) $tot_likes;
        }
	} 
	public function like_name($pubID){
         $like_userid =$this->CI->DatabaseModel->select_data('like_uid','likes use INDEX(like_pubid)', array('like_pubid'=>$pubID),1,'',array('likes_id','DESC'));
		if(!empty($like_userid)){
			$user_name = $this->CI->DatabaseModel->select_data('user_name','users use INDEX(user_id)', array('user_id'=>$like_userid[0]['like_uid']),1);
			IF(!empty($user_name)){
				return $user_name[0]['user_name'];
			}	
		}
	}
	
	public function get_main_commets_count($pub_id=''){
		return $this->CI->DatabaseModel->aggregate_data('comments use INDEX(com_pubid,com_parentid)','com_id','COUNT',array('com_pubid'=>$pub_id,'com_parentid'=>0));
	}
	
	public function partOfString($string,$start=0,$end=500){
		$subString			=  (strlen($string)< $end)?$string:substr($string,$start,$end)."<span>...</span>";
		if(strlen($string) > $end){
			$subString 		.= '<span class="contentText more_text_content" style="display:none;">'.substr($string,$end).'</span>';
			$subString 		.= '<a class="more_text">Read More</a>';
		}
		return $subString ;
	}
	
	public function getPublishReason($res_status,$gender_type,$share_uid){
		$message = '';
		if($res_status == 1){   
			$message = 'updated '. $this->get_gender($gender_type)[1] . ' Profile picture.';
		}else 
		if($res_status == 2){ 
			$message = '<i class="fa fa-chevron-right" aria-hidden="true"></i> Shared by '.get_user_fullname($share_uid) .'.';
		}
		
		return $message;
	}
	public function get_gender($gender_type){
		if($gender_type == 1){
			$gener = array('Male','his');
		}else
		if($gender_type == 2){
			$gener = array('Female','her');
		}else{
			$gener = array('NA','their');
		}
		return $gener;
	}
	
	
	
	public function getUserProfileInfo($other_user=''){
	  
		$uid = $this->uid;
		$sigup_acc_type = '';
		
		if(!empty($other_user)){
			$otherUser = $this->CI->DatabaseModel->select_data('user_id,sigup_acc_type','users use INDEX(user_uname)',array('user_uname'=>$other_user),1);
			if(isset($otherUser[0]['user_id'])){
				$uid			=	$otherUser[0]['user_id'];
				$sigup_acc_type	=	$otherUser[0]['sigup_acc_type'];
			}
		}
		
		$data['uid'] 			= $uid;
		$data['sigup_acc_type'] = $sigup_acc_type;
		$data['is_session_uid'] = (is_session_uid($uid)) ? 1 : 0 ;
		$data['other_user'] 	= $other_user;
		$field = 'users.user_id,users.user_uname,users.user_name,artist_category.category_name,users.user_regdate,users.user_dir,users.user_status,users_content.uc_ty pe,users_content.aws_s3_profile_video,users_content.uc_type,users_content.uc_pic,users_content.uc_city,users_content.name,country.country_name,users_content.uc_about,state.name,users.referral_by,users_content.is_video_processed,users_content.is_fc_member';
		
		$accessParam = array(
			'field' => $field,
			'where' => 'user_id='.$uid . ',user_status=1,is_deleted=0',
		);
						
		$userDetail	= $this->CI->query_builder->user_list($accessParam);
		
		$data['userDetail'] = []; 
		if(isset($userDetail['users']) && !empty($userDetail['users'])){
			$userDetail = $userDetail['users'];

			if(isset($userDetail[0]['referral_by']) && !empty($userDetail[0]['referral_by'])){
				 
				 $referral_cond = array('user_uname'=>$userDetail[0]['referral_by']);
				 
				 $referral_name = $this->CI->DatabaseModel->select_data('user_name','users use INDEX(user_uname)',$referral_cond,1);
				 
				 if(isset($referral_name[0]['user_name']) && !empty($referral_name[0]['user_name'])){
					 $data['referral_name'] =	$referral_name[0]['user_name'];
					 $data['referral_by']  	=	$userDetail[0]['referral_by'];
				 }
			}
			
			$data['userDetail'] = $userDetail;
			
			$data['sub_catname'] = ''; 
			
			if(!empty($userDetail[0]['uc_type']))
			{
				$sub_cat = $this->CI->DatabaseModel->select_data('category_name','artist_category','category_id IN ('.$userDetail[0]['uc_type'].')');
				
				$size = (sizeof($sub_cat) <= 5)?sizeof($sub_cat):5;
				
				for($i=0;$i < $size; $i++ ){
					$data['sub_catname'] .=  $sub_cat[$i]['category_name'].',';
				}
				
				$data['sub_catname'] = rtrim($data['sub_catname'],", ");
			}
			
			
			
			$data['DP'] 			= 	'';
			if($other_user == 'social'){
				$this->CI->load->library('audition_functions');
				$this->CI->audition_functions->manage_my_web_mode_session('social');
				$data['cover_video'] 	=  	$this->CI->audition_functions->get_cover_video();
				$data['social'] = 1;
				$data['DP'] 			= 	!empty($userDetail[0]['uc_pic']) ? create_upic($uid, $userDetail[0]['uc_pic']) : user_default_image() ;
			}else{
				$cover 					= 	$userDetail[0]['aws_s3_profile_video'];
				$url =      $preview	=	"";
				
				if(!(empty($cover))){
					$url 				= 	AMAZON_URL .$cover;
					$preview			=	$this->CI->share_url_encryption->FilterIva($uid,'','',$cover,false,'.mp4',$userDetail[0]['is_video_processed']);
					$preview			=	isset($preview['video'])?$preview['video']:'';
				}
			
				$data['cover_video'] 	= 	array('url'=>$url,'preview'=>$preview);
				$data['DP'] 			= 	!empty($userDetail[0]['uc_pic']) ? create_upic($uid, $userDetail[0]['uc_pic']) : user_default_image() ;
				
				$data['metaData'] 		= 	array(
					'title' 		=> 	'Profile@'.$userDetail[0]['user_name'], 
					'description' 	=> 	'', 
					'image' 		=> 	$data['DP']
				);
			}
			
			$this->CI->db->cache_on();
			$defaultVideo			=	$this->CI->DatabaseModel->select_data('channel_post_video.uploaded_video','page_setting',array('website_mode'=>4),1,array('channel_post_video','channel_post_video.post_id = page_setting.default_profile_video' ));
			$data['defaultVideo'] 	=  isset($defaultVideo[0]['uploaded_video']) ?  $defaultVideo[0]['uploaded_video'] : '';
			$this->CI->db->cache_off();
		}
		
		return $data;
	}
	
	
	public function getUserFeturedVideo($uid){
		$data = [];
		if(!empty($uid)){
			$field ="channel_post_video.mode,channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type";
					
			$where = 'channel_post_video.user_id ='.$uid.' AND channel_post_thumb.active_thumb = 1 AND channel_post_video.delete_status = 0';
				
			$globalCond =  $this->CI->common->GobalPrivacyCond($uid);
			
			$where .=  $globalCond;	  
			
			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
									  'left'),
								array('users' , 'users.user_id = channel_post_video.user_id'),
							)
						);
			
			$Order = array('channel_post_video.post_id','DESC');
			
			array_push($join[1],array(	'website_mode','website_mode.mode_id = channel_post_video.mode','left'));
			
			$where_cond = ' channel_post_video.featured_by_user = 1 AND ' . $where ; 
			
			$field = 'channel_post_video.featured_by_user,channel_post_video.iva_id,channel_post_video.post_key,channel_post_video.post_id,channel_post_video.title,channel_post_video.uploaded_video';
			
			$channel_video  = $this->CI->DatabaseModel->select_data($field ,'channel_post_video',$where_cond,1,$join);
			
			
			if(!isset($channel_video[0]['post_id']))
			$channel_video  = $this->CI->DatabaseModel->select_data($field,'channel_post_video',$where,1,$join,$Order);
			
			
			if(isset($channel_video[0]['post_id']) && !empty($channel_video[0]['post_id'])){
				$vCount   				= 	sizeof($channel_video);
				$index 					= 	0;
				$post_key 				= 	$channel_video[$index]['post_key'];
				$iva_id   				= 	$channel_video[$index]['iva_id'];
				$up_video 				= 	$channel_video[$index]['uploaded_video'];
				
				$data['single_video'] 	= 	base_url().$this->CI->common->generate_single_content_url_param($post_key , 2);
				
				$data['feature_video'] 	= 	$this->CI->share_url_encryption->FilterIva($uid, $iva_id,'',$up_video,'','.m3u8');
				$data['feature_video'] 	= 	isset($data['feature_video']['video'])?str_replace("m3u8","mp4",$data['feature_video']['video']):'';
				
				$data['feature_pid']  	= 	$channel_video[$index]['post_id'];
				$data['title']    		= 	$channel_video[$index]['title'];
			}
		}
		return $data;
		
	}
	
	
	

	
}


?>
