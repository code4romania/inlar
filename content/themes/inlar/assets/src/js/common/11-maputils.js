function maputils() {
	this.precision = 6;
	this.center    = false;
	this.input     = false;
	this.baselayer = false;
	this.textlayer = false;
	this.map       = false;
	this.markers   = false;
}

maputils.prototype.get_coords = function() {
	// start with default coordinates
	var coords = window.mapconfig.center;

	if (typeof this.input == 'object' && this.input.val()) {
		coords = this.str_to_latlng(this.input.val());
	}

	return coords;
};

// Convert LatLng pair to string
maputils.prototype.latlng_to_str = function(obj) {
	return obj.lat.toFixed(this.precision) + ',' + obj.lng.toFixed(this.precision);
};

// Convert string to LatLng pair
maputils.prototype.str_to_latlng = function(str) {
	str = str.split(',');
	return (str.length == 2 ? jQuery.map(str, parseFloat) : false);
};

maputils.prototype.control_dropdown = function() {
	var target   = jQuery('#country-control'),
		source   = jQuery('#template-map_control').html(),
		template = Handlebars.compile(source);

	target.empty().html(template({
		current: mapconfig.current,
		countries: mapconfig.countries
	}));
};

maputils.prototype.add_markers = function(geojson, country_id) {
	this.map.removeLayer(this.markers);
	this.markers = L.geoJson(geojson, {
		pointToLayer: function(geoJsonPoint, latlng) {
			return L.marker(latlng, {
				icon: L.divIcon({
					className: 'point-icon',
					iconSize:  new L.Point(20, 20),
				}),
			});
		},
		onEachFeature: function(feature, layer) {
			var source   = jQuery('#template-map_card').html(),
				template = Handlebars.compile(source);

			layer.bindPopup(template(feature.properties));
		},
		filter: function(feature, layer) {
			if (feature.properties.country_id == country_id) {
				window.mapconfig.current = {
					'id':   feature.properties.country_id,
					'flag': feature.properties.country_flag,
					'name': feature.properties.country_name,
				};

				return true;
			}
			return false;
		}
	}).addTo(this.map);
};

maputils.prototype.enable_map = function() {
	this.control_dropdown();
	jQuery('.map-container').removeClass('map-closed').addClass('map-open');
	this.map.fitBounds(this.markers.getBounds(), {
		paddingTopLeft: [0, 150],
		maxZoom: 15,
	});
};

maputils.prototype.disable_map = function() {
	jQuery('.map-container').removeClass('map-open').addClass('map-closed');

	jQuery('#country-control').empty();
};
