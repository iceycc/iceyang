<?php

// 负责返回评论数据的数据接口
// 1. 查询数据库中的评论数据
require_once '../../functions.php';

$comments = icey_fetch_all('select * from comments;');




// 2. 序列化为JSON
$json_str = json_encode($comments);

// 3. 响应给客户端
header('Content-Type: application/json');
echo $json_str;
