<?php
$page=$_GET['page'];
if ($page<1) $page=1;
$model['page']=$page;
$model['jobs']=$db->select('jobs',['[><]users'=>['author'=>'uid']],['jid','title','uid','url','name','jtime'],['ORDER'=>['utime'=>'DESC'],'LIMIT'=>[($page-1)*50,50]]);
$template='jobs';
$title='全部招募';