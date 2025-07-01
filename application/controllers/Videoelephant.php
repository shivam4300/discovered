<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Videoelephant extends CI_Controller {
	/*public 	$uid ;
	public 	$AVI_Subscription_Key = '263a83e3b6f943a4ba5437a69b6639f2';*/
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('manage_session','share_url_encryption','form_validation')); 
		//$this->load->library(array('image_lib','audition_functions','dashboard_function','form_validation','query_builder',,'API_common_function')); 
		$this->uid = is_login();
		$this->load->helper(array('aws_s3_action','file')); 

	}
	function index(){
		if($_SESSION['is_ele']){
		$data['page_info'] 		= 	array('page'=>'videoelephant','title'=>'Videoelephant');
		$data['web_mode'] 	= 	$this->DatabaseModel->select_data('mode_id,mode','website_mode',array('channel_status'=>1));
		$website_mode = '';
			foreach($data['web_mode'] as $mode){
				$website_mode .= "<option value='{$mode['mode_id']}'>".ucfirst($mode['mode'])."</option>";
			}
		$data['website_mode'] = $website_mode;
		$this->load->view('home/inc/header',$data);
	    $this->load->view('home/videoelephant/videoelephant',$data);
	    $this->load->view('common/notofication_popup');
        $this->load->view('home/inc/footer',$data);
    	}else{
    		redirect(base_url('four_zero_four'));
    		
    	}
	}
	function warning_handler($errno, $errstr) { 
		echo "1";
		$this->respMessage = "error";
		$this->statusCode=1;
		$this->statusType="";
	}
	function getMrssVideoElephanta(){
		$resp	=	[];
		define ('XMLFILE', $_POST['mrss_url']);
		$items = array ();
		$i = 0;
		$ext = pathinfo(XMLFILE, PATHINFO_EXTENSION);
		set_error_handler(array($this, 'warning_handler'));
		
		try {
			if($ext=="xml"){
				$xmlReader = new XMLReader();
				$xmlReader->open(XMLFILE, null, LIBXML_NOBLANKS);

				$isParserActive = false;
				$simpleNodeTypes = array ("title", "description", "media:title", "link", "author", "pubDate", "guid","media:category","media:tags","media:keywords");
				while($xmlReader->read())
				{
					$nodeType = $xmlReader->nodeType;
					// Only deal with Beginning/Ending Tags
					if ($nodeType != XMLReader::ELEMENT && $nodeType != XMLReader::END_ELEMENT) { continue; }
					else if ($xmlReader->name == "item") {
						if (($nodeType == XMLReader::END_ELEMENT) && $isParserActive) { $i++; }
						$isParserActive = ($nodeType != XMLReader::END_ELEMENT);
					}

					if (!$isParserActive || $nodeType == XMLReader::END_ELEMENT) { continue; }
					$name = $xmlReader->name;
					if (in_array ($name, $simpleNodeTypes)) {
					   
						$xmlReader->read ();
						$items[$i][$name] = $xmlReader->value;
						if($name=="media:tags"){
							$items[$i]['tags'] = $xmlReader->value;
						}
						if($name=="media:keywords"){
							$items[$i]['keywords'] = $xmlReader->value;
						}
					} else if ($name == "media:thumbnail") {
						$items[$i]['thumbnail'] = array (
								"url" => $xmlReader->getAttribute("url"),
								"width" => $xmlReader->getAttribute("width"),
								"height" => $xmlReader->getAttribute("height"),
								"type" => $xmlReader->getAttribute("type")
						);
					} else if ($name == "media:content") {
						$items[$i]['content'] = array (
								"url" => $xmlReader->getAttribute("url"),
								"duration" => $xmlReader->getAttribute("duration"),
								"lang" => $xmlReader->getAttribute("lang")
						);
					}
				}
				
				
				$insert_array=array(
					'video_type'=>	4,
					'user_id'	=>	$this->session->userdata('user_login_id')
				);
				
				$data['ids']	=	$this->DatabaseModel->access_database('channel_post_video','insert',$insert_array);
				$data['items']	=	$items;
				$modes			= 	$this->DatabaseModel->access_database('website_mode','select','',array('channel_status'=>1)); 
				$website_mode 	= 	'';
				
				foreach($modes as $mode){
					$website_mode .= "<option value='{$mode['mode_id']}'>".ucfirst($mode['mode'])."</option>";
				}
				
				$data['website_mode'] = $website_mode;
				$html =$this->load->view('home/videoelephant/getMrssVideoElephanta',$data,true);
				$this->respMessage = $html;
				$this->statusCode=1;
				$this->statusType="";
			}else{
				$this->respMessage = "error";
				$this->statusCode=1;
				$this->statusType="";
			}
		}catch(Exception $e){
				$this->respMessage = "error";
				$this->statusCode=1;
				$this->statusType="";
		}
		$this->show_my_response($resp);
	}
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	} 
	public function uploadS3VideoElephanta(){
		$resp	=	[];
		$ids="";
		$uid=$this->session->userdata('user_login_id');
		if(isset($_POST['vid_provider_id']) && !empty($_POST['vid_provider_id']) && isset($_POST['url']) && !empty($_POST['url']) && isset($_POST['postLength']) && isset($_POST['post_id']) && !empty($_POST['post_id']) && isset($_POST['post_id']) && !empty($_POST['post_id'])){

			$checkdata= $this->DatabaseModel->access_database('channel_post_video','select','',array('vid_provider_id'=>$_POST['vid_provider_id'],'user_id'=>$uid,'delete_status'=>0));
			
			if($_POST['postLength']>0){
				$insert_array=array(
					'video_type'=>4,
					'user_id'=>$this->session->userdata('user_login_id')
				);
				$ids=$this->DatabaseModel->access_database('channel_post_video','insert',$insert_array);
			}
			if(empty($checkdata)){

				$post_old_id=0;
				$rns = $this->common->generateRandomString(20);
				$ext  = 'mp4';
				$name = $rns.'.'.$ext;
				$amazon_path = "aud_{$uid}/videos/{$name}";
				$res = multipartUploader_ad($_POST['url'],$amazon_path,true);
				
				$check = $this->share_url_encryption->share_single_page_link_creator('2|'.$_POST['post_id'],'encode','id');
				
				$channel_array = array(	
					'uploaded_video'=>$res['key'],
					'post_key'		=>$check[0],
					'video_duration'=>(isset($_POST['duration']))?$_POST['duration']:''
				);
				$this->DatabaseModel->access_database('channel_post_video','update',$channel_array, array('post_id'=>$_POST['post_id']));

				$ThumbPath = "./uploads/aud_{$uid}/images/";
				$img = explode('/',$_POST['thumbnail']);
				$img_name= end($img);
				
				// copy($_POST['thumbnail'],$ThumbPath.$img_name);
				
				if(file_put_contents($ThumbPath.$img_name , file_get_contents($_POST['thumbnail']))){
        
					$insertArr = array('post_id'=> trim($_POST['post_id']),'image_name' =>$img_name,'user_id'=>$uid);
					$insertArr['active_thumb'] = 1;  
					$thumb_id = $this->DatabaseModel->access_database('channel_post_thumb','insert',$insertArr);
					
					$this->load->library('convert_image_webp');
					if(file_exists($ThumbPath.$img_name))
					$this->convert_image_webp->convertIntoWebp($ThumbPath.$img_name);
					
					$this->audition_functions->resizeImage('315','217',
					$ThumbPath.$img_name,'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);	
					
					$img = explode('.',$img_name);
					$path = $ThumbPath.$img[0].'_thumb.'.$img[1];
		
					if(file_exists($path))
					$this->convert_image_webp->convertIntoWebp($path);
				}

				
							
				$this->statusType=0;
				upload_all_images($uid);
			}else{
				$this->DatabaseModel->access_database('channel_post_video','delete','', array('post_id'=>$_POST['post_id']));
				$this->statusType=1;
				$post_old_id=$checkdata[0]['post_id'];
			}
			$this->respMessage =array('post_id'=>$ids,"post_old_id"=>$post_old_id);
			
		}else{
			$this->statusType=2;
			$this->respMessage =array('error'=>"Somthing went wrong");
		}
		$this->statusCode=1;
		$this->show_my_response($resp);
	}

	function AddVideosInVideoElephant(){
		$this->load->library('manage_session');
		$this->load->library('creator_jwt');
		$uid = $this->uid;
		$resp = array();
		$TokenResponce = $this->creator_jwt->MatchToken();
		
		if($TokenResponce['status'] == 1){ 
			$rules = array(
				array( 'field' => 'post_id[]', 'label' => 'Post', 'rules' => 'trim|required'),
				array( 'field' => 'mode[]', 'label' => 'Mode', 'rules' => 'trim|required'),
				array( 'field' => 'genre[]', 'label' => 'Genre', 'rules' => 'trim|required'),
				array( 'field' => 'title[]', 'label' => 'Title', 'rules' => 'trim|required'),
				array( 'field' => 'description[]','label' => 'Description','rules' => 'trim|required'),
			);
			$this->form_validation->set_rules($rules);
			
			if($this->form_validation->run()){
				$post_id 	= $this->input->post('post_id');
				$mode 		= $this->input->post('mode');
				$genre 		= $this->input->post('genre');
				$title 		= $this->input->post('title');
				$tag 		= $this->input->post('tag');
				$desc 		= $this->input->post('description');
				$sub_genre 	= $this->input->post('sub_genre');
				$vid_provider_id 	= $this->input->post('vid_provider_id');
				
				$join  = array(
					'multiple',
					array(
						array('channel_post_thumb' , 'channel_post_thumb.post_id = channel_post_video.post_id'),
					
					)
				);
				
				$cond = "channel_post_video.post_id IN(". implode(',',$post_id) .") AND active_thumb = 1";
				
				$videos = $this->DatabaseModel->select_data('channel_post_video.*,channel_post_thumb.image_name','channel_post_video use INDEX(post_id)',$cond,'',$join);
				
				//$updateArray = array();
				$insertArray = array();
				$post_id_cooma=array();
				$all_post_id=array();
				for($x = 0; $x < sizeof($post_id); $x++){
					if(!empty($post_id[$x])){
						$m_key		=	$mode[$x];
						$post_id_cooma[$m_key]['data'][]=$post_id[$x];
						$post_id_cooma[$m_key]['genre']=$genre[$x];
						$all_post_id[]=$post_id[$x];
						$Rarray 	= $this->audition_functions->searchForId($post_id[$x], 'post_id' , $videos);
						$age_restr 	= ( isset($Rarray['age_restr']) &&  trim($Rarray['age_restr']) != '') ? $Rarray['age_restr'] : 'Unrestricted' ;
						$privacy 	= ( isset($Rarray['privacy_status']) &&  trim($Rarray['privacy_status']) != 0) ? $Rarray['privacy_status'] : 7;
						$language 	= ( isset($Rarray['language']) &&  trim($Rarray['language']) != '') ? $Rarray['language'] : 'en_US';
						
						$d 		= isset($desc[$x])?$desc[$x]:'';
						$t 		= isset($title[$x]) ? $title[$x]  : '';
						
						$updateArray = array(
							'mode' 			=> 	isset($mode[$x]) ? $mode[$x] : '',
							'genre' 		=> 	isset($genre[$x]) ? $genre[$x] : '',
							'tag' 			=> 	isset($tag[$x]) ? $tag[$x] : '',
							'title' 		=> 	checkForeignChar($t),
							'description' 	=> 	checkForeignChar($d) ,
							'age_restr' 	=> 	$age_restr,
							'privacy_status'=> 	$privacy,
							'complete_status'=> 1,
							'active_status'	=>  1,
							'language'		=> 	$language,
							'sub_genre'		=>  isset($sub_genre[$x])?$sub_genre[$x]:'',
							'vid_provider_id'=>  isset($vid_provider_id[$x])?$vid_provider_id[$x]:''
						);
						
						$this->DatabaseModel->access_database('channel_post_video','update',$updateArray, array('post_id'=>$post_id[$x]));
					}
				}
				
				//if(!empty($updateArray))
					//$this->db->update_batch('channel_post_video',$updateArray, 'post_id');
				$userName=(isset($_SESSION['user_uname']))? $_SESSION['user_uname'] : '';
				$resp['redurl']		= base_url('channel?user=').$userName;
				///echo "<pre>";
				$this->statusCode 	= 1;
				$this->statusType 	= 1;
				if(isset($_POST['slider_mode']) && !empty($_POST['slider_id'])){
					$checkdata= current($this->DatabaseModel->select_data('*','homepage_sliders',array('id'=>$_POST['slider_id'])));
					$data= $checkdata['data'].','.implode(",",$all_post_id);
					$this->DatabaseModel->access_database('homepage_sliders','update',array('data'=>$data), array('id'=>$_POST['slider_id']));
					/*foreach($post_id_cooma as $key => $value_data){
						$title 	= 	strtoupper(trim($_POST['slider_title']));
						$slug	=	strtolower(str_replace(" ","_",$title));
						$insert_arr=array(
							'slider_title'	=>$title,
							'type'			=>$slug,
							'data'			=>implode(",",$value_data['data']),
							'mode'			=>$key,
							'genre'			=>  isset($value_data['genre'])?$value_data['genre']:'',
							'status'		=>1
						);
						$this->DatabaseModel->access_database('homepage_sliders','insert',$insert_arr);
					}*/
					
				}
				
				$this->respMessage  =  "Videos uploaded successfully.";
				
			}else{
				$errors = array_values($this->form_validation->error_array());
				$this->respMessage  =  isset($errors[0])?$errors[0]:'';
			}
		}else{
			$this->respMessage = $TokenResponce['message'];
		}
		
		$this->show_my_response($resp);
	}
	function getIdifFaildData(){
		$uid=$this->session->userdata('user_login_id');
		$checkdata= current($this->DatabaseModel->select_data('*','channel_post_video',array('user_id'=>$uid,'delete_status'=>0,'uploaded_video'=>"",'video_type'=>4),1,'',array('post_id','DESC')));
		$this->respMessage =array('post_id'=>$checkdata['post_id'],"post_old_id"=>0);
		$this->statusCode 	= 1;
		$this->statusType 	= 1;
		$this->show_my_response();
	}
	function getSlidarList(){
		$resp = array();
		$checkdata= $this->DatabaseModel->select_data('*','homepage_sliders',array('mode'=>$_POST['id'],'cron_status'=>0));
		//echo "<pre>";
		$html='<option value="">Select slider</option>';
		foreach ($checkdata as $key => $value) {
			$html.='<option value="'.$value['id'].'">'.$value['slider_title'].'</option>';
		}
		$resp['data'] =$html;
		$this->respMessage ='Success';
		$this->statusCode 	= 1;
		$this->statusType 	= 1;
		$this->show_my_response($resp);
	}
	function confirm_delete(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$post_id=$_POST['id'];
			$uid=$this->session->userdata('user_login_id');
			$channel_data 	= current($this->DatabaseModel->select_data('post_id,uploaded_video,social,video_type,is_stream_live','channel_post_video',array('post_id'=>$post_id)));
			if(!empty($channel_data['uploaded_video'])){
				$old_key 		= trim($channel_data['uploaded_video']);
				$r = s3_delete_object(array($old_key));
				$key = explode('.',$old_key)[0];
				$ro = s3_delete_matching_object(trim($key),TRAN_BUCKET);

				$where 			= array('post_id'=>$post_id);
				$kpath = 'aud_'.$uid.'/images/';
				$previous_thumb = $this->DatabaseModel->select_data('image_name','channel_post_thumb',$where);
				if(!empty($previous_thumb)){
					foreach($previous_thumb as $thumb){
						$image_name = trim($thumb['image_name']);
						if(!empty($image_name)) {
							$img = explode('.',$image_name);
							if(isset($img[1])){
								$img = $img[0].'_thumb.'.$img[1];
								s3_delete_object(array($kpath.$image_name,$kpath.$image_name.'.webp',$kpath.$img,$kpath.$img.'.webp' ));
							}
						}
					}
					$merge_cond 	= array_merge($where, ['active_thumb'=>0]);

					$this->DatabaseModel->access_database('channel_post_thumb','delete','', $merge_cond );
				}
				$this->DatabaseModel->access_database('channel_post_video','update',array('delete_status'=>1,'is_stream_live'=>0),$where);
				$this->statusCode = 1;
				$this->statusType = 'Success';
				$this->respMessage = 'You have successfully deleted the video.';
			}else{
				$this->statusCode = 2;
				$this->statusType = 'errors';
				$this->respMessage = 'Video not found.';
			}
		}else{
			$this->statusCode = 2;
				$this->statusType = 'errors';
			$this->respMessage = 'Video not found.';
		}
		$this->show_my_response();
	}
}