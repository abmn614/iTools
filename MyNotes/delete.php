<?php 

$time = $_GET['time'];

if (!empty($time)) {
    $xml = new DOMDocument();
    $xml->load('data.xml');

    $xpath = new DOMXpath($xml);
    $note = $xpath->query("/noteslist/note[time='{$time}']");
    $del = $note->item(0);
    if ($del) {
        $del->parentNode->removeChild($del);
    }

    if ($xml->save('data.xml')) {
        header('location:index.php');
    }else{
        echo "删除出错啦";
    }

}

