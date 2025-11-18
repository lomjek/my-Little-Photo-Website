<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

//Loading the main color scheme.
$c_file = "colors.txt";
if (file_exists($c_file)) {
    $data = file_get_contents($c_file);
    $color = explode(PHP_EOL, $data);
} else {
    file_put_contents($c_file, "#333" . PHP_EOL . "#aaa");
    $color = ["#333333", "#aaaaaa"];
}

print_r($_POST);
$upgrade = $_POST['rename'];
$name = str_replace(' ', '-', $_POST['cname']);
$old_name = str_replace(' ', '-', $_POST['oldname']);
$date = $_POST['cdate'];
$background = $_POST['bcol'];
$text = $_POST['tcol'];

$root = $_SERVER['DOCUMENT_ROOT'];

$success = false;

function search_for($array, $needle){
    for ($i = 0; $i < count($array); $i++) {
        if (trim($needle) == trim($array[$i])){
            return $i;
        }
    }
};

if ($upgrade) {
    if (rename($root . "/photos/" . $old_name, $root . "/photos/" . $name)) {
        $success = true;
    } else {
        $success = false;
    }
    if (rename($root . "/thumbnails/" . $old_name, $root . "/thumbnails/" . $name)) {
        $success = true;
    } else {
        $success = false;
    }
    //update the order...
    $filename = $root . "/photos/order.csv";
    $order = explode(",", file_get_contents($filename));
    $index = search_for($order, $old_name);
    $order[$index] = $name;
    file_put_contents($filename, implode(", ", $order));
} else {
    if (mkdir($root . "/photos/" . $name, 775)){
        $success = true;
    } else {
        $success = false;
    }
    echo "Step 1 done";
    if (mkdir($root . "/thumbnails/" . $name, 775)){
        $success = true;
    } else {
        $success = false;
    }
    system("chgrp serverers " . $root . "/photos/" . $name);
    system("chgrp serverers " . $root . "/thumbnails/" . $name);
    system("chmod 775 " . $root . "/photos/" . $name);
    system("chmod 775 " . $root . "/thumbnails/" . $name);
    $filename = $root . "/photos/order.csv";
    $order = explode(",", file_get_contents($filename));
    array_unshift($order, $name);
    file_put_contents($filename, implode(", ", $order));
}
if (file_put_contents($root . "/photos/" . $name . "/data.txt", $cdate . PHP_EOL . $background . PHP_EOL . $text)){
    $success = true;
} else {
    $success = false;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Updating <?php echo $name; ?></title>
        <style>
            @font-face {
                font-family: Nirmala;
                src: url(../Nirmala.ttc);
            }
            h1{
                font-family: Nirmala;
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
                text-align: center;
            }
            h2 {
                font-size: 40px;
                color: <?php echo $color[0]; ?>;
                text-align: center;
                font-family: Nirmala;
            }
            body {
                background-color: <?php echo $color[1]; ?>;
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
    <body>
        <?php
        if ($success){
            echo "<h1 style='color: #080; font-size: 75px; text-align: center;'>Success!</h1>";
        } else {
            echo "<h1 style='color: #800; font-size: 75px; text-align: center;'>Something went wrong...!</h1>";
        }
        
        ?>
        <hr>
        <table style="padding: 0 !important;">
            <tr>
                <td onclick="window.location.assign('../')" class="menu"><h2>Home</h2></td>
                <td onclick="window.location.assign('./')" class="menu" id="middle"><h2>Main Update</h2></td>
                <td onclick="window.location.assign('./images/')" class="menu"><h2>Add Images</h2></td>
            </tr>
        </table>
        <hr>
        <h2>You can ignore Warnings above, as long as success is written below. Else, copy the whole log from aboce and send it to me.</h2>
    </body>
</html>
