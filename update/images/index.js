/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

// @ {} [] # \ || != ~

function Upload() {
    document.getElementById("files").disabled = true;
    document.getElementById("skupovi").disabled = true;

    const input = document.getElementById('files');
    const files = input.files;

    console.log("I live");

    if (files.length === 0) {
        alert('Please select at least one file.');
        document.getElementById("files").disabled = false;
        document.getElementById("skupovi").disabled = false;
        return;
    }

    const formData = new FormData();
    formData.append('collection', document.getElementById('skupovi').value);
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }

    const xhr = new XMLHttpRequest();

    xhr.upload.addEventListener('progress', function (e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            document.getElementById('progressBar').value = percentComplete;
        }
    });

    xhr.onload = function () {
        if (xhr.status === 200 || xhr.status == 202 || xhr.status == 201) {
            console.log(xhr.responseText)
            alert("Image processing will be done in background. You may leave this site.")
        } else {
            console.error('Error:', xhr.statusText);
            alert('Error uploading files.');
        }
    };

    xhr.open('POST', '/libs/upload.php', true);
    document.getElementById("Upload").style.display = 'none';
    document.getElementById('uploading').style.display = 'block';
    xhr.send(formData);
}
