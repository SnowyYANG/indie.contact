<?php
$page=$_GET['page'];
if ($page<1) $page=1;
$where=['ORDER'=>['utime'=>'DESC'],'LIMIT'=>[($page-1)*30,30]];
if ($_GET['tags']) {
    $model['tags']=$db->select('tags',['tagid','tagurl','tagname']);
    $title='搜索用户';
    $model['users']=$db->select('users',['[><]user_tags'=>['uid'=>'uid','tagid'=>$_GET['tags']]],['@uid','url','name','role','contact'],$where);
}
else {
    $join=[];
    $title='全部用户';
    $model['users']=$db->select('users',['uid','url','name','role','contact'],$where);
}
$model['page']=$page;
$template='users';