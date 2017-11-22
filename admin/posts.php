<?php 
  //载入公共函数 
  require_once '../functions.php';
  // 获取用户登陆信息 如果没登陆,返回登录页面
  icey_get_current_user();

  // 上层分类目录下拉菜单的获取
  $categories = icey_fetch_all('select * from categories;');



  // 处理筛选逻辑=====================================================================
  //传递了筛选参数才需要where
  $where = '1 = 1' ; 
  if (isset($_GET['category']) && $_GET['category'] !== 'all') {
    // 客户端传递了分类的参数
    $where .= ' and posts.category_id = ' . $_GET['category'];
    
  }
  $category = isset($_GET['category']) ? $_GET['category'] : '' ;
  if (isset($_GET['status']) && $_GET['status'] !== 'all') {
    // 客户端传递了状态的参数 不为all
    $where .= " and posts.status = '{$_GET['status']}'";  
    
  }
  $status = isset($_GET['status']) ? $_GET['status'] : '' ;  

  //处理分页参数 ==========================
  //页码
  $page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
  // 每页数量
  $size = 20 ;
  // 查询时limit的第一个参数,跳过多少行开始查询
  $rows = ($page - 1) * $size;
  // 查询数据库中满足条件的有多少条数据
  $count = (int)icey_fetch_one('select
      count(1) as num 
    from posts
    inner join users on posts.user_id = users.id
    inner join categories on posts.category_id = categories.id
    where ' . $where)['num'];
  // 求总页数 开始页码 结束页码 
  var_dump($count);
  $sum_page = (int)ceil($count / $size);
  $begin = $page - 2 < 1 ? 1 : $page - 2;
  $end = $begin + 4;
  if ($end > $sum_page) {
    $end = $sum_page;
    $begin = $end - 4 < 1 ? 1 : $end - 4;
  }
  // var_dump($where);
  // 查询语句 ===================
  // 通过posts.user_id = users.id和posts.category_id = categories.id
  // 使posts表与users表和categories表建立联系
  $sql = 'select
      posts.id,
      posts.created,
      posts.title,      
      posts.status,
      users.nickname as user_name,
      categories.name as category_name
    from posts
    inner join users on posts.user_id = users.id
    inner join categories on posts.category_id = categories.id
    where ' . $where . '
    order by posts.created desc
    limit ' . $rows . ',' . $size . ';' ;

  // var_dump($sql);
  $posts = icey_fetch_all($sql);


  // 过滤函数======-----------------------=========================

  // $posts = icey_fetch_all('select * from posts');
  // var_dump($posts);
  function posts_status ($status) {
    switch ($status) {
      case 'drafted':
        return '草稿';
      case 'published':
        return '已发布';
      case 'trashed':
        return '回收站';
      default:
        return '未知';
    }
  }


  // 可以通过一次查询，抽时间完善
  // 获取用户名

  // 获取时间并格式化
  function edit_time ($data) {
    // 转为时间戳
    $totime = strtotime($data);
    return date('Y年m月d日<b\r>H:i:s', $totime);
  }
 ?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($categories as $item): ?>
              <option value="<?php echo $item['id']; ?>"<?php echo isset($_GET['category']) && $_GET['category'] == $item['id'] ? ' selected' : '' ?>><?php echo $item['name'] ?></option>              
            <?php endforeach ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted"<?php echo isset($_GET['status']) && $_GET['status'] ==='drafted' ? ' selected' : '' ?> >草稿</option>
            <option value="published"<?php echo isset($_GET['status']) && $_GET['status'] ==='published' ? ' selected' : '' ?> >已发布</option>
            <option value="trashed"<?php echo isset($_GET['status']) && $_GET['status'] ==='trashed' ? ' selected' : '' ?> >回收站</option>
          </select>
          <button class="btn btn-default btn-sm" type="submit">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <!-- 左上分页按钮分页业务的处理 -->
          <!-- 法一 ： 通过服务端获取到 当前页$page 起始业$begin 结束页$end 渲染动态页面 -->
<!--           <li><a href="/admin/posts.php?page=<?php //echo $page - 1; ?>">上一页</a></li>
          <?php //for ($i = $begin; $i <= $end; $i++): ?>
          <li<?php// echo $page === $i ? ' class="active"' : ''; ?>><a href="/admin/posts.php?page=<?php //echo $i; ?>"><?php //echo $i; ?></a></li>
          <?php //endfor; ?>
          <li><a href="/admin/posts.php?page=<?php //echo $page + 1; ?>">下一页</a></li> -->


          <!-- 法二  通过封装函数   传入$page, $total_page ,请求地址含参数  还有展示的格数 -->
          <!-- &categories='. $category .'&status='.$status -->
          <?php icey_pagination($page, $sum_page, '?page=%d', 5); ?>
          
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $item): ?>            
            <tr>
              <td class="text-center"><input type="checkbox"></td>
              <td><?php echo $item['title'] ?></td>
              <td><?php echo $item['user_name'] ?></td>
              <td><?php echo $item['category_name'] ?></td>
              <td class="text-center"><?php echo edit_time($item['created'])?></td>
              <td class="text-center"><?php echo posts_status($item['status'])?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
              </td>
            </tr>
          <?php endforeach ?>

          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'posts'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
