<?php
error_reporting(E_ALL);
if ($user) {
    if (!file_exists(FILE_DIR .$user['uid'])) {
        mkdir(FILE_DIR .$user['uid'], 0777, true);
    }
    $uploadfile = FILE_DIR .$user['uid'].'/'. basename($_FILES['file']['name']);
    var_dump(move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile));
}
exit;