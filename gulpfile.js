/* jshint node: true */
'use strict';

var del         = require('del');
var fs          = require('fs');
var g           = require('gulp-load-plugins')();
var gulp        = require('gulp');
var path        = require('path');
var runSequence = require('run-sequence');


// === Develpement ===

gulp.task('clean', function(cb) {
  return del([
      '.sass-cache',
      'build/*',
      'log/*',
      'temp/btfj.dat',
      'temp/cache',
      'temp/sessions/*',
      'www/css/*',
    ], cb);
});

gulp.task('compass', function() {
  return gulp.src('www/sass/**/*.{sass,scss}')
    .pipe(g.compass({
      css: 'www/css',
      sass: 'www/sass',
      style: 'nested',
      comments: false,
    })).on('error', function(err) { console.warn(err.message) })
    .pipe(g.autoprefixer())
    .pipe(gulp.dest('www/css'));
});

gulp.task('jshint', function() {
  return gulp.src([
      'www/js/**/*.js',
      '!www/js/netteForms.js',
    ])
    .pipe(g.jshint())
    .pipe(g.jshint.reporter('jshint-stylish'));
});

gulp.task('watch', function() {
  g.livereload.listen();

  // compile handlers
  gulp.watch('www/sass/**/*.{sass,scss}', ['compass']);
  gulp.watch('www/js/**/*.js', ['jshint']);

  // livereload handlers
  gulp.watch([
    'app/**/*.latte',
    'app/config/*.neon',
    'app/**/*.php',
    'www/css/**/*.css',
    'www/js/**/*.js',
  ]).on('change', g.livereload.changed);
});


// === Build ===

gulp.task('buildCompass', function() {
  return gulp.src('www/sass/**/*.{sass,scss}')
    .pipe(g.compass({
      css: 'build/www/css',
      sass: 'www/sass',
      style: 'compressed',
      comments: false,
    })).on('error', function(err) { console.warn(err.message) })
    .pipe(g.autoprefixer())
    .pipe(g.csso())
    .pipe(gulp.dest('build/www/css'));
});

gulp.task('buildImages', function() {
  return gulp.src('www/images/**/*.{gif,jpg,jpeg,png,webp}')
    .pipe(g.imagemin({
      optimizationLevel: 7,
      progressive: true,
      interlaced: true,
    }))
    .pipe(gulp.dest('build/www/images'));
});

gulp.task('buildWrapper', function() {
  var assets = g.useref.assets({
    searchPath: ['build/www', 'www']
  });
  var app = g.filter('js/app.js');
  var wrapper = g.filter('@wrapper.latte');

  return gulp.src('app/templates/@wrapper.latte')
    .pipe(g.replace(new RegExp('vendor\\/([\\w\\-\\.\\/]+).((js|css))', 'g'), 'vendor/$1.min.$2'))
    .pipe(g.replace('{$basePath}/', ''))
    .pipe(assets)   // concatenate assets defined in HTML build blocks

    .pipe(g.rev())
    .pipe(assets.restore())
    .pipe(g.useref())
    .pipe(wrapper)
    .pipe(g.rename({
      extname: '.html'
    }))
    .pipe(wrapper.restore())
    .pipe(g.revReplace())
    .pipe(wrapper)
    .pipe(g.rename({
      extname: '.latte'
    }))
    .pipe(g.replace(/(href|src)="(\w)/g, '$1="{$baseUrl}/$2'))
    .pipe(wrapper.restore())
    .pipe(gulp.dest('build/app/templates'));
});

gulp.task('copyApp', function() {
  return gulp.src([
    'app/**/*',
    '!app/templates/@layout.latte',
    'bin/**/*',
    'log/.*',
    'temp/**/.*',
    'vendor/**/*',
    'www/*.*',
    'www/.*',
  ], {base: './'})
    .pipe(gulp.dest('build'));
});

gulp.task('copyRevedAssets', function() {
  return gulp.src([
      'build/app/templates/css/*',
      'build/app/templates/js/*',
    ], {base: './build/app/templates'})
    .pipe(gulp.dest('build/www'));
});

gulp.task('cleanBuild', function(cb) {
  return del([
      'build/app/templates/css',
      'build/app/templates/js',
    ], cb);
});

gulp.task('jsMaps', function() {
  return gulp.src([
      'www/vendor/jquery/dist/jquery.min.map',
    ])
    .pipe(gulp.dest('build/www/js'));
});


// === Main tasks definitions ===
//

gulp.task('compile', [
  'compass',
  'jshint',
]);

gulp.task('build', function() {
  return runSequence(
    'clean',
    ['buildCompass', 'buildImages', 'copyApp', 'jsMaps'],
    'buildWrapper',
    'copyRevedAssets',
    'cleanBuild'
  );
});

gulp.task('default', function() {
  return runSequence(
    ['compile', 'watch']
  );
});
