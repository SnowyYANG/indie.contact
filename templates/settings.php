<?php

if (!$user) {
    header('Location: /login');
    exit;
}

$page=$user;

function view() {
    global $user,$page,$mysqli;
 ?>
<form method="POST" onpost="return $('password1').value===document.forms[0].password.value">
<div>Email:<input name="email" type="email" value="<?php echo $page['email'];?>" placeholder="Email用来登录与取回密码"></div>
<div>账户URL：https://indie.contact/<input name="url" placeholder="?u=<?php echo $user['uid']; ?>" value="<?php echo $user['url'];?>"></div>
<div>修改密码时才填写以下3行：</div>
<div>原密码:<input name="password0" type="password"></div>
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