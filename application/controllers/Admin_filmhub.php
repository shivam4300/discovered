<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_filmhub extends CI_Controller {
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
	public function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['admin'])){
			redirect('auth/logout');
		}
        $this->load->helper(array('aws_s3_action'));
		$this->load->library(array('query_builder'));
		
	}
	function is_ajax(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
	}
	 
	private function show_my_response($resp = array()){
		$resp['status']     = $this->statusCode;
		$resp['type']       = $this->statusType;
		$resp['message']    = $this->respMessage;
		$this->output->set_content_type('application/json');
		$this->output->set_status_header(($resp['status'] == 1)?200:401);
		$this->output->set_output(json_encode($resp));
	}

    public function index(){
       
		$data['page_menu'] = 'filmhub|filmhub_list|Filmhub|filmhub_list'; 
		
		$this->load->view('admin/include/header',$data);
		$this->load->view('admin/filmhub/filmhub_list',$data);
        $this->load->view('common/notofication_popup');
		$this->load->view('admin/include/footer',$data);
	}

    public function access_filmhublist(){
        $this->is_ajax();
		$leadsCount =0;
		$data 	= array();
		
		if(isset($_GET['length']) && !empty(trim($_GET['length']))){
			
			$search = 	$_GET['search']['value'];
			$colm 	=  	(isset($_GET['order'][0]['column']))?	$_GET['order'][0]['column'] : 0 ;
			$order 	=  	(isset($_GET['order'][0]['dir']))?	$_GET['order'][0]['dir'] :  'DESC' ;
			
			$cond = "";
			$start = $_GET['start'];
			
			$filed = array('film_id','type','prefix','status','user_name');
			$join = array('multiple' , array(
                                array('users','users.user_id = filmhub_listobjects.assign_to_uid','left'),
                            
                        ));

			$cond .= '(';
			for($i=0;$i < sizeof($filed); $i++){
				if($filed[$i] != ''){
					$cond .= "$filed[$i] LIKE '%".$search."%'";
					if(sizeof($filed) - $i != 1){
						$cond .= ' OR ';
					}	
				}
			}
			$cond .= ')';
			if(isset($_GET['parent_id']) && !empty($_GET['parent_id'])){
				$cond .=  "AND filmhub_listobjects.status = '{$_GET['parent_id']}'" ;
			}
            if(isset($_GET['type']) && !empty($_GET['type'])){
				$cond .=  "AND filmhub_listobjects.type = '{$_GET['type']}'" ;
			}
			$resultData 	= $this->DatabaseModel->select_data($filed,'filmhub_listobjects', $cond ,array($_GET['length'],$start) , $join, array($filed[$colm],$order) );
			$leadsCount 	= $this->DatabaseModel->aggregate_data('filmhub_listobjects','film_id','COUNT',$cond,$join);
			
			$start++;	
            $statusValue = array(1=>'Pending', 2=>'Processing', 3=>'Complete', 4=>'Failed');
            foreach($resultData as $list){
                $film_id = $list['film_id'];
                $status  = isset($statusValue[$list['status']]) ? $statusValue[$list['status']] : '';
                $action  = '<a data-backdrop="static" data-keyboard="false"  title="Process" data-target="#select_user_for_filmhub_popup" data-toggle="modal" data-prefix-name="'.$list['prefix'].'" data-film-id="'.$film_id.'" data-film-url="filmhub/ingest_film"><i class="fa fa-fw fa-edit"></i></a>';
                if($list['status']==3){
                    $action = '';
                }
                
                array_push($data , array(
                    $start++,
                    $list['type'],
                    $list['prefix'],
                    
                    $list['user_name'],
                    '<p type="button" class="dis_check_status">'. $status . '</p>',
                    $action,
                ));
            }
					
        }

		echo json_encode(array( 
			'draw' => (isset($_GET['draw']))?$_GET['draw']+1:1,
			'recordsTotal' => $leadsCount,
			'recordsFiltered' => $leadsCount,
			'data' => $data, 
			));

    }

    public function ingest_filmvv(Type $var = null)
    {   
        if(isset($_POST['film_id']) && !empty($_POST['film_id'])){

            $filmId = $_POST['film_id'];

            $listObject 	= $this->DatabaseModel->select_data('*','filmhub_listobjects',array('film_id'=>$filmId),1);
            $Marker       = (isset($listObject[0]['film_id']))? $listObject[0]['prefix'] :'';
            
            if(isset($listObject[0]['film_id'])){
            $Marker = explode('|',$listObject[0]['prefix'])[0];
            }
        
            $r            = getAllfilmhubObjects($step = 1,$Prefix='',$Marker);
        
            $bucket_path = 'https://bucket-filmhub-discovered.s3.amazonaws.com/';
        
            if(isset($r['CommonPrefixes'])){
            
                foreach($r['CommonPrefixes'] as $list){
                    
                    $path     = $bucket_path . $list['Prefix'];
            
                    $Prefix   = explode('_',$list['Prefix']);
                    $postfix  = $Prefix[sizeof($Prefix) - 1];
                    
                    $key      = str_replace($postfix,"metadata.yaml",$list['Prefix']);
                    $yaml     = $path . $key;
            
                    $parsed   = $this->parseYmal($yaml);
                
                    $mode     = ($parsed['programming_type'] == 'Single Work') ? 2 :  (($parsed['programming_type'] == 'Series') ? 3 : 1) ;
                    
                    $genre    = $this->checkNGetGenreId(['mode_id' => $mode , 'genre_name' =>  $parsed['genre']]);
                    
                    if($parsed['programming_type'] == 'Single Work'){
            
                    $array_single_work = ['trailer','main'];  
            
                    foreach( $array_single_work  as $work){
            
                        $video = $this->checkNGetVideo($work, $parsed['files']['videos'], $path);
                        
                        $channel_array = [
                        'video_type'      => 5,
                        'vid_provider_id' => $list['Prefix']. '|' . $work,
                        'post_key'        => '',
                        'uploaded_video'  => $video,
                        'user_id'         => $this->uid,
                        'created_at'      => date('Y-m-d H:i:s'),
                        'mode'            => $mode,
                        'genre'           => $genre,
                        'language'        => $parsed['language'] == 'en' ? 'en_US' : $parsed['language'],
                        'age_restr'       => 'Unrestricted',
                        'title'           => ( $work == 'trailer' ? 'Official Trailer - ' : '') . $parsed['title'] . ' | '.  $parsed['tagline'],
                        'slug'            => slugify(strtolower(str_replace(" ","-",$parsed['title']))),
                        'description'     => $parsed['description'],
                        'video_duration'  => isset($_SESSION['video_duration'])?$_SESSION['video_duration']: ( $parsed['running_time'] * 60 ) ,
                        'tag'             => implode(',',$parsed['tags']),
                        'social'          => 0,
                        'privacy_status'  => 7,
                        'active_status'   => 1,
                        'complete_status' => 1,
                        'delete_status'   => 0,
                        ];
            
                        $post_id  = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
                        $post_key = $this->share_url_encryption->share_single_page_link_creator('2|'.$post_id,'encode','id');
                        $this->DatabaseModel->access_database('channel_post_video','update',array('post_key'=>$post_key[0]), array('post_id'=>$post_id));
                        
                        $this->query_builder->changeVideoCount($this->uid,'increase'); 
                        $this->CreateThumbImage('landscape_16x9',$parsed['files']['images'],$path,$post_id);
                        
                        $cast_array = $this->createArrCast($parsed['cast'],$post_id);
                        $this->db->insert_batch('channel_cast_images', $cast_array);
                        
                        if($work == 'main'){
                        $srt_array = @ $this->addAndUploadSrtFiles($parsed['files']['videos'],$post_id,$path);
                        $this->db->insert_batch('channel_post_caption', $srt_array);
                        $this->DatabaseModel->access_database('filmhub_listobjects','insert',['prefix'=> $channel_array['vid_provider_id' ] ,'status'=>3, 'created_at' => date('Y-m-d H:i:s')]);
                        }
                    }
                    @ upload_all_images($this->uid);
                    }else
                    if($parsed['programming_type'] == 'Series'){
                    $array_series = [['sku' => 'trailer','season_number' => 0,'episode_number' => 1]];  
                    $array_image = ['landscape_16x9'];
            
                    $array_series = array_merge($array_series,$parsed['episodes']);
            
                    foreach($array_series as $key => $work){
                        $video = $this->checkNGetSeriesVideo($work, $parsed['files']['videos'], $path);
                        
                        $channel_array = [
                        'video_type'      => 5,
                        'vid_provider_id' => $list['Prefix']. '|' . $work['sku'],
                        'post_key'        => '',
                        'uploaded_video'  => $video,
                        'user_id'         => $this->uid,
                        'created_at'      => date('Y-m-d H:i:s'),
                        'mode'            => $mode,
                        'genre'           => $genre,
                        'language'        => $parsed['language'] == 'en' ? 'en_US' : $parsed['language'],
                        'age_restr'       => 'Unrestricted',
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
                        ];
            
                        $post_id  = $this->DatabaseModel->access_database('channel_post_video','insert',$channel_array);
                        $post_key = $this->share_url_encryption->share_single_page_link_creator('2|'.$post_id,'encode','id');
                        $this->DatabaseModel->access_database('channel_post_video','update',array('post_key'=>$post_key[0]), array('post_id'=>$post_id));
                        $this->query_builder->changeVideoCount($this->uid,'increase'); 
                        
                        $imgkey = isset($array_image[$key])?$array_image[$key]:'other';
                        $this->CreateSeriesThumbImage($imgkey, $work , $parsed['files']['images'],$path,$post_id);
                        
                        $cast_array = $this->createArrCast($parsed['cast'],$post_id);
                        if(!empty($cast_array)){
                            $this->db->insert_batch('channel_cast_images', $cast_array);
                        }
                        if($work['sku'] != 'trailer'){
                        $srt_array = @ $this->addAndUploadSeariesSrtFiles($work, $parsed['files']['videos'], $post_id, $path);
                        if(!empty($srt_array) && isset($srt_array[0]))
                            $this->db->insert_batch('channel_post_caption', $srt_array);
                        }
                        $this->DatabaseModel->access_database('filmhub_listobjects','insert',['prefix'=> $channel_array['vid_provider_id'],'status'=>3, 'created_at' => date('Y-m-d H:i:s') ]);
                    }
                    @ upload_all_images($this->uid);
                    }
                }
            }
        }
      
    }
    
    




}