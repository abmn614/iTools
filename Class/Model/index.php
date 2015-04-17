<?php 
require('ModelClass.php');

$arr = array(
    'type'      => 'mysql',
    'host'      => 'localhost',
    'port'      => 3306,
    'dbname'    => 'test',
    'username'  => 'root',
    'password'  => '',
    'names'     => 'utf8'
    );

$db = M($arr, 'user');

$select = $db->where('id < 10')->limit('1,3')->order('id desc')->select('id,user');

// $count = $db->count();

// $data = array(
//     'user' => 'abc',
//     'age'   => 20,
//     );
// $insert = $db->insert($data);

// $data = array(
//     'user' => 'dlm',
//     'age'   => 24,
//     );
// $update = $db->where('id<10')->update($data);

// $del = $db->where('id>0 and id< 6')->delete();

echo "<pre>";
print_r($select);
echo "</pre>";






