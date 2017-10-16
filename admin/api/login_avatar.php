<?php 


$email = $_GET['email'];

// 连接数据库
require_once '../functions.php';

$conn = icey_connect();



$query= mysqli_query($conn,"select * from users where email ='$email'");

$user = mysqli_fetch_assoc($query);
 

$avatar = isset($user['avatar']) ? $user['avatar'] : '/static/assets/img/default.png';


echo $avatar;


