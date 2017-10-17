<?php 
require_once '../functions.php';
// 1 接受请求过来的id值,并校验
// 2 链接数据库,找到该id对应的那一行数据,删除它
// 3 返回
// 
if (empty($_GET['id'])) {
	# code...
	exit('没得到正确的id');
}
$id = $_GET['id'];

$sql = 'delete from users where id = ' . $id; 
icey_execute ($sql);

header('Location:/admin/users.php');