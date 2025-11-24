<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';

if (isset($_GET['c'])) {
    $current = htmlspecialchars($_GET['c']);
} else {
    http_response_code(422);
    exit;
}
if (!file_exists($current)) {
    http_response_code(404);
    exit;
}

$colors = get_collection_colors($current);
$name = str_replace("-", " ", $current);
$desc = get_collection_description($current);

$files = scandir($current);
foreach ($files as $file) {
    if (str_starts_with($file, '.')) {
        $files = array_diff($files, [$file]);
    }
}
$files = array_values($files);
?>

<html>

<head>
    <title><?php echo $name ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">

    <link rel="stylesheet" href="/data/style.css">
    <link rel="stylesheet" href="/data/photos.css">
</head>

<body style="background-color: <?php echo $colors[0]; ?>;">
    <h1 id="Title" style="color: <?php echo $colors[1]; ?>;"><?php echo $name; ?></h1>
    <h3 class="Home" style="color: <?php echo $colors[1]; ?>;" onclick="window.location.assign('/')"><- Na glavnu stranicu</h3>
            <?php if ($desc) {
                echo '<h2 id="desc" style="color: ' . $colors[1] . ';">' . $desc . '</h2>';
            } ?>
            <hr style="background-color: <?php echo $colors[1]; ?>;">

            <div id="image_container">
                <?php
                foreach ($files as $image) {
                    echo "<a href=/photo/" . $current . "/" . $image . ">";
                    echo "<img class='imgs' src='/photos/" . $current . "/.t_" . $image . "'>";
                    echo "</a>";
                }
                ?>
            </div>

            <hr style="background-color: <?php echo $colors[1]; ?>;">
            <h3 class="Home" style="color: <?php echo $colors[1]; ?>;" onclick="window.location.assign('/')"><- Na glavnu stranicu</h3>
</body>

</html>