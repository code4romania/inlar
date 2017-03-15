jQuery(window).load(function() {
	
	var basemap = L.tileLayer('//cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
		subdomains: 'abcd',
		maxZoom: 19
	});

	var map = L.map('map', {
		center: [48.0988048, 4.1474343], // Center to Europe by default
		zoom: 4,
		scrollWheelZoom: false,
		layers: [basemap],
	});

	// .addTo(map);
});
