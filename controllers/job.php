<?php
$job=$db->get('jobs',['[><]users'=>['author'=>'uid']],['title','content','url','name'],['jid'=>$jid]);
if (!$job) {
    $template='x404';
    return;
}
$title=$job['title'];
$model['job']=$job;
$model['attachments']=$db->get('job_attachments','*',['jid'=>$jid]);
$template='job';