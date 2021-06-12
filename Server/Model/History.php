<?php
require_once "functions.php";
require_once "DB.php";

class History extends DB{
	
	public function __construct(){
		parent::__construct();
	}

    public function getList($cond = "1", $order_by = ""){
        return $this->select("history", "*", $cond, $order_by);
        // return $this->select("history", "uid, correct, total, time, pack, exam", $cond, $order_by);
    }

	public function addItem($uid, $exam, $pack, $correct, $total, $status, $answer_data){
        $this->insert("history", array('uid' => $uid,
                                        'exam' => $exam,
                                        'pack' => $pack,
                                        'time' => date("Y-m-d H:i:s"),
                                        'correct' => $correct,
                                        'total' => $total,
                                        'status' => $status,
                                        'answer_data' => $answer_data));
    }
}
?>