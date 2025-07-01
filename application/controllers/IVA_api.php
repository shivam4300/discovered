<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IVA_api extends CI_Controller {
	public 	$uid ;
	public 	$AVI_Subscription_Key = '263a83e3b6f943a4ba5437a69b6639f2';
	public function __construct()
	{
		parent::__construct();
		if(isset($_POST) && !empty($_POST)) {
	        if(!isset($_SERVER['HTTP_REFERER'])) {
                die('Direct Access Not Allowed!!');
	        }
	    }
		$this->load->library(array('manage_session')); 
		$this->uid = is_login();
	}
	
	function search_autocomplete(){
		$resp = array();
		if(isset($_GET['q']) && isset($_GET['t'])){
			
			
			if($_GET['t'] == 'Celebrity'){
				$targetURL = 'https://ee.iva-api.com/api/Celebrity/RankedSearch';
								
				$celebrityData = $this->access_data_by_curl('GET' ,
															array(
																'StartsWith' => $_GET['q'],
																'subscription-key' => $this->AVI_Subscription_Key
															), 
															$targetURL);	
				foreach($celebrityData as $celebrityD){
					array_push($resp , array('label' => $celebrityD['Name'] , 'value' => $celebrityD['Id']));
				}
			}elseif($_GET['t'] == 'Movie'){
				
				$targetURL = 'https://ee.iva-api.com/api/entertainment/search';
				$movieData = $this->access_data_by_curl('GET' ,
															array(
																'ProgramTypes' => 'Movie',
																'HasVideo' => true,
																'SortBy' => 'ReleaseDate',
																'Title' => $_GET['q'],
																'subscription-key' => $this->AVI_Subscription_Key
															), 
															$targetURL);	
													
				if(isset($movieData['Hits'])){
					foreach($movieData['Hits'] as $hitsData){
						array_push($resp , array('label' => $hitsData['Source']['Title'] , 'value' => $hitsData['Source']['Id']));
					}
				}
																			
			}elseif($_GET['t'] == 'Show'){
			
				$targetURL = 'https://ee.iva-api.com/api/entertainment/search';
				$showData = $this->access_data_by_curl('GET' ,
															array(
																'ProgramTypes' => 'Show',
																'HasVideo' => true,
																'SortBy' => 'ReleaseDate',
																'Title' => $_GET['q'],
																'subscription-key' => $this->AVI_Subscription_Key
																), 
															$targetURL);
															
				if(isset($showData['Hits'])){
					foreach($showData['Hits'] as $hitsData){
						array_push($resp , array('label' => $hitsData['Source']['Title'] , 'value' => $hitsData['Source']['Id']));
					}
				}
			}
			
		}
		
		echo $_GET['callback'].'('.json_encode($resp).');';
		
	}

	
	
	function search_video_data(){
		if(isset($_POST['id']) && isset($_POST['type'])){
			$htmlData = ''; 
			if($_POST['type'] == 'Celebrity'){
				
				$getMyCelebritiesVideoAPIUrl = 'https://ee.iva-api.com/api/entertainment/search';
				$getMyCelebritiesVideoAPIData = $this->access_data_by_curl(
																	'GET' , 
																	array(
																		'PeopleIds' => $_POST['id'],
																		'Skip' => 0,
																		'HasVideo' => true,
																		//'Take' => 10,
																		'ProgramTypes' => 'Movie',
																		'Includes' => 'Genres,Descriptions,Tags,Videos,IvaRating',
																		'subscription-key' => $this->AVI_Subscription_Key
																	) , 
																	$getMyCelebritiesVideoAPIUrl);
																	
				$htmlData = $this->show_search_content_via_api_data($getMyCelebritiesVideoAPIData);
				
			}elseif($_POST['type'] == 'Movie'){
				
				$getMyMovieAPIUrl = 'https://ee.iva-api.com/api/entertainment/search';
				$getMyMovieAAPIData = $this->access_data_by_curl('GET' , array(
																			'Ids' => $_POST['id'],
																			'HasVideo' => true,
																			'Take' => 1,
																			'Includes' => 'Genres,Descriptions,Tags,Videos,IvaRating',
																			'subscription-key' => $this->AVI_Subscription_Key
																			) , $getMyMovieAPIUrl);
																			
				$htmlData = $this->show_search_content_via_api_data($getMyMovieAAPIData);
				
			}elseif($_POST['type'] == 'Show'){				
				$getMyShowAPIUrl = 'https://ee.iva-api.com/api/entertainment/search';
				$getMyShowAAPIData = $this->access_data_by_curl('GET' , array(
																			'Ids' => $_POST['id'],
																			'HasVideo' => true,
																			'Take' => 1,
																			'Includes' => 'Genres,Descriptions,Tags,Videos,IvaRating',
																			'subscription-key' => $this->AVI_Subscription_Key
																			) , $getMyShowAPIUrl);
																			
				$htmlData = $this->show_search_content_via_api_data($getMyShowAAPIData);
			}
			
			$resp = array('status' => 1 , 'data' => ($htmlData != '')?$htmlData:'There are no any content available.');
			
			
		}else{
			$resp = array('status' => 0);
		}
		echo json_encode($resp);
		
	}
	
	
	function show_search_content_via_api_data($myApiData){
		//print_r($myApiData);
		
		$htmlData = '';
		
		
		if(isset($myApiData['Hits'])){
			$countD = 1;
			foreach($myApiData['Hits'] as $hitsData){
				
				$videoDataSource = $hitsData['Source']; 
				
				
				$videoDesc = '';
				if(isset($videoDataSource['Descriptions'])){
					$videoDesc = $videoDataSource['Descriptions'][0]['Description'];
				}
				
				$videoShortDesc = (strlen($videoDesc) > 100)?substr($videoDesc, 0, 100).'...':$videoDesc;
				$videoLongDesc = $videoDesc;
				
				
				$myVideoScreenCaptureUrl = '';
			
				if(isset($videoDataSource['Videos']) && !empty($videoDataSource['Videos'])){
					foreach($videoDataSource['Videos'] as $videoD){ //loop of video trailler array
						if(isset($videoD['Language']) && $videoD['Language'] == 'English'){ //get only English language trailer
							foreach($videoD['ScreenCaptures'] as $videoScreenCap){ //loop of screencapture array
								$myVideoScreenCaptureUrl = $this->get_screen_capure_redirect_url($videoScreenCap['FilePath']);
								if($myVideoScreenCaptureUrl != ''){ break; }
							}
						}
						if($myVideoScreenCaptureUrl != ''){ break; }
					}
					
					if($myVideoScreenCaptureUrl == '' && isset($videoDataSource['Videos'][0]) && isset($videoDataSource['Videos'][0]['ScreenCaptures'])){
						$myVideoScreenCaptureUrl = $this->get_screen_capure_redirect_url($videoDataSource['Videos'][0]['ScreenCaptures'][0]['FilePath']);
					}
				}else{
					
				}
				
				$myVideoScreenCaptureUrl = ($myVideoScreenCaptureUrl == '')?base_url('repo/images/thumbnail.jpg'):$myVideoScreenCaptureUrl;
				$videoDataSource['screen_capture_url'] =  $myVideoScreenCaptureUrl;
				
				$htmlData .= '<div class="panel panel-default">
							  <div class="panel_topbox">
								<div class="dis_checkbox checkboxenqry_checkbox">
									<label>
										<input type="checkbox" value="'.$videoDataSource['Id'].'" class="validate selectedVideo">
										<i class="input-helper"></i>
										<div class="hide myVideoData">'.json_encode($videoDataSource).'</div>
									</label>
								</div>
								<div class="enqry_thumb">
									<a>
										<img src="'.$myVideoScreenCaptureUrl.'" alt="" class="image-fluid">
									</a>
								</div>
								<div class="enqry_headinfo">
									<a href="" class="e_movie_title">'.$videoDataSource['Title'].'</a>
									<div class="enqry_rating">
										<i class="fa fa-star" aria-hidden="true"></i>
										<span class="">IVA Rating: '.$videoDataSource['IvaRating'].'</span>
									</div>
									<p>'.$videoShortDesc.'</p>
								</div>
								<div class="enqry_dropbtn '.(($videoShortDesc != '')?'':'hide').'">
								  <a data-toggle="collapse" data-parent="#accordion" href="#collapse_'.$countD.'">
									<i class="fa fa-angle-down " aria-hidden="true"></i>
								  </a>
								</div>
							  </div>
							  
							  <div id="collapse_'.$countD++.'" class="panel-collapse collapse">
								<div class="panel-body">
								<p>'.nl2br($videoLongDesc).'</p>
								</div>
							  </div>
							</div>';
			}
		}
		return $htmlData;
	}
	
	function upload_my_video(){
		if(isset($_POST['selectedVideo']) && isset($_POST['selectedVideoContent']) && !empty($_POST['selectedVideoContent'])){
			$resp = $this->add_iva_video_to_dtv($_POST['selectedVideoContent']);
			
			$resp = array('status' => 1);
		}else{
			$resp = array('status' => 0);
		}
		echo json_encode($resp);
	}
	
	function add_iva_video_to_dtv($myVideoData){
		$userId = $this->uid;
		foreach($myVideoData as $vData){
			$videoData = json_decode($vData , true);
			
			$dataMode = (isset($videoData['Type']) && $videoData['Type'] == 'Movie')?2:3;
			
			if(isset($videoData['Videos'])){ //check video exist in data, that mean this this code is only for video 
				
				/*******************************************/
				/* manage Video screen Captures data start */
				/*******************************************/
				
				$targetVideoId  = 0;
				$myVideoScreenCaptureUrl = $videoData['screen_capture_url'];
				
				if(isset($videoData['Videos']) && !empty($videoData['Videos'])){
					foreach($videoData['Videos'] as $videoD){ //loop of video trailler array
						if(isset($videoD['Language']) && $videoD['Language'] == 'English'){ //get only English language trailer
							$targetVideoId  = $videoD['Id'];
							/*foreach($videoD['ScreenCaptures'] as $videoScreenCap){ //loop of screencapture array
								$myVideoScreenCaptureUrl = $this->get_screen_capure_redirect_url($videoScreenCap['FilePath']);
								
								if($myVideoScreenCaptureUrl != ''){ break; }
							}*/
						}
						//if($myVideoScreenCaptureUrl != ''){ break; }
					}
					
					if($targetVideoId == 0 && isset($videoData['Videos'][0]['Id'])){
						$targetVideoId  = $videoData['Videos'][0]['Id'];
					}
				}
				
				
				$getMyVideoAPIData = $this->get_video_trailer_url($targetVideoId);
				
				if(!isset($getMyVideoAPIData['statusCode'])){ //statusCode get in case of error in API
					
					$myVideoTrailerUrl = (isset($getMyVideoAPIData[0]['Url']))?$getMyVideoAPIData[0]['Url']:$getMyVideoAPIData['Url'];
					
					/*********************************/
					/* manage Video genre data start */
					/*********************************/

					$genresListArray = (isset($videoData['Genres']))?$videoData['Genres']:array();
					
					foreach ($genresListArray as $key=>$value) {
						$genresListArray[$key] = str_replace("'","\'",trim(ucfirst($value)));
					}
					$genresList = implode("','" , $genresListArray);
					
					
					// print_r($genresList );die;
					$genresCond = 'mode_id = '.$dataMode;
					
					if($genresList != ''){
						$genresCond .=  ' AND genre_name IN ('."'".$genresList."'".')';	
					}
					
					
					
					$genresId = 0;
					
					
					$checkMyGenreData = $this->DatabaseModel->select_data('genre_id','mode_of_genre',$genresCond , 1);
					if(empty($checkMyGenreData) && isset($genresListArray[0])){
						$this->load->helper('info_helper');
						$slug = slugify($genresListArray[0]);
						$genresId = $this->DatabaseModel->insert_data('mode_of_genre', array(
																							'mode_id' => $dataMode,
																							'genre_name' => ucfirst($genresListArray[0]),
																							'genre_slug' => $slug,
																							'status' => 1
																							));	
					}else{
						$genresId = $checkMyGenreData[0]['genre_id'];
					}
					
					
					
					
					
					/************************************/
					/* manage Video language data start */
					/************************************/
					
					
					$videoLangId = 0;
					
					
					$getLanhuageCode  = $this->DatabaseModel->select_data('id','language_list', array('value' => $videoData['OriginalLanguage']) , 1);
					
					if(!empty($getLanhuageCode)){
						$videoLangId = $getLanhuageCode[0]['id'];
					}
					
					
					
					$moviesInsData = array(
										'iva_id' =>$videoData['Id'],
										'uploaded_video' =>$myVideoTrailerUrl,
										'user_id' => $userId,
										'mode' => $dataMode,
										'genre' => $genresId,
										'language' => $videoLangId,
										'title' => $videoData['Title'],
										'description' => (isset($videoData['Descriptions']['0']['Description']))?$videoData['Descriptions']['0']['Description']:'',
										'tag' => (isset($videoData['Tags']))?implode("," , $videoData['Tags']):'',
										'privacy_status' => 7,
										'active_status' => 1,
										'complete_status' => 1,
									);
					
					$insVideoId = 0;
					$checkVideoExist = array();
					
					//manage video data
					$checkVideoExist = $this->DatabaseModel->select_data('post_id' , 'channel_post_video use INDEX(iva_id)' , array('iva_id' => $videoData['Id'] , 'user_id' =>  $userId) , 1);
					
					if(empty($checkVideoExist)){
						$insVideoId = $this->DatabaseModel->insert_data('channel_post_video', $moviesInsData);			
						

						$this->load->library('share_url_encryption');
						$check = $this->share_url_encryption->share_single_page_link_creator('2|'.$insVideoId,'encode','id');
						$this->DatabaseModel->update_data_limit('channel_post_video' , array('post_key'=>$check[0]) , array('post_id'=>$insVideoId) , 1);	
					}else{
						$insVideoId = $checkVideoExist[0]['post_id'];
						$this->DatabaseModel->update_data_limit('channel_post_video' , $moviesInsData , array('post_id'=>$insVideoId) , 1);
					}
					
					
					$arrContextOptions=array(
						"ssl"=>array(
							"verify_peer"=>false,
							"verify_peer_name"=>true,
						),
					);  
					
					
					$name = rand().'.jpeg';
					// $filePath = ABS_PATH.'uploads/aud_215/images/'.$name.'.jpeg'; 
					$pathToImages = ABS_PATH .'uploads/aud_'.$userId.'/images/'.$name ;
					// $response = file_get_contents($myVideoScreenCaptureUrl, true, stream_context_create($arrContextOptions));
					// if(file_put_contents($pathToImages, $response)){
						// $myVideoScreenCaptureUrl  = $name;
					// };
					$myVideoScreenCaptureUrl = str_replace("&amp;","&",$myVideoScreenCaptureUrl);
					if(file_put_contents($pathToImages, file_get_contents($myVideoScreenCaptureUrl))){
						 $myVideoScreenCaptureUrl  = $name;
						 $this->load->library('audition_functions');
						 $this->audition_functions->resizeImage('212','157',$pathToImages,'',$maintain_ratio = false,$create_thumb= TRUE);
					}
					
					// print_r($myVideoScreenCaptureUrl);
					
					// print_r($pathToImages);die;
					$imgDetail = array(
										'post_id' => $insVideoId, 
										'user_id' => $userId, 
										'image_name' => $myVideoScreenCaptureUrl, 
										'active_thumb' => 1
										);
					
					$insDataId = 0;
					if(!empty($checkVideoExist)){
						$insDataId = $this->DatabaseModel->update_data_limit('channel_post_thumb', $imgDetail , array('post_id' => $insVideoId) , 1);		
					}else{
						$insDataId = $this->DatabaseModel->insert_data('channel_post_thumb', $imgDetail);		
					}
					
				}
			}
		}
		
	}
	
	function get_screen_capure_redirect_url($filePath){

		//this is row code for get the image data 
		/*
		$getMyScreenCaptureAPIUrl = 'https://ee.iva-api.com/api/Images/ScreenCaptures/Batch?format=json&subscription-key='.$this->AVI_Subscription_Key;
		
		$getMyScreenCaptureAPIData = $this->access_data_by_curl('POST' , array(
																	'FilePath' => $filePath
																	) , $getMyScreenCaptureAPIUrl);
		*/															
	
		
		
		
		
		
		$getMyScreenCaptureAPIUrl = 'https://ee.iva-api.com/api/Images/ScreenCaptures/Redirect';
		$getMyScreenCaptureAPIData = $this->access_data_by_curl('GET' , array(
																'FilePath' => $filePath,
																'subscription-key' => $this->AVI_Subscription_Key,
																'ExpirationMinutes' => 60*24*365*5
																) , $getMyScreenCaptureAPIUrl , 1); 
																
												
		
		
		/*
		$getMyScreenCaptureAPIData = '[{"Url":"https://d1argtojjc5x02.cloudfront.net/content/photos/7756/25809_099.jpg?&Expires=1571834888&Signature=e8zNec1dSYqTTXTZCrYIwXy-NPlTXOySz4xsNeSKnJnxbwPyLjA7B-gWLOWsH38Ff6VO~XZewJbriBzp0or6J7H0c68vSQQRjIiy7GgoTXNfxes5fsdnyNG3ZA24qppmR84BzRosjLe5wFU2LQSpvvkagUl9F2Fr4D2lbLFmskrZd0vjXe~nUfS3Uxg0v8xW7PVrrgyXDs7iDWZsxqDFM05KHvhuFgVHNPUgbD2wNLiu0IfNcYKf8kSxK8KJEo10evk-B75K05pMbNdnh9FlktlC5cGFu9xyyKxADt24OREY4fBc0ABeU69~UIEu1betNNRuZrdq2WQXvoPF8ECrtA__&Key-Pair-Id=APKAIUDDGLY3RASDQSZQ","FilePath":"/content/hdphotos/7756/007756/007756_562x316_25809_099.jpg","Expires":"2019-10-23T08:14:15.3869451Z"}]';
		$getMyScreenCaptureAPIData = '[{"Url":"https://d1argtojjc5x02.cloudfront.net/content/photos/7756/25809_099.jpg?&Expires=1571834888&Signature=e8zNec1dSYqTTXTZCrYIwXy-NPlTXOySz4xsNeSKnJnxbwPyLjA7B-gWLOWsH38Ff6VO~XZewJbriBzp0or6J7H0c68vSQQRjIiy7GgoTXNfxes5fsdnyNG3ZA24qppmR84BzRosjLe5wFU2LQSpvvkagUl9F2Fr4D2lbLFmskrZd0vjXe~nUfS3Uxg0v8xW7PVrrgyXDs7iDWZsxqDFM05KHvhuFgVHNPUgbD2wNLiu0IfNcYKf8kSxK8KJEo10evk-B75K05pMbNdnh9FlktlC5cGFu9xyyKxADt24OREY4fBc0ABeU69~UIEu1betNNRuZrdq2WQXvoPF8ECrtA__&Key-Pair-Id=APKAIUDDGLY3RASDQSZQ","FilePath":"/content/hdphotos/7756/007756/007756_562x316_25809_099.jpg","Expires":"2019-10-23T08:14:15.3869451Z"}]';
		*/
		
		
		
		// $getMyScreenCaptureAPIData = Array(
										// array(
											// "Url" => "https://d1argtojjc5x02.cloudfront.net/content/photos/9901/942816_011.jpg?&Expires=1729600477&Signature=AQzsaDqmHKeR3Jyygoqu0imLnjr8KqbfGHgUOrU-j~NHHNZWhEGAoYURPNt6yVnQD2APrOnkXWY~eXx0fRSGLxebqP35UpR1tHfIy9eVpuA3akKWDFc8IccoZJ9qCRm-qaKBBzT5sukWfdq9lsFgxrUHz6FUHfZ1fNkmvLx6hik-50r9SQzhPeMxsXfc7HmewzNIQK-x7NqSCTaH6Cx~4OjeKK5k3wW3hJZBKi0rFlQP3-68b~DEZcQmDZguFVOYyPgMkyPK-knF4~YtB0PE2Svdg4g-1-z72kzutMLvmlKjdl-3RqgGtmgYqeKL2BiZxUQp0Y78~Yftx-GcmQVMnA__&Key-Pair-Id=APKAIUDDGLY3RASDQSZQ"
											// )	
										// );
		
		
		//print_r($getMyScreenCaptureAPIData); exit;
		
		
		return (!empty($getMyScreenCaptureAPIData[0]['Url']) && trim($getMyScreenCaptureAPIData[0]['Url']) != '')?$getMyScreenCaptureAPIData[0]['Url']:'';
	}
	
	function get_video_trailer_url($targetVideoId){
		
		$getMyVideoAPIUrl = 'https://ee.iva-api.com/api/Videos/GetVideo/'.$targetVideoId;
		$getMyVideoAPIData = $this->access_data_by_curl('GET' , array(
																	'Format' => 'hls',
																	'kbrate' => '2500',
																	// 'Expires' => '2019-10-23T10:06:02.120Z',
																	'Expires' => date("c" ,  strtotime('+5 years')),
																	'subscription-key' => $this->AVI_Subscription_Key
																	) , $getMyVideoAPIUrl); 
																	
		//print_r($getMyVideoAPIData); exit;													
		
		
		
		
		// $getMyVideoAPIData = '[{"Url":"https://video.internetvideoarchive.net/video.mp4?cmd=6&fmt=4&customerid=222333&publishedid=942816&e=1729691335&videokbrate=2500&h=9b284f3d5bd9f08c06a2bf422e80a9f4","Expires":"2024-10-23T13:48:55.0000000Z"}]';

		// $getMyVideoAPIData = json_decode($getMyVideoAPIData , true);
		
		
		
		return $getMyVideoAPIData;
		
	}
	
	
	
	public function access_data_by_curl($method , $arrayData , $targteUrl , $redirect = NULL){
		$url = 	$targteUrl;
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if($arrayData){ curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arrayData)); }
				break; 
			case "PUT": 
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if($arrayData){
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arrayData));
				}          
				break;
			case "DELETE": 
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				if($arrayData){
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arrayData));
				}          
				break;
			default:
				$url = sprintf("%s?%s", $url, http_build_query($arrayData));
		}
		
		//echo '</br>'.$url.'</br>'; exit;
		
	   // OPTIONS: 
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
		if($redirect){ //for get the redirect data
			curl_setopt($curl, CURLOPT_HEADER, TRUE);
		}
		// EXECUTE:
		$result = curl_exec($curl); 
		//print_r($result);exit;
		//this code is only get date from redirect header
		if ($redirect && preg_match('~Location: (.*)~i', $result, $match)) {
			
			$result = json_encode(array(array('Url' => trim($match[1]))));
		}
		
		
		
		if(!$result){die("Connection Failure");}
		curl_close($curl);
		
		$resultArray = json_decode($result , true);
		
		return $resultArray;
	}

	
	
	
	
	
}
