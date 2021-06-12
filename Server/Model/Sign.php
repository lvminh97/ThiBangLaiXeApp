<?php
require_once "functions.php";
require_once "DB.php";

class Sign extends DB{
	
	public function __construct(){
		parent::__construct();
	}

    public function getList($cond = "1", $order_by = ""){
        return $this->select("sign", "*", $cond, $order_by);
    }

    public function getItem($uid, $time){
        $tmp = $this->select("sign", "*", "uid='$uid' AND time='$time'");
        if(count($tmp) == 1) return $tmp[0];
        else return "null";
    }
}
?>