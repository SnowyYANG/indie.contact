<?php
require_once 'config.php';
require_once 'Medoo.php';
include 'Template.php';

use Medoo\Medoo;

$db =  new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',
    'charset' => 'utf8mb4',
    'database' => 'indie',
    'username' => MYSQL_USER,
    'password' => MYSQL_PASSWORD
]);

session_start();
//auto login
if ($uid=$_SESSION['uid']) $user = $db->get('users',['uid','name','email','url'],['uid'=>$uid]);
else if ($_COOKIE['uid']&&$_COOKIE['sessionkey']) {
    $user=$db->get('users',['uid','name','email','url','sessionkey'],['uid'=>$_COOKIE['uid']]);
    if ($_COOKIE['sessionkey'] === $result['sessionkey']) $uid=$_SESSION['uid']=$user['uid'];
    else $user=null;
}

if ($url = $_REQUEST['q'])
{
    if ($url[0]==='/') $url=substr($url,1);
    if (in_array($url, ['login','settings','upload'])) $controller=$url;
    else if (substr($url,0,4)==='job/'&&strlen($url)>4) {
        $controller='job';
        $jid=substr($url,4);
    }
    else $controller = 'user';
}
else if ($_REQUEST['u']) {
    $controller='user';
}
else  $controller='homepage';

function user2url($row) {
    return '/'.($row['url']??"?u=$row[uid]");
}

$model=['user'=>$user];
require 'controllers/'.$controller.'.php';
if ($template) Template::view($template.'.html', $model);
?>
