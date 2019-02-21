<?php
    require_once '../../config.php';
    require_once '../../functions.php';
    header('Content-Type:application/json');
    //数据总条数
    $total_pages=(int)xiu_fetch_once("select 
        count(1) as num 
        from  comments
        inner join posts on comments.post_id = posts.id")['num'];
    $size=3;
    //最大页码数
    $max_page=ceil($total_pages/$size);

    $page=empty($_GET['page'])?1:$_GET['page'];
    $page=$page>$max_page?$max_page:$page;

    $offset=($page-1)*$size;
    $sql=sprintf('select
      comments.id,
      comments.author,
      comments.content,
      posts.title as posts_title,
      comments.created,
      comments.status
      from comments
      inner join posts on comments.post_id = posts.id
      order by comments.created desc
      limit %d,%d;',$offset,$size);
    $comments_data=xiu_fetch_all($sql);



    $json=json_encode(array(
        'max_page' => $max_page,
        'comment' => $comments_data
    ));
    echo $json;
//    var_dump($comments_data);


