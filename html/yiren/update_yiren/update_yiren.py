#coding=utf-8
import sys
reload(sys)
sys.setdefaultencoding('utf8')
import MySQLdb
import re
#################连接tv,film,zy数据库
yiren_db = MySQLdb.connect(host="56a3768226622.sh.cdb.myqcloud.com",user="root",passwd="ctfoxno1",port=4892,db="yiren",charset="utf8")
tv_db = MySQLdb.connect(host="56a3768226622.sh.cdb.myqcloud.com",user="root",passwd="ctfoxno1",port=4892,db="TV",charset="utf8")
film_db = MySQLdb.connect(host="56a3768226622.sh.cdb.myqcloud.com",user="root",passwd="ctfoxno1",port=4892,db="movie",charset="utf8")
filmdaily_db = MySQLdb.connect(host="56a3768226622.sh.cdb.myqcloud.com",user="root",passwd="ctfoxno1",port=4892,db="filmdaily",charset="utf8")
tv_cursor = tv_db.cursor()
yiren_cursor = yiren_db.cursor()
film_cursor = film_db.cursor()
filmdaily_cursor = filmdaily_db.cursor()

#################从tv里面选出zzsy（正在上映）字段为1的数据，筛选出每条数据的导演，编剧，主演，并且去重复
tv_cursor = tv_db.cursor()
tv_cursor.execute("select daoyan,bianju,zhuyan from douban_tv where zzsy='1';")
daoyan = []
bianju = []
zhuyan = []
for value in tv_cursor.fetchall():
    dy = value[0].encode("utf-8")
    bj = value[1].encode("utf-8")
    zy = value[2].encode("utf-8")
    if dy != 'NULL' and dy != 'null' and dy != None:
        daoyan += dy.split(';')
    if zy != 'NULL' and zy != 'null' and zy != None:
        zhuyan += zy.split(';')
    if bj != 'NULL' and bj != 'null' and bj != None:
        bianju += bj.split(';')

        ###将所有的名字综合起来,并且插入到数据库中，已存在的忽略

yiren_name_from_tv = daoyan+bianju+zhuyan
for value in yiren_name_from_tv:
    if value == "":
        continue
    else:
        yiren_cursor.execute("select count(*) from actname where me = '%s'"%(value))
        count = yiren_cursor.fetchone()[0]
        if count == 1:
            print "exist"
            continue
        elif count == long(0):
            print value
            yiren_cursor.execute("insert into actname values('%s',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1')"%(value))
            yiren_db.commit()
        else:
            print "dumplicate"
            continue

###################从film里面选出zzsy（正在上映）字段为1的数据，筛选出导演，编剧，主演，并且去重复
filmdaily_cursor.execute("select dbname from filmname where zzsy = 1")
daoyan = []
zhuyan = []
for value in filmdaily_cursor.fetchall():
    film_cursor.execute("select daoyan,zhuyan from maoyan where movie = '%s';"%(value[0]))
    if film_cursor.rowcount == 1:
        data = film_cursor.fetchone()
        dy = data[0]
        zy = data[1]
        if dy != 'NULL' and dy != 'null' and dy != None:
            if dy.find(';') != -1:
                daoyan += dy.split(';')
            elif dy.find(',') != -1:
                daoyan += dy.split(',')
        if zy != 'NULL' and zy != 'null' and zy != None:
            if zy.find(';') != -1:
                zhuyan += zy.split(';')
            elif zy.find(',') != -1:
                zhuyan += zy.split(',')

                ###将所有的名字综合起来,并且插入到数据库中，已存在的忽略
yiren_name_from_film = daoyan+zhuyan
for value in yiren_name_from_film:
    if value == "" or value.find('·') != -1 or value.find('.') != -1 or re.search('[a-zA-Z]',value):
        continue
    else:
        yiren_cursor.execute("select count(*) from actname where me = '%s'"%(value))
        count = yiren_cursor.fetchone()[0]
        if count == 1:
            print "exist"
            continue
        elif count == long(0):
            print value
            yiren_cursor.execute("insert into actname values('%s',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1')"%(value))
            yiren_db.commit()
        else:
            print "dumplicate"
            continue
#####close database
tv_db.close()
yiren_db.close()
film_db.close()
filmdaily_db.close()
