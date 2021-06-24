<?php
function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'):string {
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

if (($email=$model['email']=$_POST['email'])&&$_POST['password']) {
    if (($result=$db->get('users',['uid','password'],['email'=>$email]))&&password_verify($_POST['password'],$result['password'])) {
        $sessionkey=random_str();
        $db->update('users',['sessionkey'=>$sessionkey],['uid'=>$result['uid']]);
        $_SESSION['uid']=$result['uid'];
        setcookie("uid", $result['uid'], time()+31536000,'/');
        setcookie("sessionkey", $sessionkey, time()+31536000,'/');
        header('Location: /');
        exit;
    }
}

$template='login';
