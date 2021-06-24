<?php
function pagination($page) {
    $new_data = array("currentpage" => "mypage.html");
    $full_data = array_merge($_GET, $new_data);  // New data will overwrite old entry
    return http_build_query($full_data);
}