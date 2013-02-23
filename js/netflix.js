
function NetFlix () {

}

NetFlix._BASE_URL		= 'http://odata.netflix.com/';

NetFlix.prototype = {
	catalog: {
		titles: {
			substring: function (term, callback) {
				url = NetFlix._BASE_URL + 'Catalog/Titles?' +
					'$filter=substringof(\'' + term + '\',Name)&' +
					'$callback=callback&' + 
					'$format=json';

					console.log(url);

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
		} 
	}


};