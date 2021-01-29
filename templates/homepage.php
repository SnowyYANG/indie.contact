<?php
function view() {
global $mysqli,$user,$page; ?>
<div>在indie.contact找找独立游戏策划、美术、程序、音乐、文案、测试人的联系方式吧。你也可以发布组队招募的消息。</div>
<div style="margin:1em">
<div style="font-weight:bold">最新加入</div>
<?php 
$result=$mysqli->query("SELECT * FROM users ORDER BY ucreatetime DESC LIMIT 10");
while($row=$result->fetch_assoc()) {
    echo "<div><a href=\"".user2url($row)."\">$row[name]</a> $row[role] $row[contact]</div>";
}
echo '</div>';
echo '<div style="margin:1em">';
echo '<div style="font-weight:bold">最新招募</div>';
$result=$mysqli->query("SELECT * FROM jobs ORDER BY jtime DESC LIMIT 10");
while($row=$result->fetch_assoc()) {
    echo "<a href=\"/job/$row[jid]\"><div>$row[title]</div></a>";
}?></div>
<?php } ?>