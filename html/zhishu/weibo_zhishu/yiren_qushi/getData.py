#coding=utf-8
import sys
reload(sys)
import requests
import urllib

class xl():
        def pc(self,id,sdate,edate):
            headers={
        'Host': 'data.weibo.com',
        'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding': 'gzip, deflate',
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
        'Referer': 'http://data.weibo.com/index/hotword',
	'Cookie' : 'UOR=os.51cto.com,widget.weibo.com,www.baidu.com; SINAGLOBAL=6177330910821.6455.1479996866703; ULV=1490703895723:15:9:1:6357418374937.262.1490703895719:1490342608174; SUBP=0033WrSXqPxfM72wWs9jqgMF55529P9D9WF89TI_0xIecGnkHmmz6bJm5JpV2hq4SKMReKMRS2WpMC4odcXt; SCF=AkYTkdo2vVqUkrcW7F94nk_Vg0SuYS1C_agdN1Qz5Ek-gPP3QXRrH1xePoD37KJ5Y8bd0n2h7glZAQZqEzpg59U.; SUHB=0d4u0oQCvYk3oj; WEB3=3494e3bb245670abc62f46d05878a7f3; _s_tentry=www.baidu.com; Apache=6357418374937.262.1490703895719; PHPSESSID=2eblmm1fm98dkio6qdb6d82ad1',
        'Connection': 'keep-alive',
        }
            r=requests.get("http://data.weibo.com/index/ajax/getchartdata?wid="+id+"&sdate="+sdate+"&edate="+edate+"&__rnd=1489559229891",headers=headers)
            return r.text

x=xl()
print x.pc(sys.argv[1],sys.argv[2],sys.argv[3])
