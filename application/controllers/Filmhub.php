<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filmhub extends CI_Controller {
	
	private $uid;
  public $filmhub_queue = 0;
  public $parentUname = '';
	public $statusCode 	= 	0;
  Private $statusType = 'Error';
	public $respMessage = '';
	

	public function __construct(){
		parent::__construct();
		// if(isset($_POST) && !empty($_POST)) {
	  //       if(!isset($_SERVER['HTTP_REFERER'])) {
    //             die('Direct Access Not Allowed!!');
	  //       }
	  //   }
		$this->load->helper(array('aws_s3_action')); 
		$this->load->library(array('share_url_encryption','convert_image_webp')); 
		$this->uid = is_login();
    $this->parentUname = 'filmhub';
	}

	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
    $resp['type'] = $this->statusType;
		$resp['message']= $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}

 


function ymls(){
		$r = str_replace("[","",file_get_contents('https://bucket-filmhub-discovered.s3.amazonaws.com/kn49498_tears_of_steel_151342/kn49498_tears_of_steel_metadata.yaml'));
		$r = str_replace("]","",$r);
		$r = str_replace("'","",$r);
		$r = str_replace("\"","'",$r);
		echo '<pre>';
		// print_r($r);die;
		$r = trim(preg_replace('/\t+/', '',$r ));
	
		$yaml = <<<EOD
---
{$r}
...
EOD;

$parsed = yaml_parse($yaml);
echo '<pre>';
print_r($parsed);

	}


  
  function addAndUploadSeariesSrtFiles($type, $video_files, $post_id, $path){
    $srts = [];
    try {
      $find =  "S".$type['season_number']."E".$type['episode_number'];

      foreach($video_files as $srt){
        if(strpos($srt['filename'],$find) > -1 && $srt['type'] == 'text_track' ){
        
          $pathToSrt  = ABS_PATH .'uploads/aud_'.$this->uid.'/images/' ;
          
          $vtt  = 'WEBVTT';
          $vtt .= "\n\n" ;
          $vtt .= file_get_contents($path.$srt['filename']);
          
          $vtt = str_replace(",",".",$vtt);
          
          $filename = str_replace(".srt",".vtt",$srt['filename']);

          $lang     = explode('_',str_replace(".srt","",$srt['filename']));
          $lang     = $lang[sizeof($lang)-1];
          
          if(file_put_contents($pathToSrt.$filename, $vtt )){
            $target = "aud_{$this->uid}/captions/".$filename;

            $r      = multipartUploader_ad($source = $pathToSrt.$filename, $key = $target, $ci_mime=true);
            
            if(isset($r['key'])){

              unlink($pathToSrt.$filename);
              $srts[] = [
                'post_id'       => $post_id,
                'user_id'       => $this->uid ,
                'caption_name'  => $filename,
                'language'      => $lang,
                'created_at'    => date('Y-m-d H:i:s') 
              ];
            }
          }
        }
      }

    }catch(Exception $e) {
      echo 'Message: ' .$e->getMessage();
    }
    return $srts;
  }

  function CreateSeriesThumbImage($imgkey, $type, $image_files,$path,$post_id){
    try{
      
      if($imgkey == 'other'){
        $find =  "S".$type['season_number']."E".$type['episode_number'];
        foreach($image_files as $file){
          if(strpos($file['filename'],$find) > -1 && $file['type'] == 'other'){
              $r = $file;
              break;
          }
        }
      }
      
      if(!isset($r['filename'])){
        $r         = $this->audition_functions->searchForId('landscape_16x9','type',$image_files) ;
      }

      if(!isset($r['filename'])){
        $r         = $this->audition_functions->searchForId('16x9','type',$image_files) ;
      }
      
      
      $thumbName    = rand().'.jpeg';
      
      $AbsPath      = ABS_PATH .'uploads/aud_'.$this->uid.'/images/' ;
      $pathToImages = $AbsPath.$thumbName ;
      
      if(isset($r['filename'])){
        if(@ file_put_contents($pathToImages, file_get_contents($path.$r['filename']))){
        
          $this->convert_image_webp->convertIntoWebp($pathToImages);
        
          $this->audition_functions->resizeImage('315','217',$pathToImages,'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);	
        
          $breakThumb = explode('.',$thumbName);
          
          $pathToThumbImage = $AbsPath.$breakThumb[0].'_thumb.'.$breakThumb[1];
          
          if(file_exists($pathToThumbImage)){
            $this->convert_image_webp->convertIntoWebp($pathToThumbImage);
          }
        
          $this->DatabaseModel->access_database('channel_post_thumb','insert', ['post_id'=> $post_id ,'image_name' =>$thumbName,'user_id'=>$this->uid,'active_thumb'=>1]);
          return true;
        }
      }
      
    }catch(Exception $e) {
      $this->respMessage  = 'Series upload error '.$e->getMessage();
      return false;
    }

  }

  function checkNGetSeriesVideo($type,$video_files,$path,$uploadPath){
    if($type['sku'] == 'trailer'){
      $r      = $this->audition_functions->searchForId('trailer','type',$video_files) ;
    }else{
      $name = str_replace(' ','_',strtolower($type['name']));
     
      $season = !empty($type['season_number'])? $type['season_number'] : 0;
      $find = 'S'. $season .'E'. $type['episode_number'] .'_'. $type['sku'] ;
      $r      = $this->audition_functions->searchFromMultiArray($find,'filename',$video_files) ;
    }
   
    
    $this->audition_functions->videoDuration($uploadPath.$r['filename']);
    
    $target = "aud_{$this->uid}/videos/".uniqid(). '.mp4';
    
    $fileSize = getSize($path.$r['filename']); // Check file size if greater than 5GB

    if($fileSize['filesize']>5368709120){
      $r = multipartUploader_ad($source = $uploadPath.$r['filename'] , $key = $target ,$ci_mime=true);
    }else{
      $r = copyObjectFilmhub($path.$r['filename'], $target );
    }

    
    return $r['key'];
  }

  function addAndUploadSrtFiles($srt_array,$post_id,$path){
    $srts = [];
    try {
      $pathToSrt  = ABS_PATH .'uploads/aud_'.$this->uid.'/images/' ;
      if (!is_dir($pathToSrt)){
        return false;
      }

      foreach($srt_array as $srt){
        if($srt['type'] == 'text_track'){
        
          $vtt  = 'WEBVTT';
          $vtt .= "\n\n" ;
          $vtt .= file_get_contents($path.$srt['filename']);
          
          $vtt = str_replace(",",".",$vtt);

          $filename = str_replace(".srt",".vtt",$srt['filename']);

          $lang = explode('_',str_replace(".srt","",$srt['filename']));
          $lang = $lang[sizeof($lang)-1];
          
          if(file_put_contents($pathToSrt.$filename, $vtt )){
       
            $target = "aud_{$this->uid}/captions/".$filename;
  
            $r      = multipartUploader_ad($source = $pathToSrt.$filename, $key = $target, $ci_mime=true);
            
            if(isset($r['key'])){
              unlink($pathToSrt.$filename);
              $srts[] = [
                'post_id'       => $post_id,
                'user_id'       => $this->uid ,
                'caption_name'  => $filename,
                'language'      => $lang,
                'created_at'    => date('Y-m-d H:i:s') 
              ];
            }
          }
        }
      }
    }catch(Exception $e) {
      // echo 'Message: ' .$e->getMessage();
    }
    return $srts;
  }

  function createArrCast($cast_array,$post_id){
    $casts = [];
    foreach($cast_array as $cast){
      
      $casts[] = [
        'post_id' => $post_id ,
        'user_id' => $this->uid ,
        'cast_real_name' =>  $cast['name'] ,
        'cast_script_name' =>  $cast['role'] , 
        'created_at' => date('Y-m-d H:i:s') 
      ];
    }
    return $casts;
  }

  function CreateThumbImage($type,$image_files,$path,$post_id){
    try{
      $r            = $this->audition_functions->searchForId($type,'type',$image_files) ;
      if(!isset($r['filename'])){
        $r            = $this->audition_functions->searchForId('16x9','type',$image_files) ;
      }
      $thumbName    = rand().'.jpeg';
      
      $AbsPath      = ABS_PATH .'uploads/aud_'.$this->uid.'/images/' ;
      $pathToImages = $AbsPath.$thumbName ;
      
      if (!is_dir($AbsPath)){
        $this->respMessage  = 'Something went wrong in thumb creation.';
        return false;
      }
      
      if(file_put_contents($pathToImages, file_get_contents($path.$r['filename']))){
        
        $this->convert_image_webp->convertIntoWebp($pathToImages);
      
        $this->audition_functions->resizeImage('315','217',$pathToImages,'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);	
      
        $breakThumb = explode('.',$thumbName);
        
        $pathToThumbImage = $AbsPath.$breakThumb[0].'_thumb.'.$breakThumb[1];
        
        if(file_exists($pathToThumbImage)){
          $this->convert_image_webp->convertIntoWebp($pathToThumbImage);
        }
      
        $this->DatabaseModel->access_database('channel_post_thumb','insert', ['post_id'=> $post_id ,'image_name' =>$thumbName,'user_id'=>$this->uid,'active_thumb'=>1]);

        return true;
      
      }
    }catch(Exception $e) {
      $this->respMessage  = $e->getMessage();
      return false;
    }

  }

  function checkNgetVideo($type,$video_files,$path,$uploadPath){
    $r      = $this->audition_functions->searchForId($type ,'type',$video_files) ;
    if(isset($r['filename']) && !empty($r['filename'])){
      $target = "aud_{$this->uid}/videos/".uniqid(). '.mp4';
      $this->audition_functions->videoDuration($uploadPath.$r['filename']);

      $fileSize = getSize($path.$r['filename']); // Check file size if greater than 5GB

      if($fileSize['status']==1){
        if($fileSize['filesize']>5368709120){
          $rr = multipartUploader_ad($source = $uploadPath.$r['filename'] , $key = $target ,$ci_mime=true);
        }else{
          $rr = copyObjectFilmhub($path.$r['filename'], $target );
        }
      }else{
        $this->respMessage  = 'Size Error '.$fileSize['message'];
        return false;
      }

      if($rr['status']==1){
        return $rr['key'];
      }else{
        $this->respMessage  = 'File upload error '.$rr['message'];
        return false;
      }
      
    }else{
      return false;
    }
  }

  function checkNGetGenreId($cond){ 
    $genresId = 0;
    $isGenre = $this->DatabaseModel->select_data('genre_id','mode_of_genre',$cond,1);
    if(empty($isGenre)){
      // $genresId = $this->DatabaseModel->insert_data('mode_of_genre', array(
      //   'mode_id'     => $cond['mode_id'],
      //   'genre_name'  => ucfirst($cond['genre_name']),
      //   'genre_slug'  => slugify($cond['genre_name']),
      //   'status'      => 1
      // ));	
        $genresId = ($cond['mode_id'] == 2)? 426: 427;
    }else{
      $genresId = $isGenre[0]['genre_id'];
    }
    return $genresId;
  }

  function parseYmal($yaml){
    $r = str_replace("[","",file_get_contents($yaml));
		$r = str_replace("]","",$r);
		$r = str_replace("'","",$r);
		$r = str_replace("\"","'",$r);

		$r = trim(preg_replace('/\t+/', '',$r ));
	
		$yaml = <<<EOD
---
{$r}
...
EOD;

return yaml_parse($yaml);
  }
 

  function convertTowebp(){
    $AbsPath      = ABS_PATH .'uploads/aud_'.$this->uid.'/images/637761919.jpeg' ;
    $this->audition_functions->resizeImage('315','217',$AbsPath,'',$maintain_ratio = TRUE,$create_thumb= TRUE,95);	
    die;
    $m = @ imagecreatefromjpeg( $AbsPath);
    imagewebp($m,$AbsPath.'.webp',50);
    imagedestroy($m);
    die;
  }

  
  public function FilmhubAutoUpload(){ 
    $cond = "( status = 2 OR status = 1 ) AND type = 'Show' ";
    $checkProcess 	= $this->DatabaseModel->select_data('*','filmhub_listobjects',$cond,2);
    
    $r      = $this->audition_functions->searchForId((string) 2,'status',$checkProcess) ;
    if(empty($r)){
      $r      = $this->audition_functions->searchForId((string) 1,'status',$checkProcess) ;
      $s      = $this->common->CallCurl('POST',['film_id' => $r['film_id'] ], base_url('filmhub/readYamlFile'),[]);
      $d      = json_decode($s,true);
      
      $filmhub_uid = $r['type'] == 'Series' || $r['type'] == 'Show' ? 398 : 402;

      $this->DatabaseModel->access_database('filmhub_listobjects','update',['status' => 2,'assign_to_uid' => $filmhub_uid ], array('film_id'=>$r['film_id']));

      $this->uploadFilmhubContent($d['data'],$r['film_id'],$filmhub_uid);
    }
  }

  function uploadFilmhubContent($data,$film_id,$filmhub_uid){
    if(is_array($data['contents']) && $this->filmhub_queue < sizeof($data['contents']) ){

        $array = [
          'film_id'         => $film_id,
          'filmhub_uid'     => $filmhub_uid,
          'data'            => json_encode($data),
          'work'            => json_encode($data['contents'][$this->filmhub_queue]),
          'contentsLength'  => sizeof($data['contents']),
          'filzmhub_queue'  => $this->filmhub_queue
        ];
       
        $r = $this->common->CallCurl('POST',$array, base_url('filmhub/ingest_film'),[]);
      
        $d = json_decode($r,true);
       
        if($d['status'] == 1){
            $this->filmhub_queue++;
            $this->uploadFilmhubContent($data,$film_id,$filmhub_uid);
        }
    }
  }

  public function readYamlFile(){
    
    if((isset($_POST['film_id']) && !empty($_POST['film_id']))){
      $filmId       = $_POST['film_id'];

      $listObject 	= $this->DatabaseModel->select_data('*','filmhub_listobjects',array('film_id'=>$filmId),1);
      $listprefix   = (isset($listObject[0]['film_id']))? $listObject[0]['prefix'] :'';

      $bucket_path  = 'https://'.FILMHUB_BUCKET.'.s3.amazonaws.com/';
      $path         = $bucket_path . $listprefix;
          
      $Prefix       = explode('_',$listprefix);
      $postfix      = $Prefix[sizeof($Prefix) - 1];
      
      $key          = str_replace($postfix,"metadata.yaml",$listprefix);
      $yaml         = $path . $key;

      $parsed       = $this->parseYmal($yaml);
      
      $r = $this->audition_functions->searchForId('trailer','type', $parsed['files']['videos']) ;
      
      if($parsed['programming_type'] == 'Single Work' || $parsed['programming_type'] == 'Movie'){
        if(isset($r['filename']) && !empty($r['filename'])){
          $contents = ['trailer','main'];
        }else{
          $contents = ['main'];
        }
      }else
      if($parsed['programming_type'] == 'Series' || $parsed['programming_type'] == 'Show'){
        $contents = [['sku' => 'trailer', 'name' => 'trailer' ,'season_number' => 0,'episode_number' => 1]]; 
       
        if(isset($r['filename'])){
          $contents = array_merge($contents,$parsed['episodes']);
        }else{
          $contents = $parsed['episodes'];
        }
      }
    }
    $data['data'] = ['listprefix' => $listprefix , 'parsed' => $parsed , 'contents' => $contents,'bucket_path' => $bucket_path , 'key' => $key];
    $this->statusCode  	= 1;
    $this->statusType   = 'Success';
    $this->respMessage  = 'Video fetched successfully.';
    $this->show_my_response($data);
  }

  public function ingest_film(Type $var = null)
  {   
    if((isset($_POST['filmhub_uid']) && !empty($_POST['filmhub_uid'])) && (isset($_POST['film_id']) && !empty($_POST['film_id']))){
          $this->uid = $_POST['filmhub_uid'];

          $this->db->trans_begin();

          $filmhub_plan   = 2;
          $filmId         = $_POST['film_id'];
          $contentsLength = $_POST['contentsLength'];
          $filzmhub_queue = $_POST['filzmhub_queue'];

          $_POST['data']  = json_decode($_POST['data'],true);

          $listprefix     = $_POST['data']['listprefix'];
          $bucket_path    = $_POST['data']['bucket_path'];
          $key            = $_POST['data']['key'];
            
          $path     = $bucket_path . $listprefix;

          $parsed   = $_POST['data']['parsed'];
          
          $mode     = ($parsed['programming_type'] == 'Single Work' || $parsed['programming_type'] == 'Movie') ? 2 :  (($parsed['programming_type'] == 'Series' || $parsed['programming_type'] == 'Show') ? 3 : 1) ;
          
          $genre    = $this->checkNGetGenreId(['mode_id' => $mode , 'genre_name' =>  $parsed['genre']]);
          
          $work = json_decode($_POST['work'],true);
          
          if($parsed['programming_type'] == 'Single Work' || $parsed['programming_type'] == 'Movie'){
  
            $array_single_work = ['trailer','main'];
                  
            try{
                $vid_provider_id = $listprefix. '|' . $work;
                $isCompleted 	= $this->DatabaseModel->select_data('post_id','channel_post_video',array('vid_provider_id'=>$vid_provider_id,'delete_status' => 0),1);

                if(!isset($isCompleted[0]['post_id'])){
                  $video = $this->checkNGetVideo($work, $parsed['files']['videos'], $listprefix,$path);
                  if($video){
                    
                    $channel_array = [
                    'video_type'      => 5,
                    'vid_provider_id' => $vid_provider_id,
                    'post_key'        => '',
                    'uploaded_video'  => $video,
                    'user_id'         => $this->uid,
                    'parent_uname'    => $this->parentUname,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'mode'            => $mode,
                    'genre'           => $genre,
                    'language'        => $parsed['language'] == 'en' ? 'en_US' : $parsed['language'],
                    'age_restr'       => '13+', //'Unrestricted',
                    'title'           => ( $work == 'trailer' ? 'Official Trailer - ' : '') . $parsed['title'],
                    'slug'            => slugify(strtolower(str_replace(" ","-",$parsed['title']))),
                    'description'     => $parsed['description'],
                    'video_duration'  => isset($_SESSION['video_duration'])?$_SESSION['video_duration']: ( $parsed['running_time'] * 60 ) ,
                    'tag'             => implode(',',$parsed['tags']),
                    'social'          => 0,
                    'privacy_status'  => 7,
                    'active_status'   => 1,
                    'complete_status' => 1,
                    'delete_status'   => 0,
                    'video_ads_rate_plan' => $filmhub_plan,
                    ];
        
                    $post_id  = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
                    $post_key = $this->share_url_encryption->share_single_page_link_creator('2|'.$post_id,'encode','id');
                    $this->DatabaseModel->access_database('channel_post_video','update',array('post_key'=>$post_key[0]), array('post_id'=>$post_id));
                    
                    $this->query_builder->changeVideoCount($this->uid,'increase'); 
                    $th = $this->CreateThumbImage('landscape_16x9',$parsed['files']['images'],$path,$post_id);
                    
                    $cast_array = $this->createArrCast($parsed['cast'],$post_id);
                    if(!empty($cast_array)){
                      $this->db->insert_batch('channel_cast_images', $cast_array);
                    }
                    if($work == 'main'){
                      $srt_array = @ $this->addAndUploadSrtFiles($parsed['files']['videos'],$post_id,$path);
                      if(!empty($srt_array))
                      $this->db->insert_batch('channel_post_caption', $srt_array);
                      
                      $set = ['assign_to_uid'=>$this->uid,'status'=>1];
                      if($contentsLength-1 == $filzmhub_queue) $set['status'] = 3;

                      $this->DatabaseModel->access_database('filmhub_listobjects','update',$set, array('film_id'=>$filmId));
                    }

                    $this->addPidInFilmhubPlan($filmhub_plan,$post_id);

                    @ upload_all_images($this->uid);
                  }
                }
                
                $this->statusCode  	= 1;
                $this->statusType   = 'Success';
                $this->respMessage  = 'Video uploaded successfully.';
            }catch(Exception $e) {
              $this->DatabaseModel->access_database('filmhub_listobjects','update',['assign_to_uid'=>$this->uid,'status'=> 4 ], array('film_id'=>$filmId));
              $this->respMessage  = 'Error in loop:- '.$e->getMessage();
            }
          }else if($parsed['programming_type'] == 'Series' || $parsed['programming_type'] == 'Show'){
            $array_image = ['landscape_16x9'];
    
            try{  
                $vid_provider_id = $listprefix. '|' . $work['sku'];
                $isCompleted 	= $this->DatabaseModel->select_data('post_id','channel_post_video',array('vid_provider_id'=>$vid_provider_id,'delete_status' => 0),1);

                if(!isset($isCompleted[0]['post_id'])){
                  $video = $this->checkNGetSeriesVideo($work, $parsed['files']['videos'], $listprefix , $path);
                  if($video){
                    $channel_array = [
                      'video_type'      => 5,
                      'vid_provider_id' => $vid_provider_id,
                      'post_key'        => '',
                      'uploaded_video'  => $video,
                      'user_id'         => $this->uid,
                      'parent_uname'    => $this->parentUname,
                      'created_at'      => date('Y-m-d H:i:s'),
                      'mode'            => $mode,
                      'genre'           => $genre,
                      'language'        => $parsed['language'] == 'en' ? 'en_US' : $parsed['language'],
                      'age_restr'       => '13+', //'Unrestricted',
                      'title'           => ( $work['sku'] == 'trailer' ? 'Official Trailer - ' .$parsed['title'] : "S".$work['season_number']."E".$work['episode_number']  .' - '. $parsed['title'] . ' | ' . $work['name'] ) ,
                      'slug'            => slugify(strtolower(str_replace(" ","-",$parsed['title']))),
                      'description'     => ( $work['sku'] == 'trailer' ? $parsed['description'] : $work['description'] ) . $parsed['title'],
                      'video_duration'  => isset($_SESSION['video_duration'])?$_SESSION['video_duration']: ( $parsed['running_time'] * 60 ) ,
                      'tag'             => implode(',',$parsed['tags']),
                      'social'          => 0,
                      'privacy_status'  => 7,
                      'active_status'   => 1,
                      'complete_status' => 1,
                      'delete_status'   => 0,
                      'video_ads_rate_plan' => $filmhub_plan,
                      ];
          
                      $post_id  = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
                      $post_key = $this->share_url_encryption->share_single_page_link_creator('2|'.$post_id,'encode','id');
                      $this->DatabaseModel->access_database('channel_post_video','update',array('post_key'=>$post_key[0]), array('post_id'=>$post_id));
                      $this->query_builder->changeVideoCount($this->uid,'increase'); 
                      
                      $imgkey = isset($array_image[$filzmhub_queue])?$array_image[$filzmhub_queue]:'other';
                      $th = $this->CreateSeriesThumbImage($imgkey, $work , $parsed['files']['images'],$path,$post_id);
                   
                      $cast_array = $this->createArrCast($parsed['cast'],$post_id);
                      if(!empty($cast_array)){
                          $this->db->insert_batch('channel_cast_images', $cast_array);
                      }
                      if($work['sku'] != 'trailer'){
                        $srt_array = @ $this->addAndUploadSeariesSrtFiles($work, $parsed['files']['videos'], $post_id, $path);
                        if(!empty($srt_array) && isset($srt_array[0]))
                            $this->db->insert_batch('channel_post_caption', $srt_array);
                      }
                      
                      $set = ['assign_to_uid'=>$this->uid];
                      if($contentsLength-1 == $filzmhub_queue){
                        $this->createPlaylist($listprefix,$filmId,$this->uid);
                        $set['status'] = 3;
                      }
                      $this->DatabaseModel->access_database('filmhub_listobjects','update',$set, array('film_id'=>$filmId));
                     
                      $this->addPidInFilmhubPlan($filmhub_plan,$post_id);
                      
                      @ upload_all_images($this->uid);
                  }
                }
                  

                  $this->statusCode  	= 1;
                  $this->statusType   = 'Success';
                  $this->respMessage  = 'Video uploaded successfully.';
                  
            }catch(Exception $e) {
              $this->DatabaseModel->access_database('filmhub_listobjects','update',['assign_to_uid'=>$this->uid,'status'=> 4 ], array('film_id'=>$filmId));
              $this->respMessage  = $e->getMessage();
              return $this->show_my_response();die;
            }
      }
        
      if($this->db->trans_status() === FALSE) {
        $this->DatabaseModel->access_database('filmhub_listobjects','update',['assign_to_uid'=>$this->uid,'status'=> 4 ], array('film_id'=>$filmId));
              
        $this->db->trans_rollback();
      }else {
        $this->db->trans_commit();
      }
    }else{
      $this->respMessage  = 'Params not available.';
    }
    $this->show_my_response([]);
  }

  public function addPidInFilmhubPlan($filmhub_plan,$post_id){
    $plan_vid_info 	= $this->DatabaseModel->select_data('video_ids','ads_global_rate_details',array('rdetail_id'=>$filmhub_plan,'status'=>1),1);
    if(isset($plan_vid_info[0]['video_ids'])){
      $video_ids = !empty($plan_vid_info[0]['video_ids']) ? json_decode($plan_vid_info[0]['video_ids'],true) : [];
      array_push($video_ids,$post_id);
      $this->DatabaseModel->access_database('ads_global_rate_details','update',['video_ids' => json_encode(array_unique($video_ids))], array('rdetail_id'=>$filmhub_plan));
    }
  }

  public function createPlaylist($search,$film_id,$user_id){
    $cond = "delete_status = 0 && vid_provider_id LIKE '%".$search."%'";
    $post_ids 	= $this->DatabaseModel->select_data('post_id','channel_post_video',$cond);
   
    $post_bar = '';
    $first_video_id  = 0;
    foreach($post_ids as $key => $post_id){
      $post_bar .= '|'.$post_id['post_id'];
      if($key == 0){
        $first_video_id = $post_id['post_id'];
      }
    }
    
    $r = $this->common->CallCurl('POST',['film_id' => $film_id], base_url('filmhub/readYamlFile'),[]);
    $d    = json_decode($r,true);
    
    $title = isset($d['data']['parsed']['title'])?$d['data']['parsed']['title']:'';
    $playlist = $this->DatabaseModel->access_database('channel_video_playlist','insert',['title' => $title, 'user_id' =>$user_id,'privacy_status' => 7,'first_video_id' => $first_video_id  ,'video_ids'=> $post_bar ,'playlist_type'=> 2, 'created_at' => date('Y-m-d H:i:s')]);
  }

  public function refreshFilmhubObjectList()
  {
    try{
      
      $listObject 	= $this->DatabaseModel->select_data('*','filmhub_listobjects','',1,'',['film_id' , 'DESC']);
      $Marker       = (isset($listObject[0]['film_id']))? $listObject[0]['prefix'] :'';
      
      if(isset($listObject[0]['film_id'])){
        $Marker = explode('|',$listObject[0]['prefix'])[0];
      }

      $r  = getAllfilmhubObjects($step = 1,$Prefix='',$Marker='');
      
      if(isset($r['CommonPrefixes'])){
          foreach($r['CommonPrefixes'] as $list){
            $listObject 	= $this->DatabaseModel->select_data('film_id','filmhub_listobjects',array('prefix'=>$list['Prefix']));
            if(empty($listObject)){
              $film_id = $this->DatabaseModel->access_database('filmhub_listobjects','insert',['prefix'=> $list['Prefix' ] ,'status'=>1, 'created_at' => date('Y-m-d H:i:s')]);
              
              $r    = $this->common->CallCurl('POST',['film_id' => $film_id], base_url('filmhub/readYamlFile'),[]);
              $d    = json_decode($r,true);
              $type = isset($d['data']['parsed']['programming_type'])?$d['data']['parsed']['programming_type']:'';
              $this->DatabaseModel->access_database('filmhub_listobjects','update',array('type'=>$type), array('film_id'=>$film_id));
            }else{
              $film_id = $listObject[0]['film_id'];
            }
          }
          $this->statusCode  	= 1;
          $this->statusType   = 'Success';
          $this->respMessage  = 'List refresh successfully.';
      }else{
        $this->respMessage  = 'List already refreshed.';
      }
    }catch(Exception $e) {
      $this->respMessage  = $e->getMessage();
    }
    $this->show_my_response();

  }

  


}




?>
