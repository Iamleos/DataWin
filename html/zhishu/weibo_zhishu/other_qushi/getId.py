#coding=utf-8
import sys
import demjson
reload(sys)
import requests
import urllib

class xl():
        def pc(self,name):
            url_name=urllib.quote(name)
            headers={
        'Host': 'data.weibo.com',
        'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding': 'gzip, deflate',
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
        'Referer': 'http://data.weibo.com/index/hotword?wid=1011642351362&wname='+url_name,
        'Cookie': 'UOR=os.51cto.com,widget.weibo.com,login.sina.com.cn; SINAGLOBAL=6177330910821.6455.1479996866703; ULV=1489504585907:11:5:3:4586936386863.846.1489504585868:1489503831048; SUBP=0033WrSXqPxfM72wWs9jqgMF55529P9D9WF89TI_0xIecGnkHmmz6bJm5JpV2hq4SKMReKMRS2WpMC4odcXt; SCF=AkYTkdo2vVqUkrcW7F94nk_Vg0SuYS1C_agdN1Qz5Ek-gPP3QXRrH1xePoD37KJ5Y8bd0n2h7glZAQZqEzpg59U.; SUHB=0d4u0oQCvYk3oj; WEB3_PHP-FPM_BX=0489acdf82dd7f3d45866401c0298d58; PHPSESSID=1me4a45d8hqctq68f1veeg97v3; _s_tentry=-; Apache=4586936386863.846.1489504585868; WBStorage=02e13baf68409715|undefined',
        'Connection': 'keep-alive',
        }
            r=requests.get("http://data.weibo.com/index/ajax/contrast?key2="+url_name+"&key3=&key4=&key5=&key6=&_t=0&__rnd=1489559374542",headers=headers)
            if demjson.decode(r.text)['code'] == '100001':
                return 'error'
            else:
                return demjson.decode(r.text)['data']['key2']['id']

x=xl()
print sys.argv[1]
print x.pc(sys.argv[1])
