<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

echo 'Processing data started...<br>';

$cname = html_entity_decode($_POST['cname']);
$cdate = $_POST['cdate'];
$color = $_POST['cbcol'];
$tcolor = $_POST['ctcol'];
$create_new = $_POST['newc'];

if ($create_new == "true"){
    $create_new = true;
} else {
    $create_new = false;
}
$folder = "/var/www/lovro-leon-photos/photos/" .  str_replace(" ", "-", $cname) . "/";
$tolder = "/var/www/lovro-leon-photos/thumbnails/" .  str_replace(" ", "-", $cname) . "/";

echo $folder . "<br>";

echo 'Loaded variables...<br>';

function retturn() {
    echo '<br><a href="index.html">Return to Uploading</a>';
}

function handleFileUploads($targetDirectory) {
    global $cname, $folder, $tolder;
    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0775, true);
    }

    // Loop through each file in the $_FILES array
    foreach ($_FILES['file']['name'] as $key => $name) {
        $name = str_replace(' ', '_', $name); // Replace spaces with underscores
        $name = preg_replace('/[,><:;\/?!@#$%^&*()_+={\$\$\\\\}]/', '', $name);
        $tempFile = $_FILES['file']['tmp_name'][$key];
        $targetFile = $targetDirectory . basename($name);

        // Move the file to the target directory
        if (move_uploaded_file($tempFile, $targetFile)) {
            echo "The file $name has been uploaded successfully.<br>";
            $output = "";
	    $command = "/usr/bin/python3 /var/www/lovro-leon-photos/tumbs.py " . $folder . "/" . $name . " " . $tolder . "/" . pathinfo($name, PATHINFO_FILENAME) . ".webp";
            $helu = system($command, $output);
	    echo $command;
            echo $output;
        } else {
            echo "Sorry, there was an error uploading $name.<br>";
        }
    }
}

function create_new_collection() {
    global $folder, $color, $tcolor, $cdate, $cname, $tolder;
    if (is_dir($folder)) {
        echo 'The collection already exists. There must have been a minor error, that will be ignored.<br>';    
    } else {
        system("mkdir " . $folder);
	system("mkdir " . $tolder);
	system("chmod 775 -R /var/www/lovro-leon-photos/photos/" . $cname);
	system("chmod 775 -R /var/www/lovro-leon-photos/thumbnails/" . $cname);
	system("chgrp serverers -R /var/www/lovro-leon-photos/thumbnails/" . $cname);
	system("chgrp serverers -R /var/www/lovro-leon-photos/photos/" . $cname);
        file_put_contents($folder . "/data.txt", $cdate . "\n" . $cname . "\n" . $color . "\n" . $tcolor . "\n");
        echo 'Created folders...<br>';
    }
}

if ($create_new){
    create_new_collection();
}

handleFileUploads($folder);
echo "<a href='../index.html'>Return to home</a><br>";
?>
