<?php
/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Arguments are a for action c for collection and d for additional Data
    if ($_POST['a'] == 'save_order'){
        $order = explode(",", $_POST['order']);
        echo file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/photos/order.csv', implode(", ", $order));
        exit;
    } elseif ($_POST['a'] == 'delete') {
        if (isset($_POST['c'])){
            $collection = str_replace(" ", "-", $_SERVER['DOCUMENT_ROOT'] . "/photos/" . $_POST['c']);
            if (file_exists($collection)){
                rmd($collection);
                kill(201);
            } else {
                kill(404);
            }
        } else {
            kill(400);
        }
    } elseif ($_POST['a'] == 'publish') {
        if (isset($_POST['c'])){
            $collection = str_replace(" ", "-", $_SERVER['DOCUMENT_ROOT'] . "/photos/" . $_POST['c']);
            if (file_exists($collection . '/.u_')){
                unlink($collection . '/.u_');
                kill(201);
            } else {
                kill(404, $collection);
            }
        } else {
            kill(400);
        }
    } elseif ($_POST['a'] == 'rename') {
        if (isset($_POST['c']) && isset($_POST['d'])){
            $collection = str_replace(" ", "-", $_SERVER['DOCUMENT_ROOT'] . "/photos/" . $_POST['c']);
            $new = str_replace(" ", "-", $_SERVER['DOCUMENT_ROOT'] . "/photos/" . $_POST['d']);
            if (is_dir($collection)){
                rename($collection, $new);
                kill(201);
            } else {
                kill(404);
            }
        } else {
            kill(400);
        }
    } elseif ($_POST['a'] == 'colors') {
        if (isset($_POST['c']) && isset($_POST['d'])){
            $collection = str_replace(" ", "-", $_SERVER['DOCUMENT_ROOT'] . "/photos/" . $_POST['c']);
            if (is_dir($collection)){
                file_put_contents($collection . '/.c_', str_replace('\n', PHP_EOL, $_POST['d']));
                kill(201);
            } else {
                kill(404);
            }
        } else {
            kill(400);
        }
    } elseif ($_POST['a'] == 'description') {
        if (isset($_POST['c']) && isset($_POST['d'])){
            $collection = str_replace(" ", "-", $_SERVER['DOCUMENT_ROOT'] . "/photos/" . $_POST['c']);

            if (is_dir($collection)){
                file_put_contents($collection . '/.d_', str_replace('\n', PHP_EOL, htmlspecialchars($_POST['d'])));
                kill(201);
            } else {
                kill(404);
            }
        } else {
            kill(400);
        }
    } else {
        kill(406);
    }
}  
elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if ($_GET['a'] === 'edit'){
        $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $_GET['c'];
        if (file_exists($path)){
            $title = str_replace('-', ' ', $_GET['c']);
            $name = $title;
            $colors = explode("\n", file_get_contents($path . '/.c_'));
            $colors[0] = trim($colors[0]);
            $colors[1] = trim($colors[1]);
            if (file_exists($path . '/.d_')){
                $description = file_get_contents($path . '/.d_');
            } else {
                $description = '';
            }
        }
    } elseif ($_GET['a'] === 'new'){
        $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/Novi-Skup';
        if (mkd($path)){
            $title = 'Napravite novi Skup';
            $name = 'Novi Skup';
            file_put_contents($path . '/.u_',  '');
            $colors = ['#aaaaaa', '#333333'];
            $description = '';
        } else {
            kill(410, 'Novi skup nije mogo bit napravljen...');
        }
    } elseif ($_GET['a'] === 'delete'){
        print('delete');
    } else {
        kill(406);
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
    $elements = scandir($_SERVER['DOCUMENT_ROOT'] . '/photos/');
    foreach ($elements as $folder){
        $folder = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $folder;
        if (file_exists($folder . '/.u_')){
            rmd($folder);
        }
    }
    kill(200);
}
else {
    kill(400, 'Not accepted method...');
}
?>
<!DOCTYPE html>
<html lang="hr">
    <head>
        <title><?php echo str_replace('-', ' ', htmlspecialchars($title)); ?></title>
        <link rel="stylesheet" href="/update/collection/main.css">
        <script src="/update/collection/main.js"></script>
    </head>
    <body style="background-color: <?php echo $colors[0]; ?>;">
        <h3 id="leave" onclick="leave()" style="background-color: <?php echo $colors[0]; ?>; color: <?php echo $colors[1]; ?>;"><= Ažuriranje LLS</h3>
        <input id='Title' type="text" placeholder="Ime Skupa" value="<?php echo $name; ?>" onblur="rename()" style="background-color: <?php echo $colors[0]; ?>; color: <?php echo $colors[1]; ?>;">
        
        <div style="margin-bottom: 20px;"></div>
        
        <textarea id="desc" type="text" rows="4" placeholder="Opišite ovdje, što sadrži vaš skup..." onblur="save_description()" style="background-color: <?php echo $colors[0]; ?>; color: <?php echo $colors[1]; ?>;"><?php echo $description; ?></textarea>
        
        <div style="margin-bottom: 20px;"></div>

        <input id="bcolor" type="color" onblur="save_colors()" onchange="change_bg_color()" value="<?php echo $colors[0]; ?>">
        <input id="tcolor" type="color" onblur="save_colors()" onchange="change_t_col()" value="<?php echo $colors[1]; ?>">

        <div style="margin-bottom: 20px;"></div>

        <button id='add' style="background-color: <?php echo $colors[0]; ?>; color: <?php echo $colors[1]; ?>;" onclick="<?php if ($_GET['a'] === 'new'){ echo 'publish()'; } else { echo 'ask_delete()'; }?>"><?php if ($_GET['a'] === 'new'){ echo "Publiciraj"; } else { echo "Izbriši Skup"; }?></button>
    </body>
</html>
