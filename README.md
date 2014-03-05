拍拍客
====

我们喜欢随手拍一拍,大多数照片没有特殊意义，偶尔也会看一看。我们是——————拍拍客！

1、功能
----
        通过微信公共账号（以后会添加来往、易信等）发送图片。采用three.js显示3D照片墙，共有4中样式分别如下
####1.1 照片墙
![table](http://www.pper.com.cn/img/table.gif) 
####1.2 水晶球
 ![sphere](http://www.pper.com.cn/img/sphere.gif)  
####1.3 螺旋塔
![helix](http://www.pper.com.cn/img/helix.gif)
####1.4 展览厅
![grid](http://www.pper.com.cn/img/grid.gif)  


2、产品特点
----
####2.1 绚丽
    采用Three.js的3D样式，打破了传统照片墙单调乏味的风格
####2.2 互动
	+   通过微信就可以上传图片，大大简化互动环节。
	+   可以手动拖动3D墙，增加趣味性。
####2.3 代码极简主义
    +   No MVC！
    +   No DB!
	
####2.4 维护方便
	图片的名称采用格式：时间+openID。根据前端名称，后端可以方便的提取、修改或者删除图片，也可以根据openID追溯来源。

3、安装
----
####3.1 下载源代码解压
https://github.com/liuhill/i-Pper/archive/master.zip

####3.2 编辑公共账号
	将./img/weixin.jpg修改为自己的微信公共账号二维码图片
####3.3 配置
  在config.php中修改微信公共账号token
```
    define("TOKEN", "你的token");
```
####4）开始图片上墙吧~~~~


4、demo
----
####4.1 网址
http://www.pper.com.cn/

####4.2 使用方法
	1)关注微信公共账号
	2)发送图片给该账号
	3)刷新网页，照片上墙了~~~~


