import gulp  from 'gulp';
import twig  from 'gulp-twig';
import path from 'path';
import fs from 'fs';
import data from 'gulp-data';
import rename from 'gulp-rename';
import notify from 'gulp-notify';

export function templates() {
    return gulp.src(['views/*.twig'])
        .pipe(data(function () {
            return JSON.parse(fs.readFileSync('config/global.json'));
        }))
        .on('error', function (err) {
            notify().write(err);
            this.emit('end');
        })
        .pipe(twig({
            includes: [
                'views/partials/*.twig',
            ],
            getIncludeId: function (filePath) {
                return path.relative('views', filePath);
            }
        }))
        .on('error', function (err) {
            notify().write(err);
            this.emit('end');
        })
        .pipe(rename(function (path) {
            path.extname = ".html"
        }))
        .pipe(gulp.dest('./'))
}

module.exports = {
    templates
}