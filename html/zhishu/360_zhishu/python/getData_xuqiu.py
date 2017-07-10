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
        def pc(self,name,cookie):
            url_name=urllib.quote(name)
            headers={
            'Host': 'trends.so.com',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': 'application/json, text/plain, */*',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate',
            'X-Requested-With': 'XMLHttpRequest',
            'Referer': 'http://trends.so.com/result/trend?keywords='+url_name+'&time=30',
            'Cookie': cookie,
            'Connection': 'keep-alive',
            }
            params = {}
            params = json.dumps(params)
            r=requests.post("http://trends.so.com/index/radarJson?t=30&q="+url_name,headers=headers, data = params)
            return r.text

x=xl()
#解析account.xml文件
DOMTree = xml.dom.minidom.parse("/var/www/html/zhishu/360_zhishu/account.xml")
collection = DOMTree.documentElement
account = collection.getElementsByTagName("account")
#生成随机数，随机取cookie
rNum = random.randint(0,9)
cookie = account[0].getElementsByTagName("value")[0].childNodes[0].data
#XXT = 'fR9Ee1fb-uRpfIYyL1mGK-QfbGwQi8tiCU6Y'
#cookie = 'pgv_pvi=8950272000; pt2gguin=o0695781784; ptcz=7a713e29973a9d4d22ec8ef33e7b7dd2744a40d450b3e78b7ef3f8eb0cb8c010; _csrf=cesc7-Fv3kfPL-x4XQo5lDuY; XSRF-TOKEN=urI8WP4T-nf_4p-wOnxR6hZTGchrB1AXdi6w; pgv_si=s3545664512; ptisp=edu; ptui_loginuin=695781784; uin=o0695781784; skey=@9CHSOeBv8; p_uin=o0695781784; p_skey=8NFp4Z*cujvqSZ3d8uDiakYXFE3pBWx-T4hr*48TLzI_; pt4_token=gGGGgfInw0vGerIl*7e-RNqV8Q4zXYG8Hb1ZvarelHI_'
name = sys.argv[1]
#print now_date
print x.pc(name,cookie)
