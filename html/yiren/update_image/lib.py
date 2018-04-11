#coding=utf-8
import sys
reload(sys)
import requests
import urllib
from xml.dom.minidom import parse
sys.setdefaultencoding('utf8')
class xl():
        def pc(self,page):
            cookie = 'BAIDUID=4384A847FE7B57E5FFD1B3DCC0E4E937:FG=1; BIDUPSID=5F5545705B26892F30C059D9BE799CE0; PSTM=1497966187; BDUSS=21Jb2xIN1JGVX55TGIyWk9uVDFxR0NmQ01rdG83SHFsa1dIWjB5d3RIWnNOM05aSVFBQUFBJCQAAAAAAAAAAAEAAABA8oQha2t4MjIxODgxMwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGyqS1lsqktZS; Hm_lvt_55b574651fcae74b0a9f1cf9c8d7c93a=1503144803,1503145425,1503818431; H_PS_PSSID=1420_21102_17001_20880; BDORZ=B490B5EBF6F3CD402E515D22BCDA1598; PSINO=1; Hm_lpvt_55b574651fcae74b0a9f1cf9c8d7c93a=1503818431; pgv_pvi=1007682560; pgv_si=s5660838912'
            headers={
            'Host': 'baike.baidu.com',
            'User-Agent': 'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language': 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding': 'gzip, deflate, br',
            'X-Requested-With': 'XMLHttpRequest',
            'Referer': 'https://baike.baidu.com/starrank?fr=lemmaxianhua',
            'Cookie': cookie,
            'Connection': 'keep-alive',
            }
            r=requests.get('https://baike.baidu.com/starflower/api/starflowerstarlist?rankType=thisWeek&pg='+str(page),headers=headers)
            return r.text

###定义数据库插入，更新操作函数
def sql_opt(db,cursor,str):
    try:
        cursor.execute(str)
        db.commit()
    except:
        db.rollback()
