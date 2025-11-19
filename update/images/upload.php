<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

// Handle file upload logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['files']) || !is_array($_FILES['files']['name']) || count($_FILES['files']['name']) === 0) {
        echo 1;
    }

    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/photos/" . str_replace(" ", "-", html_entity_decode($_POST['collection'])) . "/";
    if (!file_exists($targetDir)) {
        echo 2;
    }

    foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
        $tmpName = htmlspecialchars($tmpName);
        $filename = basename($_FILES['files']['name'][$index]);
        $targetFilePath = $targetDir . html_entity_decode(str_replace(" ", "_", $filename));

        if ($_FILES['files']['error'][$index] !== UPLOAD_ERR_OK) {
            echo 3;
//	    echo $_FILES['files']['error'][$index];
	    exit;
        }

        if (move_uploaded_file($tmpName, $targetFilePath)) {
            exec("python3 " . $_SERVER['DOCUMENT_ROOT'] . "/thumbnails/generate.py " . $targetFilePath . " " . $_SERVER['DOCUMENT_ROOT'] . "/thumbnails/" . str_replace(" ", "-", $_POST['collection']) . "/" . pathinfo($filename, PATHINFO_FILENAME) . ".webp");
            echo 0;
        } else {
            echo 4;
        }
    }
    exit;
} else {
    http_response_code(405);
    exit;
}
?>
