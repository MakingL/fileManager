<?php
 // 根目录
define('PATH_ROOT', "./file_download");
define("TEMP_FILES", "./file_download/temp_files");
define('PATH_ZIP_TEMP', TEMP_FILES.'/zip_temp');

// 上传的文件保存的路径
define("PATH_SAVE_UPLOAD", PATH_ROOT.'/'."files_upload");
define("MAXSIZE_UPLOAD", (200*1024*1024) );

if (!file_exists(PATH_ROOT)) {
    mkdir(PATH_ROOT, 0777, true);
}
if (!file_exists(TEMP_FILES)) {
    mkdir(TEMP_FILES, 0777, true);
}
if (!file_exists(PATH_ZIP_TEMP)) {
	mkdir(PATH_ZIP_TEMP, 0777, true);
}
if (!file_exists(PATH_SAVE_UPLOAD)) {
	mkdir(PATH_SAVE_UPLOAD, 0777, true);
}

// 数据库信息
/*
$SERVERNAME = "localhost";
$USER_DB = 'root';
$PASSWD_DB = '';
$DBNAME = "filemanager";
*/

// 用户当前目录
session_start();    //启动Session

if (empty($_SESSION['_path_now'])) {
	$_SESSION['_path_now'] =  PATH_ROOT;
}

if (empty($_SESSION['_feedback_upload'])) {
	$_SESSION['_feedback_upload'] =  "无文件上传";
}

$_feedback_uploadfile = $_SESSION['_feedback_upload'];

$path_now = $_SESSION['_path_now'];
