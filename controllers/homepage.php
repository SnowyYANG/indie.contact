<?php
$model['users']=$db->select('users',['uid','url','name','role0','role','contact'],['ORDER'=>['ucreatetime'=>'DESC'],'LIMIT'=>[0,10]]);
$model['jobs']=$db->select('jobs',['[><]users'=>['author'=>'uid']],['jid','title','uid','url','name','jtime'],['ORDER'=>['jcreatetime'=>'DESC'],'LIMIT'=>[0,10]]);
$model['hottags']=$db->select('tags',['tagurl','tagname'],['hot'=>true]);
$template='homepage';