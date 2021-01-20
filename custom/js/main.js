const uploadFile = async (form) => {
	const file = form.files;
	if (file.value === '') {
		alert('Select a file.');
		return undefined;
	}
	const formdata = new FormData(form);
	try {
		const result = await fetch('/lib/server/upload.php', {
			method: 'POST',
			body: formdata,
		});

		const json = await result.json();
		return json;
	} catch (e) {
		return e;
	}
};
