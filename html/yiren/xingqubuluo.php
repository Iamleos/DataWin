<?php
/**
 *@调用八爪鱼豆瓣八组api
 *@author: xujun
 *@notice 具体解释看dianyingba.php
 */
 #! /usr/bin/php -q
    header("Content-Type: text/html;charset=utf-8");
	set_time_limit(0);
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
   //$host="localhost";
    $name="root";
    $password="ctfoxno1";
   //$password="123456";
    $dbname="yiren";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

    //mysql_query("drop table if exists xingqubuluo;",$con);
    mysql_query("create table if not exists xingqubuluo(name varchar(30),guanzhu int(8),huati int(8),increlove int(8),increhuati int(8),acquitime date,primary key(name,acquitime));",$con);
    $day=date("Y-m-d");
    //echo $day;
    $start="{$day} 10:00:00";
    $end="{$day} 11:00:00";

    //接口地址
    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=f67b15fd-f6a1-46c9-9635-486cb11e84b9&from={$start}&to={$end}"

    );
    $doc = new DomDocument();
    //循环接口地址
    foreach($apiUrl as $v){
        $doc->load($v);
        $books = $doc->getElementsByTagName( "Root" );
        foreach( $books as $Root )
        {
            // 获取XML内容
            $names = $Root->getElementsByTagName( "name" );
            $name = $names->item(0)->nodeValue;
            $guanzhus = $Root->getElementsByTagName( "guanzhu" );
            $guanzhu = $guanzhus->item(0)->nodeValue;
            $huatis=$Root->getElementsByTagName( "huati" );
            $huati=$huatis->item(0)->nodeValue;

			if(strstr($huati,"万"))
			{
				$huati=(int)(10000*((double)$huati));
			}
			if(strstr($guanzhu,"万"))
			{
				$guanzhu=(int)(10000*((double)$guanzhu));
			}
			$acquitime=date("Y-m-d");
			$time=time()-(1*24*60*60);
			$yestoday=date("Y-m-d",$time);

            $increlove=0;
            $increhuati=0;

            $info=mysql_query("select guanzhu,huati from xingqubuluo where name='{$name}' and $acquitime='{$yestoday}'",$con);
            //echo $yinfo;
            while($row=mysql_fetch_row($info))
            {
				if($row[0]!=0&&$guanzhu!=0)
				{
					$increlove=$guanzhu-$row[0];
				}
                if($row[1]!=0&&$huati!=0)
				{
					 $increhuati=$huati-$row[1];
				}

            }


           // mysql_query("delete  from xingqubuluo where name='{$name}'");
			if($name!="")
			{
               $sqlinsert="insert into xingqubuluo(name,guanzhu,huati,increlove,increhuati,acquitime) values('{$name}','{$guanzhu}','{$huati}','{$increlove}','{$increhuati}','{$acquitime}')";
               echo $sqlinsert;
               mysql_query($sqlinsert,$con);
			}

        }
    }
	//mysql_query("update xingqubuluo set acquitime='{$day}'");
	//echo "<br/>";
	//echo "update xingqubuluo set acquitime='{$day}'";
    mysql_close($con);
?>
