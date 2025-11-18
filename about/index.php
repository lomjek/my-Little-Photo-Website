<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$c_file = "../colors.txt";
if (file_exists($c_file)) {
    $data = file_get_contents($c_file);
    $color = explode(PHP_EOL, $data);
} else {
    file_put_contents($c_file, "#333" . PHP_EOL . "#aaa");
    $color = ["#333333", "#aaaaaa"];
}
$speech = "If you see this text, something went very the wrong way.";
$a_file = "thing.txt";
if (file_exists($a_file)) {
    $speech = file_get_contents($a_file);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>About Us</title>
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
    <body>
        <h1 id="Title">About Us</h1>
        <h2><?php echo $speech;?><h2>
        <hr>
        <a href="../">Back to Home</a>
    </body>
</html>
