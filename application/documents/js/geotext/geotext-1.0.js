/*!
 * geotext v1.0
 *
 * https://github.com/Frizzled/geotext
 *
 * Copyright (c) 2014 Vladimir Loscutoff
 * Released under the MIT license
 */
var GeoText = (function ($, gMaps, undefined) {
	'use strict';
	
	function GeoText(vars) {
		this.vars = { // Settings
			'name' : 'default',
			'location' : new Object,
			'delimiter' : ', '
		};
		this.data = { // Settings
			'success' : false
		};

		// Merge settings
		if (vars !== undefined) { this.vars = $.extend(this.vars, vars); }

		this.init();
	}

	GeoText.prototype.init = function() {
		var that = this;
		if (navigator.geolocation)
		{
		
			// Get rules
			var ax = document.getElementsByName( 'Application_User_UserLocation_Creator' );
			if( ax.length )
			{ 
			//	alert( ax.length );
				ayoola.spotLight.splashScreen();
			}
			
			navigator.geolocation.getCurrentPosition(
				function (location) 
				{
				
					that.location = location;
					var point = new gMaps.LatLng(location.coords.latitude, location.coords.longitude);
					new gMaps.Geocoder().geocode({'latLng': point}, function (res, status) {
						if(status === gMaps.GeocoderStatus.OK && res[0] !== undefined) {
							that.setLocation(res[0]);
						}
					});
				},
				null,
				{ enableHighAccuracy: true }
			);
		
			// Get rules
		}
	};

	GeoText.prototype.setLocation = function(location) {
		var that = this;
		that.data.success = true;
		$.each(location.address_components, function(k,v1) {
			$.each(v1.types, function(k2, v2) { 
				that.data[v2]=v1.short_name;
				that.data[v2+'_long']=v1.long_name;
			});
		});
		that.applyText();
	};

	GeoText.prototype.applyText = function() {
		var that = this;
		var geoFields = $('[class*=geotext]');
		$.each(geoFields, function(key, field) {
			var $field = $(field);
			var delimiter = $field.data('geotext-delimiter') || that.vars.delimiter;
			var text = that.parseField($field, delimiter);
			if (text) {
				// Check for leading or following text
				if ($field.data('geotext-text-before')) { text = $field.data('geotext-text-before') + text; }
				if ($field.data('geotext-text-after')) { text = text + $field.data('geotext-text-after'); }

				if ($field.is('input')) {
					$field.val(text).change();
				} else if ($field.is('select')) {
					$field.val(text).change;
				} else {
					$field.html(text);
				}
			}
		});	
		if( document.getElementsByName( 'Application_User_UserLocation_Creator' ) )
		{ 
			ayoola.spotLight.splashScreenObject.close();
		}
	};

	GeoText.prototype.parseField = function(field, delimiter) {
		var that = this;

		// Get rules
		var getRules = /geotext\[(.*)\]/.exec(field.attr('class'));
	//	alert( getRules ); 
		if (!getRules) { return false; }
		var str = getRules[1];
		var rules = str.split(/\[|,|\]/);
		$.each (rules, function(key, rule) {
			rules[key] = rule.replace(" ", "");
			if (rules[key] === '') { delete rules[key]; }
		});
		
		// Generate text
		var text = '';
		$.each (rules, function(key, rule) {
		//	alert( that.location.coords.longitude );
			try {
				switch (rule) {
					case "address": text += that.data.street_number +' '+ that.data.route; break;
					case "street": text += that.data.route; break;
					case "street-long": text += that.data.route_long; break;
					case "city": text += that.data.locality; break;
					case "city-state": text += that.data.locality +delimiter+ that.data.administrative_area_level_1; break;
					case "city-state-zip": text += that.data.locality +delimiter+ that.data.administrative_area_level_1 + ' ' + that.data.postal_code; break;
					case "state": text += that.data.administrative_area_level_1; break;
					case "state-long": text += that.data.administrative_area_level_1_long; 
					break;
					case "zip": text += ( that.data.postal_code ? that.data.postal_code : '' ); break;
					case "county": text += that.data.administrative_area_level_2; break;
					case "country": text += that.data.country; break;
					case "country-long": 
						text += that.data.country_long; 
						text = text.toUpperCase();
					break;
					case "longitude": 
						text += that.location.coords.longitude; 
					break;
					case "latitude": 
						text += that.location.coords.latitude; 
					break;
				}
		//	alert( text );
				if (rules[(key+1)] !== undefined) { text += delimiter; }
			} catch (ignore) {}
		});

		return text;
	};

	return GeoText;
})(jQuery, google.maps);
