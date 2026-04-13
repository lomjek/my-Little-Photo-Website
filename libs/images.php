<?php

/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';

#region Image Functions
function delete_image(string $collection, string $image): bool
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/' . $image;
    $thumbnail_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.t_' . $image;
    if (!collection_exists($collection)) {
        echo "CnF: " . $collection;
        return false;
    }
    if (file_exists($path)) {
        unlink($path);
    }
    if (file_exists($thumbnail_path)) {
        unlink($thumbnail_path);
    }
    return true;
}
function image_exists($collection, $image)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/' . $image;
    return file_exists($path);
}

function get_image_description($collection, $image)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.d_' . $image . '.md';
    if (file_exists($path)) {
        return file_get_contents($path);
    } else {
        return "";
    }
}
function set_image_description($collection, $image, $description)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.d_' . $image . '.md';
    if (!file_exists($path)) {
        touch($path);
    }
    file_put_contents($path, $description);
}
function rename_image($collection, $image, $new_image_name)
{
    $image_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/' . $image;
    $thumbnail_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.t_' . $image;
    $description_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.d_' . $image . '.md';

    $new_image_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/' . $new_image_name;
    $new_thumbnail_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.t_' . $new_image_name;
    $new_description_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.d_' . $new_image_name . '.md';

    if (file_exists($image_path)) {
        rename($image_path, $new_image_path);
    } else {
        return false;
    }
    if (file_exists($thumbnail_path)) {
        rename($thumbnail_path, $new_thumbnail_path);
    } else {
        generate_thumbnail($collection, $new_image_name);
    }
    if (file_exists($description_path)) {
        rename($description_path, $new_description_path);
    }

    $iod_image = shell_exec($_SERVER['DOCUMENT_ROOT'] . '/libs/iod.dc');
    if ($iod_image == $image_path) {
        exec($_SERVER['DOCUMENT_ROOT'] . '/libs/iod.dc override ' . $image_path);
    }
    return true;
}

#endregion
#region Image Fetchers
function get_images_in_collection($collection)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection;
    $files = scandir($path);
    $images = [];
    foreach ($files as $file) {
        if (str_ends_with($file, '.webp') || str_ends_with($file, '.avif')) {
            if (str_starts_with($file, '.t_') || str_starts_with($file, '.d_')) {
                continue;
            }
            $images[] = $file;
        }
    }
    return $images;
}
function get_images_in_collection_for_update($collection)
{
    $images = get_images_in_collection($collection);
    $result = [];
    foreach ($images as $image) {
        $result[] = [
            'name' => $image,
            'description' => get_image_description($collection, $image)
        ];
    }
    return $result;
}
#endregion
#region Image Processing
function process_images($collection, $paths)
{
    echo "process_images" . PHP_EOL;
    if (!collection_exists($collection)) {
        echo "Cannot write into inexistent collection..." . PHP_EOL;
        return false;
    }

    foreach ($paths as $path) {
        if (!file_exists($path)) {
            echo "File " . $path . " that was supposed to exist on the server is nowhere to be found..." . PHP_EOL;
            continue;
        }
        $f_info = pathinfo($path);
        $root = $_SERVER['DOCUMENT_ROOT'];
        $command = $root . "/libs/process_image.dc " . $path . " " . $collection . " " . $f_info['filename'];
        exec($command);
    }
    rmd($_SERVER['DOCUMENT_ROOT'] . "/update/images/uploads/");
}
#region API
if (realpath(__FILE__) != realpath($_SERVER['SCRIPT_FILENAME'])) {
    return;
}

if ($_POST['func'] == 'get_images_in_collection') {
    $collection = $_POST['collection'];
    echo json_encode(get_images_in_collection($collection));
    if ($collection == null) {
        http_response_code(400);
    } else {
        http_response_code(200);
    }
} elseif ($_POST['func'] == 'get_images_in_collection_for_update') {
    $collection = $_POST['collection'];
    echo json_encode(get_images_in_collection_for_update($collection));
    if ($collection == null) {
        http_response_code(400);
    } else {
        http_response_code(200);
    }
} elseif ($_POST['func'] == 'rename_image') {
    $collection = $_POST['collection'];
    $image = $_POST['old_name'];
    $new_image_name = $_POST['new_name'];
    if ($collection != null && $image != null && $new_image_name != null) {
        rename_image($collection, $image, $new_image_name);
        http_response_code(200);
    } else {
        http_response_code(400);
    }
} elseif ($_POST['func'] == 'set_image_description') {
    $collection = $_POST['collection'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    if ($collection != null && $image != null) {
        set_image_description($collection, $image, $description);
        http_response_code(200);
    } else {
        http_response_code(400);
    }
} elseif ($_POST['func'] == 'delete_image') {
    $collection = $_POST['collection'];
    $image = $_POST['image'];
    if ($collection != null && $image != null) {
        delete_image($collection, $image);
        http_response_code(200);
    } else {
        http_response_code(400);
    }
} elseif ($_POST['func'] == "generate_thumbnail") {
    $collection = $_POST['collection'];
    $image = $_POST['image'];
    generate_thumbnail($collection, $image);
} elseif ($_POST['func'] == 'process_images') {
    $collection = str_replace(" ", "-", $_POST['collection']);
    $paths = explode(",", $_POST['paths']);
    if ($collection != null && $paths != null) {
        process_images($collection, $paths);
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    exit;
} else {
    http_response_code(422);
    echo 'Bad Request: Unknown function: ' . htmlspecialchars($_POST['func']);
    exit;
}
#endregion
