<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$c_file = $_SERVER['DOCUMENT_ROOT'] . "/colors.txt";
if (file_exists($c_file)) {
    $data = file_get_contents($c_file);
    $color = explode(PHP_EOL, $data);
} else {
    file_put_contents($c_file, "#333" . PHP_EOL . "#aaa");
    $color = ["#333333", "#aaaaaa"];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Update LLP</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <style>
            @font-face {
                font-family: Nirmala;
                src: url(../Nirmala.ttc);
            }
            #Title {
                text-align: center;
                color: <?php echo $color[0]; ?>;
                font-size: 100px;
                font-family: Nirmala;
            }
            #middle {
                border-left: 5px solid <?php echo $color[0]; ?>;
                border-right: 5px solid <?php echo $color[0]; ?>;
            }

            .inside {
                text-align: left;
            }
            .open {
                width: 100%;
                max-height: 100px;
            }
            .arrows {
                width: 25%;
                user-select: none;
            }
            .Carrows {
                font-weight: bold;
            }

            table {
                width: 100%;
                padding-left: 20px;
                padding-right: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
            }
            td {
                width: -webkit-fit-content; 
                width: -moz-fit-content; 
                width: fit-content;
                min-width: 25%;
            }

            h2 {
                font-size: 40px;
                color: <?php echo $color[0]; ?>;
                text-align: center;
                font-family: Nirmala;
            }
            h3 {
                font-size: 25px;
                color: <?php echo $color[0]; ?>;
                text-align: center !important;
                font-family: Nirmala;
            }
            body {
                background-color: <?php echo $color[1]; ?>;
            }
            hr {
                height: 5px;
                background-color: <?php echo $color[0]; ?>;
            }
        </style>
        <script>
            let order = []; // Initialize your public array

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

            function move(upordown, collection) {
                const INDEX = get_order_Index(collection);

                if (upordown) {
                    if (INDEX === 0) { // Check if the element exists and is not the last item
                        alert("Element not found or is already at the start.");
                        return order; // Return the original array if the element is not found or is at the end
                    }
                    [order[INDEX], order[INDEX - 1]] = [order[INDEX - 1], order[INDEX]];
                } else {
                    if (INDEX === -1 || INDEX >= order.length - 1) { // Check if the element exists and is not the last item
                        alert("Element not found or is already at the end.");
                    }
                    [order[INDEX], order[INDEX + 1]] = [order[INDEX + 1], order[INDEX]];  
                }
                order = cleanUpArray(order); // Clean up the array after moving
                sort_colletcions(order); // Assuming this function sorts the collections
                save_changes();
            }

            function redirect_to_collection(collection) {
                window.location.assign("collection/" + collection);
            }
            function redirect(uri){
                window.location.assign(uri);
            }

            function save_changes(){
                const xhr = new XMLHttpRequest();
                var data = new FormData();
                data.append('order', order);
                xhr.open('POST', 'save_order.php', true);
                xhr.onload = function () {
                    console.log(xhr.responseText);
                }
                xhr.send(data);
            }

            async function loadfolders() {
                const XHR = new XMLHttpRequest();

                XHR.onreadystatechange = function() {
                    if (XHR.readyState === XMLHttpRequest.DONE) {
                        if (XHR.status === 200) {
                            const responseText = XHR.responseText;
                            var lines = responseText.split("\n");
                            lines.forEach(line => {
                                var order = line.split(":");
                                if (order[1] != undefined){
                                    display(order[0], order[1], order[3], order[4]);
                                };
                            });
                            sort_colletcions(order);
                        } else {
                            console.error('Request failed with status:', XHR.status);
                        }
                    }
                };

                XHR.open('POST', '../loading.php', true);
                XHR.send();
            }

            function load_main_colors(c1, c2){
                document.body.style.backgroundColor = c2
            }

            function display(folder, jpgcnt, bcolor, tcolor){
                // Create a table element
                var table = document.createElement('table');

                order.push(folder.split("-").join(" "));

                // Create table cells (td) and their contents
                var title_cell = document.createElement('td');
                var heading1 = document.createElement('h2');
                heading1.textContent = folder.split("-").join(" ");
                heading1.classList.add('inside');
                heading1.style.setProperty('color', tcolor, 'important');
                title_cell.appendChild(heading1);

                var cell_up = document.createElement('td');
                var heading2 = document.createElement('h3');
                heading2.textContent = "↑";
                heading2.classList.add('inside');
                heading2.style.setProperty('color', tcolor, 'important');
                heading2.className = "Carrows";
                cell_up.className = "arrows";
                cell_up.appendChild(heading2);

                var cell_down = document.createElement('td');
                var heading3 = document.createElement('h3');
                heading3.textContent = "↓";
                heading3.classList.add('inside');
                heading3.style.setProperty('color', tcolor, 'important');
                heading3.className = "Carrows";
                cell_down.className = "arrows";
                cell_down.appendChild(heading3);

                title_cell.style.color = tcolor;
                cell_up.style.color = tcolor;
                cell_down.style.color = tcolor;

                // Append cells to the row
                table.appendChild(title_cell);
                table.appendChild(cell_up);
                table.appendChild(cell_down);

                table.style.setProperty('background-color', bcolor, 'important');
                
                title_cell.onclick = () => redirect_to_collection(folder);
                cell_up.onclick = () => move(true, folder.replaceAll("-", " "));
                cell_down.onclick = () => move(false, folder.replaceAll("-", " "));
                
                table.id = folder;
                table.style.marginBottom = "10px";
                table.style.marginTop = "10px";

                document.getElementById("Images").appendChild(table);

                document.getElementById("LoadingIndicator").style.display = "none";
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

            loadfolders();
        </script>

    </head>
    <body>
        <h1 id="Title">Update Lovro-Leon-Photos</h1>
        
        <div> <!--This is the menu div.-->
            <hr>
            <table style="padding: 0 !important;">
                <tr>
                    <td onclick="window.location.assign('../')"><h2>Home</h2></td>
                    <td onclick="window.location.assign('images/')" id="middle"><h2>Add Images</h2></td>
                    <td onclick="window.location.assign('about')"><h2>Edit About Page</h2></td>
                </tr>
            </table>
            <hr>
        </div>
        <br><br>

        <table onclick="redirect('collection/')">
            <tr>
                <td><h2>Add a new collection</h2></td>
            </tr>
        </table>

        <div id="Images">
            <h2 id="LoadingIndicator" style="text-align: center;">Loading Elements to Edit...</h2>
        </div>

        <table onclick="redirect('delete/')">
            <tr>
                <td><h2>Delete Images</h2></td>
            </tr>
        </table>
    </body>
</html>
