<?php
/**
 * Created by PhpStorm.
 * User: XH
 * Date: 2018/12/20
 * Time: 17:20
 */

require_once '../functions.php';
require_once '../config.php';
xiu_get_current_user();

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
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <!-- 顶部导航引入 -->
    <?php $current_page='users' ?>
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>电影</h1>
      </div>
        <div class="movies"></div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->


    <!-- 侧边栏引入 -->
    <?php include 'inc/sidebar.php'; ?>
  </div>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
   <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script>NProgress.done()</script>
      <script id="movies_tmpl" type="text/x-jsrender">
          {{for movies}}
            <li>{{:title}}</li>
            <li><img src="{{:images['small']}}" alt=""></li>
          {{/for}}
      </script>
      <script>
          function faa(res) {
              console.log(res);
              var html=$('#movies_tmpl').render({movies:res['subjects']})
              $('.movies').html(html);
          }
          $(function () {

              // var script=document.createElement('script');
              // document.body.appendChild(script);

              // script.src='http://api.douban.com/v2/movie/in_theaters?callback=foo';

          });
      </script>
      <script src="http://api.douban.com/v2/movie/in_theaters?callback=faa"></script>
</body>
</html>
