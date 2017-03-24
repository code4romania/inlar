"use strict";

maputils.prototype.init_admin = function() {
	this.input = jQuery('#ptf_coords');

	// Bail if we are not on the right page
	if (!this.input.length)
		return;

	this.center = this.get_coords();

	// Set up the base layer
	this.baselayer = L.tileLayer(window.mapconfig.template.all, {
		attribution: window.mapconfig.attribution,
		subdomains: 'abcd',
		maxZoom: 19,
		minZoom: 4
	});

	// Set up the map and add the baselayer
	this.map = L.map('map', {
		layers: [this.baselayer],
		scrollWheelZoom: true,
		center: this.center,
		zoom: 4,
	});

	var _this = this;

	// Add the contact marker
	this.marker = L.marker(this.center, {
		draggable: true,
	}).addTo(this.map).on('movestart', function(e) {
		_this.input.prop('disabled', true);
	}).on('moveend', function(e) {
		_this.update_coords('input', e.target._latlng);
		_this.input.prop('disabled', false);
	});

	this.input.on('change', function(e) {
		_this.update_coords('marker', e.target.value);
	});
}

maputils.prototype.update_coords = function(target, coords) {
	switch(target) {
		case 'input':
			this.input.val(this.latlng_to_str(coords));
			break;

		case 'marker':
			this.marker.setLatLng(this.str_to_latlng(coords));
			break;

		default:
			console.log('Invalid target ' + target + ' for update_coords()');
			break;
	}
}

jQuery(window).load(function() {
	var mu = new maputils();

	mu.init_admin();
});
