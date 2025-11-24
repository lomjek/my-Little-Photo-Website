<?php

/*****************************************************/
/*  This file is part of 'my Little Photo Website'   */
/* It is published on github under the MIT License:  */
/* https://github.com/lomjek/my-Little-Photo-Website */
/*****************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
require $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';

if ($_GET['a'] === 'edit') {
    $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $_GET['c'];
    if (file_exists($path)) {
        $title = str_replace('-', ' ', $_GET['c']);
        $name = $title;
        $colors = get_collection_colors($_GET['c']);
        if (file_exists($path . '/.d_')) {
            $description = file_get_contents($path . '/.d_');
        } else {
            $description = '';
        }
    }
} elseif ($_GET['a'] === 'new') {
    $path = $_SERVER['DOCUMENT_ROOT'] . '/photos/Novi-Skup';
    if (collection_exists('Novi-Skup')) {
        echo "<script>alert('Skup pod imenom \"Novi Skup\" već postoji. Molimo, izbrišite ga ili ga preimenujte prije nego napravite novi skup.'); window.location = '/update/collection/edit/Novi-Skup';</script>";
        exit();
    }
    if (create_collection('Novi-Skup')) {
        header('Location: /update/collection/edit/Novi-Skup');
        exit();
    } else {
        kill(410, 'Novi skup nije mogo bit napravljen...');
    }
} else {
    kill(406);
}

?>
<!DOCTYPE html>
<html lang="hr">

<head>
    <title><?php echo str_replace('-', ' ', htmlspecialchars($title)); ?></title>

    <link rel="stylesheet" href="/data/style.css">
    <link rel="stylesheet" href="/update/collection/main.css">

    <script src="/update/collection/main.js"></script>
</head>

<body>
    <h3 id="leave" onclick="leave()">
        <= Ažuriranje LLS</h3>
            <input id='Title' type="text" placeholder="Ime Skupa" value="<?php echo $name; ?>" onblur="rename()">

            <div style="margin-bottom: 20px;"></div>

            <textarea id="desc" type="text" rows="4" placeholder="Opišite ovdje, što sadrži vaš skup..." onblur="save_description()"></textarea>

            <div style="margin-bottom: 20px;">
                <label for="bcolor">Promjeni boju ozadine:</label>
                <input id="bcolor" type="color" onblur="save_colors()" onchange="change_bg_color()" value="<?php echo $colors[0]; ?>">

                <label for="tcolor">Promjeni boju teksta:</label>
                <input id="tcolor" type="color" onblur="save_colors()" onchange="change_t_col()" value="<?php echo $colors[1]; ?>">

                <select id="visibility" onchange="set_visibility()">
                    <option value="public" <?php if (get_collection_visibility($_GET['c']) == 'public') {
                                                echo 'selected';
                                            } ?>>Javno</option>
                    <option value="unlisted" <?php if (get_collection_visibility($_GET['c']) == 'unlisted') {
                                                    echo 'selected';
                                                } ?>>Nerazvrstano</option>
                    <option value="private" <?php if (get_collection_visibility($_GET['c']) == 'private') {
                                                echo 'selected';
                                            } ?>>Privatno</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;"></div>

            <button id='delete' onclick="ask_delete()">Izbriši Skup</button>
</body>
<script>
    change_bg_color();
    change_t_col();
</script>

</html>