const { src, dest, watch, parallel, series } = require('gulp');
const sass = require('gulp-sass');
const babel = require('gulp-babel');
const browsersync = require('browser-sync').create();
const notify = require('gulp-notify');
const autoprefixer = require('gulp-autoprefixer');
const plumber = require('gulp-plumber');
const webpack = require('webpack-stream');
const newer = require('gulp-newer');
const tinypng = require('gulp-tinypng-compress');
const sourcemaps = require('gulp-sourcemaps');
const gulpAvif = require('gulp-avif');

// BrowserSync
function browserSync(done) {
    browsersync.init({
        server: {
            baseDir: './dist'
        }
    });
    done();
}

// BrowserSync Reload
function browserSyncReload(done) {
    browsersync.reload();
    done();
}

// Styles
function style() {
    return src('./assets/scss/*.scss')
        .pipe(plumber({
            errorHandler: notify.onError({
                title: "Gulp error in <%= error.plugin %>",
                message: "<%= error.message %>"
            })
        }))
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed',
            sourceComments: 'map',
            sourceMap: 'sass',
            outputStyle: 'nested'
        }).on('error', sass.logError))
        .pipe(autoprefixer('last 2 versions'))
        .pipe(sourcemaps.write('./'))
        .pipe(dest('./dist/css'))
        .pipe(browsersync.stream());
}

// Images
function images() {
    return src('./assets/images/**/*.{png,jpg,jpeg}')
        .pipe(newer('./dist/img'))
        .pipe(gulpAvif())
        .pipe(dest('./dist/img'));
}

// Media
function media() {
    return src('./assets/video/*.{gif,mp4,ogg,webp}')
        .pipe(newer('./dist/video'))
        .pipe(dest('./dist/video'));
}

// SVG
function svg() {
    return src('./assets/images/vectors/*.svg')
        .pipe(newer('./dist/img'))
        .pipe(dest('./dist/img'));
}

// JavaScript
function js() {
    return src('./assets/js/**/*.js')
        .pipe(plumber({
            errorHandler: notify.onError({
                title: "Gulp error in <%= error.plugin %>",
                message: "<%= error.message %>"
            })
        }))
        .pipe(webpack({
            watch: true,
            mode: 'development', // Ensure mode is set to development
            entry: {
                index: './assets/js/main.js',
            },
            output: {
                filename: '[name].js'
            },
            optimization: {
                splitChunks: {
                    chunks: 'all'
                },
                minimize: false // Ensure minification is turned off
            },
            devtool: 'source-map',
            performance: { hints: false },
            module: {
                rules: [{
                    test: /\.(js|jsx)$/,
                    exclude: /(node_modules)/,
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            ['@babel/preset-env', {
                                modules: false
                            }],
                        ],
                    },
                }],
            },
            resolve: {
                modules: ['node_modules'],
            }
        }))
        .pipe(dest('./dist/js'))
        .pipe(browsersync.stream());
}

// Watch files
function watchFiles() {
    watch('./assets/scss/**/*.scss', style);
    watch('./assets/images/**/*.{png,jpg,jpeg}', images);
    watch('./assets/video/**/*.{gif,mp4,ogg,webp}', media);
    watch('./assets/images/vectors/*.svg', svg);
    watch('./assets/js/**/*.js', js);
    watch('./dist/**/*.html', browserSyncReload);
}

// Export tasks
exports.js = js;
exports.style = style;
exports.images = images;
exports.media = media;
exports.svg = svg;
exports.default = series(parallel(style, images, svg, media, js), parallel(watchFiles, browserSync));
exports.watch = watchFiles;
