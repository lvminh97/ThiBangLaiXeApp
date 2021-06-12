<?php
require_once "Controller.php";
class ActionController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function login($loginData){
        $loginResp = $this->accountObj->login($loginData['email'], _hash($loginData['password']));
        echo json_encode($loginResp);
    }

	public function updateInfoAction($data){
		$resp = $this->accountObj->updateInfo($data['uid'], $data['fullname']);
		echo json_encode($resp);
	}

	public function updatePasswordAction($data){
		$resp = $this->accountObj->changePass($data);
		echo json_encode($resp);
	}

    public function signupAction($data){
		$resp = $this->accountObj->signup(trim($data['fullname']), trim($data['email']), $data['password']);
		echo json_encode($resp);
    }

	public function getQuestion($data){
		$uid = $data['uid'];
		$pack = $data['pack'];
		$type = $data['type'];
		if($pack == "A1"){
			if($type == "luat"){
				$start = rand(0, 4) * 20;
				$limit = 20;
			}
			elseif($type == "bienbao"){
				$start = rand(0, 4) * 15;
				if($start == 60) $start = 50;
				$limit = 15;
			}
			else{
				$start = rand(0, 3) * 10;
				if($start == 30) $start = 25;
				$limit = 10;
			}
		}
		else if($pack == "A2"){
			if($type == "luat"){
				$start = rand(0, 9) * 20;
				if($start == 180) $start = 165;
				$limit = 20;
			}
			elseif($type == "bienbao"){
				$start = rand(0, 8) * 20;
				if($start == 160) $start = 155;
				$limit = 20;
			}
			else{
				$start = rand(0, 8) * 10;
				$limit = 10;
			}
		}
		else if($pack == "B1"){
			if($type == "luat"){
				$start = rand(0, 14) * 20;
				// if($start == 180) $start = 165;
				$limit = 20;
			}
			elseif($type == "bienbao"){
				$start = rand(0, 8) * 20;
				// if($start == 160) $start = 155;
				$limit = 20;
			}
			else{
				$start = rand(0, 6) * 20;
				$limit = 20;
			}
		}
		$start = rand(0, 4) * 20;
		$quesList = $this->quesObj->getListByPackAndType($pack, $type, $start, $limit);
		for($i = 0; $i < count($quesList); $i++){
			if(trim($quesList[$i]['ques_image']) != "") {
				$quesList[$i]['ques_image'] = "Resource/Images/{$quesList[$i]['ques_image']}";
			}
			else{
				$quesList[$i]['ques_image'] = "";
			} 			
		}
		echo json_encode($quesList);
	}

	public function getExam($data){
		$uid = $data['uid'];
		$exam = $data['exam'];
		$pack = $data['pack'];
		$quesList = $this->quesObj->getListByExam($pack, $exam);
		for($i = 0; $i < count($quesList); $i++){
			if(trim($quesList[$i]['ques_image']) != "") {
				$quesList[$i]['ques_image'] = "Resource/Images/{$quesList[$i]['ques_image']}";
			}
			else{
				$quesList[$i]['ques_image'] = "";
			} 			
		}
		echo json_encode($quesList);
	}

	public function submitAnswer($data){
		$uid = $data['uid'];
		$json = json_decode($data['answer']);
		$i = 0;
		$res = array();
		$total = 0;
		$correct = 0;
		$status = 0;
		foreach($json as $ans){
			$res[$i] = $this->quesObj->getItem($ans->ques_id);
			$res[$i]['your_answer'] = $ans->ans;
			if(trim($res[$i]['ques_image']) != "") {
				$res[$i]['ques_image'] = "Resource/Images/{$res[$i]['ques_image']}";
			}
			else{
				$res[$i]['ques_image'] = "";
			}
			if($res[$i]['your_answer'] == $res[$i]['correct_ans']) $correct++; 	
			else if($res[$i]['important'] == "1") $status = 2;
			$i++;
		}
		$total = $i;
		if($data['type'] == "thithu"){
			$pack = $data['pack'];
			if($status == 0 && ($pack == "A1" && $correct < 21 || $pack == "A2" && $correct < 23 || $pack == "B1" && $correct < 32)) $status = 1;
			$this->historyObj->addItem($uid, $data['exam'], $data['pack'], $correct, $total, $status, $data['answer']);
		}
		echo json_encode($res);
	}

	public function getHistoryList($data){
		$res = $this->historyObj->getList("uid='{$data['uid']}'", "time DESC");
		for($i = 0; $i < count($res); $i++){
			$res[$i]['wrong'] = "".($res[$i]['total'] - $res[$i]['correct']);
			$res[$i]['time'] = date_format(date_create($res[$i]['time']), "H:i:s d/m/Y");
		}
		echo json_encode($res);
	}

	public function getSignHistoryList($data){
		$res = $this->signObj->getList("uid='{$data['uid']}'", "time DESC");
		for($i = 0; $i < count($res); $i++){
			$res[$i]['datetime'] = date("H:i:s d/m/Y", $res[$i]['time']);
		}
		echo json_encode($res);
	}

	public function getSignHistory($data){
		$sign = $this->signObj->getItem($data['uid'], $data['time']);
		if($sign != "null"){
			$sign['img'] = "Resource/Sign/".$sign['image'];
			$sign['datetime'] = date("H:i:s d/m/Y", $sign['time']);
		}
		else{
			$sign = array();
		}
		echo json_encode($sign);
	}

	public function uploadAction(){
		getView("upload", null);
	}

	public function uploadDataAction($data){
		$pack = $data['pack'];
		$exam = $data['exam'];
		$quesData = json_decode($data['ques_data']);
		$db = new DB;
		// print_r($quesData);
		foreach($quesData as $ques){
			if(file_exists("./Resource/Images/".$ques->ques_image) === false)
				file_put_contents("./Resource/Images/".$ques->ques_image, file_get_contents($ques->ques_image_url));
			$db->insert("ques", array('id' => 'null',
										'ques' => $ques->ques,
										'ans1' => $ques->ans1,
										'ans2' => $ques->ans2,
										'ans3' => $ques->ans3,
										'ans4' => $ques->ans4,
										'correct_ans' => $ques->correct_ans,
										'important' => $ques->important,
										'ques_image' => $ques->ques_image,
										'detail' => $ques->detail,
										'type' => $ques->type,
										'pack' => $pack,
										'exam' => $exam));
		}
	}

	public function getQuesTest(){
		$pack = $_GET['pack'];
		$exam = $_GET['exam'];
		$db = new DB;
		$data = $db->select("ques", "*", "exam='$exam' AND pack='$pack'", "id");
		getView("get_ques_test", array('data' => $data));
	}
}
?>