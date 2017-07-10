#coding=utf-8
import sys
reload(sys)
import requests
import urllib
import json
from xml.dom.minidom import parse
import xml.dom.minidom
import random
sys.setdefaultencoding('utf8')
class xl():
        def pc(self,url,cookie):
            headers={
            'Host': 'www.cbooo.cn',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': 'application/json, text/javascript, */*; q=0.01',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate',
            'X-Requested-With': 'XMLHttpRequest',
            'Referer': 'http://www.cbooo.cn/Teleplay',
            'Cookie': cookie,
            'Connection': 'keep-alive',
            'Cache-Control': 'max-age=0'
            }
            r=requests.post(url,headers=headers)
            return r.text

x=xl()
#解析account.xml文件
#DOMTree = xml.dom.minidom.parse("../account.xml")
#collection = DOMTree.documentElement
#account = collection.getElementsByTagName("account")
#生成随机数，随机取cookie
#rNum = random.randint(0,9)
#cookie = account[rNum].getElementsByTagName("value")[0].childNodes[0].data
#XXT = 'QfxWeSQk-HD-80cakC0aGzuJms14J4ArIDhI'
cookie = 'Hm_lvt_daabace29afa1e8193c0e3000d391562=1499341183; bdshare_firstime=1480600696858; Hm_lpvt_daabace29afa1e8193c0e3000d391562=1499341209'
arg = sys.argv[1];
url = 'http://www.cbooo.cn/Mess/GetDayPlays?tvType='+arg
print x.pc(url,cookie)
