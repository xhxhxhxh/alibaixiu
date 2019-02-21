<?php
require_once '../functions.php';
require_once '../config.php';
$user_data_initial=xiu_get_current_user();
$user_data=xiu_fetch_once("select * from users where id={$user_data_initial['id']} limit 1");

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" id="error" style="display: none">
        <strong>错误！</strong>
      </div>
      <form class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file" accept="image/*">
              <img src="<?php echo $user_data['avatar'];?>">
              <i class="mask fa fa-upload"></i>
                <input type="hidden" id="avatarPath">
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $user_data['email'];?>" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $user_data['slug'];?>" placeholder="slug">
            <p class="help-block">https://zce.me/author/<strong>zce</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $user_data['nickname'];?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" placeholder="Bio" cols="30" rows="6"><?php echo $user_data['bio'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="button" class="btn btn-primary" id="submit">更新</button>
            <a class="btn btn-link" href="/admin/password-reset.php">修改密码</a>
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
          var $warning='';
          $('#avatar').on('change',function () {
              var file=$(this).context.files;
              // console.log($(this))
              // console.log(file.length);
              if (file.length==0) {
                  $('.form-image img').attr('src','/static/assets/img/default.png');
                  $('#avatarPath').val('');
                  return;
              }
              if (file[0].size>1*1024*1024) return;

              //拿到上传文件
              var data=new FormData();
              data.append('avatar',file[0]);
              var xhr=new XMLHttpRequest();
              xhr.open('post','/admin/api/profile-check.php');
              xhr.send(data);
              xhr.onload=function () {
                  // console.log(this.responseText);
                  $('.form-image img').attr('src',this.responseText);
                  $('#avatarPath').val(this.responseText);
              };
          });

          //错误显示
          function error(warning) {
              $('#error').fadeIn().html('<strong>错误！</strong>'+warning);
          }
          //修改成功提示
          function success(warning) {
              $('#error').removeClass('alert-danger').addClass('alert-success').fadeIn().html('<strong>成功！</strong>'+warning);
          }
          //表单提交
          $('#submit').on('click',function () {
             var $avatar=$('#avatarPath').val();
             var $slug=$('#slug').val();
             var $name=$('#nickname').val();
             var $bio=$('#bio').val();

             //信息验证
             if ($slug=='') {
                  $warning='请输入别名';
                  error($warning);
                  return;
              }
              if ($name=='') {
                  $warning='请输入昵称';
                  error($warning);
                  return;
              }
              $.get('/admin/api/profile-check.php',{id:<?php echo $user_data['id']; ?>,avatar:$avatar,slug:$slug,name:$name,bio:$bio},function (res) {
                  if (res=='信息修改成功') {
                      success('更新成功');
                      <?php $user=xiu_fetch_once("select * from users where id={$user_data_initial['id']} limit 1");
                            $_SESSION['current_user_login']=$user;
                      ?>
                  }
              });
          });
      });
  </script>
</body>
</html>
