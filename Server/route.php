<?php
    require_once "Controller/Route.php";
    $route = new Route("ViewController@getIndex");
    // Signup
    $route->post("action", "signup", "ActionController@signupAction");
    // Login
    $route->post("action", "login", "ActionController@login");

    $route->post("action", "update_info", "ActionController@updateInfoAction");
    $route->post("action", "update_password", "ActionController@updatePasswordAction");

    $route->post("action", "get_ques", "ActionController@getQuestion");
    $route->post("action", "get_exam", "ActionController@getExam");
    $route->post("action", "submit_answer", "ActionController@submitAnswer");
    $route->post("action", "sign_detect", "ActionController@signDetectAction");
    
    $route->get("site", "test", "ActionController@testAction");
    $route->get("site", "upload", "ActionController@uploadAction");
    $route->post("action", "upload_data", "ActionController@uploadDataAction");
    // $route->get("site", "upload_b1", "ActionController@uploadB1Action");

    $route->post("action", "get_history_list", "ActionController@getHistoryList");
    $route->post("action", "get_sign_history_list", "ActionController@getSignHistoryList");
    $route->post("action", "get_sign_history", "ActionController@getSignHistory");

    $route->get("site", "get_ques_test", "ActionController@getQuesTest");

    $route->process();
?>