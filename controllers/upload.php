<?php
error_reporting(E_ALL);
if ($uid) {
    if (!file_exists(FILE_DIR .$uid)) {
        mkdir(FILE_DIR .$uid, 0777, true);
    }
    $count = $db->count('works', ['uid'=>$uid]);
    if ($count < 10) {
        $uploadfile = FILE_DIR .$user['uid'].'/'. basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            $desc = basename($_FILES['file']['name']);
            $url = "/attachments/$user[uid]/".basename($_FILES['file']['name']);
            $db->insert('works',['uid'=>$uid,'url'=>$url,'desc'=>$desc,'order'=>$count,'size'=>$_FILES['file']['size']]);
            echo json_encode(['url'=>$url]);
        }
    }
}
exit;
