/*
 * JavaScript Pretty Date
 * Copyright (c) 2011 John Resig (ejohn.org)
 * Licensed under the MIT and GPL licenses.
 */

// Takes an ISO time and returns a string representing how
// long ago the date represents.
function prettyDate(time){
	var date = new Date((time || "").replace(/-/g,"/").replace(/[TZ]/g," ")),
		diff = (((new Date()).getTime() - date.getTime()) / 1000),
		day_diff = Math.floor(diff / 86400);
			
	if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 )
		return;
			
	return day_diff == 0 && (
			diff < 60 && "Beberapa detik yang lalu" ||
			diff < 120 && "1 menit yang lalu" ||
			diff < 3600 && Math.floor( diff / 60 ) + " menit yang lalu" ||
			diff < 7200 && "1 jam yang lalu" ||
			diff < 86400 && Math.floor( diff / 3600 ) + " jam yang lalu | "+time) ||
		day_diff == 1 && "Kemarin | "+time ||
		day_diff < 7 && day_diff + " hari yang lalu | "+time ||
		day_diff < 31 && Math.ceil( day_diff / 7 ) + " minggu yang lalu | "+time;
}

// If jQuery is included in the page, adds a jQuery plugin to handle it as well
if ( typeof jQuery != "undefined" )
	jQuery.fn.prettyDate = function(){
		return this.each(function(){
			var date = prettyDate(this.title);
			if ( date )
				jQuery(this).text( date );
		});
	};