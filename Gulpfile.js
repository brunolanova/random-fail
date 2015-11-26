var gulp = require('gulp');
var compass = require('gulp-compass');
var jshint = require('gulp-jshint');

// Compila os arquivos em SASS
gulp.task('css-compass', function() {
	gulp.src('assets/scss/**/*.scss')
		.pipe(compass({
			css: 'assets/css',
			sass: 'assets/scss',
			image: 'img',
			font: 'assets/css/fonts',
			style: 'expanded',
			comments: false
		}));
});

gulp.task('lint', function() {
  return gulp.src('assets/js/**/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

// Inicia as tarefas
gulp.task('default', function() {
	
	gulp.run('css-compass');

	// Assiste a modificações dos arquivos SASS
	gulp.watch('assets/scss/**/*.scss', function(event) {
		gulp.src(event.path)
			.pipe(compass({
				css: 'assets/css',
				sass: 'assets/scss',
				image: 'img',
				font: 'assets/css/fonts',
				style: 'expanded',
				comments: false
			}));
	});
});