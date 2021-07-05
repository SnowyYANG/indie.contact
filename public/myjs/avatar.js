$('uploadAvatar').onclick=function() {
    var input = document.createElement("input");
    input.type = "file";
    input.onchange = function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();

            // Set the image for the FileReader
            reader.onload = function (e) {
                var img = document.createElement("img");
                img.src = e.target.result;
                img.onload=function(){
                    var canvas = document.createElement("canvas");
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);

                    var MAX_WIDTH = 128;
                    var MAX_HEIGHT = 128;
                    var width = img.width;
                    var height = img.height;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width;
                        width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function(blob) {
                        if (blob != null) {
                            var xhr = new XMLHttpRequest();
                            xhr.onload = function() {
                                var r = JSON.parse(xhr.responseText);
                                if (r.fail) console.log(r.fail);
                                else $("avatar").src=r.url;
                            };
                        
                            var formData = new FormData();
                            formData.append("file", blob);
                            xhr.open("POST", "/upload?type=avatar");
                            xhr.send(formData);
                        }
                        else console.log("null blob");
                    },"image/jpeg",1);
                };//img.onload
            };//reader.onload
            reader.readAsDataURL(file);
        }
    }.bind(input);
    input.click();
}
