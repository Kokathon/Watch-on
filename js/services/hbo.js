
function hbo () {
	this._BASE_URL		= 'http://kokarn.com/kokathon/repos/Watch-on/search.php';
	this._SERVICE_NAME 	= 'hbo';
}

hbo.prototype = {

	findMovies: function (term, callback) {

		var data = {
			service: this._SERVICE_NAME,
			term: term,
			find: 'movie'
		}

		$.ajax({
			dataType: 'jsonp',
			data: data,
			url: this._BASE_URL,
			success: callback
		});
	},

	findTv: function (term, callback) {
		var data = {
			service: this._SERVICE_NAME,
			term: term,
			find: 'tv'
		}

		$.ajax({
			dataType: 'jsonp',
			data: data,
			url: this._BASE_URL,
			success: callback
		});
	},

	findAll: function (term, callback) {
		var data = {
			service: this._SERVICE_NAME,
			term: term,
			find: 'all'
		}

		$.ajax({
			dataType: 'jsonp',
			data: data,
			url: this._BASE_URL,
			success: callback
		});
	} 



}