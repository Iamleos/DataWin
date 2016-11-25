# -*- coding:utf-8 -*-
import urllib
import urllib2
import re
import MySQLdb
from bs4 import BeautifulSoup

movie = raw_input('please enter movie: ')
URL = 'http://www.cbooo.cn/search?k='+movie

htmlPage = urllib2.urlopen(URL).read()
soup = BeautifulSoup(htmlPage,"lxml")
pattern = re.compile(r'(http:\/\/www.cbooo.cn\/m\/)([0-9]*)')
url = re.search(pattern,str(soup.find_all('a'))).group( )

print url

conn= MySQLdb.connect(
        host='56a3768226622.sh.cdb.myqcloud.com',
        port = 4892,
        user='root',
        passwd='ctfoxno1',
        db ='movie',
        charset='utf8'
        )
cur = conn.cursor()

user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
headers = { 'User-Agent' : user_agent }
request = urllib2.Request(url,headers = headers)
response = urllib2.urlopen(request).read()
#print response
pat = re.compile('<dt>(.*?)</dt>.*?<dd>(.*?)</dd>',re.S)
items = re.findall(pat,response)
zz = re.compile(r'<a.*?>(.*?)</a>',re.S)
op = [movie]
for item in items:
#  print item[0]
  tests = re.findall(zz,item[1])
  for test in tests:
#    print '**************************'+test+'*****************************'
    e =""+test
#    print e
    op.append(e)
#  print'***************************'
for key in op:
  print key
sql = "INSERT INTO yien(movie, \
       daoyan, zhuyan, zhipian, faxing) \
       VALUES ('%s', '%s', '%s', '%s', '%s' )" % \
       ( op[0], op[1], op[2], op[3], op[4])
# 执行sql语句
cur.execute(sql)
# 提交到数据库执行
conn.commit()
