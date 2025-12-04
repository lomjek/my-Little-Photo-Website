<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/server.php";

$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/update/images/uploads/';
$results = [];

function verify_upload_dir()
{
    global $upload_dir;
    rmd($upload_dir);
    mkd($upload_dir);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_upload_dir();
    if (!isset($_FILES['files']['name'])) {
        http_response_code(400);
        echo "We did not get any files uploaded.";
        exit;
    }

    $file_array = $_FILES['files'];
    $file_count = count($file_array['name']);

    for ($i = 0; $i < $file_count; $i++) {

        $message = 'Unknown error.';
        $file_name = ['name'][$i];

        if ($file_array['error'][$i] != UPLOAD_ERR_OK) {
            $message = 'Upload error code: ' . $file_array['error'][$i];
            continue;
        }

        $file_tmp_path = $file_array['tmp_name'][$i];
        $target_path = $upload_dir . $file_array['full_path'][$i];

        if (move_uploaded_file($file_tmp_path, $target_path)) {
            $message = 'success';
        } else {
            $message = 'Failed to move file.';
        }

        $results[] = [
            'path' => $target_path,
            'message' => $message
        ];
    }

    http_response_code(200);

    echo json_encode(['status' => 'complete', 'results' => $results]);
} else {
    http_response_code(405);
    header('Allow: POST');
    echo 'Only POST requests are allowed.';
}
