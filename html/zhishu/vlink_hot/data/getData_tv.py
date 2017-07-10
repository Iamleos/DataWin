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
            'Host': 'www.xunyee.cn',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': '*/*',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate',
            'Referer': 'http://www.vlinkage.com/datatop.html',
            'Cookie': cookie,
            'Connection': 'keep-alive',
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
cookie = 'PHPSESSID=louj231cfaaddhqnemdpllsne1; SERVERID=1d8fb78101975df708d964699b9d56b4|1499346104|1499346104'
url = 'http://www.xunyee.cn/api/get_top_teleplay_date?callback=jsonp_1499346161993_14658418265219386&_time=1499346161993'
print x.pc(url,cookie)
