/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

function change_bg_color() {
    const allBodyElements = document.body.querySelectorAll('*');
    allBodyElements.forEach(element => {
        element.style.setProperty("background-color", document.getElementById("bcolor").value, "important");
    });
    document.body.style.setProperty("background-color", document.getElementById("bcolor").value, "important");
}
function change_t_col() {
    const allBodyElements = document.body.querySelectorAll('*');
    allBodyElements.forEach(element => {
        element.style.setProperty("color", document.getElementById("tcolor").value, "important");
    });
}

//input
function leave() {
    window.location.assign('/update/');
}
async function sendRequest(urlencoded_data) {
    try {
        const response = await fetch('/libs/collections.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: urlencoded_data
        })
        console.log(response);
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}. Message: ${errorText}`);
        }
        console.log(await response.text());
        return true;
    } catch (error) {
        alert('Error during request: ' + error);
        return false;
    }
}
function rename() {
    const formData = new URLSearchParams();
    formData.append('a', 'rename');
    formData.append('c', original_name);
    formData.append('d', document.getElementById('Title').value);
    if (sendRequest(formData)) {
        original_name = document.getElementById('Title').value
    }
}
function save_description() {
    const formData = 'func=set_collection_description&&collection=' + encodeURIComponent(original_name.replaceAll(" ", "-")) + '&&description=' + encodeURIComponent(document.getElementById('desc').value);
    sendRequest(formData);
}
function save_colors() {
    let colors = [document.getElementById('bcolor').value, document.getElementById('tcolor').value];
    let formData = 'func=set_collection_colors&&collection=' + encodeURIComponent(original_name.replaceAll(" ", "-")) + '&&bg_color=' + encodeURIComponent(colors[0]) + '&&text_color=' + encodeURIComponent(colors[1]);
    sendRequest(formData);
}
function set_visibility() {
    const formData = 'func=set_collection_visibility&&collection=' + encodeURIComponent(original_name.replaceAll(" ", "-")) + '&&visibility=' + encodeURIComponent(document.getElementById('visibility').value);
    sendRequest(formData)
}
async function ask_delete() {
    const userChoice = confirm("Dali ste sigurni, da bi izbrisali ovaj skup?\nJednom kad ga nema, ga nema...");
    var send_thing = 'func=delete_collection&&collection=' + original_name.replaceAll(" ", "-");
    console.log(send_thing);
    if (sendRequest(send_thing)) {
        window.location = '/update/';
    }
}
//When page loaded...
window.addEventListener('load', function () {
    original_name = document.getElementById('Title').value;
});
