
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audition_functions {
	private $uid;
	public function __construct()
	{
        $this->CI = get_instance();

		if(isset($this->CI->session->userdata['user_login_id']))
		$this->uid =  $this->CI->session->userdata['user_login_id'];

		// if(!isset($_SESSION["website_mode"]['name'])){
		// 	$_SESSION["website_mode"]['name'] = 'music';
		// 	$_SESSION["website_mode"]['id']   = 1;
		// }
	}


	function manage_my_web_mode_session($modeName){
		if(!empty($modeName)){
			$this->CI->load->library('valuelist');
			$mode_id = $this->CI->valuelist->website_mode($modeName);
			$_SESSION['website_mode'] = array('name' => $modeName, 'id' => $mode_id);
		}
		else{
			$_SESSION['website_mode'] = array('name' => '', 'id' => '');
		}
	}


	public function get_header_menu(){
		return $header_menu = $this->CI->DatabaseModel->access_database('website_mode','orderby',array('mode_order','ASC'),array('status'=>1));
	}



	public function browse_genre(){
		$mode_id = $_SESSION["website_mode"]['id'];

		$where = "channel_post_video.mode = {$mode_id} AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND users.user_status = '1'";

		$field = 'mode_of_genre.genre_name,mode_of_genre.genre_slug,mode_of_genre.genre_id,mode_of_genre.mode_id,mode_of_genre.image, (select COUNT(post_id) from channel_post_video use INDEX(genre) LEFT JOIN users ON users.user_id = channel_post_video.user_id where genre = genre_id AND '. $where .') as genre_total_video';

		$where .= " AND mode_of_genre.mode_id = {$mode_id} AND mode_of_genre.status = 1";

		$where .= " AND channel_post_thumb.active_thumb = 1";

		$GROUP_BY = "mode_of_genre.genre_id";

		$P = base_url().'repo_admin/images/genre/';

		$join  = array(
						'multiple',
						array(
							array('website_mode','website_mode.mode_id = mode_of_genre.mode_id','left'),
							array('channel_post_video','channel_post_video.genre= mode_of_genre.genre_id','left'),
							array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id',
								  'left'),
							array('users' , 'users.user_id = channel_post_video.user_id'),
						)
					);

		// $ORDER_BY = array('mode_of_genre.browse_order','ASC');
		$ORDER_BY = array('genre_total_video','DESC');

		$result =  $this->CI->DatabaseModel->select_data($field,'mode_of_genre',$where,11,$join,$ORDER_BY,'',$GROUP_BY);

		$key_values = array_column($result, 'genre_name');
		array_multisort($key_values, SORT_ASC, $result);

		return $result;

	}


	public function get_icon_menu(){
		$icon_menus 	= json_decode($this->get_website_info('icon_menu',mode('name')));

		if(!empty($icon_menus)){
			$icon_menus 	= implode(",",$icon_menus);
			$cond = "users_content.uc_userid IN({$icon_menus})";
			$join = array('multiple' , array(
									array(	'users',
											'users_content.uc_userid = users.user_id',
											'left'),
									array(	'artist_category',
											'users_content.uc_type 	 = artist_category.category_id',
											'left'),
									));
			return $this->CI->DatabaseModel->select_data('artist_category.category_name,users.user_uname','users_content',$cond,'',$join);
		}
	}

	public function get_sub_category_menu(){


		$where = "channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND users.user_status = '1'";

		$field = 'category_id,artist_category.category_slug,artist_category.category_name,(select COUNT(post_id) from channel_post_video LEFT JOIN users ON users.user_id = channel_post_video.user_id where channel_post_video.category = category_id AND '. $where .') as cate_total_video';

		$where .=" AND artist_category.parent_id = 1 ";

		$GROUP_BY = "channel_post_video.category";

		$join  = array(
			'multiple',
			array(
				array('channel_post_video','channel_post_video.category= artist_category.category_id','left'),
				array('users' , 'users.user_id = channel_post_video.user_id'),
			)
		);

		$ORDER_BY = array('cate_total_video','DESC');

		$result = $this->CI->DatabaseModel->select_data($field,'artist_category use INDEX(category_id)',$where,11,$join,$ORDER_BY,'',$GROUP_BY);

		$key_values = array_column($result, 'category_name');
		array_multisort($key_values, SORT_ASC, $result);


		return $result;
	}

	public function get_user_fullname($uid,$coloum=null){
     	$userDetail = $this->CI->DatabaseModel->access_database('users','select','',array('user_id'=>$uid));

		if($coloum == 'user_uname' ){
			if(isset($userDetail[0]['user_uname'])){
				return $userDetail[0]['user_uname'];
			}
		}else{
			if(isset($userDetail[0]['user_name'])){
				return $userDetail[0]['user_name'];
			}
		}


	}



	public function get_cover_image($mode=null){       /*GET COVER IMAGE FOR HOMAPAGES*/
		if($mode == null){
			$mode = mode('name');
		}
		$cover_image = $this->get_website_info('cover_image',$mode);

		if(!empty($cover_image)){
			return  base_url('repo_admin/images/homepage/').$cover_image;
		}
	}



	public function get_cover_video($mode=null){
		if($mode == null){
			$mode 		= mode('name');
			$mode_id 	= mode('id');
		}else{
			$this->CI->load->library('valuelist');
			$mode_id = $this->CI->valuelist->website_mode($mode);
		}

		$detail = $this->CI->DatabaseModel->select_data('cover_video,cover_video_link','page_setting',['website_mode'=> $mode_id ],1);

		$cover_video_id 	= isset($detail['0']['cover_video'])? $detail['0']['cover_video']:[];
		$cover_video_link 	= isset($detail['0']['cover_video_link'])? json_decode($detail['0']['cover_video_link'],true):[];

		if(!empty($cover_video_id)){
			/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
			$where =  	$this->CI->common->channelGlobalCond([1,1,7,0,1,1,NULL]) . " channel_post_video.post_id IN({$cover_video_id})";

			$order =	"FIELD(channel_post_video.post_id,$cover_video_id)";

			$join = array('multiple' , array(
			array(	'channel_post_thumb',
					'channel_post_thumb.post_id = channel_post_video.post_id',
					'left'),
			array(	'users',
					'users.user_id 				= channel_post_video.user_id',
					'left'),
			));

			$data =  	$this->CI->DatabaseModel->select_data('channel_post_video.post_key,channel_post_video.is_video_processed,channel_post_video.video_type,channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.uploaded_video','channel_post_video use INDEX(post_id)',$where,5, $join , $order );

			$cover_videos = [];
			foreach($data as $i => $d){
				$vid  				= 	$d['uploaded_video'] ;
				$key 				= 	explode('.',$vid);
				$folder 			= 	explode('/',$key[0]);

				$url 				= 	AMAZON_URL .$vid;
				$preview 			= 	($d['is_video_processed'] == 1 ) ? AMAZON_TRANCODE_URL.$key[0].'/'.$folder[2].'.mp4': $url ;
				$post_key			=	base_url($this->CI->common->generate_single_content_url_param($d['post_key'] , 2));

				$AdsBtn 			= 	isset($cover_video_link[$d['post_id']])?$cover_video_link[$d['post_id']]:[];
				$cover_videos[$i] 	=   ['user_id'=>$d['user_id'],'post_id'=>$d['post_id'],'url'=>$url,'title'=>$d['title'],'preview'=>$preview,'post_key'=>$post_key,'AdsBtn'=>$AdsBtn];
			}
			return $cover_videos;
		}
	}


	public function get_website_info($field,$website_mode){
		$this->CI->load->library('valuelist');
		$mode_id = $this->CI->valuelist->website_mode($website_mode);

		$detail = $this->CI->DatabaseModel->select_data($field,'page_setting',['website_mode'=>$mode_id],1);

		if(isset($detail[0][$field])){
			return $detail[0][$field];
		}
	}
	function founderClubHtml(){
		return  file_get_contents( base_url('repo/email_doc/founders_club_designation.html') );
	}

	function HtmlMailByMandrill($user_email,$fullname,$subj,$em_msg){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'https://mandrillapp.com/api/1.0/messages/send.json');
		$params = array(
			"key" => "NtSxVXvKNB_5JQOjv5bFTw",
			"message" => array(
				"html" => $em_msg,
				"text" => $em_msg,
				"to" => array(
					array("name" => $fullname, "email" => $user_email , "type" => "to")
				),
				"from_email" => 'support@discovered.tv',
				"from_name" => 'Team Discovered',
				"subject" => $subj,
			),
			"async" => false,
			"ip_pool" => "Main Pool"
		);
		curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode( $params) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		// print_r($result);die;
		if(isset($result[0]['status'])){
			if($result[0]['status'] == 'sent'){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	function MailByMandrill($user_email,$fullname,$subj,$em_msg){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'https://mandrillapp.com/api/1.0/messages/send-template.json');
		curl_setopt($ch,CURLOPT_POSTFIELDS, '{
			"key":"NtSxVXvKNB_5JQOjv5bFTw",
			"template_name":"Discovered",
			"template_content":[
				{
					"name":"emailmessage",
					"content":"'.$em_msg.'"
				}
			],
			"message":{
			"to":[
				{
					"email":"'.$user_email.'",
					"name":"'.$fullname.'",
					"type":"to"
				}
			],
			"subject":"'.$subj.'"
			},
			"async":false,
			"ip_pool":"Main Pool"
		}');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		if(isset($result[0]['status'])){
			if($result[0]['status'] == 'sent'){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	function MailByMandrillforLink($to,$subj,$greeting,$action,$button,$link){
		$year = date('Y');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'https://mandrillapp.com/api/1.0/messages/send-template.json');
		curl_setopt($ch,CURLOPT_POSTFIELDS, '{
			"key":"NtSxVXvKNB_5JQOjv5bFTw",
			"template_name":"Discovered",
			"template_content":[],
			 "message":{
				"to":[
					'.$to.'
				],
				"subject":"'.$subj.'",
				"merge": true,
				"global_merge_vars": [
					{
						"name": "GREETING",
						"content": "'.$greeting.'"
					},
					{
						"name": "ACTION",
						"content": "'.$action.'"
					},
					{
						"name": "BUTTON",
						"content": "'.$button.'"
					},
					{
						"name": "TLINK",
						"content": "'.$link.'"
					}
					,
					{
						"name": "YEAR",
						"content": "'.$year.'"
					}
				]
			},
			"async":false,
			"ip_pool":"Main Pool"
		}');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		// print_r($result);
		if(isset($result[0]['status'])){
			if($result[0]['status'] == 'sent'){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}

	}



	function MailByMandrillForRegstr($user_email,$fullname,$subj,$greeting,$action,$email,$password){
		$year = date('Y');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'https://mandrillapp.com/api/1.0/messages/send-template.json');
		curl_setopt($ch,CURLOPT_POSTFIELDS, '{
			"key":"NtSxVXvKNB_5JQOjv5bFTw",
			"template_name":"Discovered",
			"template_content":[],
			 "message":{
				"to":[
				{"email":"'.$user_email.'","name":"'.$fullname.'","type":"to"}
				],
				"subject":"'.$subj.'",
				"merge": true,
				"global_merge_vars": [
					{
						"name": "GREETING",
						"content": "'.$greeting.'"
					},
					{
						"name": "ACTION",
						"content": "'.$action.'"
					},{
						 "name": "VIVEKKADATA",
						 "content": "'.$email.'"
					 },
					{
						"name": "PASSWORD",
						"content": "'.$password.'"
					},
					{
						"name": "YEAR",
						"content": "'.$year.'"
					}
				]
			},
			"async"	:false,
			"ip_pool":"Main Pool"
		}');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
			if(isset($result[0]['status'])){
				if($result[0]['status'] == 'sent'){
					return 1;
				}else{
					return 0;
				}
			}else{
				return 0;
			}

	}



	function sendNotification($token_array,$msg_array){
		$resp = [];
		if(!isset($msg_array['noti_type'])){
			$msg_array['noti_type']='other';
		}

		if(isset($token_array['web']) && !empty($token_array['web'])){

			$payload =  [
					'registration_ids'=> (is_array($token_array['web'])) ? $token_array['web'] : [$token_array['web']],
					'data'=>$msg_array,
					'notification' =>$msg_array,
					//'priority'=>'high'
					];
			$resp['web'] = $this->push_notification($payload);
		}

		if(isset($token_array['android']) && !empty($token_array['android'])){

			unset($msg_array['click_action']);
			$payload =  [
					'registration_ids'=> (is_array($token_array['android'])) ? $token_array['android'] : [$token_array['android']],
					'data'=>$msg_array,
					//'priority'=>'high'	 //'normal'
					];
			$resp['android'] = $this->push_notification($payload);
		}

		if(isset($token_array['ios']) && !empty($token_array['ios'])){

			unset($msg_array['click_action']);
			$payload =  [
					'registration_ids'=> (is_array($token_array['ios'])) ? $token_array['ios'] : [$token_array['ios']],
					'data'=>$msg_array,
					'notification' =>$msg_array,
					//'priority'=>'5'	//'5' or 'normal or 'high'
					];
			$resp['ios'] = $this->push_notification($payload);
		}
		return $resp;
	}



	function push_notification($payload){

		$serverKey ='AAAAeRH2T_8:APA91bGcOQhQTSKdLyNXdLn3ftrm_3KsjUSfTPw6YXWNj5DothvoKhqJAn34TAnvADJQjGKk8HS1r_OcG-l29Ay8y3usQBAp_4eBjTJTvNBnzGcgdU2VOm0Wg5haXZtPNqoHf4c9aBnH'; //'AIzaSyDCS-3AfN3en_KvQBh8lkuue7GPRPYlgKU';
		//$serverKey ='AAAApoZM67A:APA91bGu6d2Obo0XiH3DkfVsIn4waFclhqvNYfViULvI6NgVKiL-NzsjnDPNaf7qNzSDJ0jcRLBHjV15IBVG0jE29lq0Zl8rfDBtkIHYtu6dZcY4JJcuNUwicv-tpjwOk1QjKWhue3s2';
		$header = [
			'Authorization:key=' .$serverKey,
			'Content-Type: application/json'
		];

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode($payload),
		  CURLOPT_HTTPHEADER => $header,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return "cURL Error #:" . $err;
		} else {
		  return $response;
		}

	}



	public function getFirebaseToken($user_id){
		$token = $this->CI->DatabaseModel->select_data('user_firebase_token','users',array('user_id'=>$user_id),1);
		$tokn='';
		if(isset($token[0]['user_firebase_token']) && !empty($token[0]['user_firebase_token'])){
			$tokn = json_decode($token[0]['user_firebase_token'],true);
		}
		return $tokn;
	}

	public function getFireBaseTokenOfMyFans($user_id = NULL,$limit = 1000 ,$start = 0 , $with_user = false){
		$join  = array(
					'multiple',
					array(
						array('users' , 'become_a_fan.following_id  = users.user_id',),

					)
				);

		$cond = ($user_id != NULL)? array('become_a_fan.user_id'=>$user_id) : '';

		$field = 'user_firebase_token';
		if($with_user){
			$field = 'user_firebase_token,following_id';
		}
		$tokens = $this->CI->DatabaseModel->select_data($field,'become_a_fan use INDEX(user_id)',$cond ,array($limit,$start),$join);

		$TokenData = [];
		$UserData = [];
		foreach($tokens as $tok){
			if(isset($tok['user_firebase_token']) && !empty($tok['user_firebase_token'])){
				$tokn = json_decode($tok['user_firebase_token'],true);
				$TokenData['web'][] 	=  isset($tokn['web']) && !empty($tokn['web']) ? $tokn['web'] : '';
				$TokenData['android'][] =  isset($tokn['android']) && !empty($tokn['android']) ? $tokn['android'] : '';
				$TokenData['ios'][] 	=  isset($tokn['ios']) && !empty($tokn['ios']) ? $tokn['ios'] : '';
				if($with_user)
				$UserData[] = $tok['following_id'];
			}
		}
		if($with_user){
			return ['TokenData'=>$TokenData,'UserData'=>$UserData];
		}else{
			return $TokenData;
		}
	}

	/* public function getFireBaseTokenOfALLFans($limit = 1000 ,$start = 0){
		$join  = array(
					'multiple',
					array(
						array('users' , 'become_a_fan.following_id  = users.user_id',),

					)
				);

		$tokens = $this->CI->DatabaseModel->select_data('user_firebase_token','become_a_fan use INDEX(user_id)','',array($limit,$start),$join);

		$TokenData = [];
		foreach($tokens as $tok){
			if(isset($tok['user_firebase_token']) && !empty($tok['user_firebase_token'])){
				$tokn = json_decode($tok['user_firebase_token'],true);
				$TokenData['web'][] =  isset($tokn['web']) && !empty($tokn['web']) ? $tokn['web'] : '';
				$TokenData['android'][] =  isset($tokn['android']) && !empty($tokn['android']) ? $tokn['android'] : '';
			}
		}
		return $TokenData;
	} */


     public function get_user_link($uid){

        $bodyhead="<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title>Audition Live</title>
        </head><body>";

        $from = 'Help';
        $from_add = 'help@discovered.com';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "From: =?UTF-8?B?". base64_encode($from) ."?= <$from_add>\r\n" .
        'Reply-To: '.$from_add . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to,$subject,$bodyhead.$body.'</body></html>',$headers, '-f'.$from_add);
	}



	function upload_file($uploadPath,$allowedType,$fileName,$encrypt= false ,$fileSize = null){

		$config['upload_path']          = $uploadPath;
		$config['allowed_types']        = $allowedType;
		$config['encrypt_name'] 		= $encrypt;
		$config['max_size']      		= $fileSize;

		$this->CI->load->library('upload', $config);
		if ($this->CI->upload->do_upload($fileName)){

			$ud		=	$this->CI->upload->data();
			$image	=	$ud['raw_name'].$ud['file_ext'];

			return array('file_name'=>$image,'file_type'=>$ud['file_ext'],'w'=>$ud['image_width'],'h'=>$ud['image_height']);

		}else{
			return 0;
	        return $this->CI->upload->display_errors();
	    }
	}



	function upload_files($uploadPath,$allowed_types,$files,$encrypt= false ,$fileSize = null ){

		$config['upload_path']          = $uploadPath;
		$config['allowed_types']        = $allowed_types;
		$config['encrypt_name'] 		= $encrypt;
		$config['max_size']      		= $fileSize;

        $this->CI->load->library('upload', $config);
        $array=array();
        $images = array();

		if(!empty($files['image']['name'][0])){
        foreach ($files['image']['name'] as $key => $image) {

            $_FILES['images[]']['name']= $files['image']['name'][$key];
            $_FILES['images[]']['type']= $files['image']['type'][$key];
            $_FILES['images[]']['tmp_name']= $files['image']['tmp_name'][$key];
            $_FILES['images[]']['error']= $files['image']['error'][$key];
            $_FILES['images[]']['size']= $files['image']['size'][$key];

	        $uniqid = uniqid();
	        $fileName = $uniqid .'_'. $image;
	        $config['file_name'] = $fileName;
	        $this->CI->upload->initialize($config);

           if  ($this->CI->upload->do_upload('images[]'))
           {
	           $upload_data =  $this->CI->upload->data();

	           $image=$upload_data['raw_name'].$upload_data['file_ext'];
	           $array[]=array('file_name'=>$image,'file_type'=>$upload_data['file_ext']);
	        }
			else {
				return 0;
	            // return $this->CI->upload->display_errors();
	        }
	    }
	}
       return $array;

	}


	public function resizeImage($width,$height,$source_path,$target_path,$maintain_ratio = TRUE,$create_thumb= TRUE,$q=90){
		$config_manip = array(
			'image_library' 	=> 'imagemagick',
			'library_path' 	=> '/usr/bin/convert',
			'width' 			=> $width,
			'height' 			=> $height,
			'source_image' 	=> $source_path,
			'new_image' 		=> $target_path,
			'maintain_ratio' 	=> $maintain_ratio,
			'create_thumb' 	=> $create_thumb,
			'quality'			=> $q
		);


		$this->CI->load->library('image_lib', $config_manip);
		$this->CI->image_lib->initialize($config_manip);
		if (!$this->CI->image_lib->resize()) {
			return 0;
			return $this->CI->image_lib->display_errors();
		}else{
			$this->CI->image_lib->clear();
		return true;
		}
	}

	function rotateImage($path){
		$pathToImages = ABS_PATH .$path;
		if(file_exists($pathToImages)){
			$config=array();
			$config['image_library']   	= 'gd2';
			$config['source_image'] 	= $pathToImages;
			$config['rotation_angle'] 	= '90';
			$this->CI->load->library('image_lib',$config);
			$this->CI->image_lib->initialize($config); // reinitialize it instead of reloading

			if (!$this->CI->image_lib->rotate()) {
				$this->image_lib->clear();
				return 0;
				return $this->CI->image_lib->display_errors();
			}else{
				$this->CI->image_lib->clear();
				return true;
			}
		}
	}


	public function createThumb($video,$size,$filePath,$fileType){
		$fileName = rand().'.'.$fileType;
		$cmd = "ffmpeg -i {$video} -ss 0 -vframes 1 -s {$size} {$filePath}{$fileName}";

		exec ($cmd,$output,$responce );
		if($responce == 0){				/* 0 means successfully done*/
			return $fileName;
		}else{
			return false;
		}
	}



	function videoDuration($path){
		$c = "ffmpeg -i {$path} -vstats 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//";
		/*CALCULATING DURATION OF VIDEO BY FFMPEG*/

		$output = exec( $c );
		$dt = new DateTime("1970-01-01 $output", new DateTimeZone('UTC'));
		$seconds = (int)$dt->getTimestamp();
		$_SESSION['video_duration'] = $seconds ;
		$seconds =  $seconds/3;

		$t1 = 4;
		$t2=  $seconds *1;
		$t3 = $seconds *2;

		$t1 = round($t1);
		$t2 = round($t2);
		$t3 = round($t3);

		$t1 = sprintf('%02d:%02d:%02d', ($t1/3600),($t1/60%60), $t1%60);
		$t2 = sprintf('%02d:%02d:%02d', ($t2/3600),($t2/60%60), $t2%60);
		$t3 = sprintf('%02d:%02d:%02d', ($t3/3600),($t3/60%60), $t3%60);

		// return array($t1,$t2,$t3);
		return array($t1);

	}

	public function createChannelThumb($video,$size,$filePath,$fileType){
		$imgArr = [];
		// $time = array('00:00:01','00:00:03','00:00:05');
		$time = $this->videoDuration($video);
		$i=0;
		foreach($time as $val){
			$fileName = rand().'.'.$fileType; /*FOR EXAMPLE :    "JPG"*/

			$val = floor($val);
			// $cmd = "ffmpeg  -i {$video} -ss {$val} -vframes 1 -filter:v scale='{$size}:-1'  {$filePath}{$fileName}";
			$cmd = "ffmpeg -itsoffset -1 -i {$video} -ss {$time[$i]} -vframes 1 -filter:v scale='{$size}:-1'  {$filePath}{$fileName}";
			exec ($cmd,$output,$responce );

			if($responce == 0){
				$imgArr[$i] = $fileName;
				$i++;
			}
		}


		return $imgArr;
	}


	function super_unique($array,$key)
    {
       $temp_array = [];
       foreach ($array as &$v) {
           if (!isset($temp_array[$v[$key]]))
           $temp_array[$v[$key]] =& $v;
       }
       $array = array_values($temp_array);
       return $array;

    }

	function searchForId($id, $K , $array) {  //$K = field name

	   foreach ($array as $key => $val) {
		   if ($val[$K] === $id) {
			   return $val;
		   }
	   }
	   return null;
	}

	function searchFromMultiArray($search, $K , $array, $ext = '.mp4') {  //$K = field name
		foreach ($array as $key => $val){
			if (strpos(trim($val[$K]),trim($search)) !== false && strpos(trim($val[$K]),trim($ext)) !== false) {
				return $val;
			}
		}
		return null;
	}

	function isLiveStreamingVideo($vidArr=[]){
		$result=[];
		foreach($vidArr as $key=>$val){
			if($val['video_type']!=2){
				return $val;
			}
		}
		return $result;
	}

	function age(){
		return array(
			'13+'			=>	'13+ → Everyone',
			'17+'			=>	'17+',
		);

		/*return array(
			'15+'			=>	'PG-13 – Parents Strongly Cautioned',
			'18+'			=>	'NC-17 – Adults Only',
			'Unrestricted'	=>	'G – General Audiences',
			'PG'			=>	'PG – Parental Guidance Suggested',
			'R'				=>	'R – Restricted'
		);*/
	}



/***************************** Post Status START ***********************************/

	function post_status(){
		// return array(5=>'Only Me',6=>'Private',7=>'Public');
		return array(5=>'Only Me', 7=>'Public');
	}


/***************************** POst status START ***********************************/

/***************************** genders Status START ***********************************/

	function genders(){
		return array(1=>'Male',2=>'Female',3=>'Transgender',4=>'Prefer not to answer');
	}


/***************************** genders status START ***********************************/

/***************************** Notification START ***********************************/
	function getNotiStatus($status,$type){
		$notification = '';
		if($status == 1 && $type == 1){     				/* type 1 =for endorsement ,status 1 = Requested*/
			$notification = 'sent an endorsement request ';
		}else if($status == 2 && $type == 1 ){  			/* type 1 =for endorsement ,status 2=Accepted*/
			$notification = 'has accepted your endorsement';
		}else if($status == 3 && $type == 1){   			/* type 1 =for endorsement ,status 3=Declined*/
			$notification = 'declined your endorsement request';
		}else if($status == 1 && $type == 2){   			/* type 2 =for social comment ,status 1=comment on post*/
			$notification = 'commented on your post';
		}else if($status == 2 && $type == 2){   			/* type 2 =for social comment ,status 2=reply on comment*/
			$notification = 'replied to your comment';
		}else if($status == 1 && $type == 3){  			 	/* type 3 =for social Like ,status 1=Like on post*/
			$notification = 'Loved your post';
		}else if($status == 1 && $type == 4){  			 	/* type 4 =for share  ,status 1=just to profile share*/
			$notification = 'shared a profile of';
		}else if($status == 2 && $type == 4){  			 	/* type 4 =for share  ,status 2=just to Post share*/
			$notification = 'shared a post to you';
		}else if($status == 3 && $type == 4){  			 	/* type 4 =for share  ,status 3=just to channel share*/
			$notification = 'shared a channel of';
		}else if($status == 4 && $type == 4){  			 	/* type 4 =for share  ,status 4=just to channel video share*/
			$notification = 'shared an official video # ';
		}else if($status == 1 && $type == 5){  			 	/* type 5 =for Auto Notifications  ,status 1= To remember to upload Cover Video*/
			$notification = 'Wants you to upload cover video to make your profile attractive';
		}else if($status == 2 && $type == 5){  			 	/* type 5 =for Auto Notifications  ,status 2= To remember to upload Official Video*/
			$notification = 'Wants to monetize your videos ! Upload Official Video Now';
		}else if($status == 1 && $type == 6){  			 	/* type 6 =For Messages  ,status 1= For  Message*/
			$notification = 'Sent a new message to you';
		}else if($status == 2 && $type == 6){  			 	/* type 6 =For Messages  ,status 2= For Message Reply*/
			$notification = 'replied to your message';
		}else if($status == 1 && $type == 7){  			 	/* type 7 =For Upload A video  ,status 1= For upload*/
			$notification = 'launched a new video ';
		}else if($status == 1 && $type == 8){  			 	/* type 8 =For New Icon Join   ,status 1= Register New Icon */
			$notification = 'joined discovered';
		}else if($status == 0 && $type == 9){  			 	/* type 9 =For Streaming  ,status 0= Declined of live stream request */
			$notification = 'declined request for live streaming';
		}else if($status == 1 && $type == 9){  			 	/* type 9 =For Streaming  ,status 1= Approval of live stream request */
			$notification = 'approved request for live streaming';
		}else if($status == 2 && $type == 9){  			 	/* type 9 =For Streaming   ,status 2= scheduled Stream post */
			$notification = 'scheduled a new live stream';
		}else if($status == 3 && $type == 9){  			 	/* type 9 =For Streaming   ,status 2= scheduled Stream post */
			$notification = 'is now live ';
		}else if($status == 1 && $type == 10){  			 	/* type 10 =For become a fan   ,status 1= become a fan */
			$notification = 'just followed you on Discovered';
		}else if($status == 1 && $type == 11){  			 	/* type 11 =For create social post   ,status 2= social post */
			$notification = 'just added a new post on social';
		}else if($status == 1 && $type == 12){  			 	/* type 12 =For ticket,status 1=reply */
			$notification = 'just replied on your ticket';
		}else if($status == 2 && $type == 12){  			 	/* type 12 =For ticket,status 2=close */
			$notification = 'just closed your ticket';
		}else if($status == 3 && $type == 12){  			 	/* type 12 =For ticket,status 3=reopen */
			$notification = 'just reopen your ticket';
		}


		return $notification;
	}

	function getNotiLink($status,$type,$refrence_id,$firebase_link = false){
		$link = '';
		$this->CI->load->library('share_url_encryption');

		if(in_array($status,[1,2,3]) && $type == 1){   		/* type 1 =for endorsement ,status 1 = Requested,2=Accepted,3=Declined*/
			$flink = base_url('endorse/request/'.$this->CI->common->base64url_encode($refrence_id));
			$link = "href=\"{$flink}\"";
		}else

		if(in_array($status,[1,2]) && $type == 2 ){ 		/*type 2=for social comment,status 1=comment on post,2=reply on comment*/
			$pid='';
			$post = $this->CI->DatabaseModel->select_data('com_pubid','comments',array('com_id'=>$refrence_id),1);
			if(isset($post[0])){
				$pid = $post[0]['com_pubid'];
			}
			$flink = $this->CI->share_url_encryption->share_single_page_link_creator(1 .'|'.$pid, 'encode');
			$link = "href=\"{$flink}\"";
		}else

		if(in_array($status,[1]) && $type == in_array($type,[3,11]) ){ 			/*type 3=for social Like,status 1=Like on post*/
			$flink = $this->CI->share_url_encryption->share_single_page_link_creator(1 .'|'.$refrence_id, 'encode');
			$link = "href=\"{$flink}\"";
		}else

		if(($status == 1 || $status == 3) && in_array($type,[4,10])){  /* type 4 =for share  ,status 1=profile share*/
			$post = $this->CI->DatabaseModel->select_data('user_uname','users',array('user_id'=>$refrence_id),1);
			$uname = (isset($post[0]))?$post[0]['user_uname']:'';
			$flink = ($status == 1)?  base_url('profile?user='.$uname) :  base_url('channel?user='.$uname);
			$link = "href=\"{$flink}\"";

		}else

		if($status == 2 && $type == 4){  			 	/* type 4 =for share  ,status 1= post share*/
			$flink = $this->CI->share_url_encryption->share_single_page_link_creator(1 .'|'.$refrence_id, 'encode');
			$link = "href=\"{$flink}\"";
		}else

		if(in_array($status,[4,1]) && in_array($type,[4,7])){  			 	/* type 4 =for share  ,status 1=  channel video share*/
			$flink = $this->CI->share_url_encryption->share_single_page_link_creator(2 .'|'.$refrence_id, 'encode');
			$link = "href=\"{$flink}\"";
		}else

		if($status == 1 && $type == 5){  				/* type 5 =for Auto Notifications  ,status 1= To remember to upload Cover Video*/
			$link = 'data-toggle="modal" data-target="#upload_video"';
		}else

		if($status == 2 && $type == 5){  			 	/* type 5 =for Auto Notifications  ,status 2= To remember to upload Official Video*/
			$flink = base_url('monetization');
			$link = "href=\"{$flink}\"";
		}else if(($status == 1 || $status == 2) && $type == 6){  	/* type 6 =for Chat Notifications  ,status 1= For  Message ,status 2= For Message Reply*/
			$post = $this->CI->DatabaseModel->select_data('user_uname','users',array('user_id'=>$refrence_id),1);
			$uname = (isset($post[0]))?$post[0]['user_uname']:'';
			$flink =  base_url('profile?user='.$uname.'#chat_message') ;
			$link = "href=\"{$flink}\"";
		}else
		if(($status == 1 || $status == 0) && $type == 9){  				/* type 9 =for Streaming  ,status 1= Approval */
			// $flink = base_url('Streaming');
			$flink = base_url('media_stream');
			$link = "href=\"{$flink}\"";
		}else if(($status == 2 || $status == 3) && $type == 9){  			 	/* type 9 =For Streaming   ,status 2= Creating Stream post */
			$flink = $this->CI->share_url_encryption->share_single_page_link_creator(2 .'|'.$refrence_id, 'encode');
			$link = "href=\"{$flink}\"";
		}else if(in_array($status,[1,2,3]) && $type == 12){  			 	/* type 5 =for Auto Notifications  ,status 2= To remember to upload Official Video*/
			$flink = base_url('support/ticketSingle/'.$refrence_id);
			$link = "href=\"{$flink}\"";
		}
		return ($firebase_link)? $flink : $link;
	}

	function getSharedProfileName($status,$type,$refrence_id){
		$name='';
		if(($status == 1 || $status == 3) && $type == 4){
			$post = $this->CI->DatabaseModel->select_data('user_name','users',array('user_id'=>$refrence_id),1);
			if(isset($post[0])){
				$name = $post[0]['user_name'];
			}
			return $name;
		}
	}
	function getChannelTitleName($status,$type,$refrence_id){
		$title='';
		if(in_array($status,[4,1]) && in_array($type,[4,7])){
			$post = $this->CI->DatabaseModel->select_data('title','channel_post_video',array('post_id'=>$refrence_id),1);
			if(isset($post[0])){
				$title = $post[0]['title'];
				$title		=  (strlen($title)< 50)?$title:substr($title,0,50)."...";
			}
			return $title;
		}
	}

	function insertNoti($data_array){
		return $this->CI->DatabaseModel->access_database('notifications','insert',$data_array);
	}
	function deleteNoti($where_array){
		return $this->CI->DatabaseModel->access_database('notifications','delete','',$where_array);
	}
	function getTotalNotiCount($status){
		return $this->CI->DatabaseModel->aggregate_data('notifications','noti_id','COUNT', array('view_status'=>$status,'to_user'=>$this->uid));
	}
/***************************** Notification END ***********************************/


     public function send_emails($to,$subject,$body){

        $bodyhead="<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title>Discovered</title>
        </head><body>";

        $from = 'Team Discovered';
        $from_add = 'no-reply@pixelnx.com';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "From: =?UTF-8?B?". base64_encode($from) ."?= <$from_add>\r\n" .'Reply-To: '.$from_add . "\r\n" .'X-Mailer: PHP/' . phpversion();

		$headers .= "From: { $from_add }\r\n";

		$headers .= "Return-Path: { $from_add }\r\n";


        mail($to,$subject,$bodyhead.$body.'</body></html>',$headers, '-f'.$from_add);
	}

	function sendNotiOnMonetizeVideo($uid,$post_id,$video_title){
		/* START send firebase notification*/
		$token = $this->getFireBaseTokenOfMyFans($uid);
		//print_R($token);die;
		if(!empty($token)){

			$mess 		= $this->getNotiStatus(1,7);
			$fullname 	= $this->get_user_fullname($uid,'user_name');
			$link 		= $this->getNotiLink(1,7,$post_id,true);

			$imageArr = $this->getChanneThumbsCommon($post_id);
			$videoThumb='';
			if(isset($imageArr[0])){
				$videoThumb = $imageArr[0]['name'];
			}

			// echo 'asd:'.$link ;die;
			$msg_array 	=  [
				'title'	=>	$fullname .' '. $mess,
				'body'	=>	$video_title,
				'icon'	=>	base_url('repo/images/firebase.png'),
				'image' =>  $videoThumb,
				'click_action'=>$link,
				'extra_data'=>array('id'=>$post_id,'intent'=>'single_video','videoThumb'=>$videoThumb)
			];
			return $this->sendNotification($token,$msg_array);
		}
		/* END send firebase notification*/
	}


	function sendNotiOnCreateSocialPost($uid,$post_id,$post_title,$postImage,$status){
		/* START send firebase notification*/
		$token = $this->getFireBaseTokenOfMyFans($uid);
		//print_R($token);die;
		if(!empty($token)){

			$mess 		= $this->getNotiStatus($status,11);
			$fullname 	= $this->get_user_fullname($uid,'user_name');
			$link 		= $this->getNotiLink($status,11,$post_id,true);

			// echo 'asd:'.$link ;die;
			$msg_array 	=  [
				'title'	=>	$fullname .' '. $mess,
				'body'	=>	$post_title,
				'icon'	=>	base_url('repo/images/firebase.png'),
				//'image' =>  $postImage,
				'click_action'=>$link,
				'extra_data'=>array('id'=>$post_id,'intent'=>'social_post')
			];
			return $this->sendNotification($token,$msg_array);
		}
		/* END send firebase notification*/
	}



	function sendNotiOnJoinedNewIcon($uid,$user_level=''){
		/* START send firebase notification*/
		$start=0;
		$limit= 1000;

		$cond ="user_id !={$uid} and user_firebase_token !=''";
		$tokens = $this->CI->DatabaseModel->select_data('user_id,user_firebase_token','users use INDEX(user_id)',$cond ,array($limit,$start));

		$TokenData = [];
		foreach($tokens as $tok){
			if(isset($tok['user_firebase_token']) && !empty($tok['user_firebase_token'])){
				$tokn = json_decode($tok['user_firebase_token'],true);
				if(isset($tokn['web']) && !empty($tokn['web'])){
					$TokenData['web'][] =  $tokn['web'];
				}
				if(isset($tokn['android']) && !empty($tokn['android'])){
					$TokenData['android'][] =  $tokn['android'];
				}
				if(isset($tokn['ios']) && !empty($tokn['ios'])){
					$TokenData['ios'][] =  $tokn['ios'];
				}
			}
		}

		$sub_cat = $this->CI->DatabaseModel->select_data('category_name','artist_category', array('category_id'=>$user_level));
		$user_cate=isset($sub_cat[0]['category_name'])?$sub_cat[0]['category_name'] :'user';

		$token = $TokenData;

		if(!empty($token)){

			$mess 		= $this->getNotiStatus(1,8);
			$fullname 	= $this->get_user_fullname($uid,'user_name');
			$link 		= $this->getNotiLink(1,4,$uid,true);
			// echo 'asd:'.$link ;die;
			$msg_array 	=  [
				'title'	=>	'A new '.strtolower($user_cate).' '.$mess,
				'body'	=>	'',
				'icon'	=>	base_url('repo/images/firebase.png'),
				'click_action'=>$link,
				'extra_data'=>array('id'=>$uid,'intent'=>'user_profile')
			];
			return $this->sendNotification($token,$msg_array);
		}
		/* END send firebase notification*/
	}

	function sendNotiOnLiveStreaming($uid,$post_id,$video_title,$status ){
		/* START send firebase notification*/
		$token = $this->getFireBaseTokenOfMyFans($uid , '' , '' , $with_user = true );
		if(!empty($token['TokenData'])){
			$insert = [];
			for($i=0;$i < sizeof($token['UserData']); $i++){
				$insert[] = array(	'noti_type'		=>	9,
									'noti_status'	=>	$status,
									'from_user'		=>	$uid,
									'to_user'		=>	$token['UserData'][$i],
									'reference_id'	=>	$post_id,
									'created_at'	=>	date('Y-m-d H:i:s')
									);
			}
			$this->CI->db->insert_batch('notifications', $insert);


			$mess 		= $this->getNotiStatus($status,9);
			$fullname 	= $this->get_user_fullname($uid,'user_name');
			$link 		= $this->getNotiLink($status,9,$post_id,true);


			$imageArr = $this->getChanneThumbsCommon($post_id);
			$videoThumb='';
			if(isset($imageArr[0])){
				$videoThumb = $imageArr[0]['name'];
			}

			$msg_array 	=  [
				'title'	=>	$fullname .' '. $mess,
				'body'	=>	$video_title,
				'icon'	=>	base_url('repo/images/firebase.png'),
				'image' =>	$videoThumb,
				'click_action'=>$link,
				'extra_data'=>array('id'=>$post_id,'intent'=>'single_video','videoThumb'=>$videoThumb)
			];
			return $this->sendNotification($token['TokenData'],$msg_array);

		}
		/* END send firebase notification*/
	}


	function getChanneThumbsCommon($post_id=''){
		$thumArray = [];
		if ($post_id !='')
		{
			$join  = array(
						'multiple',
						array(
							array('channel_post_video' , 'channel_post_thumb.post_id = channel_post_video.post_id'),

						)
					);
			$field = 'channel_post_thumb.thumb_id,channel_post_thumb.active_thumb,channel_post_thumb.image_name,channel_post_thumb.user_id,channel_post_video.iva_id';
			$imgArr = $this->CI->DatabaseModel->select_data($field,'channel_post_thumb',array('channel_post_thumb.post_id'=>$post_id,'channel_post_thumb.active_thumb'=>1),'',$join);


			if(isset($imgArr[0])){
				for($i=0;$i<sizeof($imgArr);$i++){

					$FilterData = $this->CI->share_url_encryption->FilterIva($imgArr[$i]['user_id'],$imgArr[$i]['iva_id'],$imgArr[$i]['image_name'],'',true);

					$thumArray[$i] = array('thumb_id'=>$imgArr[$i]['thumb_id'],'name'=>$FilterData['thumb'],'active_thumb'=>$imgArr[$i]['active_thumb']);
				}
				return $thumArray;

			}else{
				return  $thumArray;
			}
		}else{
			return  $thumArray;
		}
	}


	function sendNotiOnBecomeAfan($from_uid,$to_user,$post_id='',$body_title='',$status){
		/* START send firebase notification*/
		$token = $this->getFirebaseToken($to_user);
		if(!empty($token)){

			$insert = array(	'noti_type'		=>	10,
								'noti_status'	=>	$status,
								'from_user'		=>	$from_uid,
								'to_user'		=>	$to_user,
								'reference_id'	=>	$post_id,
								'created_at'	=>	date('Y-m-d H:i:s')
								);

			$this->insertNoti($insert);


			$mess 		= $this->getNotiStatus($status,10);
			$fullname 	= $this->get_user_fullname($from_uid,'user_name');
			$link 		= $this->getNotiLink($status,10,$from_uid,true);


			$msg_array 	=  [
				'title'	=>	$fullname .' '. $mess,
				'body'	=>	$body_title,
				'icon'	=>	base_url('repo/images/firebase.png'),
				'click_action'=>$link,
				'extra_data'=>array('id'=>$from_uid,'intent'=>'user_profile')
			];
			return $this->sendNotification($token,$msg_array);

		}
		/* END send firebase notification*/
	}

	function GiveAwayMail($to,$fullname,$email=''){
		$subj = 'We’re halfway, have you claimed a prize yet?';

		$greeting = 'Congratulations '.ucfirst($fullname).' ! <br/><br/> Your registration for PERFECT 10 DISCOVERED GIVEAWAY is confirmed!';

		$action =  ' We are giving you the chance to win some fantastic prizes! <br/> Playstation 5  <br/>  Grand Theft Auto 5 <br/><br/> Winners will be announced by 31st October 2021. <br/> Follow us @discovered_usa - https://www.instagram.com/discovered_usa/?hl=en on Instagram to be the first to hear about future promotions, contests, and giveaways!';
		// <br/><br/> Also, check out the Discovered Giveaways channel- https://discovered.tv/giveaways as often as you can
		$button = 'Visit Discovered Giveaways channel';
		$link = 'https://discovered.tv/channel?user=dtvgiveaways';

		//return $this->MailByMandrillforLink($to,$subj,$greeting,$action,$button,$link);

		$this->CI->load->helper('aws_ses_action');
		send_smtp([
			'greeting'=>$greeting,
			'action'=>$action,
			'email'=>NULL,
			'receiver_email'=>$email,
			'password'=>NULL,
			'button'=>$button,
			'link'=>$link,
			'subject'=>$subj,
		]);
	}

	public function get_new_cover_video($cover_video_id){

		if(!empty($cover_video_id)){

			$where = "channel_post_video.post_id IN ({$cover_video_id}) AND channel_post_video.active_status = '1' AND  channel_post_video.complete_status = 1 AND channel_post_video.privacy_status = '7' AND channel_post_video.delete_status = 0 AND channel_post_thumb.active_thumb = 1 ";

			$data =  $this->CI->DatabaseModel->select_data('channel_post_video.post_key,channel_post_video.is_video_processed,channel_post_video.user_id,channel_post_video.post_id,channel_post_video.title,channel_post_video.uploaded_video','channel_post_video use INDEX(post_id)',$where,'',array('channel_post_thumb','channel_post_thumb.post_id = channel_post_video.post_id','left'));

			$cover_videos = [];
			foreach($data as $i => $d){
				$vid  				= 	$d['uploaded_video'] ;
				$url 				= 	AMAZON_URL .$vid;
				$key 				= 	explode('.',$vid);
				$folder 			= 	explode('/',$key[0]);
				$preview 			= 	($d['is_video_processed'] == 1 ) ? AMAZON_TRANCODE_URL.$key[0].'/'.$folder[2].'.mp4?q='.time()    :    $url  ;
				$post_key			=	base_url().$this->CI->common->generate_single_content_url_param($d['post_key'] , 2);
				$cover_videos[$i] 	=   ['user_id'=>$d['user_id'],'post_id'=>$d['post_id'],'url'=>$url,'title'=>$d['title'],'preview'=>$preview,'post_key'=>$post_key];
			}
			return $cover_videos;
			/* if(isset($data[0])){
				$vid  		= 	$data[0]['uploaded_video'] ;
				$url 		= 	AMAZON_URL .$vid;
				$key 		= 	explode('.',$vid);
				$folder 	= 	explode('/',$key[0]);
				$preview 	= 	($data[0]['is_video_processed'] == 1 ) ? AMAZON_TRANCODE_URL.$key[0].'/'.$folder[2].'.mp4?q='.time()    :    $url  ;
				$post_key	=	base_url().$this->CI->common->generate_single_content_url_param($data[0]['post_key'] , 2);
				return  array('user_id'=>$data[0]['user_id'],'post_id'=>$data[0]['post_id'],'url'=>$url,'title'=>$data[0]['title'],'preview'=>$preview,'post_key'=>$post_key);
			} */
		}
	}
}

/* End of file Audition_functions.php */
?>
