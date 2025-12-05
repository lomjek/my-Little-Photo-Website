/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

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
            processImages(xhr.responseText)
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
function processImages(json_string) {
    const paths = JSON.parse(json_string).results
        .filter(item => item.message === "success")
        .map(item => item.path);

    console.log(paths)

    let path_string = paths.join(",")
    let Data = 'func=process_images'
    Data += '&&collection=' + encodeURIComponent(document.getElementById("skupovi").value.replace(/ /g, '-'));
    Data += '&&paths=' + encodeURIComponent(path_string.replace(/ /g, '-'));

    fetch('/libs/images.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: Data
    }).then(response => {
        if (response.ok) {
            return response.text().then(text => {
                console.log(text);
                alert("Images Uploaded succesfully");
                window.location.reload();
            });
        } else {
            console.error('Error:', response.statusText, 'Status:', response.status);
            alert('Error uploading files. Try again');
            window.location.reload();
        }
    }).catch(error => {
        console.error('Network Error:', error);
        alert('Error uploading files. Try again');
        window.location.reload();
    });
}
