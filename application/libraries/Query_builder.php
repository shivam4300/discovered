<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Query_builder { 
	
	Private $statusCode = 0;
	Private $statusType = 'Error';
	Private $respMessage = '';
	
	function __Construct(){
		$this->CI = get_instance();
	}
	
	private function show_my_response($resp = array()){
		$resp['status'] = $this->statusCode;
		$resp['type'] = $this->statusType;
		$resp['message'] = $this->respMessage;
		return $resp;
	}
	
	function check_my_table_fields($fieldArray,$field = null){
		$fields = $field;
		
		if($fields != '*' && is_array(explode(',' , $fields))){
			$fieldsArray = array();
			foreach(explode(',' , $fields) as $fName){
				if(in_array($fName , $fieldArray)){
					array_push($fieldsArray , $fName);
				}
			}
		}else{
			$fieldsArray = $fieldArray;
		}
		//print_r($fieldsArray); exit;
		return implode(',' , $fieldsArray);
	}
	
	function check_my_limit($defautlLimit = NULL){
		$limit = (isset($_GET['limit']))?$_GET['limit']:$defautlLimit;

		if(count(explode(',' , $limit)) > 1){
			$limit = explode(',' , $limit);
		}
		return $limit;
	}
	
	function check_my_order($fieldArray,$defautOrder = NULL){
		$checkOrder = $defautOrder;
		// echo "<pre>";
		// print_r($fieldArray);
		// exit; 
		if(count(explode(',' , $checkOrder)) > 1 && in_array(explode(',' , $checkOrder)[0] , $fieldArray)){
			$checkOrder = explode(',' , $checkOrder);
		}
		
		return $checkOrder;
	}
	
	/***************************************************************************/
	/*************************** Common Section start **************************/
	/***************************************************************************/
	
	
	
	
	function user_list($accessParam = null){
		
		$users_fields			= array('users.user_id','users.user_uname','users.user_name','users.user_email','users.user_phone','users.user_key','users.user_status','users.user_level','users.user_regdate','users.user_dir','users.referral_by','users.referral_from','users.user_address');
		
		$users_content_fields 	= array('users_content.uc_type','users_content.uc_city','users_content.uc_gender','users_content.uc_phone','users_content.uc_email','users_content.uc_name','users_content.uc_website','users_content.uc_pic','users_content.aws_s3_profile_video','users_content.uc_about','users_content.is_iva','users_content.is_video_processed','users_content.uc_video','users_content.uc_dob','users_content.is_fc_member');
		
		$artist_category		= array('artist_category.category_id','artist_category.category_name');
		$country				= array('country.country_name');
		$state					= array('state.name');
		
		$basicFields 			= array_merge($users_fields,$users_content_fields,$artist_category,$country,$state);
		
		
		$user_content_table_join_type 	= (isset($accessParam['user_content_table_join_type']))? $accessParam['user_content_table_join_type'] : 'left';
		
		$join = array('multiple' , array(
									array(	'users_content', 
											'users.user_id 				= users_content.uc_userid', 
											$user_content_table_join_type),
									array(	'artist_category', 
											'users.user_level 			= artist_category.category_id', 
											'left'),		
									array(	'country', 
											'users_content.uc_country 	= country.country_id', 
											'left'),
									array(	'state',
											'users_content.uc_state 	= state.id',
											'left'),
									));
		
			
		$field 				= (isset($accessParam['field']))? $accessParam['field'] : '';
		$selectFields 		= $this->check_my_table_fields($basicFields,$field);
		
		$lim 				= (isset($accessParam['limit']))? $accessParam['limit'] : '';
		$limit 				= $this->check_my_limit($lim);
		
		$ord 				= (isset($accessParam['order']))? $accessParam['order'] : '';
		$order 				= $this->check_my_order($basicFields,$ord);
			
			
		$cond = "users.user_role = 'member'" ;
			
		if(isset($accessParam['where'])){
			$whereData = explode(',' , $accessParam['where']);
			foreach($whereData as $wCond){
				$condAray = explode('=' , $wCond);
				if(count($condAray) > 1){
					
					if($condAray[0] == 'user_id'){	
						$cond .= " AND  users.user_id 	= '".$condAray[1]."'";
					}
					
					if($condAray[0] == 'user_status'){
						$cond .= " AND  users.user_status 	= '".$condAray[1]."'";
					}
					if($condAray[0] == 'is_deleted'){
						$cond .= " AND  users.is_deleted 	= '".$condAray[1]."'";
					}
					if($condAray[0] == 'user_role'){
						$cond .= " AND users.user_role 	= '".$condAray[1]."'";
					}
					if($condAray[0] == 'user_email'){
						$cond .= " AND users.user_email 	= '".$condAray[1]."'";
					}
					if($condAray[0] == 'user_uname'){
						$cond .= " AND users.user_uname 	= '".$condAray[1]."'";
					}
					if($condAray[0] == 'not_user_uname'){
						$cond .= " AND users.user_uname != ''";
					}
					if($condAray[0] == 'not_user_level'){
						$cond .= " AND users.user_level != '".$condAray[1]."'";;
					}
					if($condAray[0] == 'official_status'){
						$cond .= " AND users.official_status = '".$condAray[1]."'";
					}
					if($condAray[0] == 'referral_by'){
						$cond .= " AND users.referral_by 	= '".$condAray[1]."'";
					}
					if($condAray[0] == 'uc_type'){
						$cond .= " AND FIND_IN_SET($condAray[1],users_content.uc_type)";
					}

					if($condAray[0] == 'keyword'){
						$cond .= " AND (users.user_uname LIKE '".$condAray[1]."%' OR  users.user_name LIKE '".$condAray[1]."%' OR users.user_email LIKE '".$condAray[1]."%'  OR artist_category.category_name LIKE '%".$condAray[1]."%')";
					}
					
					if($condAray[0] == 'staff_ids'){
						$instr = str_replace('|',',',$condAray[1]); 
						$cond .= " AND users_content.staff_id IN($instr)";
					}
				}
			}
		}
		
		$requestData 	= $this->CI->DatabaseModel->select_data($selectFields , 'users' , $cond , $limit  , $join, $order , '' , '' , '');
		if(!empty($requestData)){
			$resp = array(
						'users' => $requestData
						);
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = '';
		}else{
			$this->respMessage = 'users not available.';
		}
		
		return $this->show_my_response((isset($resp))?$resp:array());
	}
	
	function category_list($accessParam = null){
		
		$category_fields	= array('category_id','parent_id','level','category_name','status','category_order');
		$basicFields 		= array_merge($category_fields);
		
		$join = '';
		$field 			= (isset($accessParam['field']))? $accessParam['field'] : '';
		$selectFields 	= $this->check_my_table_fields($basicFields,$field );
		
		$lim 			= (isset($accessParam['limit']))? $accessParam['limit'] : '';
		$limit 			= $this->check_my_limit($lim);
		
		$ord 			= (isset($accessParam['order']))? $accessParam['order'] : '';
		$order 			= $this->check_my_order($basicFields,$ord);
			
			
		$cond 			= "" ;
		// $cond 			= "status = 1" ;	
		if(isset($accessParam['where'])){
			
			$whereData = explode(',' , $accessParam['where']);
			foreach($whereData as $wCond){
				
				$condAray = explode('=' , $wCond);
				if(count($condAray) > 1){
					if($condAray[0] == 'keyword'){
						$cond .= "(category_name LIKE '%".$condAray[1]."%') ";
					}
					if($condAray[0] == 'status'){
						$cond .= "AND status = '".$condAray[1]."'";
					}
					if($condAray[0] == 'level'){
						$cond .= " AND level = '".$condAray[1]."'";
					}if($condAray[0] == 'category_id'){
						$cond .= "category_id = '".$condAray[1]."'";
					}if($condAray[0] == 'parent_id'){
						$cond .= "AND parent_id = '".$condAray[1]."'";
					}if($condAray[0] == 'parents_id'){
						$cond .= " parent_id = '".$condAray[1]."'";
					}
				}
			}
		}
		$cond = trim($cond,"AND");
		$requestData 	= $this->CI->DatabaseModel->select_data($selectFields , 'artist_category' , $cond , $limit  , $join, $order , '' , '' , '');
		if(!empty($requestData)){
			$resp = array(
						'category' => $requestData
						);
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = '';
		}else{
			$this->respMessage = 'category not available.';
		}
		
		return $this->show_my_response((isset($resp))?$resp:array());
	}
	
	
	function channel_video_list($accessParam = null){
		
		$channel_post_video		= array('channel_post_video.post_id','channel_post_video.post_key','channel_post_video.uploaded_video','channel_post_video.user_id','channel_post_video.created_at','channel_post_video.mode','channel_post_video.genre','channel_post_video.category','channel_post_video.language','channel_post_video.age_restr','channel_post_video.title','channel_post_video.slug','channel_post_video.description','channel_post_video.tag','channel_post_video.social','channel_post_video.privacy_status','channel_post_video.active_status','channel_post_video.complete_status','channel_post_video.count_views','channel_post_video.count_votes','channel_post_video.iva_id','channel_post_video.featured_by_admin','channel_post_video.video_duration','channel_post_video.is_video_processed','channel_post_video.video_type');
		
		$users_fields			= array('users.user_uname','users.user_name','users.user_email','users.user_phone','users.user_address','users.user_level');
		$users_content			= array('users_content.uc_pic','users_content.uc_city');
		$website_mode			= array('website_mode.mode AS web_mode');
		$mode_of_genre			= array('mode_of_genre.genre_name','mode_of_genre.genre_slug');
		$artist_category		= array('artist_category.category_name');
		$language_list			= array('language_list.id AS language','language_list.value AS lang','language_list.value');
		$channel_post_thumb		= array('channel_post_thumb.post_id AS post_thumb_id','channel_post_thumb.thumb_id','channel_post_thumb.image_name'); 
		$country				= array('country.country_name');
		$state					= array('state.name');
		$basicFields 			= array_merge($channel_post_video,$users_fields,$users_content,$website_mode,$mode_of_genre,$artist_category,$language_list,$channel_post_thumb,$country,$state);
		
		$join = array('multiple' , array(
									array(	'users', 
											'users.user_id 				= channel_post_video.user_id', 
											'left'),
									array(	'users_content', 
											'users.user_id 				= users_content.uc_userid', 
											'left'),		
									array(	'website_mode', 
											'website_mode.mode_id 		= channel_post_video.mode', 
											'left'),		
									array(	'mode_of_genre', 
											'mode_of_genre.genre_id 	= channel_post_video.genre', 
											'left'),
									array(	'artist_category',
											'artist_category.category_id= channel_post_video.category',
											'left'),
									array(	'language_list',
											'language_list.id 			= channel_post_video.language',
											'left'),
									array(	'channel_post_thumb',
											'channel_post_thumb.post_id = channel_post_video.post_id',
											'left'),
									array(	'country',
											'country.country_id = users_content.uc_country',
											'left'),
									array(	'state',
											'users_content.uc_state 	= state.id',
											'left')
									));
									
		
		if(isset($accessParam['JoinTableAndType']) && !empty($accessParam['JoinTableAndType'])){
			$tablesNameAndJoinType = (explode(",",$accessParam['JoinTableAndType']));
			
			foreach($tablesNameAndJoinType as $TNAJT){
				$condAray = explode('|' , $TNAJT);
				if(count($condAray) > 1){
					if($condAray[0] == 'channel_favorite_video'){
						$basicFields  = array_merge($basicFields ,array('channel_favorite_video.fav_id'));
						array_push($join[1],array('channel_favorite_video',
													   'channel_favorite_video.channel_post_id 	= channel_post_video.post_id',
													   $condAray[1]));
						 
					}
				}
			}
		}
		
		
		
		$field 			= (isset($accessParam['field']))? $accessParam['field'] : '';
		$selectFields 	= $this->check_my_table_fields($basicFields,$field);
		
		$lim 			= (isset($accessParam['limit']))? $accessParam['limit'] : '';
		$limit 			= $this->check_my_limit($lim);
		
		$ord 			= (isset($accessParam['order']))? $accessParam['order'] : '';
		$order 			= $this->check_my_order($basicFields,$ord);
			
		/* [active_status,complete_status,privacy_status,delete_status,active_thumb,user_status,is_deleted]*/
		$this->CI->load->library('common');
		$cond = $this->CI->common->channelGlobalCond([1,1,NULL,0,1,1,0]);
		
		$copy = $cond ;
		$state = '';
		if(isset($accessParam['where'])){
			$whereData = explode(',' , $accessParam['where']);
			foreach($whereData as $wCond){
				$condAray = explode('=' , $wCond);
				if(count($condAray) > 1){
					
					if($condAray[0] == 'keyword'){
						$cond .= $search = " AND (users.user_name LIKE '%".$condAray[1]."%' OR users.user_uname LIKE '%".$condAray[1]."%' OR  channel_post_video.title LIKE '%".$condAray[1]."%')";
					}
					if($condAray[0] == 'post_id'){
						$cond .= " AND channel_post_video.post_id = '".$condAray[1]."'";
					}
					if($condAray[0] == 'post_id_in'){
						$cond .= " AND channel_post_video.post_id IN('".$condAray[1]."')";
					}
					if($condAray[0] == 'not_post_id'){
						$cond .= " AND channel_post_video.post_id != '".$condAray[1]."'";
					}
					if($condAray[0] == 'next_post_id'){
						$cond .= " AND channel_post_video.post_id > '".$condAray[1]."'";
					}
					if($condAray[0] == 'mode'){
						$cond .= " AND channel_post_video.mode = '".$condAray[1]."'";
					}
					if($condAray[0] == 'genre'){
						$cond .= " AND channel_post_video.genre = '".$condAray[1]."'";
					}
					
					if($condAray[0] == 'user_id'){
						$cond .= " AND channel_post_video.user_id = '".$condAray[1]."'";
					}
					
					if($condAray[0] == 'featured_by_admin'){
						$cond .= " AND channel_post_video.featured_by_admin = '".$condAray[1]."'";
					}
					if($condAray[0] == 'privacy_status'){
						$cond .= " AND channel_post_video.privacy_status = ".$condAray[1];
					}
					if($condAray[0] == 'privacy_status_in'){ 
						$privacy_status = str_replace('|',',',$condAray[1]); 
						$cond .= " AND channel_post_video.privacy_status IN($privacy_status)";
					}
					if($condAray[0] == 'posts_id'){
						$cond .= " AND channel_post_video.post_id = '".$condAray[1]."'";
					}
					if($condAray[0] == 'start_date'){
						$cond .= " AND channel_post_video.created_at >= '".$condAray[1]."'";
					}
					if($condAray[0] == 'end_date'){
						$cond .= " AND channel_post_video.created_at <= '".$condAray[1]."'";
					}
					if($condAray[0] == 'active_status'){
						$cond =  str_replace("channel_post_video.active_status = 1","channel_post_video.active_status = {$condAray[1]}",$cond);
					}
					if($condAray[0] == 'complete_status'){
						$cond =  str_replace("channel_post_video.complete_status = 1","channel_post_video.complete_status = {$condAray[1]}",$cond);
					}
					
					if($condAray[0] == 'category'){
						$cond .= " AND artist_category.category_id = '".$condAray[1]."'";
					}
					if($condAray[0] == 'favorite_user_id'){
						$cond .= " AND channel_favorite_video.user_id = '".$condAray[1]."'";
					}
					if($condAray[0] == 'user_level'){
						$instr = str_replace('|',',',$condAray[1]); 
						$cond .= " AND users.user_level IN($instr)";
					}
					if($condAray[0] == 'user_status'){
						$cond .= " AND  users.user_status 	= '".$condAray[1]."'";
					}
					if($condAray[0] == 'remove_all_status'){
						$cond =  str_replace($copy.' AND',"",$cond);
					}
					if($condAray[0] == 'profanity_words'){
						$state = 'prof_filter';
						$str = file_get_contents( base_url('repo_admin/txt/cs_badWords_php.txt'));
						$str_arr = explode (",", $str); 
						$where = '';
						$total = count($str_arr);
						for ($i=0; $i < $total ; $i++) { 
							$param  = trim($str_arr[$i]);
							$where .= "title REGEXP '[[:<:]]{$param}[[:>:]]' OR description REGEXP '[[:<:]]{$param}[[:>:]]' OR tag REGEXP '[[:<:]]{$param}[[:>:]]'"; 
	
							if($total - 1 !== $i){
								$where .= ' OR ' ;
							}
						}
						$this->CI->db->where($where, '', false);
					}
					if($condAray[0] == 'non_profanity_words'){
						$str = file_get_contents( base_url('repo_admin/txt/cs_badWords_php.txt'));
						$str_arr = explode (",", $str); 
						$where = '';
						$arr = [];
						$total = count($str_arr);

						for ($i=0; $i < $total ; $i++) { 
							$param  = trim($str_arr[$i]);
							$where .= "channel_post_video.title NOT LIKE '% {$param} %' OR channel_post_video.description NOT LIKE '% {$param} %' OR channel_post_video.tag NOT LIKE '% {$param} %'" ; 
							if($total - 1 !== $i){
								$where .= ' OR ' ;
							}
						}
						$cond .= " AND  ($where)";
					}

					if($condAray[0] == 'videolength'){
						$c = explode('-',$condAray[1]);
						$cond  .=  ' AND video_duration BETWEEN "'.($c[0]*60).'" AND "'.($c[1]*60).'" ';
					}
				}
			}
		}

		// $orderin = array('channel_post_video.post_id','DESC');

		// $condin = $this->CI->common->channelGlobalCond([1,1,NULL,0,NULL,1,0]) .  $search   ;

		// $joinin = array('multiple' , 
		// 		array(
		// 			array(	'users', 
		// 					'users.user_id 		= channel_post_video.user_id', 
		// 					'left'),
		// 		)
		// );

		// $search_result = $this->CI->DatabaseModel->select_data($selectFields , 'channel_post_video use INDEX(post_id)' , $condin , $limit  , $joinin, $orderin );
		// $search_post_id = array_column($search_result, 'post_id');
		// $search_post_id = implode(',',$search_post_id);
		// $search_post_id = !empty($search_post_id) ? $search_post_id : 0 ;
		// $condin 		= $cond . " AND channel_post_video.post_id IN($search_post_id) ";

		// $requestData = $this->CI->DatabaseModel->select_data($selectFields , 'channel_post_video use INDEX(post_id)' , $condin , '' , $join);
		
		// $total 		 = $this->CI->DatabaseModel->aggregate_data( 'channel_post_video use INDEX(post_id)','channel_post_video.post_id', 'COUNT' , $cond , $join);

		$requestData = $this->CI->DatabaseModel->select_data($selectFields , 'channel_post_video use INDEX(post_id)' , $cond , $limit  , $join, $order , '' ,$group='', '');
		
		$total 		 = $this->CI->DatabaseModel->aggregate_data( 'channel_post_video use INDEX(post_id)','channel_post_video.post_id', 'COUNT' , $cond , $join,NULL,$group='');
		
		
		if(!empty($requestData)){
			$resp = array(
						'channel' => $requestData,
						'total' => $total
						);
			$this->statusCode = 1;
			$this->statusType = 'Success';
			$this->respMessage = '';
		}else{
			$this->respMessage = 'channel video not available.';
		}
		
		return $this->show_my_response((isset($resp))?$resp:array());
	}
	
	
	
	function outstanding($param){
		
		$coloum_name	= 	isset($param['debit'])?'debit':'credit';
		$value 			= 	$param[$coloum_name];
		
		$user_id 		= 	$param['user_id'];
		
		$cond			=	array('user_id'=> $user_id);
		$order			=	array('outstanding_id','DESC');
		
		$balance 		= 	$this->CI->DatabaseModel->select_data('balance','outstandings use INDEX(user_id)',$cond,1,'',$order);
		
		$balance 		= 	(isset($balance[0]['balance']))? $balance[0]['balance'] : 0 ;
		
		$balance 		= 	($coloum_name == 'debit')? ($balance+$value) : $balance-$value;
			 
		$this->CI->db->set('user_id',$user_id);
		
		$this->CI->db->set('entry_against',$param['entry_against']);
		$this->CI->db->set('created_at',date('Y-m-d H:i:s'));
		$this->CI->db->set($coloum_name,$value);
		$this->CI->db->set('balance', number_format($balance,3) , FALSE); 
		$this->CI->db->insert('outstandings');
		
		
		$this->CI->db->set('outstanding',number_format($balance,3),FALSE);
		$this->CI->db->where('user_id',$user_id);
		$this->CI->db->update('users');
	
	}
	
	function changeVideoCount($uid,$type = 'increase')
	{
		if($type == 'increase')	
		$this->CI->db->set('total_channel_video', '`total_channel_video`+ 1', FALSE);
		else 
		$this->CI->db->set('total_channel_video', '`total_channel_video`- 1', FALSE);
	
		$this->CI->db->where('uc_userid', $uid);
		$this->CI->db->update('users_content');
	}	
	function WhoFollowMeCount($user_id,$user_level,$limit,$start){
		$FollowMe = $this->CI->DatabaseModel->aggregate_data('become_a_fan',' fan_id','COUNT', array('become_a_fan.user_id'=>$user_id,'user_level'=>$user_level),array('users','users.user_id = become_a_fan.following_id'),array($limit,$start));
	
	
		return $FollowMe;
	}
	function WhomIEndorseCount($user_id,$user_level,$limit,$start){
		$FollowMe = $this->CI->DatabaseModel->aggregate_data('become_a_endorser',' become_a_endorser.endorsee_id','COUNT',array('become_a_endorser.endorsement_status'=>2,'become_a_endorser.endorser_id'=>$user_id,'user_level'=>$user_level),array('users','users.user_id = become_a_endorser.endorsee_id'),array($limit,$start));
		return $FollowMe;
	}
	function WhoEndorseMeCount($user_id,$user_level,$limit,$start){
		$FollowMe = $this->CI->DatabaseModel->aggregate_data('become_a_endorser',' become_a_endorser.endorser_id','COUNT',array('become_a_endorser.endorsement_status'=>2,'become_a_endorser.endorsee_id'=>$user_id,'user_level'=>$user_level),array('users','users.user_id = become_a_endorser.endorser_id'),array($limit,$start));
		return $FollowMe;
	}
}
