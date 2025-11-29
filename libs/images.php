<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/libs/iod.php';

function delete_image($collection, $image)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/' . $image;
    $thumbnail_path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $collection . '/.t_' . $image;
    if (file_exists($path)) {
        unlink($path);
    }
    if (file_exists($thumbnail_path)) {
        unlink($thumbnail_path);
    }
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
        return;
    }
    if (file_exists($thumbnail_path)) {
        rename($thumbnail_path, $new_thumbnail_path);
    } else {
        //Generate thumbnail if it does not exist
        return;
    }
    if (file_exists($description_path)) {
        rename($description_path, $new_description_path);
    }

    $iod_image = $_SERVER['DOCUMENT_ROOT'] . '/' . get_iod();
    if ($iod_image == $image_path) {
        override_iod($new_image_path);
    }
}

// FUNCTION CALLS FOR ACCESS VIA AJAX/POST
if (!(realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) || !($_SERVER['REQUEST_METHOD'] === 'POST')) {
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
    if ($collection != null && $image != null && $description != null) {
        set_image_description($collection, $image, $description);
        http_response_code(200);
    } else {
        http_response_code(400);
    }
} else {
    http_response_code(422);
}
