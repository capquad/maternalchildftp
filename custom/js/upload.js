$(document).ready(() => {
	$("#file-upload-form").on('submit', function (e) {
		e.preventDefault();
		uploadFile(document.getElementById("file-upload-form"));
	})
});
