<?php 
require_once '../functions.php';



function login(){
  // 接受表单提交的数据
  // echo "1";
  if (empty($_POST['email'])) {
    $GLOBALS['err_ms'] = '没写用户名';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['err_ms'] = '没写密码';
    return;
  }
  $email = $_POST['email'];
  $password = $_POST['password'];
  // 业务校验  
  // 简单校验
  // if($email !== 'a@qq.com'){
  //   $GLOBALS['err_ms'] = '用户名和密码不匹配';
  //   return;
  // }
  // if($password !== '123'){
  //   $GLOBALS['err_ms'] = '用户名和密码不匹配';
  //   return;
  // }
  //连接数据库对象
  $conn = icey_connect();
  // 查询语句
  $sql = "select * from users where email = '$email' limit 1";
  // 返回一行数据
  $query = mysqli_query($conn,$sql);

  if (!$query) {
    // 数据查询失败
    $GLOBALS['err_ms'] = '查询失败';
    return;  
  }

  $user = mysqli_fetch_assoc($query);
  $user = isset($user) ? $user : '';
  
  if($email !== $user['email']){
    $GLOBALS['err_ms'] = '用户名和密码不匹配';
    return; 
  }
  if ($password !== $user['password']) {
    $GLOBALS['err_ms'] = '用户名和密码不匹配';
    return; 
  }

  // 设置个cookie
  session_start();
  $_SESSION['current_login_user'] = $user;
  

  // 反馈
  header('Location:/admin/') ;

}

// 判断是否是表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  login();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  
}

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <img id = "avatar" class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->


      <?php if (isset($err_ms)): ?>
        <div class="alert alert-danger">
          <strong>错误！</strong> <?php echo $err_ms; ?>
        </div>        
      <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input name="email" id="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input name="password" id="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block" type='submit' >登 录</button>
    </form>
  </div>

  <!-- 当邮箱输入框失去焦点时,自动获取头像 -->
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script>
    $("#email").on("blur",function(){ 

      $.get('./join_ajax.php', { email: this.value }, function (res) {
        $("#avatar").attr("src",res)
      })
    })
  </script>
</body>
</html>
