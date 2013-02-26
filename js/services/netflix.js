
function netflix () {
	this._BASE_URL		= 'http://odata.netflix.com/v2/';
	this._SERVICE_NAME 	= 'netflix';
}

netflix.prototype = {

	getJsonpCallback: function () {
		return 'jQueryCallback' + (new Date).getTime();
	},

	findMovies: function (term, callback) {
		var jsonpCallback = 'jQueryCallback' + (new Date).getTime();

		var url = this._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + term + '\',Name) and Type eq \'Movie\'&' +
			'$callback=' + jsonpCallback + '&' +
			'$select=Name,Url&' +
			'$inlinecount=allpages&$top=50&' +
			'$format=json';

		var success = function (data) {

			var movies = [];

			$.each(data.d.results, function(index, movie) {
				movies.push({
					service: 'netflix',
					title: movie.Name,
					type: 'movie',
					url: movie.Url
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

	findTv: function (term, callback) {
		var jsonpCallback = 'jQueryCallback' + (new Date).getTime();

		var url = this._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + term + '\',Name) and Type eq \'Season\'&' +
			'$callback=' + jsonpCallback + '&' +
			'$select=Name,Url&' +
			'$inlinecount=allpages&$top=50&' +
			'$format=json';

		var success = function (data) {

			var series = [];

			$.each(data.d.results, function(index, serie) {
				series.push({
					service: 'netflix',
					title: serie.Name,
					type: 'tv',
					url: serie.Url
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

	findAll: function (term, callback) {

		var jsonpCallback = 'jQueryCallback' + (new Date).getTime();

		var url = this._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + term + '\',Name) and (Type eq \'Season\' or Type eq \'Movie\')&' +
			'$callback=' + jsonpCallback + '&' +
			'$select=Name,Type,Url&' +
			'$inlinecount=allpages&$top=50&' +
			'$format=json';

		var success = function (data) {

			var items = [];

			$.each(data.d.results, function(index, item) {
				items.push({
					service: 'netflix',
					title: item.Name,
					type: (item.Type == 'Movie' ? 'movie':'tv'),
					url: item.Url
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