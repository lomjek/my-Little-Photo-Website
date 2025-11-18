<?php
$c_file = "colors.txt";
if (file_exists($c_file)) {
    $data = file_get_contents($c_file);
    $color = explode(PHP_EOL, $data);
} else {
    file_put_contents($c_file, "#333" . PHP_EOL . "#aaa");
    $color = ["#333333", "#aaaaaa"];
}

$o_missing = false;
$o_file = "photos/order.csv";
if (file_exists($o_file) || file_get_contents($o_file) != "") {
    $order = file_get_contents($o_file);
} else {
    $order = "";
    $o_missing = true;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Lovro Leon Photos</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

        <script>
            var order = <?php echo (!$o_missing && json_encode($order)) ? json_encode($order) : "'Hello'"; ?>;

            function redirect_to_collection(collection) {
                window.location.assign("/photos/" + collection);
            }

            async function loadfolders() {
                const xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const responseText = xhr.responseText;
                            var lines = responseText.split("\n");
                            lines.forEach(line => {
                                //console.log(line);
                                var arr = line.split(":");
                                if (arr[1] != undefined){
                                    display(arr[0], arr[1], arr[2], arr[3], arr[4]);
                                };
                            });
                        } else {
                            console.error('Request failed with status:', xhr.status);
                        }
                    }
                };

                xhr.open('POST', 'loading.php', true);
                xhr.send();
            }
            
            function display(folder, jpgcnt, date, bcolor, tcolor){
                // Create a table element
                var table = document.createElement('table');

                // Create table cells (td) and their contents
                var cell1 = document.createElement('td');
                var heading1 = document.createElement('h2');
                heading1.textContent = folder.split("-").join(" ");
                heading1.classList.add('inside');
                heading1.style.setProperty('color', tcolor, 'important');
                cell1.appendChild(heading1);

                var cell2 = document.createElement('td');
                var heading2 = document.createElement('h3');
                heading2.textContent = date;
                heading2.classList.add('inside');
                heading2.style.setProperty('color', tcolor, 'important');
                cell2.appendChild(heading2);

                var cell3 = document.createElement('td');
                var heading3 = document.createElement('h4');
                heading3.textContent = jpgcnt + ' Photos';
                heading3.classList.add('inside');
                heading3.style.setProperty('color', tcolor, 'important');
                cell3.appendChild(heading3);

                // Append cells to the row
                table.appendChild(cell1);
                if (date != "") {
                    table.appendChild(cell2);
                }
                table.appendChild(cell3);

                table.style.setProperty('background-color', bcolor, 'important');
                
                table.onclick = () => redirect_to_collection(folder);
                table.id = folder;
                table.style.marginBottom = "10px";
                table.style.marginTop = "10px";

                //if (jpgcnt != 0){
                    document.getElementById("Images").appendChild(table);
                    document.getElementById("LoadingIndicator").style.display = "none";
                //}
            }

            loadfolders();
        </script>
        
        <style>
            @font-face {
                font-family: Nirmala;
                src: url(Nirmala.ttc);
            }
            #Title {
                text-align: center;
                color: <?php echo $color[0]; ?>;
                font-size: 100px;
                font-family: Nirmala;
            }
            .inside {
                text-align: left;
            }
            .open {
                width: 100%;
                max-height: 100px;
            }

            table {
                width: 100%;
                border-radius: 50px;
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
                font-family: Nirmala;
            }
            body {
                background-color: <?php echo $color[1]; ?>;
            }
            td {
                text-align: center;
            }
            #middle {
                border-left: 5px solid <?php echo $color[0]; ?>;
                border-right: 5px solid <?php echo $color[0]; ?>;
            }
            hr {
                height: 5px;
                background-color: <?php echo $color[0]; ?>;
            }
        </style>

    </head>
    <body id="main">
        <h1 id="Title">Lovro Leon Photos</h1>

        <hr>
        <table style="padding: 0 !important;">
            <tr>
                <td onclick="window.location.assign('about')" class="menu"><h2>About us</h2></td>
                <td onclick="window.location.assign('iod')" class="menu" id="middle"><h2>Image of the day</h2></td>
                <td onclick="window.location.assign('update')" class="menu"><h2>Add Images</h2></td>
            </tr>
        </table>
        <hr>

        <h3 id="maintenance">Currently there is server maintenance. We may shut down several times.</h3>
        <br><br>

        <div id="Images">
            <h2 id="LoadingIndicator" style="text-align: center;">Loading Elements...</h2>
        </div>
        
    </body>
</html>
