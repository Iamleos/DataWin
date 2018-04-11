#coding=utf-8
import sys
reload(sys)
import os
sys.setdefaultencoding('utf8')
import MySQLdb
import re
import urllib
from lib import*
import time
import demjson

x=xl()

yiren_db = MySQLdb.connect(host="56a3768226622.sh.cdb.myqcloud.com",user="root",passwd="ctfoxno1",port=4892,db="yiren",charset="utf8")
yiren_cursor = yiren_db.cursor()


for i in range(0,50):
    data = x.pc(i)
    data = demjson.decode(data)['data']['thisWeek']
    for value in data:
        name = value['name'].encode('utf8')
        print name,i
        if '·' in name:
            continue
        yiren_cursor.execute("select count(*) from yiren_image_url where name = '%s'"%(name))
        if yiren_cursor.fetchone()[0] !=0:
            continue
        image_url = value['picUrl']
        sql_opt(yiren_db,yiren_cursor,"insert into yiren_image_url values('%s','%s')"%(name,image_url))
        time.sleep(1)
