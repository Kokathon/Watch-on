
function NetFlix () {

}

NetFlix._BASE_URL		= 'http://odata.netflix.com/v2/';

NetFlix.prototype = {

	findMovies: function (titel, callback) {
		url = NetFlix._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + titel + '\',Name) and Type eq \'Movie\'&' +
			'$callback=callback&' + 
			'$select=Name&' +
			'$skip=0&top=50&' +
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
			jsonpCallback: 'callback',
			success: success
		});

	},

	findSeries: function (titel, callback) {
		url = NetFlix._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + titel + '\',Name) and Type eq \'Series\'&' +
			'$callback=callback&' + 
			'$select=Name&' +
			'$skip=0&top=50&' +
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
			jsonpCallback: 'callback',
			success: success
		});

	}


};