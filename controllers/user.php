<?php
$columns=$_REQUEST['a']==='edittags'?['uid','name']:'*';
if ($_REQUEST['u']) $page=$db->get('users',$columns,['uid'=>$_REQUEST['u']]);
else if ($url) $page=$db->get('users',$columns,['url'=>$url]);
if (!($model['page']=$page)) {
    $template='x404';
    return;
}
$editable=$admin||$page['uid']===$uid;
if ($editable&&$_GET['a']==='edittags') {
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
            $bio_html=content2html($_POST['bio']);
            $contact2_html=content2html($_POST['contact2']);
            if ($db->update('users',['name'=>$_POST['name'],'role0'=>$_POST['role0'],'role'=>$_POST['role'],'contact'=>$_POST['contact'],'contact2'=>$_POST['contact2'],'contact2_html'=>$contact2_html,'bio'=>$_POST['bio'],'bio_html'=>$bio_html],['uid'=>$uid])->rowCount()) {
                header('Location: '.user2url($user));
                exit;
            }
        }
        $template='user_edit';
    }
    else $template='user';
    $model['tags']=$db->select('user_tags',['[><]tags'=>['tagid'=>'tagid']],['tagname']);
}
$title=$page['name'];
