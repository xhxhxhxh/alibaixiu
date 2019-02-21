<?php
    require_once '../../config.php';
    require_once '../../functions.php';

    //个人信息验证
    if ($_SERVER['REQUEST_METHOD']==='POST') {
         password_reset();
         exit();
       }

    function password_reset() {
        //信息校验
        if (empty($_POST['old'])) {
             exit('旧密码为空');
           }
        if (empty($_POST['new'])) {
            exit('新密码为空');
        }
        $old=$_POST['old'];
        $new=$_POST['new'];
        $id=$_POST['id'];

        //验证初始密码
        $old_password=xiu_fetch_once("select password from users where id={$id} limit 1")['password'];
//        var_dump($old_password);
        if ($old_password!==md5($old)) {
            exit('密码错误');
           }

        //修改新密码
        $new=md5($new);
        $row_affected=xiu_execute("update users set password='{$new}' where id={$id}");
        if ($row_affected==1) {
             exit('修改密码成功');
           }
        exit('修改密码失败');
    };





