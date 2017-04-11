#coding=utf-8
import sys
reload(sys)
import requests
import urllib

class xl():
        def pc(self,id):
            #url_name=urllib.quote(name)
            headers={
        'Host': 'data.weibo.com',
        'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding': 'gzip, deflate',
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
        'Referer': 'http://data.weibo.com/index/zone',
        'Cookie': 'UOR=os.51cto.com,widget.weibo.com,www.baidu.com; SINAGLOBAL=6177330910821.6455.1479996866703; ULV=1489642521527:12:6:4:5356826972173.07.1489642521516:1489504585907; SUBP=0033WrSXqPxfM72wWs9jqgMF55529P9D9WF89TI_0xIecGnkHmmz6bJm5JpV2hq4SKMReKMRS2WpMC4odcXt; SCF=AkYTkdo2vVqUkrcW7F94nk_Vg0SuYS1C_agdN1Qz5Ek-gPP3QXRrH1xePoD37KJ5Y8bd0n2h7glZAQZqEzpg59U.; SUHB=0d4u0oQCvYk3oj; WEB3_PHP-FPM_BX=5757298fb390cb5c028cc6469aa9900f; _s_tentry=www.baidu.com; Apache=5356826972173.07.1489642521516; PHPSESSID=j678lpherr5q435es6qoqs31c5; WBStorage=02e13baf68409715|undefined',
        #'Connection': 'keep-alive',
        }
            r=requests.get("http://data.weibo.com/index/ajax/keywordzone?type=notdefault&wid="+id+"&__rnd=4516",headers=headers)
            return r.text

x=xl()
#print sys.argv[1]
print x.pc(sys.argv[1])
