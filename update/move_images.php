<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/collections.php';

?>
<!DOCTYPE html>
<html>

<head>
	<title>Move images between collections</title>

	<link rel="stylesheet" href="/data/style.css">
	<style>
		h1 {
			font-size: clamp(1rem, 6vw, 5rem);
		}
		div {
			display: flex;
			flex-direction: row;
			justify-content: center;
			flex-wrap: wrap;
		}
		label, input, select {
			text-align: center;
			color: #333;
			font-size: clamp(0.75rem, 3vw, 4rem);
			font-family: 'Dejavu Serif', Serif-serif;
			margin: 1%;
		}

	</style>
	<script>
		let collections = <?php echo json_encode(get_collections()); ?>;

		function submit(){
			const data = new FormData();
			data.append('func', 'change_collection_of_image');
			data.append('collection', document.getElementById("orig_collection").value);
			data.append('image', document.getElementById("fname").value);
			data.append('new_collection', document.getElementById("new_collection").value);

			fetch('/libs/images	.php', {
				method: 'POST',
				body: data
			})
			.then(response => response.text())
			.then(result => {
				console.log(result);
				if (result){
					document.getElementById("Output").innerText = result;
				}
				else {
					document.getElementById("Output").innerText = "Something went wrong..."
				}
			});
		}

		window.addEventListener('load', function () {
			collections.forEach(element => {
				const node = document.createElement('option');
				node.value = element;
				node.textContent = element;
				document.getElementById('orig_collection').appendChild(node);
				document.getElementById('new_collection').appendChild(node.cloneNode(true));
			});
		});
	</script>
</head>

<body>
	<svg class="Home margin_10_px" viewBox="0 0 16 16" onclick="window.location.assign('/update/')">
		<path fill="none" stroke="#333" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 8H4l4 4M4 8l4-4"/>
	</svg>
	<h1>Move images between collections</h1>
	<div>
		<label for="orig_collection">Collection: </label>
		<select name="orig_collection" id="orig_collection"></select>
		<input type="text" placeholder="Filename" id="fname">
		<label for="new_collection">to Collection:</label>
		<select name="new_collection" id="new_collection"></select>
		<input type="submit" onclick="submit();">
	</div>
	<hr>
	<h2 id="Output"></h2>
</body>

</html>