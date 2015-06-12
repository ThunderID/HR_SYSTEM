var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir.config.sourcemaps = false;
elixir(function(mix) {
	mix.less('app.less')
		.less('bootstrap/bootstrap.less', 'public/css/')    	
		.version('public/css/app.css')
		.copy('resources/assets/plugins/', 'public/plugins');
});