/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/


async function get_collections() {
    var collection_container = document.getElementById('collection_container');
    data = {
        'func': 'display_collections'
    }
    try {
        fetch('libs/collections.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data).toString()
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                collection_container.innerHTML = data;
            });
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

get_collections();