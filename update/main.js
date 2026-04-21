/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

function get_order_Index(thing) {
    for (let i = 0; i < order.length; i++) {
        if (thing.trim() === order[i].trim()) {
            return i;
        }
    }
    return null; // Return null if not found
}

function cleanUpArray() {
    for (i = 0; i < order.length - 1; i++) {
        order[i] = order[i].trim()
    }
    return order.filter(element => element.trim() !== ""); // Filter out empty elements
}
function move(is_up, item) {
    let payload = "func=change_collection_priority";
    payload += "&&collection=";
    payload += item;
    payload += "&&up=";
    payload += is_up;

    fetch('/libs/collections.php', {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: payload
    })
        .then(response => {
            if (response.ok) {
                console.log(response)
                window.location.reload();
            }
        })
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
}