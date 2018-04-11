#coding=utf-8
import sys
reload(sys)
sys.setdefaultencoding('utf8')
import MySQLdb
import re
import urllib
from lib import*
import time


###############连接数据库
yiren_db = MySQLdb.connect(host="56a3768226622.sh.cdb.myqcloud.com",user="root",passwd="ctfoxno1",port=4892,db="yiren",charset="utf8")
yiren_cursor = yiren_db.cursor()
yiren_cursor.execute("select me from actname;")
name = yiren_cursor.fetchall()
pachong = xl()
for value in name:
    value = value[0].decode('utf-8')
    print value
    yiren_cursor.execute("select gender from yiren_info where name='%s'"%('梁静'))
    gender = yiren_cursor.fetchone()[0]
    yiren_cursor.execute("select introduction from yiren_info where name='%s'"%(value))
    introduction = yiren_cursor.fetchone()[0]
    #print gender != 'NULL' or introduction !='NULL'
    #exit()
    if gender != 'NULL' or introduction !='NULL':
        print value+'exist'
        continue;

    result = pachong.pc(str(value))
    info = {
        'gender' : 'NULL',
        'introduction' : 'NULL',
    }
    introduction_pattern1 = re.compile(r'</dl><div class="lemmaWgt-lemmaSummary lemmaWgt-lemmaSummary-dark">\s*([\s\S]*?)</div>',re.M)
    introduction_pattern2 = re.compile(r'<div class="lemma-summary" label-module="lemmaSummary">\s*([\s\S]*)<div class="configModuleBanner">',re.M)
    introduction_pattern3 = re.compile(r'</dl><div class="lemmaWgt-lemmaSummary lemmaWgt-lemmaSummary-light">\s*([\s\S]*?)</div>',re.M)
    introduction1 = introduction_pattern1.findall(result)
    introduction2 = introduction_pattern2.findall(result)
    introduction3 = introduction_pattern3.findall(result)
    #print introduction2
    #exit()
    if introduction1 != []:
        introduction = introduction1
    elif introduction2 !=[]:
        introduction = introduction2
    elif introduction3 !=[]:
        introduction = introduction3
    else:
        continue
    introduction = introduction[0].encode('raw_unicode_escape')
    #print introduction
    #exit()

#########去括号，空白字符，以及选取前三句介绍
    introduction = del_blankchar(del_kuohao(introduction))
    introduction_split = introduction.split('。')
    introduction = ''
    flag = 0
    for value1 in introduction_split:
        if flag < 3:
            introduction += value1+'。'
            flag += 1
        else:
            break
    info['introduction'] = introduction
#############辨析性别
    if ("女演员" in introduction) or ("女艺人" in introduction) or ("女歌手" in introduction):
        info['gender'] = "女"
    elif ("男演员" in introduction) or ("男艺人" in introduction) or ("男歌手" in introduction):
        info['gender'] = "男"
    elif "男" in introduction:
        info['gender'] = "男"
    elif "女" in introduction:
        info['gender'] = "女"
    ##########定义正则模式
    ######去<>正则模式
    yiren_cursor.execute("update yiren_info set gender='%s', introduction='%s' where name='%s'"%(info['gender'],info['introduction'],value))
    yiren_db.commit()
    time.sleep(1)
    ###插入数据库
yiren_db.close()
