<?php

class UserModel extends MY_Model {

	static public $tableName = 'users';
	static public $idColumn = 'user_id';
	static public $privateFields = ['user_password', 'user_firebase_token'];
	
}