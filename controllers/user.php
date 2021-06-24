<?php
$columns=$_REQUEST['a']==='edittags'?['uid','name']:'*';
if ($_REQUEST['u']) $page=$db->get('users',$columns,['uid'=>$_REQUEST['u']]);
else if ($url) $page=$db->get('users',$columns,['url'=>$url]);
if (!($model['page']=$page)) {
    $template='x404';
    return;
}
$editable=$admin||$page['uid']===$uid;
if ($editable&&$_REQUEST['a']==='edittags') {
    $template='user_edittags';
    $model['tags']=$db->select('tags','*');
    $model['selection']=[];
    $db->select('user_tags',['tagid'],['uid'=>$page['uid']],function($data){
        global $model;
        array_push($model['selection'],$data['tagid']);
    });
}
else {
    $model['works']=$db->select('works','*',['uid'=>$page['uid'],'ORDER'=>'order']);
    if ($editable&&$_REQUEST['a']==='edit') {
        if ($_POST) {
            $result=$db->update('users',['name'=>$_POST['name'],'role'=>$_POST['role'],'contact'=>$_POST['contact'],'bio'=>$_POST['bio']],['uid'=>$uid]);
            if ($result->rowCount()) {
                header('Location: '.user2url($user));
                exit;
            }
        }
        $template='user_edit';
    }
    else $template='user';
    $model['tags']=$db->select('user_tags',['[><]tags'=>['tagid'=>'tagid']],['tagname']);
}
$model['title']=$page['name'];
