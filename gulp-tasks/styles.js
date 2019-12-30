import gulp from 'gulp';
import sassSCSS from 'gulp-sass';
import notify from 'gulp-notify';
import autoprefixer from 'gulp-autoprefixer';


export function styles() {
  return gulp.src('assets/scss/main.scss')
    .pipe(sassSCSS({
      errLogToConsole: false,
      outputStyle: 'compressed'
    }))
    .on('error', function (err) {
      notify().write(err);
      this.emit('end');
    })
    .pipe(autoprefixer())
    .pipe(gulp.dest('assets/css'))
}

module.exports = {
  styles
}