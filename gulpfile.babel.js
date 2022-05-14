'use strict';

import gulp from 'gulp';
import dartSass from 'sass';
import gulpSass from 'gulp-sass';
import babel from 'gulp-babel';
import babelregister from '@babel/register';
import uglify from 'gulp-uglify';
import concat from 'gulp-concat';
import imagemin from 'gulp-image';
import fontmin from 'gulp-fontmin';
import plumber from 'gulp-plumber';
const sass = gulpSass(dartSass);

const THEME_NAME = "gate-child";



// Gestion du wp-config.php
gulp.task('wp-config', () =>
  gulp.src(['./workspace/wp-config.php'])
  .pipe(gulp.dest('./final'))
);


// Dossier Template
gulp.task('template', () =>
  gulp.src(['./workspace/themes/'+ THEME_NAME +'/template/**/*'])
  .pipe(gulp.dest('./final/wp-content/themes/'+ THEME_NAME +''))
);


// SCSS Frontend
gulp.task('scss', () =>
  gulp.src(['./workspace/themes/'+ THEME_NAME +'/scss/**/*.scss'])
  .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
  .pipe(concat('main.min.css'))
  .pipe(gulp.dest('./final/wp-content/themes/'+ THEME_NAME +'/assets/css'))
);


// JS Frontend
gulp.task('js', () =>
  gulp.src(['./workspace/themes/'+ THEME_NAME +'/js/**/*.js'])
  .pipe(babel())
  .pipe(uglify())
  .pipe(concat('main.min.js'))
  .pipe(gulp.dest('./final/wp-content/themes/'+ THEME_NAME +'/assets/js'))
);



// Optimisation des images
gulp.task('images', () =>
  gulp.src(['./workspace/themes/'+ THEME_NAME +'/images/**/*'])
  .pipe(imagemin())
  .pipe(gulp.dest('./final/wp-content/themes/'+ THEME_NAME +'/assets/images'))
);


// Optimisation des fonts
gulp.task('fonts', () =>
  gulp.src(['./workspace/themes/'+ THEME_NAME +'/fonts/**/*.ttf'])
  .pipe(fontmin())
  .pipe(gulp.dest('./final/wp-content/themes/'+ THEME_NAME +'/assets/fonts'))
);


gulp.task('watch', function () {

  gulp.watch(['./workspace/wp-config.php'], gulp.series('wp-config'))

  gulp.watch(['./workspace/themes/'+ THEME_NAME +'/template/**/*'], gulp.series('template'))

  gulp.watch(['./workspace/themes/'+ THEME_NAME +'/scss/**/*.scss'], gulp.series('scss'))
  
  gulp.watch(['./workspace/themes/'+ THEME_NAME +'/js/**/*.js'], gulp.series('js'))

});



// Watch task
gulp.task('default', gulp.series(
  'wp-config',
  'template',
  'scss',
  'js',
  'watch'
));


gulp.task('prod', gulp.series(
  'wp-config',
  'template',
  'scss',
  'js',
  'images',
  'fonts'
));