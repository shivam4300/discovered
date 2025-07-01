<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH .'third_party/webp/vendor/autoload.php';
use WebPConvert\WebPConvert;

class Convert_image_webp{
	public $CI;
	public function __construct(){
		$this->CI = get_instance();
	}
	
	function convertIntoWebp($source){
		
		$destination = $source . '.webp';
		$options = [];
		WebPConvert::convert($source, $destination, $options);
	}
	
	


	
	
}
?>