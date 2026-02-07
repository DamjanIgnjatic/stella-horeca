// Require necessary elements
const gulp = require('gulp'),
    sass = require('gulp-sass')(require('sass')),
    cleancss = require('gulp-clean-css'),
    uglify = require('gulp-uglify'),
    notify = require('gulp-notify'),
    plumber = require('gulp-plumber'),
    autoprefixer = require('gulp-autoprefixer'),
    lec = require('gulp-line-ending-corrector')
;

// SASS
// sass.compiler = require('node-sass');
gulp.task('sass', () => {
    return gulp.src('./assets/scss/*.scss')
        .pipe(sass().on('error', sass.logError))

        .pipe(plumber({
            errorHandler: notify.onError("Error: <%= error.messageOriginal %>")
        }))
        .pipe(autoprefixer(['last 8 versions']))
        .pipe(lec({ verbose: true, eolc: 'CRLF', encoding: 'utf8' }))
        .pipe(cleancss({ compatibility: 'ie8' }))
        .pipe(gulp.dest('./dist/css'));
});

// JS
gulp.task('es6', function() {
    return gulp.src('./assets/es6/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('./dist/js'));
});

// Block functions
// SASS
// sass.compiler = require('node-sass');
gulp.task('block-sass', () => {
    return gulp.src('./blocks/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(cleancss({ compatibility: 'ie8' }))
        .pipe(gulp.dest('./dist/css/blocks/'));
});

// JS
gulp.task('block-es6', function() {
    return gulp.src('./blocks/**/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('./dist/js/blocks/'));
});

// Libraries
// CSS
gulp.task('libraries-css', () => {
    return gulp.src('./assets/scss/libraries/*.scss')
        .pipe(cleancss({ compatibility: 'ie8' }))
        .pipe(gulp.dest('./dist/css/libraries/'));
});

// JS
gulp.task('libraries-js', function() {
    return gulp.src('./assets/es6/libraries/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('./dist/js/libraries/'));
});

// Watch
gulp.task('watch', function() {
    gulp.watch('./assets/scss/**/*.scss', gulp.series('sass'));
    gulp.watch(['./assets/es6/*.js'], gulp.series('es6'));

    // Blocks
    gulp.watch('./blocks/**/*.scss', gulp.series('block-sass'));
    gulp.watch('./blocks/**/*.js', gulp.series('block-es6'));
});

// Default task
gulp.task('default', gulp.series(
    'sass', 'es6', 'block-sass', 'block-es6'
));

// libraries
gulp.task('libraries', gulp.series(
    'libraries-js', 'libraries-css'
));
