/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

function change_bg_color(){
    let color = document.getElementById("bcolor").value;
    document.body.style.setProperty("background-color", color, "important");
    document.getElementById('Title').style.setProperty("background-color", color, "important");
    document.getElementById('desc').style.setProperty("background-color", color, "important");
    document.getElementById('desc').style.setProperty("background-color", color, "important");
    document.getElementById('add').style.setProperty("background-color", color, "important");
}
function change_t_col(){
    let color = document.getElementById("tcolor").value;
    document.getElementById('Title').style.setProperty("color", color, "important");
    document.getElementById('leave').style.setProperty("color", color, "important");
    document.getElementById('desc').style.setProperty("color", color, "important");
    document.getElementById('desc').style.setProperty("color", color, "important");
    document.getElementById('add').style.setProperty("color", color, "important");
}

//input
function leave(){
    fetch('main.php', {method: 'DELETE'})
    .then(response => {
        if (!response.ok) {
            alert("Cleanup didn't work: " + response.status + "\nYou shouldn't encounter any issues, but you should inform me.");
        }
        window.location.assign('/update/');
    })
}
async function sendRequest(formData){
    fetch('main.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert("There was an error. There shouldn't be any interferences.\nError code: " + response.status);
            return false;
        } else {
            return true;
        }
    })
}
function rename(){
    const formData = new URLSearchParams();
    formData.append('a', 'rename');
    formData.append('c', original_name);
    formData.append('d', document.getElementById('Title').value);
    if (sendRequest(formData)){
        original_name = document.getElementById('Title').value
    }
}
function save_description(){
    const formData = new URLSearchParams();
    formData.append('a', 'description');
    formData.append('c', original_name);
    formData.append('d', document.getElementById('desc').value);
    sendRequest(formData);
}
function save_colors(){
    const formData = new URLSearchParams();
    formData.append('a', 'colors');
    formData.append('c', original_name);
    let colors = [document.getElementById('bcolor').value, document.getElementById('tcolor').value];
    formData.append('d', colors.join('\n'));
    sendRequest(formData);
}
function publish(){
    const formData = new URLSearchParams();
    formData.append('a', 'publish');
    formData.append('c', original_name);
    if (sendRequest(formData)){
        window.location = '/update/collection/edit/' + original_name.replace(' ', '-');
    }
}

function redirect_to_delete(){
    window.location = '/update/collection/delete';
}
//When page loaded...
window.addEventListener('load', function() {
    original_name = document.getElementById('Title').value;
});
