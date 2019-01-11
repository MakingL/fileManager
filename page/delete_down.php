<?php
require_once 'config.php';
require_once 'do.zip.php';
require_once 'down_delete.function.php';

if (!empty($_POST['operation'])) {
	$data_request = $_POST['operation'];
} elseif (!empty($_GET['operation'])) {
	$js_data = json_decode($_GET['operation'], true);
	$data_request = $js_data['operation'];
}

$command = NULL;
$folders = NULL;
$files 	 = NULL;
// 解析前端发过来的JSON数据
foreach ($data_request as $key => $value) {
	$op = json_decode($value, true);
	if(!$op) continue;
	foreach ($op as $k => $v) {
		if ($k == "operate") {
			$command = $v;
		} elseif ($k == 'type') {
			// 解析JSON
			$Finfo = json_decode($value, true);
			if(!$Finfo) continue;

			// 注意将路径转换为绝对路径
			if ($Finfo['type'] == 'folder') {
				$folders[] = $path_now.'/'.$Finfo['name'];
			} elseif ($Finfo['type'] == 'file') {
				$files[] =  $path_now.'/'.$Finfo['name'];
			}
		}
	}
}

// header('content-type:text/html;charset=utf-8');
// 执行指令
do_command($command, $folders, $files);

// //
// // 刷新页面值
// put_filename_folder($path_now);
// // 跳转回页面
// $url = "./index.php";
// Header("Location: ".$url);


?>
