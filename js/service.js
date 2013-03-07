Function.prototype.method = function (name, func) {
    this.prototype[name] = func;
    return this;
};

function Service( name ){
    this.setServiceName( name );
    this._BASE_URL = 'http://varkanjag.se/search.php';
}

Service.method( 'setServiceName', function( value ){
    this._SERVICE_NAME = value;
    return this;
});

Service.method( 'findMovies', function( term, callback ){
    var data = {
        service: this._SERVICE_NAME,
        term: term,
        find: 'movie'
    };

    $.ajax({
        dataType: 'jsonp',
        data: data,
        url: this._BASE_URL,
        success: callback
    });
});

Service.method( 'findTv', function (term, callback) {
    var data = {
        service: this._SERVICE_NAME,
        term: term,
        find: 'tv'
    };

    $.ajax({
        dataType: 'jsonp',
        data: data,
        url: this._BASE_URL,
        success: callback
    });
});

Service.method( 'findAll', function (term, callback) {
    var data = {
        service: this._SERVICE_NAME,
        term: term,
        find: 'all'
    };

    $.ajax({
        dataType: 'jsonp',
        data: data,
        url: this._BASE_URL,
        success: callback
    });
});
