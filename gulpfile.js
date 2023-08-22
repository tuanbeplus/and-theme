var gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    order = require('gulp-order'),
    rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin'),
    cache = require('gulp-cache');
var minifycss = require('gulp-minify-css');
var sass = require('gulp-sass');
var browserSync = require('browser-sync');

gulp.task('browser-sync', function() {
	var files = [
    './style.css',
    './*.php'
    ];
  browserSync.init(files, {
    //browsersync with a php server
    proxy: "conference-2021.acps.local/",
    notify: false
    });
});

gulp.task('bs-reload', function () {
  browserSync.reload();
});

gulp.task('images', function(){
  gulp.src('src/imgs/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('assets/imgs/'));
});

gulp.task('styles', function(){
  gulp.src([
	    'src/scss/**/**/*.scss',
  		'src/scss/**/*.scss'
  	])
    .pipe(plumber({
      errorHandler: function (error) {
        console.log(error.message);
        this.emit('end');
    }}))
    .pipe(sass())
    .pipe(gulp.dest('assets/css/'))
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .pipe(gulp.dest('assets/css/'))
    .pipe(browserSync.reload({stream:true}))
});

gulp.task('scripts', function(){
  return gulp.src([
  		'src/js/vendors/jquery.min.js',
  		'src/js/vendors/vAlign.js',
  		'src/js/vendors/equalHeight.js',
        'src/js/vendors/swiperjs/dist/js/swiper.min.js', 
        'src/js/vendors/selectric/jquery.selectric.js', 
        'src/js/vendors/selectric/jquery.selectric.placeholder.js', 
        'src/js/vendors/selectric/jquery.selectric.addNew.js', 
        'src/js/vendors/jquery.sticky-sidebar.min.js', 
        'src/js/vendors/popup/jquery.magnific-popup.js', 
        'src/js/vendors/dropzone.js',  
  		'src/js/app/*.js' 
  	])
  	.pipe(plumber({
      errorHandler: function (error) {
        console.log(error.message);
        this.emit('end');
    }}))
    .pipe(concat('main.js'))
    .pipe(gulp.dest('assets/js/')) 
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest('assets/js/'))
    .pipe(browserSync.reload({stream:true}))
});

gulp.task('default', ['browser-sync','images','styles','scripts'], function(){
  gulp.watch("src/scss/**/*.scss", ['styles','images']);
  gulp.watch("src/js/**/*.js", ['scripts']);
  gulp.watch("*.html", ['bs-reload']);
});