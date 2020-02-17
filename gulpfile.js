'use strict';

var gulp = require('gulp');
var del = require('del');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var minifyCSS = require('gulp-minify-css');
var rigger = require('gulp-rigger');
var autoprefixer = require('gulp-autoprefixer');


var browserSync = require('browser-sync');
var reload      = browserSync.reload;
// Static server
gulp.task('browser-sync', function(done) {
    browserSync.init({
        server: {
            baseDir: "./"
        },
        port: 8181,
        open: true,
        notify: false
    });
    done();
});


gulp.task('sass', function (done) {
    gulp.src(['sass/import.scss'])
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer("last 50 versions", "ie >= 9"))
        .pipe(concat('style.min.css'))
        .pipe(minifyCSS({
            keepBreaks: false
        }))
        .pipe(gulp.dest('production'));
    done();
});

gulp.task('html', function (done) {
    gulp.src(['html/*.html'])
        .pipe(rigger())
        .pipe(gulp.dest('./'))
        .pipe(reload({stream:true}));
    done();
});

gulp.task('js', function (done) {
    gulp.src(['js/libs/*.js', 'js/*.js'])
        .pipe(concat('scripts.min.js'))
        .pipe(gulp.dest('production'))
        .pipe(reload({stream:true}));
    done();
});


gulp.task('clean', function() {
    return del(['production']);
    //return gulp.src(['production'], {read: false, allowEmpty: true}).pipe(clean());
});
gulp.task('default', gulp.series('clean','sass', 'js', 'html', 'browser-sync', (done) => {
    // Смотрим за scss
    gulp.watch(
        ['sass/*.sass', 'sass/*/*.sass', 'sass/libs/*.*'],
        gulp.series('sass')
    );
    // Смотрим за js
    gulp.watch(['js/libs/*.js', 'js/*.js', 'js/*/*.js'],
        gulp.series('js')
    );
    // Сборка html
    gulp.watch(['html/*.html','html/*/*.html'], gulp.series('html'));
    done();
}));