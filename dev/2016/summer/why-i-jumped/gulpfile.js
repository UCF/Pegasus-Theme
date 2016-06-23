var gulp = require('gulp'),
    sass = require('gulp-sass'),
    minifyCss = require('gulp-minify-css'),
    scsslint = require('gulp-scss-lint'),
    autoprefixer = require('gulp-autoprefixer'),
    browserSync = require('browser-sync').create();

var config = {
    path: '.',
    sync: true,
    syncTarget: 'http://localhost/pegasus/'
};

console.log(config.path);

gulp.task('js', function () {
});

gulp.task('css', function() {
  gulp.src(config.path + '/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(minifyCss({compatibility: 'ie8'}))
    .pipe(autoprefixer({
      browsers: ['last 2 versions', 'ie >= 8'],
      cascade: false
    }))
    .pipe(gulp.dest(config.path))
    .pipe(browserSync.stream());
});

gulp.task('watch', ['css'], function() {
  if (config.sync) {
    browserSync.init({
        proxy: {
          target: config.syncTarget
        }
    });
  }

  gulp.watch(config.path + '/*.scss', ['css']);
  gulp.watch(config.path + '/*.js', ['js']).on("change", browserSync.reload);
  gulp.watch(config.path + '/*.html').on("change", browserSync.reload);
});


gulp.task('default', ['css', 'js']);