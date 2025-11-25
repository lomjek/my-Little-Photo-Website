/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

async function get_IOD() { //photo
    var iod_link = document.getElementById('iod_link');
    var iod_img = document.getElementById('iod_img');

    try {
        const response = await fetch('libs/iod.php', {
            method: 'POST'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const urlEncodedString = await response.text();
        const params = new URLSearchParams(urlEncodedString);
        const receivedString = params.get('data');

        var iod_array = receivedString.split('/');
        iod_array[0] = iod_array[0].slice(0, -1)
        iod_array[2] = ".t_" + iod_array[2];
        let iod_url = iod_array.join("/");

        iod_link.onclick = function () {
            window.location.assign(iod_url);
        };
        iod_img.onclick = function () {
            window.location.assign(iod_url);
        };

        iod_img.src = receivedString;

    } catch (error) {
        console.error('Fetch error:', error);
    }
}

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

get_IOD();
get_collections();