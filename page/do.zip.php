<?php
Include_once("zip.class.php");

define('FOLDER_MODE', '1');
define('FILE_MODE', '2');

//压缩的路径--注意结合chdir()用
/*
    $Path 要压缩的文件或目录，文件可为数组
    $ZipFile 压缩文件名
    $Typ 文件类型: 文件 or 文件夹
    $Todo 工作模式：保存 or 下载
*/

function toZip($Path,$ZipFile,$Typ=1,$Todo=1){
    if (is_string($Path)) {
        $Path=str_ireplace("\\","/",($Path));
    }
    if(is_null($Path) Or empty($Path) Or !isset($Path)){return False;}
    if(is_null($ZipFile) Or empty($ZipFile) Or !isset($ZipFile)){return False;}

    $zip_path = $ZipFile;

    $zip = new PHPZip($zip_path);

    if (is_string($Path)) {
        if(substr($Path,-1,1)=="/"){$Path=substr($Path,0,strlen($Path)-1);}
    }

    @ob_end_clean();
    switch ($Typ) {
        case "1":
            $zip->ZipDir($Path,$ZipFile,$Todo);
            break;
        case "2":
            $zip->ZipFile($Path,$ZipFile,$Todo);
            break;
    }

    return True;
}

