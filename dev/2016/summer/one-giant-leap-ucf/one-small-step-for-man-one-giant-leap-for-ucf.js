(function () {

// Based on Codepen by Amelia Bellamy-Royds http://codepen.io/AmeliaBR/pen/emoPVL

var fallback_file, img, src;

function svgasimg() {
	return document.implementation.hasFeature(
		"http://www.w3.org/TR/SVG11/feature#Image", "1.1");
}

if (!svgasimg()){
	var e = document.getElementsByTagName("img");
	if (!e.length){
		e = document.getElementsByTagName("IMG");
	}

	for (var i=0, n=e.length; i<n; i++){
		img = e[i];
		src = img.getAttribute("src");

		if (src && src.match(/svgz?$/) && img.getAttribute("data-fallback")) {
			fallback_file = src.substr(0, src.lastIndexOf(".")) + ".png";

			/* URL ends in svg or svgz */
			img.setAttribute("src", fallback_file);
		}
	}
}
} ());