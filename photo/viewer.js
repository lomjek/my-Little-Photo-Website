/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

function back_to_collection(collection) {
    window.location.assign('/photos/' + collection)
}

function set_description_visibility(to) {
    document.getElementById("description_container").style.display = to ? "flex" : "none";
}

function toggle_description_visibility() {
    const desc_element = document.getElementById("description_container")
    var visible = desc_element.style.display == "flex"
    if (visible) {
        set_description_visibility(false);
    } else {
        set_description_visibility(true);
    }
} 