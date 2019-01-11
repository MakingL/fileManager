<?php
	require_once "config.php";
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>文件远程管理系统</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<link rel="stylesheet" type="text/css" href="index_stylesheet.css">
	<script type="text/javascript" src="jquery-3.2.1.min.js"></script>
	<script>
		// $(document).ready(function(){
		//   $("#upload").click(function(){
		//    	self.location='upload.html';
		//   });
		// });
		var checkbox_status = false;
		function upload() {
			self.location='upload.html';
		}

		function refreshInfo() {
			/*获取后台发来的JSON*/
			$.getJSON("get_dirInfo.php", function(data) {

			   // console.log(data);
			   // console.log("数据长度: " + data.length);

			   for(var i = 0; i < data.length; i++) {

			   		// 解析JSON数组中的各个JSON
			   		var obj = JSON.parse(data[i]);
			   		console.log(data[i]);
		   			// console.log(obj);

		   			URL="href_document.php?type=" + obj.type + "&name=" + obj.name + "#";

			   		/*动态添加复选框元素*/
			   		if (obj.name == "..") {
			   			$("#files").append('<span calss = "path-parior"><a href="' + URL + '">' + obj.name + '</a></span>' + '<br />');
			   		} else {
			   			$("#files").append('<input type="checkbox" name="operation[]" value=' + data[i] + '>' + '<a href="' + URL + '">' + obj.name + '</a>' + '<br />');
			   		}
			   }

			});
		}

		//获取单选或者多选的值，返回一个值得数组，如果没有值，返回空数组，参数inputlist是jQuery对象
		function checkAll(inputlist){
		    var arr = [];
		    var num = inputlist.length;
		    for(var i = 0; i < num; i++){
		        if (inputlist[i].checked) {
		        	console.log( String(inputlist[i].value));
		        	arr.push(String(inputlist[i].value));
		        }
		    }
		    return arr;
		}

		function down() {
			// var url = 'delete_down.php?' + getFormInfo('download');
			var fom = document.getElementById('fileinfo');
			var down = document.getElementById('download');
			var operation = [];
			operation = checkAll(fom);
			operation.push(down.value);

			var strify = JSON.stringify({"operation" : operation});
			// console.log(operation);
			// console.log(strify);
			var URL_GET = 'delete_down.php?operation=' + strify + '#';
			window.location.href=URL_GET;

			// var params = {operation : operation};
			// DownLoadFile(URL_POST, {operation : operation})
			// $.post('delete_down.php',
			// 	{operation : operation}	,
			// 	function(data,status){
			//     	console.log("Data: " + data + "nStatus: " + status);
			//     	// location.reload();
			//  	}
			// );

		}

		function del() {
			var fom = document.getElementById('fileinfo');
			var down = document.getElementById('delete');
			var operation = [];
			operation = checkAll(fom);
			operation.push(down.value);

			console.log(operation);
			$.post('delete_down.php',
					{operation : operation}	,
					function(data,status){
				    	location.reload();
				 	}
			);
		}
	</script>
</head>
<body>
	<h1>文件远程管理系统</h1>

	<div class="contents">
		<div class="file-operation">
			<button class="button white" id = "upload" onclick="upload()">上传</button>
			<button class="button white" id = "download" name="operation[]" value='{"operate":"download"}' onclick="down()">下载</button>
			<button class="button white" id = "delete" name="operation[]" value='{"operate":"delete"}' onclick="del()">删除</button>
		</div>
		<form class = "files-form" id = "fileinfo" action="delete_down.php" method="post">
<!-- 			<div class="operate">
				<input type="radio" name="operation[]" value='{"operate":"delete"}'>删除
				<input type="radio" name="operation[]" value='{"operate":"download"}'>下载 <br />
			</div> -->

			<div class="files-name">
				<a id="files"></a>
			</div>

			 <script>
			 	refreshInfo();
			</script>

		   <!-- <input type="submit" value="提交"> -->
		</form>

	</div>
</body>
</html>
