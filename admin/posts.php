<?php
require_once '../functions.php';
require_once '../config.php';
xiu_get_current_user();
//处理分页函数
$page = empty($_GET['page'])?1:(int)$_GET['page'];
$size = 5;

//分类筛选
$category=xiu_fetch_all("select id,name from categories;");
$where='1=1';
$search='';
if (isset($_GET['category']) && $_GET['category']!=='all') {
     $where .= " and categories.id={$_GET['category']}";
     $search .= "&category={$_GET['category']}";
   }

//状态筛选
$status=xiu_fetch_all("select status from posts;");
if (isset($_GET['status']) && $_GET['status']!=='all') {
    $where .= " and posts.status='{$_GET['status']}'";
    $search .= "&status={$_GET['status']}";
}
//echo $where;
//总页码数
$total_pages = (int)xiu_fetch_once("select
count(1) as num
from posts
inner join categories on posts.category_id = categories.id
inner join users on posts.user_id = users.id
where {$where};")['num'];

//最大页码数
$max_pages=ceil($total_pages/$size);

$page<1?$page=1:$page;
$page>$max_pages?$page=$max_pages:$page;

$offset = ($page-1)*$size;
$post_result = xiu_fetch_all("select
posts.id,
posts.title,
users.nickname as user_name,
categories.name as category_name ,
posts.created,
posts.status
from posts
inner join categories on posts.category_id = categories.id
inner join users on posts.user_id = users.id
where {$where}
order by posts.created desc
limit {$offset},{$size};");

//处理分页码
$visiables = 5;
$begin = $page-($visiables-1)/2;
$end = $page+($visiables-1)/2;


//echo $max_pages;

if ($begin<1) {
     $begin=1;
     $end=$begin+$visiables-1;
   }

if ($end>$max_pages) {
     $end=$max_pages;
     $begin=$end-($visiables-1);
     if ($begin<1) {
         $begin=1;
        }
   }

//判断文章状态
function current_status($status) {
    switch ($status) {
        case 'drafted': echo "草稿";break;
        case 'published': echo "已发布";break;
        case 'trashed': echo "回收站";break;
    }
}
//改变日期格式
function convert_date($date) {
    $datetemp = strtotime($date);
    return date('Y年m月d日<b\r>H:i:s',$datetemp);
}

//获得作者名称
//function get_user_nickname ($user_id) {
//    $user_nickname_result=xiu_fetch_once("select nickname from users where id={$user_id};");
//    return $user_nickname_result['nickname'];
//}

//获得文章分类
//function get_categories_name ($categories_id) {
//    $categories_name_result=xiu_fetch_once("select `name` from categories where id={$categories_id};");
//    return $categories_name_result['name'];
//}
?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
  
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="/admin/api/posts-delete.php" style="display: none" id="delete-all">批量删除</a>
        <form class="form-inline" action="posts.php">

          <select name="category" class="form-control input-sm">
            <option value="all"<?php echo isset($_GET['category']) && $_GET['category']==='all'?' selected':'';?>>所有分类</option>
            <?php foreach ($category as $value): ?>
                <option value="<?php echo $value['id']?>"<?php echo isset($_GET['category']) && $_GET['category']===$value['id']?' selected':'';?>><?php echo $value['name'];?></option>
            <?php endforeach ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all"<?php echo isset($_GET['status']) && $_GET['status']==='all'?' selected':'';?>>所有状态</option>
            <option value="published"<?php echo isset($_GET['status']) && $_GET['status']==='published'?' selected':'';?>>已发布</option>
              <option value="drafted"<?php echo isset($_GET['status']) && $_GET['status']==='drafted'?' selected':'';?>>草稿</option>
              <option value="trashed"<?php echo isset($_GET['status']) && $_GET['status']==='trashed'?' selected':'';?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
            <li><a href="?page=1">第一页</a></li>
            <li><a href="?page=<?php echo $page-1;?>">上一页</a></li>
            <?php for($i=$begin;$i<=$end;$i++):?>
            <li<?php echo $i==$page?' class="active"':'';?>><a href="?page=<?php echo $i;?><?php echo $search;?>"><?php echo $i;?></a></li>
            <?php endfor;?>
          <li><a href="?page=<?php echo $page+1;?>">下一页</a></li>
            <li><a href="?page=<?php echo $max_pages;?>">最后页</a></li>
        </ul>

      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox" id="checkAll"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ( $post_result as $item): ?>
                <tr>
                    <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id'];?>"></td>
                    <td><?php echo $item['title'];?></td>
<!--                    <td>--><?php //echo get_user_nickname($item['user_id']);?><!--</td>-->
<!--                    <td>--><?php //echo get_categories_name($item['category_id']);?><!--</td>-->
                    <td><?php echo $item['user_name'];?></td>
                    <td><?php echo $item['category_name'];?></td>
                    <td class="text-center"><?php echo convert_date($item['created']);?></td>
                    <td class="text-center"><?php current_status($item['status']);?></td>
                    <td class="text-center">
                        <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                        <a href="/admin/api/posts-delete.php?id=<?php echo $item['id'];?>" class="btn btn-danger btn-xs">删除</a>
                    </td>

                </tr>
            <?php endforeach ?>


        </tbody>
      </table>
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
          //批量删除功能
          var $checkBox=$('tbody input');
          var $checkedNum=[];
          var $deleteAll=$('#delete-all');
          var $checkAll=$('#checkAll');
          $checkBox.on('change',function () {
              if ($(this).prop('checked')) {
                  $checkedNum.includes($(this).data('id')) || $checkedNum.push($(this).data('id'));
              }else {
                  $checkedNum.splice($checkedNum.indexOf($(this).data('id')),1);
              }

              //将id放入到按钮中
              $deleteAll.prop('search','?id='+$checkedNum);

              if ($checkedNum.length>0) {
                  $deleteAll.fadeIn();
              }else {
                  $deleteAll.stop().fadeOut();
              }

              if ($checkedNum.length==$checkBox.length) {
                  $checkAll.prop('checked',true);
              }else {
                  $checkAll.prop('checked',false);
              }
          });

          //全选与全不选
          $checkAll.on('click',function () {
              $checkBox.prop('checked',$(this).prop('checked')).change();
          });
      });
  </script>
</body>
</html>
