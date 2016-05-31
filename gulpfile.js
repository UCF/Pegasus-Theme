var gulp = require('gulp'),
    configLocal = require('./gulp-config.json'),
    merge = require('merge'),
    sass = require('gulp-sass'),
    minifyCss = require('gulp-minify-css'),
    bless = require('gulp-bless'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    jshint = require('gulp-jshint'),
    jshintStylish = require('jshint-stylish'),
    scsslint = require('gulp-scss-lint'),
    autoprefixer = require('gulp-autoprefixer'),
    browserSync = require('browser-sync').create();

var configDefault = {
      fontPath: './static/fonts',
      componentsPath: './static/components'
    },
    config = merge(configDefault, configLocal);

config.versionsPath = './versions/' + config.version;


// Lint all scss files (by version)
gulp.task('scss-lint', function() {
  gulp.src(config.versionsPath + '/**/*.scss')
    .pipe(scsslint());
});


// Compile + bless primary scss files (by version)
gulp.task('css-main', function() {
  switch (config.version) {
    case 'v3':
    case 'v4':
      gulp.src(config.versionsPath + '/static/scss/style.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(rename('style.min.css'))
        .pipe(autoprefixer({
          browsers: ['last 2 versions', 'ie >= 8'],
          cascade: false
        }))
        .pipe(bless())
        .pipe(gulp.dest(config.versionsPath + '/static/css/'))
        .pipe(browserSync.stream());
      break;
    default:
      break;
  }
});


// All css-related tasks
gulp.task('css', ['scss-lint', 'css-main']);


// Run jshint on all js files in the version's js path
// (except already minified files) by version.
gulp.task('js-lint', function() {
  gulp.src([config.versionsPath + '/**/*.js', '!' + config.versionsPath + '/**/*.min.js'])
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'))
    .pipe(jshint.reporter('fail'));
});


// Concat and uglify primary js files (by version)
gulp.task('js-main', function() {
  switch (config.version) {
    case 'v3':
    case 'v4':
      var minified = [
        config.componentsPath + '/bootstrap-sass-3.3.4/assets/javascripts/bootstrap.js',
        config.versionsPath + '/static/js/webcom-base.js',
        config.versionsPath + '/static/js/generic-base.js',
        config.versionsPath + '/static/js/script.js'
      ];

      gulp.src(minified)
        .pipe(concat('script.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(config.versionsPath + '/static/js/'))
        .pipe(browserSync.stream());

      break;
    default:
      break;
  }
});


// All js-related tasks
gulp.task('js', ['js-lint', 'js-main']);


// Rerun tasks when files change
gulp.task('watch', function() {
  if (config.sync) {
    browserSync.init({
        proxy: {
          target: config.syncTarget
        }
    });
  }

  gulp.watch(config.versionsPath + '/**/*.scss', ['css']).on('change', browserSync.reload);
  gulp.watch(config.versionsPath + '/**/*.js', ['js']).on('change', browserSync.reload);
});


// Default task
gulp.task('default', ['css', 'js']);
