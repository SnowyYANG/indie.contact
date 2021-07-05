<?php
//error_reporting(E_ALL);
if ($uid) {
    if ($_FILES['file']) {
        $ugroup=intdiv($uid,1000);
        $userdir=FILE_DIR.'/'.$ugroup.'/'.$uid;
        if (!file_exists($userdir)) mkdir($userdir, 0777, true);
        switch ($_REQUEST['type']) {
            case 'avatar':
                if ($_FILES['file']['size']<=1024*1024) {
                    $filename=time().'.jpg';
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $userdir.'/'.$filename)) {
                        $old=$db->get('users',['avatar'],['uid'=>$uid])['avatar'];
                        if ($old[1]==='u') unlink(FILE_DIR.substr($old,8));
                        $url="/uploads/$ugroup/$uid/$filename";
                        $db->update('users',['avatar'=>$url],['uid'=>$uid]);
                        $r=['url'=>$url];
                    }
                    else $r=['fail'=>'move'];
                }
                else $r=['fail'=>'size'];
                break;
            default:
                $count = $db->count('works', ['uid'=>$uid]);
                if ($count < 10) {
                    $path = $userdir.'/'. basename($_FILES['file']['name']);
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
                        $desc = basename($_FILES['file']['name']);
                        $url = "/uploads/$ugroup/$uid/".basename($_FILES['file']['name']);
                        $db->insert('works',['uid'=>$uid,'url'=>$url,'desc'=>$desc,'order'=>$count,'size'=>$_FILES['file']['size']]);
                        $r=['url'=>$url];
                    }
                }
        }//switch
    }
    else $r=['fail'=>'no file'];
}
else $r=['fail'=>'uid'];
echo json_encode($r);
exit;
