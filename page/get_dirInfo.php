<?php
require_once 'config.php';

function str_to_utf8 ($str) {

    if (mb_detect_encoding($str, 'UTF-8', true) === false) {
    	$str = utf8_encode($str);
    }

    return $str;
}

function put_filename_folder($folder) {

	// echo "folder :  $folder".'<br />';
	//检查要打开的是不是一个目录
	if (!is_dir($folder)) {
		return false;
	}

	 //打开目录
	$fp=opendir($folder);

	 //阅读目录
	while(false != ($file=readdir($fp)) ) {

		//列出所有文件并去掉'.'
    if ($file == '.' )  continue;
    if ($file == '..' && $GLOBALS["path_now"] == PATH_ROOT)  continue;

    $type_f = NULL;
    $path   = "$folder/$file";

    if (is_dir($path) )  {
    	$type_f = "folder";
    } elseif (is_file($path)) {
    	$type_f = "file";
    }

    if (isset($type_f)) {
	//$file = mb_convert_encoding($file, "utf-8", "gb2312");
	$res = array('type' => $type_f, 'name' => $file);

    	$file = str_to_utf8 ($file);

    	if (!($js_res = json_encode($res))) continue;	//过滤掉了中文的
    	// echo $js_res;
    	$arr_res[] = $js_res;
    }
	}

	//关闭目录
	closedir($fp);

	if (is_array($arr_res)) {
		if (($js_arry = json_encode($arr_res))) {
			echo $js_arry;
		}
	}

	return true;
}


header('content-type:text/html;charset=utf-8');
// echo "_path_now: ".$_path_now."<br />";
put_filename_folder($path_now);

// if ( empty($_GET['dir_now']) ) {

// 	put_filename_folder($_path_now);

// } elseif ( is_string($_GET['dir_now']) ) {

// 	// 改变当前工作路径
// 	$_path_now = $_path_now.'/'.$_GET['dir_now'];
// 	chdir($_path_now);
// 	put_filename_folder($_path_now);

// }
