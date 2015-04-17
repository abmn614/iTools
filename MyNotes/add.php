<?php 
$add = 'active';
if (!empty($_POST)) {
    date_default_timezone_set('PRC');
    $xml = new DOMDocument();
    $xml->load('data.xml');

    $note = $xml->createElement('note');
    $title = $xml->createElement('title', "{$_POST['title']}");
    $cdata = $xml->createCDATASection("{$_POST['content']}");
    $content = $xml->createElement('content');
    $content->appendChild($cdata);
    $time = $xml->createElement('time', date('Y-m-d H:i:s'));
    $note->appendChild($title);
    $note->appendChild($content);
    $note->appendChild($time);

    $noteslist = $xml->getElementsByTagName('noteslist');
    $noteslist->item(0)->appendChild($note);

    if ($xml->save('data.xml')) {
        header('location:index.php');
    } else {
        echo "<script>alert('添加出错啦')</script>";
    }
}

 ?>

<? include 'header.php'; ?>

<form action="add.php" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">标题：</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="title" name="title" placeholder="请输入标题">
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">内容：</label>
        <div class="col-sm-5">
            <textarea class="form-control" name="content" rows="10" placeholder="请输入内容"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-3 col-sm-offset-2">
            <input type="submit" value="添加笔记" class="btn btn-primary">
        </div>
    </div>
</form>

<script type="text/javascript">
    $("form").submit(function(){
        var title = $("input[name='title']").val();
        var content = $("textarea[name='content']").val();
        if (title == '' || content == '') {
            alert('标题和内容不能为空哟~~');
            return false;
        };

    });
</script>

<? include 'footer.php'; ?>