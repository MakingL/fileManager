<?php
require_once 'upload.class.php';
require_once 'config.php';

$upload_files = new uploadFile($path_now, MAXSIZE_UPLOAD);
$upload_files->uploadFile();
$upload_files->get_uploadStatus();

header('content-type:text/html;charset=utf-8');
// 跳转回页面
$url = "../upload.html";
Header("Location: ".$url);

?>
