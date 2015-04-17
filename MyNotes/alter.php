<?php 
$index = 'active';
date_default_timezone_set('PRC');
$xml = new DOMDocument();
$xml->load('data.xml');

$time = empty($_POST['time']) ? $_GET['time'] : $_POST['time'];

$xpath = new DOMXPath($xml);
$timeObj = $xpath->query("/noteslist/note[time='{$time}']/time");
$titleObj = $xpath->query("/noteslist/note[time='{$time}']/title");
$contentObj = $xpath->query("/noteslist/note[time='{$time}']/content");
$return = false;

if (!empty($_POST['title'])) {
    $titleObj->item(0)->nodeValue = $_POST['title'];
    $return = true;
}
if (!empty($_POST['content'])) {
    $parentNode = $contentObj->item(0)->parentNode;
    $contentObj->item(0)->parentNode->removeChild($contentObj->item(0));
    $cdata = $xml->createCDATASection("{$_POST['content']}");
    $content = $xml->createElement('content');
    $content->appendChild($cdata);
    $parentNode->appendChild($content);
    $return = true;
}
if (!empty($_POST)) {
    $timeObj->item(0)->nodeValue = date('Y-m-d H:i:s');
}
if ($return) {
    if ($xml->save('data.xml')) {
        header("location:index.php");
    }else{
        echo "<script>alert('修改出错啦')</script>";
    }
}

 ?>


<? include 'header.php'; ?>

<form action="alter.php" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">标题：</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="title" name="title" value="<?=$titleObj->item(0)->nodeValue?>">
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">内容：</label>
        <div class="col-sm-5">
            <textarea class="form-control" name="content" rows="10"><?=$contentObj->item(0)->nodeValue?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-3 col-sm-offset-2">
        <input type="hidden" name="time" value="<?=$timeObj->item(0)->nodeValue?>">
            <input type="submit" value="提交修改" class="btn btn-primary">
        </div>
    </div>
</form>

<? include 'footer.php'; ?>