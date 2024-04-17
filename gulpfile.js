const {
    src,
    dest,
    watch,
    parallel
} = require('gulp');
const sass = require('gulp-sass');
const babel = require('gulp-babel');
const browsersync = require("browser-sync").create();
const notify = require('gulp-notify');
const autoprefixer = require('gulp-autoprefixer');
const plumber = require('gulp-plumber'); //for debug
const webpack = require('webpack-stream');
const newer = require('gulp-newer');
const tinypng = require('gulp-tinypng-compress');
const sourcemaps = require('gulp-sourcemaps');

// BrowserSync
function browserSync(done) {
    browsersync.init({
        // port: 3000
        // proxy: 'siteline.noooserver.com'
    });
    done();
}

// BrowserSync Reload
function browserSyncReload(done) {
    browsersync.reload();
    done();
}

// styles
function style() {
    return src(['./assets/scss/*.scss'])
        .pipe(plumber({
            errorHandler: function(err) {
                notify.onError({
                    title: "Gulp error in " + err.plugin,
                    message: err.toString()
                })(err);
            }
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
        .pipe(dest("./dist/css"))
        .pipe(browsersync.stream());
}

// images
function images() {
    return src('./assets/images/**/*.{png,jpg,jpeg,avif}')
        .pipe(newer('./dist/img'))
        // .pipe(tinypng({
        //     key: 'LoNi0JXMlZmcc7Tl8rVzmQenFAmkEjIH',
        //     sigFile: 'images/.tinypng-sigs',
        //     summarise: true,
        //     log: true
        // }))
        .pipe(dest('./dist/img'))
}


//media
function media() {
    return src('./assets/video/*.{gif,mp4,ogg,webp}')
        .pipe(newer('./dist/video'))
        .pipe(dest('./dist/video'))
}

//svg
function svg() {
    return src('./assets/images/vectors/*.svg')
        .pipe(newer('./dist/img'))
        .pipe(dest('./dist/img'))
}



// js
function js() {
    return src(['./assets/js/**.js'])
        .pipe(plumber({
            errorHandler: function(err) {
                notify.onError({
                    title: "Gulp error in " + err.plugin,
                    message: err.toString()
                })(err);
            }
        }))
        .pipe(webpack({
            watch: true,
            mode: "production",
            entry: {
                index: './assets/js/main.js',
            },
            output: {
                filename: "[name].min.js"
            },
            optimization: {
                splitChunks: {
                    chunks: 'all'
                }
            },
            devtool: "source-map",
            performance: { hints: false },
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
                    },
                }, ],
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
    watch("./assets/scss/**/*.scss", style);
    watch("./assets/images/**/*.{png,jpg,jpeg}", images);
    watch("./assets/video/**/*.{gif,mp4,ogg,webp}", media);
    watch("./assets/images/vectors/*.svg", svg);
    watch("./assets/js/**/*.js", js);
    src('./assets/js/**/*.js')
        .pipe(notify('Gulp is watching'));
}

exports.js = js;
exports.style = style;
exports.images = images;
exports.media = media;
exports.svg = svg;
exports.default = parallel(style, images, svg, media, js, watchFiles, browserSync);
exports.watch = watchFiles;
