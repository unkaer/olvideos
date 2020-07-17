<?php
if(array_key_exists("url", $_POST)|array_key_exists("wd", $_GET)){
    if(isset($_POST["wd"])){$name = $_POST["wd"];}else{$name = $_GET["wd"];}
    $teshu=array(array(",","!",":"),array("，","！","："),array("(",")","普通话","粤语","版","[","]","\"","\'"));  // 0替换为1，2删除
    for($i=0;$i<sizeof($teshu[0]);$i++){
        $name=str_replace($teshu[0][$i],$teshu[1][$i],$name);
    }
    for($i=0;$i<sizeof($teshu[2]);$i++){
        $name=str_replace($teshu[2][$i],'',$name);
    }
}else{
    header("Location: ..");
    exit();
}
?>