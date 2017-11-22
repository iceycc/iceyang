<?php 
  //载入公共函数 
  require_once '../functions.php';
  // 获取用户登陆信息 如果没登陆,返回登录页面
  icey_get_current_user();

  // 提交文章函数
  function add_post () {
    // 1-获取提交的数据并校验(+业务校验)
    // 2-持久化
    // 3-反馈
    
    // 简单的校验是否提交了数据
    if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['slug']) || empty($_POST['category']) || empty($_POST['created']) || empty($_POST['status'])) {
      // 请完整填写表单
      $GLOBALS['err_mes'] = '请完整填写表单';
      
    }
  } 

  // 判断是否为post请求
  // var_dump($_SERVER);
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    add_post();
  }
 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <!-- 插入富文本编辑器css -->
  <link rel="stylesheet" href="/static/assets/vendors/simplemde/simplemde.min.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($err_mes)): ?>
        <div class="alert alert-danger">
          <strong>错误！</strong><?php echo $err_mes ?>
        </div>         
      <?php endif ?>
      <form class="row" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img id="preview" class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <option value="1">未分类</option>
              <option value="2">潮生活</option>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'post-add'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <!-- 插入富文本编辑器 js -->
  <script src="/static/assets/vendors/simplemde/simplemde.min.js"></script>
  <script>
    $(function ($) {
      // 插入富文本编辑器
      new SimpleMDE({
        element: $('#content')[0],
        spellChecker: false
      })

      // 找一个合适的时机
      // 做一件合适的事情
      $('#feature').on('change', function () {
        // 这里的代码会在用户选择文件过后执行
        if (!this.files.length) return

        // 肯定选择了一个文件

        var file = this.files[0]

        // 判断是是图片文件
        if (!file.type.startsWith('image/')) return

        // 肯定是一个图片文件
        // 为这个文件分配一个临时的地址
        var url = URL.createObjectURL(file)

        // 处理事件重复注册问题
        $('#preview').attr('src', url).fadeIn().on('load', function () {
          // 吊销这个地址 一定是在图片的 onload 事件中执行
          URL.revokeObjectURL(url)
        })
      })

      var time = moment().format('YYYY-MM-DDTHH:mm')
      // console.log(time)
      $('#created').val(time)
    })
  </script> 

  <script>NProgress.done()</script>
</body>
</html>
