<?php 
  // var_dump(pathinfo('./foo/bar.ext'));

  //载入公共函数 
  require_once '../functions.php';
  // 获取用户登陆信息 如果没登陆,返回登录页面
  icey_get_current_user();

  // 添加或者修改用户
  function edit_user(){
    //接受及校验获取的数据
    if (empty($_POST['email']) || empty($_POST['slug']) || empty($_POST['nickname']) || empty($_POST['password'] ) ) {
        $GLOBALS['message'] = '请完整填写表单';
        return;
     }

    // 接受并校验上传的头像
    if (!(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK)) {
        $GLOBALS['message'] = '上传头像失败';
        return;
    }
    //  pathinfo('./foo/bar.ext')
    
    // 获取上传文件的扩展名
    $ext_name = pathinfo($_FILES['avatar']['name'])['extension'];    
    // var_dump($_FILES['avatar']['name']);
    // var_dump($ext_name);
    // 目标目录
    $target = '../static/uploads/img-' . uniqid() . '.' . $ext_name;
    // 临时存放目录$_FILES['avatar']['tmp_name']
    $is_ok = move_uploaded_file($_FILES['avatar']['tmp_name'], $target);

    if (!$is_ok) {
        $GLOBALS['message'] = '上传头像失败';
        return;
    } 
    // 得到最终提交的数据
    $email = $_POST['email'];
    $slug = $_POST['slug'];
    $nickname = $_POST['nickname'];
    $password = $_POST['password'];
    $avatar = substr($target, 2);

    // 数据持久化


    if (empty($_POST['id'])) {
      // die('可以进行添加了');
      $sql = "insert into users values (null,'$slug','$email','$password','$nickname','$avatar',null,'activated');";
      $affected_rows = icey_execute($sql);
      if ($affected_rows === 1) {
      // 添加成功
      $GLOBALS['success'] = '添加成功' ;
      }  
    }else{
      // die('还不能实现修改');
      
      
    }

  }


  // 提交表单时
  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // die('可以进行编辑了')
    edit_user();
  }

  // 获取数据库中全部的users的数据  然后渲染到html页面
  $users = icey_fetch_all('select * from users');
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <style>
    #preview{
      width: 100px;
      height: 100px;
      overflow: hidden;
      border: 1px solid #999;
      margin:10px;
    }
    #preview img{
      width: 100%;
      height: 100%;
    }


  </style>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <div class="alert alert-danger">
          <strong>错误！</strong><?php echo $message; ?>
        </div>
      <?php endif ?>  
      <!-- 有错误信息时展示 -->
      <?php if (isset($success)): ?>
        <div class="alert alert-success">
          <strong>成功！</strong><?php echo $success; ?>
        </div>
      <?php endif ?>

      <div class="row">
        <div class="col-md-4">
          <form action = "<?php echo $_SERVER['PHP_SELF']?>" method = "post" enctype="multipart/form-data">
            <h2>添加新用户</h2>
            <input type="hidden"  name = "id" value="0">


            <!-- 添加一个上传头像 的input-->
            <div id="form-group">              
              <label for="avatar">上传头像</label>
              <input type="file" name="avatar">
              <!-- //头像预览 -->
              <div id="preview">头像预览</div>
            </div>
            
            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="密码">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none" id="btn-del">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $item): ?>
                <tr>
                  <td class="text-center"><input type="checkbox" data-id = "<?php echo $item['id']?>"></td>
                  <td class="text-center"><img class="avatar" src="<?php echo isset($item['avatar']) ? $item['avatar'] : "" ?>"></td>
                  <td><?php echo isset($item['email']) ? $item['email'] : "" ?></td>
                  <td><?php echo isset($item['slug']) ? $item['slug'] : "" ?></td>
                  <td><?php echo isset($item['nickname']) ? $item['nickname'] : "" ?></td>
                  <td><?php echo empty($item['status']) ? "未激活" : "激活" ?></td>
                  <td class="text-center">
                    <button class="btn btn-info btn-xs btn-edit" data-slug = "<?php echo $item['slug'] ?>" data-name = "<?php echo $item['nickname'] ?>" data-slug ="<?php echo $item['slug'] ?>" data-id = "<?php echo $item['id'] ?>">编辑</button>
                    <a href="/admin/users_del.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>                 
              <?php endforeach ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page = 'users'; ?>
  <?php include 'inc/sidebar.php'; ?>


  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>

    
    $(function($){
      //添加图片预览功能-------------------------
      $('[type="file"]') .on ("change", function (e) {
        var file = e.target.files[0]
        // console.log(file)
        preview(file);
      })
      function preview (file) {
        var img = new Image();      
        url = img.src = URL.createObjectURL(file);
        var $img = $(img)
        img.onload = function (e) {
          URL.revokeObjectURL(url)
          $("#preview").empty().append($img)
        } 
      }
      // 批量删除功能------------------------------------
      var $btnDel = $("#btn-del")
      // 用于保存被选中行的id
      var checkeds = []
      $("tbody").on("change","input",function() {
        var $this = $(this)
        // console.log($this)
        var id = $this.data("id")
        // console.log(id)
        if ($this.prop("checked")) {
          checkeds.push(id)
        } else {
          checkeds.splice(checkeds.indexOf(id),1)
        }
        // 根据有没有选中宣示或隐藏        
        checkeds.length ? $btnDel.fadeIn() : $btnDel.fadeOut()
        // 改变批量删除的问号参数
        // 
        $btnDel.attr("href", "/admin/users_del.php?id=" + checkeds)      

      })



      $("tbody").on("click", ".btn-edit", function() {
        
        var name = $(this).data("name")
        var slug = $(this).data("slug")
        var id = $(this).data("id")
        console.log(id)
        
        $("form h2").text("编辑页面")
        $(".btn-add").text("保存")
        $("#id").val(id)
        
        $("#name").val(name)
        $("#slug").val(name)
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


    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
