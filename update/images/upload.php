<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

session_start();

// Handle file upload logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['files']) || !is_array($_FILES['files']['name']) || count($_FILES['files']['name']) === 0) {
        echo false;
        exit;
    }

    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/photos/" . str_replace(" ", "-", $_POST['collection']) . "/";
    if (!file_exists($targetDir)) {
        echo false;
    }

    foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
        $filename = basename($_FILES['files']['name'][$index]);
        $targetFilePath = $targetDir . str_replace(" ", "_", $filename);

        if ($_FILES['files']['error'][$index] !== UPLOAD_ERR_OK) {
            continue;
        }

        if (move_uploaded_file($tmpName, $targetFilePath)) {
            exec("python3 " . $_SERVER['DOCUMENT_ROOT'] .  "/thumbnails/generate.py " . $targetFilePath . " " . $_SERVER['DOCUMENT_ROOT'] . "/thumbnails/" . str_replace(" ", "-", $_POST['collection']) . "/" . pathinfo($filename, PATHINFO_FILENAME) . ".webp");
            echo true;
        } else {
            echo false;
        }
    }
    exit;
} else {
    http_response_code(405);
    exit;
}
?>
