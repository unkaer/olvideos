<?php
set_time_limit(0);
ob_implicit_flush();
if($_GET['id']=='1'){
// 下载最新版系统
$url = "https://github.com/unkaer/olvideo/archive/master.zip";
$file = "./olvideo.zip";
$html = file_get_contents($url);
file_put_contents($file,$html);
echo "下载成功";
}
// 解压文件
if($_GET['id']=='2'){
$zip = new ZipArchive() ; 
if ($zip->open($file) !== TRUE) { 
  die ("打开压缩文件失败"); 
} 
//将压缩文件解压到指定的目录下 
$zip->extractTo('./'); 
//关闭zip文档 
$zip->close();
echo '解压成功'; 
unlink($file); //删除压缩文件
}

if($_GET['id']=='3'){
function copydir($dirsrc, $dirto) {
//如果原来的文件存在， 判断是不是一个目录
    if(file_exists($dirto)) {
        if(!is_dir($dirto)) {
        echo "目标不是一个目录， 不能copy进去<br>";
        exit;
        }
    }
    else{
        mkdir($dirto);
    }
    $dir = opendir($dirsrc);
    while($filename = readdir($dir)) {
        if($filename != "." && $filename !="..") {
            $srcfile = $dirsrc."/".$filename; //原文件
            $tofile = $dirto."/".$filename; //目标文件
            if(is_dir($srcfile)) {
            copydir($srcfile, $tofile); //递归处理所有子目录
            }
            else{
            //是文件就拷贝到目标目录
            copy($srcfile, $tofile);
            }
        }
    }
}
copydir("olvideo-master", "..");
unlink(olvideo-master); //删除旧目录下的文件
}

?>
