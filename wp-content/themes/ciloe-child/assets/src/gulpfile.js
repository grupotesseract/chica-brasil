/* jshint node:true */

// Include project requirements.
var gulp = require( 'gulp' ),
	jshint = require( 'gulp-jshint' ),
	uglify = require( 'gulp-uglify' ),
	sass = require( 'gulp-sass' );

// Sets assets folders.
var dirs = {
	js: '../js',
	css: '../css',
	sass: '../css/sass',
	images: '../images',
	fonts: '../css/fonts'
};

gulp.task( 'sass', function () {
	// Compile all SCSS files.
	gulp.src( dirs.sass + '/*.scss' )
		.pipe( sass({
			compass: true,
			outputStyle: 'compressed'
		}) )
		.pipe( gulp.dest( dirs.css ) );
});

gulp.task( 'watch', function () {
	// Watch SCSS changes.
	gulp.watch( dirs.sass + '/*.scss', function () {
		gulp.run( 'sass' );
	});
});

gulp.task( 'default', function () {
	// Compile all assets.
	gulp.run( 'sass', 'watch' );
});
