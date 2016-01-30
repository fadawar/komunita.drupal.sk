var gulp = require('gulp');
var mainBowerFiles = require('main-bower-files');
var browserSync = require('browser-sync');

var p = require('gulp-load-plugins')({
    DEBUG: false,
    pattern: ['gulp-*', 'gulp.*'],
    scope: ['devDependencies'],
    replaceString: /^gulp(-|\.)/,
    camelize: true,
    lazy: true
});

var dir = {
    source: 'src',
    build: '',
    temp: 'temp'
};

var logErrorHandler = function (err) {
    console.log(err);
    p.notify.onError({
        title:    "Gulp",
        subtitle: "Failure!",
        message:  "Error: <%= error.message %>",
        sound:    "Beep"
    })(err);
    this.emit('end');
};

// spojenie a minifikacia JS
gulp.task('scripts', function() {
    return gulp.src(mainBowerFiles('**/*.js'))
        .pipe(p.plumber({ errorHandler: logErrorHandler }))
        .pipe(p.addSrc(dir.source + '/js/**/*.js'))
        .pipe(p.order(['*', 'main.js']))
        .pipe(p.concat('bundle.js'))
        .pipe(p.minify())
        .pipe(gulp.dest(dir.build));
});

// konverzia SASS, spojenie a minifikacia CSS
gulp.task('styles', function() {
    return gulp.src(mainBowerFiles('**/*.css'))
        .pipe(p.plumber({ errorHandler: logErrorHandler }))
        .pipe(p.addSrc(dir.source + '/scss/main.scss'))
        .pipe(p.order(['*.*', 'main.scss'], {base: '.'}))
        .pipe(p.concat('style.scss'))
        .pipe(p.sass())
        .pipe(p.autoprefixer({
            browsers: ['last 5 versions'],
            cascade: false
        }))
        .pipe(p.minifyCss())
        .pipe(gulp.dest(dir.build + 'css'))
        .pipe(browserSync.reload({stream: true}));
});

//// kopirovanie html
//gulp.task('markup', function() {
//    return gulp.src([dir.source + '/html/**/*.html'])
//        .pipe(p.plumber({ errorHandler: logErrorHandler }))
//        .pipe(p.htmlmin({collapseWhitespace: true, removeComments: true}))
//        .pipe(gulp.dest(dir.build));
//});

//// kopirovanie a minifikovanie obrazkov
//gulp.task('images', function() {
//    return gulp.src(dir.source + '/img/**/*')
//        .pipe(p.imagemin({progressive: true}))
//        .pipe(gulp.dest(dir.build + '/img'));
//});


//// Static Server from browser-sync
//gulp.task('browser-sync', function() {
//    browserSync({
//        server: './' + dir.build
//    });
//});


// tasky, ktore zabezpecia, ze sa browser obnovi (reload) az ked sa dokonci prvy task
gulp.task('scripts-watch', ['scripts'], browserSync.reload);
gulp.task('markup-watch', ['markup'], browserSync.reload);
gulp.task('images-watch', ['images'], browserSync.reload);


// predvolena uloha
gulp.task('default', ['styles', 'scripts'], function() {
    gulp.watch([dir.source + '/scss/**/*.scss'], ['styles']);
    gulp.watch([dir.source + '/js/**/*.js'], ['scripts-watch']);
    //gulp.watch([dir.source + '/html/**/*.html'], ['markup-watch']);
    //gulp.watch([dir.source + '/img/**/*.*'], ['images-watch']);
});