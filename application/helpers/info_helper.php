<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	
	function getChnlImg($uid,$img,$type='.webp'){
		return AMAZON_URL .'aud_'.$uid.'/images/'.$img.$type;
	}

	function getChnlthmb($uid,$img){
		$img = explode('.',$img);
		
		return isset($img[1]) ? AMAZON_URL .'aud_'.$uid.'/images/'.$img[0].'_thumb.'.$img[1] .'.webp' : thumb_default_image() ;
	}

	function thumb_default_image(){  
		return CDN_BASE_URL . 'repo/images/thumbnail.jpg';
	}
	
	function slugify($text){
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);// replace non letter or digits by -
	  $text = preg_replace('~[^-\w]+~', '', $text); // remove unwanted characters
	  $text = trim($text, '-'); // trim
	  $text = preg_replace('~-+~', '-', $text);// remove duplicate -
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);// transliterate
	  $text = strtolower($text); // lowercase
	//   $text = trim(preg_replace('/ +/', '-', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($text))))));

	  if (empty($text)) {
		return 'n-a';
	  }
	  return $text;
	}

	function checkForeignChar($text){
		$text = remove_special_char($text);
		$text = garbagereplace($text);
		return htmlToPlainText($text);
	}

	function remove_special_char($text) {
		// Replace en dash with a regular dash (optional)
		$text = str_replace("–", "-", $text);
		
		// Remove HTML tags
		$text = strip_tags($text);
		
		// Replace unwanted special characters using preg_replace
		// This regex keeps only alphanumeric characters, spaces, line breaks, and selected special characters, including apostrophes, quotes, and periods
		return preg_replace('/[^A-Za-z0-9 !@#$_%^&*()\-\'":\/\n\.]/u', '', $text);
	}
	

	function garbagereplace($string){
		$garbagearray = array('&nbsp;', '&amp;', '$', '%', '^', '*', ';', '""', '–');
		$string = str_replace($garbagearray, '', $string);
		return $string;
	}

	function htmlToPlainText($str) {
		$str = str_replace('&nbsp;', ' ', $str);
		$str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$str = strip_tags($str);
		return $str;
	}

	function clear_cache(){
		$CI =& get_instance();
        $CI->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $CI->output->set_header("Pragma: no-cache");
    }
	function validate_input($data){
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	function user_default_image(){
		return CDN_BASE_URL . 'repo/images/user/user.png';
	}
	
	function get_user_image($uid){
		$CI =& get_instance();
		$CI->load->model('DatabaseModel');
		$uc_pic = $CI->DatabaseModel->select_data('uc_pic','users_content use INDEX(uc_userid)',array('uc_userid'=>$uid),1);
		if(isset($uc_pic[0]['uc_pic']) && !empty($uc_pic[0]['uc_pic'])){
			$uc_pic = explode('.',$uc_pic[0]['uc_pic']);
			$uc_pic = trim($uc_pic[0].'_thumb.'.$uc_pic[1]);
			$img = AMAZON_URL.'aud_'.$uid.'/images/'.$uc_pic.'?q='.date('his');
		}else{
			$img = user_default_image();
		}
		return $img;
    } 
	function get_user_fullname($uid,$coloum=null){
		$CI =& get_instance();
		$CI->load->model('DatabaseModel');
		
		$user = $CI->DatabaseModel->select_data('user_uname,user_name','users use INDEX(user_id)',array('user_id'=>$uid),1);
		if($coloum == 'user_uname' ){
			if(isset($user[0]['user_uname'])){
				return $user[0]['user_uname'];	
			}
		}else{
			if(isset($user[0]['user_name'])){
				return $user[0]['user_name'];	
			}
		}
	}
	function get_user($uid){
		$CI =& get_instance();
		$CI->load->model('DatabaseModel');
		$join = array('multiple' , array(
									array(	'users_content', 
											'users_content.uc_userid= users.user_id ',
											'left'),
									));
		$cond = array('user_id'=>$uid);
		return $CI->DatabaseModel->select_data('users.user_name,users.user_uname,users_content.uc_pic,HDYDU','users use INDEX(user_id)',$cond,1,$join);
	}
	function create_upic($uid ='',$uc_pic=''){
		if(!empty($uc_pic)){
			$uc_pic = explode('.',$uc_pic);
			$uc_pic = trim($uc_pic[0].'_thumb.'.$uc_pic[1]);
			$img = AMAZON_URL.'aud_'.$uid.'/images/'.$uc_pic.'?q='.$uc_pic;
		}else{
			$img =CDN_BASE_URL . 'repo/images/user/user.png';
		}
		return $img;
    }
	function user_abs_path($uid,$fd="images"){
		return ABS_PATH .'uploads/aud_'.$uid.'/'.$fd.'/';
	}

	function is_session_uid($uid){
		$login_uid = is_login();
		if($login_uid == $uid){
			return true;
		}
		return false;
	}
	
	function is_login(){
		$login_uid='';
		$CI =& get_instance();
		if(isset($CI->session->userdata['user_login_id'])){
			$login_uid =$CI->session->userdata['user_login_id'];
		}
		return $login_uid; 
	}


	function is_admin(){
		
		if(isset($_SESSION['admin']) && $_SESSION['admin']==1){
			return $_SESSION['admin'];
		}else{
			return false;
		}
	}
	
	function mode($type='id'){ /*$type = id,name | current mode */
		return $_SESSION['website_mode'][$type];
	}
	
	function time_elapsed_string($timestamp,$full = false){
  
	  $CI =& get_instance();  
	  $time_ago        = strtotime($timestamp);
	  $current_time    = strtotime($CI->common->manageTimezone(date('Y-m-d H:i:s')));
	  $time_difference = $current_time - $time_ago;
	  $seconds         = $time_difference;
	  
	  $minutes = round($seconds / 60); // value 60 is seconds  
	  $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
	  $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
	  $weeks   = round($seconds / 604800); // 7*24*60*60;  
	  $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
	  $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
					
		if ($seconds <= 60){
			return "Just Now";
		}else 
		if ($minutes <= 60){
			if ($minutes == 1){
				return "one min ago";
			}else{
				return "$minutes min ago";
			}
		} else if ($hours <= 24){
			if ($hours == 1){
				return "an hour ago";
			} else {
			return "$hours hrs ago";
			}
		} else if ($days <= 7){
			if ($days == 1){
				return "yesterday";
			} else {
				return "$days days ago";
			}
		} else if ($weeks <= 4){
			if ($weeks == 1){
				return "a week ago";
			} else {
				return "$weeks weeks ago";
			}
		} else if ($months <= 12){
			if ($months == 1){
				return "a month ago";
			} else {
				return "$months months ago";
			}
		} else {
			if ($years == 1){
				return "one year ago";
			} else {
				return "$years years ago";
			}
		}
	}
	
	function is_mobile_device(){
		$useragent	=	isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '' ;

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		return true;
	}
	// Converts a number into a short version, eg: 1000 -> 1k
	// Based on: http://stackoverflow.com/a/4371114
	function number_format_short( $n, $precision = 1 ) {
		if ($n < 900) {
			// 0 - 900
			$n_format = number_format($n, $precision);
			$suffix = '';
		} else if ($n < 900000) {
			// 0.9k-850k
			$n_format = number_format($n / 1000, $precision);
			$suffix = 'K';
		} else if ($n < 900000000) {
			// 0.9m-850m
			$n_format = number_format($n / 1000000, $precision);
			$suffix = 'M';
		} else if ($n < 900000000000) {
			// 0.9b-850b
			$n_format = number_format($n / 1000000000, $precision);
			$suffix = 'B';
		} else {
			// 0.9t+
			$n_format = number_format($n / 1000000000000, $precision);
			$suffix = 'T';
		}

	  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
	  // Intentionally does not affect partials, eg "1.50" -> "1.50"
		if ( $precision > 0 ) {
			$dotzero = '.' . str_repeat( '0', $precision );
			$n_format = str_replace( $dotzero, '', $n_format );
		}

		return $n_format . $suffix;
	}

	
	function gen_uuid()
	{
		if (function_exists('com_create_guid') === true)
			return trim(com_create_guid(), '{}');

		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
	
	function get_store_status(){
		$CI =& get_instance();
		$CI->load->model('DatabaseModel');
		$ustore = $CI->DatabaseModel->select_data('store_status,store_vendor_id','users_store',['store_user_id'=>is_login()],1); 
		$store=[];
		if(isset($ustore[0]['store_status'])){
			$store = ['store_vendor_id'=>$ustore[0]['store_vendor_id'],'store_status'=>$ustore[0]['store_status']];
		}
		return $store;
	}
	
	function showRatingStar($average_rating = 0){
		$average_rating = (int)$average_rating;
		$ratingStar ='<ul class="dis_pro_rating">';		
		for($i=0; $i<$average_rating; $i++){
			$ratingStar .='<li><a href="javascript:;">
							<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="12" height="12" viewBox="0 0 511.99143 511"><g><path xmlns="http://www.w3.org/2000/svg" d="m510.652344 185.882812c-3.371094-10.367187-12.566406-17.707031-23.402344-18.6875l-147.796875-13.417968-58.410156-136.75c-4.3125-10.046875-14.125-16.53125-25.046875-16.53125s-20.738282 6.484375-25.023438 16.53125l-58.410156 136.75-147.820312 13.417968c-10.835938 1-20.011719 8.339844-23.402344 18.6875-3.371094 10.367188-.257813 21.738282 7.9375 28.925782l111.722656 97.964844-32.941406 145.085937c-2.410156 10.667969 1.730468 21.699219 10.582031 28.097656 4.757813 3.457031 10.347656 5.183594 15.957031 5.183594 4.820313 0 9.644532-1.28125 13.953125-3.859375l127.445313-76.203125 127.421875 76.203125c9.347656 5.585938 21.101562 5.074219 29.933593-1.324219 8.851563-6.398437 12.992188-17.429687 10.582032-28.097656l-32.941406-145.085937 111.722656-97.964844c8.191406-7.1875 11.308594-18.535156 7.9375-28.925782zm-252.203125 223.722657" fill="rgb(238 180 6)" data-original="rgb(238 180 6)"></path></g></svg>
							</a></li>';
		}
        for($j=0; $j<(5-$average_rating); $j++){
			$ratingStar .='<li><a href="javascript:;">
						<svg xmlns="http://www.w3.org/2000/svg" width="12.81" height="12" viewBox="0 0 12.81 12">
							<path fill="#6a767d" fill-rule="evenodd" d="M1292.55,363.683c0.12-.7.24-1.42,0.37-2.137q0.135-.776.27-1.549a0.341,0.341,0,0,0-.12-0.351c-0.87-.825-1.74-1.657-2.6-2.485a0.436,0.436,0,0,1-.21-0.49,0.514,0.514,0,0,1,.5-0.286c1.18-.168,2.36-0.34,3.54-0.5a0.366,0.366,0,0,0,.33-0.231c0.53-1.064,1.07-2.123,1.59-3.186a0.485,0.485,0,0,1,.46-0.343,0.48,0.48,0,0,1,.43.341c0.53,1.069,1.07,2.136,1.6,3.206a0.331,0.331,0,0,0,.29.207c1.17,0.16,2.33.331,3.5,0.5,0.09,0.013.18,0.023,0.27,0.044a0.357,0.357,0,0,1,.21.618c-0.09.1-.19,0.194-0.29,0.289-0.81.773-1.61,1.549-2.42,2.316a0.368,0.368,0,0,0-.13.374l0.63,3.563a0.408,0.408,0,0,1-.14.468,0.466,0.466,0,0,1-.53-0.019c-1.08-.558-2.16-1.111-3.23-1.673a0.386,0.386,0,0,0-.41,0q-1.62.853-3.27,1.691A0.4,0.4,0,0,1,1292.55,363.683Z" transform="translate(-1290.25 -352.125)"></path>
						</svg></a></li>';
        }
		$ratingStar .='</ul>';
		return $ratingStar;
    }
	function discountPercentage($reg_price = 0, $sale_price = 0)
	{	
		if($sale_price == 0){
			$sale_price = $reg_price;
		}
		$dis_percentage = ($reg_price - $sale_price) / $reg_price * 100;
		return round($dis_percentage);
	}
	
	function getBlogCategoryById($id,$type){
		$CI =& get_instance();
		$CI->load->model('DatabaseModel');
		
		if ($type == 'by_article_id') {
			$cond = array('article_id'=>$id);
		}else if($type == 'by_category_id'){
			$cond = array('ar_category_id'=>$id);
		}

		$category = $CI->DatabaseModel->select_data('ar_category_id','articles',$cond);
		$ret = '';
		if (count($category) > 0){
			$ret = $category[0]['ar_category_id'];
		}
		return $ret;
    } 

	function get_domain_only($url) {
		$url=str_replace("www.","",$url);
		$url=str_replace("WWW.","",$url);
		
	    if (!preg_match("@^https?://@i", $url) && !preg_match("@^ftps?://@i", $url)) {
	        $url = "http://" . $url;
	    }
		
		
	  	$parsed=parse_url($url);
		
		return $parsed['host'];
	}

	function estimateReadingTime($text, $img = 0, $wpm = 200) {
		$totalWords = str_word_count(strip_tags($text));
		$minutes = floor(($totalWords / $wpm) + $img);
		$seconds = floor($totalWords % $wpm / ($wpm / 60));
		$total_seconds = ($minutes * 60) + $seconds;		
		return array(
			'minutes' => $minutes,
			'seconds' => $seconds,
			'total_seconds' => $total_seconds
		);
	}

	function converttoUnit($size,$unit) 
	{
		if($unit == "KB")
		{
			return $fileSize = round($size / 1024,2) . 'KB';	
		}
		if($unit == "MB")
		{
			return $fileSize = round($size / 1024 / 1024,2) . 'MB';	
		}
		if($unit == "GB")
		{
			return $fileSize = round($size / 1024 / 1024 / 1024, 4); // . 'GB';	
		}
	}


	function filesize_formatted($size)
	{
		//$size = filesize($path);
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}


	function convertToReadableSize($size){
		$base = log($size) / log(1024);
		$suffix = array("", "KB", "MB", "GB", "TB");
		$f_base = 3; //floor($base);
		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
	}

	function get_mb($size) {
		return sprintf("%4.2f MB", $size/1048576);
	}
	
	function gbToBytes($gb=0){
		return ($gb*pow(1024,3));
	}

	function formatSizeUnits($bytes=0)
	{
		if ($bytes >= 1073741824)
		{
			$bytes = number_format($bytes / 1073741824, 1) . ' GB';
		}
		elseif ($bytes >= 1048576)
		{
			$bytes = number_format($bytes / 1048576, 1) . ' MB';
		}
		elseif ($bytes >= 1024)
		{
			$bytes = number_format($bytes / 1024, 1) . ' KB';
		}
		elseif ($bytes > 1)
		{
			$bytes = $bytes . ' bytes';
		}
		elseif ($bytes == 1)
		{
			$bytes = $bytes . ' byte';
		}
		else
		{
			$bytes = '0 bytes';
		}

		return $bytes;
  	}


	function is_disposable_email($email) {
		$disposable_domains = [
			'mailinator.com', 'guerrillamail.com', 'tempmail.org', 'throwawaymail.com', 'maildrop.cc',
			'fakemailgenerator.com', 'yopmail.com', 'getnada.com', 'mailnesia.com', 'inboxes.com',
			'dispostable.com', 'mytrashmail.com', 'meltmail.com', 'trunkmail.com', 'binkmail.com',
			'tempinbox.com', '10minutemail.com', 'spamgourmet.com', 'fakeinbox.com', 'moakt.com',
			'puedemail.com', 'quieresmail.com', 'sabesmail.com', 'estabamail.com', 'gmailgmx.com.de',
			'web.de','gmx.de','soscandia.org','rambler.ru'
		];
		
		// Extract domain from email
		$domain = substr(strrchr($email, "@"), 1);
		
		// Check if the domain is in the list of disposable domains
		if (in_array($domain, $disposable_domains)) {
			//die("This email address looks fake or invalid, please enter a real email address.");
			return true;
		}else{
			return false;
		} 
	}



	function is_ip_existold(){
		$CI =& get_instance();
		$ip = $CI->common->get_client_ip();
		$cond = "user_location LIKE '%".$ip."%'";
		$result = $CI->DatabaseModel->select_data('user_id','users',$cond);
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}

	function is_ip_exist(){
		$CI =& get_instance();
		$ip = $CI->common->get_client_ip();
		$cond = "user_location LIKE '%".$search."%'";
		$result = $CI->DatabaseModel->select_data('user_id,user_regdate','users',$cond,1,'',array('user_regdate','DESC'));
	  
		$regTime = $result[0]['user_regdate'];
		$currentTime  = $CI->common->manageTimezone(date('Y-m-d H:i:s'));
		if(!empty($result) && !empty($result[0])){
			$timeDiffrence =  strtotime($currentTime)  - strtotime($regTime);
			if($timeDiffrence < 600 ){ // 10 min
				return true;
			}
		}else{
		  	return false;
		}
	}