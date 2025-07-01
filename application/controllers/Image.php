<?php

/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Controller {

	var $image;
	var $image_type;
	function index(){
		$this->CreateImage();
	}
	
	
	
	function CreateImage(){
		//works with both POST and GET
		
		 $pathToImages = ABS_PATH .'uploads/aud_6/images/'.$_GET['file'];	
		$this->imageResizer($pathToImages, $_GET['width'], $_GET['height']);
	   
	  
	}	
	function imageResizer($url, $width, $height) {
		$url = $this->resize_image($url,  $width,  $height);
		ob_start();
		imagejpeg($url);
		$output = base64_encode(ob_get_contents());
		ob_end_clean();
		// return "data:image/jpeg;base64,{$output}";

		echo '<img src="data:image/jpeg;base64,'.$output.'"/>';
	}
	function resize_image($file, $w, $h, $crop=FALSE) {
		list($width, $height) = getimagesize($file);
		$r = $width / $height;
		if ($crop) {
			if ($width > $height) {
				$width = ceil($width-($width*abs($r-$w/$h)));
			} else {
				$height = ceil($height-($height*abs($r-$w/$h)));
			}
			$newwidth = $w;
			$newheight = $h;
		} else {
			if ($w/$h > $r) {
				$newwidth = $h*$r;
				$newheight = $h;
			} else {
				$newheight = $w/$r;
				$newwidth = $w;
			}
		}
		$src = imagecreatefromjpeg($file);
		$dst = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		return $dst;
	}
	function CreateImages(){
	  
	   $this->load($_GET['file']);
	   $this->resize($_GET['width'], $_GET['height']);
	   $this->output();
	}	
	function load($filename) {
	$pathToImages = ABS_PATH .'uploads/aud_6/images/'.$_GET['file'];	
	  $image_info = getimagesize($pathToImages);
	  // print_r(  $image_info);die;
	  $this->image_type = $image_info[2];
	  if( $this->image_type == IMAGETYPE_JPEG ) {

		 $this->image = imagecreatefromjpeg($filename);
	  } elseif( $this->image_type == IMAGETYPE_GIF ) {

		 $this->image = imagecreatefromgif($filename);
	  } elseif( $this->image_type == IMAGETYPE_PNG ) {

		 $this->image = imagecreatefrompng($filename);
	  }
	}
	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {

	  if( $image_type == IMAGETYPE_JPEG ) {
		 imagejpeg($this->image,$filename,$compression);
	  } elseif( $image_type == IMAGETYPE_GIF ) {

		 imagegif($this->image,$filename);
	  } elseif( $image_type == IMAGETYPE_PNG ) {

		 imagepng($this->image,$filename);
	  }
	  if( $permissions != null) {

		 chmod($filename,$permissions);
	  }
	}
	function output($image_type=IMAGETYPE_JPEG) {

	  if( $image_type == IMAGETYPE_JPEG ) {
		 imagejpeg($this->image);
	  } elseif( $image_type == IMAGETYPE_GIF ) {

		 imagegif($this->image);
	  } elseif( $image_type == IMAGETYPE_PNG ) {

		 imagepng($this->image);
	  }
	}
	function getWidth() {

	  return imagesx($this->image);
	}
	function getHeight() {

	  return imagesy($this->image);
	}
	function resizeToHeight($height) {

	  $ratio = $height / $this->getHeight();
	  $width = $this->getWidth() * $ratio;
	  $this->resize($width,$height);
	}

	function resizeToWidth($width) {
	  $ratio = $width / $this->getWidth();
	  $height = $this->getheight() * $ratio;
	  $this->resize($width,$height);
	}

	function scale($scale) {
	  $width = $this->getWidth() * $scale/100;
	  $height = $this->getheight() * $scale/100;
	  $this->resize($width,$height);
	}

	function resize($width,$height) {
	  $new_image = imagecreatetruecolor($width, $height);
	  imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
	  $this->image = $new_image;
	}    
	   

}
?>