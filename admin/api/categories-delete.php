<?php
if (empty($_GET['id'])) {
		exit('输入参数错误');
	}
	$id=$_GET['id'];
	require_once '../../config.php';
	require_once '../../functions.php';
	$row=xiu_execute ("delete from categories where id in ({$id});");
	if ($row<=0) {
		exit('删除失败');
	}
	header('location:/admin/categories.php');

