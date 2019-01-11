<?php
	require_once "./page/config.php";
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>文件管理系统</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="zh-CN" />
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="./static/css/stylesheet.css">
	<script type="text/javascript" src="./static/js/jquery-3.2.1.min.js"></script>
	<script>
		// 跳转到上传文件页面
		function upload() {
			self.location='upload.html';
		}

		// 刷新文件信息
		function refreshInfo() {
			/*获取后台发来的JSON*/
			$.getJSON("./page/get_dirInfo.php", function(data) {

			   for(var i = 0; i < data.length; i++) {

			   		// 解析JSON数组中的各个JSON
			   		var obj = JSON.parse(data[i]);
		   			// 构造超链接
		   			URL="./page/href_document.php?type=" + obj.type + "&name=" + obj.name + "#";

		   			var icon_name = '';
		   			// 选择要添加的图标
		   			if (obj.type == 'file') {
		   				icon_name = 'fa-file-text-o';
		   			} else {
		   				icon_name = 'fa-folder';
		   			}
		   			var add_icon = '<td class="file-icon" id="file-icon"><i class="fa ' + icon_name + '"></i></td>';

			   		/*动态添加复选框元素*/
			   		if (obj.name == "..") {
			   			$("#path-parior-span").append('<span class = "path-parior"><a href="' + URL + '">' + '返回上层目录' + '</a></span>' + '<br />');
			   		} else {
			   			$("#file-name-table-body").append('<tr><td id="file-checkbox"><input type="checkbox" name="operation[]" value=' + data[i] + '></td>' + add_icon + '<td id="file-name"> <a href="' + URL + '">' + obj.name + '</a></td>' + '<br /></tr>');

			   		}
			   }

			});
		}

		// 添加当前路径显示
		function getpath_now() {
			$.get("./page/getpath_now.php", function(data) {
				$("#path-name-now").append(data);
			});
		}

		// 检查选中的复选框中的值
		function check_files_checked(files) {
			if (files.length == 0) {
				alert("未选中任何文件\n 请选择要操作的文件");
				//location.reload();
				return false;
			}
			return true;
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

		// 下载文件
		function down() {
			var fom = document.getElementById('fileinfo');
			var down = document.getElementById('download');
			var operation = [];
			operation = checkAll(fom);
			if(!check_files_checked(operation)) return;
			operation.push(down.value);

			var strify = JSON.stringify({"operation" : operation});
			var URL_GET = './page/delete_down.php?operation=' + strify + '#';
			// 跳转到该URL
			window.location.href=URL_GET;

		}

		// 删除
		function del() {
			var fom = document.getElementById('fileinfo');
			var down = document.getElementById('delete');
			var operation = [];
			operation = checkAll(fom);
			if(!check_files_checked(operation)) return;
			operation.push(down.value);

			console.log(operation);
			$.post('./page/delete_down.php',
					{operation : operation}	,
					function(data,status){
				    	//location.reload();
							// 跳转到该URL
							window.location.href = 'index.php';
				 	}
			);
		}
	</script>
</head>
<body>
	<h1>文件管理系统</h1>

	<div class="contents">
		<div class="navigation">
			<button class="button white" id = "upload" onclick="upload()">上传</button>
			<button class="button white" id = "download" name="operation[]" value='{"operate":"download"}' onclick="down()">下载</button>
			<button class="button white" id = "delete" name="operation[]" value='{"operate":"delete"}' onclick="del()">删除</button>
		</div>
		<div class="path-now">
			<span class="path-name" id="path-name-now">当前目录:</span>
			<script type="text/javascript">
				getpath_now();
			</script>
		</div>
		<form class = "files-form" id = "fileinfo" action="./page/delete_down.php" method="post">
			<div id="files-name">
				<span id="path-parior-span"></span>
				<table id="file-name-table">
					<thead>
					</thead>
					<tbody id="file-name-table-body">
					</tbody>
				</table>
			</div>

			<script type="text/javascript">
			 	refreshInfo();
			</script>
		</form>

	</div>
</body>
</html>
