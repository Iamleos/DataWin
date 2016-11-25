<?php
	/**
	  * descript:将艺人吧的数据插入到数据库(手工采集的数据)
      * @date 2016/4/9
      * @author  XuJun
      * @version 1.0
      * @package
   */

   header("Content-Type: text/html;charset=utf-8");

   //由于数据量很大，所以设置永不超时。
   set_time_limit(0);

   //计算百度搜索指数的行数
   $line = count(file('/var/www/html/uploads/yirenba.txt'));

   //打开百度搜索指数文件
   $yirenba=file("/var/www/html/uploads/yirenba.txt");

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

   //存入艺人吧指数数据时，先删除表格。
   mysql_query("create table if not exists yirenba(yname varchar(30),ytheme int(8),ypaper int(10),ylove int(10),yacquitime date,incretheme int(8),increpaper int(10),increlove int(10),primary key(yname,yacquitime));",$con);


   //读取艺人吧中的数据
	for($j=0;$j<$line;$j++)
	{
		//读取一行中的指定列的数据
      if($j>0)
      { //先分割一行的字符串
        $arr=explode("\t",$yirenba[$j]);
	    //获得字符串中艺人名称
        $yname=trim($arr[0]);
		$ytheme=trim($arr[1]);
		$ypaper=trim($arr[2]);
		$ylove=trim($arr[3]);
		$yacquitime=date("Y-m-d");

		$increlove=0;
        $incretheme=0;
        $increpaper=0;

		$yinfo=mysql_query("select ytheme,ypaper,ylove from yirenba where yname='{$yname}'",$con);

		while($row=mysql_fetch_row($yinfo))
            {
				if($row[0]!=0)
				{
					$incretheme=$ytheme-$row[0];
				}
                if($row[1]!=0)
				{
					 $increpaper=$ypaper-$row[1];
				}
				if($row[2]!=0)
				{
					$increlove=$ylove-$row[2];

				}
            }

         mysql_query("delete  from yirenba where yname='{$yname}'");

        $sqlinsert="insert into yirenba(yname,ytheme,ypaper,ylove,yacquitime,incretheme,increpaper,increlove) values('{$yname}','{$ytheme}','{$ypaper}','{$ylove}','{$yacquitime}','{$incretheme}','{$increpaper}','{$increlove}')";

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