<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

echo 'Processing data started...<br>';

$cname = $_POST['cname'];
$cdate = $_POST['cdate'];
$color = $_POST['cbcol'];
$tcolor = $_POST['ctcol'];
$create_new = $_POST['newc'];
if ($create_new == "true"){
    $create_new = true;
} else {
    $create_new = false;
}
$folder = "../photos/" .  str_replace(" ", "-", $cname) . "/";

echo $folder . "<br>";

echo 'Loaded variables...<br>';

function retturn() {
    echo '<br><a href="index.html">Return to Uploading</a>';
}

function handleFileUploads($targetDirectory) {
    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0775, true);
    }

    // Loop through each file in the $_FILES array
    foreach ($_FILES['file']['name'] as $key => $name) {
        $name = str_replace(' ', '_', $name); // Replace spaces with underscores
        $tempFile = $_FILES['file']['tmp_name'][$key];
        $targetFile = $targetDirectory . basename($name);

        // Move the file to the target directory
        if (move_uploaded_file($tempFile, $targetFile)) {
            echo "The file $name has been uploaded successfully.<br>";
        } else {
            echo "Sorry, there was an error uploading $name.<br>";
        }
    }
}

function create_new_collection() {
    global $folder, $color, $tcolor, $cdate, $cname;
    if (is_dir($folder)) {
        echo 'The collection already exists. There must have been a minor error, that will be ignored.<br>';    
    } else {
        mkdir($folder);
        file_put_contents($folder . "/data.txt", $cdate . "\n" . $cname . "\n" . $color . "\n" . $tcolor . "\n");
        echo 'Created folder...<br>';

    }
}

if ($create_new){
    create_new_collection();
}
    
handleFileUploads($folder);
echo "<a href='../index.html'>Return to home</a><br>";
?>
