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

if ($url = $_REQUEST['q'] && $url != 'login' && $url != 'edit') {
    $mysqli->escape_string($url);
    if ($page = $mysqli->query("SELECT * FROM users WHERE url='$url'")->fetch_assoc()) {
        $template = 'user';
        $title = "$user[name] - indie.contact";
    }
}
else {
    $template = 'homepage';
    $title = "indie.contact";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title;?></title>
        <link href="/rfwiki/rfwiki.css" type="text/css" rel="stylesheet"/>
    </head>
    <body>
<?php

function user2url($row) {
    return $row['url']??"?u=$row[id]";
}

echo '<div style="border-bottom:1px solid;padding-bottom:0.5em">indie.contact<a href="/login"><div style="float:right">登录/注册</div></a></div>';
echo '<div>在indie.contact找找独立游戏策划、美术、程序、音乐、文案、测试人的联系方式吧。你也可以发布组队招募的消息。</div>';
echo '<div style="margin:1em">';
echo '<div style="font-weight:bold">最新加入</div>';
$result=$mysqli->query("SELECT * FROM users ORDER BY time DESC LIMIT 10");
while($row=$result->fetch_assoc()) {
    echo "<div><a href=\"/".user2url($row)."\">$row[name]</a> $row[role] $row[contact]</div>";
}
echo '</div>';
echo '<div style="margin:1em">';
echo '<div style="font-weight:bold">最新招募</div>';
$result=$mysqli->query("SELECT * FROM jobs ORDER BY time DESC LIMIT 10");
while($row=$result->fetch_assoc()) {
    echo "<a href=\"/jobs/$row[id]\"><div>$row[title]</div></a>";
}
echo '</div>';
?>
<footer>indie.contact © 2021</footer>
</body>
</html>