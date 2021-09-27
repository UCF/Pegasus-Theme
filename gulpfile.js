const fs           = require('fs');
const browserSync  = require('browser-sync').create();
const gulp         = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS     = require('gulp-clean-css');
const include      = require('gulp-include');
const eslint       = require('gulp-eslint');
const babel        = require('gulp-babel');
const rename       = require('gulp-rename');
const sass         = require('gulp-sass')(require('sass'));
const sassLint     = require('gulp-sass-lint');
const uglify       = require('gulp-uglify');
const merge        = require('merge');
const bless        = require('gulp-bless');
const gulpIf       = require('gulp-if');


let config = {
  devPath: './dev',
  fontPath: './static/fonts',
  componentsPath: './static/components',
  packagesPath: './node_modules',
  sync: false,
  target: 'http://localhost/',
  version: 'v5',
  versionPath: ''
};

/* eslint-disable no-sync */
if (fs.existsSync('./gulp-config.json')) {
  const overrides = JSON.parse(fs.readFileSync('./gulp-config.json'));
  config = merge(config, overrides);
}
/* eslint-enable no-sync */

config.versionPath = `./versions/${config.version}`;


//
// Helper functions
//

// Base SCSS linting function
function lintSCSS(src) {
  return gulp.src(src)
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError());
}

// Base SCSS compile function
function buildCSS(src, dest, renameMinified = true) {
  dest = dest || `${config.versionPath}/static/css`;

  let versionBrowsersList = [];
  let versionCleanCSSOptions = {};
  let doBless = false;

  switch (config.version) {
    case 'v3':
    case 'v4':
      versionBrowsersList = ['last 2 versions', 'ie >= 8'];
      versionCleanCSSOptions = {
        compatibility: 'ie8'
      };
      doBless = true;
      break;
    case 'v5':
      versionBrowsersList = ['last 2 versions'];
      break;
    default:
      break;
  }

  return gulp.src(src)
    .pipe(sass({
      includePaths: [`${config.versionPath}/static/scss`, config.componentsPath, config.packagesPath]
    })
      .on('error', sass.logError))
    .pipe(cleanCSS(versionCleanCSSOptions))
    .pipe(autoprefixer({
      overrideBrowserslist: versionBrowsersList,
      cascade: false
    }))
    .pipe(gulpIf(renameMinified, rename({
      extname: '.min.css'
    })))
    .pipe(gulpIf(doBless, bless()))
    .pipe(gulp.dest(dest));
}

// Base JS linting function (with eslint).
// NOTE: normally we'd tell eslint to fix problems in place, but
// for whatever reason, eslint-if-fixed refuses to send along fixed,
// linted JS to the correct dest directory in this project, so
// it's been removed here
function lintJS(src) {
  return gulp.src(src)
    .pipe(eslint())
    .pipe(eslint.format());
}

// Base JS compile function
function buildJS(src, dest) {
  dest = dest || `${config.versionPath}/static/js`;

  return gulp.src(src)
    .pipe(include({
      includePaths: [config.packagesPath, config.componentsPath, `${config.versionPath}/static/js`]
    }))
    .on('error', console.log) // eslint-disable-line no-console
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename({
      extname: '.min.js'
    }))
    .pipe(gulp.dest(dest));
}

// BrowserSync reload function
function serverReload(done) {
  if (config.sync) {
    browserSync.reload();
  }
  done();
}

// BrowserSync serve function
function serverServe(done) {
  if (config.sync) {
    browserSync.init({
      proxy: {
        target: config.target
      }
    });
  }
  done();
}


//
// CSS
//

// Lint all current version scss files
gulp.task('scss-lint-version', () => {
  return lintSCSS(`${config.versionPath}/**/*.scss`);
});

// Compile current version stylesheet
gulp.task('scss-build-version', () => {
  return buildCSS(`${config.versionPath}/static/scss/style.scss`);
});

// All theme css-related tasks
gulp.task('css', gulp.series('scss-lint-version', 'scss-build-version'));


//
// JavaScript
//

// Run eslint on js files in src.jsPath
gulp.task('es-lint-version', () => {
  return lintJS([
    `${config.versionPath}/**/*.js`,
    `!${config.versionPath}/**/*.min.js`
  ]);
});

// Concat and uglify js files through babel
gulp.task('js-build-version', () => {
  return buildJS(`${config.versionPath}/static/js/script.js`);
});

// All js-related tasks
gulp.task('js', gulp.series('es-lint-version', 'js-build-version'));


//
// Rerun tasks when files change
//
gulp.task('watch', (done) => {
  serverServe(done);

  gulp.watch(
    `${config.versionPath}/**/*.scss`,
    gulp.series('css', serverReload)
  );

  gulp.watch(
    [
      `${config.versionPath}/**/*.js`,
      `!${config.versionPath}/**/*.min.js`
    ],
    gulp.series('js', serverReload)
  );

  gulp.watch(
    [
      './**/*.php',
      `${config.devPath}/**/*.html`,
      `${config.devPath}/**/*.js`,
      `!${config.devPath}/**/*.min.js`
    ],
    gulp.series(serverReload)
  );

  // NOTE: add event handlers to gulp.watch() directly here
  // in order to have access to the modified file's filename
  // passed along on change:
  const devScssWatcher = gulp.watch(`${config.devPath}/**/*.scss`);
  devScssWatcher.on('change', (src) => {
    return lintSCSS(src);
  });
  devScssWatcher.on('change', (src) => {
    const dest = src.slice(0, (src.lastIndexOf('/') > -1 ? src.lastIndexOf('/') : src.lastIndexOf('\\')) + 1);
    return buildCSS(src, dest, false);
  });
});


//
// Default task
//
gulp.task('default', gulp.series('css', 'js'));
