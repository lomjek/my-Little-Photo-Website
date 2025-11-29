<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';

function get_rand_img_path()
{
    //Set the seed
    $currentDate = date("Ymd");
    mt_srand($currentDate);
    //Set the directory
    $directories = get_collections();
    $randomNumber = mt_rand(1, count($directories));
    $directory = $directories[$randomNumber - 1];
    //Set the image
    $files = scandir($_SERVER['DOCUMENT_ROOT'] . '/photos/' . $directory);
    foreach ($files as $file) {
        if (str_starts_with($file, '.')) {
            $files = array_diff($files, [$file]);
        }
    }
    $files = array_values($files);
    $randomFileIndex = mt_rand(1, count($files));
    $file = $files[$randomFileIndex - 1];

    $file = 'photos/' . $directory . '/' . $file;

    return $file;
}


function needs_reload()
{
    $reload_file = $_SERVER['DOCUMENT_ROOT'] . '/data/iod.conf';
    if (!file_exists($reload_file)) {
        file_put_contents($reload_file, "\n");
        return true;
    }
    $file_contents = file_get_contents($reload_file);
    $line = explode("\n", $file_contents)[0];
    if (trim($line) == date("Ymd")) {
        if (file_exists(get_iod_from_file())) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

function override_iod($path = "")
{
    $reload_file = $_SERVER['DOCUMENT_ROOT'] . '/data/iod.conf';
    if ($path != "") {
        $rand_img_path = $path;
    } else {
        $rand_img_path = get_rand_img_path();
    }
    file_put_contents($reload_file, date("Ymd") . PHP_EOL . $rand_img_path);
}

function get_iod_from_file()
{
    $reload_file = $_SERVER['DOCUMENT_ROOT'] . '/data/iod.conf';
    $file_contents = file_get_contents($reload_file);
    $line = explode("\n", $file_contents)[1];
    return trim($line);
}

function get_iod()
{
    if (needs_reload()) {
        override_iod();
        return get_iod_from_file();
    } else {
        return get_iod_from_file();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    $message_string = get_iod();
    $data_array = [
        'data' => $message_string
    ];
    $urlencoded_data = http_build_query($data_array);
    header('Content-Type: application/x-www-form-urlencoded');
    echo $urlencoded_data;
    exit();
} else {
    http_response_code(405);
}
