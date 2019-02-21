<?php 
  require_once '../../config.php';
  if (empty($_GET['email'])) {
    exit('输入参数有误');
  }
  $email=$_GET['email'];
 
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
  if (empty($user)) {
     exit('邮箱不存在');
  }
  echo $user['avatar'];