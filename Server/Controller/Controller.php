<?php
if(!defined('__CONTROLLER__')) define('__CONTROLLER__', 'true');
require_once "Model/Account.php";
require_once "Model/Question.php";
require_once "Model/History.php";
require_once "Model/Sign.php";

class Controller{
    protected $accountObj;
    protected $quesObj;
    protected $historyObj;
    protected $signObj;

    public function __construct(){
        $this->accountObj = new Account;
        $this->quesObj = new Question;
        $this->historyObj = new History;
        $this->signObj = new Sign;

        sessionInit();
        setTimeZone();
    }
}
?>