<?php 

$search = 'active';
$text = $_POST['text'];
if (!empty($text)) {
    $xml = new DOMDocument();
    $xml->load('data.xml');
    $xpath = new DOMXPath($xml);

    $notes = $xpath->query("/noteslist//note[contains(title, '{$text}') or contains(content, '{$text}')]");
    if ($notes->item(0)->nodeValue) {
        foreach ($notes as $note) {
            $titles = $note->getElementsByTagName('title');
            $title[] = $titles->item(0)->nodeValue;
            $contents = $note->getElementsByTagName('content');
            $content[] = $contents->item(0)->nodeValue;
            $times = $note->getElementsByTagName('time');
            $time[] = $times->item(0)->nodeValue;
        }
    } else {
        echo "<script>alert('啥都没找到');location.href='search.php';</script>";
    }
}
 ?>

<? include 'header.php'; ?>

<form action="search.php" method="post">
    <div class="input-group col-md-4">
        <input type="text" class="form-control" name="text" placeholder="今天搜点啥好呢...">
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">给我搜</button>
        </span>
    </div>
</form>
<br />
<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>内容</th>
        <th>时间</th>
        <th style="width: 100px;">操作</th>
    </tr>
    <?php for ($i=0; $i < count($title); $i++) { ?>
    <tr>
        <td><?=$i+1?></td>
        <td class="title"><?=$title[$i]?></td>
        <td class="content"><?=$content[$i]?></td>
        <td><?=$time[$i]?></td>
        <td>
            <a href="alter.php?time=<?=$time[$i]?>" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
            <a href="delete.php?time=<?=$time[$i]?>" class="btn btn-danger del"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
    </tr>
    <?php } ?>
</table>

<? include 'footer.php'; ?>

<script type="text/javascript">
    $('form').submit(function(){
        if ($("input[name='text']").val() == '') {
            alert("让我搜啥啊");
            $("input[name='text']").focus();
            return false;
        };
    });

    $('.title, .content').hover(function(){
        $(this).zclip({
            path:'http://cdn.bootcss.com/zclip/1.1.2/ZeroClipboard.swf',
            copy:$(this).text(),
            afterCopy:function(){},
        });
    });

</script>