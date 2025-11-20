<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
$order = get_collections();
?>
<!DOCTYPE html>
<html lang="hr">
	<head>
        <title>Izrbišite Skup</title>
        <link rel="stylesheet" href="/update/collection/main.css">
        <script>  
            function delete_collection(){
                const confirmation = confirm("Are you sure you want to proceed?\nThe collection will be deleted and all the images in it as well...");
                if (confirmation) {
                    const formData = new URLSearchParams();
                    formData.append('a', 'delete');
                    formData.append('c', document.getElementById("skupovi").value);
                    console.log(document.getElementById("skupovi").value);
                    fetch('main.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            alert("There was an error. There shouldn't be any interferences.\nError code: " + response.status);
                        }
                        window.location = '/update/'
                    })
                }
            }
        </script>
    </head>
    <body style="background-color: #aaaaaa;">
        <h3 id="leave" onclick="window.location = '/update/'"><= Ažuriranje LLS</h3>
        <h1 id = 'Title' style="color: #333333;">Izrbišite Skup</h1>
        
        <div style="margin-bottom: 20px;"></div>

        <select id="skupovi" style="width: 100%; font-size: clamp(0.5rem, 7vw, 4rem);">
			<?php 
			foreach ($order as $collection){
				echo "<option value='" . $collection . "'>" . $collection . "</option>";
			}
			?>
        </select>

        <div style="margin-bottom: 20px;"></div>

        <button id='add' style="background-color: #aaaaaa; color: #333333;" onclick="delete_collection()">Izbriši Skup</button>
    </body>
</html>
