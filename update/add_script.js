/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

var responses = ""
var data = ""

function sync_collections(){
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                responses = xhr.responseText.split('\n');
            } else {
                console.error('Request failed with status:', xhr.status);
            }
        }
    };
    xhr.open('POST', '../loading.php', true);
    xhr.send();
    }
    
function toggle_new_ui(enable){
    if (enable) {
        sneaky()
        document.getElementById("cdate").style.display = "none";
        document.getElementById("lcdate").style.display = "none";
        document.getElementById("cbcol").style.display = "none";
        document.getElementById("lcbcol").style.display = "none";
        document.getElementById("ctcol").style.display = "none";
        document.getElementById("lctcol").style.display = "none";
        document.getElementById("newc").value = "false";
    } else {
        unsneaky()
        document.getElementById("cdate").style.display = "block";
        document.getElementById("lcdate").style.display = "block";
        document.getElementById("cbcol").style.display = "block";
        document.getElementById("lcbcol").style.display = "block";
        document.getElementById("ctcol").style.display = "block";
        document.getElementById("lctcol").style.display = "block";
        document.getElementById("newc").value = "true";
    } 
}

function unsneaky(){
    const form = document.getElementById("f2")
    while (form.firstChild){
        form.removeChild(form.firstChild)
    }
}

function sneaky(){
    const form = document.getElementById("f2")
    while (form.firstChild){
        form.removeChild(form.firstChild)
    }
    const submit = document.createElement('input')
    submit.id = "delete"
    submit.type = "submit"
    submit.value = "Delete"

    const collect = document.createElement('input')
    collect.id = "collect"
    collect.type = "text"
    collect.classList.add("hidden")
    collect.name = "collect"
    collect.value = document.getElementById("cname").value

    const backgrnd = document.createElement('input')
    backgrnd.id = "backgrnd"
    backgrnd.type = "text"
    backgrnd.classList.add("hidden")
    backgrnd.name = "backgrnd"
    backgrnd.value = data[3]

    const foregrnd = document.createElement('input')
    foregrnd.id = "foregrnd"
    foregrnd.type = "text"
    foregrnd.classList.add("hidden")
    foregrnd.name = "foregrnd"
    foregrnd.value = data[4]
    
    form.appendChild(submit)
    form.appendChild(collect)
    form.appendChild(backgrnd)
    form.appendChild(foregrnd)
}

function is_collection_new(){
    document.getElementById("cname").value = document.getElementById("cname").value.replace(/[.,><:;/?!@#$%^&*()_+={$$$$\\}]/g, '');
    var needle = document.getElementById("cname").value;

    for (const line of responses){
        if (line === ""){
            break
        }

        data = line.split(":");
        name = data[0].replaceAll('-', " ");
        if (name == needle || data[0] == needle){
            document.body.style.backgroundColor = data[3];
            document.body.style.color = data[4];

            toggle_new_ui(true);
            break
        } else {
            document.body.style.color = "#000"
            document.body.style.backgroundColor = "#aaa"
            toggle_new_ui(false);
        }
    }
}

function change_background(){
    var bcolor = document.getElementById("cbcol").value;
    document.body.style.backgroundColor = bcolor;
}

function change_color(){
    var tcolor = document.getElementById("ctcol").value;
    document.body.style.color = tcolor;
}

sync_collections();
