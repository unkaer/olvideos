# olviedos
简易 PHP 在线视频网站，搜索并播放资源站视频。
搜电影电视剧 即可在线观看
无需数据库 直接调用资源站的播放地址
[演示站http://jx.unkaer.tk/](http://jx.unkaer.tk/)

# 结构
```
.
├── README.md
├── index.php   输入框
├── pc.php  爬取 资源站搜索 视频播放地址
├── ap.php  通过资源站API获取视频播放地址
├── play.php    播放页面
├── _css    
|   ├── d.css   搜索页布局
├── _dplayer   播放器文件
|   ├── DPlayer.min.css
|   ├── DPlayer.min.js
|   └── hls.min.js
```
# 流程
index.php
![首页](https://gitee.com/unkaer/blog/raw/master/images/material/20200623212859.webp)

pc.php or ap.php
 输入框  ~~url或~~ 名称
返回 视频列表
点击播放
![播放列表](https://gitee.com/unkaer/blog/raw/master/images/material/20200623213235.webp)

play.php
![播放页](https://gitee.com/unkaer/blog/raw/master/images/material/20200623213605.webp)


暂时只做了，视频名称搜索功能。 

# 待添加功能
 1. 可选 播放器  
 2. url解析功能等
 
 有空 添加

没有排版，外观简陋
