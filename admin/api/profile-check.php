<?php
    require_once '../../config.php';
    require_once '../../functions.php';

    //个人信息验证
    if ($_SERVER['REQUEST_METHOD']==='GET') {
         update();
         exit();
       }

    function update() {
        //信息校验
        if (empty($_GET['slug'])) {
             exit('别名为空');
           }
        if (empty($_GET['name'])) {
            exit('昵称为空');
        }
        $id=$_GET['id'];
        $avatar=$_GET['avatar'];
        $slug=$_GET['slug'];
        $name=$_GET['name'];
        $bio=$_GET['bio'];

        //当头像没提交时
        if (empty($_GET['avatar'])) {
           $row_affected=xiu_execute("update users set slug='{$slug}',nickname='{$name}',bio='{$bio}' where id = {$id}");
            if ($row_affected==1) {
                 exit('信息修改成功');
               }else {
                exit('信息修改失败');
            }
           }

        //头像提交时
        $row_affected=xiu_execute("update users set avatar='{$avatar}',slug='{$slug}',nickname='{$name}',bio='{$bio}' where id = {$id}");
        if ($row_affected==1) {
            exit('信息修改成功');
        }else {
            exit('信息修改失败');
        }
    }

    //头像验证
    if (empty($_FILES['avatar'])) {
        exit('没有接收到文件');
      }
   $avatar=$_FILES['avatar'];

   //校验文件
    //校验图片格式
    if (!($avatar['type']=='image/png'||$avatar['type']=='image/jpeg'||$avatar['type']=='image/gif')) {
        exit('头像图片类型不符，请上传png、jpg或gif格式文件');
    }
    //校验图片大小
    if ($avatar['size']>=2*1024*1024) {
        exit('头像图片大小不能超过2m');
    }

    //移动文件
    $source=$avatar['tmp_name'];
    $target='../../static/uploads/' . uniqid() . '.' . pathinfo($avatar['name'],PATHINFO_EXTENSION);
    if (!move_uploaded_file($source,$target)) {
         exit('图片移动失败');
       }
    $avatar_path=substr($target,5);
    echo $avatar_path;



