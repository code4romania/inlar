"use strict";
window.mapconfig = {
	template: {
		all:        '//cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png',
		nolabels:   '//cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}.png',
		onlylabels: '//cartodb-basemaps-{s}.global.ssl.fastly.net/dark_only_labels/{z}/{x}/{y}.png',		
	},
	attribution: '&copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
	// Center to Europe by default
	center: [48.0988048, 4.1474343],
	// Filled in on page load when needed
	data: [],
};
