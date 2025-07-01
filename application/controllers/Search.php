<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

	private $uid;
	public $search_data = [];
	public function __construct(){
		parent::__construct();

		$this->load->library(array('share_url_encryption','audition_functions'));
	    $this->load->helper(array('button'));

		$this->uid = is_login();
	}

	public function index(){
		$data['currentMode'] ='';
		$data['currentGenre'] ='';
		$data['daterange'] ='';
		$data['video_duration'] ='';
		$data['sort_by'] ='';

		if(isset($_GET['mode_id'])){
			$data['currentMode'] = $_GET['mode_id'];
		}
		if(isset($_GET['genre_id'])){
			$data['currentGenre'] = $_GET['genre_id'];
		}
		if(isset($_GET['daterange'])){
			$data['daterange'] = $_GET['daterange'];
		}
		if(isset($_GET['video_duration'])){
			$data['video_duration'] = $_GET['video_duration'];
		}
		if(isset($_GET['sort_by'])){
			$data['sort_by'] = $_GET['sort_by'];
		}

		if(!empty($data['currentMode'])){  
			$genre_mode = ($data['currentMode'] == 'series') ? 3 : $data['currentMode'];
			$data['genre_list'] = $this->DatabaseModel->select_data("genre_id,genre_name",'mode_of_genre',array('mode_id'=>$genre_mode,'status'=>1,'level'=>1),'','',array('genre_name','ASC'));
		}

		$data['search_query'] = (isset($_GET['search_query']))?urldecode(trim($_GET['search_query'])):'';
		$data['website_mode'] = array(
										array('mode_id'=>1,'mode'=>'music'),
										//array('mode_id'=>'series','mode'=>'series'),
										array('mode_id'=>2,'mode'=>'movies'),
										array('mode_id'=>3,'mode'=>'television'), //default single selected 
										array('mode_id'=>7,'mode'=>'gaming'),
										array('mode_id'=>9,'mode'=>'live'),
										array('mode_id'=>10,'mode'=>'articles'),
										array('mode_id'=>4,'mode'=>'social'),
									); //$this->DatabaseModel->select_data("*",'website_mode',array('search_status'=>1),'','',array('mode_order','ASC'));
		$data['page_info'] = array('page'=>'search','title'=>'Search');

		$this->load->view('home/inc/header',$data);
		$this->load->view('home/search/search',$data);
		$this->load->view('common/notofication_popup');
		$this->load->view('home/inc/footer',$data);
	}

	function search_content(){
		if(isset($_POST['search']) && !empty($_POST['search'])){
			$list = '';
			$search_result=[];

			$mode_id  = '';   /*IN CASE OF ALL*/
			$genre_id = '';   /*IN CASE OF ALL*/
			if(isset($_POST['mode_id']) && !empty($_POST['mode_id'])){
					$mode_id  = validate_input($_POST['mode_id']); /*site mode selected by user in a serch filter page*/
			}

			if(isset($_POST['genre_id']) && !empty($_POST['genre_id'])){
				$genre_id  = validate_input($_POST['genre_id']); /*site genre selected by user in a serch filter page*/
			}

			$searchKey = addslashes(validate_input($_POST['search']));
			$searchKey = str_replace("&amp;","&",$searchKey);
			$searchKey = html_entity_decode($searchKey);
			$searchKey = stripslashes($searchKey);
			// $searchKey = $this->db->escape($searchKey);
			
			if($mode_id =='series'){

				$this->search_content_series($searchKey); //Search series content

			}else if($mode_id == 10){

				$this->search_content_articles($searchKey); //Search articles content

			}else{

				$cond = "users.is_deleted = 0  AND users.user_status = 1";

				$join = array('multiple' , array(
							array(	'users_content',
									'users.user_id 	= users_content.uc_userid',
									'right'	),
						));
				// $like = ['users.user_uname,users.user_name',''.$searchKey.','.$searchKey];
				
				$this->db->group_start(); 
				$this->db->or_like('users.user_uname', $searchKey);
				$this->db->or_like('users.user_name', $searchKey);
				$this->db->group_end();

				$search_result = $this->DatabaseModel->select_data('users.user_name','users',$cond,10,$join);
				
				if(isset($search_result[0]))
					$this->AddSearcResult($search_result);

				$cond = $this->common->channelGlobalCond([1,1,7,0,NULL,1,0]) ;
				$searchKey = stripslashes($searchKey);
                // $like = ['users.user_uname,users.user_name,channel_post_video.title',''.$searchKey.','.$searchKey.','.$searchKey];
				$this->db->group_start(); 
				$this->db->or_like('users.user_uname', $searchKey);
				$this->db->or_like('users.user_name', $searchKey);
				$this->db->or_like('channel_post_video.title', $searchKey);
				$this->db->group_end();
				
				if(!empty($mode_id) && $mode_id !=9)
				$cond .= " AND mode = {$mode_id}";


				if(!empty($mode_id) && $mode_id ==9)
				$cond .= " AND channel_post_video.video_type = 2"; // For live stream only


				if(!empty($genre_id))
				$cond .= " AND channel_post_video.genre = {$genre_id}"; // For genre search

				$join = array('multiple' , array(
												array('users',
														'users.user_id 		= channel_post_video.user_id',
														'left'),
												array('users_content',
														'users.user_id 		= users_content.uc_userid',
														'right')
											)
							);

				$search_result = $this->DatabaseModel->select_data('channel_post_video.title','channel_post_video',$cond,10,$join);
				// echo $this->db->last_query();die;
				if(isset($search_result[0]))
					$this->AddSearcResult($search_result);
			}

			$search = array_values(array_unique($this->search_data));

			echo json_encode( $search );

		}else{
			echo 0;
		}

	}

	public function AddSearcResult($search_result){
			$results = [];

			foreach($search_result as $result){
				if(isset($result['title']))
				array_push($results,$result['title']);

				if(isset($result['user_name']))
				array_push($results,$result['user_name']);
			}
			$data = array_values(array_unique(array_filter($results)));

			foreach($data as $result){
				array_push($this->search_data,strtolower($result));
			}
			return true;
	}

	public function search_content_series($searchKey){

		$cond ="users.user_status = 1 AND users.is_deleted = 0 AND channel_video_playlist.privacy_status = 7 AND channel_video_playlist.playlist_type = 2 AND  (channel_video_playlist.title LIKE '%".$searchKey."%' OR users.user_name LIKE '%".$searchKey."%' OR users.user_uname LIKE '%".$searchKey."%')" ;

		$order = array('channel_video_playlist.playlist_id','DESC');

		//if(!empty($mode_id)) $cond .= " AND channel_video_playlist.mode = {$mode_id}";

		$join = array('multiple' ,
						array(

							array(	'users use INDEX(user_id)',
									'users.user_id 		= channel_video_playlist.user_id',
									'left'),
						)
					);

		$search_result = $this->DatabaseModel->select_data('channel_video_playlist.title','channel_video_playlist use INDEX(title)',$cond,'',$join,$order);
		if(isset($search_result[0]))
			$this->AddSearcResult($search_result);
	}

	public function search_content_articles($searchKey){

		$cond ="users.user_status = 1 AND users.is_deleted = 0 AND  articles.complete_status = 1 AND articles.privacy_status = 7 AND articles.active_status=1 AND articles.delete_status = 0 AND (articles.ar_title LIKE '%".$searchKey."%' OR articles.ar_tag LIKE '%".$searchKey."%' OR users.user_name LIKE '%".$searchKey."%' OR users.user_uname LIKE '%".$searchKey."%')" ;

		$order = array('articles.article_id','DESC');

		//if(!empty($mode_id)) $cond .= " AND channel_video_playlist.mode = {$mode_id}";

		$join = array('multiple' ,
						array(

							array(	'users use INDEX(user_id)',
									'users.user_id 		= articles.ar_uid',
									'left'),
						)
					);

		$search_result = $this->DatabaseModel->select_data('articles.ar_title as title, users.user_name','articles use INDEX(ar_title)',$cond,'',$join,$order);
		if(isset($search_result[0]))
			$this->AddSearcResult($search_result);
	}

	function get_my_content($searchType){
		$searchKey 	= (isset($_GET['search_query']))?$_GET['search_query']:'';
		$searchKey 	= addslashes(validate_input($searchKey));
		$searchKey 	= str_replace("&amp;","&",$searchKey);
		
		$mode_id  	= '';   /*IN CASE OF ALL*/
		$genre_id   = '';   /*IN CASE OF ALL*/
		$category_id   = '';   
		$by_user_id   = '';   

		if(isset($_GET['mode_id']) && !empty($_GET['mode_id'])){
			$mode_id  = validate_input($_GET['mode_id']); /*site mode selected by user in a serch filter page*/
		}

		if(isset($_GET['genre_id']) && !empty($_GET['genre_id'])){
			$genre_id  = validate_input($_GET['genre_id']); /*site genre selected by user in a serch filter page*/
		}

		if(isset($_GET['by_user_id']) && !empty($_GET['by_user_id'])){
			$by_user_id  = validate_input($_GET['by_user_id']); /*site genre selected by user in a serch filter page*/
		}
		
		if(isset($_GET['category_id']) && !empty($_GET['category_id'])){
			$category_id  = validate_input($_GET['category_id']); /*site genre selected by user in a serch filter page*/
		}

		$limitData 	= 12;
		$startOffset= (isset($_POST['start']))?$_POST['start']:0;

		$result = '';
		if(!empty($searchType)){
			if($searchType == 'series'){
				$result .= $this->getSearchPlaylist($searchKey); // search playlist
			}else if($searchType == 'articles'){
				$result .= $this->getSearchArticles($searchKey,$mode_id); // search articles
			}else if($searchType == 'video'){
				$searchKey = html_entity_decode($searchKey);
				$searchKey = stripslashes($searchKey);
				$field = 'channel_post_video.post_id,channel_post_video.user_id,channel_post_video.created_at,channel_post_video.title,channel_post_video.description,channel_post_video.age_restr,channel_post_thumb.image_name,channel_post_video.count_views,channel_post_video.count_votes,channel_post_video.post_key,users.user_name,users.user_uname,users.user_level,channel_post_video.iva_id,users_content.uc_pic,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration,channel_post_video.mode,users.user_level';
				// $cond = $this->common->channelGlobalCond([1,1,7,0,1,1,0]) . " AND (channel_post_video.title LIKE '%".$searchKey."%' OR users.user_name LIKE '%".$searchKey."%' )" ;
				$cond = $this->common->channelGlobalCond([1,1,7,0,NULL,1,0]);
				
				if((isset($_GET['uid']) && !empty($_GET['uid']) ) || !empty($this->uid)){
					$uid = isset($_GET['uid'])? $_GET['uid']: $this->uid ;
					if(!is_session_uid($uid)){   /* FOR OTHER USER	*/
						$AmIFanOfHim = AmIFollowingHim($uid);
						if(isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)){
							$cond .= " AND channel_post_video.privacy_status IN(6,7)"; /* PRIVATE,PUBLIC*/
						}else{
							$cond .=  ' AND channel_post_video.privacy_status = 7';/*PUBLIC*/
						}
					}
				}else{
					$cond .=  ' AND channel_post_video.privacy_status = 7';/*PUBLIC*/
				}
				
				$this->db->group_start(); 
				$this->db->or_like('users.user_uname', $searchKey);
				$this->db->or_like('users.user_name', $searchKey);
				$this->db->or_like('channel_post_video.title', $searchKey);
				$this->db->group_end();

				$order = array('channel_post_video.post_id','DESC');

				if(isset($_GET['sort_by']) && !empty($_GET['sort_by'])){

					if($_GET['sort_by']=='1'){ //Relevance

						$order = array('channel_post_video.title','ASC');

					}else if($_GET['sort_by']=='2'){ //Upload date

						$order = array('channel_post_video.created_at','DESC');

					}else if($_GET['sort_by']=='3'){ //View count

						$order = array('channel_post_video.count_views','DESC');

					}else if($_GET['sort_by']=='4'){ //Rating

						$order = array('channel_post_video.count_votes','DESC');
					}
				}

				if(!empty($mode_id) && $mode_id !=9) 
					$cond .= " AND channel_post_video.mode = {$mode_id}";

				if(!empty($mode_id) && $mode_id==9) 
					$cond .= " AND channel_post_video.video_type = 2"; //For live stream video only

				if(!empty($genre_id)) 
					$cond .= " AND channel_post_video.genre = {$genre_id}"; // For genre search

				if(!empty($category_id)) 
					$cond .= " AND users.user_level = {$category_id}"; // For category search
				
				if(!empty($by_user_id)) 
					$cond .= " AND channel_post_video.user_id = {$by_user_id}"; // For user search


				if(isset($_GET['daterange']) && !empty($_GET['daterange'])){
					$datetype = $_GET['daterange'];
					$rangeDateArray = array(
						1 => date("Y-m-d H:i:s", strtotime('-1 hour')), //last hour
						2 => date('Y-m-d'), //today
						3 => array(date('Y-m-d',strtotime("-7 days")) , date('Y-m-d')), //this week
						4 => array(date("Y-m-d", strtotime("first day of this month")) , date("Y-m-d", strtotime("last day of this month"))), //this month
						5 => array(date('Y-m-d', strtotime('first day of january this year')) , date('Y-m-d', strtotime('last day of december this year'))), // this year
					);
					$dateRange  = $rangeDateArray[$datetype];
					if(!is_array($dateRange)){
						$dateRange = array($dateRange , $dateRange);
					}
					$cond .= " AND (channel_post_video.created_at >= '".$dateRange[0]."' AND channel_post_video.created_at <= '".$dateRange[1]."') "; // For filter by daterange
				}

				if(isset($_GET['video_duration']) && !empty($_GET['video_duration'])){
					$v_duration = $_GET['video_duration'];
					$durationArr = array(
									1=>array(240), // under 4 min
									2=>array(240,1200), // 4 - 20 minutes
									3=>array(1200) // Over 20 minutes
								);
					$duration	= 	$durationArr[$v_duration];
					if($v_duration==1){
						$cond .= " AND (channel_post_video.video_duration <= '".$duration[0]."') ";
					}else if($v_duration==2){
						$cond .= " AND (channel_post_video.video_duration >= '".$duration[0]."' AND channel_post_video.video_duration <= '".$duration[1]."') ";
					}else{
						$cond .= " AND (channel_post_video.video_duration >= '".$duration[0]."') ";
					}
				}

				if(!isset($_GET['favorite'])){
					$join = array('multiple' ,
							array(
								array(	'users',
										'users.user_id 		= channel_post_video.user_id',
										'left'),
							)
					);

					$search_result = $this->DatabaseModel->select_data('post_id','channel_post_video use INDEX(title)',$cond,array($limitData+1,$startOffset),$join,$order);
					
					$search_post_id = array_column($search_result, 'post_id');
					$search_post_id = implode(',',$search_post_id);
					$search_post_id = !empty($search_post_id) ? $search_post_id : 0 ;
					$cond = "channel_post_video.post_id IN($search_post_id) AND channel_post_thumb.active_thumb = 1";
					$order_by = "FIELD(channel_post_video.post_id,$search_post_id)";
				}

				$join = array('multiple',
								array(
									array(	'users use INDEX(user_id)',
											'users.user_id 		= channel_post_video.user_id',
											'left'),
									array(	'users_content use INDEX(uc_userid)',
											'users.user_id 		= users_content.uc_userid',
											'right'),
									array(	'channel_post_thumb use INDEX(post_id)',
											'channel_post_thumb.post_id = channel_post_video.post_id',
											'left'),
								)
						);


				$favorite_limit = '';
				if(isset($_GET['favorite'])){
					$order = array('channel_favorite_video.fav_id','DESC');
					$cond = "channel_post_thumb.active_thumb = 1 AND channel_favorite_video.user_id = '".$this->uid."'";
					$field .= ",channel_favorite_video.fav_id";
					$favorite_limit = array($limitData+1,$startOffset);
					array_push($join[1],array(	'channel_favorite_video',
												'channel_favorite_video.channel_post_id = channel_post_video.post_id',
												'LEFT'));
				}

				$search_result = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$cond,$favorite_limit,$join,$order_by);

				if(isset($search_result[0])){
					$All_my_fav = [];
					if(!empty($this->uid)){
						$all_post_id = array_column($search_result, 'post_id');
						$all_post_id = implode(',',$all_post_id);

						if(!empty($all_post_id)){
							$fav_cond = "user_id = $this->uid AND channel_post_id IN($all_post_id)";
							$All_my_fav = $this->DatabaseModel->select_data('channel_post_id','channel_favorite_video use INDEX(channel_post_id,user_id)',$fav_cond);
							$All_my_fav = array_column($All_my_fav, 'channel_post_id');
						}
					}

					$checkChanelCount 	= 	1;
					$isMyFavorite		=	0;
					$ages 				= 	$this->audition_functions->age();

					$this->load->library('Valuelist');
					$web_mode_arr 	= $this->valuelist->mode();
					$user_level 	= $this->valuelist->level();

					foreach($search_result as $channel){
						if($checkChanelCount++ <= $limitData){

							if(strlen($channel['description']) < 250){
								$description = $channel['description'];
							}else{
								$description = substr($channel['description'],0,250)."..." ;
							}

							$description=strip_tags($description);

							if(!empty($this->uid)){
								$isMyFavorite = in_array($channel['post_id'],$All_my_fav) ? 1 : 0 ;
							}

							$FavoriteActive = 	($isMyFavorite == 1)?'active':'';
							$isMyFavoriteText = ($isMyFavorite == 1)?'Added To favorites':'Add To favorites';

							$FilterData = $this->share_url_encryption->FilterIva($channel['user_id'],$channel['iva_id'],$channel['image_name'],$channel['uploaded_video'],true,'.m3u8',$channel['is_video_processed']);
							$img = $FilterData['thumb'];
							$webp 	= 	isset($FilterData['webp'])?$FilterData['webp']:$img;

							$previewFile='';
							if(isset($FilterData['video'])){
								$videoFile 	 = $FilterData['video'];
								$previewFile = $this->share_url_encryption->getPreviewFile($videoFile,$channel['video_type']);
							}

							$is_session_uid = 0;
							if(!empty($this->uid))
							{
								$is_session_uid = (is_session_uid($channel['user_id']))?1:0;
							}

							$age_restr = isset($ages[$channel['age_restr']]) ? $ages[$channel['age_restr']] : $channel['age_restr'];

							$dur_section = $channel['video_duration'] != 0 ? '<span class="dis_videotime">'. gmdate("H:i:s", $channel['video_duration']).'</span>' : '';

							$web_mode  = isset($web_mode_arr[$channel['mode']])? $web_mode_arr[$channel['mode']] : '';

							$user_cate = isset($user_level[$channel['user_level']])? $user_level[$channel['user_level']] : '';

							$edit = $is_session_uid ? '<li>
								<div class="dis_sld_preview" onclick="redirect(\'monetize/'. $channel['post_id'] .'\',10)" bis_skin_checked="1">
									<span class="preview_txt">Edit</span>
									<span class="pre_icon">
										<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M9.68872 1.21677L8.78255 0.310524C8.36849 -0.103518 7.69486 -0.103498 7.28083 0.310524L6.92615 0.665231L9.33403 3.07331L9.68872 2.71861C10.1037 2.30359 10.1038 1.63183 9.68872 1.21677Z" fill="white"></path>
										<path d="M0.429919 7.35832L0.00490067 9.65365C-0.0126579 9.74851 0.0175764 9.84595 0.085799 9.91418C0.1541 9.98248 0.25156 10.0127 0.346306 9.99509L2.64146 9.57004L0.429919 7.35832Z" fill="white"></path>
										<path d="M6.51185 1.07957L0.746063 6.84581L3.15395 9.25388L8.91974 3.48766L6.51185 1.07957Z" fill="white"></path>
										</svg>
									</span>
								</div>
							</li>' : '';

							$result .='<div class="s_v_i_box">
										<div class="search_video_box dis_user_post_data" data-post_delete_id="'.$channel['post_id'].'">
											<div class="dis_user_post_header">
												<div class="dis_headerDetails">
													<div class="dis_user_img">
														<img src="'.create_upic($channel['user_id'],$channel['uc_pic']).'" alt="" onError="this.onerror=null;this.src=\''.user_default_image().'\';">
													</div>

													<div class="dis_user_detail">
														<h3><a href="'.base_url('channel?user='.$channel['user_uname']).'">'.$channel['user_name'].'</a>, <br><p>'.$user_cate.'</p></h3>
														<!--p class="published_date">published'.' '. time_elapsed_string($this->common->manageTimezone($channel['created_at']) ,false) .'</p-->
													</div>
												</div>
												<div class="dis_headerBtn">
														<ul class="dis_btn_grp">
															<li><a  class="dis_fanbtn  dis_bgclr_yellow">'.$web_mode.'</a></li>
															<!--li><a class="dis_fanbtn">'.$age_restr.'</a></li-->
															<li class="sp_action_wrp">
																<div class="dis_actiondiv">
																	<span>
																		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18px" height="35px"><g><g>
																			<g>
																				<g>
																					<circle cx="256" cy="256" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																					<circle cx="256" cy="448" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																					<circle cx="256" cy="64" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																				</g>
																			</g>
																		</g></g> </svg>
																	</span>
																	<div class="dis_action_content">
																		<ul>';
																			if(is_login()){
																				$result .='<li><a class="AddToFavriote '.$FavoriteActive.'" data-post_id="'.$channel['post_id'].'"><span>'.$isMyFavoriteText.'<span></a></li>';
																			}else{
																				$result .='<li><a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl"><span>'.$isMyFavoriteText.'<span></a></li>';
																			}

																			$result	.='<li><a  class="dtvShareMe common_click" data-share="2|'.$channel['post_id'].' ">Share</a></li>
																		</ul>
																	</div>
																</div>
															</li>
														</ul>
													</div>
											</div>


											<div class="search_video_content play_preview_common" data-preview-src="'.$previewFile.'">
														<div class="search_video_thumb dis_cardS_oplistWrap">
															<a href="'.base_url().$this->common->generate_single_content_url_param($channel['post_key'] , 2).'">
																<img src="'.$webp.'" class="image-fluid" alt="Discovered" onError="ImageOnLoadError(this,\''.$img.'\',\''.thumb_default_image().'\')">
																<div class="dis_previewvideo">
																	<video autoplay muted loop playsinline preload="metadata" width="100%" height="100%">
																		<source src="" type="video/mp4">
																	</video>
																</div>
																<div class="search_overlay">

																</div>
																'. $dur_section .'
															</a>

															<ul class="dis_cardS_oplist">
																<li>
																	<div class="dis_sld_preview openModalPopup" data-href="modal/video_popup/'.$channel['post_id'].'" data-cls="dis_custom_video_popup">
																		<span class="preview_txt">Preview</span>
																		<span class="pre_icon">
																			<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
																			<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
																			</svg>
																		</span>
																	</div>
																</li>
																'. $edit .'
															</ul>
														</div>
														<div class="search_video_data">
															<a href="'.base_url().$this->common->generate_single_content_url_param($channel['post_key'] , 2).'" class="searh_video_title">'.$channel['title'].'</a>
															<!--h5>'.$channel['count_views'].' Views / '. $channel['count_votes'].' Votes</h5-->
															<p>'.$description.'</p>

															<!--a href="#" class="vote_btn"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 471.701 471.701"><path d="M433.601,67.001c-24.7-24.7-57.4-38.2-92.3-38.2s-67.7,13.6-92.4,38.3l-12.9,12.9l-13.1-13.1 c-24.7-24.7-57.6-38.4-92.5-38.4c-34.8,0-67.6,13.6-92.2,38.2c-24.7,24.7-38.3,57.5-38.2,92.4c0,34.9,13.7,67.6,38.4,92.3 l187.8,187.8c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-3.9l188.2-187.5c24.7-24.7,38.3-57.5,38.3-92.4 C471.801,124.501,458.301,91.701,433.601,67.001z M414.401,232.701l-178.7,178l-178.3-178.3c-19.6-19.6-30.4-45.6-30.4-73.3 s10.7-53.7,30.3-73.2c19.5-19.5,45.5-30.3,73.1-30.3c27.7,0,53.8,10.8,73.4,30.4l22.6,22.6c5.3,5.3,13.8,5.3,19.1,0l22.4-22.4 c19.6-19.6,45.7-30.4,73.3-30.4c27.6,0,53.6,10.8,73.2,30.3c19.6,19.6,30.3,45.6,30.3,73.3 C444.801,187.101,434.001,213.101,414.401,232.701z"/></svg>
															'.$channel['count_votes'].' Votes</a-->

														</div>
											</div>
										</div>
										</div>';
						}

					}

					if(sizeof($search_result) > $limitData){
						$result .= '<div class="col-xs-12">
							<div class="profile_load_more text-center hide">
								<a href="javascript:;" data-action="loadMoreContent" data-offset="'.($startOffset+$limitData).'" class="dis_btn">Load More</a>
							</div>
						</div>';
					}else{
						$result .='<div class="col-xs-12">
										<div class="profile_load_more text-center">
											<div class="dis_loadmore_loader hideme">-- No more data available --</div>
										</div>
									</div>';
					}
				}else{
					$result .= '<div class="col-xs-12">
									<div class="profile_load_more text-center">
										 '.$this->common_html->content_not_available_html().'
									 </div>
								 </div>';
				}


			}else if($searchType == 'people'){
				$limitData = 11;
				$accessParam = array(
							'field' => '*',
							'where' => 'keyword='.$searchKey.',not_user_uname=1,user_status=1,is_deleted=0',
							'order' => 'users.user_id,DESC',
							'limit' => $limitData+1 .','.$startOffset,
							'user_content_table_join_type'=>'right'
							);



				if(isset($_GET['cat']) && !empty($_GET['cat'])){   // from header browse icon menu

					$category_slug = isset($_GET['cat']) ? $_GET['cat'] :'';

					$list 	= $this->DatabaseModel->select_data('category_id,category_name','artist_category',array('category_slug'=>$category_slug),1);

					$subcate_id = $list[0]['category_id'];

					if(isset($list[0]['category_id']) && !empty($list[0]['category_id'])){
						$accessParam['where'] .=',uc_type='.$subcate_id.',not_user_level=4';
					}
				}

				if(isset($_GET['referral_by'])){
					$accessParam['where'] = 'referral_by='.validate_input($_GET['referral_by']);
				}

				$profile_result = $this->query_builder->user_list($accessParam);

				if(isset($profile_result['users'][0])){
					$checkChanelCount = 1;

					foreach($profile_result['users'] as $users){
						if($checkChanelCount++ <= $limitData){
							$button="";
							if($this->session->userdata('user_login_id')!=$users['user_id']){
								if(!empty(FanButton($users['user_id']))){
									$button = FanButton($users['user_id']);
									$button = isset($button['old']) ? $button['old'] : '';
								}else{
									$button = '<a href="'.base_url('profile?user='.$users['user_uname']).'" class="dis_btn">Visit Profile</a>';
								}
							}


							$category_name 	= !empty($users['category_name'])?$users['category_name'] . ', ':'';
							$uc_city 		= !empty($users['uc_city'])?$users['uc_city'] . ', ':'';
							$name 			= !empty($users['name'])?$users['name'] . ', ':'';/*state name*/
							$country_name 	= !empty($users['country_name'])?$users['country_name'] . ', ' :'';
							$referral_from 	= !empty($users['referral_from'])?$users['referral_from'] :'';

							$result .= '<div class="profile_box text-center">
											<a href="'.base_url('profile?user='.$users['user_uname']).'" class="prof_img">
												<img class="img-reponsive" src="'.create_upic($users['user_id'],$users['uc_pic']).'" title="'.$users['user_name'].'" alt="'.$users['user_name'].'"/ onError="this.onerror=null;this.src=\''.user_default_image().'\';">

											<h3>'.$users['user_name'].'</h3>
											</a>
											<p>'. $category_name . $uc_city .$name .$country_name .'</p>

											<!--span>'.date('F-d-Y',strtotime($users['user_regdate'])).'</span-->
											<div class="text-center">
												'.$button.'
											</div>
													'.$this->setSvgIcon($referral_from).'
										</div>';
						}

					}

					if(sizeof($profile_result['users']) > $limitData){
						$result .= '<div class="col-xs-12">
										<div class="profile_load_more text-center hide">
											<a href="javascript:;" data-action="loadMoreContent" data-offset="'.($startOffset+$limitData).'" class="dis_btn">Load More</a>
										</div>
									</div>';
					}else{
						$result .='<div class="col-xs-12">
										<div class="profile_load_more text-center">
											<div class="dis_loadmore_loader hideme">-- No more data available --</div>
										</div>
									</div>';
					}
				}else{
					if(isset($_GET['referral_by']) && isset($_SESSION['user_uname'])){
						$user_uname = $_SESSION['user_uname'];
						$result .= '<div class="col-xs-12">
										<div class="text-center">
											'.$this->common_html->content_not_available_html_invitelink().'
											<a href="javascript:;" class="dis_btn shareInviteLink common_click" data-user_uname="'.$user_uname.'">Your Invite Link</a>
										</div>
									</div>';

					}else{
						$result .= '<div class="col-xs-12">
									<div class="profile_load_more text-center">
										 '.$this->common_html->content_not_available_html().'
									 </div>
								 </div>';
					}
				}
			}
		}

		echo $result;

	}

	public function getSearchPlaylist($searchKey='')
	{
		$result = '';
		//if(!empty($searchKey)){

			$limitData 	= 12;
			$startOffset= (isset($_POST['start']))?$_POST['start']:0;

			$cond ="users.user_status = 1 AND users.is_deleted = 0 AND channel_video_playlist.privacy_status = 7 AND channel_video_playlist.playlist_type = 2 AND  (channel_video_playlist.title LIKE '%".$searchKey."%' OR users.user_name LIKE '%".$searchKey."%' OR users.user_uname LIKE '%".$searchKey."%') AND channel_video_playlist.video_ids IS NOT NULL AND channel_video_playlist.video_ids !=''" ;

			if(isset($_GET['genre_id']) && !empty($_GET['genre_id'])){
				$genre_id  = validate_input($_GET['genre_id']); /*site genre selected by user in a serch filter page*/
				$cond .= " AND channel_post_video.genre = {$genre_id}"; // For genre search
			}

			if(isset($_GET['by_user_id']) && !empty($_GET['by_user_id'])){
				$by_user_id  = validate_input($_GET['by_user_id']); 
				$cond .= " AND channel_video_playlist.user_id = {$by_user_id}"; // For user search
			}
			
			if(isset($_GET['category_id']) && !empty($_GET['category_id'])){
				$category_id  = validate_input($_GET['category_id']); 
				$cond .= " AND users.user_level = {$category_id}"; // For category search
			}
	
			
			$order = array('channel_video_playlist.playlist_id','DESC');

			//if(!empty($mode_id)) $cond .= " AND channel_video_playlist.mode = {$mode_id}";

			$join = array('multiple' ,
				array(
					array(	'users use INDEX(user_id)',
							'users.user_id 		= channel_video_playlist.user_id',
							'left'),
					array(	'users_content use INDEX(uc_userid)',
							'users.user_id 		= users_content.uc_userid',
							'right'),
					array(	'channel_post_video use INDEX(post_id)','channel_post_video.post_id = channel_video_playlist.first_video_id',
							'left'),
					array(	'channel_post_thumb use INDEX(post_id)',
							'channel_post_thumb.post_id = channel_video_playlist.first_video_id AND channel_post_thumb.active_thumb =1',
							'left'),
				)
			);

			$field = "channel_video_playlist.user_id as p_uid,channel_video_playlist.playlist_id,channel_video_playlist.video_ids,channel_video_playlist.title,channel_video_playlist.first_video_id,channel_video_playlist.playlist_thumb,channel_post_video.mode,channel_post_thumb.image_name,users.user_uname,users.user_name,users.user_level,channel_post_video.age_restr,channel_post_video.created_at,users_content.uc_pic,channel_post_video.video_duration,channel_post_video.user_id";
			
			$search_result = $this->DatabaseModel->select_data($field,'channel_video_playlist use INDEX(title)',$cond,array($limitData+1,$startOffset),$join,$order);

			if(!empty($search_result)){
				$ages 				= 	$this->audition_functions->age();

				$this->load->library('Valuelist');
				$web_mode_arr 	= $this->valuelist->mode();
				$user_level 	= $this->valuelist->level();
				$checkPlaylistCount = 1;
				foreach($search_result as $list){
					if($checkPlaylistCount++ <= $limitData){
						$video_ids_count = sizeof(explode('|',$list['video_ids'])) - 1;

						if($video_ids_count>0){

							$image_uid  = $list['user_id'];
							$image_name = $list['image_name'];
							if($list['playlist_thumb'] !=''){
								$image_uid  = $list['p_uid'];
								$image_name = $list['playlist_thumb'];
							}
							$FilterData = $this->share_url_encryption->FilterIva($image_uid,0,$image_name,'',true);
							$img 		= $FilterData['thumb'];
							$webp 		= isset($FilterData['webp'])?$FilterData['webp']:$img;
							$errimg		= thumb_default_image();
							$href 		= $this->share_url_encryption->share_single_page_link_creator(2 .'|'.$list['first_video_id'],'encode','',array('list'=> $list['playlist_id']));
							$edithref 	= base_url('playlist/').$list['playlist_id'];

							$age_restr = isset($ages[$list['age_restr']]) ? $ages[$list['age_restr']] : $list['age_restr'];

							if(strlen($list['title']) < 20){
								$title =  $list['title'] ;
							}else{
								$title = substr($list['title'],0,20)."..." ;
							}
							$FavoriteActive = $previewFile = $edit  = $dur_section = '';

							$isMyFavoriteText  = 'Add To favorites';

							$web_mode  = isset($web_mode_arr[$list['mode']])? $web_mode_arr[$list['mode']] : '';

							$user_cate = isset($user_level[$list['user_level']])? $user_level[$list['user_level']] : '';

							$is_session_uid = 0;
							if(!empty($this->uid))
							{
								$is_session_uid = (is_session_uid($list['p_uid']))?1:0;
							}

							$age_restr = isset($ages[$list['age_restr']]) ? $ages[$list['age_restr']] : $list['age_restr'];

							$dur_section = $list['video_duration'] != 0 ? '<span class="dis_videotime">'. gmdate("H:i:s", $list['video_duration']).'</span>' : '';
							$video_count = ($video_ids_count > 1) ? '<span class="dis_videotime">'.$video_ids_count.' Videos </span>' : '<span class="dis_videotime">'.$video_ids_count.' Video </span>';

							$edit = $is_session_uid ? '<li>
								<div class="dis_sld_preview" bis_skin_checked="1">
								<a href="'.$edithref.'"><span class="preview_txt">Edit</span>
									<span class="pre_icon">
										<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M9.68872 1.21677L8.78255 0.310524C8.36849 -0.103518 7.69486 -0.103498 7.28083 0.310524L6.92615 0.665231L9.33403 3.07331L9.68872 2.71861C10.1037 2.30359 10.1038 1.63183 9.68872 1.21677Z" fill="white"></path>
										<path d="M0.429919 7.35832L0.00490067 9.65365C-0.0126579 9.74851 0.0175764 9.84595 0.085799 9.91418C0.1541 9.98248 0.25156 10.0127 0.346306 9.99509L2.64146 9.57004L0.429919 7.35832Z" fill="white"></path>
										<path d="M6.51185 1.07957L0.746063 6.84581L3.15395 9.25388L8.91974 3.48766L6.51185 1.07957Z" fill="white"></path>
										</svg>
									</span></a>
								</div>
							</li>' : '';

							$result .='<div class="s_v_i_box">
											<div class="search_video_box dis_user_post_data" data-post_delete_id="'.$list['playlist_id'].'">
												<div class="dis_user_post_header">
													<div class="dis_headerDetails">
														<div class="dis_user_img">
															<img src="'.create_upic($list['p_uid'],$list['uc_pic']).'" alt="" onError="this.onerror=null;this.src=\''.user_default_image().'\';">
														</div>
														<div class="dis_user_detail">
															<h3><a href="'.base_url('channel?user='.$list['user_uname']).'">'.$list['user_name'].'</a>, <br><p>'.$user_cate.'</p></h3>
															<!--p class="published_date">published'.' '. time_elapsed_string($this->common->manageTimezone($list['created_at']) ,false) .'</p-->
														</div>
													</div>
													<div class="dis_headerBtn">
														<ul class="dis_btn_grp">
															<li><a  class="dis_fanbtn  dis_bgclr_yellow">'.$web_mode.'</a></li>
															<!--li><a class="dis_fanbtn">'.$age_restr.'</a></li-->
															<li class="sp_action_wrp">
																<div class="dis_actiondiv">
																	<span>
																		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18px" height="35px"><g><g>
																			<g>
																				<g>
																					<circle cx="256" cy="256" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																					<circle cx="256" cy="448" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																					<circle cx="256" cy="64" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																				</g>
																			</g>
																		</g></g> </svg>
																	</span>
																	<div class="dis_action_content">
																		<ul>
																			<!--li><a class="AddToFavriote '.$FavoriteActive.'" data-post_id="'.$list['playlist_id'].'"><span>'.$isMyFavoriteText.'<span></a></li-->
																			<li><a href="javascript:;"  class="dtvShareMe common_click" data-share="2|'.$list['first_video_id'].'|'.$list['playlist_id'].'">Share</a></li>
																		</ul>
																	</div>
																</div>
															</li>
														</ul>
													</div>
												</div>
												<div class="search_video_content play_preview_common" data-preview-src="'.$previewFile.'">
													<div class="search_video_thumb dis_cardS_oplistWrap">
														<a href="'.$href.'">
															<img src="'.$webp.'" class="image-fluid" alt="Discovered" onError="ImageOnLoadError(this,\''.$img.'\',\''.thumb_default_image().'\')">
															<!--div class="dis_previewvideo">
																<video autoplay muted loop playsinline preload="metadata" width="100%" height="100%">
																	<source src="" type="video/mp4">
																</video>
															</div-->
															<div class="search_overlay">
																<div class="dis_overlay_inner">
																	<a href="'.$href.'" class="dis_play_icon">
																		<img src="'.base_url().'repo/images/playlist_icon.png" alt="" class="img-responsive ">
																	</a>
																</div>
															</div>
															'. $video_count .'
														</a>
														<ul class="dis_cardS_oplist">
															<!--li>
																<div class="dis_sld_preview openModalPopup" data-href="modal/video_popup/'.$list['playlist_id'].'" data-cls="dis_custom_video_popup">
																	<span class="preview_txt">Preview</span>
																	<span class="pre_icon">
																		<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
																		<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
																		</svg>
																	</span>
																</div>
															</li-->
															'. $edit .'
														</ul>
													</div>
													<div class="search_video_data">
														<a href="'.$href.'" class="searh_video_title">'.$list['title'].'</a>
														<!--h5>'.$list['count_views'] =''.' Views / '. $list['count_votes']=''.' Votes</h5-->
														<p>'.$description=''.'</p>

														<!--a href="#" class="vote_btn"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 471.701 471.701"><path d="M433.601,67.001c-24.7-24.7-57.4-38.2-92.3-38.2s-67.7,13.6-92.4,38.3l-12.9,12.9l-13.1-13.1 c-24.7-24.7-57.6-38.4-92.5-38.4c-34.8,0-67.6,13.6-92.2,38.2c-24.7,24.7-38.3,57.5-38.2,92.4c0,34.9,13.7,67.6,38.4,92.3 l187.8,187.8c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-3.9l188.2-187.5c24.7-24.7,38.3-57.5,38.3-92.4 C471.801,124.501,458.301,91.701,433.601,67.001z M414.401,232.701l-178.7,178l-178.3-178.3c-19.6-19.6-30.4-45.6-30.4-73.3 s10.7-53.7,30.3-73.2c19.5-19.5,45.5-30.3,73.1-30.3c27.7,0,53.8,10.8,73.4,30.4l22.6,22.6c5.3,5.3,13.8,5.3,19.1,0l22.4-22.4 c19.6-19.6,45.7-30.4,73.3-30.4c27.6,0,53.6,10.8,73.2,30.3c19.6,19.6,30.3,45.6,30.3,73.3 C444.801,187.101,434.001,213.101,414.401,232.701z"/></svg>
														'.$list['count_votes']=''.' Votes</a-->
													</div>
												</div>
											</div>
										</div>';
						}
					}
				}

				if(sizeof($search_result) > $limitData){
					$result .= '<div class="col-xs-12">
						<div class="profile_load_more text-center hide">
							<a href="javascript:;" data-action="loadMoreContent" data-offset="'.($startOffset+$limitData).'" class="dis_btn">Load More</a>
						</div>
					</div>';
				}else{
					$result .='<div class="col-xs-12">
									<div class="profile_load_more text-center">
										<div class="dis_loadmore_loader hideme">-- No more data available --</div>
									</div>
								</div>';
				}
			}else{
				$result .= '<div class="col-xs-12">
								<div class="profile_load_more text-center">
									 '.$this->common_html->content_not_available_html().'
								 </div>
							 </div>';
			}


		//}
		echo $result;
	}

	public function getSearchArticles($searchKey='',$mode_id)
	{
		$result = '';

		$limitData 	= 12;

		$startOffset= (isset($_POST['start']))?$_POST['start']:0;

		$cond ="users.user_status = 1 AND users.is_deleted = 0 AND  articles.complete_status = 1 AND articles.privacy_status = 7 AND articles.active_status=1 AND articles.delete_status = 0 AND (articles.ar_title LIKE '%".$searchKey."%' OR articles.ar_tag LIKE '%".$searchKey."%' OR users.user_name LIKE '%".$searchKey."%' OR users.user_uname LIKE '%".$searchKey."%') AND articles_content.order_ = 0" ;

		$order = array('articles.article_id','DESC');

		$join = array('multiple',
						array(
							array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
							array(	'users use INDEX(user_id)',
									'users.user_id 		= articles.ar_uid',
									'left'),
							/*array(	'users_content use INDEX(uc_userid)',
									'users.user_id 		= users_content.uc_userid',
									'right'),*/
							array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
						)
					);

		$field 	= 'articles.*,articles.ar_title as title,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,users.user_name,users.user_level,article_categories.cat_name';

		$search_result = $this->DatabaseModel->select_data($field,'articles use INDEX(ar_title)',$cond,array($limitData+1,$startOffset),$join,$order);
		//print_R($search_result);die;
		if(!empty($search_result)){

			foreach($search_result as $list){

				$cat_name   = $list['cat_name'];

				$image_name = $list['content'];

				$imgData 	= explode('.',$list['content']);

				$img = $webp = $errimg	= base_url() .'repo/images/blog_pp.png';

				if($list['content_type'] == 'image'){
					$img 	= AMAZON_URL.$image_name;

					$webp 	= AMAZON_URL.trim($imgData[0]).'_thumb.webp';
				}

				$href 		= base_url('article/').$this->share_url_encryption->share_single_article_link_creator($list['article_id'] , 'encode').'/'.$list['ar_slug'].'';

				if(strlen($list['title']) < 20){
					$title =  $list['title'] ;
				}else{
					$title = substr($list['title'],0,80)."..." ;
				}

				$result .='<li>
							<div class="dis_articleBox">
								<a href="'.$href.'" class="dis_articleImg">
									<img src="'.$webp.'" alt="Discovered" onError="ImageOnLoadError(this,\''.$img.'\',\''.$errimg.'\')">
								</a>
								<div class="dis_articleDetails">
									<a href="'.base_url('article').'?category='.$cat_name.'" class="dis_articleCat">'.$cat_name.'</a><br>
									<h1 class="dis_articleT"><a href="'.$href.'" class="dis_articleTtl">'.$title.'</a></h1>
									<div class="dis_articleTtlMeta"> <span class="dis_articleTtlAuthor"> <svg class="dis_articleTtlAuthorI" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 20.75"><path d="M17.92 13.08a10.38 10.38 0 00-3.17-2.18 6.278 6.278 0 002.02-4.62c0-3.46-2.82-6.28-6.28-6.28S4.22 2.82 4.22 6.28c0 1.76.74 3.43 2.02 4.62a10.38 10.38 0 00-3.17 2.18c-1.98 1.98-3.08 4.87-3.08 7.67h2.57c0-4.38 3.56-8.19 7.94-8.19s7.94 3.81 7.94 8.19H21c0-2.8-1.09-5.69-3.08-7.67zm-3.71-6.79c0 2.05-1.67 3.72-3.72 3.72S6.77 8.34 6.77 6.29s1.67-3.72 3.72-3.72 3.72 1.67 3.72 3.72z"></path></svg>'.$list['user_name'].'</span> <!-- <span class="dis_articleTtlDate">7 months ago</span> --> </div>
								</div>
							</div>
						</li>';

			}

			if(sizeof($search_result) > $limitData){
				$result .= '<div class="col-xs-12">
					<div class="profile_load_more text-center hide">
						<a href="javascript:;" data-action="loadMoreContent" data-offset="'.($startOffset+$limitData).'" class="dis_btn">Load More</a>
					</div>
				</div>';
			}else{
				/*$result .='<div class="col-xs-12">
								<div class="profile_load_more text-center">
									<div class="dis_loadmore_loader">-- No more data available --</div>
								</div>
							</div>';*/
			}
		}else{
			$result .= '<div class="col-xs-12">
							<div class="profile_load_more text-center">
									'.$this->common_html->content_not_available_html().'
								</div>
							</div>';
		}



		echo $result;
	}



	public function getSearchArticlesOLD($searchKey='',$mode_id)
	{
		$result = '';
		//if(!empty($searchKey)){

			$limitData 	= 12;
			$startOffset= (isset($_POST['start']))?$_POST['start']:0;

			$cond ="users.user_status = 1 AND users.is_deleted = 0 AND  articles.complete_status = 1 AND articles.privacy_status = 7 AND articles.active_status=1 AND articles.delete_status = 0 AND (articles.ar_title LIKE '%".$searchKey."%' OR articles.ar_tag LIKE '%".$searchKey."%' OR users.user_name LIKE '%".$searchKey."%' OR users.user_uname LIKE '%".$searchKey."%') AND articles_content.order_ = 0" ;

			$order = array('articles.article_id','DESC');

			$join = array('multiple' ,
							array(
								array('articles_content' , 'articles_content.article_id = articles.article_id','left'),
								array(	'users use INDEX(user_id)',
										'users.user_id 		= articles.ar_uid',
										'left'),
								array(	'users_content use INDEX(uc_userid)',
										'users.user_id 		= users_content.uc_userid',
										'right'),
								array('article_categories', 'article_categories.id = articles.ar_category_id','left'),
							)
						);

			$field 			= 'articles.*,articles.ar_title as title,articles.ar_date_created,articles_content.content_type,articles_content.content,articles_content.plain_content,users.user_name,users.user_level,users_content.uc_pic,article_categories.cat_name';

			$search_result = $this->DatabaseModel->select_data($field,'articles use INDEX(ar_title)',$cond,array($limitData+1,$startOffset),$join,$order);
			//print_R($search_result);die;
			if(!empty($search_result)){

				$this->load->library('Valuelist');
				$web_mode_arr 	= $this->valuelist->mode();
				$user_level 	= $this->valuelist->level();
				$web_mode  		= isset($web_mode_arr[$mode_id])? $web_mode_arr[$mode_id] : '';
				foreach($search_result as $list){

						$image_uid  = $list['ar_uid'];

						$image_name = $list['content'];

						$imgData 	= explode('.',$list['content']);

						$img = $webp = $errimg	= base_url() .'repo/images/blog_pp.png';

						if($list['content_type'] == 'image'){
							$img 	= AMAZON_URL.$image_name;

							$webp 	= AMAZON_URL.trim($imgData[0]).'_thumb.webp';
						}

						$href 		= base_url('article/').$this->share_url_encryption->share_single_article_link_creator($list['article_id'] , 'encode').'/'.$list['ar_slug'].'';

						$edithref 	= base_url('playlist/').$list['playlist_id'];

						if(strlen($list['title']) < 20){
							$title =  $list['title'] ;
						}else{
							$title = substr($list['title'],0,20)."..." ;
						}
						$FavoriteActive = $previewFile = $edit  = $dur_section =  $video_count = '';

						$isMyFavoriteText  = 'Add To favorites';

						$user_cate = isset($user_level[$list['user_level']])? $user_level[$list['user_level']] : '';

						$is_session_uid = 0;
						if(!empty($this->uid))
						{
							$is_session_uid = (is_session_uid($list['ar_uid']))?1:0;
						}

						$age_restr = '';


						$edit = $is_session_uid ? '<li>
							<div class="dis_sld_preview" bis_skin_checked="1">
							<a href="'.$edithref.'"><span class="preview_txt">Edit</span>
								<span class="pre_icon">
									<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M9.68872 1.21677L8.78255 0.310524C8.36849 -0.103518 7.69486 -0.103498 7.28083 0.310524L6.92615 0.665231L9.33403 3.07331L9.68872 2.71861C10.1037 2.30359 10.1038 1.63183 9.68872 1.21677Z" fill="white"></path>
									<path d="M0.429919 7.35832L0.00490067 9.65365C-0.0126579 9.74851 0.0175764 9.84595 0.085799 9.91418C0.1541 9.98248 0.25156 10.0127 0.346306 9.99509L2.64146 9.57004L0.429919 7.35832Z" fill="white"></path>
									<path d="M6.51185 1.07957L0.746063 6.84581L3.15395 9.25388L8.91974 3.48766L6.51185 1.07957Z" fill="white"></path>
									</svg>
								</span></a>
							</div>
						</li>' : '';

						$result .='<div class="s_v_i_box">
										<div class="search_video_box dis_user_post_data" data-post_delete_id="'.$list['article_id'].'">
											<div class="dis_user_post_header">
												<div class="dis_headerDetails">
													<div class="dis_user_img">
														<img src="'.create_upic($list['ar_uid'],$list['uc_pic']).'" alt="" onError="this.onerror=null;this.src=\''.user_default_image().'\';">
													</div>
													<div class="dis_user_detail">
														<h3><a href="'.base_url('channel?user='.$list['user_uname']).'">'.$list['user_name'].'</a>, <br><p>'.$user_cate.'</p></h3>
														<!--p class="published_date">published'.' '. time_elapsed_string($this->common->manageTimezone($list['ar_date_created']) ,false) .'</p-->
													</div>
												</div>
												<div class="dis_headerBtn">
													<ul class="dis_btn_grp">
														<li><a  class="dis_fanbtn  dis_bgclr_yellow">'.$web_mode.'</a></li>
														<!--li><a class="dis_fanbtn">'.$age_restr.'</a></li-->
														<li class="sp_action_wrp hideme">
															<div class="dis_actiondiv">
																<span>
																	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18px" height="35px"><g><g>
																		<g>
																			<g>
																				<circle cx="256" cy="256" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																				<circle cx="256" cy="448" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																				<circle cx="256" cy="64" r="64" data-original="#000000" class="active-path" data-old_color="#000000" fill="#777777"/>
																			</g>
																		</g>
																	</g></g> </svg>
																</span>
																<div class="dis_action_content">
																	<ul>
																		<!--li><a class="AddToFavriote '.$FavoriteActive.'" data-post_id="'.$list['article_id'].'"><span>'.$isMyFavoriteText.'<span></a></li-->
																		<li><a href="javascript:;"  class="dtvShareMe common_click" data-share="2|'.$list['first_video_id'].'|'.$list['article_id'].'">Share</a></li>
																	</ul>
																</div>
															</div>
														</li>
													</ul>
												</div>
											</div>
											<div class="search_video_content play_preview_common" data-preview-src="'.$previewFile.'">
												<div class="search_video_thumb dis_cardS_oplistWrap">
													<a href="'.$href.'">
														<img src="'.$webp.'" class="image-fluid" alt="Discovered" onError="ImageOnLoadError(this,\''.$img.'\',\''.$errimg.'\')">
														<!--div class="dis_previewvideo">
															<video autoplay muted loop playsinline preload="metadata" width="100%" height="100%">
																<source src="" type="video/mp4">
															</video>
														</div-->
														<!--div class="search_overlay">
															<div class="dis_overlay_inner">
																<a href="'.$href.'" class="dis_play_icon">
																	<img src="'.base_url().'repo/images/playlist_icon.png" alt="" class="img-responsive ">
																</a>
															</div>
														</div-->
														'. $video_count .'
													</a>
													<ul class="dis_cardS_oplist">
														<!--li>
															<div class="dis_sld_preview openModalPopup" data-href="modal/video_popup/'.$list['playlist_id'].'" data-cls="dis_custom_video_popup">
																<span class="preview_txt">Preview</span>
																<span class="pre_icon">
																	<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="12px" viewBox="0 0 17.938 12">
																	<path fill="rgb(240 233 233);" fill-rule="evenodd" d="M8.964,3.6A2.4,2.4,0,1,0,11.414,6,2.427,2.427,0,0,0,8.964,3.6Zm0-3.6A9.655,9.655,0,0,0-.017,6a9.655,9.655,0,0,0,8.982,6,9.651,9.651,0,0,0,8.982-6A9.651,9.651,0,0,0,8.964,0Zm0,10A4.044,4.044,0,0,1,4.882,6a4.083,4.083,0,0,1,8.165,0A4.044,4.044,0,0,1,8.964,10Z"></path>
																	</svg>
																</span>
															</div>
														</li-->
														'. $edit .'
													</ul>
												</div>
												<div class="search_video_data">
													<a href="'.$href.'" class="searh_video_title">'.$list['title'].'</a>
													<!--h5>'.$list['views'] =''.' Views / '. $list['views']=''.' Votes</h5-->
													<p>'.$description=''.'</p>

													<!--a href="#" class="vote_btn"><svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 471.701 471.701"><path d="M433.601,67.001c-24.7-24.7-57.4-38.2-92.3-38.2s-67.7,13.6-92.4,38.3l-12.9,12.9l-13.1-13.1 c-24.7-24.7-57.6-38.4-92.5-38.4c-34.8,0-67.6,13.6-92.2,38.2c-24.7,24.7-38.3,57.5-38.2,92.4c0,34.9,13.7,67.6,38.4,92.3 l187.8,187.8c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-3.9l188.2-187.5c24.7-24.7,38.3-57.5,38.3-92.4 C471.801,124.501,458.301,91.701,433.601,67.001z M414.401,232.701l-178.7,178l-178.3-178.3c-19.6-19.6-30.4-45.6-30.4-73.3 s10.7-53.7,30.3-73.2c19.5-19.5,45.5-30.3,73.1-30.3c27.7,0,53.8,10.8,73.4,30.4l22.6,22.6c5.3,5.3,13.8,5.3,19.1,0l22.4-22.4 c19.6-19.6,45.7-30.4,73.3-30.4c27.6,0,53.6,10.8,73.2,30.3c19.6,19.6,30.3,45.6,30.3,73.3 C444.801,187.101,434.001,213.101,414.401,232.701z"/></svg>
													'.$list['count_votes']=''.' Votes</a-->
												</div>
											</div>
										</div>
									</div>';

				}

				if(sizeof($search_result) > $limitData){
					$result .= '<div class="col-xs-12">
						<div class="profile_load_more text-center hide">
							<a href="javascript:;" data-action="loadMoreContent" data-offset="'.($startOffset+$limitData).'" class="dis_btn">Load More</a>
						</div>
					</div>';
				}else{
					$result .='<div class="col-xs-12">
									<div class="profile_load_more text-center">
										<div class="dis_loadmore_loader hideme">-- No more data available --</div>
									</div>
								</div>';
				}
			}else{
				$result .= '<div class="col-xs-12">
								<div class="profile_load_more text-center">
									 '.$this->common_html->content_not_available_html().'
								 </div>
							 </div>';
			}


		//}
		echo $result;
	}






	// private function timeConvertor($seconds)
	// {
	// 	$time = round($seconds) + 1;
	// 	$hrs = $time/3600;
	// 	if ($hrs < 1) {
	// 		$hrs_txt = sprintf('%02d:%02d', ($time/60%60), $time%60);
	// 	}else{
	// 		$hrs_txt = sprintf('%02d:%02d:%02d', ($time/3600),($time/60%60), $time%60);
	// 	}
	// 	return $hrs_txt;
	// }

	public function get_my_video_content($searchType){

		$searchKey = (isset($_POST['search']))?$_POST['search']:'';
		$searchKey = addslashes(validate_input($searchKey));
		$searchKey = str_replace("&amp;","&",$searchKey);

		$mode_id  = '';   /*IN CASE OF ALL*/
		if(isset($_GET['mode_id']) && !empty($_GET['mode_id'])){
				$mode_id  = validate_input($_GET['mode_id']); /*site mode selected by user in a serch filter page*/
		}

		$limitData = 10;
		$startOffset = (isset($_POST['start']))?$_POST['start']:0;

		$result = [];
		if(!empty($searchType)){
			if($searchType == 'video'){

				$field = 'channel_post_video.post_id,channel_post_video.user_id,channel_post_video.created_at,channel_post_video.title,channel_post_video.description,website_mode.mode AS web_mode,channel_post_video.age_restr,channel_post_thumb.image_name,channel_post_video.count_views,channel_post_video.count_votes,channel_post_video.post_key,users.user_name,users.user_uname,users.user_level,country.country_name,channel_post_video.iva_id,users_content.uc_pic';

				$accessParam = array(
							'field' => $field,
							'order' => 'channel_post_video.post_id,DESC',
							'where' => 'keyword='.$searchKey.',user_status=1',
							'limit' => $limitData+1 .','.$startOffset,
							);
				if(!empty($mode_id)){
					$accessParam['where']	.= ',mode='.$mode_id;
				}

				if(isset($_GET['uid']) && !empty($_GET['uid'])){
					if(!is_session_uid($_GET['uid'])){   /* FOR OTHER USER	*/
						$AmIFanOfHim = AmIFollowingHim($_GET['uid']);
						if(isset($AmIFanOfHim[0]) && !empty($AmIFanOfHim)){
							$accessParam['where'] .= ',privacy_status_in=6|7';/* PRIVATE,PUBLIC*/
						}else{
							$accessParam['where'] .= ',privacy_status_in=7';/*PUBLIC*/
						}
					}
				}else{
					$accessParam['where'] .= ',privacy_status_in=7';/*PUBLIC*/
				}

				if(isset($_GET['favorite'])){
					$accessParam = array(
						'field' 			=> 	$field,
						'JoinTableAndType'	=>	'channel_favorite_video|left',
						'where'				=> 	'favorite_user_id='.$this->uid.',privacy_status=7,user_status=1',
						'limit' 			=> 	$limitData+1 .','.$startOffset,
						'order' 			=> 'channel_favorite_video.fav_id,DESC',
						);
				}

				$video_result	= $this->query_builder->channel_video_list($accessParam);


				if(isset($video_result['channel'][0])){
					$checkChanelCount = 1;
					$isMyFavorite=0;
					foreach($video_result['channel'] as $channel){
						if($checkChanelCount++ <= $limitData){

							if(strlen($channel['description']) < 250){
								$description =  $channel['description'] ;
							}else{
								$description = substr($channel['description'],0,250)."..." ;
							}

							$FilterData = $this->share_url_encryption->FilterIva($channel['user_id'],$channel['iva_id'],$channel['image_name'],'',true);
							$img = $FilterData['thumb'];
							$webp 	= 	isset($FilterData['webp'])?$FilterData['webp']:$img;

							$full_title	=	$channel['title'];
							$result[] = array(	'post_id' 	 => $channel['post_id'],
												'user_id' 	 => $channel['user_id'],
												'created_at' => date('d M Y', strtotime($channel['created_at'])),
												'title'		 => (strlen($full_title)< 20)?$full_title:substr($full_title,0,20)."...",
												'description'=> $description,
												'web_mode'   => ucfirst($channel['web_mode']),
												'age_restr'  => $channel['post_id'],
												'img'		 => $img,
												'webp'		 => $webp,
												'errimg'	 => thumb_default_image()
										);

						}

					}
					$resp =array('status'=>1, 'data'=>$result);
					if(sizeof($video_result['channel']) > $limitData){

					}
				}else{
					$resp =array('status'=>0, 'data'=>$result);
				}

			}else{
				$resp =array('status'=>0, 'data'=>$result);
			}
		}
		echo json_encode($resp);
	}





/***************************** Casting  call ENDS ***********************************/
	function setSvgIcon($referral_from){


		if($referral_from == 'direct'){
		$icon= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
				  <defs>
					<style>
					  .cls-1 {
						fill: #a77bff;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #fff;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="20" height="20"/>
				  <path class="cls-2" d="M11.93,7.637a4.4,4.4,0,0,0-.767-0.586A4.756,4.756,0,0,0,8.7,6.367a4.682,4.682,0,0,0-3.232,1.27L1.32,11.574A4.218,4.218,0,0,0-.02,14.63a4.455,4.455,0,0,0,4.572,4.333A4.673,4.673,0,0,0,7.784,17.7l3.428-3.248a0.3,0.3,0,0,0,.1-0.222,0.318,0.318,0,0,0-.328-0.308H10.84a5.761,5.761,0,0,1-2.089-.384,0.34,0.34,0,0,0-.356.068L5.931,15.947a2.037,2.037,0,0,1-2.772,0,1.788,1.788,0,0,1-.094-2.532q0.045-.049.094-0.094L7.327,9.374a2.036,2.036,0,0,1,2.768,0,1.393,1.393,0,0,0,1.834,0A1.18,1.18,0,0,0,12,7.7Q11.965,7.669,11.93,7.637Zm6.3-5.966a4.751,4.751,0,0,0-6.464,0L8.34,4.908a0.3,0.3,0,0,0-.069.34,0.327,0.327,0,0,0,.307.189H8.7a5.749,5.749,0,0,1,2.086.391,0.34,0.34,0,0,0,.356-0.068L13.6,3.433a2.037,2.037,0,0,1,2.772,0,1.788,1.788,0,0,1,.094,2.532C16.435,6,16.4,6.029,16.371,6.059l-3.062,2.9-0.026.027-1.07,1.008a2.036,2.036,0,0,1-2.768,0,1.393,1.393,0,0,0-1.834,0,1.188,1.188,0,0,0-.063,1.681q0.03,0.033.063,0.063a4.542,4.542,0,0,0,1.306.866c0.069,0.031.137,0.056,0.206,0.083s0.14,0.05.209,0.074,0.14,0.046.209,0.064l0.2,0.05c0.13,0.031.261,0.056,0.392,0.077a4.779,4.779,0,0,0,.49.043h0.248l0.2-.021c0.071,0,.147-0.018.232-0.018H11.4l0.225-.031,0.1-.019,0.189-.037h0.036a4.638,4.638,0,0,0,2.118-1.138l4.156-3.938a4.171,4.171,0,0,0,.215-5.906Q18.341,1.775,18.228,1.67Z"/>
				</svg>';
		}else if($referral_from == 'facebook'){
			$icon=  '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
				  <defs>
					<style>
					  .cls-1 {
						fill: #a77bff;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #fff;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="20" height="20"/>
				  <path class="cls-2" d="M15.585,0.155H12.947A4.581,4.581,0,0,0,8.064,4.407a4.525,4.525,0,0,0,0,.633V7.292H5.415A0.41,0.41,0,0,0,5,7.7v3.265a0.409,0.409,0,0,0,.414.4H8.068V19.6a0.41,0.41,0,0,0,.415.4h3.461a0.409,0.409,0,0,0,.415-0.4h0V11.37h3.1a0.409,0.409,0,0,0,.415-0.4h0V7.7a0.4,0.4,0,0,0-.122-0.286,0.421,0.421,0,0,0-.294-0.119h-3.1V5.384c0-.918.224-1.383,1.449-1.383h1.777A0.41,0.41,0,0,0,16,3.6V0.564a0.409,0.409,0,0,0-.41-0.409h0Z"/>
				</svg>';
		}else if($referral_from == 'twitter'){
			$icon=  '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
				  <defs>
					<style>
					  .cls-1 {
						fill: #a77bff;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #fff;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="20" height="20"/>
				  <path class="cls-2" d="M20,3.885a8.689,8.689,0,0,1-2.363.634,4.008,4.008,0,0,0,1.8-2.219,8.3,8.3,0,0,1-2.6.972,4.15,4.15,0,0,0-3-1.271,4.057,4.057,0,0,0-4,4.931,11.689,11.689,0,0,1-8.444-4.2A3.964,3.964,0,0,0,2.653,8.1,4.118,4.118,0,0,1,.8,7.6V7.649a4.055,4.055,0,0,0,3.285,3.945,4.172,4.172,0,0,1-1.075.132,3.7,3.7,0,0,1-.776-0.069,4.129,4.129,0,0,0,3.831,2.8A8.352,8.352,0,0,1,.981,16.166,7.863,7.863,0,0,1,0,16.11a11.723,11.723,0,0,0,6.29,1.8A11.47,11.47,0,0,0,17.959,6.647q0-.079,0-0.158c0-.177-0.006-0.349-0.015-0.519A8.087,8.087,0,0,0,20,3.885Z"/>
				</svg>';
		}else{
			$icon=  '';
		}
		if(!empty($icon)){
			return '<div class="invited_icon">
					<span>
					'. $icon .'
					</span>
				</div>';
		}

	}




	function search_iva(){
		if(!isset($_SESSION['is_iva']) || !$_SESSION['is_iva']){
			redirect($this->agent->referrer());
		}

		$data['page_info'] = array('page'=>'search','title'=>'Search');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/search/search_iva',$data);
		$this->load->view('home/inc/footer',$data);
	}

	function my_favorite_video(){
		if(!empty($this->uid)){

			$data['search_query'] = (isset($_GET['search_query']))?urldecode(trim($_GET['search_query'])):'';
			$data['currentMode']  = '';
			$data['myFavPage']  = 1;

			$data['page_info'] = array('page'=>'search','title'=>'My Favorite');
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/search/search',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);
		}else{
			redirect('sign-up');
		}
	}

}
?>
