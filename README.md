拍拍客
====

我们喜欢随手拍一拍,大多数照片没有特殊意义，偶尔也会看一看。我们是——————拍拍客！

1、功能
----
通过微信公共账号（以后会添加来往、易信等）发送图片。采用three.js显示3D照片墙，共有4中样式分别如下
###1）照片墙
![table](http://www.pper.com.cn/img/table.gif)  
###2）水晶球
![sphere](http://www.pper.com.cn/img/sphere.gif)  
###3）螺旋塔
![helix](http://www.pper.com.cn/img/helix.gif)  
###4）展览厅
![grid](http://www.pper.com.cn/img/grid.gif)  


2、产品特点
----
###1)绚丽
采用Three.js的3D样式，打破了传统照片墙单调乏味的风格
###2)互动
通过微信就可以上传图片，大大简化互动环节。<br />
可以手动拖动3D墙，增加趣味性。
###3)架构极简主义
no mvc！no database!!
###4)维护方便
图片的名称采用格式：时间+openID.jpg格式。根据前端名称，后端可以方便的提取、修改或者删除图片。

3、安装
----
###1）下载源代码解压
源代码地址：https://github.com/liuhill/i-Pper/archive/master.zip

###2）修改公共账号图片
将./img/weixin.jpg修改为自己的微信公共账号二维码图片
###3）修改token
在./weixin.php中修改token
 ```
		define("TOKEN", "hillock");
```
###4）开始娱乐吧~~~~


4、demo
----
###网址
http://www.pper.com.cn/

###使用方法
1)关注微信公共账号<br />
2)发送图片给该账号<br />
3)刷新网页<br />


