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
            'Referer': 'http://index.baidu.com/?tpl=demand&word='+url_name,
            'Cookie': cookie,
            'Connection': 'keep-alive',
            }
            r=requests.post(url+url_name,headers=headers)
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
cookie = 'Hm_lvt_d101ea4d2a5c67dab98251f0b5de24dc=1498131011,1498131631; searchtips=1; bdshare_firstime=1490089994923; BAIDUID=4384A847FE7B57E5FFD1B3DCC0E4E937:FG=1; BIDUPSID=5F5545705B26892F30C059D9BE799CE0; PSTM=1497966187; BDORZ=B490B5EBF6F3CD402E515D22BCDA1598; H_PS_PSSID=1420_21102_17001; PSINO=1; CHKFORREG=e5cab12bb0f6bbd63d872bcce1d3cfe7; Hm_lpvt_d101ea4d2a5c67dab98251f0b5de24dc=1498133641; BDUSS=21Jb2xIN1JGVX55TGIyWk9uVDFxR0NmQ01rdG83SHFsa1dIWjB5d3RIWnNOM05aSVFBQUFBJCQAAAAAAAAAAAEAAABA8oQha2t4MjIxODgxMwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGyqS1lsqktZS; FP_UID=a536a677ba8fdcf99dba4f2bb623c7b8'
name = sys.argv[1]
url = 'http://index.baidu.com/Interface/Newwordgraph/?res=AWw1MS0jcDp6CiE9SycEdXpjQjdcMkR6dAsBXiVhICUhNAx3XBJ+XR5KVQILCWVCADhEMwY/eRdRNBgJdiliQgZCfiAjDxYQfgAUAQt2fyc0K0FUJwpGJXZeegkQLDwkISoiFxUNUWgjKQgtLwQFBUYedB9VNHotOQEBe243BCESP1MdBxUYE3AfDnAzOQxzNSxVGiRmGngEVVcAJHA3Xz8PLwU/BUJreWN9BmtTA3s8NTMyIlh+bgJLNgMACQZ3SkMBDAV9N3NZCz9vJxIvcmYjOCU7LA==&res2=212.39181EXSTRq0bmQEKorGHzF4VRdRQMeMmLxs0ztJ4ollx9kOA6FNNt0cJAPy520.332716.181&word='
print x.pc(name,url,cookie)
