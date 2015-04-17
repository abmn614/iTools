<?php 

include('ImageClass.php');


$img = new Image('1.jpg');

// $img->thumb('width', 500)->output('show');
$img->thumb('width', 500)->fontmark('bottom-right', 30, 0, 'white', 50, 'msyh.ttf', '我爱多多')->output('show');

