<?php 

$index = 'active';
$xml = new DOMDocument();
$xml->load('data.xml');
$notes = $xml->getElementsByTagName('note');
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
    echo "<script>alert('还木有笔记哇，先来添加点吧~');location.href='add.php'</script>";
}

 ?>

<? include 'header.php'; ?>

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

<script type="text/javascript">
    $(".del").click(function(){
        if (!confirm('确定要删除吗？')) {
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

<? include 'footer.php'; ?>