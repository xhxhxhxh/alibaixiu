<?php 
  require_once '../functions.php';
   $user=xiu_get_current_user();
 ?>
<div class="aside">
    <div class="profile">
      <img class="avatar" src="<?php echo $user['avatar'] ?>">
      <h3 class="name"><?php echo $user['nickname'] ?></h3>
</div>
 <ul class="nav">
      <li <?php echo $current_page=='index'? 'class = "active"': ''; ?>>
        <a href="index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <li <?php echo isset($post_menu)? 'class = "active"': ''; ?>>
        <a href="#menu-posts" class="<?php echo isset($post_menu)? 'collapse': 'collapsed'; ?>" data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse<?php echo isset($post_menu)? ' in': ''; ?>">
          <li <?php echo isset($post_menu) && $post_menu[0] == 'posts'? 'class = "active"': ''; ?>><a href="posts.php">所有文章</a></li>
          <li <?php echo isset($post_menu) && $post_menu[0] == 'post-add'? 'class = "active"': ''; ?>><a href="post-add.php">写文章</a></li>
          <li <?php echo isset($post_menu) && $post_menu[0] == 'categories'? 'class = "active"': ''; ?>><a href="categories.php">分类目录</a></li>
        </ul>
      </li>
      <li <?php echo $current_page=='comments'? 'class = "active"': ''; ?>>
        <a href="comments.php"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li <?php echo $current_page=='users'? 'class = "active"': ''; ?>>
        <a href="users.php"><i class="fa fa-users"></i>用户</a>
      </li>
      <li <?php echo isset($setting_menu)? 'class = "active"': ''; ?>>
        <a href="#menu-settings" class="<?php echo isset($setting_menu)? 'collapse': 'collapsed'; ?>" data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse<?php echo isset($setting_menu)? ' in': ''; ?>">
          <li <?php echo isset($setting_menu) && $setting_menu[0] == 'nav-menus'? 'class = "active"': ''; ?>><a href="nav-menus.php">导航菜单</a></li>
          <li <?php echo isset($setting_menu) && $setting_menu[0] == 'slides'? 'class = "active"': ''; ?>><a href="slides.php">图片轮播</a></li>
          <li <?php echo isset($setting_menu) && $setting_menu[0] == 'settings'? 'class = "active"': ''; ?>><a href="settings.php">网站设置</a></li>
        </ul>
      </li>
</ul>