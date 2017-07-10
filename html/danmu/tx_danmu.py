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
        def pc(self,request_url,cookie):
            headers={
            'Host': 'service.danmu.youku.com',
            'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate',
            'Referer': 'http://static.youku.com/v201706281000.0/v/swf/upsplayer/PanelDanmuYouku.swf',
            'Cookie': cookie,
            'Connection': 'keep-alive',
	    'Content-type': 'application/x-www-form-urlencoded',
	    'Content-length': '89'
            }
            r=requests.post(request_url,headers=headers)
            return r.text

x=xl()
cookie = 'ysestep=2; yseidcount=2; ystep=4; juid=01bjn788261635; __ysuid=1498649208313lqa; cna=UHXaETU7gngCAd8DSCVgA/64; __aysid=1498649207097YvC; __ayspstp=6; isg=An19CKC7-M-yYVwjPQk_-HCFjdmoGflJkpjaBT_DoVQDdp-oAWpOPkNsVpHO; __aryft=1498650705; __ayft=1498650935085; __arpvid=1498650939877wh4ICj-1498650939884; __arycid=dd-3-2038-2074-307289-701656525; __ayscnt=1; __arcms=dd-3-2038-2074-307289-701656525; __aypstp=3; ypvid=1498650939726ld8A2h; yseid=1498650935426tZvb37; yseidtimeout=1498658139728; ycid=0; seid=01bjn8t0492k48; referhost=http%3A%2F%2Fwww.soku.com; seidtimeout=1498652739731; P_ck_ctl=23ACDF49514FE1523AD9DB936F8CF458; __ayvstp=3; __aysvstp=3'
request_url = 'http://service.danmu.youku.com/list?2784'
print x.pc(request_url,cookie)
