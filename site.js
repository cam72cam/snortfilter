
var dialog;
$(document).ready(function () {
	dialog = document.createElement('div');
	document.body.appendChild(dialog);
	dialog = $(dialog).attr('id', 'dialog');
	dialog.dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 100
		},
		hide: {
			effect: "blind",
			duration: 100
		},
		width: '80%',
		height: 500,
	});
});


function show_dialog(frame) {
	dialog.html('<iframe src="' + frame + '" frameborder="0" style="overflow:hidden;overflow-x:hidden;overflow-y:hidden;height:100%;width:100%;position:absolute;top:0px;left:0px;right:0px;bottom:0px" height="100%" width="100%">');
	dialog.dialog('open');
}
