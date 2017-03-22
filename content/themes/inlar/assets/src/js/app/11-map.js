"use strict";
jQuery(window).load(function() {

	var basemap = L.tileLayer(mapconfig.template.nolabels, {
		attribution: mapconfig.attribution,
		subdomains: 'abcd',
		maxZoom: 19
	});

	var map = L.map('map', {
		center: mapconfig.center,
		scrollWheelZoom: false,
		layers: [basemap],
		zoom: 4,
	});

	var mu = new maputils();

	// .addTo(map);
});

(function($) {
	var url = "/json/ngos";

	$.getJSON(url, function(response) {
		window.mapconfig.data = response.data;
	})
})(jQuery);
