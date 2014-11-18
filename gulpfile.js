/* jshint node: true */
'use strict';

var del         = require('del');
var g           = require('gulp-load-plugins')();
var gulp        = require('gulp');


// === Develpement ===

gulp.task('clean', function(cb) {
  return del([
      '.sass-cache',
      'log/*',
      'temp/btfj.dat',
      'temp/cache',
      'temp/sessions/*',
      'www/webtemp/*',
    ], cb);
});

gulp.task('jshint', function() {
  return gulp.src([
      'www/js/**/*.js',
    ])
    .pipe(g.jshint())
    .pipe(g.jshint.reporter('jshint-stylish'));
});

gulp.task('watch', function() {
  g.livereload.listen();

  // compile handlers
  gulp.watch('www/js/**/*.js', ['jshint']);

  // livereload handlers
  gulp.watch([
    'app/**/*.latte',
    'app/config/*.neon',
    'app/**/*.php',
    'styles/**/*.{less,sass,scss}',
    'www/js/**/*.js',
  ]).on('change', g.livereload.changed);
});


// === Build ===

gulp.task('minifyImages', function() {
  return gulp.src('www/images/**/*.{gif,jpg,jpeg,png,webp}')
    .pipe(g.imagemin({
      optimizationLevel: 7,
      progressive: true,
      interlaced: true,
    }))
    .pipe(gulp.dest('www/images'));
});


// === Main tasks definitions ===

gulp.task('default', ['jshint', 'watch']);
