<?php
$url=array("http://www.zuidazy3.net/index.php","http://www.okzyw.com/index.php");
if(array_key_exists("name", $_POST)){
    $name=$_POST['name'];
}else{
    $name="流浪地球";
}

function playdetail($detailurl)
{
    $html = file_get_contents($detailurl);
    preg_match_all("/https?:\/\/.*\.jpe?g/",$html,$cover); // 封面 $cover[0][0]
    preg_match_all("/<h2>(.*)<\/h2>/",$html,$title); // 标题 $title[1][0]
    print_r("<img src=".$cover[0][0]." style=\"width:150;\"></br>");
    preg_match_all("/([^>]+)[$](https?.*\/index.m3u8)/",$html,$playurl);  // 播放地址
    for($i=0;$i<sizeof($playurl[2]);$i++){
        print_r("<form action=\"./play.php\" method='POST'>");
        print_r("<input type=\"hidden\" name=\"url\" value=".$playurl[2][$i]."><p><input type=\"submit\" value=\"播放·".$playurl[1][$i]."·".$title[1][0]."\"></p></form>");
    }
    
}

print_r("<meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\">");
print_r("<a href=\"..\">回到首页</a></br>");
for($i=0;$i<sizeof($url);$i++){
    print_r("爬取".($i+1)."</br>");
    $html = file_get_contents($url[$i]."?m=vod-search&wd=".$name);
    preg_match_all("/\?m=vod-detail-id-.+.html/",$html,$detail);
    foreach($detail[0] as $x=>$x_value){
        playdetail($url[$i].$x_value);
    }
}
?>