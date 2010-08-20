function evfeCreateInfoDiv(video,source) {
	var evfeInfoDiv = document.createElement('div');
	var evfeInfoDivText = document.createTextNode('working...'+source);
	evfeInfoDiv.appendChild(evfeInfoDivText);
	video.parentNode.appendChild(evfeInfoDiv);
}

function evfeTest(video) {
	var source = video.currentSrc;
	video.src = "http://kevinwiliarty.com/video/carousel.webm";
	video.load();
	evfeCreateInfoDiv(video,source);
}
