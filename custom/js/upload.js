$(document).ready(() => {
	$('#file-upload-form').on('submit', function (e) {
		e.preventDefault();
		$('#upload-btn').prop('disabled', true);
		uploadFile(document.getElementById('file-upload-form'))
			.then((res) => {
				alert(res.message);
				$('#upload-btn').prop('disabled', false);
			})
			.catch((err) => {
				console.log(err);
				$('#upload-btn').prop('disabled', false);
			});
	});
});
