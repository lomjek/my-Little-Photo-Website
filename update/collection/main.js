/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

images_in_collection = [];

function get_basename(path) {
	let fname = path.split('/').reverse()[0];
	const lastDotIndex = fname.lastIndexOf('.');
	if (lastDotIndex === -1) return fname;
	return fname.substring(0, lastDotIndex);
}

function change_bg_color() {
	const allBodyElements = document.body.querySelectorAll('*');
	allBodyElements.forEach(element => {
		element.style.setProperty("background-color", document.getElementById("bcolor").value, "important");
	});
	document.body.style.setProperty("background-color", document.getElementById("bcolor").value, "important");
}
function change_t_col() {
	const allBodyElements = document.body.querySelectorAll('*');
	allBodyElements.forEach(element => {
		element.style.setProperty("color", document.getElementById("tcolor").value, "important");
	});
	document.getElementById("path_back").style.stroke=document.getElementById("tcolor").value, "important";
}

//input
function leave() {
	window.location.assign('/update/');
}
async function sendRequest(urlencoded_data, reciver) {
	try {
		const response = await fetch(reciver, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: urlencoded_data
		})
		console.log(response);
		if (!response.ok) {
			const errorText = await response.text();
			throw new Error(`HTTP error! Status: ${response.status}. Message: ${errorText}`);
		}
		console.log(await response.text());
		return true;
	} catch (error) {
		alert('Error during request: ' + error);
		return false;
	}
}
function rename() {
	const formData = "func=rename_collection&&old_name=" + encodeURIComponent(original_name.replaceAll(" ", "-")) + "&&new_name=" + encodeURIComponent(document.getElementById('Title').value.replaceAll(" ", "-").replaceAll("&", "-"));
	if (sendRequest(formData, '/libs/collections.php')) {
		original_name = document.getElementById('Title').value
	} else {
		document.getElementById('Title').value = original_name;
	}
}
function save_description() {
	const formData = 'func=set_collection_description&&collection=' + encodeURIComponent(original_name.replaceAll(" ", "-")) + '&&description=' + encodeURIComponent(document.getElementById('desc').value);
	sendRequest(formData, '/libs/collections.php');
}
function save_colors() {
	let colors = [document.getElementById('bcolor').value, document.getElementById('tcolor').value];
	let formData = 'func=set_collection_colors&&collection=' + encodeURIComponent(original_name.replaceAll(" ", "-")) + '&&bg_color=' + encodeURIComponent(colors[0]) + '&&text_color=' + encodeURIComponent(colors[1]);
	sendRequest(formData, '/libs/collections.php');
}
function set_visibility() {
	const formData = 'func=set_collection_visibility&&collection=' + encodeURIComponent(original_name.replaceAll(" ", "-")) + '&&visibility=' + encodeURIComponent(document.getElementById('visibility').value);
	sendRequest(formData, '/libs/collections.php');
}
async function ask_delete() {
	const userChoice = confirm("Dali ste sigurni, da bi izbrisali ovaj skup?\nJednom kad ga nema, ga nema...");
	var send_thing = 'func=delete_collection&&collection=' + original_name.replaceAll(" ", "-");
	console.log(send_thing);
	if (sendRequest(send_thing, '/libs/collections.php')) {
		window.location.href = '/update/';
	}
}

function delete_image_from_collection(image_name) {
	const userChoice = confirm("Dali ste sigurni, da bi izbrisali ovu sliku iz skupa?");
	if (userChoice) {
		let formData = 'func=delete_image';
		formData += '&& collection=' + encodeURIComponent(original_name.replaceAll(" ", "-"));
		formData += '&&image=' + encodeURIComponent(image_name);

		console.log(formData);
		sendRequest(formData, '/libs/images.php').then(success => {
			if (success) {
				const imgParent = document.getElementById(image_name + "_parent");
				const imageParent = document.getElementById(image_name + "_parent");
				imageParent.remove();
			}
		});
	}
}
function set_image_description(imgParent) {
	let formData = "func=set_image_description";
	formData += "&&collection=" + encodeURIComponent(original_name.replaceAll(" ", "-"));
	formData += "&&image=" + encodeURIComponent(imgParent.querySelector('img').alt);
	formData += "&&description=" + encodeURIComponent(imgParent.querySelector('textarea').value);
	sendRequest(formData, '/libs/images.php').then(success => {
		if (success) {
			console.log("Description saved.");
			window.location.reload();
		}
	});
}

function sendImgRenameRequest(imgParent) {
	const image = imgParent.querySelector('img');
	const new_name = imgParent.querySelector('input').value;

	let formData = "func=rename_image";
	formData += "&&collection=" + encodeURIComponent(original_name.replaceAll(" ", "-"));
	formData += "&&old_name=" + encodeURIComponent(image.alt);
	formData += "&&new_name=" + encodeURIComponent(new_name.replaceAll(" ", "-") + '.webp');

	console.log(formData)
	sendRequest(formData, '/libs/images.php').then(success => {
		if (success) {
			window.location.reload();
		}
	});
}

async function load_images_in_collection() {
	console.log("Loading images in collection: " + original_name);
	const formData = "func=get_images_in_collection_for_update&&collection=" + encodeURIComponent(original_name.replaceAll(" ", "-"));
	try {
		const response = await fetch('/libs/images.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: formData
		});
		if (!response.ok) {
			const errorText = await response.text();
			throw new Error(`HTTP error! Status: ${response.status}. Message: ${errorText}`);
		}
		const data = await response.json();
		console.log(data);
		const imageContainer = document.getElementById('image_container');
		data.forEach(image => {
			images_in_collection.push(image.name);

			const imgParent = document.createElement('div');
			imgParent.id = image.name + "_parent";
			imgParent.className = "imgParent";

			const nameLabel = document.createElement('input');
			nameLabel.value = get_basename(image.name);
			nameLabel.className = "imgNameLabel";
			nameLabel.id = image.name + "_label";
			nameLabel.onblur = sendImgRenameRequest.bind(null, imgParent);
			imgParent.appendChild(nameLabel);

			const imgElement = document.createElement('img');
			const link = '/photos/' + encodeURIComponent(original_name.replaceAll(" ", "-")) + '/' + encodeURIComponent(image.name);
			imgElement.src = link;
			imgElement.alt = image.name;
			imgElement.className = "imgElement";
			imgParent.appendChild(imgElement);

			const caption = document.createElement('textarea');
			caption.value = image.description;
			caption.className = "imgCaption";
			caption.placeholder = "Opis slike...";
			caption.id = image.name + "_caption";
			caption.onblur = set_image_description.bind(null, imgParent);
			imgParent.appendChild(caption);

			const moreContainer = document.createElement('div');
			moreContainer.className = "imgMoreContainer";
			moreContainer.id = image.name + "_more_container";

			const deleteButton = document.createElement('button');
			deleteButton.innerText = "Izbriši sliku iz skupa";
			deleteButton.className = "imgDeleteButton";
			deleteButton.id = image.name + "_delete_button";
			deleteButton.onclick = delete_image_from_collection.bind(null, image.name);
			moreContainer.appendChild(deleteButton);

			imgParent.appendChild(moreContainer);
			imgParent.appendChild(document.createElement('hr'));

			imageContainer.appendChild(imgParent);
		});
	} catch (error) {
		alert('Error during request: ' + error);
	}
	change_bg_color();
	change_t_col();
}
//When page loaded...
window.addEventListener('load', function () {
	change_bg_color();
	change_t_col();
	original_name = document.getElementById('Title').value;

	load_images_in_collection();
});
