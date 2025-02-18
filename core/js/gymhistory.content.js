/** global: gymName */

$(function () {
	function htmlspecialchars_decode(string) {
		var escapeMap = {
			"&amp;": "&",
			"&lt;": "<",
			"&gt;": ">",
			"&quot;": "\"",
			"&#39;": "'",
			"&#039;": "'"
		};
		return String(string).replace(/&(amp|lt|gt|quot|#0?39);/gi, function (s) {
			return escapeMap[s] || s;
		});
	}

	$.getJSON("core/json/variables.json", function(variables) {
		var pokeimg_path = variables['system']['pokeimg_path'];
		var hide_cp_changes = variables['system']['gymhistory_hide_cp_changes'];

		$('.topShaverLoader').hide();
		$('.gymShaverLoader').hide();
		$('.gymLoader').hide();

		var pageShaver = 0;
		var page = 0;
		var teamSelector = ''; //''=all; 0=neutral; 1=Blue; 2=Red; 3=Yellow
		var rankingFilter = 0; //0=Level & Gyms; 1=Level; 2=Gyms
		var gymHistoryLoaded = false;
		var topShaverLoaded = false;
		var gymShaverLoaded = false;

		$('input#name').filter(':visible').val(htmlspecialchars_decode(gymName));

		var gymLoader = function(pagination, stayOnPage) {
			if (!gymHistoryLoaded || pagination) {
				loadGyms(page, $('input#name').filter(':visible').val(), teamSelector, rankingFilter, pokeimg_path, hide_cp_changes, stayOnPage);
				gymHistoryLoaded = true;
				page++;
			}
		}

		var gymShaverLoader = function(pagination) {
			if (!gymShaverLoaded || pagination) {
				loadGymShaver(pageShaver, $('input#name').filter(':visible').val(), teamSelector, rankingFilter, pokeimg_path);
				gymShaverLoaded = true;
				pageShaver++;
			}
		}

		var topShaverLoader = function(pagination) {
			if (!topShaverLoaded || pagination) {
				loadTopShaver();
				topShaverLoaded = true;
			}
		}

		$('#loadMoreButton').click(function () {
			gymLoader(true, false);
		});

		$('#loadMoreButtonShaver').click(function () {
			gymShaverLoader(true);
		});

		$('a[href="#gymHistory"]').click(function() {
			gymLoader(false, false);
		}).trigger('click');

		$('a[href="#topShaver"]').click(function() {
			topShaverLoader(false);
		});

		$('a[href="#gymShaver"]').click(function() {
			topShaverLoader(false);
			gymShaverLoader(false);
		});

		$('#searchGyms').submit(function ( event ) {
			pageShaver = 0;
			page = 0;
			gymHistoryLoaded = false;
			gymShaverLoaded = false;
			$('#gymsContainer').empty();
			$('#gymShaverContainer').empty();
			if ($('a[href="#gymHistory"]').parent().hasClass('active')) {
				gymLoader(false, true);
			} else if ($('a[href="#gymShaver"]').parent().hasClass('active')) {
				gymShaverLoader(false);
			}
			event.preventDefault();
		});

		$('.teamSelectorItems').click(function ( event ) {
			switch ($(this).attr('id')) {
				case 'NeutralTeamsFilter':
					teamSelector=0;
					break;
				case 'BlueTeamFilter':
					teamSelector=1;
					break;
				case 'RedTeamFilter':
					teamSelector=2;
					break;
				case 'YellowFilter':
					teamSelector=3;
					break;
				default:
					teamSelector='';
			}
			$('#teamSelectorText').html($(this).html());
			event.preventDefault();
			$('#searchGyms').submit();
		});

		$('.rankingOrderItems').click(function ( event ) {
			switch ($(this).attr('id')) {
				case 'changedFirst':
					rankingFilter=0;
					break;
				case 'nameFirst':
					rankingFilter=1;
					break;
				case 'totalcpFirst':
					rankingFilter=2;
					break;
				default:
					rankingFilter=0;
			}
			$('#rankingOrderText').html($(this).html());
			event.preventDefault();
			$('#searchGyms').submit();
		});

		window.onpopstate = function() {
			if (window.history.state && 'gymhistory' === window.history.state.page) {
				$('input#name').filter(':visible').val(window.history.state.name);
				page = 0;
				pageShaver = 0;
				gymHistoryLoaded = false;
				gymShaverLoaded = false;
				$('#gymsContainer').empty();
				$('#gymShaverContainer').empty();
				if ($('a[href="#gymHistory"]').parent().hasClass('active')) {
					gymLoader(false, false);
				} else if ($('a[href="#gymShaver"]').parent().hasClass('active')) {
					gymShaverLoader(false);
				}
			} else {
				window.history.back();
			}
		};
	});
});

function loadTopShaver() {
	$('.topShaverLoader').show();
	$.ajax({
		'type': 'GET',
		'global': false,
		'dataType': 'json',
		'url': 'core/process/aru.php',
		'data': {
			'type' : 'gymshaver_count'
		}
	}).done(function (data) {
		if (data.stats) {
			var statsTable = $('#gymShaverStatsTable');
			statsTable.find('.count-day').html(data.stats.day);
			statsTable.find('.count-week').html(data.stats.week);
			statsTable.find('.count-total').html(data.stats.total);
		}
		var lastCount = 0;
		var place = 0;
		$.each(data.shavers, function (idx, entry) {
			if (lastCount != entry.count) {
				place++;
				printTopShaver(place, entry, data.locale);
			} else {
				printTopShaver('', entry, data.locale);
			}
			lastCount = parseInt(entry.count);
		});
		$('.topShaverLoader').hide();
	});
}

function loadGymShaver(page, name, teamSelector, rankingFilter, pokeimg_path) {
	$('.gymShaverLoader').show();
	$.ajax({
		'type': 'GET',
		'global': false,
		'dataType': 'json',
		'url': 'core/process/aru.php',
		'data': {
			'type' : 'gymshaver',
			'page' : page,
			'name' : name,
			'team' : teamSelector,
			'ranking' :rankingFilter
		}
	}).done(function (data) {
		var internalIndex = 0;
		$.each(data.entries, function (idx, entry) {
			internalIndex++
			printGymShaver(entry, pokeimg_path, data.locale);
		});
		if (internalIndex < 5) {
			$('#loadMoreButtonShaver').hide();
		} else {
			$('#loadMoreButtonShaver').removeClass('hidden').show();
		}
		$('.gymShaverLoader').hide();
	});
}

function loadGyms(page, name, teamSelector, rankingFilter, pokeimg_path, hide_cp_changes, stayOnPage) {
	$('.gymLoader').show();
	if (stayOnPage) {
		// build a state for this name
		var state = {name: name, page: 'gymhistory'};
		window.history.pushState(state, 'gymhistory', 'gymhistory?name=' + name);
	}
	$.ajax({
		'type': 'GET',
		'global': false,
		'dataType': 'json',
		'url': 'core/process/aru.php',
		'data': {
			'type' : 'gyms',
			'page' : page,
			'name' : name,
			'team' : teamSelector,
			'ranking' :rankingFilter
		}
	}).done(function (data) {
		var internalIndex = 0;
		$.each(data.gyms, function (idx, gym) {
			internalIndex++
			printGym(gym, pokeimg_path, hide_cp_changes);
		});
		if (internalIndex < 10) {
			$('#loadMoreButton').hide();
		} else {
			$('#loadMoreButton').removeClass('hidden').show();
		}
		$('.gymLoader').hide();
	});
}

function loadGymHistory(page, gym_id, pokeimg_path, hide_cp_changes) {
	$('#gymHistory_'+gym_id).addClass('active').show();
	$('#gymHistory_'+gym_id).find('.gymHistoryLoader').show();
	$.ajax({
		'type': 'GET',
		'global': false,
		'dataType': 'json',
		'url': 'core/process/aru.php',
		'data': {
			'type' : 'gymhistory',
			'page' : page,
			'gym_id' : gym_id
		}
	}).done(function (data) {
		var internalIndex = 0;
		$.each(data.entries, function(idx, entry) {
			internalIndex++
			if (entry.only_cp_changed && hide_cp_changes) return;
			printGymHistory(gym_id, entry, pokeimg_path);
		});
		if (data.last_page == true) {
			$('#gymHistory_'+gym_id).find('.loadMoreButtonHistory').hide();
		} else {
			$('#gymHistory_'+gym_id).find('.loadMoreButtonHistory').removeClass('hidden').data('page', page+1).show();
		}
		$('#gymHistory_'+gym_id).find('.gymHistoryLoader').hide();
	});
}

function printPokemonList(pokemons, pokeimg_path) {
	var gymPokemon = $('<ul>',{class: 'list-inline'});
	$.each(pokemons, function(idx, pokemon) {
		var list = $('<li>', {class: pokemon.class});
		list.append($('<a>', { class: 'no-link', href : 'pokemon/'+pokemon.pokemon_id }).append($('<img />', { src: pokeimg_path.replace('{pokeid}', pokemon.pokemon_id) }).css('height', '2em')));
		list.append($('<br><span class="small">'+pokemon.cp+' CP</span>'));
		list.append($('<br><span style="font-size:70%"><a href="trainer?name='+pokemon.trainer_name+'" class="no-link">'+pokemon.trainer_name+'</a></span>'));
		gymPokemon.append(list);
	});
	return gymPokemon;
}

function printGymHistory(gym_id, entry, pokeimg_path) {
	var gymHistory = $('<tr>').css('border-bottom', '2px solid '+(entry.team_id=='3'?'#ffbe08':entry.team_id=='2'?'#ff7676':entry.team_id=='1'?'#00aaff':'#ddd'));
	gymHistory.append($('<td>',{text: entry.last_modified}));
	gymHistory.append($('<td>',{text: entry.pokemon_count, class: 'level'}).prepend($('<img />', {src:'core/img/map_'+(entry.team_id=='1'?'blue':entry.team_id=='2'?'red':entry.team_id=='3'?'yellow':'white')+'.png'})));
	gymHistory.append($('<td>',{text: parseInt(entry.total_cp).toLocaleString('de-DE'), class: entry.class}).append(
		entry.total_cp_diff !== 0 ? $('<span class="small"> ('+(entry.total_cp_diff > 0 ? '+' : '')+entry.total_cp_diff+')</span>') : null
	));
	var gymPokemon = printPokemonList(entry.pokemon, pokeimg_path);
	gymHistory.append($('<td>').append(gymPokemon));
	$('#gymHistory_'+gym_id).find('tbody').append(gymHistory);
}

function hideGymHistoryTables(gymHistoryTables) {
	gymHistoryTables.removeClass('active').hide();
	gymHistoryTables.find('tbody tr').remove();
	gymHistoryTables.find('.loadMoreButtonHistory').hide();
	gymHistoryTables.find('.gymHistoryLoader').hide();
}

function printGym(gym, pokeimg_path, hide_cp_changes) {
	var gymsInfos = $('<tr>',{id: 'gymInfos_'+gym.gym_id}).css('cursor', 'pointer').css('border-bottom', '2px solid '+(gym.team_id=='3'?'#ffbe08':gym.team_id=='2'?'#ff7676':gym.team_id=='1'?'#00aaff':'#ddd')).click(function() {
		if (!$('#gymHistory_'+gym.gym_id).hasClass('active')) {
			hideGymHistoryTables($('#gymsContainer').find('.gymhistory'));
			loadGymHistory(0, gym.gym_id, pokeimg_path, hide_cp_changes);
		} else {
			hideGymHistoryTables($('#gymHistory_'+gym.gym_id));
		}
	});
	gymsInfos.append($('<td>',{text: gym.last_modified}));
	if (gym.name.length > 50) { gym.name = gym.name.substr(0, 50) + '…'; }
	gymsInfos.append($('<td>',{text: gym.name}));
	gymsInfos.append($('<td>',{text: gym.pokemon_count, class: 'level'}).prepend($('<img />', {src:'core/img/map_'+(gym.team_id=='1'?'blue':gym.team_id=='2'?'red':gym.team_id=='3'?'yellow':'white')+'.png'})));
	gymsInfos.append($('<td>',{text: parseInt(gym.total_cp).toLocaleString('de-DE')}));
	var gymPokemon = printPokemonList(gym.pokemon, pokeimg_path);
	gymsInfos.append($('<td>').append(gymPokemon));
	$('#gymsContainer').append(gymsInfos);
	var historyTable = $('<table>',{class: 'table'});
	historyTable.append('<thead><tr><th style="min-width:7em">Time</th><th>Level</th><th>Total CP</th><th>Pokémon</th></tr></thead>');
	historyTable.append('<tbody></tbody>');
	historyTable.append('<tfoot><tr class="loadMore text-center"><td colspan="4"><button class="loadMoreButtonHistory btn btn-default btn-sm hidden">Load more</button></td></tr><tr class="gymHistoryLoader"><td colspan="4"><div class="loader"></div></td></tr></tfoot>');
	historyTable.find('.loadMoreButtonHistory').data('page', 0).click(function() {
		loadGymHistory($(this).data('page'), gym.gym_id, pokeimg_path, hide_cp_changes);
	});
	var row = $('<td>',{colspan: 6});
	row.append(historyTable);
	$('#gymsContainer').append($('<tr>', {id: 'gymHistory_'+gym.gym_id, class: 'gymhistory'}).hide().append(row));
}


function printGymShaver(gym, pokeimg_path, locale) {
	var gymsInfos = $('<tr>',{id: 'gymInfos_'+gym.gym_id}).css('border-bottom', '2px solid '+(gym.team_id=='3'?'#ffbe08':gym.team_id=='2'?'#ff7676':gym.team_id=='1'?'#00aaff':'#ddd'));
	gymsInfos.append($('<td>').append(gym.last_modified_end + '<br>' + gym.last_modified_start));
	var gymNameShort = gym.name.length > 50 ? gym.name.substr(0, 50) + '…' : gym.name;
	gymsInfos.append($('<td>').append($('<a>', {class: 'no-link', href: 'gymhistory?name='+gym.name, text: gymNameShort})));
	gymsInfos.append($('<td>',{text: gym.pokemon_count_end, class: 'level'}).prepend($('<img />', {src:'core/img/map_'+(gym.team_id=='1'?'blue':gym.team_id=='2'?'red':gym.team_id=='3'?'yellow':'white')+'.png'})));
	gymsInfos.append($('<td>',{text: parseInt(gym.total_cp_end).toLocaleString('de-DE'), class: gym.class}).append(
		gym.total_cp_diff !== 0 ? $('<span class="small"> ('+(gym.total_cp_diff > 0 ? '+' : '')+gym.total_cp_diff+')</span>') : null
	));
	var gymPokemon = printPokemonList(gym.pokemon, pokeimg_path, true);
	gymsInfos.append($('<td>').append(gymPokemon));
	$('#gymShaverContainer').append(gymsInfos);
}

function printTopShaver(place, entry, locale) {
	var shaver = $('<tr>').css('border-bottom', '2px solid '+(entry.team=='3'?'#ffbe08':entry.team=='2'?'#ff7676':entry.team=='1'?'#00aaff':'#ddd'));
	shaver.append($('<td>',{text: place}));
	shaver.append($('<td>').append($('<a>', {class: 'no-link', href: 'trainer?name='+entry.name, text: entry.name})));
	shaver.append($('<td>').append($('<img />', {src:'core/img/map_'+(entry.team=='1'?'blue':entry.team=='2'?'red':entry.team=='3'?'yellow':'white')+'.png'})));
	shaver.append($('<td>',{text: entry.level, class: 'level'}));
	shaver.append($('<td>',{text: parseInt(entry.count)}));
	$('#topShaverContainer').append(shaver);
}
