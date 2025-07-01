<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Youtube extends CI_Controller {
	public $responses='';
	function __Construct(){
		parent::__Construct();
		$this->load->library(array('Audition_functions','query_builder','share_url_encryption'));
	}
	
	
	
	public function youtubeClient($outh=false){
		require APPPATH .'third_party/analytics/analytic/vendor/autoload.php';
		$config =  APPPATH .'third_party/analytics/client_secret_youtube.json';
		// AJAYDEEP PIXELNX ACCOUNT 
		// APP NAME - DISCOVERED_YOUTUBE
		try {
			$client = new Google_Client();
			$client->setAuthConfig($config);
			$client->addScope([
				'https://www.googleapis.com/auth/userinfo.profile',
				'https://www.googleapis.com/auth/userinfo.email',
				'https://www.googleapis.com/auth/youtube',
				'https://www.googleapis.com/auth/yt-analytics-monetary.readonly',
				'https://www.googleapis.com/auth/youtube.force-ssl'
			]);
			$client->setAccessType('offline');
			$client->setIncludeGrantedScopes(true);
			
			if($outh){
				$client->setApprovalPrompt("force");
			}else{
				$client->setApprovalPrompt("consent");
				$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/getRefreshToken');
			}
			return $client;
		}catch (Google\Service\Exception $e) {
			return  (object) array( 'error' => $e->getMessage() );
		}
	}
	
	function checkAccessToken($client,$channel_id=NULL){
		if(!empty($channel_id)){
			$token= $this->DatabaseModel->select_data('youtube_token','youtube_channel_list',array('channel_id'=>$channel_id));
			if(isset($token[0]['youtube_token']) && !empty($token[0]['youtube_token'])){
				$youtube_token 				= json_decode($token[0]['youtube_token'],true);
				$_SESSION['access_token']  	= $youtube_token['access_token'];
				$_SESSION['refresh_token'] 	= $youtube_token['refresh_token'];
			}else{
				return (object) array('status'=>0,'error'=>'Token not available in database.');
			}
		}
		
		if ($client->isAccessTokenExpired()) {
			$refresh_token = isset($_SESSION['refresh_token'])? $_SESSION['refresh_token'] : '';
			if(isset($refresh_token) && !empty($refresh_token)){
				$_SESSION['access_token'] = $client->fetchAccessTokenWithRefreshToken($refresh_token);
				$client->setAccessToken($_SESSION['access_token']);
				return $client;
			}else{
				return (object) array('status'=>0,'error'=>'Token not available in database.');
			}
		}else if(isset($_SESSION['access_token']) && $_SESSION['access_token']) {
			 $client->setAccessToken($_SESSION['access_token']);
			 return $client;
		}
	}
	
	function getRefreshToken(){
		$client = $this->youtubeClient(true);
		
		if(!isset($client->error)){
			if (!isset($_GET['code'])) {
				$auth_url = $client->createAuthUrl();
				redirect($auth_url);
			}else 
			{
				$client->authenticate($_GET['code']);

				$_SESSION['refresh_token'] = $client->getRefreshToken();
				$_SESSION['access_token'] = $client->getAccessToken();
				
				$redirect_uri = base_url('youtube/getChannel');
				redirect($redirect_uri);
			}
		}else{
			$redirect_uri = base_url('youtube/channel_page');
			redirect($redirect_uri.'?error='.$client->error);
		}
	}
	
	function getChannel(){
		// echo '<pre>';
		// print_r($_SESSION);die;
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client);
			
			if(!isset($client->error)){
				
				$youtube = new Google_Service_YouTube($client);
				try{
					$channelsResponse = $youtube->channels->listChannels('snippet,statistics,status,id', array('mine' => 'true'));
					
					if(isset($channelsResponse['items'][0]['id'])){
						$channel_id 		=  	$channelsResponse['items'][0]['id'];
						$title 				=  	$channelsResponse['items'][0]['snippet']['title'];
						$thumbnails 		=  	$channelsResponse['items'][0]['snippet']['thumbnails']['high']['url'];
						$publishedAt 		=  	$channelsResponse['items'][0]['snippet']['publishedAt'];
						$privacyStatus 		=  	$channelsResponse['items'][0]['status']['privacyStatus'];
						$subscriberCount 	=  	$channelsResponse['items'][0]['statistics']['subscriberCount'];
						$videoCount 		=  	$channelsResponse['items'][0]['statistics']['videoCount'];
						$viewCount 			=  	$channelsResponse['items'][0]['statistics']['viewCount'];
						$uid 				= 	is_login();
						
						$checkData = $this->DatabaseModel->select_data('*','youtube_channel_list',array('user_id'=>$uid,'channel_id'=>$channel_id));
						
						if(empty($checkData)){
							$insert = [	'user_id' 		=> $uid,
										'channel_id' 	=> $channel_id,
										'title' 		=> $title,
										'thumbnails' 	=> $thumbnails,
										'privacyStatus' => $privacyStatus,
										'subscriberCount' => $subscriberCount,
										'videoCount' 	=> $videoCount,
										'viewCount' 	=> $viewCount,
										'publish_at' 	=> date('Y-m-d H:i:s',strtotime($publishedAt)),
										'youtube_token'	=> json_encode(['refresh_token'=>$_SESSION['refresh_token'],'access_token'=>$_SESSION['access_token']]),
										];
							$result = $this->DatabaseModel->access_database('youtube_channel_list','insert',$insert);
								
						}else{
							$where  = [	'user_id' 		=> $uid,
										'channel_id' 	=> $channel_id];
										
							$update = [	'title' 		=> $title,
										'thumbnails' 	=> $thumbnails,
										'privacyStatus' => $privacyStatus,
										'subscriberCount'=> $subscriberCount,
										'videoCount' 	=> $videoCount,
										'viewCount' 	=> $viewCount,
										'youtube_token'	=> json_encode(['refresh_token'=>$_SESSION['refresh_token'],'access_token'=>$_SESSION['access_token']]),
									];
							$result = $this->DatabaseModel->access_database('youtube_channel_list','update',$update,$where);
						}
						if($result)
							$this->getVideosByChannel($channel_id);
							// $this->getPlaylist($channel_id); 
					}
				}catch(Google\Service\Exception $e){
					print_r( array( 'error' => $e->getMessage() ) );die;
				}
			}else{
				$redirect_uri = base_url('youtube/getRefreshToken');
				redirect($redirect_uri.'?error='.$client->error);
			}

		}else{
			$redirect_uri = base_url('youtube/my_channels');
			redirect($redirect_uri.'?error='.$client->error);
			
		}
	}
	
	function getPlaylist($channel_id){
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client,$channel_id);
			
			if(!isset($client->error)){
				
				$youtube = new Google_Service_YouTube($client);
				try{
					$queryParams = [
						'channelId' => 	$channel_id,
						'maxResults'=>	50,
					];
					$PlaylistResponse = $youtube->playlists->listPlaylists('contentDetails,id,localizations,player,snippet,status', $queryParams);
					
					if(isset($PlaylistResponse['items'])){
						
						foreach($PlaylistResponse['items'] as $playlist){
							
							$playlist_id 		=  	$playlist['id'];
							$title 				=  	$playlist['snippet']['title'];
							$thumbnails 		=  	$playlist['snippet']['thumbnails']['high']['url'];
							$publishedAt 		=  	$playlist['snippet']['publishedAt'];
							$privacyStatus 		=  	$playlist['status']['privacyStatus'];
							$uid 				= 	is_login();
							
							$checkData = $this->DatabaseModel->select_data('*','youtube_playlist_list',array('user_id'=>$uid,'playlist_id'=>$playlist_id));
							
							if(empty($checkData)){
								$insert = [	'user_id' 		=> $uid,
											'channel_id' 	=> $channel_id,
											'playlist_id' 	=> $playlist_id,
											'title' 		=> $title,
											'thumbnails' 	=> $thumbnails,
											'privacyStatus' => $privacyStatus,
											'publish_at' 	=> date('Y-m-d H:i:s',strtotime($publishedAt)) ];
								$result = $this->DatabaseModel->access_database('youtube_playlist_list','insert',$insert);
									
							}else{
								$where  = [	'user_id' 		=> $uid,
											'playlist_id' 	=> $playlist_id];
											
								$update = [	'title' 		=> $title,
											'thumbnails' 	=> $thumbnails,
											'privacyStatus' => $privacyStatus];
								$result = $this->DatabaseModel->access_database('youtube_playlist_list','update',$update,$where);
							}
							if($result)
								$this->getPlaylistItem($channel_id,$playlist_id);
						}
						
					}
				
				}catch(Google\Service\Exception $e){
					print_r( array( 'error' => $e->getMessage() ) );die;
				}
			}else{
				$redirect_uri = base_url('test/getRefreshToken');
				redirect($redirect_uri.'?error='.$client->error);
			}

		}else{
			$redirect_uri = base_url('test/my_channels');
			redirect($redirect_uri.'?error='.$client->error);
			
		}
	}
	
	function getPlaylistItem($channel_id,$playlist_id){
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client,$channel_id);
			 
			if(!isset($client->error)){
				
				$youtube = new Google_Service_YouTube($client);
				$video_items = [];
				$nextPageToken='';
				try{
					do {
						
						$queryParams = [
							'maxResults' => 50,
							'playlistId' => $playlist_id,
							'pageToken' => $nextPageToken
						];

						$listItemResponse = $youtube->playlistItems->listPlaylistItems('contentDetails,status', $queryParams);
						
						$nextPageToken = isset($listItemResponse['nextPageToken'])? $listItemResponse['nextPageToken']: '';
						
						if(isset($listItemResponse['items'])){
							
							foreach($listItemResponse['items'] as $item){
								// if($item['status']['privacyStatus'] == 'public')
								$video_items[] =  $item['contentDetails']['videoId'];
							}
						}
					}while($nextPageToken != '');
					
					$where  = [	'user_id' => is_login(),'channel_id' => $channel_id,'playlist_id' => $playlist_id];
										
					$update = [	'video_items' => implode(',',$video_items) ];
					
					$result = $this->DatabaseModel->access_database('youtube_playlist_list','update',$update,$where);
				
				}catch(Google\Service\Exception $e){
					print_r( array( 'error' => $e->getMessage() ) );die;
				}
			}else{
				$redirect_uri = base_url('test/getRefreshToken');
				redirect($redirect_uri.'?error='.$client->error);
			}
		}else{
			$redirect_uri = base_url('test/playlist_page');
			redirect($redirect_uri.'?error='.$client->error);
			
		}
	}
	
	function getVideosByChannel($channel_id){
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client,$channel_id);
			 
			if(!isset($client->error)){
				
				$youtube = new Google_Service_YouTube($client);
				$video_items = [];
				$nextPageToken='';
				
				try{
					do {
						
						$queryParams = [
							'maxResults' => 50,
							'channelId' => $channel_id,
							'type' => 'video',
							'pageToken' => $nextPageToken,
						];
						
						$searchResponse = $youtube->search->listSearch('snippet', $queryParams);
						// echo '<pre>';
					// print_r($searchResponse);die;
						$nextPageToken = isset($searchResponse['nextPageToken'])? $searchResponse['nextPageToken']: '';
						
						if(isset($searchResponse['items'])){
							
							foreach($searchResponse['items'] as $item){
								// if($item['status']['privacyStatus'] == 'public')
								$video_items[] =  $item['id']['videoId'];
							}
						}
					}while($nextPageToken != '');
					
					$where  = [	'user_id' => is_login(),'channel_id' => $channel_id];
										
					$update = [	'video_items' => implode(',',$video_items) ];
					
					$result = $this->DatabaseModel->access_database('youtube_channel_list','update',$update,$where);
				
				}catch(Google\Service\Exception $e){
					print_r( array( 'error' => $e->getMessage() ) );die;
				}
				
			}else{
				$redirect_uri = base_url('test/getRefreshToken');
				redirect($redirect_uri.'?error='.$client->error);
			}
		}else{
			$redirect_uri = base_url('test/playlist_page');
			redirect($redirect_uri.'?error='.$client->error);
			
		}
	}
	
	public function uploadYoutubeVideo() {
		$htmlBody='NA';
		$video="linkedin.mp4";
		$title="tvn rahul youtube api v3";
		$desc="tvn rahul youtube api v3 for php";
		$tags=["rahultvn","youtubeapi3"];
		$privacy_status="public";
		
		$client = $this->youtubeClient();
		
		if(!isset($client->error)){
			
			$client =  $this->checkAccessToken($client,$channel_id = 'UCuvfroUjVTqP6MsRbdblHew');
			// Define an object that will be used to make all API requests.
			$youtube = new Google_Service_YouTube($client);
			
			try{
				// REPLACE this value with the path to the file you are uploading.
				
				$videoPath = ABS_PATH .'uploads/admin/video/video.webm';
				//$videoPath = "videos/linkedin.mp4";

				// Create a snippet with title, description, tags and category ID
				// Create an asset resource and set its snippet metadata and type.
				// This example sets the video's title, description, keyword tags, and
				// video category.
				$snippet = new Google_Service_YouTube_VideoSnippet();
				$snippet->setTitle($title);
				$snippet->setDescription($desc);
				$snippet->setTags($tags);

				// Numeric video category. See
				// https://developers.google.com/youtube/v3/docs/videoCategories/list 
				$snippet->setCategoryId("22");

				// Set the video's status to "public". Valid statuses are "public",
				// "private" and "unlisted".
				$status = new Google_Service_YouTube_VideoStatus();
				$status->privacyStatus = $privacy_status;

				// Associate the snippet and status objects with a new video resource.
				$video = new Google_Service_YouTube_Video();
				$video->setSnippet($snippet);
				$video->setStatus($status);

				// Specify the size of each chunk of data, in bytes. Set a higher value for
				// reliable connection as fewer chunks lead to faster uploads. Set a lower
				// value for better recovery on less reliable connections.
				$chunkSizeBytes = 1 * 1024 * 1024;

				// Setting the defer flag to true tells the client to return a request which can be called
				// with ->execute(); instead of making the API call immediately.
				$client->setDefer(true);

				// Create a request for the API's videos.insert method to create and upload the video.
				$insertRequest = $youtube->videos->insert("status,snippet", $video);

				// Create a MediaFileUpload object for resumable uploads.
				$media = new Google_Http_MediaFileUpload(
				$client,
				$insertRequest,
				'video/*',
				null,
				true,
				$chunkSizeBytes
				);
				$media->setFileSize(filesize($videoPath));

				// Read the media file and upload it chunk by chunk.
				$status = false;
				$handle = fopen($videoPath, "rb");
				while (!$status && !feof($handle)) {
					$chunk = fread($handle, $chunkSizeBytes);
					$status = $media->nextChunk($chunk);
				}

				fclose($handle);

				// If you want to make other calls after the file upload, set setDefer back to false
				$client->setDefer(false);


				$htmlBody .= "<h3>Video Uploaded</h3><ul>";
				$htmlBody .= sprintf('<li>%s (%s)</li>',
				$status['snippet']['title'],
				$status['id']);

				$htmlBody .= '</ul>';
				$result['id']=$status['id'];
				$result['title']=$status['snippet']['title'];

			}catch (Google_Service_Exception $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}

			 
			
			$result['message']=$htmlBody;
			echo '<pre>';
			print_r($result); 
		}
	}
	
	
	
	
	
	
	
	/******************************END OF YOUTUBE ***************************************/
	
	public function gaouth(){
		
		require APPPATH .'third_party/analytics/analytic/vendor/autoload.php';
		$config =  APPPATH .'third_party/analytics/client_secret_314045516986-2f8ngc90jk9a02j2vgpla64a03vjjngu.apps.googleusercontent.com.json';
		
		
		// Create the client object and set the authorization configuration
		// from the client_secretes.json you downloaded from the developer console.
		try {
			$client = new Google_Client();
			$client->setAuthConfig($config);
			$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
			$client->setAccessType('offline');
			$client->setApprovalPrompt('force');
		
		// If the user has already authorized this app then get an access token
		// else redirect to ask the user to authorize access to Google Analytics.
		// unset($_SESSION['access_token']);die; 
		
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  // Set the access token on the client.
		  $client->setAccessToken($_SESSION['access_token']);
		  // Create an authorized analytics service object.
		  $analytics = new Google_Service_Analytics($client);
		  // Get the first view (profile) id for the authorized user.
		  $profile = $this->getFirstProfileId($analytics);

		  // Get the results from the Core Reporting API and print the results.
		  $results = $this->getResults($analytics, $profile);
		
		  $this->printResults($results);
		}
		else 
		{
			if ($client->isAccessTokenExpired()) {
				
				$refresh_token = '1//0d3i8MHqVQ9SXCgYIARAAGA0SNwF-L9Ir6I3qsp4hlD0xuE3mM2DZu3fri5airmX0_aLTbPjgrIp9ymZUQxyQhTaWkreYq7S3RcE';
				// if(isset($_SESSION['refresh_token']) && $_SESSION['access_token'] ){
				if($refresh_token){
					// $_SESSION['access_token'] = $client->fetchAccessTokenWithRefreshToken($_SESSION['refresh_token']);
					$_SESSION['access_token'] = $client->fetchAccessTokenWithRefreshToken('1//0d3i8MHqVQ9SXCgYIARAAGA0SNwF-L9Ir6I3qsp4hlD0xuE3mM2DZu3fri5airmX0_aLTbPjgrIp9ymZUQxyQhTaWkreYq7S3RcE');
					redirect(base_url('test/gaouth'));
				}else{
					$redirect_uri = base_url('test/oauth2callback');
					header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
				}
				
				
			}
		}
		
		}catch (Google\Service\Exception $e) {
			print_r( array( 'error' => $e->getMessage() ) );
		}
		
	}
	
	function getFirstProfileId($analytics) {
	  // Get the user's first view (profile) ID.

	  // Get the list of accounts for the authorized user.
	  $accounts = $analytics->management_accounts->listManagementAccounts();
		
	  if (count($accounts->getItems()) > 0) {
		$items = $accounts->getItems();
		
		$firstAccountId = $items[1]->getId(); /* For Discovered Account*/

		// Get the list of properties for the authorized user.
		$properties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);

		if (count($properties->getItems()) > 0) {
		  $items = $properties->getItems();
		  $firstPropertyId = $items[0]->getId();

		  // Get the list of views (profiles) for the authorized user.
		  $profiles = $analytics->management_profiles->listManagementProfiles($firstAccountId, $firstPropertyId);
// echo '<pre>';
		// print_r($profiles);die;
		  if (count($profiles->getItems()) > 0) {
			$items = $profiles->getItems();

			// Return the first view (profile) ID.
			return $items[0]->getId();

		  } else {
			throw new Exception('No views (profiles) found for this user.');
		  }
		} else {
		  throw new Exception('No properties found for this user.');
		}
	  } else {
		throw new Exception('No accounts found for this user.');
	  }
	}

	function getResults($analytics, $profileId) {
	  // Calls the Core Reporting API and queries for the number of sessions
	  // for the last seven days.
	  // $segmentlist = $this->segmentlist($analytics);
	  // echo '<pre>';
	  // print_r($segmentlist);die;	
	  return $analytics->data_ga->get( 
		  'ga:'.$profileId,
		  '2021-01-29',
		  '2021-02-04',
		  // 'ga:users,ga:newUsers,ga:sessions'
		  'ga:users,ga:sessions'
		  // ,['dimensions'=>'ga:country',"segment" => "gaid::it1yeqyJS9-4vphn7hU0iQ"]
		  ,['dimensions'=>'ga:country',"segment" => "users::condition::ga:hostname=@apis.discovered.tv"]
		  // ,['dimensions'=>'ga:country',"segment" => "users::condition::ga:hostname=@discovered.tv"]
		  ); 
	   
		  
	}
	function segmentlist($analytics){
		try {
		  $segments = $analytics->management_segments->listManagementSegments();
		} catch (apiServiceException $e) {
		  print 'There was an Analytics API service error '
			  . $e->getCode() . ':' . $e->getMessage();

		} catch (apiException $e) {
		  print 'There was a general API error '
			  . $e->getCode() . ':' . $e->getMessage();
		}
		 $html = '';

		/*
		 * Example #2:
		 * The results of the list method are stored in the segments object.
		 * The following code shows how to iterate through them.
		 */
		foreach ($segments->getItems() as $segment) {
		  $html .= "HTML
					<pre>

					Segment ID = {$segment->getId()}
					Kind       = {$segment->getKind()}
					Self Link  = {$segment->getSelfLink()}
					Name       = {$segment->getName()}
					Definition = {$segment->getDefinition()}
					Created    = {$segment->getCreated()}
					Updated    = {$segment->getUpdated()}
					</pre>
					HTML";
		 
		}
		 print $html;
	}
	function printResults($results) {
	  // Parses the response from the Core Reporting API and prints
	  // the profile name and total sessions.
	 
	  if (count($results->getRows()) > 0) {

		// Get the profile name.
		$profileName = $results->getProfileInfo()->getProfileName();

		// Get the entry for the first entry in the first row.
		$rows['geoData'] = $results->getRows();
		// $sessions = $rows[0][0];
 
		// Print the results.
		// echo '<pre>';
		// print "<p>First view (profile) found: $profileName</p>";
		// print_r($rows);
		$this->load->view('test/analytics3',$rows);
	  } else {
		print "<p>No results found.</p>";
	  }
	}
	function oauth2callback(){
		require APPPATH .'third_party/analytics/analytic/vendor/autoload.php';
		$config =  APPPATH .'third_party/analytics/client_secret_314045516986-2f8ngc90jk9a02j2vgpla64a03vjjngu.apps.googleusercontent.com.json';
		
		// Create the client object and set the authorization configuration
		// from the client_secretes.json you downloaded from the developer console.
		
		
		$client = new Google_Client();
		$client->setAuthConfig($config);
		$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
		$client->setAccessType('offline');
		$client->setApprovalPrompt('force');


		// Handle authorization flow from the server.
		if (! isset($_GET['code'])) {
		  $auth_url = $client->createAuthUrl();
		  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
		} else {
		  $client->authenticate($_GET['code']);
		 
		  $_SESSION['refresh_token'] = $client->getRefreshToken();
		   $_SESSION['access_token'] = $client->getAccessToken();
		  
		  // echo '<pre>';
		  // print_r($_SESSION);die;
		  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/test/gaouth';
		  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}

	}
	
	
	function downloadaudio(){
		$ch = curl_init('https://s3-trans-cdn.discovered.tv/aud_160/videos/3c92106ebc06a7e3860987db6c78b0d5/3c92106ebc06a7e3860987db6c78b0d5.mp4');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		$output = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($status == 200) {
			header("Content-type: application/octet-stream"); 
			header("Content-Disposition: attachment; filename=m.mp4"); 
			echo $output;
		}
		// $this->load->helper('aws_s3_action');
		// s3_get_object('aud_218/videos/07PvlY1XoPdlFEaldLSA.mp4');
	}
	
	function getcontent(){
		$name = rand().'.jpeg';
		$filePath = 'https://s3-us-west-1.amazonaws.com/discovered.tv/aud_215/images/m8qrivrkydrtZGHsUXX7.png'; 
		$pathToImages = ABS_PATH .'uploads/aud_15/images/'.$name ;
		file_put_contents($pathToImages, file_get_contents($filePath));
	}
	
						
	
	
}

