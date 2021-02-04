<?php
error_reporting(E_ALL);
if ($user) {
    if (!file_exists(FILE_DIR .$user['uid'])) {
        mkdir(FILE_DIR .$user['uid'], 0777, true);
    }
    $count = (int)($mysqli->query("SELECT COUNT(*) as c FROM attachments WHERE uid='$user[uid]'")->fetch_assoc()['c']);
    if ($count < 10) {
        $uploadfile = FILE_DIR .$user['uid'].'/'. basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $desc = basename($_FILES['file']['name']);
            $url = "/attachments/$user[uid]/".basename($_FILES['file']['name']);
            $mysqli->query("INSERT INTO attachments(uid,url,`desc`,`order`) VALUES('$user[uid]','$url','$desc',$count)");
            if ($mysqli->affected_rows)
                echo json_encode(['url'=>$url]);
        }
    }
}
exit;
