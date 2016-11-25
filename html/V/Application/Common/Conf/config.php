<?php
return array(
	//'配置项'=>'配置值'
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '56a3768226622.sh.cdb.myqcloud.com', // 服务器地址
    'DB_NAME'   => 'V', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'ctfoxno1', // 密码
    'DB_PORT'   => 4892, // 端口
    'DB_PREFIX' => 'tp_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'TMPL_PARSE_STRING' =>  array( // 地址替换,用_UPLOAD_目录 代替 根目录下的Upload目录
        '__UPLOAD__'    =>  __ROOT__.'/Uploads',
    ),
);