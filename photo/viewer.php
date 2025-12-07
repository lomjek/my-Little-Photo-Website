<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/images.php';

$current_collection = $_GET['s'];
$file = $_GET['f'];

if (!isset($_GET['s']) || !isset($_GET['f'])) {
    http_response_code(422);
    exit;
}

$current_collection = urldecode($current_collection);
$file = urldecode($file);

$path = '/photos/' . $current_collection . "/" . $file;
$current_description = get_image_description($current_collection, $file);
http_response_code(200);
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo pathinfo($file)['filename']; ?></title>

    <script src="/photo/viewer.js"></script>
    <link rel='stylesheet' href='/photo/viewer.css' type='text/css' />
</head>

<body>
    <div id="toolbox">
        <img id='back' class='tool'
            src='/photo/ArrowLeft.svg' alt="BACK"
            onclick="back_to_collection('<?php echo $current_collection; ?>')" />
        <img id='desc' class='tool'
            src='/photo/Description.svg' alt="DESCRIPTION"
            onclick="toggle_description_visibility()" />
    </div>

    <div class='container'>
        <img draggable='false' id='img' src='<?php echo $path; ?>' alt='Something strange...'>
    </div>

    <p id="description"><?php echo $current_description; ?></p>
</body>

</html>