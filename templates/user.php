<?php
if ($_REQUEST['a']==='edit'&&$user['uid']===$page['uid']) {
    if ($_POST) {
        $name=$mysqli->escape_string($_POST['name']);
        $role=$mysqli->escape_string($_POST['role']);
        $contact=$mysqli->escape_string($_POST['contact']);
        $page=$mysqli->escape_string($_POST['page']);
        $mysqli->query("UPDATE users SET name='$name',role='$role',contact='$contact',page='$page' WHERE uid='$user[uid]'");
        if ($mysqli->affected_rows) {
            header('Location: '.user2url($user));
            exit;
        }
    }
}
function view() {
    global $mysqli,$user,$page;
    if ($_REQUEST['a']==='edit') {
?>
<form method="POST">
<div><input name="name" value="<?php echo $page['name'];?>" placeholder="昵称" required></div>
<div><input name="role" value="<?php echo $page['role'];?>" placeholder="职能（程序/美术/音乐/...）" required></div>
<div><input name="contact" value="<?php echo $page['contact'];?>" placeholder="主要联系方式" required></div>
<textarea name="page"><?php echo $page['page'] ?></textarea>
<script>
function upload() {
    var input = document.createElement('input');
    input.type = 'file';
    input.onchange = function(e) {
        var formData = new FormData();
        var file = e.target.files[0];
        formData.append("file", file);
        var xhr = new XMLHttpRequest();
        xhr.onprogress=function(e2) {
            var contentLength;
            if (e2.lengthComputable) {
                contentLength = e2.total;
            } else {
                contentLength = file.size;
            }
            $('uploadbutton').innerHTML='上传文件'+e2.loaded/contentLength*100+'%';
        }
        xhr.onload = function(e3) {
            $('uploadbutton').innerHTML='上传文件 100%';
        }
        xhr.open('POST', '/upload');
        xhr.send(formData);
    };
    input.click();
}
</script>
<div><button id="uploadbutton" type="button" onclick="upload()">上传附件</button>支持jpg/png/gif/mp3，最多上传10个。大文件尽量传到外站，然后在个人介绍中插入URL。</div>
<input type="submit">
</form>
<?php
    }
    else {
        if ($user['uid']===$page['uid']) echo "<a href=\"/settings\">账户设置</a> <a href=\"/?u=$user[uid]&a=edit\">编辑个人资料</a>";
        echo "<h2>$page[name]</h2>";
        echo "<div>$page[role]</div>";
        echo "<div>主要联系方式：$page[contact]</div>";
        $page_html=$page['page'];
        $page_html=preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="$0" target="_blank" title="$0">$0</a>', $page_html);
        $page_html=nl2br($page_html);
        echo "<div style=\"margin-top:1em\">$page_html</div>";
        echo "<div style=\"color:grey\">上次更新：$page[utime]</div>";
    }
}