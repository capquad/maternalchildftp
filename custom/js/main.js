const uploadFile = (form) => {
	const file = form.files;
	if (file.value === '') {
		alert('Select a file.');
		return undefined;
	}
	const formdata = new FormData(form);
	(async () => {
		try {
			const result = await fetch('/lib/server/upload.php', {
				method: 'POST',
				body: formdata,
			});

			const json = await result.json();
			alert(json.message);
		} catch (e) {
			console.error(e);
		}
	})();
};
