import gulp    from 'gulp'
import Browser from 'browser-sync'
import webpack from 'webpack'
import webpackDevMiddleware from 'webpack-dev-middleware'
import webpackHotMiddleware from 'webpack-hot-middleware'

import { config as webpackConfig } from './webpack'

const browser = Browser.create()
const bundler = webpack(webpackConfig)

function server() {
    let config = {
        server: './',
        index: 'home.html',
        injectChanges: true,
        baseDir: "../",
        open: false,
        files: [
            './css/*.css'
        ],
        middleware: [
            webpackDevMiddleware(bundler, { 
                stats: {colors: true}
             }),
            webpackHotMiddleware(bundler)
        ],
    }

    browser.init(config)

    gulp.watch('assets/js/src/*.js').on('change', () => browser.reload())
}

module.exports = { browser, server }
