<?php
function pagination($page) {
    $new_data = array("currentpage" => "mypage.html");
    $full_data = array_merge($_GET, $new_data);  // New data will overwrite old entry
    return http_build_query($full_data);
}
function content2html($content) {
    return nl2br(preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="$0" target="_blank">$0</a>', htmlspecialchars($content)));
}