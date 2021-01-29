<?php

function view() {
    global $mysqli,$user,$page; 
    echo "<h2>$page[title]</h2>";
    echo "<div>by <a href=\"".user2url($page)."\">$page[name]</a></div>";
    echo $content;
    var_dump($page);
}
