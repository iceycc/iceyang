<?php 
  //载入公共函数 
  require_once '../functions.php';
  // 获取登陆信息
  icey_get_current_user();

  // 添加表单
  function add_category() {
    // 1-获取提交过来数据
    // 2-1校验(校验是否填写)
    // 2-2业务校验(填写格式,数字,字母,大小写等是否符合业务要求)
    // 3-持久化 保存到服务端数据库
    // 4-响应
    
    // 1获取并校验提交过来的数据
    if (empty($_POST['name']) || empty($_POST['slug']) ) {
      
      $GLOBALS['message'] = '请填写完整表单';
      return;
    }
    $name = $_POST['name'];
    $slug = $_POST['slug'];

    // 链接数据库 添加一行数据
    $sql = "insert into categories values (null,'$slug','$name');";
    $affected_rows = icey_execute($sql);
    if ($affected_rows === 1) {
      // 添加成功
      $GLOBALS['success'] = '添加成功' ;
    }    
  }

  // 修改表单
  function edit_category() {
    // 获取要修改为的数据 包含id 
    // 链接数据库找到数据库对应的数据
    // 修改数据库中的内容
    // 反馈
        if (empty($_POST['name']) || empty($_POST['slug']) ) {
      
      $GLOBALS['message'] = '请填写完整表单';
      return;
    }
    $id = $_POST['id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];

    // 链接数据库 添加一行数据
    $sql = "update categories set name = '{$name}', slug = '{$slug}' where id ={$id}";
    $affected_rows = icey_execute($sql);
    if ($affected_rows === 1) {
      // 添加成功
      $GLOBALS['success'] = '修改成功' ;
    }  else{
      $GLOBALS['message'] = '修改失败' ;
    }  
     

  }
  // 判断请求方式 post ,post为编辑功能 判断是
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['id'])) {
      add_category();
    }else{
      edit_category();
    }
    
  }
  // 获取数据库中全部的Categories分类的数据
  $categories = icey_fetch_all('select * from categories');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <div class="alert alert-danger">
          <strong>错误！</strong><?php echo $message; ?>
        </div>
      <?php endif ?>
      <?php if (isset($success)): ?>
        <div class="alert alert-success">
          <strong>成功！</strong><?php echo $success; ?>
        </div>
      <?php endif ?>

      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>添加新分类目录</h2>
            <input type="hidden" value="0" name="id" id="id">
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary btn-add" type="submit">添加</button>
              <button class="btn btn-default btn-no" type="button" style="display: none">取消</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none" id="btn_del">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $item): ?>
                <tr>
                  <td class="text-center"><input data-id="<?php echo $item['id']; ?>" type="checkbox"></td>
                  <td><?php echo $item['name'] ?></td>
                  <td><?php echo $item['slug'] ?></td>
                  <td class="text-center">
                    <button class="btn btn-info btn-xs btn-edit" data-name = "<?php echo $item['name'] ?>" data-slug ="<?php echo $item['slug'] ?>" data-id = "<?php echo $item['id'] ?>">编辑</button>
                    <a href="/admin/categories_del.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>
              <?php endforeach ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page = 'categories'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>

  <script>
    
    // 点击某一行的编辑按钮,将选中的那行的数据添加到右侧表单
    $("tbody").on("click", ".btn-edit", function() {
      
      var name = $(this).data("name")
      var slug = $(this).data("slug")
      var id = $(this).data("id")
      console.log(id)
      
      $("form h2").text("编辑页面")
      $(".btn-add").text("保存")
      $("#id").val(id)
      
      $("#name").val(name)
      $("#slug").val(slug)
      $(".btn-no").fadeIn()
    })
      // 取消编辑
    $(".btn-no").on("click", function () {
      $("form h2").text("添加新分类目录")
      $(".btn-add").text("提交")

      $("#name").val("")
      $("#slug").val("")
      $(".btn-no").fadeOut()
    })



    // 批量删除
    $(function($){

      var $btnDel = $("#btn_del")
      // 保存选中行对应的id
      var checkeds = []
      // 事件委托 
      $("tbody").on("change", "input", function() {
        // console.log(1)
        var $this = $(this)
        // 注意给input添加一个data-id的自定义属性
        var id = $this.data("id")        

        // checked选中为true
        if ($this.prop("checked")) {          
          checkeds.push(id)
        } else {
        // indexOf获取数组中某个元素的索引
        // splice去除数组中连续的元素 ,返回截取的元素
          checkeds.splice(checkeds.indexOf(id),1)
        }
        // 根据有没有选中宣示或隐藏        
        checkeds.length ? $btnDel.fadeIn() : $btnDel.fadeOut()
        // 改变批量删除的问号参数
        // 
        $btnDel.attr("href", "/admin/categories_del.php?id=" + checkeds)
      }) 
      
      
    })

  </script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
