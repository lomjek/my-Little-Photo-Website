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
        <title>Ažuriranje LLS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="/data/style.css">
        <script>
            let order = "<?php echo file_get_contents('../photos/order.csv'); ?>".split(", ");
            console.log(order);

            function get_order_Index(thing) {
                for (let i = 0; i < order.length; i++) {
                    if (thing.trim() === order[i].trim()) {
                        return i;
                    }
                }
                return null; // Return null if not found
            }

            function cleanUpArray() {
                for (i = 0; i < order.length - 1; i++){
                    order[i] = order[i].trim()
                }
                return order.filter(element => element.trim() !== ""); // Filter out empty elements
            }
            function move(is_up, item) {
                const index = order.indexOf(item);
                if (index === -1) {
                    console.log('Item ' + item + ' not found in the order.');
                    return;
                }
                let newIndex = is_up ? index - 1 : index + 1;
                if (newIndex < 0 || newIndex >= order.length) {
                    console.log('Cannot move item in that direction.');
                    return;
                }
                [order[index], order[newIndex]] = [order[newIndex], order[index]];
                sort_colletcions(order);
                save_changes();
            }
            function save_changes(){
                const xhr = new XMLHttpRequest();
                var data = new FormData();
                data.append('order', order);
                data.append('a', 'save_order');
                xhr.open('POST', 'collection/main.php', true);
                xhr.onload = function () {
                    console.log(xhr.responseText);
                }
                xhr.send(data);
            }
            function sort_colletcions(ORDER){
                //Takes an array which contains the title of all the names of the collections and then it sorts them like in the array.
                //!WARNING! If there is a Collection, that is not in the array it will be moved to the top!
                for (i = 0; i < ORDER.length; i++) {
                    var element = ORDER[i]
                    if (element !== "" && element.trim() !== "") {
                        element = element.trim().replaceAll(" ", "-");
                        var object = document.getElementById(element);
                        object.remove()
                        document.getElementById("Images").appendChild(object);
                    }
                }
            }
        </script>

    </head>
    <body>
        <h3 onclick="window.location.assign('/')"><= Glavna strana</h3>
        <h1 id="Title">Ažuriranje LLS</h1>
        <h3>Slike se izbriše tako da iz linka slike /photos/{skup}/{slika}.webp => /update/delete/{skup}/{slika}.webp</h3>
        <h3 id='open_update' onclick="window.location.assign('/update/collection/new')">Napravite novi Skup =></h3>
        <h3 id='open_update' onclick="window.location.assign('/update/collection/delete')">Izbrišite skup =></h3>
        <h3 id='open_update' onclick="window.location.assign('/update/images')">Dodajte Slike =></h3>
        <hr>
        <div id="Images">
            <?php display_collections($order, true); ?>
        </div>
    </body>
</html>
