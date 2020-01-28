const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const webpack = require('webpack');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const config = require('./config.js');

module.exports = {
  plugins: [
    new webpack.DefinePlugin({
      'process.env': { 'NODE_ENV': "'production'" }
    }),
    new VueLoaderPlugin(),
    new UglifyJsPlugin(),
    new BrowserSyncPlugin( {
      proxy: config.url,
      files: [
          {
            match: ['./assets/css/*/*.css', './assets/css/*.css', './assets/js/*.js', '**/*.php'],
            fn: (event, file) => {
              if (event == 'change') {
                const bs = require("browser-sync").get("bs-webpack-plugin");
                if (file.split('.').pop()=='js' || file.split('.').pop()=='php' ) {
                  bs.reload();
                } else {
                  bs.reload("*.css");
                }
              }
            }
          }
        ],
        reloadDelay: 0
      },
      {
        reload: false,
        name: 'bs-webpack-plugin'
      }
    ),
  ],
  entry: {
    main : "./assets/js/src/index",
    // home : "./src/components/templates/home/index",
    // single : "./src/components/templates/single/index",
    // page : "./src/components/templates/page/index",
    // default : "./src/components/templates/default/index"
  },
  output: {
      path: path.resolve(__dirname, "../assets/js/"),
      filename: '[name].bundle.js'
  },
  module: {
    rules: [{
        test: /\.(js|jsx)$/,
        exclude: /(node_modules)/,
        loader: 'babel-loader',
        query: {
          presets: [
            ['@babel/preset-env', {
              modules: false
            }],
          ],
          plugins: ["@babel/plugin-transform-spread"]
        },
      },
      {
        test: /\.vue$/,
        exclude: /(node_modules)/,
        loader: 'vue-loader',
        options: {
          loaders: {
            // you need to specify `i18n` loaders key with `vue-i18n-loader` (https://github.com/kazupon/vue-i18n-loader)
            i18n: '@kazupon/vue-i18n-loader'
          }
        }
      },
      {
        test: /\.scss$/,
        use: [
          'vue-style-loader',
          'css-loader',
          'sass-loader'
        ]
      }
    ],
  },
  devServer: {
    historyApiFallback: true,
    compress: true,
    port: 9000,
    https: config.url.indexOf('https') > -1 ? true : false,
    publicPath: config.fullPath,
    proxy: {
        '*': {
            'target': config.url,
            'secure': false
        },
        '/': {
            target: config.url,
            secure: false
        }
    },
},
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.common.js'
    }
  },
  watchOptions: {
    poll: 1000
  }
};