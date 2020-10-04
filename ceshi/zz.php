<?php

$file ="./ceshi.p";
$html = file_get_contents($file);

$dm1=array('http://1090ys1.com');
$dm2=array('/search.html?wd=xxx');
$dm3=array('1090');
$rule1=array("/<a class=\"v-thumb stui-vodlist__thumb lazyload\" href=\"(.*?)\" title=\"(.*?)\" data-original/"); // 地址+标题
$rule2=array('/<span class="pic-text text-right">([\S\s]*?)<\/span>/'); // 集数

preg_match_all($rule1[0],$html,$cms);
for($i=0;$i<sizeof($cms[0]);$i++){
    $array["url"][$i]=$search1.$cms[1][$i];
    // print_r($array);
    $array["title"][$i]=$cms[2][$i];
    // print_r($array);
}
preg_match_all($rule2[0],$html,$state);
for($i=0;$i<sizeof($state[1]);$i++){
    $array["state"][$i]=$state[1][$i];
    // print_r($array);
}
print_r($array);

?>
<a class="v-thumb stui-vodlist__thumb lazyload" href="/show/55455.html" title="姜子牙" data-original="http://jpg.mintehao.com/upload/vod/20201004-1/ea0121956789a0bd4b82ce4a0469a875.jpg" style="background-image: url(&quot;http://jpg.mintehao.com/upload/vod/20201004-1/ea0121956789a0bd4b82ce4a0469a875.jpg&quot;);"><span class="play hidden-xs"></span><span class="pic-text text-right">TC</span></a>