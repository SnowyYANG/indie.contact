<?php
$model['users']=$db->select('users',['uid','url','name','role'],['ORDER'=>['ucreatetime'=>'DESC'],'LIMIT'=>[0,10]]);
$model['jobs']=$db->select('jobs',['jid','title'],['ORDER'=>['jtime'=>'DESC'],'LIMIT'=>[0,10]]);
$template='homepage';