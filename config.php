<?php

// get 第三方视频链接
$dm1=array('http://flvweb.com','http://www.nicotv.me','http://www.zzzfun.com','https://www.agefans.tv');
$dm2=array('/index.php/vod/search.html?wd=xxx','/video/search/xxx.html','/vod-search.html?wd=xxx.html','/search?query=xxx&page=1');
$dm3=array('flv','妮可动漫','zzzfun动漫视频网','AGE动漫');
$rule1=array("/href=\"(.*?)\" title=\"(.*?)\" data-original=/","/<a href=\"(.*?)\" title=\"(.*?)\">/","/<a href=\"(\/vod-detail.*?)\">(.*?)<\/a>/","/<a href=\"(.*?)\" class=\"cell_imform_name\">(.*?)<\/a>/");
$rule3=array('/<span class="pic-text text-right">([\S\s]*?)<\/span>/','/<span class="continu">([\S\s]*?)<\/span>/','/<span class="color">([\S\s]*?)<\/span>/','/<span class="newname">(.*?)<\/span>/');

// search.asp  第三方视频链接
$post1=array('http://www.tv6box.com');
$post2=array('/index.php?s=vod-search-name');
$post3=array('电影盒子');
$rule5=array("/href=\"(.*?)\" target=\"_blank\" title=\"(.*?)\"/",);
$rule6=array('/<span  class=\"v_bottom_tips\">([\S\s]*?)<\/span>/',);

// post 第三方视频链接
$search1=array('http://www.dm530.net','http://www.tldm.net','http://www.imomoe.in');
$search2=array('风车动漫','天乐动漫','樱花动漫');
$rule2=array("/<a href=\"([^>]*?)\" target=\"_blank\" title=\"(.*?)\">/","/<a href=\"(.*?)\" target=\"_blank\"><img src=\".*?\" alt=\"(.*?)\">/","/<a href=\"([^>]*?)\" target=\"_blank\" title=\"(.*?)\">/");
$rule4=array('/<span>别名：.*?<\/span><span><font color="red">(.*?)<\/font>　类型：/','/<abbr>(.*?)<\/abbr>/','/<span>别名：.*?<\/span><span>(.*?)　类型：/');

?>