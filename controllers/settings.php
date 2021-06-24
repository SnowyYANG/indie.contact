<?php

if (!$user) {
    header('Location: /login');
    exit;
}

$pageuid=$uid==='1'&&$_REQUEST['u']?$_REQUEST['u']:$uid;

if ($_POST) {
    if ($_POST['email']&&$user['email']!==$_POST['email']) {
        $page['email']=$_POST['email'];
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            if ($db->update('users',['email'=>$_POST[email]],['uid'=>$pageuid])->rowCount()) $success['email']=true;
            else $error['email']='E-mail和已有账户重复';
        }
        else $error['email']='无效的E-mail';
    }
    if ($user['url']!==$_POST['url']) {
        $url=$page['url']=$_POST['url'];
        if ($url===null||$url==='') $url=null; //$page['url'] in model should still be ''
        if (!$url||in_array($url,$routers)) $error['url']='不能使用的保留词语';
        else if (!preg_match('/\A[a-z0-9_]*\z/',$url)) $error['url']='无效的URL，URL只能包含数字、小写字母、下划线';
        else {
            if ($db->update('users',['url'=>$url],['uid'=>$pageuid])->rowCount()) $success['url']=true;
            else $error['url']='URL已占用';
        }
    }
    if (($uid==='1'||$_POST['password0'])&&$_POST['password']) {
        if ($uid==='1'||($pageuser=$db->get('users',['password'],['uid'=>$pageuid]))) {
            if ($uid==='1'||password_verify($_POST['password0'],$pageuser['password'])) {
                if ($db->update('users',['password'=>password_hash($_POST['password'],PASSWORD_DEFAULT)],['uid'=>$pageuid])->rowCount()) $success['password']=true;
            }
            else $error['password0']='原密码错误';
        } 
    }
    if (!$error) {
        $_SESSION['success']=$success;
        header('Location: /settings');
        exit;
    }
}

if ($_SESSION['success']) {
    $model['success']=$_SESSION['success'];
    $_SESSION['success']=null;
}
$model['page']=$db->get('users',['uid','email','url'],['uid'=>$uid==='1'&&$_REQUEST['u']?$_REQUEST['u']:$uid]);
$template='settings';