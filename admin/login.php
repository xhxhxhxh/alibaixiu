<?php 
 function login(){
  //1.获取并校验
  //导入账号文件
  require_once '../config.php';
  session_start();

  if (empty($_POST['email'])) {
    $GLOBALS['error']='请输入邮箱!';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['error']='请输入密码!';
    return;
  }
  $email=$_POST['email'];
  $password=$_POST['password'];

  //连接服务器

  $connection=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (!$connection) {
    exit('连接数据库失败');
  }
  $query=mysqli_query($connection,"select * from users where email = '{$email}' limit 1;");
  // echo "select * from  where email= {$email} limit 1";
  if (!$query) {
    exit('查询数据库失败');
  }
  $user=mysqli_fetch_assoc($query);
  // var_dump($user);
  if (empty($user)) {
     $GLOBALS['error']='用户名不存在!';
    return;
  }
  // echo md5($password);
  if ($user['password']!==md5($password)) {
     $GLOBALS['error']='密码错误!';
    return;
  }
  $_SESSION['current_user_login']=$user;
  header('location:/admin');
  //2.持久化
  //3.响应

 } 

  if ($_SERVER['REQUEST_METHOD']==='POST') {
    login();
  }
  if (isset($_GET['action']) && $_GET['action']==='login_out') {
      session_start();
     unset($_SESSION['current_user_login']);
  }
 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap<?php echo isset($error)?' shake animated':''; ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if (isset($error)): ?>
        <div class="alert alert-danger">
          <strong>错误！</strong> <?php echo $error; ?>
        </div>
      <?php endif ?> 
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" name="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo isset($_POST['email'])?$_POST['email']:''; ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" name="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script>
    $(function ($) {
      //邮箱正则表达式
      var emailRegular=/^[0-9a-zA-Z_.-]+[@][0-9a-zA-Z_.-]+([.][a-zA-Z]+){1,2}$/;
      $('#email').on('blur',function () {
        var value=$(this).val();
        if (!value || !emailRegular.test(value)) return;
        $.get('/admin/api/avatar.php',{email:value},function (data) {
          $('.avatar').fadeOut(function () {
            $(this).attr('src',data).fadeIn();
          });
        });
      });
    })
  </script>
</body>
</html>
