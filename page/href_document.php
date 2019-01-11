<?php

header('content-type:text/html;charset=utf-8');

require_once 'config.php';
require_once 'down_delete.function.php';
require_once 'get_dirInfo.php';


$type = $_GET['type'];
$path_name = $_GET['name'];
// var_dump($type);
// var_dump($name);

if ($path_name != "..") {
	//路径改为绝对路径
	$path_name = "{$path_now}/{$path_name}";
}

// 判断参数的格式, 如果是文件夹，更新当前文件显示的目录, 否则下载
if ( !empty($type) ) {

	if ($path_name == ".." and $path_now != PATH_ROOT) {

		echo $path_now;
		$path_ch = dirname($path_now);
		$_SESSION['_path_now'] =  $path_ch;

	} else {
		if ($path_name != "..") {
			// 文件不存在，直接退出
			if (file_exists($path_name) ) {
				if ($type == 'file') {
					// 下载该文件
					download_file($path_name);

				} else if ($type == 'folder') {
					// 修改当前路径
					$_SESSION['_path_now'] =  $path_name;
				}
			}
		}

	}
}


// 跳转回页面
$url = "../index.php";
Header("Location: ".$url);

?>
