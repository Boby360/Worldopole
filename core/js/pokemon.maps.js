/** global: google */
/** global: pokemon_id */
/** global: navigator */
/** global: MarkerClusterer */
var map, heatmap;
var pokemonMarkers = {};
var updateLiveTimeout;
var markerCluster = false;

var ivMin = 80;
var ivMax = 100;

function initMap() {
	var geoOpts = {
		'type': 'GET',
		'global': false,
		'dataType': 'json',
		'url': 'core/process/aru.php',
		'data': {
			'type': 'maps_localization_coordinates'
		}
	}
	$.when($.getJSON('core/json/variables.json'), $.ajax(geoOpts)).then(function(response1, response2) {
		var variables = response1[0];
		var coordinates = response2[0];
		var latitude = Number(variables['system']['map_center_lat']);
		var longitude = Number(variables['system']['map_center_long']);
		var zoom_level = Number(variables['system']['zoom_level']);
		var pokeimg_path = variables['system']['pokeimg_path'];
		var cluster = variables.system.cluster_pokemon;

		map = new google.maps.Map(document.getElementById('map'), {
			center: {
				lat: latitude,
				lng: longitude
			},
			zoom: zoom_level,
			zoomControl: true,
			scaleControl: false,
			scrollwheel: true,
			disableDoubleClickZoom: false,
			streetViewControl: false,
			mapTypeControlOptions: {
				mapTypeIds: [
					google.maps.MapTypeId.ROADMAP,
					'pogo_style',
					'dark_style',
				]
			}
		});

		$.getJSON('core/json/pogostyle.json', function(data) {
			var styledMap_pogo = new google.maps.StyledMapType(data, { name: 'PoGo' });
			map.mapTypes.set('pogo_style', styledMap_pogo);
		});
		$.getJSON('core/json/darkstyle.json', function(data) {
			var styledMap_dark = new google.maps.StyledMapType(data, { name: 'Dark' });
			map.mapTypes.set('dark_style', styledMap_dark);
		});
		$.getJSON('core/json/defaultstyle.json', function(data) {
			map.set('styles', data);
		});

		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};

				if (position.coords.latitude <= coordinates.max_latitude && position.coords.latitude >= coordinates.min_latitude) {
					if (position.coords.longitude <= coordinates.max_longitude && position.coords.longitude >= coordinates.min_longitude) {
						map.setCenter(pos);
					}
				}
			});
		}

		if (cluster) {
			var clusterOptions = {
				cssClass: 'pokedexCluster',
				gridSize: cluster.grid || 60,
				minimumClusterSize: cluster.minCluster || 3,
			}
			markerCluster = new MarkerClusterer(map, [], clusterOptions);
			markerCluster.setCalculator(function(markers) {
				var index = 1;
				var len = markers.length;
				if (len > 7 && len < 15) {
					index = 2;
				} else if (len >= 15) {
					index = 3;
				}
				return {
					text: len,
					index: index
				}
			});
		}
		initHeatmap();
		initSelector(pokeimg_path);
	});
}

function initSelector(pokeimg_path) {
	$('#heatmapSelector').click(function() {
		hideLive();
		showHeatmap();
		$('#heatmapSelector').addClass('active');
		$('#liveSelector').removeClass('active');
	});
	$('#liveSelector').click(function() {
		hideHeatmap();
		map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
		initLive(pokeimg_path);
		$('#liveSelector').addClass('active');
		$('#heatmapSelector').removeClass('active');
	});
}

function initLive(pokeimg_path) {
	showLive();
	$('#liveFilterSelector').rangeSlider({
		bounds: {
			min: 0,
			max: 100
		},
		defaultValues: {
			min: ivMin,
			max: ivMax
		},
		formatter: function(val) {
			return 'IV: ' + Math.round(val) + '%';
		}
	});

	$('#liveFilterSelector').bind('valuesChanged', function(e, data) {
		clearTimeout(updateLiveTimeout);
		removePokemonMarkerByIv(data.values.min, data.values.max);
		ivMin = data.values.min;
		ivMax = data.values.max;
		updateLive(pokeimg_path);
	});
	updateLive(pokeimg_path);
}

function initHeatmap() {
	$.ajax({
		'type': 'GET',
		'global': false,
		'dataType': 'json',
		'url': 'core/process/aru.php',
		'data': {
			'type': 'pokemon_slider_init'
		}
	}).done(function(bounds) {
		initHeatmapData(bounds);
	});
}

function initHeatmapData(bounds) {
	var boundMin = new Date(bounds.min.replace(/-/g, '/'));
	var boundMax = new Date(bounds.max.replace(/-/g, '/'));
	var selectorMax = boundMax;
	var selectorMin = boundMin;

	// two weeks in millisec
	var twoWeeks = 12096e5;
	var maxMinus2Weeks = new Date(selectorMax.getTime() - twoWeeks);
	if (selectorMin < maxMinus2Weeks) {
		selectorMin = maxMinus2Weeks;
	}

	// dict with millisec => migration nr.
	var migrations = {};
	// start at 4 because 06. Oct 2016 was the 4th migration
	var migr_nr = 4;
	$('#timeSelector').dateRangeSlider({
		bounds: {
			min: boundMin,
			max: boundMax
		},
		defaultValues: {
			min: selectorMin,
			max: selectorMax
		},
		scales: [{
			first: function(value) {
				// 06. Oct 2016 (4th migration). 2 week schedule starts with this migration
				var migrationStart = new Date('2016-10-06T00:00:00Z');
				var now = new Date();
				var result = new Date();
				for (var migration = migrationStart; migration <= now; migration.setTime(migration.getTime() + twoWeeks)) {
					if (migration >= value) {
						result = migration;
						migrations[result.getTime()] = migr_nr;
						break;
					}
					migr_nr++;
				}
				return result;
			},
			next: function(value) {
				var next = new Date(new Date(value).setTime(value.getTime() + twoWeeks));
				migr_nr++;
				migrations[next.getTime()] = migr_nr;
				return next;
			},
			label: function(value) {
				if (isMobileDevice() && isTouchDevice()) {
					return '#' + migrations[value.getTime()];
				}
				return 'Migration #' + migrations[value.getTime()];
			},
		}]
	});
	createHeatmap();
}

function createHeatmap() {

	heatmap = new google.maps.visualization.HeatmapLayer({
		data: [],
		map: map
	});

	var gradient = [
		'rgba(0, 255, 255, 0)',
		'rgba(0, 255, 255, 1)',
		'rgba(0, 191, 255, 1)',
		'rgba(0, 127, 255, 1)',
		'rgba(0, 63, 255, 1)',
		'rgba(0, 0, 255, 1)',
		'rgba(0, 0, 223, 1)',
		'rgba(0, 0, 191, 1)',
		'rgba(0, 0, 159, 1)',
		'rgba(0, 0, 127, 1)',
		'rgba(63, 0, 91, 1)',
		'rgba(127, 0, 63, 1)',
		'rgba(191, 0, 31, 1)',
		'rgba(255, 0, 0, 1)'
	];
	heatmap.set('gradient', gradient);
	heatmap.setMap(map);
	$('#timeSelector').bind('valuesChanged', function() { updateHeatmap() });
	$('#timeSelector').dateRangeSlider('min'); // will trigger valuesChanged
}

function updateHeatmap() {
	var dateMin = $('#timeSelector').dateRangeSlider('min');
	var dateMax = $('#timeSelector').dateRangeSlider('max');
	$('#loaderContainer').show();
	$.ajax({
		'type': 'GET',
		'global': false,
		'dataType': 'json',
		'url': 'core/process/aru.php',
		'data': {
			'type': 'pokemon_heatmap_points',
			'pokemon_id': pokemon_id,
			'start': Math.floor(dateMin.getTime() / 1000),
			'end': Math.floor(dateMax.getTime() / 1000)
		}
	}).done(function(points) {
		var googlePoints = [];
		for (var i = 0; i < points.length; i++) {
			googlePoints.push(new google.maps.LatLng(points[i].latitude, points[i].longitude))
		}
		var newPoints = new google.maps.MVCArray(googlePoints);
		heatmap.set('data', newPoints);
		$('#loaderContainer').hide();
	});
}

function hideHeatmap() {
	$('#timeFilterContainer').hide();
	heatmap.set('map', null);
}

function showHeatmap() {
	$('#timeFilterContainer').show();
	heatmap.set('map', map);
	hideLive();
}

function hideLive() {
	$('#liveFilterContainer').hide();
	clearTimeout(updateLiveTimeout);
	clearPokemonMarkers();
}

function showLive() {
	hideHeatmap();
	clearTimeout(updateLiveTimeout);
	$('#liveFilterContainer').show();

}

function updateLive(pokeimg_path) {
	$.ajax({
		'type': 'POST',
		'global': false,
		'dataType': 'json',
		'url': 'core/process/aru.php',
		'data': {
			'type': 'pokemon_live',
			'pokemon_id': pokemon_id,
			'inmap_pokemons': extractEncountersId(),
			'ivMin': ivMin,
			'ivMax': ivMax
		}
	}).done(function(pokemons) {
		var markers = [];
		for (var i = 0; i < pokemons.points.length; i++) {
			var marker = addPokemonMarker(pokemons.points[i], pokeimg_path, pokemons.locale);
			if (markerCluster) {
				markers.push(marker);
			} else {
				marker.setMap(map);
			}
		}
		if (markerCluster) {
			markerCluster.addMarkers(markers);
		}
		updateLiveTimeout = setTimeout(function() { updateLive(pokeimg_path) }, 5000);
	});
}

function addPokemonMarker(pokemon, pokeimg_path, locale) {
	var image = {
		url: pokeimg_path.replace('{pokeid}', pokemon.pokemon_id),
		scaledSize: new google.maps.Size(32, 32),
		origin: new google.maps.Point(0, 0),
		anchor: new google.maps.Point(16, 16),
		labelOrigin: new google.maps.Point(16, 36)
	};
	var encountered = false;
	var ivPercent = 100;
	if (pokemon.individual_attack !== null) {
		encountered = true;
		ivPercent = ((100 / 45) * (parseInt(pokemon.individual_attack) + parseInt(pokemon.individual_defense) + parseInt(pokemon.individual_stamina))).toFixed(2);
	}
	var marker = new google.maps.Marker({
		position: { lat: parseFloat(pokemon.latitude), lng: parseFloat(pokemon.longitude) },
		icon: image,
		ivPercent: ivPercent
	});
	if (encountered) {
		marker.setLabel(getMarkerLabel(ivPercent));
	}

	var infoWindow = new google.maps.InfoWindow({
		content: getPokemonContent(pokemon, locale, encountered, ivPercent),
		disableAutoPan: true
	});
	infoWindow.isClickOpen = false;

	marker.addListener('click', function() {
		infoWindow.isClickOpen = true;
		infoWindow.open(map, this);
	});
	google.maps.event.addListener(infoWindow, 'closeclick', function() {
		this.isClickOpen = false;
	});
	marker.addListener('mouseover', function() {
		infoWindow.open(map, this);
	});

	// assuming you also want to hide the infowindow when user mouses-out
	marker.addListener('mouseout', function() {
		if (infoWindow.isClickOpen === false) {
			infoWindow.close();
		}
	});
	pokemonMarkers[pokemon.encounter_id] = marker;
	var now = new Date().getTime();
	var endTime = new Date(pokemon.disappear_time_real.replace(/-/g, '/')).getTime();

	setTimeout(function() { removePokemonMarkerByEncounter(pokemon.encounter_id) }, endTime - now);
	return marker;
}

function getMarkerLabel(ivPercent) {
	if (ivPercent < 80) {
		return null;
	}
	var ivColor = 'rgba(0, 0, 255, 0.70)';
	if (ivPercent > 90) {
		ivColor = 'rgba(246, 178, 107, 0.90)';
	}
	if (ivPercent > 99) {
		ivColor = 'rgba(255, 0, 0, 1)';
	}
	return {
		color: ivColor,
		text: ivPercent + '%'
	};
}

function getPokemonContent(pokemon, locale, encountered, ivPercent) {
	var contentString = '<div>' +
		'<h4> ' + pokemon.name + ' #' + pokemon.pokemon_id + (encountered ? ' IV: ' + ivPercent + '% ' : '') + '</h4>' +
		'<div id="bodyContent">' +
		'<p class="disappear_time_display text-center">' + pokemon.disappear_time_real + '<span class="disappear_time_display_timeleft"></span></p>';
	if (encountered) {
		contentString +=
			'<p></p>' +
			'<div class="progress" style="height: 6px; width: 120px; margin-bottom: 10px; margin-top: 2px; margin-left: auto; margin-right: auto">' +
			'<div title="' + locale.ivAttack + ': ' + pokemon.individual_attack + '" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="' + pokemon.individual_attack + '" aria-valuemin="0" aria-valuemax="45" style="width: ' + (((100 / 15) * pokemon.individual_attack) / 3) + '%">' +
			'<span class="sr-only">' + locale.ivAttack + ': ' + pokemon.individual_attack + '</span>' +
			'</div>' +
			'<div title="' + locale.ivDefense + ': ' + pokemon.individual_defense + '" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="' + pokemon.individual_defense + '" aria-valuemin="0" aria-valuemax="45" style="width: ' + (((100 / 15) * pokemon.individual_defense) / 3) + '%">' +
			'<span class="sr-only">' + locale.ivDefense + ': ' + pokemon.individual_defense + '</span>' +
			'</div>' +
			'<div title="' + locale.ivStamina + ': ' + pokemon.individual_stamina + '" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' + pokemon.individual_stamina + '" aria-valuemin="0" aria-valuemax="45" style="width: ' + (((100 / 15) * pokemon.individual_stamina) / 3) + '%">' +
			'<span class="sr-only">' + locale.ivStamina + ': ' + pokemon.individual_stamina + '</span>' +
			'</div>' +
			'</div>' +
			'<p class="text-center">(' + pokemon.individual_attack + "/" + pokemon.individual_defense + "/" + pokemon.individual_stamina + ')</p>' +
			'<p class="text-center">' + pokemon.quick_move + "/" + pokemon.charge_move + '</p>';
	}
	contentString += '</div>';
	return contentString;
}

function clearPokemonMarkers() {
	pokemonMakersEach(function(marker) {
		removePokemonMarker(marker);
	});
	pokemonMarkers = {};
}

function removePokemonMarkerByEncounter(encounter_id) {
	removePokemonMarker(pokemonMarkers[encounter_id]);
	delete pokemonMarkers[encounter_id];
}

function removePokemonMarker(marker) {
	if (markerCluster) {
		markerCluster.removeMarker(marker);
	} else {
		marker.setMap(null);
	}
}


function removePokemonMarkerByIv(ivMin, ivMax) {
	pokemonMakersEach(function(marker, key) {
		if (marker.ivPercent < ivMin || marker.ivPercent > ivMax) {
			removePokemonMarker(marker);
			delete pokemonMarkers[key];
		}
	});
}

function extractEncountersId() {
	var inmapEncounter = [];
	pokemonMakersEach(function(marker, key) {
		inmapEncounter.push(key);
	});
	return inmapEncounter;
}

function pokemonMakersEach(func) {
	for (var key in pokemonMarkers) {
		if (pokemonMarkers.hasOwnProperty(key)) {
			func(pokemonMarkers[key], key);
		}
	}
}

function isTouchDevice() {
	// Should cover most browsers
	return 'ontouchstart' in window || navigator.maxTouchPoints
}

function isMobileDevice() {
	// Basic mobile OS (not browser) detection
	return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
}