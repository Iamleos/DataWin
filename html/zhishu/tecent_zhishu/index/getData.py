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
        def pc(self,name,date,XXT,cookie):
            url_name=urllib.quote(name)
            headers={
            'Host': 'tbi.tencent.com',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': 'application/json, text/plain, */*',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate',
            'Content-Type': 'application/json;charset=utf-8',
            'X-XSRF-TOKEN': XXT,
            'Referer': 'http://tbi.tencent.com/index?word='+url_name+'&date=1',
            'Content-Length': '76',
            'Cookie': cookie,
            'Connection': 'keep-alive',
            'Cache-Control': 'max-age=0',
            }
            params = {"tagId":"","tag":name,"type":0,"start":date,"end":date}
            params = json.dumps(params)
            r=requests.post("http://tbi.tencent.com/tbi/queryTagIndex",headers=headers, data = params)
            return r.text

x=xl()
#解析account.xml文件
DOMTree = xml.dom.minidom.parse("/var/www/html/zhishu/tecent_zhishu/account.xml")
collection = DOMTree.documentElement
account = collection.getElementsByTagName("account")
#生成随机数，随机取cookie
rNum = random.randint(0,9)
XXT = account[rNum].getElementsByTagName("key")[0].childNodes[0].data
cookie = account[rNum].getElementsByTagName("value")[0].childNodes[0].data
name = sys.argv[1]
date = sys.argv[2]
print x.pc(name,date,XXT,cookie)
