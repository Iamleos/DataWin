<?php
    checkStatus('maoyanpiaofang', 7, 'filmdaily', 'php /var/www/html/filmdaily/maoyanpiaofang/acq.php');
    checkStatus('mpiaofangjianbao', 7, 'filmdaily', 'php /var/www/html/filmdaily/maoyanpiaofangjianbao/acq.php');
    checkStatus('maoyanfen', 4, 'filmdaily', 'php /var/www/html/filmdaily/maoyanfen/acq.php');
    checkStatus('maoyanhuangjinpaipian', 3, 'filmdaily', 'php /var/www/html/filmdaily/maoyan_hjpp.php');
    checkStatus('maoyanpaipian', 3, 'filmdaily', 'php /var/www/html/filmdaily/maoyan_pp.php');
    checkStatus('enyipiaofang', 5, 'filmdaily', 'php /var/www/html/filmdaily/enyipiaofang.php');
    checkStatus('zzbpiaofang', 11, 'filmdaily', 'php /var/www/html/filmdaily/zzbpiaofang.php');
    checkStatusForYesterday("turing_zzb_cinema",,"time",4,"filmdaily",'php /var/www/html/filmdaily/truing_zzb_movie.php');
    function checkStatus($tabname,$i,$dbname,$shell){
        $host="56a3768226622.sh.cdb.myqcloud.com:4892";
        $name="root";
        $password="ctfoxno1";
        //$dbname="filmdaily";
        global $result;
        //连接数据库
        $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");

        //选择数据库
        mysql_select_db($dbname,$con);

        //设置数据库表格编码


        mysql_query("set names utf8");

        $sqlresult=mysql_query("select * from {$tabname}",$con);
        $arr=array();
        $today=date("Y-m-d");
        if($row=mysql_fetch_row($sqlresult))
        {

                if(strtotime($row[$i])>=strtotime("{$today} 00:00:00"))
                {
                        echo "OK";

                }else{
                        shell_exec($shell);

                }

        }else{

                        shell_exec($shell);

        }
        mysql_close();
    }

    function checkStatusForYesterday($tabname,$time,$i,$dbname,$shell){
            $host="56a3768226622.sh.cdb.myqcloud.com:4892";
            $name="root";
            $password="ctfoxno1";
            //$dbname="filmdaily";
            global $result;

            //连接数据库
            $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");

            //选择数据库
            mysql_select_db($dbname,$con);

            //设置数据库表格编码


            mysql_query("set names utf8");
        $sqlresult=mysql_query("select * from {$tabname} order by {$time} desc limit 1",$con);
            $arr=array();
            $today=date("Y-m-d",strtotime("-1 day"));
            if($row=mysql_fetch_row($sqlresult))
            {
                    if(strtotime($row[$i])>=strtotime("{$today} 00:00:00"))
                    {
                            echo "ok";

                    }else{
                            shell_exec($shell);

                    }

            }else{

                            shell_exec($shell);

            }
            mysql_close();
    }
?>
