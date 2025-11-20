<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

if (isset($_GET['c'])) {
    $current = htmlspecialchars($_GET['c']);
} else {
    http_response_code(422);
    exit;
}
if (!file_exists($current)){
    http_response_code(404);
    exit;
}

$colors = explode(PHP_EOL, file_get_contents($current . '/.c_'));
$name = str_replace("-", " ", $current);
$descf = $current . '/.d_';
$desc = "";
if (file_exists($descf)){
    $desc = file_get_contents($descf);
}

$files = scandir($current);
foreach ($files as $file) {
    if (str_starts_with($file, '.')){
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
    </head>
    <body style="background-color: <?php echo $colors[0]; ?>;">
        <h1 style="color: <?php echo $colors[1];?>;"><?php echo $name; ?></h1>
        <h3 style="color: <?php echo $colors[1];?>;" onclick="window.location.assign('/')"><= Nazad na glavnu stranu</h3>
        <?php if ($desc) { echo '<h2 style="color: ' . $colors[1] . '; text-align: center;">' . $desc . '</h2>'; } ?>
        <hr style="background-color: <?php echo $colors[1];?>;">
        <div>
        <?php
            $index = 0;
            foreach ($files as $image){
                echo "<a href=/photo/" . $current . "/" . $image . ">";
                if ($index % 2 == 0) {
                    echo "<img src='/photos/" . $current . "/.t_" . $image . "' class='thumbnail_left'>";
                } else {
                    echo "<img src='/photos/" . $current . "/.t_" . $image . "' class='thumbnail_right'>";
                }
                $index += 1;
                echo "</a>";
            }    
        ?>
        </div>
        <hr style="background-color: <?php echo $colors[1];?>;">
        <h3 style="color: <?php echo $colors[1];?>;" onclick="window.location.assign('/')"><= Nazad na glavnu stranu</h3>
    </body>
</html>
