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
    yiren_cursor.execute("select count(*) from yiren_info where name='%s'"%(value))
    if yiren_cursor.fetchone()[0] != 0:
        print value+'exist'
        continue;
    result = pachong.pc(str(value))
    info = {
        'guoji' : 'NULL',
        'mingzu' : 'NULL',
        'xingzuo' : 'NULL',
        'xuexing' : 'NULL',
        'shengao' : 'NULL',
        'tizhong' : 'NULL',
        'chushengdi' : 'NULL',
        'chushengriqi' : 'NULL',
        'zhiye' : 'NULL',
        'biyexuexiao' : 'NULL',
        'jinjigongsi' : 'NULL',
        'daibiaozuoping' : 'NULL',
        'bieming' : 'NULL',
        'zhuyaochengjiu' : 'NULL',
        'changpiangongsi' : 'NULL',
        'peiou' : 'NULL',
        'nver' : 'NULL',
        'erzi' : 'NULL'
    }
    ##########定义正则模式
    ######去<>正则模式
    del_kuohao = re.compile(r'<.*?>')
    ######数据爬虫正则模式
    guoji_pattern = re.compile(r'国&nbsp;&nbsp;&nbsp;&nbsp;籍</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    mingzu_pattern = re.compile(r'民&nbsp;&nbsp;&nbsp;&nbsp;族</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    xingzuo_pattern = re.compile(r'星&nbsp;&nbsp;&nbsp;&nbsp;座</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    xuexing_pattern = re.compile(r'血&nbsp;&nbsp;&nbsp;&nbsp;型</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    shengao_pattern = re.compile(r'身&nbsp;&nbsp;&nbsp;&nbsp;高</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    tizhong_pattern = re.compile(r'体&nbsp;&nbsp;&nbsp;&nbsp;重</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    chushengdi_pattern = re.compile(r'出生地</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    chushengriqi_pattern = re.compile(r'出生日期</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    zhiye_pattern = re.compile(r'职&nbsp;&nbsp;&nbsp;&nbsp;业</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    biyexuexiao_pattern = re.compile(r'毕业院校</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    jinjigongsi_pattern = re.compile(r'经纪公司</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    daibiaozuoping_pattern = re.compile(r'代表作品</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    bieming_pattern = re.compile(r'别&nbsp;&nbsp;&nbsp;&nbsp;名</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    zhuyaochengjiu_pattern = re.compile(r'主要成就</dt>\s*<dd class="basicInfo-item value">\s*([\s\S]*?)\s*</dd>',re.M)
    changpiangongsi_pattern = re.compile(r'唱片公司</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    peiou_pattern = re.compile(r'配&nbsp;&nbsp;&nbsp;&nbsp;偶</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    nver_pattern = re.compile(r'女&nbsp;&nbsp;&nbsp;&nbsp;儿</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)
    erzi_pattern = re.compile(r'儿&nbsp;&nbsp;&nbsp;&nbsp;子</dt>\s*<dd class="basicInfo-item value">\s*(.*)\s*</dd>',re.M)

    guoji = guoji_pattern.findall(result)
    mingzu = mingzu_pattern.findall(result)
    xingzuo = xingzuo_pattern.findall(result)
    xuexing = xuexing_pattern.findall(result)
    shengao = shengao_pattern.findall(result)
    tizhong = tizhong_pattern.findall(result)
    chushengdi = chushengdi_pattern.findall(result)
    chushengriqi = chushengriqi_pattern.findall(result)
    zhiye = zhiye_pattern.findall(result)
    biyexuexiao = biyexuexiao_pattern.findall(result)
    jinjigongsi = jinjigongsi_pattern.findall(result)
    daibiaozuoping = daibiaozuoping_pattern.findall(result)
    bieming = bieming_pattern.findall(result)
    zhuyaochengjiu = zhuyaochengjiu_pattern.findall(result)
    changpiangongsi = changpiangongsi_pattern.findall(result)
    peiou = peiou_pattern.findall(result)
    nver = nver_pattern.findall(result)
    erzi = erzi_pattern.findall(result)

    if daibiaozuoping != []:
        daibiaozuoping = daibiaozuoping[0].encode('raw_unicode_escape')
        daibiaozuoping = del_kuohao.sub('',daibiaozuoping)
        daibiaozuoping = daibiaozuoping.replace('&nbsp;','')
        daibiaozuoping = daibiaozuoping.replace('主要成就;','')
        daibiaozuoping = daibiaozuoping.replace('\r;','')
        daibiaozuoping = daibiaozuoping.replace('\n;','')
        daibiaozuoping = daibiaozuoping.replace('\t;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        daibiaozuoping = pattern.sub('',daibiaozuoping)
        if '、' in daibiaozuoping:
            daibiaozuoping = daibiaozuoping.replace('、',',')
        elif ',' in daibiaozuoping:
            daibiaozuoping = daibiaozuoping.replace(',',',')
        elif '，' in daibiaozuoping:
            daibiaozuoping = daibiaozuoping.replace('，',',')
        elif ';' in daibiaozuoping:
            daibiaozuoping = daibiaozuoping.replace(';',',')
        elif '；' in daibiaozuoping:
            daibiaozuoping = daibiaozuoping.replace('；',',')
        info['daibiaozuoping'] = daibiaozuoping
    if bieming != []:
        bieming = bieming[0].encode('raw_unicode_escape')
        bieming = del_kuohao.sub('',bieming)
        pattern = re.compile(r'（.*?）',re.M)
        bieming = pattern.sub('',bieming)
        pattern = re.compile(r'\(.*?\)',re.M)
        bieming = pattern.sub('',bieming)
        bieming = bieming.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        bieming = pattern.sub('',bieming)
        if '、' in bieming:
            bieming = bieming.replace('、',',')
        elif ',' in bieming:
            bieming = bieming.replace(',',',')
        elif '，' in bieming:
            bieming = bieming.replace('，',',')
        elif ';' in bieming:
            bieming = bieming.replace(';',',')
        elif '；' in bieming:
            bieming = bieming.replace('；',',')
        info['bieming'] = bieming
    if zhuyaochengjiu != []:
        zhuyaochengjiu = zhuyaochengjiu[0].encode('raw_unicode_escape')
        zhuyaochengjiu = zhuyaochengjiu.replace('<br/>',',')
        zhuyaochengjiu = del_kuohao.sub('',zhuyaochengjiu)
        zhuyaochengjiu = zhuyaochengjiu.replace('&nbsp;','')
        zhuyaochengjiu = zhuyaochengjiu.replace('主要成就','')
        zhuyaochengjiu = zhuyaochengjiu.replace('\r','')
        zhuyaochengjiu = zhuyaochengjiu.replace('\n','')
        zhuyaochengjiu = zhuyaochengjiu.replace('\t','')
        pattern = re.compile(r'\[.*?\]',re.M)
        zhuyaochengjiu = pattern.sub('',zhuyaochengjiu)
        if '、' in zhiye:
            zhiye = zhiye.replace('、',',')
        elif ',' in zhiye:
            zhiye = zhiye.replace(',',',')
        elif '，' in zhiye:
            zhiye = zhiye.replace('，',',')
        elif ';' in zhiye:
            zhiye = zhiye.replace(';',',')
        elif '；' in zhiye:
            zhiye = zhiye.replace('；',',')
        info['zhuyaochengjiu'] = zhuyaochengjiu
    if changpiangongsi != []:
        changpiangongsi = changpiangongsi[0].encode('raw_unicode_escape')
        changpiangongsi = del_kuohao.sub('',changpiangongsi)
        changpiangongsi = changpiangongsi.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        changpiangongsi = pattern.sub('',changpiangongsi)
        info['changpiangongsi'] = changpiangongsi
    if peiou != []:
        peiou = peiou[0].encode('raw_unicode_escape')
        peiou = del_kuohao.sub('',peiou)
        peiou = peiou.replace('&nbsp;','')
        pattern = re.compile(r'（.*?）',re.M)
        peiou = pattern.sub('',peiou)
        pattern = re.compile(r'\(.*?\)',re.M)
        peiou = pattern.sub('',peiou)
        pattern = re.compile(r'\[.*?\]',re.M)
        peiou = pattern.sub('',peiou)
        info['peiou'] = peiou
    if nver != []:
        nver = nver[0].encode('raw_unicode_escape')
        nver = del_kuohao.sub('',nver)
        nver = nver.replace('&nbsp;','')
        pattern = re.compile(r'（.*?）',re.M)
        nver = pattern.sub('',nver)
        pattern = re.compile(r'\(.*?\)',re.M)
        nver = pattern.sub('',nver)
        pattern = re.compile(r'\[.*?\]',re.M)
        nver = pattern.sub('',nver)
        info['nver'] = nver
    if erzi != []:
        erzi = erzi[0].encode('raw_unicode_escape')
        erzi = del_kuohao.sub('',erzi)
        erzi = erzi.replace('&nbsp;','')
        pattern = re.compile(r'（.*?）',re.M)
        erzi = pattern.sub('',erzi)
        pattern = re.compile(r'\(.*?\)',re.M)
        erzi = pattern.sub('',erzi)
        pattern = re.compile(r'\[.*?\]',re.M)
        erzi = pattern.sub('',erzi)
        info['erzi'] = erzi
    if guoji != []:
        guoji = guoji[0].encode('raw_unicode_escape')
        guoji = del_kuohao.sub('',guoji)
        guoji = guoji.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        guoji = pattern.sub('',guoji)
        if ('中' in guoji) and ('国' in guoji):
            guoji = '中国'
        info['guoji'] = guoji
    if mingzu != []:
        mingzu = mingzu[0].encode('raw_unicode_escape')
        mingzu = del_kuohao.sub('',mingzu)
        mingzu = mingzu.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        mingzu = pattern.sub('',mingzu)
        info['mingzu'] = mingzu
    if xingzuo != []:
        xingzuo = xingzuo[0].encode('raw_unicode_escape')
        xingzuo = del_kuohao.sub('',xingzuo)
        xingzuo = xingzuo.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        xingzuo = pattern.sub('',xingzuo)
        info['xingzuo'] = xingzuo
    if xuexing != []:
        xuexing = xuexing[0].encode('raw_unicode_escape')
        xuexing = del_kuohao.sub('',xuexing)
        xuexing = xuexing.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        xuexing = pattern.sub('',xuexing)
        info['xuexing'] = xuexing
    if shengao != []:
        shengao = shengao[0].encode('raw_unicode_escape')
        shengao = del_kuohao.sub('',shengao)
        pattern = re.compile(r'（.*?）',re.M)
        shengao = pattern.sub('',shengao)
        pattern = re.compile(r'\(.*?\)',re.M)
        shengao = pattern.sub('',shengao)
        shengao = shengao.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        shengao = pattern.sub('',shengao)
        if ('cm' in shengao) or ('厘米' in shengao) or ('CM' in shengao) or ('㎝' in shengao) or ('Cm' in shengao) or ('cM' in shengao) or ('公分' in shengao):
            shengao = shengao.replace('cm','')
            shengao = shengao.replace('CM','')
            shengao = shengao.replace('厘米','')
            shengao = shengao.replace('㎝','')
            shengao = shengao.replace('Cm','')
            shengao = shengao.replace('cM','')
            shengao = shengao.replace('公分','')
        elif ('m' in shengao) or ('米' in shengao) or ('M' in shengao):
            shengao = shengao.replace('m','')
            shengao = shengao.replace('米','')
            shengao = shengao.replace('M','')
            if shengao.isdigit() == True:
                shengao = str(float(shengao)*100)
            else:
                shengao = 'NULL'

        else:
            shengao = 'NULL'
        info['shengao'] = shengao
    if tizhong != []:
        tizhong = tizhong[0].encode('raw_unicode_escape')
        tizhong = del_kuohao.sub('',tizhong)
        tizhong = tizhong.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        tizhong = pattern.sub('',tizhong)
        if ('kg' in tizhong) or ('公斤' in tizhong) or ('KG' in tizhong) or ('Kg' in tizhong) or ('kG' in tizhong) or ('千克' in tizhong):
            tizhong = tizhong.replace('kg','')
            tizhong = tizhong.replace('公斤','')
            tizhong = tizhong.replace('KG','')
            tizhong = tizhong.replace('Kg','')
            tizhong = tizhong.replace('kG','')
            tizhong = tizhong.replace('千克','')
            if tizhong.isdigit() == True:
                pass
            else:
                tizhong = 'NULL'
        elif '斤' in tizhong:
            tizhong = tizhong.replace('斤','')
            if tizhong.isdigit() == True:
                tizhong = str(float(tizhong)/2)
            else:
                tizhong = 'NULL'
        elif '磅' in tizhong:
            tizhong = tizhong.replace('磅','')
            if tizhong.isdigit() == True:
                tizhong = str(float(tizhong)*0.45359237)
            else:
                tizhong = 'NULL'
        info['tizhong'] = tizhong
    if chushengdi != []:
        chushengdi = chushengdi[0].encode('raw_unicode_escape')
        chushengdi = del_kuohao.sub('',chushengdi)
        chushengdi = chushengdi.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        chushengdi = pattern.sub('',chushengdi)
        info['chushengdi'] = chushengdi
    if chushengriqi != []:
        chushengriqi = chushengriqi[0].encode('raw_unicode_escape')
        chushengriqi = del_kuohao.sub('',chushengriqi)
        chushengriqi = chushengriqi.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        chushengriqi = pattern.sub('',chushengriqi)
        pattern = re.compile(r'\(.*?\)',re.M)
        chushengriqi = pattern.sub('',chushengriqi)
        pattern = re.compile(r'（.*?）',re.M)
        chushengriqi = pattern.sub('',chushengriqi)
        pattern = re.compile(r'\(.*?）',re.M)
        chushengriqi = pattern.sub('',chushengriqi)
        pattern = re.compile(r'（.*?\)',re.M)
        chushengriqi = pattern.sub('',chushengriqi)
        if re.search(r'[0-9]{0,4}年[0-9]{1,2}月[0-9]{1,2}日{0,1}',chushengriqi) == None:
            chushengriqi = 'NULL'
        info['chushengriqi'] = chushengriqi
    if zhiye != []:
        zhiye = zhiye[0].encode('raw_unicode_escape')
        zhiye = del_kuohao.sub('',zhiye)
        zhiye = zhiye.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        zhiye = pattern.sub('',zhiye)
        if '、' in zhiye:
            zhiye = zhiye.replace('、',',')
        elif ',' in zhiye:
            zhiye = zhiye.replace(',',',')
        elif '，' in zhiye:
            zhiye = zhiye.replace('，',',')
        elif ';' in zhiye:
            zhiye = zhiye.replace(';',',')
        elif '；' in zhiye:
            zhiye = zhiye.replace('；',',')
        info['zhiye'] = zhiye
    if biyexuexiao != []:
        biyexuexiao = biyexuexiao[0].encode('raw_unicode_escape')
        biyexuexiao = del_kuohao.sub('',biyexuexiao)
        biyexuexiao = biyexuexiao.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        biyexuexiao = pattern.sub('',biyexuexiao)
        info['biyexuexiao'] = biyexuexiao
    if jinjigongsi != []:
        jinjigongsi = jinjigongsi[0].encode('raw_unicode_escape')
        jinjigongsi = del_kuohao.sub('',jinjigongsi)
        jinjigongsi = jinjigongsi.replace('&nbsp;','')
        pattern = re.compile(r'\[.*?\]',re.M)
        jinjigongsi = pattern.sub('',jinjigongsi)
        info['jinjigongsi'] = jinjigongsi
    yiren_cursor.execute("insert into yiren_info values(null,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')"%(value,info["guoji"],info["mingzu"],info["xingzuo"],info["xuexing"],info["shengao"],info["tizhong"],info["chushengdi"],info["chushengriqi"],info["zhiye"],info["biyexuexiao"],info["jinjigongsi"],info["daibiaozuoping"],info["bieming"],info["zhuyaochengjiu"],info["changpiangongsi"],info["peiou"],info["nver"],info["erzi"]))
    yiren_db.commit()
    print value
    time.sleep(1)
    ###插入数据库
yiren_db.close()
