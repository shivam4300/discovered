<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Modal extends CI_Controller {

    private $uid;

    public function __construct(){

		parent::__construct();
		$this->load->library(array('audition_functions','share_url_encryption','common'));
		//$this->load->helper(array('aws_s3_action','button'));
        $this->uid = is_login();
	}


    public function login_popup(){

        $checked  = isset($_COOKIE['aud_email'])? 'checked' : '';
        $aud_mail = isset($_COOKIE['aud_email'])? $_COOKIE['aud_email'] : '';
        $aud_pwd  = isset($_COOKIE['aud_pwd'])? $_COOKIE['aud_pwd'] : '';
        $getdiscovered  = (isset($_GET['getdiscovered']) && $_GET['getdiscovered']==1)? '?getdiscovered=1' : '';


        $html ='<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="text-center">Log in to '.PROJECT.'</h3>
                </div>
                <div class="modal-body">
                    <div class="au_form_section">
                        <div class="au_box_login">
                            <div class="au_socialconnect">
                                <a id="faceLogin" class="au_facebook">Login with Facebook</a>
                                <a  class="au_google">Login with Google</a>
                                <div  class="g-signin2 hide " data-onsuccess="onSignIn"></div>
                            </div>
                            <span id="login_box">
                                <h5 class="upper_spacer20 text-center"><i>Or login with your email</i></h5>
                                <div class="au_box_form">
                                    <form>
                                        <div class="form-group">
                                            <input type="text" class="form-control login_form" id="u_email" placeholder="E-mail" value="'.$aud_mail.'">
                                            <span class="form-error help-block"></span>
                                        </div>
                                        <div class="form-group position-realtive">
                                            <span toggle="#u_pwd" class="dis_eyeIcon fa fa-eye dis-toggle-password" aria-hidden="true"></span>
                                            <input autocomplete  type="password" class="form-control login_form" id="u_pwd" placeholder="Password" value="'.$aud_pwd.'">
                                            <span class="form-error help-block"></span>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="row">
                                                <div class="checkbox">
                                                    <label>
                                                    <input type="checkbox" id="rem_me" '.$checked.'> Remember Me
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 text-right">
                                            <div class="form-group">
                                                <a href="'.base_url('home/reset_password').'" >Forgot password ?</a>
                                            </div>
                                        </div>
                                        <div class="col-lg12 col-md-12">
                                            <div class="row">
                                                <div id="login-recaptcha" class="dis_recaptcha_wrap m_t_10 m_b_10"></div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn dis_btn" onclick="login_user()">Submit</button>
                                        <div class="form-group text-center">
                                            Don\'t have a '.PROJECT.' account? <a href="'.base_url('sign-up').$getdiscovered.'">Create one today!</a>
                                            <span class="dis_notehelp">Need help? Contact us at  <a href="mailto:help@discovered.tv">help@discovered.tv </a></span>
                                        </div>
                                    </form>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>';

        echo $html;
    }


    public function  getVideoDiscription($post_id,$playlist_id){
		if(!empty($post_id)){

			$field = 'channel_post_video.is_video_processed,channel_post_video.iva_id,channel_post_video.title,channel_post_video.user_id,users.user_uname,users.user_name,channel_post_video.description,channel_post_video.uploaded_video,country.country_name,channel_post_video.post_key,mode_of_genre.genre_name,website_mode.mode,channel_post_video.video_duration,channel_post_video.age_restr,artist_category.category_name';

		    $join = array('multiple' , array(
												array(	'users',
														'users.user_id 	= channel_post_video.user_id',
														'left'),
												array(	'users_content',
														'users.user_id 	= users_content.uc_userid',
														'left'),
												array(	'country',
														'country.country_id = users_content.uc_country',
														'left'),
												array(	'website_mode',
														'website_mode.mode_id 	= channel_post_video.mode',
														'left'),
												array(	'mode_of_genre',
														'channel_post_video.genre = mode_of_genre.genre_id',
														'left'),
												array(	'artist_category',
														'users.user_level = artist_category.category_id',
														'left'),
											)
										);

			$Video = $this->DatabaseModel->select_data($field,'channel_post_video',array('post_id'=>$post_id),'',$join);

			if(isset($Video[0])){

				$user_id 					= $Video[0]['user_id'];
				$iva_id 					= $Video[0]['iva_id'];
				$upl_video 					= $Video[0]['uploaded_video'];
				$post_key 					= $Video[0]['post_key'];
				$is_video_processed 		= $Video[0]['is_video_processed'];

				$link 						= base_url().$this->common->generate_single_content_url_param($post_key , 2);
				$Video[0]['links'] 			= $link;

                if(!empty($playlist_id)){
                    $Video[0]['links'] = $this->share_url_encryption->share_single_page_link_creator(2 .'|'.$post_id,'encode','',array('list'=> $playlist_id ));
                }

				$FilterData 				= $this->share_url_encryption->FilterIva($user_id,$iva_id,'',trim($upl_video),false,'.mp4',$is_video_processed);
				$Video[0]['uploaded_video'] = $FilterData['video'];

				$Video[0]['video_duration'] = gmdate("H:i:s", $Video[0]['video_duration'])  ;

				$ages = $this->audition_functions->age();
				$Video[0]['age_restr'] = isset($ages[$Video[0]['age_restr']])?$ages[$Video[0]['age_restr']]:$Video[0]['age_restr'];

				$Video[0]['isMyFavorite'] 	= 0;
				if(isset($this->session->userdata['user_login_id'])){
					$uid 	= $this->session->userdata['user_login_id'];

					$isMyFavorite 	= $this->DatabaseModel->select_data('*','channel_favorite_video',['user_id'=>$uid,'channel_post_id'=>$post_id],1);

					if(!empty($isMyFavorite)){
						$Video[0]['isMyFavorite']	=	1;
					}
				}
				return $Video[0];
			}else{
				return [];
			}
		}else{
			return [];
		}


	}


    function getMyPlaylist($post_id=''){
		$this->load->library('manage_session');
		//$this->load->library('creator_jwt');
		$uid 	= $this->uid;
		$playlist = [];
		//$TokenResponce = $this->creator_jwt->MatchToken();
		//if($TokenResponce['status'] == 1){
			if(!empty($post_id)){
				//$post_id 	   = $this->input->post('post_id');
				$playlist_ids  = $this->DatabaseModel->select_data('*','channel_video_playlist',['user_id'=>$uid,'playlist_type'=>2]);

				if(!empty($playlist_ids)){
					foreach($playlist_ids as $list){
						$video_ids 	= explode('|',$list['video_ids']);
						$checked 	= (in_array($post_id ,$video_ids ))? 'checked' : '';
						$playlist[] = ['playlist_id'=>$list['playlist_id'],'title'=>$list['title'],'checked'=>$checked,'video_ids_count'=>(sizeof($video_ids)-1)];
					}
				}

			}
		//}
		return $playlist;
	}


    Public function video_popup($post_id,$playlist_id=''){
        $this->load->helper('cookie');
        if(!empty($post_id)){

            $popup_audio =  get_cookie('popup_audio');

            $mute =   $popup_audio == 'true'  ||  $popup_audio == ''   ? 'mute' : '';
            $muted =   $popup_audio == 'true' ||  $popup_audio == ''   ? 'muted' : '';

            $data = $this->getVideoDiscription($post_id,$playlist_id);

            if(!empty($data)){
                $by = 'By - <a href="'.base_url().'channel?user='.$data['user_uname'].'">'.$data['user_name'].'</a>, '.$data['category_name'].', '.$data['country_name'].'';
                $isMyFavoriteText = ($data['isMyFavorite'] == 1) ? "Added To favorites" : "Add To favorites";

                $description = $data['description'];

                if(strlen($data['description']) > 150){
                    $description = substr($data['description'],0,150).'<a href="javascript:;" id="show_more">... Show More</a>';
                }
                $is_session_uid = (is_session_uid($data['user_id']))?1:0;

                $html = '<button type="button" class="close video_view pause_sneak_peak" data-dismiss="modal">&times;</button>
                         <div class="modal-body">
                            <div class="dis_popup_data">
                                <div class="Flexible-container">
                                    <video autoplay loop '. $muted .'  class="banner_video play_sneak_peak" id="modelVideo">
                                        <source src="'.$data['uploaded_video'].'" type="video/mp4" >
                                    </video>
                                </div>
                                <div class="overlay">
                                    <div class="dis_popup_overlay_data">
                                        <div class="">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#preview" aria-controls="preview" role="tab" data-toggle="tab">PREVIEW</a></li>
                                                <li role="presentation"><a href="#moreinfo" aria-controls="moreinfo" role="tab" data-toggle="tab">MORE INFO</a></li>
                                                <li role="presentation"><a href="#castncrew" aria-controls="castncrew" role="tab" data-toggle="tab" class="ShowCastNCrew" data-post_id="'.$post_id.'">Cast & Crew</a></li>
                                                <li role="presentation"><a href="#detailDescription" aria-controls="detailDescription" role="tab" data-toggle="tab">SYNOPSIS</a></li>';
                                        if(!empty($playlist_id)){
                                            $html .='<li role="presentation"><a href="#episodePlaylist" aria-controls="episodePlaylist" role="tab" data-toggle="tab" class="ShowEpisode" data-playlist_id="'.$playlist_id.'">EPISODES</a></li>';
                                        }
                                        $html .='</ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content custom_scrol ">
                                                <div role="tabpanel" class="tab-pane active" id="preview">
                                                    <div class="dis_tabdata tb_preview appendData">
                                                        <div class="dis_overlay_btn">
                                                            <a href="'.$data['links'].'" id="modelPopVideo" class="">
                                                                <img src="'.CDN_BASE_URL.'repo/images/popup_play_icon.png" class="img-responsive" alt="">
                                                            </a>
                                                        </div>
                                                        <h3>'.$data['title'].'</h3>
                                                        <ul class="dis_sneakPeek_vd" id="modeGenreGurationAge">
                                                            <li>'.strtoupper($data['mode']).'</li>
                                                            <li>'.strtoupper($data['genre_name']).'</li>
                                                            <li>'.$data['video_duration'].'</li>
                                                            <!--li>'.$data['age_restr'].'</li-->
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="moreinfo">
                                                    <div class="dis_tabdata appendData">
                                                        <h3>'.$data['title'].'</h3>
                                                        <h4>'.$by.'</h4>
                                                        <ul class="dis_sneakPeek_vd" id="modeGenreGurationAge">
                                                            <li>'.strtoupper($data['mode']).'</li>
                                                            <li>'.strtoupper($data['genre_name']).'</li>
                                                            <li>'.$data['video_duration'].'</li>
                                                            <!--li>'.$data['age_restr'].'</li-->
                                                        </ul>
                                                        <div class="dis_overlay_btn">
                                                            <a href="'.$data['links'].'" id="modelPopVideo" class="">
                                                                <img src="'.CDN_BASE_URL.'repo/images/popup_play_icon.png" class="img-responsive" alt="">
                                                            </a>
                                                        </div>

                                                        <p>'.$description.'</p>
                                                        <div class="dis_btndiv" id="vote_n_favourite">
                                                            <a  class="dis_btn dtvShareMe" data-share="2|'.$post_id.'">
                                                                <!--img src="'.base_url().'repo/images/vote_now_icon.png" class="img-responsive" alt=""-->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" fill-opacity="0" fill="rgb(119, 119, 119)" d="M0.000,0.000 L20.000,0.000 L20.000,20.000 L0.000,20.000 L0.000,0.000 Z"></path>
                                                                <path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M17.878,7.029 L13.021,2.216 C12.901,2.097 12.758,2.037 12.594,2.037 C12.430,2.037 12.287,2.097 12.167,2.216 C12.047,2.335 11.987,2.476 11.987,2.639 L11.987,5.045 L9.862,5.045 C5.352,5.045 2.585,6.308 1.561,8.834 C1.226,9.674 1.058,10.717 1.058,11.964 C1.058,13.005 1.460,14.418 2.263,16.204 C2.282,16.248 2.315,16.323 2.362,16.429 C2.410,16.536 2.453,16.630 2.490,16.712 C2.529,16.793 2.570,16.862 2.614,16.918 C2.690,17.025 2.778,17.078 2.880,17.078 C2.974,17.078 3.049,17.047 3.102,16.984 C3.156,16.922 3.183,16.843 3.183,16.749 C3.183,16.693 3.175,16.610 3.159,16.500 C3.144,16.390 3.136,16.317 3.136,16.279 C3.104,15.853 3.088,15.468 3.088,15.123 C3.088,14.490 3.144,13.923 3.254,13.422 C3.365,12.920 3.518,12.486 3.714,12.120 C3.910,11.753 4.163,11.437 4.473,11.170 C4.783,10.904 5.117,10.686 5.474,10.517 C5.831,10.348 6.252,10.214 6.736,10.117 C7.219,10.020 7.707,9.953 8.197,9.915 C8.687,9.878 9.242,9.859 9.862,9.859 L11.987,9.859 L11.987,12.265 C11.987,12.428 12.047,12.570 12.167,12.688 C12.287,12.807 12.429,12.867 12.594,12.867 C12.758,12.867 12.900,12.807 13.021,12.688 L17.878,7.875 C17.998,7.756 18.058,7.615 18.058,7.452 C18.058,7.289 17.998,7.148 17.878,7.029 Z"></path></svg><span>Share now</span>
                                                            </a>';
                                                            if(is_login()){
                                                                $html .= '<a class="dis_btn AddToFavriote" data-post_id="'.$post_id.'">';
                                                            }else{
                                                                $html .='<a class="dis_btn openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">';
                                                            }

                                                            $html .='<!--img src="'. base_url().'repo/images/favourites_icon.png" class="img-responsive" alt=""-->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 20 20"> <path fill-rule="evenodd" fill-opacity="0" fill="rgb(119, 119, 119)" d="M0.000,0.000 L20.000,0.000 L20.000,20.000 L0.000,20.000 L0.000,0.000 Z"></path>
                                                                <path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M16.000,13.000 L16.000,16.999 L14.000,16.999 L14.000,13.000 L10.000,13.000 L10.000,11.000 L14.000,11.000 L14.000,7.000 L16.000,7.000 L16.000,11.000 L20.000,11.000 L20.000,13.000 L16.000,13.000 ZM-0.000,7.000 L12.000,7.000 L12.000,9.000 L-0.000,9.000 L-0.000,7.000 ZM-0.000,3.000 L12.000,3.000 L12.000,5.000 L-0.000,5.000 L-0.000,3.000 ZM8.000,13.000 L-0.000,13.000 L-0.000,11.000 L8.000,11.000 L8.000,13.000 Z"></path>
                                                                </svg><span>'.$isMyFavoriteText.'</span>
                                                            </a>';


                                                            if(is_login()){
                                                                $html .='<a class="dis_btn AddToPlaylistTT openModalPopup" data-post_id="'.$post_id.'" data-href="modal/playlist_popup/'.$post_id.'" data-cls="dis_addplaylist_modal dis_center_modal muli_font">';
                                                            }else{
                                                                $html .='<a class="dis_btn openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">';
                                                            }

                                                            $html .='<!--img src="'.base_url().'repo/images/favourites_icon.png" class="img-responsive" alt=""-->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 20 20"> <path fill-rule="evenodd" fill-opacity="0" fill="rgb(119, 119, 119)" d="M0.000,0.000 L20.000,0.000 L20.000,20.000 L0.000,20.000 L0.000,0.000 Z"></path>
                                                                <path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M16.000,13.000 L16.000,16.999 L14.000,16.999 L14.000,13.000 L10.000,13.000 L10.000,11.000 L14.000,11.000 L14.000,7.000 L16.000,7.000 L16.000,11.000 L20.000,11.000 L20.000,13.000 L16.000,13.000 ZM-0.000,7.000 L12.000,7.000 L12.000,9.000 L-0.000,9.000 L-0.000,7.000 ZM-0.000,3.000 L12.000,3.000 L12.000,5.000 L-0.000,5.000 L-0.000,3.000 ZM8.000,13.000 L-0.000,13.000 L-0.000,11.000 L8.000,11.000 L8.000,13.000 Z"></path>
                                                                </svg><span>Add To Playlist</span>
                                                            </a>';
                                                      // onclick="$(\'#myModal\').modal(\'show\'); $(\'#myCommonModal\').modal(\'hide\');"
                                                $html .='</div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="castncrew" >
                                                    <div class="dis_cast_div muli_font">
                                                        <ul id="castandcrewhtml" class="dis_CastCrewList">
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="detailDescription">
                                                    <div class="dis_tabdata appendData2">
                                                        <p>'.$data['description'].'</p>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="episodePlaylist">
                                                    <div class="dis_cast_div muli_font">
                                                        <ul id="episodeHtml" class="dis_CastCrewList">
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- speaker button start -->
                            <a class="speaker '. $mute .'" data-video="play_sneak_peak">
                                <span></span>
                            </a>
                        </div>';

                    echo $html;
            }
        }
    }


    public function playlist_popup($post_id=''){
        $html = '';
        $playlist = '';
        $hidemeCls ='';
        $btnDone = 'hideme';
        if(!empty($post_id)){

            $data = $this->getMyPlaylist($post_id);

            if(!empty($data)){
                $hidemeCls = 'hideme';
                $btnDone   = '';
                foreach($data as $list){
                    $playlist .='<li>
                                    <div class="checkbox dis_checkbox">
                                        <label>
                                            <input type="checkbox" value="'.$list['playlist_id'].'" class="check check_vid_count" name="playlist_ids[]"'.$list['checked'].' data-video_count="'.$list['video_ids_count'].'">
                                            <i class="input-helper"></i>
                                            <p>'.$list['title'].' ('.$list['video_ids_count'].')</p>
                                        </label>
                                        <span class="form-error help-block"></span>
                                    </div>
                                </li>';
                }
            }

            $html = '<div class="modal-body mp_0">
                        <div class="">
                            <div class="dis_ap_modal_box">
                                <div class="upl_dd_header">
                                    <h2 class="upl_dd_head_ttl">Playlist</h2>
                                    <h2 class="upl_dd_head_new newpl_click" data-id="NewPlaylistComm">Create New PlayList</h2>
                                    <input type="hidden" id="playlist_post_id" value="'.$post_id.'">
                                </div>
                                <ul class="upl_dd '.$btnDone.'" id="PlayListAreaComm">
                                '.$playlist.'
                                </ul>
                                <div class="upl_dd_middle '.$hidemeCls.'" id="ShowPlayListFormComm">
                                    <div class="upl_dd_newpl_inner">
                                        <div class="upl_dd_pl_filed">
                                            <textarea placeholder="Enter Playlist Title" class="upl_dd_pl_inner" id="playlistTitleComm" maxlength="50"></textarea>
                                        </div>
                                        <div class="upl_dd_pl_filed">
                                            <select class="upl_dd_pl_inner" id="PlayListStatusComm">
                                                <option value="5">Only Me</option>
                                                <option value="6">Private</option>
                                                <option selected="selected" value="7">Public</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upl_dd_footer">
                                <div class="upl_dd_foot_inner center '.$btnDone.'">
                                    <a href="javascript:;" class="upl_dd_foot_btn" id="submitAddToPlaylist">Done</a>
                                </div>
                                <div class="upl_dd_foot_inner '.$hidemeCls.'">
                                    <h2 class="upl_dd_foot_new newpl_click" data-id="CancelPlaylistComm">Cancel</h2>
                                    <a href="javascript:;" class="dis_playlistuniq upl_dd_foot_btn createNewPlaylistComm" data-page="singlepage">Create</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>';
        }else{

            $html = '<div class="modal-body mp_0">
                        <div class="">
                            <div class="dis_ap_modal_box">
                                <div class="upl_dd_header">
                                    <h2 class="upl_dd_head_ttl">Playlist</h2>
                                    <input type="hidden" id="playlist_post_id">
                                </div>
                                <div class="upl_dd_middle '.$hidemeCls.'" id="ShowPlayListFormComm">
                                    <div class="upl_dd_newpl_inner">
                                        <div class="upl_dd_pl_filed">
                                            <textarea placeholder="Enter Playlist Title" class="upl_dd_pl_inner" id="playlistTitleComm" maxlength="50"></textarea>
                                        </div>
                                        <div class="upl_dd_pl_filed">
                                            <select class="upl_dd_pl_inner" id="PlayListStatusComm">
                                                <option value="5">Only Me</option>
                                                <option value="6">Private</option>
                                                <option selected="selected" value="7">Public</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upl_dd_footer">
                                <div class="upl_dd_foot_inner">
                                    <a href="javascript:;" class="dis_playlistuniq upl_dd_foot_btn createNewPlaylistComm" data-page="playlist">Create</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>';

        }

       echo $html;

    }


    public function cast_crew_popup($cpost_id='', $cast_id=''){
        $html='';
        if(is_login()){
            $cast_name       = '';
            $cast_script     = '';
            $cast_profession = '';
            $action          = 'dashboard/AddCast';
            $heading         = 'ADD CAST/CREW';
            $btnTitle        = 'Add cast/Crew';
            $imgUploadTitle  = 'Add Profile Image';
            $option = '';
            if(!empty($cast_id)){
                $join  				= 	['multiple',[
                    ['users' , 'users.user_id = channel_cast_images.cast_user_id','left']
                ]];
                $cond 			= 	['cast_image_id' => $cast_id];

                $cast_crew 		= 	$this->DatabaseModel->select_data('channel_cast_images.*,users.user_name','channel_cast_images use INDEX(cast_image_id)',$cond,'',$join);

                if(!empty($cast_crew)){

                    $action         = 'dashboard/updateCast';
                    $heading        = 'UPDATE CAST/CREW';
                    $btnTitle       = 'Update cast/Crew';
                    $imgUploadTitle = 'Update Profile Image';

                    $cpost_id        = $cast_crew[0]['post_id'];
                    $cast_id         = $cast_crew[0]['cast_image_id'];
                    $cast_name       = $cast_crew[0]['cast_real_name'];
                    $cast_script     = $cast_crew[0]['cast_script_name'];
                    $cast_profession = $cast_crew[0]['profession'];

                    if(!empty($cast_crew[0]['cast_user_id'])){
                        $option = '<option value="'.$cast_crew[0]['cast_user_id'].'">'.$cast_crew[0]['user_name'].'</option>';
                    }else{
                        $option = '<option value="'.$cast_name.'">'.$cast_name.'</option>';
                    }
                }
            }
            if(!empty($cpost_id)){
                $html ='<div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 id="addupdate">'.$heading.'</h3>
                            <div class="dis_cast_upload_div dis_select_video">
                                <form action="'.$action.'" class="castform" method="post" id="castform" >
                                    <input type="hidden" name="post_id" id="cpost_id"  value="'.$cpost_id.'">
                                    <input type="hidden" name="cast_id" id="cast_id"  value="'.$cast_id.'">
                                    <div class="dis_CastCrew_DW">
                                        <div class="dis_upload_div">
                                            <input type="file" id="cast_file" name="userfile" class="inputfile" accept="image/*">
                                            <label for="cast_file">
                                                <figure>
                                                    <svg xmlns="https://www.w3.org/2000/svg" width="24px" height="23px"><path fill-rule="evenodd"  fill="rgb(117, 117, 117)" d="M22.135,9.760 L13.823,9.760 L13.823,1.795 C13.823,1.293 13.651,0.869 13.307,0.522 C12.963,0.177 12.528,0.004 12.004,0.004 C11.479,0.004 11.045,0.177 10.701,0.522 C10.356,0.869 10.184,1.293 10.184,1.795 L10.184,9.760 L1.873,9.760 C1.348,9.760 0.905,9.925 0.545,10.255 C0.184,10.585 0.004,11.002 0.004,11.504 C0.004,11.975 0.184,12.384 0.545,12.729 C0.905,13.075 1.348,13.248 1.873,13.248 L10.184,13.248 L10.184,21.213 C10.184,21.716 10.356,22.140 10.701,22.485 C11.045,22.831 11.479,23.004 12.004,23.004 C12.528,23.004 12.963,22.831 13.307,22.485 C13.651,22.140 13.823,21.716 13.823,21.213 L13.823,13.248 L22.135,13.248 C22.659,13.248 23.101,13.083 23.463,12.753 C23.823,12.423 24.004,12.007 24.004,11.504 C24.004,11.002 23.823,10.585 23.463,10.255 C23.101,9.925 22.659,9.760 22.135,9.760 L22.135,9.760 Z"/></svg>
                                                </figure>
                                                <span>Add Profile Image</span>
                                            </label>
                                        </div>
                                        <div class="dis_add_cast_form">
                                            <div class="dis_AcastCrew_MList">
                                                <ul class="dis_AcastCrew_List">
                                                    <li>
                                                        <div class="dis_field_box">
                                                            <label class="dis_field_label">Select Users</label>
                                                            <div class="dis_field_wrap dis_select2">
                                                                <select class="dis_field_input form-control js-data-ajax require" data-ajax--url="'.base_url().'dashboard/usersWhoFollowedMe" data-placeholder="Select User" name="cast_user_id" data-select2-id="1" tabindex="-1" aria-hidden="true" data-error="User name is required.">
                                                                '.$option.'
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="dis_field_box">
                                                            <label class="dis_field_label">Script Name</label>
                                                            <div class="dis_field_wrap">
                                                                <input type="text" class="dis_field_input form-control require" placeholder="Script Name" name="cast_script_name" value="'.$cast_script.'" maxlength="20" data-error="Script name is required.">
                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 m_t_20">
                                            <button type="submit" class="dis_btn dis_host_dev">'.$btnTitle.'<!--span class="publish_btn">
                                            <i class="fa fa-spinner fa-pulse fa-fw"></i></span--></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script>intializeSelect2AutoComplete()</script>
                        ';
            }
            echo $html;
        }

    }


    public function upload_profile_picture_popup(){

        $html ='<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload profile picture</h4>
                </div>
                <div class="modal-body">
                    <div class="upload_img_wrapper">
                        <div class="browse_area browse_area_wrapper">
                            <div class="browse_area_inner">
                                <div class="browse_btn_wrapper">
                                    <p id="upload_err" class="help_note"></p>

                                        <div class="img-container">
                                        <input type="file" id="imgInp" class=""/>
                                        <img id="ProImage" src="" style="width:45%">
                                    </div>
                                    <label for="imgInp" class="browse_btn_label">
                                        <img src="'.CDN_BASE_URL.'repo/images/video_upload.png">
                                        <p class="info_text"> Click To Browse Or Drag & Drop Your Profile Picture Here</p>
                                    </label>
                                    <div id="preview-template" style="display: none;"></div>

                                </div>
                            </div>
                            <p class="help_note">The Best Image Size Is 246x246 (WxH)*</p>
                        </div>
                        <button class="dis_btn" id="cropit" onclick="crop()" disabled>Upload <span class="hideme publish_pro"> <i class="fa fa-spinner fa-pulse fa-fw"></i></span></button>
                    </div>
                </div>';
        echo $html;
    }


    public function upload_cover_video_popup(){
        $html ='<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload social video</h4>
                </div>
                <div class="modal-body">
                    <div class="upload_img_wrapper">
                    <p id="upload_video_err" class="help_note help_error"></p>
                        <div class="browse_area">
                            <div class="browse_area_inner">
                                <div class="browse_btn_wrapper">
                                    <div class="">
                                    <input type="file" id="pro_video_upload" name="userfile" class="inputfile" data-id="pro_video_upload">
                                        <label for="pro_video_upload" class="browse_btn_label">
                                        <i class="fa-solid fa-video"></i>
                                        <p class="info_text">Drop a video here or Click to browse</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="montz_videouplod_wrap _progress_bar hide">
                                    <div class="montz_progress">
                                        <div class="monyz_prog_fill _progress_percent">
                                            0%
                                        </div>
                                    </div>
                                    <div class="montz_vid_name">
                                        <span class="montz_vid_ttl _progress_title">file name .mp4</span>
                                    </div>
                                    <div class="montz_vid_cncl">
                                        <a class="dis_btn b_btn _process_abort">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <p class="help_note"><strong>NOTE:</strong>Video should be in MP4 or MOV format.</p>
                        </div>
                    </div>
                </div>';
        echo $html;
    }


    function get_violations_category($parent_id='', $type=''){
		$this->load->library('manage_session');
		$uid = is_login();
		$resp = array();
		if(!empty($type)){

            $cond ="parent_id = {$parent_id} AND status = 1 AND type = '{$type}'";

            if($parent_id == 0){

                $cond .=" AND (SELECT COUNT(viol_id) AS viol_id from violations_category c2 where c2.parent_id = c1.viol_id) > 0";
            }

            $resp = $this->DatabaseModel->select_data('viol_id,violations_title,parent_id','violations_category c1',$cond);
        }

        return  $resp;
	}


    public function report_content_popup($parent_id='', $type='', $heading=''){
        $html = '';

        if(!empty($type)){

            $cate = $this->get_violations_category($parent_id, $type);

            if(!empty($cate)){

                $backBtnCls = ($parent_id == 0) ? 'hide' : '';

                $cateHtml ="";
                foreach($cate as $element){
                    $cls ='class="raise_flag_report"';
                    if($parent_id == 0){
                        $cls = 'class="raise_flag_report openModalPopup" data-href="modal/report_content_popup/'.$element['viol_id'].'/'.$type.'/'.str_replace(' ','-',$heading).'" data-cls="dis_Reporting_modal dis_center_modal"';
                    }
                    $cateHtml .='<li '.$cls.' data-type="'.$type.'" data-viol_id="'.$element['viol_id'].'" data-parent_id="'.$element['parent_id'].'">
                                    <div class="dis_checkbox">
                                        <label for="flags_'.$element['viol_id'].'">
                                            <input id="flags_'.$element['viol_id'].'" type="radio" value="'.$element['viol_id'].'" class="check" name="viol_id" />
                                            <i class="input-helper"></i>
                                            <p>'.$element['violations_title'].'</p>
                                        </label>
                                    </div>
                                </li>';
                }

                $html ='<div class="modal-body mp_0">
                            <div class="muli_font">
                                <h2 class="dis_bmodalTll mp_0">'.str_replace('-',' ', $heading).' ?</h2>
                            </div>
                            <div class="muli_font">
                                <ul class="dis_reportingList" id="violation_cate">
                                    '.$cateHtml.'
                                </ul>

                                <!--ul class="dis_reportingList">
                                    <li>
                                        <div class="dis_checkbox">
                                            <label>
                                                <input type="radio" value="" class="check" name="mySource" >
                                                <i class="input-helper"></i>
                                                <p>Search Engine (Google Yahoo etc.)</p>
                                            </label>
                                        </div>
                                    </li>

                                </ul-->
                            </div>
                            <div class="dis_reportingmsg m_t_20 hide">
                                <div class="dis_field_box">
                                    <label class="dis_field_label">Other Message (optional)</label>
                                    <div class="dis_field_wrap">
                                        <textarea type="text" name="message" class="dis_field_input require" id="viol_msg" placeholder="Enter Other Message Here"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="dis_reportingFooter">
                                <ul class="dis_discoveredUs_btn">
                                    <li class="backFlagBtn '.$backBtnCls.'">
                                        <a href="javascript:;" class="dis_btn raise_flag_report openModalPopup" data-href="modal/report_content_popup/0/'.$type.'/'.str_replace(' ','-',$heading).'" data-cls="dis_Reporting_modal dis_center_modal"  data-viol_id="0" data-parent_id="0" data-type="" id="SkipFlagPopup">back</a>
                                    </li>
                                    <li class="submitFlagBtn hide">
                                        <a href="javascript:;" id="SubmitFlagPopup" class="dis_btn">Submit</a>
                                    </li>
                                </ul>
                            </div>
                    </div>
                    <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>';
            }
        }
        echo $html;
    }


    public function generate_link($target = ''){
        $_POST['taget'] = $target;
		if(isset($_POST['taget']) && !empty($_POST['taget'])){
			$shareData = explode('|' , $_POST['taget']);
			if(count($shareData) >= 2){
				$list='';
				if(isset($shareData[2]) && !empty($shareData[2])){
					$_POST['taget'] = $shareData[0].'|'.$shareData[1];
					$list =  array('list'=>$shareData[2]);
				}
				$link = $this->share_url_encryption->share_single_page_link_creator($_POST['taget'] , 'encode','',$list);

				$sharetext = '';
				$description = '';
				$media = '';
				if($shareData[0] == 2){ //check video post
					$post_id = $shareData[1];
					$checkVideoData = $this->DatabaseModel->select_data('title,description','channel_post_video',array('post_id' => $shareData[1]) , 1);
					if(!empty($checkVideoData)){
						$sharetext = urlencode($checkVideoData[0]['title']);
						$description = urlencode(strip_tags(json_decode($checkVideoData[0]['description'])));
					}
					$thumb = $this->DatabaseModel->select_data('user_id,image_name','channel_post_thumb',array('post_id' => $shareData[1]) , 1);
					if(isset($thumb[0]['user_id'])){
						$media = $this->share_url_encryption->FilterIva($thumb[0]['user_id'],'',$thumb[0]['image_name'],'',false)['thumb'];
					}
				}
				$link = urlencode($link);
				$resp = array('status' => 1 , 'link' =>  array(
								'main' 		=> urldecode($link),
								'facebook' 	=> 'https://www.facebook.com/sharer/sharer.php?u='.$link,
								'twitter' 	=> 'https://twitter.com/share?url='.$link.'&text='.$sharetext.'&via=Discovered.TV&hashtags=Discovered.TV',
								'pinterest' => 'https://pinterest.com/pin/create/button/?url='.$link.'&media='.urlencode($media).'&description=Discovered.TV',
								'linkedin' 	=> 'https://www.linkedin.com/shareArticle?mini=true&url='.$link.'&title='.$sharetext.'&summary='.$description.'&source=Discovered.TV',
							)
						);

				$this->load->library('Gamification');
				$this->load->model('UserModel');

				$this->gamification->player_shared_video(
					first($this->UserModel->get($this->uid)),
					$post_id
				);
			}else{
				$resp = array('status' => 0);
			}
		}else{
			$resp = array('status' => 0);
		}
		return $resp;
	}


    public function share_content_popup($target = ''){
        $html = '';
        if(!empty($target)){

            $resp = $this->generate_link($target);

            if($resp['status']==1){

                $html ='<div class="dis_common_popup dis_sharepost_popup custom_scrol" data-modal="share">
                        <div class="common_popup_inner sharepost_inner">
                            <h4 class="popup_heading">share post</h4>
                            <ul class="share_social">
                                <li>
                                    <a data-share-post="embed">
                                        <div class="sharepost_icon">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 94.504 94.504" class="share_top">
                                            <g>
                                                <g>
                                                    <path d="M93.918,45.833L69.799,21.714c-0.75-0.75-2.077-0.75-2.827,0l-5.229,5.229c-0.781,0.781-0.781,2.047,0,2.828
                                                        l17.477,17.475L61.744,64.724c-0.781,0.781-0.781,2.047,0,2.828l5.229,5.229c0.375,0.375,0.884,0.587,1.414,0.587
                                                        c0.529,0,1.039-0.212,1.414-0.587l24.117-24.118C94.699,47.881,94.699,46.614,93.918,45.833z"/>
                                                    <path d="M32.759,64.724L15.285,47.248l17.477-17.475c0.375-0.375,0.586-0.883,0.586-1.414c0-0.53-0.21-1.039-0.586-1.414
                                                        l-5.229-5.229c-0.375-0.375-0.884-0.586-1.414-0.586c-0.53,0-1.039,0.211-1.414,0.586L0.585,45.833
                                                        c-0.781,0.781-0.781,2.047,0,2.829L24.704,72.78c0.375,0.375,0.884,0.587,1.414,0.587c0.53,0,1.039-0.212,1.414-0.587l5.229-5.229
                                                        C33.542,66.771,33.542,65.505,32.759,64.724z"/>
                                                    <path d="M60.967,13.6c-0.254-0.466-0.682-0.812-1.19-0.962l-4.239-1.251c-1.058-0.314-2.172,0.293-2.484,1.352L33.375,79.382
                                                        c-0.15,0.509-0.092,1.056,0.161,1.521c0.253,0.467,0.682,0.812,1.19,0.963l4.239,1.251c0.189,0.056,0.38,0.083,0.567,0.083
                                                        c0.863,0,1.66-0.564,1.917-1.435l19.679-66.644C61.278,14.612,61.221,14.065,60.967,13.6z"/>
                                                </g>
                                            </g>
                                            </svg>
                                        </div>
                                        <div class="sharepost_name">Embed</div>
                                    </a>
                                </li>
                                <li>';
                                if(is_login()){
                                        $html .='<a target="_blank" data-share-post="discovered">';
                                }else{
                                        $html .='<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl">';
                                }
                                        $html .='<div class="sharepost_icon discovered">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="30px" class="share_top">
                                            <path fill-rule="evenodd" fill="rgb(236, 88, 33)"
                                            d="M15.377,17.693 C15.377,20.566 14.274,22.002 12.068,22.002 C11.149,22.002 10.360,21.702 9.701,21.103 C9.042,20.504 8.713,19.786 8.713,18.949 C8.713,18.083 9.031,17.343 9.667,16.730 C10.302,16.117 11.064,15.810 11.953,15.810 C12.658,15.810 13.362,16.069 14.067,16.589 L14.067,7.669 C13.041,7.583 12.275,7.540 11.769,7.540 C8.583,7.540 5.825,8.651 3.497,10.874 C1.168,13.096 0.004,15.731 0.004,18.776 C0.004,21.922 1.176,24.578 3.520,26.743 C5.864,28.908 8.743,29.990 12.160,29.990 C13.875,29.990 15.549,29.636 17.181,28.929 C18.812,28.222 20.157,27.270 21.214,26.072 C23.052,23.993 23.971,21.302 23.971,17.996 L23.971,0.005 L15.377,0.005 L15.377,17.693 Z"/>
                                            </svg>
                                        </div>
                                        <div class="sharepost_name">Discovered</div>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" data-share-link="facebook" href="" >
                                        <div class="sharepost_icon facebook">
                                            <svg xmlns="https://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 430.113 430.114" class="share_top"><g><g>
                                                <path id="Facebook" d="M158.081,83.3c0,10.839,0,59.218,0,59.218h-43.385v72.412h43.385v215.183h89.122V214.936h59.805   c0,0,5.601-34.721,8.316-72.685c-7.784,0-67.784,0-67.784,0s0-42.127,0-49.511c0-7.4,9.717-17.354,19.321-17.354   c9.586,0,29.818,0,48.557,0c0-9.859,0-43.924,0-75.385c-25.016,0-53.476,0-66.021,0C155.878-0.004,158.081,72.48,158.081,83.3z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#FFFFFF"/>
                                            </g></g> </svg>
                                        </div>
                                        <div class="sharepost_name">Facebook</div>
                                    </a>
                                </li>
                                <li><a target="_blank" data-share-link="twitter" href="" >
                                        <div class="sharepost_icon twitter 1">
                                            <svg width="30px" height="30px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1226.37 1226.37" ><g><path d="M727.348 519.284 1174.075 0h-105.86L680.322 450.887 370.513 0H13.185l468.492 681.821L13.185 1226.37h105.866l409.625-476.152 327.181 476.152h357.328L727.322 519.284zM582.35 687.828l-47.468-67.894-377.686-540.24H319.8l304.797 435.991 47.468 67.894 396.2 566.721H905.661L582.35 687.854z" fill="#000000" opacity="1" data-original="#000000" class="" style="fill: #ffffff;"></path></g></svg>
                                        </div>
                                        <div class="sharepost_name">Twitter</div>
                                    </a>
                                </li>

                                <li>

                                    <a target="_blank" data-share-link="linkedin" href="" " rel="noopener">
                                        <div class="sharepost_icon linkedin">
                                            <?xml version="1.0"?>
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512.02 512.02" style="enable-background:new 0 0 512.02 512.02;" xml:space="preserve" width="32" height="32"><g><g transform="translate(0 -1)">
                                                <path d="M61.813,5.424c33.545,0,61.793,28.248,61.793,61.793c0,35.31-30.014,62.676-61.793,61.793   C29.151,129.893,0.02,102.527,0.02,67.217C-0.863,32.789,27.386,5.424,61.813,5.424" data-original="#007AAA" class="active-path" data-old_color="#007AAA" fill="#FFFFFF"/>
                                                <path d="M96.241,508.596H25.62c-9.71,0-17.655-7.945-17.655-17.655V181.976   c0-9.71,7.062-17.655,16.772-17.655h71.503c9.71,0,17.655,7.945,17.655,17.655v309.848   C113.896,500.651,105.951,508.596,96.241,508.596" data-original="#007AAA" class="active-path" data-old_color="#007AAA" fill="#FFFFFF"/>
                                                <path d="M511.137,272.017c0-65.324-48.552-116.524-113.876-116.524h-18.538   c-35.31,0-69.738,16.772-88.276,44.138c-6.179,6.179-8.828,8.828-8.828,8.828v-35.31c0-3.531-5.297-8.828-8.828-8.828h-88.276   c-3.531,0-8.828,3.531-8.828,7.945v329.269c0,3.531,5.297,7.062,8.828,7.062h97.103c3.531,0,8.828-3.531,8.828-7.062V310.858   c0-32.662,24.717-60.028,57.379-60.91c16.772,0,31.779,6.179,43.255,17.655c10.593,10.593,15.007,25.6,15.007,42.372v189.793   c0,3.531,5.297,8.828,8.828,8.828h88.276c3.531,0,8.828-5.297,8.828-8.828V272.017H511.137z" data-original="#007AAA" class="active-path" data-old_color="#007AAA" fill="#FFFFFF"/>
                                            </g></g> </svg>

                                        </div>
                                        <div class="sharepost_name">LinkedIn </div>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" data-share-link="pinterest" href="">
                                        <div class="sharepost_icon pinterest">
                                            <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 310.05 310.05" width="30px" height="30px" class="share_top"><g><g id="XMLID_798_">
                                            <path id="XMLID_799_" d="M245.265,31.772C223.923,11.284,194.388,0,162.101,0c-49.32,0-79.654,20.217-96.416,37.176   c-20.658,20.9-32.504,48.651-32.504,76.139c0,34.513,14.436,61.003,38.611,70.858c1.623,0.665,3.256,1,4.857,1   c5.1,0,9.141-3.337,10.541-8.69c0.816-3.071,2.707-10.647,3.529-13.936c1.76-6.495,0.338-9.619-3.5-14.142   c-6.992-8.273-10.248-18.056-10.248-30.788c0-37.818,28.16-78.011,80.352-78.011c41.412,0,67.137,23.537,67.137,61.425   c0,23.909-5.15,46.051-14.504,62.35c-6.5,11.325-17.93,24.825-35.477,24.825c-7.588,0-14.404-3.117-18.705-8.551   c-4.063-5.137-5.402-11.773-3.768-18.689c1.846-7.814,4.363-15.965,6.799-23.845c4.443-14.392,8.643-27.985,8.643-38.83   c0-18.55-11.404-31.014-28.375-31.014c-21.568,0-38.465,21.906-38.465,49.871c0,13.715,3.645,23.973,5.295,27.912   c-2.717,11.512-18.865,79.953-21.928,92.859c-1.771,7.534-12.44,67.039,5.219,71.784c19.841,5.331,37.576-52.623,39.381-59.172   c1.463-5.326,6.582-25.465,9.719-37.845c9.578,9.226,25,15.463,40.006,15.463c28.289,0,53.73-12.73,71.637-35.843   c17.367-22.418,26.932-53.664,26.932-87.978C276.869,77.502,265.349,51.056,245.265,31.772z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#FFFFFF"/>
                                        </g></g> </svg>
                                        </div>
                                        <div class="sharepost_name">Pinterest</div>
                                    </a>
                                </li>
                            </ul>
                            <div class="sharepost_link">
                                <textarea class="link_input" id="copy_input" data-share-link="copy_input" readonly="true" rows="1"></textarea>
                                <div class="share_copybox" data-clipboard-target="#copy_input" id="copyContent">
                                    <div class="share_copyicon">
                                        <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 247.744 247.744" width="16px" height="16px" class=""><g><g>
                                            <path d="M206.89,0H74.824c-9.649,0-17.5,7.851-17.5,17.5v21.005h-16.47c-9.649,0-17.5,7.85-17.5,17.5   v174.239c0,9.649,7.851,17.5,17.5,17.5h132.065c9.649,0,17.5-7.851,17.5-17.5V209.24h16.471c9.649,0,17.5-7.851,17.5-17.5V17.5   C224.39,7.851,216.54,0,206.89,0z M175.419,230.244c0,1.355-1.145,2.5-2.5,2.5H40.854c-1.355,0-2.5-1.145-2.5-2.5V56.005   c0-1.356,1.145-2.5,2.5-2.5h132.065c1.355,0,2.5,1.144,2.5,2.5V230.244z M209.39,191.74c0,1.355-1.145,2.5-2.5,2.5h-16.471V56.005   c0-9.65-7.851-17.5-17.5-17.5H72.324V17.5c0-1.355,1.144-2.5,2.5-2.5H206.89c1.355,0,2.5,1.145,2.5,2.5V191.74z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
                                            <path d="M149.553,77.121H64.22c-4.143,0-7.5,3.357-7.5,7.5c0,4.143,3.357,7.5,7.5,7.5h85.333   c4.143,0,7.5-3.357,7.5-7.5C157.053,80.479,153.696,77.121,149.553,77.121z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
                                            <path d="M149.553,110.898H64.22c-4.143,0-7.5,3.357-7.5,7.5s3.357,7.5,7.5,7.5h85.333   c4.143,0,7.5-3.357,7.5-7.5S153.696,110.898,149.553,110.898z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
                                            <path d="M149.553,144.676H64.22c-4.143,0-7.5,3.357-7.5,7.5c0,4.143,3.357,7.5,7.5,7.5h85.333   c4.143,0,7.5-3.357,7.5-7.5C157.053,148.033,153.696,144.676,149.553,144.676z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
                                            <path d="M149.553,178.453H64.22c-4.143,0-7.5,3.357-7.5,7.5c0,4.143,3.357,7.5,7.5,7.5h85.333   c4.143,0,7.5-3.357,7.5-7.5C157.053,181.811,153.696,178.453,149.553,178.453z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
                                        </g></g> </svg>
                                    </div>
                                    <div class="share_copytext">
                                        <p>Copy</p>
                                    </div>
                                </div>
                            </div>';

                        if(is_login()){

                            $html .='<div class="Pro_search_wrrp hide">
                                <span class="ps_dissugst"></span>
                                <div class="ps_search_box">
                                    <a class="ps_icon">
                                        <svg xmlns="https://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 485.213 485.213"><g><g><g><path d="M471.882,407.567L360.567,296.243c-16.586,25.795-38.536,47.734-64.331,64.321l111.324,111.324    c17.772,17.768,46.587,17.768,64.321,0C489.654,454.149,489.654,425.334,471.882,407.567z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path>
                                        <path d="M363.909,181.955C363.909,81.473,282.44,0,181.956,0C81.474,0,0.001,81.473,0.001,181.955s81.473,181.951,181.955,181.951    C282.44,363.906,363.909,282.437,363.909,181.955z M181.956,318.416c-75.252,0-136.465-61.208-136.465-136.46    c0-75.252,61.213-136.465,136.465-136.465c75.25,0,136.468,61.213,136.468,136.465    C318.424,257.208,257.206,318.416,181.956,318.416z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path><path d="M75.817,181.955h30.322c0-41.803,34.014-75.814,75.816-75.814V75.816C123.438,75.816,75.817,123.437,75.817,181.955z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path></g></g></g>
                                    </svg>
                                    </a>
                                    <input type="text" class="form-control ps_search ad_search_filter" placeholder="Search People..." >

                                </div>
                                <div class="ps_result">
                                    <div class="custom_scrol ps_resultbox">
                                        <span class="ps_sugst">SUGGESTED</span>
                                        <ul class="ad_user_list">
                                            <?php echo $this->common_html->pro_loader(); ?>
                                        </ul>

                                    </div>
                                </div>
                            </div>';
                        }

                        $html .='<span class="common_close">
                                <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" width="11px" height="11px"><g><path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#EB581F"/></g> </svg>
                            </span>
                        </div>
                    </div>';
            }
        }

        echo $html;
    }


    public function browse_header_menu(){
        $website_mode			=	$homepage_url='';
        $uid 					= 	'';
        $user_uname 	   		= 	'';
	    $is_login				=   is_login();

	    if($is_login) {
		    $uid 				= 	$is_login;
            $cuser 				= 	get_user($uid);
            $user_uname 		=	(isset($cuser[0]['user_uname']) && !empty($cuser[0]['user_uname']))? $cuser[0]['user_uname'] : '';
	    }

        if(isset($_SESSION['website_mode']['name'])){
            $website_mode 		= $_SESSION['website_mode']['name'];
            $homepage_url 		= BASE_URL. $website_mode ;

            if($website_mode == 'social'){
                $homepage_url 	=  BASE_URL . 'profile?user=social' ;
            }
        }

        $col_one ='<li class="page_menu">
                        <a href="'.$homepage_url.'"  class="browseItem">home</a>';
                        if($is_login){
                            $col_one .='<a href="'.base_url('profile?user='.$user_uname).'" class="browseItem">SOCIAL PROFILE</a>';
                            $col_one .='<a href="'.base_url('channel?user='.$user_uname).'" class="browseItem"> MY CHANNEL</a>';
                            $col_one .='<a href="'.base_url('my-favorite?favorite='.$uid.'&hide=search|people').'" class="browseItem">MY FAVORITES</a>';
                        }else{
                            $col_one .='<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl" onclick="OpenRoute(\'\')" class="browseItem">SOCIAL PROFILE</a>';
                        }
        $col_one .='</li>';

        $col_second ='<li class="all_artist">
                            <a class="menu_title">discovered '.$website_mode.' <i class="fa fa-caret-down menu_downarrow" aria-hidden="true"></i></a>
                            <div class="half_wrapper_parent">
                                '.$this->getGenreMenu().'
                            </div>
                        </li>';


        $col_third ='<li class="all_icon">
                        <a class="menu_title"><?= PROJECT; ?> DIRECTORY <i class="fa fa-caret-down menu_downarrow" aria-hidden="true"></i></a>
                        <div class="half_wrapper_parent">
                            '.$this->getIconMenu().'
                        </div>
                    </li>';

        echo $html = $col_one.''.$col_second.''.$col_third;

    }

    function getGenreMenu(){
        $menu = '';
        $is_login				=   is_login();
        $browse_genre 			= 	$this->audition_functions->browse_genre();

        if(isset($browse_genre) && !empty($browse_genre)){
            $browse_genre 		= 	array_chunk($browse_genre,2);
            $size = sizeof($browse_genre);
            $i=1;
            foreach($browse_genre as $genres){
                foreach($genres as $genre){
                    $menu .='<div class="half_wrapper">
                                <a onclick="location.href = \''.base_url('genre?g='.$genre['genre_slug']).'\'" class="browseItem">'.strtolower($genre['genre_name']).'</a>
                            </div>';
                }
                if($size == $i){
                    $menu .='<div class="half_wrapper">
                                <a onclick="location.href = \''.base_url('genre?g=all').'\'" class="browseItem">View All</a>
                            </div>';
                }
                $i++;
            }
        }

        if(isset($_SESSION['website_mode']['name'])  && $_SESSION['website_mode']['name'] == 'live'){
            $menu = '';
            $videoTypes = array('is_live'=>'Live Now','recently_ended'=>'Recently Ended','scheduled_live_streams'=>'Scheduled Live Streams');
            foreach($videoTypes as $key=>$v){
                $key   = str_replace('_','-',$key);
                $href  = base_url('watch-all?v='.$key);
                $menu .='<div class="half_wrapper hideme" id="'.str_replace('-','_',$key).'"> <a href="'.$href.'" class="browseItem">'.$v.'</a></div>';
            }

            $web_mode = array_reverse($this->valuelist->mode(), true);
            foreach ($web_mode as $key => $v) {
                if(in_array($key ,[1,2,3,7])){
                    $href  = base_url('watch-all?v=mode&mode-id='.$key);
                    $menu .='<div class="half_wrapper hideme" id="live_'.$v.'_mode"> <a href="'.$href.'" class="browseItem">'.$v.'</a></div>';
                }
            }
        }

        if(isset($_SESSION['website_mode']['name'])  && $_SESSION['website_mode']['name'] == 'spotlight'){
            $menu = '';
            $header_menu 			= 	$this->audition_functions->get_header_menu();
            foreach($header_menu as $list){
                $url = base_url($list['mode']);
                $href = 'href="'.$url.'" ';
                if($list['mode_id'] == 4 ){/*social*/
                    if($is_login){
                        $href = 'href="'.base_url('profile?user='.$list['mode']).'" ';
                    }else{
                        $href = 'class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl" onclick="OpenRoute(\'profile?user=social\')"';
                    }
                }
                if($list['mode'] != 'spotlight')
                $menu .= '<div class="half_wrapper"><a '.$href.' class="browseItem">Discover '.$list['mode'].'</a></div>';
            }
        }

        return $menu;
    }

    function getIconMenu(){ //for redirect to videos
        $menu = '';
        $icon_sub_category 		= 	$this->audition_functions->get_sub_category_menu();

        if(isset($icon_sub_category) && !empty($icon_sub_category)){
            $icon_sub_category 	= 	array_chunk($icon_sub_category,2);
            $size = sizeof($icon_sub_category);
            $i=1;
            foreach($icon_sub_category as $sub_category){

                foreach($sub_category as $sub){
                    $menu .='<div class="half_wrapper">
                                <a onclick="location.href = \''.base_url().'icon?iv='.$sub['category_slug'].'\'" class="browseItem">'.$sub['category_name'].'</a>
                            </div>';
                }
                if($size == $i){
                    $menu .='<div class="half_wrapper">
                                <a onclick="location.href =\''.base_url('icon?iv=all') .'\'" class="browseItem">View All</a>
                            </div>';
                }
                $i++;
            }
        }
        return $menu;
    }

    function getIconMenuNew(){  //for redirect to profile
        $menu = '';
        $icon_sub_category 		= 	$this->audition_functions->get_sub_category_menu();

        if(isset($icon_sub_category) && !empty($icon_sub_category)){
            $icon_sub_category 	= 	array_chunk($icon_sub_category,2);
            $size = sizeof($icon_sub_category);
            $i=1;
            foreach($icon_sub_category as $sub_category){

                foreach($sub_category as $sub){
                    $menu .='<div class="half_wrapper">
                                <a onclick="location.href = \''.base_url().'search?search_query=&cat='.$sub['category_slug'].'&mode_id=4\'" class="browseItem">'.$sub['category_name'].'</a>
                            </div>';
                }
                if($size == $i){
                    $menu .='<div class="half_wrapper">
                                <a onclick="location.href =\''.base_url('search?search_query=&mode_id=4') .'\'" class="browseItem">View All</a>
                            </div>';
                }
                $i++;
            }
        }
        return $menu;
    }


    function maintenance(){
        return `
            <!-- This is only used when the site is under maintenance start, remove 'hide class' -->
            <!-- Trigger maintains modal with a button -->
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#maintenanceBtn" style=" position: fixed;bottom: 80px; left: 0;display:none;">Open Modal</button>

            <!-- maintains modal -->
            <div id="maintenanceBtn" class="maintenanceModal  text-center modal fade hide" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
                    <div class="dis_maintenanceModalInner">
                        <p class="main_heading">Notice of server downtime!</p>
                        <p class="main_h1">Please note that we will be experiencing server downtime on the <span class="primary_link">22nd March 2023, Wednesday from 11 PM PST to 2 AM PST.</span></p>
                        <p class="main_h2">Our website, mobile applications and CTV apps will not be available during this time.</p>
                        <p class="main_h2">We apologize in advance for any inconvenience.</p>
                    </div>
                    <div class="">
                    <button type="button" class="dis_btn" data-dismiss="modal">Close</button>
                </div>
                </div>
                </div>

            </div>
            </div>
            <!-- This is only used when the site is under maintenance end  -->
            `;
    }


}

?>