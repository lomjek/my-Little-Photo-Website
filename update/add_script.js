/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

var responses = ""
function sync_collections(){
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                responses = xhr.responseText.split('\n');
                console.log(responses);
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
        document.getElementById("delete").style.display = "block";
        document.getElementById("cdate").style.display = "none";
        document.getElementById("lcdate").style.display = "none";
        document.getElementById("cbcol").style.display = "none";
        document.getElementById("lcbcol").style.display = "none";
        document.getElementById("ctcol").style.display = "none";
        document.getElementById("lctcol").style.display = "none";
        document.getElementById("newc").value = "false";
    } else {
        document.getElementById("delete").style.display = "none";
        document.getElementById("cdate").style.display = "block";
        document.getElementById("lcdate").style.display = "block";
        document.getElementById("cbcol").style.display = "block";
        document.getElementById("lcbcol").style.display = "block";
        document.getElementById("ctcol").style.display = "block";
        document.getElementById("lctcol").style.display = "block";
        document.getElementById("newc").value = "true";
    } 
}

function is_collection_new(){
    var needle = document.getElementById("cname").value;

    for (const line of responses){
        if (line === ""){
            break
        }

        var data = line.split(":");
        if (data[0] == needle){
            toggle_new_ui(true);
            break
        } else {
            toggle_new_ui(false);
        }
        console.log(data[0]);
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
