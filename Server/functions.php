<?php
function setTimeZone(){
    date_default_timezone_set('asia/ho_chi_minh');
}

function sessionInit(){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function getView($view, $data = null){
    $viewParams = $data;
    require_once "View/$view.view.php";
}

function getTemplate($template, $data = null){
    $viewParams = $data;
    require_once "View/Template/$template.template.php";
}

function getModal($modal, $data = null){
    $viewParams = $data;
    require_once "View/Modal/$modal.modal.php";
}

function notice_and_nextpage($mess, $link){
    echo "<html><head>"
        ."<meta charset=\"UTF-8\">"
        ."<title>Thông báo</title></head>"
        ."<body>"
            ."<script>"
                ."alert(\"$mess\");"
                ."window.location=\"$link\";"
            ."</script>"
        ."</body>"
        ."</html>";
}

function notice($mess){
    echo "<script>alert(\"$mess\");</script>";
}

function nextpage($link){
    ob_start();
    header("location: $link");
    ob_flush();
}

function debug($var){
    echo "<script>console.log('$var');</script>";
}

function _hash($data){
    return sha1(sha1($data));
}

function randString($length){
    $chars="abcdefghijklmnopqrstuvwxyz0123456789";
    $str="";
    for($i = 0; $i < $length; $i++)
        $str .= $chars[rand(0,35)];
    return $str;
}

function echo_max_len($str, $len){
    if(strlen($str) <= $len){
        echo $str;
    }
    else {
        echo substr($str, 0, $len)."...";
    }
}

function cacheDisable(){
    header("Expires: on, 01 Jan 1970 00:00:00 GMT");
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

function pathSplit($path){
    return explode("/", $path);
}

function getItemName($path){
    $paths = pathSplit($path);
    return $paths[count($paths) - 1];
}

function getPreviousPath($path){
    $newPath = "";
    $paths = pathSplit($path);
    for($i = 0; $i < count($paths) - 1; $i++){
        if($newPath != "") $newPath .= "/";
        $newPath .= $paths[$i];
    }
    return $newPath;
}

function protectFolder($path){
    $file = fopen($path."/index.php", "w");
    fwrite($file, "You can not access this site!!!");
    fclose($file);
}
?>