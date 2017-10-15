<?php 
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
if($email !== 'a@qq.com'){
  $GLOBALS['err_ms'] = '用户名和密码不匹配';
  return;
}
if($password !== '123'){
  $GLOBALS['err_ms'] = '用户名和密码不匹配';
  return;
}
// 反馈
header('Location:/admin/') ;

}

// 判断是否是表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  login();
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
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->


      <?php if (isset($err_ms)): ?>
        <div class="alert alert-danger">
          <strong>错误！</strong> <?php echo $err_ms; ?>
        </div>        
      <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input name="email" id="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input name="password" id="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block" type='submit'>登 录</button>
    </form>
  </div>
</body>
</html>
