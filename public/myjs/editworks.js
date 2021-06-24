var _el;
function isBefore(el1, el2) {
    if (el2.parentNode === el1.parentNode)
    for (var cur = el1.previousSibling; cur && cur.nodeType !== 9; cur = cur.previousSibling)
        if (cur === el2)
        return true;
    return false;
}
function onDragStart(e) {
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text/plain", null); // Thanks to bqlou for their comment.
    _el = e.target;
}
function onDragOver(e) {
    var target;
    for (target = e.target;target.parentNode.id != 'works';target = target.parentNode);
    if (isBefore(_el, target))
        target.parentNode.insertBefore(_el, target);
    else
        target.parentNode.insertBefore(_el, target.nextSibling);
}
$('uploadbutton').onclick=function() {
    var input = document.createElement('input');
    input.type = 'file';
    input.onchange = function(e) {
        var formData = new FormData();
        var file = e.target.files[0];
        formData.append("file", file);
        var xhr = new XMLHttpRequest();
        xhr.upload.onprogress=function(e2) {
            $('uploadbutton').innerHTML='正在上传'+e2.loaded/e2.total*100+'%';
        }
        xhr.upload.onload = function(e3) {
            $('uploadbutton').innerHTML='上传附件';
        }
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                var node = document.createElement("li");
                node.draggable=true;
                node.ondragstart = onDragStart;
                node.ondragover = onDragOver;
                var url = JSON.parse(xhr.responseText).url;
                node.innerHTML='<a draggable="false" href="'+url+'">'+url+'</a>';
                $('works').appendChild(node);
            }
        }
        xhr.open('POST', '/upload');
        xhr.send(formData);
    };
    input.click();
}
