<?php 
require_once '../functions.php';

// 
if (empty($_GET['id'])) {
	# code...
	exit('没得到正确的id');
}
$id = $_GET['id'];

$sql = 'delete from categories where id = ' . $id; 
icey_execute ($sql);

header('Location:/admin/categories.php');