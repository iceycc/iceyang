<?php 
/**
 * 公共的函数封装
 */

// 载入配置文件  为了防止function重复被载入出错
require_once '../config.php';



/**
 *  根据配置文件链接数据库
 * @return [type] [description]   返回数据库连接对象
 */
function icey_connect(){
  
  $conn = mysqli_connect( ICEY_DB_HOST , ICEY_DB_USER , ICEY_DB_PASS , ICEY_DB_NAME );
  if (!$conn) {
    // 如果连接失败报错
    die('<h1>Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() . '</h1>');
  }
  // 设置数据库编码 规定当与数据库服务器进行数据传送时要使用的默认字符集
  mysqli_set_charset($conn,'utf8');
  return $conn;
}



/**
 * 执行一个SQL语句, 得到执行结果(列表)
 * @param  [type] $sql [description]
 * @return [type]      [description]
 */
function icey_fetch_all($sql) {
  $conn = icey_connect();
  $query = mysqli_query($conn , $sql);
  if (!$query) {
    # code...
    return false;
  }
  while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
  }
  // 释放结果集
  mysqli_free_result($query);
  // 断开链接数据库
  mysqli_close($conn);

  return $data;
}


/**
 * 执行一个 SQL 语句 得到执行结果(单条)
 * @param  [type] $sql [description]
 * @return [type]      [description]
 */
function icey_fetch_one ($sql) {
  return icey_fetch_all($sql)[0];
}


/**
 * 执行一个非查询的查询语句，执行增删改语句
 * @param  [type] $sql [description]
 * @return [type]      [description]
 */
function icey_execute ($sql) {
  $conn = icey_connect();

  $query = mysqli_query($conn, $sql);

  if (!$query) {
    // 查询失败
    return false;
  }

  // 获取增删改语句受影响行数
  $affected_rows = mysqli_affected_rows($conn);

  // 增删改语句没有结果集需要释放
  // // 释放结果集
  // mysqli_free_result($query);

  // 断开与服务端的连接
  // 数据库连接是有限的，有必要在使用完了之后手动关闭掉
  mysqli_close($conn);

  return $affected_rows;
}


/**
 * 拿到客户端请求带来的小票
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