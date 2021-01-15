const sentList = document.getElementById('sent-list');
const receivedList = document.getElementById('received-list');

const composeTableData = (data, mode) => {
	// compose table data by parsing data and checking for mode of received or sent then return html
	let html = '';
	if (mode === 'r') {
		if (data.length < 1) {
			return `
			<tr>
				<td colspan='3' style='text-align: center'>No data in table.</td>
			</tr>
			`;
		}
		let keys = Object.keys(data);
		keys.forEach((item, index) => {
			html += `
			<tr>
				<td>${data[index].sender}</td>
				<td><a href='${data[index].file_link}' target='_blank'>${data[index].name}</a></td>
			</tr>
			`;
		});
		return html;
	}
	if (mode === 's') {
		if (data.length < 1) {
			return `
			<tr>
				<td colspan='3' style='text-align: center'>No data in table.</td>
			</tr>
			`;
		}
		let keys = Object.keys(data);
		keys.forEach((item, index) => {
			html += `
			<tr>
				<td>${data[index].receiver}</td>
				<td><a href='${data[index].file_link}' target='_blank'>${data[index].name}</a></td>
			</tr>
			`;
		});
		return html;
	}
};

const refreshFileList = async () => {
	try {
		const response = await fetch('/lib/server/getinfo.php?content=ftp');
		const json = await response.json();
		sentList.innerHTML = composeTableData(json.data.sent, 's');
		receivedList.innerHTML = composeTableData(json.data.received, 'r');
	} catch (err) {
		console.error(err);
	}
};

$(document).ready(() => {
	refreshFileList();
	$('#file-upload-form').on('submit', function (e) {
		e.preventDefault();
		$('#upload-btn').prop('disabled', true);
		uploadFile(document.getElementById('file-upload-form'))
			.then((res) => {
				refreshFileList().then(() => {
					alert(res.message);
					$('#upload-btn').prop('disabled', false);
					$('#reset-btn').click();
				});
			})
			.catch((err) => {
				console.log(err);
				$('#upload-btn').prop('disabled', false);
			});
	});
});
