 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temp extends CI_Controller {
	
	
	function uploadFile(){
		$uid = 215;
		$pathToImage = user_abs_path($uid);
		$name = '';
		// print_r($_FILES);die;
		$u = $this->audition_functions->upload_file($pathToImage,'jpg|png|jpeg','userfile',true);
		// print_r($u);die;
		if($u != 0 ){
			$name 	= $u['file_name'];
			$path 	= $pathToImage.$name ; 
			$r = $this->audition_functions->resizeImage('1080','608',$path,'',false,false);
			if($r != 0 ){
				$this->load->library('convert_image_webp');
				
				if(file_exists($path))
				$this->convert_image_webp->convertIntoWebp($path);
				
				$r=$this->audition_functions->resizeImage('315','217',$pathToImage.$name,'',false,TRUE);	
				if($r != 0 ){
					$img = explode('.',$name);
					$path =	$pathToImage.$img[0].'_thumb.'.$img[1];	
					
					if(file_exists($path))
					$this->convert_image_webp->convertIntoWebp($path);
				}
			}
		}
	}
	
	public function index(){
		$url = 'https://s3-us-west-1.amazonaws.com/discovered.tv/Discovered_Promotional_Video.mp4';
		$data['cover_video'] = array('url'=>$url,
									 'post_id'=>'',
									 'title'=>"Discover Everything !");
		$this->load->view('home/inc/header',$data);
		$this->load->view('coming_soon',$data);	
		$this->load->view('home/inc/footer',$data);	   
	}

	function saveEmails(){
		if( $_SERVER['HTTP_REFERER'] == base_url('temp') ){
			if(isset($_POST['em'])){
				$email = $_POST['em'];
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$d = array(
						'Aweber_consumer_key'	=>	'AzNEZX3yI5zehkyDJl0Neb3h',
						'Aweber_consumer_secret'	=>	'oY6oxGSocyUnTQwyjX9STZfO6496D601JoVjKszA',
						'Aweber_access_key'	=>	'AgSxPaml9MbvVUDLvr2BW99V',
						'Aweber_access_secret'	=>	'ay8Z7VUUSt5Go9kd8fQCeWXYmmyB96YdMGlCSvLT'
					);
					require_once 'aweber/Aweber/aweber_api.php';
					$aweber = new AWeberAPI($d['Aweber_consumer_key'], $d['Aweber_consumer_secret']);
					$account = $aweber->getAccount($d['Aweber_access_key'], $d['Aweber_access_secret']);
					$list_name = 'Discovered LIST';
					$foundLists = $account->lists->find( array( 'name' => $list_name ) );
					$list = $foundLists[0];
					$subscribers = $list->subscribers;

					$descr = '';
					try
					{
						$params = array(
								'email' => $email
						);
						$new_subscriber = $subscribers->create( $params );
						echo '1';
					}
					catch (AWeberAPIException $exc)
					{
						echo '0';
					}
					die(); 
				}
			}
		}
		die('Get out');
	}
	public function send_sms(){
		$this->load->helper('aws_sms_action');
		$arr = [
			'message' => $this->input->post('message'),
			'phone' => $this->input->post('phone')
		];
		
		$r = send_sms($arr);
		echo $r;
		
	}
}