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
        def pc(self,name,url,cookie):
            url_name=urllib.quote(name)
            headers={
            'Host': 'index.baidu.com',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': '*/*',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate',
            'X-Requested-With': 'XMLHttpRequest',
            'Referer': 'http://index.baidu.com/?tpl=crowd&word='+url_name,
            'Cookie': cookie,
            'Connection': 'keep-alive',
            }
            r=requests.get(url,headers=headers)
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
cookie = 'Hm_lvt_d101ea4d2a5c67dab98251f0b5de24dc=1498131011,1498131631; searchtips=1; bdshare_firstime=1490089994923; BAIDUID=4384A847FE7B57E5FFD1B3DCC0E4E937:FG=1; BIDUPSID=5F5545705B26892F30C059D9BE799CE0; PSTM=1497966187; BDORZ=B490B5EBF6F3CD402E515D22BCDA1598; BDUSS=21Jb2xIN1JGVX55TGIyWk9uVDFxR0NmQ01rdG83SHFsa1dIWjB5d3RIWnNOM05aSVFBQUFBJCQAAAAAAAAAAAEAAABA8oQha2t4MjIxODgxMwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGyqS1lsqktZS; FP_UID=a536a677ba8fdcf99dba4f2bb623c7b8; H_PS_PSSID=1420_21102_17001_20880; Hm_lpvt_d101ea4d2a5c67dab98251f0b5de24dc=1498228886; CHKFORREG=e5cab12bb0f6bbd63d872bcce1d3cfe7'
name = sys.argv[1]
url='http://index.baidu.com/Interface/Social/getSocial/?res=KW4aeSZAVwwAER0ABgYnNxNOJ1UsFxsmAhQjCBIFJCM2DnANVFwHKj5FVw43IChhYEVzHVoxVXJkaAIiBQ4dBBkPencXKnA1WAdBNWQiAFQ2XCwXI18PCBckKjp2cTYweAYHcw03BAYiBhJLDG1pQ1NwNVIpOCxbWHojBBJTLQkjMxgmTBYXfCU5JCN0KAQwUTo5ElgEWh47ew12aBR2NANHdisrMCwyN0VLW3BtYQ5EDHduIxsXayM0dxYSMxMRBHYGAS4maAICZWkKIgYE&res2=dmyrCgK6BoG0hMBsCToAhG2QM4xpQCtEJJBfPOunodqTNoLLOg672.271672.271'
print x.pc(name,url,cookie)
