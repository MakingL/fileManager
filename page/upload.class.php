<?php
require_once 'config.php';

class uploadFile{
	protected $maxSize;
	protected $uploadPath;
	protected $fileInfo;
	public $error;
	// protected static $feedback = NULL;
	/**
	 * @param string $uploadPath 	文件保存的路径
	 * @param number $maxSize
	 */
	public function __construct($uploadPath=PATH_SAVE_UPLOAD, $maxSize = MAXSIZE_UPLOAD){
		$this->maxSize 		=	$maxSize;
		$this->uploadPath 	=	$uploadPath;

		$this->fileInfo     =	 uploadFile::getFiles_upload();
	}

	/**
	 * 构建上传文件信息
	 * @return mixed
	 */
	public static function getFiles_upload() {
	    // 没有文件上传
	    if (empty($_FILES) )  return NULL;

	    $i = 0;
	    foreach($_FILES as $file) {
	        //单文件或者多个单文件上传
	        if(is_string($file['name'])) {
	            $files[$i]['name'] = $file;
	            $i++;
	        } //多文件上传
	        elseif(is_array($file['name'])) {
	            foreach($file['name'] as $key=>$val) {
	                $files[$i]['name'] = $file['name'][$key];
	                $files[$i]['type'] = $file['type'][$key];
	                $files[$i]['tmp_name'] = $file['tmp_name'][$key];
	                $files[$i]['error'] = $file['error'][$key];
	                $files[$i]['size'] = $file['size'][$key];
	                $i++;
	            }
	        }
	    }
	    return $files;
	}

	/**
	 *获取错误信息
	 */
	protected function checkError(){
		if(!empty($this->fileInfo)){
			foreach ($this->fileInfo as $file) {
				if($file['error'] > 0){
					switch($file['error']){
						case 1:
							$this->error[$file['name']]='文件大小超过了服务器大小限制';
							break;
						case 2:
							$this->error[$file['name']]='超过了表单中MAX_FILE_SIZE设置的值';
							break;
						case 3:
							$this->error[$file['name']]='文件部分被上传';
							break;
						case 4:
							$this->error['noFile_upload']='没有选择上传文件';
							return false;			//无文件上传时应该终止及时后面的操作
							break;
						case 6:
							$this->error[$file['name']]='没有找到临时目录';
							break;
						case 7:
							$this->error[$file['name']]='文件不可写';
							break;
						case 8:
							$this->error[$file['name']]='由于PHP的扩展程序中断文件上传';
							break;

					}
				}
			}
			return true;
		}
		// else{
		// 	$this->error[] = '上传文件错误';
		// }
	}

	/**
	 * 检测上传文件的大小
	 */
	protected function checkSize(){
		if(!empty($this->fileInfo)){
			foreach ($this->fileInfo as $file) {
				if ($file['size'] > $this->maxSize) {
					$this->error[$file['name']] = '文件过大';
				}
			}
		}
	}

	/**
	 * 检测是否通过HTTP POST方式上传上来的
	 */
	protected function checkHTTPPost(){
		if(!empty($this->fileInfo)){
			foreach ($this->fileInfo as $file) {
				if(!is_uploaded_file($file['tmp_name'])){
					$this->error[$file['name']] = '文件不是通过POST方式上传的';
				}
			}
		}
	}

	/**
	 * 检测目录不存在则创建
	 */
	protected function checkUploadPath(){
		if(!file_exists($this->uploadPath)){
			mkdir($this->uploadPath, 0777, true);
		}
	}


	/**
	 * 上传文件
	 * @return string
	 */
	public function uploadFile(){

		$files =  $this->fileInfo;
		$this->checkUploadPath();
		if(!$this->checkError()) {
			// 无文件上传
			return;
		}
	/*	if (count( $files['name']) == 1 && $files['name'] == "") {
			// $errors['noFile_upload'] = '无文件上传';
			return;
		}*/
		$this->checkSize();
		$this->checkHTTPPost();

		$errors = $this->error;

		if(!empty($this->fileInfo)){
			foreach ($this->fileInfo as $file) {
				if ( !empty($errors[ $file['name']]) ) continue;

				$destination = $this->uploadPath.'/'.$file['name'];
				if(@move_uploaded_file($file['tmp_name'], $destination) == false){
					$errors[ $file['name']] = '文件移动失败';
				}
				// else {
				// 	//改变文件权限 --- 对文件夹才是必要的操作
				// 	chmod($destination, 777);
				// }
			}
		}
		/* else {
			$errors[] = "无文件上传";
		}*/
	}

	/*
		获取文件上传的状态
		输出到数据流
	*/
	public function get_uploadStatus() {

		$files =  $this->fileInfo;
		$errors = $this->error;

		// 无文件上传的情况
		if (!empty($errors['noFile_upload'])) {
			$res = array('name' => 'noFile_upload', 'status' => '无文件上传');

			if ($js_res = json_encode($res)) {
				$arr_status[] = $js_res;
			}
		} else {
			foreach ($files as $file) {
				if ( empty($errors[$file['name']]) ) {
					$status = "上传成功";
				} else {
					$status = $errors[$file['name']];
				}

				if (mb_detect_encoding($status, 'UTF-8', true) === false) {
			    	$status = utf8_encode($status);
			    }
				$res = array('name' => $file['name'], 'status' => $status);
				// echo $res['name']."上传状态: ".$res['status'].'<br />';
				$js_res = json_encode($res);
				// if (!($js_res = json_encode($res))) continue;
				$arr_status[] = $js_res;
			}
		}


		if (is_array($arr_status)) {

			// uploadFile::$feedback = $arr_status;

			// if (sizeof($arr_status) == 1) {
			// 	$_SESSION['_feedback_upload'] = $arr_status[0];
			// 	echo $arr_status[0];
			// }
			// var_dump($arr_status);
			if (($js_arry = json_encode($arr_status)) != false) {
				$_SESSION['_feedback_upload'] = $js_arry;
				echo $js_arry;
			}
		}
	}
}

// $upload_files = new uploadFile();
// $upload_files->uploadFile();
// $res = $upload_files->get_uploadStatus();
// echo $res;

// // 跳转回页面
// // $url = "upload.html";
// // Header("Location: ".$url);

?>
