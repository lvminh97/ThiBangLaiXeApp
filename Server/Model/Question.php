<?php
require_once "functions.php";
require_once "DB.php";

class Question extends DB{
	
	public function __construct(){
		parent::__construct();
	}

	private function genID(){
		$item_id = randString(25);
		$check_id = $this->getList("item_id='$item_id'");
		while(count($check_id) > 0){
			$item_id = randString(25);
			$check_id = $this->getList("item_id='$item_id'");
		}
		return $item_id;
	}

	public function getList($cond = "1", $order = ""){
		return $this->select("ques", "*", $cond, $order);
	}

	public function getListByPackAndType($pack, $type, $start, $limit){
		$tmp = $this->select("ques", "*", "pack='$pack' AND type='$type'", "id ASC LIMIT $start, $limit");
		return $tmp;
	}

	public function getListByExam($pack, $exam){
		$tmp = $this->select("ques", "*", "pack='$pack' AND exam='$exam'");
		return $tmp;
	}

	public function getItem($id){
		$tmp = $this->getList("id='$id'");
		if(count($tmp) == 1){
			return $tmp[0];
		}
		else{
			return "null";
		}
	}
}
?>