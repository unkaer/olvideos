<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    $url = "https://github.com/unkaer/olvideo/archive/master.zip";  // 下载地址
    $file = "./olvideo.zip";  // 下载压缩包，存放位置
    $dirsrc = "olvideo-master";  // 解压后目录
    $dirto = "..";  // 覆盖安装目录
    
    if($_GET['id']=='1'){   // 下载最新版系统
        $html = file_get_contents($url);
        file_put_contents($file,$html);
        echo "下载成功<br/>\n";
        $size = filesize($file);
        function trans_byte($byte)
        {
            $KB = 1024;
            $MB = 1024 * $KB;
            $GB = 1024 * $MB;
            $TB = 1024 * $GB;
            if ($byte < $KB) {
                return $byte . "B";
            } elseif ($byte < $MB) {
                return round($byte / $KB, 2) . "KB";
            } elseif ($byte < $GB) {
                return round($byte / $MB, 2) . "MB";
            } elseif ($byte < $TB) {
                return round($byte / $GB, 2) . "GB";
            } else {
                return round($byte / $TB, 2) . "TB";
            }
        }
        echo trans_byte($size);
        echo '<div><a href="./down.php?id=2">解压文件</a></div>';
    }

    if($_GET['id']=='2'){   // 解压文件
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
        echo '已删除压缩包';
        echo '<div><a href="./down.php?id=3">安装</a></div>';
    }

    if($_GET['id']=='3'){   // 复制文件，删除旧文件
        function copydir($dirsrc,$dirto){
            //如果原来的文件存在， 判断是不是一个目录
            if(file_exists($dirto)) {
                if(!is_dir($dirto)) {
                    echo "目标不是一个目录，不能copy进去<br>";
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
                    if(copydir($srcfile, $tofile))echo "成功拷贝目录：$srcfile--->$tofile<br/>\n"; //递归处理所有子目录
                    }
                    else{
                    //是文件就拷贝到目标目录
                    if(file_exists($tofile)){  // 如果文件存在，判读是否相同。不同的才改变
                        $md51 = md5_file($srcfile);
                        $md52 = md5_file($tofile);
                        if($md51!=$md52){
                            if(copy($srcfile, $tofile)){
                                echo "成功拷贝文件：$srcfile--->$tofile<br/>\n";
                            }
                            else{
                                echo "拷贝文件失败：$srcfile--->$tofile<br/>\n";
                            }
                        }
                    }
                    else{
                        if(copy($srcfile, $tofile)){
                            echo "成功拷贝文件：$srcfile--->$tofile<br/>\n";
                        }
                        else{
                            echo "拷贝文件失败：$srcfile--->$tofile<br/>\n";
                        }
                    }
                    }
                }
            }
        }
        echo "<p>拷贝文件夹:</p>";
        if(!is_dir($dirsrc)){
            echo $dirsrc."文件目录不存在";
            exit;
        }
        copydir($dirsrc, $dirto);

        function delDirAndFile($dirName){ 
            if ($handle=opendir($dirName)){
                while(false!==($item=readdir($handle))){
                    if($item!="."&&$item!=".."){
                        if(is_dir("$dirName/$item")){
                            delDirAndFile("$dirName/$item");
                        }
                        else{
                            if(unlink("$dirName/$item"))echo "成功删除文件：$dirName/$item<br/>\n";
                        }
                    }
                }
                closedir($handle);
                if(rmdir($dirName))echo "成功删除目录：$dirName<br/>\n"; 
            }
        }
        echo "<p>删除旧目录:</p>";
        delDirAndFile("olvideo-master");  // 删除旧目录
        echo '<div><a href="./">回到管理页</a></div>';
    }
}
else{
    echo("您无权访问，请登录");
    print_r('
    <form action="./login.php" method="POST">
    <p>用户：<input id="ipt" type="text" name="username" autofocus value="">
    <p>密码：<input id="ipt" type="text" name="password" autofocus value="">
        <input type="submit" value="登录"></p>
    </form>');
}

?>
