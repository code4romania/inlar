"use strict";
jQuery(window).load(function() {
	var mu = new maputils();

	mu.baselayer = L.tileLayer(mapconfig.template.nolabels, {
		attribution: mapconfig.attribution,
		subdomains: 'abcd',
		maxZoom: 19
	});

	mu.map = L.map('map', {
		center: mapconfig.center,
		scrollWheelZoom: false,
		layers: [mu.baselayer],
		zoom: 4,
	});

	jQuery('.countries a', '.map-container').on('click', function(e) {
		var country_id = jQuery(this).data('country');
		e.preventDefault();

		mu.add_markers(mapconfig.ngos, country_id);
		mu.enable_map();
	});
});

(function($) {
	var json = {
		'ngos':      '/json/ngos/',
		'countries': '/json/countries/',
	};

	$.each(json, function(key, url) {
		$.getJSON(url, function(response) {
			window.mapconfig[key] = response.data;
		})
	});

	
})(jQuery);
