function maputils() {
	this.precision = 6;
	this.center    = false;
	this.input     = false;
	this.baselayer = false;
	this.map       = false;
	this.marker    = false;
}


maputils.prototype.get_coords = function() {
	// start with default coordinates
	var coords = window.mapconfig.center;

	if (typeof this.input == 'object') {
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

maputils.prototype.add_markers = function(list) {
	jQuery.each(list, function(i,k) {
		console.log(i, k);
	});
}
