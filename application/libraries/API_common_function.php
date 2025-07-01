
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API_common_function {
	private $uid;
    public function __construct()
	{
        $this->CI = get_instance();
		
		//if(isset($this->CI->session->userdata['user_login_id']))
		//$this->uid =  $this->CI->session->userdata['user_login_id'];
	}
	
	function WhomIFollow($user_id,$user_level,$limit,$start){  
		
		$IFollows = $this->CI->DatabaseModel->select_data('become_a_fan.user_id,users.user_name,users.user_uname',' become_a_fan', array('become_a_fan.following_id'=>$user_id,'user_level'=>$user_level),array($limit,$start),array('users','users.user_id = become_a_fan.user_id'),'rand()');
		
		if(isset($IFollows[0])){
			 
			foreach($IFollows as $key=>$IFollow){
				$IFollows[$key]['user_pic'] = get_user_image($IFollow['user_id']);
			}
			 
		}		
	
		return $IFollows;
	}
	
	function WhoFollowMe($user_id,$user_level,$limit,$start){  
		
		$FollowMe = $this->CI->DatabaseModel->select_data('become_a_fan.following_id,become_a_fan.user_id,users.user_name,users.user_uname',' become_a_fan', array('become_a_fan.user_id'=>$user_id,'user_level'=>$user_level),array($limit,$start),array('users','users.user_id = become_a_fan.following_id'),'rand()');
		
		if(isset($FollowMe[0])){
			foreach($FollowMe as $key=>$Follow){
				$FollowMe[$key]['user_pic'] = get_user_image($Follow['following_id']);
			}
		}		
	
		return $FollowMe;
	}
	
	function WhomIEndorse($user_id,$user_level,$limit,$start){  
		
		$IEndorsed = $this->CI->DatabaseModel->select_data('become_a_endorser.endorsee_id AS following_id,users.user_name,users.user_uname',' become_a_endorser', array('become_a_endorser.endorsement_status'=>2,'become_a_endorser.endorser_id'=>$user_id,'user_level'=>$user_level),array($limit,$start),array('users','users.user_id = become_a_endorser.endorsee_id'),'rand()');
		
		if(isset($IEndorsed[0])){
			foreach($IEndorsed as $key=>$IEndorse){
				$IEndorsed[$key]['user_pic'] = get_user_image($IEndorse['following_id']);
			}
		}		
	
		return $IEndorsed;
	}
	
	
	function WhoEndorseMe($user_id,$user_level,$limit,$start){  
		
		$EndorsedMe = $this->CI->DatabaseModel->select_data('become_a_endorser.endorser_id AS following_id,users.user_name,users.user_uname',' become_a_endorser', array('become_a_endorser.endorsement_status'=>2,'become_a_endorser.endorsee_id'=>$user_id,'user_level'=>$user_level),array($limit,$start),array('users','users.user_id = become_a_endorser.endorser_id'),'rand()');
		
		if(isset($EndorsedMe[0])){
			foreach($EndorsedMe as $key=>$Endorse){
				$EndorsedMe[$key]['user_pic'] = get_user_image($Endorse['following_id']);
			}
		}		
	
		return $EndorsedMe;
	}
	
	
	
	function getSvgImage($key=''){
		$svgImage='';
		if(!empty($key)){
			
			$svgArr =array(
			
					'music'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20.781 20">
                     <path fill="#78899e" id="Music" d="M56.178,363.977a14.644,14.644,0,0,0-2.337-8.4,8.771,8.771,0,0,0-7.483-3.765,8.771,8.771,0,0,0-7.483,3.765,14.645,14.645,0,0,0-2.336,8.4,5.623,5.623,0,0,0-.566,2.47,5.222,5.222,0,0,0,5.042,5.373,0.855,0.855,0,0,0,.827-0.881v-8.984a0.855,0.855,0,0,0-.827-0.881,4.788,4.788,0,0,0-2.685.829c0.558-4.342,2.871-8.33,8.029-8.33s7.471,3.988,8.029,8.33a4.788,4.788,0,0,0-2.685-.829,0.855,0.855,0,0,0-.827.881v8.984a0.855,0.855,0,0,0,.827.881,5.222,5.222,0,0,0,5.042-5.373A5.624,5.624,0,0,0,56.178,363.977Z" transform="translate(-35.969 -351.813)"/>
                   </svg>',
				   
				   'movies'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20.125 16">
                     <path fill="#78899e" id="Movies" d="M54.725,264H35.27a0.347,0.347,0,0,0-.347.348v15.319a0.348,0.348,0,0,0,.347.348H54.725a0.348,0.348,0,0,0,.347-0.348V264.343A0.347,0.347,0,0,0,54.725,264ZM38.4,279.314H35.617V276.18H38.4v3.134Zm0-3.83H35.617V272.35H38.4v3.134Zm0-3.83H35.617v-3.133H38.4v3.133Zm0-3.83H35.617v-3.133H38.4v3.133Zm9.593,4.54-4.385,3a0.38,0.38,0,0,1-.406.014A0.436,0.436,0,0,1,42.992,275v-6a0.435,0.435,0,0,1,.207-0.376,0.376,0.376,0,0,1,.406.014l4.385,3A0.446,0.446,0,0,1,47.989,272.364Zm6.388,6.95H51.6V276.18h2.779v3.134Zm0-3.83H51.6V272.35h2.779v3.134Zm0-3.83H51.6v-3.133h2.779v3.133Zm0-3.83H51.6v-3.133h2.779v3.133Z" transform="translate(-34.938 -264)"/>
                   </svg>',
				   
				   'television'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18.219 18">
                     <path fill="#78899e" id="Television" d="M52.8,443H39.186a2.488,2.488,0,0,0-2.306,2.633v8.586a2.489,2.489,0,0,0,2.307,2.634h5.891v2.08h-2.7a1.048,1.048,0,0,0,0,2.08h7.225a1.048,1.048,0,0,0,0-2.08h-2.7v-2.08H52.8a2.489,2.489,0,0,0,2.306-2.634v-8.586A2.488,2.488,0,0,0,52.8,443Zm0.173,2.824v8.2a0.489,0.489,0,0,1-.445.522H39.458a0.489,0.489,0,0,1-.445-0.522v-8.2a0.489,0.489,0,0,1,.445-0.522H52.53A0.489,0.489,0,0,1,52.975,445.819Z" transform="translate(-36.875 -443)"/>
                   </svg>',
				   
				   'gaming'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 16">
                     <path fill="#78899e" id="Gaming" d="M54.816,537.6c-1.924-4.249-3.986-4.546-5.905-3.533a4.015,4.015,0,0,0-1.446,1.217H42.328a4.012,4.012,0,0,0-1.446-1.217c-1.92-1.013-3.981-.716-5.905,3.533s-2.984,10.84-1.065,11.853c1.334,0.7,3.754-1.475,5.752-4.294H50.127c2,2.819,4.418,5,5.752,4.294C57.8,548.435,56.74,541.843,54.816,537.6Zm-12.281,2.711a0.325,0.325,0,0,1-.316.333H40.707v1.672a0.325,0.325,0,0,1-.315.334h-1.07a0.326,0.326,0,0,1-.315-0.334v-1.672H37.494a0.325,0.325,0,0,1-.316-0.333v-1.131a0.326,0.326,0,0,1,.316-0.334h1.512v-1.672a0.325,0.325,0,0,1,.315-0.333h1.07a0.324,0.324,0,0,1,.315.333v1.672h1.512a0.326,0.326,0,0,1,.316.334v1.131h0Zm8.657-3.023a1.042,1.042,0,1,1-1.039-1.122A1.083,1.083,0,0,1,51.192,537.283Zm-3.183,3.435a1.125,1.125,0,1,1,1.039-1.123A1.083,1.083,0,0,1,48.008,540.718Zm4.292,0a1.125,1.125,0,1,1,1.039-1.123A1.083,1.083,0,0,1,52.3,540.718Zm-2.148,2.313a1.125,1.125,0,1,1,1.039-1.122A1.082,1.082,0,0,1,50.152,543.031Z" transform="translate(-32.906 -533.563)"/>
                   </svg>',
				   
				   'social'=>'',
				   
				   'live'=>'',
				   
				   'spotlight'=>'<svg xlink="http://www.w3.org/1999/xlink"width="19px" height="19px"><path fill-rule="evenodd" fill="#78899e"d="M16.564,7.210 L13.521,4.019 L13.947,3.572 L13.927,3.572 L13.927,1.113 L5.636,1.113 L5.636,3.572 L5.616,3.572 L6.042,4.019 L2.999,7.210 L1.418,5.552 C0.577,4.670 0.577,3.242 1.418,2.360 C2.257,1.481 3.622,1.481 4.461,2.360 L4.574,2.479 L4.574,1.113 L3.500,1.113 L3.500,-0.000 L16.097,-0.000 L16.097,1.113 L14.988,1.113 L14.988,2.479 L15.102,2.360 C15.941,1.481 17.306,1.481 18.145,2.360 C18.986,3.243 18.986,4.670 18.145,5.552 L16.564,7.210 ZM7.091,19.000 L0.781,19.000 L5.686,13.639 L7.091,19.000 ZM3.625,8.127 L6.908,4.684 L8.214,5.468 L4.383,9.490 L3.625,8.127 ZM13.348,11.453 L10.517,8.359 L11.537,7.244 L13.823,9.642 L13.348,11.453 ZM15.937,8.127 L15.180,9.490 L11.346,5.469 L12.654,4.684 L15.937,8.127 ZM8.024,7.242 L18.782,19.000 L8.191,19.000 L5.739,9.641 L8.024,7.242 Z"/></svg>'
				   
			
			);
			$svgImage = isset($svgArr[$key])? $svgArr[$key] : '';
		}
		return $svgImage;
	}
	
	
	
	
	
}


?>