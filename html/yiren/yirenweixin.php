<?php
/*
 * @purpose: 使用curl并行处理url
 * @return: array 每个url获取的数据
 * @param: $urls array url列表
 * @param: $callback string 需要进行内容处理的回调函数。示例：func(array)
 */
  echo date("Y-n-d H:i:s",time()+8*3600)."</br>";
  function curl($urls = array(), $callback = '',$yname=array())
  {
      $flag = 0;
      $response = array();
      if (empty($urls)) {
          return $response;
      }
      $chs = curl_multi_init();
      $map = array();
      foreach($urls as $url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_TIMEOUT, 20);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_NOSIGNAL, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          curl_multi_add_handle($chs, $ch);
          $map[strval($ch)] = $url;
      }
      do{
          if (($status = curl_multi_exec($chs, $active)) != CURLM_CALL_MULTI_PERFORM) {
              if ($status != CURLM_OK) { break; } //如果没有准备就绪，就再次调用curl_multi_exec
              while ($done = curl_multi_info_read($chs)) {
                  $info = curl_getinfo($done["handle"]);
                  $error = curl_error($done["handle"]);
                  $result = curl_multi_getcontent($done["handle"]);
                  $url = $map[strval($done["handle"])];
                  $rtn = compact('info', 'error', 'result', 'url');
                  if (trim($callback)) {
                      $callback($rtn);
                  }
                  $response[$url] = $rtn;
                  curl_multi_remove_handle($chs, $done['handle']);
                  curl_close($done['handle']);
                  //如果仍然有未处理完毕的句柄，那么就select
                  if ($active > 0) {
                      curl_multi_select($chs, 0.5); //此处会导致阻塞大概0.5秒。
                  }

              }
          }
      }
      while($active > 0); //还有句柄处理还在进行中
      curl_multi_close($chs);
      return $response;
  }

  //使用方法
  function deal($data){
      if ($data["error"] == '') {
          //echo $data["url"]." -- ".$data["info"]["http_code"]."\n";
      } else {
          echo $data["url"]." -- ".$data["error"]."\n";
      }
  }

  ////
  function getData($result, $yname){
    $data = array(
      "read" => NULL,
      "name" => NULL,
      "love" => NULL,
      "time" => NULL,
      "paper" => NULL,
    );
    $data["name"] = $yname;
    preg_match_all('/总阅读<span class="">（\s*(.*)\s*）<\/span>/',$result,$yread);
    if(strchr($yread[1][0], '万+')){
      $data["read"] = str_replace('万+', '0000', $yread[1][0]);
    }
    elseif (strchr($yread[1][0], '亿+')) {
      $data["read"] = str_replace('亿+', '00000000', $yread[1][0]);
    }
    else {
      $data["read"] = $yread[1][0];
    }
    preg_match_all('/微信文章<span class=\"\">（共(.*)项结果）<\/span>/',$result,$ypaper);
    $data["paper"] = $ypaper[1][0];
    preg_match_all('/<i class=\"fa fa-thumbs-o-up\"><\/i>(.*)/',$result,$ygood);
    preg_match_all('/<i class="fa fa-book"><\/i>(.*)\s*<i class="fa fa-thumbs-o-up">/',$result,$yread0);
    $ylove = 0;
    $yread1 = 0;
    foreach ($ygood[1] as $key => $value) {
      $ylove +=$value;
    }
    foreach ($yread0[1] as $key => $value) {
      if(strchr($value, '万+')){
        $value = str_replace('万+', '0000', $value);
      }
      elseif (strchr($value, '亿+')) {
        $value = str_replace('亿+', '00000000', $value);
      }
      $yread1 += $value;
    }
    $ylove = round($data["read"]*$ylove/$yread1);
    $data["love"] = (int)$ylove;
    $data["name"] = $yname;
    $data["time"] = date("Y-m-d H:i:s", time());
    //var_dump($data);
    return $data;
  }

function getUrls($yname){
  ///////////////yname->url////////////////////////////////
  $URL_PREFIX = 'http://www.gsdata.cn/Query/article?q=';
  $URL_SUFFIX = '&search_field=0&post_time=1&sort=-3&cp=1&range_title=1&range_content=1&range_wx=0';
  $urls = array();
  foreach ($yname as $key => $value) {
    $urls[$key] = $URL_PREFIX.urlencode($value).$URL_SUFFIX;
  }
  return $urls;
}
//////////////////get yname//////////////
$host="56a3768226622.sh.cdb.myqcloud.com";
$name="root";
$password="ctfoxno1";
$dbname="yiren";
$con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
mysqli_select_db($con,$dbname);
mysqli_query($con,"set names utf8");
mysqli_query($con, "drop table if exists yirenweixin");
mysqli_query($con, "create table yirenweixin(yname varchar(30),ypaper varchar(30),yread varchar(30),ylove int(8),ydate varchar(30));");
$res = mysqli_query($con, "select me from actname");
$yname = array();
$i=0;
while ($row=mysqli_fetch_row($res)){
  if ($row[0]!=NULL){
    $yname[$i]=$row[0];
    $i++;
  }
  else {
    continue;
  }
}
echo count($yname);
///////////connect url&&&&&getData////////////////

while(count($yname) != 0){
  $failname =array();
  $urls = getUrls($yname);
  $result = curl($urls, "deal", $yname);
  $data = array();
  foreach ($yname as $key => $value) {
    $data[$value] = getData($result[$urls[$key]]["result"],$value);
  }
  foreach ($yname as $key => $value) {
    if($data[$value]['paper']!=NULL){
      mysqli_query($con, "insert into yirenweixin(yname,ypaper,yread,ylove,ydate) values('{$data[$value]['name']}','{$data[$value]['paper']}','{$data[$value]['read']}','{$data[$value]['love']}','{$data[$value]['time']}')");
    }
    else {
      array_push($failname, $value);
    }
  }
  $yname = $failname;
}

mysqli_close($con);
echo date("Y-n-d H:i:s",time()+8*3600)."</br>";










 ?>
