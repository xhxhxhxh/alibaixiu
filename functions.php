<?php 	
	session_start();
	
/**
*
*获取登录用户信息
*/
	function xiu_get_current_user() {
		if (empty($_SESSION['current_user_login'])) {
    		header('location:/admin/login.php');
    		exit();
  		}
  		return $_SESSION['current_user_login'];
	}


/**
*
*数据库查询多条数据封装函数
*/
	
	function xiu_fetch_all($sql) {
		$connection=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  		if (!$connection) {
  		  exit('连接数据库失败');
  		}
  		$query=mysqli_query($connection,$sql);
  		// echo "select * from  where email= {$email} limit 1";
  		if (!$query) {
  		  exit('查询数据库失败');
  		}
  		while ($row=mysqli_fetch_assoc($query)) {
  			$result[]=$row;
  		}
  		mysqli_free_result($query);
  		mysqli_close($connection);
  		return $result;
	}

/**
*
*数据库查询单条数据封装函数
*/
	
	function xiu_fetch_once($sql) {
		$res=xiu_fetch_all($sql);
		return isset($res)?$res[0]:null;
	}

	/**
*
*数据库增、删、改数据封装函数
*/
	
	function xiu_execute ($sql) {
		$connection=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  		if (!$connection) {
  		  exit('连接数据库失败');
  		}
  		$query=mysqli_query($connection,$sql);
  		// echo "select * from  where email= {$email} limit 1";
  		if (!$query) {
  		  exit('查询数据库失败');
  		}
  		$row_affected=mysqli_affected_rows($connection);
//  		mysqli_free_result($query);
  		mysqli_close($connection);
  		return $row_affected;
	}
  