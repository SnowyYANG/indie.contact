<?php
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = strlen($keyspace) - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

if ($_POST['email']&&$_POST['password']) {
    $email=$mysqli->escape_string($_POST['email']);
    if ($result=$mysqli->query("SELECT uid,password FROM users WHERE email='$email'")->fetch_assoc()) {
        if (password_verify($_POST['password'],$result['password'])) {
            $sessionkey=random_str();
            $mysqli->query("UPDATE users SET sessionkey='$sessionkey' WHERE uid='$result[uid]'");
            $_SESSION['uid']=$result['uid'];
            setcookie("uid", $result['uid'], time()+31536000,'/');
            setcookie("sessionkey", $sessionkey, time()+31536000,'/');
            header('Location: /');
            exit;
        }
    }
}

?>
<form method="POST">
<div>E-mail:<input name="email" type="email" required></div>
<div>密码:<input name="password" type="password" required></div>
<input type="submit">
</form>