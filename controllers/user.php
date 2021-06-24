<?php
if ($_REQUEST['u']) $pageuser=$db->get('users','*',['uid'=>$_REQUEST['u']]);
else if ($url) $pageuser=$db->get('users','*',['url'=>$url]);
if (!($model['page']=$pageuser)) {
    $template='x404';
    return;
}
$model['works']=$db->select('works','*',['uid'=>$pageuser['uid'],'ORDER'=>'order']);
if ($_REQUEST['a']==='edit'&&($admin||$uid===$pageuser['uid'])) {
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
$model['title']=$pageuser['name'];