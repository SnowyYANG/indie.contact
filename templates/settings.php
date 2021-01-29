<?php

if (!$user) {
    header('Location: /login');
    exit;
}

$page=$user;

if ($_POST) {
    if ($_POST['email']&&$user['email']!==$_POST['email']) {
        $page['email']=$_POST['email'];
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $mysqli->query("UPDATE users SET email='$_POST[email]' WHERE uid='$user[uid]'");
            if ($mysqli->affected_rows) $success['email']=true;
            else $error['email']='E-mail和已有账户重复';
        }
        else $error['email']='无效的E-mail';
    }
    if ($user['url']!==$_POST['url']) {
        if (!$_POST['url']) $_POST['url']=null;
        $page['url']=$_POST['url'];
        if ($_POST['url']===null||preg_match('/\A[a-z0-9_]*\z/',$_POST['url'])) {
            if ($_POST['url']) $mysqli->query("UPDATE users SET url='$_POST[url]' WHERE uid='$user[uid]'");
            else $mysqli->query("UPDATE users SET url=NULL WHERE uid='$user[uid]'");
            if ($mysqli->affected_rows) $success['url']=true;
            else $error['url']='URL已占用';
        }
        else $error['url']='无效的URL，URL只能包含数字、小写字母、下划线';
    }
    if ($_POST['password0']&&$_POST['password']) {
        if ($result=$mysqli->query("SELECT uid,password FROM users WHERE uid='$user[uid]'")->fetch_assoc()) {
            if (password_verify($_POST['password0'],$result['password'])) {
                $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
                $mysqli->query("UPDATE users SET password='$password' WHERE uid='$user[uid]'");
                if ($mysqli->affected_rows) $success['password']=true;
            }
            else $error['password0']='密码错误';
        } 
    }
    if (!$error) {
        $_SESSION['success']=$success;
        header('Location: /settings');
        exit;
    }
}

function view() {
    global $user,$page,$mysqli,$error;

    if ($_SESSION['success']) $success=$_SESSION['success'];
    if ($success['email']) echo '<div>E-mail修改成功。</div>';
    if ($success['url']) echo '<div>URL修改成功。</div>';
    if ($success['password']) echo '<div>密码修改成功。</div>';
    $_SESSION['success']=null;
 ?>
<form method="POST" onpost="return $('password1').value===document.forms[0].password.value">
<div>Email:<input name="email" type="email" value="<?php echo $page['email'];?>" placeholder="Email用来登录与取回密码"><?php echo $error['email']; ?></div>
<div>账户URL：https://indie.contact/<input name="url" placeholder="?u=<?php echo $user['uid']; ?>" value="<?php echo $page['url'];?>"><?php echo $error['url']; ?></div>
<div>修改密码时才填写以下3行：</div>
<div>原密码:<input name="password0" type="password"><?php echo $error['password0']; ?></div>
<div>新密码:<input name="password" type="password" oninput="p_oninput()"></div>
<div>确认新密码:<input id="password1" type="password" oninput="p_oninput()"><span id="notmatch" style="visibility:hidden">密码不匹配</span></div>
<input type="submit">
</form>
<script>
function p_oninput() {
    $('notmatch').style.visibility=$('password1').value===document.forms[0].password.value?'hidden':'visible';
}
</script>
<?php } ?>