<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] 		= 'home';
$route['404_override'] 				= 'home/four_zero_four';
$route['translate_uri_dashes'] 		= FALSE;

$route['embed/(:num)'] 				= 'embed/index/$1' ;
$route['embedcv/(:num)'] 			= 'embed/embed_channel_video/$1' ;
$route['embedcv/(:num)/(:num)'] 	= 'embed/embed_channel_video/$1/$2';

$route['withOutSheMedia/(:num)'] 	= 'embed/withOutSheMedia/$1' ;
$route['withONlySheMediaTag/(:num)']= 'embed/withONlySheMediaTag/$1' ;
$route['withSheMediaTagAfterPlayerLoad/(:num)']= 'embed/withSheMediaTagAfterPlayerLoad/$1' ;

$route['profile'] 					 = 'dashboard/profile';  
/*** chat Routing Start ***/
$route['collection'] 			    = 'dashboard/collection';  
$route['about'] 					= 'dashboard/about_me';  
$route['watch-all'] 				= 'home/watchAll';  
$route['my-favorite'] 				= 'search/my_favorite_video';  
$route['monetize/(:any)']           = 'dashboard/upload_channel_video/single/false/$1';  
$route['monetization'] 				= 'dashboard/upload_channel_video/bulk';   
$route['monetization/getdiscovered'] = 'getdiscovered/upload_channel_video';   

$route['playlist'] 			        = 'dashboard/my_playlist';
$route['playlist/(:num)'] 			= 'dashboard/playlist/$1';

$route['article'] 			        = 'articles/blog';
$route['article/(:any)/(:any)'] 	= 'articles/blog/$1/$2';
$route['article/(:any)'] 	        = 'articles/redirect_blog/$1';

$route['monetization_by_mrss'] 	    = 'Videoelephant';

/*******************************************************************/
/****************** chat Routing Start **********************/
/*******************************************************************/
// $route['messenger'] = 'chat/firebase_chat';
// $route['socket_messenger'] = 'chat/socket_chat'; 
$route['firebase_messenger'] = 'chat/firebase_chat';
$route['messenger'] = 'chat/socket_chat';
/*******************************************************************/
/****************** chat Routing End **********************/
/*******************************************************************/

/*******************************************************************/
/****************** sign-up Routing Start **********************/
/*******************************************************************/
$route['sign-up'] 				= 'home/sign_up';
$route['account_type'] 			= 'home/account_type';
$route['primary_type/(:num)'] 	= 'home/primary_type/$1';
$route['artist_info'] 			= 'home/artist_info';
$route['testVideoElephant'] 	= 'home/testVideoElephant';
/*******************************************************************/
/****************** sign-up Routing End **********************/
/*******************************************************************/


/*******************************************************************/
/****************** Header Menu Routing Start **********************/
/*******************************************************************/
$route['music'] 		= 'home/current_mode/music';
$route['movies'] 		= 'home/current_mode/movies';
$route['television'] 	= 'home/current_mode/television';
$route['gaming'] 		= 'home/current_mode/gaming';
$route['casting_call'] 	= 'home/current_mode/casting_call';
$route['spotlight'] 	= 'home/current_mode/spotlight';
$route['live'] 			= 'home/current_mode/live';
$route['store'] 		= 'home/current_mode/store';
// $route['home'] 		    = 'home/spotlight';
/*******************************************************************/
/****************** Header Menu Routing End ************************/
/*******************************************************************/

/*******************************************************************/
/****************** Footer Routing Start ***************************/
/*******************************************************************/
//$route['about'] = 'footer/about';
$route['careers'] = 'footer/careers';
$route['press'] = 'footer/press';
$route['presblog'] = 'footer/blog';
$route['policies'] = 'footer/policies';
$route['terms-and-privacy'] = 'footer/terms_privacy';
$route['about_us'] = 'footer/about_us';
$route['giveaways'] = 'footer/giveaways';
$route['giveaway-rules'] = 'footer/giveaway_rules';


/*******************************************************************/
/****************** Footer Routing End *****************************/
/*******************************************************************/



/*******************************************************************/
/****************** Share Routing Start ****************************/
/*******************************************************************/
$route['watch'] = 'share';
$route['watch/(:any)'] = 'share/video/$1';
$route['watch/(:any)/(:any)'] = 'share/video/$1/$2';

$route['watching'] = 'share';
/*******************************************************************/
/****************** Share Routing End ******************************/
/*******************************************************************/

/*******************************************************************/
/****************** admin Routing Start ****************************/
/*******************************************************************/
$route['admin/support'] = 'support/support_admin';
$route['admin/department'] = 'support/department';
$route['admin/support_ticket'] = 'support/support_ticket';

/*******************************************************************/
/****************** admin Routing End ******************************/
/*******************************************************************/



$route['urbanone'] 		    = 'home/urbanone';

