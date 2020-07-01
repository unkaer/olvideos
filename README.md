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
 视频搜索框  热门电影、电视剧  
![首页](https://gitee.com/unkaer/blog/raw/master/images/material/20200701173611.webp)

dx.php
返回 视频列表
点击播放  + 迅雷P2P下载链接
![播放列表](https://gitee.com/unkaer/blog/raw/master/images/material/20200701174024.webp)

play.php
自动播放下一集  手动切换集数
![播放页](https://gitee.com/unkaer/blog/raw/master/images/material/20200701174347.webp)


## 计划
- [ ] 可选 播放器
- [x] 热播排行榜
- [x] 缓存 ，提高下一次搜索速度
~~- [ ] bt在线播放 解决画质低的片源~~  没有好的在线播放器，速度取决于资源。
- [ ] url解析功能等

 有空 添加

## 安装使用
php 环境
1. 下载程序压缩包 [点我下载最新版](https://github.com/unkaer/olvideo/archive/master.zip)
2. 解压置于网站根目录

### 本地安装使用
推荐一键式搭建LAMP,LNMP环境
[phpstudy](https://www.unkaer.cf/phpstudy.html)

### 服务器安装使用
推荐免费服务器
[彩虹云](https://www.unkaer.cf/free.html)

演示用的网站，就运行在这上面

## 
如果你觉得这个项目不错，欢迎 `star` 支持一下。
