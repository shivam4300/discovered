'format es6';
'use strict';

function onResize() {
	const vh = window.innerHeight * 0.01;
	document.documentElement.style.setProperty('--vh', `${vh}px`);
}

const obj = {
	init(ctx:HTMLElement) {
		window.addEventListener('resize', onResize);
		onResize();
	},
};

export default obj;
