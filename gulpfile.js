var gulp = require('gulp');
var runSequence = require('run-sequence');
var gulp_util = require('gulp-util');
var sass = require('gulp-sass');
var css_minify = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var ngAnnotate = require('gulp-ng-annotate');
var sourcemaps = require('gulp-sourcemaps');
var babel = require('gulp-babel');
var concat = require('gulp-concat');
var eslint = require('gulp-eslint');

var template_cache = require('gulp-angular-templatecache');

var watchify = require('watchify');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var assign = require('lodash.assign');

var flo = require('fb-flo');
var fs = require('fs');

var shell = require('gulp-shell');

inlineCss = require('gulp-inline-css');

var config = {
	app_url: 'http://local.hobojet.com/'
};

var sassAdmin = function(){
	return gulp.src('resources/assets/sass/admin.scss')
		.pipe(sass.sync().on('error', sass.logError))
		.pipe(gulp.dest('public/css/'));
}
gulp.task('sass:admin', function () {

	sassAdmin();
	gulp.watch('resources/assets/sass/**/*.scss', function() {
		sassAdmin();
	});

});
gulp.task('build:sass:admin', sassAdmin);

var sassApp = function(){
	return gulp.src('resources/assets/sass/app.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('public/css/'));
}
gulp.task('sass:app', function () {


	sassApp();
	gulp.watch('resources/assets/sass/**/*.scss', function() {
		sassApp();
	});

});
gulp.task('build:sass:app', sassApp);

gulp.task('css:admin:vendor', function(){
	return gulp.src([
			'resources/assets/bower_components/angular-motion/dist/angular-motion.css',
			'resources/assets/bower_components/ui-select/dist/select.min.css',
			'resources/assets/sass/admin/theme/_vendor/angular-ui-select.css',
			'resources/assets/sass/admin/theme/_vendor/ripples.css',
			'resources/assets/sass/admin/theme/_vendor/helpers.css'
		])
		.pipe(concat('admin_vendor.css'))
		.pipe(css_minify())
		.pipe(gulp.dest('public/css/'))
})


gulp.task('js:app', function(){

	var customOpts = {
		entries: ['resources/assets/app/js/App.js'],
		transform: [
			"babelify"
		],
		debug: true
	};
	var opts = assign({}, watchify.args, customOpts);
	var b = watchify(browserify(opts));

	function bundle() {
		return b.bundle()
				.on('error', gulp_util.log.bind(gulp_util, 'Browserify Error'))
				.pipe(source('App.js'))
				.pipe(buffer())
				.pipe(sourcemaps.init({loadMaps: true}))
				.pipe(sourcemaps.write('./'))
				.pipe(gulp.dest('public/js/app'));
	}
	b.on('update', bundle);
	bundle();

});

gulp.task('js:admin', function(){

	var customOpts = {
		entries: ['resources/assets/app/js/admin/Admin.js'],
		transform: [
			"babelify"
		],
		debug: true
	};
	var opts = assign({}, watchify.args, customOpts);
	var b = watchify(browserify(opts));

	function bundle() {
		return b.bundle()
				.on('error', gulp_util.log.bind(gulp_util, 'Browserify Error'))
				.pipe(source('Admin.js'))
				.pipe(buffer())
				.pipe(sourcemaps.init({loadMaps: true}))
				.pipe(sourcemaps.write('./'))
				.pipe(gulp.dest('public/js/app'));
	}
	b.on('update', bundle);
	bundle();

});

var templatesAdmin = function(){
	return gulp.src('resources/assets/app/js/admin/templates/**/*.html')
		.pipe(template_cache('templates.admin.js', {standalone: true}))
		.pipe(gulp.dest('public/js/app'));
}

gulp.task('templates:admin', function(){


	templatesAdmin();
	gulp.watch('resources/assets/app/js/admin/templates/**/*.html', function(){
		templatesAdmin();
	})


});

gulp.task('build:templates:admin', templatesAdmin);

gulp.task('js:dependencies', function(){

	return gulp.src([
		'resources/assets/bower_components/jquery/dist/jquery.min.js',
		'resources/assets/bower_components/angular/angular.js',
		'resources/assets/bower_components/angular-route/angular-route.min.js',
		'resources/assets/bower_components/angular-cookies/angular-cookies.min.js',
		'resources/assets/bower_components/angular-messages/angular-messages.min.js',
		'resources/assets/bower_components/angular-strap/dist/angular-strap.min.js',
		'resources/assets/bower_components/angular-strap/dist/angular-strap.tpl.min.js',
		'resources/assets/bower_components/ng-file-upload/ng-file-upload.min.js',
		'resources/assets/bower_components/ui-select/dist/select.min.js',
		'resources/assets/bower_components/angular-sanitize/angular-sanitize.min.js',
		'resources/assets/bower_components/moment/moment.js',
		'resources/assets/bower_components/moment-timezone/moment-timezone.js',
		'resources/assets/bower_components/angular-animate/angular-animate.js',
		'resources/assets/bower_components/angular-slick-carousel/src/slick.js',
		'resources/assets/bower_components/slick-carousel/slick/slick.min.js',
		'resources/assets/bower_components/angularjs-slider/dist/rzslider.min.js',
		'resources/assets/bower_components/branch-sdk/dist/build.min.js'
		])
			.pipe(concat('dependencies.js'))
			.pipe(gulp.dest('./public/js/'));

});

gulp.task('flo', function() {

	flo(
			'public/',
			{
				port: 8888,
				host: config.app_url,
				glob: [
					'css/**/*.css',
					'js/**/*.js'
				]
			},
			function(filepath, callback) {
				gulp_util.log('Reloading \'' + gulp_util.colors.cyan(filepath) + '\' with flo...')
				callback({
					resourceURL: filepath,
					contents: fs.readFileSync('public/' + filepath),
					update: function(_window, _resourceURL) {
						//console.log("Resource " + _resourceURL + " has just been updated with new content");
					}
				});
			}
	)

});

gulp.task('build:fonts', function(){
		gulp.src([
			'resources/assets/bower_components/bootstrap-sass/assets/fonts/bootstrap/**/*',
			'resources/assets/bower_components/themify-icons/fonts/*',
		])
		.pipe(gulp.dest('public/fonts'));

	return gulp.src([
			'resources/assets/bower_components/bootstrap-sass/assets/fonts/bootstrap/**/*',
			'resources/assets/bower_components/themify-icons/fonts/*',
		])
		.pipe(gulp.dest('public/css/fonts'));

});


gulp.task('devel:app', ['js:app', 'sass:app']);
gulp.task('devel:admin', ['js:admin', 'templates:admin', 'sass:admin']);


gulp.task('build', function (done) {
	runSequence(
		'build:js:app',
		'build:js:compress:app',

		'build:templates:admin',
		'build:js:admin',
		'build:js:compress:admin',

		'js:dependencies',
		'build:js:compress:dependencies',

		'build:fonts',

		'css:admin:vendor',
		'build:css:admin:vendor',
		'build:sass:admin',
		'build:css:admin',

		'build:sass:app',
		'build:css:app'
	, function () {
			done();
		})
});

gulp.task('build:js:app', function(){
	var customOpts = {
		entries: ['resources/assets/app/js/App.js'],
		transform: [
			"babelify"
		],
		debug: true
	};
	var opts = assign({}, watchify.args, customOpts);
	var b = browserify(opts);

	return b.bundle()
		.on('error', gulp_util.log.bind(gulp_util, 'Browserify Error'))
		.pipe(source('App.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('public/js/app'));

})
gulp.task('build:js:admin', function(){
	var customOpts = {
		entries: ['resources/assets/app/js/admin/Admin.js'],
		transform: [
			"babelify"
		],
		debug: true
	};
	var opts = assign({}, watchify.args, customOpts);
	var b = browserify(opts);

	return b.bundle()
		.on('error', gulp_util.log.bind(gulp_util, 'Browserify Error'))
		.pipe(source('Admin.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('public/js/app'));

})


gulp.task('build:js:compress:admin', function(){
	return gulp.src(
		[
			'public/js/app/templates.admin.js',
			'public/js/app/Admin.js'
		]
		)
		.pipe(concat('admin.js'))
		.pipe(ngAnnotate())
		.pipe(uglify({mangle: false}))
		.pipe(gulp.dest('public/build/js'));

})
gulp.task('build:js:compress:app', function(){
	return gulp.src(
		[
			'public/js/app/App.js'
		]
		)
		.pipe(concat('app.js'))
		.pipe(ngAnnotate())
		.pipe(uglify())
		.pipe(gulp.dest('public/build/js'));

})

gulp.task('build:js:compress:dependencies', function(){

	return gulp.src([
			'public/js/dependencies.js'
		])
		//.pipe(uglify())
		.pipe(gulp.dest('public/build/js/'));


});

gulp.task('build:css:admin', function(){
	return gulp.src(
		'public/css/admin.css')
		.pipe(css_minify())
		.pipe(gulp.dest('public/build/css/'));
});


gulp.task('build:css:app', function(){
	return gulp.src('public/css/app.css')
		.pipe(css_minify())
		.pipe(gulp.dest('public/build/css/'));
});


gulp.task('build:css:admin:vendor', function(){
	return gulp.src('public/css/admin_vendor.css')
		.pipe(css_minify())
		.pipe(gulp.dest('public/build/css/'))
})

gulp.task('build:email', function() {
	return gulp.src('resources/email_templates/*.html')
		.pipe(inlineCss({
			applyStyleTags: true,
			applyLinkTags: true,
			removeStyleTags: true,
			removeLinkTags: true
		}))
		.pipe(gulp.dest('resources/email_templates/build/'));
});


gulp.task('server', shell.task([
	'php -S localhost:8080 -t public/'
]))
