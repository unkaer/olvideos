# olviedos
简易 PHP 在线视频网站，搜索并播放资源站视频。
搜电影电视剧 即可在线观看
无需数据库 直接调用资源站的播放地址

## 演示
   演示站点：️[在线视频站](http://jx.unkaer.tk/)

## 结构
```
.
├── README.md
├── index.php   输入框
├── dx.php  API+爬取 资源站 缓存 视频播放地址
├── play.php    播放页面
├── _data   存放缓存文件    
├── _css    
|   ├── d.css   搜索页布局
├── _dplayer   播放器文件
|   ├── DPlayer.min.css
|   ├── DPlayer.min.js
|   └── hls.min.js
```
## 运行流程
index.php
![首页](https://gitee.com/unkaer/blog/raw/master/images/material/20200623212859.webp)

dx.php
 输入框  ~~url或~~ 名称
返回 视频列表
点击播放
![播放列表](https://gitee.com/unkaer/blog/raw/master/images/material/20200623213235.webp)

play.php
![播放页](https://gitee.com/unkaer/blog/raw/master/images/material/20200623213605.webp)


暂时只做了，视频名称搜索功能。 

## 计划
- [ ] 可选 播放器
- [ ] 热播排行榜
- [x] 缓存 ，提高下一次速度
- [ ] bt在线播放 解决画质低的片源
- [ ] url解析功能等

 有空 添加

## 安装使用
php 环境
1. 下载程序压缩包 [点我下载最新版](https://github.com/unkaer/olvideo/archive/master.zip)
2. 解压置于网站根目录


没有排版，外观简陋

欢迎 `star`
