<?php 
  require_once '../functions.php';
  require_once '../config.php';
   xiu_get_current_user();
   //数据添加功能
   if (!empty($_GET['id'])) {
     $categories_id=$_GET['id'];
     $current_edit_categories=xiu_fetch_once("select * from categories where id={$categories_id};");
     // var_dump($current_edit_categories);
     
   }
   //添加功能函数
   function add_categories () {
    if (empty($_POST['name'])) {
      $GLOBALS['error']='请输入名称!';
      return;
    }
    if (empty($_POST['slug'])) {
      $GLOBALS['error']='请输入别名!';
      return;
    }
    $name=$_POST['name'];
    $slug=$_POST['slug'];
    $rows=xiu_execute("insert into categories values(null,'{$slug}','{$name}');");
    if ($rows<=0) {
      $GLOBALS['error']='添加失败!';
      return;
    }
    $GLOBALS['error']='添加成功！';
   }

   //编辑功能函数
   function edit_categories () {
    global $current_edit_categories;
    global $categories_id;
    $name=empty($_POST['name'])?$current_edit_categories['name']:$_POST['name'];
    $slug=empty($_POST['slug'])?$current_edit_categories['slug']:$_POST['slug'];
    echo $current_edit_categories['name'];
    // echo "update categories set name='{$name}',slug='{$slug}' where id={$categories_id};";
    $rows=xiu_execute("update categories set name='{$name}',slug='{$slug}' where id=$categories_id;");
    if ($rows<=0) {
      $GLOBALS['error']='更新失败!';
      return;
    }
    $GLOBALS['error']='更新成功！';
   }
   if ($_SERVER['REQUEST_METHOD']==='POST') {
     if (empty($_GET['id'])) {
        add_categories();
     }else{
        edit_categories();
     }
    
   }
   

   $categories=xiu_fetch_all("select * from categories;");
   // var_dump($categories);
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
   <!-- 顶部导航引入 -->
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
     <?php if (isset($_GET['id'])): ?>
        <?php if (isset($error)): ?>
        <?php if ($error==='更新成功！'): ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo $error; ?>
          </div>>
          <?php else: ?>
            <div class="alert alert-danger">
              <strong>错误！</strong><?php echo $error; ?>
            </div>
        <?php endif ?>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $categories_id; ?>" method="post">
            <h2>编辑"<?php echo $current_edit_categories['name']; ?>"</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo empty($_POST['name'])?$current_edit_categories['name']:$_POST['name']; ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo empty($_POST['slug'])?$current_edit_categories['slug']:$_POST['slug']; ?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
            </div>
          </form>
       <?php else: ?>
         <?php if (isset($error)): ?>
        <?php if ($error==='添加成功！'): ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo $error; ?>
          </div>>
          <?php else: ?>
            <div class="alert alert-danger">
              <strong>错误！</strong><?php echo $error; ?>
            </div>
        <?php endif ?>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>添加新分类目录</h2>
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
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
     <?php endif ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="/admin/api/categories-delete.php" style="display: none" id="batchDeletion">批量删除</a>
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
                <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['slug']; ?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/api/categories-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
             <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
    <!-- 侧边栏引入 -->
    <?php $current_page='' ?>
    <?php $post_menu = array('categories'); ?>
    <?php include 'inc/sidebar.php'; ?>
  </div>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>
    $(function ($) {
      var batchDeletion=$('#batchDeletion');
      var checkedStatus=[];
      var $everyInput=$('tbody  input');
      var $allInputs=$('thead input');
      $everyInput.on('change', function () {
        if ($(this).prop('checked')) {
            checkedStatus.includes($(this).data('id')) || checkedStatus.push($(this).data('id'));
          // console.log($(this).data('id2'));
        } else {
          checkedStatus.splice(checkedStatus.indexOf($(this).data('id')),1);
        }
        batchDeletion.prop('search','?id='+checkedStatus);
        checkedStatus.length?batchDeletion.fadeIn():batchDeletion.stop().fadeOut();
          if ($everyInput.length==checkedStatus.length) {
              $allInputs.prop('checked',true);
          }else {
              $allInputs.prop('checked',false);
          }
      });
//      $allInputs.on('change',function () {
////          var $checked=$(this).prop('checked');
////          $everyInput.prop('checked',$checked);
//          if ($(this).prop('checked')){
//              $everyInput.each(function (index,element) {
//                  if (!$(element).prop('checked')) {
//                      $(element).click();
//                  }
//              });
//          }else {
//              $everyInput.click();
//          }
//
//      });

        //方法二
        $allInputs.on('change',function () {
          var $checked=$(this).prop('checked');
          $everyInput.prop('checked',$checked).change();


      });
    });
  </script>
</body>
</html>
