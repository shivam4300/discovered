<?php

class Account extends MY_Controller {

	private $userFields = [
		"mailing_address" => "user_address",
		"phone" => "user_phone",
		"profile_url" => "user_uname",
	];

	private $userContentFields = [
		"country" => "uc_country",
		"state" => "uc_state",
		"city" => "uc_city",
		"zip" => "uc_zipcode",
		"gender" => "uc_gender",
		"mailing_address" => "uc_addr1",
		"date_of_birth" => "uc_dob",
		"reference_name" => "uc_name",
		"reference_phone_number" => "uc_phone",
		"reference_email" => "uc_email",
	];

	private $statsFields = [
		"interest_music",
		"interest_movies",
		"interest_television",
		"interest_gaming",
		"interest_articles",
		"interest_live_events",
	];

	public function __construct() {
		parent::__construct();
		$this->load->library(['PlayFabXR', 'PlayFab', 'creator_jwt', 'audition_functions']);
		$this->load->library([
			'exceptions/UnauthenticatedException', 
			'exceptions/ObjectNotFoundException',
			'exceptions/SignupAlreadyCompletedException',
			'exceptions/InvalidParameterValueException',
		]);
		$this->load->helper(['iter', 'aws_s3_action', 'playfab', 'file']);
		$this->load->model('UserModel');
		$this->load->model('UserContentModel');
		$this->load->model('LevelModel');
		$this->load->model('ArtistCategoryModel');
		$this->load->model('PublishDataModel');
	}

	public function isUsernameAvailable($username) {
		$available = !len($this->UserModel->get(["user_uname" => $username]));
		$this->show_my_response(["available" => $available]);
	}

	public function _createUploadsFolders($uid) {
		$paths = [
			'./uploads',
			'./uploads/aud_'.$uid,
			'./uploads/aud_'.$uid.'/images',
			'./uploads/aud_'.$uid.'/videos',
		];
		foreach ($paths as $path) {
			if (!file_exists($path)) {
				mkdir($path);
			}
		}
		$this->UserModel->update(['user_dir' => 1], $uid);
	}

	public function _uploadProfilePicture($uid, $picture) {
		$pathToImages = ABS_PATH . "uploads/aud_$uid/images/";
		$imageName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);	
		$dst = $pathToImages . $imageName;
		$this->_createUploadsFolders($uid);
		$dst = save_base64_image($picture, $dst);
		$parts = explode('/', $dst);
		$imgFileName = end($parts);
		$this->load->library('image_autorotate', array('filepath' => $dst));
		$this->audition_functions->resizeImage('246', '246', $dst, '', $maintain_ratio=false, $create_thumb=true);
		upload_all_images($uid);
		$this->UserContentModel->save(['uc_pic' => $imgFileName, 'uc_userid' => $uid], ['uc_userid' => $uid]);
		$this->PublishDataModel->insert([
			'pub_uid'		=>	$uid,
			'pub_reason'	=>	1,
			'pub_media'		=>	$imgFileName.'|image',
			'pub_status'	=>	7,
			'pub_date'		=>	date('Y-m-d H:i:s')
		]);
	}

	public function upgrade() {
		$uid = $this->creator_jwt->require_user_id();
		$user = $this->UserModel->require($uid);
		$input = json_decode(file_get_contents("php://input"));

		// Validate 'user_uname'
		$user_uname = get($input, 'profile_url');
		if (!preg_match('/^[A-z0-9\-_\.]*$/', $user_uname)) {
			throw new InvalidParameterValueException('user_uname', $user_uname, '/^[A-z0-9\-_\.]*$/');
		}

		// Upload profile picture if provided.
		$picture = get($input, 'profile_picture');
		if ($picture) {
			$this->_uploadProfilePicture($uid, $picture);
		} 

		// Save User
		$update = zipluck($input, $this->userFields); 
		$brandLevel = get($input, 'brand_level');
		if ($brandLevel) {
			$levels = explode(',', get($user, 'user_cate'));
			$levels[] = $brandLevel;
			$update['user_cate'] = implode(',', array_unique($levels));
		}
		$accountType = get($input, 'account_type');
		$user->sigup_acc_type = 'standard';
		$update['sigup_acc_type'] = 'standard';
		if ($accountType) {
			$categories = key_by($this->ArtistCategoryModel->get(['level' => 1]), 'category_slug');
			$update['user_level'] = $categories[$accountType]->category_id ?? 0;
			$_SESSION['account_type'] = $update['user_level'];
			$_SESSION['sigup_acc_type'] = $user->sigup_acc_type;
			$_SESSION['user_uname'] = $update['user_uname'];
		}
		$this->UserModel->update($update, $uid);
		
		// Save UserContent
		$update = zipluck($input, $this->userContentFields);
		$types = not_in(pluck($input, ["icon_type", "emerging_type", "brand_type"]), [null, []]);
		if ($types) {
			$update["uc_type"] = implode(',', first($types));
			$_SESSION['primary_type'] = $update["uc_type"];
		}
		if ($update) {
			$update['uc_userid'] = $uid;
			$this->UserContentModel->save($update, ['uc_userid' => $uid]);
		}

		// Link & Sync PlayFab account
		$user->playfab_id = getPlayFabId($uid, true, false);  // Sync is set to false so we don't sync twice.
		$user = first($this->UserModel->get($uid));  // Need to fetch the latest version
		syncPlayFabAccount($user);  // Sync manually here because we want to sync everytime (not only when the link happens).

		// Save interests in PlayFabXR
		$update = pluck(get($input, 'interests', []), $this->statsFields);
		$update = not_equal($update, null);  // Do not update unspecified interests.
		if ($update) {
			PlayFabXR::server("SetPlayerStatistics", [
				"PlayFabId" => $user->playfab_id,
				"Statistics" => json_encode(PlayFabXR::arrayToStats($update)),
			]);
		}

		$this->creator_jwt->refresh_token();

		$this->show_my_response();
	}

	public function batch($key=null) {
		if (($key ?: get($_GET, 'key')) !== '863A1EEFdd2ADEFEa906') {
			return;
		}
		$limit = (int) get($_GET, 'limit', 1);
		$table = $this->UserModel->getTableName();
		$batch = $this->UserModel->db->query("SELECT * FROM `$table` WHERE playfab_id IS NULL AND is_deleted = 0 AND user_level < 4 ORDER BY user_id ASC LIMIT $limit")->result();
		$remaining = (int) first(values(first($this->db->query("SELECT COUNT(user_id) FROM `$table` WHERE playfab_id IS NULL AND is_deleted = 0 AND user_level < 4")->result())));
		$total = (int) first(values(first($this->db->query("SELECT COUNT(user_id) FROM `$table` WHERE is_deleted = 0 AND user_level < 4")->result())));
		$processed = $total - $remaining;
		foreach($batch as $user) {
			linkPlayFabAccount($user, true);
		}
		$this->show_my_response([
			'processed' => len($batch),
			'total_processed' => $processed + len($batch),
			'total_remaining' => $remaining - len($batch),
			'total' => $total,
			'progress' => ((int)($processed / $total * 10000) / 100) . ' %',
			'users_synced' => column($batch, 'user_id'), 
		]);
	}

}