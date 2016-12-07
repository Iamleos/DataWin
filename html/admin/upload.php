<?php
/**
 * descript: 文件上传，由于时间紧迫没有写成函数形式，后期需要修改成函数形式
 * @date  2016/4/3
 * @author  XuJun
 * @version 2.0
 * @package
 * @notice please use utf-8 to read.请使用utf-8查看源代码
 */

	//设置浏览器的浏览编码方式
	header("Content-Type: text/html;charset=utf-8");

	//由于数据量很大，所以设置超时为零
	set_time_limit(0);

	//获取用户名和密码
	$user=$_REQUEST['user'];
	$pswd=$_REQUEST['password'];

	//用于判断微指数的整体指数和移动指数
	$wzsnum=0;
	//var_dump($_FILES);

	//由于只有一个管理员操作，所以设定用户名和密码为root,123456。如果用户名很多需要建立数据库。
	if($user=="root"&&$pswd=="123456"){


	/*
		UPLOAD_ERR_OK         其值为 0，没有错误发生，文件上传成功。
		UPLOAD_ERR_INI_SIZE   其值为 1，上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。
		UPLOAD_ERR_FORM_SIZE  其值为 2，上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。
		UPLOAD_ERR_PARTIAL    其值为 3，文件只有部分被上传。
		UPLOAD_ERR_NO_FILE    其值为 4，没有文件被上传。
		UPLOAD_ERR_NO_TMP_DIR 其值为 6，找不到临时文件夹。
		UPLOAD_ERR_CANT_WRITE 其值为 7，文件写入失败。
	*/


		/**
		 * descript:建立一个函数处理文件上传
		 * @param string $key 上传框的name
		 * @return
		 * @date  2016/4/8
		 */
		function upload($key,$value)
		{
			//判断是否是百度指数
			if($key=="baidu")
			{
				if($value["error"]>0)
				{
					errcode("百度搜索指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/baidu.txt", "w") or die("<font color='red'>服务器中不能创建百度指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode(",",$first_line);

					$len=count($key_array);

					if($len==22){

						fwrite($myfile, $txt);

						fclose($myfile);

						$url='http://115.159.205.133/admin/baidu.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>百度搜索指数：上传成功</font><br/>";
					}else{

						fclose($myfile);

						echo"<font color='red'>百度搜索指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是百度媒体指数
			if($key=="baidumeiti")
			{
				if($value["error"]>0)
				{
					errcode("百度媒体指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/baidumeiti.txt", "w") or die("<font color='red'>服务器中不能创建百度媒体指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode(",",$first_line);

					$len=count($key_array);

					if($len==4){

						fwrite($myfile, $txt);

						fclose($myfile);

						$url='http://115.159.205.133/admin/baidumeiti.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>百度媒体指数：上传成功</font><br/>";
					}else{

						fclose($myfile);

						echo"<font color='red'>百度媒体指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是360指数
			if($key=="qihu")
			{
				if($value["error"]>0)
				{
					errcode("360指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/qihu.txt", "w") or die("<font color='red'>服务器中不能创建360指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode(",",$first_line);

					$len=count($key_array);

					if($len==7){

						fwrite($myfile, $txt);

						fclose($myfile);

						$url='http://115.159.205.133/admin/qihu.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>360指数：上传成功</font><br/>";
					}else{

						fclose($myfile);

						echo"<font color='red'>360指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否微指数整体

			if($key=="weizhishuzhengti")
			{
				if($value["error"]>0)
				{
					errcode("微指数整体：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/weizhishuzhengti.txt", "w") or die("<font color='red'>服务器中不能创建微指数指数整体文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$second_line=$line_array[1];

					$key_array=explode(",",$second_line);

					global $wzsnum ;

					$wzsnum = $key_array[1];

					fwrite($myfile, $txt);

					fclose($myfile);

					$url='http://115.159.205.133/admin/weizhishuzhengti.php';
					file_get_contents($url);
					echo"<font color='#32CD32'>微指数整体上传成功</font><br/>";
				}
			}

			//判断微指数移动是否正确
			//判断是否微指数整体

			if($key=="weizhishuyidong")
			{
				if($value["error"]>0)
				{
					errcode("微指数移动：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/weizhishuyidong.txt", "w") or die("<font color='red'>服务器中不能创建微指数指数移动文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$second_line=$line_array[1];

					$key_array=explode(",",$second_line);

					$ydzs = $key_array[1];

					global $wzsnum ;

					if ($wzsnum >= $ydzs ||$wzsnum==0) {

						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/weizhishuyidong.php';
						file_get_contents($url);
						echo"<font color='#32CD32'>微指数移动上传成功</font><br/>";

					}else {

							fclose($myfile);

							echo "<font color='red'>请重新上传 微指数整体 和 微指数移动</font><br/>";
					}


				}
			}


			/*
			1、艺人贴吧
			2、电影吧
			3、豆瓣八组
			4、豆瓣分
			5、格瓦拉分
			6、猫眼票房简报
			7、猫眼黄金排片
			8、猫眼排片
			9、猫眼票房粗报
			10、猫眼评分
			11、天涯
			12、晋江兔区
			13、艺恩票房
			14、友谊吧
			15、娱乐圈吧
			16、专资办票房
			*/

			//判断是否是艺人贴吧数据
			if($key=="file1")
			{
				if($value["error"]>0)
				{
					errcode("艺人贴吧指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/yirenba.txt", "w") or die("<font color='red'>服务器中不能创建艺人贴吧指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="yname"&&$len==6)
					{
						fwrite($myfile, $txt);

						fclose($myfile);

						$url='http://115.159.205.133/admin/yirenba.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>艺人贴吧指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>艺人贴吧指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是电影吧数据
			if($key=="file2")
			{
				if($value["error"]>0)
				{
					errcode("电影吧指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/dianyingba.txt", "w") or die("<font color='red'>服务器中不能创建电影吧指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="dname"&&$len==6)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/dianyingba.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>电影吧指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>电影吧指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是豆瓣八组数据
			if($key=="file3")
			{
				if($value["error"]>0)
				{
					errcode("豆瓣八组指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/doubanbazu.txt", "w") or die("<font color='red'>服务器中不能创建豆瓣八组指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="dtitle"&&$len==6)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/doubanbazu.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>豆瓣八组指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>豆瓣八组指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

            //判断是否是豆瓣分数据
			if($key=="file4")
			{
				if($value["error"]>0)
				{
					errcode("豆瓣分指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/doubanfen.txt", "w") or die("<font color='red'>服务器中不能创建豆瓣分指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="dname"&&$len==5)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/doubanfen.php';
						file_get_contents($url);
						echo"<font color='#32CD32'>豆瓣分指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>豆瓣分指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			 //判断是否是格瓦拉分数据
			if($key=="file5")
			{
				if($value["error"]>0)
				{
					errcode("格瓦拉分指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/gewalafen.txt", "w") or die("<font color='red'>服务器中不能创建格瓦拉分指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="wname"&&$len==5)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/gewalafen.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>格瓦拉分指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>格瓦拉分指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是猫眼票房简报数据
			if($key=="file6")
			{
				if($value["error"]>0)
				{
					errcode("猫眼票房简报指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/maoyanpiaofangjianbao.txt", "w") or die("<font color='red'>服务器中不能创建猫眼票房简报指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="macquitime"&&$len==8)
					{
						fwrite($myfile, $txt);

						fclose($myfile);

						$url='http://115.159.205.133/admin/maoyanpiaofangjianbao.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>猫眼票房简报指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>猫眼票房简报指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是猫眼黄金排片数据
			if($key=="file7")
			{
				if($value["error"]>0)
				{
					errcode("猫眼黄金排片指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/maoyanhuangjinpaipian.txt", "w") or die("<font color='red'>服务器中不能创建猫眼黄金排片指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="m_gold_name"&&$len==5)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/maoyanhuangjinpaipian.php';
						file_get_contents($url);
						echo"<font color='#32CD32'>猫眼黄金排片指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>猫眼黄金排片指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是猫眼排片数据
			if($key=="file8")
			{
				if($value["error"]>0)
				{
					errcode("猫眼排片指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/maoyanpaipian.txt", "w") or die("<font color='red'>服务器中不能创建猫眼排片指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="name"&&$len==6)
					{
						fwrite($myfile, $txt);

						fclose($myfile);

						$url='http://115.159.205.133/admin/maoyanpaipian.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>猫眼排片指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>猫眼排片指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是猫眼票房粗报
			if($key=="file9")
			{
				if($value["error"]>0)
				{
					errcode("猫眼票房粗报指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/maoyanpiaofangcubao.txt", "w") or die("<font color='red'>服务器中不能创建猫眼票房粗报指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="mname"&&$len==9)
					{
						fwrite($myfile, $txt);

						fclose($myfile);

						$url='http://115.159.205.133/admin/maoyanpiaofangcubao.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>猫眼票房粗报指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>猫眼票房粗报指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是猫眼评分
			if($key=="file10")
			{
				if($value["error"]>0)
				{
					errcode("猫眼评分指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/maoyanfen.txt", "w") or die("<font color='red'>服务器中不能创建猫眼评分指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="mname"&&$len==5)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/maoyanfen.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>猫眼评分指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>猫眼评分指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是天涯指数
			if($key=="file11")
			{
				if($value["error"]>0)
				{
					errcode("天涯指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/tianya.txt", "w") or die("<font color='red'>服务器中不能创建天涯指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="ttitle"&&$len==3)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/tianya.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>天涯指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>天涯指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是晋江兔区
			if($key=="file12")
			{
				if($value["error"]>0)
				{
					errcode("晋江兔区指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/tuqu.txt", "w") or die("<font color='red'>服务器中不能创建晋江兔区指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="jtitle"&&$len==3)
					{
						fwrite($myfile, $txt);

						fclose($myfile);

						$url='http://115.159.205.133/admin/tuqu.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>晋江兔区指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>晋江兔区指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是艺恩票房
			if($key=="file13")
			{
				if($value["error"]>0)
				{
					errcode("艺恩票房指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/yien.txt", "w") or die("<font color='red'>服务器中不能创建艺恩票房指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="ename"&&$len==6)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/enyipiaofang.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>艺恩票房指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>艺恩票房指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是友谊吧数据
			if($key=="file14")
			{
				if($value["error"]>0)
				{
					errcode("友谊吧指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/youyiba.txt", "w") or die("<font color='red'>服务器中不能创建友谊吧指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="ytitle"&&$len==6)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/youyiba.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>友谊吧指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>友谊吧指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是娱乐圈吧数据
			if($key=="file15")
			{
				if($value["error"]>0)
				{
					errcode("娱乐圈吧指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/yulequanba.txt", "w") or die("<font color='red'>服务器中不能创建娱乐圈吧指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="ytitle"&&$len==7)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/yulequanba.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>娱乐圈吧指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>娱乐圈吧指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

			//判断是否是专资办票房数据
			if($key=="file16")
			{
				if($value["error"]>0)
				{
					errcode("专资办票房指数：",$value["error"]);
				}else{

					$myfile=fopen("/var/www/html/uploads/zpiaofang.txt", "w") or die("<font color='red'>服务器中不能创建专资办票房指数文件</font><br/");
					$txt= iconv( 'gbk', 'UTF-8' ,file_get_contents($value["tmp_name"]));

					$line_array=explode("\r\n",$txt);

					$first_line=$line_array[0];

					$key_array=explode("\t",$first_line);

					$len=count($key_array);

					if(trim($key_array[0])=="时间"&&$len==13)
					{
						fwrite($myfile, $txt);

						fclose($myfile);
						$url='http://115.159.205.133/admin/zpiaofang.php';
						file_get_contents($url);

						echo"<font color='#32CD32'>专资办票房指数：上传成功</font><br/>";

					}else{

						fclose($myfile);

						echo"<font color='red'>专资办票房指数：格式不正确，请重新上传</font><br/>";

					}
				}
			}

		} // end func


		/**
		 * descript:处理得到错误代码解释
		 * @param string $filename 上传文件名
		 * @return	输出错误信息
		 * @date 2016/4/8
		 */
		function errcode($filename,$err)
		{
			if($err==1)
			{
				echo "<font color='red'>{$filename}上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。</font><br/>";
			}
			if($err==2)
			{
				echo "<font color='red'>{$filename}上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。</font><br/>";
			}
			if($err==3)
			{
				echo "<font color='red'>{$filename}文件只有部分被上传。</font><br/>";
			}
			if($err==4)
			{
				echo "<font color='red'>{$filename}没有文件被上传。</font><br/>";
			}
			if($err==6)
			{
				echo "<font color='red'>{$filename}找不到临时文件夹。</font><br/>";
			}
			if($err==7)
			{
				echo "<font color='red'>{$filename}文件写入失败</font><br/>";
			}



		} // end func

		foreach($_FILES as $key => $value)
		{
		//	var_dump($key);
		//	var_dump($value);
		//	echo "<br/>";
			upload($key,$value);



		}



	}else{

     echo "用户名或密码错误";

	}

?>
