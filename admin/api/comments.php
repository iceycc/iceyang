<?php

// 负责返回评论数据的数据接口
// 1. 查询数据库中的评论数据
require_once '../../functions.php';

$page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
$size = 20;
$rows = ($page - 1) * $size;

$comments = icey_fetch_all('select
	comments.*,
	posts.title as post_title
from comments
inner join posts on comments.post_id = posts.id
order by comments.created desc
limit ' . $rows . ',' . $size);

// 查询总条数
$total_count = (int)icey_fetch_one('select
  count(1) as i
from comments
inner join posts on comments.post_id = posts.id')['i'];
// 计算总页数
$total_pages = ceil($total_count / $size);

// 2. 序列化为JSON
$json_str = json_encode($comments);

// 3. 响应给客户端
header('Content-Type: application/json');
echo $json_str;
