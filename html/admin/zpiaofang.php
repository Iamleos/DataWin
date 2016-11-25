<?php
	/**
	*@手工数据插入数据库
	*@author: xujun
	*@date 2016/4/9
	*/

   header("Content-Type: text/html;charset=utf-8");

   //由于数据量很大，所以设置永不超时。
   set_time_limit(0);

   //计算百度搜索指数的行数
   $line = count(file('/var/www/html/uploads/zpiaofang.txt'));

   //打开百度搜索指数文件
   $zpiaofang=file("/var/www/html/uploads/zpiaofang.txt");

   //数据库的基本信息
   $host="56a3768226622.sh.cdb.myqcloud.com:4892";
   $name="root";
   $password="ctfoxno1";
   $dbname="filmdaily";

   //连接数据库
   $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");

   //选择数据库
   mysql_select_db($dbname,$con);

   //设置数据库表格编码
   mysql_query("set names utf8");

   //用于判断是否插入成功
   $flag=0;
   //用于显示在哪一行执行失败
   $num=0;

   //由于昨天的数据不需要，所以先删除数据再建立表格，如果是先清空数据也可以
    mysql_query("drop table if exists zpiaofang;",$con);
    mysql_query("create table zpiaofang(zname varchar(30),zboxofficesum int(6),zboxoffice int(5),zsession int(7),zperson int(8),zofficesale int(6),zofficerate decimal(4,2),zinternetsale int(6),zinternetrate decimal(4,2),zrealtimeboxoffice int(8),zestimatedboxoffice int(8),zacquitime date,primary key(zname,zacquitime));",$con);

   //读取电影吧中的数据
	for($j=0;$j<$line;$j++)
	{
		//读取一行中的指定列的数据
      if($j>0)
      { //先分割一行的字符串
        $arr=explode("\t",$zpiaofang[$j]);
	    //获得字符串中艺人名称
        $zname=trim($arr[1]);
		$zboxofficesum=trim($arr[3]);
		$zboxoffice=trim($arr[2]);
		$zsession=trim($arr[4]);
		$zperson=trim($arr[5]);
		$zofficesale=trim($arr[6]);
		$zofficerate=trim($arr[7]);
		$zinternetsale=trim($arr[8]);
		$zinternetrate=trim($arr[9]);
		$zrealtimeboxoffice=trim($arr[10]);
		$zestimatedboxoffice=trim($arr[11]);
		$zacquitime=trim($arr[12]);

		$sqlinsert="insert into zpiaofang(zname,zboxofficesum,zboxoffice,zsession,zperson,zofficesale,zofficerate,zinternetsale,zinternetrate,zrealtimeboxoffice,zestimatedboxoffice,zacquitime) values('{$zname}','{$zboxofficesum}','{$zboxoffice}','{$zsession}','{$zperson}','{$zofficesale}','{$zofficerate}','{$zinternetsale}','{$zinternetrate}','{$zrealtimeboxoffice}','{$zestimatedboxoffice}','{$zacquitime}')";

		echo $sqlinsert;

	    $result=mysql_query($sqlinsert,$con);
	    if(!$result)
		{
			$flag=1;
			$num=$j;
			break;
	    }

      }

	}

	if($flag==1)
	{
		//用于判断在哪一行执行失败
		echo "excute Failed, check line {$num}";

	}else{

		echo "excute OK";
	}
	mysql_close($con);

?>
