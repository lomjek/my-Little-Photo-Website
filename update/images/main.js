/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

function Upload(){
    document.getElementById("files").disabled = true;
    document.getElementById("skupovi").disabled = true;
    document.getElementById("process").disabled = true;

    const input = document.getElementById('files');
    const files = input.files;
    
    if (files.length === 0) {
        alert('Please select at least one file.');
        document.getElementById("files").disabled = false;
        document.getElementById("skupovi").disabled = false;
        return;
    }

    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    formData.append('collection', document.getElementById("skupovi").value);

    const xhr = new XMLHttpRequest();
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            document.getElementById('progressBar').value = percentComplete;
        }
    });
    xhr.onload = function() {
        if (xhr.status === 200 || xhr.status == 202 || xhr.status == 201) {
            document.getElementById("process").disabled = false;
            console.log(xhr.responseText)
        } else {
            console.error('Error:', xhr.statusText);
            alert('Error uploading files.');
        }
    };
    xhr.open('POST', '/update/images/index.php', true);
    document.getElementById("Upload").style.display = 'none';
    document.getElementById('uploading').style.display = 'block';
    xhr.send(formData);
}
function sendProcess(){
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("process").disabled = false;
            console.log(xhr.responseText)
            alert("Done processing images!")
        } else if (xhr.status == 409) {
            alert("An instance of the converter already running.")
        } else {
            console.error('Error:', xhr.statusText);
            alert('Error uploading files.');
        }
    };
    xhr.open('PUT', '/update/images/index.php', true);
    xhr.send();
    console.log("Sent!!!")
    alert("Processing Images Started, you may leave the site now...")
}