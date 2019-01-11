<?php

require_once "config.php";

$res = array('name' => 'noFile_upload', 'status' => '无文件上传');
$js_res = json_encode($res);
$_SESSION['_feedback_upload'] =  $js_res;

// 跳转回页面
$url = "../upload.html";
Header("Location: ".$url);
