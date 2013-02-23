
function NetFlix () {

}

NetFlix._BASE_URL		= 'http://odata.netflix.com/';

NetFlix.prototype = {

	findMovies: function (titel, callback) {
		url = NetFlix._BASE_URL + 'Catalog/Titles?' +
			'$filter=substringof(\'' + titel + '\',Name) and Type eq \'Movie\'&' +
			'$callback=callback&' + 
			'$format=json';

		var success = function (data) {
			callback.call(null, data.d.results);
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
			'$format=json';

		var success = function (data) {
			callback.call(null, data.d.results);
		}

		$.ajax({
			dataType: 'jsonp',
			url: url,
			jsonpCallback: 'callback',
			success: success
		});

	}


};