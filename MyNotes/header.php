<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>我的笔记</title>
    <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <script src="http://cdn.bootcss.com/zclip/1.1.2/jquery.zclip.min.js"></script>
</head>

<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">我的笔记</a>
        <ul class="nav navbar-nav">
            <li class="<?=$index?>"><a href="index.php">笔记列表</a></li>
            <li class="<?=$add?>"><a href="add.php">添加笔记</a></li>
            <li class="<?=$search?>"><a href="search.php">搜索 </a></li>
        </ul>
    </div>
</nav>