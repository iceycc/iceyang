<?php 
/**
 * 公共的函数封装
 */

// 载入配置文件  为了防止function重复被载入出错
require_once '../config.php';



/**
 * [icey_connent description]  根据配置文件链接数据库
 * @return [type] [description]   返回数据库连接对象
 */
function icey_connect(){
  
  $connection = mysqli_connect( ICEY_DB_HOST , ICEY_DB_USER , ICEY_DB_PASS , ICEY_DB_NAME );
  if (!$connection) {
    // 如果连接失败报错
    die('<h1>Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() . '</h1>');
  }
  // 设置数据库编码 规定当与数据库服务器进行数据传送时要使用的默认字符集
  mysqli_set_charset($connection,'utf8');
  return $connection;
}





/**
 * [xiu_get_current_user description]
 * @return [type] [description]
 */
session_start();
function icey_get_current_user () {
  // 拿到客户端请求带来的小票
  // 找到那个对应箱子，取出标识用户是否登录的数据
  // 根据这个数据判断用户是否登录

  // 如果不存在 is_logged_in 或者值为 false
  if (empty($_SESSION['current_login_user'])) {
    // 没有登录
    header('Location: /admin/login.php');
    return;
  }
  return $_SESSION['current_login_user'];
}