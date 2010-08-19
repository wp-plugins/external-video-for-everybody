function evfeTest(video) {
	var source = video.currentSrc;
	alert(source);
	video.src = "http://kevinwiliarty.com/video/carousel.webm";
	alert(video.src);
	video.load();
}
