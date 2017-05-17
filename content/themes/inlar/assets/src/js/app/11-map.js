"use strict";
jQuery(window).load(function() {
	if (!jQuery('#map').length)
		return;

	var mu = new maputils(),
	markers_fn = function(e) {
		var country_id = jQuery(this).data('country');

		if (typeof country_id == 'undefined') {
			country_id = jQuery(this).val();
		}

		if (!country_id)
			return;

		e.preventDefault();

		mu.add_markers(mapconfig.ngos, country_id);
		mu.enable_map();
	};

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

	jQuery('.countries li', '.map-description').on('click', markers_fn);
	jQuery('#country-control').on('click', '.dropdown li', markers_fn);

	jQuery('.countries select', '.map-description').on('change', markers_fn);
	jQuery('#country-control').on('change', 'select', markers_fn);
});

(function($) {
	var json = {
		'ngos':      '/json/ngos/',
		'countries': '/json/countries/',
	};

	$.each(json, function(key, url) {
		$.getJSON(url, function(response) {
			if (!response.success)
				return;

			window.mapconfig[key] = response.data[key];
		})
	});

	
})(jQuery);
