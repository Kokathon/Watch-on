
function NetFlix () {

}

NetFlix._BASE_URL		= 'http://odata.netflix.com/v2/';

NetFlix.prototype = {

	findMovies: function (titel, callback) {
		var jsonpCallback = 'jQueryCallback' + (new Date).getTime();

		var url = NetFlix._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + titel + '\',Name) and Type eq \'Movie\'&' +
			'$callback=' + jsonpCallback + '&' +
			'$select=Name&' +
			'$inlinecount=allpages&$top=50&' +
			'$format=json';

		var success = function (data) {

			var movies = [];

			$.each(data.d.results, function(index, movie) {
				movies.push({
					service: 'netflix',
					title: movie.Name,
					type: 'movie'
				});
			});

			callback.call(null, movies);
		}

		$.ajax({
			dataType: 'jsonp',
			url: url,
			jsonp: false,
			jsonpCallback: jsonpCallback,
			success: success
		});

	},

	findSeries: function (titel, callback) {
		var jsonpCallback = 'jQueryCallback' + (new Date).getTime();

		var url = NetFlix._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + titel + '\',Name) and Type eq \'Series\'&' +
			'$callback=' + jsonpCallback + '&' +
			'$select=Name&' +
			'$inlinecount=allpages&$top=50&' +
			'$format=json';

		var success = function (data) {

			var series = [];

			$.each(data.d.results, function(index, serie) {
				series.push({
					service: 'netflix',
					title: serie.Name,
					type: 'tv'
				});
			});

			callback.call(null, series);
		}

		$.ajax({
			dataType: 'jsonp',
			url: url,
			jsonp: false,
			jsonpCallback: jsonpCallback,
			success: success
		});

	},

	findAll: function (titel, callback) {

		var jsonpCallback = 'jQueryCallback' + (new Date).getTime();

		var url = NetFlix._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + titel + '\',Name) and (Type eq \'Series\' or Type eq \'Movie\')&' +
			'$callback=' + jsonpCallback + '&' +
			'$select=Name,Type&' +
			'$inlinecount=allpages&$top=50&' +
			'$format=json';

		var success = function (data) {

			var items = [];

			$.each(data.d.results, function(index, item) {
				items.push({
					service: 'netflix',
					title: item.Name,
					type: (item.Type == 'Movie' ? 'movie':'tv')
				});
			});

			callback.call(null, items);
		}

		$.ajax({
			dataType: 'jsonp',
			url: url,
			jsonp: false,
			jsonpCallback: jsonpCallback,
			success: success
		});

	}


};