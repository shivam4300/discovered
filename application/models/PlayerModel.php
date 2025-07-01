<?php

class PlayerModel extends UserModel {

	static public $tableName = 'users';
	static public $idColumn = 'playfab_id';
	static public $privateFields = ['user_password', 'user_firebase_token'];
	
}