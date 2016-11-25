<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!-- saved from url=(0038)http://v3.bootcss.com/examples/signin/ -->
<html lang="zh-CN"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/V/Public/favicon.ico" type="image/x-icon">

    <title>德塔文大数据</title>

    <!-- Bootstrap core CSS -->
    <link href="/V/Public/boot/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/V/Public/boot/css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="/V/Public/boot/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/V/Public/boot/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">

    <form action="<?php echo U('Home/User/upload');?>" enctype="multipart/form-data" method="post" >
    <div class="form-group">
            <label for="title">标题</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="请输入标题">
        </div>
        <!--<div class="form-group">-->
            <!--<label for="file">File input</label>-->
            <!--<input type="file" id="file" name="docs[]">-->
            <!--<p class="help-block">Example block-level help text here.</p>-->
        <!--</div>-->
        <div class="form-group">
            <label for="title">请输入链接</label>
            <input type="text" class="form-control" id="url" name="url" placeholder="请输入链接">
        </div>
        <div class="form-group">
	    <label for="title">文章分类</label>
            <select class="form-control" id="select" name="select">
                <option value="今日晨读">今日晨读</option>
                <option value="艺人数据分析">艺人数据分析</option>
                <option value="电影数据分析">电影数据分析</option>
                <option value="影视项目总分析">影视项目总分析</option>
                <option value="行业研究">行业研究</option>
                <option value="月咨询总结">月咨询总结</option>
                <option value="最新消息">最新消息</option>
            </select>
        </div>
        <div class="form-group">
            <label for="photo">图片</label>
            <input type="file" id="photo" name="docs[]">
        </div>
        <button id="btn" type="submit" class="btn btn-default">提交</button>
    </form>

</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/V/Public/boot/js/ie10-viewport-bug-workaround.js"></script>

</body></html>