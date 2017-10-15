<?php 
require_once '../functions.php';

$email = $_GET['email'];

$conn = icey_connect();

$query= mysqli_query($conn,"select * from users where email ='$email'");

$user = mysqli_fetch_assoc($query);
// if (!$user) {
// 	# code...
// 	$avatar = '/static/assets/img/default.png';
// 	die($avatar);
// }
$avatar = isset($user['avatar']) ? $user['avatar'] : '/static/assets/img/default.png';


echo $avatar;


