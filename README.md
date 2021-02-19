# olviedos
简易 PHP 在线视频网站，无需数据库，占用空间小。
基本原理 搜索（API或爬取）资源站视频 在线播放。

## 演示
   演示站点：️
   [在线视频站](http://jx.unkaer.cf/)  (彩虹云+~~cfCDN~~)
   [备用站点](http://jx.unkaer.tk/) (Heroku+cfCDN 自动更新最新版)

## 结构
```
.
├── README.md
├── index.php     搜索首页
├── dx.php     API+爬取 资源站 的视频播放地址
├── dm.php     搜索第三方播放 链接
├── play.php      播放页面
├── error.php     错误页
├── _data      存放缓存数据 
|   └── _img    存放缓存图片
├── _src      存放缓存文件 
|   ├── _css
|   |   └── d.css     搜索页布局
|   ├── _js
|   |   └── settime.js     建站时间
|   └ _dplayer      播放器文件
|   | ├── DPlayer.min.css
|   | ├── DPlayer.min.js
|   | └── hls.min.js
|   ├── function.php  页面布局
|   ├── favicon.ico   网站图标
|   └── ss.svg 搜索图案
```
## 运行流程
index.php
 视频搜索框  热门电影、电视剧 
![首页](https://gitee.com/unkaer/blog/raw/master/images/material/20200706101406.webp)

dx.php
返回 视频列表
点击播放  + 迅雷P2P下载链接
![播放列表](https://gitee.com/unkaer/blog/raw/master/images/material/20200706101457.webp)

dm.php
返回 第三方链接 跳转到第三方视频站
![第三方](https://gitee.com/unkaer/blog/raw/master/images/material/20200706101949.webp)

play.php
自动播放下一集  手动切换集数
![播放页](https://gitee.com/unkaer/blog/raw/master/images/material/20200706101624.webp)


## 计划
- [ ] 可选 播放器
- [x] 哔哩哔哩动画弹幕
- [x] p2p播放
- [x] 热播排行榜
- [x] 缓存 ，提高下一次搜索速度
- [ ] ~~bt在线播放 解决画质低的片源~~  没有好的在线播放器，速度取决于资源。
- [x] url解析功能等 (暂时支持部分爱奇艺、腾讯视频)
- [x] cookie 历史记录 (基本完成，但只记录上一次搜索和观看集数。没有播放时间记录，没有会员登录，多端同步功能)
- [x] ip 频率限制，怕有人恶意点几下，主机就挂了  (只是浏览器记录一个15秒过期的cookie)
- [ ] 后台 可选主题 （基本页面还没做好）

 有空 添加

## 安装使用
php 环境
1. 下载程序压缩包 [点我下载最新版](https://github.com/unkaer/olvideos/archive/master.zip)
2. 解压置于网站根目录

### 本地安装使用
推荐一键式搭建LAMP,LNMP环境
[phpstudy](https://www.unkaer.cf/phpstudy.html)

### 服务器安装使用
推荐免费服务器
[彩虹云](https://www.unkaer.cf/free.html)

[heroku](https://www.heroku.com/)

演示用的网站，就运行在这上面(并添加 clouflare [CDN加速](https://dash.cloudflare.com/))。

## 常见错误
因为部分信息通过爬虫获得，所以可能需要修改部分php.ini，并重启服务器

### SSL
1. 抢完<a href="https://curl.haxx.se/docs/caextract.html">ca证书下载地址</a>下载，或者直接下载<a href="https://curl.se/ca/cacert-2021-01-19.pem">cacert-2021-01-19.pem</a>；
2. 上传到PHP可读目录；
3. 修改 `php.ini`。
```ini
[openssl]
openssl.cafile=/***php可读目录***/cert.pem
```

### user_agent
默认的 `user_agent` 是 PHP，把它改成Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)来模拟浏览器。
```ini
user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
```

## 支持
如果你觉得这个项目不错，欢迎 `star` 支持一下。

### 微信
![微信捐赠](https://gitee.com/Unkaer/blog/raw/master/images/wechatpayqr.webp)

### 支付宝
![支付宝捐赠](https://gitee.com/Unkaer/blog/raw/master/images/alipayqr.webp)
