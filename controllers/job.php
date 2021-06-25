<?php
if ($jid!=='new') {
    $job=$db->get('jobs',['[><]users'=>['author'=>'uid']],['title','content','url','uid','name','jtime'],['jid'=>$jid]);
    if (!$job) {
        $template='x404';
        return;
    }
}
$edit=$jid==='new'||$_REQUEST['a']==='edit'&&($admin||$job['uid']===$uid);

if ($edit&&$_POST) {
    if ($jid==='new') {
        $db->insert('jobs',['title'=>$_POST['title'],'content'=>$_POST['content']]);
        $jid=$db->id();
        //set job_attachments jid
        header('Location: /job/'.$jid);
        exit;
    }
    if ($db->update('jobs',['title'=>$_POST['title'],'content'=>$_POST['content']],['jid'=>$jid])->rowsCount()) {
        header('Location: /job/'.$jid);
        exit;
    }
    $job['title']=$_POST['title'];
    $job['content']=$_POST['content'];
}

$title=$edit?$jid==='new'?'创建招募':'编辑招募':$job['title'];
$model['job']=$job;
$model['attachments']=$db->get('job_attachments','*',['jid'=>$jid]);
$template=$edit?'job_edit':'job';