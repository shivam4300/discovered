<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use App\ThirdParty\subscriber\Mailchimp\MailChimp;
class Home extends CI_Controller {
	public $newWatchList;
	public function __construct()
	{
		parent::__construct();
		if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    }
		$this->load->library(array('share_url_encryption'));
		$this->newWatchList = [];
	}

	public function index(){
		$defaul_mode = is_login() ? 'music' : '';
		$m = $this->DatabaseModel->select_data('mode','website_mode',array('default_mode_status'=>1),1);
		$mode = isset($m[0]['mode'])? $m[0]['mode'] : $defaul_mode;

		if(strlen($defaul_mode) == 0){
			$this->audition_functions->manage_my_web_mode_session('');
			$data['page_info'] = array('page'=> 'creatorinfo' ,'title'=>'Spotlight');
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/other_pages/creatorinfo',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);
		}else{
			$this->current_mode($mode);
		}
		
		
	}

	public function current_mode($mode){
		/************Login By Token for mobile app iframe web***********/
		if(isset($_GET['token']) && !empty($_GET['token'])){

			$cond = array('user_login_token'=>$_GET['token']);

			if(isset($_GET['email']) && !empty($_GET['email'])){
				$cond['user_email'] = $_GET['email'];
			}

			$userDetails = $this->DatabaseModel->select_data('user_id,user_uname,user_status',USERS,$cond,1);
			if( isset($userDetails[0]['user_id']) && !empty($userDetails[0]['user_id']) ) {
				$user_id = $userDetails[0]['user_id'];
				$user_uname = $userDetails[0]['user_uname'];
				
				$this->DatabaseModel->access_database(USERS,'update',array('user_login_token'=>''),array('user_id'=>$user_id)); // token removed for mobile app iframe web login
				$user 	 = $this->create_session($user_id,'old');
				$this->load->library('manage_session');
				redirect(base_url('profile?user='.$user_uname));
			}else{
				redirect(base_url('home/logout'));
			}
		}
		/************Login By Token for mobile app iframe web***********/

		$this->audition_functions->manage_my_web_mode_session($mode);
		$mode_id 				= 	mode();

		$data['modename']	    = 	$mode;

		if($mode_id == 7){
			$data['sub_genres'] =	$this->SubGenreSlider($mode_id);
		}

		$data['rndmGenr'] 		= 	$this->RandoeGenreHomeVideo($mode_id);

		$data['cover_video'] 	=  	$this->audition_functions->get_cover_video();

		$data['page_info'] 		= 	array('page'=> $mode == 'store' ? 'store' : 'homepage' ,'title'=>''.ucfirst($mode));

		$this->load->view('home/inc/header',$data);
	    $this->load->view('home/homepage',$data);
		$this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}


	function show_homepage_slider_new(){

		if($this->input->is_ajax_request() && isset($_POST['limit']) && !empty($_POST['limit'])){

			$live_mode_slider_disable = false;

			$start	=	$_POST['start'];
			$limit	=	$_POST['limit'];

			$mode	=  	mode();

			$fields = 	array(
							'slider_title',
							'type',
							'mode',
							'data',
							'status',
							'slider_order',
							'user_id',
							'id',
							'slider_type',
							'data_order',
							'query_type',
							'search_query',
							'genre',
							'category_id',
							'slider_mode',
							'slider_category_slug'
						);

			$cond		=	array($fields[14] => $mode ,$fields[4] => 1);	        //slider_mode
			$order_home	=	array($fields[5] , 'ASC');

			$result		= 	$this->DatabaseModel->select_data($fields,'homepage_sliders use INDEX(mode)',$cond,array($limit,$start),'', $order_home);

			$data		=	array();

			if($mode == 9 && $live_mode_slider_disable){
				if($start==0){
					$liveData	=	$this->getLiveData('is_live');
					if(!empty($liveData)){
						array_push($data , $liveData);
					}

					$lastLive	=	$this->getLiveData('recently_ended');
					if(!empty($lastLive)){
						array_push($data , $lastLive);
					}

					$upcoming	=	$this->getLiveData('scheduled_live_streams');
					if(!empty($upcoming)){
						array_push($data , $upcoming);
					}
					$this->load->library('Valuelist');
					$web_mode = array_reverse($this->valuelist->mode(), true);

					foreach ($web_mode as $key => $value) {
						if(in_array($key ,[1,2,3,7])){
							$category = $this->getLiveData('mode', $key ,$value);
							if(!empty($category)){
								array_push($data , $category);
							}
						}
					}
				}
			}

			if($start == 0 && $mode != 9){
				$watchHistory = $this->getUserWatchList();
				if(!empty($watchHistory)){
					array_push($data , $watchHistory);
				}
			}

			if(isset($result[0])){

				while (True) {

					$reset = false;
					foreach($result as $list){
						$limit 		= 	12;
						$slider_type=	$list['slider_type'];

						if($slider_type == 'playlist'){
							$htmlarray = $this->getPlaylistSlider($list,$start,$limit);
							array_push($data,$htmlarray);
						}else{
							$htmlarray = $this->getSingleVideoSlider($list,$start,$limit);
							array_push($data,$htmlarray);
						}
					}

					if ( ! $reset ) {
						break;
					}
				}
				$resp = array('status'=>1,'data'=>$data , '$result' => $result);

			}else{
				$resp = array('status'=>1,'data'=>$data);
			}
			// $this->db->cache_off();
			echo json_encode($resp);
		}

	}


	public function getPlaylistSlider($list=[],$start=0,$limit=10)
	{
		if(!empty($list)){
			$title		=	$list['slider_title'];
			$type		=   $slug =	$list['type'];
			$user		=	$list['user_id'];
			$slider_type=	$list['slider_type'];
			$slider_mode=	$list['slider_mode'];
			$mode		=	$list['mode'];
			$vidList 	= 	explode(',',$list['data']);
			$query_type	=	$list['query_type'];
			$genre		=	$list['genre'];
			$category	=	$list['category_id'];

			if(!empty($list['data_order']) ){
				$vidList 	= 	explode(',',$list['data_order']);   //in case if admin selected the order of video
			}

			$limit	 	= 	(sizeof($vidList) <= $limit)? sizeof($vidList) : $limit;
			$keys 		= 	array_rand($vidList ,$limit);

			$values		=	[];
			if(is_array($keys)){
				for($i=0;$i< sizeof($keys);$i++){
					array_push($values,$vidList[$keys[$i]]);
				}
				$vidList = implode(',',$values);
			}else{
				$vidList = $vidList[$keys];
			}

			$field = "channel_video_playlist.user_id as playlist_user_id,channel_video_playlist.video_ids,channel_post_video.post_id,channel_video_playlist.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.user_id,channel_video_playlist.playlist_thumb";

			$where = "channel_video_playlist.privacy_status = 7 AND channel_video_playlist.playlist_id IN($vidList) AND " . $this->common->channelGlobalCond();
			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/

			$join  = array(
						'multiple',
						array(
							array('channel_video_playlist use INDEX(first_video_id)' , 'channel_video_playlist.first_video_id = channel_post_video.post_id'),
							array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
							array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
						)
					);

			$order = "FIELD(channel_video_playlist.playlist_id,$vidList)";

			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);

			$html = '';
			if(!empty($videoData)){

				if($query_type == 'Search'){
					// $user 		= 	$this->DatabaseModel->select_data('user_uname,user_name','users use INDEX(user_id)',array('user_id'=>$user),1);
					// $user_uname = 	isset($user[0]['user_uname'])?$user[0]['user_uname']:'';
					// $user_name 	= 	isset($user[0]['user_name'])?$user[0]['user_name']:'';
					// $href		=	base_url('search?search_query='.$user_uname.'&un='.$user_name.'&mode_id=series&genre_id='.$genre);
					$href		=	base_url('search?search_query='.$search_query.'&genre_id='.$genre.'&category_id='.$category.'&by_user_id='.$user.'&mode_id=series');

					$href		=	'<a  href="'.$href.'" class="dis_sh_btn muli_font">See All<span class="dis_sh_btnicon">
									<svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
									<path  fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z" />
									</svg>
									</span>
									</a>';
				}else{
					$type 		= 	urlencode(str_replace('_','-',$type));

					$href 		= 	base_url('watch-all?v='.$type);
					$href 		.= '&mode_id='.mode();
					$href 		= 	'<a  href="'.$href.'" class="dis_sh_btn muli_font">See All<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
									<path  fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z" />
									</svg>
									</span>
									</a>';
				}

				$color 		=  ($start%2 == 0)? "bg-white" : "";

				$autoplay	=	array("4000","4500","5000");
				$random_keys=	array_rand($autoplay,1);
				$auto 		= 	$autoplay[$random_keys];
				$pl 		= 	explode(',',$vidList);

				$htmlarray = array(
					'color'=>$color,
					'title'=>$title,
					'type'=>$slug,
					'href' =>$href,
					'auto' =>$auto,
					'videoData'=>$this->common_html->swiper_slider_without_html($videoData,[],$pl),

				);

				return $htmlarray;
			}
		}
	}

	public function getSingleVideoSlider($list=[],$start=0,$limit=10)
	{
		if(!empty($list)){
			// $mode		=  	mode();
			$title		=	$list['slider_title'];
			$type		=   $slug =	$list['type'];
			$user		=	$list['user_id'];
			$slider_type=	$list['slider_type'];
			$query_type	=	$list['query_type'];
			$search_query=	$list['search_query'];
			$genre		=	$list['genre'];
			$category	=	$list['category_id'];
			$slider_mode=	$list['slider_mode'];
			$mode		=	$list['mode'];

			if($title == 'TOP GAMES'){
				return array('title'=>'TOP GAMES');
			}else
			if($title == 'EXPLORE VIDEOS BY GENRES'){
				return array('title'=>'EXPLORE VIDEOS BY GENRES');
			}else{
				$vidList = '';
				if(!empty($list['slider_category_slug'])){ // for getDiscovered videos only

					$CateData = $this->DatabaseModel->select_data('category_id','artist_category',array('category_slug' =>$list['slider_category_slug']));
					if(!empty($CateData)){
						$catIds = array_column($CateData, 'category_id');
						$catIds = implode(',',$catIds);

						$cond = "category IN($catIds) AND upload_source_page = 'getdiscovered' AND active_status = 1 AND complete_status = 1";

						$videoData = $this->DatabaseModel->select_data('post_id','channel_post_video use INDEX(post_id)',$cond,25,'',array('post_id','DESC'));
						if(!empty($videoData)){
							$vidList = array_column($videoData, 'post_id');
						}
					}
				}else{

					$vidList 	= 	explode(',',$list['data']);
				}
				if(!empty($list['data_order']) ){
					$vidList 	= 	explode(',',$list['data_order']);   //in case if admin selected the order of video
				}

				$limit	 	= 	(sizeof($vidList) <= $limit)? sizeof($vidList) : $limit;

				$keys 		= 	array_rand($vidList ,$limit);

				shuffle($keys);

				$values		=	[];

				if(is_array($keys)){
					for($i=0;$i< sizeof($keys);$i++){
						array_push($values,$vidList[$keys[$i]]);
					}
					$vidList = implode(',',$values);
				}else{
					$vidList = $vidList[$keys];
				}

				if(!empty($vidList)){

					$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type";

					$where = "channel_post_video.post_id IN($vidList) AND " . $this->common->channelGlobalCond();
					/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/

					$join  = array(
								'multiple',
								array(
									array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
									array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
								)
							);

					$order = "FIELD(channel_post_video.post_id,$vidList)";

					if($title == 'NEW RELEASES'){
						$order = array('post_id','DESC');
					}else
					if($title == 'TOP VIDEOS OF THE MONTH' || $title == 'MOST POPULAR VIDEOS'){
						$order = array('count_views','DESC');
					}

					$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);
					// print_r($this->db->last_query());die;
					$html = '';
					if(!empty($videoData)){
						// if(!empty($user)){
						if($query_type == 'Search'){
							$users 		= 	$this->DatabaseModel->select_data('user_uname,user_name','users use INDEX(user_id)',array('user_id'=>$user),1);
							$user_uname = 	isset($users[0]['user_uname'])?$users[0]['user_uname']:'';
							$user_name 	= 	isset($users[0]['user_name'])?$users[0]['user_name']:'';

							$href		=	base_url('search?search_query='.$search_query.'&genre_id='.$genre.'&category_id='.$category.'&by_user_id='.$user.'&mode_id='.$mode);

							$href		=	'<a  href="'.$href.'" class="dis_sh_btn muli_font">See All<span class="dis_sh_btnicon">
											<svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
											<path  fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z" />
											</svg>
											</span>
											</a>';
						}else{
							$type 		= 	urlencode(str_replace('_','-',$type));
							$href 		=   base_url('watch-all?v='.$type);
							$href 		.= '&mode_id='.mode();
							$href 		= 	'<a  href="'.$href.'" class="dis_sh_btn muli_font">See All<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
											<path  fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z" />
											</svg>
											</span>
											</a>';
						}

						$color 		=  ($start%2 == 0)? "bg-white" : "";

						$autoplay	=	array("4000","4500","5000");
						$random_keys=	array_rand($autoplay,1);
						$auto 		= 	$autoplay[$random_keys];

						$htmlarray = array(
							'color'		=>	$color,
							'title'		=>	$title,
							'type'		=>	$slug,
							'href' 		=>	$href,
							'auto' 		=>	$auto,
							'videoData'	=>	$this->common_html->swiper_slider_without_html($videoData),
						);

						return $htmlarray;
					}else{
						if(mode() !=10){
							$this->DatabaseModel->access_database('homepage_sliders','update',array('status'=>0),array('id' => $list['id'] ));
						}
					}
				}else{
					if(mode() !=10){
						//$this->DatabaseModel->access_database('homepage_sliders','update',array('status'=>0),array('id' => $list['id'] ));
					}
				}

			}
		}
	}


	public function SubGenreSlider($mode_id=null){
		if($mode_id){
			$where 	= 'mode_id='.$mode_id .' AND status = 1 AND level = 2 AND is_in_slider = 1';
			return $this->DatabaseModel->select_data('genre_slug,image,genre_name','mode_of_genre use INDEX(mode_id)',$where,8,'','rand()');
		}
	}

	public function RandoeGenreHomeVideo($mode_id = NULL){
		if(!empty($mode_id)){
			$where ="mode_of_genre.mode_id ={$mode_id} AND mode_of_genre.status=1 AND ";

			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			$where .= "channel_post_video.mode = {$mode_id} AND " . $this->common->channelGlobalCond([1,1,NULL,0,NULL,1,0]);

			$GROUP_BY 	= "mode_of_genre.genre_id";
			$P 			= base_url().'repo_admin/images/genre/';
			$join  = array(
							'multiple',
							array(
								array('channel_post_video use INDEX(genre)','channel_post_video.genre= mode_of_genre.genre_id','left'),
								array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
							)
						);

			$videoData =  $this->DatabaseModel->select_data('mode_of_genre.genre_name,mode_of_genre.genre_slug,mode_of_genre.genre_id,mode_of_genre.mode_id,mode_of_genre.image','mode_of_genre use INDEX(mode_id)',$where,8,$join,'rand()','',$GROUP_BY);

			return (isset($videoData[0])) ? $videoData : [] ;
		}
	}

	function casting_call(){
		$this->audition_functions->manage_my_web_mode_session('casting_call');
		$data['page_info'] 		= 	array('page'=>'homepage','title'=>'casting call');
		$data['cover_video'] = array('url'=>base_url().'repo/videos/casting_couch.mp4','post_id'=>'','title'=>"Auditions");
		$this->load->view('home/inc/header',$data);
		$this->load->view('user/casting_call',$data);
		$this->load->view('home/inc/footer',$data);
	}

	public function ChangeModeNPage($mode=null){
		if($mode){
			$this->audition_functions->manage_my_web_mode_session($mode);
			redirect(base_url('dashboard/charts/belowChart'));
		}
	}
	/* Common Logout */
    public function logout(){
		$this->session->sess_destroy();
		setcookie("AuthTkn", "", 0, '/');
		$this->DeleteAccountTypeCookie();
		clear_cache();
        redirect(base_url());
	}

	function DeleteAccountTypeCookie(){
		if (isset($_COOKIE['gamAccountType'])) {
			unset($_COOKIE['gamAccountType']);
			setcookie('gamAccountType', null, -1, '/');
			return true;
		} else {
			return false;
		}
	}

	function SaveLoginCookie(){
		if($_POST['rem_me'] == '1'){
			setcookie("aud_email", $_POST['u_em'] , time()+3600 * 24 * 14,'/');
			setcookie("aud_pwd", $_POST['u_pwd'] , time()+3600 * 24 * 14,'/');
		}elseif($_POST['rem_me'] == '0'){
			setcookie("aud_email", $_POST['u_em'] , time()-3600 * 24 * 365,'/');
			setcookie("aud_pwd", $_POST['u_pwd'] , time()-3600 * 24 * 365,'/');
		}
	}
	function AdminLoginMe(){
		if ($this->input->is_ajax_request() && isset($_POST['uid']) && isset($_SESSION['admin'])) {
			echo $this->create_session($_POST['uid'],'old');
		}
	}

	function AdminLoginMeSupport(){
		if ($this->input->is_ajax_request() && isset($_POST['uid']) && isset($_SESSION['admin'])) {
			//echo $_POST['uid'];
			$checkUser = $this->DatabaseModel->select_data('*' , 'support_user' , array('id' => $_POST['uid']), 1);
				if(!(empty($checkUser))){
					$sessData = array(
						'id' 					=> $checkUser[0]['id'],
						'support_department' 	=> $checkUser[0]['support_department'],
						'user_name' 			=> $checkUser[0]['user_name'],
						'user_email' 			=> $checkUser[0]['user_email']
					);

					$this->session->set_userdata($sessData);
					echo "success";

				}else{
					echo "failed";
				}
			//echo $this->create_session($_POST['uid'],'old');
		}
	}
	/**************** LOGIN STARTS **********************/
	function allow_login_access(){
		$recap = $this->common->validateRecaptcha();
		if($recap['status']){
			if(isset($_POST['u_em']) && isset($_POST['u_pwd'])) {
				$userDetails = $this->DatabaseModel->select_data('user_id,user_name,user_status,is_deleted','users',array('user_email'=>$_POST['u_em'],'user_password'=>md5($_POST['u_pwd'])),1);

				if( isset($userDetails[0]['user_id']) && !empty($userDetails[0]['user_id']) ) {

					$user_status 	= $userDetails[0]['user_status'];
					$user_name 		= $userDetails[0]['user_name'];
					$user_id 		= $userDetails[0]['user_id'];
					$is_deleted 	= $userDetails[0]['is_deleted'];

					if($is_deleted == 1){
						$resp = array( 'status'=>0 , 'mess' => 'Your account is in the process of being deleted or Temporary deactivated, so you can\'t login with this account now.');//  Delete
					}else
					if( $user_status == '4' ){
						$resp = array('status'=>0,'mess' => 'Your icon profile is in the process of being accredited, An activation link will be sent to your email upon completion.');// Icon Blocked
					}else
					if( $user_status == '3' ){
						$resp = array('status'=>0 , 'mess' => 'You account has been blocked. Please, contact us at support@discovered.tv !');// Blocked
					}else
					if( $user_status == '2' ){
						$resp = array('status'=>0 , 'mess' => 'Your account is inactive. Please activate your account OR contact us at support@discovered.tv !');// Inactive
					}else
					if( $userDetails[0]['user_status'] == '1' ){
						$is_giveaways 	= isset($_POST['is_giveaways'])?$_POST['is_giveaways']:NULL;
						if($is_giveaways != NULL){
							$update = $this->DatabaseModel->access_database('users','update',array('is_giveaways'=>$is_giveaways),array('user_id' => $user_id ));
							if($update){
								$email = $_POST['u_em'];
								$to = 	'{"email":"'.$_POST['u_em'].'","name":"'.$user_name.'","type":"to"}' ;
								$this->audition_functions->GiveAwayMail($to,$user_name,$email);
							}
						}
						$this->SaveLoginCookie();
						$user =  $this->create_session($user_id,'old');
						$resp = array('status'=>1,'user'=>$user);
					}
				}else{
					$resp = array('status'=>0 , 'mess' => 'Invalid login credentials.Please,try again.');
				}
			}else{
				// $resp = array('status'=>404);
				$resp = array('status'=>0 , 'mess' => 'Something went wrong ! Please try again.');
			}
		}else{
			$resp = array('status'=>0 , 'mess' => $recap['message']);
		}
		echo json_encode($resp);
	}
	/**************************** Login ENDS **********************/

	public function social_login(){
	    $user_email 	= (isset($_POST['user_email']) && !empty($_POST['user_email']) && $_POST['user_email'] != 'undefined')? $_POST['user_email'] : NULL;

		if(isset($_POST['user_social']) && !empty($_POST['user_social'])){

			$user_social 	= $this->input->post('user_social');
			$user_name 		= $this->input->post('user_name');
			$sigup_acc_type = $this->input->post('sigup_acc_type');
			$is_giveaways 	= isset($_POST['is_giveaways'])?$_POST['is_giveaways']:NULL;
			$registration_source = isset($_POST['registration_source'])?$_POST['registration_source']:NULL;
			$cond 			= "user_social = '{$user_social}'";


				if(!empty($user_email)){
					$cond .= " OR user_email = '{$user_email}'";
				}
				$checkUser 	= $this->DatabaseModel->select_data('user_id,user_social','users',$cond,1);

				if(empty($checkUser) && $sigup_acc_type != 'undefined'){

					$insert_array = array(
						'user_name'   	=>  trim($user_name),
						'user_email'   	=>  $user_email,
						'sigup_acc_type'=>	$sigup_acc_type,
						'is_giveaways'	=>	$is_giveaways,
						'registration_source' => $registration_source,
						'user_status'   =>  1,
						'user_social'   =>  $user_social,
						'referral_by'	=>	isset($_SESSION['referral_by'])?$_SESSION['referral_by']:''	,
						'referral_from'	=>	isset($_SESSION['referral_from'])?$_SESSION['referral_from']:'',
						'register_by'	=>  'WEB',
						'user_location' =>  $this->common->getlocationbyip($this->common->get_client_ip())
					);
					if($sigup_acc_type == 'express'){
						$insert_array['user_uname'] = str_replace(" ","_",strtolower($user_name)).'_'.$this->common->generateRandomString($length = 3,$onlyNum=true);
					}
					$userid = $this->DatabaseModel->access_database('users','insert',$insert_array,'');
					if($sigup_acc_type == 'express' && !empty($userid) ){
						$this->DatabaseModel->access_database('users_content','insert',['uc_userid'=>$userid]);
					}

					if(isset($_POST['registration_source']) && $_POST['registration_source'] == 'gamepass' && !empty($user_email)){
						$_POST['u_em'] = $user_email;
						//$this->subsCribe(); //MailChimp subscribe
					}

					$this->create_session($userid,'new');
				}else
				if(empty($checkUser) && $sigup_acc_type == 'undefined'){
					$resp = array('status'=>2,'message'=>'Please registered an account before login.'); /********Means redirected for the signup first **********/
					echo json_encode($resp);die;
				}else {

					$array  = array('user_social'=>$user_social);

					if($is_giveaways != NULL)
					$array['is_giveaways'] = $is_giveaways;

					$update = $this->DatabaseModel->access_database('users','update',$array,array('user_id' => $checkUser[0]['user_id']));
					if($update){
						$to = 	'{"email":"'.$user_email.'","name":"'.$user_name.'","type":"to"}' ;
						$this->audition_functions->GiveAwayMail($to,$user_name,$user_email);
					}

					$this->create_session($checkUser[0]['user_id'],'old');
				}

				if(isset($_SESSION['user_name'])){
					$user =  !empty($_SESSION['user_uname']) ? strtolower($_SESSION['user_uname']) : 'newLogin';
					$resp = array('status'=>1,'user'=>$user);
				}else{
					$resp = array('status'=>0,'message'=>'Something went wrong ! please try again later.');
				}
		}else{
			$resp = array('status'=>0,'message'=>'Something went wrong with social account ! please try again later.');
		}
		echo json_encode($resp);
	}




    /*************** Create Session and Send Email STARTS **********************/

    function create_session($userid,$type) {
        $userDetails 	= $this->DatabaseModel->select_data('user_name,user_uname,sigup_acc_type,user_status,user_level,user_cate,user_email,store_customer_id','users',array('user_id'=>$userid),1);
		$userContent 	= $this->DatabaseModel->select_data('uc_type,is_iva,uc_country,is_ele','users_content',array('uc_userid'=>$userid),1);

		$user_type 		= '0';
		$is_iva 		= '0';
		$is_ele 		= '0';
		$uc_country 	= ' ';

		if( isset($userContent[0]) ){
			if(!empty($userContent[0]['uc_type'])){
				$user_type 	= $userContent[0]['uc_type'];
			}
			if(!empty($userContent[0]['is_iva'])){
				$is_iva 	= $userContent[0]['is_iva'];
			}if(!empty($userContent[0]['is_ele'])){
				$is_ele 	= $userContent[0]['is_ele'];
			}
			if(!empty($userContent[0]['uc_country'])){
				$uc_country = $userContent[0]['uc_country'];
			}
		}

		$firstname 	= '';
		$store_customer_id = '';
        if( !empty($userDetails[0]['user_name']) ) {
            $fullname 	= $userDetails[0]['user_name'];
            $store_customer_id 	= $userDetails[0]['store_customer_id'];
            $f_arr  	= explode(' ',$fullname);
            $firstname 	= ucfirst(isset($f_arr[0]) ? $f_arr[0] : '' );
        }

        $session	= array(
            'user_login_id'		=> $userid,
            'user_name'			=> $firstname,
            'user_uname'		=> $userDetails[0]['user_uname'],
            'sigup_acc_type'	=> $userDetails[0]['sigup_acc_type'],
            'user_login'		=> true,
            'user_status'		=> $userDetails[0]['user_status'],
            'account_type'		=> $userDetails[0]['user_level'], /*ARTIST CATEGORY*/
            'user_category'		=> $userDetails[0]['user_cate'], /*ARTIST LEVEL*/
            'is_iva'			=> $is_iva,
            'is_ele'			=> $is_ele,
            'primary_type'		=> (string) $user_type,
            'uc_country'		=> $uc_country,
            'user_email'		=> $userDetails[0]['user_email'],
			'iat'				=> time(),
			'store_customer_id' => $store_customer_id,
			'sub'				=> $userid,
		);

		$this->load->library('creator_jwt');

		$jwtToken 	= 	$this->creator_jwt->GenerateToken($session);
		setcookie("AuthTkn", $jwtToken , time()+ 86400 * 30 ,'/');

		if(isset($_SESSION['referral_from']) && !empty($_SESSION['referral_from'])){
			unset($_SESSION['referral_from']);
			unset($_SESSION['referral_by']);
		}
		$this->session->set_userdata($session);

		// Last login date time update here
		$this->DatabaseModel->access_database(USERS,'update',array('last_login'=>date('Y-m-d H:i:s')),array('user_id'=>$userid));

        if( $type == 'new' ) {
            $email = $userDetails[0]['user_email'];
            $randomstr = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
            $this->DatabaseModel->access_database('users','update',array('user_password'=>md5($randomstr)),array('user_id'=>$userid));

            $subject = 'Welcome to '.PROJECT;
            $body ="<p> Hi ".$firstname.", <br/> Thank you very much for creating an account with us. You can login to the ".PROJECT." using your Facebook or Google or even you can use these credentials , <br/> </p> <p> Login Email : ".$email."</p> <p> Password : ".$randomstr."<br/><br/> Thanks.</p> ";

			$greeting = 'Thanks for creating an account with us.';
			$action = 'Now you can login to '. PROJECT .' by using your personal email and password or you can login with your Facebook or Google accounts.';

			// $this->audition_functions->MailByMandrillForRegstr($email,$firstname,$subject,$greeting,$action,$email,$randomstr);

			$this->load->helper('aws_ses_action');
			send_smtp([
				'greeting'=>$greeting,
				'action'=>$action,
				'email'=>$email,
				'receiver_email'=>$email,
				'password'=>$randomstr,
				'button'=>NULL,
				'link'=>NULL,
				'subject'=>$subject,
			]);
        }
		return $session['user_uname'];
    }
    /*************** Create Session and Send Email ENDS **********************/

	/*************** Choose Level STARTS **********************/



    /*************** Choose Level ENDS **********************/



	/**************** Register Email STARTS **********************/
	function register_email(){

		$recap = $this->common->validateRecaptcha();
		if(!$recap['status']){
			echo $recap['message'];die;
		}

		if(isset($_POST['u_em']) && isset($_POST['u_pwd']) && isset($_POST['user_name']) ) {

			$user_email 		= 	$this->input->post('u_em');
			$user_pass 			= 	$this->input->post('u_pwd');
			$user_name 			= 	$this->input->post('user_name');
			$sigup_acc_type 	= 	$this->input->post('sigup_acc_type');

			if(is_disposable_email($user_email)){
				echo 'This email address looks fake or invalid, please enter a real email address.';die;
			}

			if(is_ip_exist()){
				echo "We've detected unusual signup activity. Please wait a while before trying again.";die;
			}

			$result = $this->DatabaseModel->select_data('user_id','users',array('user_email'=> $user_email ),1);

			if(empty($result)) {

				$randomstr	= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);

				$param = array(
					'user_key'		=>	md5($randomstr),
					'user_email'	=>	$user_email,
					'user_name'		=>	$user_name,
					'sigup_acc_type'=>	$sigup_acc_type,
					'is_giveaways'	=>	isset($_POST['is_giveaways'])?$_POST['is_giveaways']:NULL,
					'registration_source' =>	isset($_POST['registration_source'])?$_POST['registration_source']:NULL,
					'user_password'	=>	md5($user_pass),
					'referral_by'	=>	isset($_SESSION['referral_by'])?	$_SESSION['referral_by']	:	'',
					'referral_from'	=>	isset($_SESSION['referral_from'])?	$_SESSION['referral_from']	:	'',
					'register_by'	=>  'WEB',
					'user_location' =>  $this->common->getlocationbyip($this->common->get_client_ip())
				);

				if($sigup_acc_type == 'express'){
					$param['user_uname'] = str_replace(" ","_",strtolower($user_name)).'_'.$this->common->generateRandomString($length = 3,$onlyNum=true);
				}

				$userid = $this->DatabaseModel->access_database('users','insert',$param);
				if($sigup_acc_type == 'express' && !empty($userid) ){
					$this->DatabaseModel->access_database('users_content','insert',['uc_userid'=>$userid]);
				}
				$email 		= 	$user_email;
				$link 		= 	base_url().'home/verify_email/'.md5($randomstr);

				$subject 	= 	'Welcome to '. PROJECT;
				$body 		=	"<p> Hi, <br/> Thank you very much for creating an account with us. Please click on the link to verify your email. <br/><a href='".$link."'> Yes, this is my email</a></p> <p style='text-align:center;'>OR</p><p>Copy this link and paste it in your browser<br/>".$link."</p><br/><br/> Thanks</p> ";

				$greeting 	= 	'Thanks for creating an account with us.';
				$action 	= 	'You have entered <b>'.$email.'</b> as the email address for your account. <br/>To complete your sign up process, simply click the button below so we know this account belongs to you.';

				//$action 	= 	'Please click on the link to verify your email. OR Copy this link and paste it in your browser. ';
				$button 	= 	'Activate Your Account';
				$to 		= 	'{"email":"'.$user_email.'","name":"'.$user_name.'","type":"to"}' ;

				// $this->audition_functions->MailByMandrillforLink($to,$subject,$greeting,$action,$button,$link);

				$this->load->helper('aws_ses_action');
				send_smtp([
					'greeting'=>$greeting,
					'action'=>$action,
					'email'=>NULL,
					'receiver_email'=>$email,
					'password'=>NULL,
					'button'=>$button,
					'link'=>$link,
					'subject'=>$subject,
				]);

				if(isset($_POST['is_giveaways']))
				$this->audition_functions->GiveAwayMail($to,$user_name,$email);

				echo '1';
			}else{
				echo '401';
			}
		}else{
			echo '404';
		}
	}
	function testmail(){
		$to = '{"email":"ajay.parmar@himanshusofttech.com","name":"ajaydeep","type":"to"}' ;
		$this->audition_functions->GiveAwayMail($to,'Ajaydeep','ajay.parmar@himanshusofttech.com');
	}

	public function validateGamepassEmail(){
		$recap = $this->common->validateRecaptcha();
		if($recap['status']){

			if(isset($_POST['validate_email']) && !empty($_POST['validate_email'])) {
				if(is_disposable_email($_POST['validate_email'])){
					$resp = array('status'=>0 , 'mess' => 'This email address looks fake or invalid, please enter a real email address.');
					echo json_encode($resp);die;
				}

				$userDetails = $this->DatabaseModel->select_data('user_id,user_name,user_status,is_deleted','users',array('user_email'=>$_POST['validate_email']),1);

				if( isset($userDetails[0]['user_id']) && !empty($userDetails[0]['user_id']) ) {

					$user_status 	= $userDetails[0]['user_status'];
					$user_name 		= $userDetails[0]['user_name'];
					$user_id 		= $userDetails[0]['user_id'];
					$is_deleted 	= $userDetails[0]['is_deleted'];

					if($is_deleted == 1){
						$resp = array( 'status'=>0 , 'mess' => 'Your account is in the process of being deleted or Temporary deactivated. Please, contact us at support@discovered.tv !.');//  Delete
					}else
					if( $user_status == '4' ){
						$resp = array('status'=>0,'mess' => 'Your icon profile is in the process of being accredited, An activation link will be sent to your email upon completion.');// Icon Blocked
					}else
					if( $user_status == '3' ){
						$resp = array('status'=>0 , 'mess' => 'You account has been blocked. Please, contact us at support@discovered.tv !');// Blocked
					}else
					if( $user_status == '2' ){
						$resp = array('status'=>0 , 'mess' => 'Your account is inactive. Please activate your account OR contact us at support@discovered.tv !');// Inactive
					}else
					if( $userDetails[0]['user_status'] == '1' ){
						$_POST['u_em'] = $_POST['validate_email'];
						$_POST['user_name'] = $user_name;
						$resp = array('status'=>1,'mess' =>'You have successfully registered for the PC Game Pass free trial and should have received an email confirmation. <br> Please check both your inbox and spam folder.', 'user'=>$userDetails);
						/*$MailChimpResp = $this->subsCribe();
						if(isset($MailChimpResp['success'])){
							$resp = array('status'=>1,'mess' =>$MailChimpResp['success'], 'user'=>$userDetails, 'mailChimp'=> $MailChimpResp);
						}else{
							$resp = array('status'=>0,'mess' =>$MailChimpResp['error'], 'user'=>$userDetails, 'mailChimp'=> $MailChimpResp);
						}*/
					}
				}else{
					$resp = array('status'=>0 , 'mess' => 'This email address is not registered with us.</br> Please sign up and try again.');
				}
			}else{
				// $resp = array('status'=>404);
				$resp = array('status'=>0 , 'mess' => 'Something went wrong ! Please try again.');
			}
		}else{
			$resp = array('status'=>0, 'mess' =>$recap['message']);
		}
		echo json_encode($resp);
	}


	/*public function subsCribe(){
		if(isset($_POST['u_em']) && !empty($_POST['u_em'])){
			$emailExist = $this->DatabaseModel->select_data('id','gamepass_coupon_codes',array('user_email'=>$_POST['u_em']));
			if(empty($emailExist)){

				$coupon = $this->DatabaseModel->select_data('id,coupon_code','gamepass_coupon_codes',array('status'=>0),1);
				$holdCoupon = array('status'=>2, 'user_email'=>$_POST['u_em']);
				$holdUpdate = $this->DatabaseModel->access_database('gamepass_coupon_codes','update',$holdCoupon,array('id' => $coupon[0]['id'])); // coupon on hold

				if(!empty($coupon) && !empty($coupon[0]['coupon_code'])) {

					include_once APPPATH . 'third_party/MailChimp.php';

					$MailChimp = new MailChimp(MAILCHIMP_KEY);
					$listID ='75e5618d5d';

					if (isset($_POST['user_name'])  && !empty($_POST['user_name'])){
						$args = array('FNAME' => $_POST['user_name'],'COUPON'=>$coupon[0]['coupon_code']);
					}else{
						$args = array();
					}

					if(!empty($listID)){
						$mdata = $MailChimp->post("lists/$listID/members", [
							'email_address' => $_POST['u_em'],
							'full_name'		=> isset($_POST['user_name']) ? $_POST['user_name'] : '',
							'status'        => 'subscribed',
						]);
						/*'merge_fields' => [
								'COUPON' => 'HEY12SD3456' // 'COUPON' is the merge field in Mailchimp
							]*/
		/*			}
					$detail = isset($mdata['detail']) ? $mdata['detail'] : '';
					if ($MailChimp->success()) {
						if(!empty($args)){
							$subscriber_hash = $MailChimp->subscriberHash( $_POST['u_em'] );
							$MailChimp->patch("lists/$listID/members/$subscriber_hash", [
								'merge_fields' => $args
							]);
						}

						$updateData = array('status'=>1, 'user_email'=>$_POST['u_em']);
						$update = $this->DatabaseModel->access_database('gamepass_coupon_codes','update',$updateData,array('id' => $coupon[0]['id']));

						$result = array('success'=>'You have successfully registered for the PC Game Pass free trial and should have received an email confirmation. <br> Please check both your inbox and spam folder.');
					}elseif ($mdata['status'] == 400){
						/*if(!empty($args)){
							$subscriber_hash = $MailChimp->subscriberHash( $_POST['u_em'] );
							$mdata = $MailChimp->put("lists/$listID/members/$subscriber_hash", [
								'merge_fields' => $args
							]);
						}*/
			/*			$errorMsg = $detail;
						if($mdata['title'] == 'Member Exists'){
							$errorMsg = 'You have already registered for the PC Game Pass free trial and should have received an email confirmation. <br> Please check both your inbox and spam folder.';
						}

						/*if($mdata['title'] == 'Forgotten Email Not Subscribed'){

						}*/

			/*
						$result = array( 'error' => $errorMsg,'status'=>'12' );
					}else{
						$result = array('error'=>$MailChimp->getLastError());
					}
				}else{
					$result = array('error'=>'Coupon code not found.');
				}

			}else{
				$result = array('error'=>'You have already registered for the PC Game Pass free trial and should have received an email confirmation. <br> Please check both your inbox and spam folder.');
			}
		}else{
			$result = array('error'=>'Email id is required.');
		}
		return $result;
	}*/

	/**************************** Register Email ENDS **********************/

	/**************************** Verify Email STARTS **********************/

	function verify_email($link=''){
		if($link != '') {

			$userDetails = $this->DatabaseModel->select_data('user_id,user_email,user_name,user_status,registration_source','users',array('user_key'=>$link),1);

			if(!empty($userDetails)) {
				if( $userDetails[0]['user_name'] != '' ) {
					$fullname = $userDetails[0]['user_name'];
					$f_arr  = explode(' ',$fullname);

					$firstname = ucfirst($f_arr[0]);
				}
				else {
					$firstname = '';
				}
				$userid = $userDetails[0]['user_id'];

				if( $userDetails[0]['user_status'] == '2' ) {
					$this->DatabaseModel->access_database('users','update',array('user_status'=>1,'user_key'=>''),array('user_id'=>$userid));

					if($userDetails[0]['registration_source'] == 'gamepass' && !empty($userDetails[0]['user_email'])){
						$_POST['u_em'] 		= $userDetails[0]['user_email'];
						$_POST['user_name'] = $userDetails[0]['user_name'];
						//$this->subsCribe(); //MailChimp subscribe
					}
					// Gamification - cookie
					setcookie('accountVerify', true, time() + (86400 * 30), "/");
					redirect(base_url().'home/messages/activation_success');
				}
				else if( $userDetails[0]['user_status'] == '3' ) {
				$this->DatabaseModel->access_database('users','update',array('user_key'=>''),array('user_id'=>$userid));
					redirect(base_url().'home/messages/blocked');
				}
				else if( $userDetails[0]['user_status'] == '4' ) {
					$this->DatabaseModel->access_database('users','update',array('user_key'=>''),array('user_id'=>$userid));
					redirect(base_url().'home/messages/icon_inactive');
				}
				else {
					redirect(base_url());
				}

			}
			else {
				redirect(base_url().'home/messages/used_activation');
			}
		}
		else {
			redirect(base_url());
		}
	}

	/**************************** Verify Email ENDS **********************/



	/**************** Get States STARTS **********************/
	function getStates(){
		if(isset($_POST['country'])) {
			$data['country'] 		=	$this->DatabaseModel->select_data('*','country','','','',['country_name','ASC']);
			$stateDetails 	= $this->DatabaseModel->select_data('id,name','state',['country_id'=>$_POST['country']],'','',['name','ASC']);
			$str = '<option value="0">State*</option>';
			if(!empty($stateDetails)) {
				foreach($stateDetails as $solo_state) {
					$str .= '<option value="'.$solo_state['id'].'">'. ucfirst(strtolower(trim($solo_state['name']))) .'</option>';
				}
			}
			echo $str;
		}
		else {
			echo '0';
		}
		die();
	}
	/**************************** Get States ENDS **********************/



	/**************************** Message page STARTS *********************/

	function messages($type='') {
		if( $type != '' ) {
			$data['msg'] = '';
			$data['path'] = '';
			$data['type'] = '';
			$data['page_info'] 	= array('page'=>'message','title'=>$type);

			if($type == 'icon_inactive') {
				$data['msg'] = '<span style="color:green;">Your Icon profile is in the process of being accredited, an activation link will be sent to your email upon completion.<span style="color:green;">
								';
				$data['type'] = 'success';
				$data['icon_inactive'] = 1;
			}
			elseif($type == 'inactive') {
				$this->session->sess_destroy();
				$data['msg'] = '<span style="color:red;"> Your account is inactive. Please activate your account first. </span>';
				$data['type'] = 'error';
			}
			elseif($type == 'invalid_account') {
				$data['msg'] = '<span style="color:red;"> Your account does not exist on '. PROJECT .'. </span>';
				$data['type'] = 'error';
			}elseif($type == 'invalid_captcha') {
				$data['msg'] = '<span style="color:red;">reCAPTCHA verification failed. Please try again.</span>';
				$data['type'] = 'error';
			}
			elseif($type == 'invalid_access') {
				$data['msg'] = '<span style="color:red;">This is not a valid access. </span>';
				$data['type'] = 'error';
			}
			elseif($type == 'activation_success') {
				$data['msg'] = '<span style="color:green;"> Your account is verified successfully. </span>
								<br>
								<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl"> Click here to Login </a>';
				$data['type'] = 'success';
			}
			elseif($type == 'reset_link_sent') {
				$data['msg'] = '<span style="color:green;">An email with a link to reset your password has been sent to your registered email. Please check your email..</span>';
				$data['type'] = 'success';
			}
			elseif($type == 'pwd_change_success') {
				$data['msg'] = '<span style="color:green;"> Your password is changed successfully . You can login now.</span>
								<br>
								<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl"> Click here to Login </a>';
				$data['type'] = 'success';
			}
			elseif($type == 'used_activation') {
				$data['msg'] = '<span style="color:red;"> You have already used this activation link. </span>
								<br>
								<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl"> Click here to Login </a>';
				$data['type'] = 'error';
			}
			elseif($type == 'blocked') {
				$this->session->sess_destroy();
				$data['msg'] = '<span style="color:red;">You account is blocked. Please, contact support. </span>';
				$data['type'] = 'error';
			}
			elseif($type == 'upload_success') {
				$data['path'] = 'home/redirectToLastUploadedvideo';
				$data['msg'] = '<span>Your Video has been successfully uploaded to your channel</span>';
				$data['type'] = 'upload_success';
			}elseif($type == 'upload_failed') {
				$data['path'] = 'channel?user='.isset($_SESSION) && isset($_SESSION['user_uname'])?$_SESSION['user_uname']:'my';
				$data['msg'] = '<span style="color:red;">Video uploaded failed due to some technical reason, try again. </span>';
				$data['type'] = 'error';
			}
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/inc/message_page',$data);
			$this->load->view('home/inc/footer',$data);
		}
		else {
			redirect(base_url());
		}
	}

	/**************************** Message page ENDS **********************/

	function redirectToLastUploadedvideo($param=''){
		if(is_login()) {
			$res 	= $this->DatabaseModel->select_data('post_key','channel_post_video',array('user_id'=>is_login()),1,'',array('post_id','DESC'));
			if(isset($res[0]['post_key']) && !empty($res[0]['post_key'])){
				$castcrew = (!empty($param) && $param =='add_castcrew') ? '?castCrew=1' : '';
				redirect(base_url('watch/'.$res[0]['post_key'].''.$castcrew));
			}else{
				redirect(base_url('channel?user='.isset($_SESSION['user_uname'])?$_SESSION['user_uname']:'my'));
			}
		}
	}

	/************************* Forgot Password STARTS **********************/

	function reset_password($key=''){
		if( $key != '' ) {
			$data['key'] = 1;
			if( isset($_POST['pwd']) ) {
				$this->DatabaseModel->access_database('users','update',array('user_password'=>md5($_POST['pwd']),'user_key'=>''),array('user_key'=>$key));

				redirect(base_url().'home/messages/pwd_change_success');

			}
			else {
				$res 	= $this->DatabaseModel->select_data('user_id','users',array('user_key'=>$key),1);
				if(empty($res)) {
					redirect(base_url().'home/messages/invalid_access');
				}
			}
		}
		else {
			if( isset($_POST['forgot_email']) ) {

				$recap = $this->common->validateRecaptcha();
				if($recap['status']){

					$res 	= $this->DatabaseModel->select_data('user_id,user_name','users',array('user_email'=>$_POST['forgot_email']),1);
					if(!empty($res)) {

						$randomstr = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
						$this->DatabaseModel->access_database('users','update',array('user_key'=>md5($randomstr)),array('user_id'=>$res[0]['user_id']));

						if( $res[0]['user_name'] != '' ) {
							$fullname = $res[0]['user_name'];
							$f_arr  = explode(' ',$fullname);

							$firstname = ucfirst($f_arr[0]);
						}
						else {
							$firstname = '';
							$fullname='';
						}


						$email 		= $_POST['forgot_email'];
						$link 		= base_url().'home/reset_password/'.md5($randomstr);

						$subject 	= PROJECT . ' - Reset your password';

						$greeting 	= 'This email was sent automatically by '.PROJECT.' in response to your request to recover your password. This is done for your protection, only you, the recipient of this email can take the next step in the password recovery process.';

						$action 	= 'To reset your password and access your account either click on the orange button below or copy and paste the following link into the address bar of your browser:';
						$button 	= 'Reset Your Password';

						$to 		= '{
											"email":"'.$email.'",
											"name":"'.$fullname.'",
											"type":"to"
										}' ;
						//$this->audition_functions->MailByMandrillforLink($to,$subject,$greeting,$action,$button,$link);

						$this->load->helper('aws_ses_action');
						$r = send_smtp([
							'greeting'=>$greeting,
							'action'=>$action,
							'email'=>NULL,
							'receiver_email'=>$email,
							'password'=>NULL,
							'button'=>$button,
							'link'=>$link,
							'subject'=>$subject,
						]);

						redirect(base_url().'home/messages/reset_link_sent?q='.$r);
					}
					else {
						redirect(base_url().'home/messages/invalid_account?q='.$r);
					}
				}else{
					redirect(base_url().'home/messages/invalid_captcha');
				}
			}

			$data['key'] = 0;
		}

		$data['page_info'] 	= array('page'=>'forgot_pass','title'=>'Forgot Password');

		$this->load->view('home/inc/header',$data);
		$this->load->view('home/forgot_password',$data);
		$this->load->view('home/inc/footer',$data);
	}

	/************************* Forgot Password ENDS **********************/

	function sign_up(){
		$this->audition_functions->manage_my_web_mode_session('');
		if(isset($_SESSION['user_login_id'])){
			redirect(base_url());
		}

		if(isset($_GET['invite']) && !empty($_GET['invite'])){

			$invite = explode('-',$_GET['invite']);
			$_SESSION['referral_by'] 	= isset($invite[0])?$invite[0]:'';
			$_SESSION['referral_from'] 	= isset($invite[1])?$invite[1]:'direct';

			$user 			= $this->DatabaseModel->select_data('user_name','users',array('user_uname'=>$_SESSION['referral_by']),1);
			$user_name 		= (isset($user[0]['user_name']))?$user[0]['user_name']: PROJECT;
			$title 			= 'Invitation from '.$user_name.' to join '. PROJECT;
			$description 	= PROJECT . ' is a streaming VIDEO & SOCIAL PLATFORM that helps Creators in Music, Film, and TV EARN MONEY from their original VIDEO CONTENT GLOBALLY!';

			$data['metaData'] = array(
						'title' 		=> $title,
						'description' 	=> $description,
						'image' 		=> base_url('repo/images/discovered-logo.png')
					);
		}

		$data['page_info'] 		= array('page'=>'signUp','title'=>'Sign Up');

        $this->load->view('home/inc/header',$data);
        $this->load->view('home/signup/sign_up',$data);
		$this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}

	public function account_type(){

		if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard'){
			$this->is_user_login();

			$data['banner_video'] = '';
			$data['artist_category'] = $this->DatabaseModel->select_data(
																'*',
																'artist_category',
																array('level'=>1,'status'=>1,'category_id !='=>130)
															);
															/*Means not official*/
			$data['page_info'] 	= array('page'=>'choose_level','title'=>'Account Type');

			$this->load->view('home/inc/header',$data);
			$this->load->view('home/signup/account_type',$data);
			$this->load->view('home/inc/footer',$data);
		}else if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'express'){
			redirect(base_url('profile?user='.isset($_SESSION['user_uname'])? $_SESSION['user_uname']:'' ));
		}
	}
	public function verify_icon($account_type=''){
		$this->is_user_login();
		$data = [];
		$data['page_info'] = array('page'=>'verify_icon','title'=>'Verify Icon ');
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/signup/verify_icon',$data);
			$this->load->view('home/inc/footer',$data);
	}

	public function primary_type($account_type=''){
		$this->is_user_login();
		$data = [];

		if($account_type == 1){
			 redirect(base_url('home/verify_icon/'.$account_type));
		}
		if($account_type == 5){ /******1 icon and 5 icon approved  , Only For Icon Approval*******/
			$account_type = 1;
		}
		$data['artist_category'] = $this->DatabaseModel->select_data(
															'*',
															'artist_category',
															array('level'=>2,'status'=>1,'parent_id'=>$account_type)
															,'','',
															array('category_name','ASC')
														);

		$_SESSION['account_type'] = ($account_type != '') ? $account_type : '0';

		if($account_type != 4){   	/*account type not equal to fan*/
			$data['page_info'] = array('page'=>'primary_type','title'=>'Primary Type');
			$data['category'] = 	$this->DatabaseModel->select_data('*','artist_category',['category_id'=>$account_type],1);
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/signup/primary_type',$data);
			$this->load->view('home/inc/footer',$data);
		}else{
			redirect(base_url('artist_info'));
		}
	}

	public function add_primary_type(){
		if(isset($_POST['primary_type'])){
			if(!empty($_POST['primary_type'])){
				$_SESSION['primary_type']	= implode(',',$_POST['primary_type']);
				redirect(base_url('artist_info'));
			}
		}
	}


	public function artist_info() {
		$this->is_user_login();

		$userid 		= is_login();
		$account_type 	= isset($_SESSION['account_type'])? $_SESSION['account_type'] : '';
		$primary_type 	= isset($_SESSION['primary_type'])? $_SESSION['primary_type'] : '';

		if( isset($_POST['user_email'])  && !empty($_POST['user_email'])) {

			$data_array['uc_type'] 		= $primary_type;
			$data_array['uc_country'] 	= isset($_POST['country']) 	? $_POST['country'] : '' ;
			$data_array['uc_state'] 	= isset($_POST['state']) 	? $_POST['state'] : '' ;
			$data_array['uc_city'] 		= isset($_POST['city']) 	? $_POST['city'] : '' ;
			$data_array['uc_zipcode'] 	= isset($_POST['uc_zipcode']) ? $_POST['uc_zipcode'] : '' ;
			$data_array['uc_gender'] 	= isset($_POST['uc_gender']) ? $_POST['uc_gender'] : '' ;
			$data_array['uc_dob'] 		= $_POST['y'].'-'.$_POST['m'].'-'.$_POST['d'] ;
			$data_array['uc_phone'] 	= isset($_POST['phone']) ? $_POST['phone'] : '' ;
			$data_array['uc_email'] 	= isset($_POST['user_email']) ? $_POST['user_email'] : '' ;
			$data_array['uc_name'] 		= isset($_POST['cont_name']) ? $_POST['cont_name'] : '' ;
			$data_array['uc_website'] 	= isset($_POST['website']) ? $_POST['website'] : '' ;


			$where 						= ['uc_userid'=> $userid];
			$response 					= $this->DatabaseModel->select_data('uc_userid','users_content',$where,1);

			if(!empty($response)){
				$this->DatabaseModel->access_database('users_content','update',$data_array,$where);
			}else{
				$q = array_merge($where,$data_array);
				$this->DatabaseModel->access_database('users_content','insert',$q);
			}

			$user_status = $_SESSION['user_status'] =  ( $account_type == '1' ) ? 1 : 1 ;

			$update = [
				'user_address'	=>	isset($_POST['user_address']) ? $_POST['user_address'] : '',
				'user_phone'	=>	isset($_POST['user_phone']) ? $_POST['user_phone'] : '' ,
				'user_email'	=>	isset($_POST['user_email']) ? $_POST['user_email'] : '' ,
				'user_cate'		=>	isset($_POST['user_cate']) ? $_POST['user_cate']  : '' ,
				'user_status'	=>	$user_status,
				'user_level'	=>	$account_type,
				'sigup_acc_type'=>	'standard',
			];

			if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard' ){
				$_SESSION['user_uname'] = $update['user_uname'] =  validate_input($_POST['user_uname']);
			}

			$this->DatabaseModel->access_database('users','update',$update,['user_id'=>$userid]);

			/* INSERT NOTIFICATION FOR UPLOAD COVER VIDEO and  UPLOAD OFFICIAL VIDEO START */
			$noti_array = [	'noti_status'	=>	1,
							'noti_type'		=>	5,
							'from_user'		=>	1,
							'to_user'		=>	$userid,
							'created_at'	=>	date('Y-m-d H:i:s')
						];
			$this->DatabaseModel->access_database('notifications','insert',$noti_array);

			if($account_type != 4){
				$noti_array['noti_status'] = 2;
				$this->DatabaseModel->access_database('notifications','insert',$noti_array);
			}

			if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'standard' ){
				redirect(base_url('profile?user='.$update['user_uname']));
			}else{
				redirect(base_url('profile?user='.isset($_SESSION['user_uname'])? $_SESSION['user_uname']:'' ));
			}
		}else{
			$data['country'] 		=	$this->DatabaseModel->select_data('*','country','','','',['country_name','ASC']);
			$data['user_details'] 	= 	$this->DatabaseModel->select_data('user_email,user_name','users',['user_id'=>$userid],1);
			$data['category'] 		= 	$this->DatabaseModel->select_data('*','artist_category',['category_id'=>$account_type],1);
			$data['level'] 			= 	$this->DatabaseModel->select_data('*','levels',['cate_id'=>$account_type,'level_status'=>1]);

			$data['type'] 			= 	$account_type;
			$data['page_info'] 		= 	array('page'=>'artist_info','title'=>'Artist Info');

			$this->load->view('home/inc/header',$data);
			$this->load->view('home/signup/artist_info',$data);
			$this->load->view('home/inc/footer',$data);

		}
	}
	/**************** Set Username STARTS **********************/
	function is_user_exists(){
		$status = 0 ;
		if(isset($_POST['user_uname']) && $_POST['user_uname'] != '') {
			$userDetails 	= $this->DatabaseModel->select_data('user_id','users',array('user_uname'=>$_POST['user_uname']),1);
			if(empty($userDetails)) {
				$status = 1 ;
			}
		}
		if ($this->input->is_ajax_request()) {
			echo $status;exit;
		}

		return  $status ;

	}
	/**************************** Set Username ENDS **********************/

	function is_email_exists(){
		if ($this->input->is_ajax_request()) {
			if(isset($_POST['email']) && !empty($_POST['email'])){
				$user_email = $this->DatabaseModel->select_data('user_id','users',array('user_email'=>trim($_POST['email'])));
				if(!empty($user_email)){
					echo json_encode(array('status'=>1,'message'=>'This email is already registered. Please enter a different email ID.'));
				}else{
					echo json_encode(array('status'=>0));
				}
			}
		}
	}

	public function getCastimages($post_id = null){
		$casts = $this->DatabaseModel->select_data('*','channel_cast_images',array('post_id'=>$post_id));
		echo json_encode($casts);
	}

	public function  getVideoDiscription($post_id){
		if(!empty($post_id)){

			$field = 'channel_post_video.is_video_processed,channel_post_video.iva_id,channel_post_video.title,channel_post_video.user_id,users.user_uname,users.user_name,channel_post_video.description,channel_post_video.uploaded_video,country.country_name,channel_post_video.post_key,mode_of_genre.genre_name,website_mode.mode,channel_post_video.video_duration,channel_post_video.age_restr,artist_category.category_name';


			$join = array('multiple' , array(
												array(	'users',
														'users.user_id 	= channel_post_video.user_id',
														'left'),
												array(	'users_content',
														'users.user_id 	= users_content.uc_userid',
														'left'),
												array(	'country',
														'country.country_id = users_content.uc_country',
														'left'),
												array(	'website_mode',
														'website_mode.mode_id 	= channel_post_video.mode',
														'left'),
												array(	'mode_of_genre',
														'channel_post_video.genre = mode_of_genre.genre_id',
														'left'),
												array(	'artist_category',
														'users.user_level = artist_category.category_id',
														'left'),
											)
										);

			$Video = $this->DatabaseModel->select_data($field,'channel_post_video',array('post_id'=>$post_id),'',$join);

			if(isset($Video[0])){

				$user_id 					= $Video[0]['user_id'];
				$iva_id 					= $Video[0]['iva_id'];
				$upl_video 					= $Video[0]['uploaded_video'];
				$post_key 					= $Video[0]['post_key'];
				$is_video_processed 		= $Video[0]['is_video_processed'];

				$link 						= $this->common->generate_single_content_url_param($post_key , 2);
				$Video[0]['links'] 			= $link;

				$FilterData 				= $this->share_url_encryption->FilterIva($user_id,$iva_id,'',trim($upl_video),false,'.mp4',$is_video_processed);
				$Video[0]['uploaded_video'] = $FilterData['video'];

				$Video[0]['video_duration'] = gmdate("H:i:s", $Video[0]['video_duration'])  ;

				$ages = $this->audition_functions->age();
				$Video[0]['age_restr'] = isset($ages[$Video[0]['age_restr']])?$ages[$Video[0]['age_restr']]:$Video[0]['age_restr'];

				$Video[0]['isMyFavorite'] 	= 0;
				if(isset($this->session->userdata['user_login_id'])){
					$uid 	= $this->session->userdata['user_login_id'];

					$isMyFavorite 	= $this->DatabaseModel->select_data('*','channel_favorite_video',['user_id'=>$uid,'channel_post_id'=>$post_id],1);

					if(!empty($isMyFavorite)){
						$Video[0]['isMyFavorite']	=	1;
					}
				}
				echo json_encode($Video[0]);
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}


	}



	function watchAll(){
		if(isset($_POST) && !empty($_POST)){

			echo $this->SeeALLVideo($_POST['offset'],10);
		}else{
			$data = [];
			$data['Title'] = 'ALL VIDEO';
			if(isset($_GET['v'])){
				$_SESSION['video_types'] = 	str_replace('-','_',$_GET['v']);
				$data['Title'] 			 =	str_replace('-',' ',$_GET['v']);
			}

			if(isset($_GET['mode-id'])){
				$_SESSION['video_mode_id'] = $_GET['mode-id'];
				$this->load->library('Valuelist');
				$web_mode = $this->valuelist->mode()[$_GET['mode-id']];
				$data['Title'] = $web_mode;
			}

			// $data['other_video'] =$this->SeeALLVideo();
			$data['page_info'] 	= array('page'=>'watchAll','title'=>$data['Title']);
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/watchall',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);
		}

	}


	function SeeALLVideo($offset = 0,$limit = 8){

		if(isset($_SESSION['video_types']) && !empty($_SESSION['video_types'])){

			$videoTypes = ['is_live','recently_ended','scheduled_live_streams','mode'];

			if(in_array($_SESSION['video_types'] , $videoTypes)){
				$type 		= 	$_SESSION['video_types'];

				$mode_id	=	'';
				$mode		=	'';

				if(isset($_SESSION['video_mode_id'])){
					$mode_id = $_SESSION['video_mode_id'];
					$this->load->library('Valuelist');
					$web_mode = $this->valuelist->mode();
					$mode 	  = $web_mode[$mode_id];
				}



				$videoData = $this->getSeeAllLiveData($type, $mode_id, $mode, $offset);
				if(isset($videoData[0])){
					return $this->common_html->loadModeVideo($videoData);
				}else{
					return '';
				}

			}else{

				$mode_id	=	mode();

				if(isset($_POST['mode_id'])){
					$mode_id = $_POST['mode_id'];
				}

				$freshVideoData = $this->DatabaseModel->select_data('data,slider_title,slider_type,slider_category_slug', 'homepage_sliders' , array('type' => $_SESSION['video_types'], 'mode'=>$mode_id) ,1);

				if(isset($freshVideoData[0]) && !empty($freshVideoData[0])){
					$vidList 	= $freshVideoData[0]['data'] ;  /*mode id from info helper*/
					$title 		= $freshVideoData[0]['slider_title'] ;  /*mode id from info helper*/

					if($freshVideoData[0]['slider_type'] == 'single'){
						$where = '';

						if(!empty($freshVideoData[0]['slider_category_slug'])){ // for getDiscovered videos only

							$CateData = $this->DatabaseModel->select_data('category_id','artist_category',array('category_slug' =>$freshVideoData[0]['slider_category_slug']));
							if(!empty($CateData)){
								$catIds = array_column($CateData, 'category_id');
								$catIds = implode(',',$catIds);

								$cond = "category IN($catIds) AND upload_source_page = 'getdiscovered' AND active_status = 1 AND complete_status = 1";

								$videoData = $this->DatabaseModel->select_data('post_id','channel_post_video use INDEX(post_id)',$cond,'','',array('post_id','DESC'));
								if(!empty($videoData)){
									$vidListArray = array_column($videoData, 'post_id');
									$vidList = implode(',',$vidListArray);
								}
							}
						}

						if(!empty($vidList)){
							$where  .= "channel_post_video.post_id IN($vidList) AND ";
						}

						$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.slug,channel_post_video.iva_id,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.video_duration";

						/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
						$where .= $this->common->channelGlobalCond();




						$join  = array('multiple',
										array(
											array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
											array('users' , 'users.user_id = channel_post_video.user_id'),
										)
									);

						$order = array('post_id','DESC');
						if($title == 'Top Videos Of The Month' || $title == 'Most Popular Videos'){
							$order = array('count_views','DESC');
						}

						$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$offset),$join,$order);

						if(isset($videoData[0])){
							return $this->common_html->loadModeVideo($videoData)	;
						}else{
							return '';
						}
					}else
					if($freshVideoData[0]['slider_type'] == 'playlist'){
						$field = "channel_video_playlist.user_id as playlist_user_id,channel_video_playlist.video_ids,channel_post_video.post_id,channel_video_playlist.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id,channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.user_id,channel_video_playlist.playlist_thumb";

						$where = "channel_video_playlist.privacy_status = 7 AND channel_video_playlist.playlist_id IN($vidList) AND " . $this->common->channelGlobalCond();
						/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/

						$join  = array(
									'multiple',
									array(
										array('channel_video_playlist use INDEX(first_video_id)' , 'channel_video_playlist.first_video_id = channel_post_video.post_id'),
										array('channel_post_thumb use INDEX(post_id)' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
										array('users use INDEX(user_id)' , 'users.user_id = channel_post_video.user_id'),
									)
								);

						$order = "FIELD(channel_video_playlist.playlist_id,$vidList)";

						$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$offset),$join,$order);
						if(isset($videoData[0])){
							return $this->common_html->loadModeVideo($videoData)	;
						}else{
							return '';
						}

					}


				}else{
					redirect(base_url());
				}
			}
		}else{
			redirect(base_url());
		}

	}


	function privacy_policy(){
		$this->load->view('common/privacy_policy');
	}

	function email_template(){
		$this->load->view('common/email_template');
	}

	function mail_notificaiton_template(){
		$this->load->view('common/mail_notificaiton_template');
	}

	function four_zero_four(){

		$data['page_info'] 	= array('page'=>'404','title'=>'404');
		$this->load->view('home/inc/header',$data);
		$this->load->view('home/404',$data);
        $this->load->view('home/inc/footer',$data);
	}

	function is_user_login(){
		if(!is_login()) {
			redirect(base_url().'sign-up');
		}
	}

	function SetTimeZoneOffSet(){
		$_SESSION['TimeZoneOffset'] = isset($_POST['offset'])?$_POST['offset']:0;
	}

	function ExpressToStandard(){
		// if(isset($_SESSION['sigup_acc_type']) && $_SESSION['sigup_acc_type'] == 'express'){
			$_SESSION['upgrade_account'] = 1;
			$_SESSION['sigup_acc_type'] = 'standard';
			redirect(base_url('account_type'));
		// }else{
		// 	redirect(base_url('settings'));
		// }
	}
	function CanclUpgrdAccProcess(){
		$_SESSION['upgrade_account'] = 0;
		$_SESSION['sigup_acc_type'] = 'express';
		redirect(base_url('settings'));
	}

	function saveWatchHistory(){
		if ($this->input->is_ajax_request()) {
			if(isset($_SESSION['user_login_id']) && isset($_POST['watch_history']) && !empty($_POST['watch_history'])){

				$where =array('user_id'=>$_SESSION['user_login_id']);

				$response = $this->DatabaseModel->select_data('id,watch_data','watch_history',$where,1);

				if(!empty($response)){
					if($response[0]['watch_data'] !=''){
						$newWatch 		= json_decode($_POST['watch_history'],true);
						$previousWatch  = json_decode($response[0]['watch_data'],true);
						$this->newWatchList = $previousWatch;
						if(!empty($previousWatch)){
							foreach($previousWatch as $key=>$pList){
								$this->checkvideoExist($key , $pList, $newWatch);
							}
						}
					}

					$update_array = ['user_id'=>$_SESSION['user_login_id'], 'watch_data'=>json_encode(array_slice($this->newWatchList , -10))];

					$this->DatabaseModel->access_database('watch_history','update',$update_array,$where);
				}else{
					$watchList = json_decode($_POST['watch_history'], true);
					$watchList  = array_slice($watchList , -10 );
					$insert_array = ['user_id'=>$_SESSION['user_login_id'], 'watch_data'=>json_encode($watchList)];
					$this->DatabaseModel->access_database('watch_history','insert',$insert_array);
				}
				echo json_encode(array('status'=>1,'message'=>'watch list save successfully.'));
			}else{
				echo json_encode(array('status'=>0));
			}
		}else{
			echo json_encode(array('status'=>0));
		}
	}


	function checkvideoExist($key, $pList, $newWatch){
		if(!empty($newWatch) && !empty($pList)){
			foreach($newWatch as $nList){
				if(isset($pList['vid']) && isset($nList['vid'])){
					if($pList['vid'] == $nList['vid']){
						$this->newWatchList[$key]['time'] = $nList['time'];
					}else{
						if($this->searchForId($nList['vid'], $this->newWatchList) ===null){
							array_push($this->newWatchList,array('vid'=>$nList['vid'], 'time'=>$nList['time'], 'plist_id'=>isset($nList['plist_id'])?$nList['plist_id']:""));
						}
					}
				}
			}
		}
	}


	function searchForId($id, $array) {
		foreach ($array as $key => $val) {
		   if ($val['vid'] === $id) {
			   return $key;
		   }
		}
		return null;
	}


	function getWatchHistory($return=false){
		if ($this->input->is_ajax_request() && isset($_SESSION['user_login_id'])) {

			$where =array('user_id'=>$_SESSION['user_login_id']);

			$response = $this->DatabaseModel->select_data('watch_data','watch_history',$where,1);

			if(!empty($response)){
				$data = $response[0]['watch_data'];

				$resp = json_encode(array('status'=>1,'message'=>'watch list.', 'data'=>$data));
			}else{
				$resp = json_encode(array('status'=>0, 'data'=>[]));
			}
		}else{
			$resp = json_encode(array('status'=>0, 'data'=>[]));
		}
		if($return){
			return $resp;
		}else{
			echo $resp;
		}
	}



	function getUserWatchList(){

		$data = json_decode($this->getWatchHistory(true) , true);

		if($data['status']==1){
			$vid=[];

			$watch = (json_decode($data['data'],true) !=null) ? json_decode($data['data'],true) : [];

			foreach($watch as $val){
				array_push($vid, $val['vid']);
			}

			$vidList = implode(',',array_reverse($vid));

			$start   = 0;
			$limit   = 8;

			$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id, channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type"; //channel_video_playlist.playlist_id

			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			$where = "channel_post_video.post_id IN($vidList) AND " . $this->common->channelGlobalCond();



			$join  = array(
							'multiple',
							array(
								//array('channel_video_playlist' , 'channel_video_playlist.video_ids LIKE CONCAT("%",channel_post_video.post_id, "%") AND channel_video_playlist.playlist_type = 2','left'),
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
								array('users' , 'users.user_id = channel_post_video.user_id'),
							)
						);

			$order ="FIELD(channel_post_video.post_id,$vidList)";
			$title = $type = 'Continue Watching';

			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,$limit,$join,$order);

			if(!empty($videoData)){

				//$playlistId_arr = array_column($videoData, 'playlist_id');

				$color 	= ($start%2 == 0)? "bg-white" : "";

				$autoplay=array("2000","2500","3000");
				$random_keys=array_rand($autoplay,1);
				$auto = $autoplay[$random_keys];

				$htmlarray = array(	'color'=>$color,
									'title'=>$title,
									'type'=>'',
									'href' =>'',
									'auto' =>$auto,
									'videoData'=>$this->common_html->swiper_slider_without_html($videoData,$watch)
								);

				return $htmlarray;
			}else{
				return array();
			}
		}else{
			return array();
		}
	}

	function getLiveData($type, $mode_id='', $mode=''){
			$start   = 0;
			$limit   = 10;

			$joinCond = array('users_medialive_info' , 'users_medialive_info.user_id = users.user_id','left');

			if($type ==	'is_live'){
				$condition =" AND channel_post_video.is_stream_live = 1 AND ( users_medialive_info.is_scheduled != 1  || users_medialive_info.schedule_time < '". gmdate("Y-m-d H:i:s") ."' )";
				$title = 'Live Now';
			}

			if($type == 'recently_ended'){
				$condition =" AND channel_post_video.created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
				$condition =" AND  channel_post_video.is_stream_live = 0 AND users_medialive_info.is_scheduled = 0 ";
				$title = 'Recently Ended Streams';
			}

			if($type =='scheduled_live_streams'){
				$condition =" AND users_medialive_info.schedule_time > '". gmdate("Y-m-d H:i:s") ."' AND users_medialive_info.is_scheduled = 1 ";
				$title = 'Scheduled Live Streams';
				$joinCond = array('users_medialive_info' , 'users_medialive_info.live_pid = channel_post_video.post_id');
			}

			if($type =="mode"){
				$condition =" AND channel_post_video.mode = {$mode_id}";
				$title = $mode;
			}

			$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.iva_id, channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type,users_medialive_info.schedule_time";

			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			$where = "channel_post_video.video_type = 2 {$condition} AND " . $this->common->channelGlobalCond();

			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
								array('users' , 'users.user_id = channel_post_video.user_id'),
								$joinCond ,
							)
						);

			$order = array('channel_post_video.post_id','DESC');

			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$start),$join,$order);

			if(!empty($videoData)){

				$color 		= 	($start%2 == 0)? "bg-white" : "";
				$autoplay	=	array("2000","2500","3000");
				$random_keys=	array_rand($autoplay,1);
				$auto 		= 	$autoplay[$random_keys];
				$slug 		= 	urlencode(str_replace('_','-',$type));
				$href       = 	base_url('watch-all?v='.$slug);
				if($type =="mode"){
					$href  .= '&mode-id='.$mode_id;
				}
				$href 		= 	'<a  href="'.$href.'" class="dis_sh_btn muli_font">See All<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
								<path  fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z" />
								</svg>
								</span>
								</a>';

				$htmlarray = array(	'color'=>$color,
									'title'=>$title,
									'type'=>$type,
									'href' =>$href,
									'auto' =>$auto,
									'videoData'=>$this->common_html->swiper_slider_without_html($videoData)
								);

				return $htmlarray;
			}else{
				return array();
			}

	}

	function getSeeAllLiveData($type, $mode_id='', $mode='', $offset){
			$mode_id 	= preg_replace( '/[^0-9]/', '', $mode_id );
			$start   = $offset;
			$limit   = 8;

			$joinCond = array('users_medialive_info' , 'users_medialive_info.user_id = users.user_id','left');

			if($type ==	'is_live'){
				$condition =" AND channel_post_video.is_stream_live = 1 AND ( users_medialive_info.is_scheduled != 1  || users_medialive_info.schedule_time < '". gmdate("Y-m-d H:i:s") ."' )";
				$title = 'Live Now';
			}

			if($type == 'recently_ended'){
				$condition =" AND channel_post_video.created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
				$condition =" AND  channel_post_video.is_stream_live = 0 AND users_medialive_info.is_scheduled = 0 ";
				$title = 'Recently Ended';
			}

			if($type =='scheduled_live_streams'){
				$condition =" AND users_medialive_info.schedule_time > '". gmdate("Y-m-d H:i:s") ."' AND users_medialive_info.is_scheduled = 1 ";
				$title = 'Scheduled Live Streams';

				$joinCond = array('users_medialive_info' , 'users_medialive_info.live_pid = channel_post_video.post_id');
			}

			if($type =="mode"){
				$condition =" AND channel_post_video.mode = {$mode_id}";
				$title = $mode;
			}

			$field = "channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.post_key,channel_post_thumb.image_name,channel_post_video.slug,channel_post_video.iva_id,channel_post_video.video_duration,channel_post_video.uploaded_video,channel_post_video.is_video_processed,channel_post_video.video_type";

			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			$where = "channel_post_video.video_type = 2 {$condition} AND " . $this->common->channelGlobalCond();

			$join  = array(
							'multiple',
							array(
								array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
								array('users' , 'users.user_id = channel_post_video.user_id'),
								$joinCond ,
							)
						);

			$order = array('channel_post_video.post_id','DESC');

			$videoData = $this->DatabaseModel->select_data($field,'channel_post_video use INDEX(post_id)',$where,array($limit,$start),$join,$order);

			if(!empty($videoData)){
				return $videoData;
			}else{
				return array();
			}

	}

	function urbanone(){
		$url =  base_url('uploads/admin/urbanone.pdf');

		$html = '<iframe src="'.$url.'" style="border:none; width: 100%; height: 100%"></iframe>';
		echo $html;
	}

	function perks(){
		if(!is_login()) {
			$data['page_info'] = array('page'=> 'perks' ,'title'=>'Perks');
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/other_pages/perks',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);
		}else{
			redirect(base_url());
		}
	}
	function spotlight(){
		//if(!is_login()) {
			$data['page_info'] = array('page'=> 'creatorinfo' ,'title'=>'Spotlight');
			$this->load->view('home/inc/header',$data);
			$this->load->view('home/other_pages/creatorinfo',$data);
			$this->load->view('common/notofication_popup');
			$this->load->view('home/inc/footer',$data);
		/*}else{
			redirect(base_url());
		}*/
	}
	function about(){
		$data['page_info'] = array('page'=> 'about' ,'title'=>'About');
		$this->load->view('home/inc/header',$data);
	    $this->load->view('home/other_pages/about',$data);
		$this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
	}
}
