$(document).ready(() => {
	loadFiles();
	const fileForm = $('#fileform');

	$("#refresh").on('click', () => {
		loadFiles();
	});

	fileForm.on('submit', (e) => {
		e.preventDefault();
		// Array of valid file extensions
		let match = ['pdf', 'doc', 'docx', 'jpeg', 'png', 'jpg', 'xlsx', 'txt', 'mp4', 'xls', 'sql', 'exe', 'html', 'css', 'php'];

		// Input information
		let recips = $("#recipients").val(), files = $("#files").prop('files');
		// let title = $("#title").val();


		// File Validation - checking file type
		if (files.length == 0) {
			return alert('No files selected.');
		} else if (files.length == 1) {
			let filetype = files[0].type;
			console.log(filetype);
			// Troubleshooting
			// alert(filetype);
			// return;
			if (!validateFiles(files[0], match)) {
				alert('Invalid File. Only PNG, JPG, JPEG, PDF, & MSWORD Files allowed '+filetype);
				return undefined;
			}
		} else if (files.length > 1) {
			for (let i = 0; i < files.length; i += 1) {
				let filetype = files[i].type;
				if (!validateFiles(files[i], match)) {
					alert('Invalid File. Only PNG, JPG, JPEG, PDF, & MSWORD Files allowed');
					return undefined;
				}
			}
		}

		// Compile Form data
		let formData = new FormData();
		// formData.append('title', title);
		formData.append('recipients', JSON.stringify(recips));
		if (files.length > 1) {
			let key = Object.keys(files);
			key.forEach((item, index) => {
				// console.log(item, files[index]);
				formData.append(item, files[index]);
			});
		} else {
			// console.log('file', files[0]);
			formData.append('file', files[0]);
		}

		// Send Request
		$.ajax({
			url: "../process/filetransfer.php",
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'text',
			data: formData,
			type: 'post',
			success: (res) => {
				// let response = JSON.parse(res);
				alert(res);
				loadFiles();
			}
		});

	});
});

// const validateString = (string) => {
// 	let pattern = /[a-zA-Z0-9 ]/;
// 	return pattern.test(string);
// };

const validateFiles = (file, match) => {
	const filetype = file.name.split('.').pop() || undefined;
	// let match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
	if (!match.includes(filetype)) {
		return false;
	} else {
		return true;
	}
};

const loadFiles = async () => {
	let recFilesTable = $("#rec-files-table");
	let sentFilesTable = $("#sent-files-table");
	try {
		const response = await fetch("../process/loadfiles.php");
		const json = await response.json();
		console.log(json);

		if (json.received !== 'undefined') {
			recFilesTable.html(constructTable(json.received, 'sender'));
			// console.log(recFilesTable);
		}
		if (json.sent !== 'undefined') {
			sentFilesTable.html(constructTable(json.sent, 'receiver'));
			// console.log(recFilesTable);
		}
	} catch (e) {
		console.log(e);
		recFilesTable.html("<tr><td>No files</td></tr>");
		sentFilesTable.html('<tr><td>No files</td></tr>');
	}
};

const constructTable = (object, type) => {
	let rows = "";
	if (object.length < 1) {
		rows += "<tr>";
		rows += `<td>No Files</td>`;
		rows += "</tr>";
		return rows;
	}
	if (object[0]) {
		let keys = Object.keys(object);
		keys.forEach((item, index) => {
			rows += "<tr>";
			rows += `<td>${object[item][type]}</td>`;
			rows += `<td><a href='../${object[item]['file_link']}' target='_blank'>${object[item]['name']}</a></td>`;
			rows += "</tr>";
		});
	} else {
		rows += "<tr>";
		rows += `<td>${object[type]}</td>`;
		rows += `<td><a href='../${object['file_link']}' target='_blank'>${object['name']}</a></td>`;
		rows += "</tr>";
	}
	return rows;
};