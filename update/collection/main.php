<?php

/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

require $_SERVER['DOCUMENT_ROOT'] . '/data/server.php';
require $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';

$title = 'Ažuriranje Skupa';
$name = '';
$colors = ['#aaaaaa', '#333333'];
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
	<link rel="stylesheet" href="/data/tiling.css">

	<script src="/update/collection/main.js"></script>
	<script src="/data/ghost_checker.js"></script>
</head>

<body>
	<svg class="Home margin_10_px" viewBox="0 0 16 16" onclick="leave()">
		<path id="path_back" fill="none" stroke="var(--tx_color, <?php echo $colors[1]; ?>)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 8H4l4 4M4 8l4-4"/>
	</svg>
	
	<div id="Opce stvari">
		<div style="display:flex; flex-direction: row; align-items: center; justify-content: center;">
			<input id='Title' type="text" placeholder="Ime Skupa" value="<?php echo $name; ?>" onblur="rename()">
		</div>

		<div style="margin-bottom: 20px;"></div>

		<div style="display:flex; flex-direction: row; align-items: center; justify-content: center;">
			<textarea id="desc" type="text" rows="4" placeholder="Opišite ovdje, što sadrži vaš skup..." onblur="save_description()"><?php echo get_collection_description($title) ?></textarea>
		</div>

		<div style="margin-bottom: 20px;">
			<label for="bcolor">Promjeni boju ozadine:</label>
			<input id="bcolor" type="color" onblur="save_colors()" onchange="change_bg_color()" value="<?php echo $colors[0]; ?>">

			<label for="tcolor">Promjeni boju teksta:</label>
			<input id="tcolor" type="color" onblur="save_colors()" onchange="change_t_col()" value="<?php echo $colors[1]; ?>">

			<select id="visibility" onchange="set_visibility()">
				<option value="public" 
					<?php if (get_collection_visibility($_GET['c']) == 'public') {
							echo 'selected';
						} ?>
					>Javno
				</option>
				<option value="unlisted"
					<?php if (get_collection_visibility($_GET['c']) == 'unlisted') {
						echo 'selected';
					} ?>
					>Nerazvrstano
				</option>
				<option value="private"
					<?php if (get_collection_visibility($_GET['c']) == 'private') {
						echo 'selected';
					} ?>
					>Privatno
				</option>
			</select>
		</div>

		<div style="margin-bottom: 20px;"></div>
	</div>

	<hr>

	<div id="image_section">
	<h2>Slike u skupu:</h2>
		<div id="image_container">

		</div>
	</div>

	<hr>

	<div style="display:flex; flex-direction: row; align-items: center; justify-content: center;">
		<button id='delete_collection' onclick="ask_delete()">
			<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 16 16">
				<path fill="currentColor" d="m5 1v1h-4v2h14v-2h-4v-1zm-3 4v8a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-8zm1 2h2v6h-2zm4 0h2v6h-2zm4 0h2v6h-2z"/>
			</svg>
			<span>Izbriši Skup</span>
		</button>
	</div>

</body>

</html>