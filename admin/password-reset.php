<?php
require_once '../functions.php';
require_once '../config.php';
$user_data=xiu_get_current_user();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
   <!-- 顶部导航引入 -->
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" id="error" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <form class="form-horizontal">
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" type="password" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" type="password" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" type="password" placeholder="确认新密码">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <button type="button" class="btn btn-primary" id="submit">修改密码</button>
          </div>
        </div>
      </form>
    </div>
  </div>

     <!-- 侧边栏引入 -->
    <?php $current_page='' ?>
    <?php $post_menu = array('posts'); ?>
    <?php include 'inc/sidebar.php'; ?>
  </div>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>

  <script>
      $(function () {
          //错误显示
          function error(warning) {
              $('#error').removeClass('alert-success').addClass('alert-danger').fadeIn().html('<strong>错误！</strong>'+warning);
          }
          //修改成功提示
          function success(warning) {
              $('#error').removeClass('alert-danger').addClass('alert-success').fadeIn().html('<strong>成功！</strong>'+warning);
          }

          var $old=$('#old');
          var $password=$('#password');
          var $confirm=$('#confirm');
          var $error=$('#error');
          var $submit=$('#submit');

          //两次密码校验
          $confirm.on('keyup',function () {
              if ($password.val()!=$confirm.val()) {
                  error('两次输入密码不一致');
              }else {
                  $error.fadeOut();
              }
          });

          $submit.on('click',function () {
             //校验密码
              if ($old.val()=='')  {
                 error('请输入初始密码');
                 return;
             }
              if ($password.val()!=$confirm.val()) {
                  error('两次输入密码不一致');
                  return;
              }

              //发送请求
              $.post('/admin/api/password-check.php',{id:<?php echo $user_data['id'];?>,old:$old.val(),new:$confirm.val()},function (res) {
                if (res=='修改密码成功') {
                    success('修改密码成功');
                }else if (res=='密码错误') {
                    error('输入密码有误');
                } else {
                    error('修改密码失败');
                }
              });
          });
      });
  </script>
</body>
</html>
