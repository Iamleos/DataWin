*************************************************猫眼票房各脚本细则****************************************
此文本解释了猫眼票房各脚本的实现步骤和原理，以及运行方式
包括如下脚本：
1、maoyan_cinema
2、maoyan_yingtou
3、maoyanpiaofang
4、maoyanfen
5、maoyanpiaofangjianbao
*************************************************脚本细则**********************************************
1、每个猫眼的功能都部署在服务器/var/www/html/filmdaily目录下面。并且以上述名字命名的文件夹为单位
2、每个功能所属目录总共有3个基本脚本共同实现该功能，若有其他文件，均为该三个基本脚本的输出。maoyan_download_resource.php，maoyan_getData.php，maoyan.java 。

*************************************************maoyan各脚本原理***************************************
1、maoyan_download_resouce.php
   安需求，curl模拟请求相应页面，设置各请求参数。获取相应页面的源码，同时将网页源码中附带的字体文件下载下来
将网页源码和字体文件保存在相应目录下（map.ttf, result.txt），并且shell_exec启动其他脚本

2、maoyan.java
   用来破解猫眼的数据加密，猫眼实际上是通过自定义的字体文件，将0-9的unicode码与其图元进行的重新映射。所以浏览器可以正常现实数据。
所以，我们需要对其附带的字体文件进行解析，用java，将字体文件以二进制字节流形式读入，通过图元的名称进行匹配，获取图元相应的unicode码，这样网页源码中的转义字符序列就可以破解了。破解的映射关系楔入key.txt

3、maoyan_getData.txt
   将result.txt中保存下来的网页源码和key.txt中解析出来的映射关系进行匹配，并且再通过正则表达式爬去想要的数据，保存到数据库中
