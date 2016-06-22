// Based on Codepen by Amelia Bellamy-Royds http://codepen.io/AmeliaBR/pen/emoPVL

function svgasimg() {
	return document.implementation.hasFeature(
		"http://www.w3.org/TR/SVG11/feature#Image", "1.1");
}

if (!svgasimg()){
	var e = document.getElementsByTagName("img");
	if (!e.length){
		e = document.getElementsByTagName("IMG");
	}
	console.log(e);
	for (var i=0, n=e.length; i<n; i++){
		var img = e[i],
				src = img.getAttribute("src");
		if (src && src.match(/svgz?$/) && img.getAttribute("data-fallback")) {
			console.log(img);
			/* URL ends in svg or svgz */
			img.setAttribute("src",
			img.getAttribute("data-fallback"));
		}
	}
}