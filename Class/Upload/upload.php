<?php 
include('UploadClass.php');

$config = array(
    'path'  => 'uploads',
    'maxsize'   => 1024*1024*1024,
    'allowtype' => array('jpg', 'jpeg', 'png', 'gif'),
    );

$up = new UploadFile;
if($up->upload('file', $config)){
    echo "<pre>";
    print_r($up->getFileName());
    echo "</pre>";

}else{
    echo "<pre>";
    print_r($up->getErrorMsg());
    echo "</pre>";
    
}
