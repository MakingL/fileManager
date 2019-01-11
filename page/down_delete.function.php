<?php

header('content-type:text/html;charset=utf-8');
require_once 'config.php';
require_once 'do.zip.php';

function get_basename($filename){
		// basename 不支持中文名
     return preg_replace('/^.+[\\\\\\/]/', '', $filename);
 }

/**
 * 下载文件 用绝对路径
 * @return boolean
 */
function download_file($filename) {
	if (file_exists($filename)) {
		// basename 不支持中文名
		// $name_file = basename( $filename );

		header ( 'Content-Description: File Transfer' );
		header ( 'Content-Type: application/octet-stream' );
		header ( 'Content-Disposition: attachment; filename='.get_basename( $filename ));
		// header ( "Content-Disposition: attachment; filename=$filename; filename*=utf-8''$filename");

		header ( 'Content-Transfer-Encoding: binary' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate' );
		header ( 'Pragma: public' );
		header ( 'Content-Length: '  .  filesize ( $filename ));
		ob_clean ();
		flush ();
		readfile ( $filename );
		return readfile($filename);
	}

	return false;
}

// 递归删除目录
function deleteDir_r($dir) {
	if (is_dir($dir)) {
		if(@rmdir($dir) == false) {

			if ($dp = opendir($dir)) {

				while (($file=readdir($dp)) != false) {

					if ($file =='.' or $file =='..')  continue;

					$path_file = "{$dir}/{$file}";

					if (is_dir($path_file) ) {
						deleteDir_r($path_file);
					} else {
						unlink($path_file);
					}
				}

			    closedir($dp);
			} else {
			    exit('Have not permission');
			}

			// 删除最外层
			rmdir($dir);
		}
	}
}

// 执行删除和下载操作
function do_command($command, $folders, $files) {
	// 所有路径都应该用绝对路径

	if (isset($command)) {
		if ($command == "delete") {
			// echo "删除文件： ";
			if(!empty($files))	{
				foreach ($files as $key => $file) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}

			if (!empty($folders)) {
				foreach ($folders as $key => $folder) {
					if (file_exists($folder)) {
						deleteDir_r($folder);
					}
				}
			}

		} elseif ($command == "download") {
			echo "下载文件\n";
			if (empty($folders) and sizeof($files) == 1 ) {
				// 下载单个文件
				download_file($files[0]);
				return;
			} elseif (empty($files) and sizeof($folders) == 1) {
				// 下载单个文件夹
				// $file_name = basename($folders[0]);
				$file_name = get_basename($folders[0]);

				toZip($folders[0], $file_name.'.zip', FOLDER_MODE, PHPZip::DOWNLOAD);
				return;
			}

			if (!empty($folders)) {
				// 将各个要下载的文件夹压缩打包 放在临时文件夹中
				foreach ($folders as $key => $folder) {

					if (!file_exists($folder)) {
						continue;
					}

					// 绝对路径
					$zip_file_name = PATH_ZIP_TEMP.'/'.get_basename ( $folder ).'.zip';
					$folder_zips[] = $zip_file_name;
					$files[] = $zip_file_name;

					toZip($folder, $zip_file_name, FOLDER_MODE, PHPZip::SAVE);
				}
			}

			if (!empty($files)) {

				// 将所有文件打包下载
				toZip($files, PATH_ZIP_TEMP.'/files_download.zip', FILE_MODE, PHPZip::DOWNLOAD);
			}

			if (empty($folder_zips)) return;

			foreach ($folder_zips as $key => $folder_zip) {
				unlink($folder_zip);
			}
		}
		return;
	}
}
