function maputils() {
	this.precision = 6;
	this.center    = false;
	this.input     = false;
	this.baselayer = false;
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
}

// Convert LatLng pair to string
maputils.prototype.latlng_to_str = function(obj) {
	return obj.lat.toFixed(this.precision) + ',' + obj.lng.toFixed(this.precision);
}

// Convert string to LatLng pair
maputils.prototype.str_to_latlng = function(str) {
	str = str.split(',');
	return (str.length == 2 ? jQuery.map(str, parseFloat) : false);
}

maputils.prototype.control_dropdown = function() {
	var target = jQuery('#country-control'),
		control;

	control = '<span class="button current">';
	control+= '<img src="'+ flags_url +'/'+ mapconfig.current.flag + png_or_svg() + '" class="flag">';
	control+= '<span class="name">'+ mapconfig.current.name + '</span>';
	control+= '</span>';

	control+= '<div class="dropdown-container">';
	control+= '<span class="button dropdown-toggle">'+ i18n.another_country +'<i class="icon-arrow-black"></i></span>';
	control+= '<ul class="dropdown top-right">';

	for (var country in mapconfig.countries) {
		control+= '<li'+ (mapconfig.current.id == mapconfig.countries[country].id ? ' hidden' : '') +'>';
		control+= '<a href="'+ mapconfig.countries[country].url +'" data-country="'+mapconfig.countries[country].id +'">'+ mapconfig.countries[country].name +'</a>';
		control+= '</li>';
	}

	target.empty().html(control);
}

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
			var props = ['phone', 'email'],
				popup = '';

			popup+= '<div class="card-header">';
			popup+= (feature.properties.img != '') ? '<img src="'+ feature.properties.img +'" alt="">' : '';
			popup+= '<strong class="name">'+ feature.properties.name +'</strong>';
			popup+= '</div>';

			popup+= '<div class="card-content">';
			for (var i = 0; i < props.length; i++) {				
				if (feature.properties.hasOwnProperty(props[i]) && feature.properties[props[i]] !== '') {
					popup+= '<span class="'+ props[i] +'">'+ feature.properties[props[i]] +'</span>';
				}
			}
			popup+= '</div>';

			layer.bindPopup(popup);
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
}

maputils.prototype.enable_map = function() {
	this.control_dropdown();
	jQuery('.map-container').removeClass('map-closed').addClass('map-open');
}

maputils.prototype.disable_map = function() {
	jQuery('.map-container').removeClass('map-open').addClass('map-closed');

	jQuery('#country-control').empty();
}
