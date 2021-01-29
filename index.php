<?php
require_once 'config.php';
if ($mysqli = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWORD,'indie')) {
    $mysqli->set_charset('utf8mb4');
}
else {
    http_response_code(503);
    echo '维护中...';
    exit;
}

session_start();
if (!($uid=$_SESSION['uid'])) {
    if ($sessionkey=$_COOKIE['sessionkey']) {
        $_uid=$mysqli->escape_string($_COOKIE['uid']);
        if ($result=$mysqli->query("SELECT name,sessionkey FROM users WHERE uid='$_uid'")->fetch_assoc()) {
            if ($sessionkey === $result['sessionkey']) $uid=$_SESSION['uid']=$_uid;
        }
    }
}
if ($uid) $user = $mysqli->query("SELECT uid,name,email,url FROM users WHERE uid='$uid'")->fetch_assoc();

if ($url = $_REQUEST['q'])
{
    if ($url[0]==='/') $url=substr($url,1);
    if (in_array($url, ['login','settings','upload'])) {
        $template=$url;
    }
    else if (substr($url,0,4)==='job/') {
        $template = 'job';
        $id=$mysqli->escape_string(substr($url,4));
        $page=$mysqli->query($sql="SELECT * FROM jobs INNER JOIN users ON jobs.author=users.uid WHERE jobs.jid='$id'")->fetch_assoc();
        $title=$page['title'];
    }
    else {
        $url=$mysqli->escape_string($url);
        $sql = "SELECT * FROM users WHERE url='$url'";
        $template = 'user';
    }
}
else if ($pageuid = $_REQUEST['u']) {
    $pageuid=$mysqli->escape_string($pageuid);
    $sql = "SELECT * FROM users WHERE uid='$pageuid'";
    $template = 'user';
}
if ($template === 'user') {
    $page = $mysqli->query($sql)->fetch_assoc();
    $title = "$user[name] - indie.contact";
}
if (!$template) {
    $template = 'homepage';
    $title = "indie.contact";
}

function user2url($row) {
    return '/'.($row['url']??"?u=$row[uid]");
}

require 'templates/'.$template.'.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title;?></title>
        <!--link href="/main.css" type="text/css" rel="stylesheet"/-->
        <script src="/main.js"></script>
    </head>
    <body>
    <div style="border-bottom:1px solid;padding-bottom:0.5em"><a href="/">indie.contact</a><div style="float:right">
<?php
    if ($user) echo "<a href=\"".user2url($user)."\">$user[name]</a>";
    else echo '<a href="/login">登录/注册</a>';
echo '</div></div>';

view();

?>
<footer>indie.contact © 2021</footer>
</body>
</html>