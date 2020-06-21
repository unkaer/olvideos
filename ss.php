<?php
$api=array('http://cj.wlzy.tv/inc/api_mac_m3u8.php','http://api.iokzy.com/inc/apickm3u8.php');
if(array_key_exists("name", $_POST)){
    $name=$_POST['name'];
}else{
    header("Location: ..");
    exit();
}

//获取视频id  并打印
function getname($name,$api){
    $data = file_get_contents($api."?wd=".$name);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        $id=(string)$video->id;
        geturl($id,$api);
    }
}

function geturl($id,$api){
    $data = file_get_contents($api."?ac=videolist&ids=".$id);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        $pic=(string)$video->pic;
        print_r("<img src=".$pic." style=\"width:150;\"></br>");  // 封面
        $url=(string)$video->dl->dd;   //播放地址
        preg_match_all("/http?:\/\/[^#]*\/index.m3u8/",$url,$playurl);
        preg_match_all("/#?([^#]+)[$]/",$url,$tag);
        $title=(string)$video->name;
        for($i=0;$i<sizeof($playurl[0]);$i++){
            print_r("<form action=\"./play.php\" method='POST'>");
            print_r("<input type=\"hidden\" name=\"url\" value=\"".$playurl[0][$i]."\">");
            print_r("<input type=\"submit\" value=播放·".$tag[1][$i]."·".$title."></form>");
        }
    }
}

print_r("<a href=\"..\">回到首页</a></br>");
for($i=0;$i<sizeof($api);$i++){ 
    print_r("接口".($i+1)."</br>");
    getname($name,$api[$i]);
}
?>