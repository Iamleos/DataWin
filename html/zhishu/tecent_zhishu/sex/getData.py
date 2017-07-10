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
        def pc(self,name,last_date,now_date,XXT,cookie):
            url_name=urllib.quote(name)
            headers={
            'Host': 'tbi.tencent.com',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': 'application/json, text/plain, */*',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate',
            'Content-Type': 'application/json;charset=utf-8',
            'X-XSRF-TOKEN': XXT,
            'Referer': 'http://tbi.tencent.com/index?word='+url_name+'&date=1&type=0',
            'Content-Length': '85',
            'Cookie': cookie,
            'Connection': 'keep-alive',
            }
            params = {"tagId":"","tag":name,"profile":1,"type":0,"start":last_date,"end":now_date}
            params = json.dumps(params)
            r=requests.post("http://tbi.tencent.com/tbi/queryTagProfile",headers=headers, data = params)
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
#XXT = 'fR9Ee1fb-uRpfIYyL1mGK-QfbGwQi8tiCU6Y'
#cookie = 'pgv_pvi=8950272000; ptui_loginuin=695781784; pt2gguin=o0695781784; ptcz=7a713e29973a9d4d22ec8ef33e7b7dd2744a40d450b3e78b7ef3f8eb0cb8c010; _csrf=ABHfQHEsJOc9JIajz3ZgCI8K; XSRF-TOKEN=fR9Ee1fb-uRpfIYyL1mGK-QfbGwQi8tiCU6Y; pgv_si=s5330306048; ptisp=edu; uin=o0695781784; skey=@L5Z6NtgUx; p_uin=o0695781784; p_skey=Mm-kYHLx7f7incq*6m-bZ2YuCBYxOGEPOOwiLa28Jgs_; pt4_token=1jBWIRUymVxR35iulkJ5D11m5mnmwIZEMXcHU09sJr4_'
name = sys.argv[1]
last_date = sys.argv[2]
now_date = sys.argv[3]
#print now_date
print x.pc(name,last_date,now_date,XXT,cookie)
