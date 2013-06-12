$(function(){
	$('.notes_img').mobilynotes({
		init: 'rotate',
		showList: false
	});
	$('.notes_txt').mobilynotes({
		init: 'plain',
		positionMultiplier: 20,
		title: 'h2',
		autoplay: false
	});
});
