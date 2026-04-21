/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

window.addEventListener('load', function () {
	const docWidth = document.documentElement.offsetWidth;

	[].forEach.call(document.querySelectorAll('*'), function(el) {
	if (el.offsetWidth > docWidth) {
		console.log('Ghost found:', el);
		}
	});
});