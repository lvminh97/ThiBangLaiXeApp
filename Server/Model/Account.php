<?php
require_once "functions.php";
require_once "DB.php";

class Account extends DB{

	public function __construct(){
		parent::__construct();
	}

	private function genID(){
		$account_id = randString(20);
		$check_id = $this->getList("uid='$account_id'");
		while(count($check_id) > 0){
			$account_id = randString(20);
			$check_id = $this->getList("uid='$account_id'");
		}
		return $account_id;
	}

	public function getList($cond = "1", $order = ""){
		$list = $this->select("account", "*", $cond, $order);
		return $list;
	}

	public function getItem($account_id){
		$tmp = $this->getList("uid='$account_id'");
		if(count($tmp) == 1) return $tmp[0];
		else return "null";
	}

	public function login($email, $password){
		$res = array('code' => '');
		$check = $this->getList("email='$email' AND password='$password'");
		if(count($check) == 1){
			$res['uid'] = $check[0]['uid'];
			$res['email'] = $check[0]['email'];
			$res['fullname'] = $check[0]['fullname'];
			$res['code'] = "LoginOK";
		}
		else{
			$res['uid'] = "null";
			$res['code'] = "LoginFail";
		}
		return $res;
	}

	public function logout(){
		sessionInit();
		unset($_SESSION['qcloud_user']);
		unset($_SESSION['qcloud_pass']);
		unset($_SESSION['qcloud_uid']);
	}

	public function checkLoggedIn(){
		sessionInit();
		if(!isset($_SESSION['qcloud_user']) || !isset($_SESSION['qcloud_pass'])) return "Role_None";
		$check = $this->getList("username='{$_SESSION['qcloud_user']}' AND password='{$_SESSION['qcloud_pass']}'");
		if(count($check) != 1){
			$this->logout();
			return "Role_None";
		}
		else{
			return "Role_User";
		}
	}

	public function signup($fullname, $email, $password){
		$resp = array();
		if($email == ""){
			$resp['code'] = "EmptyEmail";
			return $resp;
		}
		else{
			$tmp = explode("@", $email);
			if(count($tmp) != 2) {
				$resp['code'] = "WrongEmail";
				return $resp;
			} else{
				$tmp = explode(".", $tmp[1]);
				if(count($tmp) != 2) {
					$resp['code'] = "WrongEmail";
					return $resp;
				}
			}
		}
		$check = $this->getList("email='$email'");
		if(count($check) > 0){
			$resp['code'] = 'ExistEmail';
			return $resp;
		}
		if($fullname == ""){
			$resp['code'] = "EmptyFullname";
			return $resp;
		}
		if(strlen($password) < 8){
			$resp['code'] = "ShortPassword";
			return $resp;
		}
		$uid = $this->genID();
		$this->insert("account", array('uid' => $uid,
										'fullname' => $fullname,
										'email' => $email,
										'password' => _hash($password)));
		$resp['code'] = "SignupOK";
		$resp['uid'] = $uid;
		return $resp;
	}

	public function updateInfo($uid, $fullname){
		$resp = array();
		$this->update("account", array('fullname' => $fullname), "uid='$uid'");
		$resp['code'] = "UpdateInfoOK";
		$resp['fullname'] = $fullname;
		return $resp;
	}

	public function changePass($data){
		$uid = $data['uid'];
		$oldpass = _hash($data['oldpass']);
		$newpass = $data['newpass'];
		$newpass2 = $data['newpass2'];

		$resp = array();

		$check = $this->getList("uid='$uid' AND password='$oldpass'");
		if(count($check) != 1) {
			$resp['code'] = "WrongOldPass";
		}
		elseif(strlen($newpass) < 8){
			$resp['code'] = "ShortPassword";
		}
		elseif($newpass != $newpass2){
			$resp['code'] = "NewPassMismatch";
		}
		else{
			$this->update("account", array('password' => _hash($newpass)), "uid='$uid'");
			$resp['code'] = "UpdateOK";
		}
		return $resp;
	}

	public function checkUsername($username){
		return count($this->getList("username='$username'")) == 0;
	}

	public function checkPassword($password, $password2){
		if(strlen($password) < 8)
			return 1; // password is too short
		elseif($password != $password2)
			return 2; // password is mismatch
		else
			return 0; // OK
	}
}
?>