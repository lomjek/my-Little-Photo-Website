<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

$root = $_SERVER['DOCUMENT_ROOT'];

echo "<title>Delete Collection from LLP</title>";

function deleteFolder($dir) {
    if (!is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), ['.', '..']);

    foreach ($files as $file) {
        $filePath = "$dir/$file";
        if (is_dir($filePath)) {
            deleteFolder($filePath);
        } else {
            unlink($filePath);
        }
    }
    if (rmdir($dir)){
        return true;
    }
    return false;
}

if (isset($_POST['c']) && is_string($_POST['c'])) {
    $cname = $_POST['c'];
    $cpath = $root . "/photos/" . $cname . "/";
    $tpath = $root . "/thumbnails/" . $cname . "/";

    if (is_dir($cpath)){
        echo $cpath;
        echo deleteFolder($tpath);
        echo deleteFolder($cpath);
    } else {
        http_response_code(406);
        exit;
    }
} else {
    http_response_code(400);
    exit;
}
?>