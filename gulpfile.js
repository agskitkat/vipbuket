'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var clean = require('gulp-clean');
var concat = require('gulp-concat');
var minifyCSS = require('gulp-minify-css');
var rigger = require('gulp-rigger');
var autoprefixer = require('gulp-autoprefixer');


var browserSync = require('browser-sync');
var reload      = browserSync.reload;
// Static server
gulp.task('browser-sync', function() {
    browserSync.init({
        server: {
            baseDir: "./"
        },
        port: 8181,
        open: true,
        notify: false
    });
});



gulp.task('sass', function () {
    gulp.src(['sass/import.scss'])
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer("last 50 versions", "ie >= 9"))
        .pipe(concat('style.min.css'))
        .pipe(minifyCSS({
            keepBreaks: false
        }))
        .pipe(gulp.dest('production'));
});
gulp.task('sass:watch', function () {
    gulp.watch('sass/*.scss', ['sass']);
});




gulp.task('js', function () {
    gulp.src(['js/libs/*.js', 'js/*.js'])
        .pipe(uglify().on('error', function(e){
            console.log(e);
        }))
        .pipe(concat('scripts.min.js'))
        .pipe(gulp.dest('production'));
});
gulp.task('js:watch', function () {
    gulp.watch(['js/*.js', 'js/libs/*.js'], ['js']);
});



gulp.task('html', function(){
    gulp.src('html/*.html')
        .pipe(rigger())
        .pipe(gulp.dest(''))
        .pipe(reload({stream:true}));
});
gulp.task('html:watch', function () {
    gulp.watch(['html/*.html','html/*/*.html' ], ['html']);
});


gulp.task('clean', function() {
    return gulp.src(['production'], {read: false})
        .pipe(clean());
});

gulp.task('default', ['clean', 'browser-sync'], function() {
    gulp.start('sass:watch', 'html:watch', 'js:watch', 'sass', 'js');
});