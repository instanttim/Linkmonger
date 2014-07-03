// Linkmonger Javascript - (C) 2005 Timothy B Martin

function popupWindow(name, url, width, height, scroll, resize) {
	//creating the window
	config='toolbar=no,location=no,directories=no,status=no,menubar=no,width='+width+',height='+height+',scrollbars='+scroll+',resizable='+resize;
	newWindow = window.open(url, name, config);
}

function changeTitle(name) {
	document.title = name;
	return true;
}

function moveOn(where) {
	location.replace(where);
	return true;
}

