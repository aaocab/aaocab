/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var maxSizeAllowed = 3.5 * 1024 * 1024;



function uploadDoc(doc_type, doc_name) {
	$("#uploadDoc" + doc_type)[0].submit();
}
$(document).ready(function ()
{
	$skipShowGozonowPromt = true;
});

function previewDoc(obj, imgPlaceHolder) {
	const file = obj.files[0];  
	console.log(file);  
	if (file) {
		let reader = new FileReader(); 
		reader.onload = function (event) {
			$("." + imgPlaceHolder).attr('src', event.target.result); 
		}; 
		reader.readAsDataURL(file);
	}
}


function getFileSize(fileName) {
	var fileInput = fileName;
	var fileSizeB = fileInput.files[0].size;
//	var fileShow = "";
//	fileShow = fileSizeB + " B";
//	if (parseInt(fileSizeB / 1024) >= 1) {
//		var fileSizekB = parseInt(fileSizeB / 1024);
//		fileShow = fileSizekB + " KB";
//	}
//	if (parseInt(fileSizekB / 1024) >= 1) {
//		var fileSizemB = parseInt(fileSizekB / 1024);
//		fileShow = fileSizemB + " MB";
//	}

	return fileSizeB;
}
function fileValidation(fileName, sbmtbtnid) {
	var fileInput = fileName;
	var filePath = fileInput.value;
	// Allowing file type
	var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
	document.getElementById(sbmtbtnid).disabled = false;

	if (!allowedExtensions.exec(filePath)) {

		fileInput.value = '';
		document.getElementById(sbmtbtnid).disabled = true;
		alert('Invalid file type2');
		return false;
	} else
	{
		var fileSizeB = getFileSize(fileName);
		if (fileSizeB == 0) {
			alert("Empty file not allowed");
			fileInput.value = '';
			document.getElementById(sbmtbtnid).disabled = true;
			return false;
		}
		if (maxSizeAllowed > 0 && fileSizeB > maxSizeAllowed) {
			alert("Max size allowed " + maxSizeAllowed / 1024 / 1024 + " MB");
			fileInput.value = '';
			document.getElementById(sbmtbtnid).disabled = true;
			return false;
		}
	}
}